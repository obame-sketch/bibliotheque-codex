<?php

declare(strict_types=1);

namespace App\Tests\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Livre\Livre;
use App\Domain\Services\ServiceDisponibilite;
use PHPUnit\Framework\TestCase;

class ServiceDisponibiliteTest extends TestCase
{
    private ExemplaireRepositoryInterface $repository;

    private ServiceDisponibilite $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ExemplaireRepositoryInterface::class);
        $this->service = new ServiceDisponibilite($this->repository);
    }

    // ========== TESTS POUR verifierDisponibilite ==========

    public function test_verifier_disponibilite_retourne_false_si_aucun_exemplaire_trouve(): void
    {
        $livreId = 'livre-1';
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([]);

        $result = $this->service->verifierDisponibilite($livreId);
        $this->assertFalse($result);
    }

    public function test_verifier_disponibilite_retourne_false_si_tableau_vide(): void
    {
        $livreId = 'livre-1';
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([]);

        $result = $this->service->verifierDisponibilite($livreId);
        $this->assertFalse($result);
    }

    public function test_verifier_disponibilite_retourne_false_si_aucun_exemplaire_disponible(): void
    {
        $livreId = 'livre-1';
        $exemplaireNonDispo = $this->creerExemplaire(false);
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([$exemplaireNonDispo]);

        $result = $this->service->verifierDisponibilite($livreId);
        $this->assertFalse($result);
    }

    public function test_verifier_disponibilite_retourne_true_si_au_moins_un_exemplaire_disponible(): void
    {
        $livreId = 'livre-1';
        $exemplaireDispo = $this->creerExemplaire(true);
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([$exemplaireDispo]);

        $result = $this->service->verifierDisponibilite($livreId);
        $this->assertTrue($result);
    }

    public function test_verifier_disponibilite_ignore_exemplaires_non_disponibles(): void
    {
        $livreId = 'livre-1';
        $dispo = $this->creerExemplaire(true);
        $nonDispo1 = $this->creerExemplaire(false);
        $nonDispo2 = $this->creerExemplaire(false);
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([$nonDispo1, $dispo, $nonDispo2]);

        $result = $this->service->verifierDisponibilite($livreId);
        $this->assertTrue($result);
    }

    // ========== TESTS POUR obtenirExemplaireDisponible ==========

    public function test_obtenir_exemplaire_disponible_retourne_null_si_aucun_exemplaire_trouve(): void
    {
        $livreId = 'livre-1';
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([]);

        $result = $this->service->obtenirExemplaireDisponible($livreId);
        $this->assertNull($result);
    }

    public function test_obtenir_exemplaire_disponible_retourne_null_si_tableau_vide(): void
    {
        $livreId = 'livre-1';
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([]);

        $result = $this->service->obtenirExemplaireDisponible($livreId);
        $this->assertNull($result);
    }

    public function test_obtenir_exemplaire_disponible_retourne_null_si_aucun_disponible(): void
    {
        $livreId = 'livre-1';
        $exemplaireNonDispo = $this->creerExemplaire(false);
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([$exemplaireNonDispo]);

        $result = $this->service->obtenirExemplaireDisponible($livreId);
        $this->assertNull($result);
    }

    public function test_obtenir_exemplaire_disponible_retourne_le_premier_exemplaire_disponible(): void
    {
        $livreId = 'livre-1';
        $dispo1 = $this->creerExemplaire(true);
        $dispo2 = $this->creerExemplaire(true);
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([$dispo1, $dispo2]);

        $result = $this->service->obtenirExemplaireDisponible($livreId);
        $this->assertSame($dispo1, $result);
    }

    public function test_obtenir_exemplaire_disponible_ignore_les_non_disponibles_et_prend_le_premier_disponible(): void
    {
        $livreId = 'livre-1';
        $nonDispo = $this->creerExemplaire(false);
        $dispo = $this->creerExemplaire(true);
        $this->repository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with($livreId)
            ->willReturn([$nonDispo, $dispo]);

        $result = $this->service->obtenirExemplaireDisponible($livreId);
        $this->assertSame($dispo, $result);
    }

    // ========== Méthode utilitaire pour créer des exemplaires réels ==========

    private function creerExemplaire(bool $disponible): Exemplaire
    {
        $statut = $disponible ? StatutExemplaire::DISPONIBLE : StatutExemplaire::EMPRUNTE;
        // Génération d'un ID et d'un code-barre uniques pour éviter les interférences
        $uniqueId = uniqid();

        $exemplaire = new Exemplaire(
            id: $uniqueId,
            codeBarre: 'CODE-'.$uniqueId,
            statut: $statut
        );
        $exemplaire->setLivre(new Livre(
            id: 'livre-1',
            titre: 'Titre de Test',
            auteur: 'Auteur de Test',
            isbn: 'ISBN-TEST',
            datePublication: new \DateTimeImmutable('2020-01-01')
        ));

        return $exemplaire;
    }
}
