<?php
ob_start();

// Supondo que o Controller agora envia a lista de todas as comodidades disponíveis
// Ex: $this->LoadView('HospedeCadastrar', ['errors' => $errors, 'comodidades' => $allAmenities]);
$allAmenities = $comodidades ?? []; // Garante que a variável exista
$hospedePreferencias = $_POST['preferencias'] ?? []; // Pega as preferências já marcadas em caso de erro

?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Cadastro de Novo Hóspede</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-3">
                    <form action="/RoomFlow/Hospedes/Cadastrar" method="post" enctype="multipart/form-data" role="form">

                        <h6 class="text-dark text-sm mt-4">Dados Pessoais</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" name="nome"
                                        value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['nome'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['nome']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-static my-3">
                                    <label>Data de Nascimento</label>
                                    <input type="date" class="form-control" name="dataNasc"
                                        value="<?php echo htmlspecialchars($_POST['dataNasc'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['dataNasc'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['dataNasc']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['email'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['email']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">CPF</label>
                                    <input type="text" class="form-control" name="cpf" id="cpf-input"
                                        value="<?php echo htmlspecialchars($_POST['cpf'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['cpf'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['cpf']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" name="telefone" id="telefone-input"
                                        value="<?php echo htmlspecialchars($_POST['telefone'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['telefone'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['telefone']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="horizontal dark my-4">

                        <h6 class="text-dark text-sm">Endereço</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" name="cep" id="cep-input"
                                        value="<?php echo htmlspecialchars($_POST['cep'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['cep'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['cep']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Rua / Logradouro</label>
                                    <input type="text" class="form-control" name="rua" id="rua-input"
                                        value="<?php echo htmlspecialchars($_POST['rua'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['rua'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['rua']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" name="numero" id="numero-input"
                                        value="<?php echo htmlspecialchars($_POST['numero'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['numero'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['numero']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade-input"
                                        value="<?php echo htmlspecialchars($_POST['cidade'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['cidade'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['cidade']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" name="estado" id="estado-input"
                                        value="<?php echo htmlspecialchars($_POST['estado'] ?? ''); ?>" required>
                                </div>
                                <?php if (!empty($errors['estado'])): ?>
                                    <div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['estado']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="horizontal dark my-4">

                        <div class="row">
                            <div class="col-md-5">
                                <h6 class="text-dark text-sm">Foto do Hóspede (Opcional)</h6>
                                <div class="d-flex align-items-center">
                                    <img id="image-preview" src="/RoomFlow/public/assets/img/placeholder.jpg"
                                        alt="preview" class="avatar avatar-xxl me-3 shadow-sm border-radius-lg"
                                        style="object-fit: cover;">
                                    <div>
                                        <label for="image-upload" class="btn btn-sm btn-outline-dark mb-0">Escolher
                                            Imagem</label>
                                        <input type="file" name="imagem" id="image-upload" class="d-none">
                                        <p id="file-name" class="text-xs text-secondary mt-1 mb-0">Nenhum arquivo
                                            selecionado.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h6 class="text-dark text-sm">Preferências / Observações</h6>
                                <p class="text-xs text-secondary mb-3">Ex: Intolerância a lactose, andar baixo, etc.</p>

                                <div id="preferences-container">
                                    <?php
                                    // Se houver erros de validação e o formulário for reenviado,
                                    // este loop recria os campos de preferência que o usuário já havia preenchido.
                                    $posted_preferences = $_POST['preferencias'] ?? [];
                                    if (!empty($posted_preferences)):
                                        foreach ($posted_preferences as $index => $pref_text):
                                            if (!empty($pref_text)): // Só recria se não estiver vazio
                                                ?>
                                                <div class="row align-items-center preference-item mb-2">
                                                    <div class="col-10">
                                                        <div class="input-group input-group-outline is-filled">
                                                            <label class="form-label">Preferência</label>
                                                            <input type="text" class="form-control" name="preferencias[]"
                                                                value="<?php echo htmlspecialchars($pref_text); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button"
                                                            class="btn btn-icon-only btn-link text-danger remove-pref-btn"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Remover">
                                                            <i class="material-symbols-rounded">delete</i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                </div>

                                <button type="button" id="add-preference-btn"
                                    class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="material-symbols-rounded align-middle">add</i>
                                    Adicionar Preferência
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="/RoomFlow/Hospedes" class="btn btn-outline-dark me-2">Cancelar</a>
                            <button type="submit" class="btn bg-gradient-dark">Cadastrar Hóspede</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ... (as lógicas de máscara e CEP continuam as mesmas) ...
        IMask(document.getElementById('cpf-input'), { mask: '000.000.000-00' });
        IMask(document.getElementById('cep-input'), { mask: '00000-000' });
        IMask(document.getElementById('telefone-input'), { mask: '(00) 00000-0000' });
        // ... (a lógica do ViaCEP continua a mesma) ...

        // ==========================================================
        // NOVA LÓGICA PARA GERENCIAR PREFERÊNCIAS
        // ==========================================================
        const addPrefBtn = document.getElementById('add-preference-btn');
        const preferencesContainer = document.getElementById('preferences-container');

        // Função para adicionar um novo campo de preferência
        addPrefBtn.addEventListener('click', function () {
            const newPreferenceItem = document.createElement('div');
            newPreferenceItem.classList.add('row', 'align-items-center', 'preference-item', 'mb-2');

            newPreferenceItem.innerHTML = `
            <div class="col-10">
                <div class="input-group input-group-outline is-filled my-3">
                    <label class="form-label">Preferência</label>
                    <input type="text" class="form-control" name="preferencias[]">
                </div>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-icon-only btn-link text-danger remove-pref-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover">
                    <i class="material-symbols-rounded">delete</i>
                </button>
            </div>
        `;

            preferencesContainer.appendChild(newPreferenceItem);
            // Foca no novo campo criado
            newPreferenceItem.querySelector('input').focus();
        });

        // Função para remover um campo de preferência (usando delegação de evento)
        preferencesContainer.addEventListener('click', function (event) {
            // Procura pelo botão de remover mais próximo do elemento que foi clicado
            const removeButton = event.target.closest('.remove-pref-btn');
            if (removeButton) {
                // Remove o elemento pai do botão, que é a linha inteira da preferência
                removeButton.closest('.preference-item').remove();
            }
        });


        // 3. PRÉ-VISUALIZAÇÃO DA IMAGEM
        const imageUpload = document.getElementById('image-upload');
        const imagePreview = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');

        imageUpload.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            }
        });
    });
</script>