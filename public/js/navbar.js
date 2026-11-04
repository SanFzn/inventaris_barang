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

    // ── Admin real-time loan notification polling ────────────────
    if (window.adminCheckPendingUrl) {
        let lastCount = window.initialPendingCount || 0;
        let lastLatestId = null;

        const checkPendingLoans = async () => {
            try {
                const response = await fetch(window.adminCheckPendingUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) return;
                const data = await response.json();

                // Update badge in sidebar
                const sidebarBadge = document.getElementById('sidebar-approvals-badge');
                if (sidebarBadge) {
                    sidebarBadge.textContent = data.count;
                    if (data.count > 0) {
                        sidebarBadge.classList.remove('d-none');
                    } else {
                        sidebarBadge.classList.add('d-none');
                    }
                }

                // Update badge in topbar bell
                const navbarBadge = document.getElementById('navbar-notif-badge');
                if (navbarBadge && data.count > 0) {
                    navbarBadge.classList.remove('d-none');
                }

                // If count increased and there is a new latest item
                if (data.count > 0 && data.count > lastCount && data.latest && data.latest.id_pinjam !== lastLatestId) {
                    lastLatestId = data.latest.id_pinjam;
                    lastCount = data.count;

                    // Show Toast
                    const toastEl = document.getElementById('adminLoanToast');
                    if (toastEl && typeof bootstrap !== 'undefined') {
                        const userEl = document.getElementById('toastLoanUser');
                        const itemEl = document.getElementById('toastLoanItem');
                        const timeEl = document.getElementById('toastLoanTime');
                        if (userEl) userEl.textContent = data.latest.user_name;
                        if (itemEl) itemEl.textContent = `Mengajukan peminjaman: ${data.latest.barang_nama}`;
                        if (timeEl) timeEl.textContent = data.latest.tgl;

                        const toast = new bootstrap.Toast(toastEl, { delay: 10000 });
                        toast.show();
                    }

                    // Native browser notification if permitted
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('Pengajuan Peminjaman Baru', {
                            body: `${data.latest.user_name} mengajukan peminjaman ${data.latest.barang_nama}`
                        });
                    }
                } else {
                    lastCount = data.count;
                }
            } catch (err) {
                // Ignore network errors during polling
            }
        };

        // Poll every 8 seconds
        setInterval(checkPendingLoans, 8000);

        // Request browser notification permission once user interacts
        if ('Notification' in window && Notification.permission === 'default') {
            document.addEventListener('click', () => {
                Notification.requestPermission();
            }, { once: true });
        }
    }
});
