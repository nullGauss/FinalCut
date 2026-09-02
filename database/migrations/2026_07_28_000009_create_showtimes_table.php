<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('movie_id');
            $table->unsignedInteger('studio_id');
            $table->date('show_date');
            $table->time('show_time');
            $table->decimal('price', 10, 2);
            $table->foreign('movie_id')->references('id')->on('movies')->cascadeOnDelete();
            $table->foreign('studio_id')->references('id')->on('studios')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};
