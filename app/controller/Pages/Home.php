<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page{
  
  public static function getHome() {
    $obOrganization = new Organization;

    $content = View::render('Pages/Home', [
      'name' => $obOrganization->name
    ]);

    return parent::getPage('HOME Vitinho', $content);
  }
  
}