<?php 

ob_start();

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
        $alertMessage = 'Erro ao cadastrar a acomodação.';
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
    <form action="/Roomflox/Comodidades/<?php echo $data['id']; ?>" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 <?php echo !empty($errors['tipo']) || !empty($_POST['tipo']) ? 'is-filled' : ''; ?>">
                    <label class="form-label">Tipo</label>
                    <input type="text" class="form-control" name="tipo" value="<?php echo $_POST['nome'] ?? $data['nome'] ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 <?php echo !empty($errors['numero']) || !empty($_POST['numero']) ? 'is-filled' : ''; ?>">
                    <label class="form-label">Número</label>
                    <input type="text" class="form-control" name="numero" value="<?php echo $_POST['numero'] ?? $data['numero'] ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group input-group-outline my-3 <?php echo !empty($errors['descricao']) || !empty($_POST['descricao']) ? 'is-filled' : ''; ?>">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="descricao" value="<?php echo $_POST['descricao'] ?? $data['descricao'] ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-static mb-4">
                    <label for="status" class="ms-0">Status</label>
                    <select class="form-control" name="status" id="status" required>
                        <option value="disponível">Disponível</option>
                        <option value="ocupado">Ocupado</option>
                        <option value="manutenção">Manutenção</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 <?php echo !empty($errors['capacidade']) || !empty($_POST['capacidade']) ? 'is-filled' : ''; ?>">
                    <label class="form-label">Capacidade</label>
                    <input type="number" class="form-control" name="capacidade" value="<?php echo $_POST['capacidade'] ?? $data['capacidade'] ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 <?php echo !empty($errors['preco']) || !empty($_POST['preco']) ? 'is-filled' : ''; ?>">
                    <label class="form-label">Preço</label>
                    <input type="text" class="form-control" name="preco" value="<?php echo $_POST['preco'] ?? $data['preco'] ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="my-3">
                    <label class="form-label">Amenidades</label>
                    <?php
                    // Aqui, buscamos as amenidades da tabela 'amenidades'
                    $query = "SELECT id, nome FROM amenidades";
                    $result = mysqli_query($conn, $query);
                    $counter = 0;  // Contador para controlar as linhas
                    echo '<div class="row">'; // Garante que comece com uma linha
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="amenidades[]" value="<?php echo $row['id']; ?>" id="amenidade_<?php echo $row['id']; ?>">
                                <label class="form-check-label" for="amenidade_<?php echo $row['id']; ?>">
                                    <?php echo $row['nome']; ?>
                                </label>
                            </div>
                        </div>
                    <?php
                        $counter++;
                        // A cada 3 checkboxes, fecha e abre uma nova linha
                        if ($counter % 3 == 0) {
                            echo '</div><div class="row">';
                        }
                    }
                    // Fecha a última linha caso não tenha fechado ainda
                    if ($counter % 3 != 0) {
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-static my-3 <?php echo !empty($errors['minimo_noites']) || !empty($_POST['minimo_noites']) ? 'is-filled' : ''; ?>">
                    <label for="minimo_noites" class="ms-0">Mínimo de Noites</label>
                    <input type="number" class="form-control" name="minimo_noites" value="<?php echo $_POST['minimo_noite'] ?? $data['minimo_noite'] ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-static my-3 <?php echo !empty($errors['camas_casal']) || !empty($_POST['camas_casal']) ? 'is-filled' : ''; ?>">
                    <label for="camas_casal" class="ms-0">Camas de Casal</label>
                    <input type="number" class="form-control" name="camas_casal" value="<?php echo $_POST['camas_casal'] ?? $data['camas_casal'] ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-static my-3 <?php echo !empty($errors['camas_solteiro']) || !empty($_POST['camas_solteiro']) ? 'is-filled' : ''; ?>">
                    <label for="camas_solteiro" class="ms-0">Camas de Solteiro</label>
                    <input type="number" class="form-control" name="camas_solteiro" value="<?php echo $_POST['camas_solteiro'] ?? $data['camas_solteiro'] ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-static my-3 <?php echo !empty($errors['check_in_time']) || !empty($_POST['check_in_time']) ? 'is-filled' : ''; ?>">
                    <label for="check_in_time" class="ms-0">Hora de Check-in</label>
                    <input type="time" class="form-control" name="check_in_time" id="check_in_time" value="<?php echo $_POST['check_in_time'] ?? $data['hora_checkin'] ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-static my-3 <?php echo !empty($errors['check_out_time']) || !empty($_POST['check_out_time']) ? 'is-filled' : ''; ?>">
                    <label for="check_out_time" class="ms-0">Hora de Check-out</label>
                    <input type="time" class="form-control" name="check_out_time" id="check_out_time" value="<?php echo $_POST['check_out_time'] ?? $data['hora_checkout'] ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div><button type="submit" class="btn btn-primary">Cadastrar</button></div>
        </div>
    </form>
</div>

<?php 

$content = ob_get_clean();
include __DIR__ . '/Layout.php'

?>