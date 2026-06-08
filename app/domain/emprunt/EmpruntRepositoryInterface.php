<?php

declare(strict_types=1);

namespace App\Domain\Emprunt;

interface EmpruntRepositoryInterface
{
    public function findById(string $id): ?Emprunt;

    public function findEnCoursByLecteur(string $lecteurId): array;

    public function findByExemplaireId(string $exemplaireId): array;

    public function save(Emprunt $emprunt): void;
}
