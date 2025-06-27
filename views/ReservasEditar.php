<?php
ob_start();

// Garante que as variáveis sempre existam para evitar erros
$reserva = $reserva ?? [];
$hospedes = $hospedes ?? [];
$acomodacoes = $acomodacoes ?? [];
$datasReservadas = $datasReservadas ?? [];
$errors = $errors ?? [];

// --- LÓGICA PARA O RESUMO INICIAL ---
$precoPorNoiteResumo = 0;
$noitesResumo = 0;
$valorTotalResumo = $reserva['valor_total'] ?? 0;

if (!empty($reserva['id_acomodacao']) && !empty($acomodacoes)) {
    foreach ($acomodacoes as $ac) {
        if ($ac['id'] == $reserva['id_acomodacao']) {
            $precoPorNoiteResumo = $ac['preco'];
            break;
        }
    }
}
if (!empty($reserva['data_checkin']) && !empty($reserva['data_checkout'])) {
    try {
        $checkin = new DateTime($reserva['data_checkin']);
        $checkout = new DateTime($reserva['data_checkout']);
        $intervalo = $checkout->diff($checkin);
        $noitesResumo = $intervalo->days;
    } catch (Exception $e) {
        $noitesResumo = 0;
    }
}
// --- FIM DA LÓGICA PARA O RESUMO INICIAL ---

$alertClass = '';
$alertMessage = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'success_update') {
    $alertClass = 'alert-success';
    $alertMessage = 'Reserva atualizada com sucesso!';
} elseif (!empty($errors['general'])) {
    $alertClass = 'alert-danger';
    $alertMessage = $errors['general'];
}

