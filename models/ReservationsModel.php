<?php

class ReservationsModel extends Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }

    public function hospedesGetAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM hospedes WHERE active = 1");
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function acomodacoesGetDisponiveis()
    {
        $stmt = $this->pdo->query("SELECT * FROM acomodacoes");

        $stmt->execute();

        $acomodacoesDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $acomodacoesDisponiveis;
    }

    public function create($data)
    {

        $query = "SELECT preco FROM acomodacoes WHERE id = :acomodacao";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':acomodacao', $data['acomodacao']);
        $stmt->execute();
        $acomodacao = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($acomodacao) {
            $preco = $acomodacao['preco'];
            $data_checkin = new DateTime($data['data_checkin']);
            $data_checkout = new DateTime($data['data_checkout']);
            $diferenca = $data_checkin->diff($data_checkout)->days;
            $valor_total = $preco * $diferenca;
        } else {
            return false; // Acomodação não encontrada
        }

        $query = "INSERT INTO reservas (id_hospede, id_acomodacao, data_checkin, data_checkout, status, valor_total, metodo_pagamento, observacoes) VALUES (:hospede, :acomodacao, :data_checkin, :data_checkout, :status, :valor_total, :metodo_pagamento, :observacoes)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':hospede', $data['hospede']);
        $stmt->bindParam(':acomodacao', $data['acomodacao']);
        $stmt->bindParam(':data_checkin', $data['data_checkin']);
        $stmt->bindParam(':data_checkout', $data['data_checkout']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':metodo_pagamento', $data['metodo_pagamento']);
        $stmt->bindParam(':observacoes', $data['observacoes']);
        $stmt->bindParam(':valor_total', $valor_total, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllReservations()
    {
        $stmt = $this->pdo->query("SELECT * FROM reservas WHERE status != 'cancelada'");
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function getNameAccommodationById($id_acomodacao)
    {
        $stmt = $this->pdo->prepare("SELECT tipo FROM acomodacoes WHERE id = :id_acomodacao");
        $stmt->bindParam(':id_acomodacao', $id_acomodacao);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getNameGuestById($id_hospede)
    {
        $stmt = $this->pdo->prepare("SELECT nome FROM hospedes WHERE id = :id_hospede");
        $stmt->bindParam(':id_hospede', $id_hospede);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getReservationsDate()
    {
        $stmt = $this->pdo->prepare('SELECT id_acomodacao, data_checkin, data_checkout FROM reservas');
        $stmt->execute();
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $datasReservadas = [];

        foreach ($reservas as $row) {
            $id = $row['id_acomodacao'];
            $checkin = new DateTime($row['data_checkin']);
            $checkout = new DateTime($row['data_checkout']);

            // Vamos iterar por todas as datas entre checkin e checkout
            $interval = new DateInterval('P1D'); // 1 dia
            $period = new DatePeriod($checkin, $interval, $checkout);

            foreach ($period as $date) {
                $dataStr = $date->format('Y-m-d');

                // Inicializa o array se ainda não existir
                if (!isset($datasReservadas[$id])) {
                    $datasReservadas[$id] = [];
                }

                // Adiciona a data se ainda não estiver no array (evita duplicata)
                if (!in_array($dataStr, $datasReservadas[$id])) {
                    $datasReservadas[$id][] = $dataStr;
                }
            }
        }
        return $datasReservadas;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM reservas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getReservationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservas WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $query = "UPDATE reservas SET id_hospede = :hospede, id_acomodacao = :acomodacao, data_checkin = :data_checkin, data_checkout = :data_checkout, status = :status, metodo_pagamento = :metodo_pagamento, observacoes = :observacoes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':hospede', $data['hospede']);
        $stmt->bindParam(':acomodacao', $data['acomodacao']);
        $stmt->bindParam(':data_checkin', $data['data_checkin']);
        $stmt->bindParam(':data_checkout', $data['data_checkout']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':metodo_pagamento', $data['metodo_pagamento']);
        $stmt->bindParam(':observacoes', $data['observacoes']);
        return $stmt->execute();
    }

    public function arquivarReservasExpiradas()
    {
        // Pega as reservas onde a data de checkout é anterior a hoje.
        $stmt = $this->pdo->prepare("SELECT * FROM reservas WHERE data_checkout < CURDATE()");
        $stmt->execute();
        $reservasExpiradas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($reservasExpiradas)) {
            return ['status' => 'success', 'message' => 'Nenhuma reserva expirada para arquivar.', 'arquivadas' => 0];
        }

        $arquivadasComSucesso = 0;
        $erros = [];

        foreach ($reservasExpiradas as $reserva) {
            try {
                // Inicia uma transação para garantir a integridade dos dados
                $this->pdo->beginTransaction();

                // 1. Insere a reserva na tabela de histórico com a nova estrutura
                $insertStmt = $this->pdo->prepare(
                    "INSERT INTO historico_reservas (id_reserva, detalhes, id_hospede, id_acomodacao, data_checkin, data_checkout, status, valor_total, metodo_pagamento, observacoes, data_reserva)
                     VALUES (:id_reserva, :detalhes, :id_hospede, :id_acomodacao, :data_checkin, :data_checkout, :status, :valor_total, :metodo_pagamento, :observacoes, :data_reserva)"
                );

                $insertStmt->execute([
                    ':id_reserva'       => $reserva['id'], // O ID original da reserva
                    ':detalhes'         => 'Reserva arquivada automaticamente por expiração.',
                    ':id_hospede'       => $reserva['id_hospede'],
                    ':id_acomodacao'    => $reserva['id_acomodacao'],
                    ':data_checkin'     => $reserva['data_checkin'],
                    ':data_checkout'    => $reserva['data_checkout'],
                    ':status'           => 'finalizada', // Define o status como 'finalizada'
                    ':valor_total'      => $reserva['valor_total'],
                    ':metodo_pagamento' => $reserva['metodo_pagamento'],
                    ':observacoes'      => $reserva['observacoes'],
                    ':data_reserva'     => $reserva['data_reserva']
                ]);

                // 2. Deleta a reserva da tabela principal
                $deleteStmt = $this->pdo->prepare("DELETE FROM reservas WHERE id = :id");
                $deleteStmt->execute([':id' => $reserva['id']]);

                // Se tudo deu certo, confirma a transação
                $this->pdo->commit();
                $arquivadasComSucesso++;
            } catch (PDOException $e) {
                // Se algo der errado, desfaz a transação e registra o erro
                $this->pdo->rollBack();
                $erros[] = "Erro ao arquivar reserva ID " . $reserva['id'] . ": " . $e->getMessage();
            }
        }

        if (empty($erros)) {
            return ['status' => 'success', 'message' => "Operação concluída.", 'arquivadas' => $arquivadasComSucesso];
        } else {
            return ['status' => 'error', 'message' => "Ocorreram erros durante o arquivamento.", 'details' => $erros];
        }
    }

    public function getCheckinsHoje()
    {
        $query = "SELECT r.id, h.nome as nome_hospede, a.tipo as nome_acomodacao, a.numero 
                  FROM reservas r
                  JOIN hospedes h ON r.id_hospede = h.id
                  JOIN acomodacoes a ON r.id_acomodacao = a.id
                  WHERE r.data_checkin = CURDATE()";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCheckoutsHoje()
    {
        $query = "SELECT r.id, h.nome as nome_hospede, a.tipo as nome_acomodacao, a.numero 
                  FROM reservas r
                  JOIN hospedes h ON r.id_hospede = h.id
                  JOIN acomodacoes a ON r.id_acomodacao = a.id
                  WHERE r.data_checkout = CURDATE()";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservasPendentes()
    {
        $query = "SELECT r.id, h.nome as nome_hospede, a.tipo as nome_acomodacao, r.data_checkin 
                  FROM reservas r
                  JOIN hospedes h ON r.id_hospede = h.id
                  JOIN acomodacoes a ON r.id_acomodacao = a.id
                  WHERE r.status = 'pendente' ORDER BY r.data_checkin ASC"; //
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReceitaNoPeriodo($dataInicio, $dataFim)
    {
        $query = "SELECT SUM(valor_total) as total FROM historico_reservas WHERE status = 'finalizada' AND data_registro BETWEEN :dataInicio AND :dataFim";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dataInicio' => $dataInicio, ':dataFim' => $dataFim]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    public function getContagemReservasFinalizadasNoPeriodo($dataInicio, $dataFim)
    {
        $query = "SELECT COUNT(id) as total FROM historico_reservas WHERE status = 'finalizada' AND data_registro BETWEEN :dataInicio AND :dataFim";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dataInicio' => $dataInicio, ':dataFim' => $dataFim]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    public function getHistoricoReservas()
    {
        $query = "SELECT 
                        hr.id_reserva,
                        hr.data_registro as data_arquivamento,
                        hr.detalhes,
                        h.nome as nome_hospede,
                        a.tipo as nome_acomodacao,
                        a.numero as numero_acomodacao,
                        hr.data_checkin,
                        hr.data_checkout,
                        hr.valor_total,
                        hr.status
                  FROM historico_reservas hr
                  LEFT JOIN hospedes h ON hr.id_hospede = h.id
                  LEFT JOIN acomodacoes a ON hr.id_acomodacao = a.id
                  ORDER BY hr.data_registro DESC"; // Ordena pelas mais recentes

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
