<?php


class GuestController extends RenderView
{

    public function list()
    {
        $guests = new GuestModel();

        $this->LoadView('Hospedes', [
            'Title' => 'Listagem de todos os Ususarios',
            'Guests' => $guests->listar(),
            'father' => 'Hospedes;',
            'page' => 'Listar',
        ]);
    }

    public function editar($id)
    {
        $id_guest = $id;

        $guest = new GuestModel();
        $preferencias = new GuestModel();

        $this->LoadView('HospedeEditar', [
            'guest' => $guest->getHospedeById($id_guest),
            'preferencias' => $preferencias->getPreferencesById($id_guest),
            'father' => 'Hospedes',
            'page' => 'Editar',
        ]);
    }

    public function create($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Função de limpeza de entrada
            function cleanInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            // Definir dados
            $data = [
                'nome' => cleanInput($_POST['nome'] ?? ''),
                'email' => cleanInput($_POST['email'] ?? ''),
                'telefone' => cleanInput($_POST['telefone'] ?? ''),
                'cpf' => cleanInput($_POST['cpf'] ?? ''),
                'rua' => cleanInput($_POST['rua'] ?? ''),
                'cidade' => cleanInput($_POST['cidade'] ?? ''),
                'estado' => cleanInput($_POST['estado'] ?? ''),
                'numero' => cleanInput($_POST['numero'] ?? ''),
                'cep' => cleanInput($_POST['cep'] ?? ''),
                'dataNasc' => cleanInput($_POST['dataNasc'] ?? '')
            ];

            // Remover formatação do CPF (se houver)
            $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);

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

            // Se não houver erros, salvar no banco
            if (empty($errors)) {
                $guestModel = new GuestModel();

                // Verificar se o CPF já está cadastrado
                if ($guestModel->cpfExists($data['cpf'])) {
                    $errors['cpf'] = 'O CPF já está cadastrado.';
                } else {
                    // Salvar no banco se não houver erros
                    $success = $guestModel->criar(
                        $data['nome'],
                        $data['email'],
                        $data['telefone'],
                        $data['cpf'],
                        $data['rua'],
                        $data['cidade'],
                        $data['estado'],
                        $data['numero'],
                        $data['cep'],
                        $data['dataNasc']
                    );

                    if ($success) {
                        header("Location: /Roomflox/Hospedes/Cadastrar?msg=success_create");
                        exit();
                    } else {
                        $errors['general'] = "Erro ao cadastrar! Tente novamente.";
                    }
                }
            }
        }

        // Exibir a View de cadastro com os erros
        $this->LoadView('HospedeCadastrar', [
            'Title' => 'Cadastro de Hóspede',
            'errors' => $errors,
            'father' => 'Hospedes',
            'page' => 'Cadastrar',
        ]);
    }

    public function update(){

    }
}
