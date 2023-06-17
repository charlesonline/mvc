<?php

namespace App\Session\Admin;

class Login {

    /**
     * INICIA SESSÃO
     */
    private static function init(){
        //VERIFICA SE A SESSÃO NÃO ESTA ATIVA
        if( session_status() != PHP_SESSION_ACTIVE ){
            session_start();
        }
    }

    /**
     * Método responsável por criar o login do usuário
     * @param User $obUser
     * 
     * @return boolean
     */
    public static function login($obUser){
        //INICIA  ASESSÃO
        self::init();

        //DEFINE A SESSÃO DO USUÁRIO
        $_SESSION['admin']['usuario'] = [
            'id' => $obUser->id,
            'nome' => $obUser->nome,
            'email' => $obUser->email
        ];

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por verificar se o usuário está logado
     * @return boolean
     */
    public static function isLogged(){
        //INICIA  ASESSÃO
        self::init();

        //RETORNA  AVERIFICAÇÃO
        return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * Método responsável por deslogar o usuário
     * @return boolean
     */
    public static function logout(){
        //INICIA  ASESSÃO
        self::init();

        //DESLOGA O USUÁRIO
        unset($_SESSION['admin']['usuario']);

        //SUCESSO
        return true;
    }
}