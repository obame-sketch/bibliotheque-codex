<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Emprunt\StatutEmprunt;
use App\Infrastructure\Persistence\Eloquent\Models\EmpruntModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    $this->model = new EmpruntModel;
});

test('emprunt model extends eloquent model', function () {
    expect($this->model)->toBeInstanceOf(Model::class);
});

test('emprunt model uses emprunts table', function () {
    expect($this->model->getTable())->toBe('emprunts');
});

test('emprunt model is not incrementing', function () {
    expect($this->model->incrementing)->toBeFalse();
});

test('emprunt model key type is string', function () {
    $reflection = new \ReflectionClass($this->model);
    $property = $reflection->getProperty('keyType');

    expect($property->getValue($this->model))->toBe('string');
});

test('emprunt model has correct fillable attributes', function () {
    expect($this->model->getFillable())->toBe([
        'id',
        'lecteur_id',
        'exemplaire_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_effective',
        'statut',
    ]);
});

test('emprunt model has id cast to string', function () {
    expect($this->model->getCasts())->toHaveKey('id', 'string');
});

test('emprunt model has date_emprunt cast to datetime', function () {
    expect($this->model->getCasts())->toHaveKey('date_emprunt', 'datetime');
});

test('emprunt model has date_retour_prevue cast to datetime', function () {
    expect($this->model->getCasts())->toHaveKey('date_retour_prevue', 'datetime');
});

test('emprunt model has date_retour_effective cast to datetime', function () {
    expect($this->model->getCasts())->toHaveKey('date_retour_effective', 'datetime');
});

test('emprunt model has statut cast to StatutEmprunt enum', function () {
    expect($this->model->getCasts())->toHaveKey('statut', StatutEmprunt::class);
});

test('emprunt model has lecteur belongsTo relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('lecteur'))->toBeTrue();
    $method = $reflection->getMethod('lecteur');
    expect($method->getReturnType()?->getName())->toBe(BelongsTo::class);
});

test('emprunt model has exemplaire belongsTo relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('exemplaire'))->toBeTrue();
    $method = $reflection->getMethod('exemplaire');
    expect($method->getReturnType()?->getName())->toBe(BelongsTo::class);
});
