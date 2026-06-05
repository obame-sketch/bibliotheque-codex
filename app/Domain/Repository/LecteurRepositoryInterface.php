<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Lecteur;

interface LecteurRepositoryInterface
{
    public function all(): array;

    public function find(int $id): ?Lecteur;

    public function save(Lecteur $lecteur): Lecteur;

    public function delete(int $id): void;
}
