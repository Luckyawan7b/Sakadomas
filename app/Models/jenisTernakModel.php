<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jenisTernakModel extends Model
{
    protected $table = 'jenis_ternak';
    protected $primaryKey = 'id_jenis_ternak';
    public $timestamps = false;

    protected $fillable = [
        'jenis_ternak',
    ];

}
