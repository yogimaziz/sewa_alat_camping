<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sewa extends Model 
{
    protected $table = 'sewa';
    
    protected $fillable = [
        'user_id', 'penyewa_id', 'barang_id', 'tanggal_sewa', 'tanggal_pengembalian'
    ];

    public $timestamps = true;

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }

    public function penyewa(){
    	return $this->belongsTo('App\Models\Penyewa');
    }

    public function barang(){
    	return $this->belongsTo('App\Models\Barang');
    }

    public function pembayaran(){
    	return $this->hasOne('App\Models\Pembayaran');
    }
}