<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Application\Bibliothecaire\DTO\RetournerLivreDto;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Services\ServiceGestionEmprunt;

/**
 * Cas d'utilisation pour le retour d'un livre.
 *
 * Traite le retour d'un emprunt en mettant à jour son statut et
 * en rendant l'exemplaire disponible.
 */
final class RetournerLivreUseCase
{
    public function __construct(
        private readonly EmpruntRepositoryInterface $empruntRepository,
        private readonly ServiceGestionEmprunt $serviceGestionEmprunt,
    ) {}

    /**
     * Exécute le retour d'un emprunt.
     *
     * @param  RetournerLivreDto  $dto  DTO contenant l'ID de l'emprunt
     *
     * @throws \RuntimeException si l'emprunt n'est pas trouvé
     */
    public function execute(RetournerLivreDto $dto): void
    {
        // Étape 1 : Vérifier que l'emprunt existe
        $emprunt = $this->empruntRepository->findById($dto->empruntId);

        if ($emprunt === null) {
            throw new \RuntimeException(sprintf('Emprunt introuvable pour l\'ID %s.', $dto->empruntId));
        }

        // Étape 2 : Enregistrer le retour via le service de gestion
        // Le service met à jour le statut de l'exemplaire (DISPONIBLE)
        // et clôture l'emprunt avec la date du jour
        $this->serviceGestionEmprunt->enregistrerRetour($emprunt);
    }
}
