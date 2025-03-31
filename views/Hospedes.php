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

?>

<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <?php if ($alertMessage): ?>
                        <div class="alert <?php echo $alertClass; ?> text-white" role="alert" id="alertMessage">
                            <?php echo $alertMessage; ?>
                        </div>
                    <?php endif; ?>
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Hospedes</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hospede</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telefone</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CPF</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($Guests != []): ?>
                                    <?php foreach ($Guests as $guest): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="/RoomFlow/Public/assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($guest['nome']); ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?php echo htmlspecialchars($guest['email']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($guest['email']); ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success"><?php echo htmlspecialchars($guest['telefone']); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?php echo htmlspecialchars($guest['documento']); ?></span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="/RoomFlow/Hospedes/<?php echo $guest['id']; ?>" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                                    Editar
                                                </a>
                                                <form action="/RoomFlow/Hospedes/Deletar" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $guest['id']; ?>">
                                                    <button type="submit" class="text-danger font-weight-bold text-xs" onclick="return confirm('Tem certeza que deseja excluir este hóspede?');">
                                                        Deletar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Nenhum hóspede encontrado.</td>
                                    </tr>
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

$content = ob_get_clean(); // Captura o conteúdo da página
include __DIR__ . '/layout.php'; // Inclui o layout base

?>