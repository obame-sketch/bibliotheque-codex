<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exemplaires', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('livre_id', 36);
            $table->string('code_barre');
            $table->string('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exemplaires');
    }
};
