<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent de base abstrait pour toutes les entités de la bibliothèque.
 *
 * Désactive l'incrémentation automatique et force l'utilisation d'UUID
 * comme identifiants primaires de type chaîne.
 */
abstract class AbstractModel extends Model
{
    /**
     * Désactive l'auto-incrémentation SQL.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Type de la clé primaire.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Casts globaux de l'identifiant.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
    ];

    public $timestamps = false;
}
