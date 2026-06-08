<?php

declare(strict_types=1);

namespace App\Domain\Livre;

interface LivreRepositoryInterface
{
    public function findById(string $id): ?Livre;

    public function findByIsbn(string $isbn): ?Livre;

    public function save(Livre $livre): void;
}
