<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Emprunt\StatutEmprunt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Eloquent représentant un emprunt.
 *
 * Correspond à la table "emprunts".
 */
class EmpruntModel extends AbstractModel
{
    /**
     * Nom de la table associée.
     *
     * @var string
     */
    protected $table = 'emprunts';

    /**
     * Attributs mass assignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'lecteur_id',
        'exemplaire_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
        'statut',
    ];

    /**
     * Casts de types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'date_emprunt' => 'datetime',
        'date_retour_prevue' => 'datetime',
        'date_retour_effective' => 'datetime',
        'statut' => StatutEmprunt::class,
    ];

    /**
     * Relation : un emprunt appartient à un lecteur.
     *
     * @return BelongsTo<LecteurModel>
     */
    public function lecteur(): BelongsTo
    {
        return $this->belongsTo(LecteurModel::class);
    }

    /**
     * Relation : un emprunt porte sur un exemplaire.
     *
     * @return BelongsTo<ExemplaireModel>
     */
    public function exemplaire(): BelongsTo
    {
        return $this->belongsTo(ExemplaireModel::class);
    }
}
