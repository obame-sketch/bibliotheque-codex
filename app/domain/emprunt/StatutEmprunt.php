<?php

declare(strict_types=1);

namespace App\Domain\Emprunt;

enum StatutEmprunt: string
{
    case EN_COURS = 'en_cours';
    case RENDU = 'rendu';
    case EN_RETARD = 'en_retard';
}
