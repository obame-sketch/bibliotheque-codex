<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Eloquent représentant une bibliothèque.
 *
 * Correspond à la table "bibliotheques".
 */
class BibliothequeModel extends AbstractModel
{
    protected $table = 'bibliotheques';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nom',
        'adresse',
    ];

    /**
     * Relation : une bibliothèque possède plusieurs livres.
     *
     * @return HasMany<LivreModel>
     */
    public function livres(): HasMany
    {
        return $this->hasMany(LivreModel::class);
    }
}
