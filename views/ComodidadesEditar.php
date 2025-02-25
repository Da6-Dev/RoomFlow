<?php 

ob_start();

// Verifica se há uma mensagem na URL e define a classe e o texto do alerta com base nela
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$alertClass = '';
$alertMessage = '';

switch ($msg) {
    case 'success_update':
        $alertClass = 'alert-success';
        $alertMessage = 'Amenidade atualizada com sucesso!';
        break;
    case 'error_update':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao atualizar a amenidade.';
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
                        <h6 class="text-white text-capitalize ps-3">Editar Amenidade</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <form action="/Roomflox/Comodidades/<?php echo $data['id']; ?>" method="post">
                        <div class="row p-3">
                            <div class="col-md-6">
                                <div class="input-group input-group-outline my-3 <?php echo !empty($errors['nome']) || !empty($data['nome']) ? 'is-filled' : ''; ?>">
                                    <label class="form-label">Nome da Amenidade</label>
                                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($data['nome'] ?? ''); ?>" required>
                                </div>
                                <?php if (isset($errors['nome'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errors['nome']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row p-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn bg-gradient-dark">Atualizar</button>
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