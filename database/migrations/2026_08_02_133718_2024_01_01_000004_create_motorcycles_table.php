<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('plate_number')->unique();
            $table->decimal('daily_rate', 8, 2);
            $table->decimal('deposit', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('status')->default('available'); // available, rented, maintenance
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};