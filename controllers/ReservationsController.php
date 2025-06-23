<?php

class ReservationsController extends RenderView
{

    public function create()
    {

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Função de limpeza de entrada
            function cleanInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            $data = [
                'hospede' => cleanInput($_POST['hospede']),
                'acomodacao' => cleanInput($_POST['acomodacao']),
                'data_checkin' => cleanInput($_POST['checkin']),
                'data_checkout' => cleanInput($_POST['checkout']),
                'status' => cleanInput($_POST['status']),
                'metodo_pagamento' => cleanInput($_POST['metodo_pagamento']),
                'observacoes' => cleanInput($_POST['observacoes']),
            ];

            if (empty($errors)) {
                $reservations = new ReservationsModel();

                $success = $reservations->create($data);

                if ($success) {
                    header('Location: /RoomFlow/Reservas/Cadastrar?msg=success_create');
                    exit;
                } else {
                    $errors['general'] = 'Erro ao cadastrar a reserva.';
                }
            }
        }

        $reservations = new ReservationsModel();

        $this->LoadView('ReservasCadastrar', [
            'Title' => 'Cadastrar Reserva',
            'father' => 'Reservas',
            'page' => 'Cadastrar',
            'hospedes' => $reservations->hospedesGetAll(),
            'acomodacoes' => $reservations->acomodacoesGetDisponiveis(),
            'data' => $date = date('Y-m-d'),
            'datasReservadas' => $reservations->getReservationsDate(),
            'errors' => $errors,
        ]);
    }



    public function list()
    {
        $reservations = new ReservationsModel();
        $reservas = $reservations->getAllReservations();

        foreach ($reservas as &$reserva) {
            $reserva['acomodacao'] = $reservations->getNameAccommodationById($reserva['id_acomodacao']);
            $reserva['hospede'] = $reservations->getNameGuestById($reserva['id_hospede']);
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

            if ($id) {
                $reservations = new ReservationsModel();
                $success = $reservations->delete($id);

                if ($success) {
                    header('Location: /RoomFlow/Reservas?msg=success_delete');
                    exit;
                } else {
                    header('Location: /RoomFlow/Reservas?msg=error_delete');
                    exit;
                }
            } else {
                header('Location: /RoomFlow/Reservas?msg=error_invalid_id');
                exit;
            }
        }
    }

    public function editar($id)
    {
        $reservations = new ReservationsModel();
        $reserva = $reservations->getReservationById($id);

        $this->LoadView('ReservasEditar', [
            'Title' => 'Editar Reserva',
            'father' => 'Reservas',
            'page' => 'Editar',
            'reserva' => $reserva,
            'hospedes' => $reservations->hospedesGetAll(),
            'acomodacoes' => $reservations->acomodacoesGetDisponiveis(),
            'data' => $date = date('Y-m-d'),
            'datasReservadas' => $reservations->getReservationsDate(),
        ]);
    }

    public function update($id)
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Função de limpeza de entrada
            function cleanInput($data)
            {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            $data = [
                'id' => $id,
                'hospede' => cleanInput($_POST['hospede']),
                'acomodacao' => cleanInput($_POST['acomodacao']),
                'data_checkin' => cleanInput($_POST['checkin']),
                'data_checkout' => cleanInput($_POST['checkout']),
                'status' => cleanInput($_POST['status']),
                'metodo_pagamento' => cleanInput($_POST['metodo_pagamento']),
                'observacoes' => cleanInput($_POST['observacoes']),
            ];

            if (empty($errors)) {
                $reservations = new ReservationsModel();
                $success = $reservations->update($data);

                if ($success) {
                    header('Location: /RoomFlow/Reservas?msg=success_update');
                    exit;
                } else {
                    $errors['general'] = 'Erro ao atualizar a reserva.';
                }
            }
        }

        $reservations = new ReservationsModel();
        $reserva = $reservations->getReservationById($id);

        $this->LoadView('ReservasEditar', [
            'Title' => 'Editar Reserva',
            'father' => 'Reservas',
            'page' => 'Editar',
            'reserva' => $reserva,
            'hospedes' => $reservations->hospedesGetAll(),
            'acomodacoes' => $reservations->acomodacoesGetDisponiveis(),
            'errors' => $errors,
            'data' => $date = date('Y-m-d'),
            'datasReservadas' => $reservations->getReservationsDate(),
        ]);
    }

    public function Historico()
    {
        // Busca os dados do histórico usando o novo método do model
        $reservations = new ReservationsModel();
        $historico = $reservations->getHistoricoReservas();

        // Carrega a view, passando os dados do histórico
        $this->LoadView('ReservasHistorico', [
            'Title'         => 'Histórico de Reservas',
            'father'        => 'Reservas',
            'page'          => 'Histórico',
            'historico'     => $historico // Envia a lista para a view
        ]);
    }
}
