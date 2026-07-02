<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent représentant un bibliothécaire.
 *
 * Correspond à la table "bibliothecaires".
 */
class BibliothecaireModel extends AbstractModel
{
    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'bibliothecaires';

    /**
     * Attributs mass assignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nom',
        'prenom',
        'email',
    ];
}
