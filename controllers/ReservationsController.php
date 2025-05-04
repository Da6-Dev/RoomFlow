<?php

    class ReservationsController extends RenderView
    {

        public function create()  {

        $reservations = new ReservationsModel();
            
        $this ->LoadView('ReservasCadastrar', [
                'Title' => 'Cadastrar Reserva',
                'father' => 'Reservas',
                'page' => 'Cadastrar',
                'hospedes' => $reservations->hospedesGetAll(),
                'acomodacoes' => $reservations->acomodacoesGetDisponiveis(),
            ]); 
        }  

    }

?>