<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Emprunt;

interface EmpruntRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?Emprunt;

    public function findByLecteurId(int $lecteurId): array;

    public function findByExemplaireId(int $exemplaireId): array;

    public function save(Emprunt $emprunt): Emprunt;

    public function delete(int $id): void;
}
