<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\DTO;

/**
 * DTO pour encapsuler les informations nécessaires à la création d'un livre.
 */
final readonly class AjouterLivreDto
{
    /**
     * @param  string  $titre  Titre du livre
     * @param  string  $auteur  Nom de l'auteur
     * @param  string  $isbn  Code ISBN
     * @param  \DateTimeImmutable  $datePublication  Date de parution
     * @param  int  $nombreExemplaires  Nombre de copies à ajouter initialement
     * @param  string  $categorie  Catégorie du livre
     */
    public function __construct(
        public string $titre,
        public string $auteur,
        public string $isbn,
        public \DateTimeImmutable $datePublication,
        public int $nombreExemplaires,
        public string $categorie = '',
    ) {}
}

?>

