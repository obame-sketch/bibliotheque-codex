<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecteurs', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->date('date_adhesion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecteurs');
    }
};
