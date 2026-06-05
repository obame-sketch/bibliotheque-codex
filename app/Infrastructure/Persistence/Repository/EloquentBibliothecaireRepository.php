<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entities\Bibliothecaire as BibliothecaireEntity;
use App\Domain\Repository\BibliothecaireRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Bibliothecaire as BibliothecaireModel;

class EloquentBibliothecaireRepository implements BibliothecaireRepositoryInterface
{
    public function all(): array
    {
        return BibliothecaireModel::with('livres')
            ->get()
            ->map(fn(BibliothecaireModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function find(int $id): ?BibliothecaireEntity
    {
        $model = BibliothecaireModel::with('livres')->find($id);

        return $model?->toDomainEntity();
    }

    public function save(BibliothecaireEntity $bibliothecaire): BibliothecaireEntity
    {
        $model = $bibliothecaire->getId()
            ? BibliothecaireModel::find($bibliothecaire->getId())
            : new BibliothecaireModel();

        $model->fill([
            'nom' => $bibliothecaire->getNom(),
            'prenom' => $bibliothecaire->getPrenom(),
            'email' => $bibliothecaire->getEmail(),
        ]);

        $model->save();

        return $model->refresh()->toDomainEntity();
    }

    public function delete(int $id): void
    {
        BibliothecaireModel::findOrFail($id)->delete();
    }
}
