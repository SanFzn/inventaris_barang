/**
 * Standalone QR Scanner Page Script (/qr/pindai)
 */
document.addEventListener('DOMContentLoaded', () => {
    const readerContainer = document.getElementById('qr-reader');
    if (!readerContainer || typeof Html5Qrcode === 'undefined') return;

    const generateUrlTemplate = document.body.dataset.generateUrl || '/qr/generate/:code';
    const baseUrl = generateUrlTemplate.replace(':code', '');
    const reader = new Html5Qrcode('qr-reader');
    const status = document.getElementById('scan-status');
    const result = document.getElementById('scan-result');
    const cameraButton = document.getElementById('start-camera');
    const photoButton = document.getElementById('scan-photo');
    const photoInput = document.getElementById('photo-input');
    const photoModalElement = document.getElementById('photoResultModal');
    const photoModal = photoModalElement ? new bootstrap.Modal(photoModalElement) : null;
    let cameraRunning = false;
    let isPhotoMode = false;

    function showStatus(message, type = 'secondary') {
        status.className = `alert alert-${type}`;
        status.textContent = message;
    }

    function showResultForCamera(decodedText) {
        result.className = 'alert alert-success';
        result.innerHTML = `<img src="${baseUrl}${encodeURIComponent(decodedText)}" alt="QR" class="qr-result-image"><div style="margin-top: 0.5rem;"><strong>QR terbaca:</strong> <span></span></div>`;
        result.querySelector('span').textContent = decodedText;
        showStatus('Pemindaian selesai.', 'success');
    }

    function showResultForPhoto(decodedText) {
        const modalQrImg = document.createElement('img');
        modalQrImg.src = `${baseUrl}${encodeURIComponent(decodedText)}`;
        modalQrImg.alt = 'QR Code';
        
        const modalQrContainer = document.getElementById('modal-qr');
        if (modalQrContainer) {
            modalQrContainer.innerHTML = '';
            modalQrContainer.appendChild(modalQrImg);
        }
        
        const modalQrText = document.getElementById('modal-qr-text');
        if (modalQrText) {
            modalQrText.textContent = decodedText;
        }
        
        if (photoModal) {
            photoModal.show();
        }
        
        showStatus('QR dari foto berhasil dipindai.', 'success');
    }

    function showResult(decodedText) {
        if (isPhotoMode) {
            showResultForPhoto(decodedText);
        } else {
            showResultForCamera(decodedText);
        }
    }

    function onScanSuccess(decodedText) {
        if (cameraRunning) {
            reader.stop().then(() => {
                try { reader.clear(); } catch (e) {}
            }).catch(() => {});
            cameraRunning = false;
            cameraButton.textContent = 'Buka Kamera';
        }
        showResult(decodedText);
    }

    cameraButton?.addEventListener('click', async () => {
        if (cameraRunning) return;
        isPhotoMode = false;
        try { reader.clear(); } catch (e) {}
        result.className = 'alert alert-success d-none';
        showStatus('Meminta izin kamera...', 'info');
        try {
            await reader.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 250, height: 250 }, disableFlip: false },
                onScanSuccess,
                () => {}
            );
            const videoElement = document.querySelector('#qr-reader video');
            if (videoElement) {
                videoElement.style.filter = 'none';
            }
            cameraRunning = true;
            cameraButton.textContent = 'Kamera Aktif';
            showStatus('Arahkan kamera ke kode QR.', 'info');
        } catch (error) {
            showStatus('Kamera tidak dapat dibuka. Periksa izin browser atau gunakan Pilih Foto.', 'danger');
        }
    });

    photoButton?.addEventListener('click', () => photoInput?.click());
    photoInput?.addEventListener('change', async (event) => {
        const file = event.target.files[0];
        if (!file) return;
        isPhotoMode = true;
        try { reader.clear(); } catch (e) {}
        showStatus('Membaca QR dari foto...', 'info');
        try {
            const decodedText = await reader.scanFile(file, false);
            showResult(decodedText);
        } catch (error) {
            showStatus('Kode QR tidak ditemukan pada foto.', 'warning');
            isPhotoMode = false;
        }
        photoInput.value = '';
    });
});

