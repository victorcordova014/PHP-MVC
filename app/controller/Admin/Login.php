<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User;

class Login extends Page{
  
  /** 
   * Método responsável por retornar a renderização da página de login
   * @param Request $request
   * @param String $errorMessage
   * @return String
   */
  public static function getLogin($request, $errorMessage) {
    //Status do login
    $status = !is_null($errorMessage) ? View::render('admin/login/status', [
      'mensagem' => $errorMessage
    ]) : '';
    
    //Conteúdo da Página de login
    $content = View::render('admin/login', [
      'status' => $status
    ]);

    //Retorna a página completa
    return parent::getPage('Login > Vitinho', $content);
  }

  /**
   * Método responsável por definir o login do usuário
   * @param Request $request
   */
  public static function setLogin($request) {
    //POST VARS
    $postVars = $request->getPostVars();
    $email    = $postVars['email'] ?? '';
    $senha    = $postVars['senha'] ?? '';

    //BUSCA O USUÁRIO PELO E-MAIL
    $obUser = User::getUserByEmail($email);
    if(!$obUser instanceof User) {
      return self::getLogin($request, 'E-mail ou senha inválidos');
    }

    //verifica a senha do usuário
    if (!password_verify($senha, $obUser->senha)) {
      return self::getLogin($request, 'E-mail ou senha inválidos');
    }
  }
}