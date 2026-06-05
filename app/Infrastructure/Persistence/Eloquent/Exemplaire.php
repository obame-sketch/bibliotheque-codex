<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Entities\Emprunt as EmpruntEntity;
use App\Domain\Entities\Exemplaire as ExemplaireEntity;
use App\Domain\Entities\Livre as LivreEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exemplaire extends Model
{
    use HasFactory;

    protected $table = 'exemplaires';

    protected $fillable = [
        'codeBarre',
        'statut',
        'livre_id',
    ];

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    public function toDomainEntity(): ExemplaireEntity
    {
        $livre = $this->relationLoaded('livre') ? $this->livre->toDomainEntity() : null;
        $emprunts = $this->relationLoaded('emprunts') ? $this->emprunts->map(fn($emprunt) => $emprunt->toDomainEntity())->all() : [];

        return new ExemplaireEntity(
            $this->id,
            $this->codeBarre,
            $this->statut,
            $this->livre_id,
            $livre,
            $emprunts
        );
    }
}
