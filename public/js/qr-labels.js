/**
 * QR Labels Print & Download Script (/qr/labels)
 */
document.addEventListener('DOMContentLoaded', () => {
    const selectors = document.querySelectorAll('.asset-selector');
    const selectedCount = document.getElementById('selectedCount');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
    const printSelectedBtn = document.getElementById('printSelectedBtn');

    if (!selectors.length) return;

    function updateSelectedCount() {
        const count = document.querySelectorAll('.asset-selector:checked').length;
        if (selectedCount) {
            selectedCount.textContent = count === 1 ? '1 label dipilih' : 'Belum ada label dipilih';
        }
        if (clearSelectionBtn) {
            clearSelectionBtn.disabled = count === 0;
        }
        if (downloadSelectedBtn) {
            downloadSelectedBtn.disabled = count !== 1;
        }
    }

    selectors.forEach((selector) => selector.addEventListener('change', updateSelectedCount));

    clearSelectionBtn?.addEventListener('click', () => {
        selectors.forEach((selector) => {
            selector.checked = false;
        });
        updateSelectedCount();
    });

    downloadSelectedBtn?.addEventListener('click', () => {
        const selected = document.querySelector('.asset-selector:checked');

        if (!selected) {
            window.alert('Pilih satu label untuk disimpan.');
            return;
        }

        const code = selected.dataset.code;
        const link = document.createElement('a');
        link.href = '/qr/generate/' + encodeURIComponent(code) + '?size=1000';
        link.download = code.replace(/[^a-z0-9-_]+/gi, '-').toLowerCase() + '.png';
        document.body.appendChild(link);
        link.click();
        link.remove();
    });

    printSelectedBtn?.addEventListener('click', () => {
        const selected = document.querySelectorAll('.asset-selector:checked');

        if (selected.length !== 1) {
            window.alert('Pilih satu label untuk dicetak.');
            return;
        }

        selected.forEach((selector) => {
            selector.closest('.label-preview')?.classList.add('selected-for-print');
        });
        window.print();
    });

    window.addEventListener('afterprint', () => {
        document.querySelectorAll('.selected-for-print').forEach((label) => {
            label.classList.remove('selected-for-print');
        });
    });
});

