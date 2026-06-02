<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jarak extends Model
{
    protected $table = 'jarak';
    protected $primaryKey = 'id_jarak';
    public $timestamps = false;

    protected $fillable = [
        'id_desa',
        'jarak_km',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id_desa');
    }
}
