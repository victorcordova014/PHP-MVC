<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout {
  /**
   * Método responsável por executar o middleware
   * @param Request $request
   * @param Clousure next
   * @return Response
   */
  public function handle($request, $next) {
    //Verifica se o usuário está logado
    if (SessionAdminLogin::isLogged()) {
      $request->getRouter()->redirect('/admin');
    }
    //Continua a execução
    return $next($request);
  }
}