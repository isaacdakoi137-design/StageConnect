<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $students = User::role('Etudiant')->count();
        $companies = User::role('Entreprise')->count();
        $offers = Offer::count();
        $applications = Application::count();

        $latestApplications = Application::with(['user', 'offer'])
            ->latest()
            ->take(5)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'students',
                'companies',
                'offers',
                'applications',
                'latestApplications'
            )
        );
    }

    public function applications()
    {
        $applications = Application::with(['user', 'offer'])
            ->latest()
            ->get();

        return view(
            'admin.applications',
            compact('applications')
        );
    }

    public function updateApplicationStatus(
        Request $request,
        Application $application
    ) {
        $request->validate([
            'status' => 'required|in:En attente,Acceptee,Refusee',
        ]);

        $application->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Statut mis à jour avec succès');
    }
}
