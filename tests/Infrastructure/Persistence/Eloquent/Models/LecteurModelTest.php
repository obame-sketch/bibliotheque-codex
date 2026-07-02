<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Eloquent\Models;

use App\Infrastructure\Persistence\Eloquent\Models\LecteurModel;
use App\Infrastructure\Persistence\Eloquent\Models\EmpruntModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

beforeEach(function () {
    $this->model = new LecteurModel();
});

test('lecteur model extends eloquent model', function () {
    expect($this->model)->toBeInstanceOf(Model::class);
});

test('lecteur model uses lecteurs table', function () {
    expect($this->model->getTable())->toBe('lecteurs');
});

test('lecteur model is not incrementing', function () {
    expect($this->model->incrementing)->toBeFalse();
});

test('lecteur model key type is string', function () {
    $reflection = new \ReflectionClass($this->model);
    $property = $reflection->getProperty('keyType');

    expect($property->getValue($this->model))->toBe('string');
});

test('lecteur model has correct fillable attributes', function () {
    expect($this->model->getFillable())->toBe(['id', 'nom', 'prenom', 'email', 'date_adhesion']);
});

test('lecteur model has id cast to string', function () {
    expect($this->model->getCasts())->toHaveKey('id', 'string');
});

test('lecteur model has date_adhesion cast to date', function () {
    expect($this->model->getCasts())->toHaveKey('date_adhesion', 'date');
});

test('lecteur model has emprunts hasmany relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('emprunts'))->toBeTrue();
    $method = $reflection->getMethod('emprunts');
    expect($method->getReturnType()?->getName())->toBe(HasMany::class);
});
