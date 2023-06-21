<?php

use \App\Http\Response;
use App\Controller\Api\User;

//ROTA DE LISTAGEM DE USUARIO
$obRouter->get('/api/v1/users',[
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200,User::getUsers($request),'application/json');
    }
]);

//ROTA DE CONSULTA DO USUARIO ATUAL
$obRouter->get('/api/v1/users/me',[
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200,User::getCurrentUser($request),'application/json');
    }
]);

//ROTA DE CONSULTA INDIVIDUAL DE USUARIOS
$obRouter->get('/api/v1/users/{id}',[
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,User::getUser($request,$id),'application/json');
    }
]);

//ROTA DE CADASTRO DE USUARIOS
$obRouter->post('/api/v1/users',[
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201,User::setNewUser($request),'application/json');
    }
]);

//ROTA DE ATUALIZAÇÃO DE USUARIOS
$obRouter->put('/api/v1/users/{id}',[
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,User::setEditUser($request,$id),'application/json');
    }
]);

//ROTA DE EXCLUSÃO DE USUARIOS
$obRouter->delete('/api/v1/users/{id}',[
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request,$id){
        return new Response(200,User::setDeleteUser($request,$id),'application/json');
    }
]);
