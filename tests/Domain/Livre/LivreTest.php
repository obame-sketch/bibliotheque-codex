<?php

namespace Tests\Domain\Livre;

use App\Domain\Livre\Livre;

test('un livre peut être créé avec des données valides', function () {
    $date = new \DateTimeImmutable('2023-01-01');
    $livre = new Livre(
        titre: 'Le Petit Prince',
        auteur: 'Antoine de Saint-Exupéry',
        isbn: '978-2-07-061275-8',
        datePublication: $date,
        id: '123'
    );

    expect($livre->id())->toBe('123');
    expect($livre->titre())->toBe('Le Petit Prince');
    expect($livre->auteur())->toBe('Antoine de Saint-Exupéry');
    expect($livre->isbn())->toBe('978-2-07-061275-8');
    expect($livre->datePublication())->toEqual($date);
});

test('le constructeur lève une exception si le titre est vide', function () {
    new Livre(
        titre: '',
        auteur: 'Auteur',
        isbn: '1234567890',
        datePublication: new \DateTimeImmutable()
    );
})->throws(\InvalidArgumentException::class, 'Le champ "titre" ne peut pas être vide');

test('le constructeur lève une exception si l\'auteur est vide', function () {
    new Livre(
        titre: 'Titre',
        auteur: '',
        isbn: '1234567890',
        datePublication: new \DateTimeImmutable()
    );
})->throws(\InvalidArgumentException::class, 'Le champ "auteur" ne peut pas être vide');

test('le constructeur lève une exception si l\'isbn est vide', function () {
    new Livre(
        titre: 'Titre',
        auteur: 'Auteur',
        isbn: '',
        datePublication: new \DateTimeImmutable()
    );
})->throws(\InvalidArgumentException::class, 'Le champ "isbn" ne peut pas être vide');

test('un livre est publié si sa date de publication est passée', function () {
    $datePassee = new \DateTimeImmutable('-1 day');
    $livre = new Livre('Titre', 'Auteur', '123', $datePassee);
    expect($livre->estPublie())->toBeTrue();
});

test('un livre n\'est pas publié si sa date de publication est future', function () {
    $dateFuture = new \DateTimeImmutable('+1 day');
    $livre = new Livre('Titre', 'Auteur', '123', $dateFuture);
    expect($livre->estPublie())->toBeFalse();
});

test('on peut mettre à jour le titre', function () {
    $livre = new Livre('Titre', 'Auteur', '123', new \DateTimeImmutable());
    $livre->mettreAJourTitre('Nouveau Titre');
    expect($livre->titre())->toBe('Nouveau Titre');
});
