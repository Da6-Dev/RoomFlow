<?php
ob_start();

/**
 * Função para renderizar um campo de formulário completo (label, input, erro).
 * CORRIGIDO: Agora recebe $data e $errors como parâmetros para garantir o escopo correto.
 * @param string $name O atributo 'name' do campo.
 * @param string $label O texto para a label do campo.
 * @param array $data Os dados submetidos para repopular o campo.
 * @param array $errors Os erros de validação para exibir.
 * @param array $options Opções adicionais como 'type', 'col_class', 'default', etc.
 */
function render_form_field($name, $label, $data, $errors, $options = [])
{
    // Define valores padrão para as opções
    $type = $options['type'] ?? 'text';
    $col_class = $options['col_class'] ?? 'col-md-12';
    $default_value = $options['default'] ?? '';
    $is_required = $options['required'] ?? false;
    
    // Prepara o valor e as classes de estilo com base nos dados existentes
    $current_value = $data[$name] ?? $default_value;
    $is_filled_class = ($current_value !== '' && $current_value !== null) ? 'is-filled' : '';
    $input_group_class = in_array($type, ['select', 'time', 'date']) ? 'input-group-static' : 'input-group-outline';

    // Inicia a renderização do container do campo
    echo "<div class='{$col_class}'>";
    echo "<div class='input-group {$input_group_class} my-3 {$is_filled_class}'>";

    // Renderiza a label de acordo com o tipo do input
    if ($input_group_class === 'input-group-static') {
        echo "<label class='ms-0'>{$label}</label>";
    } else {
        echo "<label class='form-label'>{$label}</label>";
    }

    // Renderiza o elemento de formulário apropriado (input, textarea, select)
    $required_attr = $is_required ? 'required' : '';
    $value_attr = htmlspecialchars($current_value);

    if ($type === 'textarea') {
        echo "<textarea class='form-control' name='{$name}' rows='5' {$required_attr}>{$value_attr}</textarea>";
    } elseif ($type === 'select') {
        echo "<select class='form-control' name='{$name}' {$required_attr}>";
        foreach ($options['options'] as $val => $text) {
            $selected_attr = ($current_value == $val) ? 'selected' : '';
            echo "<option value='{$val}' {$selected_attr}>{$text}</option>";
        }
        echo "</select>";
    } else {
        echo "<input type='{$type}' class='form-control' name='{$name}' value='{$value_attr}' {$required_attr}>";
    }

    echo "</div>"; // Fecha o .input-group

    // Renderiza a mensagem de erro, se existir
    if (!empty($errors[$name])) {
        echo "<div class='text-danger ps-2' style='font-size: 0.8rem;'>{$errors[$name]}</div>";
    }

    echo "</div>"; // Fecha o .col
}

/**
 * Retorna os detalhes do alerta com base na mensagem da URL ou erros gerais.
 */
function get_alert_details($errors = []) {
    $msg = $_GET['msg'] ?? '';

    if ($msg === 'success_create') {
        return ['class' => 'alert-success', 'message' => 'Cadastro realizado com sucesso!'];
    }
    if (!empty($errors['general'])) {
        return ['class' => 'alert-danger', 'message' => $errors['general']];
    }
    if (!empty($errors['exists'])) {
        return ['class' => 'alert-danger', 'message' => $errors['exists']];
    }
    return null;
}

// Garante que as variáveis sempre existam para evitar erros
$Amenities = $Amenities ?? [];
$data = $data ?? [];
$errors = $errors ?? [];
$alert = get_alert_details($errors);
?>

