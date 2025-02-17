<?php
// Inclua o arquivo de conexão com o banco de dados
include('../../Database/connection.php');

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receba os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $rua = $_POST['rua'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $numero = $_POST['numero'];
    $cep = $_POST['cep'];
    $dataNasc = $_POST['dataNasc'];

    // Insira o hóspede na tabela hospedes
    $stmt = $conn->prepare("INSERT INTO hospedes (nome, email, telefone, documento, rua, cidade, estado, numero, cep, data_nascimento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssssss', $nome, $email, $telefone, $cpf, $rua, $cidade, $estado, $numero, $cep, $dataNasc);
    
    if ($stmt->execute()) {
        // Obtém o ID do hóspede recém inserido
        $idHospede = $stmt->insert_id;

        // Insere as preferências se existirem
        $npref = 1;
        while (isset($_POST["pref{$npref}"])) {
            $preferencia = $_POST["pref{$npref}"];
            
            // Insira as preferências na tabela preferencias_hospedes
            $stmtPref = $conn->prepare("INSERT INTO preferencias_hospedes (id_hospede, descricao) VALUES (?, ?)");
            $stmtPref->bind_param('is', $idHospede, $preferencia);
            $stmtPref->execute();
            
            $npref++;
        }

        // Redireciona para cadastroclientes.php com uma mensagem de sucesso
        header("Location: ../../Public/cadastrohospedes.php?msg=success_create");
        exit;
    } else {
        // Redireciona para cadastroclientes.php com uma mensagem de erro
        header("Location: ../../Public/cadastrohospedes.php?msg=error_create");
        exit;
    }

} else {
    echo "Método de requisição inválido.";
}
?>
