<?php
// Incluindo a conexão com o banco de dados
include_once('../../Database/connection.php');

// Obtendo os dados do formulário
$idHospede = $_POST['id']; // O ID do hóspede é passado no formulário
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

// Atualizando as informações do hóspede
$sql = "UPDATE hospedes SET nome=?, email=?, telefone=?, documento=?, rua=?, cidade=?, estado=?, numero=?, cep=?, data_nascimento=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssi", $nome, $email, $telefone, $cpf, $rua, $cidade, $estado, $numero, $cep, $dataNasc, $idHospede);

if ($stmt->execute()) {
    // Atualização dos dados do hóspede bem-sucedida, agora atualizando as preferências
    // Apagar as preferências atuais antes de adicionar as novas
    $sqlDeletePreferences = "DELETE FROM preferencias_hospedes WHERE id_hospede=?";
    $stmtDelete = $conn->prepare($sqlDeletePreferences);
    $stmtDelete->bind_param("i", $idHospede);
    $stmtDelete->execute();

    // Adicionando as novas preferências
    for ($i = 1; $i <= count($_POST) - 11; $i++) { // Contando o número de preferências enviadas
        if (isset($_POST["pref$i"])) {
            $descricao = $_POST["pref$i"];
            $sqlInsertPreference = "INSERT INTO preferencias_hospedes (id_hospede, descricao) VALUES (?, ?)";
            $stmtInsert = $conn->prepare($sqlInsertPreference);
            $stmtInsert->bind_param("is", $idHospede, $descricao);
            $stmtInsert->execute();
        }
    }

    // Redirecionando para a lista de hóspedes após o sucesso
    header("Location: ../../public/listarhospedes.php?msg=success_update");
} else {
    // Caso ocorra algum erro ao atualizar o hóspede
    header("Location: ../../public/listarhospedes.php?msg=error_update");
}

// Fechar a conexão
$stmt->close();
$stmtDelete->close();
$conn->close();
?>
