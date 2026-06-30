<?php

namespace Tests\Domain\Exemplaire;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Livre\Livre;

beforeEach(function () {
    $this->livre = new Livre('Titre', 'Auteur', '123', new \DateTimeImmutable, 'livre-id');
    $this->exemplaire = new Exemplaire(
        codeBarre: 'BARRE123',
        statut: StatutExemplaire::DISPONIBLE,
        id: 'ex-id'
    );
    $this->exemplaire->setLivre($this->livre);
});

test('un exemplaire peut être créé avec un statut disponible', function () {
    expect($this->exemplaire->id())->toBe('ex-id');
    expect($this->exemplaire->codeBarre())->toBe('BARRE123');
    expect($this->exemplaire->statut())->toBe(StatutExemplaire::DISPONIBLE);
    expect($this->exemplaire->getLivre())->toBe($this->livre);
});

test('emprunter change le statut en EMPRUNTE si disponible', function () {
    $this->exemplaire->emprunter();
    expect($this->exemplaire->statut())->toBe(StatutExemplaire::EMPRUNTE);
    expect($this->exemplaire->estDisponible())->toBeFalse();
});

test('emprunter lève une exception si l\'exemplaire n\'est pas disponible', function () {
    $this->exemplaire->emprunter(); // devient emprunté
    $this->exemplaire->emprunter(); // tentative de ré-emprunt
})->throws(\DomainException::class, 'Cet exemplaire n\'est pas disponible pour un emprunt.');

test('retourner change le statut en DISPONIBLE si emprunté', function () {
    $this->exemplaire->emprunter();
    $this->exemplaire->retourner();
    expect($this->exemplaire->statut())->toBe(StatutExemplaire::DISPONIBLE);
});

test('retourner lève une exception si l\'exemplaire est perdu', function () {
    $exemplairePerdu = new Exemplaire('BARRE', StatutExemplaire::PERDU);
    $exemplairePerdu->setLivre($this->livre);
    $exemplairePerdu->retourner();
})->throws(\DomainException::class, 'Un exemplaire perdu ne peut pas être retourné.');
