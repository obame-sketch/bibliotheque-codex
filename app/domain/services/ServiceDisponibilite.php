<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;

final class ServiceDisponibilite
{
    public function __construct(
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {
    }

    public function verifierDisponibilite(string $livreId): bool
    {
        return count($this->exemplaireRepository->findDisponiblesByLivreId($livreId)) > 0;
    }

    public function obtenirExemplaireDisponible(string $livreId): Exemplaire
    {
        $exemplaires = $this->exemplaireRepository->findDisponiblesByLivreId($livreId);

        if (empty($exemplaires)) {
            throw new \DomainException('Aucun exemplaire disponible pour ce livre.');
        }

        return $exemplaires[0];
    }
}
