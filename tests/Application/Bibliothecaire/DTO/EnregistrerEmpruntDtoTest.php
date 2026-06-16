<?php

declare(strict_types=1);

namespace Tests\Application\Bibliothecaire\DTO;

use App\Application\Bibliothecaire\DTO\EnregistrerEmpruntDto;
use Error;
use Faker\Factory;

uses()->group('dto', 'emprunt');

describe('EnregistrerEmpruntDto', function () {

    it('est créé avec les bonnes valeurs', function () {
        $lecteurId = 'abc-123';
        $exemplaireId = 'ex-456';

        $dto = new EnregistrerEmpruntDto(
            lecteurId: $lecteurId,
            exemplaireId: $exemplaireId
        );

        expect($dto->lecteurId)->toBe($lecteurId)
            ->and($dto->exemplaireId)->toBe($exemplaireId);
    });

    it('est immuable (readonly)', function () {
        $dto = new EnregistrerEmpruntDto(
            lecteurId: 'lecteur-1',
            exemplaireId: 'exemplaire-1'
        );

        expect(fn () => $dto->lecteurId = 'nouvel-id')
            ->toThrow(Error::class);
    });

    it('accepte des valeurs dynamiques', function () {
        $faker = Factory::create();

        $lecteurId = $faker->uuid();
        $exemplaireId = $faker->uuid(); // ou un format personnalisé

        $dto = new EnregistrerEmpruntDto(
            lecteurId: $lecteurId,
            exemplaireId: $exemplaireId
        );

        expect($dto->lecteurId)->toBe($lecteurId)
            ->and($dto->exemplaireId)->toBe($exemplaireId)
            ->and($dto->lecteurId)->toBeString()
            ->and($dto->exemplaireId)->toBeString();
    });
});
