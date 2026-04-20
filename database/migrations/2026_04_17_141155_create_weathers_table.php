<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weathers', function (Blueprint $table) {
            $table->id();

            // 🔥 RELASI KE CITIES
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();

            $table->float('temperature');
            $table->string('condition');
            $table->integer('humidity');
            $table->float('wind_speed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weathers');
    }
};
