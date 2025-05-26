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

    public function  getImagesByAccommodationId($id)
    {
        $query = "SELECT * FROM imagens_acomodacoes WHERE acomodacao_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImagensCapa(){
        $query = "SELECT * FROM imagens_acomodacoes WHERE capa_acomodacao = 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            if ($stmt->execute()) {
                $uploadDir = 'Public/uploads/acomodacoes/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                $capa_definida = false;

                foreach ($data['imagens']['tmp_name'] as $key => $tmp) {
                    if ($data['imagens']['error'][$key] === UPLOAD_ERR_OK && getimagesize($tmp)) {
                        $nome_original = basename($_FILES['imagens']['name'][$key]);
                        $caminho = $uploadDir . uniqid() . "_" . $nome_original;

                        if (move_uploaded_file($tmp, $caminho)) {
                            // Define se essa imagem será a capa (apenas a primeira válida)
                            $capa = $capa_definida ? 0 : 1;
                            $capa_definida = true;

                            $query = "INSERT INTO imagens_acomodacoes (acomodacao_id, nome_arquivo, caminho_arquivo, capa_acomodacao) VALUES (:acomodacao_id, :nome_arquivo, :caminho_arquivo, :capa_acomodacao)";
                            $stmt = $this->pdo->prepare($query);
                            $stmt->bindParam(":acomodacao_id", $accommodationId, PDO::PARAM_INT);
                            $stmt->bindParam(":nome_arquivo", $nome_original);
                            $stmt->bindParam(":caminho_arquivo", $caminho);
                            $stmt->bindParam(":capa_acomodacao", $capa, PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                }
                // Retorna true se a inserção for bem-sucedida
                return true;
            } else {
                return false; // Retorna false se a inserção falhar
            }
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

            $uploadDir = 'Public/uploads/acomodacoes/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            foreach ($data['imagens']['tmp_name'] as $key => $tmp) {
                if ($data['imagens']['error'][$key] === UPLOAD_ERR_OK && getimagesize($tmp)) {
                    $nome_original = basename($_FILES['imagens']['name'][$key]);
                    $caminho = $uploadDir . uniqid() . "_" . $nome_original;

                    if (move_uploaded_file($tmp, $caminho)) {
                        $query = "INSERT INTO imagens_acomodacoes (acomodacao_id, nome_arquivo, caminho_arquivo) VALUES (:acomodacao_id, :nome_arquivo, :caminho_arquivo)";
                        $stmt = $this->pdo->prepare($query);
                        $stmt->bindParam(":acomodacao_id", $id, PDO::PARAM_INT);
                        $stmt->bindParam(":nome_arquivo", $nome_original);
                        $stmt->bindParam(":caminho_arquivo", $caminho);
                        $stmt->execute();
                    }
                }
            }

            // Deletar as imagens que foram marcadas para exclusão
            foreach ($data['delete_imagens'] as $deleteId) {
                $query = "SELECT caminho_arquivo FROM imagens_acomodacoes WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
                $stmt->execute();
                $imagem = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($imagem && file_exists($imagem['caminho_arquivo'])) {
                    unlink($imagem['caminho_arquivo']);
                }

                // Remover a imagem do banco de dados
                $query = "DELETE FROM imagens_acomodacoes WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
                $stmt->execute();
            }

            
            // Atualizar a imagem de capa
            $query = "SELECT * FROM imagens_acomodacoes WHERE id = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $data['imagem_capa'], PDO::PARAM_INT);
            $stmt->execute();
            $imagem = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($imagem && file_exists($imagem['caminho_arquivo'])) {
                $query = "UPDATE imagens_acomodacoes SET capa_acomodacao = 1 WHERE id = :acomodacao_id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':acomodacao_id', $data['imagem_capa'], PDO::PARAM_INT);
                $stmt->execute();
            }

            // Verifica se a capa atual nao foi excluida
            $query = "SELECT * FROM imagens_acomodacoes WHERE acomodacao_id = :acomodacao_id AND capa_acomodacao = 1";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $imagem = $stmt->fetch(PDO::FETCH_ASSOC);
            // Se a imagem de capa foi excluída, define a primeira imagem como capa
            if (!$imagem) {
                $query = "UPDATE imagens_acomodacoes SET capa_acomodacao = 1 WHERE acomodacao_id = :acomodacao_id LIMIT 1";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
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

            //Deletar as imagens do diretório
            $query = "SELECT caminho_arquivo FROM imagens_acomodacoes WHERE acomodacao_id = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($imagens as $imagem) {
                $caminho = $imagem['caminho_arquivo'];
                if (file_exists($caminho)) {
                    unlink($caminho);
                }
            }

            // Remover as imagens relacionadas
            $query = "DELETE FROM imagens_acomodacoes WHERE acomodacao_id = :acomodacao_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':acomodacao_id', $id, PDO::PARAM_INT);
            $stmt->execute();


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
