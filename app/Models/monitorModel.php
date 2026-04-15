<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class monitorModel extends Model
{
    protected $table = 'monitoring';
    protected $primaryKey = 'id_monitoring';
    public $timestamps = false;

    protected $fillable = [
        'usia', //int (bulan)
        'berat', // int (kg)
        'penyakit', // TEXT
        'tgl_monitoring', // Date
        'id_ternak',
    ];

    public function ternak()
    {
        return $this->belongsTo(ternakModel::class, 'id_ternak', 'id_ternak');
    }
}
