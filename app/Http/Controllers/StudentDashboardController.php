<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Student;
use App\Services\MatchingService;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $applicationsCount = Application::where(
            'user_id',
            $user->id
        )->count();

        $acceptedCount = Application::where('user_id', $user->id)
            ->where('status', 'Acceptee')
            ->count();

        $successRate = $applicationsCount > 0 ? (int)round(($acceptedCount / $applicationsCount) * 100) : 0;

        $interviewsCount = \App\Models\Interview::whereHas('application', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $applications = Application::with('offer')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Calculate matching percentage for each application
        $applicationsWithMatching = $applications->map(function ($application) use ($student) {
            $matchPercentage = 0;
            if ($student && $student->skills && $application->offer->required_skills) {
                $matchPercentage = MatchingService::calculateMatchPercentage(
                    $student->skills,
                    $application->offer->required_skills
                );
            }
            $application->match_percentage = $matchPercentage;
            return $application;
        });

        return view(
            'student.dashboard',
            compact(
                'user',
                'student',
                'applicationsCount',
                'interviewsCount',
                'successRate',
                'applicationsWithMatching'
            )
        );
    }
}