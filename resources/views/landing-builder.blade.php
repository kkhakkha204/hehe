<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Landing Builder - {{ $course->title }}</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0b1020;
            --panel: #10182b;
            --panel-2: #16223c;
            --line: rgba(148, 163, 184, 0.18);
            --text: #e5eefc;
            --muted: #94a3b8;
            --accent: #3b82f6;
            --accent-2: #60a5fa;
            --success: #22c55e;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.18), transparent 24%),
                radial-gradient(circle at top right, rgba(96, 165, 250, 0.12), transparent 20%),
                var(--bg);
        }

        .builder-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .builder-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--line);
            background: rgba(10, 15, 28, 0.92);
            backdrop-filter: blur(18px);
        }

        .builder-toolbar__group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .builder-btn,
        .builder-input,
        .builder-toggle {
            height: 40px;
            border-radius: 10px;
            border: 1px solid var(--line);
            font-size: 13px;
        }

        .builder-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 14px;
            background: var(--panel);
            color: var(--text);
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
        }

        .builder-btn:hover {
            filter: brightness(1.05);
        }

        .builder-btn.is-active {
            border-color: rgba(96, 165, 250, 0.74);
            background: rgba(59, 130, 246, 0.18);
            color: #ffffff;
        }

        .builder-btn--primary {
            border-color: rgba(59, 130, 246, 0.6);
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
        }

        .builder-input {
            min-width: 240px;
            padding: 0 12px;
            background: var(--panel);
            color: var(--text);
        }

        .builder-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 12px;
            background: var(--panel);
            color: var(--muted);
        }

        .builder-toggle input {
            accent-color: var(--accent);
        }

        .builder-toolbar .builder-toggle {
            display: none;
        }

        .builder-note,
        .builder-status,
        .builder-error {
            margin: 14px 20px 0;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: rgba(16, 24, 43, 0.82);
            font-size: 13px;
            line-height: 1.6;
        }

        .builder-note {
            color: var(--muted);
        }

        .builder-status {
            color: var(--success);
        }

        .builder-error {
            color: #f87171;
        }

        .builder-main {
            flex: 1 1 auto;
            min-height: 0;
            display: grid;
            grid-template-columns: minmax(480px, 1.05fr) minmax(360px, 0.95fr);
            gap: 16px;
            padding: 16px 20px 20px;
        }

        .builder-panel {
            min-height: 0;
            display: flex;
            flex-direction: column;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: rgba(16, 24, 43, 0.9);
        }

        .builder-panel__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            background: rgba(22, 34, 60, 0.92);
        }

        .builder-panel__title {
            font-size: 14px;
            font-weight: 800;
        }

        .builder-panel__subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .builder-panel__actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .builder-editor {
            flex: 1 1 auto;
            width: 100%;
            min-height: 560px;
            padding: 18px;
            border: 0;
            outline: 0;
            resize: none;
            background: #0a1222;
            color: #dbeafe;
            font-family: Consolas, "Courier New", monospace;
            font-size: 14px;
            line-height: 1.6;
            tab-size: 2;
        }

        .builder-preview-wrap {
            position: relative;
            flex: 1 1 auto;
            min-height: 0;
            background: #ffffff;
        }

        .builder-preview {
            width: 100%;
            height: 100%;
            min-height: 560px;
            border: 0;
            background: #ffffff;
        }

        .builder-preview-state {
            position: absolute;
            right: 14px;
            bottom: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(10, 15, 28, 0.82);
            color: var(--muted);
            font-size: 12px;
            opacity: 0;
            transform: translateY(4px);
            transition: all .18s ease;
            pointer-events: none;
        }

        .builder-preview-state.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .builder-visual-tools {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            padding: 14px 16px 0;
        }

        .builder-inspector {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            padding: 14px 16px 16px;
            border-top: 1px solid var(--line);
            background: rgba(22, 34, 60, 0.9);
        }

        .builder-inspector.is-hidden {
            display: none;
        }

        .builder-inspector__full {
            grid-column: 1 / -1;
        }

        .builder-inspector__meta {
            grid-column: 1 / -1;
            color: var(--muted);
            font-size: 12px;
        }

        .builder-textarea {
            width: 100%;
            min-height: 108px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: var(--panel);
            color: var(--text);
            font: inherit;
            resize: vertical;
        }

        .builder-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .builder-field label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .builder-modal {
            position: fixed;
            inset: 0;
            z-index: 90;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .builder-modal.is-open {
            display: flex;
        }

        .builder-modal__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(2, 6, 23, 0.74);
            backdrop-filter: blur(8px);
        }

        .builder-modal__dialog {
            position: relative;
            z-index: 1;
            width: min(100%, 980px);
            max-height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: rgba(10, 15, 28, 0.98);
            box-shadow: 0 24px 80px rgba(15, 23, 42, 0.5);
        }

        .builder-modal__header,
        .builder-modal__footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            background: rgba(22, 34, 60, 0.92);
        }

        .builder-modal__footer {
            border-top: 1px solid var(--line);
            border-bottom: 0;
        }

        .builder-modal__body {
            padding: 18px;
            background: rgba(10, 15, 28, 0.98);
        }

        .builder-modal__body .builder-editor {
            min-height: 380px;
            height: min(60vh, 520px);
            border-radius: 14px;
            border: 1px solid var(--line);
        }

        @media (max-width: 1180px) {
            .builder-main {
                grid-template-columns: 1fr;
            }

            .builder-editor,
            .builder-preview {
                min-height: 460px;
            }
        }

        @media (max-width: 720px) {
            .builder-toolbar,
            .builder-main {
                padding-left: 14px;
                padding-right: 14px;
            }

            .builder-toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            .builder-toolbar__group {
                width: 100%;
            }

            .builder-btn,
            .builder-input,
            .builder-toggle {
                width: 100%;
            }

            .builder-panel__header {
                flex-direction: column;
                align-items: flex-start;
            }

            .builder-visual-tools,
            .builder-inspector {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="builder-shell">
        <form id="landing-form" method="POST" action="{{ route('admin.courses.landing.update', $course) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="landing_title" id="landing_title" value="{{ old('landing_title', $course->landing_title) }}">
            <input type="hidden" name="landing_html" id="landing_html">
            <input type="hidden" name="landing_css" id="landing_css" value="">
            <input type="hidden" name="landing_js" id="landing_js" value="">
            <input type="hidden" name="landing_project_data" id="landing_project_data" value="">
            <input type="hidden" name="landing_html_b64" id="landing_html_b64">
            <input type="hidden" name="landing_css_b64" id="landing_css_b64" value="">
            <input type="hidden" name="landing_js_b64" id="landing_js_b64" value="">
            <input type="hidden" name="landing_project_data_b64" id="landing_project_data_b64" value="">

            <div class="builder-toolbar">
                <div class="builder-toolbar__group">
                    <a href="{{ url('/admin/courses') }}" class="builder-btn">Quay lại</a>
                    <a href="{{ route('courses.landing', $course->slug) }}" class="builder-btn" target="_blank" rel="noopener">Xem landing public</a>
                    <a href="{{ route('courses.info', $course->slug) }}" class="builder-btn" target="_blank" rel="noopener">Xem trang thông tin</a>
                </div>

                <div class="builder-toolbar__group">
                    <label class="builder-toggle">
                        <input type="checkbox" name="landing_enabled" value="1" {{ $course->landing_enabled ? 'checked' : '' }}>
                        Bật landing custom
                    </label>

                    <button type="button" class="builder-btn" id="paste-from-clipboard">Dán từ clipboard</button>
                    <button type="button" class="builder-btn" id="refresh-preview">Làm mới preview</button>
                    <button type="button" class="builder-btn builder-btn--primary" id="save-landing">Lưu landing</button>
                </div>
            </div>
        </form>

        @if (session('status'))
            <div class="builder-status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="builder-error">{{ $errors->first() }}</div>
        @endif

        <div class="builder-main">
            <section class="builder-panel">
                <div class="builder-panel__header">
                    <div>
                        <div class="builder-panel__title">Full HTML document</div>
                        <div class="builder-panel__subtitle">Dán nguyên HTML export từ Stitch/Figma vào đây và chỉnh sửa trực tiếp nếu cần.</div>
                    </div>

                    <div class="builder-panel__actions">
                        <button type="button" class="builder-btn" id="reset-template">Mẫu mặc định</button>
                    </div>
                </div>

                <textarea id="landing-html-editor" class="builder-editor" spellcheck="false"></textarea>
            </section>

            <section class="builder-panel">
                <div class="builder-panel__header">
                    <div>
                        <div class="builder-panel__title">Preview</div>
                        <div class="builder-panel__subtitle">Render trực tiếp từ HTML đang sửa.</div>
                    </div>

                    <div class="builder-panel__actions">
                        <button type="button" class="builder-btn" id="open-preview-tab">Mở preview tab mới</button>
                    </div>
                </div>

                <div class="builder-preview-wrap">
                    <iframe id="landing-preview" class="builder-preview"></iframe>
                    <div id="preview-state" class="builder-preview-state">Đang render preview...</div>
                </div>

                <div class="builder-visual-tools">
                    <button type="button" class="builder-btn" id="toggle-text-edit">Sửa text trên web</button>
                </div>

                <div id="builder-inspector" class="builder-inspector is-hidden">
                    <div class="builder-inspector__meta" id="builder-inspector-meta">Bấm vào phần tử trong preview để sửa.</div>

                    <div class="builder-field">
                        <label for="inspector-text">Text</label>
                        <input id="inspector-text" type="text" class="builder-input">
                    </div>

                    <div class="builder-field">
                        <label for="inspector-href">Href</label>
                        <input id="inspector-href" type="text" class="builder-input" placeholder="/courses hoặc https://...">
                    </div>

                    <div class="builder-field">
                        <label for="inspector-src">Src</label>
                        <input id="inspector-src" type="text" class="builder-input" placeholder="https://...">
                    </div>

                    <div class="builder-field">
                        <label for="inspector-alt">Alt</label>
                        <input id="inspector-alt" type="text" class="builder-input">
                    </div>

                    <div class="builder-field builder-inspector__full">
                        <label for="inspector-class">Class</label>
                        <input id="inspector-class" type="text" class="builder-input">
                    </div>

                    <div class="builder-field builder-inspector__full">
                        <label for="inspector-style">Inline style</label>
                        <textarea id="inspector-style" class="builder-textarea"></textarea>
                    </div>

                    <div class="builder-panel__actions builder-inspector__full">
                        <button type="button" class="builder-btn" id="apply-element-edit">Cập nhật phần tử</button>
                        <button type="button" class="builder-btn" id="clear-element-edit">Bỏ chọn</button>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div id="paste-html-modal" class="builder-modal" aria-hidden="true">
        <div class="builder-modal__backdrop" id="paste-html-modal-backdrop"></div>

        <div class="builder-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="paste-html-modal-title">
            <div class="builder-modal__header">
                <div>
                    <div class="builder-panel__title" id="paste-html-modal-title">Dán HTML vào đây</div>
                    <div class="builder-panel__subtitle">Dán nguyên file HTML export từ Stitch/Figma. Hệ thống sẽ format lại để dễ đọc hơn trước khi render.</div>
                </div>

                <button type="button" class="builder-btn" id="close-paste-html-modal">Đóng</button>
            </div>

            <div class="builder-modal__body">
                <textarea id="paste-html-input" class="builder-editor" spellcheck="false" placeholder="Dán full HTML document vào đây..."></textarea>
            </div>

            <div class="builder-modal__footer">
                <div class="builder-panel__subtitle">Sau khi áp dụng, preview sẽ được render lại ngay.</div>

                <div class="builder-panel__actions">
                    <button type="button" class="builder-btn" id="cancel-paste-html">Hủy</button>
                    <button type="button" class="builder-btn builder-btn--primary" id="apply-paste-html">Áp dụng HTML</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.15.4/beautify-html.min.js" referrerpolicy="no-referrer"></script>
    <script>
        const existingHtml = @json($course->landing_html ?? '');
        const existingCss = @json($course->landing_css ?? '');
        const existingJs = @json($course->landing_js ?? '');
        const courseTitle = @json($course->title);
        const courseDescription = @json(strip_tags((string) $course->description));
        const courseThumbnail = @json($course->thumbnail_url);
        const existingLandingTitle = @json(old('landing_title', $course->landing_title));

        const htmlEditor = document.getElementById('landing-html-editor');
        const previewFrame = document.getElementById('landing-preview');
        const previewState = document.getElementById('preview-state');
        const landingTitleInput = document.getElementById('landing_title');
        const toggleTextEditButton = document.getElementById('toggle-text-edit');
        const inspector = document.getElementById('builder-inspector');
        const inspectorMeta = document.getElementById('builder-inspector-meta');
        const inspectorText = document.getElementById('inspector-text');
        const inspectorHref = document.getElementById('inspector-href');
        const inspectorSrc = document.getElementById('inspector-src');
        const inspectorAlt = document.getElementById('inspector-alt');
        const inspectorClass = document.getElementById('inspector-class');
        const inspectorStyle = document.getElementById('inspector-style');
        const pasteHtmlModal = document.getElementById('paste-html-modal');
        const pasteHtmlInput = document.getElementById('paste-html-input');

        let visualMode = 'element';
        let selectedElement = null;

        const helperStyleId = 'builder-editor-helper-style';

        const isFullDocument = (html) => /<!doctype|<html[\s>]|<head[\s>]/i.test((html || '').trim());

        const encodeUtf8Base64 = (value) => {
            const text = String(value ?? '');
            const bytes = new TextEncoder().encode(text);
            let binary = '';
            const chunkSize = 0x8000;

            for (let index = 0; index < bytes.length; index += chunkSize) {
                const chunk = bytes.subarray(index, index + chunkSize);
                binary += String.fromCharCode(...chunk);
            }

            return btoa(binary);
        };

        const formatHtmlDocument = (value) => {
            const html = String(value ?? '').trim();

            if (!html) {
                return '';
            }

            if (typeof window.html_beautify === 'function') {
                return window.html_beautify(html, {
                    indent_size: 2,
                    preserve_newlines: true,
                    max_preserve_newlines: 2,
                    end_with_newline: true,
                    wrap_line_length: 0,
                    extra_liners: [],
                    indent_scripts: 'keep',
                }).trim();
            }

            return html;
        };

        const updateLandingTitleFromHtml = (html) => {
            if (!landingTitleInput) {
                return;
            }

            try {
                const parsed = new DOMParser().parseFromString(String(html || ''), 'text/html');
                const nextTitle = (parsed?.title || '').trim();
                landingTitleInput.value = nextTitle || existingLandingTitle || courseTitle;
            } catch (error) {
                landingTitleInput.value = existingLandingTitle || courseTitle;
            }
        };

        const setEditorHtml = (value, { format = true } = {}) => {
            htmlEditor.value = format ? formatHtmlDocument(value) : String(value ?? '');
            updateLandingTitleFromHtml(htmlEditor.value);
        };

        const escapeHtml = (value) => String(value || '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;');

        const buildLegacyDocument = () => {
            const bodyHtml = (existingHtml || '').trim() || `<section style="padding:40px"><h1>${escapeHtml(courseTitle)}</h1></section>`;
            const cssTag = (existingCss || '').trim() ? `\n<style>\n${existingCss}\n</style>` : '';
            const jsTag = (existingJs || '').trim() ? `\n<script>\n${existingJs}\n<\/script>` : '';

            return `<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${escapeHtml(courseTitle)}</title>${cssTag}
</head>
<body>
${bodyHtml}${jsTag}
</body>
</html>`;
        };

        const defaultDocument = `<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${courseTitle}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #0f172a;
            color: #f8fafc;
        }
        .hero {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 40px;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 72px 24px;
        }
        .eyebrow {
            margin: 0 0 12px;
            letter-spacing: .24em;
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .title {
            margin: 0;
            font-size: clamp(42px, 6vw, 78px);
            line-height: 0.98;
            text-transform: uppercase;
        }
        .desc {
            margin: 18px 0 0;
            max-width: 640px;
            font-size: 18px;
            line-height: 1.8;
            color: rgba(248, 250, 252, 0.78);
        }
        .cta {
            display: inline-block;
            margin-top: 28px;
            padding: 16px 22px;
            border-radius: 999px;
            background: #fff;
            color: #0f172a;
            text-decoration: none;
            font-weight: 700;
        }
        .visual {
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
            background: rgba(255, 255, 255, 0.06);
        }
        .visual img {
            display: block;
            width: 100%;
            height: auto;
        }
        @media (max-width: 920px) {
            .hero {
                grid-template-columns: 1fr;
                padding: 48px 20px;
            }
        }
    </style>
</head>
<body>
    <section class="hero">
        <div>
            <p class="eyebrow">Landing course</p>
            <h1 class="title">${courseTitle}</h1>
            <p class="desc">${courseDescription || 'Dan file HTML export vao day de luu nguyen ban, khong mat CSS va script.'}</p>
            <a class="cta" href="#">Chinh landing nay</a>
        </div>
        <div class="visual">
            <img src="${courseThumbnail || 'https://placehold.co/1200x800/111827/FFFFFF?text=Landing+Preview'}" alt="${courseTitle}">
        </div>
    </section>
</body>
</html>`;

        const getInitialDocument = () => {
            const rawHtml = (existingHtml || '').trim();

            if (isFullDocument(rawHtml)) {
                return rawHtml;
            }

            if (rawHtml.length > 0 || (existingCss || '').trim().length > 0 || (existingJs || '').trim().length > 0) {
                return buildLegacyDocument();
            }

            return defaultDocument;
        };

        const setPreviewBusy = (busy) => {
            previewState.classList.toggle('is-visible', busy);
        };

        const renderPreview = () => {
            const html = htmlEditor.value.trim() || defaultDocument;
            setPreviewBusy(true);
            previewFrame.srcdoc = html;
        };

        const debounce = (callback, delay = 240) => {
            let timer = null;

            return (...args) => {
                window.clearTimeout(timer);
                timer = window.setTimeout(() => callback(...args), delay);
            };
        };

        const renderPreviewDebounced = debounce(renderPreview, 220);

        setEditorHtml(getInitialDocument());
        renderPreview();

        previewFrame.addEventListener('load', () => {
            attachVisualEditor();
            window.setTimeout(() => setPreviewBusy(false), 120);
        });

        htmlEditor.addEventListener('input', () => {
            renderPreviewDebounced();
        });

        htmlEditor.addEventListener('blur', () => {
            setEditorHtml(htmlEditor.value);
            renderPreview();
        });

        const openPasteHtmlModal = () => {
            pasteHtmlInput.value = '';
            pasteHtmlModal.classList.add('is-open');
            pasteHtmlModal.setAttribute('aria-hidden', 'false');
            window.setTimeout(() => pasteHtmlInput.focus(), 30);
        };

        const closePasteHtmlModal = () => {
            pasteHtmlModal.classList.remove('is-open');
            pasteHtmlModal.setAttribute('aria-hidden', 'true');
        };

        document.getElementById('paste-from-clipboard').addEventListener('click', () => {
            openPasteHtmlModal();
        });

        document.getElementById('close-paste-html-modal').addEventListener('click', () => {
            closePasteHtmlModal();
        });

        document.getElementById('cancel-paste-html').addEventListener('click', () => {
            closePasteHtmlModal();
        });

        document.getElementById('paste-html-modal-backdrop').addEventListener('click', () => {
            closePasteHtmlModal();
        });

        document.getElementById('apply-paste-html').addEventListener('click', () => {
            const pastedHtml = pasteHtmlInput.value.trim();

            if (!pastedHtml) {
                alert('Hay dan HTML vao o ben tren.');
                return;
            }

            setEditorHtml(pastedHtml);

            closePasteHtmlModal();
            renderPreview();
        });

        document.getElementById('refresh-preview').addEventListener('click', () => {
            renderPreview();
        });

        document.getElementById('reset-template').addEventListener('click', () => {
            setEditorHtml(defaultDocument);
            renderPreview();
        });

        document.getElementById('open-preview-tab').addEventListener('click', () => {
            const win = window.open('', '_blank');

            if (!win) {
                return;
            }

            win.document.open();
            win.document.write(htmlEditor.value.trim() || defaultDocument);
            win.document.close();
        });

        document.getElementById('save-landing').addEventListener('click', () => {
            setEditorHtml(htmlEditor.value);
            const html = htmlEditor.value.trim() || defaultDocument;

            document.getElementById('landing_html').value = html;
            document.getElementById('landing_html_b64').value = encodeUtf8Base64(html);
            document.getElementById('landing_css').value = '';
            document.getElementById('landing_js').value = '';
            document.getElementById('landing_project_data').value = '';
            document.getElementById('landing_css_b64').value = '';
            document.getElementById('landing_js_b64').value = '';
            document.getElementById('landing_project_data_b64').value = '';

            document.getElementById('landing-form').submit();
        });

        toggleTextEditButton.addEventListener('click', () => {
            visualMode = visualMode === 'text' ? 'element' : 'text';
            clearSelectedElement();
            applyVisualMode();
        });

        document.getElementById('apply-element-edit').addEventListener('click', () => {
            if (!selectedElement) {
                return;
            }

            if (canEditTextOnElement(selectedElement)) {
                selectedElement.textContent = inspectorText.value;
            }

            if (selectedElement.tagName.toLowerCase() === 'a') {
                selectedElement.setAttribute('href', inspectorHref.value.trim() || '#');
            }

            if (['img', 'iframe', 'source', 'video'].includes(selectedElement.tagName.toLowerCase())) {
                const srcValue = inspectorSrc.value.trim();

                if (srcValue !== '') {
                    selectedElement.setAttribute('src', srcValue);
                } else {
                    selectedElement.removeAttribute('src');
                }
            }

            if (selectedElement.tagName.toLowerCase() === 'img') {
                selectedElement.setAttribute('alt', inspectorAlt.value);
            }

            const classValue = inspectorClass.value.trim();
            if (classValue !== '') {
                selectedElement.setAttribute('class', classValue);
            } else {
                selectedElement.removeAttribute('class');
            }

            const styleValue = inspectorStyle.value.trim();
            if (styleValue !== '') {
                selectedElement.setAttribute('style', styleValue);
            } else {
                selectedElement.removeAttribute('style');
            }

            refreshInspectorFields();
            syncPreviewToEditor();
        });

        document.getElementById('clear-element-edit').addEventListener('click', () => {
            clearSelectedElement();
            applyVisualMode();
        });

        const attachVisualEditor = () => {
            const doc = previewFrame.contentDocument;

            if (!doc?.head || !doc.body) {
                return;
            }

            if (!doc.getElementById(helperStyleId)) {
                const style = doc.createElement('style');
                style.id = helperStyleId;
                style.textContent = `
                    [data-builder-text-editable="1"] {
                        outline: 1px dashed rgba(59, 130, 246, 0.9);
                        outline-offset: 2px;
                        cursor: text;
                    }
                    [data-builder-text-editable="1"]:focus {
                        outline: 2px solid rgba(59, 130, 246, 0.95);
                        background: rgba(59, 130, 246, 0.08);
                    }
                    [data-builder-selected="1"] {
                        outline: 2px solid rgba(245, 158, 11, 0.95);
                        outline-offset: 2px;
                        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.22);
                    }
                `;
                doc.head.appendChild(style);
            }

            if (!doc.body.dataset.builderBound) {
                doc.body.dataset.builderBound = '1';

                doc.addEventListener('click', (event) => {
                    const target = event.target instanceof Element ? event.target : null;

                    if (!target) {
                        return;
                    }

                    if (visualMode !== 'text') {
                        event.preventDefault();
                        event.stopPropagation();

                        const element = resolveSelectableElement(target);

                        if (element) {
                            selectElement(element);
                        }
                    }

                    if (visualMode === 'text' && target.closest('a')) {
                        event.preventDefault();
                    }
                }, true);

                doc.addEventListener('input', (event) => {
                    const target = event.target instanceof Element ? event.target : null;

                    if (target?.hasAttribute('data-builder-text-editable')) {
                        syncPreviewToEditorDebounced();
                    }
                }, true);

                doc.addEventListener('focusout', (event) => {
                    const target = event.target instanceof Element ? event.target : null;

                    if (target?.hasAttribute('data-builder-text-editable')) {
                        syncPreviewToEditor();
                    }
                }, true);
            }

            applyVisualMode();
        };

        const resolveSelectableElement = (target) => {
            let element = target;
            const blockedTags = ['html', 'head', 'body', 'script', 'style'];

            while (element && blockedTags.includes(element.tagName.toLowerCase())) {
                element = element.parentElement;
            }

            return element;
        };

        const canEditTextOnElement = (element) => {
            if (!element) {
                return false;
            }

            const tag = element.tagName.toLowerCase();
            const blockedTags = ['img', 'video', 'audio', 'source', 'svg', 'path', 'iframe', 'canvas', 'script', 'style'];

            if (blockedTags.includes(tag)) {
                return false;
            }

            return (element.textContent || '').trim().length > 0;
        };

        const applyVisualMode = () => {
            toggleTextEditButton.classList.toggle('is-active', visualMode === 'text');

            const doc = previewFrame.contentDocument;
            if (!doc?.body) {
                return;
            }

            doc.querySelectorAll('[data-builder-text-editable]').forEach((element) => {
                element.removeAttribute('data-builder-text-editable');
                element.removeAttribute('contenteditable');
            });

            if (visualMode === 'text') {
                Array.from(doc.body.querySelectorAll('*'))
                    .filter((element) => element.children.length === 0 && canEditTextOnElement(element))
                    .forEach((element) => {
                        element.setAttribute('data-builder-text-editable', '1');
                        element.setAttribute('contenteditable', 'true');
                    });
            }

            if (visualMode === 'text') {
                clearSelectedElement();
            }

            inspector.classList.toggle('is-hidden', visualMode === 'text' || !selectedElement);
        };

        const selectElement = (element) => {
            clearSelectedElement();
            selectedElement = element;
            selectedElement.setAttribute('data-builder-selected', '1');
            refreshInspectorFields();
            inspector.classList.remove('is-hidden');
        };

        const refreshInspectorFields = () => {
            if (!selectedElement) {
                return;
            }

            const tag = selectedElement.tagName.toLowerCase();
            inspectorMeta.textContent = `Dang sua <${tag}>`;
            inspectorText.value = canEditTextOnElement(selectedElement) ? (selectedElement.textContent || '') : '';
            inspectorHref.value = selectedElement.getAttribute('href') || '';
            inspectorSrc.value = selectedElement.getAttribute('src') || '';
            inspectorAlt.value = selectedElement.getAttribute('alt') || '';
            inspectorClass.value = selectedElement.getAttribute('class') || '';
            inspectorStyle.value = selectedElement.getAttribute('style') || '';

            inspectorText.disabled = !canEditTextOnElement(selectedElement);
            inspectorHref.disabled = tag !== 'a';
            inspectorSrc.disabled = !['img', 'iframe', 'source', 'video'].includes(tag);
            inspectorAlt.disabled = tag !== 'img';
        };

        const clearSelectedElement = () => {
            if (selectedElement) {
                selectedElement.removeAttribute('data-builder-selected');
            }

            selectedElement = null;
            inspectorMeta.textContent = 'Bam vao phan tu trong preview de sua.';
            inspectorText.value = '';
            inspectorHref.value = '';
            inspectorSrc.value = '';
            inspectorAlt.value = '';
            inspectorClass.value = '';
            inspectorStyle.value = '';
            inspector.classList.add('is-hidden');
        };

        const getPreviewDocumentHtml = () => {
            const doc = previewFrame.contentDocument;

            if (!doc?.documentElement) {
                return htmlEditor.value.trim() || defaultDocument;
            }

            const clone = doc.documentElement.cloneNode(true);
            clone.querySelector(`#${helperStyleId}`)?.remove();
            clone.querySelectorAll('[data-builder-text-editable]').forEach((element) => {
                element.removeAttribute('data-builder-text-editable');
                element.removeAttribute('contenteditable');
            });
            clone.querySelectorAll('[data-builder-selected]').forEach((element) => {
                element.removeAttribute('data-builder-selected');
            });
            if (clone.querySelector('body')) {
                clone.querySelector('body').removeAttribute('data-builder-bound');
            }

            const doctype = doc.doctype
                ? `<!DOCTYPE ${doc.doctype.name}>`
                : '<!DOCTYPE html>';

            return `${doctype}\n${clone.outerHTML}`;
        };

        const syncPreviewToEditor = () => {
            setEditorHtml(getPreviewDocumentHtml());
        };

        const syncPreviewToEditorDebounced = debounce(syncPreviewToEditor, 160);
    </script>
</body>
</html>
