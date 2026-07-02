<?php

declare(strict_types=1);

namespace App\Tests\Domain\Services;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Emprunt\StatutEmprunt;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use App\Domain\Livre\Livre;
use App\Domain\Services\ServiceGestionEmprunt;

beforeEach(function () {
    $this->empruntRepository = mock(EmpruntRepositoryInterface::class);
    $this->exemplaireRepository = mock(ExemplaireRepositoryInterface::class);
    $this->service = new ServiceGestionEmprunt(
        $this->empruntRepository,
        $this->exemplaireRepository
    );

    $this->lecteur = new Lecteur('Dupont', 'Jean', 'jean@test.com', new \DateTimeImmutable, 'lecteur-id');
    $this->livre = new Livre('Titre', 'Auteur', '123', new \DateTimeImmutable, 'livre-id');
    $this->exemplaire = new Exemplaire('BARRE', StatutExemplaire::DISPONIBLE, 'ex-id');
    $this->exemplaire->setLivre($this->livre);
});

test('enregistrerEmprunt marque l\'exemplaire comme emprunté et sauvegarde l\'emprunt', function () {
    // On s'attend à ce que l'exemplaire soit sauvegardé après emprunter()
    $this->exemplaireRepository
        ->shouldReceive('save')
        ->once()
        ->withArgs(function ($exemplaire) {
            return $exemplaire->statut() === StatutExemplaire::EMPRUNTE;
        })
        ->andReturn($this->exemplaire);

    // On s'attend à ce que l'emprunt soit sauvegardé
    $this->empruntRepository
        ->shouldReceive('save')
        ->once()
        ->withArgs(function ($emprunt) {
            return $emprunt instanceof Emprunt
                && $emprunt->getLecteur() === $this->lecteur
                && $emprunt->getExemplaire() === $this->exemplaire
                && $emprunt->statut() === StatutEmprunt::EN_COURS;
        })
        ->andReturnUsing(function ($emprunt) {
            // On simule l'assignation d'un ID par le repository
            // On peut utiliser reflection ou simplement retourner l'emprunt avec un ID
            $reflection = new \ReflectionClass($emprunt);
            $property = $reflection->getProperty('id');
            $property->setValue($emprunt, 'new-id');

            return $emprunt;
        });

    $emprunt = $this->service->enregistrerEmprunt($this->lecteur, $this->exemplaire);

    expect($emprunt)->toBeInstanceOf(Emprunt::class);
    expect($emprunt->getId())->toBe('new-id');
    expect($emprunt->getExemplaire()->statut())->toBe(StatutExemplaire::EMPRUNTE);
});

test('emprunter récupère un exemplaire disponible et appelle enregistrerEmprunt', function () {
    // Simuler la recherche d'exemplaires disponibles
    $this->exemplaireRepository
        ->shouldReceive('findDisponiblesByLivre')
        ->with('livre-id')
        ->once()
        ->andReturn([$this->exemplaire]);

    // On s'attend à ce que enregistrerEmprunt soit appelé (on peut espionner ou vérifier les appels)
    // Mais comme enregistrerEmprunt est public, on peut mocker partiellement le service ?
    // Ou on vérifie que l'exemplaire est passé à enregistrerEmprunt.
    // Ici on va mocker le service pour espionner enregistrerEmprunt ou on le teste indirectement.
    // Pour une approche plus propre, on peut ne pas mocker le service, mais vérifier que les repos sont appelés correctement.
    // On va simuler le comportement de enregistrerEmprunt.
    // Le plus simple: on espionne que save de l'exemplaire est appelé avec le bon statut et que save de l'emprunt est appelé.
    // On peut aussi mocker la méthode enregistrerEmprunt du service avec partial mock, mais Pest ne facilite pas le mocking partiel.
    // On va plutôt tester le comportement complet.

    // On prépare les mocks pour les sauvegardes.
    $this->exemplaireRepository
        ->shouldReceive('save')
        ->once()
        ->andReturn($this->exemplaire);

    $this->empruntRepository
        ->shouldReceive('save')
        ->once()
        ->andReturnUsing(function ($emprunt) {
            $reflection = new \ReflectionClass($emprunt);
            $property = $reflection->getProperty('id');
            $property->setValue($emprunt, 'emprunt-id');

            return $emprunt;
        });

    $emprunt = $this->service->emprunter($this->lecteur, 'livre-id');

    expect($emprunt)->toBeInstanceOf(Emprunt::class);
    expect($emprunt->getId())->toBe('emprunt-id');
});

test('enregistrerRetour clôture l\'emprunt et remet l\'exemplaire disponible', function () {
    // Créer un emprunt en cours
    $dateEmprunt = new \DateTimeImmutable('-10 days');
    $dateRetourPrevue = new \DateTimeImmutable('+11 days');
    $emprunt = new Emprunt(
        $this->lecteur,
        $this->exemplaire,
        $dateEmprunt,
        $dateRetourPrevue,
        id: 'emprunt-id'
    );
    // On s'attend à ce que l'exemplaire soit sauvegardé avec statut DISPONIBLE
    $this->exemplaireRepository
        ->shouldReceive('save')
        ->once()
        ->withArgs(function ($exemplaire) {
            return $exemplaire->statut() === StatutExemplaire::DISPONIBLE;
        })
        ->andReturn($this->exemplaire);

    // On s'attend à ce que l'emprunt soit sauvegardé avec statut RENDU
    $this->empruntRepository
        ->shouldReceive('save')
        ->once()
        ->withArgs(function ($emprunt) {
            return $emprunt->statut() === StatutEmprunt::RENDU
                && $emprunt->dateRetourEffective() !== null;
        })
        ->andReturn($emprunt);

    $this->service->enregistrerRetour($emprunt);

    expect($emprunt->statut())->toBe(StatutEmprunt::RENDU);
    expect($emprunt->getExemplaire()->statut())->toBe(StatutExemplaire::DISPONIBLE);
});

test('retourner modifie les entités sans persister (ne fait que les appels métier)', function () {
    // Cette méthode ne sauvegarde pas, elle modifie directement
    $dateEmprunt = new \DateTimeImmutable('-10 days');
    $dateRetourPrevue = new \DateTimeImmutable('+11 days');
    $emprunt = new Emprunt(
        $this->lecteur,
        $this->exemplaire,
        $dateEmprunt,
        $dateRetourPrevue,
        id: 'emprunt-id'
    );

    $this->service->retourner($emprunt);

    expect($emprunt->statut())->toBe(StatutEmprunt::RENDU);
    expect($emprunt->getExemplaire()->statut())->toBe(StatutExemplaire::DISPONIBLE);
    // Vérifier que les repositories ne sont pas appelés
});

test('calculerRetard retourne le nombre de jours de retard de l\'emprunt', function () {
    $dateEmprunt = new \DateTimeImmutable('2023-01-01');
    $dateRetourPrevue = new \DateTimeImmutable('2023-01-10');
    $emprunt = new Emprunt($this->lecteur, $this->exemplaire, $dateEmprunt, $dateRetourPrevue);
    // On simule un retour en retard le 15 janvier
    $emprunt->cloturer(new \DateTimeImmutable('2023-01-15'));

    $retard = $this->service->calculerRetard($emprunt);
    expect($retard)->toBe(5);
});
