<?php

namespace App\Http\Middleware;

class Maintenance {

    /**
     * Método resposnsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * 
     * @return Response
     */
    public function handle($request,$next){
        //Verifica o estado de manutenção da página
        if ( getenv('MAINTENENCE') == 'true' ) {
            throw new \Exception("Página em manutenção!",200);
        }

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }

}