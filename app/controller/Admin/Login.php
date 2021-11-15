<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Login extends Page{
  
  /** 
   * Método responsável por retornar a renderização da página de login
   * @param Request $request
   * @return String
   */
  public static function getLogin($request) {
    //Conteúdo da Página de login
    $content = View::render('admin/login', []);

    //Retorna a página completa
    return parent::getPage('Login > Vitinho', $content);
  }
}