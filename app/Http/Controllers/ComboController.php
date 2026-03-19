<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    public function show(Request $request, Combo $combo)
    {
        abort_unless($combo->is_active, 404);

        $combo->load([
            'courses' => function ($query) {
                $query->where('is_published', true)
                    ->with('author')
                    ->orderBy('title');
            },
        ]);

        $search = trim((string) $request->query('search', ''));

        $includedCourses = $combo->courses;
        if ($search !== '') {
            $includedCourses = $includedCourses->filter(function ($course) use ($search) {
                return str_contains(mb_strtolower($course->title), mb_strtolower($search));
            })->values();
        }

        return view('combos.show', [
            'combo' => $combo,
            'includedCourses' => $includedCourses,
            'search' => $search,
        ]);
    }
}
