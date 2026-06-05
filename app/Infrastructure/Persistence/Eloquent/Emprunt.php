<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Emprunt as EmpruntEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprunt extends Model
{
    use HasFactory;

    public const STATUT_EN_COURS = 'EN_COURS';
    public const STATUT_TERMINE = 'TERMINE';
    public const STATUT_EN_RETARD = 'EN_RETARD';

    protected $table = 'emprunts';

    protected $fillable = [
        'dateEmprunt',
        'dateRetourPrevue',
        'dateRetourEffective',
        'statut',
        'lecteur_id',
        'exemplaire_id',
    ];

    protected $casts = [
        'dateEmprunt' => 'date',
        'dateRetourPrevue' => 'date',
        'dateRetourEffective' => 'date',
    ];

    public function lecteur(): BelongsTo
    {
        return $this->belongsTo(Lecteur::class);
    }

    public function exemplaire(): BelongsTo
    {
        return $this->belongsTo(Exemplaire::class);
    }

    public function toDomainEntity(): EmpruntEntity
    {
        $lecteur = $this->relationLoaded('lecteur') ? $this->lecteur->toDomainEntity() : null;
        $exemplaire = $this->relationLoaded('exemplaire') ? $this->exemplaire->toDomainEntity() : null;

        return new EmpruntEntity(
            $this->id,
            $this->dateEmprunt,
            $this->dateRetourPrevue,
            $this->dateRetourEffective,
            $this->statut,
            $this->lecteur_id,
            $this->exemplaire_id,
            $lecteur,
            $exemplaire
        );
    }
}
