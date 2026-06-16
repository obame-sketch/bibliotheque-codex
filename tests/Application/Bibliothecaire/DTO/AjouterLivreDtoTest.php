<?php

declare(strict_types=1);

namespace Tests\Application\Bibliothecaire\DTO;

use App\Application\Bibliothecaire\DTO\AjouterLivreDto;
use DateTimeImmutable;
use Error;
use Faker\Factory;

uses()->group('dto', 'livre');

describe('AjouterLivreDto', function () {

    it('est créé avec les bonnes valeurs', function () {
        $titre = 'Le Petit Prince';
        $auteur = 'Antoine de Saint-Exupéry';
        $isbn = '978-2-07-040850-4';
        $datePublication = new DateTimeImmutable('1943-04-06');
        $nombreExemplaires = 5;

        $dto = new AjouterLivreDto(
            titre: $titre,
            auteur: $auteur,
            isbn: $isbn,
            datePublication: $datePublication,
            nombreExemplaires: $nombreExemplaires
        );

        expect($dto->titre)->toBe($titre)
            ->and($dto->auteur)->toBe($auteur)
            ->and($dto->isbn)->toBe($isbn)
            ->and($dto->datePublication)->toBe($datePublication)
            ->and($dto->nombreExemplaires)->toBe($nombreExemplaires);
    });

    it('est immuable (readonly)', function () {
        $dto = new AjouterLivreDto(
            titre: 'Titre',
            auteur: 'Auteur',
            isbn: '1234567890',
            datePublication: new DateTimeImmutable,
            nombreExemplaires: 1
        );

        // On capture l'erreur sans tester le message exact (car il contient le nom complet)
        expect(fn () => $dto->titre = 'Nouveau titre')
            ->toThrow(Error::class);
    });

    it('accepte des valeurs dynamiques', function () {
        $faker = Factory::create();

        // Convertir en DateTimeImmutable
        $dateTime = DateTimeImmutable::createFromMutable(
            $faker->dateTimeThisCentury()
        );

        $dto = new AjouterLivreDto(
            titre: $faker->sentence(3),
            auteur: $faker->name(),
            isbn: $faker->isbn13(),
            datePublication: $dateTime,
            nombreExemplaires: $faker->numberBetween(1, 100)
        );

        expect($dto->titre)->not->toBeEmpty()
            ->and($dto->auteur)->not->toBeEmpty()
            ->and($dto->isbn)->not->toBeEmpty()
            ->and($dto->datePublication)->toBeInstanceOf(DateTimeImmutable::class)
            ->and($dto->nombreExemplaires)->toBeInt();
    });
});
