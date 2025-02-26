<?php 

$Routes = [
    '/' => 'HomeController@index',
    '/Hospedes' => 'GuestController@list',
    '/Hospedes/@id' => 'GuestController@editar',
    '/Hospedes/Update/@id' => 'GuestController@update',
    '/Hospedes/Cadastrar' => 'GuestController@create',
    '/Hospedes/Deletar' => 'GuestController@delete',
    '/Comodidades' => 'AmenitiesController@list',
    '/Comodidades/Cadastrar' => 'AmenitiesController@create',
    '/Comodidades/@id' => 'AmenitiesController@editar',
    '/Comodidades/Update/@id' => 'AmenitiesController@update',
    '/Comodidades/Deletar' => 'AmenitiesController@delete',
    '/Acomodacoes' => 'AccommodationsController@list',	
    '/Acomodacoes/Cadastrar' => 'AccommodationsController@create',
    '/Acomodacoes/@id' => 'AccommodationsController@editar',
    '/Acomodacoes/Update/@id' => 'AccommodationsController@update',
    '/Acomodacoes/Deletar' => 'AccommodationsController@delete',
];