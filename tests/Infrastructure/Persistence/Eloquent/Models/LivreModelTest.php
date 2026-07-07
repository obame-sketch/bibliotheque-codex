<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Eloquent\Models;

use App\Infrastructure\Persistence\Eloquent\Models\LivreModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

beforeEach(function () {
    $this->model = new LivreModel;
});

test('livre model extends eloquent model', function () {
    expect($this->model)->toBeInstanceOf(Model::class);
});

test('livre model uses livres table', function () {
    expect($this->model->getTable())->toBe('livres');
});

test('livre model is not incrementing', function () {
    expect($this->model->incrementing)->toBeFalse();
});

test('livre model key type is string', function () {
    $reflection = new \ReflectionClass($this->model);
    $property = $reflection->getProperty('keyType');

    expect($property->getValue($this->model))->toBe('string');
});

test('livre model has correct fillable attributes', function () {
    expect($this->model->getFillable())->toBe(['id', 'titre', 'auteur', 'isbn', 'date_publication', 'bibliotheque_id', 'bibliothecaire_id']);
});

test('livre model has id cast to string', function () {
    expect($this->model->getCasts())->toHaveKey('id', 'string');
});

test('livre model has date_publication cast to date', function () {
    expect($this->model->getCasts())->toHaveKey('date_publication', 'date');
});

test('livre model has bibliothecaire belongsTo relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('bibliothecaire'))->toBeTrue();
    $method = $reflection->getMethod('bibliothecaire');
    expect($method->getReturnType()?->getName())->toBe(BelongsTo::class);
});

test('livre model has exemplaires hasmany relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('exemplaires'))->toBeTrue();
    $method = $reflection->getMethod('exemplaires');
    expect($method->getReturnType()?->getName())->toBe(HasMany::class);
});
