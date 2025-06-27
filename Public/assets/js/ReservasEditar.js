// Função de delete com SweetAlert
function confirmDelete(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você está prestes a excluir esta reserva. Esta ação não pode ser desfeita!",
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

document.addEventListener('DOMContentLoaded', function () {
    // --- 1. ELEMENTOS E DADOS ---
    const allReservedDates = JSON.parse(document.getElementById("datasReservadas").value);
    const acomodacaoSelect = document.getElementById('acomodacao');
    const checkinInput = document.getElementById('data_checkin');
    const checkoutInput = document.getElementById('data_checkout');
    const valorTotalHidden = document.getElementById('valor_total_hidden');
    const summaryPrecoNoite = document.getElementById('summary-preco-noite');
    const summaryNoites = document.getElementById('summary-noites');
    const summaryTotal = document.getElementById('summary-total');

    let fpCheckin, fpCheckout;

    // --- 2. FUNÇÕES AUXILIARES ---
    const formatCurrency = (value) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);

    function calcularResumo() {
        const selectedOption = acomodacaoSelect.options[acomodacaoSelect.selectedIndex];
        if (!selectedOption) return;

        const precoPorNoite = parseFloat(selectedOption.getAttribute('data-preco')) || 0;
        summaryPrecoNoite.textContent = formatCurrency(precoPorNoite);

        if (!fpCheckin || !fpCheckout) return;
        const checkinDate = fpCheckin.selectedDates[0];
        const checkoutDate = fpCheckout.selectedDates[0];

        if (!checkinDate || !checkoutDate || checkoutDate <= checkinDate) {
            summaryNoites.textContent = '0';
            summaryTotal.textContent = formatCurrency(0);
            valorTotalHidden.value = '0.00';
            return;
        }

        const diffTime = Math.abs(checkoutDate - checkinDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const valorTotal = precoPorNoite * diffDays;

        summaryNoites.textContent = diffDays;
        summaryTotal.textContent = formatCurrency(valorTotal);
        valorTotalHidden.value = valorTotal.toFixed(2);
    }

    // --- 3. LÓGICA PRINCIPAL BASEADA NO SEU CÓDIGO ---
    function setupDatepickers() {
        // Pega o ID da acomodação atualmente selecionada
        const acomodacaoId = acomodacaoSelect.value;
        // Pega as datas desabilitadas para essa acomodação
        const disabledDates = allReservedDates[acomodacaoId] || [];

        // Destrói instâncias antigas para evitar conflitos
        if (fpCheckin) fpCheckin.destroy();
        if (fpCheckout) fpCheckout.destroy();

        // Configuração comum para ambos os calendários
        const commonConfig = {
            locale: "pt",
            dateFormat: "Y-m-d",
            disable: disabledDates
        };

        // Inicializa o calendário de CHECK-IN
        fpCheckin = flatpickr(checkinInput, {
            ...commonConfig,
            defaultDate: checkinInput.value || null,
            minDate: "today",
            onChange: function (selectedDates) {
                // Quando o check-in muda, atualiza a data mínima do check-out
                if (selectedDates.length > 0) {
                    const nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    fpCheckout.set('minDate', nextDay);
                }
                calcularResumo();
            },
        });

        // Inicializa o calendário de CHECK-OUT
        fpCheckout = flatpickr(checkoutInput, {
            ...commonConfig,
            defaultDate: checkoutInput.value || null,
            minDate: fpCheckin.selectedDates.length > 0 ? new Date(fpCheckin.selectedDates[0]).fp_incr(1) : new Date().fp_incr(1),
            onChange: function () {
                calcularResumo();
            },
        });
    }

    // --- 4. EVENTOS ---
    // Quando o usuário troca a acomodação...
    acomodacaoSelect.addEventListener('change', function () {
        // Limpa as datas para evitar confusão
        checkinInput.value = '';
        checkoutInput.value = '';
        // Reconfigura os calendários para a nova acomodação
        setupDatepickers();
        // Recalcula o resumo (que irá para 0, pois as datas foram limpas)
        calcularResumo();
    });

    // --- 5. INICIALIZAÇÃO ---
    // Apenas configura os calendários na primeira vez que a página carrega.
    // Nenhum cálculo é disparado aqui, mantendo os valores do PHP.
    setupDatepickers();
});