<?php

namespace Tests\Domain\Emprunt;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\StatutEmprunt;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use App\Domain\Livre\Livre;

beforeEach(function () {
    $this->lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable, 'lecteur-id');
    $this->livre = new Livre('Titre', 'Auteur', '123', new \DateTimeImmutable, 'livre-id');
    $this->exemplaire = new Exemplaire('BARRE', StatutExemplaire::DISPONIBLE, 'ex-id');
    $this->exemplaire->setLivre($this->livre);
    $this->dateEmprunt = new \DateTimeImmutable('2023-01-01');
    $this->dateRetourPrevue = $this->dateEmprunt->modify('+21 days');
});

test('un emprunt peut être créé avec des données valides', function () {
    $dateEmprunt = new \DateTimeImmutable('-1 day');
    $dateRetourPrevue = $dateEmprunt->modify('+21 days');

    $emprunt = new Emprunt(
        lecteur: $this->lecteur,
        exemplaire: $this->exemplaire,
        dateEmprunt: $dateEmprunt,
        dateRetourPrevue: $dateRetourPrevue,
        id: 'emprunt-id'
    );

    expect($emprunt->getId())->toBe('emprunt-id');
    expect($emprunt->getLecteur())->toBe($this->lecteur);
    expect($emprunt->getExemplaire())->toBe($this->exemplaire);
    expect($emprunt->dateEmprunt())->toEqual($dateEmprunt);
    expect($emprunt->dateRetourPrevue())->toEqual($dateRetourPrevue);
    expect($emprunt->dateRetourEffective())->toBeNull();
    expect($emprunt->statut())->toBe(StatutEmprunt::EN_COURS);
});

test('le constructeur lève une exception si dateRetourPrevue < dateEmprunt', function () {
    new Emprunt(
        $this->lecteur,
        $this->exemplaire,
        $this->dateEmprunt,
        $this->dateEmprunt->modify('-1 day')
    );
})->throws(\InvalidArgumentException::class, 'La date de retour prévue doit être postérieure à la date d\'emprunt.');

test('cloturer met à jour la date de retour effective et le statut', function () {
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $this->dateEmprunt, $this->dateRetourPrevue);
    $dateRetour = new \DateTimeImmutable('2023-01-22');
    $emprunt->cloturer($dateRetour);

    expect($emprunt->dateRetourEffective())->toEqual($dateRetour);
    expect($emprunt->statut())->toBe(StatutEmprunt::RENDU);
});

test('cloturer lève une exception si l\'emprunt est déjà rendu', function () {
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $this->dateEmprunt, $this->dateRetourPrevue);
    $emprunt->cloturer(new \DateTimeImmutable);
    $emprunt->cloturer(new \DateTimeImmutable);
})->throws(\DomainException::class, 'Cet emprunt est déjà clôturé.');

test('cloturer lève une exception si dateRetour antérieure à dateEmprunt', function () {
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $this->dateEmprunt, $this->dateRetourPrevue);
    $emprunt->cloturer($this->dateEmprunt->modify('-1 day'));
})->throws(\InvalidArgumentException::class, 'La date de retour effective ne peut pas être antérieure à la date d\'emprunt.');

test('estEnRetard retourne vrai si la date actuelle dépasse dateRetourPrevue (et pas encore rendu)', function () {
    $dateRetourPrevuePassee = new \DateTimeImmutable('-1 day');
    $empruntEnRetard = new Emprunt(
        $this->lecteur,
        $this->exemplaire,
        $this->dateEmprunt,
        $dateRetourPrevuePassee
    );
    expect($empruntEnRetard->estEnRetard())->toBeTrue();
});

test('estEnRetard retourne faux si la date de retour effective est dans les délais', function () {
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $this->dateEmprunt, $this->dateRetourPrevue);
    $emprunt->cloturer($this->dateRetourPrevue);
    expect($emprunt->estEnRetard())->toBeFalse();
});

test('joursDeRetard retourne 0 si pas de retard', function () {
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $this->dateEmprunt, $this->dateRetourPrevue);
    $emprunt->cloturer($this->dateRetourPrevue);
    expect($emprunt->joursDeRetard())->toBe(0);
});

test('joursDeRetard retourne le bon nombre de jours de retard pour un emprunt en cours', function () {
    $dateEmprunt = new \DateTimeImmutable('2023-01-01');
    $dateRetourPrevue = new \DateTimeImmutable('2023-01-10');
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $dateEmprunt, $dateRetourPrevue);
    $dateRetourEffective = new \DateTimeImmutable('2023-01-15');
    $emprunt->cloturer($dateRetourEffective);
    expect($emprunt->joursDeRetard())->toBe(5);
});
