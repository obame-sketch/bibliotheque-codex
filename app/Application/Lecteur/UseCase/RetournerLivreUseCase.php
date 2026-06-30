<?php

declare(strict_types=1);

namespace App\Application\Lecteur\UseCase;

use App\Application\Lecteur\DTO\RetournerLivreDto;
use App\Domain\Common\Exception\DomainException;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Services\ServiceGestionEmprunt;

/**
 * Cas d'utilisation pour retourner un livre emprunté.
 *
 * Orchestre le retour d'un livre :
 * - Vérifie l'existence de l'emprunt
 * - Déléguet les règles de modification à l'état au service du domaine
 * - Persist l'emprunt mis à jour
 */
final readonly class RetournerLivreUseCase
{
    /**
     * Constructeur avec injections des dépendances.
     */
    public function __construct(
        private EmpruntRepositoryInterface $empruntRepository,
        private ServiceGestionEmprunt $serviceGestionEmprunt,
    ) {}

    /**
     * Orchestre le retour d'un livre emprunté.
     *
     * @param  RetournerLivreDto  $dto  DTO contenant l'ID de l'emprunt
     *
     * @throws DomainException Si l'emprunt n'existe pas ou est déjà clôturé
     */
    public function execute(RetournerLivreDto $dto): void
    {
        $emprunt = $this->empruntRepository->findById($dto->empruntId());
        if (! $emprunt) {
            throw new DomainException("Action impossible : la référence de l'emprunt est invalide.");
        }
        $this->serviceGestionEmprunt->retourner($emprunt);
        $this->empruntRepository->save($emprunt);
    }
}
