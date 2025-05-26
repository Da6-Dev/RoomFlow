<?php

class AccommodationsController extends RenderView
{

    public function list()
    {
        $accommodations = new AccommodationsModel();

        $this->LoadView('Acomodacoes', [
            'Title' => 'Listagem de todas as Acomodações',
            'Accommodations' => $accommodations->listar(),
            'father' => 'Acomodações',
            'page' => 'Listar',
            'imagens_capa' => $accommodations->getImagensCapa(),
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
                'tipo' => cleanInput($_POST['tipo'] ?? ''),
                'numero' => cleanInput($_POST['numero'] ?? ''),
                'descricao' => cleanInput($_POST['descricao'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? ''),
                'capacidade' => cleanInput($_POST['capacidade'] ?? ''),
                'preco' => cleanInput($_POST['preco'] ?? ''),
                'minimo_noites' => cleanInput($_POST['minimo_noites'] ?? ''),
                'camas_casal' => cleanInput($_POST['camas_casal'] ?? ''),
                'camas_solteiro' => cleanInput($_POST['camas_solteiro'] ?? ''),
                'check_in_time' => cleanInput($_POST['check_in_time'] ?? ''),
                'check_out_time' => cleanInput($_POST['check_out_time'] ?? ''),
                'amenidades' => $_POST['amenidades'] ?? [],
                'imagens' => $_FILES['imagens'] ?? [],
            ];

            //Validações
            $validations = [
                'tipo' => validarTipo($data['tipo']),
                'numero' => validarNumero($data['numero']),
                'descricao' => validarDescricao($data['descricao']),
                'status' => validarStatus($data['status']),
                'capacidade' => validarCapacidade($data['capacidade']),
                'preco' => validarPreco($data['preco']),
                'minimo_noites' => validarMinimoNoites($data['minimo_noites']),
                'camas_casal' => validarCamasCasal($data['camas_casal']),
                'camas_solteiro' => validarCamasSolteiro($data['camas_solteiro']),
                'check_in_time' => validarCheckInTime($data['check_in_time']),
                'check_out_time' => validarCheckOutTime($data['check_out_time']),
            ];

            // Coletar erros
            foreach ($validations as $field => $validation) {
                if ($validation['status'] === 'error') {
                    $errors[$field] = $validation['msg'];
                }
            }

            //Validar as amenidades
            if (empty($data['amenidades'])) {
                $errors['amenidades'] = 'Selecione pelo menos uma amenidade.';
            } else {
                foreach ($data['amenidades'] as $amenity) {
                    if (!is_numeric($amenity)) {
                        $errors['amenidades'] = 'A amenidade selecionada é inválida.';
                        break;
                    }
                }
            }

            if (empty($errors)) {
                $accommodations = new AccommodationsModel();
                // Verifica se a acomodação já existe
                if ($accommodations->getAccommodationByName($data['tipo']) && $accommodations->getAccommodationByNumber($data['numero'])) {
                    $errors['exists'] = 'Essa acomodação já existe.';
                } else {
                    $success = $accommodations->create($data);
                    if ($success) {
                        // Redirecionar para a página de listagem com mensagem de sucesso
                        header('Location: /RoomFlow/Acomodacoes/Cadastrar?msg=success_create');
                        exit();
                    } else {
                        $errors['general'] = 'Erro ao cadastrar a acomodação. Tente novamente.';
                    }
                }
            }
        }

        $amenities = new AmenitiesModel();
        $this->LoadView('AcomodacoesCadastrar', [
            'Title' => 'Cadastrar Acomodação',
            'errors' => $errors,
            'father' => 'Acomodações',
            'page' => 'Cadastrar',
            'Amenities' => $amenities->listar(),

        ]);
    }

    public function editar($id)
    {
        $id_accommodation = $id;

        $accommodations = new AccommodationsModel();
        $amenities = new AmenitiesModel();

        $this->LoadView('AcomodacoesEditar', [
            'acomodacao' => $accommodations->getAccommodationById($id_accommodation),
            'amenidades_acomodacao' => $amenities->getAmenitiesAccommodations($id_accommodation),
            'amenidades' => $amenities->listar(),
            'Title' => 'Editar Acomodação',
            'father' => 'Acomodações',
            'page' => 'Editar',
            'imagens' => $accommodations->getImagesByAccommodationId($id_accommodation),
        ]);
    }

    public function update($id)
    {
        $errors = [];
        $id_accommodation = $id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Função de limpeza de entrada
            function cleanInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            // Definir dados
            $data = [
                'tipo' => cleanInput($_POST['tipo'] ?? ''),
                'numero' => cleanInput($_POST['numero'] ?? ''),
                'descricao' => cleanInput($_POST['descricao'] ?? ''),
                'status' => cleanInput($_POST['status'] ?? ''),
                'capacidade' => cleanInput($_POST['capacidade'] ?? ''),
                'preco' => cleanInput($_POST['preco'] ?? ''),
                'minimo_noites' => cleanInput($_POST['minimo_noites'] ?? ''),
                'camas_casal' => cleanInput($_POST['camas_casal'] ?? ''),
                'camas_solteiro' => cleanInput($_POST['camas_solteiro'] ?? ''),
                'check_in_time' => cleanInput($_POST['check_in_time'] ?? ''),
                'check_out_time' => cleanInput($_POST['check_out_time'] ?? ''),
                'amenidades' => $_POST['amenidades'] ?? [],
                'imagens' => $_FILES['imagens'] ?? [],
                'delete_imagens' => $_POST['delete_imagens'] ?? [],
                'imagem_capa' => $_POST['imagem_capa'] ?? '',
            ];

            //Validações
            $validations = [
                'tipo' => validarTipo($data['tipo']),
                'numero' => validarNumero($data['numero']),
                'descricao' => validarDescricao($data['descricao']),
                'status' => validarStatus($data['status']),
                'capacidade' => validarCapacidade($data['capacidade']),
                'preco' => validarPreco($data['preco']),
                'minimo_noites' => validarMinimoNoites($data['minimo_noites']),
                'camas_casal' => validarCamasCasal($data['camas_casal']),
                'camas_solteiro' => validarCamasSolteiro($data['camas_solteiro']),
                'check_in_time' => validarCheckInTime($data['check_in_time']),
                'check_out_time' => validarCheckOutTime($data['check_out_time']),
            ];

            // Coletar erros
            foreach ($validations as $field => $validation) {
                if ($validation['status'] === 'error') {
                    $errors[$field] = $validation['msg'];
                }
            }

            //Validar as amenidades
            if (empty($data['amenidades'])) {
                $errors['amenidades'] = 'Selecione pelo menos uma amenidade.';
            } else {
                foreach ($data['amenidades'] as $amenity) {
                    if (!is_numeric($amenity)) {
                        $errors['amenidades'] = 'A amenidade selecionada é inválida.';
                        break;
                    }
                }
            }

            // Se não houver erros, atualizar no banco de dados
            if (empty($errors)) {
                $accommodations = new AccommodationsModel();
                $success = $accommodations->update($id, $data);

                if ($success) {
                    // Redirecionar para a página de listagem com mensagem de sucesso
                    header('Location: /RoomFlow/Acomodacoes?msg=success_update');
                    exit();
                } else {
                    $errors['general'] = 'Erro ao atualizar a acomodação. Tente novamente.';
                }
            }
        }



        $accommodations = new AccommodationsModel();
        $amenities = new AmenitiesModel();

        $this->LoadView('AcomodacoesEditar', [
            'acomodacao' => $accommodations->getAccommodationById($id_accommodation),
            'amenidades_acomodacao' => $amenities->getAmenitiesAccommodations($id_accommodation),
            'amenidades' => $amenities->listar(),
            'Title' => 'Editar Acomodação',
            'father' => 'Acomodações',
            'page' => 'Editar',
            'errors' => $errors,
            'imagens' => $accommodations->getImagesByAccommodationId($id_accommodation),
        ]);
    }

    public function delete()
    {
        $id = $_POST['id'];
        $accommodations = new AccommodationsModel();
        $result = $accommodations->delete($id);
        if ($result) {
            echo "Acomodação excluída com sucesso!";
        } else {
            echo "Erro ao excluir acomodação.";
        }
        header('Location: /RoomFlow/Acomodacoes?msg=success_delete');
        exit();
    }
}
