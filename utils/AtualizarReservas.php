<?php
// Habilita exibição e detecção de erros
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "RoomFlow");
$conn->set_charset("utf8mb4");

try {
    // Início da transação
    $conn->begin_transaction();

    // Seleciona reservas expiradas
    $query = "SELECT * FROM reservas WHERE data_checkout < NOW() AND status != 'Expirada'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Itera pelas reservas e insere no histórico
        while ($row = $result->fetch_assoc()) {
            $id_reserva = (int) $row['id'];
            $detalhes = "Reserva expirada";
            $id_hospede = (int) $row['id_hospede'];
            $id_acomodacao = (int) $row['id_acomodacao'];
            $data_checkin = $row['data_checkin'];
            $data_checkout = $row['data_checkout'];
            $valor_total = (float) $row['valor_total'];
            $metodo_pagamento = $row['metodo_pagamento'] ?? '';
            $observacoes = $row['observacoes'] ?? '';



            // Prepara a inserção no histórico
            $conn->query( "INSERT INTO historico_reservas (id_reserva, detalhes, data_registro, id_hospede, id_acomodacao,data_checkin, data_checkout, valor_total, metodo_pagamento,observacoes, data_reserva) VALUES ($id_reserva, '$detalhes', NOW(), $id_hospede, $id_acomodacao,'$data_checkin', '$data_checkout', $valor_total, '$metodo_pagamento','$observacoes', NOW())");
            if ($conn->error) {
                throw new Exception("Erro ao inserir no histórico: " . $conn->error);
            }
            die();
        }

        $insertStmt->close();
        $result->free();

        echo "Reservas expiradas inseridas no histórico com sucesso.<br>";
    } else {
        echo "Nenhuma reserva expirada encontrada.<br>";
    }

    // Remove as reservas expiradas da tabela principal
    $deleteQuery = "DELETE FROM reservas WHERE data_checkout < NOW() AND status != 'Expirada'";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->execute();
    $deleteStmt->close();

    echo "Reservas expiradas deletadas com sucesso.<br>";

    // Finaliza a transação
    $conn->commit();
} catch (Exception $e) {
    // Em caso de erro, desfaz a transação
    $conn->rollback();
    echo "Erro ao processar reservas expiradas: " . $e->getMessage();
} finally {
    // Fecha a conexão
    $conn->close();
}
