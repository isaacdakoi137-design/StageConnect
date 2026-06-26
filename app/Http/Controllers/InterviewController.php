<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InterviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Entreprise')) {
            $interviews = Interview::whereHas('application.offer', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->with('application.user', 'application.offer')->latest()->get();
            
            return view('interviews.index', compact('interviews'));
        }

        // For Student
        $interviews = Interview::whereHas('application', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('application.offer')->latest()->get();

        return view('interviews.index', compact('interviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'scheduled_at' => 'required|date|after:now',
        ]);

        $application = Application::find($request->application_id);
        
        // Ensure this company owns the offer
        if ($application->offer->user_id !== Auth::id()) {
            return abort(403);
        }

        // Create Interview
        $interview = Interview::create([
            'application_id' => $application->id,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'Programmé',
            'video_room_id' => (string) Str::uuid()
        ]);

        // Automatically update application status to 'entretien' or equivalent
        $application->update(['status' => 'Entretien planifié']);

        // Create database notification for the student
        \App\Models\Notification::create([
            'user_id' => $application->user_id,
            'title' => '📅 Entretien planifié',
            'message' => "L'entreprise {$application->offer->company} a planifié un entretien pour le poste '{$application->offer->title}' le " . \Carbon\Carbon::parse($request->scheduled_at)->format('d/m/Y à H:i') . ".",
            'type' => 'interview',
            'link' => route('interviews.index')
        ]);

        return redirect()->back()->with('success', 'Entretien planifié avec succès.');
    }

    public function show(Interview $interview)
    {
        $user = Auth::user();
        $application = $interview->application;

        // Ensure user is authorized to join this room
        if ($user->id !== $application->user_id && $user->id !== $application->offer->user_id) {
            return abort(403);
        }

        return view('interviews.show', compact('interview', 'user', 'application'));
    }

    public function saveReport(Request $request, Interview $interview)
    {
        $request->validate([
            'report_summary' => 'required|string',
            'status' => 'required|in:Programmé,Complété,Annulé'
        ]);

        // Ensure company is editing
        if (!Auth::user()->hasRole('Entreprise') || $interview->application->offer->user_id !== Auth::id()) {
            return abort(403);
        }

        $interview->update([
            'report_summary' => $request->report_summary,
            'status' => $request->status
        ]);

        // Notify student of interview status change
        \App\Models\Notification::create([
            'user_id' => $interview->application->user_id,
            'title' => '📝 Compte-rendu d\'entretien',
            'message' => "Un compte-rendu a été rédigé pour votre entretien pour l'offre '{$interview->application->offer->title}'. Status: {$request->status}.",
            'type' => 'interview',
            'link' => route('interviews.index')
        ]);

        return redirect()->route('interviews.index')->with('success', 'Compte-rendu d\'entretien enregistré.');
    }
}
