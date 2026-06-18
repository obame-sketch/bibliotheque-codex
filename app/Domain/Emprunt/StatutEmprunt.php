<?php

declare(strict_types=1);

namespace App\Domain\Emprunt;

/**
 * Enumération des statuts d'un emprunt.
 *
 * Utilisé pour représenter le cycle de vie d'un emprunt : en cours, rendu ou en retard.
 */
enum StatutEmprunt: string
{
    case EN_COURS = 'en_cours';
    case RENDU = 'rendu';
    case EN_RETARD = 'en_retard';
}
