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
        $alertMessage = 'Hóspede excluído com sucesso!';
        break;
    case 'error_delete':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao excluir o hóspede.';
        break;
    default:
        $alertClass = '';
        $alertMessage = '';
        break;
}
/**
 * Função para formatar o CPF para exibição.
 * @param string $cpf O CPF sem formatação.
 * @return string O CPF formatado (ou o original se for inválido).
 */
function formatarCPF($cpf) {
    $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpfLimpo) != 11) {
        return $cpf; // Retorna o original se não for um CPF válido
    }
    return substr($cpfLimpo, 0, 3) . '.' . substr($cpfLimpo, 3, 3) . '.' . substr($cpfLimpo, 6, 3) . '-' . substr($cpfLimpo, 9, 2);
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <?php if ($alertMessage): ?>
                <div class="alert <?php echo $alertClass; ?> text-white alert-dismissible fade show" role="alert" id="alertMessage">
                    <span class="alert-icon align-middle">
                      <i class="material-symbols-rounded">check_circle</i>
                    </span>
                    <span class="alert-text"><strong>Sucesso!</strong> <?php echo $alertMessage; ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card my-4">
                <div class="card-header p-3 border-bottom">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                            <h6 class="mb-0">Hóspedes Cadastrados</h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="/RoomFlow/Hospedes/Cadastrar/">
                                <i class="material-symbols-rounded">add</i>&nbsp;&nbsp;Adicionar Hóspede
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-striped table-hover align-items-center mb-0" id="hospedesTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Hóspede</th>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">Contato</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">CPF</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($Guests)): ?>
                                    <?php foreach ($Guests as $guest): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="/RoomFlow/Public/uploads/hospedes/<?php echo htmlspecialchars($guest['imagem'] ?? 'default.png'); ?>" class="avatar avatar-md me-3 border-radius-lg" alt="foto do hospede" style="object-fit: cover;">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-md"><?php echo htmlspecialchars($guest['nome']); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($guest['email']); ?></p>
                                                <p class="text-sm text-secondary mb-0"><?php echo htmlspecialchars($guest['telefone']); ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-bold"><?php echo formatarCPF($guest['documento']); ?></span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <form action="/RoomFlow/Hospedes/Deletar" method="POST" id="form-delete-<?php echo $guest['id']; ?>" class="d-none">
                                                    <input type="hidden" name="id" value="<?php echo $guest['id']; ?>">
                                                </form>

                                                <a href="/RoomFlow/Hospedes/<?php echo $guest['id']; ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Hóspede">
                                                    <i class="material-symbols-rounded">edit</i>
                                                </a>
                                                
                                                <a href="#" class="text-danger ms-2" onclick="confirmDelete(<?php echo $guest['id']; ?>, '<?php echo htmlspecialchars(addslashes($guest['nome'])); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir Hóspede">
                                                    <i class="material-symbols-rounded">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
// Inclui o layout principal
include __DIR__ . '/layout.php';
?>

<script>
// Função para o alerta de confirmação de exclusão com SweetAlert2
function confirmDelete(guestId, guestName) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `Você está prestes a excluir o hóspede <strong>${guestName}</strong>.<br>Esta ação não pode ser desfeita!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Se confirmado, submete o formulário de exclusão correspondente
            document.getElementById('form-delete-' + guestId).submit();
        }
    });
}


// Espera o documento carregar para inicializar os plugins
document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializa o DataTable na nossa tabela
    const hospedesTable = new simpleDatatables.DataTable("#hospedesTable", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        labels: {
            placeholder: "Buscar hóspede...",
            perPage: "hóspedes por página",
            noRows: "Nenhum hóspede encontrado",
            info: "Mostrando {start} a {end} de {rows} hóspedes"
        }
    });

    // Faz a mensagem de alerta desaparecer após 5 segundos
    const alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
        setTimeout(() => {
            new bootstrap.Alert(alertMessage).close();
        }, 5000); // 5000 milissegundos = 5 segundos
    }
});
</script>