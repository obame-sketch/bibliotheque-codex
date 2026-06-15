<?php

declare(strict_types=1);

namespace App\Domain\Lecteur;

/**
 * Contrat de persistance pour les lecteurs.
 *
 * Définit les opérations minimales nécessaires pour retrouver un lecteur
 * et sauvegarder ses informations. Les implémentations gèrent la couche
 * d'infrastructure.
 */
interface LecteurRepositoryInterface
{
    public function findById(string $id): ?Lecteur;

    public function save(Lecteur $lecteur): void;
}
