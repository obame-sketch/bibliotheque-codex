<?php

declare(strict_types=1);

namespace App\Tests\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
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

    public function testVerifierDisponibiliteRetourneFalseSiAucunExemplaireTrouve(): void
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

    public function testVerifierDisponibiliteRetourneFalseSiTableauVide(): void
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

    public function testVerifierDisponibiliteRetourneFalseSiAucunExemplaireDisponible(): void
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

    public function testVerifierDisponibiliteRetourneTrueSiAuMoinsUnExemplaireDisponible(): void
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

    public function testVerifierDisponibiliteIgnoreExemplairesNonDisponibles(): void
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

    public function testObtenirExemplaireDisponibleRetourneNullSiAucunExemplaireTrouve(): void
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

    public function testObtenirExemplaireDisponibleRetourneNullSiTableauVide(): void
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

    public function testObtenirExemplaireDisponibleRetourneNullSiAucunDisponible(): void
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

    public function testObtenirExemplaireDisponibleRetourneLePremierExemplaireDisponible(): void
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

    public function testObtenirExemplaireDisponibleIgnoreLesNonDisponiblesEtPrendLePremierDisponible(): void
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
        return new Exemplaire(
            id: $uniqueId,
            codeBarre: 'CODE-' . $uniqueId,
            statut: $statut
        );
    }
}
