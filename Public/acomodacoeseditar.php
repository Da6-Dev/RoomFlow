<?php
session_start();

$_SESSION['caminhoPai'] = "Acomodações";
$_SESSION['pagina'] = "Editar";
include('../Database/connection.php');
include_once('../Includes/navbar.php');

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$alertClass = '';
$alertMessage = '';

switch ($msg) {
    case 'success_update':
        $alertClass = 'alert-success';
        $alertMessage = 'Acomodação atualizada com sucesso!';
        break;
    case 'error_update':
        $alertClass = 'alert-danger';
        $alertMessage = 'Erro ao atualizar a acomodação.';
        break;
    default:
        $alertClass = '';
        $alertMessage = '';
        break;
}

if (!isset($_GET['id'])) {
    echo "ID da acomodação não fornecido!";
    exit;
}

$acomodacao_id = $_GET['id'];
$sql = "SELECT * FROM acomodacoes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $acomodacao_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Acomodação não encontrada!";
    exit;
}
$acomodacao = $result->fetch_assoc();
$stmt->close();

// Buscar todas as amenidades disponíveis
$sqlAmenidades = "SELECT * FROM amenidades";
$resultAmenidades = $conn->query($sqlAmenidades);

// Buscar as amenidades associadas a esta acomodação
$sqlAcomodacaoAmenidades = "SELECT amenidade_id FROM acomodacao_amenidade WHERE acomodacao_id = ?";
$stmt = $conn->prepare($sqlAcomodacaoAmenidades);
$stmt->bind_param("i", $acomodacao_id);
$stmt->execute();
$resultAcomodacaoAmenidades = $stmt->get_result();
$amenidadesSelecionadas = [];
while ($row = $resultAcomodacaoAmenidades->fetch_assoc()) {
    $amenidadesSelecionadas[] = $row['amenidade_id'];
}
$stmt->close();
?>

<div class="container-fluid py-2">
    <?php if ($alertMessage): ?>
        <div class="alert <?php echo $alertClass; ?> text-white" role="alert">
            <?php echo $alertMessage; ?>
        </div>
    <?php endif; ?>
    <form action="../App/Controllers/atualizaAcomodacao.php" method="post">
        <input type="hidden" name="id" value="<?php echo $acomodacao['id']; ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Tipo</label>
                    <input type="text" class="form-control" name="tipo" value="<?php echo htmlspecialchars($acomodacao['tipo']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Número</label>
                    <input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($acomodacao['numero']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="descricao" value="<?php echo htmlspecialchars($acomodacao['descricao']); ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-static mb-4">
                    <label for="status" class="ms-0">Status</label>
                    <select class="form-control" name="status" id="status" required>
                        <option value="disponível" <?php echo ($acomodacao['status'] == 'disponível') ? 'selected' : ''; ?>>Disponível</option>
                        <option value="ocupado" <?php echo ($acomodacao['status'] == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                        <option value="manutenção" <?php echo ($acomodacao['status'] == 'manutenção') ? 'selected' : ''; ?>>Em Manutenção</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Capacidade</label>
                    <input type="number" class="form-control" name="capacidade" value="<?php echo htmlspecialchars($acomodacao['capacidade']); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Preço</label>
                    <input type="text" class="form-control" name="preco" value="<?php echo htmlspecialchars($acomodacao['preco']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Check-in</label>
                    <input type="time" class="form-control" name="check_in_time" value="<?php echo htmlspecialchars($acomodacao['check_in_time']); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Check-out</label>
                    <input type="time" class="form-control" name="check_out_time" value="<?php echo htmlspecialchars($acomodacao['check_out_time']); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Mínimo de Noites</label>
                    <input type="number" class="form-control" name="minimo_noites" value="<?php echo htmlspecialchars($acomodacao['minimo_noites']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Camas de Casal</label>
                    <input type="number" class="form-control" name="camas_casal" value="<?php echo htmlspecialchars($acomodacao['camas_casal']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Camas de Solteiro</label>
                    <input type="number" class="form-control" name="camas_solteiro" value="<?php echo htmlspecialchars($acomodacao['camas_solteiro']); ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <label class="form-label">Amenidades</label>
                <div class="row">
                    <?php while ($amenidade = $resultAmenidades->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="amenidades[]" value="<?php echo $amenidade['id']; ?>"
                                    <?php echo in_array($amenidade['id'], $amenidadesSelecionadas) ? 'checked' : ''; ?>>
                                <label class="form-check-label"> <?php echo htmlspecialchars($amenidade['nome']); ?> </label>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </div>
    </form>
</div>

<?php include_once('../Includes/footer.php'); ?>