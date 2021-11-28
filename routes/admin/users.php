<?php

use App\Http\Response;
use \App\Controller\Admin;

//Rota de listagem de usuários 
$obRouter->get('/admin/users', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\User::getUsers($request));
  }
]);

//Rota de Cadastro de usuário
$obRouter->get('/admin/users/new', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\User::getNewUser($request));
  }
]);

//Rota de Cadastro de usuário (POST)
$obRouter->post('/admin/users/new', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request) {
    return new Response(200, Admin\User::setNewUser($request));
  }
]);

//Rota de edição de usuário
$obRouter->get('/admin/users/{id}/edit', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request, $id) {
    return new Response(200, Admin\User::getEditUser($request, $id));
  }
]);

//Rota de edição de usuário (POST)
$obRouter->post('/admin/users/{id}/edit', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request, $id) {
    return new Response(200, Admin\User::setEditUser($request, $id));
  }
]);

//Rota de exclusão de usuário
$obRouter->get('/admin/users/{id}/delete', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request, $id) {
    return new Response(200, Admin\Testimonies::getDeleteTestimony($request, $id));
  }
]);

//Rota de exclusão de usuário (POST)
$obRouter->post('/admin/users/{id}/delete', [
  'middlewares' => [
    'require-admin-login'  
    ],
  function($request, $id) {
    return new Response(200, Admin\Testimonies::setDeleteTestimony($request, $id));
  }
]);