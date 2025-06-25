<?php

ob_start();

// Verifica se há uma mensagem na URL e define a classe e o texto do alerta com base nela
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alertClass = '';
$alertMessage = '';

switch ($msg) {
    case 'success_create':
        $alertClass = 'alert-success';
        $alertMessage = 'Cadastro realizado com sucesso!';
        break;
    case 'error_create':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao cadastrar o hóspede.';
        break;
    case 'success_update':
        $alertClass = 'alert-success';
        $alertMessage = 'Dados atualizados com sucesso!';
        break;
    case 'error_update':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao atualizar os dados do hóspede.';
        break;
    case 'success_delete':
        $alertClass = 'alert-success';
        $alertMessage = 'Comodidade excluída com sucesso!';
        break;
    case 'error_delete':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao excluir a comodidade.';
        break;
    default:
        $alertClass = '';
        $alertMessage = '';
        break;
}
/**
 * Função para retornar um ícone do Material Symbols com base no nome da comodidade.
 * @param string $nome O nome da comodidade.
 * @return string O nome do ícone.
 */
function getAmenityIcon($nome)
{
    $nome = strtolower($nome);
    if (strpos($nome, 'wi-fi') !== false || strpos($nome, 'wifi') !== false)
        return 'wifi';
    if (strpos($nome, 'ar condicionado') !== false || strpos($nome, 'ar-condicionado') !== false)
        return 'ac_unit';
    if (strpos($nome, 'piscina') !== false)
        return 'pool';
    if (strpos($nome, 'estacionamento') !== false)
        return 'local_parking';
    if (strpos($nome, 'café') !== false || strpos($nome, 'cafe') !== false)
        return 'coffee';
    if (strpos($nome, 'tv') !== false)
        return 'tv';
    if (strpos($nome, 'frigobar') !== false)
        return 'kitchen';
    if (strpos($nome, 'banheira') !== false)
        return 'bathtub';
    if (strpos($nome, 'academia') !== false)
        return 'fitness_center';

    return 'label'; // Ícone padrão
}

// Lógica para preservar o valor do campo de criação em caso de erro
$create_nome_value = (isset($form_action) && $form_action === 'create' && isset($data['nome'])) ? htmlspecialchars($data['nome']) : '';
?>

<div class="container-fluid py-4">
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white alert-dismissible fade show" role="alert" id="alertMessage">
            <span class="alert-icon align-middle"><i class="material-symbols-rounded">check_circle</i></span>
            <span class="alert-text"><strong>Sucesso!</strong> <?php echo $alertMessage; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="material-symbols-rounded opacity-10 me-1">add_circle</i> Adicionar Nova
                        Comodidade</h6>
                </div>
                <div class="card-body pt-0 p-3">
                    <form action="/RoomFlow/Comodidades/Cadastrar" method="POST">
                        <div class="input-group input-group-outline my-3 <?php echo !empty($create_nome_value) ? 'is-filled' : ''; ?>">
                            <label class="form-label">Nome da Comodidade (ex: Wi-Fi Grátis)</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $create_nome_value; ?>" required>
                        </div>
                        <?php 
                        // **CORREÇÃO AQUI**: Exibe o erro de criação apenas se a ação for 'create'
                        if (isset($errors['nome']) && isset($form_action) && $form_action === 'create'): 
                        ?>
                            <div class="d-flex align-items-center text-danger text-sm mt-1">
                                <i class="material-symbols-rounded align-middle me-1" style="font-size: 1.1rem;">error</i>
                                <span><?php echo $errors['nome']; ?></span>
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
                    <h6 class="mb-0"><i class="material-symbols-rounded opacity-10 me-1">checklist</i> Comodidades
                        Existentes</h6>
                </div>
                <div class="card-body pt-0 p-3">
                    <ul class="list-group">
                        <?php if (!empty($Amenities)): ?>
                            <?php foreach ($Amenities as $amenity): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i
                                            class="material-symbols-rounded me-3"><?php echo getAmenityIcon($amenity['nome']); ?></i>
                                        <span class="text-sm"><?php echo htmlspecialchars($amenity['nome']); ?></span>
                                    </div>
                                    <div class="actions">
                                        <form action="/RoomFlow/Comodidades/Deletar" method="POST"
                                            id="form-delete-<?php echo $amenity['id']; ?>" class="d-none">
                                            <input type="hidden" name="id" value="<?php echo $amenity['id']; ?>">
                                        </form>

                                        <a href="#" class="text-secondary me-2"
                                            onclick="openEditModal(<?php echo $amenity['id']; ?>, '<?php echo htmlspecialchars(addslashes($amenity['nome'])); ?>')"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                            <i class="material-symbols-rounded">edit</i>
                                        </a>
                                        <a href="#" class="text-danger"
                                            onclick="confirmDelete(<?php echo $amenity['id']; ?>, '<?php echo htmlspecialchars(addslashes($amenity['nome'])); ?>')"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir">
                                            <i class="material-symbols-rounded">delete</i>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
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
    // Função para abrir e preparar o modal de edição
    function openEditModal(id, name) {
        // Limpa erros antigos antes de abrir
        document.getElementById('edit-error-container').innerHTML = '';

        // Preenche os campos do formulário no modal com os dados atuais
        document.getElementById('editAmenityId').value = id;
        document.getElementById('editAmenityName').value = name;
        document.getElementById('editForm').action = '/RoomFlow/Comodidades/Update/' + id;

        // Foca no campo de nome e remove o label flutuante se necessário
        const inputField = document.getElementById('editAmenityName');
        inputField.focus();

        // Mostra o modal
        var myModal = new bootstrap.Modal(document.getElementById('editAmenityModal'));
        myModal.show();
    }

    // Função de delete com SweetAlert
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Tem certeza?',
            html: `Você está prestes a excluir a comodidade <strong>${name}</strong>.`,
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

    // Lida com o alerta de notificação que some sozinho
    document.addEventListener('DOMContentLoaded', function () {
        const alertMessage = document.getElementById('alertMessage');
        if (alertMessage) {
            setTimeout(() => {
                new bootstrap.Alert(alertMessage).close();
            }, 5000);
        }

        // **CORREÇÃO AQUI**: Verifica se houve um erro de atualização e reabre o modal
        <?php if (isset($form_action) && $form_action === 'update' && isset($errors['nome'])): ?>
            // Pega o nome que o usuário tentou salvar (que veio do controller na variável $data)
            const failedName = '<?php echo addslashes($data['nome']); ?>';
            
            // Reabre o modal com os dados que falharam
            openEditModal(<?php echo $update_error_id; ?>, failedName);

            // Cria e insere a mensagem de erro dentro do modal
            const errorContainer = document.getElementById('edit-error-container');
            const errorMessage = '<?php echo $errors['nome']; ?>';
            errorContainer.innerHTML = `
                <div class="d-flex align-items-center text-danger text-sm mt-1">
                    <i class="material-symbols-rounded align-middle me-1" style="font-size: 1.1rem;">error</i>
                    <span>${errorMessage}</span>
                </div>`;
        <?php endif; ?>
    });
</script>