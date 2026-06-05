<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entities\Livre as LivreEntity;
use App\Domain\Repository\LivreRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Livre as LivreModel;

class EloquentLivreRepository implements LivreRepositoryInterface
{
    public function all(): array
    {
        return LivreModel::with(['bibliothecaire', 'exemplaires'])
            ->get()
            ->map(fn(LivreModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function find(int $id): ?LivreEntity
    {
        $model = LivreModel::with(['bibliothecaire', 'exemplaires'])->find($id);

        return $model?->toDomainEntity();
    }

    public function findByIsbn(string $isbn): ?LivreEntity
    {
        $model = LivreModel::with(['bibliothecaire', 'exemplaires'])
            ->where('isbn', $isbn)
            ->first();

        return $model?->toDomainEntity();
    }

    public function save(LivreEntity $livre): LivreEntity
    {
        $model = $livre->getId()
            ? LivreModel::find($livre->getId())
            : new LivreModel();

        $model->fill([
            'titre' => $livre->getTitre(),
            'auteur' => $livre->getAuteur(),
            'isbn' => $livre->getIsbn(),
            'datePublication' => $livre->getDatePublication()->format('Y-m-d'),
            'bibliothecaire_id' => $livre->getBibliothecaireId(),
        ]);

        $model->save();

        return $model->refresh()->toDomainEntity();
    }

    public function delete(int $id): void
    {
        LivreModel::findOrFail($id)->delete();
    }
}
