<?php

namespace App\Session\Admin;

class Login {

  /**
   * Método responsável por iniciar a sessão
   */
  private static function init() {
    //Verifica se a sessão não está ativa
    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();
    }
  }
  /**
   * Método responsável por criar o login do usuário
   * @param User $obUser
   * @return boolean
   */
  public static function login($obUser) {
    //Inicia a sessão
    self::init();

    $_SESSION['admin']['usuario'] = [
      'id'    => $obUser->id,
      'nome'  => $obUser->nome,
      'email' => $obUser->email
    ];

    //Sucesso
    return true;
  }

  /**
   * Método responsável por verificar se o usuário está logado
   * @return boolean
   */
  public static function isLogged() {
    //inicia a sessão
    self::init();

    //Retorna a verificação
    return isset($_SESSION['admin']['usuario']['id']);
  }

  /**
   * Método responsável por deslogar o usuário
   * @return boolean
   */
  public static function logout() {
    //Inicia a sessão
    self::init();

    //Desloga o usuário
    unset($_SESSION['admin']['usuario']);

    //Sucesso em deslogar o usuário
    return true;
  }
}