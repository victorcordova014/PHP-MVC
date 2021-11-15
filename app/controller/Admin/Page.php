<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page {

  /**
   * Método resposável por retornar o conteúdo (view) da estrutura genérica da página do painel
   * @param String $title
   * @param String $content
   * @return String
   */
  public static function getPage($title, $content) {
    return View::render('Admin/page', [
      'title'   => $title,
      'content' => $content
    ]);
  }
}