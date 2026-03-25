<!-- Static frontend assets (no Vite build required) -->
<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@1.8.4/dist/axios.min.js"></script>
<script>
    if (window.axios) {
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }
</script>