$currentAcomodacaoId = $reserva['id_acomodacao'] ?? null;
if ($currentAcomodacaoId && isset($datasReservadas[$currentAcomodacaoId])) {
    $checkinAtual = new DateTime($reserva['data_checkin']);
    $checkoutAtual = new DateTime($reserva['data_checkout']);
    $intervalo = new DateInterval('P1D');
    $periodoAtual = new DatePeriod($checkinAtual, $intervalo, $checkoutAtual);

    $datasDaReservaAtual = [];
    foreach ($periodoAtual as $data) {
        $datasDaReservaAtual[] = $data->format('Y-m-d');
    }

    $datasReservadas[$currentAcomodacaoId] = array_values(array_diff(
        $datasReservadas[$currentAcomodacaoId],
        $datasDaReservaAtual
    ));
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid py-4">
    <input type="hidden" id="datasReservadas" value="<?php echo htmlspecialchars(json_encode($datasReservadas)); ?>">
    <input type="hidden" id="reservaId" value="<?php echo htmlspecialchars($reserva['id']); ?>">

    <div class="row">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Editar Reserva</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-3">

                    <?php if ($alertMessage) : ?>
                        <div class="alert <?php echo $alertClass; ?> text-white alert-dismissible fade show" role="alert">
                            <span class="alert-text"><strong><?php echo $alertClass === 'alert-success' ? 'Sucesso!' : 'Erro!'; ?></strong> <?php echo htmlspecialchars($alertMessage); ?></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <form action="/RoomFlow/Reservas/Update/<?php echo $reserva['id']; ?>" method="post" role="form" id="form-edit-reserva">
                        <input type="hidden" name="valor_total" id="valor_total_hidden" value="<?php echo htmlspecialchars($reserva['valor_total'] ?? '0'); ?>">

                        <h6 class="text-dark text-sm mt-4 d-flex align-items-center">
                            <i class="material-symbols-rounded opacity-5 me-2">room_preferences</i>
                            Seleção Principal
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Hóspede</label>
                                    <select class="form-control" name="hospede" required>
                                        <option value="" disabled>Selecione um hóspede</option>
                                        <?php foreach ($hospedes as $hospede) : ?>
                                            <option value="<?php echo $hospede['id']; ?>" <?php echo (($reserva['id_hospede'] ?? '') == $hospede['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($hospede['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Acomodação</label>
                                    <select class="form-control" name="acomodacao" id="acomodacao" required>
                                        <option value="" data-preco="0" disabled>Selecione uma acomodação</option>
                                        <?php foreach ($acomodacoes as $acomodacao) : ?>
                                            <option value="<?php echo $acomodacao['id']; ?>" data-preco="<?php echo htmlspecialchars($acomodacao['preco']); ?>" <?php echo (($reserva['id_acomodacao'] ?? '') == $acomodacao['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($acomodacao['tipo'] . ' - Nº ' . $acomodacao['numero']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark my-4">
                        <h6 class="text-dark text-sm d-flex align-items-center">
                            <i class="material-symbols-rounded opacity-5 me-2">date_range</i>
                            Período da Estadia
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3 is-filled">
                                    <label>Data do Check-in</label>
                                    <input type="text" id="data_checkin" name="checkin" class="form-control" value="<?php echo htmlspecialchars($reserva['data_checkin'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3 is-filled">
                                    <label>Data do Check-out</label>
                                    <input type="text" id="data_checkout" class="form-control" name="checkout" value="<?php echo htmlspecialchars($reserva['data_checkout'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="horizontal dark my-4">
                        <h6 class="text-dark text-sm d-flex align-items-center">
                            <i class="material-symbols-rounded opacity-5 me-2">credit_card</i>
                            Detalhes da Reserva
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3"><label>Status</label><select class="form-control" name="status" required>
                                        <option value="confirmada" <?= (($reserva['status'] ?? '') === 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
                                        <option value="pendente" <?= (($reserva['status'] ?? '') === 'pendente') ? 'selected' : '' ?>>Pendente</option>
                                        <option value="cancelada" <?= (($reserva['status'] ?? '') === 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
                                    </select></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3"><label>Método de Pagamento</label><select class="form-control" name="metodo_pagamento" required>
                                        <option value="cartao-credito" <?= (($reserva['metodo_pagamento'] ?? '') === 'cartao-credito') ? 'selected' : '' ?>>Cartão de Crédito</option>
                                        <option value="cartao-debito" <?= (($reserva['metodo_pagamento'] ?? '') === 'cartao-debito') ? 'selected' : '' ?>>Cartão de Débito</option>
                                        <option value="dinheiro" <?= (($reserva['metodo_pagamento'] ?? '') === 'dinheiro') ? 'selected' : '' ?>>Dinheiro</option>
                                        <option value="pix" <?= (($reserva['metodo_pagamento'] ?? '') === 'pix') ? 'selected' : '' ?>>Pix</option>
                                    </select></div>
                            </div>
                        </div>
                        <h6 class="text-dark text-sm mt-4 d-flex align-items-center">
                            <i class="material-symbols-rounded opacity-5 me-2">edit_note</i>
                            Observações
                        </h6>
                        <div class="input-group input-group-outline my-3 is-filled">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" name="observacoes" rows="3"><?php echo htmlspecialchars($reserva['observacoes'] ?? ''); ?></textarea>
                        </div>
                    </form>

                    <form action="/RoomFlow/Reservas/Deletar" method="POST" id="form-delete-<?php echo $reserva['id']; ?>" class="d-none">
                        <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card position-sticky top-1">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-success shadow-success border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Resumo da Reserva</h6>
                    </div>
                </div>
                <div class="card-body pt-5">
                    <ul class="list-group list-group-flush" id="reservation-summary">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-2">Preço por Noite<span class="text-bold" id="summary-preco-noite">R$ <?php echo number_format($precoPorNoiteResumo, 2, ',', '.'); ?></span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-2">Noites<span class="text-bold" id="summary-noites"><?php echo $noitesResumo; ?></span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pt-3">
                            <h6 class="mb-0">Total</h6>
                            <h6 class="mb-0" id="summary-total">R$ <?php echo number_format($valorTotalResumo, 2, ',', '.'); ?></h6>
                        </li>
                    </ul>
                    <hr class="horizontal dark my-4">
                    <div class="d-flex flex-column">
                        <button type="submit" form="form-edit-reserva" class="btn bg-gradient-dark mb-2">Salvar Alterações</button>
                        <button type="button" onclick="confirmDelete(<?php echo $reserva['id']; ?>)" class="btn btn-danger mb-2">Excluir Reserva</button>
                        <a href="/RoomFlow/Reservas" class="btn btn-outline-dark">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/pt.js"></script>
<script>
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

    document.addEventListener('DOMContentLoaded', function() {
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
                onChange: function(selectedDates) {
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
                onChange: function() {
                    calcularResumo();
                },
            });
        }

        // --- 4. EVENTOS ---
        // Quando o usuário troca a acomodação...
        acomodacaoSelect.addEventListener('change', function() {
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
</script>