<?php

declare(strict_types=1);

namespace App\Domain\Exemplaire;

use App\Domain\Livre\Livre;

/**
 * Entité représentant un exemplaire physique ou numérique d'un livre.
 *
 * Un exemplaire possède un identifiant, un code-barres et un statut. Cette
 * classe fournit les opérations métier sur un exemplaire : emprunter,
 * retourner et vérifier la disponibilité.
 */
final class Exemplaire
{
    private Livre $livre;
    public function __construct(
        private readonly string $id,
        private string $codeBarre,
        private StatutExemplaire $statut,
    ) {
        $this->guardNonVide($codeBarre, 'codeBarre');
    }

    public function setLivre(Livre $livre): void
    {
        $this->livre = $livre;
    }

    public function getLivre(): Livre
    {
        return $this->livre;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function livreId(): string
    {
        return $this->livre->id();
    }

    public function codeBarre(): string
    {
        return $this->codeBarre;
    }

    public function statut(): StatutExemplaire
    {
        return $this->statut;
    }

    /**
     * Marque l'exemplaire comme emprunté.
     *
     * Lève une DomainException si l'exemplaire n'est pas disponible.
     *
     * @throws \DomainException
     */
    public function emprunter(): void
    {
        if (! $this->estDisponible()) {
            throw new \DomainException('Cet exemplaire n\'est pas disponible pour un emprunt.');
        }

        $this->statut = StatutExemplaire::EMPRUNTE;
    }

    /**
     * Retourne l'exemplaire et le marque comme disponible.
     *
     * Lève une DomainException si l'exemplaire est marqué perdu.
     *
     * @throws \DomainException
     */
    public function retourner(): void
    {
        if ($this->statut === StatutExemplaire::PERDU) {
            throw new \DomainException('Un exemplaire perdu ne peut pas être retourné.');
        }

        $this->statut = StatutExemplaire::DISPONIBLE;
    }

    public function estDisponible(): bool
    {
        return $this->statut === StatutExemplaire::DISPONIBLE;
    }

    /**
     * Valide qu'un champ texte n'est pas vide.
     *
     * @param  string  $valeur  Valeur à valider
     * @param  string  $champ  Nom du champ (pour le message d'erreur)
     *
     * @throws \InvalidArgumentException
     */
    private function guardNonVide(string $valeur, string $champ): void
    {
        if ($valeur === '') {
            throw new \InvalidArgumentException(sprintf('Le champ "%s" ne peut pas être vide.', $champ));
        }
    }
}
