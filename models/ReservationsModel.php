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

}
