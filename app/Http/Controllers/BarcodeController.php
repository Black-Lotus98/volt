<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Records;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BarcodeController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        return Inertia::render('Barcode/Index', [
            'clients' => $clients,
        ]);
    }

    private function updateClientTotalBarcodes(Client $client, int $endNumber)
    {
        $client->total_barcodes = $endNumber;
        $client->save();
    }

    public function generateBarcodes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'count' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $client = Client::find($request->client_id);
        $count = $request->count;
        $startNumber = $client->total_barcodes + 1;
        $endNumber = $startNumber + $count - 1;

        $this->updateClientTotalBarcodes($client, $endNumber);

        try {
            DB::beginTransaction();

            $record = $this->createRecord($client);

            $pdfLink = $this->generateAndSavePDF($client, $startNumber, $endNumber);

            $record->update(['record_link' => $pdfLink]);

            DB::commit();

            return redirect()->route('barcode.index')
                ->with('success', 'Barcodes generated successfully.')
                ->with('pdf_link', $pdfLink);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('barcode.index')
                ->with('error', 'Error generating barcodes: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function createRecord(Client $client)
    {
        return Records::create([
            'client_id' => $client->id,
            'user_id' => auth()->id(),
            'record_link' => '',
        ]);
    }

    private function generateAndSavePDF(Client $client, int $startNumber, int $endNumber): string
    {
        $pdf = new \FPDF('L', 'cm', [4, 2.5]);
        $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();

        for ($i = $startNumber; $i <= $endNumber; $i++) {
            $barcodeValue = $client->reference_number . str_pad($i, 5, '0', STR_PAD_LEFT);

            $pdf->AddPage();

            // Generate barcode image
            $barcodeImage = $barcodeGenerator->getBarcode($barcodeValue, $barcodeGenerator::TYPE_CODE_128);

            // Save barcode image to temporary file
            $tempBarcodePath = tempnam(sys_get_temp_dir(), 'barcode') . '.png';
            file_put_contents($tempBarcodePath, $barcodeImage);

            // Add barcode image to PDF
            $pdf->Image($tempBarcodePath, 0.5, 0.7, 3, 1.3);

            // Delete temporary file
            unlink($tempBarcodePath);

            // Set the font for the barcode value
            $pdf->SetFont('arial', '', 6);

            // Position the barcode value below the barcode image
            $pdf->SetXY(0.5, 0.44); // Adjust the Y position to fit below the barcode
            $pdf->Cell(3, 0.05, $barcodeValue, 0, 1, 'C');
        }

        // Output PDF to a string
        $pdfContent = $pdf->Output('', 'S');

        // Save PDF to storage
        $filename = 'barcodes/' . Str::uuid() . '.pdf';
        Storage::disk('public')->put($filename, $pdfContent);

        return Storage::url($filename);
    }
}
