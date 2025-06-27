<?php

class AccommodationsModel extends Database
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
     * Lista todas as acomodações com suas respectivas imagens de capa.
     * @return array
     */
    public function listar()
    {
        $query = "SELECT a.*, ia.caminho_arquivo as caminho_capa
                  FROM acomodacoes a
                  LEFT JOIN imagens_acomodacoes ia ON a.id = ia.acomodacao_id AND ia.capa_acomodacao = 1
                  ORDER BY a.tipo, a.numero ASC";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma acomodação pelo seu ID.
     * @param int $id
     * @return array|null
     */
    public function getAccommodationById($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) return null;

        $query = "SELECT * FROM acomodacoes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Busca uma acomodação pelo seu nome/tipo.
     * @param string $name
     * @return array|null
     */
    public function getAccommodationByName($name)
    {
        $query = "SELECT * FROM acomodacoes WHERE tipo = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Busca uma acomodação pelo seu número.
     * Útil para verificar se um número de quarto/acomodação já existe.
     * @param int $number O número da acomodação.
     * @return array|null Retorna os dados da acomodação ou nulo se não encontrar.
     */
    public function getAccommodationByNumber($number)
    {
        $number = filter_var($number, FILTER_VALIDATE_INT);
        if (!$number) {
            return null;
        }

        $query = "SELECT * FROM acomodacoes WHERE numero = :number";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Busca os IDs de todas as imagens associadas a uma acomodação.
     * @param int $id O ID da acomodação.
     * @return array
     */
    public function getImagesByAccommodationId($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) return [];

        $query = "SELECT * FROM imagens_acomodacoes WHERE acomodacao_id = :id ORDER BY ordem ASC, id ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cria uma nova acomodação, suas amenidades e imagens de forma transacional.
     * @param array $data Os dados da acomodação.
     * @return bool
     */
    public function create($data)
    {
        try {
            $this->pdo->beginTransaction();

            $query = "INSERT INTO acomodacoes (tipo, numero, descricao, status, capacidade, preco, minimo_noites, camas_casal, camas_solteiro, hora_checkin, hora_checkout) VALUES (:tipo, :numero, :descricao, :status, :capacidade, :preco, :minimo_noites, :camas_casal, :camas_solteiro, :check_in_time, :check_out_time)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':tipo' => $data['tipo'],
                ':numero' => $data['numero'],
                ':descricao' => $data['descricao'],
                ':status' => $data['status'],
                ':capacidade' => $data['capacidade'],
                ':preco' => $data['preco'],
                ':minimo_noites' => $data['minimo_noites'],
                ':camas_casal' => $data['camas_casal'],
                ':camas_solteiro' => $data['camas_solteiro'],
                ':check_in_time' => $data['check_in_time'],
                ':check_out_time' => $data['check_out_time']
            ]);

            $accommodationId = $this->pdo->lastInsertId();

            if (!empty($data['amenidades'])) {
                $queryAmenity = "INSERT INTO amenidades_acomodacoes (id_acomodacoes, id_amenidades) VALUES (:acomodacao_id, :amenidade_id)";
                $stmtAmenity = $this->pdo->prepare($queryAmenity);
                foreach ($data['amenidades'] as $amenityId) {
                    $stmtAmenity->execute([':acomodacao_id' => $accommodationId, ':amenidade_id' => $amenityId]);
                }
            }

            $this->processarImagens($accommodationId, $data['imagens']);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erro ao criar acomodação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza uma acomodação, suas amenidades e imagens de forma transacional.
     * @param int $id O ID da acomodação.
     * @param array $data Os dados para atualização.
     * @return bool
     */
    public function update($id, $data)
    {
        try {
            $this->pdo->beginTransaction();

            $query = "UPDATE acomodacoes SET tipo = :tipo, numero = :numero, descricao = :descricao, status = :status, capacidade = :capacidade, preco = :preco, minimo_noites = :minimo_noites, camas_casal = :camas_casal, camas_solteiro = :camas_solteiro, hora_checkin = :check_in_time, hora_checkout = :check_out_time WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':tipo' => $data['tipo'],
                ':numero' => $data['numero'],
                ':descricao' => $data['descricao'],
                ':status' => $data['status'],
                ':capacidade' => $data['capacidade'],
                ':preco' => $data['preco'],
                ':minimo_noites' => $data['minimo_noites'],
                ':camas_casal' => $data['camas_casal'],
                ':camas_solteiro' => $data['camas_solteiro'],
                ':check_in_time' => $data['check_in_time'],
                ':check_out_time' => $data['check_out_time']
            ]);

            $this->pdo->prepare("DELETE FROM amenidades_acomodacoes WHERE id_acomodacoes = :id")->execute([':id' => $id]);
            if (!empty($data['amenidades'])) {
                $queryAmenity = "INSERT INTO amenidades_acomodacoes (id_acomodacoes, id_amenidades) VALUES (:acomodacao_id, :amenidade_id)";
                $stmtAmenity = $this->pdo->prepare($queryAmenity);
                foreach ($data['amenidades'] as $amenityId) {
                    $stmtAmenity->execute([':acomodacao_id' => $id, ':amenidade_id' => $amenityId]);
                }
            }

            if (!empty($data['imagens']['tmp_name'][0])) {
                $this->processarImagens($id, $data['imagens'], false);
            }

            if (!empty($data['delete_imagens'])) {
                $this->deletarImagens($data['delete_imagens']);
            }

            if (!empty($data['image_order'])) {
                $this->updateImageOrder($data['image_order']);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erro ao atualizar acomodação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui uma acomodação e todos os seus dados relacionados (imagens, amenidades).
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            $this->pdo->beginTransaction();

            $imagens = $this->getImagesByAccommodationId($id);
            foreach ($imagens as $imagem) {
                if (file_exists($imagem['caminho_arquivo'])) {
                    unlink($imagem['caminho_arquivo']);
                }
            }

            $this->pdo->prepare("DELETE FROM imagens_acomodacoes WHERE acomodacao_id = :id")->execute([':id' => $id]);
            $this->pdo->prepare("DELETE FROM amenidades_acomodacoes WHERE id_acomodacoes = :id")->execute([':id' => $id]);
            $this->pdo->prepare("DELETE FROM acomodacoes WHERE id = :id")->execute([':id' => $id]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erro ao deletar acomodação: " . $e->getMessage());
            return false;
        }
    }

    public function getStatusAcomodacoes()
    {
        $query = "SELECT status, COUNT(id) as total FROM acomodacoes GROUP BY status";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAcomodacoesEmManutencao()
    {
        $query = "SELECT tipo, numero FROM acomodacoes WHERE status = 'manutencao'";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function processarImagens($accommodationId, $imagensData, $definirPrimeiraComoCapa = true)
    {
        $uploadDir = 'Public/uploads/acomodacoes/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $capa_definida = !$definirPrimeiraComoCapa;

        $query = "INSERT INTO imagens_acomodacoes (acomodacao_id, nome_arquivo, caminho_arquivo, capa_acomodacao) VALUES (:acomodacao_id, :nome_arquivo, :caminho_arquivo, :capa_acomodacao)";
        $stmt = $this->pdo->prepare($query);

        foreach ($imagensData['tmp_name'] as $key => $tmp) {
            if ($imagensData['error'][$key] === UPLOAD_ERR_OK) {
                $nome_original = basename($imagensData['name'][$key]);
                $caminho = $uploadDir . uniqid() . "_" . $nome_original;

                if (move_uploaded_file($tmp, $caminho)) {
                    $capa = $capa_definida ? 0 : 1;
                    $capa_definida = true;

                    $stmt->execute([
                        ":acomodacao_id" => $accommodationId,
                        ":nome_arquivo" => $nome_original,
                        ":caminho_arquivo" => $caminho,
                        ":capa_acomodacao" => $capa
                    ]);
                }
            }
        }
    }

    private function deletarImagens($idsParaDeletar)
    {
        $querySelect = "SELECT caminho_arquivo FROM imagens_acomodacoes WHERE id = :id";
        $stmtSelect = $this->pdo->prepare($querySelect);

        $queryDelete = "DELETE FROM imagens_acomodacoes WHERE id = :id";
        $stmtDelete = $this->pdo->prepare($queryDelete);

        foreach ($idsParaDeletar as $deleteId) {
            $stmtSelect->execute([':id' => $deleteId]);
            $imagem = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($imagem && file_exists($imagem['caminho_arquivo'])) {
                unlink($imagem['caminho_arquivo']);
            }
            $stmtDelete->execute([':id' => $deleteId]);
        }
    }

    public function updateImageOrder($imageIds)
    {
        $query = "UPDATE imagens_acomodacoes SET ordem = :ordem, capa_acomodacao = :capa WHERE id = :id";
        $stmt = $this->pdo->prepare($query);

        foreach ($imageIds as $index => $imageId) {
            $stmt->execute([
                ':ordem' => $index,
                ':capa' => ($index === 0) ? 1 : 0,
                ':id' => $imageId
            ]);
        }
    }

        /**
     * Busca uma acomodação pela combinação de tipo e número, opcionalmente excluindo um ID.
     * Essencial para evitar duplicatas na criação e edição.
     * @param string $tipo O tipo da acomodação.
     * @param int $numero O número da acomodação.
     * @param int|null $excludeId O ID da acomodação a ser ignorado na busca (usado ao atualizar).
     * @return array|null Retorna os dados da acomodação encontrada ou nulo.
     */
    public function findByTypeAndNumber($tipo, $numero, $excludeId = null)
    {
        $query = "SELECT id FROM acomodacoes WHERE tipo = :tipo AND numero = :numero";
        $params = [':tipo' => $tipo, ':numero' => $numero];

        if ($excludeId !== null) {
            $query .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}