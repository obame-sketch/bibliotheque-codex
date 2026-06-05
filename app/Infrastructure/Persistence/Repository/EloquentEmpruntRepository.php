<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entities\Emprunt as EmpruntEntity;
use App\Domain\Repository\EmpruntRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Emprunt as EmpruntModel;

class EloquentEmpruntRepository implements EmpruntRepositoryInterface
{
    public function all(): array
    {
        return EmpruntModel::with(['lecteur', 'exemplaire'])
            ->get()
            ->map(fn(EmpruntModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function find(int $id): ?EmpruntEntity
    {
        $model = EmpruntModel::with(['lecteur', 'exemplaire'])->find($id);

        return $model?->toDomainEntity();
    }

    public function findByLecteurId(int $lecteurId): array
    {
        return EmpruntModel::with(['lecteur', 'exemplaire'])
            ->where('lecteur_id', $lecteurId)
            ->get()
            ->map(fn(EmpruntModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function findByExemplaireId(int $exemplaireId): array
    {
        return EmpruntModel::with(['lecteur', 'exemplaire'])
            ->where('exemplaire_id', $exemplaireId)
            ->get()
            ->map(fn(EmpruntModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function save(EmpruntEntity $emprunt): EmpruntEntity
    {
        $model = $emprunt->getId()
            ? EmpruntModel::find($emprunt->getId())
            : new EmpruntModel();

        $model->fill([
            'dateEmprunt' => $emprunt->getDateEmprunt()->format('Y-m-d'),
            'dateRetourPrevue' => $emprunt->getDateRetourPrevue()->format('Y-m-d'),
            'dateRetourEffective' => $emprunt->getDateRetourEffective()?->format('Y-m-d'),
            'statut' => $emprunt->getStatut(),
            'lecteur_id' => $emprunt->getLecteurId(),
            'exemplaire_id' => $emprunt->getExemplaireId(),
        ]);

        $model->save();

        return $model->refresh()->toDomainEntity();
    }

    public function delete(int $id): void
    {
        EmpruntModel::findOrFail($id)->delete();
    }
}
