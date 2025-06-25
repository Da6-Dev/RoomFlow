<?php

class AmenitiesController extends RenderView
{
    private $amenitiesModel;

    public function __construct()
    {
        $this->amenitiesModel = new AmenitiesModel();
    }

    private function collectDataFromRequest()
    {
        return [
            'nome' => htmlspecialchars(stripslashes(trim($_POST['nome'] ?? ''))),
        ];
    }

    private function validateData($data, $id = null)
    {
        $errors = [];
        if (empty($data['nome'])) {
            $errors['nome'] = 'O campo nome é obrigatório.';
        } elseif (strlen($data['nome']) < 3) {
            $errors['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
        } elseif (!preg_match("/^[\p{L}\s-]+$/u", $data['nome'])) {
            $errors['nome'] = 'O nome deve conter apenas letras, espaços e hífen.';
        } else {
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
            'Title' => 'Gestão de Comodidades',
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
                header('Location: /RoomFlow/Comodidades?msg=success_create');
                exit();
            }
        }

        // **CORREÇÃO AQUI**: Informa a view que o erro foi no formulário de 'create'
        $this->LoadView('Comodidades', [
            'Title' => 'Gestão de Comodidades',
            'Amenities' => $this->amenitiesModel->listar(),
            'errors' => $errors,
            'data' => $data,
            'father' => 'Comodidades',
            'page' => 'Listar',
            'form_action' => 'create' // Identificador da ação
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data, $id);

            if (empty($errors)) {
                $data['id'] = $id;
                $this->amenitiesModel->update($data);
                header('Location: /RoomFlow/Comodidades?msg=success_update');
                exit();
            }

            // **CORREÇÃO AQUI**: Se houver erro, recarrega a view com os erros
            // e informações para reabrir o modal correto.
            $currentData = $this->amenitiesModel->getAmenityById($id);
            $this->LoadView('Comodidades', [
                'Title' => 'Editar Comodidade',
                'errors' => $errors,
                'data' => array_merge($currentData, $data), // Mantém o que o usuário digitou
                'father' => 'Comodidades',
                'page' => 'Editar',
                'Amenities' => $this->amenitiesModel->listar(),
                'form_action' => 'update', // Identificador da ação
                'update_error_id' => $id    // ID do item com erro para reabrir o modal
            ]);
            exit(); // Garante que o script para aqui
        }
        
        // Se o método for acessado via GET, redireciona para a lista
        header('Location: /RoomFlow/Comodidades');
        exit();
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