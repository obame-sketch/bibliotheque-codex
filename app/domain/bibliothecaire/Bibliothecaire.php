<?php

declare(strict_types=1);

namespace App\Domain\Bibliothecaire;

/**
 * Entité de domaine représentant un bibliothécaire.
 *
 * Contient les informations de base d'un bibliothécaire (id, nom, prénom, email)
 * et fournit des opérations simples pour renommer et changer l'email.
 * Les validations (champ non vide, email valide) sont appliquées ici pour
 * garantir l'invariance de l'entité.
 */
final class Bibliothecaire
{
    public function __construct(
        private readonly string $id,
        private string $nom,
        private string $prenom,
        private string $email,
    ) {
        $this->guardNonVide($nom, 'nom');
        $this->guardNonVide($prenom, 'prenom');
        $this->guardEmail($email);
    }

    /**
     * Retourne l'identifiant unique du bibliothécaire.
     *
     * @return string Identifiant unique
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Retourne le nom du bibliothécaire.
     *
     * @return string Nom
     */
    public function nom(): string
    {
        return $this->nom;
    }

    /**
     * Retourne le prénom du bibliothécaire.
     *
     * @return string Prénom
     */
    public function prenom(): string
    {
        return $this->prenom;
    }

    /**
     * Retourne l'adresse email du bibliothécaire.
     *
     * @return string Email
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * Renomme le bibliothécaire (nom et prénom).
     *
     * Valide que les valeurs ne sont pas vides avant d'appliquer la modification.
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

    /**
     * Change l'adresse email du bibliothécaire après validation.
     *
     * @param  string  $email  Nouvelle adresse email
     */
    public function changerEmail(string $email): void
    {
        $this->guardEmail($email);
        $this->email = $email;
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
