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
        Schema::create('shipment_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('state');
            $table->string('country');
            $table->string('address');
            $table->string('phone_number');
            $table->string('landmark');
            $table->enum('type', ['sender', 'receiver']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_addresses');
    }
};
