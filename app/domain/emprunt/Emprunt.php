<?php

declare(strict_types=1);

namespace App\Domain\Emprunt;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Lecteur\Lecteur;

/**
 * Entité représentant un emprunt d'un exemplaire par un lecteur.
 *
 * Elle encapsule les dates d'emprunt, de retour prévue et effective, ainsi que
 * le statut. Fournit la logique métier pour clôturer un emprunt et calculer
 * les retards.
 */
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

    /**
     * Retourne l'identifiant unique de l'emprunt.
     *
     * @return string Identifiant
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Retourne le lecteur associé à cet emprunt.
     *
     * @return Lecteur Instance du lecteur
     */
    public function getLecteur(): Lecteur
    {
        return $this->lecteur;
    }

    /**
     * Retourne l'exemplaire emprunté.
     *
     * @return Exemplaire Instance de l'exemplaire
     */
    public function getExemplaire(): Exemplaire
    {
        return $this->exemplaire;
    }

    /**
     * Retourne la date d'emprunt.
     *
     * @return \DateTimeImmutable Date d'emprunt
     */
    public function dateEmprunt(): \DateTimeImmutable
    {
        return $this->dateEmprunt;
    }

    /**
     * Retourne la date de retour prévue pour l'emprunt.
     *
     * @return \DateTimeImmutable Date prévue de retour
     */
    public function dateRetourPrevue(): \DateTimeImmutable
    {
        return $this->dateRetourPrevue;
    }

    /**
     * Retourne la date de retour effective si le livre a été rendu.
     *
     * @return ?\DateTimeImmutable Date de retour effective ou null
     */
    public function dateRetourEffective(): ?\DateTimeImmutable
    {
        return $this->dateRetourEffective;
    }

    /**
     * Retourne le statut courant de l'emprunt. Si l'emprunt est en cours et
     * la date de retour prévue est dépassée, renvoie automatiquement
     * StatutEmprunt::EN_RETARD.
     *
     * @return StatutEmprunt Statut calculé de l'emprunt
     */
    public function statut(): StatutEmprunt
    {
        if ($this->statut === StatutEmprunt::EN_COURS && $this->estEnRetard()) {
            return StatutEmprunt::EN_RETARD;
        }

        return $this->statut;
    }

    /**
     * Clôture l'emprunt en enregistrant la date de retour effective et en
     * mettant à jour le statut.
     *
     * @param  \DateTimeImmutable  $dateRetour  Date de retour effective
     *
     * @throws \DomainException Si l'emprunt est déjà clôturé
     * @throws \InvalidArgumentException Si la date de retour est antérieure à la date d'emprunt
     */
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

    /**
     * Indique si l'emprunt est en retard.
     *
     * Si la date de retour effective est présente, compare avec la date prévue,
     * sinon compare la date actuelle.
     *
     * @return bool True si en retard
     */
    public function estEnRetard(): bool
    {
        if ($this->dateRetourEffective !== null) {
            return $this->dateRetourEffective > $this->dateRetourPrevue;
        }

        return new \DateTimeImmutable > $this->dateRetourPrevue;
    }

    /**
     * Calcule le nombre de jours de retard pour l'emprunt.
     *
     * @return int Nombre de jours de retard (0 si pas de retard)
     */
    public function joursDeRetard(): int
    {
        if (! $this->estEnRetard()) {
            return 0;
        }

        $reference = $this->dateRetourEffective ?? new \DateTimeImmutable;

        return (int) $this->dateRetourPrevue->diff($reference)->days;
    }
}
