<?php

declare(strict_types=1);

namespace App\Domain\Lecteur;

/**
 * Entité représentant un lecteur de la bibliothèque.
 *
 * Contient les informations personnelles et la date d'adhésion. Fournit des
 * opérations pour renommer le lecteur et changer son email, ainsi que
 * utilitaires pour vérifier l'adhésion.
 */
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

    /**
     * Retourne l'identifiant unique du lecteur.
     *
     * @return string Identifiant
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Retourne le nom du lecteur.
     *
     * @return string Nom
     */
    public function nom(): string
    {
        return $this->nom;
    }

    /**
     * Retourne le prénom du lecteur.
     *
     * @return string Prénom
     */
    public function prenom(): string
    {
        return $this->prenom;
    }

    /**
     * Retourne l'adresse email du lecteur.
     *
     * @return string Email
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * Retourne la date d'adhésion du lecteur.
     *
     * @return \DateTimeImmutable Date d'adhésion
     */
    public function dateAdhesion(): \DateTimeImmutable
    {
        return $this->dateAdhesion;
    }

    /**
     * Indique si le lecteur est actuellement adhérent (date d'adhésion passée).
     *
     * @return bool Vrai si l'adhésion est active
     */
    public function estAdherentActif(): bool
    {
        return $this->dateAdhesion <= new \DateTimeImmutable;
    }

    /**
     * Change l'adresse email du lecteur après validation.
     *
     * @param  string  $email  Nouvelle adresse email
     */
    public function changerEmail(string $email): void
    {
        $this->guardEmail($email);
        $this->email = $email;
    }

    /**
     * Renomme le lecteur (nom et prénom) après validation.
     *
     * @param  string  $nom  Nouveau nom
     * @param  string  $prenom  Nouveau prénom
     */
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
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('L\'adresse e-mail fournie est invalide.');
        }
    }
}
