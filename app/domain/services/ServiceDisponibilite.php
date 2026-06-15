<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;

/**
 * Service de domaine qui encapsule la logique de disponibilité des livres.
 *
 * Fournit des méthodes pour vérifier s'il existe des exemplaires disponibles
 * pour un livre donné et pour obtenir un exemplaire disponible.
 */
final class ServiceDisponibilite
{

    private ExemplaireRepositoryInterface $exemplaireRepository;
    public function __construct(ExemplaireRepositoryInterface $exemplaireRepository)
    {
        $this->exemplaireRepository = $exemplaireRepository;
    }

    /**
     * Vérifie s'il existe au moins un exemplaire disponible pour un livre donné.
     *
     * @param  string  $livreId  Identifiant du livre
     * @return bool True si au moins un exemplaire est disponible
     */
    public function verifierDisponibilite(string $livreId): bool
    {
        $exemplaires = $this->exemplaireRepository->findDisponiblesByLivre($livreId);
        if(isset($exemplaires) && is_array($exemplaires) && count($exemplaires) > 0){
            $exemplairesDisponibles = array();
            foreach ($exemplaires as $value) {
                if($value->estDisponible()){
                    array_push($exemplairesDisponibles, $value);
                }
            }
            return count($exemplairesDisponibles) > 0;
        }
        return false;
    }

    /**
     * Retourne un exemplaire disponible pour le livre donné, ou null si aucun.
     *
     * @param  string  $livreId  Identifiant du livre
     * @return ?Exemplaire Un exemplaire disponible ou null
     */
    public function obtenirExemplaireDisponible(string $livreId): ?Exemplaire
    {
        $exemplaires = $this->exemplaireRepository->findDisponiblesByLivre($livreId);
        if(isset($exemplaires) && is_array($exemplaires) && count($exemplaires) > 0){
            $exemplairesDisponibles = array();
            foreach ($exemplaires as $value) {
                if($value->estDisponible()){
                    array_push($exemplairesDisponibles, $value);
                }
            }
            return $exemplairesDisponibles[0] ?? null;
        }
        return null;
    }
}
