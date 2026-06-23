<?php

declare(strict_types=1);

namespace App\Application\Lecteur\UseCase;

use App\Domain\Livre\LivreRepositoryInterface;

/**
 * Cas d'utilisation pour consulter le catalogue de livres.
 * 
 * Retourne la liste complète de tous les livres disponibles dans la bibliothèque.
 */
final readonly class ConsulterCatalogueUseCase
{
    /**
     * Constructeur avec injection du repository de livres.
     */
    public function __construct(
        private LivreRepositoryInterface $livreRepository,
    ) {}

    /**
     * Exécute la consultation du catalogue.
     *
     * @return array<mixed> Liste de tous les livres du catalogue
     */
    public function execute(): array
    {
        return $this->livreRepository->findAll();
    }
}