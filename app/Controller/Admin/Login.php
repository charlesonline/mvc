<?php

namespace App\Controller\Admin;

use App\Model\Entity\User;
use App\Utils\View;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page {

    /**
     * Método resposnável de retornar a renderização da página de login
     * @param Request $request
     * @param string $errorMessage
     * 
     * @return string
     */
    public static function getLogin($request,$errorMessage = null){
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';


        //CONTEUDO DA PÁGINA DE LOGIN
        $content = View::render('admin/login',[
            'status' => $status,
            'url' => getenv('URL')
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('Login > DEV',$content);
    }

    /**
     * Método resposnável por definir o login do usuario
     * @param Request $request
     * 
     * @return string
     */
    public static function setLogin($request){
        //POST VARS
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //BUSCA USUÁRIO PELO EMAIL
        $obUser = User::getUserByEmail($email);
        if ( !$obUser instanceof User ) {
            return self::getLogin($request,'E-mail ou senha inválidos!');
        }

        //VERIFICAR A SENHA DO USUÁRIO
        if ( !password_verify($senha,$obUser->senha) ) {
            return self::getLogin($request,'E-mail ou senha inválidos!!');
        }

        //CRIA A SESSÃO DE LOGIN
        SessionAdminLogin::login($obUser);

        //REDIRECIONA O USUÁRIO PARA A DMIN
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Método responsável por desligar o usuário
     * @param Request $request
     */
    public static function setLogout($request){
        //DESTROI A SESSÃO DE LOGIN
        SessionAdminLogin::logout();

        //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
        $request->getRouter()->redirect('/admin/login');
    }
}