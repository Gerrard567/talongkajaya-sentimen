<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leksikon extends Model
{
    use HasFactory;

    protected $fillable = [
        'kata_manado',
        'kata_baku',
    ];
}
