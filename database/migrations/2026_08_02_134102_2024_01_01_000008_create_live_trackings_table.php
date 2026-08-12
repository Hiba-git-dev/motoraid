<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable(); // km/h
            $table->decimal('heading', 5, 2)->nullable(); // 0-360 degrés
            $table->string('status')->default('inactive'); // active, sos, inactive
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_trackings');
    }
};