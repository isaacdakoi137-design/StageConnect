<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function create()
    {
        $student = Auth::user()->student;

        return view('student.profile', compact('student'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'bio' => 'nullable|string',
            'skills' => 'nullable|string',
            'projects' => 'nullable|string',
            'experiences' => 'nullable|string',
            'certifications' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:5120',
        ]);

        $student = Student::firstOrNew(['user_id' => Auth::id()]);
        $photoPath = $student->photo;
        $cvPath = $student->cv;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('students/photos', 'public');
        }

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')
                ->store('students/cv', 'public');
        }

        $student->fill([
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'city' => $request->city,
            'school' => $request->school,
            'level' => $request->level,
            'bio' => $request->bio,
            'skills' => $request->skills,
            'projects' => $request->projects,
            'experiences' => $request->experiences,
            'certifications' => $request->certifications,
            'photo' => $photoPath,
            'cv' => $cvPath,
        ]);

        $student->save();

        return redirect()
            ->back()
            ->with('success', 'Profil enregistré avec succès');
    }
}
