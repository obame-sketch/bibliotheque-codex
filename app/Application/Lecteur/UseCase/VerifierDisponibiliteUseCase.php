<?php

declare(strict_types=1);

namespace App\Application\Lecteur\UseCase;

use App\Domain\Services\ServiceDisponibilite;

/**
 * Cas d'utilisation pour vérifier la disponibilité d'un livre.
 * 
 * Vérifie si au moins un exemplaire d'un livre est disponible à l'emprunt.
 */
final readonly class VerifierDisponibiliteUseCase
{
    /**
     * Constructeur avec injection du service de disponibilité.
     */
    public function __construct(
        private ServiceDisponibilite $serviceDisponibilite,
    ) {}

    /**
     * Exécute la vérification de disponibilité d'un livre.
     *
     * @param string $livreId L'identifiant du livre à vérifier
     * @return bool          Vrai si le livre est disponible
     */
    public function execute(string $livreId): bool
    {
        return $this->serviceDisponibilite->verifier($livreId);
    }
}