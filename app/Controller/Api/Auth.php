<?php

namespace App\Controller\Api;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \App\Model\Entity\User;

class Auth extends Api {

    /**
     * Método responsável por gerar um token JWT
     * @param Request $request
     * 
     * @return array
     */
    public static function generateToken($request){

        //POST VARS
        $postVars = $request->getPostVars();

        //VALIDA OS CAMPOS OBRIGATORIOS
        if ( !isset($postVars['email']) || !isset($postVars['senha']) ) {
            throw new \Exception('Os campos email e senha são obrigatórios',400);
        }

        //BUSCA USUÁRIO POR EMAIL
        $obUser = User::getUserByEmail($postVars['email']);

        //VERIFICA SE TEM UMA INSTANCIA
        if ( !$obUser instanceof User ) {
            throw new \Exception('Usuario não encontrado',400);
        }

        //VALIDA A SENHA
        if ( !password_verify($postVars['senha'],$obUser->senha) ) {
            throw new \Exception('Erro de usuário, verifique seus dados e tente novamente',400);
        }

        //PAYLOAD
        $payload = [
            'email' => $obUser->email
        ];

        $jwt = JWT::encode($payload, getenv('JWT_KEY'), 'HS256');

        // echo '<pre>';
        // print_r($obUser);
        // echo '</pre>'; exit;

        //RETORNA O TOKEN GERADO
        return [
            "token" => $jwt
        ];
    }
}