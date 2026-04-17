<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaInspeksi extends Model
{
    use HasFactory;

    protected $table = 'area_inspeksi';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'id_kapal',
        'nama_area',
        'kode_area',
        'titik_koordinat',
    ];

    /**
     * Relationship: Area Inspeksi milik satu Kapal
     */
    public function kapal(): BelongsTo
    {
        return $this->belongsTo(Kapal::class, 'id_kapal', 'id_kapal');
    }
}
