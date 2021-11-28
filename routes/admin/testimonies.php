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

//Rota de edição de depoimentos
$obRouter->get('/admin/testimonies/{id}/edit', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request, $id) {
    return new Response(200, Admin\Testimonies::getEditTestimony($request, $id));
  }
]);

//Rota de edição de depoimentos (POST)
$obRouter->post('/admin/testimonies/{id}/edit', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request, $id) {
    return new Response(200, Admin\Testimonies::setEditTestimony($request, $id));
  }
]);