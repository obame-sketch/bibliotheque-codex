<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titre');
            $table->string('auteur');
            $table->string('isbn');
            $table->date('date_publication');
            $table->uuid('bibliothecaire_id');
            $table->foreign('bibliothecaire_id')->references('id')->on('bibliothecaires')->restrictOnDelete();
            $table->index('bibliothecaire_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livres');
    }
};
