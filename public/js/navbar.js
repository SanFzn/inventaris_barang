/**
 * Navbar & Global App Interaction Script
 */
document.addEventListener('DOMContentLoaded', () => {
    // ── Logout confirmation ──────────────────────────────────────
    const logoutForm = document.querySelector('.logout-form');
    const logoutModalElement = document.getElementById('logoutConfirmModal');
    const confirmLogoutButton = document.getElementById('confirmLogoutButton');

        if (logoutForm && logoutModalElement && confirmLogoutButton) {
            const logoutModal = new bootstrap.Modal(logoutModalElement);
            logoutForm.addEventListener('submit', (event) => {
                event.preventDefault();
                logoutModal.show();
            });
            confirmLogoutButton.addEventListener('click', () => logoutForm.submit());
        }

            // ── Profile modal: submit active tab's form ──────────────────
            const saveProfileBtn = document.getElementById('saveProfileBtn');
            if (saveProfileBtn) {
                saveProfileBtn.addEventListener('click', () => {
                    const activePane = document.querySelector('#profileTabContent .tab-pane.active');
                    if (activePane) {
                        const form = activePane.querySelector('form');
                        if (form) form.submit();
                    }
                });
            }

            // ── Toggle password visibility ───────────────────────────────
            document.querySelectorAll('.toggle-password').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    btn.querySelector('i').classList.toggle('bi-eye', !isPassword);
                    btn.querySelector('i').classList.toggle('bi-eye-slash', isPassword);
                });
            });
        });
