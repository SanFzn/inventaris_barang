/**
 * Asset Management Page Script (/aset)
 */
document.addEventListener("DOMContentLoaded", () => {
    const assetNameInput = document.getElementById("nama_barang");
    const assetCodeInput = document.getElementById("kode_barang");

    if (assetNameInput && assetCodeInput) {
        const updateAssetCode = () => {
            const prefix = assetNameInput.value
                .trim()
                .slice(0, 4)
                .toUpperCase();
            assetCodeInput.value = prefix
                ? `BRG-${prefix}-${Math.floor(Math.random() * 900) + 100}`
                : "";
        };

        assetNameInput.addEventListener("input", updateAssetCode);
        updateAssetCode();
    }

    const deleteModalElement = document.getElementById("deleteConfirmModal");
    const confirmDeleteButton = document.getElementById("confirmDeleteButton");
    const deleteAssetName = document.getElementById("deleteAssetName");

    if (!deleteModalElement || !confirmDeleteButton || !deleteAssetName) {
        return;
    }

    const deleteModal = new bootstrap.Modal(deleteModalElement);
    let pendingDeleteForm = null;

    document.querySelectorAll(".delete-asset-form").forEach((form) => {
        form.addEventListener("submit", (event) => {
            event.preventDefault();
            pendingDeleteForm = form;
            deleteAssetName.textContent = form.dataset.assetName || "";
            deleteModal.show();
        });
    });

    confirmDeleteButton.addEventListener("click", () => {
        if (pendingDeleteForm) {
            pendingDeleteForm.submit();
        }
    });

    // Word Counter & Validation for Spesifikasi / Deskripsi
    function countWords(text) {
        const trimmed = (text || "").trim();
        if (!trimmed) return 0;
        const matches = trimmed.match(/\S+/g);
        return matches ? matches.length : 0;
    }

    function initWordCounter(textarea) {
        const wrapper = textarea.closest("div") || textarea.parentElement;
        const counter = wrapper ? wrapper.querySelector(".word-counter") : null;
        const minWords = 10;

        function update() {
            const words = countWords(textarea.value);
            if (!counter) return;

            if (words < minWords) {
                counter.textContent = `${words} / ${minWords} kata (kurang ${minWords - words} kata)`;
                counter.className = "word-counter text-danger small fw-semibold";
                textarea.setCustomValidity(`Deskripsi / spesifikasi wajib diisi minimal ${minWords} kata (saat ini ${words} kata).`);
            } else {
                counter.textContent = `${words} / ${minWords} kata ✓`;
                counter.className = "word-counter text-success small fw-semibold";
                textarea.setCustomValidity("");
            }
        }

        textarea.addEventListener("input", update);
        update();
    }

    document.querySelectorAll(".asset-desc-input, textarea[name='spesifikasi']").forEach(initWordCounter);
});
