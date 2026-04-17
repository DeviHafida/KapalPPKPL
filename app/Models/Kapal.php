<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';
    protected $primaryKey = 'id_kapal';

    protected $fillable = [
        'nama_kapal',
        'jenis_kapal',
        'tahun_pembuatan',
        'bobot_kapal',
        'status_operasional',
    ];

    protected $casts = [
        'tahun_pembuatan' => 'integer',
        'bobot_kapal' => 'decimal:2',
    ];

    /**
     * Relationship: Kapal memiliki banyak Area Inspeksi
     */
    public function areaInspeksi(): HasMany
    {
        return $this->hasMany(AreaInspeksi::class, 'id_kapal', 'id_kapal');
    }
}
