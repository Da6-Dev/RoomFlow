<?php 

$Routes = [
    '/' => 'HomeController@index',
    '/Hospedes' => 'GuestController@list',
    '/Hospedes/@id' => 'GuestController@editar',
    '/Hospedes/Cadastrar' => 'GuestController@create'
];