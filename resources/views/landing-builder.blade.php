<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Landing Builder - {{ $course->title }}</title>

    <link rel="stylesheet" href="https://unpkg.com/grapesjs@0.22.5/dist/css/grapes.min.css">
    <style>
        html,
        body {
            height: 100%;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0f1116;
            color: #fff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .landing-toolbar {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 0 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            background: #151925;
        }
        .landing-toolbar__left,
        .landing-toolbar__right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .landing-input {
            height: 36px;
            border: 1px solid #30384d;
            background: #0f1422;
            color: #fff;
            border-radius: 8px;
            padding: 0 10px;
            min-width: 240px;
        }
        .landing-btn {
            height: 36px;
            border-radius: 8px;
            border: 1px solid #30384d;
            background: #1f2a44;
            color: #fff;
            padding: 0 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
        }
        .landing-btn:hover { opacity: .9; }
        .landing-btn--primary { background: #2f7cff; border-color: #2f7cff; }
        .landing-status { color: #89f0ae; font-size: 13px; }
        .landing-error { color: #ff8b8b; font-size: 13px; }
        #gjs {
            flex: 1 1 auto;
            min-height: 320px;
            min-width: 0;
        }
        #gjs .gjs-editor-cont,
        #gjs .gjs-cv-canvas,
        #gjs .gjs-frame-wrapper,
        #gjs .gjs-frame {
            min-height: 100%;
        }
        .landing-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #d0d8ea;
        }
        .landing-toggle input { accent-color: #2f7cff; }
        .landing-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 1200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .landing-modal.is-open {
            display: flex;
        }
        .landing-modal__content {
            width: min(900px, 100%);
            max-height: min(82vh, 760px);
            background: #151925;
            border: 1px solid #30384d;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .landing-modal__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 15px;
            font-weight: 700;
        }
        .landing-modal__body {
            padding: 14px 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow: auto;
        }
        .landing-modal__hint {
            font-size: 12px;
            color: #9fb0d8;
            margin: 0;
        }
        .landing-modal__textarea {
            width: 100%;
            min-height: 320px;
            resize: vertical;
            border: 1px solid #30384d;
            background: #0f1422;
            color: #dfe8ff;
            border-radius: 8px;
            padding: 10px 12px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 13px;
            line-height: 1.6;
        }
        .landing-modal__options {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .landing-modal__check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #d0d8ea;
        }
        .landing-modal__footer {
            padding: 12px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        @media (max-width: 1000px) {
            .landing-toolbar {
                height: auto;
                flex-direction: column;
                align-items: stretch;
                padding: 12px;
            }
            .landing-toolbar__left,
            .landing-toolbar__right {
                flex-wrap: wrap;
            }
            .landing-input {
                min-width: 180px;
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <form id="landing-form" method="POST" action="{{ route('admin.courses.landing.update', $course) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="landing_html" id="landing_html">
        <input type="hidden" name="landing_css" id="landing_css">
        <input type="hidden" name="landing_js" id="landing_js">
        <input type="hidden" name="landing_project_data" id="landing_project_data">

        <div class="landing-toolbar">
            <div class="landing-toolbar__left">
                <a href="{{ url('/admin/courses') }}" class="landing-btn">← Quay lại</a>
                <a href="{{ route('courses.landing', $course->slug) }}" class="landing-btn" target="_blank" rel="noopener">Xem landing public</a>
                <a href="{{ route('courses.info', $course->slug) }}" class="landing-btn" target="_blank" rel="noopener">Xem trang thông tin</a>

                <label class="landing-toggle">
                    <input type="checkbox" name="landing_enabled" value="1" {{ $course->landing_enabled ? 'checked' : '' }}>
                    Bật landing page custom
                </label>
            </div>

            <div class="landing-toolbar__right">
                <input
                    type="text"
                    class="landing-input"
                    name="landing_title"
                    placeholder="Tiêu đề landing (SEO / tab)"
                    value="{{ old('landing_title', $course->landing_title) }}"
                >
                <button type="button" id="open-import-modal" class="landing-btn">Dán HTML</button>
                <button type="button" id="save-landing" class="landing-btn landing-btn--primary">Lưu landing</button>
            </div>
        </div>
    </form>

    @if (session('status'))
        <div class="landing-status" style="padding: 10px 16px;">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="landing-error" style="padding: 10px 16px;">{{ $errors->first() }}</div>
    @endif

    <div id="gjs"></div>

    <div class="landing-modal" id="import-modal" role="dialog" aria-modal="true" aria-labelledby="import-modal-title">
        <div class="landing-modal__content">
            <div class="landing-modal__header">
                <span id="import-modal-title">Dán mã HTML/CSS/JS</span>
                <button type="button" class="landing-btn" id="close-import-modal">Đóng</button>
            </div>
            <div class="landing-modal__body">
                <p class="landing-modal__hint">Dán code trang vào đây. Hệ thống sẽ lấy nội dung trong &lt;body&gt; để render, và có thể lấy luôn &lt;style&gt; / &lt;script&gt; nếu anh bật tùy chọn.</p>
                <textarea id="import-code" class="landing-modal__textarea" placeholder="Dán HTML đầy đủ hoặc đoạn HTML vào đây..."></textarea>
                <div class="landing-modal__options">
                    <label class="landing-modal__check">
                        <input type="checkbox" id="import-css" checked>
                        Áp dụng CSS từ thẻ &lt;style&gt;
                    </label>
                    <label class="landing-modal__check">
                        <input type="checkbox" id="import-js" checked>
                        Áp dụng JS từ thẻ &lt;script&gt;
                    </label>
                </div>
            </div>
            <div class="landing-modal__footer">
                <button type="button" class="landing-btn" id="cancel-import-modal">Hủy</button>
                <button type="button" class="landing-btn landing-btn--primary" id="apply-import-modal">Áp dụng vào landing</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/grapesjs@0.22.5/dist/grapes.min.js"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage@1.0.3/dist/index.js"></script>
    <script>
        const initialProjectData = @json($projectData);
        const initialHtml = @json($course->landing_html ?? '');
        const initialCss = @json($course->landing_css ?? '');
        const initialJs = @json($course->landing_js ?? '');
        const courseTitle = @json($course->title);
        const courseDescription = @json(strip_tags((string) $course->description));
        const courseThumbnail = @json($course->thumbnail_url);
        const infoUrl = @json(route('courses.info', $course->slug));
        let manualScriptContent = (initialJs || '').trim();

        const defaultCss = `
            .lp-root{font-family:Arial,Helvetica,sans-serif;background:#0d0d0f;color:#fff}
            .lp-hero{max-width:1100px;margin:0 auto;padding:64px 20px;display:grid;grid-template-columns:1.1fr .9fr;gap:28px;align-items:center}
            .lp-title{font-size:48px;line-height:1.05;margin:0 0 12px;font-weight:800}
            .lp-desc{font-size:17px;line-height:1.7;color:rgba(255,255,255,.82)}
            .lp-cta{display:inline-block;margin-top:18px;background:#fff;color:#121212;text-decoration:none;padding:14px 20px;border-radius:12px;font-weight:700}
            .lp-img{width:100%;border-radius:18px;overflow:hidden;box-shadow:0 24px 70px rgba(0,0,0,.35)}
            .lp-sec{max-width:1100px;margin:0 auto;padding:22px 20px 56px}
            .lp-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
            .lp-card{background:#17181d;border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:18px}
            .lp-card h3{margin:0 0 8px;font-size:18px}
            .lp-card p{margin:0;color:rgba(255,255,255,.75);line-height:1.6}
            @media(max-width:900px){.lp-hero{grid-template-columns:1fr}.lp-title{font-size:34px}.lp-grid{grid-template-columns:1fr}}
        `;

        const defaultHtml = `
            <div class="lp-root">
                <section class="lp-hero">
                    <div>
                        <h1 class="lp-title">${courseTitle}</h1>
                        <p class="lp-desc">${courseDescription || 'Landing giới thiệu khoá học. Hãy chỉnh sửa nội dung, feedback, hình ảnh, lợi ích và CTA theo chiến dịch của bạn.'}</p>
                        <a href="${infoUrl}" class="lp-cta">Xem thông tin khóa học</a>
                    </div>
                    <div class="lp-img">
                        <img src="${courseThumbnail || 'https://placehold.co/900x506/111111/FFFFFF?text=Landing+Image'}" style="display:block;width:100%;height:auto;">
                    </div>
                </section>
                <section class="lp-sec">
                    <div class="lp-grid">
                        <article class="lp-card"><h3>Lợi ích rõ ràng</h3><p>Học viên hiểu trước họ sẽ đạt gì sau khóa học.</p></article>
                        <article class="lp-card"><h3>Feedback thực tế</h3><p>Thêm ảnh, quote, case study để tăng chuyển đổi.</p></article>
                        <article class="lp-card"><h3>CTA nổi bật</h3><p>Điều hướng về trang thông tin hoặc mua khóa học nhanh.</p></article>
                    </div>
                </section>
            </div>
        `;

        const editor = grapesjs.init({
            container: '#gjs',
            height: '100%',
            fromElement: false,
            storageManager: false,
            plugins: ['gjs-preset-webpage'],
            pluginsOpts: {
                'gjs-preset-webpage': {
                    modalImportTitle: 'Dán mã HTML/CSS',
                    modalImportLabel: 'Nhập nội dung và import',
                    filestackOpts: false,
                },
            },
        });

        const applyDefaultTemplate = () => {
            editor.setComponents((initialHtml || '').trim() || defaultHtml);
            editor.setStyle((initialCss || '').trim() || defaultCss);

            if (manualScriptContent) {
                const scriptBlock = editor.DomComponents.addComponent({
                    tagName: 'script',
                    content: manualScriptContent,
                });
                editor.getWrapper().append(scriptBlock);
            }
        };

        const hasRenderableContent = (html) => {
            if (!(html || '').trim()) {
                return false;
            }

            try {
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const nodes = Array.from(parsed.body.querySelectorAll('*'))
                    .filter((el) => el.tagName !== 'SCRIPT' && el.tagName !== 'STYLE');

                return nodes.length > 0 || (parsed.body.textContent || '').trim().length > 0;
            } catch (error) {
                return (html || '').trim().length > 0;
            }
        };

        const openImportModal = () => {
            document.getElementById('import-modal').classList.add('is-open');
            document.getElementById('import-code').focus();
        };

        const closeImportModal = () => {
            document.getElementById('import-modal').classList.remove('is-open');
        };

        const parseImportedSnippet = (rawSnippet) => {
            const parsed = new DOMParser().parseFromString(rawSnippet, 'text/html');
            const bodyHtml = (parsed.body?.innerHTML || '').trim();
            const styleText = Array.from(parsed.querySelectorAll('style'))
                .map((el) => el.textContent || '')
                .join('\n')
                .trim();
            const scriptText = Array.from(parsed.querySelectorAll('script'))
                .map((el) => el.textContent || '')
                .join('\n')
                .trim();

            return {
                html: bodyHtml || rawSnippet.trim(),
                css: styleText,
                js: scriptText,
            };
        };

        const applyImportedCode = () => {
            const rawSnippet = document.getElementById('import-code').value || '';
            if (!rawSnippet.trim()) {
                alert('Vui lòng dán code trước khi áp dụng.');
                return;
            }

            const { html, css, js } = parseImportedSnippet(rawSnippet);
            if (!html.trim()) {
                alert('Không đọc được HTML hợp lệ từ đoạn code vừa dán.');
                return;
            }

            editor.setComponents(html);

            if (document.getElementById('import-css').checked && css) {
                editor.setStyle(css);
            }

            if (document.getElementById('import-js').checked) {
                manualScriptContent = js || '';
            }

            closeImportModal();
            resizeEditor();
        };

        const resizeEditor = () => {
            const toolbarHeight = document.querySelector('.landing-toolbar')?.offsetHeight || 0;
            const statusHeight = document.querySelector('.landing-status')?.offsetHeight || 0;
            const errorHeight = document.querySelector('.landing-error')?.offsetHeight || 0;
            const editorHeight = Math.max(window.innerHeight - toolbarHeight - statusHeight - errorHeight, 320);

            const gjsEl = document.getElementById('gjs');
            gjsEl.style.height = `${editorHeight}px`;

            try {
                editor.refresh({ tools: true });
            } catch (error) {
                // ignore transient resize errors
            }
        };

        const hasUsableProjectData = (() => {
            if (!initialProjectData || typeof initialProjectData !== 'object') {
                return false;
            }

            if (Array.isArray(initialProjectData)) {
                return false;
            }

            const pages = initialProjectData.pages;

            if (Array.isArray(pages) && pages.length > 0) {
                return true;
            }

            if (pages && typeof pages === 'object' && Object.keys(pages).length > 0) {
                return true;
            }

            return Object.keys(initialProjectData).length > 0 && !!initialProjectData.styles;
        })();

        try {
            if (hasUsableProjectData) {
                editor.loadProjectData(initialProjectData);
            } else {
                applyDefaultTemplate();
            }
        } catch (error) {
            applyDefaultTemplate();
        }

        editor.on('load', () => {
            if (!hasRenderableContent(editor.getHtml())) {
                applyDefaultTemplate();
            }

            resizeEditor();
            setTimeout(resizeEditor, 80);
            setTimeout(resizeEditor, 280);
        });

        window.addEventListener('resize', resizeEditor);
        window.addEventListener('orientationchange', resizeEditor);
        resizeEditor();

        document.getElementById('open-import-modal').addEventListener('click', openImportModal);
        document.getElementById('close-import-modal').addEventListener('click', closeImportModal);
        document.getElementById('cancel-import-modal').addEventListener('click', closeImportModal);
        document.getElementById('apply-import-modal').addEventListener('click', applyImportedCode);
        document.getElementById('import-modal').addEventListener('click', function (event) {
            if (event.target.id === 'import-modal') {
                closeImportModal();
            }
        });

        document.getElementById('save-landing').addEventListener('click', function () {
            document.getElementById('landing_html').value = editor.getHtml();
            document.getElementById('landing_css').value = editor.getCss();
            const jsChunks = [manualScriptContent, editor.getJs()]
                .map((item) => (item || '').trim())
                .filter((item) => item.length > 0);
            document.getElementById('landing_js').value = Array.from(new Set(jsChunks)).join('\n\n');
            document.getElementById('landing_project_data').value = JSON.stringify(editor.getProjectData());
            document.getElementById('landing-form').submit();
        });
    </script>
</body>
</html>
