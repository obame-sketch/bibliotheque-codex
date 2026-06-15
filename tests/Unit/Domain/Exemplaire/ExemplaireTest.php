<?php

namespace Tests\Unit\Domain\Exemplaire;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\StatutExemplaire;
use PHPUnit\Framework\TestCase;

class ExemplaireTest extends TestCase
{
    private Exemplaire $exemplaire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exemplaire = new Exemplaire(
            id: '1',
            codeBarre: 'CODE-001',
            statut: StatutExemplaire::DISPONIBLE
        );
    }

    public function test_exemplaire_creation(): void
    {
        $this->assertEquals('1', $this->exemplaire->id());
        $this->assertEquals('CODE-001', $this->exemplaire->codeBarre());
        $this->assertEquals(StatutExemplaire::DISPONIBLE, $this->exemplaire->statut());
    }

    public function test_code_barre_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Exemplaire('1', '', StatutExemplaire::DISPONIBLE);
    }

    public function test_est_disponible_retourne_true_pour_disponible(): void
    {
        $this->assertTrue($this->exemplaire->estDisponible());
    }

    public function test_est_disponible_retourne_false_pour_emprunte(): void
    {
        $exemplaire = new Exemplaire('1', 'CODE-001', StatutExemplaire::EMPRUNTE);
        $this->assertFalse($exemplaire->estDisponible());
    }

    public function test_emprunter_change_statut_a_emprunte(): void
    {
        $this->exemplaire->emprunter();
        $this->assertEquals(StatutExemplaire::EMPRUNTE, $this->exemplaire->statut());
    }

    public function test_emprunter_leve_exception_si_non_disponible(): void
    {
        $this->exemplaire->emprunter();
        $this->expectException(\DomainException::class);
        $this->exemplaire->emprunter();
    }

    public function test_retourner_change_statut_a_disponible(): void
    {
        $exemplaireEmprunte = new Exemplaire('1', 'CODE-001', StatutExemplaire::EMPRUNTE);
        $exemplaireEmprunte->retourner();
        $this->assertEquals(StatutExemplaire::DISPONIBLE, $exemplaireEmprunte->statut());
    }

    public function test_retourner_leve_exception_si_perdu(): void
    {
        $exemplairePierdu = new Exemplaire('1', 'CODE-001', StatutExemplaire::PERDU);
        $this->expectException(\DomainException::class);
        $exemplairePierdu->retourner();
    }
}
