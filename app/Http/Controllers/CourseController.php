<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Combo;
use App\Models\Course;
use App\Models\LessonProgress;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Illuminate\Http\Request;
use Throwable;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('is_published', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categories')) {
            $query->whereIn('category_id', $request->categories);
        }

        if ($request->filled('levels')) {
            $query->whereIn('level', $request->levels);
        }

        switch ($request->get('sort', 'newest')) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $courses = $query->paginate(6);
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $combos = Combo::with([
            'courses' => function ($query) {
                $query->where('is_published', true)
                    ->with('author')
                    ->orderBy('title');
            },
        ])
            ->active()
            ->take(12)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('courses.partials.course-grid', compact('courses'))->render(),
                'hasMore' => $courses->hasMorePages(),
                'nextPage' => $courses->currentPage() + 1,
            ]);
        }

        return view('courses.index', compact('courses', 'categories', 'combos'));
    }

    public function show($slug)
    {
        $course = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $course->increment('views');

        $relatedCourses = Course::with(['author'])
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $isEnrolled = false;
        $progressPercent = 0;
        $resumeLesson = null;

        if (auth()->check()) {
            $user = auth()->user();

            $isEnrolled = $user->enrollments()
                ->where('course_id', $course->id)
                ->exists();

            if ($isEnrolled) {
                $completedLessons = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $totalLessons = $course->chapters->pluck('lessons')->flatten()->count();

                $progressPercent = $totalLessons > 0
                    ? (int) round(($completedLessons / $totalLessons) * 100)
                    : 0;

                $resumeLesson = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->latest('last_viewed_at')
                    ->with('lesson')
                    ->first()
                    ?->lesson;
            }
        }

        return view('courses.show', compact('course', 'relatedCourses', 'isEnrolled', 'progressPercent', 'resumeLesson'));
    }

    public function landing($slug)
    {
        $course = Course::with(['category', 'author', 'chapters.lessons'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $course->increment('views');

        $hasCustomLanding = (bool) $course->landing_enabled && filled($course->landing_html);

        if ($hasCustomLanding) {
            $landingHtml = (string) $course->landing_html;

            // When a full HTML document is pasted from external builders (e.g. Stitch),
            // return it as-is so all <head> assets and script execution order are preserved.
            if ($this->isFullHtmlDocument($landingHtml)) {
                $landingHtml = $this->injectInlineAssetsIntoDocument(
                    $landingHtml,
                    (string) ($course->landing_css ?? ''),
                    (string) ($course->landing_js ?? ''),
                );

                return response($landingHtml, 200)
                    ->header('Content-Type', 'text/html; charset=UTF-8');
            }
        }

        $relatedCourses = Course::with(['author'])
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('is_published', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $isEnrolled = false;
        $progressPercent = 0;
        $resumeLesson = null;

        if (auth()->check()) {
            $user = auth()->user();

            $isEnrolled = $user->enrollments()
                ->where('course_id', $course->id)
                ->exists();

            if ($isEnrolled) {
                $completedLessons = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->whereNotNull('completed_at')
                    ->count();

                $totalLessons = $course->chapters->pluck('lessons')->flatten()->count();

                $progressPercent = $totalLessons > 0
                    ? (int) round(($completedLessons / $totalLessons) * 100)
                    : 0;

                $resumeLesson = LessonProgress::query()
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->latest('last_viewed_at')
                    ->with('lesson')
                    ->first()
                    ?->lesson;
            }
        }

        $landingPayload = $this->extractLandingAssets((string) ($course->landing_html ?? ''));

        return view('courses.landing', compact(
            'course',
            'relatedCourses',
            'isEnrolled',
            'progressPercent',
            'resumeLesson',
            'landingPayload',
        ));
    }

    private function isFullHtmlDocument(string $html): bool
    {
        $rawHtml = trim($html);

        if ($rawHtml === '') {
            return false;
        }

        return preg_match('/<!doctype\s+html|<html[\s>]|<head[\s>]/i', $rawHtml) === 1;
    }

    private function injectInlineAssetsIntoDocument(string $html, string $css = '', string $js = ''): string
    {
        $result = $html;
        $trimmedCss = trim($css);
        $trimmedJs = trim($js);

        if ($trimmedCss !== '') {
            $styleTag = "<style>\n{$trimmedCss}\n</style>";

            if (preg_match('/<\/head>/i', $result) === 1) {
                $result = preg_replace('/<\/head>/i', $styleTag . "\n</head>", $result, 1) ?? ($styleTag . "\n" . $result);
            } else {
                $result = $styleTag . "\n" . $result;
            }
        }

        if ($trimmedJs !== '') {
            $scriptTag = "<script>\n{$trimmedJs}\n</script>";

            if (preg_match('/<\/body>/i', $result) === 1) {
                $result = preg_replace('/<\/body>/i', $scriptTag . "\n</body>", $result, 1) ?? ($result . "\n" . $scriptTag);
            } else {
                $result .= "\n" . $scriptTag;
            }
        }

        return $result;
    }

    /**
     * Split custom landing HTML into:
     * - body_html: markup to render inside page body
     * - head_styles: <link>/<style> tags that should be injected in <head>
     * - head_scripts: <script> tags from <head> to keep external libraries working
     */
    private function extractLandingAssets(string $html): array
    {
        $rawHtml = trim($html);

        if ($rawHtml === '') {
            return [
                'body_html' => '',
                'head_styles' => [],
                'head_scripts' => [],
            ];
        }

        $bodyHtml = $rawHtml;
        $headStyles = [];
        $headScripts = [];

        $internalErrorsState = libxml_use_internal_errors(true);

        try {
            $document = new DOMDocument();
            $loaded = $document->loadHTML('<?xml encoding="utf-8" ?>' . $rawHtml, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);

            if ($loaded) {
                $xpath = new DOMXPath($document);

                foreach ($xpath->query('//head/link|//head/style') ?: [] as $node) {
                    if ($node instanceof DOMNode) {
                        $markup = trim((string) $document->saveHTML($node));

                        if ($markup !== '') {
                            $headStyles[] = $markup;
                        }
                    }
                }

                foreach ($xpath->query('//head/script') ?: [] as $node) {
                    if ($node instanceof DOMNode) {
                        $markup = trim((string) $document->saveHTML($node));

                        if ($markup !== '') {
                            $headScripts[] = $markup;
                        }
                    }
                }

                $bodyNode = $xpath->query('//body')->item(0);

                if ($bodyNode instanceof DOMNode) {
                    $bodyHtml = $this->innerHtml($bodyNode);
                }
            }
        } catch (Throwable) {
            // Fallback to raw HTML if parsing fails.
            $bodyHtml = $rawHtml;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($internalErrorsState);
        }

        return [
            'body_html' => $bodyHtml ?: $rawHtml,
            'head_styles' => array_values(array_unique($headStyles)),
            'head_scripts' => array_values(array_unique($headScripts)),
        ];
    }

    private function innerHtml(DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument?->saveHTML($child) ?? '';
        }

        return trim($html);
    }
}
