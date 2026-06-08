<?php

declare(strict_types=1);

namespace App\Domain\Lecteur;

interface LecteurRepositoryInterface
{
    public function findById(string $id): ?Lecteur;

    public function save(Lecteur $lecteur): void;
}
