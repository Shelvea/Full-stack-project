<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->string('recipient_name');
            $table->string('recipient_email');
            $table->string('recipient_phone');
            $table->string('recipient_address');
            $table->string('quotation_id')->nullable();
            $table->string('sender_stop_id')->nullable();
            $table->string('recipient_stop_id')->nullable();
            $table->decimal('delivery_fee', 8, 2)->default(0);
            $table->string('currency' , 10)->nullable();
            $table->decimal('delivery_distance', 8, 2)->default(0);
            $table->string('unit', 5)->default('km');            
            $table->string('delivery_status')->default('pending');
            $table->string('image')->nullable();
            $table->string('delivered_at')->nullable();
            $table->string('driver_id')->default('');
            $table->string('share_link')->nullable();
            $table->string('payment_status')->default('unpaid')->after('payment_method');
            $table->dateTime('payment_due_at')->nullable();
            $table->string('lalamove_order_id')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropColumn([
                'recipient_name',
                'recipient_email',
                'recipient_phone',
                'recipient_address',
                'quotation_id',
                'sender_stop_id',
                'recipient_stop_id',
                'delivery_fee',
                'currency',
                'delivery_distance',
                'unit',
                'delivery_status',
                'image',
                'delivered_at',
                'driver_id',
                'share_link',
                'payment_status',
                'payment_due_at',
                'lalamove_order_id',
                'subtotal',
                
            ]);
        });
    }
};
