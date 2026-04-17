<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodeNdt extends Model
{
    use HasFactory;

    protected $table = 'metode_ndt';
    protected $primaryKey = 'id_metode';

    protected $fillable = [
        'kode_metode',
        'nama_metode',
        'deskripsi',
    ];
}
