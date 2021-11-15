<?php

use \App\Http\Router;
require __DIR__.'/includes/app.php';

$obRouter = new Router(URL);

//Inclui as rotas de páginas
include __DIR__.'/routes/pages.php';
//Inclui as rotas do painel
include __DIR__.'/routes/admin.php';

$obRouter->run()->sendResponse();

