<?php 

$Routes = [
    '/' => 'HomeController@index',
    '/Hospedes' => 'GuestController@list',
    '/Hospedes/@id' => 'GuestController@editar',
    '/Hospedes/Update/@id' => 'GuestController@update',
    '/Hospedes/Cadastrar' => 'GuestController@create'
];