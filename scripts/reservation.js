document.querySelectorAll('.toggleEditReservationForm').forEach(button => {
    button.addEventListener('click', function () {
        // Get reservation details from the clicked button's data attributes
        const reservationId = button.getAttribute('data-id');
        const reservationDate = button.getAttribute('data-date');
        const reservationTimeslot = button.getAttribute('data-timeslot');
        const reservationQuantity = button.getAttribute('data-quantity');

        // Populate the form fields
        document.getElementById('edit_reservation_id').value = reservationId;
        document.getElementById('edit_reservation_date').value = reservationDate;
        document.getElementById('edit_reservation_timeslot').value = reservationTimeslot;
        document.getElementById('edit_reservation_quantity').value = reservationQuantity;

        // Show the form
        const editReservationForm = document.querySelector('.editReservationForm');
        editReservationForm.classList.remove('hidden');
    });
});

document.querySelectorAll('.remove_reservations').forEach(button => {
    button.addEventListener('click', function (e) {
        if (!confirm('Opravdu chcete tuto rezervaci smazat?')) {
            e.preventDefault();
        }
    });
});

// toggle edit form visibility
document.querySelectorAll('.editButton').forEach(function(button) {
    button.addEventListener('click', function() {
        // Get the reservation id from the button's id attribute
        const reservationId = button.id.replace('editButton_', '');
        const editForm = document.getElementById('editForm_' + reservationId);

        // Toggle the 'hidden' class on the corresponding form
        if (editForm.classList.contains('hidden')) {
            editForm.classList.remove('hidden');
        } else {
            editForm.classList.add('hidden');
        }
    });
});
