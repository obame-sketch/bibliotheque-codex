<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Services\ServiceGestionEmprunt;

/**
 * Cas d'utilisation pour enregistrer le retour d'un emprunt.
 */
final class EnregistrerRetourUseCase
{
    public function __construct(
        private readonly EmpruntRepositoryInterface $empruntRepository,
        private readonly ServiceGestionEmprunt $serviceGestionEmprunt,
    ) {}

    /**
     * Traite le retour d'un emprunt et met à jour son statut.
     *
     * @param  string  $empruntId  Identifiant de l'emprunt retourné
     */
    public function execute(string $empruntId): void
    {
        $emprunt = $this->empruntRepository->findById($empruntId);

        if ($emprunt === null) {
            throw new \RuntimeException(sprintf('Emprunt introuvable pour l\'ID %s.', $empruntId));
        }
        $this->serviceGestionEmprunt->enregistrerRetour($emprunt);
    }
}
