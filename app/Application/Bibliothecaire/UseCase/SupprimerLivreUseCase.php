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
     * @param  string  $livreId  Identifiant du livre à supprimer
     */
    public function execute(string $livreId): void
    {
        $livre = $this->livreRepository->findById($livreId);

        if ($livre === null) {
            throw new \RuntimeException(sprintf('Livre introuvable pour l\'ID %s.', $livreId));
        }
        $exemplaires = $this->exemplaireRepository->findByLivre($livreId);
        foreach ($exemplaires as $exemplaire) {
            if ($exemplaire->statut() !== StatutExemplaire::PERDU) {
                throw new \RuntimeException('Le livre ne peut pas être supprimé tant qu\'un exemplaire est disponible ou emprunté.');
            }
        }
        $this->livreRepository->delete($livreId);
    }
}
