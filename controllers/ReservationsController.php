<?php

class ReservationsController extends RenderView
{
    private $reservationsModel;

    // 1. O construtor inicializa o model.
    public function __construct()
    {
        $this->reservationsModel = new ReservationsModel();
    }

    // 2. Métodos privados para limpar e coletar dados.
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

    public function create()
    {
        $errors = [];
        $data = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            // NOTA: Adicionar lógica de validação aqui se necessário.
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
        ]);
    }

    public function list()
    {
        // NOTA DE PERFORMANCE: O ideal é que o método getAllReservations() no Model
        // já retorne os nomes do hóspede e da acomodação usando JOINs na consulta SQL.
        // O loop abaixo pode causar múltiplas consultas desnecessárias ao banco de dados (problema N+1).
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
         // Redireciona se o acesso não for via POST
        header('Location: /RoomFlow/Reservas');
        exit;
    }

    public function editar($id)
    {
        $reserva = $this->reservationsModel->getReservationById($id);

        if(!$reserva) {
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
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectDataFromRequest();
            $data['id'] = $id;

            // NOTA: Adicionar lógica de validação aqui se necessário.
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
            'reserva' => $this->reservationsModel->getReservationById($id),
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
            'Title'         => 'Histórico de Reservas',
            'father'        => 'Reservas',
            'page'          => 'Histórico',
            'historico'     => $this->reservationsModel->getHistoricoReservas()
        ]);
    }
}