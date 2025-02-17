<?php
// Incluindo a conexão com o banco de dados
include_once('../../Database/connection.php');

// Verificando se o ID do hóspede foi passado
if (isset($_POST['id'])) {
    $idHospede = $_POST['id'];

    // Iniciando uma transação para garantir que as duas exclusões ocorram com sucesso
    $conn->begin_transaction();

    try {
        // Deletando as preferências do hóspede
        $sqlDeletePreferences = "DELETE FROM preferencias_hospedes WHERE id_hospede=?";
        $stmtDeletePreferences = $conn->prepare($sqlDeletePreferences);
        $stmtDeletePreferences->bind_param("i", $idHospede);
        $stmtDeletePreferences->execute();

        // Deletando o hóspede
        $sqlDeleteHospede = "DELETE FROM hospedes WHERE id=?";
        $stmtDeleteHospede = $conn->prepare($sqlDeleteHospede);
        $stmtDeleteHospede->bind_param("i", $idHospede);
        $stmtDeleteHospede->execute();

        // Commitando a transação
        $conn->commit();

        // Redirecionando para a página de lista de hóspedes com sucesso
        header("Location: ../../Public/listarhospedes.php?success=2");
    } catch (Exception $e) {
        // Em caso de erro, realizando o rollback da transação
        $conn->rollback();

        // Exibindo erro
        echo "Erro ao excluir o hóspede: " . $e->getMessage();
    }

    // Fechando as declarações e a conexão
    $stmtDeletePreferences->close();
    $stmtDeleteHospede->close();
} else {
    echo "ID do hóspede não fornecido.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>