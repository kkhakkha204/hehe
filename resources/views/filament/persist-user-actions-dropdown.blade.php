@once
    <script>
        (() => {
            /**
             * Keep the table-actions dropdown open while the user interacts
             * with a modal that was opened from that dropdown.
             *
             * Problem:  x-float's click-outside handler (registered on `window`
             *           in the bubble phase) closes the dropdown whenever the
             *           user clicks Cancel / X / overlay on a modal, because
             *           those elements live outside the dropdown's DOM parent.
             *
             * Solution: Register our OWN click handler on `window` in the
             *           bubble phase BEFORE any x-float handler (our script
             *           runs at page load; x-float registers when a dropdown
             *           opens, i.e. later).  When we detect a click inside a
             *           .fi-modal while a dropdown is "pinned", we call
             *           stopImmediatePropagation() so x-float's handler never
             *           fires → dropdown stays open.
             *
             *           Modal close still works because the modal's own
             *           handlers (x-on:click.self, x-on:click on buttons)
             *           are on DOM elements, which fire during the normal
             *           bubble BEFORE the event reaches `window`.
             */

            let hasPinnedDropdown = false;

            // ─── Capturing phase (fires first) ───────────────────────────
            // Decide per-click whether we have a pinned dropdown and whether
            // the click is "safe" (i.e. outside any dropdown and any modal),
            // in which case we clear the pin so the next bubble-phase handler
            // lets x-float close the dropdown normally.
            document.addEventListener('click', (e) => {
                const t = e.target;
                if (!(t instanceof Element)) return;

                // Clicked a dropdown trigger / item → pin it
                if (t.closest('.fi-ta-actions .fi-dropdown')) {
                    hasPinnedDropdown = true;
                    return;
                }

                // Clicked inside a modal → keep the pin (handled in bubble)
                if (t.closest('.fi-modal')) return;

                // Clicked inside a floating dropdown-panel (teleported content)
                if (t.closest('.fi-dropdown-panel')) return;

                // Clicked "outside" everything → unpin (allow normal close)
                hasPinnedDropdown = false;
            }, true);

            // ─── Bubble phase on window (fires before x-float's handler) ─
            window.addEventListener('click', (e) => {
                if (!hasPinnedDropdown) return;

                const t = e.target;
                if (!(t instanceof Element)) return;

                // If this click was inside a modal (X, Cancel, overlay, anything)
                // → block x-float from closing the dropdown.
                if (t.closest('.fi-modal')) {
                    e.stopImmediatePropagation();
                }
            }, false);

            // ─── SPA navigation reset ────────────────────────────────────
            document.addEventListener('livewire:navigated', () => {
                hasPinnedDropdown = false;
            });
        })();
    </script>
@endonce
