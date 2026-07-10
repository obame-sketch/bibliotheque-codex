<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Infrastructure\Persistence\Eloquent\Models\ExemplaireModel;
use Illuminate\Support\Str;

final class EloquentExemplaireRepository implements ExemplaireRepositoryInterface
{
    public function findById(string $id): ?Exemplaire
    {
        $model = ExemplaireModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByLivre(string $livreId): array
    {
        return ExemplaireModel::where('livre_id', $livreId)
            ->get()
            ->map(fn (ExemplaireModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function findDisponiblesByLivre(string $livreId): array
    {
        return ExemplaireModel::where('livre_id', $livreId)
            ->where('statut', StatutExemplaire::DISPONIBLE->value)
            ->get()
            ->map(fn (ExemplaireModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(Exemplaire $exemplaire): ?Exemplaire
    {
        if ($exemplaire->id() !== null) {
            $model = ExemplaireModel::find($exemplaire->id());

            if ($model === null) {
                $model = new ExemplaireModel();
                $model->id = $exemplaire->id();
            }
        } else {
            $model = new ExemplaireModel();
            $model->id = (string) Str::uuid();
        }

        $model->code_barre = $exemplaire->codeBarre();
        $model->statut = $exemplaire->statut();
        $model->livre_id = $exemplaire->livreId();

        $model->save();

        return $this->toDomain($model);
    }

    private function toDomain(ExemplaireModel $model): Exemplaire
    {
        return new Exemplaire(
            codeBarre: $model->code_barre,
            statut: $model->statut instanceof StatutExemplaire ? $model->statut : StatutExemplaire::tryFrom((string) $model->statut) ?? StatutExemplaire::DISPONIBLE,
            id: $model->id,
        );
    }
}
