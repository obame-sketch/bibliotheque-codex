<?php

declare(strict_types=1);

namespace Tests\Application\Bibliothecaire\DTO;

use App\Application\Bibliothecaire\DTO\ModifierLivreDto;
use DateTimeImmutable;
use Error;
use Faker\Factory;

uses()->group('dto', 'livre');

describe('ModifierLivreDto', function () {

    it('est créé avec toutes les propriétés non null', function () {
        $titre = 'Le Petit Prince';
        $auteur = 'Antoine de Saint-Exupéry';
        $isbn = '978-2-07-040850-4';
        $datePublication = new DateTimeImmutable('1943-04-06');

        $dto = new ModifierLivreDto(
            titre: $titre,
            auteur: $auteur,
            isbn: $isbn,
            datePublication: $datePublication
        );

        expect($dto->titre)->toBe($titre)
            ->and($dto->auteur)->toBe($auteur)
            ->and($dto->isbn)->toBe($isbn)
            ->and($dto->datePublication)->toBe($datePublication);
    });

    it('est créé avec des propriétés partielles (certaines null)', function () {
        $titre = 'Nouveau titre';
        $auteur = null;
        $isbn = '1234567890';
        $datePublication = null;

        $dto = new ModifierLivreDto(
            titre: $titre,
            auteur: $auteur,
            isbn: $isbn,
            datePublication: $datePublication
        );

        expect($dto->titre)->toBe($titre)
            ->and($dto->auteur)->toBeNull()
            ->and($dto->isbn)->toBe($isbn)
            ->and($dto->datePublication)->toBeNull();
    });

    it('est créé sans paramètres (tout null)', function () {
        $dto = new ModifierLivreDto();

        expect($dto->titre)->toBeNull()
            ->and($dto->auteur)->toBeNull()
            ->and($dto->isbn)->toBeNull()
            ->and($dto->datePublication)->toBeNull();
    });

    it('est immuable (readonly)', function () {
        $dto = new ModifierLivreDto(
            titre: 'Titre',
            auteur: 'Auteur',
            isbn: '1234567890',
            datePublication: new DateTimeImmutable()
        );

        expect(fn() => $dto->titre = 'Nouveau titre')
            ->toThrow(Error::class);
    });

    it('accepte des valeurs dynamiques', function () {
        $faker = Factory::create();

        $titre = $faker->sentence(3);
        $auteur = $faker->name();
        $isbn = $faker->isbn13();
        $datePublication = DateTimeImmutable::createFromMutable(
            $faker->dateTimeThisCentury()
        );

        $dto = new ModifierLivreDto(
            titre: $titre,
            auteur: $auteur,
            isbn: $isbn,
            datePublication: $datePublication
        );

        expect($dto->titre)->toBe($titre)
            ->and($dto->auteur)->toBe($auteur)
            ->and($dto->isbn)->toBe($isbn)
            ->and($dto->datePublication)->toEqual($datePublication)
            ->and($dto->titre)->toBeString()
            ->and($dto->auteur)->toBeString()
            ->and($dto->isbn)->toBeString()
            ->and($dto->datePublication)->toBeInstanceOf(DateTimeImmutable::class);
    });
});
