<?php

namespace App\Domain\Entities;

final class Bibliothecaire
{
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private array $livres;

    public function __construct(?int $id, string $nom, string $prenom, string $email, array $livres = [])
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->livres = $livres;
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

    public function getLivres(): array
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): void
    {
        $this->livres[] = $livre;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['nom'],
            $data['prenom'],
            $data['email'],
            $data['livres'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'livres' => $this->livres,
        ];
    }
}
