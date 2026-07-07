<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Exemplaire\StatutExemplaire;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Eloquent représentant un exemplaire.
 *
 * Correspond à la table "exemplaires".
 */
class ExemplaireModel extends AbstractModel
{
    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'exemplaires';

    /**
     * Attributs mass assignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'livre_id',
        'code_barre',
        'statut',
    ];

    /**
     * Casts de types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'statut' => StatutExemplaire::class,
    ];

    /**
     * Relation : un exemplaire appartient à un livre.
     *
     * @return BelongsTo<LivreModel>
     */
    public function livre(): BelongsTo
    {
        return $this->belongsTo(LivreModel::class);
    }

    /**
     * Relation : un exemplaire peut être concerné par plusieurs emprunts.
     *
     * @return HasMany<EmpruntModel>
     */
    public function emprunts(): HasMany
    {
        return $this->hasMany(EmpruntModel::class);
    }
}
