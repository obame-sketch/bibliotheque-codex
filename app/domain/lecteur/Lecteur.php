<?php

declare(strict_types=1);

namespace App\Domain\Lecteur;

final class Lecteur
{
    public function __construct(
        private readonly string $id,
        private string $nom,
        private string $prenom,
        private string $email,
        private \DateTimeImmutable $dateAdhesion,
    ) {
        $this->guardNonVide($nom, 'nom');
        $this->guardNonVide($prenom, 'prenom');
        $this->guardEmail($email);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function nom(): string
    {
        return $this->nom;
    }

    public function prenom(): string
    {
        return $this->prenom;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function dateAdhesion(): \DateTimeImmutable
    {
        return $this->dateAdhesion;
    }

    public function estAdherentActif(): bool
    {
        return $this->dateAdhesion <= new \DateTimeImmutable();
    }

    public function changerEmail(string $email): void
    {
        $this->guardEmail($email);
        $this->email = $email;
    }

    public function renommer(string $nom, string $prenom): void
    {
        $this->guardNonVide($nom, 'nom');
        $this->guardNonVide($prenom, 'prenom');

        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    private function guardNonVide(string $valeur, string $champ): void
    {
        if ($valeur === '') {
            throw new \InvalidArgumentException(sprintf('Le champ "%s" ne peut pas être vide.', $champ));
        }
    }

    private function guardEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('L\'adresse e-mail fournie est invalide.');
        }
    }
}
