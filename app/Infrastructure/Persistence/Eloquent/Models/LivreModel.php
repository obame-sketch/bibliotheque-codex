<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Eloquent représentant un livre.
 *
 * Correspond à la table "livres".
 */
class LivreModel extends AbstractModel
{
    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'livres';

    /**
     * Attributs mass assignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'auteur',
        'isbn',
        'date_publication',
    ];

    /**
     * Casts de types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'date_publication' => 'date',
    ];

    /**
     * Relation : un livre possède plusieurs exemplaires.
     *
     * @return HasMany<ExemplaireModel>
     */
    public function exemplaires(): HasMany
    {
        return $this->hasMany(ExemplaireModel::class);
    }
}
