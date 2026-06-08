<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

interface ExemplaireRepositoryInterface
{
    public function findById(string $id): ?Exemplaire;

    public function findDisponiblesByLivreId(string $livreId): array;

    public function findByLivreId(string $livreId): array;

    public function save(Exemplaire $exemplaire): void;
}
