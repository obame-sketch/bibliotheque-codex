<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Lecteur\Lecteur;

final class ServiceGestionEmprunt
{
    public function __construct(
        private readonly EmpruntRepositoryInterface $empruntRepository,
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    public function enregistrerEmprunt(Lecteur $lecteur, Exemplaire $exemplaire): Emprunt
    {
        $exemplaire->emprunter();
        $this->exemplaireRepository->save($exemplaire);

        $dateEmprunt = new \DateTimeImmutable;
        $dateRetourPrevue = $dateEmprunt->modify('+21 days');

        $emprunt = new Emprunt(
            id: uniqid('', true),
            lecteur: $lecteur,
            exemplaire: $exemplaire,
            dateEmprunt: $dateEmprunt,
            dateRetourPrevue: $dateRetourPrevue,
        );

        $this->empruntRepository->save($emprunt);

        return $emprunt;
    }

    public function enregistrerRetour(Emprunt $emprunt): void
    {
        $emprunt->cloturer(new \DateTimeImmutable);
        $emprunt->exemplaire()->retourner();

        $this->exemplaireRepository->save($emprunt->exemplaire());
        $this->empruntRepository->save($emprunt);
    }

    public function calculerRetard(Emprunt $emprunt): int
    {
        return $emprunt->joursDeRetard();
    }
}
