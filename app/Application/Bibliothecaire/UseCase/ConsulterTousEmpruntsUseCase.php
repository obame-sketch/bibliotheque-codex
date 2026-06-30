<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Domain\Emprunt\EmpruntRepositoryInterface;

/**
 * Cas d'utilisation pour consulter l'historique de tous les emprunts.
 */
final class ConsulterTousEmpruntsUseCase
{
    public function __construct(
        private readonly EmpruntRepositoryInterface $empruntRepository,
    ) {}

    /**
     * Retourne tous les emprunts enregistrés.
     *
     * @return array Tableau des emprunts
     */
    public function execute(): array
    {
        return $this->empruntRepository->findAll();
    }
}
