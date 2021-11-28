<?php

use App\Http\Response;
use \App\Controller\Admin;

//Rota do Login
$obRouter->get('/admin/login', [
  'middlewares' => [
  'require-admin-logout'  
  ],
  function($request) {
    return new Response(200, Admin\Login::getLogin($request));
  }
]);

//Rota do login (POST)
$obRouter->post('/admin/login', [
  'middlewares' => [
    'require-admin-logout'  
    ],
  function($request) {
    return new Response(200, Admin\Login::setLogin($request));
  }
]);

//Rota do Logout
$obRouter->get('/admin/logout', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\Login::setLogout($request));
  }
]);