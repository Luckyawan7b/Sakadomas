<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\akunModel;

class surveiModel extends Model
{
    protected $table = 'survei';
    protected $primaryKey = 'id_survei';
    public $timestamps = false;

    protected $fillable = [
        'tgl_survei',
        'status',
        'ket',
        'id_akun'
    ];

    public function akun()
    {
        return $this->belongsTo(akunModel::class, 'id_akun', 'id_akun');
    }
}
