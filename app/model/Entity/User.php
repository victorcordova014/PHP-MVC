<?php

namespace App\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class User{
  /**
   * ID do usuário
   * @var integer
   */
  public $id;
  /**
   * Nome do usuário
   * @var String
   */
  public $nome;
  /**
   * E-mail do usuário
   * @var String
   */
  public $email;
  /**
   * Senha do usuário
   * @var String
   */
  public $senha;

  /**
   * Método responsável por retornar um usuário com base no seu E-mail
   * @param String $email
   * @return User
   */
  public static function getUserByEmail($email) {
    return self::getUsers('email ='.$email)->fetchObject(self::class);

  }

  public static function getUsers($where = null, $order = null, $limit = null, $field = '*') {
    return (new Database('usuarios')) ->select($where, $order, $limit, $field);
  }

  /**
   * Método responsável por cadastrar uma instancia de usuário no banco de dados
   * @return boolean
   */
  public function cadastrar() {
    $this->id = (new Database('usuarios'))->insert([
      'nome'  => $this->nome,
      'email' => $this->email,
      'senha' => $this->senha
    ]);

    //Sucesso
    return true;
  }

  /**
   * Método responsável por atualizar uma instancia de usuário no banco de dados
   * @return boolean
   */
  public function atualizar() {
    $this->id = (new Database('usuarios'))->update('id ='.$this->id, [
      'nome'  => $this->nome,
      'email' => $this->email,
      'senha' => $this->senha
    ]);
  }
  /**
   * Método responsável por excluir uma instancia de usuário no banco de dados
   * @return boolean
   */
  public function excluir() {
    $this->id = (new Database('usuarios'))->delete('id ='.$this->id);
  }
  /**
   * Método responsável por buscar uma instancia de usuário no banco de dados pelo ID
   * @return boolean
   */
  public static function getUserById($id) {
    return self::getUsers('id ='.$id)->fetchObject(self::class);
  }
}
