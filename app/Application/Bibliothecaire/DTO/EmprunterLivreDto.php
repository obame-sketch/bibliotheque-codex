<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\DTO;

/**
 * DTO pour encapsuler les informations nécessaires à l'emprunt d'un livre.
 *
 * Permet au Use Case EmprunterLivre de recevoir les données du client
 * (lecteur et livre) de manière structurée.
 */
final readonly class EmprunterLivreDto
{
    /**
     * @param  string  $lecteurId  Identifiant du lecteur effectuant l'emprunt
     * @param  string  $livreId  Identifiant du livre à emprunter
     */
    public function __construct(
        public string $lecteurId,
        public string $livreId,
    ) {}
}
