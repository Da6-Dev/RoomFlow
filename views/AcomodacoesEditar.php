<?php
ob_start();

// Garante que as variáveis globais sempre existam para evitar erros.
$acomodacao = $acomodacao ?? [];
$amenidades = $amenidades ?? [];
$amenidades_acomodacao = $amenidades_acomodacao ?? [];
$imagens = $imagens ?? [];
$errors = $errors ?? [];

// Lógica para determinar o valor a ser exibido: usa o dado do POST se existir, senão, o do banco.
function get_form_value($name, $db_record, $db_key = null, $is_numeric = false) {
    if ($db_key === null) {
        $db_key = $name;
    }
    
    if (isset($_POST[$name])) {
        return htmlspecialchars($_POST[$name]);
    }
    
    if (isset($db_record[$db_key])) {
        $value = $db_record[$db_key];
        // Formata o preço apenas na carga inicial
        if ($name === 'preco') {
            return number_format((float)$value, 2, ',', '.');
        }
        return htmlspecialchars($value);
    }
    
    return $is_numeric ? '0' : '';
}

?>

<style>
    .filepond--root{font-family:'Roboto',sans-serif;}.sortable-ghost{opacity:0.4;background:#f0f0f0;}.image-card-handle{cursor:grab;}.image-card-handle:active{cursor:grabbing;}
</style>

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
            <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Editar Acomodação: <?php echo htmlspecialchars($acomodacao['tipo'] . ' - Nº ' . $acomodacao['numero']); ?></h6>
            </div>
        </div>
        <div class="card-body px-4 pb-3">

            <form action="/RoomFlow/Dashboard/Acomodacoes/Update/<?php echo $acomodacao['id'] ?>" method="post" enctype="multipart/form-data" role="form">
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
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Tipo</label>
                                    <input type="text" class="form-control" name="tipo" value="<?php echo get_form_value('tipo', $acomodacao); ?>" required>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Número</label>
                                    <input type="number" class="form-control" name="numero" value="<?php echo get_form_value('numero', $acomodacao); ?>" required>
                                </div>
                            </div>
                            <div class="col-12">
                                 <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control" name="descricao" rows="5" required><?php echo get_form_value('descricao', $acomodacao); ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Status</label>
                                    <select class="form-control" name="status" required>
                                        <?php $statusValue = get_form_value('status', $acomodacao); ?>
                                        <option value="disponivel" <?php echo ($statusValue == 'disponivel') ? 'selected' : ''; ?>>Disponível</option>
                                        <option value="ocupado" <?php echo ($statusValue == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                                        <option value="manutencao" <?php echo ($statusValue == 'manutencao') ? 'selected' : ''; ?>>Manutenção</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="details" role="tabpanel">
                         <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Preço (R$)</label>
                                    <input type="text" class="form-control" id="preco-input" name="preco" value="<?php echo get_form_value('preco', $acomodacao); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Capacidade</label>
                                    <input type="number" class="form-control" name="capacidade" value="<?php echo get_form_value('capacidade', $acomodacao); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Mínimo de Noites</label>
                                    <input type="number" class="form-control" name="minimo_noites" value="<?php echo get_form_value('minimo_noites', $acomodacao); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Camas de Casal</label>
                                    <input type="number" class="form-control" name="camas_casal" value="<?php echo get_form_value('camas_casal', $acomodacao, null, true); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-outline my-3 is-filled">
                                    <label class="form-label">Camas de Solteiro</label>
                                    <input type="number" class="form-control" name="camas_solteiro" value="<?php echo get_form_value('camas_solteiro', $acomodacao, null, true); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Hora de Check-in</label>
                                    <input type="time" class="form-control" name="check_in_time" value="<?php echo get_form_value('check_in_time', $acomodacao, 'hora_checkin'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label>Hora de Check-out</label>
                                    <input type="time" class="form-control" name="check_out_time" value="<?php echo get_form_value('check_out_time', $acomodacao, 'hora_checkout'); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="amenities" role="tabpanel">
                        <div class="row mt-4">
                            <?php
                            // --- CORREÇÃO APLICADA AQUI ---
                            // Removemos o "array_column" para usar o array de IDs diretamente.
                            $current_amenities = $_POST['amenidades'] ?? $amenidades_acomodacao;
                            
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
                                <?php else: foreach ($imagens as $imagem): ?>
                                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4 image-card-handle">
                                        <div class="card h-100">
                                            <img src="/RoomFlow/<?php echo htmlspecialchars($imagem['caminho_arquivo']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                                            <div class="card-body text-center py-2">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" name="delete_imagens[]" value="<?php echo $imagem['id']; ?>" id="delete_<?php echo $imagem['id']; ?>">
                                                    <label class="form-check-label" for="delete_<?php echo $imagem['id']; ?>">Excluir</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="image_order[]" value="<?php echo $imagem['id']; ?>">
                                        </div>
                                    </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="/RoomFlow/Dashboard/Acomodacoes" class="btn btn-outline-secondary mb-0 me-2">Voltar</a>
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