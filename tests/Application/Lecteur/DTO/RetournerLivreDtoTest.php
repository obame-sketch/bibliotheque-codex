<?php

declare(strict_types=1);

namespace Tests\Application\Lecteur\DTO;

use App\Application\Lecteur\DTO\RetournerLivreDto;
use PHPUnit\Framework\TestCase;

class RetournerLivreDtoTest extends TestCase
{
    public function test_est_cree_avec_les_bonnes_valeurs(): void
    {
        $empruntId = 'emprunt-123';

        $dto = new RetournerLivreDto(empruntId: $empruntId);

        $this->assertEquals($empruntId, $dto->empruntId());
    }

    public function test_accesseur_empruntId_retourne_la_valeur_correcte(): void
    {
        $dto = new RetournerLivreDto(empruntId: 'emprunt-test');

        $this->assertEquals('emprunt-test', $dto->empruntId());
    }

    public function test_accepte_un_empruntId_vide(): void
    {
        $dto = new RetournerLivreDto(empruntId: '');

        $this->assertEquals('', $dto->empruntId());
    }
}