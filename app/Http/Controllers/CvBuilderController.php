<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvBuilderController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        return view('student.cv_builder', compact('student'));
    }

    public function print()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.profile')->with('error', 'Veuillez d\'abord compléter votre profil.');
        }

        return view('student.cv_print', compact('user', 'student'));
    }

    public function coverLetterForm()
    {
        $student = Auth::user()->student;
        $offers = Offer::latest()->get();
        return view('student.cover_letter', compact('student', 'offers'));
    }

    public function generateCoverLetter(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'custom_pitch' => 'nullable|string'
        ]);

        $user = Auth::user();
        $student = $user->student;
        $offer = Offer::find($request->offer_id);

        // Generate simulated personalized cover letter
        $letter = $this->buildCoverLetterText($user, $student, $offer, $request->custom_pitch);

        return response()->json([
            'success' => true,
            'letter' => $letter
        ]);
    }

    private function buildCoverLetterText($user, $student, $offer, $customPitch)
    {
        $date = now()->format('d/m/Y');
        $studentName = $user->name;
        $studentEmail = $user->email;
        $studentPhone = $student?->phone ?? '[Votre téléphone]';
        $studentCity = $student?->city ?? '[Votre ville]';
        $school = $student?->school ?? '[Votre école]';
        $level = $student?->level ?? '[Votre niveau]';
        
        $companyName = $offer->company;
        $jobTitle = $offer->title;
        $jobLocation = $offer->location;

        $skillsList = $student?->skills ? str_replace(';', ', ', $student->skills) : '[Vos compétences]';

        $pitchText = $customPitch ? trim($customPitch) : "Actuellement en formation à {$school} en niveau {$level}, je suis particulièrement motivé par les technologies que vous utilisez au quotidien.";

        $text = "{$studentName}\n{$studentEmail}\n{$studentPhone}\n{$studentCity}\n\nLe {$date}\n\nÀ l'attention du responsable du recrutement\n{$companyName}\n{$jobLocation}\n\n"
              . "Objet : Candidature pour le poste de {$jobTitle} en Stage\n\n"
              . "Madame, Monsieur,\n\n"
              . "C'est avec un grand intérêt que j'ai pris connaissance de votre offre pour le poste de {$jobTitle} au sein de {$companyName}. {$pitchText}\n\n"
              . "Mon profil correspond aux compétences recherchées, notamment dans les technologies suivantes : {$skillsList}. Mes différents projets académiques et personnels m'ont permis de mettre en pratique ces outils et de développer une solide autonomie technique.\n\n"
              . "Rejoindre votre équipe serait pour moi l'opportunité de mettre mon dynamisme et ma passion pour le développement au service de vos projets tout en continuant d'apprendre au contact de vos experts.\n\n"
              . "Je me tiens à votre entière disposition pour un entretien afin de vous exposer de vive voix mes motivations.\n\n"
              . "Je vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.\n\n"
              . "{$studentName}";

        return $text;
    }
}
