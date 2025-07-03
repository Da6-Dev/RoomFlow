<?php 

$Routes = [
    '/' => 'HomeController@index',
    '/Signin' => 'HomeController@signin',
    '/Signup' => 'HomeController@signup',
    '/Dashboard' => 'HomeController@dashboard',
    '/Dashboard/Hospedes' => 'GuestController@list',
    '/Dashboard/Hospedes/@id' => 'GuestController@editar',
    '/Dashboard/Hospedes/Update/@id' => 'GuestController@update',
    '/Dashboard/Hospedes/Cadastrar' => 'GuestController@create',
    '/Dashboard/Hospedes/Deletar' => 'GuestController@delete',

    // Rotas de Comodidades Corrigidas
    '/Dashboard/Comodidades' => 'AmenitiesController@list',
    '/Dashboard/Comodidades/Cadastrar' => 'AmenitiesController@create',
    '/Dashboard/Comodidades/Update/@id' => 'AmenitiesController@update',
    '/Dashboard/Comodidades/Deletar' => 'AmenitiesController@delete',
    
    '/Dashboard/Acomodacoes' => 'AccommodationsController@list',	
    '/Dashboard/Acomodacoes/Cadastrar' => 'AccommodationsController@create',
    '/Dashboard/Acomodacoes/@id' => 'AccommodationsController@editar',
    '/Dashboard/Acomodacoes/Update/@id' => 'AccommodationsController@update',
    '/Dashboard/Acomodacoes/Deletar' => 'AccommodationsController@delete',
    '/Dashboard/Reservas' => 'ReservationsController@list',
    '/Dashboard/Reservas/Cadastrar' => 'ReservationsController@create',
    '/Dashboard/Reservas/@id' => 'ReservationsController@editar',
    '/Dashboard/Reservas/Update/@id' => 'ReservationsController@update',
    '/Dashboard/Reservas/Deletar' => 'ReservationsController@delete',
    '/Dashboard/Reservas/Historico' => 'ReservationsController@Historico',
];