<?php

namespace Tests\Unit\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Services\ServiceDisponibilite;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ServiceDisponibiliteTest extends TestCase
{
    private ServiceDisponibilite $service;
    private MockObject|ExemplaireRepositoryInterface $exemplaireRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exemplaireRepository = $this->createMock(ExemplaireRepositoryInterface::class);
        $this->service = new ServiceDisponibilite($this->exemplaireRepository);
    }

    public function test_verifier_disponibilite_retourne_true_si_exemplaires_disponibles(): void
    {
        $exemplaire = new Exemplaire('1', 'CODE-001', StatutExemplaire::DISPONIBLE);
        $this->exemplaireRepository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with('livre-1')
            ->willReturn([$exemplaire]);

        $this->assertTrue($this->service->verifierDisponibilite('livre-1'));
    }

    public function test_verifier_disponibilite_retourne_false_si_aucun_exemplaire(): void
    {
        $this->exemplaireRepository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with('livre-1')
            ->willReturn([]);

        $this->assertFalse($this->service->verifierDisponibilite('livre-1'));
    }

    public function test_obtenir_exemplaire_disponible_retourne_premier_exemplaire(): void
    {
        $exemplaire = new Exemplaire('1', 'CODE-001', StatutExemplaire::DISPONIBLE);
        $this->exemplaireRepository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with('livre-1')
            ->willReturn([$exemplaire]);

        $result = $this->service->obtenirExemplaireDisponible('livre-1');

        $this->assertEquals($exemplaire, $result);
    }

    public function test_obtenir_exemplaire_disponible_retourne_null_si_aucun(): void
    {
        $this->exemplaireRepository
            ->expects($this->once())
            ->method('findDisponiblesByLivre')
            ->with('livre-1')
            ->willReturn([]);

        $result = $this->service->obtenirExemplaireDisponible('livre-1');

        $this->assertNull($result);
    }
}
