<?php

namespace Tests\Domain\Services;

use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use App\Domain\Services\ServiceGestionEmprunt;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ServiceGestionEmpruntTest extends TestCase
{
    private ServiceGestionEmprunt $service;

    private MockObject|EmpruntRepositoryInterface $empruntRepository;

    private MockObject|ExemplaireRepositoryInterface $exemplaireRepository;

    private Lecteur $lecteur;

    private Exemplaire $exemplaire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empruntRepository = $this->createMock(EmpruntRepositoryInterface::class);
        $this->exemplaireRepository = $this->createMock(ExemplaireRepositoryInterface::class);
        $this->service = new ServiceGestionEmprunt($this->empruntRepository, $this->exemplaireRepository);

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
        $this->exemplaire->setLivre(new \App\Domain\Livre\Livre(
            id: 'livre-1',
            titre: 'Titre de Test',
            auteur: 'Auteur de Test',
            isbn: 'ISBN-TEST',
            datePublication: new \DateTimeImmutable('2020-01-01')
        ));
    }

    public function test_enregistrer_emprunt_change_statut_exemplaire(): void
    {
        $this->exemplaireRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->exemplaire);

        $this->empruntRepository
            ->expects($this->once())
            ->method('save');

        $emprunt = $this->service->enregistrerEmprunt($this->lecteur, $this->exemplaire);

        $this->assertInstanceOf(Emprunt::class, $emprunt);
        $this->assertEquals($this->lecteur, $emprunt->getLecteur()); // Already using getLecteur(), but `exemplaire()` below needs fixing.
        $this->assertEquals($this->exemplaire, $emprunt->getExemplaire());
        $this->assertEquals(StatutExemplaire::EMPRUNTE, $this->exemplaire->statut());
    }

    public function test_enregistrer_retour_clot_emprunt(): void
    {
        $now = new \DateTimeImmutable;
        $emprunt = new Emprunt(
            '1',
            $this->lecteur,
            $this->exemplaire,
            $now,
            $now->modify('+21 days')
        );

        $this->exemplaireRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->exemplaire);

        $this->empruntRepository
            ->expects($this->once())
            ->method('save')
            ->with($emprunt);

        $this->service->enregistrerRetour($emprunt);

        $this->assertEquals(StatutExemplaire::DISPONIBLE, $emprunt->getExemplaire()->statut());
    }

    public function test_calculer_retard(): void
    {
        $empruntAvecRetard = new Emprunt(
            '1',
            $this->lecteur,
            $this->exemplaire,
            new \DateTimeImmutable('2024-01-01'),
            new \DateTimeImmutable('2024-01-10')
        );

        $retard = $this->service->calculerRetard($empruntAvecRetard);

        $this->assertGreaterThan(0, $retard);
    }
}
