<?php

declare(strict_types=1);

namespace App\Application\Lecteur\DTO;

/**
 * Data Transfer Object immuable pour l'emprunt d'un livre.
 *
 * Ce DTO transporte les données nécessaires au Use Case EmprunterLivreUseCase :
 * l'identifiant du lecteur et l'identifiant du livre à emprunter.
 */
final readonly class EmprunterLivreDto
{
    /**
     * Constructeur du DTO d'emprunt.
     *
     * @param  string  $lecteurId  Identifiant unique du lecteur effectuant l'emprunt
     * @param  string  $livreId  Identifiant unique du livre à emprunter
     */
    public function __construct(
        private string $lecteurId,
        private string $livreId
    ) {}

    /**
     * Retourne l'identifiant du lecteur.
     *
     * @return string L'ID du lecteur
     */
    public function lecteurId(): string
    {
        return $this->lecteurId;
    }

    /**
     * Retourne l'identifiant du livre.
     *
     * @return string L'ID du livre
     */
    public function livreId(): string
    {
        return $this->livreId;
    }
}
