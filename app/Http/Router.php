<?php

namespace App\Http;

use \Closure;
use \Exception;
use Reflection;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router{
  /**
   * URL completa do projeto (raiz)
   * @var String
   */
  private $url = '';

  /**
   * Prefixo de todas as rotas
   * @var String
   */
  private $prefix = '';

  /**
   * Índice de rotas
   * @var array
   */
  private $routes = [];

  /**
   * Instancia de Request
   * @var Request
   */
  private $request;

  /**
   * Método responsável por iniciar a classe
   * @param String 
   */
  public function __construct($url) {
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
  }
  /**
   * Método responsável por definir o prefixo das rotas
   */
  private function setPrefix() {
    $parseUrl = parse_url($this->url);

    $this->prefix = $parseUrl['path'] ?? '';
  }

  /**
   * Método responsável por adicionar uma rota na classe
   * @param String $method
   * @param String $route
   * @param array $params
   */
  private function addRoute($method, $route, $params = []) {
    //Validação do paramentros
    foreach ($params as $key => $value) {
      if ($value instanceof Closure) {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }

    //MIDDLEWARES DA ROTA
    $params['middlewares'] = $params['middlewares'] ?? [];

    $params['variables'] = [];

    $patternVariable = '/{(.*?)}/';

    if (preg_match_all($patternVariable, $route, $matches)) {
      $route = preg_replace($patternVariable, '(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    //Padrão de validação de URL
    $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

    //Adiciona a rota dentro da classe
    $this->routes[$patternRoute][$method] = $params;
  }

  /**
   * Método responsável por definir uma rota de GET
   * @param String $route
   * @param array $params
   */
  public function get($route, $params = []) {
    return $this->addRoute('GET', $route, $params);
  }

  public function post($route, $params = []) {
    return $this->addRoute('POST', $route, $params);
  }

  public function put($route, $params = []) {
    return $this->addRoute('PUT', $route, $params);
  }

  public function delete($route, $params = []) {
    return $this->addRoute('DELETE', $route, $params);
  }

  /**
   * Metodo responsavel por retornar a URI desconsiderando o prefixo
   * @return String
   */
  private function getUri() {
    //URI da Request
    $uri = $this->request->getUri();

    //Fatia a URI com o prefixo
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    //Retorna a URI sem prefix
    return end($xUri);
  }

  /**
   * Metodo responsavel por retornar os dados da rota atual
   * @return array
   */
  private function getRoute() {
    //URI
    $uri = $this->getUri();

    $httpMethod = $this->request->getHttpMethod();

    foreach ($this->routes as $patternRoute => $methods) {
      if (preg_match($patternRoute, $uri, $matches)) {
        if (isset($methods[$httpMethod])) {
          unset($matches[0]);

          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
          $methods[$httpMethod]['variables']['request'] = $this->request;

          return $methods[$httpMethod];
        }

        throw new Exception("Método não permitido", 405);
        
      }
      
    }

    throw new Exception("URL não encontrada", 404);

  }

  /**
   * Método responsável por executar a rota atual
   * @return Response
   */
  public function run() {
    try {
      $route = $this->getRoute();
      
      if (!isset($route['controller'])){
        throw new Exception("A URL não pode ser processada", 500);
      }

      $args = [];

      $reflection = new ReflectionFunction($route['controller']);
      foreach ($reflection->getParameters() as $parameter) {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? '';
      }
      
      return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request); //call_user_func_array($route['controller'], $args);

    } catch (Exception $e) {
      return new Response($e->getCode(), $e->getMessage());
    }
  }

  //MÉTODO RESPONSÁVEL POR RETORNAR A URL ATUAL
  public function getCurrentUrl() {
    return $this->url.$this->getUri();
  }
  
}