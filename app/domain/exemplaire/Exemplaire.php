<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

final class Exemplaire
{
    public function __construct(
        private readonly string $id,
        private string $codeBarre,
        private StatutExemplaire $statut,
    ) {
        $this->guardNonVide($codeBarre, 'codeBarre');
    }

    public function id(): string
    {
        return $this->id;
    }

    public function codeBarre(): string
    {
        return $this->codeBarre;
    }

    public function statut(): StatutExemplaire
    {
        return $this->statut;
    }

    public function emprunter(): void
    {
        if (! $this->estDisponible()) {
            throw new \DomainException('Cet exemplaire n\'est pas disponible pour un emprunt.');
        }

        $this->statut = StatutExemplaire::EMPRUNTE;
    }

    public function retourner(): void
    {
        if ($this->statut === StatutExemplaire::PERDU) {
            throw new \DomainException('Un exemplaire perdu ne peut pas être retourné.');
        }

        $this->statut = StatutExemplaire::DISPONIBLE;
    }

    public function estDisponible(): bool
    {
        return $this->statut === StatutExemplaire::DISPONIBLE;
    }

    private function guardNonVide(string $valeur, string $champ): void
    {
        if ($valeur === '') {
            throw new \InvalidArgumentException(sprintf('Le champ "%s" ne peut pas être vide.', $champ));
        }
    }
}
