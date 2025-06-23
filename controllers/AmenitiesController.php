<?php

class AmenitiesController extends RenderView
{
    private $amenitiesModel;

    // 1. O construtor inicializa o model.
    public function __construct()
    {
        $this->amenitiesModel = new AmenitiesModel();
    }

    // Método privado para coletar dados do formulário.
    private function collectDataFromRequest()
    {
        return [
            'nome' => htmlspecialchars(stripslashes(trim($_POST['nome'] ?? ''))),
        ];
    }

    // Método privado para validar os dados.
    private function validateData($data, $id = null)
    {
        $errors = [];

        // Validar nome
        if (empty($data['nome'])) {
            $errors['nome'] = 'O campo nome é obrigatório.';
        } elseif (strlen($data['nome']) < 3) {
            $errors['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['nome'])) {
            $errors['nome'] = 'O nome deve conter apenas letras e espaços.';
        } else {
            // Verifica se a comodidade já existe (ignorando o ID atual na edição)
            $existingAmenity = $this->amenitiesModel->getAmenityByName($data['nome']);
            if ($existingAmenity && $existingAmenity['id'] != $id) {
                $errors['nome'] = 'Essa comodidade já existe.';
            }
        }
        return $errors;
    }

    public function list()
    {
        $this->LoadView('Comodidades', [
            'Title' => 'Listagem de todas as Comodidades',
            'Amenities' => $this->amenitiesModel->listar(),
            'father' => 'Comodidades',
            'page' => 'Listar',
        ]);
    }

    public function create()
    {
        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data);

            if (empty($errors)) {
                $this->amenitiesModel->create($data);
                header('Location: /RoomFlow/Comodidades/Cadastrar?msg=success_create');
                exit();
            }
        }

        $this->LoadView('ComodidadesCadastrar', [
            'Title' => 'Cadastrar Comodidade',
            'errors' => $errors,
            'data' => $data,
            'father' => 'Comodidades',
            'page' => 'Cadastrar',
        ]);
    }

    // A função 'editar' agora apenas exibe o formulário.
    public function editar($id)
    {
        $data = $this->amenitiesModel->getAmenityById($id);

        // Se a comodidade não existe, redireciona para a listagem
        if (!$data) {
            header('Location: /RoomFlow/Comodidades?msg=not_found');
            exit();
        }

        $this->LoadView('ComodidadesEditar', [
            'Title' => 'Editar Comodidade',
            'errors' => [],
            'data' => $data,
            'father' => 'Comodidades',
            'page' => 'Editar',
        ]);
    }

    // A lógica de atualização foi movida para o método 'update'.
    public function update($id)
    {
        $data = [];
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data, $id);

            if (empty($errors)) {
                $data['id'] = $id;
                $this->amenitiesModel->update($data);
                header('Location: /RoomFlow/Comodidades?msg=success_update');
                exit();
            }
        }

        // Se houver erro, exibe o formulário novamente com os erros
        $currentData = $this->amenitiesModel->getAmenityById($id);
        $this->LoadView('ComodidadesEditar', [
            'Title' => 'Editar Comodidade',
            'errors' => $errors,
            'data' => array_merge($currentData, $data), // Mantém o que o usuário digitou
            'father' => 'Comodidades',
            'page' => 'Editar',
        ]);
    }


    public function delete()
    {
        $id = $_POST['id'] ?? null;

        if ($id && $this->amenitiesModel->delete($id)) {
            header("Location: /RoomFlow/Comodidades?msg=success_delete");
        } else {
            header("Location: /RoomFlow/Comodidades?msg=error_delete");
        }
        exit();
    }
}