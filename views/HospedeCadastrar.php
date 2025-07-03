<?php
ob_start();

// Garante que as variáveis para o formulário sempre existam.
$errors = $errors ?? [];
$data = $data ?? [];

/**
 * Função auxiliar para renderizar um campo de formulário completo.
 * CORRIGIDO: Agora recebe $data e $errors como parâmetros para garantir o escopo correto.
 *
 * @param string $name O atributo 'name' do campo.
 * @param string $label O texto para a label do campo.
 * @param array $data Os dados do formulário para repopulação.
 * @param array $errors Os erros de validação a serem exibidos.
 * @param array $options Opções como 'type', 'col_class', 'id', etc.
 */
function render_form_field($name, $label, $data, $errors, $options = []) {
    // REMOVIDO: A linha "global $errors, $data;" que causava o erro.

    // Configurações padrão
    $type = $options['type'] ?? 'text';
    $col_class = $options['col_class'] ?? 'col-md-12';
    $id = $options['id'] ?? "{$name}-input";
    $required = $options['required'] ?? true;
    
    // Define o valor do campo (usa o valor do array $data passado como argumento)
    $value = htmlspecialchars($data[$name] ?? '');

    // Define a classe do grupo de input
    $group_class = ($type === 'date') ? 'input-group-static' : 'input-group-outline';
    $filled_class = !empty($value) ? 'is-filled' : '';

    // Inicia a renderização do campo
    echo "<div class='{$col_class}'>";
    echo "  <div class='input-group {$group_class} my-3 {$filled_class}'>";
    
    if ($type === 'date') {
        echo "<label>{$label}</label>";
    } else {
        echo "<label class='form-label'>{$label}</label>";
    }

    echo "<input type='{$type}' class='form-control' name='{$name}' id='{$id}' value='{$value}' " . ($required ? 'required' : '') . ">";
    
    echo "  </div>";
    
    // Exibe a mensagem de erro, se houver (usa o array $errors passado como argumento)
    if (!empty($errors[$name])) {
        echo "<div class='text-danger text-xs mt-n2 ms-1'>{$errors[$name]}</div>";
    }
    
    echo "</div>";
}

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
                    <form action="/RoomFlow/Dashboard/Hospedes/Cadastrar" method="post" enctype="multipart/form-data" role="form">

                        <h6 class="text-dark text-sm mt-4">Dados Pessoais</h6>
                        <div class="row">
                            <?php render_form_field('nome', 'Nome Completo', $data, $errors, ['col_class' => 'col-md-8']); ?>
                            <?php render_form_field('dataNasc', 'Data de Nascimento', $data, $errors, ['type' => 'date', 'col_class' => 'col-md-4']); ?>
                        </div>

                        <div class="row">
                            <?php render_form_field('email', 'Email', $data, $errors, ['type' => 'email', 'col_class' => 'col-md-6']); ?>
                            <?php render_form_field('cpf', 'CPF', $data, $errors, ['col_class' => 'col-md-3']); ?>
                            <?php render_form_field('telefone', 'Telefone', $data, $errors, ['type' => 'tel', 'col_class' => 'col-md-3']); ?>
                        </div>

                        <hr class="horizontal dark my-4">

                        <h6 class="text-dark text-sm">Endereço</h6>
                        <div class="row">
                            <?php render_form_field('cep', 'CEP', $data, $errors, ['col_class' => 'col-md-3']); ?>
                            <?php render_form_field('rua', 'Rua / Logradouro', $data, $errors, ['col_class' => 'col-md-7']); ?>
                            <?php render_form_field('numero', 'Número', $data, $errors, ['col_class' => 'col-md-2']); ?>
                        </div>
                        <div class="row">
                            <?php render_form_field('cidade', 'Cidade', $data, $errors, ['col_class' => 'col-md-6']); ?>
                            <?php render_form_field('estado', 'Estado', $data, $errors, ['col_class' => 'col-md-6']); ?>
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
                                        <label for="image-upload" class="btn btn-sm btn-outline-dark mb-0">Escolher Imagem</label>
                                        <input type="file" name="imagem" id="image-upload" class="d-none" accept="image/*">
                                        <p id="file-name" class="text-xs text-secondary mt-1 mb-0">Nenhum arquivo selecionado.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <h6 class="text-dark text-sm">Preferências / Observações</h6>
                                <p class="text-xs text-secondary mb-3">Ex: Intolerância a lactose, andar baixo, etc.</p>

                                <div id="preferences-container">
                                    <?php
                                    $posted_preferences = $data['preferencias'] ?? [];
                                    if (!empty($posted_preferences)):
                                        foreach ($posted_preferences as $pref_text):
                                            if (!empty($pref_text)):
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
                                                        <button type="button" class="btn btn-icon-only btn-link text-danger remove-pref-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover">
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

                                <button type="button" id="add-preference-btn" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="material-symbols-rounded align-middle">add</i>
                                    Adicionar Preferência
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="/RoomFlow/Dashboard/Hospedes" class="btn btn-outline-dark me-2">Cancelar</a>
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