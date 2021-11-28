<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Alert{

  /**
   * Método responsável por retornar uma mensagem de sucesso
   * @param String $message
   * @return String
   */
  public static function getSuccess($message) {
    return View::render('admin/alert/status', [
      'tipo' => 'success',
      'mensagem' => $message
    ]);
  }

  /**
   * Método responsável por retornar uma mensagem de erro
   * @param String $message
   * @return String
   */
  public static function getError($message) {
    return View::render('admin/alert/status', [
      'tipo' => 'danger',
      'mensagem' => $message
    ]);
  }

}