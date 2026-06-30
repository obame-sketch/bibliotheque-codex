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
        $emprunt = $this->empruntRepository->findById($dto->empruntId);

        if ($emprunt === null) {
            throw new \RuntimeException(sprintf('Emprunt introuvable pour l\'ID %s.', $dto->empruntId));
        }
        $this->serviceGestionEmprunt->enregistrerRetour($emprunt);
    }
}
