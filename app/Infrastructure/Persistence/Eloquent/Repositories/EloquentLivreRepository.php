<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Livre\Livre;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\LivreModel;
use Illuminate\Support\Str;

final class EloquentLivreRepository implements LivreRepositoryInterface
{
    public function findById(string $id): ?Livre
    {
        $model = LivreModel::find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findAll(): array
    {
        return LivreModel::all()
            ->map(fn (LivreModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function search(string $keyword): array
    {
        return LivreModel::where('titre', 'like', "%{$keyword}%")
            ->orWhere('auteur', 'like', "%{$keyword}%")
            ->get()
            ->map(fn (LivreModel $model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(Livre $livre): ?Livre
    {
        if ($livre->id() !== null) {
            $model = LivreModel::find($livre->id());

            if ($model === null) {
                $model = new LivreModel();
                $model->id = $livre->id();
            }
        } else {
            $model = new LivreModel();
            $model->id = (string) Str::uuid();
        }

        $model->titre = $livre->titre();
        $model->auteur = $livre->auteur();
        $model->isbn = $livre->isbn();
        $model->categorie = $livre->categorie();
        $model->date_publication = $livre->datePublication();

        $bibliothecaire = $livre->bibliothecaire();
        if ($bibliothecaire !== null) {
            $model->bibliothecaire_id = $bibliothecaire->id();
        }

        $bibliotheque = $livre->bibliotheque();
        if ($bibliotheque !== null) {
            $model->bibliotheque_id = $bibliotheque->id();
        }

        $model->save();

        return $this->toDomain($model);
    }

    public function delete(string $id): void
    {
        LivreModel::where('id', $id)->delete();
    }

    public function findByIsbn(string $isbn): ?Livre
    {
        $model = LivreModel::where('isbn', $isbn)->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(LivreModel $model): Livre
    {
        return new Livre(
            titre: $model->titre,
            auteur: $model->auteur,
            isbn: $model->isbn,
            datePublication: $model->date_publication instanceof \DateTimeInterface
                ? \DateTimeImmutable::createFromInterface($model->date_publication)
                : new \DateTimeImmutable((string) $model->date_publication),
            categorie: (string) ($model->categorie ?? ''),
            id: $model->id,
        );
    }
}
