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
        Schema::create('parking_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations');
            $table->foreignId('building_id')->constrained('buildings');
            $table->foreignId('entry_access_point_id')->constrained('access_points');
            $table->foreignId('exit_access_point_id')->nullable()->constrained('access_points');
            $table->foreignId('vehicle_master_id')->constrained('vehicle_masters');
            $table->dateTime('in_time');
            $table->dateTime('out_time')->nullable();
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};
