document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const closeOverlay = document.querySelector('[data-sidebar-close]');
    if (toggle && sidebar) toggle.addEventListener('click', function () { document.body.classList.toggle('sidebar-open'); });
    if (closeOverlay) closeOverlay.addEventListener('click', function () { document.body.classList.remove('sidebar-open'); });

    document.querySelectorAll('[data-dismiss-alert]').forEach(function (button) {
        button.addEventListener('click', function () { const alert = button.closest('.alert'); if (alert) alert.remove(); });
    });
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.getElementById(button.getAttribute('data-password-toggle'));
            if (!input) return;
            const reveal = input.type === 'password'; input.type = reveal ? 'text' : 'password'; button.textContent = reveal ? 'Sembunyikan' : 'Lihat';
        });
    });
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.getAttribute('data-confirm') || 'Lanjutkan tindakan ini?')) event.preventDefault();
        });
    });
});
