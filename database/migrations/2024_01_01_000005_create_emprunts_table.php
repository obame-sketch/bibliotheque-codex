<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table des emprunts
        Schema::create('emprunts', function (Blueprint $table) {
            $table->id(); // Identifiant primaire
            $table->date('dateEmprunt'); // Date du prêt
            $table->date('dateRetourPrevue'); // Date de retour prévue
            $table->date('dateRetourEffective')->nullable(); // Date de retour effective, peut être nulle
            $table->enum('statut', ['EN_COURS', 'TERMINE', 'EN_RETARD']); // Statut de l'emprunt
            $table->foreignId('lecteur_id')->constrained('lecteurs')->onDelete('cascade'); // Clé étrangère vers lecteurs
            $table->foreignId('exemplaire_id')->constrained('exemplaires')->onDelete('cascade'); // Clé étrangère vers exemplaires
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        // Suppression de la table des emprunts
        Schema::dropIfExists('emprunts');
    }
};