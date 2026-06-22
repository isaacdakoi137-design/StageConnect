<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function create()
    {
        return view('student.profile');
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
            'photo' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf|max:5120',
        ]);

        $photoPath = null;
        $cvPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')
                ->store('students/photos', 'public');
        }

        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')
                ->store('students/cv', 'public');
        }

        Student::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'city' => $request->city,
                'school' => $request->school,
                'level' => $request->level,
                'bio' => $request->bio,
                'photo' => $photoPath,
                'cv' => $cvPath,
            ]
        );

        return redirect()->back()->with(
            'success',
            'Profil enregistré avec succès'
        );
    }
}