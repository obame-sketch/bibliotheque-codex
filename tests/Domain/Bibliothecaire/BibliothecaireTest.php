<?php

namespace Tests\Domain\Bibliothecaire;

use App\Domain\Bibliothecaire\Bibliothecaire;
use PHPUnit\Framework\TestCase;

class BibliothecaireTest extends TestCase
{
    private Bibliothecaire $bibliothecaire;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bibliothecaire = new Bibliothecaire(
            id: '1',
            nom: 'Martin',
            prenom: 'Sophie',
            email: 'sophie.martin@library.com'
        );
    }

    public function test_bibliothecaire_creation(): void
    {
        $this->assertEquals('1', $this->bibliothecaire->id());
        $this->assertEquals('Martin', $this->bibliothecaire->nom());
        $this->assertEquals('Sophie', $this->bibliothecaire->prenom());
        $this->assertEquals('sophie.martin@library.com', $this->bibliothecaire->email());
    }

    public function test_nom_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Bibliothecaire('1', '', 'Sophie', 'email@test.com');
    }

    public function test_prenom_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Bibliothecaire('1', 'Martin', '', 'email@test.com');
    }

    public function test_email_invalide_leve_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Bibliothecaire('1', 'Martin', 'Sophie', 'email-invalide');
    }

    public function test_renommer(): void
    {
        $this->bibliothecaire->renommer('Dupont', 'Marie');
        $this->assertEquals('Dupont', $this->bibliothecaire->nom());
        $this->assertEquals('Marie', $this->bibliothecaire->prenom());
    }

    public function test_changer_email(): void
    {
        $this->bibliothecaire->changerEmail('marie.dupont@library.com');
        $this->assertEquals('marie.dupont@library.com', $this->bibliothecaire->email());
    }

    public function test_changer_email_invalide_leve_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->bibliothecaire->changerEmail('email-invalide');
    }
}
