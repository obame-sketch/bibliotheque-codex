<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class Livre
{
    private ?int $id;
    private string $titre;
    private string $auteur;
    private string $isbn;
    private DateTimeImmutable $datePublication;
    private int $bibliothecaireId;
    private ?Bibliothecaire $bibliothecaire;
    private array $exemplaires;

    public function __construct(
        ?int $id,
        string $titre,
        string $auteur,
        string $isbn,
        DateTimeImmutable $datePublication,
        int $bibliothecaireId,
        ?Bibliothecaire $bibliothecaire = null,
        array $exemplaires = []
    ) {
        $this->id = $id;
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->isbn = $isbn;
        $this->datePublication = $datePublication;
        $this->bibliothecaireId = $bibliothecaireId;
        $this->bibliothecaire = $bibliothecaire;
        $this->exemplaires = $exemplaires;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getDatePublication(): DateTimeImmutable
    {
        return $this->datePublication;
    }

    public function getBibliothecaireId(): int
    {
        return $this->bibliothecaireId;
    }

    public function getBibliothecaire(): ?Bibliothecaire
    {
        return $this->bibliothecaire;
    }

    public function getExemplaires(): array
    {
        return $this->exemplaires;
    }

    public function addExemplaire(Exemplaire $exemplaire): void
    {
        $this->exemplaires[] = $exemplaire;
    }

    public function withBibliothecaire(Bibliothecaire $bibliothecaire): self
    {
        $clone = clone $this;
        $clone->bibliothecaire = $bibliothecaire;

        return $clone;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['titre'],
            $data['auteur'],
            $data['isbn'],
            new DateTimeImmutable($data['datePublication']),
            $data['bibliothecaire_id'],
            $data['bibliothecaire'] ?? null,
            $data['exemplaires'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'auteur' => $this->auteur,
            'isbn' => $this->isbn,
            'datePublication' => $this->datePublication->format('Y-m-d'),
            'bibliothecaire_id' => $this->bibliothecaireId,
            'bibliothecaire' => $this->bibliothecaire,
            'exemplaires' => $this->exemplaires,
        ];
    }
}
