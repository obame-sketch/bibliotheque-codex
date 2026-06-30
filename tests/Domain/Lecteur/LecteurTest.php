<?php

namespace Tests\Domain\Lecteur;

use App\Domain\Lecteur\Lecteur;

test('un lecteur peut être créé avec des données valides', function () {
    $date = new \DateTimeImmutable('2023-01-01');
    $lecteur = new Lecteur(
        nom: 'Dupont',
        prenom: 'Jean',
        email: 'jean.dupont@example.com',
        dateAdhesion: $date,
        id: 'abc'
    );

    expect($lecteur->id())->toBe('abc');
    expect($lecteur->nom())->toBe('Dupont');
    expect($lecteur->prenom())->toBe('Jean');
    expect($lecteur->email())->toBe('jean.dupont@example.com');
    expect($lecteur->dateAdhesion())->toEqual($date);
});

test('le constructeur valide l\'email', function () {
    new Lecteur('Dupont', 'Jean', 'invalid-email', new \DateTimeImmutable());
})->throws(\InvalidArgumentException::class, 'L\'adresse e-mail fournie est invalide.');

test('un lecteur est adhérent actif si la date d\'adhésion est passée', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable('-1 day'));
    expect($lecteur->estAdherentActif())->toBeTrue();
});

test('un lecteur n\'est pas adhérent si la date d\'adhésion est future', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable('+1 day'));
    expect($lecteur->estAdherentActif())->toBeFalse();
});

test('on peut changer l\'email avec validation', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'old@test.com', new \DateTimeImmutable());
    $lecteur->changerEmail('new@test.com');
    expect($lecteur->email())->toBe('new@test.com');
});

test('changerEmail lève une exception si email invalide', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'old@test.com', new \DateTimeImmutable());
    $lecteur->changerEmail('invalid');
})->throws(\InvalidArgumentException::class);

test('on peut renommer un lecteur', function () {
    $lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable());
    $lecteur->renommer('Martin', 'Marie');
    expect($lecteur->nom())->toBe('Martin');
    expect($lecteur->prenom())->toBe('Marie');
});
