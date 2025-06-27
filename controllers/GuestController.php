<?php

class GuestController extends RenderView
{
    private $guestModel;

    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }

    private function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    private function collectGuestDataFromRequest($currentGuest = null)
    {
        $preferencias = [];
        if (isset($_POST['preferencias']) && is_array($_POST['preferencias'])) {
            foreach ($_POST['preferencias'] as $preferencia) {
                if (!empty(trim($preferencia))) {
                    $preferencias[] = $this->cleanInput($preferencia);
                }
            }
        }

        return [
            'id'             => $currentGuest['id'] ?? null,
            'nome'           => $this->cleanInput($_POST['nome'] ?? ''),
            'email'          => $this->cleanInput($_POST['email'] ?? ''),
            'telefone'       => $this->cleanInput($_POST['telefone'] ?? ''),
            'cpf'            => $this->cleanInput($_POST['cpf'] ?? ''), // Limpeza de CPF feita no Model
            'rua'            => $this->cleanInput($_POST['rua'] ?? ''),
            'cidade'         => $this->cleanInput($_POST['cidade'] ?? ''),
            'estado'         => $this->cleanInput($_POST['estado'] ?? ''),
            'numero'         => $this->cleanInput($_POST['numero'] ?? ''),
            'cep'            => $this->cleanInput($_POST['cep'] ?? ''),
            'dataNasc'       => $this->cleanInput($_POST['dataNasc'] ?? ''),
            'preferencias'   => $preferencias,
            'imagem'         => $_FILES['imagem'] ?? null,
            'imagem_atual'   => $currentGuest['imagem'] ?? null
        ];
    }

    private function validateGuestData(array $data)
    {
        $errors = [];
        // As funções de validação (validarNome, etc.) devem existir em outro arquivo de helpers.
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

        foreach ($validations as $field => $validation) {
            if ($validation['status'] === 'error') {
                $errors[$field] = $validation['msg'];
            }
        }
        return $errors;
    }

    public function list()
    {
        $this->LoadView('Hospedes', [
            'Title' => 'Listagem de Hóspedes',
            'Guests' => $this->guestModel->listar(),
            'father' => 'Hospedes',
            'page' => 'Listar',
            'page_script' => 'Hospedes.js',
        ]);
    }

    public function create()
    {
        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectGuestDataFromRequest();
            $errors = $this->validateGuestData($data);

            if (empty($errors)) {
                $result = $this->guestModel->salvar($data);

                if ($result['status'] === 'success') {
                    header("Location: /RoomFlow/Hospedes?msg=success_create");
                    exit();
                } else {
                    $errors = array_merge($errors, $result['errors']);
                }
            }
        }

        $this->LoadView('HospedeCadastrar', [
            'Title' => 'Cadastro de Hóspede',
            'errors' => $errors,
            'data' => $data,
            'father' => 'Hospedes',
            'page' => 'Cadastrar',
            'page_script' => 'HospedeCadastrar.js',
        ]);
    }

    public function editar($id)
    {
        $guest = $this->guestModel->getHospedeById($id);
        if (!$guest) {
            header("Location: /RoomFlow/Hospedes?msg=not_found");
            exit();
        }

        $this->LoadView('HospedeEditar', [
            'Title' => 'Editar Hóspede',
            'guest' => $guest,
            'preferencias' => $this->guestModel->getPreferencesById($id),
            'father' => 'Hospedes',
            'page' => 'Editar',
            'page_script' => 'HospedeEditar.js',
        ]);
    }

    public function update($id)
    {
        $guest = $this->guestModel->getHospedeById($id);
        if (!$guest) {
            header("Location: /RoomFlow/Hospedes?msg=not_found");
            exit();
        }

        $data = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectGuestDataFromRequest($guest);
            $data['id'] = $id;
            $errors = $this->validateGuestData($data);

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

        $this->LoadView('HospedeEditar', [
            'Title' => 'Editar Hóspede',
            'errors' => $errors,
            'guest' => array_merge($guest, $_POST),
            'preferencias' => $_POST['preferencias'] ?? $this->guestModel->getPreferencesById($id),
            'father' => 'Hospedes',
            'page' => 'Editar',
            'page_script' => 'HospedeEditar.js',
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
}