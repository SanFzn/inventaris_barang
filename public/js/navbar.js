/**
 * Navbar & Global App Interaction Script
 */
document.addEventListener('DOMContentLoaded', () => {
    const logoutForm = document.querySelector('.logout-form');
    const logoutModalElement = document.getElementById('logoutConfirmModal');
    const confirmLogoutButton = document.getElementById('confirmLogoutButton');

    if (!logoutForm || !logoutModalElement || !confirmLogoutButton) {
        return;
    }

    const logoutModal = new bootstrap.Modal(logoutModalElement);
    logoutForm.addEventListener('submit', (event) => {
        event.preventDefault();
        logoutModal.show();
    });
    confirmLogoutButton.addEventListener('click', () => logoutForm.submit());
});

