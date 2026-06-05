<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entities\Exemplaire as ExemplaireEntity;
use App\Domain\Repository\ExemplaireRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Exemplaire as ExemplaireModel;

class EloquentExemplaireRepository implements ExemplaireRepositoryInterface
{
    public function all(): array
    {
        return ExemplaireModel::with(['livre', 'emprunts'])
            ->get()
            ->map(fn(ExemplaireModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function find(int $id): ?ExemplaireEntity
    {
        $model = ExemplaireModel::with(['livre', 'emprunts'])->find($id);

        return $model?->toDomainEntity();
    }

    public function findByLivreId(int $livreId): array
    {
        return ExemplaireModel::with(['livre', 'emprunts'])
            ->where('livre_id', $livreId)
            ->get()
            ->map(fn(ExemplaireModel $model) => $model->toDomainEntity())
            ->all();
    }

    public function save(ExemplaireEntity $exemplaire): ExemplaireEntity
    {
        $model = $exemplaire->getId()
            ? ExemplaireModel::find($exemplaire->getId())
            : new ExemplaireModel();

        $model->fill([
            'codeBarre' => $exemplaire->getCodeBarre(),
            'statut' => $exemplaire->getStatut(),
            'livre_id' => $exemplaire->getLivreId(),
        ]);

        $model->save();

        return $model->refresh()->toDomainEntity();
    }

    public function delete(int $id): void
    {
        ExemplaireModel::findOrFail($id)->delete();
    }
}
