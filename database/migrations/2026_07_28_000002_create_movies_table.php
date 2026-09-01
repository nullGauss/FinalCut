<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('synopsis')->nullable();
            $table->string('director')->nullable();
            $table->text('cast')->nullable();
            $table->string('poster')->nullable();
            $table->string('trailer_url')->nullable();
            $table->integer('duration')->nullable();
            $table->date('release_date')->nullable();
            $table->enum('status', ['now_showing', 'coming_soon', 'ended'])->default('coming_soon');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
