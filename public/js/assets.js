document.addEventListener('DOMContentLoaded', () => {
    const deleteModalElement = document.getElementById('deleteConfirmModal');
    const confirmDeleteButton = document.getElementById('confirmDeleteButton');
    const deleteAssetName = document.getElementById('deleteAssetName');

    if (!deleteModalElement || !confirmDeleteButton || !deleteAssetName) {
        return;
    }

    const deleteModal = new bootstrap.Modal(deleteModalElement);
    let pendingDeleteForm;

    document.querySelectorAll('.delete-asset-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            pendingDeleteForm = form;
            deleteAssetName.textContent = form.dataset.assetName;
            deleteModal.show();
        });
    });

    confirmDeleteButton.addEventListener('click', () => {
        if (pendingDeleteForm) {
            pendingDeleteForm.submit();
        }
    });
});
