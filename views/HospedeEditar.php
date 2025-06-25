<?php
ob_start();

// O controller já nos passa as preferências do hóspede na variável $preferencias
$hospedePreferencias = $_POST['preferencias'] ?? array_column($preferencias, 'descricao');

?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Editar Hóspede: <?php echo htmlspecialchars($guest['nome']); ?></h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-3">
                    <form action="/RoomFlow/Hospedes/Update/<?php echo $guest['id']; ?>" method="post" enctype="multipart/form-data" role="form">

                        <h6 class="text-dark text-sm mt-4">Dados Pessoais</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? $guest['nome']); ?>" required>
                                </div>
                                <?php if (!empty($errors['nome'])): ?><div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['nome']; ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-static my-3">
                                    <label>Data de Nascimento</label>
                                    <input type="date" class="form-control" name="dataNasc" value="<?php echo htmlspecialchars($_POST['dataNasc'] ?? $guest['data_nascimento']); ?>" required>
                                </div>
                                <?php if (!empty($errors['dataNasc'])): ?><div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['dataNasc']; ?></div><?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $guest['email']); ?>" required>
                                </div>
                                <?php if (!empty($errors['email'])): ?><div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['email']; ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">CPF</label>
                                    <input type="text" class="form-control" name="cpf" id="cpf-input" value="<?php echo htmlspecialchars($_POST['cpf'] ?? $guest['documento']); ?>" required>
                                </div>
                                <?php if (!empty($errors['cpf'])): ?><div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['cpf']; ?></div><?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" name="telefone" id="telefone-input" value="<?php echo htmlspecialchars($_POST['telefone'] ?? $guest['telefone']); ?>" required>
                                </div>
                                <?php if (!empty($errors['telefone'])): ?><div class="text-danger text-xs mt-n2 ms-1"><?php echo $errors['telefone']; ?></div><?php endif; ?>
                            </div>
                        </div>

                        <hr class="horizontal dark my-4">

                        <h6 class="text-dark text-sm">Endereço</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">CEP</label>
                                    <input type="text" class="form-control" name="cep" id="cep-input" value="<?php echo htmlspecialchars($_POST['cep'] ?? $guest['cep']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Rua / Logradouro</label>
                                    <input type="text" class="form-control" name="rua" id="rua-input" value="<?php echo htmlspecialchars($_POST['rua'] ?? $guest['rua']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($_POST['numero'] ?? $guest['numero']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade-input" value="<?php echo htmlspecialchars($_POST['cidade'] ?? $guest['cidade']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" name="estado" id="estado-input" value="<?php echo htmlspecialchars($_POST['estado'] ?? $guest['estado']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark my-4">

                        <div class="row">
                            <div class="col-md-5">
                                <h6 class="text-dark text-sm">Foto do Hóspede</h6>
                                <p class="text-xs text-secondary mb-2">Selecione uma nova imagem apenas se desejar alterá-la.</p>
                                <div class="d-flex align-items-center">
                                    <img id="image-preview" src="/RoomFlow/Public/uploads/hospedes/<?php echo htmlspecialchars($guest['imagem'] ?? 'default.png'); ?>" alt="preview" class="avatar avatar-xxl me-3 shadow-sm border-radius-lg" style="object-fit: cover;">
                                    <div>
                                        <label for="image-upload" class="btn btn-sm btn-outline-dark mb-0">Escolher Imagem</label>
                                        <input type="file" name="imagem" id="image-upload" class="d-none">
                                        <p id="file-name" class="text-xs text-secondary mt-1 mb-0">Nenhum arquivo novo selecionado.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h6 class="text-dark text-sm">Preferências / Observações</h6>
                                <p class="text-xs text-secondary mb-3">Adicione ou remova preferências do hóspede.</p>
                                <div id="preferences-container">
                                    <?php if (!empty($hospedePreferencias)): ?>
                                        <?php foreach ($hospedePreferencias as $pref_text): ?>
                                            <div class="row align-items-center preference-item mb-2">
                                                <div class="col-10">
                                                    <div class="input-group input-group-outline is-filled">
                                                        <label class="form-label">Preferência</label>
                                                        <input type="text" class="form-control" name="preferencias[]" value="<?php echo htmlspecialchars($pref_text); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button type="button" class="btn btn-icon-only btn-link text-danger remove-pref-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover">
                                                        <i class="material-symbols-rounded">delete</i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button type="button" id="add-preference-btn" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="material-symbols-rounded align-middle">add</i>
                                    Adicionar Preferência
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" onclick="confirmDelete(<?php echo $guest['id']; ?>, '<?php echo htmlspecialchars(addslashes($guest['nome'])); ?>')" class="btn btn-danger">Excluir Hóspede</button>
                            </div>
                            <div>
                                <a href="/RoomFlow/Hospedes" class="btn btn-outline-dark me-2">Cancelar</a>
                                <button type="submit" class="btn bg-gradient-dark">Salvar Alterações</button>
                            </div>
                        </div>

                    </form>
                    <form action="/RoomFlow/Hospedes/Deletar" method="POST" id="form-delete-<?php echo $guest['id']; ?>" class="d-none">
                        <input type="hidden" name="id" value="<?php echo $guest['id']; ?>">
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
    // Função de delete com SweetAlert
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Tem certeza?',
            html: `Você está prestes a excluir o hóspede <strong>${name}</strong>.<br>Esta ação não pode ser desfeita!`,
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

        // 1. MÁSCARAS DE CAMPO
        IMask(document.getElementById('cpf-input'), {
            mask: '000.000.000-00'
        });
        IMask(document.getElementById('cep-input'), {
            mask: '00000-000'
        });
        IMask(document.getElementById('telefone-input'), {
            mask: '(00) 00000-0000'
        });

        // 2. PREENCHIMENTO AUTOMÁTICO VIA CEP
        const cepInput = document.getElementById('cep-input');
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('rua-input').value = data.logradouro;
                            document.getElementById('cidade-input').value = data.localidade;
                            document.getElementById('estado-input').value = data.uf;
                            ['rua-input', 'cidade-input', 'estado-input'].forEach(id => {
                                const el = document.getElementById(id);
                                if (el && el.value) el.parentElement.classList.add('is-filled');
                            });
                        }
                    })
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });

        // 3. PRÉ-VISUALIZAÇÃO DA IMAGEM
        const imageUpload = document.getElementById('image-upload');
        const imagePreview = document.getElementById('image-preview');
        const fileName = document.getElementById('file-name');

        imageUpload.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            }
        });

        // 4. GERENCIADOR DE PREFERÊNCIAS
        const addPrefBtn = document.getElementById('add-preference-btn');
        const preferencesContainer = document.getElementById('preferences-container');

        addPrefBtn.addEventListener('click', function() {
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
            newPreferenceItem.querySelector('input').focus();
        });

        preferencesContainer.addEventListener('click', function(event) {
            const removeButton = event.target.closest('.remove-pref-btn');
            if (removeButton) {
                removeButton.closest('.preference-item').remove();
            }
        });
    });
</script>