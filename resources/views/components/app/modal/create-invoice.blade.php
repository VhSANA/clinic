@props([
    $patient
])



<script>

    document.addEventListener('DOMContentLoaded', function () {
        const identifier = '';
        const modal = document.getElementById(`select-personnel-shift-${identifier}`);
        const openModal = document.getElementById(`open-modal-btn-${identifier}`);
        const closeModal = document.getElementById(`close-modal-btn-${identifier}`);
        const cancelModal = document.getElementById(`cancel-modal-btn-${identifier}`);

        // Open Modal
        openModal.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        // Close Modal
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        // Cancel Button
        cancelModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });
</script>
