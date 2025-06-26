<?php
ob_start();

// Garante que as variáveis sempre existam para evitar erros
$Amenities = $Amenities ?? [];
$data = $data ?? [];
$errors = $errors ?? [];

// Lógica para exibir alertas gerais (sucesso ou erro)
$alertClass = '';
$alertMessage = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'success_create') {
    $alertClass = 'alert-success';
    $alertMessage = 'Cadastro realizado com sucesso!';
} elseif (!empty($errors['general'])) {
    $alertClass = 'alert-danger';
    $alertMessage = $errors['general'];
} elseif (!empty($errors['exists'])) {
    $alertClass = 'alert-danger';
    $alertMessage = $errors['exists'];
}

?>

<style>
    /* Estilos para o Stepper (sem alterações) */
    .stepper-header {
        display: flex;
        justify-content: space-around;
        padding: 0;
        margin-bottom: 2rem;
        list-style-type: none;
    }

    .stepper-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex-grow: 1;
        position: relative;
    }

    .step-circle {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #8392AB;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .step-title {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #8392AB;
    }

    .stepper-step.active .step-circle {
        background: linear-gradient(195deg, #EC407A, #D81B60);
        color: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .stepper-step.active .step-title {
        color: #344767;
    }

    .stepper-step::after {
        content: '';
        position: absolute;
        top: 1.5rem;
        left: 50%;
        width: 100%;
        height: 2px;
        background-color: #e9ecef;
        z-index: 1;
    }

    .stepper-step:last-child::after {
        display: none;
    }

    .step-panel {
        display: none;
    }

    .step-panel.active {
        display: block;
        animation: fadeIn 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: .5rem;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }

    #drop-zone:hover,
    #drop-zone.is-dragover {
        border-color: #D81B60;
        background-color: #f8f9fa;
    }

    #drop-zone .drop-zone-text {
        color: #6c757d;
    }

    .text-danger {
        font-size: 0.8rem;
    }

    /* Estilo para erro */
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Cadastro de Nova Acomodação</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-3">

                    <?php if ($alertMessage): ?>
                        <div class="alert <?php echo $alertClass; ?> text-white alert-dismissible fade show" role="alert">
                            <span class="alert-text"><strong><?php echo $alertClass == 'alert-success' ? 'Sucesso!' : 'Erro!'; ?></strong> <?php echo $alertMessage; ?></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <ul class="stepper-header">
                        <li class="stepper-step active" data-step="1">
                            <div class="step-circle">1</div>
                            <div class="step-title">Informações</div>
                        </li>
                        <li class="stepper-step" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-title">Detalhes e Preço</div>
                        </li>
                        <li class="stepper-step" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-title">Amenidades</div>
                        </li>
                        <li class="stepper-step" data-step="4">
                            <div class="step-circle">4</div>
                            <div class="step-title">Fotos</div>
                        </li>
                    </ul>

                    <form action="/RoomFlow/Acomodacoes/Cadastrar" method="post" enctype="multipart/form-data" role="form" id="accommodation-form">
                        <div class="step-panel active" data-step="1">
                            <h6 class="text-dark text-sm mt-3">Informações Principais</h6>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['tipo']) ? 'is-filled' : ''; ?>">
                                        <label class="form-label">Tipo (ex: Suíte Master)</label>
                                        <input type="text" class="form-control" name="tipo" value="<?php echo htmlspecialchars($data['tipo'] ?? ''); ?>" required>
                                    </div>
                                    <?php if (!empty($errors['tipo'])): ?><div class="text-danger ps-2"><?php echo $errors['tipo']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['numero']) ? 'is-filled' : ''; ?>">
                                        <label class="form-label">Número do Quarto</label>
                                        <input type="number" class="form-control" name="numero" value="<?php echo htmlspecialchars($data['numero'] ?? ''); ?>" required>
                                    </div>
                                    <?php if (!empty($errors['numero'])): ?><div class="text-danger ps-2"><?php echo $errors['numero']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-static my-3">
                                        <label class="ms-0">Status Inicial</label>
                                        <select class="form-control" name="status" required>
                                            <option value="disponivel" <?php echo (isset($data['status']) && $data['status'] == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
                                            <option value="manutencao" <?php echo (isset($data['status']) && $data['status'] == 'manutencao') ? 'selected' : ''; ?>>Manutenção</option>
                                            <option value="ocupado" <?php echo (isset($data['status']) && $data['status'] == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                                        </select>
                                    </div>
                                    <?php if (!empty($errors['status'])): ?><div class="text-danger ps-2"><?php echo $errors['status']; ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="input-group input-group-outline my-3 <?php echo !empty($data['descricao']) ? 'is-filled' : ''; ?>">
                                <label class="form-label">Descrição da Acomodação</label>
                                <textarea class="form-control" name="descricao" rows="5" required><?php echo htmlspecialchars($data['descricao'] ?? ''); ?></textarea>
                            </div>
                            <?php if (!empty($errors['descricao'])): ?><div class="text-danger ps-2"><?php echo $errors['descricao']; ?></div><?php endif; ?>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn bg-gradient-dark ms-auto next-step-btn">Próximo &rarr;</button>
                            </div>
                        </div>

                        <div class="step-panel" data-step="2">
                            <h6 class="text-dark text-sm mt-3">Detalhes e Preços</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['capacidade']) ? 'is-filled' : ''; ?>"><label class="form-label">Capacidade (Pessoas)</label><input type="number" class="form-control" name="capacidade" value="<?php echo htmlspecialchars($data['capacidade'] ?? ''); ?>" required></div>
                                    <?php if (!empty($errors['capacidade'])): ?><div class="text-danger ps-2"><?php echo $errors['capacidade']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['preco']) ? 'is-filled' : ''; ?>"><label class="form-label">Preço (R$)</label><input type="text" class="form-control" name="preco" id="preco-input" value="<?php echo htmlspecialchars($data['preco'] ?? ''); ?>" required></div>
                                    <?php if (!empty($errors['preco'])): ?><div class="text-danger ps-2"><?php echo $errors['preco']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['minimo_noites']) ? 'is-filled' : ''; ?>"><label class="form-label">Mínimo de Noites</label><input type="number" class="form-control" name="minimo_noites" value="<?php echo htmlspecialchars($data['minimo_noites'] ?? '1'); ?>" required></div>
                                    <?php if (!empty($errors['minimo_noites'])): ?><div class="text-danger ps-2"><?php echo $errors['minimo_noites']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['camas_casal']) ? 'is-filled' : ''; ?>"><label class="form-label">Camas de Casal</label><input type="number" class="form-control" name="camas_casal" value="<?php echo htmlspecialchars($data['camas_casal'] ?? '0'); ?>" required></div>
                                    <?php if (!empty($errors['camas_casal'])): ?><div class="text-danger ps-2"><?php echo $errors['camas_casal']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline my-3 <?php echo !empty($data['camas_solteiro']) ? 'is-filled' : ''; ?>"><label class="form-label">Camas de Solteiro</label><input type="number" class="form-control" name="camas_solteiro" value="<?php echo htmlspecialchars($data['camas_solteiro'] ?? '0'); ?>" required></div>
                                    <?php if (!empty($errors['camas_solteiro'])): ?><div class="text-danger ps-2"><?php echo $errors['camas_solteiro']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-static my-3"><label>Hora de Check-in</label><input type="time" class="form-control" name="check_in_time" value="<?php echo htmlspecialchars($data['check_in_time'] ?? '14:00'); ?>"></div>
                                    <?php if (!empty($errors['check_in_time'])): ?><div class="text-danger ps-2"><?php echo $errors['check_in_time']; ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-static my-3"><label>Hora de Check-out</label><input type="time" class="form-control" name="check_out_time" value="<?php echo htmlspecialchars($data['check_out_time'] ?? '12:00'); ?>"></div>
                                    <?php if (!empty($errors['check_out_time'])): ?><div class="text-danger ps-2"><?php echo $errors['check_out_time']; ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-dark prev-step-btn">&larr; Anterior</button>
                                <button type="button" class="btn bg-gradient-dark next-step-btn">Próximo &rarr;</button>
                            </div>
                        </div>

                        <div class="step-panel" data-step="3">
                            <h6 class="text-dark text-sm mt-3">Amenidades</h6>
                            <p class="text-xs">Selecione todas as amenidades que esta acomodação oferece.</p>
                            <?php if (!empty($errors['amenidades'])): ?><div class="text-danger ps-2 mb-2"><?php echo $errors['amenidades']; ?></div><?php endif; ?>
                            <div class="row">
                                <?php if (!empty($Amenities)): foreach ($Amenities as $amenity): ?>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="amenidades[]" value="<?php echo $amenity['id']; ?>" id="amenity-<?php echo $amenity['id']; ?>" <?php echo (isset($data['amenidades']) && is_array($data['amenidades']) && in_array($amenity['id'], $data['amenidades'])) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="amenity-<?php echo $amenity['id']; ?>"><?php echo htmlspecialchars($amenity['nome']); ?></label>
                                            </div>
                                        </div>
                                    <?php endforeach;
                                else: ?><p class="text-sm">Nenhuma amenidade cadastrada.</p><?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-dark prev-step-btn">&larr; Anterior</button>
                                <button type="button" class="btn bg-gradient-dark next-step-btn">Próximo &rarr;</button>
                            </div>
                        </div>

                        <div class="step-panel" data-step="4">
                            <h6 class="text-dark text-sm mt-3">Fotos da Acomodação</h6>
                            <p class="text-xs">A primeira imagem será a capa. Arraste as fotos ou clique na área abaixo.</p>
                            <?php if (!empty($errors['imagens'])): ?><div class="text-danger ps-2 mb-2"><?php echo $errors['imagens']; ?></div><?php endif; ?>
                            <div id="drop-zone">
                                <i class="material-symbols-rounded" style="font-size: 4rem; color: #ced4da;">upload_file</i>
                                <p class="drop-zone-text">Arraste as imagens aqui ou clique para selecionar</p>
                            </div>
                            <input type="file" class="d-none" name="imagens[]" id="image-upload" multiple accept="image/*" required>
                            <div class="row mt-3" id="image-preview-container"></div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-dark prev-step-btn">&larr; Anterior</button>
                                <button type="submit" class="btn bg-gradient-success">Finalizar e Cadastrar</button>
                            </div>
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
    // O SCRIPT JAVASCRIPT PERMANECE O MESMO.
    // NENHUMA ALTERAÇÃO É NECESSÁRIA AQUI.
    document.addEventListener('DOMContentLoaded', function() {
        // ... (código do stepper, upload de fotos e máscara de moeda permanece igual)
        const form = document.getElementById('accommodation-form');
        const steps = [...document.querySelectorAll('.stepper-step')];
        const panels = [...document.querySelectorAll('.step-panel')];
        let currentStep = 1;

        function goToStep(stepNumber) {
            currentStep = stepNumber;
            steps.forEach(step => step.classList.toggle('active', parseInt(step.dataset.step) === currentStep));
            panels.forEach(panel => panel.classList.toggle('active', parseInt(panel.dataset.step) === currentStep));
            window.scrollTo(0, 0);
        }

        function validateCurrentStep() {
            const currentPanel = panels[currentStep - 1];
            const inputs = [...currentPanel.querySelectorAll('input[required], textarea[required], select[required]')];

            for (const input of inputs) {
                if (input.type === 'file' && input.files.length === 0) {
                    Swal.fire('Campo Obrigatório', 'Por favor, selecione pelo menos uma imagem.', 'warning');
                    return false;
                }
                if (!input.value.trim()) {
                    input.focus();
                    const labelText = input.closest('.input-group')?.querySelector('label')?.textContent || 'este campo';
                    Swal.fire('Campo Obrigatório', `Por favor, preencha o campo "${labelText}"`, 'warning');
                    return false;
                }
            }
            return true;
        }

        function validateAllSteps() {
            for (let i = 0; i < panels.length; i++) {
                const panel = panels[i];
                const inputs = [...panel.querySelectorAll('input[required], textarea[required], select[required]')];
                for (const input of inputs) {
                    if (input.type === 'file' && input.files.length === 0) {
                        goToStep(i + 1);
                        Swal.fire('Formulário Incompleto', 'Por favor, selecione pelo menos uma imagem na etapa de Fotos.', 'error');
                        return false;
                    }
                    if (!input.value.trim()) {
                        goToStep(i + 1);
                        input.focus();
                        const labelText = input.closest('.input-group')?.querySelector('label')?.textContent || 'este campo';
                        Swal.fire('Formulário Incompleto', `O campo "${labelText}" na etapa ${i+1} é obrigatório.`, 'error');
                        return false;
                    }
                }
            }
            // Validação customizada para amenidades (opcional, mas recomendado)
            const amenitiesPanel = document.querySelector('.step-panel[data-step="3"]');
            if (amenitiesPanel.querySelectorAll('input[name="amenidades[]"]:checked').length === 0) {
                goToStep(3);
                Swal.fire('Formulário Incompleto', 'Selecione pelo menos uma amenidade.', 'error');
                return false;
            }
            return true;
        }

        form.addEventListener('click', function(e) {
            if (e.target.matches('.next-step-btn')) {
                if (validateCurrentStep()) {
                    goToStep(currentStep + 1);
                }
            } else if (e.target.matches('.prev-step-btn')) {
                goToStep(currentStep - 1);
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateAllSteps()) {
                form.submit();
            }
        });

        const dropZone = document.getElementById('drop-zone');
        const imageUpload = document.getElementById('image-upload');
        const previewContainer = document.getElementById('image-preview-container');

        dropZone.addEventListener('click', () => imageUpload.click());
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('is-dragover');
        });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('is-dragover'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('is-dragover');
            imageUpload.files = e.dataTransfer.files;
            handleFiles(imageUpload.files);
        });
        imageUpload.addEventListener('change', (e) => handleFiles(e.target.files));

        function handleFiles(files) {
            previewContainer.innerHTML = '';
            if (files.length > 0) {
                for (const file of files) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const previewCard = document.createElement('div');
                        previewCard.className = 'col-md-3 col-sm-4 mb-3';
                        previewCard.innerHTML = `<div class="card"><img src="${e.target.result}" class="img-fluid border-radius-lg" style="height: 150px; object-fit: cover;"></div>`;
                        previewContainer.appendChild(previewCard);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        // --- MÁSCARA DE MOEDA (requer a biblioteca iMask) ---
        // Se não estiver usando iMask, pode remover ou comentar este bloco
        if (typeof IMask !== 'undefined') {
            IMask(document.getElementById('preco-input'), {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        radix: ','
                    }
                }
            });
        }
    });
</script>