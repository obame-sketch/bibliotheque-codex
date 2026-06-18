<?php

namespace Tests\Domain\Livre;

use App\Domain\Livre\Livre;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires pour l'entité de domaine Livre
 *
 * Cette classe teste tous les comportements de l'entité Livre :
 * - Création d'une instance avec validation
 * - Accès aux propriétés via les accesseurs
 * - Modification des propriétés via les mutateurs avec validation
 * - Détermination du statut de publication
 */
class LivreTest extends TestCase
{
    /**
     * Instance de Livre utilisée dans les tests
     */
    private Livre $livre;

    /**
     * Configuration préalable pour chaque test
     *
     * Cette méthode crée une instance standard de Livre avec des données valides.
     * Elle est exécutée avant chaque test (méthode test_*).
     */
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

    /**
     * Teste la création d'un livre valide et l'accès à ses propriétés
     *
     * Vérifie que :
     * - L'identifiant est correctement stocké et accessible
     * - Le titre est correctement stocké et accessible
     * - L'auteur est correctement stocké et accessible
     * - L'ISBN est correctement stocké et accessible
     */
    public function test_livre_creation(): void
    {
        $this->assertEquals('1', $this->livre->id());
        $this->assertEquals('Clean Code', $this->livre->titre());
        $this->assertEquals('Robert C. Martin', $this->livre->auteur());
        $this->assertEquals('978-0132350884', $this->livre->isbn());
    }

    /**
     * Teste que la création d'un livre avec un titre vide échoue
     *
     * Vérifie que le constructeur lève une InvalidArgumentException
     * si on essaie de créer un livre avec un titre vide.
     */
    public function test_titre_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Livre('1', '', 'Auteur', 'ISBN', new \DateTimeImmutable);
    }

    /**
     * Teste que la création d'un livre avec un auteur vide échoue
     *
     * Vérifie que le constructeur lève une InvalidArgumentException
     * si on essaie de créer un livre avec un auteur vide.
     */
    public function test_auteur_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Livre('1', 'Titre', '', 'ISBN', new \DateTimeImmutable);
    }

    /**
     * Teste que la création d'un livre avec un ISBN vide échoue
     *
     * Vérifie que le constructeur lève une InvalidArgumentException
     * si on essaie de créer un livre avec un ISBN vide.
     */
    public function test_isbn_ne_peut_pas_etre_vide(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Livre('1', 'Titre', 'Auteur', '', new \DateTimeImmutable);
    }

    /**
     * Teste la mise à jour du titre du livre
     *
     * Vérifie que :
     * - La méthode mettreAJourTitre() change le titre
     * - Le nouveau titre est correctement accessible via le mutateur
     */
    public function test_mettre_a_jour_titre(): void
    {
        $this->livre->mettreAJourTitre('Design Patterns');
        $this->assertEquals('Design Patterns', $this->livre->titre());
    }

    /**
     * Teste le changement de l'auteur du livre
     *
     * Vérifie que :
     * - La méthode changerAuteur() change l'auteur
     * - Le nouvel auteur est correctement accessible via l'accesseur
     */
    public function test_changer_auteur(): void
    {
        $this->livre->changerAuteur('Martin Fowler');
        $this->assertEquals('Martin Fowler', $this->livre->auteur());
    }

    /**
     * Teste le changement de l'ISBN du livre
     *
     * Vérifie que :
     * - La méthode changerIsbn() change l'ISBN
     * - Le nouvel ISBN est correctement accessible via l'accesseur
     */
    public function test_changer_isbn(): void
    {
        $this->livre->changerIsbn('978-0201485677');
        $this->assertEquals('978-0201485677', $this->livre->isbn());
    }

    /**
     * Teste que estPublie() retourne true pour un livre avec une date de publication passée
     *
     * Le livre testé a une date de publication du 2008-08-01 (passée).
     * La méthode estPublie() doit donc retourner true.
     */
    public function test_est_publie_retourne_true_pour_date_passee(): void
    {
        $this->assertTrue($this->livre->estPublie());
    }

    /**
     * Teste que estPublie() retourne false pour un livre avec une date de publication future
     *
     * Crée un nouveau livre avec une date de publication future (2099-01-01).
     * La méthode estPublie() doit retourner false pour ce livre.
     */
    public function test_est_publie_retourne_false_pour_date_future(): void
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
