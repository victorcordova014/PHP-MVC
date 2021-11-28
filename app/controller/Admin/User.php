<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page{
  
  /**
   * Método responsável por obter a renderização dos itens de usuários para a página
   * @param Request $request
   * @param Pagination $obPagination
   * @return string
   */
  private static function getUserItems($request, &$obPagination) {
    $items = '';

    $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;
  
    $queryParams = $request->getQueryParams();
    $paginaAtual = $queryParams['page'] ?? 1;

    $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

    $result = EntityUser::getUsers(null, 'id DESC', $obPagination->getLimit());

    while($obUser = $result->fetchObject(EntityUser::class)){
      $items .= View::render('admin/modules/users/item', [
        'id' => $obUser->id,
        'nome' => $obUser->nome,
        'email' => $obUser->email
      ]);
    }

    return $items;
  }

  /**
   * Método resposável por renderizar a view de listagem de usuários 
   * @param Request
   * @return string
   */
  public static function getUsers($request) {
    // Conteúdo da HOME
    $content = View::render('admin/modules/users/index', [
      'itens'      => self::getUserItems($request, $obPagination),
      'pagination' => parent::getPagination($request, $obPagination),
      'status'     => self::getStatus($request)
    ]);

    // Retorna a página completa
    return parent::getPanel('Usuários > PHP-MVC', $content, 'users');
  }

  /**
   * Método responsável por retornar o formulário de cadastro de um novo usuário
   * @param Request $request
   * @return string
   */
  public static function getNewUser($request) {
    // Conteúdo do formulário
    $content = View::render('admin/modules/users/form', [
      'title'     => 'Cadastro de usuário',
      'nome'      => '',
      'email'  => '',
      'status'    => self::getStatus($request)
    ]);

    // Retorna a página completa
    return parent::getPanel('Cadastrar usuário > PHP-MVC', $content, 'users');
  }

  /**
   * Método responsável por cadastrar um novo depoimento no banco
   * @param Request $request
   * @return string
   */
  public static function setNewUser($request) {
    //post vars
    $postVars  = $request->getPostVars();
    $nome      = $postVars['nome'] ?? '';
    $email     = $postVars['email'] ?? '';
    $senha     = $postVars['senha'] ?? '';
    
    $obUserByMail = EntityUser::getUserByEmail($email);

    if ($obUserByMail instanceof EntityUser) {
      //Redireciona o usuário
      $request->getRouter()->redirect('/admin/users/new?status=duplicated');
    }

    //Nova instancia de usuário
    $obUser = new EntityUser;
    $obUser->nome       = $nome;
    $obUser->email       = $email;
    $obUser->senha       = password_hash($senha, PASSWORD_DEFAULT);
    $obUser->cadastrar();

    //Redireciona o usuário para alterar o cadastro
    $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');
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
        return Alert::getSuccess('Usuário criado com sucesso');
        break;
      case 'updated':
        return Alert::getSuccess('Usuário atualizado com sucesso');
        break;
      case 'deleted':
        return Alert::getSuccess('Usuário excluído com sucesso');
        break;
      case 'duplicated':
        return Alert::getError('O e-mail utilizado já está sendo usado por outro usuário');
        break;
    }
  }

  /**
   * Método responsável por retornar o formulário de edição de um usuário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function getEditUser($request, $id) {
    //obtem o depoimento do banco de dados 
    $obUser = EntityUser::getUserById($id);

    //valida a instancia
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
    }
    
    // Conteúdo do formulário
    $content = View::render('admin/modules/users/form', [
      'title'    => 'Edição de usuários',
      'nome'     =>  $obUser->nome,
      'email'    =>  $obUser->email,
      'status'   =>  self::getStatus($request)
      
    ]);

    // Retorna a página completa
    return parent::getPanel('Editar usuário > PHP-MVC', $content, 'users');
  }

  /**
   * Método responsável por gravar a atualizacao de um usuário
   * @param Request $request
   * @param integer $id
   * @return string
   */
  public static function setEditUser($request, $id) {
    //obtem o depoimento do banco de dados 
    $obUser = EntityUser::getUserById($id);

    //valida a instancia
    if (!$obUser instanceof EntityUser) {
      $request->getRouter()->redirect('/admin/users');
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
      'mensagem' =>  $obTestimony->mensagem,
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

