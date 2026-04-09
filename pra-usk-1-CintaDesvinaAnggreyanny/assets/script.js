function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
}

document.addEventListener("DOMContentLoaded", function() {
    
    const sidebarToggleTop = document.getElementById('sidebarToggleTop');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggleTop && sidebar) {
        sidebarToggleTop.addEventListener('click', function() {
            sidebar.classList.toggle('d-none');
        });
    }

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 3000);
});
