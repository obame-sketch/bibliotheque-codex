<?php

declare(strict_types=1);

namespace App\Domain\Livre;

interface LivreRepositoryInterface
{
    public function findById(string $id): ?Livre;

    public function findAll(): array;

    public function search(string $keyword): array;

    public function save(Livre $livre): void;

    public function delete(string $id): void;

    public function findByIsbn(string $isbn): ?Livre;
}
