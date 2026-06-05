<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Bibliothecaire as BibliothecaireEntity;
use App\Domain\Entities\Livre as LivreEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    use HasFactory;

    protected $table = 'livres';

    protected $fillable = [
        'titre',
        'auteur',
        'isbn',
        'datePublication',
        'bibliothecaire_id',
    ];

    protected $casts = [
        'datePublication' => 'date',
    ];

    public function bibliothecaire(): BelongsTo
    {
        return $this->belongsTo(Bibliothecaire::class);
    }

    public function exemplaires(): HasMany
    {
        return $this->hasMany(Exemplaire::class);
    }

    public function toDomainEntity(): LivreEntity
    {
        $bibliothecaire = $this->relationLoaded('bibliothecaire') ? $this->bibliothecaire->toDomainEntity() : null;
        $exemplaires = $this->relationLoaded('exemplaires') ? $this->exemplaires->map(fn($exemplaire) => $exemplaire->toDomainEntity())->all() : [];

        return new LivreEntity(
            $this->id,
            $this->titre,
            $this->auteur,
            $this->isbn,
            $this->datePublication,
            $this->bibliothecaire_id,
            $bibliothecaire,
            $exemplaires
        );
    }
}
