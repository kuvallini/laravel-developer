<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('golfers', static function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('debitor_account');
            $table->string('name');
            $table->string('email');
            $table->date('born_at');
            $table->float('latitude', 4);
            $table->float('longitude', 4);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golfers');
    }
};
