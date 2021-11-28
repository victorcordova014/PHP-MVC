<?php

use App\Http\Response;
use \App\Controller\Admin;

//Rota de listagem de depoimentos
$obRouter->get('/admin/testimonies', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\Testimonies::getTestimonies($request));
  }
]);

//Rota de Cadastro de depoimentos
$obRouter->get('/admin/testimonies/new', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\Testimonies::getNewTestimony($request));
  }
]);

//Rota de Cadastro de depoimentos (POST)
$obRouter->post('/admin/testimonies/new', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\Testimonies::setNewTestimony($request));
  }
]);