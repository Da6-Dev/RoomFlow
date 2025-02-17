<?php
// Inclua o arquivo de conexão com o banco de dados
include('../../Database/connection.php');

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receba os dados do formulário
    $nome = $_POST['nome'];

    // Insira a amenidade na tabela amenidades
    $stmt = $conn->prepare("INSERT INTO amenidades (nome) VALUES (?)");
    $stmt->bind_param('s', $nome);
    
    if ($stmt->execute()) {
        // Redireciona para a página de cadastro com uma mensagem de sucesso
        header("Location: ../../Public/amenidadescadastro.php?msg=success_create");
        exit;
    } else {
        // Redireciona para a página de cadastro com uma mensagem de erro
        header("Location: ../../Public/amenidadescadastro.php?msg=error_create");
        exit;
    }

    // Feche o statement e a conexão
    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>