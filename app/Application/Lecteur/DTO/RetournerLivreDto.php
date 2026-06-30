<?php

declare(strict_types=1);

namespace App\Application\Lecteur\DTO;

/**
 * Data Transfer Object immuable pour le retour d'un livre.
 *
 * Ce DTO transporte l'identifiant de l'emprunt à retourner.
 */
final readonly class RetournerLivreDto
{
    /**
     * Constructeur du DTO de retour.
     *
     * @param  string  $empruntId  Identifiant unique de l'emprunt à retourner
     */
    public function __construct(
        private string $empruntId
    ) {}

    /**
     * Retourne l'identifiant de l'emprunt.
     *
     * @return string L'ID de l'emprunt
     */
    public function empruntId(): string
    {
        return $this->empruntId;
    }
}
