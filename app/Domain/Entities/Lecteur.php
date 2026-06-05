<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class Lecteur
{
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private DateTimeImmutable $dateAdhesion;
    private array $emprunts;

    public function __construct(
        ?int $id,
        string $nom,
        string $prenom,
        string $email,
        DateTimeImmutable $dateAdhesion,
        array $emprunts = []
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->dateAdhesion = $dateAdhesion;
        $this->emprunts = $emprunts;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDateAdhesion(): DateTimeImmutable
    {
        return $this->dateAdhesion;
    }

    public function getEmprunts(): array
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): void
    {
        $this->emprunts[] = $emprunt;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['nom'],
            $data['prenom'],
            $data['email'],
            new DateTimeImmutable($data['dateAdhesion']),
            $data['emprunts'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'dateAdhesion' => $this->dateAdhesion->format('Y-m-d'),
            'emprunts' => $this->emprunts,
        ];
    }
}
