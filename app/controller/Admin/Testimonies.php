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

    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

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
      'itens'      => self::getTestimonyItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status'     => self::getStatus($request)
    ]);

    // Retorna a página completa
    return parent::getPanel('Depoimentos > PHP-MVC', $content, 'testimonies');
  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo depoimento
   * @param Request $request
   * @return string
   */
  public static function getNewTestimony($request) {
    // Conteúdo do formulário
    $content = View::render('admin/modules/testimonies/form', [
      'title'     => 'Cadastro de depoimento',
      'nome'      => '',
      'mensagem'  => '',
      'status'    => '',
    ]);

    // Retorna a página completa
    return parent::getPanel('Cadastrar depoimento > PHP-MVC', $content, 'testimonies');
  }

  /**
   * Método responsável por cadastrar um novo depoimento no banco
   * @param Request $request
   * @return string
   */
  public static function setNewTestimony($request) {
    //post vars
    $postVars = $request->getPostVars();

    //Nova instancia de depoimento
    $obTestimony = new EntityTestimony;
    $obTestimony->nome       = $postVars['nome'] ?? '';
    $obTestimony->mensagem   = $postVars['mensagem'] ?? '';
    $obTestimony->cadastrar();

    //Redireciona o usuário para alterar o cadastro
    $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
  }

  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @param String
   */
  private static function getStatus($request) {
    //Query params
    $queryParams = $request->getQueryParams();

    //status
    if (!isset($queryParams['status'])) return '';

    switch ($queryParams['status']) {
      case 'created':
        return Alert::getSuccess('Depoimento criado com sucesso');
        break;
      case 'updated':
        return Alert::getSuccess('Depoimento atualizado com sucesso');
        break;
      case 'deleted':
        return Alert::getSuccess('Depoimento excluído com sucesso');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de edição de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditTestimony($request, $id) {
    //obtem o depoimento do banco de dados 
    $obTestimony = EntityTestimony::getTestimonyById($id);

    //valida a instancia
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }
    
    // Conteúdo do formulário
    $content = View::render('admin/modules/testimonies/form', [
      'title'    => 'Edição de depoimento',
      'nome'     =>  $obTestimony->nome,
      'mensagem' =>  $obTestimony->mensagem,
      'status'   =>  self::getStatus($request)
      
    ]);

    // Retorna a página completa
    return parent::getPanel('Editar depoimento > PHP-MVC', $content, 'testimonies');
  }

  /**
   * Método responsável por gravar a atualizacao de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditTestimony($request, $id) {
    //obtem o depoimento do banco de dados 
    $obTestimony = EntityTestimony::getTestimonyById($id);

    //valida a instancia
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }
    
    //POST VARS
    $postVars = $request->getPostVars();

    //Atualiza a instancia
    $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
    $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
    $obTestimony->atualizar();

    //Redireciona o usuário para alterar o cadastro
    $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
  }

  /**
   * Método responsável por retornar o formulário de exclusão de um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getDeleteTestimony($request, $id) {
    //obtem o depoimento do banco de dados 
    $obTestimony = EntityTestimony::getTestimonyById($id);

    //valida a instancia
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }
    
    // Conteúdo do formulário
    $content = View::render('admin/modules/testimonies/delete', [
      'nome'     =>  $obTestimony->nome,
      'mensagem' =>  $obTestimony->mensagem
    ]);

    // Retorna a página completa
    return parent::getPanel('Excluir um depoimento > PHP-MVC', $content, 'testimonies');
  }

  /**
   * Método responsável por excluir um depoimento
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setDeleteTestimony($request, $id) {
    //obtem o depoimento do banco de dados 
    $obTestimony = EntityTestimony::getTestimonyById($id);

    //valida a instancia
    if (!$obTestimony instanceof EntityTestimony) {
      $request->getRouter()->redirect('/admin/testimonies');
    }
  
    //Excluir o depoimento
    $obTestimony->excluir();

    //Redireciona o usuário para alterar o cadastro
    $request->getRouter()->redirect('/admin/testimonies?status=deleted');
  }
}

