<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emprunts', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('lecteur_id', 36);
            $table->string('exemplaire_id', 36);
            $table->dateTime('date_emprunt');
            $table->dateTime('date_retour_prevue');
            $table->dateTime('date_retour_effective')->nullable();
            $table->string('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprunts');
    }
};
