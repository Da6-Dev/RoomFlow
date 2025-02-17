<?php
session_start();

$_SESSION['caminhoPai'] = "Hospedes";
$_SESSION['pagina'] = "Cadastrar";
include_once('../Includes/navbar.php');
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
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white" role="alert" id="alertMessage">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>
    <form action="../App/Controllers/cadHospedes.php" method="post">

        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Telefone</label>
                    <input type="tel" class="form-control" name="telefone" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control" name="cpf" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Rua</label>
                    <input type="text" class="form-control" name="rua" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Cidade</label>
                    <input type="text" class="form-control" name="cidade" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control" name="estado" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">Número</label>
                    <input type="number" class="form-control" name="numero" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3">
                    <label class="form-label">CEP</label>
                    <input type="text" class="form-control" name="cep" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-group input-group-static my-3">
                <label>Data Nascimento</label>
                <input type="date" class="form-control" name="dataNasc" required>
            </div>
        </div>
        <div class="row">
            <span>Adicionar preferência</span>
            <div>
                <button type="button" id="prefadd" class="btn btn-outline-primary">+</button>
                <button type="button" id="prefless" class="btn btn-outline-primary">-</button>
            </div>
            <div class="row" id="preferences"></div>
        </div>
        <div class="row">
            <div><button type="submit" class="btn btn-primary">Cadastrar</button></div>
        </div>

    </form>
</div>

<?php include_once('../Includes/footer.php') ?>