<?php

namespace App\Domain\Entities;

use DateTimeImmutable;

final class Exemplaire
{
    public const STATUT_DISPONIBLE = 'DISPONIBLE';
    public const STATUT_EMPRUNTE = 'EMPRUNTE';
    public const STATUT_PERDU = 'PERDU';

    private ?int $id;
    private string $codeBarre;
    private string $statut;
    private int $livreId;
    private ?Livre $livre;
    private array $emprunts;

    public function __construct(
        ?int $id,
        string $codeBarre,
        string $statut,
        int $livreId,
        ?Livre $livre = null,
        array $emprunts = []
    ) {
        $this->id = $id;
        $this->codeBarre = $codeBarre;
        $this->statut = $statut;
        $this->livreId = $livreId;
        $this->livre = $livre;
        $this->emprunts = $emprunts;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeBarre(): string
    {
        return $this->codeBarre;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getLivreId(): int
    {
        return $this->livreId;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function getEmprunts(): array
    {
        return $this->emprunts;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function addEmprunt(Emprunt $emprunt): void
    {
        $this->emprunts[] = $emprunt;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['codeBarre'],
            $data['statut'],
            $data['livre_id'],
            $data['livre'] ?? null,
            $data['emprunts'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'codeBarre' => $this->codeBarre,
            'statut' => $this->statut,
            'livre_id' => $this->livreId,
            'livre' => $this->livre,
            'emprunts' => $this->emprunts,
        ];
    }
}
