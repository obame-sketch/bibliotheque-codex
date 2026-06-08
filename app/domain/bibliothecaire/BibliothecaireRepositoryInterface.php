<?php

declare(strict_types=1);

namespace App\Domain\Bibliothecaire;

interface BibliothecaireRepositoryInterface
{
    public function findById(string $id): ?Bibliothecaire;

    public function save(Bibliothecaire $bibliothecaire): void;
}
