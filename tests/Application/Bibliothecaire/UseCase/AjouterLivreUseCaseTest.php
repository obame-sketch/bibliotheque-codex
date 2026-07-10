<?php

declare(strict_types=1);

namespace Tests\Application\Bibliothecaire\UseCase;

use App\Application\Bibliothecaire\DTO\AjouterLivreDto;
use App\Application\Bibliothecaire\UseCase\AjouterLivreUseCase;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Domain\Livre\Livre;
use Mockery;

beforeEach(function () {
    $this->livreRepository = Mockery::mock(LivreRepositoryInterface::class);
    $this->exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);

    $this->useCase = new AjouterLivreUseCase(
        $this->livreRepository,
        $this->exemplaireRepository
    );

    $this->dto = new AjouterLivreDto(
        titre: 'Titre test',
        auteur: 'Auteur test',
        isbn: 'ISBN-1234',
        datePublication: new \DateTimeImmutable('2020-01-01'),
        nombreExemplaires: 2,
        categorie: 'Roman'
    );
});

test('execute crée un livre et ses exemplaires', function () {
    $savedLivre = new Livre(
        titre: $this->dto->titre,
        auteur: $this->dto->auteur,
        isbn: $this->dto->isbn,
        datePublication: $this->dto->datePublication,
        categorie: $this->dto->categorie,
        id: 'livre-1'
    );

    $this->livreRepository
        ->shouldReceive('save')
        ->once()
        ->andReturn($savedLivre);

    // On attend deux appels au repository d'exemplaire
    $this->exemplaireRepository
        ->shouldReceive('save')
        ->twice()
        ->andReturnUsing(fn ($ex) => $ex);

    $result = $this->useCase->execute($this->dto);

    expect($result)->toBe($savedLivre);
});

test('execute génère un isbn automatique quand le dto ne fournit pas de valeur', function () {
    $dto = new AjouterLivreDto(
        titre: 'Titre test',
        auteur: 'Auteur test',
        isbn: '',
        datePublication: new \DateTimeImmutable('2020-01-01'),
        nombreExemplaires: 1,
        categorie: 'Science-fiction'
    );

    $this->livreRepository
        ->shouldReceive('save')
        ->once()
        ->andReturnUsing(function (Livre $livre) {
            expect($livre->isbn())->toStartWith('ISBN-');
            expect($livre->categorie())->toBe('Science-fiction');

            return $livre;
        });

    $this->exemplaireRepository
        ->shouldReceive('save')
        ->once()
        ->andReturnUsing(fn ($ex) => $ex);

    $this->useCase->execute($dto);
});
