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
        $alertMessage = 'Erro ao cadastrar a reserva.';
        break;
    case 'success_update':
        $alertClass = 'alert-success';
        $alertMessage = 'Dados atualizados com sucesso!';
        break;
    case 'error_update':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao atualizar os dados da reserva.';
        break;
    case 'success_delete':
        $alertClass = 'alert-success';
        $alertMessage = 'Reserva excluída com sucesso!';
        break;
    case 'error_delete':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao excluir a reserva.';
        break;
    default:
        $alertClass = '';
        $alertMessage = '';
        break;
}
/**
 * Função para retornar a classe do badge e o texto traduzido com base no status.
 * @param string $status O status da reserva.
 * @return array Um array com a 'class' e o 'text'.
 */
function getStatusBadge($status) {
    switch (strtolower($status)) {
        case 'pendente':
            return ['class' => 'bg-gradient-warning', 'text' => 'Pendente'];
        case 'confirmada':
            return ['class' => 'bg-gradient-success', 'text' => 'Confirmada'];
        case 'cancelada':
            return ['class' => 'bg-gradient-danger', 'text' => 'Cancelada'];
        case 'finalizada':
            return ['class' => 'bg-gradient-secondary', 'text' => 'Finalizada'];
        default:
            return ['class' => 'bg-gradient-light', 'text' => ucfirst($status)];
    }
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <?php if ($alertMessage): ?>
            <div class="alert <?php echo $alertClass; ?> text-white alert-dismissible fade show" role="alert" id="alertMessage">
                <span class="alert-icon align-middle"><i class="material-symbols-rounded">check_circle</i></span>
                <span class="alert-text"><strong>Sucesso!</strong> <?php echo $alertMessage; ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php endif; ?>

            <div class="card my-4">
                <div class="card-header p-3 border-bottom">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                            <h6 class="mb-0">Gerenciamento de Reservas</h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn bg-gradient-dark mb-0" href="/RoomFlow/Reservas/Cadastrar/">
                                <i class="material-symbols-rounded">add_circle</i>&nbsp;&nbsp;Nova Reserva
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-striped table-hover align-items-center mb-0" id="reservasTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Acomodação</th>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">Hóspede</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Período</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($Reservas)): ?>
                                    <?php foreach ($Reservas as $reserva): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-md"><?php echo htmlspecialchars($reserva['acomodacao']); ?></h6>
                                                        <p class="text-sm text-secondary mb-0">R$ <?php echo number_format($reserva['valor_total'], 2, ',', '.'); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0"><?php echo htmlspecialchars($reserva['hospede']); ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <p class="text-sm font-weight-bold mb-0">
                                                    <?php echo (new DateTime($reserva['data_checkin']))->format('d/m/Y'); ?>
                                                </p>
                                                <p class="text-sm text-secondary mb-0">
                                                    <?php echo (new DateTime($reserva['data_checkout']))->format('d/m/Y'); ?>
                                                </p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php $badge = getStatusBadge($reserva['status']); ?>
                                                <span class="badge badge-sm <?php echo $badge['class']; ?>"><?php echo $badge['text']; ?></span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <form action="/RoomFlow/Reservas/Deletar" method="POST" id="form-delete-<?php echo $reserva['id']; ?>" class="d-none">
                                                    <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                                </form>

                                                <a href="/RoomFlow/Reservas/<?php echo $reserva['id']; ?>" class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Ver / Editar Reserva">
                                                    <i class="material-symbols-rounded">edit</i>
                                                </a>
                                                <a href="#" class="text-danger ms-2" onclick="confirmDelete(<?php echo $reserva['id']; ?>, 'reserva para <?php echo htmlspecialchars(addslashes($reserva['hospede'])); ?>')" data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir Reserva">
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
include __DIR__ . '/Layout.php';
?>

<script>

</script>