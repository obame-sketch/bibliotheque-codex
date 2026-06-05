<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Bibliothecaire;

interface BibliothecaireRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?Bibliothecaire;

    public function save(Bibliothecaire $bibliothecaire): Bibliothecaire;

    public function delete(int $id): void;
}
