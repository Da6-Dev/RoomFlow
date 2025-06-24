<?php

class AmenitiesModel extends Database
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
     * Lista todas as amenidades cadastradas.
     * @return array
     */
    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM amenidades ORDER BY nome ASC");
        // A função fetchAll já retorna um array vazio se não houver resultados.
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma amenidade pelo seu ID.
     * @param int $id
     * @return array|null
     */
    public function getAmenityById($id)
    {
        try {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id) {
                return null; // Retorna nulo se o ID for inválido.
            }

            $stmt = $this->pdo->prepare("SELECT * FROM amenidades WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $amenity = $stmt->fetch(PDO::FETCH_ASSOC);

            return $amenity ?: null; // Retorna a amenidade ou nulo se não for encontrada.

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * Cria uma nova amenidade.
     * @param array $data Deve conter a chave 'nome'.
     * @return bool
     */
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO amenidades (nome) VALUES (:name)");
        $stmt->bindValue(':name', $data['nome']);

        return $stmt->execute();
    }

    /**
     * Atualiza o nome de uma amenidade existente.
     * @param array $data Deve conter as chaves 'id' e 'nome'.
     * @return bool
     */
    public function update($data)
    {
        $stmt = $this->pdo->prepare("UPDATE amenidades SET nome = :name WHERE id = :id");
        $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindValue(':name', $data['nome']);

        return $stmt->execute();
    }

    /**
     * Exclui uma amenidade de forma segura, removendo primeiro suas associações.
     * @param int $id O ID da amenidade a ser excluída.
     * @return bool
     */
    public function delete($id)
    {
        try {
            $this->pdo->beginTransaction(); // Inicia a transação.

            // 1. Remove as referências da amenidade na tabela de junção com acomodações.
            $stmtAcomodacoes = $this->pdo->prepare("DELETE FROM amenidades_acomodacoes WHERE id_amenidades = :id");
            $stmtAcomodacoes->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtAcomodacoes->execute();

            // 3. Finalmente, remove a amenidade principal.
            $stmt = $this->pdo->prepare("DELETE FROM amenidades WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit(); // Confirma as alterações se tudo deu certo.

            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack(); // Desfaz tudo em caso de erro.
            error_log("Erro ao excluir amenidade: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca uma amenidade pelo nome.
     * @param string $name
     * @return array|null
     */
    public function getAmenityByName($name)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM amenidades WHERE nome = :name");
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();

            $amenity = $stmt->fetch(PDO::FETCH_ASSOC);

            return $amenity ?: null; // Retorna a amenidade ou nulo.

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * Busca os IDs de todas as amenidades associadas a uma acomodação específica.
     * @param int $id O ID da acomodação.
     * @return array|null
     */
    public function getAmenitiesAccommodations($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_amenidades FROM amenidades_acomodacoes WHERE id_acomodacoes = :id_acomodacoes");
            $stmt->bindParam(':id_acomodacoes', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Retorna um array simples contendo apenas os IDs das amenidades.
            $ids_amenidades = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $ids_amenidades ?: null;

        } catch(Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}