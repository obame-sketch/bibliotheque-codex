<?php

declare(strict_types=1);

namespace App\Application\Lecteur\UseCase;

use App\Domain\Emprunt\EmpruntRepositoryInterface;

/**
 * Cas d'utilisation pour voir les emprunts d'un lecteur.
 * ph
 * Retourne l'historique ou la liste des emprunts associés à un lecteur.
 */
final readonly class VoirMesEmpruntsUseCase
{
    /**
     * Constructeur avec injection du repository d'emprunts.
     */
    public function __construct(
        private EmpruntRepositoryInterface $empruntRepository,
    ) {}

    /**
     * Exécute la récupération des emprunts du lecteur.
     *
     * @param  string  $lecteurId  L'identifiant du lecteur
     * @return array<mixed> Historique ou liste des emprunts du lecteur
     */
    public function execute(string $lecteurId): array
    {
        return $this->empruntRepository->findEnCoursByLecteur($lecteurId);
    }
}