<style>
    /* Estilos para o Stepper e Upload de Imagens (sem alterações) */
    .stepper-header{display:flex;justify-content:space-around;padding:0;margin-bottom:2rem;list-style-type:none}.stepper-step{display:flex;flex-direction:column;align-items:center;text-align:center;flex-grow:1;position:relative}.step-circle{width:3rem;height:3rem;border-radius:50%;background-color:#e9ecef;color:#8392AB;display:flex;align-items:center;justify-content:center;font-weight:bold;transition:all .3s ease;z-index:2}.step-title{margin-top:.5rem;font-size:.875rem;font-weight:600;color:#8392AB}.stepper-step.active .step-circle{background:linear-gradient(195deg,#EC407A,#D81B60);color:#fff;box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06)}.stepper-step.active .step-title{color:#344767}.stepper-step::after{content:'';position:absolute;top:1.5rem;left:50%;width:100%;height:2px;background-color:#e9ecef;z-index:1}.stepper-step:last-child::after{display:none}.step-panel{display:none}.step-panel.active{display:block;animation:fadeIn .5s}@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}#drop-zone{border:2px dashed #dee2e6;border-radius:.5rem;padding:40px;text-align:center;cursor:pointer;transition:all .2s ease-in-out}#drop-zone:hover,#drop-zone.is-dragover{border-color:#D81B60;background-color:#f8f9fa}#drop-zone .drop-zone-text{color:#6c757d}
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

                    <?php if ($alert): ?>
                        <div class="alert <?php echo $alert['class']; ?> text-white alert-dismissible fade show" role="alert">
                            <span class="alert-text"><strong><?php echo $alert['class'] == 'alert-success' ? 'Sucesso!' : 'Erro!'; ?></strong> <?php echo $alert['message']; ?></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php endif; ?>

                    <ul class="stepper-header">
                        <li class="stepper-step active" data-step="1"><div class="step-circle">1</div><div class="step-title">Informações</div></li>
                        <li class="stepper-step" data-step="2"><div class="step-circle">2</div><div class="step-title">Detalhes e Preço</div></li>
                        <li class="stepper-step" data-step="3"><div class="step-circle">3</div><div class="step-title">Amenidades</div></li>
                        <li class="stepper-step" data-step="4"><div class="step-circle">4</div><div class="step-title">Fotos</div></li>
                    </ul>

                    <form action="/RoomFlow/Acomodacoes/Cadastrar" method="post" enctype="multipart/form-data" role="form" id="accommodation-form">
                        <div class="step-panel active" data-step="1">
                            <h6 class="text-dark text-sm mt-3">Informações Principais</h6>
                            <div class="row">
                                <?php render_form_field('tipo', 'Tipo (ex: Suíte Master)', $data, $errors, ['col_class' => 'col-md-5', 'required' => true]); ?>
                                <?php render_form_field('numero', 'Número do Quarto', $data, $errors, ['type' => 'number', 'col_class' => 'col-md-3', 'required' => true]); ?>
                                <?php render_form_field('status', 'Status Inicial', $data, $errors, [
                                    'type' => 'select', 'col_class' => 'col-md-4', 'required' => true,
                                    'options' => ['disponivel' => 'Disponível', 'manutencao' => 'Manutenção', 'ocupado' => 'Ocupado']
                                ]); ?>
                            </div>
                            <?php render_form_field('descricao', 'Descrição da Acomodação', $data, $errors, ['type' => 'textarea', 'required' => true]); ?>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn bg-gradient-dark ms-auto next-step-btn">Próximo &rarr;</button>
                            </div>
                        </div>

                        <div class="step-panel" data-step="2">
                            <h6 class="text-dark text-sm mt-3">Detalhes e Preços</h6>
                            <div class="row">
                                <?php render_form_field('capacidade', 'Capacidade (Pessoas)', $data, $errors, ['type' => 'number', 'col_class' => 'col-md-3', 'required' => true]); ?>
                                <?php render_form_field('preco', 'Preço (R$)', $data, $errors, ['type' => 'text', 'col_class' => 'col-md-3', 'required' => true, 'id' => 'preco-input']); ?>
                                <?php render_form_field('minimo_noites', 'Mínimo de Noites', $data, $errors, ['type' => 'number', 'col_class' => 'col-md-3', 'default' => '1', 'required' => true]); ?>
                                <?php render_form_field('camas_casal', 'Camas de Casal', $data, $errors, ['type' => 'number', 'col_class' => 'col-md-3', 'default' => '0', 'required' => true]); ?>
                                <?php render_form_field('camas_solteiro', 'Camas de Solteiro', $data, $errors, ['type' => 'number', 'col_class' => 'col-md-3', 'default' => '0', 'required' => true]); ?>
                                <?php render_form_field('check_in_time', 'Hora de Check-in', $data, $errors, ['type' => 'time', 'col_class' => 'col-md-3', 'default' => '14:00']); ?>
                                <?php render_form_field('check_out_time', 'Hora de Check-out', $data, $errors, ['type' => 'time', 'col_class' => 'col-md-3', 'default' => '12:00']); ?>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-dark prev-step-btn">&larr; Anterior</button>
                                <button type="button" class="btn bg-gradient-dark next-step-btn">Próximo &rarr;</button>
                            </div>
                        </div>

                        <div class="step-panel" data-step="3">
                            <h6 class="text-dark text-sm mt-3">Amenidades</h6>
                            <p class="text-xs">Selecione todas as amenidades que esta acomodação oferece.</p>
                            <?php if (!empty($errors['amenidades'])): ?><div class="text-danger ps-2 mb-2" style="font-size: 0.8rem;"><?php echo $errors['amenidades']; ?></div><?php endif; ?>
                            <div class="row">
                                <?php if (!empty($Amenities)): foreach ($Amenities as $amenity): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenidades[]" value="<?php echo $amenity['id']; ?>" id="amenity-<?php echo $amenity['id']; ?>" <?php echo (isset($data['amenidades']) && is_array($data['amenidades']) && in_array($amenity['id'], $data['amenidades'])) ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="amenity-<?php echo $amenity['id']; ?>"><?php echo htmlspecialchars($amenity['nome']); ?></label>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?><p class="text-sm">Nenhuma amenidade cadastrada.</p><?php endif; ?>
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