<?php

namespace App\Http\Controllers;

use App\Infrastructure\Persistence\Eloquent\Livre;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index(Request $request)
    {
        $query = Livre::with(['bibliothecaire', 'exemplaires']);

        if ($request->filled('titre')) {
            $query->where('titre', 'like', '%' . $request->query('titre') . '%');
        }

        if ($request->filled('auteur')) {
            $query->where('auteur', 'like', '%' . $request->query('auteur') . '%');
        }

        if ($request->filled('isbn')) {
            $query->where('isbn', $request->query('isbn'));
        }

        if ($request->filled('bibliothecaire_id')) {
            $query->where('bibliothecaire_id', $request->query('bibliothecaire_id'));
        }

        if ($request->filled('disponible')) {
            $disponible = filter_var($request->query('disponible'), FILTER_VALIDATE_BOOLEAN);
            $query->whereHas('exemplaires', function ($subQuery) use ($disponible) {
                $subQuery->where('statut', $disponible ? 'DISPONIBLE' : 'EMPRUNTE');
            });
        }

        return $query->paginate(15);
    }

    public function show($id)
    {
        return Livre::with(['bibliothecaire', 'exemplaires'])->findOrFail($id);
    }
}
