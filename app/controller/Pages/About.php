<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page{
  
  public static function getAbout() {
    $obOrganization = new Organization;

    $content = View::render('Pages/About', [
      'name' => $obOrganization->name,
      'description' => $obOrganization->description,
      'URLGit' => $obOrganization->URLGit,
    ]);

    return parent::getPage('SOBRE Vitinho', $content);
  }
  
}