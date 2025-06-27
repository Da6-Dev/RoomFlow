<?php

class AmenitiesModel extends Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }

    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM amenidades ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAmenityById($id)
    {
        try {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id) {
                return null;
            }

            $stmt = $this->pdo->prepare("SELECT * FROM amenidades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO amenidades (nome) VALUES (:name)");
        $stmt->bindValue(':name', $data['nome']);
        return $stmt->execute();
    }

    public function update($data)
    {
        $stmt = $this->pdo->prepare("UPDATE amenidades SET nome = :name WHERE id = :id");
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['nome']);
        return $stmt->execute();
    }

    /**
     * Exclui uma amenidade de forma segura.
     * O uso de transações (beginTransaction/commit/rollBack) é uma excelente prática
     * para garantir que o banco de dados permaneça consistente.
     */
    public function delete($id)
    {
        try {
            $this->pdo->beginTransaction();

            $stmtAcomodacoes = $this->pdo->prepare("DELETE FROM amenidades_acomodacoes WHERE id_amenidades = :id");
            $stmtAcomodacoes->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtAcomodacoes->execute();

            $stmt = $this->pdo->prepare("DELETE FROM amenidades WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erro ao excluir amenidade: " . $e->getMessage());
            return false;
        }
    }

    public function getAmenityByName($name)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM amenidades WHERE nome = :name");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
    
    public function getAmenitiesAccommodations($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_amenidades FROM amenidades_acomodacoes WHERE id_acomodacoes = :id_acomodacoes");
            $stmt->bindParam(':id_acomodacoes', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        } catch(Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}