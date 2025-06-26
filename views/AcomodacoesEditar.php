<?php
ob_start();

// Garante que as variáveis sempre existam
$acomodacao = $acomodacao ?? [];
$amenidades = $amenidades ?? [];
$amenidades_acomodacao = $amenidades_acomodacao ?? [];
$imagens = $imagens ?? [];
$errors = $errors ?? [];

?>

<style>
    /* Estilos para FilePond e SortableJS */
    .filepond--root {
        font-family: 'Roboto', sans-serif;
    }

    .sortable-ghost {
        opacity: 0.4;
        background: #f0f0f0;
    }

    .image-card-handle {
        cursor: grab;
    }

    .image-card-handle:active {
        cursor: grabbing;
    }
</style>

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Editar Acomodação: <?php echo htmlspecialchars($acomodacao['tipo'] . ' - Nº ' . $acomodacao['numero']); ?></h6>
            </div>
        </div>
        <div class="card-body px-4 pb-3">

            <form action="/RoomFlow/Acomodacoes/Update/<?php echo $acomodacao['id'] ?>" method="post" enctype="multipart/form-data" role="form">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">Informações</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">Detalhes</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="amenities-tab" data-bs-toggle="tab" data-bs-target="#amenities" type="button">Amenidades</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos" type="button">Fotos</button></li>
                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Tipo</label><input type="text" class="form-control" name="tipo" value="<?php echo htmlspecialchars($_POST['tipo'] ?? $acomodacao['tipo']); ?>" required></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Número</label><input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($_POST['numero'] ?? $acomodacao['numero']); ?>" required></div>
                            </div>
                            <div class="col-12">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Descrição</label><textarea class="form-control" name="descricao" rows="5" required><?php echo htmlspecialchars($_POST['descricao'] ?? $acomodacao['descricao']); ?></textarea></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3"><label>Status</label><select class="form-control" name="status" required>
                                        <option value="disponivel" <?php echo (($_POST['status'] ?? $acomodacao['status']) == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
                                        <option value="ocupado" <?php echo (($_POST['status'] ?? $acomodacao['status']) == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                                        <option value="manutencao" <?php echo (($_POST['status'] ?? $acomodacao['status']) == 'manutencao') ? 'selected' : ''; ?>>Manutenção</option>
                                    </select></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="details" role="tabpanel">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Preço (R$)</label><input type="text" class="form-control" name="preco" id="preco-input" value="<?php echo isset($_POST['preco']) ? htmlspecialchars($_POST['preco']) : number_format($acomodacao['preco'], 2, ',', '.'); ?>" required></div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Capacidade</label><input type="number" class="form-control" name="capacidade" value="<?php echo htmlspecialchars($_POST['capacidade'] ?? $acomodacao['capacidade']); ?>" required></div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Mínimo de Noites</label><input type="number" class="form-control" name="minimo_noites" value="<?php echo htmlspecialchars($_POST['minimo_noites'] ?? $acomodacao['minimo_noites']); ?>" required></div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Camas de Casal</label><input type="number" class="form-control" name="camas_casal" value="<?php echo htmlspecialchars($_POST['camas_casal'] ?? $acomodacao['camas_casal']); ?>" required></div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled"><label class="form-label">Camas de Solteiro</label><input type="number" class="form-control" name="camas_solteiro" value="<?php echo htmlspecialchars($_POST['camas_solteiro'] ?? $acomodacao['camas_solteiro']); ?>" required></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3"><label>Hora de Check-in</label><input type="time" class="form-control" name="check_in_time" value="<?php echo htmlspecialchars($_POST['check_in_time'] ?? $acomodacao['hora_checkin']); ?>" required></div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3"><label>Hora de Check-out</label><input type="time" class="form-control" name="check_out_time" value="<?php echo htmlspecialchars($_POST['check_out_time'] ?? $acomodacao['hora_checkout']); ?>" required></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="amenities" role="tabpanel">
                        <div class="row mt-4">
                            <?php
                            // Pega as amenidades atuais da acomodação para pré-selecionar os checkboxes
                            $current_amenities = $_POST['amenidades'] ?? $amenidades_acomodacao;

                            // Loop para exibir todas as amenidades disponíveis
                            foreach ($amenidades as $amenity):
                            ?>
                                <div class="col-md-4 col-sm-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenidades[]" value="<?php echo $amenity['id']; ?>" id="amenidade_<?php echo $amenity['id']; ?>" <?php echo in_array($amenity['id'], $current_amenities) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="amenidade_<?php echo $amenity['id']; ?>"><?php echo htmlspecialchars($amenity['nome']); ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="photos" role="tabpanel">
                        <div class="mt-4">
                            <h5>Adicionar Novas Fotos</h5>
                            <input type="file" class="filepond" name="imagens[]" id="image-upload" multiple>

                            <hr class="horizontal dark mt-5">

                            <h5>Imagens Atuais</h5>
                            <p class="text-sm">Arraste as imagens para reordenar. A primeira imagem será a capa.</p>

                            <div class="row" id="sortable-images">
                                <?php if (empty($imagens)): ?>
                                    <p class="text-center">Nenhuma imagem cadastrada.</p>
                                <?php else: ?>
                                    <?php foreach ($imagens as $imagem): ?>
                                        <div class="col-xl-3 col-md-4 col-sm-6 mb-4 image-card-handle">
                                            <div class="card h-100">
                                                <img src="/RoomFlow/<?php echo htmlspecialchars($imagem['caminho_arquivo']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                                                <div class="card-body text-center">
                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" name="delete_imagens[]" value="<?php echo $imagem['id']; ?>" id="delete_<?php echo $imagem['id']; ?>">
                                                        <label class="form-check-label" for="delete_<?php echo $imagem['id']; ?>">Excluir</label>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="image_order[]" value="<?php echo $imagem['id']; ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="/RoomFlow/Acomodacoes" class="btn btn-outline-secondary mb-0 me-2">Voltar</a>
                        <button type="submit" class="btn bg-gradient-dark mb-0">Atualizar Acomodação</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Ativar a máscara de preço (IMask.js)
        const precoInput = document.getElementById('preco-input');
        if (precoInput) {
            IMask(precoInput, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        radix: ',',
                        mapToRadix: ['.']
                    }
                }
            });
        }

        // 2. Ativar o upload de arquivos (FilePond)
        const uploadInput = document.getElementById('image-upload');
        if (uploadInput) {
            FilePond.create(uploadInput, {
                labelIdle: `Arraste e solte seus arquivos ou <span class="filepond--label-action">Procure</span>`,
                allowMultiple: true,
                acceptedFileTypes: ['image/*'],
            });
        }

        // 3. Ativar o reordenamento de imagens (SortableJS)
        const sortableContainer = document.getElementById('sortable-images');
        if (sortableContainer) {
            new Sortable(sortableContainer, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                handle: '.image-card-handle',
            });
        }
    });
</script>