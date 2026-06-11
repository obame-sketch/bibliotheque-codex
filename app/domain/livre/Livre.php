<?php

declare(strict_types=1);

namespace App\Domain\Livre;

final class Livre
{
    public function __construct(
        private readonly string $id,
        private string $titre,
        private string $auteur,
        private string $isbn,
        private \DateTimeImmutable $datePublication,
    ) {
        $this->guardNonVide($titre, 'titre');
        $this->guardNonVide($auteur, 'auteur');
        $this->guardNonVide($isbn, 'isbn');
    }

    public function id(): string
    {
        return $this->id;
    }

    public function titre(): string
    {
        return $this->titre;
    }

    public function auteur(): string
    {
        return $this->auteur;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function datePublication(): \DateTimeImmutable
    {
        return $this->datePublication;
    }

    public function estPublie(): bool
    {
        return $this->datePublication <= new \DateTimeImmutable;
    }

    public function mettreAJourTitre(string $titre): void
    {
        $this->guardNonVide($titre, 'titre');
        $this->titre = $titre;
    }

    private function guardNonVide(string $valeur, string $champ): void
    {
        if ($valeur === '') {
            throw new \InvalidArgumentException(sprintf('Le champ "%s" ne peut pas être vide.', $champ));
        }
    }
}
