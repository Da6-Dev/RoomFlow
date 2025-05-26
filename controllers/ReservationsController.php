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



    public function list() {
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
}
