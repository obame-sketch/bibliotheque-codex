<?php

declare(strict_types=1);

namespace App\Domain\Bibliotheque;

/**
 * Entité représentant un établissement de bibliothèque.
 *
 * Une bibliothèque est l'institution propriétaire de la collection de livres.
 * Elle peut disposer d'une adresse et d'un nom officiel.
 */
final class Bibliotheque
{
    public function __construct(
        private string $nom,
        private ?string $adresse = null,
        private ?string $id = null,
    ) {
        $this->guardNonVide($nom, 'nom');
    }

    public function id(): string
    {
        return $this->id;
    }

    public function nom(): string
    {
        return $this->nom;
    }

    public function adresse(): ?string
    {
        return $this->adresse;
    }

    public function changerNom(string $nom): void
    {
        $this->guardNonVide($nom, 'nom');
        $this->nom = $nom;
    }

    public function changerAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    private function guardNonVide(string $valeur, string $champ): void
    {
        if ($valeur === '') {
            throw new \InvalidArgumentException(sprintf('Le champ "%s" ne peut pas être vide.', $champ));
        }
    }
}
