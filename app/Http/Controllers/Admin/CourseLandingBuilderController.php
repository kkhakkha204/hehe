<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseLandingBuilderController extends Controller
{
    public function edit(Request $request, Course $course): View
    {
        $this->ensureAdmin($request);

        $projectData = null;
        if (filled($course->landing_project_data)) {
            $decoded = json_decode((string) $course->landing_project_data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $projectData = $decoded;
            }
        }

        return view('landing-builder', [
            'course' => $course,
            'projectData' => $projectData,
        ]);
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->ensureAdmin($request);

        $data = $request->validate([
            'landing_title' => ['nullable', 'string', 'max:255'],
            'landing_enabled' => ['nullable', 'string'],
            'landing_html' => ['nullable', 'string'],
            'landing_css' => ['nullable', 'string'],
            'landing_js' => ['nullable', 'string'],
            'landing_project_data' => ['nullable', 'string'],
        ]);

        if (filled($data['landing_project_data'] ?? null)) {
            $decoded = json_decode((string) $data['landing_project_data'], true);
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'landing_project_data' => 'Dữ liệu landing page không hợp lệ. Vui lòng thử lại.',
                    ]);
            }
        }

        $course->fill([
            'landing_title' => $data['landing_title'] ?? null,
            'landing_enabled' => $request->boolean('landing_enabled'),
            'landing_html' => $data['landing_html'] ?? null,
            'landing_css' => $data['landing_css'] ?? null,
            'landing_js' => $data['landing_js'] ?? null,
            'landing_project_data' => $data['landing_project_data'] ?? null,
        ]);
        $course->save();

        return back()->with('status', 'Đã lưu landing page thành công.');
    }

    protected function ensureAdmin(Request $request): void
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403);
    }
}
