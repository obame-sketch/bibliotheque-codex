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
        // Étape 1 : Créer une nouvelle instance de Livre avec un ID unique
        $livre = new Livre(
            id: uniqid('', true),
            titre: $dto->titre,
            auteur: $dto->auteur,
            isbn: $dto->isbn,
            datePublication: $dto->datePublication,
        );

        // Étape 2 : Persister le livre en base de données
        $this->livreRepository->save($livre);

        // Étape 3 : Créer et persister tous les exemplaires demandés
        // Chaque exemplaire est lié au livre via livreId et dispose d'un code-barre unique
        for ($index = 0; $index < $dto->nombreExemplaires; $index++) {
            $exemplaire = new Exemplaire(
                id: uniqid('', true),
                codeBarre: sprintf('%s-%s', $livre->id(), $index + 1),
                statut: StatutExemplaire::DISPONIBLE,
            );
            $exemplaire->setLivre($livre);
            $this->exemplaireRepository->save($exemplaire);
        }

        // Retourner le livre créé
        return $livre;
    }
}
