<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table des exemplaires
        Schema::create('exemplaires', function (Blueprint $table) {
            $table->id(); // Identifiant primaire
            $table->string('codeBarre')->unique(); // Code barre unique
            $table->enum('statut', ['DISPONIBLE', 'EMPRUNTE', 'PERDU']); // Statut de l'exemplaire
            $table->foreignId('livre_id')->constrained('livres')->onDelete('cascade'); // Clé étrangère vers livres
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        // Suppression de la table des exemplaires
        Schema::dropIfExists('exemplaires');
    }
};