<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Services\MatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function create()
    {
        return view('offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'education_level' => 'nullable|string|max:255',
            'salary' => 'nullable|numeric|min:0',
            'duration' => 'nullable|string|max:255',
            'contract_type' => 'required|string|max:255',
            'work_mode' => 'required|string|max:255',
            'required_skills' => 'nullable|string',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        Offer::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'company' => $request->company,
            'location' => $request->location,
            'domain' => $request->domain,
            'education_level' => $request->education_level,
            'salary' => $request->salary,
            'duration' => $request->duration,
            'contract_type' => $request->contract_type,
            'work_mode' => $request->work_mode,
            'required_skills' => $request->required_skills,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Offre creee avec succes');
    }

    public function index(Request $request)
    {
        $student = null;
        if (Auth::check() && Auth::user()->hasRole('Etudiant')) {
            $student = Auth::user()->student;
        }

        $offers = Offer::query()
            ->when($request->filled('location'), function ($query) use ($request) {
                $query->where('location', 'like', '%'.$request->location.'%');
            })
            ->when($request->filled('domain'), function ($query) use ($request) {
                $query->where('domain', 'like', '%'.$request->domain.'%');
            })
            ->when($request->filled('contract_type'), function ($query) use ($request) {
                $query->where('contract_type', $request->contract_type);
            })
            ->when($request->filled('work_mode'), function ($query) use ($request) {
                $query->where('work_mode', $request->work_mode);
            })
            ->when($request->filled('min_salary'), function ($query) use ($request) {
                $query->where('salary', '>=', $request->min_salary);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($student) {
            $offers->getCollection()->transform(function ($offer) use ($student) {
                $offer->match_percentage = MatchingService::calculateMatchPercentage(
                    $student->skills,
                    $offer->required_skills
                );
                return $offer;
            });
        }

        return view('offers.index', compact('offers', 'student'));
    }

    public function show(Offer $offer)
    {
        $student = null;
        $matchPercentage = null;
        $matchingSkills = [];

        if (Auth::check() && Auth::user()->hasRole('Etudiant')) {
            $student = Auth::user()->student;
            if ($student) {
                $matchPercentage = MatchingService::calculateMatchPercentage(
                    $student->skills,
                    $offer->required_skills
                );
                $matchingSkills = MatchingService::getMatchingSkills(
                    $student->skills,
                    $offer->required_skills
                );
            }
        }

        return view('offers.show', compact('offer', 'student', 'matchPercentage', 'matchingSkills'));
    }
}
