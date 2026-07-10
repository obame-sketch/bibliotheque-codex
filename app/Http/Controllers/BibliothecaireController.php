<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Bibliothecaire\DTO\AjouterLivreDto;
use App\Application\Bibliothecaire\UseCase\AjouterLivreUseCase;
use App\Domain\Exemplaire\Exemplaire;
use App\Domain\Exemplaire\ExemplaireRepositoryInterface;
use App\Domain\Exemplaire\StatutExemplaire;
use App\Domain\Livre\LivreRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class BibliothecaireController extends Controller
{
    public function __construct(
        private readonly AjouterLivreUseCase $ajouterLivreUseCase,
        private readonly LivreRepositoryInterface $livreRepository,
        private readonly ExemplaireRepositoryInterface $exemplaireRepository,
    ) {}

    public function ajouterLivre(Request $request): JsonResponse
    {
        $datePublication = $this->resolveDatePublication($request);

        $dto = new AjouterLivreDto(
            titre: (string) $this->resolveInput($request, ['titre'], ''),
            auteur: (string) $this->resolveInput($request, ['auteur'], ''),
            isbn: (string) $this->resolveInput($request, ['isbn'], ''),
            datePublication: $datePublication,
            nombreExemplaires: max(1, (int) $this->resolveInput($request, ['nombreExemplaires', 'nombre_exemplaires'], 1)),
            categorie: (string) $this->resolveInput($request, ['categorie', 'categorie_livre'], ''),
        );

        $livre = $this->ajouterLivreUseCase->execute($dto);

        return new JsonResponse([
            'id' => $livre->id(),
            'titre' => $livre->titre(),
            'auteur' => $livre->auteur(),
            'isbn' => $livre->isbn(),
            'categorie' => $livre->categorie(),
            'datePublication' => $livre->datePublication()->format('Y-m-d'),
        ], JsonResponse::HTTP_CREATED);
    }

    public function listerLivres(): JsonResponse
    {
        $livres = $this->livreRepository->findAll();

        return new JsonResponse(array_map(fn ($livre) => [
            'id' => $livre->id(),
            'titre' => $livre->titre(),
            'auteur' => $livre->auteur(),
            'isbn' => $livre->isbn(),
            'categorie' => $livre->categorie(),
            'datePublication' => $livre->datePublication()->format('Y-m-d'),
        ], $livres), JsonResponse::HTTP_OK);
    }

    public function afficherLivre(string $id): JsonResponse
    {
        $livre = $this->livreRepository->findById($id);

        if ($livre === null) {
            return new JsonResponse(['message' => 'Livre introuvable.'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $livre->id(),
            'titre' => $livre->titre(),
            'auteur' => $livre->auteur(),
            'isbn' => $livre->isbn(),
            'categorie' => $livre->categorie(),
            'datePublication' => $livre->datePublication()->format('Y-m-d'),
        ], JsonResponse::HTTP_OK);
    }

    public function ajouterExemplaire(Request $request): JsonResponse
    {
        $livreId = (string) $this->resolveInput($request, ['livreId', 'livre_id'], '');
        $numeroInventaire = (string) $this->resolveInput($request, ['numeroInventaire', 'numero_inventaire'], '');

        $livre = $this->livreRepository->findById($livreId);

        if ($livre === null) {
            return new JsonResponse(['message' => 'Livre introuvable.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $exemplaire = new Exemplaire(
            codeBarre: $numeroInventaire !== '' ? $numeroInventaire : sprintf('%s-1', $livreId),
            statut: StatutExemplaire::DISPONIBLE,
            id: null,
        );
        $exemplaire->setLivre($livre);

        $savedExemplaire = $this->exemplaireRepository->save($exemplaire);

        return new JsonResponse([
            'id' => $savedExemplaire?->id(),
            'livreId' => $livre->id(),
            'numeroInventaire' => $savedExemplaire?->codeBarre(),
            'disponible' => $savedExemplaire?->estDisponible(),
            'dateAcquisition' => now()->toDateString(),
        ], JsonResponse::HTTP_CREATED);
    }

    private function resolveDatePublication(Request $request): \DateTimeImmutable
    {
        $rawValue = $this->resolveInput($request, ['datePublication', 'date_publication'], '');

        return $rawValue !== '' ? new \DateTimeImmutable((string) $rawValue) : new \DateTimeImmutable('now');
    }

    private function resolveInput(Request $request, array $keys, mixed $default): mixed
    {
        foreach ($keys as $key) {
            if ($request->filled($key)) {
                return $request->input($key);
            }
        }

        return $default;
    }
}
