<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Application\Bibliothecaire\DTO\EmprunterLivreDto;
use App\Domain\Emprunt\Emprunt;
use App\Domain\Lecteur\LecteurRepositoryInterface;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Domain\Services\ServiceDisponibilite;
use App\Domain\Services\ServiceGestionEmprunt;

/**
 * Cas d'utilisation pour l'emprunt d'un livre.
 *
 * Orchestre les vérifications (lecteur, livre, disponibilité) et crée
 * un nouvel emprunt en trouvant automatiquement un exemplaire disponible.
 */
final class EmprunterLivreUseCase
{
    public function __construct(
        private readonly LecteurRepositoryInterface $lecteurRepository,
        private readonly LivreRepositoryInterface $livreRepository,
        private readonly ServiceDisponibilite $serviceDisponibilite,
        private readonly ServiceGestionEmprunt $serviceGestionEmprunt,
    ) {}

    /**
     * Exécute l'emprunt d'un livre pour un lecteur donné.
     *
     * @param  EmprunterLivreDto  $dto  DTO contenant lecteurId et livreId
     * @return Emprunt L'emprunt créé
     *
     * @throws \RuntimeException si le lecteur, le livre ou aucun exemplaire n'est trouvé
     */
    public function execute(EmprunterLivreDto $dto): Emprunt
    {
        // Étape 1 : Vérifier que le lecteur existe
        $lecteur = $this->lecteurRepository->findById($dto->lecteurId);

        if ($lecteur === null) {
            throw new \RuntimeException(sprintf('Lecteur introuvable pour l\'ID %s.', $dto->lecteurId));
        }

        // Étape 2 : Vérifier que le livre existe
        $livre = $this->livreRepository->findById($dto->livreId);

        if ($livre === null) {
            throw new \RuntimeException(sprintf('Livre introuvable pour l\'ID %s.', $dto->livreId));
        }

        // Étape 3 : Vérifier la disponibilité d'au moins un exemplaire
        if (! $this->serviceDisponibilite->verifierDisponibilite($dto->livreId)) {
            throw new \RuntimeException(sprintf('Aucun exemplaire disponible pour le livre %s.', $dto->livreId));
        }

        // Étape 4 : Récupérer un exemplaire disponible
        $exemplaire = $this->serviceDisponibilite->obtenirExemplaireDisponible($dto->livreId);

        // Étape 5 : Enregistrer l'emprunt via le service de gestion
        // Le service met à jour le statut de l'exemplaire et crée l'enregistrement
        return $this->serviceGestionEmprunt->enregistrerEmprunt($lecteur, $exemplaire);
    }
}
