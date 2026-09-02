<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('studio_id');
            $table->string('seat_number', 10);
            $table->enum('seat_type', ['reguler', 'vip'])->default('reguler');
            $table->unique(['studio_id', 'seat_number']);
            $table->foreign('studio_id')->references('id')->on('studios')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
