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
        $stmt = $this->pdo->query("SELECT * FROM amenidades");
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function getAmenityById($id)
    {
        try {
            if (!$this->pdo) {
                throw new Exception("Erro: conexão com o banco de dados não está ativa.");
            }

            // Garante que o ID seja um número válido
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id) {
                throw new Exception("ID inválido fornecido.");
            }

            $stmt = $this->pdo->prepare("SELECT * FROM amenidades WHERE id = ?");
            $stmt->execute([$id]);

            $amenity = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$amenity) {
                return null; // Retorna null se nenhuma comodidade for encontrada
            }

            return $amenity;
        } catch (Exception $e) {
            error_log($e->getMessage()); // Registra o erro no log do servidor
            return null;
        }
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO amenidades (nome) VALUES (:name)");

        // Bind dos parâmetros usando PDO
        $stmt->bindValue(':name', $data['nome']);

        // Verificando se a inserção foi bem-sucedida
        return $stmt->execute();
    }

    public function update($data)
    {
        $stmt = $this->pdo->prepare("UPDATE amenidades SET nome = :name WHERE id = :id");

        // Bind dos parâmetros usando PDO
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['nome']);

        // Verificando se a atualização foi bem-sucedida
        return $stmt->execute();
    }

    public function delete($id)
    {
        try {
            // Iniciar uma transação
            $this->pdo->beginTransaction();

            // Desativar a comodidade na tabela amenities
            $stmt = $this->pdo->prepare("DELETE FROM amenidades WHERE id = :id");

            // Bind do parâmetro usando PDO
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Executar a exclusão
            $stmt->execute();

            // Confirmar a transação
            $this->pdo->commit();

            // Retorna true em caso de sucesso
            return true;
        } catch (PDOException $e) {
            // Em caso de erro, desfazer a transação
            $this->pdo->rollBack();
            error_log("Erro ao excluir comodidade: " . $e->getMessage());
            return false;
        }
    }


public function getAmenityByName($name)
{
    try {
        if (!$this->pdo) {
            throw new Exception("Erro: conexão com o banco de dados não está ativa.");
        }

        $stmt = $this->pdo->prepare("SELECT * FROM amenidades WHERE nome = ?");
        $stmt->execute([$name]);

        $amenity = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$amenity) {
            return null; // Retorna null se nenhuma comodidade for encontrada
        }

        return $amenity;
    } catch (Exception $e) {
        error_log($e->getMessage()); // Registra o erro no log do servidor
        return null;
    }
}

public function getAmenitiesAccommodations($id)
{
try{
    if (!$this->pdo) {
        throw new Exception("Erro: conexão com o banco de dados não está ativa.");
    }

    $stmt = $this->pdo->prepare("SELECT id_amenidades FROM amenidades_acomodacoes WHERE id_acomodacoes = ?");
    $stmt->execute([$id]);

    $amenity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $ids_amenidades = array_column($amenity, 'id_amenidades');

    if(!$ids_amenidades){
        return null;
    }

    return $ids_amenidades;
}catch(Exception $e){
    error_log($e->getMessage());
    return null;

}
}
}


?>