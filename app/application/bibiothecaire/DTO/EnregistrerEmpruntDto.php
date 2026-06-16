<?php

declare(strict_types=1);

namespace App\Application\DTO\Emprunt;

/**
 * DTO pour associer un lecteur à un exemplaire précis lors d'un emprunt.
 */
final readonly class EnregistrerEmpruntDto
{
    public function __construct(
        public string $lecteurId,
        public string $exemplaireId,
    ) {}
}

?>
