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
    return (new Database('usuarios'))->select('email = "'.$email.'"')->fetchObject(self::class);

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
}
