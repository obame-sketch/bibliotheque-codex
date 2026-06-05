<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entities\Lecteur as LecteurEntity;
use App\Domain\Repository\LecteurRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Lecteur as LecteurModel;

class EloquentLecteurRepository implements LecteurRepositoryInterface
{
    public function all(): array
    {
        return LecteurModel::with('emprunts')
            ->get()
            ->map(fn(LecteurModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function find(int $id): ?LecteurEntity
    {
        $model = LecteurModel::with('emprunts')->find($id);

        return $model?->toDomainEntity();
    }

    public function save(LecteurEntity $lecteur): LecteurEntity
    {
        $model = $lecteur->getId()
            ? LecteurModel::find($lecteur->getId())
            : new LecteurModel();

        $model->fill([
            'nom' => $lecteur->getNom(),
            'prenom' => $lecteur->getPrenom(),
            'email' => $lecteur->getEmail(),
            'dateAdhesion' => $lecteur->getDateAdhesion()->format('Y-m-d'),
        ]);

        $model->save();

        return $model->refresh()->toDomainEntity();
    }

    public function delete(int $id): void
    {
        LecteurModel::findOrFail($id)->delete();
    }
}
