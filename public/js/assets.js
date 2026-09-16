/**
 * Asset Management Page Script (/aset)
 */
document.addEventListener('DOMContentLoaded', () => {
    const deleteModalElement = document.getElementById('deleteConfirmModal');
    if (!deleteModalElement) return;

    const deleteModal = new bootstrap.Modal(deleteModalElement);
    const deleteAssetName = document.getElementById('deleteAssetName');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    let pendingDeleteForm = null;

    document.querySelectorAll('.delete-asset-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            pendingDeleteForm = form;
            if (deleteAssetName) {
                deleteAssetName.textContent = form.dataset.assetName || '';
            }
            deleteModal.show();
        });
    });

    confirmDeleteButton?.addEventListener('click', () => {
        if (pendingDeleteForm) {
            pendingDeleteForm.submit();
        }
    });
});

