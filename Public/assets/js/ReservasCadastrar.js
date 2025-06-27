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

    let fpCheckin, fpCheckout; // Variáveis para guardar as instâncias

    // --- 2. FUNÇÕES ---
    const formatCurrency = (value) => new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);

    function calcularResumo() {
        const selectedOption = acomodacaoSelect.options[acomodacaoSelect.selectedIndex];
        if (!selectedOption) return;

        const precoPorNoite = parseFloat(selectedOption.getAttribute('data-preco')) || 0;
        summaryPrecoNoite.textContent = formatCurrency(precoPorNoite);

        // As instâncias fpCheckin e fpCheckout são necessárias aqui.
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

    // **NOVA ABORDAGEM: DESTRUIR E RECRIAR OS CALENDÁRIOS**
    function inicializarCalendarios(disabledDates = []) {
        // Se já existirem instâncias, destrua-as primeiro.
        if (fpCheckin) fpCheckin.destroy();
        if (fpCheckout) fpCheckout.destroy();

        const commonConfig = {
            locale: "pt",
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: disabledDates
        };

        fpCheckin = flatpickr(checkinInput, {
            ...commonConfig,
            onChange: (selectedDates) => {
                if (selectedDates.length > 0) {
                    fpCheckout.set('minDate', new Date(selectedDates[0]).fp_incr(1));
                }
                calcularResumo();
            }
        });

        fpCheckout = flatpickr(checkoutInput, {
            ...commonConfig,
            onChange: () => {
                calcularResumo();
            }
        });
    }

    function handleAcomodacaoChange() {
        const acomodacaoId = acomodacaoSelect.value;
        const disabledDates = allReservedDates[acomodacaoId] || [];

        // Recria os calendários com as novas datas desabilitadas
        inicializarCalendarios(disabledDates);

        // Calcula o resumo, que vai mostrar o preço por noite e zerar o resto.
        calcularResumo();
    }

    // --- 3. INICIALIZAÇÃO E EVENT LISTENERS ---

    // Inicia os calendários vazios e desabilitados na carga da página
    inicializarCalendarios([]);
    checkinInput.disabled = true;
    checkoutInput.disabled = true;

    // Quando o usuário finalmente escolher uma acomodação, ativamos os calendários
    acomodacaoSelect.addEventListener('change', () => {
        checkinInput.disabled = false;
        checkoutInput.disabled = false;
        handleAcomodacaoChange();
    });
});