<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cinema_id');
            $table->string('name', 50);
            $table->unsignedInteger('capacity')->default(0);
            $table->foreign('cinema_id')->references('id')->on('cinemas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
