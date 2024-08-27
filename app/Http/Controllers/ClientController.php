<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return Inertia::render('Client/Index', [
            'clients' => $clients
        ]);
    }

    public function store(StoreClientRequest $request)
    {
        try {
            $client = Client::create($request->only('name', 'reference_number'));
            return Inertia::render('Barcode/Index')->with('success', 'Client created successfully');
        } catch (QueryException $exception) {
            // Check for a duplicate entry error
            if ($exception->getCode() == 23000) {
                return redirect()->back()
                    ->withErrors(['name' => 'The client name or reference number is already in use.'])
                    ->withInput();
            }

            // Handle other database-related errors
            return redirect()->back()
                ->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])
                ->withInput();
        }
    }

    public function show($id)
    {
        $client = Client::with(['records.user'])->find($id);

        if (!$client) {
            return redirect()->route('clients.index')->with('error', 'Client not found');
        }

        return Inertia::render('Client/ClientDetails', [
            'client' => $client,
        ]);
    }
}
