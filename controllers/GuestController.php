<?php


class GuestController extends RenderView
{

    private $guestModel;

    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }

    public function list()
    {
        $this->LoadView('Hospedes', [
            'Title' => 'Listagem de todos os Ususarios',
            'Guests' => $this->guestModel->listar(),
            'father' => 'Hospedes;',
            'page' => 'Listar',
        ]);
    }

    public function editar($id)
    {
        $guest = $this->guestModel->getHospedeById($id);
        if (!$guest) {
            // Redireciona se o hóspede não for encontrado
            header("Location: /RoomFlow/Hospedes?msg=not_found");
            exit();
        }

        $this->LoadView('HospedeEditar', [
            'Title' => 'Editar Hóspede',
            'guest' => $guest,
            'preferencias' => $this->guestModel->getPreferencesById($id), // Assumindo que este método existe
            'father' => 'Hospedes',
            'page' => 'Editar',
        ]);
    }

    public function create($id)
    {
        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Coleta os dados do formulário
            $data = $this->collectGuestDataFromRequest();

            // 2. Valida os dados de entrada
            $errors = $this->validateGuestData($data);

            // 3. Se os dados forem válidos, tenta salvar
            if (empty($errors)) {
                $result = $this->guestModel->salvar($data);

                if ($result['status'] === 'success') {
                    header("Location: /RoomFlow/Hospedes?msg=success_create");
                    exit();
                } else {
                    // Erros vindos do Model (ex: CPF duplicado)
                    $errors = array_merge($errors, $result['errors']);
                }
            }
        }

        // 4. Exibe a View de cadastro com os dados e erros (se houver)
        $this->LoadView('HospedeCadastrar', [
            'Title' => 'Cadastro de Hóspede',
            'errors' => $errors,
            'data' => $data,
            'father' => 'Hospedes',
            'page' => 'Cadastrar',
        ]);
    }

    public function update($id)
    {
        $errors = [];
        // Carrega os dados atuais para mesclar com os novos
        $guest = $this->guestModel->getHospedeById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Coleta os dados
            $data = $this->collectGuestDataFromRequest($guest);
            $data['id'] = $id; // Adiciona o ID para a operação de update

            // 2. Valida os dados
            $errors = $this->validateGuestData($data, true); // true para modo de atualização

            // 3. Se válido, tenta salvar
            if (empty($errors)) {
                $result = $this->guestModel->salvar($data);

                if ($result['status'] === 'success') {
                    header("Location: /RoomFlow/Hospedes?msg=success_update");
                    exit();
                } else {
                    $errors = array_merge($errors, $result['errors']);
                }
            }
        }

        // 4. Exibe a View de edição com os dados e erros
        $this->LoadView('HospedeEditar', [
            'Title' => 'Editar Hóspede',
            'errors' => $errors,
            'guest' => array_merge($guest, $_POST), // Mescla dados antigos com os submetidos para preencher o form
            'preferencias' => $this->guestModel->getPreferencesById($id),
            'father' => 'Hospedes',
            'page' => 'Editar',
        ]);
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $id_guest = $_POST['id'];
            if ($this->guestModel->deletar($id_guest)) {
                header("Location: /RoomFlow/Hospedes?msg=success_delete");
                exit();
            }
        }
        header("Location: /RoomFlow/Hospedes?msg=error_delete");
        exit();
    }

    private function collectGuestDataFromRequest($currentGuest = null)
    {
        // Função para limpar os dados de entrada
        function cleanInput($data)
        {
            return htmlspecialchars(stripslashes(trim($data)));
        }

        return [
            'nome' => cleanInput($_POST['nome'] ?? ''),
            'email' => cleanInput($_POST['email'] ?? ''),
            'telefone' => cleanInput($_POST['telefone'] ?? ''),
            'cpf' => preg_replace('/[^0-9]/', '', cleanInput($_POST['cpf'] ?? '')), // Remove formatação do CPF
            'rua' => cleanInput($_POST['rua'] ?? ''),
            'cidade' => cleanInput($_POST['cidade'] ?? ''),
            'estado' => cleanInput($_POST['estado'] ?? ''),
            'numero' => cleanInput($_POST['numero'] ?? ''),
            'cep' => cleanInput($_POST['cep'] ?? ''),
            'dataNasc' => cleanInput($_POST['dataNasc'] ?? ''),
            'imagem' => $_FILES['imagem'], // Passa o array do arquivo
            'imagem_atual' => $currentGuest['imagem'] ?? null
        ];
    }

    private function validateGuestData(array $data, bool $isUpdate = false)
    {
        $errors = [];

        // Validações
        $validations = [
            'nome' => validarNome($data['nome']),
            'email' => validarEmail($data['email']),
            'telefone' => validarTelefone($data['telefone']),
            'cpf' => validarCpf($data['cpf']),
            'cep' => validarCep($data['cep']),
            'dataNasc' => validarDataNascimento($data['dataNasc']),
            'rua' => validarRua($data['rua']),
            'cidade' => validarCidade($data['cidade']),
            'estado' => validarEstado($data['estado']),
            'numero' => validarNumero($data['numero'])
        ];

        // Coletar erros
        foreach ($validations as $field => $validation) {
            if ($validation['status'] === 'error') {
                $errors[$field] = $validation['msg'];
            }
        }


        // Adicione aqui as outras chamadas para suas funções de validação
        // (validarTelefone, validarCep, validarDataNascimento, etc.)

        return $errors;
    }
}
