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
        $query = "SELECT * FROM acomodacoes WHERE name = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAccommodationByNumber($number)
    {
        $query = "SELECT * FROM accommodations WHERE number = :number";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data, $amenities)
    {
        try {
            // Iniciar uma transação
            $this->pdo->beginTransaction();

            // Inserir a acomodação na tabela accommodations
            $query = "INSERT INTO acomodacoes (name, description, min_nights, double_beds, single_beds, check_in_time, check_out_time) VALUES (:name, :description, :min_nights, :double_beds, :single_beds, :check_in_time, :check_out_time)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':min_nights', $data['min_nights'], PDO::PARAM_INT);
            $stmt->bindParam(':double_beds', $data['double_beds'], PDO::PARAM_INT);
            $stmt->bindParam(':single_beds', $data['single_beds'], PDO::PARAM_INT);
            $stmt->bindParam(':check_in_time', $data['check_in_time']);
            $stmt->bindParam(':check_out_time', $data['check_out_time']);
            $stmt->execute();

            // Obtém o ID da acomodação recém inserida
            $accommodationId = $this->pdo->lastInsertId();

            // Inserir as amenidades na tabela acomodacao_amenidade
            foreach ($amenities as $amenityId) {
                $query = "INSERT INTO acomodacao_amenidade (acomodacao_id, amenidade_id) VALUES (:acomodacao_id, :amenidade_id)";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':acomodacao_id', $accommodationId, PDO::PARAM_INT);
                $stmt->bindParam(':amenidade_id', $amenityId, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Confirmar a transação
            $this->pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfazer a transação
            $this->pdo->rollBack();
            error_log("Erro ao criar acomodação: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            // Iniciar uma transação
            $this->pdo->beginTransaction();

            // Atualizar os dados da acomodação na tabela accommodations
            $query = "UPDATE accommodations SET name = :name, description = :description, min_nights = :min_nights, double_beds = :double_beds, single_beds = :single_beds, check_in_time = :check_in_time, check_out_time = :check_out_time WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':min_nights', $data['min_nights'], PDO::PARAM_INT);
            $stmt->bindParam(':double_beds', $data['double_beds'], PDO::PARAM_INT);
            $stmt->bindParam(':single_beds', $data['single_beds'], PDO::PARAM_INT);
            $stmt->bindParam(':check_in_time', $data['check_in_time']);
            $stmt->bindParam(':check_out_time', $data['check_out_time']);
            $stmt->execute();

            // Remover as amenidades antigas da tabela acomodacao_amenidade
            $query = "DELETE FROM acomodacao_amenidade WHERE acomodacao_id = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Inserir as novas amenidades na tabela acomodacao_amenidade
            foreach ($data['amenities'] as $amenityId) {
                $query = "INSERT INTO acomodacao_amenidade (acomodacao_id, amenidade_id) VALUES (:acomodacao_id, :amenidade_id)";
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
            // Iniciar uma transação
            $this->pdo->beginTransaction();

            // Remover as amenidades da tabela acomodacao_amenidade
            $query = "DELETE FROM acomodacao_amenidade WHERE acomodacao_id = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Remover a acomodação da tabela accommodations
            $query = "DELETE FROM accommodations WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Confirmar a transação
            $this->pdo->commit();

            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfazer a transação
            $this->pdo->rollBack();
            error_log("Erro ao excluir acomodação: " . $e->getMessage());
            return false;
        }
    }
}

?>