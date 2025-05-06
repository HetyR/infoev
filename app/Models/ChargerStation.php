<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChargerStation extends Model
{
    use HasFactory; 
     protected $table = 'charger_stations';
     protected $fillable = ['wilayah', 'places'];
 
     // Menyimpan data dalam format JSON untuk 'places'
     protected $casts = [
         'places' => 'array',
     ];
}
