<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Bibliothecaire\Bibliothecaire;
use App\Domain\Bibliotheque\Bibliotheque;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Eloquent représentant un livre.
 *
 * Correspond à la table "livres".
 */
class LivreModel extends AbstractModel
{
    protected $table = 'livres';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'titre',
        'auteur',
        'isbn',
        'categorie',
        'date_publication',
        'bibliotheque_id',
        'bibliothecaire_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'date_publication' => 'date',
    ];

    /**
     * Relation : un livre appartient à une bibliothèque.
     *
     * @return BelongsTo<BibliothequeModel>
     */
    public function bibliotheque(): BelongsTo
    {
        return $this->belongsTo(BibliothequeModel::class);
    }

    /**
     * Relation : un livre appartient à un bibliothécaire.
     *
     * @return BelongsTo<BibliothecaireModel>
     */
    public function bibliothecaire(): BelongsTo
    {
        return $this->belongsTo(BibliothecaireModel::class);
    }

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
