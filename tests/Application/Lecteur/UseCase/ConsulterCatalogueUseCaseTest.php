<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\UseCase;

use App\Application\Lecteur\UseCase\ConsulterCatalogueUseCase;
use App\Domain\Livre\Livre;
use App\Domain\Livre\LivreRepositoryInterface;
use DateTimeImmutable; // <--- AJOUT

beforeEach(function () {
    $this->repository = mock(LivreRepositoryInterface::class);
    $this->useCase = new ConsulterCatalogueUseCase($this->repository);
});

it('retourne tous les livres du catalogue', function () {
    $livre1 = new Livre(
        '1',
        'Titre 1',
        'Auteur 1',
        'ISBN1',
        new DateTimeImmutable() // maintenant reconnu
    );
    $livre2 = new Livre(
        '2',
        'Titre 2',
        'Auteur 2',
        'ISBN2',
        new DateTimeImmutable()
    );

    $this->repository->shouldReceive('findAll')
        ->once()
        ->andReturn([$livre1, $livre2]);

    $resultat = $this->useCase->execute();

    expect($resultat)->toBeArray();
    expect($resultat)->toHaveCount(2);
    expect($resultat[0])->toBe($livre1);
    expect($resultat[1])->toBe($livre2);
});

it('retourne un tableau vide si le catalogue est vide', function () {
    $this->repository->shouldReceive('findAll')
        ->once()
        ->andReturn([]);

    $resultat = $this->useCase->execute();

    expect($resultat)->toBeArray();
    expect($resultat)->toBeEmpty();
});
