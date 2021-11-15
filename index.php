<?php

use \App\Http\Router;
require __DIR__.'/includes/app.php';

$obRouter = new Router(URL);

//Rota HOME
 
include __DIR__.'/routes/pages.php';

$obRouter->run()->sendResponse();

