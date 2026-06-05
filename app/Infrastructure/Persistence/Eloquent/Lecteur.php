<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Lecteur as LecteurEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecteur extends Model
{
    use HasFactory;

    protected $table = 'lecteurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'dateAdhesion',
    ];

    protected $casts = [
        'dateAdhesion' => 'date',
    ];

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    public function toDomainEntity(): LecteurEntity
    {
        $emprunts = $this->relationLoaded('emprunts') ? $this->emprunts->map(fn($emprunt) => $emprunt->toDomainEntity())->all() : [];

        return new LecteurEntity(
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->dateAdhesion,
            $emprunts
        );
    }
}
