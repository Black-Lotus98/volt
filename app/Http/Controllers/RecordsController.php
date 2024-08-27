<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Records;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RecordsController extends Controller
{
    public function index()
    {
        $records = Records::with(['client', 'user'])
            ->get()
            ->map(function ($record) {
                return [
                    'record_id' => $record->id,
                    'client_name' => $record->client->name,
                    'reference_number' => $record->client->reference_number,
                    'user_name' => $record->user->name,
                    'record_link' => $record->record_link,
                ];
            });

        return Inertia::render('Barcode/BarcodeRecords', [
            'records' => $records
        ]);
    }

    public function checkLastGeneratedNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reference' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Reference number is required'], 400);
        }

        $reference = $request->input('reference');

        if (!$reference) {
            return response()->json(['error' => 'Reference number is required'], 400);
        }

        $lastRecord = Client::where('reference_number', 'LIKE', $reference . '%')
            ->orderBy('id', 'desc')
            ->get('total_barcodes');

        return response()->json($lastRecord[0], 200);
    }
}
