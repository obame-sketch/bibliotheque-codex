<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Eloquent représentant un lecteur.
 *
 * Correspond à la table "lecteurs".
 */
class LecteurModel extends AbstractModel
{
    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'lecteurs';

    /**
     * Attributs mass assignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'date_adhesion',
    ];

    /**
     * Casts de types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'date_adhesion' => 'date',
    ];

    /**
     * Relation : un lecteur peut avoir plusieurs emprunts.
     *
     * @return HasMany<EmpruntModel>
     */
    public function emprunts(): HasMany
    {
        return $this->hasMany(EmpruntModel::class);
    }
}
