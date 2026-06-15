<?php

declare(strict_types=1);

namespace App\Domain\Bibliothecaire;

/**
 * Contrat de persistance pour les bibliothécaires.
 *
 * Définit les opérations minimales attendues d'un repository de bibliothécaires
 * (recherche et sauvegarde). Les implémentations concrètes gèrent la couche
 * d'infrastructure (base de données, stockage, etc.).
 */
interface BibliothecaireRepositoryInterface
{
    public function findById(string $id): ?Bibliothecaire;

    public function save(Bibliothecaire $bibliothecaire): void;
}
