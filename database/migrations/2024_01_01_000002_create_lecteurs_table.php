<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table des lecteurs
        Schema::create('lecteurs', function (Blueprint $table) {
            $table->id(); // Identifiant primaire
            $table->string('nom'); // Nom du lecteur
            $table->string('prenom'); // Prénom du lecteur
            $table->string('email')->unique(); // Email unique
            $table->date('dateAdhesion'); // Date d'adhésion
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        // Suppression de la table des lecteurs
        Schema::dropIfExists('lecteurs');
    }
};