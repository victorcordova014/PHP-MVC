<?php

namespace App\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Testimony{
  public $id;
  public $nome;
  public $mensagem;
  public $data;

  public function cadastrar(){
    $this->data = date('Y-m-d H:i:s');

    //INSERE O DEPOIMENTO NO BANCO DE DADOS
    $this->id = (new Database('depoimentos'))->insert([
      'nome' => $this->nome,
      'mensagem' => $this->mensagem,
      'data' => $this->data
    ]);
    return true;
  }

  public static function getTestimonies($where = null, $order = null, $limit = null, $field = '*') {
    return (new Database('depoimentos')) ->select($where, $order, $limit, $field);
  }
}