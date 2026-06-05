<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Bibliothecaire as BibliothecaireEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bibliothecaire extends Model
{
    use HasFactory;

    protected $table = 'bibliothecaires';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
    ];

    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class);
    }

    public function toDomainEntity(): BibliothecaireEntity
    {
        return new BibliothecaireEntity(
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->relationLoaded('livres') ? $this->livres->map(fn($livre) => $livre->toDomainEntity())->all() : []
        );
    }
}
