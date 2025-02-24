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
        $stmt = $this->pdo->query("SELECT * FROM hospedes");
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

    public function criar($nome, $email, $telefone, $cpf, $rua, $cidade, $estado, $numero, $cep, $dataNasc)
    {
        // Inserir o hóspede na tabela hospedes
        $stmt = $this->pdo->prepare("INSERT INTO hospedes (nome, email, telefone, documento, rua, cidade, estado, numero, cep, data_nascimento) VALUES (:nome, :email, :telefone, :cpf, :rua, :cidade, :estado, :numero, :cep, :dataNasc)");

        // Bind dos parâmetros usando PDO
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':rua', $rua);
        $stmt->bindValue(':cidade', $cidade);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':numero', $numero);
        $stmt->bindValue(':cep', $cep);
        $stmt->bindValue(':dataNasc', $dataNasc);

        // Verificando se a inserção foi bem-sucedida
        if ($stmt->execute()) {
            // Obtém o ID do hóspede recém inserido
            $idHospede = $this->pdo->lastInsertId();

            // Inserir as preferências, se existirem
            $npref = 1;
            while (isset($_POST["pref{$npref}"])) {
                $preferencia = $_POST["pref{$npref}"];

                // Inserir as preferências na tabela preferencias_hospedes
                $stmtPref = $this->pdo->prepare("INSERT INTO preferencias_hospedes (id_hospede, descricao) VALUES (:id_hospede, :descricao)");
                $stmtPref->bindValue(':id_hospede', $idHospede);
                $stmtPref->bindValue(':descricao', $preferencia);
                $stmtPref->execute();

                $npref++;
            }

            // Retorna true em caso de sucesso
            return true;
        } else {
            // Retorna false em caso de erro
            return false;
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

    public function atualizar($id, $nome, $email, $telefone, $cpf, $rua, $cidade, $estado, $numero, $cep, $dataNasc, $preferencias)
{
    try {
        // Iniciar uma transação
        $this->pdo->beginTransaction();

        // Atualizar os dados do hóspede na tabela hospedes
        $stmt = $this->pdo->prepare("UPDATE hospedes SET nome = :nome, email = :email, telefone = :telefone, documento = :cpf, rua = :rua, cidade = :cidade, estado = :estado, numero = :numero, cep = :cep, data_nascimento = :dataNasc WHERE id = :id");

        // Bind dos parâmetros usando PDO
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':rua', $rua);
        $stmt->bindValue(':cidade', $cidade);
        $stmt->bindValue(':estado', $estado);
        $stmt->bindValue(':numero', $numero);
        $stmt->bindValue(':cep', $cep);
        $stmt->bindValue(':dataNasc', $dataNasc);

        // Executar a atualização
        $stmt->execute();

        // Remover as preferências antigas do hóspede
        $stmtDelete = $this->pdo->prepare("DELETE FROM preferencias_hospedes WHERE id_hospede = :id_hospede");
        $stmtDelete->bindValue(':id_hospede', $id, PDO::PARAM_INT);
        $stmtDelete->execute();

        // Inserir as novas preferências, se existirem
        foreach ($preferencias as $preferencia) {
            $stmtPref = $this->pdo->prepare("INSERT INTO preferencias_hospedes (id_hospede, descricao) VALUES (:id_hospede, :descricao)");
            $stmtPref->bindValue(':id_hospede', $id, PDO::PARAM_INT);
            $stmtPref->bindValue(':descricao', $preferencia);
            $stmtPref->execute();
        }

        // Confirmar a transação
        $this->pdo->commit();

        // Retorna true em caso de sucesso
        return true;
    } catch (PDOException $e) {
        // Em caso de erro, desfazer a transação
        $this->pdo->rollBack();
        error_log("Erro ao atualizar hóspede: " . $e->getMessage());
        return false;
    }
}
}
