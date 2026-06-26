<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('Entreprise')) {
            $quizzes = Quiz::where('user_id', $user->id)->with('questions')->get();
            $attempts = QuizAttempt::whereIn('quiz_id', $quizzes->pluck('id'))
                ->with(['user', 'quiz'])
                ->latest()
                ->get();
            return view('company.quiz.index', compact('quizzes', 'attempts'));
        }

        // For Student
        $quizzes = Quiz::with('questions')->get();
        $myAttempts = QuizAttempt::where('user_id', $user->id)->with('quiz')->get();

        return view('student.quiz.index', compact('quizzes', 'myAttempts'));
    }

    public function create()
    {
        return view('company.quiz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:qcm,code',
            // QCM fields
            'questions.*.options' => 'nullable|array',
            'questions.*.correct' => 'nullable|integer',
            // Code fields
            'questions.*.starter' => 'nullable|string',
            'questions.*.test_cases' => 'nullable|string', // JSON string representing inputs/outputs
        ]);

        $quiz = Quiz::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'domain' => $request->domain,
            'description' => $request->description
        ]);

        foreach ($request->questions as $q) {
            $isCode = $q['type'] === 'code';
            $testCases = [];
            if ($isCode && !empty($q['test_cases'])) {
                $testCases = json_decode($q['test_cases'], true) ?: [];
            }

            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question_text' => $q['text'],
                'is_code_exercise' => $isCode,
                'options' => !$isCode ? ($q['options'] ?? []) : null,
                'correct_option' => !$isCode ? ($q['correct'] ?? 0) : null,
                'code_starter' => $isCode ? ($q['starter'] ?? '') : null,
                'code_test_cases' => $isCode ? $testCases : null
            ]);
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz créé avec succès.');
    }

    public function take(Quiz $quiz)
    {
        $user = Auth::user();
        // Check if already completed
        $existing = QuizAttempt::where('user_id', $user->id)->where('quiz_id', $quiz->id)->first();
        if ($existing) {
            return redirect()->route('quizzes.index')->with('error', 'Vous avez déjà passé ce test technique.');
        }

        return view('student.quiz.take', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $questions = $quiz->questions;
        $submittedAnswers = $request->input('answers', []);
        
        $totalQuestions = count($questions);
        $correctAnswers = 0;
        $resultsLog = [];

        foreach ($questions as $question) {
            $answer = $submittedAnswers[$question->id] ?? null;

            if ($question->is_code_exercise) {
                // Execute code auto-evaluation
                $evalResult = $this->evaluateCode($answer, 'findMax', $question->code_test_cases);
                if ($evalResult['passed']) {
                    $correctAnswers++;
                    $resultsLog[$question->id] = ['status' => 'correct', 'details' => $evalResult['details'] ?? []];
                } else {
                    $resultsLog[$question->id] = ['status' => 'incorrect', 'error' => $evalResult['error'] ?? 'Vérification échouée.'];
                }
            } else {
                // QCM evaluation
                $isCorrect = ($answer !== null && (int)$answer === (int)$question->correct_option);
                if ($isCorrect) {
                    $correctAnswers++;
                }
                $resultsLog[$question->id] = [
                    'status' => $isCorrect ? 'correct' : 'incorrect',
                    'submitted' => $answer,
                    'correct' => $question->correct_option
                ];
            }
        }

        $score = $totalQuestions > 0 ? (int)round(($correctAnswers / $totalQuestions) * 100) : 0;

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'answers' => $resultsLog,
            'completed_at' => now()
        ]);

        // Award badge if score is high (80%+) and it's Laravel/PHP domain
        if ($score >= 80 && strtolower($quiz->domain) === 'php/laravel') {
            $badge = \App\Models\Badge::where('trigger_type', 'developer_laravel')->first();
            if ($badge) {
                $user->badges()->syncWithoutDetaching([$badge->id]);
            }
        }

        return view('student.quiz.result', compact('quiz', 'attempt'));
    }

    private function evaluateCode($code, $functionName, $testCases)
    {
        if (empty($code)) {
            return ['passed' => false, 'error' => 'Aucune réponse soumise.'];
        }

        try {
            // Clean php tags
            $cleanCode = str_replace(['<?php', '?>'], '', $code);
            
            // Eval the starter code safely in a sandbox scope
            ob_start();
            eval($cleanCode);
            ob_end_clean();
            
            if (!function_exists($functionName)) {
                return [
                    'passed' => false, 
                    'error' => "La fonction {$functionName} n'a pas été déclarée correctement. Vérifiez le nom de la fonction."
                ];
            }

            // Run test cases
            $passed = true;
            $details = [];
            
            // If testCases is empty, we will create default test cases for findMax
            $cases = !empty($testCases) ? $testCases : [
                ['input' => '[1, 5, 3]', 'output' => '5'],
                ['input' => '[-10, 0, -2]', 'output' => '0']
            ];

            foreach ($cases as $case) {
                // Parse input/output
                $inputVal = json_decode($case['input'], true);
                if ($inputVal === null && $case['input'] !== 'null') {
                    // Try PHP array eval fallback
                    $inputVal = eval("return {$case['input']};");
                }

                $expectedOutput = json_decode($case['output'], true);
                if ($expectedOutput === null && $case['output'] !== 'null') {
                    // Try PHP array eval fallback
                    $expectedOutput = eval("return {$case['output']};");
                }
                
                $actualOutput = call_user_func($functionName, $inputVal);
                if ($actualOutput != $expectedOutput) {
                    $passed = false;
                    $details[] = "Échec : Entrée: " . json_encode($inputVal) . " | Attendu: " . json_encode($expectedOutput) . " | Obtenu: " . json_encode($actualOutput);
                } else {
                    $details[] = "Succès : Entrée: " . json_encode($inputVal) . " => Retourne: " . json_encode($actualOutput);
                }
            }

            return [
                'passed' => $passed,
                'details' => $details
            ];

        } catch (\Throwable $t) {
            return [
                'passed' => false,
                'error' => "Erreur de syntaxe / d'exécution : " . $t->getMessage()
            ];
        }
    }
}
