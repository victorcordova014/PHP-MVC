<?php

use App\Http\Response;
use \App\Controller\Admin;

//Rota do admin
$obRouter->get('/admin', [
  function() {
    return new Response(200, 'Admin :)');
  }
]);

//Rota do admin
$obRouter->get('/login', [
  function() {
    return new Response(200, 'login :)');
  }
]);