<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggan',
        'item_furniture',
        'teks_asli',
        'teks_normalisasi',
        'label_sentimen',
        'sentimen_barang',
        'sentimen_pengiriman',
        'sentimen_packaging',
    ];
}
