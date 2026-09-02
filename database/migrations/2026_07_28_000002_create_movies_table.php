<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 150)->nullable(false);
            $table->text('sinopsis')->nullable();
            $table->unsignedInteger('durasi')->nullable()->comment('dalam menit');
            $table->string('rating_umur', 10)->default('SU');
            $table->string('poster', 255)->nullable();
            $table->string('trailer_url', 255)->nullable();
            $table->date('release_date')->nullable();
            $table->string('director', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
