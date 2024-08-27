<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'reference_number', 'total_barcodes'];

    public function records()
    {
        return $this->hasMany(Records::class);
    }
}
