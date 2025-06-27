function confirmDelete(reservaId, reservaName) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `Você está prestes a excluir a <strong>${reservaName}</strong>.<br>Esta ação não pode ser desfeita!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-delete-' + reservaId).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializa o DataTable para a tabela de reservas
    const reservasTable = new simpleDatatables.DataTable("#reservasTable", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        labels: {
            placeholder: "Buscar reserva...",
            perPage: " reservas por página",
            noRows: "Nenhuma reserva encontrada",
            info: "Mostrando {start} a {end} de {rows} reservas"
        }
    });

    // Faz a mensagem de alerta desaparecer após 5 segundos
    const alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
        setTimeout(() => {
            new bootstrap.Alert(alertMessage).close();
        }, 5000);
    }
});