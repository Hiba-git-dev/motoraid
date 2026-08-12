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
        Schema::table('groups', function (Blueprint $table) {
                   $table->integer('max_members')->default(10)->after('is_private');
        $table->boolean('is_approval_required')->default(false)->after('max_members');
        $table->string('start_point')->nullable()->after('is_approval_required');
        $table->string('end_point')->nullable()->after('start_point');
        $table->date('ride_date')->nullable()->after('end_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            //
        });
    }
};
