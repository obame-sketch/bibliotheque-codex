<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Lecteur\Lecteur;

/**
 * Service de domaine responsable de l'enregistrement des emprunts et retours.
 *
 * Cette classe orchestre les interactions entre les repositories et les
 * entités (exemplaire, emprunt) pour :
 * - enregistrer un emprunt (marquer l'exemplaire comme emprunté et créer l'emprunt)
 * - enregistrer un retour (clôturer l'emprunt et remettre l'exemplaire disponible)
 * - calculer le retard d'un emprunt
 */
final class ServiceGestionEmprunt
{
    public function __construct(
        private readonly EmpruntRepositoryInterface $empruntRepository,
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    /**
     * Enregistre un nouvel emprunt pour un lecteur et un exemplaire donnés.
     *
     * Cette méthode marque l'exemplaire comme emprunté, persiste l'exemplaire,
     * crée l'entité Emprunt et la sauvegarde via le dépôt.
     *
     * @param  Lecteur  $lecteur  Lecteur effectuant l'emprunt
     * @param  Exemplaire  $exemplaire  Exemplaire emprunté
     * @return Emprunt L'entité Emprunt créée
     */
    public function enregistrerEmprunt(Lecteur $lecteur, Exemplaire $exemplaire): Emprunt
    {
        $exemplaire->emprunter();
        $exemplaireSauvegarder = $this->exemplaireRepository->save($exemplaire);

        $dateEmprunt = new \DateTimeImmutable;
        $dateRetourPrevue = $dateEmprunt->modify('+21 days');

        $emprunt = new Emprunt(
            id: uniqid('', true),
            lecteur: $lecteur,
            exemplaire: $exemplaireSauvegarder,
            dateEmprunt: $dateEmprunt,
            dateRetourPrevue: $dateRetourPrevue,
        );

        $empruntSauvegarder = $this->empruntRepository->save($emprunt);

        return $empruntSauvegarder;
    }

    public function emprunter(Lecteur $lecteur, string $livreId): Emprunt
    {
        $exemplaire = $this->exemplaireRepository->findDisponiblesByLivre($livreId)[0] ?? null;
        return $this->enregistrerEmprunt($lecteur, $exemplaire);
    }

    /**
     * Enregistre le retour d'un emprunt : clôture l'emprunt et met l'exemplaire disponible.
     *
     * @param  Emprunt  $emprunt  Emprunt à clôturer
     */
    public function enregistrerRetour(Emprunt $emprunt): void
    {
        $emprunt->cloturer(new \DateTimeImmutable);
        $emprunt->getExemplaire()->retourner();

        $this->exemplaireRepository->save($emprunt->getExemplaire());
        $this->empruntRepository->save($emprunt);
    }

    public function retourner(Emprunt $emprunt): void
    {
        $emprunt->cloturer(new \DateTimeImmutable);
        $emprunt->getExemplaire()->retourner();
    }

    /**
     * Calcule le retard (en jours) pour l'emprunt donné.
     *
     * @param  Emprunt  $emprunt  Emprunt à évaluer
     * @return int Nombre de jours de retard
     */
    public function calculerRetard(Emprunt $emprunt): int
    {
        return $emprunt->joursDeRetard();
    }
}
