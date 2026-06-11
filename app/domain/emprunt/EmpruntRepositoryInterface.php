<?php

declare(strict_types=1);

namespace App\Domain\Emprunt;

/**
 * Contrat de persistance pour les emprunts.
 *
 * Définit les opérations attendues d'un repository d'emprunts, notamment la
 * récupération des emprunts en cours pour un lecteur et la sauvegarde d'un emprunt.
 */
interface EmpruntRepositoryInterface
{
    public function findById(string $id): ?Emprunt;

    public function findEnCoursByLecteur(string $lecteurId): array;

    public function findAll(): array;

    public function save(Emprunt $emprunt): void;

    public function findByExemplaireId(string $exemplaireId): array;
}
