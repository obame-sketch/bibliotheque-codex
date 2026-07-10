<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->uuid('bibliotheque_id')->nullable()->after('id');
            $table->foreign('bibliotheque_id')
                ->references('id')
                ->on('bibliotheques')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            $table->dropForeign(['bibliotheque_id']);
            $table->dropColumn('bibliotheque_id');
        });
    }
};
