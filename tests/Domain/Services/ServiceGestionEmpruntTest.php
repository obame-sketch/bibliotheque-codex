<?php

declare(strict_types=1);

namespace App\Tests\Domain\Services;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use App\Domain\Livre\Livre;
use App\Domain\Services\ServiceGestionEmprunt;
use PHPUnit\Framework\MockObject\MockObject;

beforeEach(function () {
    $this->empruntRepository = mock(EmpruntRepositoryInterface::class);
    $this->exemplaireRepository = mock(ExemplaireRepositoryInterface::class);
    $this->service = new ServiceGestionEmprunt(
        $this->empruntRepository,
        $this->exemplaireRepository
    );

    $this->lecteur = new Lecteur(
        '1',
        'Dupont',
        'Jean',
        'jean@example.com',
        new DateTimeImmutable('2024-01-01')
    );

    $this->exemplaire = new Exemplaire(
        '1',
        'CODE-001',
        StatutExemplaire::DISPONIBLE
    );
    $this->exemplaire->setLivre(new Livre(
        id: 'livre-1',
        titre: 'Titre de Test',
        auteur: 'Auteur de Test',
        isbn: 'ISBN-TEST',
        datePublication: new DateTimeImmutable('2020-01-01')
    ));
});

it('change le statut de l\'exemplaire lors de l\'enregistrement d\'un emprunt', function () {
    $this->exemplaireRepository
        ->shouldReceive('save')
        ->once()
        ->with($this->exemplaire)
        ->andReturn($this->exemplaire);

    $this->empruntRepository
        ->shouldReceive('save')
        ->once()
        ->andReturnUsing(function (Emprunt $emprunt) {
            return $emprunt;
        });

    $emprunt = $this->service->enregistrerEmprunt($this->lecteur, $this->exemplaire);

    expect($emprunt)->toBeInstanceOf(Emprunt::class)
        ->and($emprunt->getLecteur())->toBe($this->lecteur)
        ->and($emprunt->getExemplaire())->toBe($this->exemplaire)
        ->and($this->exemplaire->statut())->toBe(StatutExemplaire::EMPRUNTE);
});

it('clôture l\'emprunt et remet l\'exemplaire disponible lors du retour', function () {
    $now = new DateTimeImmutable;
    $emprunt = new Emprunt(
        '1',
        $this->lecteur,
        $this->exemplaire,
        $now,
        $now->modify('+21 days')
    );

    $this->exemplaireRepository
        ->shouldReceive('save')
        ->once()
        ->with($this->exemplaire)
        ->andReturn($this->exemplaire);

    $this->empruntRepository
        ->shouldReceive('save')
        ->once()
        ->with($emprunt)
        ->andReturn($emprunt);

    $this->service->enregistrerRetour($emprunt);

    expect($emprunt->getExemplaire()->statut())->toBe(StatutExemplaire::DISPONIBLE);
});

it('calcule le nombre de jours de retard', function () {
    $empruntAvecRetard = new Emprunt(
        '1',
        $this->lecteur,
        $this->exemplaire,
        new DateTimeImmutable('2024-01-01'),
        new DateTimeImmutable('2024-01-10')
    );

    $retard = $this->service->calculerRetard($empruntAvecRetard);

    expect($retard)->toBeGreaterThan(0);
});
