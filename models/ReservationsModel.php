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
        $stmt = $this->pdo->query("SELECT * FROM acomodacoes WHERE status = 'disponivel'AND id NOT IN (SELECT id_acomodacao FROM reservas WHERE status IN ('pendente', 'confirmada', 'checkin') AND CURDATE() BETWEEN data_checkin AND data_checkout)");

        $stmt->execute();

        $acomodacoesDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $acomodacoesDisponiveis;
    }
}
