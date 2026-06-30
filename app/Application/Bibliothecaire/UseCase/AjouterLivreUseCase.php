<?php

declare(strict_types=1);

namespace App\Application\Bibliothecaire\UseCase;

use App\Application\Bibliothecaire\DTO\AjouterLivreDto;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Livre\Livre;
use App\Domain\Livre\LivreRepositoryInterface;

/**
 * Cas d'utilisation pour l'ajout d'un nouveau livre et de ses exemplaires.
 */
final class AjouterLivreUseCase
{
    public function __construct(
        private readonly LivreRepositoryInterface $livreRepository,
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    /**
     * Exécute l'ajout d'un livre et crée le nombre d'exemplaires demandés.
     *
     * @param  AjouterLivreDto  $dto  Données nécessaires à la création du livre
     */
    public function execute(AjouterLivreDto $dto): Livre
    {
        $livre = new Livre(
            id: null,
            titre: $dto->titre,
            auteur: $dto->auteur,
            isbn: $dto->isbn,
            datePublication: $dto->datePublication,
        );
        $livreSauvegarder =$this->livreRepository->save($livre);
        for ($index = 0; $index < $dto->nombreExemplaires; $index++) {
            $exemplaire = new Exemplaire(
                id: null,
                codeBarre: sprintf('%s-%s', $livreSauvegarder->id(), $index + 1),
                statut: StatutExemplaire::DISPONIBLE,
            );
            $exemplaire->setLivre($livreSauvegarder);
            $exemplaireRepository->save($exemplaire);
        }
        return $livre;
    }
}
