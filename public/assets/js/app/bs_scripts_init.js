document.addEventListener('DOMContentLoaded', function () {
    var autoDismissAlerts = document.querySelectorAll('.alert-dismissible');

    autoDismissAlerts.forEach(function (alert) {
        setTimeout(function () {
            alert.classList.add('visually-hidden');
        }, 2000);
    });
});
