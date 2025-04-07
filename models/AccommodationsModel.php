<?php

class AccommodationsModel extends Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }

    public function listar()
    {
        $query = "SELECT * FROM acomodacoes";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAccommodationById($id)
    {
        $query = "SELECT * FROM acomodacoes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAccommodationByName($name)
    {
        $query = "SELECT * FROM acomodacoes WHERE tipo = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAccommodationByNumber($number)
    {
        $query = "SELECT * FROM acomodacoes WHERE numero = :number";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Inserir a acomodação na tabela accommodations
        $query = "INSERT INTO acomodacoes (tipo, numero, descricao, status, capacidade, preco, minimo_noites, camas_casal, camas_solteiro, hora_checkin, hora_checkout) VALUES (:tipo, :numero, :descricao, :status, :capacidade, :preco, :minimo_noites, :camas_casal, :camas_solteiro, :check_in_time, :check_out_time)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':numero', $data['numero']);
        $stmt->bindParam(':descricao', $data['descricao']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':capacidade', $data['capacidade'], PDO::PARAM_INT);
        $stmt->bindParam(':preco', $data['preco'],);
        $stmt->bindParam(':minimo_noites', $data['minimo_noites'], PDO::PARAM_INT);
        $stmt->bindParam(':camas_casal', $data['camas_casal'], PDO::PARAM_INT);
        $stmt->bindParam(':camas_solteiro', $data['camas_solteiro'], PDO::PARAM_INT);
        $stmt->bindParam(':check_in_time', $data['check_in_time']);
        $stmt->bindParam(':check_out_time', $data['check_out_time']);


        if ($stmt->execute()) {
            // Obtém o ID da acomodação recém inserida
            $accommodationId = $this->pdo->lastInsertId();

            // Inserir as amenidades na tabela acomodacao_amenidade
            foreach ($data['amenidades'] as $amenity) {
                $query = "INSERT INTO amenidades_acomodacoes (id_acomodacoes, id_amenidades) VALUES (:acomodacao_id, :amenidade_id)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':acomodacao_id', $accommodationId, PDO::PARAM_INT);
                $stmt->bindParam(':amenidade_id', $amenity, PDO::PARAM_INT);
                echo $stmt->execute();
            }
            return true; // Retorna true se a inserção for bem-sucedida
        } else {
            return false; // Retorna false se a inserção falhar
        }
    }

    public function update($id, $data)
    {
        try {
            // Iniciar uma transação
            $this->pdo->beginTransaction();

            // Atualizar os dados da acomodação na tabela accommodations
            $query = "UPDATE acomodacoes SET tipo = :tipo, numero = :numero, descricao = :descricao, status = :status, capacidade = :capacidade, preco = :preco, minimo_noites = :minimo_noites, camas_casal = :camas_casal, camas_solteiro = :camas_solteiro, hora_checkin = :check_in_time, hora_checkout = :check_out_time WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':tipo', $data['tipo']);
            $stmt->bindParam(':numero', $data['numero']);
            $stmt->bindParam(':descricao', $data['descricao']);
            $stmt->bindParam(':status', $data['status']);
            $stmt->bindParam(':capacidade', $data['capacidade'], PDO::PARAM_INT);
            $stmt->bindParam(':preco', $data['preco']);
            $stmt->bindParam(':minimo_noites', $data['minimo_noites'], PDO::PARAM_INT);
            $stmt->bindParam(':camas_casal', $data['camas_casal'], PDO::PARAM_INT);
            $stmt->bindParam(':camas_solteiro', $data['camas_solteiro'], PDO::PARAM_INT);
            $stmt->bindParam(':check_in_time', $data['check_in_time']);
            $stmt->bindParam(':check_out_time', $data['check_out_time']);
            $stmt->execute();

            // Remover as amenidades antigas da tabela acomodacao_amenidade
            $query = "DELETE FROM amenidades_acomodacoes WHERE id_acomodacoes = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Inserir as novas amenidades na tabela acomodacao_amenidade
            foreach ($data['amenidades'] as $amenityId) {
                $query = "INSERT INTO amenidades_acomodacoes (id_acomodacoes, id_amenidades) VALUES (:acomodacao_id, :amenidade_id)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':amenidade_id', $amenityId, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Confirmar a transação
            $this->pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfazer a transação
            $this->pdo->rollBack();
            error_log("Erro ao atualizar acomodação: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            // Inicia uma transação
            $this->pdo->beginTransaction();

            // Remover as amenidades relacionadas
            $query = "DELETE FROM amenidades_acomodacoes WHERE id_acomodacoes = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Remover a acomodação principal
            $query = "DELETE FROM acomodacoes WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Finaliza a transação
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            // Reverte se der erro
            $this->pdo->rollBack();
            error_log("Erro ao deletar acomodação: " . $e->getMessage());
            return false;
        }
    }
}
