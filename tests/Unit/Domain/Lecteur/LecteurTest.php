<?php

namespace Tests\Unit\Domain\Lecteur;

use App\Domain\Lecteur\Lecteur;
use PHPUnit\Framework\TestCase;

class LecteurTest extends TestCase
{
    private Lecteur $lecteur;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lecteur = new Lecteur(
            id: '1',
            nom: 'Dupont',
            prenom: 'Jean',
            email: 'jean.dupont@example.com',
            dateAdhesion: new \DateTimeImmutable('2024-01-01')
        );
    }

    public function test_lecteur_creation(): void
    {
        $this->assertEquals('1', $this->lecteur->id());
        $this->assertEquals('Dupont', $this->lecteur->nom());
        $this->assertEquals('Jean', $this->lecteur->prenom());
        $this->assertEquals('jean.dupont@example.com', $this->lecteur->email());
    }

    public function test_nom_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Lecteur('1', '', 'Jean', 'email@test.com', new \DateTimeImmutable());
    }

    public function test_prenom_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Lecteur('1', 'Dupont', '', 'email@test.com', new \DateTimeImmutable());
    }

    public function test_email_invalide_leve_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Lecteur('1', 'Dupont', 'Jean', 'email-invalide', new \DateTimeImmutable());
    }

    public function test_changer_email_valide(): void
    {
        $this->lecteur->changerEmail('nouveau@example.com');
        $this->assertEquals('nouveau@example.com', $this->lecteur->email());
    }

    public function test_renommer(): void
    {
        $this->lecteur->renommer('Martin', 'Paul');
        $this->assertEquals('Martin', $this->lecteur->nom());
        $this->assertEquals('Paul', $this->lecteur->prenom());
    }

    public function test_est_adherent_actif_pour_date_passee(): void
    {
        $this->assertTrue($this->lecteur->estAdherentActif());
    }

    public function test_est_adherent_actif_pour_date_future(): void
    {
        $futureLecteur = new Lecteur(
            '2',
            'Dupont',
            'Jean',
            'email@test.com',
            new \DateTimeImmutable('2099-01-01')
        );
        $this->assertFalse($futureLecteur->estAdherentActif());
    }
}
