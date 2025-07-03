<?php

class AccommodationsController extends RenderView
{
    private $accommodationsModel;
    private $amenitiesModel;

    public function __construct()
    {
        $this->accommodationsModel = new AccommodationsModel();
        $this->amenitiesModel = new AmenitiesModel();
    }

    private function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    private function collectDataFromRequest()
    {
        $precoFormatado = $_POST['preco'] ?? '0';
        $precoSemSimbolos = str_replace(['R$', ' ', '.'], '', $precoFormatado);
        $precoNumerico = str_replace(',', '.', $precoSemSimbolos);

        return [
            'tipo' => $this->cleanInput($_POST['tipo'] ?? ''),
            'numero' => $this->cleanInput($_POST['numero'] ?? ''),
            'descricao' => $this->cleanInput($_POST['descricao'] ?? ''),
            'status' => $this->cleanInput($_POST['status'] ?? ''),
            'capacidade' => $this->cleanInput($_POST['capacidade'] ?? ''),
            'preco' => $this->cleanInput($precoNumerico),
            'minimo_noites' => $this->cleanInput($_POST['minimo_noites'] ?? ''),
            'camas_casal' => $this->cleanInput($_POST['camas_casal'] ?? ''),
            'camas_solteiro' => $this->cleanInput($_POST['camas_solteiro'] ?? ''),
            'check_in_time' => $this->cleanInput($_POST['check_in_time'] ?? ''),
            'check_out_time' => $this->cleanInput($_POST['check_out_time'] ?? ''),
            'amenidades' => $_POST['amenidades'] ?? [],
            'delete_imagens' => $_POST['delete_imagens'] ?? [],
            'imagens' => $_FILES['imagens'] ?? [],
            'image_order' => $_POST['image_order'] ?? [],
        ];
    }

    private function validateData($data)
    {
        $errors = [];
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

        foreach ($validations as $field => $validation) {
            if ($validation['status'] === 'error') {
                $errors[$field] = $validation['msg'];
            }
        }

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
        return $errors;
    }

    public function list()
    {
        $this->LoadView('Acomodacoes', [
            'Title' => 'Listagem de todas as Acomodações',
            'Accommodations' => $this->accommodationsModel->listar(),
            'father' => 'Acomodações',
            'page' => 'Listar',
            'page_script' => 'Acomodacoes.js',
        ]);
    }

    public function create()
    {
        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data);
            
            // VALIDAÇÃO CORRIGIDA: Verifica a combinação de tipo e número.
            if ($this->accommodationsModel->findByTypeAndNumber($data['tipo'], $data['numero'])) {
                // Usando 'general' para exibir o erro no alerta principal do topo do formulário.
                $errors['general'] = 'Já existe uma acomodação com este mesmo Tipo e Número.';
            }

            if (empty($errors)) {
                if ($this->accommodationsModel->create($data)) {
                    header('Location: /RoomFlow/Dashboard/Acomodacoes?msg=success_create');
                    exit();
                } else {
                    $errors['general'] = 'Erro ao cadastrar a acomodação. Tente novamente.';
                }
            }
        }

        $this->LoadView('AcomodacoesCadastrar', [
            'Title' => 'Cadastrar Acomodação',
            'errors' => $errors,
            'data' => $data,
            'father' => 'Acomodações',
            'page' => 'Cadastrar',
            'Amenities' => $this->amenitiesModel->listar(),
            'page_script' => 'AcomodacoesCadastrar.js',
        ]);
    }

    public function editar($id)
    {
        $this->LoadView('AcomodacoesEditar', [
            'acomodacao' => $this->accommodationsModel->getAccommodationById($id),
            'amenidades_acomodacao' => $this->amenitiesModel->getAmenitiesAccommodations($id),
            'amenidades' => $this->amenitiesModel->listar(),
            'Title' => 'Editar Acomodação',
            'father' => 'Acomodações',
            'page' => 'Editar',
            'imagens' => $this->accommodationsModel->getImagesByAccommodationId($id),
            'page_script' => 'AcomodacoesEditar.js',
        ]);
    }

    public function update($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data);

            // VALIDAÇÃO CORRIGIDA: Verifica a combinação, ignorando o próprio registro que está sendo editado.
            if ($this->accommodationsModel->findByTypeAndNumber($data['tipo'], $data['numero'], $id)) {
                $errors['general'] = 'Já existe outra acomodação com este mesmo Tipo e Número.';
            }

            if (empty($errors)) {
                if ($this->accommodationsModel->update($id, $data)) {
                    header('Location: /RoomFlow/Dashboard/Acomodacoes?msg=success_update');
                    exit();
                } else {
                    $errors['general'] = 'Erro ao atualizar a acomodação. Tente novamente.';
                }
            }
        }

        // Ao falhar, recarrega a view de edição com os erros
        $this->LoadView('AcomodacoesEditar', [
            'acomodacao' => $this->accommodationsModel->getAccommodationById($id),
            'amenidades_acomodacao' => $this->amenitiesModel->getAmenitiesAccommodations($id),
            'amenidades' => $this->amenitiesModel->listar(),
            'Title' => 'Editar Acomodação',
            'father' => 'Acomodações',
            'page' => 'Editar',
            'errors' => $errors,
            'imagens' => $this->accommodationsModel->getImagesByAccommodationId($id),
            'page_script' => 'AcomodacoesEditar.js',
        ]);
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /RoomFlow/Dashboard/Acomodacoes?msg=error_invalid_id');
            exit();
        }

        if ($this->accommodationsModel->delete($id)) {
            header('Location: /RoomFlow/Dashboard/Acomodacoes?msg=success_delete');
        } else {
            header('Location: /RoomFlow/Dashboard/Acomodacoes?msg=error_delete');
        }
        exit();
    }
}