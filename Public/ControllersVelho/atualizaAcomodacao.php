<?php
// Inclua o arquivo de conexão com o banco de dados
include('../../Database/connection.php');

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receba os dados do formulário
    $id = $_POST['id'];
    $tipo = $_POST['tipo'];
    $numero = $_POST['numero'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $capacidade = $_POST['capacidade'];
    $preco = $_POST['preco'];
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $minimo_noites = $_POST['minimo_noites'];
    $camas_casal = $_POST['camas_casal'];
    $camas_solteiro = $_POST['camas_solteiro'];
    $amenidades = isset($_POST['amenidades']) ? $_POST['amenidades'] : [];

    // Atualizar os dados da acomodação
    $stmt = $conn->prepare("UPDATE acomodacoes SET tipo = ?, numero = ?, descricao = ?, status = ?, capacidade = ?, preco = ?, check_in_time = ?, check_out_time = ?, minimo_noites = ?, camas_casal = ?, camas_solteiro = ? WHERE id = ?");
    $stmt->bind_param('ssssidsisiii', $tipo, $numero, $descricao, $status, $capacidade, $preco, $check_in_time, $check_out_time, $minimo_noites, $camas_casal, $camas_solteiro, $id);

    if ($stmt->execute()) {
        // Atualizar as amenidades da acomodação
        $conn->query("DELETE FROM acomodacao_amenidade WHERE acomodacao_id = $id");
        
        foreach ($amenidades as $amenidade_id) {
            $stmtAmenidade = $conn->prepare("INSERT INTO acomodacao_amenidade (acomodacao_id, amenidade_id) VALUES (?, ?)");
            $stmtAmenidade->bind_param('ii', $id, $amenidade_id);
            $stmtAmenidade->execute();
            $stmtAmenidade->close();
        }

        // Redireciona para a página de edição com uma mensagem de sucesso
        header("Location: ../../Public/acomodacoeseditar.php?id=$id&msg=success_update");
        exit;
    } else {
        // Redireciona para a página de edição com uma mensagem de erro
        header("Location: ../../Public/acomodacoeseditar.php?id=$id&msg=error_update");
        exit;
    }

    // Feche o statement e a conexão
    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
