<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Création de la table des livres
        Schema::create('livres', function (Blueprint $table) {
            $table->id(); // Identifiant primaire
            $table->string('titre'); // Titre du livre
            $table->string('auteur'); // Auteur du livre
            $table->string('isbn')->unique(); // ISBN unique
            $table->date('datePublication'); // Date de publication
            $table->foreignId('bibliothecaire_id')->constrained('bibliothecaires')->onDelete('cascade'); // Clé étrangère vers bibliothécaires
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        // Suppression de la table des livres
        Schema::dropIfExists('livres');
    }
};