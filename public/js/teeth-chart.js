$(document).ready(function() {
    const form = $('#teethRecordForm');
    const patientId = window.patientId || null;

    if (!patientId) {
        console.error('Patient ID not found');
        return;
    }

    $(document).on('click', '.tooth-button', function() {
        const toothNumber = $(this).data('tooth');
        $('#modalToothNumber').text(toothNumber);
        $('#tooth_number').val(toothNumber);
        
        $.ajax({
            url: `/patients/${patientId}/teeth-records/${toothNumber}`,
            method: 'GET',
            success: function(response) {
                if (response.record) {
                    $('#condition').val(response.record.condition || '');
                    $('#remarks').val(response.record.remarks || '');
                } else {
                    $('#condition').val('');
                    $('#remarks').val('');
                }
                openModal('teethRecordModal');
            },
            error: function() {
                $('#condition').val('');
                $('#remarks').val('');
                openModal('teethRecordModal');
            }
        });
    });

    form.on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            tooth_number: $('#tooth_number').val(),
            condition: $('#condition').val(),
            remarks: $('#remarks').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: `/patients/${patientId}/teeth-records`,
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                closeModal('teethRecordModal');
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to save record.'
                });
            }
        });
    });
});
