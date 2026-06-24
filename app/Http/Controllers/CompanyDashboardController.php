<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Offer;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{
    public function index()
    {
        $offers = Offer::where('user_id', Auth::id())
            ->latest()
            ->get();

        $offersCount = $offers->count();
        $applicationsCount = Application::whereIn('offer_id', $offers->pluck('id'))->count();

        return view(
            'company.dashboard',
            compact(
                'offers',
                'offersCount',
                'applicationsCount'
            )
        );
    }

    public function applications()
    {
        $offerIds = Offer::where('user_id', Auth::id())->pluck('id');

        $applications = Application::with(['user.student', 'offer'])
            ->whereIn('offer_id', $offerIds)
            ->latest()
            ->get();

        return view(
            'company.applications',
            compact('applications')
        );
    }

    public function showCandidate(User $user)
    {
        $user->load('student');

        return view(
            'company.candidate',
            compact('user')
        );
    }

    public function updateApplicationStatus(
        Request $request,
        Application $application
    ) {
        $request->validate([
            'status' => 'required|in:En attente,Acceptee,Refusee',
            'start_date' => 'nullable|date|required_if:status,Acceptee',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $ownsOffer = Offer::where('user_id', Auth::id())
            ->where('id', $application->offer_id)
            ->exists();

        abort_unless($ownsOffer, 403);

        $application->update([
            'status' => $request->status,
        ]);

        if ($request->status === 'Acceptee') {
            Stage::updateOrCreate(
                [
                    'student_id' => $application->user_id,
                    'offer_id' => $application->offer_id,
                ],
                [
                    'status' => 'En cours',
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]
            );
        }

        return back()->with(
            'success',
            'Statut mis à jour avec succès'
        );
    }
}
