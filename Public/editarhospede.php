<?php
session_start();

$_SESSION['caminhoPai'] = "Hospedes";
$_SESSION['pagina'] = "Editar";
// Inclua o arquivo de conexão com o banco de dados
include('../Database/connection.php');
include_once("../Includes/navbar.php");

// Verifique se o ID do hóspede foi passado pela URL
if (isset($_GET['id'])) {
    $hospede_id = $_GET['id'];

    // Consulta para obter os dados do hóspede pelo ID
    $sql = "SELECT * FROM hospedes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hospede_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifique se o hóspede existe
    if ($result->num_rows > 0) {
        $hospede = $result->fetch_assoc();
    } else {
        echo "Hóspede não encontrado!";
        exit;
    }

    $id_hospede = $hospede['id'];

    // Consulta para obter as preferências do hóspede
    $sql_preferencias = "SELECT * FROM preferencias_hospedes WHERE id_hospede = $id_hospede";
    $result_preferencias = $conn->query($sql_preferencias);
    $preferencias = [];

    while ($row = $result_preferencias->fetch_assoc()) {
        $preferencias[] = $row['descricao'];
    }

    // Fechar a conexão
    $conn->close();
} else {
    echo "ID do hóspede não fornecido!";
    exit;
}

?>

<div class="container-fluid py-2">
    <form action="../App/Controllers/atualizaHospede.php" method="post">
        <input type="hidden" name="id" value="<?php echo $hospede['id']; ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($hospede['nome']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($hospede['email']); ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Telefone</label>
                    <input type="tel" class="form-control" name="telefone" value="<?php echo htmlspecialchars($hospede['telefone']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">CPF</label>
                    <input type="text" class="form-control" name="cpf" value="<?php echo htmlspecialchars($hospede['documento']); ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Rua</label>
                    <input type="text" class="form-control" name="rua" value="<?php echo htmlspecialchars($hospede['rua']); ?>" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Cidade</label>
                    <input type="text" class="form-control" name="cidade" value="<?php echo htmlspecialchars($hospede['cidade']); ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control" name="estado" value="<?php echo htmlspecialchars($hospede['estado']); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">Número</label>
                    <input type="number" class="form-control" name="numero" value="<?php echo htmlspecialchars($hospede['numero']); ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-outline my-3 is-filled">
                    <label class="form-label">CEP</label>
                    <input type="text" class="form-control" name="cep" value="<?php echo htmlspecialchars($hospede['cep']); ?>" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-group input-group-static my-3 is-filled">
                <label>Data Nascimento</label>
                <input type="date" class="form-control" name="dataNasc" value="<?php echo htmlspecialchars($hospede['data_nascimento']); ?>" required>
            </div>
        </div>
        <div class="row">
            <span>Adicionar preferência</span>
            <div>
                <button type="button" id="prefadd" class="btn btn-outline-primary">+</button>
                <button type="button" id="prefless" class="btn btn-outline-primary">-</button>
            </div>
            <div id="preferences">
                <?php
                // Carregar preferências existentes do banco de dados
                foreach ($preferencias as $index => $preferencia) {
                    echo '<div class="input-group input-group-outline my-3 is-filled" id="pref' . ($index + 1) . '">
                            <label class="form-label" style="z-index:-3">Preferência ' . ($index + 1) . '</label>
                            <input type="text" class="form-control" name="pref' . ($index + 1) . '" value="' . htmlspecialchars($preferencia) . '" required></div>';
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
            </div>
        </div>
    </form>
</div>

<?php include_once('../Includes/footer.php'); ?>