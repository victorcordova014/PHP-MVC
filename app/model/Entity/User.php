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
}
