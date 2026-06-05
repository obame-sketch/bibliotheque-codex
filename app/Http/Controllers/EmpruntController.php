<?php

namespace App\Http\Controllers;

use App\Infrastructure\Persistence\Eloquent\Emprunt;
use App\Infrastructure\Persistence\Eloquent\Exemplaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    public function index()
    {
        return Emprunt::with(['lecteur', 'exemplaire.livre'])->get();
    }

    public function show($id)
    {
        return Emprunt::with(['lecteur', 'exemplaire.livre'])->findOrFail($id);
    }

    public function emprunter(Request $request)
    {
        $data = $request->validate([
            'lecteur_id' => 'required|exists:lecteurs,id',
            'exemplaire_id' => 'required|exists:exemplaires,id',
            'dateEmprunt' => 'required|date',
            'dateRetourPrevue' => 'required|date|after_or_equal:dateEmprunt',
        ]);

        $exemplaire = Exemplaire::findOrFail($data['exemplaire_id']);

        if ($exemplaire->statut !== Exemplaire::STATUT_DISPONIBLE) {
            return response()->json([
                'message' => 'L’exemplaire n’est pas disponible pour un emprunt.',
            ], 422);
        }

        return DB::transaction(function () use ($data, $exemplaire) {
            $exemplaire->update(['statut' => Exemplaire::STATUT_EMPRUNTE]);

            $emprunt = Emprunt::create([
                'lecteur_id' => $data['lecteur_id'],
                'exemplaire_id' => $data['exemplaire_id'],
                'dateEmprunt' => $data['dateEmprunt'],
                'dateRetourPrevue' => $data['dateRetourPrevue'],
                'statut' => Emprunt::STATUT_EN_COURS,
            ]);

            return response()->json($emprunt->load(['lecteur', 'exemplaire.livre']), 201);
        });
    }

    public function retourner($id)
    {
        $emprunt = Emprunt::with('exemplaire')->findOrFail($id);

        if ($emprunt->statut !== Emprunt::STATUT_EN_COURS && $emprunt->statut !== Emprunt::STATUT_EN_RETARD) {
            return response()->json([
                'message' => 'Cet emprunt ne peut pas être retourné.',
            ], 422);
        }

        return DB::transaction(function () use ($emprunt) {
            $emprunt->update([
                'dateRetourEffective' => now()->toDateString(),
                'statut' => Emprunt::STATUT_TERMINE,
            ]);

            $emprunt->exemplaire->update(['statut' => Exemplaire::STATUT_DISPONIBLE]);

            return response()->json($emprunt->load(['lecteur', 'exemplaire.livre']));
        });
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lecteur_id' => 'required|exists:lecteurs,id',
            'exemplaire_id' => 'required|exists:exemplaires,id',
            'dateEmprunt' => 'required|date',
            'dateRetourPrevue' => 'required|date|after_or_equal:dateEmprunt',
            'statut' => 'required|in:' . implode(',', Emprunt::STATUTS),
        ]);

        return Emprunt::create($data);
    }

    public function update(Request $request, $id)
    {
        $emprunt = Emprunt::findOrFail($id);

        $data = $request->validate([
            'dateRetourEffective' => 'nullable|date',
            'statut' => 'sometimes|in:' . implode(',', Emprunt::STATUTS),
        ]);

        $emprunt->update($data);

        return $emprunt;
    }

    public function destroy($id)
    {
        $emprunt = Emprunt::findOrFail($id);
        $emprunt->delete();

        return response()->noContent();
    }
}
