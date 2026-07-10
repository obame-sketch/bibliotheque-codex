<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentLivreRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentExemplaireRepository;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LivreRepositoryInterface::class, EloquentLivreRepository::class);
        $this->app->bind(ExemplaireRepositoryInterface::class, EloquentExemplaireRepository::class);
    }

    public function boot(): void
    {
        // no-op
    }
}

