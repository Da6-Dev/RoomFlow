<?php

class GuestModel extends Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getConnection();
    }
    public function listar()
    {
        $stmt = $this->pdo->query("SELECT * FROM hospedes WHERE active = 1");
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
    public function getHospedeById($id)
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

            $stmt = $this->pdo->prepare("SELECT * FROM hospedes WHERE id = ?");
            $stmt->execute([$id]);

            $hospede = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hospede) {
                return null; // Retorna null se nenhum hóspede for encontrado
            }

            return $hospede;
        } catch (Exception $e) {
            error_log($e->getMessage()); // Registra o erro no log do servidor
            return null;
        }
    }

    public function getPreferencesById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM preferencias_hospedes WHERE id_hospede = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function salvar($dados)
    {
        $errors = [];

        // Verifica se o email já existe (ignorando o próprio usuário na atualização)
        $queryEmail = "SELECT id FROM hospedes WHERE email = ? AND (id != ? OR ? IS NULL)";
        $stmtEmail = $this->pdo->prepare($queryEmail);
        $stmtEmail->execute([$dados['email'], $dados['id'] ?? null, $dados['id'] ?? null]);
        if ($stmtEmail->fetch()) {
            $errors['email'] = 'Este e-mail já está em uso.';
        }

        // Verifica se o CPF já existe (ignorando o próprio usuário na atualização)
        $queryCpf = "SELECT id FROM hospedes WHERE documento = ? AND (id != ? OR ? IS NULL)";
        $stmtCpf = $this->pdo->prepare($queryCpf);
        $stmtCpf->execute([$dados['cpf'], $dados['id'] ?? null, $dados['id'] ?? null]);
        if ($stmtCpf->fetch()) {
            $errors['cpf'] = 'Este CPF já está cadastrado.';
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        // Lida com o upload da imagem
        $nomeImagem = $dados['imagem_atual'] ?? 'default.png'; // Mantém a imagem atual por padrão
        if (isset($dados['imagem']) && $dados['imagem']['error'] === UPLOAD_ERR_OK) {
            $novaImagem = $this->uploadImagem($dados['imagem']);
            if ($novaImagem) {
                // Apaga a imagem antiga se não for a padrão
                if ($nomeImagem && $nomeImagem != 'default.png' && file_exists(__DIR__ . '/../Public/uploads/hospedes/' . $nomeImagem)) {
                    unlink(__DIR__ . '/../Public/uploads/hospedes/' . $nomeImagem);
                }
                $nomeImagem = $novaImagem;
            }
        }

        try {
            if (empty($dados['id'])) {
                // INSERIR (Criar novo hóspede)
                $sql = "INSERT INTO hospedes (nome, email, telefone, documento, rua, cidade, estado, numero, cep, data_nascimento, imagem, data_cadastro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $dados['nome'],
                    $dados['email'],
                    $dados['telefone'],
                    $dados['cpf'],
                    $dados['rua'],
                    $dados['cidade'],
                    $dados['estado'],
                    $dados['numero'],
                    $dados['cep'],
                    $dados['dataNasc'],
                    $nomeImagem,
                    date('Y-m-d H:i:s')
                ]);
            } else {
                // ATUALIZAR (Editar hóspede existente)
                $sql = "UPDATE hospedes SET nome = ?, email = ?, telefone = ?, documento = ?, rua = ?, cidade = ?, estado = ?, numero = ?, cep = ?, data_nascimento = ?, imagem = ? WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $dados['nome'],
                    $dados['email'],
                    $dados['telefone'],
                    $dados['cpf'],
                    $dados['rua'],
                    $dados['cidade'],
                    $dados['estado'],
                    $dados['numero'],
                    $dados['cep'],
                    $dados['dataNasc'],
                    $nomeImagem,
                    $dados['id']
                ]);
            }
            return ['status' => 'success'];
        } catch (PDOException $e) {
            // Log do erro (importante para o desenvolvimento)
            error_log($e->getMessage());
            $errors['general'] = 'Ocorreu um erro no servidor ao salvar o hóspede.';
            return ['status' => 'error', 'errors' => $errors];
        }
    }

    public function emailExists($email)
    {
        try {
            // Preparando a consulta SQL para verificar se o e-mail já existe na tabela 'hospedes'
            $query = "SELECT COUNT(*) FROM hospedes WHERE email = :email";
            $stmt = $this->pdo->prepare($query);

            // Bind do parâmetro para proteger contra SQL Injection
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            // Executando a consulta
            $stmt->execute();

            // Obtendo o resultado da contagem
            $count = $stmt->fetchColumn();

            // Se o resultado for maior que 0, significa que o e-mail já existe
            return $count > 0;
        } catch (PDOException $e) {
            // Em caso de erro com o banco de dados, retorna false
            return false;
        }
    }

    public function cpfExists($cpf)
    {
        try {
            // Remover qualquer formatação do CPF (como pontos ou traços)
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            // Preparando a consulta SQL para verificar se o CPF já existe na tabela 'hospedes'
            $query = "SELECT COUNT(*) FROM hospedes WHERE documento = :cpf";
            $stmt = $this->pdo->prepare($query);

            // Bind do parâmetro para proteger contra SQL Injection
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);

            // Executando a consulta
            $stmt->execute();

            // Obtendo o resultado da contagem
            $count = $stmt->fetchColumn();

            // Debug: Exibe o CPF e o resultado da contagem
            error_log("Verificando CPF: $cpf - Resultado: $count");

            // Se o resultado for maior que 0, significa que o CPF já existe
            return $count > 0;
        } catch (PDOException $e) {
            // Em caso de erro com o banco de dados, retorna false
            error_log("Erro no banco de dados: " . $e->getMessage());
            return false;
        }
    }



    public function deletar($id)
    {
        try {
            // Primeiro, pegue o nome do arquivo da imagem para poder deletá-lo da pasta
            $stmt = $this->pdo->prepare("SELECT imagem FROM hospedes WHERE id = ?");
            $stmt->execute([$id]);
            $hospede = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($hospede) {
                $imagemParaDeletar = $hospede['imagem'];
                // Deleta o registro do banco de dados
                $deleteStmt = $this->pdo->prepare("DELETE FROM hospedes WHERE id = ?");
                $deleteStmt->execute([$id]);

                // Se o registro foi deletado com sucesso, apaga o arquivo da imagem
                if ($deleteStmt->rowCount() > 0) {
                    if ($imagemParaDeletar && $imagemParaDeletar != 'default.png' && file_exists(__DIR__ . '/../Public/uploads/hospedes/' . $imagemParaDeletar)) {
                        unlink(__DIR__ . '/../Public/uploads/hospedes/' . $imagemParaDeletar);
                    }
                    return true;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    public function getContagemNovosHospedesHoje()
    {
        // Este método assume que existe uma coluna 'data_cadastro' na sua tabela 'hospedes'.
        $query = "SELECT COUNT(id) as total FROM hospedes WHERE DATE(data_cadastro) = CURDATE()";
        $stmt = $this->pdo->query($query);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    // Em App/Models/GuestModel.php
private function uploadImagem($arquivo)
{
    if (isset($arquivo) && $arquivo['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = uniqid() . '-' . basename($arquivo['name']);
        $caminhoDestino = __DIR__ . '/../Public/uploads/hospedes/' . $nomeArquivo;

        // ---- ADICIONE ESTE CÓDIGO PARA DEPURAR ----
        $diretorioUpload = dirname($caminhoDestino);
        if (!is_dir($diretorioUpload)) {
            error_log("ERRO: O diretório de upload não existe: " . $diretorioUpload);
            return null; // Retorna nulo se o diretório não existe
        }
        if (!is_writable($diretorioUpload)) {
            error_log("ERRO: O diretório não tem permissão de escrita: " . $diretorioUpload);
            return null; // Retorna nulo se não houver permissão
        }
        // ---- FIM DO CÓDIGO DE DEPURAÇÃO ----

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
            return $nomeArquivo;
        } else {
            // Adicione um log para o erro do move_uploaded_file
            error_log("ERRO: move_uploaded_file falhou. Destino: " . $caminhoDestino);
        }
    } else if (isset($arquivo)) {
        // Adicione um log para outros erros de upload
        error_log("ERRO de Upload: Código " . $arquivo['error']);
    }
    return null;
}
}
