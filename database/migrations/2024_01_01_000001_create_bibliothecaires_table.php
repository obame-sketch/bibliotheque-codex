<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table des bibliothécaires
        Schema::create('bibliothecaires', function (Blueprint $table) {
            $table->id(); // Identifiant primaire
            $table->string('nom'); // Nom du bibliothécaire
            $table->string('prenom'); // Prénom du bibliothécaire
            $table->string('email')->unique(); // Email unique
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        // Suppression de la table des bibliothécaires
        Schema::dropIfExists('bibliothecaires');
    }
};