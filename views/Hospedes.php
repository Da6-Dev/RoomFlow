<?php
ob_start();

// -- LÓGICA DE PREPARAÇÃO DA VIEW --

// 1. Função auxiliar para os alertas de notificação
function get_alert_details($msg_code) {
    $alerts = [
        'success_create' => ['class' => 'alert-success', 'message' => 'Cadastro realizado com sucesso!'],
        'error_create'   => ['class' => 'alert-danger', 'message' => 'Erro ao cadastrar o hóspede.'],
        'success_update' => ['class' => 'alert-success', 'message' => 'Dados atualizados com sucesso!'],
        'error_update'   => ['class' => 'alert-danger', 'message' => 'Erro ao atualizar os dados do hóspede.'],
        'success_delete' => ['class' => 'alert-success', 'message' => 'Hóspede excluído com sucesso!'],
        'error_delete'   => ['class' => 'alert-danger', 'message' => 'Erro ao excluir o hóspede.'],
        'not_found'      => ['class' => 'alert-warning', 'message' => 'Hóspede não encontrado.']
    ];
    return $alerts[$msg_code] ?? null;
}

// 2. Função auxiliar para formatar CPF
function formatarCPF($cpf) {
    $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpfLimpo) != 11) {
        return $cpf;
    }
    return substr($cpfLimpo, 0, 3) . '.' . substr($cpfLimpo, 3, 3) . '.' . substr($cpfLimpo, 6, 3) . '-' . substr($cpfLimpo, 9, 2);
}

// 3. Preparação de variáveis para a view
$alert = get_alert_details($_GET['msg'] ?? '');

?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <?php if ($alert): ?>
                <div class="alert <?php echo $alert['class']; ?> text-white alert-dismissible fade show" role="alert" id="alertMessage">
                    <span class="alert-icon align-middle"><i class="material-symbols-rounded">check_circle</i></span>
                    <span class="alert-text"><strong><?php echo $alert['class'] === 'alert-success' ? 'Sucesso!' : 'Atenção!'; ?></strong> <?php echo $alert['message']; ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            <?php endif; ?>

            <div class="card my-4">
                <div class="card-header p-3 border-bottom">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                            <h6 class="mb-0">Hóspedes Cadastrados</h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="/RoomFlow/Dashboard/Hospedes/Cadastrar/">
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
                                <?php if (!empty($Guests)): foreach ($Guests as $guest): ?>
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
                                            <form action="/RoomFlow/Dashboard/Hospedes/Deletar" method="POST" id="form-delete-<?php echo $guest['id']; ?>" class="d-none">
                                                <input type="hidden" name="id" value="<?php echo $guest['id']; ?>">
                                            </form>
                                            <a href="/RoomFlow/Dashboard/Hospedes/<?php echo $guest['id']; ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Hóspede">
                                                <i class="material-symbols-rounded">edit</i>
                                            </a>
                                            <a href="#" class="text-danger ms-2" onclick="confirmDelete(<?php echo $guest['id']; ?>, '<?php echo htmlspecialchars(addslashes($guest['nome'])); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir Hóspede">
                                                <i class="material-symbols-rounded">delete</i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
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
include __DIR__ . '/layout.php';
?>