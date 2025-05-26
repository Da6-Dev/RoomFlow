<?php
ob_start();

// Mensagem de alerta geral
$alertClass = '';
$alertMessage = '';

// Checar se existe algum erro geral
if (!empty($errors['general'])) {
    // Se houver erro geral, mostrar a mensagem de erro
    $alertClass = 'alert-danger';
    $alertMessage = $errors['general'];
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'success_create') {
    // Se houver uma mensagem de sucesso, mostrar a mensagem de sucesso
    $alertClass = 'alert-success';
    $alertMessage = "Reserva cadastrada com sucesso!!";
} elseif (!empty($errors['exists'])) {
    //Se houver erro de acomodação já existente, mostrar a mensagem de erro
    $alertClass = 'alert-danger';
    $alertMessage = $errors['exists'];
} else {
    // Caso contrário, não mostrar nada
    $alertClass = '';
    $alertMessage = '';
}
?>
<input type="text" id="datasReservadas" name="datasReservadas" value="<?php echo htmlspecialchars(json_encode($datasReservadas)); ?>" hidden>
<div class="container-fluid py-2 p-5">
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white" role="alert" id="alertMessage">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>

    <!-- Título do Formulário -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-0 h4 font-weight-bolder">Editar Reserva</h3>
            <p class="mb-4">Preencha os dados abaixo para fazer atualização da reserva.</p>
        </div>
    </div>

    <form action="/RoomFlow/Reservas/Update/<?php echo $reserva['id'] ?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Hospede</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static my-3">
                            <select class="form-control" name="hospede" id="hospede" required>
                                <option value="" disabled selected>Selecione um hóspede</option>
                                <?php

                                // Preencher o select com os hóspedes disponíveis
                                if (!empty($hospedes)) {
                                    foreach ($hospedes as $hospede) {
                                        if ($reserva['id_hospede']== $hospede['id']) {
                                            $selected = 'selected';
                                        } else {
                                            $selected = '';
                                        }
                                        echo '<option value="' . $hospede['id'] . '" ' . (isset($_POST['hospede']) && $_POST['hospede'] == $hospede['id'] ? 'selected' : '') . $selected . '>' . $hospede['nome'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Nenhum hóspede disponível</option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <?php if (!empty($errors['hospede'])): ?>
                        <div class="text-danger small"><?php echo $errors['hospede']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Acomodação</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static my-3">
                            <select class="form-control" name="acomodacao" id="acomodacao" required>
                                <option value="" disabled selected>Selecione uma acomodação</option>
                                <?php
                                // Preencher o select com as acomodações disponíveis
                                if (!empty($acomodacoes)) {
                                    foreach ($acomodacoes as $acomodacao) {
                                        if ($reserva['id_acomodacao'] == $acomodacao['id']) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                        echo '<option value="' . $acomodacao['id'] . '" ' . (isset($_POST['acomodacao']) && $_POST['acomodacao'] == $acomodacao['id'] ? 'selected' : '') . $selected . '>' . $acomodacao['tipo'] . ' - ' . $acomodacao['numero'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Nenhuma acomodação disponível</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php if (!empty($errors['acomodacao'])): ?>
                        <div class="text-danger small"><?php echo $errors['acomodacao']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Data do Check-in</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['checkin']) || !empty($_POST['checkin']) ? 'is-filled' : ''; ?>">
                            <input type="text" id="data_checkin" name="checkin" class="form-control" value="<?php echo $_POST['checkin'] ?? $reserva['data_checkin']; ?>" placeholder="Data de Checkout" required>
                        </div>
                        <?php if (!empty($errors['checkin'])): ?>
                            <div class="text-danger small"><?php echo $errors['checkin']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Data do Check-out</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['checkout']) || !empty($_POST['checkout']) ? 'is-filled' : ''; ?>">
                            <input type="text" id="data_checkout" class="form-control" name="checkout" value="<?php echo $_POST['checkout'] ?? $reserva['data_checkout']; ?>" placeholder="Data de Check-out" required>
                        </div>
                        <?php if (!empty($errors['checkout'])): ?>
                            <div class="text-danger small"><?php echo $errors['checkout']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Status</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static my-3">
                            <select class="form-control" name="status" id="status" required>
                                <option value="" disabled selected>Selecione o status</option>
                                <option value="confirmada" <?= (($_POST['status'] ?? $reserva['status'] ?? '') === 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
                                <option value="pendente" <?= (($_POST['status'] ?? $reserva['status'] ?? '') === 'pendente') ? 'selected' : '' ?>>Pendente</option>
                            </select>
                        </div>
                    </div>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="text-danger small"><?php echo $errors['status']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Método de Pagamento</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static my-3">
                            <select class="form-control" name="metodo_pagamento" id="metodo_pagamento" required>
                                <option value="" disabled selected>Selecione o método de pagamento</option>
                                <option value="cartao-debito" <?= (($_POST['metodo_pagamento'] ?? $reserva['metodo_pagamento'] ?? '') === 'cartao-debito') ? 'selected' : '' ?>>Cartão de Débito</option>
                                <option value="cartao-credito" <?= (($_POST['metodo_pagamento'] ?? $reserva['metodo_pagamento'] ?? '') === 'cartao-credito') ? 'selected' : '' ?>>Cartão de Crédito</option>
                                <option value="dinheiro" <?= (($_POST['metodo_pagamento'] ?? $reserva['metodo_pagamento'] ?? '') === 'dinheiro') ? 'selected' : '' ?>>Dinheiro</option>
                                <option value="pix" <?= (($_POST['metodo_pagamento'] ?? $reserva['metodo_pagamento'] ?? '') === 'pix') ? 'selected' : '' ?>>Pix</option>
                            </select>
                        </div>
                    </div>
                    <?php if (!empty($errors['metodo_pagamento'])): ?>
                        <div class="text-danger small"><?php echo $errors['metodo_pagamento']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Observações</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['observacoes']) || !empty($_POST['observacoes']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Observações</label>
                            <input type="text" class="form-control" name="observacoes" value="<?= htmlspecialchars($_POST['observacoes'] ?? $reserva['observacoes'] ?? '') ?>">

                        </div>
                        <?php if (!empty($errors['observacoes'])): ?>
                            <div class="text-danger small"><?php echo $errors['observacoes']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- Botão Final -->
        <div class="row">
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    Atualizar
                </button>
            </div>
            <div class="col-md-2">
                <a href="/RoomFlow/Reservas" class="btn btn-secondary btn-lg px-4">
                    Voltar
                </a>
            </div>
        </div>

    </form>
</div>



<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>