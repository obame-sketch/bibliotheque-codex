<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\UseCase;

use App\Application\Lecteur\DTO\EmprunterLivreDto;
use App\Application\Lecteur\DTO\RetournerLivreDto;
use App\Application\Lecteur\UseCase\ConsulterCatalogueUseCase;
use App\Application\Lecteur\UseCase\EmprunterLivreUseCase;
use App\Application\Lecteur\UseCase\RechercherLivreUseCase;
use App\Application\Lecteur\UseCase\RetournerLivreUseCase;
use App\Application\Lecteur\UseCase\VoirMesEmpruntsUseCase;
use App\Application\Lecteur\UseCase\VerifierDisponibiliteUseCase;
use App\Domain\Common\Exception\DomainException;
use App\Domain\Emprunt\Emprunt;
use App\Domain\Emprunt\EmpruntRepositoryInterface;
use App\Domain\Emprunt\StatutEmprunt;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Lecteur\Lecteur;
use App\Domain\Lecteur\LecteurRepositoryInterface;
use App\Domain\Livre\Livre;
use App\Domain\Livre\LivreRepositoryInterface;
use App\Domain\Services\ServiceDisponibilite;
use App\Domain\Services\ServiceGestionEmprunt;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class LecteurUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private function createLecteur(): Lecteur
    {
        return new Lecteur(
            id: 'lecteur-1',
            nom: 'Dupont',
            prenom: 'Jean',
            email: 'jean@example.com',
            dateAdhesion: new \DateTimeImmutable('2024-01-01')
        );
    }

    private function createLivre(): Livre
    {
        return new Livre(
            id: 'livre-1',
            titre: 'Titre de Test',
            auteur: 'Auteur de Test',
            isbn: 'ISBN-TEST',
            datePublication: new \DateTimeImmutable('2020-01-01')
        );
    }

    private function createExemplaire(): Exemplaire
    {
        $exemplaire = new Exemplaire(
            id: 'exemplaire-1',
            codeBarre: 'CODE-001',
            statut: StatutExemplaire::DISPONIBLE
        );
        $exemplaire->setLivre($this->createLivre());
        return $exemplaire;
    }

    private function createEmprunt(Lecteur $lecteur, Exemplaire $exemplaire): Emprunt
    {
        return new Emprunt(
            id: 'emprunt-1',
            lecteur: $lecteur,
            exemplaire: $exemplaire,
            dateEmprunt: new \DateTimeImmutable(),
            dateRetourPrevue: new \DateTimeImmutable('+21 days')
        );
    }

    // ========== TESTS POUR ConsulterCatalogueUseCase ==========

    public function test_consulter_catalogue_retourne_tous_les_livres(): void
    {
        $livre1 = $this->createLivre();
        $livre2 = $this->createLivre();

        $livreRepository = Mockery::mock(LivreRepositoryInterface::class);
        $livreRepository->shouldReceive('findAll')
            ->once()
            ->andReturn([$livre1, $livre2]);

        $useCase = new ConsulterCatalogueUseCase($livreRepository);
        $result = $useCase->execute();

        $this->assertSame([$livre1, $livre2], $result);
        $this->assertCount(2, $result);
    }

    // ========== TESTS POUR RechercherLivreUseCase ==========

    public function test_rechercher_livre_avec_mot_cle_valide(): void
    {
        $livre1 = $this->createLivre();

        $livreRepository = Mockery::mock(LivreRepositoryInterface::class);
        $livreRepository->shouldReceive('rechercher')
            ->once()
            ->with('harry potter')
            ->andReturn([$livre1]);

        $useCase = new RechercherLivreUseCase($livreRepository);
        $result = $useCase->execute('harry potter');

        $this->assertSame([$livre1], $result);
    }

    public function test_rechercher_livre_retourne_tableau_vide_si_mot_cle_vide(): void
    {
        $livreRepository = Mockery::mock(LivreRepositoryInterface::class);
        $livreRepository->shouldNotReceive('rechercher');

        $useCase = new RechercherLivreUseCase($livreRepository);
        $result = $useCase->execute('   ');

        $this->assertSame([], $result);
    }

    // ========== TESTS POUR VoirMesEmpruntsUseCase ==========

    public function test_voir_mes_emprunts_retourne_les_emprunts(): void
    {
        $lecteur = $this->createLecteur();
        $exemplaire = $this->createExemplaire();
        $emprunt = $this->createEmprunt($lecteur, $exemplaire);

        $empruntRepository = Mockery::mock(EmpruntRepositoryInterface::class);
        $empruntRepository->shouldReceive('findByLecteurId')
            ->once()
            ->with('lecteur-1')
            ->andReturn([$emprunt]);

        $useCase = new VoirMesEmpruntsUseCase($empruntRepository);
        $result = $useCase->execute('lecteur-1');

        $this->assertSame([$emprunt], $result);
    }

    public function test_voir_mes_emprunts_retourne_tableau_vide(): void
    {
        $empruntRepository = Mockery::mock(EmpruntRepositoryInterface::class);
        $empruntRepository->shouldReceive('findByLecteurId')
            ->once()
            ->with('lecteur-1')
            ->andReturn([]);

        $useCase = new VoirMesEmpruntsUseCase($empruntRepository);
        $result = $useCase->execute('lecteur-1');

        $this->assertSame([], $result);
    }

    // ========== TESTS POUR VerifierDisponibiliteUseCase ==========

    public function test_verifier_disponibilite_retourne_vrai_si_disponible(): void
    {
        $exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);
        $exemplaireRepository->shouldReceive('findDisponiblesByLivre')
            ->once()
            ->with('livre-1')
            ->andReturn([$this->createExemplaire()]);

        $serviceDisponibilite = new ServiceDisponibilite($exemplaireRepository);

        $useCase = new VerifierDisponibiliteUseCase($serviceDisponibilite);
        $result = $useCase->execute('livre-1');

        $this->assertTrue($result);
    }

    public function test_verifier_disponibilite_retourne_faux_si_non_disponible(): void
    {
        $exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);
        $exemplaireRepository->shouldReceive('findDisponiblesByLivre')
            ->once()
            ->with('livre-1')
            ->andReturn([]);

        $serviceDisponibilite = new ServiceDisponibilite($exemplaireRepository);

        $useCase = new VerifierDisponibiliteUseCase($serviceDisponibilite);
        $result = $useCase->execute('livre-1');

        $this->assertFalse($result);
    }

    // ========== TESTS POUR EmprunterLivreUseCase ==========

    public function test_emprunter_livre_avec_succes(): void
    {
        $lecteur = $this->createLecteur();

        $lecteurRepository = Mockery::mock(LecteurRepositoryInterface::class);
        $lecteurRepository->shouldReceive('findById')
            ->once()
            ->with('lecteur-1')
            ->andReturn($lecteur);

        $exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);
        $exemplaireRepository->shouldReceive('findDisponiblesByLivre')
            ->twice()
            ->with('livre-1')
            ->andReturn([$this->createExemplaire()]);

        $empruntRepository = Mockery::mock(EmpruntRepositoryInterface::class);
        $empruntRepository->shouldReceive('save')
            ->once();

        $exemplaireRepository->shouldReceive('save')
            ->once();

        $serviceGestionEmprunt = new ServiceGestionEmprunt($empruntRepository, $exemplaireRepository);
        $serviceDisponibilite = new ServiceDisponibilite($exemplaireRepository);

        $livreRepository = Mockery::mock(LivreRepositoryInterface::class);

        $useCase = new EmprunterLivreUseCase(
            $lecteurRepository,
            $livreRepository,
            $serviceDisponibilite,
            $serviceGestionEmprunt
        );

        $dto = new EmprunterLivreDto('lecteur-1', 'livre-1');
        $result = $useCase->execute($dto);

        $this->assertInstanceOf(Emprunt::class, $result);
    }

    public function test_emprunter_livre_lève_exception_si_lecteur_introuvable(): void
    {
        $lecteurRepository = Mockery::mock(LecteurRepositoryInterface::class);
        $lecteurRepository->shouldReceive('findById')
            ->once()
            ->with('lecteur-invalide')
            ->andReturn(null);

        $livreRepository = Mockery::mock(LivreRepositoryInterface::class);
        $livreRepository->shouldNotReceive('findById');

        $exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);
        $serviceDisponibilite = new ServiceDisponibilite($exemplaireRepository);
        $serviceGestionEmprunt = new ServiceGestionEmprunt(
            Mockery::mock(EmpruntRepositoryInterface::class),
            Mockery::mock(ExemplaireRepositoryInterface::class)
        );

        $useCase = new EmprunterLivreUseCase(
            $lecteurRepository,
            $livreRepository,
            $serviceDisponibilite,
            $serviceGestionEmprunt
        );

        $dto = new EmprunterLivreDto('lecteur-invalide', 'livre-1');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Action impossible : le lecteur spécifié est introuvable.");

        $useCase->execute($dto);
    }

    public function test_emprunter_livre_lève_exception_si_livre_indisponible(): void
    {
        $lecteur = $this->createLecteur();

        $lecteurRepository = Mockery::mock(LecteurRepositoryInterface::class);
        $lecteurRepository->shouldReceive('findById')
            ->once()
            ->with('lecteur-1')
            ->andReturn($lecteur);

        $exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);
        $exemplaireRepository->shouldReceive('findDisponiblesByLivre')
            ->once()
            ->with('livre-1')
            ->andReturn([]);

        $serviceDisponibilite = new ServiceDisponibilite($exemplaireRepository);
        $serviceGestionEmprunt = new ServiceGestionEmprunt(
            Mockery::mock(EmpruntRepositoryInterface::class),
            Mockery::mock(ExemplaireRepositoryInterface::class)
        );

        $useCase = new EmprunterLivreUseCase(
            $lecteurRepository,
            Mockery::mock(LivreRepositoryInterface::class),
            $serviceDisponibilite,
            $serviceGestionEmprunt
        );

        $dto = new EmprunterLivreDto('lecteur-1', 'livre-1');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Action impossible : le livre demandé n'est pas disponible actuellement.");

        $useCase->execute($dto);
    }

    // ========== TESTS POUR RetournerLivreUseCase ==========

    public function test_retourner_livre_avec_succes(): void
    {
        $lecteur = $this->createLecteur();
        $exemplaire = $this->createExemplaire();
        $emprunt = $this->createEmprunt($lecteur, $exemplaire);

        $empruntRepository = Mockery::mock(EmpruntRepositoryInterface::class);
        $empruntRepository->shouldReceive('findById')
            ->once()
            ->with('emprunt-1')
            ->andReturn($emprunt);

        $exemplaireRepository = Mockery::mock(ExemplaireRepositoryInterface::class);
        $empruntRepository->shouldReceive('save')
            ->once();

        $serviceGestionEmprunt = new ServiceGestionEmprunt($empruntRepository, $exemplaireRepository);

        $useCase = new RetournerLivreUseCase($empruntRepository, $serviceGestionEmprunt);

        $dto = new RetournerLivreDto('emprunt-1');
        $useCase->execute($dto);

        $this->assertTrue(true);
    }

    public function test_retourner_livre_lève_exception_si_emprunt_introuvable(): void
    {
        $empruntRepository = Mockery::mock(EmpruntRepositoryInterface::class);
        $empruntRepository->shouldReceive('findById')
            ->once()
            ->with('emprunt-invalide')
            ->andReturn(null);

        $serviceGestionEmprunt = new ServiceGestionEmprunt(
            $empruntRepository,
            Mockery::mock(ExemplaireRepositoryInterface::class)
        );
        $empruntRepository->shouldNotReceive('save');

        $useCase = new RetournerLivreUseCase($empruntRepository, $serviceGestionEmprunt);

        $dto = new RetournerLivreDto('emprunt-invalide');

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Action impossible : la référence de l'emprunt est invalide.");

        $useCase->execute($dto);
    }
}