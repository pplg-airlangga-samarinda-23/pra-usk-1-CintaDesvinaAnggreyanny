<?php
function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

function show_alert($message, $type = 'success') {
    return "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
}
?>
