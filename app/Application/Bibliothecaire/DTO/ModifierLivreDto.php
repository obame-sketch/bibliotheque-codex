<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\DTO;

/**
 * DTO contenant les modifications d'un livre (tous les champs sont optionnels).
 */
final readonly class ModifierLivreDto
{
    /**
     * L'utilisation de types nullables permet de ne modifier que les propriétés
     * transmises lors de la requête.
     */
    public function __construct(
        public ?string $titre = null,
        public ?string $auteur = null,
        public ?string $isbn = null,
        public ?\DateTimeImmutable $datePublication = null,
    ) {}
}

?>
