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
                        <h6 class="text-white text-capitalize ps-3">Comodidades</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Comodidade</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($Amenities != []): ?>
                                    <?php foreach ($Amenities as $amenity): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($amenity['nome']); ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="/Roomflox/Comodidades/<?php echo $amenity['id']; ?>" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit amenity">
                                                    Editar
                                                </a>
                                                <form action="/Roomflox/Comodidades/Deletar" method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $amenity['id']; ?>">
                                                    <button type="submit" class="text-danger font-weight-bold text-xs" onclick="return confirm('Tem certeza que deseja excluir esta amenidade?');">
                                                        Deletar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhuma amenidade encontrada.</td>
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

$content = ob_get_clean();
include __DIR__ . '/Layout.php'

?>