<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\UseCase;

use App\Application\Lecteur\DTO\EmprunterLivreDto;
use App\Application\Lecteur\UseCase\EmprunterLivreUseCase;
use App\Domain\Common\Exception\DomainException;
use App\Domain\Emprunt\Emprunt;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use App\Domain\Lecteur\LecteurRepositoryInterface;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Domain\Services\ServiceDisponibilite;
use App\Domain\Services\ServiceGestionEmprunt;
use Mockery;

beforeEach(function () {

    // Création des mocks en utilisant Mockery directement
    $this->lecteurRepository = Mockery::mock(LecteurRepositoryInterface::class);
    $this->livreRepository = Mockery::mock(LivreRepositoryInterface::class);
    $this->serviceDisponibilite = Mockery::mock(ServiceDisponibilite::class);
    $this->serviceGestionEmprunt = Mockery::mock(ServiceGestionEmprunt::class);

    $this->useCase = new EmprunterLivreUseCase(
        $this->lecteurRepository,
        $this->livreRepository,
        $this->serviceDisponibilite,
        $this->serviceGestionEmprunt
    );

    $this->lecteurId = 'lecteur-123';
    $this->livreId = 'livre-456';
    $this->dto = new EmprunterLivreDto($this->lecteurId, $this->livreId);
});

test('execute emprunte avec succès un livre disponible', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable(), $this->lecteurId);

    $this->lecteurRepository
        ->shouldReceive('findById')
        ->once()
        ->with($this->lecteurId)
        ->andReturn($lecteur);

    $this->serviceDisponibilite
        ->shouldReceive('verifier')
        ->once()
        ->with($this->livreId)
        ->andReturn(true);

    $exemplaire = new Exemplaire('BARRE', StatutExemplaire::DISPONIBLE);
    $empruntAttendu = new Emprunt(
        $lecteur,
        $exemplaire,
        new \DateTimeImmutable(),
        (new \DateTimeImmutable())->modify('+21 days'),
        id: 'emprunt-789'
    );

    $this->serviceGestionEmprunt
        ->shouldReceive('emprunter')
        ->once()
        ->with($lecteur, $this->livreId)
        ->andReturn($empruntAttendu);

    $resultat = $this->useCase->execute($this->dto);
    expect($resultat)->toBe($empruntAttendu);
});

test('execute lève une exception si le lecteur est introuvable', function () {
    $this->lecteurRepository
        ->shouldReceive('findById')
        ->once()
        ->with($this->lecteurId)
        ->andReturn(null);

    $this->serviceDisponibilite->shouldReceive('verifier')->never();
    $this->serviceGestionEmprunt->shouldReceive('emprunter')->never();

    $this->useCase->execute($this->dto);
})->throws(DomainException::class, 'Action impossible : le lecteur spécifié est introuvable.');

test('execute lève une exception si le livre est indisponible', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable(), $this->lecteurId);

    $this->lecteurRepository
        ->shouldReceive('findById')
        ->once()
        ->with($this->lecteurId)
        ->andReturn($lecteur);

    $this->serviceDisponibilite
        ->shouldReceive('verifier')
        ->once()
        ->with($this->livreId)
        ->andReturn(false);

    $this->serviceGestionEmprunt->shouldReceive('emprunter')->never();

    $this->useCase->execute($this->dto);
})->throws(DomainException::class, 'Action impossible : le livre demandé n\'est pas disponible actuellement.');
