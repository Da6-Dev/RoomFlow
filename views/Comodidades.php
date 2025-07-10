<?php
ob_start();

// -- LÓGICA DE PREPARAÇÃO DA VIEW --

// 1. Função auxiliar para os alertas de notificação
function get_alert_details($msg_code)
{
    $alerts = [
        'success_create' => ['class' => 'alert-success', 'message' => 'Cadastro realizado com sucesso!'],
        'error_create'   => ['class' => 'alert-danger', 'message' => 'Erro ao cadastrar a comodidade.'],
        'success_update' => ['class' => 'alert-success', 'message' => 'Dados atualizados com sucesso!'],
        'error_update'   => ['class' => 'alert-danger', 'message' => 'Erro ao atualizar os dados.'],
        'success_delete' => ['class' => 'alert-success', 'message' => 'Comodidade excluída com sucesso!'],
        'error_delete'   => ['class' => 'alert-danger', 'message' => 'Erro ao excluir a comodidade.']
    ];
    return $alerts[$msg_code] ?? null;
}

// 2. Função auxiliar para ícones das amenidades
function getAmenityIcon($nome)
{
    $nome = strtolower($nome);
    if (strpos($nome, 'wi-fi') !== false || strpos($nome, 'wifi') !== false) return 'wifi';
    if (strpos($nome, 'ar condicionado') !== false || strpos($nome, 'ar-condicionado') !== false) return 'ac_unit';
    if (strpos($nome, 'piscina') !== false) return 'pool';
    if (strpos($nome, 'estacionamento') !== false) return 'local_parking';
    if (strpos($nome, 'café') !== false || strpos($nome, 'cafe') !== false) return 'coffee';
    if (strpos($nome, 'tv') !== false) return 'tv';
    if (strpos($nome, 'frigobar') !== false) return 'kitchen';
    if (strpos($nome, 'banheira') !== false) return 'bathtub';
    if (strpos($nome, 'academia') !== false) return 'fitness_center';
    return 'label';
}

// 3. Preparação de variáveis para a view
$msg_code = $_GET['msg'] ?? '';
$alert = get_alert_details($msg_code);

$form_action = $form_action ?? ''; // Evita erro de variável indefinida
$errors = $errors ?? [];
$data = $data ?? [];

// 4. Prepara valores e erros específicos para o formulário de CRIAÇÃO
$create_nome_value = '';
$create_error_msg = '';
if ($form_action === 'create') {
    $create_nome_value = htmlspecialchars($data['nome'] ?? '');
    $create_error_msg = $errors['nome'] ?? '';
}

?>

<div class="container-fluid py-4">
    <?php if ($alert): ?>
        <div class="alert <?php echo $alert['class']; ?> text-white alert-dismissible fade show" role="alert" id="alertMessage">
            <span class="alert-icon align-middle"><i class="material-symbols-rounded">check_circle</i></span>
            <span class="alert-text"><strong><?php echo $alert['class'] === 'alert-success' ? 'Sucesso!' : 'Erro!'; ?></strong> <?php echo $alert['message']; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="material-symbols-rounded opacity-10 me-1">add_circle</i> Adicionar Nova Comodidade</h6>
                </div>
                <div class="card-body pt-0 p-3">
                    <form action="/RoomFlow/Comodidades/Cadastrar" method="POST">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($create_nome_value) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Nome da Comodidade (ex: Wi-Fi Grátis)</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $create_nome_value; ?>" required>
                        </div>
                        <?php if (!empty($create_error_msg)): ?>
                            <div class="d-flex align-items-center text-danger text-sm mt-n2 mb-2">
                                <i class="material-symbols-rounded align-middle me-1" style="font-size: 1.1rem;">error</i>
                                <span><?php echo $create_error_msg; ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="text-end">
                            <button type="submit" class="btn bg-gradient-dark mb-0">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="material-symbols-rounded opacity-10 me-1">checklist</i> Comodidades Existentes</h6>
                </div>
                <div class="card-body pt-0 p-3">
                    <ul class="list-group">
                        <?php if (!empty($Amenities)): foreach ($Amenities as $amenity): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="material-symbols-rounded me-3"><?php echo getAmenityIcon($amenity['nome']); ?></i>
                                        <span class="text-sm"><?php echo htmlspecialchars($amenity['nome']); ?></span>
                                    </div>
                                    <div class="actions">
                                        <form action="/RoomFlow/Comodidades/Deletar" method="POST" id="form-delete-<?php echo $amenity['id']; ?>" class="d-none">
                                            <input type="hidden" name="id" value="<?php echo $amenity['id']; ?>">
                                        </form>
                                        <a href="#" class="text-secondary me-2" onclick="openEditModal(<?php echo $amenity['id']; ?>, '<?php echo htmlspecialchars(addslashes($amenity['nome'])); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                            <i class="material-symbols-rounded">edit</i>
                                        </a>
                                        <a href="#" class="text-danger" onclick="confirmDelete(<?php echo $amenity['id']; ?>, '<?php echo htmlspecialchars(addslashes($amenity['nome'])); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir">
                                            <i class="material-symbols-rounded">delete</i>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach;
                        else: ?>
                            <li class="list-group-item text-center">Nenhuma comodidade cadastrada.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAmenityModal" tabindex="-1" aria-labelledby="editAmenityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAmenityModalLabel">Editar Comodidade</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editAmenityId">
                    <div class="input-group input-group-outline my-3 is-filled">
                        <label class="form-label">Nome da Comodidade</label>
                        <input type="text" name="nome" id="editAmenityName" class="form-control" required>
                    </div>
                    <div id="edit-error-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
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
        const alertMessage = document.getElementById('alertMessage');
        if (alertMessage) {
            setTimeout(() => {
                new bootstrap.Alert(alertMessage).close();
            }, 5000);
        }

        // Lógica para reabrir o modal de edição em caso de erro de validação
        <?php if ($form_action === 'update' && !empty($errors['nome'])): ?>
            const failedName = '<?php echo addslashes($data['nome'] ?? ''); ?>';
            const errorId = <?php echo $update_error_id ?? 0; ?>;

            if (errorId > 0) {
                openEditModal(errorId, failedName);

                const errorContainer = document.getElementById('edit-error-container');
                const errorMessage = '<?php echo $errors['nome']; ?>';
                errorContainer.innerHTML = `
                    <div class="d-flex align-items-center text-danger text-sm mt-n2">
                        <i class="material-symbols-rounded align-middle me-1" style="font-size: 1.1rem;">error</i>
                        <span>${errorMessage}</span>
                    </div>`;
            }
        <?php endif; ?>
    });
</script>