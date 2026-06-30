<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\DTO;

use App\Application\Lecteur\DTO\EmprunterLivreDto;
use PHPUnit\Framework\TestCase;

class EmprunterLivreDtoTest extends TestCase
{
    public function test_est_cree_avec_les_bonnes_valeurs(): void
    {
        $lecteurId = 'lecteur-123';
        $livreId = 'livre-456';

        $dto = new EmprunterLivreDto(lecteurId: $lecteurId, livreId: $livreId);

        $this->assertEquals($lecteurId, $dto->lecteurId());
        $this->assertEquals($livreId, $dto->livreId());
    }

    public function test_accesseur_lecteurId_retourne_la_valeur_correcte(): void
    {
        $dto = new EmprunterLivreDto(lecteurId: 'lecteur-test', livreId: 'livre-test');

        $this->assertEquals('lecteur-test', $dto->lecteurId());
    }

    public function test_accesseur_livreId_retourne_la_valeur_correcte(): void
    {
        $dto = new EmprunterLivreDto(lecteurId: 'lecteur-test', livreId: 'livre-test');

        $this->assertEquals('livre-test', $dto->livreId());
    }
}