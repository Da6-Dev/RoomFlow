function confirmDelete(id, name) {
    Swal.fire({
        title: "Tem certeza?",
        html: `Você está prestes a excluir a acomodação <strong>${name}</strong>.<br>Esta ação não pode ser desfeita!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("form-delete-" + id).submit();
        }
    });
}

function filterAcomodacoes(status) {
    const cards = document.querySelectorAll(".acomodacao-card");
    cards.forEach((card) => {
        if (status === "all" || card.dataset.status === status) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const alertMessage = document.getElementById("alertMessage");
    if (alertMessage) {
        setTimeout(() => {
            new bootstrap.Alert(alertMessage).close();
        }, 5000);
    }
});
