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