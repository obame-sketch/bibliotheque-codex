<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Application\Bibliothecaire\DTO\EnregistrerEmpruntDto;
use App\Domain\Emprunt\Emprunt;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Lecteur\LecteurRepositoryInterface;
use App\Domain\Services\ServiceGestionEmprunt;

/**
 * Cas d'utilisation pour l'enregistrement d'un emprunt.
 */
final class EnregistrerEmpruntUseCase
{
    public function __construct(
        private readonly LecteurRepositoryInterface $lecteurRepository,
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
        private readonly ServiceGestionEmprunt $serviceGestionEmprunt,
    ) {}

    /**
     * Enregistre un emprunt en vérifiant le lecteur et l'exemplaire.
     *
     * @param  EnregistrerEmpruntDto  $dto  Données de l'emprunt à enregistrer
     */
    public function execute(EnregistrerEmpruntDto $dto): Emprunt
    {
        $lecteur = $this->lecteurRepository->findById($dto->lecteurId);
        if ($lecteur === null) {
            throw new \RuntimeException(sprintf('Lecteur introuvable pour l\'ID %s.', $dto->lecteurId));
        }
        $exemplaire = $this->exemplaireRepository->findById($dto->exemplaireId);
        if ($exemplaire === null) {
            throw new \RuntimeException(sprintf('Exemplaire introuvable pour l\'ID %s.', $dto->exemplaireId));
        }

        return $this->serviceGestionEmprunt->enregistrerEmprunt($lecteur, $exemplaire);
    }
}
