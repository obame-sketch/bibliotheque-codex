<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Eloquent\Models;

use App\Infrastructure\Persistence\Eloquent\Models\BibliothecaireModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

beforeEach(function () {
    $this->model = new BibliothecaireModel;
});

test('bibliothecaire model extends eloquent model', function () {
    expect($this->model)->toBeInstanceOf(Model::class);
});

test('bibliothecaire model uses bibliothecaires table', function () {
    expect($this->model->getTable())->toBe('bibliothecaires');
});

test('bibliothecaire model is not incrementing', function () {
    expect($this->model->incrementing)->toBeFalse();
});

test('bibliothecaire model key type is string', function () {
    $reflection = new \ReflectionClass($this->model);
    $property = $reflection->getProperty('keyType');

    expect($property->getValue($this->model))->toBe('string');
});

test('bibliothecaire model has correct fillable attributes', function () {
    expect($this->model->getFillable())->toBe(['id', 'nom', 'prenom', 'email']);
});

test('bibliothecaire model has id cast to string', function () {
    expect($this->model->getCasts())->toHaveKey('id', 'string');
});

test('bibliothecaire model has livres hasmany relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('livres'))->toBeTrue();
    $method = $reflection->getMethod('livres');
    expect($method->getReturnType()?->getName())->toBe(HasMany::class);
});
