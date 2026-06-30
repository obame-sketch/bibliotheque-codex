<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\UseCase;

use App\Application\Lecteur\UseCase\ConsulterCatalogueUseCase;
use App\Domain\Livre\Livre;
use App\Domain\Livre\LivreRepositoryInterface;

beforeEach(function () {
    $this->livreRepository = mock(LivreRepositoryInterface::class);
    $this->useCase = new ConsulterCatalogueUseCase($this->livreRepository);
});

test('execute appelle findAll et retourne la liste des livres', function () {
    $date = new \DateTimeImmutable;
    $livresAttendus = [
        new Livre('Titre 1', 'Auteur 1', 'ISBN1', $date, 'id1'),
        new Livre('Titre 2', 'Auteur 2', 'ISBN2', $date, 'id2'),
    ];

    $this->livreRepository
        ->shouldReceive('findAll')
        ->once()
        ->andReturn($livresAttendus);

    $resultat = $this->useCase->execute();

    expect($resultat)->toBe($livresAttendus);
});

test('execute retourne un tableau vide lorsque le catalogue est vide', function () {
    $this->livreRepository
        ->shouldReceive('findAll')
        ->once()
        ->andReturn([]);

    $resultat = $this->useCase->execute();

    expect($resultat)->toBe([]);
});
