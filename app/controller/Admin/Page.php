<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page {

  /**
   * Módulos disponíveis no painel
   * @var array
   */
  private static $modules = [
    'home' => [
      'label' => 'Home',
      'link'  => URL.'/admin'
    ],
    'testimonies' => [
      'label' => 'Depoimentos',
      'link'  => URL.'/admin/testimonies'
    ],
    'users' => [
      'label' => 'Usuários',
      'link'  => URL.'/admin/users'
    ]
  ];

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

  /**
   * Método responsável por renderizar a view do menu do panel
   * @param string $currentModule
   * @return string
   */
  private static function getMenu($currentModule) {
    // Links do menu
    $links = '';

    //Itera os módulos
    foreach (self::$modules as $hash=>$module) {
      $links .= View::render('admin/menu/link', [
        'label' => $module['label'],
        'link'  => $module['link'],
        'current'  => $hash == $currentModule ? 'text-danger' : ''
      ]);
    }
    
    return View::render('admin/menu/box', [
      'links' => $links
    ]);
  }

  /**
   * Método resposável por retornar o conteúdo (view) da estrutura genérica da página do painel
   * @param String $title
   * @param String $content
   * @param String $currentModule
   * @return String
   */
  public static function getPanel($title, $content, $currentModule) {
    //Renderiza a view do painel
    $contentPanel = View::render('admin/panel', [
      'menu' => self::getMenu($currentModule),
      'content' => $content
    ]);

    //retorna a página renderizada
    return self::getPage($title, $contentPanel);
    
    /*return View::render('Admin/page', [
      'title'   => $title,
      'content' => $content,
      'modulo'  => $currentModule
    ]);*/
  }
}