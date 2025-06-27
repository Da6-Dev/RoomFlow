<?php
ob_start();

// Garante que as variáveis sempre existam.
$guest = $guest ?? [];
$errors = $errors ?? [];
$preferencias = $preferencias ?? [];

/**
 * Função auxiliar para renderizar um campo de formulário no modo de edição.
 * CORRIGIDO: Agora recebe $guest e $errors como parâmetros para garantir o escopo.
 *
 * @param string $name O atributo 'name' do campo.
 * @param string $label O texto para a label do campo.
 * @param array  $guest O array com os dados do hóspede do banco.
 * @param array  $errors O array com os erros de validação.
 * @param array  $options Opções como 'type', 'col_class', 'db_key', etc.
 */
function render_edit_field($name, $label, $guest, $errors, $options = []) {
    // REMOVIDO: A linha "global $guest, $errors;" que causava o erro.

    // Configurações
    $type = $options['type'] ?? 'text';
    $col_class = $options['col_class'] ?? 'col-md-12';
    $db_key = $options['db_key'] ?? $name;
    $id = "{$name}-input";
    
    // Lógica para obter o valor: usa o do POST se existir, senão, o do banco.
    $value = htmlspecialchars($_POST[$name] ?? $guest[$db_key] ?? '');

    // Para formulários de edição, a classe 'is-filled' deve estar sempre presente.
    $group_class = ($type === 'date') ? 'input-group-static' : 'input-group-outline is-filled';

    // Inicia a renderização
    echo "<div class='{$col_class}'>";
    echo "  <div class='input-group {$group_class} my-3'>";
    
    if ($type === 'date') {
        echo "<label>{$label}</label>";
    } else {
        echo "<label class='form-label'>{$label}</label>";
    }

    echo "<input type='{$type}' class='form-control' name='{$name}' id='{$id}' value='{$value}' required>";
    
    echo "  </div>";

    // Exibe o erro de validação, se houver
    if (!empty($errors[$name])) {
        echo "<div class='text-danger text-xs mt-n2 ms-1'>{$errors[$name]}</div>";
    }
    
    echo "</div>";
}

// Prepara as preferências: usa as do POST se houver, senão, as do banco.
$hospedePreferencias = $_POST['preferencias'] ?? $preferencias;

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
                            <?php render_edit_field('nome', 'Nome Completo', $guest, $errors, ['col_class' => 'col-md-8']); ?>
                            <?php render_edit_field('dataNasc', 'Data de Nascimento', $guest, $errors, ['type' => 'date', 'col_class' => 'col-md-4', 'db_key' => 'data_nascimento']); ?>
                        </div>

                        <div class="row">
                            <?php render_edit_field('email', 'Email', $guest, $errors, ['type' => 'email', 'col_class' => 'col-md-6']); ?>
                            <?php render_edit_field('cpf', 'CPF', $guest, $errors, ['col_class' => 'col-md-3', 'db_key' => 'documento']); ?>
                            <?php render_edit_field('telefone', 'Telefone', $guest, $errors, ['type' => 'tel', 'col_class' => 'col-md-3']); ?>
                        </div>

                        <hr class="horizontal dark my-4">

                        <h6 class="text-dark text-sm">Endereço</h6>
                        <div class="row">
                            <?php render_edit_field('cep', 'CEP', $guest, $errors, ['col_class' => 'col-md-3']); ?>
                            <?php render_edit_field('rua', 'Rua / Logradouro', $guest, $errors, ['col_class' => 'col-md-7']); ?>
                            <?php render_edit_field('numero', 'Número', $guest, $errors, ['col_class' => 'col-md-2']); ?>
                        </div>
                        <div class="row">
                            <?php render_edit_field('cidade', 'Cidade', $guest, $errors, ['col_class' => 'col-md-6']); ?>
                            <?php render_edit_field('estado', 'Estado', $guest, $errors, ['col_class' => 'col-md-6']); ?>
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
                                        <input type="file" name="imagem" id="image-upload" class="d-none" accept="image/*">
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
    
</script>