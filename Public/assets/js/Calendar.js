$datas_reservadas = JSON.parse(
    document.getElementById("datasReservadas").value
);

document.getElementById("acomodacao").addEventListener("change", function () {
    atualizarValorAcomodacao();
    document.getElementById("data_checkin").value = '';
    document.getElementById("data_checkout").value = '';
});

function atualizarValorAcomodacao() {
    $id_acomodacao = document.getElementById("acomodacao").value;
    if ($datas_reservadas[$id_acomodacao]) {
        flatpickr("#data_checkin", {
            disable: $datas_reservadas[$id_acomodacao],
            minDate: "today",
            dateFormat: "Y-m-d",
        });
        flatpickr("#data_checkout", {
            disable: $datas_reservadas[$id_acomodacao],
            minDate: "today",
            dateFormat: "Y-m-d",
        });
    } else {
        flatpickr("#data_checkin", {
            disable: [],
            minDate: "today",
            dateFormat: "Y-m-d",
        });
        flatpickr("#data_checkout", {
            disable: [],
            minDate: "today",
            dateFormat: "Y-m-d",
        });
    }
}

flatpickr("#data_checkin", {
    enable: [],
    minDate: "today",
    dateFormat: "Y-m-d",
});

flatpickr("#data_checkout", {
    enable: [],
    minDate: "today",
    dateFormat: "Y-m-d",
});


