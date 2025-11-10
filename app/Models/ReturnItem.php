<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    //
    public function return(){

        return $this->belongsTo(Returns::class);
    }

    public function orderItem(){

        return $this->belongsTo(OrderItem::class);
    }
    
}
