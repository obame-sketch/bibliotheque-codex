<?php

declare(strict_types=1);

namespace App\Domain\Livre;

use App\Domain\Bibliothecaire\Bibliothecaire;
use App\Domain\Bibliotheque\Bibliotheque;

/**
 * Entité de domaine représentant un Livre dans la bibliothèque numérique.
 *
 * Cette classe encapsule les informations essentielles d'un livre :
 * - Identifiant unique
 * - Titre et auteur
 * - ISBN (International Standard Book Number) pour l'identification unique
 * - Date de publication
 * - Bibliothèque propriétaire (établissement)
 *
 * Responsabilités :
 * - Valider les données du livre (champs non vides)
 * - Fournir des accesseurs pour consulter les propriétés
 * - Fournir des mutateurs pour modifier les propriétés de manière contrôlée
 * - Déterminer le statut de publication du livre
 */
final class Livre
{
    private ?Bibliotheque $bibliotheque = null;

    private ?Bibliothecaire $bibliothecaire = null;

    public function __construct(
        private string $titre,
        private string $auteur,
        private string $isbn,
        private \DateTimeImmutable $datePublication,
        private string $categorie = '',
        private ?string $id = null
    ) {
        $this->guardNonVide($titre, 'titre');
        $this->guardNonVide($auteur, 'auteur');
        $this->guardNonVide($isbn, 'isbn');
    }

    public function bibliotheque(): ?Bibliotheque
    {
        return $this->bibliotheque;
    }

    public function setBibliotheque(Bibliotheque $bibliotheque): void
    {
        $this->bibliotheque = $bibliotheque;
    }

    public function bibliothecaire(): ?Bibliothecaire
    {
        return $this->bibliothecaire;
    }

    public function setBibliothecaire(Bibliothecaire $bibliothecaire): void
    {
        $this->bibliothecaire = $bibliothecaire;
    }

    /**
     * Récupère l'identifiant unique du livre
     *
     * @return string|null L'identifiant du livre
     */
    public function id(): ?string
    {
        return $this->id;
    }

    /**
     * Récupère le titre du livre
     *
     * @return string Le titre du livre
     */
    public function titre(): string
    {
        return $this->titre;
    }

    /**
     * Récupère l'auteur du livre
     *
     * @return string Le nom de l'auteur du livre
     */
    public function auteur(): string
    {
        return $this->auteur;
    }

    /**
     * Récupère l'ISBN du livre
     *
     * @return string L'ISBN (International Standard Book Number) du livre
     */
    public function isbn(): string
    {
        return $this->isbn;
    }

    /**
     * Récupère la date de publication du livre
     *
     * @return \DateTimeImmutable La date de publication du livre
     */
    public function datePublication(): \DateTimeImmutable
    {
        return $this->datePublication;
    }

    /**
     * Récupère la catégorie du livre
     */
    public function categorie(): string
    {
        return $this->categorie;
    }

    /**
     * Détermine si le livre a déjà été publié
     *
     * Un livre est considéré comme publié si sa date de publication est inférieure ou égale à la date actuelle.
     *
     * @return bool Vrai si le livre est publié, Faux sinon (date future)
     */
    public function estPublie(): bool
    {
        return $this->datePublication <= new \DateTimeImmutable;
    }

    /**
     * Met à jour le titre du livre
     *
     * Permet de corriger ou modifier le titre du livre après sa création.
     * Valide que le nouveau titre n'est pas vide.
     *
     * @param  string  $titre  Le nouveau titre (ne doit pas être vide)
     *
     * @throws \InvalidArgumentException Si le titre est vide
     */
    public function mettreAJourTitre(string $titre): void
    {
        $this->guardNonVide($titre, 'titre');
        $this->titre = $titre;
    }

    /**
     * Change l'auteur du livre
     *
     * Permet de corriger ou modifier l'auteur du livre après sa création.
     * Valide que le nouvel auteur n'est pas vide.
     *
     * @param  string  $auteur  Le nouvel auteur (ne doit pas être vide)
     *
     * @throws \InvalidArgumentException Si l'auteur est vide
     */
    public function changerAuteur(string $auteur): void
    {
        $this->guardNonVide($auteur, 'auteur');
        $this->auteur = $auteur;
    }

    /**
     * Change l'ISBN du livre
     *
     * Permet de corriger ou modifier l'ISBN du livre après sa création.
     * Valide que le nouvel ISBN n'est pas vide.
     *
     * @param  string  $isbn  Le nouvel ISBN (ne doit pas être vide)
     *
     * @throws \InvalidArgumentException Si l'ISBN est vide
     */
    public function changerIsbn(string $isbn): void
    {
        $this->guardNonVide($isbn, 'isbn');
        $this->isbn = $isbn;
    }

    /**
     * Change la date de publication du livre
     *
     * @param  \DateTimeImmutable  $datePublication  La nouvelle date de publication
     */
    public function changerDatePublication(\DateTimeImmutable $datePublication): void
    {
        $this->datePublication = $datePublication;
    }

    /**
     * Valide qu'un champ texte n'est pas vide
     *
     * Méthode utilitaire de validation utilisée dans le constructeur et les mutateurs.
     * Garantit que les champs obligatoires contiennent toujours une valeur valide.
     *
     * @param  string  $valeur  La valeur à valider
     * @param  string  $champ  Le nom du champ (utilisé pour le message d'erreur)
     *
     * @throws \InvalidArgumentException Si la valeur est une chaîne vide
     */
    private function guardNonVide(string $valeur, string $champ): void
    {
        if ($valeur === '') {
            throw new \InvalidArgumentException(sprintf('Le champ "%s" ne peut pas être vide.', $champ));
        }
    }
}
