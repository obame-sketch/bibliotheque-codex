<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

/**
 * Enumération des statuts possibles d'un exemplaire.
 *
 * UTILISATION : permet de vérifier et modifier l'état d'un exemplaire
 * (disponible, emprunté, perdu).
 */
enum StatutExemplaire: string
{
    case DISPONIBLE = 'disponible';
    case EMPRUNTE = 'emprunte';
    case PERDU = 'perdu';
}
