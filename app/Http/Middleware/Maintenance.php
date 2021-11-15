<?php

namespace App\Http\Middleware;

class Maintenance {
  //MÉTODO RESPONSÁVEL POR EXECUTAR O MIDDLEWARE
  public function handle($request, $next) {
    if (getenv('MAINTENANCE') == 'true') {
      throw new \Exception("Página em manutenção, tente novamento mais tarde", 200);
      
    }
    
    return $next($request);
  }
}