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
});
