@once
    <script>
        (() => {
            const USERS_ROUTE_PATTERN = /(?:\/public)?\/admin\/users(?:\/|$)/i;
            const state = {
                pinnedDropdown: null,
                initialized: false,
            };

            const isUsersRoute = () => USERS_ROUTE_PATTERN.test(window.location.pathname);

            const isModalOpen = () => document.querySelector('.fi-modal.fi-modal-open') !== null;

            const getClosestDropdown = (target) => target instanceof Element
                ? target.closest('.fi-ta-actions .fi-dropdown')
                : null;

            const getTrigger = (dropdown) => dropdown?.querySelector('.fi-dropdown-trigger');

            const canUseDropdown = (dropdown) => dropdown instanceof Element && document.body.contains(dropdown);

            const reopenPinnedDropdown = () => {
                const dropdown = state.pinnedDropdown;

                if (!canUseDropdown(dropdown)) {
                    state.pinnedDropdown = null;

                    return;
                }

                if (isModalOpen()) {
                    return;
                }

                const trigger = getTrigger(dropdown);

                if (!trigger || trigger.getAttribute('aria-expanded') === 'true') {
                    return;
                }

                trigger.dispatchEvent(new MouseEvent('click', {
                    bubbles: true,
                    cancelable: true,
                    view: window,
                }));
            };

            const onDocumentClick = (event) => {
                if (!isUsersRoute()) {
                    return;
                }

                const target = event.target;
                const clickedDropdown = getClosestDropdown(target);

                if (clickedDropdown) {
                    state.pinnedDropdown = clickedDropdown;

                    return;
                }

                if (!state.pinnedDropdown) {
                    return;
                }

                if (target instanceof Element && target.closest('.fi-dropdown-panel')) {
                    return;
                }

                window.setTimeout(reopenPinnedDropdown, 0);
            };

            const resetStateOnNavigation = () => {
                state.pinnedDropdown = null;
            };

            const init = () => {
                if (state.initialized) {
                    return;
                }

                state.initialized = true;

                document.addEventListener('click', onDocumentClick, true);
                document.addEventListener('livewire:navigated', resetStateOnNavigation);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init, { once: true });
            } else {
                init();
            }
        })();
    </script>
@endonce
