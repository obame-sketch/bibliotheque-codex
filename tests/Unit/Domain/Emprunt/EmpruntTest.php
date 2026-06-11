<?php

namespace Tests\Unit\Domain\Emprunt;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\StatutEmprunt;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use PHPUnit\Framework\TestCase;

class EmpruntTest extends TestCase
{
    private Emprunt $emprunt;

    private Lecteur $lecteur;

    private Exemplaire $exemplaire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lecteur = new Lecteur(
            '1',
            'Dupont',
            'Jean',
            'jean@example.com',
            new \DateTimeImmutable('2024-01-01')
        );

        $this->exemplaire = new Exemplaire(
            '1',
            'CODE-001',
            StatutExemplaire::DISPONIBLE
        );

        $now = new \DateTimeImmutable;
        $this->emprunt = new Emprunt(
            id: '1',
            lecteur: $this->lecteur,
            exemplaire: $this->exemplaire,
            dateEmprunt: $now,
            dateRetourPrevue: $now->modify('+21 days')
        );
    }

    public function test_emprunt_creation(): void
    {
        $this->assertEquals('1', $this->emprunt->id());
        $this->assertEquals($this->lecteur, $this->emprunt->lecteur());
        $this->assertEquals($this->exemplaire, $this->emprunt->exemplaire());
        $this->assertEquals(StatutEmprunt::EN_COURS, $this->emprunt->statut());
        $this->assertNull($this->emprunt->dateRetourEffective());
    }

    public function test_date_retour_prevue_doit_etre_posterieure_a_date_emprunt(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $now = new \DateTimeImmutable;
        new Emprunt(
            '1',
            $this->lecteur,
            $this->exemplaire,
            $now,
            $now->modify('-5 days')
        );
    }

    public function test_cloturer_emprunt(): void
    {
        $dateRetour = $this->emprunt->dateRetourPrevue();
        $this->emprunt->cloturer($dateRetour);

        $this->assertEquals(StatutEmprunt::RENDU, $this->emprunt->statut());
        $this->assertEquals($dateRetour, $this->emprunt->dateRetourEffective());
    }

    public function test_cloturer_emprunt_ne_peut_pas_etre_anterior_a_date_emprunt(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->emprunt->cloturer($this->emprunt->dateEmprunt()->modify('-1 day'));
    }

    public function test_est_en_retard_retourne_false_avant_date_limite(): void
    {
        $this->assertFalse($this->emprunt->estEnRetard());
    }

    public function test_est_en_retard_retourne_true_apres_date_limite(): void
    {
        $empruntAvecDateRetourPassee = new Emprunt(
            '2',
            $this->lecteur,
            $this->exemplaire,
            new \DateTimeImmutable('2024-01-01'),
            new \DateTimeImmutable('2024-01-10')
        );

        $this->assertTrue($empruntAvecDateRetourPassee->estEnRetard());
    }

    public function test_jours_de_retard_calcul_correct(): void
    {
        $empruntAvecRetard = new Emprunt(
            '2',
            $this->lecteur,
            $this->exemplaire,
            new \DateTimeImmutable('2024-01-01'),
            new \DateTimeImmutable('2024-01-10')
        );

        $retard = $empruntAvecRetard->joursDeRetard();
        $this->assertGreaterThan(0, $retard);
    }

    public function test_statut_devient_en_retard_si_date_depassee(): void
    {
        $empruntAvecRetard = new Emprunt(
            '2',
            $this->lecteur,
            $this->exemplaire,
            new \DateTimeImmutable('2024-01-01'),
            new \DateTimeImmutable('2024-01-10')
        );

        $this->assertEquals(StatutEmprunt::EN_RETARD, $empruntAvecRetard->statut());
    }
}
