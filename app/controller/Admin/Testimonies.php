<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimonies extends Page{
  
  private static function getTestimonyItems($request, &$obPagination) {
    $items = '';

    $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

    $result = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

    while($obTestimony = $result->fetchObject(EntityTestimony::class)){
      
      $items .= View::render('admin/modules/testimonies/item', [
        'id' => $obTestimony->id,
        'nome' => $obTestimony->nome,
        'mensagem' => $obTestimony->mensagem,
        'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
      ]);
    }

    return $items;
  }

  /**
   * Método resposável por renderizar a view de listagem de depoimentos
   * @param Request
   * @return string
   */
  public static function getTestimonies($request) {
    // Conteúdo da HOME
    $content = View::render('admin/modules/testimonies/index', [
      'itens' => self::getTestimonyItems($request, $obPagination)
    ]);

    // Retorna a página completa
    return parent::getPanel('Depoimentos > PHP-MVC', $content, 'testimonies');
  }
}