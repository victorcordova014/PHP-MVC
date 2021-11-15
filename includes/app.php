<?php

require __DIR__.'/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇÕES DE BANCO DE DADOS
Database::config(
  getenv('DB_HOST'),
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS'),
  getenv('DB_PORT'),
  );

define('URL', getenv('URL'));

View::init([
  'URL' => URL
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES
MiddlewareQueue::setMap([
  'maintenance' => \App\Http\Middleware\Maintenance::class,
  'require-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
  'require-admin-login' => \App\Http\Middleware\RequireAdminLogin::class
  
]);

MiddlewareQueue::setDefault([
  'maintenance'
]);