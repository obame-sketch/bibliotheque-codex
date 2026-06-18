<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Application\Bibliothecaire\DTO\ModifierLivreDto;
use App\Domain\Livre\LivreRepositoryInterface;

/**
 * Cas d'utilisation pour la modification d'un livre existant.
 */
final class ModifierLivreUseCase
{
    public function __construct(
        private readonly LivreRepositoryInterface $livreRepository,
    ) {}

    /**
     * Met à jour les champs non nuls du livre spécifié.
     *
     * @param  string  $livreId  Identifiant du livre à modifier
     * @param  ModifierLivreDto  $dto  Données de modification du livre
     */
    public function execute(string $livreId, ModifierLivreDto $dto): void
    {
        // Étape 1 : Récupérer le livre à modifier
        $livre = $this->livreRepository->findById($livreId);

        if ($livre === null) {
            throw new \RuntimeException(sprintf('Livre introuvable pour l\'ID %s.', $livreId));
        }

        // Étape 2 : Mettre à jour uniquement les champs fournis (approche partielle)
        // Les champs null ne modifient pas le livre existant
        if ($dto->titre !== null) {
            $livre->mettreAJourTitre($dto->titre);
        }

        if ($dto->auteur !== null) {
            $livre->changerAuteur($dto->auteur);
        }

        if ($dto->isbn !== null) {
            $livre->changerIsbn($dto->isbn);
        }

        if ($dto->datePublication !== null) {
            $livre->changerDatePublication($dto->datePublication);
        }

        // Étape 3 : Persister les modifications en base de données
        $this->livreRepository->save($livre);
    }
}
