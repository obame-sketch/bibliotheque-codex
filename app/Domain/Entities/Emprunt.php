<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class Emprunt
{
    public const STATUT_EN_COURS = 'EN_COURS';
    public const STATUT_TERMINE = 'TERMINE';
    public const STATUT_EN_RETARD = 'EN_RETARD';

    private ?int $id;
    private DateTimeImmutable $dateEmprunt;
    private DateTimeImmutable $dateRetourPrevue;
    private ?DateTimeImmutable $dateRetourEffective;
    private string $statut;
    private int $lecteurId;
    private int $exemplaireId;
    private ?Lecteur $lecteur;
    private ?Exemplaire $exemplaire;

    public function __construct(
        ?int $id,
        DateTimeImmutable $dateEmprunt,
        DateTimeImmutable $dateRetourPrevue,
        ?DateTimeImmutable $dateRetourEffective,
        string $statut,
        int $lecteurId,
        int $exemplaireId,
        ?Lecteur $lecteur = null,
        ?Exemplaire $exemplaire = null
    ) {
        $this->id = $id;
        $this->dateEmprunt = $dateEmprunt;
        $this->dateRetourPrevue = $dateRetourPrevue;
        $this->dateRetourEffective = $dateRetourEffective;
        $this->statut = $statut;
        $this->lecteurId = $lecteurId;
        $this->exemplaireId = $exemplaireId;
        $this->lecteur = $lecteur;
        $this->exemplaire = $exemplaire;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): DateTimeImmutable
    {
        return $this->dateEmprunt;
    }

    public function getDateRetourPrevue(): DateTimeImmutable
    {
        return $this->dateRetourPrevue;
    }

    public function getDateRetourEffective(): ?DateTimeImmutable
    {
        return $this->dateRetourEffective;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getLecteurId(): int
    {
        return $this->lecteurId;
    }

    public function getExemplaireId(): int
    {
        return $this->exemplaireId;
    }

    public function getLecteur(): ?Lecteur
    {
        return $this->lecteur;
    }

    public function getExemplaire(): ?Exemplaire
    {
        return $this->exemplaire;
    }

    public function setDateRetourEffective(DateTimeImmutable $dateRetourEffective): void
    {
        $this->dateRetourEffective = $dateRetourEffective;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            new DateTimeImmutable($data['dateEmprunt']),
            new DateTimeImmutable($data['dateRetourPrevue']),
            isset($data['dateRetourEffective']) && $data['dateRetourEffective'] !== null ? new DateTimeImmutable($data['dateRetourEffective']) : null,
            $data['statut'],
            $data['lecteur_id'],
            $data['exemplaire_id'],
            $data['lecteur'] ?? null,
            $data['exemplaire'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'dateEmprunt' => $this->dateEmprunt->format('Y-m-d'),
            'dateRetourPrevue' => $this->dateRetourPrevue->format('Y-m-d'),
            'dateRetourEffective' => $this->dateRetourEffective?->format('Y-m-d'),
            'statut' => $this->statut,
            'lecteur_id' => $this->lecteurId,
            'exemplaire_id' => $this->exemplaireId,
            'lecteur' => $this->lecteur,
            'exemplaire' => $this->exemplaire,
        ];
    }
}
