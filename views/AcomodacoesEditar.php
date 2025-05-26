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
    $alertMessage = "Acomodação cadastrada com sucesso!!";
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

<div class="container-fluid py-2 p-5">
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white" role="alert" id="alertMessage">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>

    <!-- Título do Formulário -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-0 h4 font-weight-bolder">Cadastro de Acomodações</h3>
            <p class="mb-4">Preencha os dados abaixo para cadastrar uma nova acomodação.</p>
        </div>
    </div>

    <form action="/RoomFlow/Acomodacoes/Update/<?php echo $acomodacao['id'] ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">tipo</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 is-filled">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" name="tipo" value="<?php echo $_POST['tipo'] ?? $acomodacao['tipo']; ?>" required>
                        </div>
                        <?php if (!empty($errors['tipo'])): ?>
                            <div class="text-danger small"><?php echo $errors['tipo']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">numero</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 is-filled">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="numero" value="<?php echo $_POST['numero'] ?? $acomodacao['numero']; ?>" required>
                        </div>
                        <?php if (!empty($errors['numero'])): ?>
                            <div class="text-danger small"><?php echo $errors['numero']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">numero</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 is-filled">
                            <label class="form-label">Descrição</label>
                            <input type="text" class="form-control" name="descricao" value="<?php echo $_POST['descricao'] ?? $acomodacao['descricao']; ?>" required>
                        </div>
                        <?php if (!empty($errors['descricao'])): ?>
                            <div class="text-danger small"><?php echo $errors['descricao']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Status</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static my-3 is-filled">
                            <select class="form-control" name="status" id="status" required>
                                <option value="disponivel" <?php echo isset($_POST['status']) && $_POST['status'] || $acomodacao['status'] == "disponivel" ? "selected" : ""; ?>>Disponível</option>
                                <option value="ocupado" <?php echo isset($_POST['status']) && $_POST['status'] || $acomodacao['status'] == "ocupado" ? "selected" : ""; ?>>Ocupado</option>
                                <option value="manutencao" <?php echo isset($_POST['status']) && $_POST['status'] || $acomodacao['status'] == "manutencao" ? "selected" : ""; ?>>Manutenção</option>
                            </select>
                        </div>
                    </div>
                    <?php if (!empty($errors['status'])): ?>
                        <div class="text-danger small"><?php echo $errors['status']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Capacidade</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 is-filled">
                            <label class="form-label">Capacidade</label>
                            <input type="number" class="form-control" name="capacidade" value="<?php echo $_POST['capacidade'] ?? $acomodacao['capacidade']; ?>" required>
                        </div>
                        <?php if (!empty($errors['capacidade'])): ?>
                            <div class="text-danger small"><?php echo $errors['capacidade']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Preço</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 is-filled">
                            <label class="form-label">Preço</label>
                            <input type="text" class="form-control" name="preco" value="<?php echo $_POST['preco'] ?? $acomodacao['preco']; ?>" required>
                        </div>
                        <?php if (!empty($errors['preco'])): ?>
                            <div class="text-danger small"><?php echo $errors['preco']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Amenidades -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Amenidades</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="row">
                            <?php
                            foreach ($amenidades as $index => $amenity): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="amenidades[]"
                                            value="<?php echo $amenity['id']; ?>"
                                            id="amenidade_<?php echo $amenity['id']; ?>"
                                            <?php echo (isset($_POST['amenidades']) && in_array($amenity['id'], $_POST['amenidades']))
                                                || in_array($amenity['id'], $amenidades_acomodacao) ? 'checked' : ''; ?>
                                            <label class="form-check-label text-dark" for="amenidade_<?php echo $amenity['id']; ?>">
                                        <?php echo $amenity['nome']; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php if (($index + 1) % 3 == 0): ?>
                        </div>
                        <div class="row">
                        <?php endif; ?>
                    <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mínimo de noites / Camas -->
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Mínimo de Noites</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static <?php echo !empty($errors['minimo_noites']) || !empty($_POST['minimo_noites']) ? 'is-filled' : ''; ?>">
                            <label class="ms-0">Mínimo de Noites</label>
                            <input type="number" class="form-control" name="minimo_noites" value="<?php echo $_POST['minimo_noites'] ?? $acomodacao['minimo_noites']; ?>" required>
                        </div>
                    </div>
                    <?php if (!empty($errors['minimo_noites'])): ?>
                        <div class="text-danger small"><?php echo $errors['minimo_noites']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Camas de Casal</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static <?php echo !empty($errors['camas_casal']) || !empty($_POST['camas_casal']) ? 'is-filled' : ''; ?>">
                            <label class="ms-0">Camas de Casal</label>
                            <input type="number" class="form-control" name="camas_casal" value="<?php echo $_POST['camas_casal'] ?? $acomodacao['camas_casal']; ?>" required>
                        </div>
                        <?php if (!empty($errors['camas_casal'])): ?>
                            <div class="text-danger small"><?php echo $errors['camas_casal']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Camas de Solteiro</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static <?php echo !empty($errors['camas_solteiro']) || !empty($_POST['camas_solteiro']) ? 'is-filled' : ''; ?>">
                            <label class="ms-0">Camas de Solteiro</label>
                            <input type="number" class="form-control" name="camas_solteiro" value="<?php echo $_POST['camas_solteiro'] ?? $acomodacao['camas_solteiro']; ?>" required>
                        </div>
                        <?php if (!empty($errors['camas_solteiro'])): ?>
                            <div class="text-danger small"><?php echo $errors['camas_solteiro']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Check-in / Check-out -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Hora de Check-in</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static <?php echo !empty($errors['check_in_time']) || !empty($_POST['check_in_time']) ? 'is-filled' : ''; ?>">
                            <label class="ms-0">Hora de Check-in</label>
                            <input type="time" class="form-control" name="check_in_time" id="check_in_time" value="<?php echo $_POST['check_in_time'] ?? $acomodacao['hora_checkin']; ?>" required>
                        </div>
                        <?php if (!empty($errors['check_in_time'])): ?>
                            <div class="text-danger small"><?php echo $errors['check_in_time']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Hora de Check-out</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static <?php echo !empty($errors['check_out_time']) || !empty($_POST['check_out_time']) ? 'is-filled' : ''; ?>">
                            <label class="ms-0">Hora de Check-out</label>
                            <input type="time" class="form-control" name="check_out_time" id="check_out_time" value="<?php echo $_POST['check_out_time'] ?? $acomodacao['hora_checkout']; ?>" required>
                        </div>
                        <?php if (!empty($errors['check_out_time'])): ?>
                            <div class="text-danger small"><?php echo $errors['check_out_time']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Upload de Fotos</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static <?php echo !empty($errors['imagens[]']) || !empty($_POST['imagens[]']) ? 'is-filled' : ''; ?>">
                            <label class="ms-0">Upload de Fotos</label>
                            <input type="file" class="form-control" name="imagens[]" id="imagens[]" multiple accept="image/*">
                        </div>
                        <?php if (!empty($errors['imagens[]'])): ?>
                            <div class="text-danger small"><?php echo $errors['imagens[]']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>                 
        <div class="d-flex flex-wrap">
            <?php foreach ($imagens as $imagem): ?>
                <div class="p-2" style="width: 20%;">
                    <div class="card">
                        <img src="/Roomflow/<?php echo $imagem['caminho_arquivo'] ?>" class="card-img-top" alt="">
                        <div class="card-body">
                            <input type="checkbox" name="delete_imagens[]" value="<?php echo $imagem['id']; ?>" id="delete_image_<?php echo $imagem['id']; ?>">
                            <label for="delete_image_<?php echo $imagem['id']; ?>" class="form-check-label">Excluir Imagem</label>
                            <br>
                            <input type="radio" name="imagem_capa" value="<?php echo $imagem['id']; ?>" id="imagem_capa_<?php echo $imagem['id']; ?>" <?php if($imagem['capa_acomodacao'] == 1){echo 'checked';}; ?>>
                            <label for="imagem_capa">Definir como Capa</label>
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Botão Final -->
        <div class="row mt-3">
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    Atualizar
                </button>
            </div>
            <div class="col-md-1">
                <a href="/RoomFlow/Acomodacoes" class="btn btn-secondary btn-lg px-4">
                    Voltar
                </a>
            </div>
        </div>

    </form>
</div>

<?php

$content = ob_get_clean();
include __DIR__ . '/Layout.php'

?>