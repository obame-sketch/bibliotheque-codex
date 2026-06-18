<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Livre\LivreRepositoryInterface;

/**
 * Cas d'utilisation pour supprimer un livre si la suppression est sûre.
 */
final class SupprimerLivreUseCase
{
    public function __construct(
        private readonly LivreRepositoryInterface $livreRepository,
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    /**
     * Supprime un livre après vérification de l'absence d'exemplaires disponibles.
     *
     * @param string $livreId Identifiant du livre à supprimer
     */
    public function execute(string $livreId): void
    {
        // Étape 1 : Vérifier que le livre existe
        $livre = $this->livreRepository->findById($livreId);

        if ($livre === null) {
            throw new \RuntimeException(sprintf('Livre introuvable pour l\'ID %s.', $livreId));
        }

        // Étape 2 : Récupérer tous les exemplaires du livre
        $exemplaires = $this->exemplaireRepository->findByLivre($livreId);

        // Étape 3 : Vérifier que TOUS les exemplaires sont perdus avant suppression
        // Un livre ne peut être supprimé que si tous ses exemplaires sont marqués comme PERDU
        // (aucun exemplaire disponible ou emprunté ne peut subsister)
        foreach ($exemplaires as $exemplaire) {
            if ($exemplaire->statut() !== StatutExemplaire::PERDU) {
                throw new \RuntimeException('Le livre ne peut pas être supprimé tant qu\'un exemplaire est disponible ou emprunté.');
            }
        }

        // Étape 4 : Supprimer définitivement le livre et tous ses exemplaires
        $this->livreRepository->delete($livreId);
    }
}
