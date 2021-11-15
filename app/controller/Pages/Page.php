<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page{

  public static function getHeader() {
    return View::render('pages/header');
  }
  
  public static function getPage($title, $content) {
    return View::render('Pages/page', [
      'title' => $title,
      'header' => self::getHeader(),
      'content' => $content,
      'footer' => self::getFooter()
    ]);
  }
  
  public static function getFooter() {
    return View::render('pages/footer');
  }

  public static function getPagination($request, $obPagination) {
    $pages = $obPagination->getPages();

    //VERIFICA A QUANTIDADE DE PÁGINAS
    if (count($pages) <= 1) return '';

    $links = '';

    //URL ATUAL SEM GETS

    $url = $request->getRouter()->getCurrentUrl();
    

    $queryParams = $request->getQueryParams();
    
    foreach ($pages as $page) {
      $queryParams['page'] = $page['page'];

      $link = $url.'?'.http_build_query($queryParams);

      $links .= View::render('Pages/pagination/link', [
        'page' => $page['page'],
        'link' => $link,
        'active' => $page['current'] ? 'active' : ''
      ]);
    }
      return View::render('Pages/pagination/box', [
        'links' => $links
      ]);
    
  }
}