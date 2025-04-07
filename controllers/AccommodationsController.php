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

            if(empty($errors)){
                $accommodations = new AccommodationsModel();
                // Verifica se a acomodação já existe
                if ($accommodations->getAccommodationByName($data['tipo']) && $accommodations->getAccommodationByNumber($data['numero'])) {
                    $errors['exists'] = 'Essa acomodação já existe.';
                }else{
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

    public function update($id)
    {
        $errors = [];

        $accommodations = new AccommodationsModel();
        $accommodation = $accommodations->getAccommodationById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Função de limpeza de entrada
            function cleanInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            // Definir dados
            $data = [
                'nome' => cleanInput($_POST['nome'] ?? ''),
                'descricao' => cleanInput($_POST['descricao'] ?? ''),
                'minimo_noites' => cleanInput($_POST['minimo_noites'] ?? ''),
                'camas_casal' => cleanInput($_POST['camas_casal'] ?? ''),
                'camas_solteiro' => cleanInput($_POST['camas_solteiro'] ?? ''),
                'check_in_time' => cleanInput($_POST['check_in_time'] ?? ''),
                'check_out_time' => cleanInput($_POST['check_out_time'] ?? ''),
                'amenidades' => $_POST['amenidades'] ?? [],
            ];

            // Validar nome
            if (empty($data['nome'])) {
                $errors['nome'] = 'O campo nome é obrigatório.';
            } elseif (strlen($data['nome']) < 3) {
                $errors['nome'] = 'O nome deve ter pelo menos 3 caracteres.';
            } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['nome'])) {
                $errors['nome'] = 'O nome deve conter apenas letras e espaços.';
            }

            // Validar descrição
            if (empty($data['descricao'])) {
                $errors['descricao'] = 'O campo descrição é obrigatório.';
            } elseif (strlen($data['descricao']) < 3) {
                $errors['descricao'] = 'A descrição deve ter pelo menos 3 caracteres.';
            }

            // Validar mínimo de noites
            if (empty($data['minimo_noites'])) {
                $errors['minimo_noites'] = 'O campo mínimo de noites é obrigatório.';
            } elseif ($data['minimo_noites'] < 1) {
                $errors['minimo_noites'] = 'O mínimo de noites deve ser maior que 0.';
            }

            // Validar camas de casal
            if (empty($data['camas_casal'])) {
                $errors['camas_casal'] = 'O campo camas de casal é obrigatório.';
            } elseif ($data['camas_casal'] < 1) {
                $errors['camas_casal'] = 'O número de camas de casal deve ser maior que 0.';
            }

            // Validar camas de solteiro
            if (empty($data['camas_solteiro'])) {
                $errors['camas_solteiro'] = 'O campo camas de solteiro é obrigatório.';
            } elseif ($data['camas_solteiro'] < 1) {
                $errors['camas_solteiro'] = 'O número de camas de solteiro deve ser maior que 0.';
            }

            // Validar hora de check-in
            if (empty($data['check_in_time'])) {
                $errors['check_in_time'] = 'O campo hora de check-in é obrigatório.';
            }

            // Validar hora de check-out
            if (empty($data['check_out_time'])) {
                $errors['check_out_time'] = 'O campo hora de check-out é obrigatório.';
            }

            // Se não houver erros, atualizar no banco de dados
            if (empty($errors)) {
                $accommodations->update($id, $data);
                header('Location: /RoomFlow/Acomodações/Atualizar/' . $id . '?msg=success_update');
                exit();
            }
        }

        $this->LoadView('AcomodaçõesAtualizar', [
            'Title' => 'Atualizar Acomodação',
            'accommodation' => $accommodation,
            'errors' => $errors,
            'father' => 'Acomodações',
            'page' => 'Atualizar',
        ]);
    }

    public function delete($id)
    {
        $accommodations = new AccommodationsModel();
        $accommodations->delete($id);
        header('Location: /RoomFlow/Acomodações?msg=success_delete');
        exit();
    }
}
