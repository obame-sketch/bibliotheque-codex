<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

/**
 * Contrat de persistance pour les exemplaires.
 *
 * Définit les opérations nécessaires pour retrouver des exemplaires liés à un
 * livre, trouver les exemplaires disponibles et sauvegarder un exemplaire.
 */
interface ExemplaireRepositoryInterface
{
    public function findById(string $id): ?Exemplaire;

    public function findByLivre(string $livreId): array;

    public function findDisponiblesByLivre(string $livreId): array;

    public function save(Exemplaire $exemplaire): ?Exemplaire;
}
