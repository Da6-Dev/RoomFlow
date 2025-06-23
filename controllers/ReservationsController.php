<?php

class ReservationsController extends RenderView
{
    private $reservationsModel;

    public function __construct()
    {
        $this->reservationsModel = new ReservationsModel();
    }

    private function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    private function collectDataFromRequest()
    {
        return [
            'hospede' => $this->cleanInput($_POST['hospede'] ?? ''),
            'acomodacao' => $this->cleanInput($_POST['acomodacao'] ?? ''),
            'data_checkin' => $this->cleanInput($_POST['checkin'] ?? ''),
            'data_checkout' => $this->cleanInput($_POST['checkout'] ?? ''),
            'status' => $this->cleanInput($_POST['status'] ?? ''),
            'metodo_pagamento' => $this->cleanInput($_POST['metodo_pagamento'] ?? ''),
            'observacoes' => $this->cleanInput($_POST['observacoes'] ?? ''),
        ];
    }

    /**
     * NOVO MÉTODO PARA VALIDAR OS DADOS DA RESERVA
     * @param array $data Os dados a serem validados.
     * @param bool $isUpdate Flag para diferenciar criação de atualização.
     * @return array Array de erros.
     */
    private function validateReservationData(array $data, bool $isUpdate = false): array
    {
        $errors = [];

        // Validações básicas de campos obrigatórios
        if (empty($data['hospede'])) $errors['hospede'] = "O campo hóspede é obrigatório.";
        if (empty($data['acomodacao'])) $errors['acomodacao'] = "O campo acomodação é obrigatório.";
        if (empty($data['data_checkin'])) $errors['data_checkin'] = "A data de check-in é obrigatória.";
        if (empty($data['data_checkout'])) $errors['data_checkout'] = "A data de checkout é obrigatória.";

        // Se as datas foram fornecidas, valida a lógica entre elas
        if (!empty($data['data_checkin']) && !empty($data['data_checkout'])) {
            try {
                $checkinDate = new DateTime($data['data_checkin']);
                $checkoutDate = new DateTime($data['data_checkout']);
                $today = new DateTime('today');

                // 1. Garante que o checkout é DEPOIS do check-in
                if ($checkoutDate <= $checkinDate) {
                    $errors['data_checkout'] = 'A data de checkout deve ser posterior à data de check-in.';
                }

                // 2. Para NOVAS reservas, garante que o check-in não é no passado
                if (!$isUpdate && $checkinDate < $today) {
                    $errors['data_checkin'] = 'A data de check-in não pode ser uma data passada.';
                }
            } catch (Exception $e) {
                $errors['general'] = 'As datas fornecidas são inválidas.';
            }
        }

        return $errors;
    }


    public function create()
    {
        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            // Utiliza o novo método de validação
            $errors = $this->validateReservationData($data);

            if (empty($errors)) {
                if ($this->reservationsModel->create($data)) {
                    header('Location: /RoomFlow/Reservas/Cadastrar?msg=success_create');
                    exit;
                } else {
                    $errors['general'] = 'Erro ao cadastrar a reserva.';
                }
            }
        }

        $this->LoadView('ReservasCadastrar', [
            'Title' => 'Cadastrar Reserva',
            'father' => 'Reservas',
            'page' => 'Cadastrar',
            'hospedes' => $this->reservationsModel->hospedesGetAll(),
            'acomodacoes' => $this->reservationsModel->acomodacoesGetDisponiveis(),
            'data' => date('Y-m-d'),
            'datasReservadas' => $this->reservationsModel->getReservationsDate(),
            'errors' => $errors,
            'formData' => $data, // Envia dados de volta para o form
        ]);
    }

    public function list()
    {
        $reservas = $this->reservationsModel->getAllReservations();
        foreach ($reservas as &$reserva) {
            $reserva['acomodacao'] = $this->reservationsModel->getNameAccommodationById($reserva['id_acomodacao']);
            $reserva['hospede'] = $this->reservationsModel->getNameGuestById($reserva['id_hospede']);
        }

        $this->LoadView('Reservas', [
            'Title' => 'Reservas',
            'father' => 'Reservas',
            'page' => 'Listar',
            'Reservas' => $reservas,
        ]);
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id && $this->reservationsModel->delete($id)) {
                header('Location: /RoomFlow/Reservas?msg=success_delete');
            } else {
                header('Location: /RoomFlow/Reservas?msg=error_delete');
            }
            exit;
        }
        header('Location: /RoomFlow/Reservas');
        exit;
    }

    public function editar($id)
    {
        $reserva = $this->reservationsModel->getReservationById($id);
        if (!$reserva) {
            header('Location: /RoomFlow/Reservas?msg=not_found');
            exit;
        }

        $this->LoadView('ReservasEditar', [
            'Title' => 'Editar Reserva',
            'father' => 'Reservas',
            'page' => 'Editar',
            'reserva' => $reserva,
            'hospedes' => $this->reservationsModel->hospedesGetAll(),
            'acomodacoes' => $this->reservationsModel->acomodacoesGetDisponiveis(),
            'data' => date('Y-m-d'),
            'datasReservadas' => $this->reservationsModel->getReservationsDate(),
        ]);
    }

    public function update($id)
    {
        $data = $this->collectDataFromRequest();
        $data['id'] = $id;
        
        // Utiliza o novo método de validação, marcando como "update"
        $errors = $this->validateReservationData($data, true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($errors)) {
                if ($this->reservationsModel->update($data)) {
                    header('Location: /RoomFlow/Reservas?msg=success_update');
                    exit;
                } else {
                    $errors['general'] = 'Erro ao atualizar a reserva.';
                }
            }
        }

        // Recarrega a view com os dados e erros em caso de falha.
        $this->LoadView('ReservasEditar', [
            'Title' => 'Editar Reserva',
            'father' => 'Reservas',
            'page' => 'Editar',
            'reserva' => array_merge($this->reservationsModel->getReservationById($id), $data),
            'hospedes' => $this->reservationsModel->hospedesGetAll(),
            'acomodacoes' => $this->reservationsModel->acomodacoesGetDisponiveis(),
            'errors' => $errors,
            'data' => date('Y-m-d'),
            'datasReservadas' => $this->reservationsModel->getReservationsDate(),
        ]);
    }

    public function Historico()
    {
        $this->LoadView('ReservasHistorico', [
            'Title' => 'Histórico de Reservas',
            'father' => 'Reservas',
            'page' => 'Histórico',
            'historico' => $this->reservationsModel->getHistoricoReservas()
        ]);
    }
}