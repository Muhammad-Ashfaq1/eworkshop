/**
 * Custom SweetAlert functions for reuse across the application
 */

// Show success message
function showSuccessAlert(message, timer = 2000) {
    Swal.fire({
        title: "Success!",
        text: message,
        icon: "success",
        customClass: {
            confirmButton: "btn btn-primary w-xs mt-2"
        },
        buttonsStyling: false,
        timer: timer,
        showConfirmButton: timer ? false : true
    });
}

// Show error message
function showErrorAlert(message) {
    Swal.fire({
        title: "Error!",
        text: message,
        icon: "error",
        customClass: {
            confirmButton: "btn btn-primary w-xs mt-2"
        },
        buttonsStyling: false
    });
}

// Show confirmation dialog
function showDeleteConfirmation(callback) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        customClass: {
            confirmButton: "btn btn-primary w-xs me-2 mt-2",
            cancelButton: "btn btn-danger w-xs mt-2",
        },
        buttonsStyling: false,
        showCloseButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            showCancelledAlert();
        }
    });
}

// Show cancelled operation message
function showCancelledAlert() {
    Swal.fire({
        title: "Cancelled",
        text: "Your record is safe :)",
        icon: "error",
        customClass: {
            confirmButton: "btn btn-primary mt-2",
        },
        buttonsStyling: false,
    });
}

// Show auto-closing toast notification
function showToast(message, icon = 'success', position = 'top-end') {
    Swal.fire({
        position: position,
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: 1500,
        showCloseButton: true,
        toast: true
    });
}

// Show loading state
function showLoading(message = 'Processing...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Close any open SweetAlert
function closeAlert() {
    Swal.close();
}
