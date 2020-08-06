<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model 
{
    protected $table = 'penyewa';
    
    protected $fillable = [
        'nama_penyewa', 'alamat_penyewa', 'no_telp'
    ];

    public $timestamps = true;

    public function sewa(){
        return $this->hasMany('App\Models\Sewa');
    }
}