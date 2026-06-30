<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\UseCase;

use App\Application\Lecteur\UseCase\RechercherLivreUseCase;
use App\Domain\Livre\LivreRepositoryInterface;
use Mockery;

beforeEach(function () {
    $this->livreRepository = Mockery::mock(LivreRepositoryInterface::class);
    $this->useCase = new RechercherLivreUseCase($this->livreRepository);
});

afterEach(function () {
    Mockery::close();
});

it('appelle findAll et retourne tous les livres si le mot-clé est vide', function () {
    $expected = [
        ['id' => '1', 'titre' => 'Livre A'],
        ['id' => '2', 'titre' => 'Livre B'],
    ];

    $this->livreRepository
        ->shouldReceive('findAll')
        ->once()
        ->andReturn($expected);

    $result = $this->useCase->execute('');

    expect($result)->toBe($expected);
});

it('appelle findAll et retourne tous les livres si le mot-clé ne contient que des espaces', function () {
    $expected = [
        ['id' => '3', 'titre' => 'Livre C'],
    ];

    $this->livreRepository
        ->shouldReceive('findAll')
        ->once()
        ->andReturn($expected);

    $result = $this->useCase->execute('   ');

    expect($result)->toBe($expected);
});

it('appelle search avec le mot-clé nettoyé si le mot-clé est non vide', function () {
    $keyword = '  Harry Potter  ';
    $cleaned = 'Harry Potter';
    $expected = [
        ['id' => '1', 'titre' => 'Harry Potter à l\'école des sorciers'],
    ];

    $this->livreRepository
        ->shouldReceive('search')
        ->once()
        ->with($cleaned)
        ->andReturn($expected);

    $result = $this->useCase->execute($keyword);

    expect($result)->toBe($expected);
});

it('passe le mot-clé tel quel si aucun espace superflu', function () {
    $keyword = 'Tolkien';
    $expected = [['id' => '4', 'titre' => 'Le Seigneur des Anneaux']];

    $this->livreRepository
        ->shouldReceive('search')
        ->once()
        ->with($keyword)
        ->andReturn($expected);

    $result = $this->useCase->execute($keyword);

    expect($result)->toBe($expected);
});
