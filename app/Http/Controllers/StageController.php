<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\User;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Etudiant')) {
            $stages = Stage::with(['offer', 'supervisor'])
                ->where('student_id', $user->id)
                ->latest()
                ->get();
        } elseif ($user->hasRole('Encadreur')) {
            $stages = Stage::with(['offer', 'student'])
                ->where('supervisor_id', $user->id)
                ->latest()
                ->get();
        } elseif ($user->hasRole('Entreprise')) {
            $stages = Stage::with(['offer', 'student', 'supervisor'])
                ->whereHas('offer', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->get();
        } else {
            // Admin or Ecole: load all
            $stages = Stage::with(['offer', 'student', 'supervisor'])
                ->latest()
                ->get();
        }

        return view('stages.index', compact('stages'));
    }

    public function show(Stage $stage)
    {
        $user = Auth::user();
        $stage->load(['offer', 'student', 'supervisor', 'weeklyReports']);

        // Check permission to view
        $isStudent = $user->id === $stage->student_id;
        $isSupervisor = $user->id === $stage->supervisor_id;
        $isCompany = $user->id === $stage->offer->user_id;
        $isSchoolOrAdmin = $user->hasRole('Ecole') || $user->hasRole('Admin');

        if (!$isStudent && !$isSupervisor && !$isCompany && !$isSchoolOrAdmin) {
            abort(403);
        }

        // Get list of potential supervisors for company to assign
        $supervisors = [];
        if ($isCompany) {
            $supervisors = User::role('Encadreur')->get();
        }

        return view('stages.show', compact('stage', 'supervisors'));
    }

    public function assignSupervisor(Request $request, Stage $stage)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id'
        ]);

        // Ensure current user is the company owner
        if (Auth::id() !== $stage->offer->user_id) {
            abort(403);
        }

        $supervisor = User::find($request->supervisor_id);
        if (!$supervisor->hasRole('Encadreur')) {
            return redirect()->back()->with('error', 'L\'utilisateur choisi n\'est pas un encadreur.');
        }

        $stage->update(['supervisor_id' => $request->supervisor_id]);

        // Notify supervisor
        \App\Models\Notification::create([
            'user_id' => $request->supervisor_id,
            'title' => '📋 Nouveau stage à encadrer',
            'message' => "Vous avez été désigné comme encadreur pour le stage de {$stage->student->name} chez {$stage->offer->company}.",
            'type' => 'stage',
            'link' => route('stages.index')
        ]);

        return redirect()->back()->with('success', 'Encadreur assigné avec succès.');
    }

    public function storeWeeklyReport(Request $request, Stage $stage)
    {
        $request->validate([
            'week_number' => 'required|integer|min:1',
            'tasks_done' => 'required|string',
            'difficulties' => 'nullable|string',
            'observations' => 'nullable|string'
        ]);

        // Ensure student is submitting
        if (Auth::id() !== $stage->student_id) {
            abort(403);
        }

        // Check if report for this week already exists
        $exists = WeeklyReport::where('stage_id', $stage->id)
            ->where('week_number', $request->week_number)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Le rapport de cette semaine a déjà été soumis.');
        }

        WeeklyReport::create([
            'stage_id' => $stage->id,
            'week_number' => $request->week_number,
            'tasks_done' => $request->tasks_done,
            'difficulties' => $request->difficulties,
            'observations' => $request->observations,
            'status' => 'En attente'
        ]);

        // Notify supervisor if exists
        if ($stage->supervisor_id) {
            \App\Models\Notification::create([
                'user_id' => $stage->supervisor_id,
                'title' => '📝 Nouveau carnet de bord',
                'message' => "L'étudiant {$stage->student->name} a soumis son carnet de bord pour la semaine {$request->week_number}.",
                'type' => 'stage',
                'link' => route('stages.show', $stage)
            ]);
        }

        return redirect()->back()->with('success', 'Carnet de bord hebdomadaire soumis avec succès.');
    }

    public function validateWeeklyReport(Stage $stage, WeeklyReport $weeklyReport)
    {
        // Ensure current user is the supervisor
        if (Auth::id() !== $stage->supervisor_id) {
            abort(403);
        }

        $weeklyReport->update(['status' => 'Validé']);

        // Notify student
        \App\Models\Notification::create([
            'user_id' => $stage->student_id,
            'title' => '✓ Carnet de bord validé',
            'message' => "Votre encadreur a validé votre carnet de bord pour la semaine {$weeklyReport->week_number}.",
            'type' => 'stage',
            'link' => route('stages.show', $stage)
        ]);

        return redirect()->back()->with('success', 'Carnet de bord validé avec succès.');
    }

    public function uploadReport(Request $request, Stage $stage)
    {
        $request->validate([
            'report' => 'required|mimes:pdf|max:10240'
        ]);

        // Ensure student is submitting
        if (Auth::id() !== $stage->student_id) {
            abort(403);
        }

        $path = $request->file('report')->store('stages/reports', 'public');
        $stage->update(['report' => $path]);

        // Notify school and supervisor
        if ($stage->supervisor_id) {
            \App\Models\Notification::create([
                'user_id' => $stage->supervisor_id,
                'title' => '📁 Rapport final déposé',
                'message' => "L'étudiant {$stage->student->name} a déposé son rapport final de stage.",
                'type' => 'stage',
                'link' => route('stages.show', $stage)
            ]);
        }

        // Notify school coordinator or admin
        $schoolUser = User::role('Ecole')->first();
        if ($schoolUser) {
            \App\Models\Notification::create([
                'user_id' => $schoolUser->id,
                'title' => '📁 Rapport de stage déposé',
                'message' => "L'étudiant {$stage->student->name} de votre école a déposé son rapport final de stage.",
                'type' => 'stage',
                'link' => route('stages.show', $stage)
            ]);
        }

        return redirect()->back()->with('success', 'Rapport de stage déposé avec succès.');
    }

    public function validateConvention(Stage $stage)
    {
        // Only Ecole can validate convention
        if (!Auth::user()->hasRole('Ecole')) {
            abort(403);
        }

        // Set status to 'Convention validée' or update state
        $stage->update(['status' => 'Convention validée']);

        // Notify student & company
        \App\Models\Notification::create([
            'user_id' => $stage->student_id,
            'title' => '✓ Convention validée',
            'message' => "L'université de Paris-Saclay a validé académiquement votre convention de stage.",
            'type' => 'stage',
            'link' => route('stages.show', $stage)
        ]);

        return redirect()->back()->with('success', 'Convention validée académiquement avec succès.');
    }
}