<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
    'user_id',
    'status', 
    'total', 
    'payment_method', 
    'payment_status', 
    'recipient_name', 
    'recipient_email', 
    'recipient_phone', 
    'recipient_address',
    'quotation_id', 
    'sender_stop_id', 
    'recipient_stop_id', 
    'delivery_fee',
    'delivery_distance', 
    'currency', 
    'unit', 
    'delivery_status', 
    'image', 
    'delivered_at', 
    'driver_id', 
    'lalamove_order_id', 
    'driver_id', 
    'share_link', 
    'payment_due_at', 
    'subtotal', 
    'total'
    ];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function orderItems(){

        return $this->hasMany(OrderItem::class);
    }

    public function address(){

        return $this->belongsTo(Address::class);
    }

    public function returns(){

        return $this->hasMany(Returns::class);
    }
}
