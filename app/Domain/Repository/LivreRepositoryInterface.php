<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Livre;

interface LivreRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?Livre;

    public function findByIsbn(string $isbn): ?Livre;

    public function save(Livre $livre): Livre;

    public function delete(int $id): void;
}
