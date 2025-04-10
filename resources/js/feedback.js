document.addEventListener('DOMContentLoaded', function() {
    const feedbackModal = document.getElementById('feedbackModal');
    if (feedbackModal) {
        feedbackModal.addEventListener('hidden.bs.modal', function() {
            // Reset form when modal is closed
            if (feedbackForm) {
                feedbackForm.reset();
            }
        });
    }
});
