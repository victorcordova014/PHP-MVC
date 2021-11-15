<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page{
  
  private static function getTestimonyItems($request, &$obPagination) {
    $items = '';

    $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

    $result = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

    while($obTestimony = $result->fetchObject(EntityTestimony::class)){
      $items .= View::render('Pages/testimony/item', [
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem,
        'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
      ]);
    }

    return $items;
  }

  public static function getTestimonies($request) {
    $obOrganization = new Organization;

    $content = View::render('Pages/testimonies', [
      'itens' => self::getTestimonyItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination)
    ]);

    return parent::getPage('Depoimentos Vitinho', $content);
  }
  
  public static function insertTestimony($request) {
    
    $postVars = $request->getPostVars();
    $obTestimony = new EntityTestimony;

    $obTestimony->nome = $postVars['nome'];
    $obTestimony->mensagem = $postVars['mensagem'];
    $obTestimony->cadastrar();

    return self::getTestimonies($request);
  }

}