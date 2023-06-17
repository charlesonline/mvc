<?php

namespace App\Http\Middleware;
use App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogin {

    /**
     * Método resposnsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next){
        //VERIFICA SE  O USUÁRIO ESTA LOGADO
        if ( !SessionAdminLogin::isLogged() ) {
            $request->getRouter()->redirect('/admin/login');
        }

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }

}