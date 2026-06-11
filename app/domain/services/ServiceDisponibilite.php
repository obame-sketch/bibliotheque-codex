<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;

final class ServiceDisponibilite
{
    public function __construct(
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    public function verifierDisponibilite(string $livreId): bool
    {
        return count($this->exemplaireRepository->findDisponiblesByLivre($livreId)) > 0;
    }

    public function obtenirExemplaireDisponible(string $livreId): ?Exemplaire
    {
        $exemplaires = $this->exemplaireRepository->findDisponiblesByLivre($livreId);

        return $exemplaires[0] ?? null;
    }
}
