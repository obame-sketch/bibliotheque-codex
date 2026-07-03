<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Eloquent\Models;

use App\Infrastructure\Persistence\Eloquent\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;

class TestableAbstractModel extends AbstractModel {}

test('abstract model extends eloquent model', function () {
    $model = new TestableAbstractModel;

    expect($model)->toBeInstanceOf(Model::class);
});

test('abstract model disables auto incrementing', function () {
    $model = new TestableAbstractModel;

    expect($model->incrementing)->toBeFalse();
});

test('abstract model uses string primary key', function () {
    $model = new TestableAbstractModel;
    $reflection = new \ReflectionClass($model);
    $property = $reflection->getProperty('keyType');

    expect($property->getValue($model))->toBe('string');
});

test('abstract model casts id to string', function () {
    $model = new TestableAbstractModel;

    expect($model->getCasts())->toHaveKey('id', 'string');
});
