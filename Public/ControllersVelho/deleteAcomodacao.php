<?php
// Incluindo a conexão com o banco de dados
include_once('../../Database/connection.php');

// Verificando se o ID da acomodação foi passado
if (isset($_POST['id'])) {
    $idAcomodacao = $_POST['id'];

    // Iniciando uma transação para garantir que todas as exclusões ocorram com sucesso
    $conn->begin_transaction();

    try {
        // Deletando as reservas associadas à acomodação
        $sqlDeleteReservas = "DELETE FROM reservas WHERE id_acomodacao=?";
        $stmtDeleteReservas = $conn->prepare($sqlDeleteReservas);
        $stmtDeleteReservas->bind_param("i", $idAcomodacao);
        $stmtDeleteReservas->execute();

        // Deletando a acomodação
        $sqlDeleteAcomodacao = "DELETE FROM acomodacoes WHERE id=?";
        $stmtDeleteAcomodacao = $conn->prepare($sqlDeleteAcomodacao);
        $stmtDeleteAcomodacao->bind_param("i", $idAcomodacao);
        $stmtDeleteAcomodacao->execute();

        // Commitando a transação
        $conn->commit();

        // Redirecionando para a página de lista de acomodações com sucesso
        header("Location: ../../Public/acomodacoeslistar.php?msg=success_delete");
    } catch (Exception $e) {
        // Em caso de erro, realizando o rollback da transação
        $conn->rollback();

        // Redirecionando com mensagem de erro
        header("Location: ../../Public/acomodacoeslistar.php?msg=error_delete");
    }

    // Fechando as declarações e a conexão
    $stmtDeleteReservas->close();
    $stmtDeleteAcomodacao->close();
} else {
    echo "ID da acomodação não fornecido.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
