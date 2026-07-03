<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emprunts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('lecteur_id')->foreignUuid()->references('id')->on('lecteurs')->nullable()->onDelete('set null');
            $table->uuid('exemplaire_id')->foreignUuid()->references('id')->on('exemplaires')->onDelete('cascade');
            $table->dateTime('date_emprunt');
            $table->dateTime('date_retour_prevue');
            $table->dateTime('date_retour_effective')->nullable();
            $table->string('statut');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emprunts');
    }
};
