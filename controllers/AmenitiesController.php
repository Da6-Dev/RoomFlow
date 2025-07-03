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
            'page_script' => 'Comodidades.js',
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
                if ($this->amenitiesModel->create($data)) {
                    header('Location: /RoomFlow/Dashboard/Comodidades?msg=success_create');
                } else {
                    $errors['general'] = 'Ocorreu um erro ao salvar a comodidade.';
                }
                exit();
            }
        }

        $this->LoadView('Comodidades', [
            'Title' => 'Gestão de Comodidades',
            'Amenities' => $this->amenitiesModel->listar(),
            'errors' => $errors,
            'data' => $data,
            'father' => 'Comodidades',
            'page' => 'Listar',
            'form_action' => 'create',
            'page_script' => 'Comodidades.js',
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data, $id);

            if (empty($errors)) {
                $data['id'] = $id;
                if ($this->amenitiesModel->update($data)) {
                    header('Location: /RoomFlow/Dashboard/Comodidades?msg=success_update');
                } else {
                    $errors['general'] = 'Ocorreu um erro ao atualizar a comodidade.';
                }
                exit();
            }

            $this->LoadView('Comodidades', [
                'Title' => 'Editar Comodidade',
                'errors' => $errors,
                'data' => $data, 
                'father' => 'Comodidades',
                'page' => 'Editar',
                'Amenities' => $this->amenitiesModel->listar(),
                'form_action' => 'update',
                'update_error_id' => $id,
                'page_script' => 'Comodidades.js',
            ]);
            exit();
        }
        
        header('Location: /RoomFlow/Dashboard/Comodidades');
        exit();
    }


    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if ($id && $this->amenitiesModel->delete($id)) {
            header("Location: /RoomFlow/Dashboard/Comodidades?msg=success_delete");
        } else {
            header("Location: /RoomFlow/Dashboard/Comodidades?msg=error_delete");
        }
        exit();
    }
}