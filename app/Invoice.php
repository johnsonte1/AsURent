<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function contract()
    {
         return $this->belongsTo(Contract::class); 
    }
     
    public function items(){
        return $this->hasMany(Item::class);
    }
}
