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
            // Replace enum values, but keep default as 'pending'
            $table->enum('status', [
                'pending',               // keep this as default
                'Confirmed',
                'Processing',
                'Ready for Delivery',
                'On the Way',
                'Completed',
                'Cancelled',
                'Returned'
            ])->default('pending')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->enum('status', [
                'pending',
                'Confirmed',
                'Processing',
                'Ready for Delivery',
                'Completed',
                'Cancelled',
                'Returned'
            ])->default('pending')->change();
        });
    }
};
