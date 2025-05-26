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
} elseif (isset($_GET['msg']) && $_GET['msg'] === 'success') {
    // Se houver uma mensagem de sucesso, mostrar a mensagem de sucesso
    $alertClass = 'alert-success';
    $alertMessage = "Cadastro de usuário realizado com sucesso!";
}

?>

<div class="container-fluid py-2">
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white" role="alert" id="alertMessage">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>

    <form action="/RoomFlow/Hospedes/Cadastrar" method="post">
        <!-- Título do Formulário -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="mb-0 h4 font-weight-bolder">Cadastro de Hóspedes</h3>
                <p class="mb-4">Preencha os dados abaixo para cadastrar um novo hóspede.</p>
            </div>
        </div>

        <!-- Seção de Nome e Email -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Nome</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['nome']) || !empty($_POST['nome']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" name="nome" value="<?php echo $_POST['nome'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['nome'])): ?>
                            <div class="text-danger small"><?php echo $errors['nome']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Email</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['email']) || !empty($_POST['email']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['email'])): ?>
                            <div class="text-danger small"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Telefone e CPF -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Telefone</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['telefone']) || !empty($_POST['telefone']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Telefone</label>
                            <input type="tel" class="form-control" name="telefone" id="telefone-input" value="<?php echo $_POST['telefone'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['telefone'])): ?>
                            <div class="text-danger small"><?php echo $errors['telefone']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">CPF</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['cpf']) || !empty($_POST['cpf']) ? 'is-filled' : ''; ?>">
                            <input type="text" class="form-control" id="cpf-input" name="cpf" value="<?php echo $_POST['cpf'] ?? ''; ?>" required placeholder="cpf">
                        </div>
                        <?php if (!empty($errors['cpf'])): ?>
                            <div class="text-danger small"><?php echo $errors['cpf']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Endereço -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">CEP</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['cep']) || !empty($_POST['cep']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep-input" name="cep" value="<?php echo $_POST['cep'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['cep'])): ?>
                            <div class="text-danger small"><?php echo $errors['cep']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Rua</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['rua']) || !empty($_POST['rua']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Rua</label>
                            <input type="text" class="form-control" id="rua-input" name="rua" value="<?php echo $_POST['rua'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['rua'])): ?>
                            <div class="text-danger small"><?php echo $errors['rua']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Estado, Número e CEP -->
        
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Estado</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['estado']) || !empty($_POST['estado']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Estado</label>
                            <input type="text" id="uf-input" class="form-control" name="estado" value="<?php echo $_POST['estado'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['estado'])): ?>
                            <div class="text-danger small"><?php echo $errors['estado']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Cidade</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['cidade']) || !empty($_POST['cidade']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Cidade</label>
                            <input type="text" id="cidade-input" class="form-control" name="cidade" value="<?php echo $_POST['cidade'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['cidade'])): ?>
                            <div class="text-danger small"><?php echo $errors['cidade']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Número</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($errors['numero']) || !empty($_POST['numero']) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Número</label>
                            <input type="number" class="form-control" name="numero" value="<?php echo $_POST['numero'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['numero'])): ?>
                            <div class="text-danger small"><?php echo $errors['numero']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Data de Nascimento -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Data de Nascimento</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="input-group input-group-static my-3 <?php echo !empty($errors['dataNasc']) || !empty($_POST['dataNasc']) ? 'is-filled' : ''; ?>">
                            <label>Data de Nascimento</label>
                            <input type="date" class="form-control" name="dataNasc" value="<?php echo $_POST['dataNasc'] ?? ''; ?>" required>
                        </div>
                        <?php if (!empty($errors['dataNasc'])): ?>
                            <div class="text-danger small"><?php echo $errors['dataNasc']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Preferências -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header p-2 ps-3 bg-gradient-dark">
                        <p class="text-sm mb-0 text-white text-capitalize">Preferências</p>
                    </div>
                    <div class="card-body p-2 ps-3">
                        <div class="d-flex align-items-center mb-3">
                            <span class="me-3">Adicionar preferência</span>
                            <button type="button" id="prefadd" class="btn btn-outline-primary btn-sm me-2">+</button>
                            <button type="button" id="prefless" class="btn btn-outline-primary btn-sm">-</button>
                        </div>
                        <div class="row" id="preferences">
                            <?php if (isset($preferencias) && $preferencias != []): ?>
                                <?php foreach ($preferencias as $index => $valor) : ?>
                                    <div class="col-md-12">
                                        <div class="input-group input-group-outline my-3 is-filled" id="pref<?php echo $index + 1; ?>">
                                            <label class="form-label">Preferência <?php echo $index + 1; ?></label>
                                            <input type="text" class="form-control" name="pref<?php echo $index + 1; ?>" value="<?php echo htmlspecialchars($valor); ?>" required>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botão de Cadastro -->
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn bg-gradient-dark text-white">Cadastrar</button>
            </div>
        </div>
    </form>
</div>

<?php

$content = ob_get_clean();
include __DIR__ . '/Layout.php';

?>