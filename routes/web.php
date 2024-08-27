<?php

use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\RegisterRequestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', [DashboardController::class, 'home'])->name('home');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/barcode-generator', [BarcodeController::class, 'index'])->name('barcode.index');
    Route::post('/generateBarcode', [BarcodeController::class, 'generateBarcodes'])->name('barcode.generate');

    Route::get('/barcode-records', [RecordsController::class, 'index'])->name('barcode.records');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::post('/clients', [ClientController::class, 'store']);


    // Route::post('/uploadBarcodePdf', [RecordsController::class, 'uploadBarcodePdf'])->name('uploadBarcodePdf');
    // Route::get('/barcodes/{filename}', [RecordsController::class, 'serveFile'])->name('barcodes.serve');
    // Route::get('/graph-data', [ClientController::class, 'getGraphData'])->name('graph.data');
    // Route::post('/clients/barcode-count', [ClientController::class, 'getClientsBarcodeCount']);




    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
