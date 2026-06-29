<?php

declare(strict_types=1);

namespace App\Application\Lecteur\UseCase;

use App\Domain\Livre\LivreRepositoryInterface;

/**
 * Cas d'utilisation pour rechercher un livre dans le catalogue.
 *
 * Permet à un lecteur de rechercher des livres par mot-clé.
 */
final readonly class RechercherLivreUseCase
{
    /**
     * Constructeur avec injection du repository de livres.
     */
    public function __construct(
        private LivreRepositoryInterface $livreRepository,
    ) {}

    /**
     * Exécute la recherche de livres par mot-clé.
     *
     * @param string $keyword Le mot-clé de recherche
     * @return array<mixed>   Résultats de recherche
     */
    public function execute(string $keyword): array
    {
        $cleanKeyword = trim($keyword);
        if (empty($cleanKeyword)) {
            return [];
        }

        return $this->livreRepository->search($cleanKeyword);
    }
}
