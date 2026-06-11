<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

interface ExemplaireRepositoryInterface
{
    public function findById(string $id): ?Exemplaire;

    public function findByLivre(string $livreId): array;

    public function findDisponiblesByLivre(string $livreId): array;

    public function save(Exemplaire $exemplaire): void;
}
