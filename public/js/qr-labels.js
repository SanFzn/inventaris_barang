/**
 * QR Labels Print & Download Script (/qr/labels)
 */
document.addEventListener('DOMContentLoaded', () => {
    const selectors = document.querySelectorAll('.asset-selector');
    const selectedCount = document.getElementById('selectedCount');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
    const printSelectedBtn = document.getElementById('printSelectedBtn');
    const downloadCountSpan = document.querySelector('.download-count');
    const printCountSpan = document.querySelector('.print-count');

    if (!selectors.length) return;

    function updateSelectedCardStyles() {
        document.querySelectorAll('.label-preview').forEach((card) => {
            const checkbox = card.querySelector('.asset-selector');
            if (checkbox && checkbox.checked) {
                card.classList.add('border', 'border-2', 'border-primary');
            } else {
                card.classList.remove('border-primary', 'border-2');
            }
        });
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.asset-selector:checked');
        const count = checked.length;

        if (selectedCount) {
            if (count === 0) {
                selectedCount.textContent = 'Belum ada label dipilih';
                selectedCount.className = 'badge bg-secondary bg-opacity-10 text-secondary border fs-6 px-3 py-2 rounded-pill';
            } else {
                selectedCount.textContent = `${count} label dipilih`;
                selectedCount.className = 'badge bg-primary bg-opacity-10 text-primary border border-primary fs-6 px-3 py-2 rounded-pill';
            }
        }

        if (clearSelectionBtn) {
            clearSelectionBtn.disabled = count === 0;
        }
        if (downloadSelectedBtn) {
            downloadSelectedBtn.disabled = count === 0;
        }
        if (printSelectedBtn) {
            printSelectedBtn.disabled = count === 0;
        }
        if (downloadCountSpan) {
            downloadCountSpan.textContent = count;
        }
        if (printCountSpan) {
            printCountSpan.textContent = count;
        }

        updateSelectedCardStyles();
    }

    selectors.forEach((selector) => {
        selector.addEventListener('change', updateSelectedCount);
    });

    // Make entire label preview card clickable to toggle selection
    document.querySelectorAll('.label-preview').forEach((card) => {
        card.addEventListener('click', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL' || e.target.closest('.label-selector')) {
                return;
            }
            const checkbox = card.querySelector('.asset-selector');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                updateSelectedCount();
            }
        });
    });

    // Select all button
    selectAllBtn?.addEventListener('click', () => {
        const allChecked = Array.from(selectors).every((s) => s.checked);
        selectors.forEach((s) => {
            s.checked = !allChecked;
        });
        updateSelectedCount();
    });

    // Clear selection button
    clearSelectionBtn?.addEventListener('click', () => {
        selectors.forEach((s) => {
            s.checked = false;
        });
        updateSelectedCount();
    });

    // Select all per category button
    document.querySelectorAll('.select-category-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const categorySlug = btn.dataset.category;
            const categoryGroup = document.querySelector(`[data-category-group="${categorySlug}"]`);
            if (!categoryGroup) return;

            const categorySelectors = categoryGroup.querySelectorAll('.asset-selector');
            const allCheckedInCategory = Array.from(categorySelectors).every((s) => s.checked);

            categorySelectors.forEach((s) => {
                s.checked = !allCheckedInCategory;
            });
            updateSelectedCount();
        });
    });

    // Initial state check on load
    updateSelectedCount();

    // Scroll to first selected label if present
    const initiallyChecked = document.querySelector('.asset-selector:checked');
    if (initiallyChecked) {
        const targetCard = initiallyChecked.closest('.label-preview');
        if (targetCard) {
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Download selected QR images
    downloadSelectedBtn?.addEventListener('click', async () => {
        const selected = document.querySelectorAll('.asset-selector:checked');

        if (!selected.length) {
            window.alert('Pilih minimal satu label untuk disimpan.');
            return;
        }

        for (const el of selected) {
            const code = el.dataset.code;
            const link = document.createElement('a');
            link.href = '/qr/generate/' + encodeURIComponent(code) + '?size=1000';
            link.download = code.replace(/[^a-z0-9-_]+/gi, '-').toLowerCase() + '.png';
            document.body.appendChild(link);
            link.click();
            link.remove();
            if (selected.length > 1) {
                await new Promise((resolve) => setTimeout(resolve, 300));
            }
        }
    });

    // Print selected QR labels
    printSelectedBtn?.addEventListener('click', () => {
        const selected = document.querySelectorAll('.asset-selector:checked');

        if (!selected.length) {
            window.alert('Pilih minimal satu label untuk dicetak.');
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

