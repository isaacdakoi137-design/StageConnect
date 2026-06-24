<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function store(Request $request, Offer $offer)
    {
        $request->validate([
            'message' => 'nullable|string|max:2000',
        ]);

        $exists = Application::where('user_id', Auth::id())
            ->where('offer_id', $offer->id)
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Vous avez déjà postulé à cette offre.'
            );
        }

        Application::create([
            'user_id' => Auth::id(),
            'offer_id' => $offer->id,
            'message' => $request->message,
            'status' => 'En attente',
        ]);

        return back()->with(
            'success',
            'Votre candidature a été envoyée.'
        );
    }

    public function index()
    {
        $query = Application::with(['user', 'offer'])->latest();

        if (Auth::user()->hasRole('Etudiant')) {
            $query->where('user_id', Auth::id());
        }

        $applications = $query->get();

        return view(
            'applications.index',
            compact('applications')
        );
    }
}
