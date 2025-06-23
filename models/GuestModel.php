<?php

class GuestModel extends Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }

    /**
     * Lista todos os hóspedes ativos no sistema.
     * @return array
     */
    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM hospedes WHERE active = 1 ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um hóspede específico pelo ID.
     * @param int $id
     * @return array|null
     */
    public function getHospedeById($id)
    {
        try {
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if (!$id) {
                return null;
            }

            $stmt = $this->pdo->prepare("SELECT * FROM hospedes WHERE id = :id AND active = 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $hospede = $stmt->fetch(PDO::FETCH_ASSOC);

            return $hospede ?: null;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * Busca as preferências de um hóspede pelo ID.
     * @param int $id
     * @return array
     */
    public function getPreferencesById($id)
    {
        // Assumindo que a coluna de referência na tabela de junção é 'id_amenidades'
        $stmt = $this->pdo->prepare("SELECT * FROM preferencias_hospedes WHERE id_hospede = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Retorna apenas um array com os IDs das amenidades/preferências
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Salva (cria ou atualiza) os dados de um hóspede e suas preferências.
     * @param array $dados
     * @return array
     */
    public function salvar($dados)
    {
        $errors = [];
        $id = $dados['id'] ?? null;

        if ($this->emailExists($dados['email'], $id)) {
            $errors['email'] = 'Este e-mail já está em uso.';
        }
        if ($this->cpfExists($dados['cpf'], $id)) {
            $errors['cpf'] = 'Este CPF já está cadastrado.';
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        try {
            $this->pdo->beginTransaction(); // <<-- INICIA A TRANSAÇÃO

            $nomeImagem = $this->gerenciarUploadImagem($dados);
            $hospedeId = null;

            if (empty($id)) {
                // INSERIR
                $sql = "INSERT INTO hospedes (nome, email, telefone, documento, rua, cidade, estado, numero, cep, data_nascimento, imagem, data_cadastro) 
                        VALUES (:nome, :email, :telefone, :documento, :rua, :cidade, :estado, :numero, :cep, :data_nascimento, :imagem, NOW())";
                $stmt = $this->pdo->prepare($sql);
            } else {
                // ATUALIZAR
                $sql = "UPDATE hospedes SET nome = :nome, email = :email, telefone = :telefone, documento = :documento, rua = :rua, cidade = :cidade, estado = :estado, numero = :numero, cep = :cep, data_nascimento = :data_nascimento, imagem = :imagem WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
            
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':documento', preg_replace('/[^0-9]/', '', $dados['cpf']));
            $stmt->bindValue(':rua', $dados['rua']);
            $stmt->bindValue(':cidade', $dados['cidade']);
            $stmt->bindValue(':estado', $dados['estado']);
            $stmt->bindValue(':numero', $dados['numero']);
            $stmt->bindValue(':cep', $dados['cep']);
            $stmt->bindValue(':data_nascimento', $dados['dataNasc']);
            $stmt->bindValue(':imagem', $nomeImagem);
            $stmt->execute();
            
            // Define o ID do hóspede para usar na tabela de preferências
            $hospedeId = empty($id) ? $this->pdo->lastInsertId() : $id;

            // --- LÓGICA PARA SALVAR PREFERÊNCIAS ---
            // 1. Deletar preferências antigas para evitar duplicatas e lidar com remoções.
            $stmtDelete = $this->pdo->prepare("DELETE FROM preferencias_hospedes WHERE id_hospede = :hospede_id");
            $stmtDelete->bindParam(':hospede_id', $hospedeId, PDO::PARAM_INT);
            $stmtDelete->execute();

            // 2. Inserir as novas preferências, se houver alguma.
            if (!empty($dados['preferencias']) && is_array($dados['preferencias'])) {
                $sqlPref = "INSERT INTO preferencias_hospedes (id_hospede, descricao) VALUES (:hospede_id, :descricao)";
                $stmtPref = $this->pdo->prepare($sqlPref);

                foreach ($dados['preferencias'] as $preferenciaId) {
                    $stmtPref->execute([
                        ':hospede_id' => $hospedeId,
                        ':descricao' => $preferenciaId
                    ]);
                }
            }
            // --- FIM DA LÓGICA DE PREFERÊNCIAS ---

            $this->pdo->commit(); // <<-- CONFIRMA A TRANSAÇÃO
            return ['status' => 'success'];

        } catch (PDOException $e) {
            $this->pdo->rollBack(); // <<-- DESFAZ TUDO EM CASO DE ERRO
            error_log($e->getMessage());
            return ['status' => 'error', 'errors' => ['general' => 'Ocorreu um erro no servidor ao salvar o hóspede.']];
        }
    }

    /**
     * Verifica se um e-mail já existe, ignorando o ID do próprio usuário (em caso de edição).
     * @param string $email
     * @param int|null $ignoreId
     * @return bool
     */
    public function emailExists($email, $ignoreId = null)
    {
        $sql = "SELECT id FROM hospedes WHERE email = :email";
        if ($ignoreId) {
            $sql .= " AND id != :ignoreId";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        if ($ignoreId) {
            $stmt->bindParam(':ignoreId', $ignoreId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica se um CPF já existe, ignorando o ID do próprio usuário (em caso de edição).
     * @param string $cpf
     * @param int|null $ignoreId
     * @return bool
     */
    public function cpfExists($cpf, $ignoreId = null)
    {
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        $sql = "SELECT id FROM hospedes WHERE documento = :cpf";
        if ($ignoreId) {
            $sql .= " AND id != :ignoreId";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpfLimpo, PDO::PARAM_STR);
        if ($ignoreId) {
            $stmt->bindParam(':ignoreId', $ignoreId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }


    /**
     * Desativa um hóspede no banco de dados (Soft Delete).
     * @param int $id
     * @return bool
     */
    public function deletar($id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE hospedes SET active = 0 WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Conta quantos novos hóspedes foram cadastrados no dia atual.
     * @return int
     */
    public function getContagemNovosHospedesHoje()
    {
        $query = "SELECT COUNT(id) as total FROM hospedes WHERE DATE(data_cadastro) = CURDATE()";
        $stmt = $this->pdo->query($query);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    /**
     * Função auxiliar para gerenciar o upload de imagem.
     * @param array $dados
     * @return string
     */
    private function gerenciarUploadImagem($dados)
    {
        $nomeImagem = $dados['imagem_atual'] ?? 'default.png';
        if (isset($dados['imagem']) && $dados['imagem']['error'] === UPLOAD_ERR_OK) {
            $novaImagem = $this->uploadImagem($dados['imagem']);
            if ($novaImagem) {
                if ($nomeImagem && $nomeImagem != 'default.png' && file_exists(__DIR__ . '/../Public/uploads/hospedes/' . $nomeImagem)) {
                    unlink(__DIR__ . '/../Public/uploads/hospedes/' . $nomeImagem);
                }
                return $novaImagem;
            }
        }
        return $nomeImagem;
    }

    /**
     * Função auxiliar que realiza o upload do arquivo.
     * @param array $arquivo
     * @return string|null
     */
    private function uploadImagem($arquivo)
    {
        $uploadDir = __DIR__ . '/../Public/uploads/hospedes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        
        if (!is_writable($uploadDir)) {
            error_log("ERRO: O diretório de upload não tem permissão de escrita: " . $uploadDir);
            return null;
        }

        $nomeArquivo = uniqid() . '-' . basename($arquivo['name']);
        $caminhoDestino = $uploadDir . $nomeArquivo;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
            return $nomeArquivo;
        } else {
            error_log("ERRO de Upload: Código " . $arquivo['error']);
            return null;
        }
    }
}