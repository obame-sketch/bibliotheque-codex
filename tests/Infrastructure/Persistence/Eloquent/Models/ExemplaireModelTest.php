<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Eloquent\Models;

use App\Domain\Exemplaire\StatutExemplaire;
use App\Infrastructure\Persistence\Eloquent\Models\ExemplaireModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

beforeEach(function () {
    $this->model = new ExemplaireModel;
});

test('exemplaire model extends eloquent model', function () {
    expect($this->model)->toBeInstanceOf(Model::class);
});

test('exemplaire model uses exemplaires table', function () {
    expect($this->model->getTable())->toBe('exemplaires');
});

test('exemplaire model is not incrementing', function () {
    expect($this->model->incrementing)->toBeFalse();
});

test('exemplaire model key type is string', function () {
    $reflection = new \ReflectionClass($this->model);
    $property = $reflection->getProperty('keyType');

    expect($property->getValue($this->model))->toBe('string');
});

test('exemplaire model has correct fillable attributes', function () {
    expect($this->model->getFillable())->toBe(['id', 'livre_id', 'code_barre', 'statut']);
});

test('exemplaire model has id cast to string', function () {
    expect($this->model->getCasts())->toHaveKey('id', 'string');
});

test('exemplaire model has statut cast to StatutExemplaire enum', function () {
    expect($this->model->getCasts())->toHaveKey('statut', StatutExemplaire::class);
});

test('exemplaire model has livre belongsTo relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('livre'))->toBeTrue();
    $method = $reflection->getMethod('livre');
    expect($method->getReturnType()?->getName())->toBe(BelongsTo::class);
});

test('exemplaire model has emprunts hasmany relation method', function () {
    $reflection = new \ReflectionClass($this->model);
    expect($reflection->hasMethod('emprunts'))->toBeTrue();
    $method = $reflection->getMethod('emprunts');
    expect($method->getReturnType()?->getName())->toBe(HasMany::class);
});
