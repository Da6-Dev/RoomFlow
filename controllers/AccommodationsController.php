<?php

class AccommodationsController extends RenderView
{
    private $accommodationsModel;
    private $amenitiesModel;

    // 1. O construtor inicializa os models, que serão usados em toda a classe.
    public function __construct()
    {
        $this->accommodationsModel = new AccommodationsModel();
        $this->amenitiesModel = new AmenitiesModel();
    }

    // 2. Método para limpar a entrada de dados.
    private function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // 3. Método privado para coletar dados do formulário.
    private function collectDataFromRequest()
    {
        return [
            'tipo' => $this->cleanInput($_POST['tipo'] ?? ''),
            'numero' => $this->cleanInput($_POST['numero'] ?? ''),
            'descricao' => $this->cleanInput($_POST['descricao'] ?? ''),
            'status' => $this->cleanInput($_POST['status'] ?? ''),
            'capacidade' => $this->cleanInput($_POST['capacidade'] ?? ''),
            'preco' => $this->cleanInput($_POST['preco'] ?? ''),
            'minimo_noites' => $this->cleanInput($_POST['minimo_noites'] ?? ''),
            'camas_casal' => $this->cleanInput($_POST['camas_casal'] ?? ''),
            'camas_solteiro' => $this->cleanInput($_POST['camas_solteiro'] ?? ''),
            'check_in_time' => $this->cleanInput($_POST['check_in_time'] ?? ''),
            'check_out_time' => $this->cleanInput($_POST['check_out_time'] ?? ''),
            'amenidades' => $_POST['amenidades'] ?? [],
            'imagens' => $_FILES['imagens'] ?? [],
            'delete_imagens' => $_POST['delete_imagens'] ?? [],
            'imagem_capa' => $_POST['imagem_capa'] ?? '',
        ];
    }

    // 4. Método privado para validar os dados coletados.
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
            'imagens_capa' => $this->accommodationsModel->getImagensCapa(),
        ]);
    }

    public function create()
    {
        $errors = [];
        $data = []; // Inicializa para evitar erro na view no primeiro carregamento

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data);

            if (empty($errors)) {
                if ($this->accommodationsModel->getAccommodationByName($data['tipo']) && $this->accommodationsModel->getAccommodationByNumber($data['numero'])) {
                    $errors['exists'] = 'Essa acomodação já existe.';
                } else {
                    if ($this->accommodationsModel->create($data)) {
                        header('Location: /RoomFlow/Acomodacoes/Cadastrar?msg=success_create');
                        exit();
                    } else {
                        $errors['general'] = 'Erro ao cadastrar a acomodação. Tente novamente.';
                    }
                }
            }
        }

        $this->LoadView('AcomodacoesCadastrar', [
            'Title' => 'Cadastrar Acomodação',
            'errors' => $errors,
            'data' => $data, // Envia dados submetidos de volta para preencher o formulário
            'father' => 'Acomodações',
            'page' => 'Cadastrar',
            'Amenities' => $this->amenitiesModel->listar(),
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
        ]);
    }

    public function update($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $errors = $this->validateData($data);

            if (empty($errors)) {
                if ($this->accommodationsModel->update($id, $data)) {
                    header('Location: /RoomFlow/Acomodacoes?msg=success_update');
                    exit();
                } else {
                    $errors['general'] = 'Erro ao atualizar a acomodação. Tente novamente.';
                }
            }
        }

        // Carrega a view com os dados e possíveis erros
        $this->LoadView('AcomodacoesEditar', [
            'acomodacao' => $this->accommodationsModel->getAccommodationById($id),
            'amenidades_acomodacao' => $this->amenitiesModel->getAmenitiesAccommodations($id),
            'amenidades' => $this->amenitiesModel->listar(),
            'Title' => 'Editar Acomodação',
            'father' => 'Acomodações',
            'page' => 'Editar',
            'errors' => $errors,
            'imagens' => $this->accommodationsModel->getImagesByAccommodationId($id),
        ]);
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            header('Location: /RoomFlow/Acomodacoes?msg=error_invalid_id');
            exit();
        }

        if ($this->accommodationsModel->delete($id)) {
            header('Location: /RoomFlow/Acomodacoes?msg=success_delete');
        } else {
            header('Location: /RoomFlow/Acomodacoes?msg=error_delete');
        }
        exit();
    }
}