<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Student;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function offers(Request $request)
    {
        $offers = Offer::query()
            ->when($request->filled('location'), function ($query) use ($request) {
                $query->where('location', 'like', '%'.$request->location.'%');
            })
            ->latest()
            ->paginate(15);

        return response()->json($offers);
    }

    public function students(Request $request)
    {
        $students = Student::with('user')->latest()->paginate(15);
        return response()->json($students);
    }

    public function companies(Request $request)
    {
        $companies = User::role('Entreprise')->latest()->paginate(15);
        return response()->json($companies);
    }

    public function applications(Request $request)
    {
        $applications = Application::with(['user', 'offer'])->latest()->paginate(15);
        return response()->json($applications);
    }
}
