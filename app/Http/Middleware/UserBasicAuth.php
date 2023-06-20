<?php

namespace App\Http\Middleware;

use App\Model\Entity\User;

class UserBasicAuth {

    /**
     * Método responsável por retornar uma instancia de usuário autenticado
     * @return User
     */
    private function getBasicAuthUser(){
        //VERIFICA A EXISTENCIA DOS DADOS DE ACESSO
        if ( !isset( $_SERVER['PHP_AUTH_USER'] ) || !isset( $_SERVER['PHP_AUTH_PW'] ) ) {
            return false;
        }

        //BUSCA USUÁRIO PELO EMAIL
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        //VERIFICA A INSTANCIA
        if ( !$obUser instanceof User ) {
            return false;
        }

        //VALIDA A SENHA E RETORNA O USUÁRIO
        return password_verify($_SERVER['PHP_AUTH_PW'],$obUser->senha) ? $obUser : false;
    }

    /**
     * Método responsável por validar o acesso via basic auth
     * @param Request $request
     * 
     */
    private function basicAuth($request){
        //VERIFICA O USUÁRIO RECEBIDO
        if ($obUser = $this->getBasicAuthUser()) {
            $request->user = $obUser;
            return true;
        }

        //EMITE O ERRO
        throw new \Exception('Usuário ou senha inválido',403);
    }

    /**
     * Método resposnsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * 
     * @return Response
     */
    public function handle($request,$next){
        //REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this->basicAuth($request);

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }

}