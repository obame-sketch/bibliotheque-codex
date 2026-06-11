<?php

declare(strict_types=1);

namespace App\Domain\Emprunt;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Lecteur\Lecteur;

final class Emprunt
{
    private ?\DateTimeImmutable $dateRetourEffective;

    private StatutEmprunt $statut;

    public function __construct(
        private readonly string $id,
        private readonly Lecteur $lecteur,
        private readonly Exemplaire $exemplaire,
        private readonly \DateTimeImmutable $dateEmprunt,
        private readonly \DateTimeImmutable $dateRetourPrevue,
        ?\DateTimeImmutable $dateRetourEffective = null,
        ?StatutEmprunt $statut = null,
    ) {
        $this->dateRetourEffective = $dateRetourEffective;
        $this->statut = $statut ?? StatutEmprunt::EN_COURS;

        if ($dateRetourPrevue < $dateEmprunt) {
            throw new \InvalidArgumentException('La date de retour prévue doit être postérieure à la date d\'emprunt.');
        }
    }

    public function id(): string
    {
        return $this->id;
    }

    public function lecteur(): Lecteur
    {
        return $this->lecteur;
    }

    public function exemplaire(): Exemplaire
    {
        return $this->exemplaire;
    }

    public function dateEmprunt(): \DateTimeImmutable
    {
        return $this->dateEmprunt;
    }

    public function dateRetourPrevue(): \DateTimeImmutable
    {
        return $this->dateRetourPrevue;
    }

    public function dateRetourEffective(): ?\DateTimeImmutable
    {
        return $this->dateRetourEffective;
    }

    public function statut(): StatutEmprunt
    {
        if ($this->statut === StatutEmprunt::EN_COURS && $this->estEnRetard()) {
            return StatutEmprunt::EN_RETARD;
        }

        return $this->statut;
    }

    public function cloturer(\DateTimeImmutable $dateRetour): void
    {
        if ($this->statut !== StatutEmprunt::EN_COURS && $this->statut !== StatutEmprunt::EN_RETARD) {
            throw new \DomainException('Cet emprunt est déjà clôturé.');
        }

        if ($dateRetour < $this->dateEmprunt) {
            throw new \InvalidArgumentException('La date de retour effective ne peut pas être antérieure à la date d\'emprunt.');
        }

        $this->dateRetourEffective = $dateRetour;
        $this->statut = StatutEmprunt::RENDU;
    }

    public function estEnRetard(): bool
    {
        if ($this->dateRetourEffective !== null) {
            return $this->dateRetourEffective > $this->dateRetourPrevue;
        }

        return new \DateTimeImmutable > $this->dateRetourPrevue;
    }

    public function joursDeRetard(): int
    {
        if (! $this->estEnRetard()) {
            return 0;
        }

        $reference = $this->dateRetourEffective ?? new \DateTimeImmutable;

        return (int) $this->dateRetourPrevue->diff($reference)->days;
    }
}
