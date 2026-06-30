<?php

declare(strict_types=1);

namespace App\Domain\Livre;

/**
 * Interface de persistance pour l'entité Livre
 *
 * Cette interface définit le contrat de persistance pour les livres.
 * Elle abstrait la couche d'infrastructure (base de données, API, etc.)
 * et permet au domaine de rester indépendant de l'implémentation.
 *
 * Les implémentations concrètes de cette interface sont responsables de :
 * - Sauvegarder les livres (création, mise à jour)
 * - Charger les livres depuis le stockage
 * - Supprimer les livres
 * - Rechercher les livres par différents critères
 */
interface LivreRepositoryInterface
{
    /**
     * Recherche un livre par son identifiant unique
     *
     * @param  string  $id  L'identifiant unique du livre recherché
     * @return Livre|null Le livre trouvé, ou null si introuvable
     */
    public function findById(string $id): ?Livre;

    /**
     * Récupère tous les livres de la bibliothèque
     *
     * @return array Tableau contenant tous les livres (instances de Livre)
     */
    public function findAll(): array;

    /**
     * Recherche les livres selon un mot-clé
     *
     * La recherche s'effectue généralement sur le titre et l'auteur du livre.
     *
     * @param  string  $keyword  Le mot-clé de recherche
     * @return array Tableau des livres correspondant à la recherche
     */
    public function search(string $keyword): array;

    /**
     * Sauvegarde un livre en base de données
     *
     * Cette méthode gère à la fois la création (insertion) et la mise à jour.
     * L'implémentation détermine si le livre existe déjà par son ID.
     *
     * @param  Livre  $livre  L'instance du livre à sauvegarder
     */
    public function save(Livre $livre): ?Livre;

    /**
     * Supprime un livre de la bibliothèque
     *
     * @param  string  $id  L'identifiant unique du livre à supprimer
     */
    public function delete(string $id): void;

    /**
     * Recherche un livre par son ISBN (International Standard Book Number)
     *
     * L'ISBN est un identifiant unique à l'échelle mondiale pour un livre.
     * Cette méthode permet de retrouver rapidement un livre même si son ID local est inconnu.
     *
     * @param  string  $isbn  L'ISBN du livre recherché
     * @return Livre|null Le livre trouvé, ou null si aucun livre avec cet ISBN n'existe
     */
    public function findByIsbn(string $isbn): ?Livre;
}
