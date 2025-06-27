function confirmDelete(guestId, guestName) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `Você está prestes a excluir o hóspede <strong>${guestName}</strong>.<br>Esta ação não pode ser desfeita!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-delete-' + guestId).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    new simpleDatatables.DataTable("#hospedesTable", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        labels: {
            placeholder: "Buscar hóspede...",
            perPage: "hóspedes por página",
            noRows: "Nenhum hóspede encontrado",
            info: "Mostrando {start} a {end} de {rows} hóspedes"
        }
    });

    const alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
        setTimeout(() => {
            new bootstrap.Alert(alertMessage).close();
        }, 5000);
    }
});