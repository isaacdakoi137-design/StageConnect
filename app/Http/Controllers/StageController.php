<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::with('offer')
            ->where('student_id', Auth::id())
            ->latest()
            ->get();

        return view(
            'stages.index',
            compact('stages')
        );
    }
}