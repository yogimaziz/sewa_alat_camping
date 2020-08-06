<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model 
{
    protected $table = 'pembayaran';
    
    protected $fillable = [
        'sewa_id', 'total', 'bayar', 'status'
    ];	

    public $timestamps = true;

    public function sewa(){
        return $this->hasMany('App\Models\Sewa');
    }

}
