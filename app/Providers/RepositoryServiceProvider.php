<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Repository\BibliothecaireRepositoryInterface::class,
            \App\Infrastructure\Persistence\Repository\EloquentBibliothecaireRepository::class
        );

        $this->app->bind(
            \App\Domain\Repository\LivreRepositoryInterface::class,
            \App\Infrastructure\Persistence\Repository\EloquentLivreRepository::class
        );

        $this->app->bind(
            \App\Domain\Repository\ExemplaireRepositoryInterface::class,
            \App\Infrastructure\Persistence\Repository\EloquentExemplaireRepository::class
        );

        $this->app->bind(
            \App\Domain\Repository\LecteurRepositoryInterface::class,
            \App\Infrastructure\Persistence\Repository\EloquentLecteurRepository::class
        );

        $this->app->bind(
            \App\Domain\Repository\EmpruntRepositoryInterface::class,
            \App\Infrastructure\Persistence\Repository\EloquentEmpruntRepository::class
        );
    }
}
