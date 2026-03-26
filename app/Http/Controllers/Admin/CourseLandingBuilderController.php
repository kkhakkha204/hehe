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
            'landing_html_b64' => ['nullable', 'string'],
            'landing_css_b64' => ['nullable', 'string'],
            'landing_js_b64' => ['nullable', 'string'],
            'landing_project_data_b64' => ['nullable', 'string'],
        ]);

        $landingHtml = $data['landing_html'] ?? null;
        $landingCss = $data['landing_css'] ?? null;
        $landingJs = $data['landing_js'] ?? null;
        $landingProjectData = $data['landing_project_data'] ?? null;

        if (filled($data['landing_html_b64'] ?? null)) {
            $decoded = $this->decodeBase64Utf8((string) $data['landing_html_b64']);
            if ($decoded === null) {
                return back()
                    ->withInput()
                    ->withErrors(['landing_html' => 'HTML landing không hợp lệ. Vui lòng thử lại.']);
            }
            $landingHtml = $decoded;
        }

        if (filled($data['landing_css_b64'] ?? null)) {
            $decoded = $this->decodeBase64Utf8((string) $data['landing_css_b64']);
            if ($decoded === null) {
                return back()
                    ->withInput()
                    ->withErrors(['landing_css' => 'CSS landing không hợp lệ. Vui lòng thử lại.']);
            }
            $landingCss = $decoded;
        }

        if (filled($data['landing_js_b64'] ?? null)) {
            $decoded = $this->decodeBase64Utf8((string) $data['landing_js_b64']);
            if ($decoded === null) {
                return back()
                    ->withInput()
                    ->withErrors(['landing_js' => 'JS landing không hợp lệ. Vui lòng thử lại.']);
            }
            $landingJs = $decoded;
        }

        if (filled($data['landing_project_data_b64'] ?? null)) {
            $decoded = $this->decodeBase64Utf8((string) $data['landing_project_data_b64']);
            if ($decoded === null) {
                return back()
                    ->withInput()
                    ->withErrors(['landing_project_data' => 'Project data không hợp lệ. Vui lòng thử lại.']);
            }
            $landingProjectData = $decoded;
        }

        if (filled($landingProjectData)) {
            $decoded = json_decode((string) $landingProjectData, true);
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
            'landing_html' => $landingHtml,
            'landing_css' => $landingCss,
            'landing_js' => $landingJs,
            'landing_project_data' => $landingProjectData,
        ]);
        $course->save();

        return back()->with('status', 'Đã lưu landing page thành công.');
    }

    protected function ensureAdmin(Request $request): void
    {
        abort_unless($request->user() && $request->user()->isAdmin(), 403);
    }

    private function decodeBase64Utf8(string $encoded): ?string
    {
        $decoded = base64_decode($encoded, true);

        if ($decoded === false) {
            return null;
        }

        return $decoded;
    }
}
