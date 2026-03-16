<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kecamatanModel extends Model
{
    protected $table = 'kecamatan';
    protected $primaryKey = 'id_kecamatan';
    public $timestamps = false;

    protected $fillable = [
        'nama_kecamatan',
    ];

    public function desa()
    {
        return $this->hasMany(Desa::class, 'id_kecamatan', 'id_kecamatan');
    }
}
