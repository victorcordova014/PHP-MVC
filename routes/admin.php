<?php

use App\Http\Response;
use \App\Controller\Admin;

//Rota do admin
$obRouter->get('/admin', [
  function() {
    return new Response(200, 'Admin :)');
  }
]);

//Rota do Login
$obRouter->get('/admin/login', [
  function($request) {
    return new Response(200, Admin\Login::getLogin($request));
  }
]);

//Rota do login (POST)
$obRouter->post('/admin/login', [
  function($request) {
    return new Response(200, Admin\Login::setLogin($request));
  }
]);