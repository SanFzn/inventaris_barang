/**
 * Dashboard QR Scanner Modal Script
 */
document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('scanQrModal');
    if (!modalElement || typeof Html5Qrcode === 'undefined') return;

    const generateUrlTemplate = modalElement.dataset.generateUrl || '/qr/generate/:code';
    const baseUrl = generateUrlTemplate.replace(':code', '');
    const dashboardReader = new Html5Qrcode('dashboard-qr-reader');
    const dashboardStatus = document.getElementById('dashboard-scan-status');
    const dashboardResult = document.getElementById('dashboard-scan-result');
    const dashboardCameraButton = document.getElementById('dashboard-camera-button');
    const dashboardPhotoButton = document.getElementById('dashboard-photo-button');
    const dashboardPhotoInput = document.getElementById('dashboard-photo-input');
    let dashboardCameraRunning = false;

    function setDashboardStatus(message, type = 'secondary') {
        dashboardStatus.className = `alert alert-${type} mt-3`;
        dashboardStatus.textContent = message;
    }

    function setDashboardResult(decodedText) {
        dashboardResult.className = 'alert alert-success mt-3 text-center';
        dashboardResult.innerHTML = `
            <div class="d-flex flex-column align-items-center">
                <img src="${baseUrl}${encodeURIComponent(decodedText)}" alt="QR Code" class="bg-white p-2 rounded border shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: contain;">
                <div><strong>QR terbaca:</strong> <span class="text-break">${decodedText}</span></div>
            </div>
        `;
    }

    function handleDashboardScan(decodedText) {
        if (dashboardCameraRunning) {
            dashboardReader.stop().then(() => {
                try { dashboardReader.clear(); } catch (e) {}
            }).catch(() => {});
            dashboardCameraRunning = false;
            dashboardCameraButton.innerHTML = '<i class="bi bi-camera me-1"></i>Buka Kamera';
        }
        setDashboardResult(decodedText);
        setDashboardStatus('Pemindaian selesai.', 'success');
    }

    dashboardCameraButton.addEventListener('click', async () => {
        if (dashboardCameraRunning) return;
        try { dashboardReader.clear(); } catch (e) {}
        setDashboardStatus('Meminta izin kamera...', 'info');
        try {
            await dashboardReader.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 220, height: 220 } },
                handleDashboardScan,
                () => {}
            );
            dashboardCameraRunning = true;
            dashboardCameraButton.innerHTML = '<i class="bi bi-camera-fill me-1"></i>Kamera Aktif';
            setDashboardStatus('Arahkan kamera ke kode QR.', 'info');
        } catch (error) {
            setDashboardStatus('Kamera tidak dapat dibuka. Gunakan Foto atau periksa izin browser.', 'danger');
        }
    });

    dashboardPhotoButton.addEventListener('click', () => dashboardPhotoInput.click());
    dashboardPhotoInput.addEventListener('change', async (event) => {
        const file = event.target.files[0];
        if (!file) return;
        try { dashboardReader.clear(); } catch (e) {}
        setDashboardStatus('Membaca QR dari foto...', 'info');
        try {
            setDashboardResult(await dashboardReader.scanFile(file, false));
            setDashboardStatus('Pemindaian selesai.', 'success');
        } catch (error) {
            setDashboardStatus('Kode QR tidak ditemukan pada foto.', 'warning');
        }
        dashboardPhotoInput.value = '';
    });

    modalElement.addEventListener('hidden.bs.modal', () => {
        if (dashboardCameraRunning) {
            dashboardReader.stop().catch(() => {});
            dashboardCameraRunning = false;
            dashboardCameraButton.innerHTML = '<i class="bi bi-camera me-1"></i>Buka Kamera';
        }
        try { dashboardReader.clear(); } catch (e) {}
        dashboardResult.className = 'alert alert-success d-none mt-3';
        dashboardStatus.className = 'alert alert-secondary d-none mt-3';
    });
});

