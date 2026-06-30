document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el);
    });

    const alertList = document.querySelectorAll('.alert');
    alertList.forEach(function (alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    const confirmForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    confirmForms.forEach(function(form) {
        const originalSubmit = form.onsubmit;
        form.onsubmit = null;
        form.addEventListener('submit', function(e) {
            if (!confirm(this.getAttribute('data-confirm-msg') || '¿Estás seguro de realizar esta acción?')) {
                e.preventDefault();
            }
        });
    });
});
