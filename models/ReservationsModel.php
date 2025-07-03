<?php

class ReservationsModel extends Database
{
    /**
     * @var PDO A instância da conexão com o banco de dados.
     */
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }

    /**
     * Busca todos os hóspedes ativos para serem listados em formulários de reserva.
     * @return array
     */
    public function hospedesGetAll()
    {
        $stmt = $this->pdo->query("SELECT id, nome FROM hospedes WHERE active = 1 ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca todas as acomodações para serem listadas em formulários de reserva.
     * Nota: Este método não filtra por disponibilidade de data, apenas lista todas as acomodações existentes.
     * @return array
     */
    public function acomodacoesGetDisponiveis()
    {
        $stmt = $this->pdo->query("SELECT id, tipo, numero, preco FROM acomodacoes ORDER BY tipo, numero ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria uma nova reserva no banco de dados.
     * @param array $data Dados da reserva.
     * @return bool
     */
    public function create($data)
    {
        // Calcula o valor total da reserva com base no preço da diária e no número de dias.
        $stmtPreco = $this->pdo->prepare("SELECT preco FROM acomodacoes WHERE id = :id_acomodacao");
        $stmtPreco->execute([':id_acomodacao' => $data['acomodacao']]);
        $acomodacao = $stmtPreco->fetch(PDO::FETCH_ASSOC);

        if (!$acomodacao) {
            return false; // Acomodação não encontrada.
        }

        $data_checkin = new DateTime($data['data_checkin']);
        $data_checkout = new DateTime($data['data_checkout']);
        $diferenca = $data_checkin->diff($data_checkout)->days;
        $valor_total = $acomodacao['preco'] * $diferenca;

        $query = "INSERT INTO reservas (id_hospede, id_acomodacao, data_checkin, data_checkout, status, valor_total, metodo_pagamento, observacoes) 
                  VALUES (:hospede, :acomodacao, :data_checkin, :data_checkout, :status, :valor_total, :metodo_pagamento, :observacoes)";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            ':hospede' => $data['hospede'],
            ':acomodacao' => $data['acomodacao'],
            ':data_checkin' => $data['data_checkin'],
            ':data_checkout' => $data['data_checkout'],
            ':status' => $data['status'],
            ':valor_total' => $valor_total,
            ':metodo_pagamento' => $data['metodo_pagamento'],
            ':observacoes' => $data['observacoes']
        ]);
    }

    /**
     * Atualiza uma reserva existente.
     * @param array $data Dados da reserva, incluindo o 'id'.
     * @return bool
     */
    public function update($data)
    {
        // **CORREÇÃO DE BUG**: Recalcula o valor total em caso de alteração de datas ou acomodação.
        $stmtPreco = $this->pdo->prepare("SELECT preco FROM acomodacoes WHERE id = :id_acomodacao");
        $stmtPreco->execute([':id_acomodacao' => $data['acomodacao']]);
        $acomodacao = $stmtPreco->fetch(PDO::FETCH_ASSOC);

        if (!$acomodacao) {
            return false; // Acomodação não encontrada.
        }

        $data_checkin = new DateTime($data['data_checkin']);
        $data_checkout = new DateTime($data['data_checkout']);
        $diferenca = $data_checkin->diff($data_checkout)->days;
        $valor_total = $acomodacao['preco'] * $diferenca;

        $query = "UPDATE reservas SET id_hospede = :hospede, id_acomodacao = :acomodacao, data_checkin = :data_checkin, data_checkout = :data_checkout, status = :status, valor_total = :valor_total, metodo_pagamento = :metodo_pagamento, observacoes = :observacoes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            ':id' => $data['id'],
            ':hospede' => $data['hospede'],
            ':acomodacao' => $data['acomodacao'],
            ':data_checkin' => $data['data_checkin'],
            ':data_checkout' => $data['data_checkout'],
            ':status' => $data['status'],
            ':valor_total' => $valor_total,
            ':metodo_pagamento' => $data['metodo_pagamento'],
            ':observacoes' => $data['observacoes']
        ]);
    }

    /**
     * Busca todas as reservas ativas (não canceladas).
     * @return array
     */
    public function getAllReservations()
    {
        $stmt = $this->pdo->query("SELECT * FROM reservas WHERE status != 'cancelada'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma reserva específica pelo ID.
     * @param int $id
     * @return array|null
     */
    public function getReservationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Deleta uma reserva permanentemente.
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM reservas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // --- MÉTODOS DE CONSULTA E RELATÓRIOS ---

    /**
     * Obtém o nome/tipo de uma acomodação pelo ID.
     * @param int $id_acomodacao
     * @return string|false
     */
    public function getNameAccommodationById($id_acomodacao)
    {
        $stmt = $this->pdo->prepare("SELECT tipo FROM acomodacoes WHERE id = :id_acomodacao");
        $stmt->execute([':id_acomodacao' => $id_acomodacao]);
        return $stmt->fetchColumn();
    }

    /**
     * Obtém o nome de um hóspede pelo ID.
     * @param int $id_hospede
     * @return string|false
     */
    public function getNameGuestById($id_hospede)
    {
        $stmt = $this->pdo->prepare("SELECT nome FROM hospedes WHERE id = :id_hospede");
        $stmt->execute([':id_hospede' => $id_hospede]);
        return $stmt->fetchColumn();
    }

    /**
     * Cria um array de datas reservadas para cada acomodação, ideal para calendários.
     * @return array
     */
    public function getReservationsDate()
    {
        $stmt = $this->pdo->prepare('SELECT id_acomodacao, data_checkin, data_checkout FROM reservas');
        $stmt->execute();
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $datasReservadas = [];
        $intervalo = new DateInterval('P1D'); // Intervalo de 1 dia

        foreach ($reservas as $reserva) {
            $id = $reserva['id_acomodacao'];
            $periodo = new DatePeriod(new DateTime($reserva['data_checkin']), $intervalo, new DateTime($reserva['data_checkout']));

            if (!isset($datasReservadas[$id])) {
                $datasReservadas[$id] = [];
            }

            foreach ($periodo as $data) {
                $datasReservadas[$id][] = $data->format('Y-m-d');
            }
        }
        return $datasReservadas;
    }

    /**
     * Busca check-ins agendados para a data atual.
     * @return array
     */
    public function getCheckinsHoje()
    {
        $query = "SELECT r.id, h.nome as nome_hospede, a.tipo as nome_acomodacao, a.numero 
                  FROM reservas r
                  JOIN hospedes h ON r.id_hospede = h.id
                  JOIN acomodacoes a ON r.id_acomodacao = a.id
                  WHERE r.data_checkin = CURDATE() AND r.status != 'cancelada'";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca check-outs agendados para a data atual.
     * @return array
     */
    public function getCheckoutsHoje()
    {
        $query = "SELECT r.id, h.nome as nome_hospede, a.tipo as nome_acomodacao, a.numero 
                  FROM reservas r
                  JOIN hospedes h ON r.id_hospede = h.id
                  JOIN acomodacoes a ON r.id_acomodacao = a.id
                  WHERE r.data_checkout = CURDATE() AND r.status != 'cancelada'";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca reservas com status 'pendente', ordenadas pela data de check-in.
     * @return array
     */
    public function getReservasPendentes()
    {
        $query = "SELECT r.id, h.nome as nome_hospede, a.tipo as nome_acomodacao, r.data_checkin 
                  FROM reservas r
                  JOIN hospedes h ON r.id_hospede = h.id
                  JOIN acomodacoes a ON r.id_acomodacao = a.id
                  WHERE r.status = 'pendente' ORDER BY r.data_checkin ASC";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca o histórico de reservas arquivadas.
     * @return array
     */
    public function getHistoricoReservas()
    {
        $query = "SELECT 
                        hr.id_reserva, hr.data_registro as data_arquivamento, hr.detalhes,
                        h.nome as nome_hospede, a.tipo as nome_acomodacao, a.numero as numero_acomodacao,
                        hr.data_checkin, hr.data_checkout, hr.valor_total, hr.status
                  FROM historico_reservas hr
                  LEFT JOIN hospedes h ON hr.id_hospede = h.id
                  LEFT JOIN acomodacoes a ON hr.id_acomodacao = a.id
                  ORDER BY hr.data_registro DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Arquiva reservas expiradas, associando a ação a um funcionário.
     * @param int $idFuncionario O ID do funcionário (ou sistema) que está realizando a operação.
     */
    public function arquivarReservasExpiradas()
    {
        // 1. A consulta SQL para buscar as reservas corretas (com JOIN e hora específica)
        $sqlSelect = "
        SELECT
            reservas.*
        FROM
            reservas
        JOIN
            acomodacoes ON reservas.id_acomodacao = acomodacoes.id
        WHERE
            TIMESTAMP(reservas.data_checkout, acomodacoes.hora_checkout) < NOW()
    ";

        $stmtSelect = $this->pdo->prepare($sqlSelect);
        $stmtSelect->execute();
        $reservasExpiradas = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

        if (empty($reservasExpiradas)) {
            return ['status' => 'success', 'message' => 'Nenhuma reserva expirada para arquivar.', 'arquivadas' => 0];
        }

        $arquivadasComSucesso = 0;
        $erros = [];

        // 2. Prepara as queries de INSERÇÃO e DELEÇÃO (sem o campo id_funcionario)
        $insertSql = "INSERT INTO historico_reservas 
                    (id_reserva, id_hospede, id_acomodacao, data_checkin, data_checkout, status, valor_total, metodo_pagamento, observacoes, data_reserva, detalhes)
                  VALUES 
                    (:id_reserva, :id_hospede, :id_acomodacao, :data_checkin, :data_checkout, :status, :valor_total, :metodo_pagamento, :observacoes, :data_reserva, :detalhes)";

        $deleteSql = "DELETE FROM reservas WHERE id = :id";

        $insertStmt = $this->pdo->prepare($insertSql);
        $deleteStmt = $this->pdo->prepare($deleteSql);

        // 3. Loop para arquivar cada reserva
        foreach ($reservasExpiradas as $reserva) {
            try {
                $this->pdo->beginTransaction();

                // Executa a inserção no histórico (sem o campo id_funcionario)
                $insertStmt->execute([
                    ':id_reserva'       => $reserva['id'],
                    ':id_hospede'       => $reserva['id_hospede'],
                    ':id_acomodacao'    => $reserva['id_acomodacao'],
                    ':data_checkin'     => $reserva['data_checkin'],
                    ':data_checkout'    => $reserva['data_checkout'],
                    ':status'           => 'finalizada',
                    ':valor_total'      => $reserva['valor_total'],
                    ':metodo_pagamento' => $reserva['metodo_pagamento'],
                    ':observacoes'      => $reserva['observacoes'],
                    ':data_reserva'     => $reserva['data_reserva'],
                    ':detalhes'         => 'Reserva arquivada automaticamente por expiração.'
                ]);

                $deleteStmt->execute([':id' => $reserva['id']]);

                $this->pdo->commit();
                $arquivadasComSucesso++;
            } catch (PDOException $e) {
                $this->pdo->rollBack();
                $erros[] = "Erro ao arquivar reserva ID " . $reserva['id'] . ": " . $e->getMessage();
            }
        }

        // 4. Retorna o resultado da operação
        if (empty($erros)) {
            return ['status' => 'success', 'message' => "Operação concluída com sucesso.", 'arquivadas' => $arquivadasComSucesso];
        } else {
            return ['status' => 'error', 'message' => "Ocorreram erros durante o arquivamento.", 'arquivadas' => $arquivadasComSucesso, 'details' => $erros];
        }
    }

    /**
     * Calcula a receita total de reservas finalizadas em um determinado período.
     * @param string $dataInicio Data de início no formato 'YYYY-MM-DD'.
     * @param string $dataFim Data de fim no formato 'YYYY-MM-DD'.
     * @return float Retorna o valor total da receita.
     */
    public function getReceitaNoPeriodo($dataInicio, $dataFim)
    {
        $query = "SELECT SUM(valor_total) as total FROM historico_reservas WHERE status = 'finalizada' AND data_registro BETWEEN :dataInicio AND :dataFim";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dataInicio' => $dataInicio, ':dataFim' => $dataFim]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Garante que o retorno seja um float.
        return (float) ($resultado['total'] ?? 0);
    }

    /**
     * Conta o número de reservas finalizadas em um determinado período.
     * @param string $dataInicio Data de início no formato 'YYYY-MM-DD'.
     * @param string $dataFim Data de fim no formato 'YYYY-MM-DD'.
     * @return int Retorna o número total de reservas.
     */
    public function getContagemReservasFinalizadasNoPeriodo($dataInicio, $dataFim)
    {
        $query = "SELECT COUNT(id) as total FROM historico_reservas WHERE status = 'finalizada' AND data_registro BETWEEN :dataInicio AND :dataFim";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':dataInicio' => $dataInicio, ':dataFim' => $dataFim]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Garante que o retorno seja um inteiro.
        return (int) ($resultado['total'] ?? 0);
    }

    /**
     * Atualiza o status das acomodações para 'ocupado' ou 'disponivel'
     * com base nas datas das reservas ativas.
     * É executado em duas etapas para garantir a consistência.
     */
    public function atualizarStatusAcomodacoes()
    {
        try {
            $this->pdo->beginTransaction();

            // Etapa 1: Liberar acomodações que estão ocupadas mas já passaram do checkout
            $sqlLiberar = "
                UPDATE acomodacoes a
                LEFT JOIN reservas r ON a.id = r.id_acomodacao
                    AND r.status IN ('confirmada', 'check-in realizado')
                    AND NOW() >= CONCAT(r.data_checkin, ' ', a.hora_checkin)
                    AND NOW() < CONCAT(r.data_checkout, ' ', a.hora_checkout)
                SET a.status = 'disponivel'
                WHERE a.status = 'ocupado'
                AND r.id IS NULL;
            ";
            $this->pdo->exec($sqlLiberar);

            // Etapa 2: Ocupar acomodações que têm reservas ativas neste momento
            $sqlOcupar = "
                UPDATE acomodacoes a
                JOIN reservas r ON a.id = r.id_acomodacao
                SET a.status = 'ocupado'
                WHERE r.status IN ('confirmada', 'check-in realizado')
                AND NOW() >= CONCAT(r.data_checkin, ' ', a.hora_checkin)
                AND NOW() < CONCAT(r.data_checkout, ' ', a.hora_checkout)
                AND a.status NOT IN ('ocupado', 'manutencao');
            ";
            $this->pdo->exec($sqlOcupar);

            $this->pdo->commit();

            return ['status' => 'success', 'message' => 'Status das acomodações atualizado com base na hora.'];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Erro ao atualizar status de acomodações: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Erro ao atualizar status das acomodações.'];
        }
    }
}
