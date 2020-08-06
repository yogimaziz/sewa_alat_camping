<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model 
{
    protected $table = 'barang';
    
    protected $fillable = [
        'nama_barang', 'harga_sewa','stock','image'
    ];

    public $timestamps = true;

    public function sewa(){
        return $this->hasMany('App\Models\Sewa');
    }

}
