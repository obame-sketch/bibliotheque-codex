<?php

namespace Tests\Unit\Domain\Livre;

use App\Domain\Livre\Livre;
use PHPUnit\Framework\TestCase;

class LivreTest extends TestCase
{
    private Livre $livre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livre = new Livre(
            id: '1',
            titre: 'Clean Code',
            auteur: 'Robert C. Martin',
            isbn: '978-0132350884',
            datePublication: new \DateTimeImmutable('2008-08-01')
        );
    }

    public function test_livre_creation(): void
    {
        $this->assertEquals('1', $this->livre->id());
        $this->assertEquals('Clean Code', $this->livre->titre());
        $this->assertEquals('Robert C. Martin', $this->livre->auteur());
        $this->assertEquals('978-0132350884', $this->livre->isbn());
    }

    public function test_titre_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Livre('1', '', 'Auteur', 'ISBN', new \DateTimeImmutable());
    }

    public function test_auteur_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Livre('1', 'Titre', '', 'ISBN', new \DateTimeImmutable());
    }

    public function test_isbn_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Livre('1', 'Titre', 'Auteur', '', new \DateTimeImmutable());
    }

    public function test_mettre_a_jour_titre(): void
    {
        $this->livre->mettreAJourTitre('Design Patterns');
        $this->assertEquals('Design Patterns', $this->livre->titre());
    }

    public function test_estPublie_retourne_true_pour_date_passee(): void
    {
        $this->assertTrue($this->livre->estPublie());
    }

    public function test_estPublie_retourne_false_pour_date_future(): void
    {
        $futureLivre = new Livre(
            '2',
            'Future Book',
            'Auteur',
            'ISBN123',
            new \DateTimeImmutable('2099-01-01')
        );
        $this->assertFalse($futureLivre->estPublie());
    }
}
