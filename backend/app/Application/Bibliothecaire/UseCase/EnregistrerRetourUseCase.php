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
     * @param string $empruntId Identifiant de l'emprunt retourné
     */
    public function execute(string $empruntId): void
    {
        // Étape 1 : Vérifier que l'emprunt existe
        $emprunt = $this->empruntRepository->findById($empruntId);

        if ($emprunt === null) {
            throw new \RuntimeException(sprintf('Emprunt introuvable pour l\'ID %s.', $empruntId));
        }

        // Étape 2 : Enregistrer le retour via le service de gestion
        // Le service met à jour le statut de l'exemplaire (DISPONIBLE)
        // et clôture l'emprunt avec la date du jour
        $this->serviceGestionEmprunt->enregistrerRetour($emprunt);
    }
}
