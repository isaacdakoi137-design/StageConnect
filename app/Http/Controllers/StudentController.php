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
        Student::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'city' => $request->city,
                'school' => $request->school,
                'level' => $request->level,
                'bio' => $request->bio,
            ]
        );

        return redirect()->back()->with(
            'success',
            'Profil enregistré avec succès'
        );
    }
}