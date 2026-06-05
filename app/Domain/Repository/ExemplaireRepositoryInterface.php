<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Exemplaire;

interface ExemplaireRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?Exemplaire;

    public function findByLivreId(int $livreId): array;

    public function save(Exemplaire $exemplaire): Exemplaire;

    public function delete(int $id): void;
}
