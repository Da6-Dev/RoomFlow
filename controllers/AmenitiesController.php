<?php

class AmenitiesController extends RenderView
{
    public function list()
    {
        $amenities = new AmenitiesModel();

        $this->LoadView('Comodidades', [
            'Title' => 'Listagem de todas as Comodidades',
            'Amenities' => $amenities->listar(),
            'father' => 'Comodidades',
            'page' => 'Listar',
        ]);
    }

    public function create()
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
            ];

            $amenities = new AmenitiesModel();
            // Validar nome
            if (empty($data['nome'])) {
                $errors['nome'] = 'O campo nome é obrigatório.';
            } elseif (strlen($data['nome']) < 3) {
                $errors['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['nome'])) {
                $errors['nome'] = 'O nome deve conter apenas letras e espaços.';
            } elseif ($amenities->getAmenityByName($data['nome'])) {
                $errors['nome'] = 'Essa comodidade já existe.';
            }

            // Se não houver erros, inserir no banco de dados
            if (empty($errors)) {
                $amenities = new AmenitiesModel();
                $amenities->create($data);
                header('Location: /RoomFlow/Comodidades/Cadastrar?msg=success_create');
                exit();
            }
        }

        $this->LoadView('ComodidadesCadastrar', [
            'Title' => 'Cadastrar Comodidade',
            'errors' => $errors,
            'father' => 'Comodidades',
            'page' => 'Cadastrar',
        ]);
    }

    public function editar($id)
    {
        $amenities = new AmenitiesModel();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Função de limpeza de entrada
            function cleanInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            // Definir dados
            $data = [
                'id' => $id,
                'nome' => cleanInput($_POST['nome'] ?? ''),
            ];

            // Validar nome
            if (empty($data['nome'])) {
                $errors['nome'] = 'O campo nome é obrigatório.';
            } elseif (strlen($data['nome']) < 3) {
                $errors['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
            }

            // Se não houver erros, atualizar no banco de dados
            if (empty($errors)) {
                $amenities->update($data);
                header('Location: /RoomFlow/Comodidades/' . $id . '?msg=success_update');
                exit();
            }
        } else {
            $data = $amenities->getAmenityById($id);
        }

        $this->LoadView('ComodidadesEditar', [
            'Title' => 'Editar Comodidade',
            'errors' => $errors,
            'data' => $data,
            'father' => 'Comodidades',
            'page' => 'Editar',
        ]);
    }

    public function delete($id)
    {
        $id_guest = $_POST['id'];

        $amenities = new AmenitiesModel();

        $success = $amenities->delete($id_guest);

        if ($success) {
            header("Location: /RoomFlow/Comodidades?msg=success_delete");
            exit();
        } else {
            header("Location: /RoomFlow/Comodidades?msg=error_delete");
            exit();
        }
    }
}
