<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;

/**
 * Service de domaine qui encapsule la logique de disponibilité des livres.
 *
 * Fournit des méthodes pour vérifier s'il existe des exemplaires disponibles
 * pour un livre donné et pour obtenir un exemplaire disponible.
 */
final class ServiceDisponibilite
{
    public function __construct(
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    /**
     * Vérifie s'il existe au moins un exemplaire disponible pour un livre donné.
     *
     * @param  string  $livreId  Identifiant du livre
     * @return bool True si au moins un exemplaire est disponible
     */
    public function verifierDisponibilite(string $livreId): bool
    {
        return count($this->exemplaireRepository->findDisponiblesByLivre($livreId)) > 0;
    }

    /**
     * Retourne un exemplaire disponible pour le livre donné, ou null si aucun.
     *
     * @param  string  $livreId  Identifiant du livre
     * @return ?Exemplaire Un exemplaire disponible ou null
     */
    public function obtenirExemplaireDisponible(string $livreId): ?Exemplaire
    {
        $exemplaires = $this->exemplaireRepository->findDisponiblesByLivre($livreId);

        return $exemplaires[0] ?? null;
    }
}
