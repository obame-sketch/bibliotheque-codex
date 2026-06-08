<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

enum StatutExemplaire: string
{
    case DISPONIBLE = 'disponible';
    case EMPRUNTE = 'emprunte';
    case PERDU = 'perdu';
}
