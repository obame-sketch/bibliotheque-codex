<?php

declare(strict_types=1);

namespace App\Application\Lecteur\UseCase;

use App\Application\Lecteur\DTO\EmprunterLivreDto;
use App\Domain\Common\Exception\DomainException;
use App\Domain\Emprunt\Emprunt;
use App\Domain\Lecteur\LecteurRepositoryInterface;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Domain\Services\ServiceDisponibilite;
use App\Domain\Services\ServiceGestionEmprunt;

/**
 * Cas d'utilisation pour emprunter un livre.
 *
 * Orchestre le processus d'emprunt d'un livre par un lecteur :
 * - Vérifie l'existence du lecteur
 * - Vérifie la disponibilité du livre
 * - Crée l'emprunt via le service de gestion
 */
final readonly class EmprunterLivreUseCase
{
    /**
     * Constructeur avec injections des dépendances.
     */
    public function __construct(
        private LecteurRepositoryInterface $lecteurRepository,
        private LivreRepositoryInterface $livreRepository,
        private ServiceDisponibilite $serviceDisponibilite,
        private ServiceGestionEmprunt $serviceGestionEmprunt,
    ) {}

    /**
     * Orchestre le processus d'emprunt d'un livre par un lecteur.
     *
     * @param  EmprunterLivreDto  $dto  DTO contenant lecteurId et livreId
     * @return Emprunt L'emprunt créé
     *
     * @throws DomainException Si le lecteur n'existe pas ou si le livre est indisponible
     */
    public function execute(EmprunterLivreDto $dto): Emprunt
    {
        $lecteur = $this->lecteurRepository->findById($dto->lecteurId());
        if (! $lecteur) {
            throw new DomainException('Action impossible : le lecteur spécifié est introuvable.');
        }
        $estDisponible = $this->serviceDisponibilite->verifier($dto->livreId());
        if (! $estDisponible) {
            throw new DomainException("Action impossible : le livre demandé n'est pas disponible actuellement.");
        }

        return $this->serviceGestionEmprunt->emprunter($lecteur, $dto->livreId());
    }
}
