<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolDashboardController extends Controller
{
    public function index()
    {
        // Placed stats
        $totalStudents = User::role('Etudiant')->count();
        $placedStudents = Stage::whereIn('status', ['En cours', 'Convention validée', 'Soutenance programmée', 'Complété'])->count();
        $placementRate = $totalStudents > 0 ? (int)round(($placedStudents / $totalStudents) * 100) : 0;

        // Pending conventions (status !== 'Convention validée' and not completed)
        $pendingConventions = Stage::with(['student', 'offer'])
            ->where('status', 'En cours')
            ->latest()
            ->get();

        // Active stages
        $activeStages = Stage::with(['student', 'offer', 'supervisor'])
            ->where('status', 'Convention validée')
            ->latest()
            ->get();

        // Ready for defense (has report uploaded and no grade yet)
        $readyForDefense = Stage::with(['student', 'offer', 'supervisor'])
            ->whereNotNull('report')
            ->whereNull('final_grade')
            ->latest()
            ->get();

        // Completed
        $completedStages = Stage::with(['student', 'offer', 'supervisor'])
            ->whereNotNull('final_grade')
            ->latest()
            ->get();

        return view('school.dashboard', compact(
            'placementRate',
            'totalStudents',
            'placedStudents',
            'pendingConventions',
            'activeStages',
            'readyForDefense',
            'completedStages'
        ));
    }

    public function scheduleDefense(Request $request, Stage $stage)
    {
        $request->validate([
            'defense_date' => 'required|date|after:now',
            'jury_members' => 'required|string|max:255'
        ]);

        $stage->update([
            'defense_date' => $request->defense_date,
            'jury_members' => $request->jury_members,
            'status' => 'Soutenance programmée'
        ]);

        // Notify student & supervisor
        \App\Models\Notification::create([
            'user_id' => $stage->student_id,
            'title' => '📅 Soutenance programmée',
            'message' => "Votre soutenance de stage a été planifiée le " . \Carbon\Carbon::parse($request->defense_date)->format('d/m/Y à H:i') . " devant le jury : {$request->jury_members}.",
            'type' => 'stage',
            'link' => route('stages.show', $stage)
        ]);

        if ($stage->supervisor_id) {
            \App\Models\Notification::create([
                'user_id' => $stage->supervisor_id,
                'title' => '📅 Soutenance programmée',
                'message' => "La soutenance de stage de votre étudiant {$stage->student->name} est planifiée le " . \Carbon\Carbon::parse($request->defense_date)->format('d/m/Y à H:i') . ".",
                'type' => 'stage',
                'link' => route('stages.show', $stage)
            ]);
        }

        return redirect()->back()->with('success', 'Soutenance de stage planifiée avec succès.');
    }

    public function gradeStage(Request $request, Stage $stage)
    {
        $request->validate([
            'final_grade' => 'required|numeric|min:0|max:20'
        ]);

        $stage->update([
            'final_grade' => $request->final_grade,
            'status' => 'Complété'
        ]);

        // Notify student
        \App\Models\Notification::create([
            'user_id' => $stage->student_id,
            'title' => '🎓 Note de stage attribuée',
            'message' => "Félicitations, votre stage a été évalué académiquement avec la note finale de {$request->final_grade}/20.",
            'type' => 'stage',
            'link' => route('stages.show', $stage)
        ]);

        // Award badge: first_stage
        $badge = \App\Models\Badge::where('trigger_type', 'first_stage')->first();
        if ($badge) {
            $studentUser = User::find($stage->student_id);
            if ($studentUser) {
                $studentUser->badges()->syncWithoutDetaching([$badge->id]);
            }
        }

        return redirect()->back()->with('success', 'Note finale enregistrée et stage validé.');
    }
}
