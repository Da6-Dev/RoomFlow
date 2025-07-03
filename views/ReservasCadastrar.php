<?php
ob_start();

// Garante que as variáveis sempre existam para evitar erros
$hospedes = $hospedes ?? [];
$acomodacoes = $acomodacoes ?? [];
$datasReservadas = $datasReservadas ?? []; // Estrutura: [id_acomodacao => [datas], id_acomodacao_2 => [datas]]
$errors = $errors ?? [];

// Lógica para exibir alertas gerais
$alertClass = '';
$alertMessage = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'success_create') {
    $alertClass = 'alert-success';
    $alertMessage = 'Reserva cadastrada com sucesso!';
} elseif (!empty($errors['general'])) {
    $alertClass = 'alert-danger';
    $alertMessage = $errors['general'];
} elseif (!empty($errors['exists'])) {
    $alertClass = 'alert-danger';
    $alertMessage = $errors['exists'];
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container-fluid py-4">
    <input type="hidden" id="datasReservadas" value="<?php echo htmlspecialchars(json_encode($datasReservadas)); ?>">

    <div class="row">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Cadastro de Nova Reserva</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-3">

                    <?php if ($alertMessage): ?>
                        <div class="alert <?php echo $alertClass; ?> text-white alert-dismissible fade show" role="alert">
                            <span class="alert-text"><strong><?php echo $alertClass === 'alert-success' ? 'Sucesso!' : 'Erro!'; ?></strong> <?php echo htmlspecialchars($alertMessage); ?></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <form action="/RoomFlow/Dashboard/Reservas/Cadastrar" method="post" role="form">
                        <input type="hidden" name="valor_total" id="valor_total_hidden" value="0">

                        <h6 class="text-dark text-sm mt-4 d-flex align-items-center">
                            <i class="material-symbols-rounded opacity-5 me-2">room_preferences</i>
                            Seleção Principal
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Hóspede</label>
                                    <select class="form-control" name="hospede" id="hospede" required>
                                        <option value="" disabled selected>Selecione um hóspede</option>
                                        <?php if (!empty($hospedes)): foreach ($hospedes as $hospede): ?>
                                                <option value="<?php echo $hospede['id']; ?>"><?php echo htmlspecialchars($hospede['nome']); ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Acomodação</label>
                                    <select class="form-control" name="acomodacao" id="acomodacao" required>
                                        <option value="" data-preco="0" disabled selected>Selecione uma acomodação</option>
                                        <?php if (!empty($acomodacoes)): foreach ($acomodacoes as $acomodacao): ?>
                                                <option
                                                    value="<?php echo $acomodacao['id']; ?>"
                                                    data-preco="<?php echo htmlspecialchars($acomodacao['preco']); ?>">
                                                    <?php echo htmlspecialchars($acomodacao['tipo'] . ' - Nº ' . $acomodacao['numero']); ?>
                                                </option>
                                        <?php endforeach;
                                        endif; ?>
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
                                <div class="input-group input-group-static my-3">
                                    <label>Data do Check-in</label>
                                    <input type="text" id="data_checkin" name="checkin" class="form-control" placeholder="Selecione uma acomodação primeiro" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Data do Check-out</label>
                                    <input type="text" id="data_checkout" class="form-control" name="checkout" placeholder="Selecione uma acomodação primeiro" required>
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
                                <div class="input-group input-group-static my-3"><label>Status da Reserva</label><select class="form-control" name="status" id="status" required>
                                        <option value="confirmada">Confirmada</option>
                                        <option value="pendente" selected>Pendente</option>
                                    </select></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3"><label>Método de Pagamento</label><select class="form-control" name="metodo_pagamento" id="metodo_pagamento" required>
                                        <option value="cartao-credito">Cartão de Crédito</option>
                                        <option value="cartao-debito">Cartão de Débito</option>
                                        <option value="dinheiro">Dinheiro</option>
                                        <option value="pix" selected>Pix</option>
                                    </select></div>
                            </div>
                        </div>

                        <h6 class="text-dark text-sm mt-4 d-flex align-items-center">
                            <i class="material-symbols-rounded opacity-5 me-2">edit_note</i>
                            Observações
                        </h6>
                        <div class="input-group input-group-outline my-3"><label class="form-label">Observações (opcional)</label><textarea class="form-control" name="observacoes" rows="4"></textarea></div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="/RoomFlow/Dashboard/Reservas" class="btn btn-outline-dark me-2">Cancelar</a>
                            <button type="submit" class="btn bg-gradient-dark">Cadastrar Reserva</button>
                        </div>
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
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-2">Preço por Noite<span class="text-bold" id="summary-preco-noite">R$ 0,00</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-2">Noites<span class="text-bold" id="summary-noites">0</span></li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pt-3">
                            <h6 class="mb-0">Total</h6>
                            <h6 class="mb-0" id="summary-total">R$ 0,00</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>

