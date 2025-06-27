function openEditModal(id, name) {
    document.getElementById('edit-error-container').innerHTML = '';
    document.getElementById('editAmenityId').value = id;
    document.getElementById('editAmenityName').value = name;
    document.getElementById('editForm').action = '/RoomFlow/Comodidades/Update/' + id;

    var myModal = new bootstrap.Modal(document.getElementById('editAmenityModal'));
    myModal.show();

    document.getElementById('editAmenityName').focus();
}

function confirmDelete(id, name) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `Você está prestes a excluir a comodidade <strong>${name}</strong>.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-delete-' + id).submit();
        }
    });
}

