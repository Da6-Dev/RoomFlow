<?php
// Inclua o arquivo de conexão com o banco de dados
include('../../Database/connection.php');

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receba os dados do formulário
    $tipo = $_POST['tipo'];
    $numero = $_POST['numero'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $capacidade = $_POST['capacidade'];
    $preco = $_POST['preco'];
    $check_in_time = $_POST['check_in_time'];
    $check_out_time = $_POST['check_out_time'];
    $minimo_noites = $_POST['minimo_noites']; // Novo campo de mínimo de noites
    $camas_solteiro = $_POST['camas_solteiro'];
    $camas_casal = $_POST['camas_casal'];
    $amenidades = isset($_POST['amenidades']) ? $_POST['amenidades'] : [];

    // Insira a acomodação na tabela acomodacoes
    $stmt = $conn->prepare("INSERT INTO acomodacoes (tipo, numero, descricao, status, capacidade, preco, check_in_time, check_out_time, minimo_noites, camas_solteiro, camas_casal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Ajuste para corresponder ao tipo correto de dados (strings e inteiros)
    $stmt->bind_param('sssssdsdiii', $tipo, $numero, $descricao, $status, $capacidade, $preco, $check_in_time, $check_out_time, $minimo_noites, $camas_solteiro, $camas_casal);

    if ($stmt->execute()) {
        // Obtém o ID da acomodação recém inserida
        $idAcomodacao = $stmt->insert_id;

        // Insere as amenidades na tabela acomodacao_amenidade
        foreach ($amenidades as $amenidade_id) {
            $stmtAmenidade = $conn->prepare("INSERT INTO acomodacao_amenidade (acomodacao_id, amenidade_id) VALUES (?, ?)");
            $stmtAmenidade->bind_param('ii', $idAcomodacao, $amenidade_id);
            $stmtAmenidade->execute();
        }

        // Redireciona para cadastracomo.php com uma mensagem de sucesso
        header("Location: ../../Public/acomodacoescadastro.php?msg=success_create");
        exit;
    } else {
        // Redireciona para cadastracomo.php com uma mensagem de erro
        header("Location: ../../Public/acomodacoescadastro.php?msg=error_create");
        exit;
    }

    // Feche o statement e a conexão
    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
