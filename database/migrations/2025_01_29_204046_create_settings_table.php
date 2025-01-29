<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('map_api_key')->nullable();
            $table->string('map_id')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert([
            'map_api_key' => 'AIzaSyCi327LFl7byyspi_v7snyRjEVQPptUBPc',
            'map_id' => "30a0d969799b3759"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
