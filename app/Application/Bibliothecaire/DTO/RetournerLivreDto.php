<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\DTO;

/**
 * DTO pour encapsuler les informations nécessaires au retour d'un emprunt.
 *
 * Permet au Use Case RetournerLivre de recevoir l'identifiant de l'emprunt
 * à clôturer.
 */
final readonly class RetournerLivreDto
{
    /**
     * @param  string  $empruntId  Identifiant de l'emprunt à retourner
     */
    public function __construct(
        public string $empruntId,
    ) {}
}

?>
