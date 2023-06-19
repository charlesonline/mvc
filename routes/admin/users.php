<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE USUARIOS
$obRouter->get('/admin/users',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\User::getUsers($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUARIO
$obRouter->get('/admin/users/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\User::getNewUser($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUARIOS (POST)
$obRouter->post('/admin/users/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\User::setNewUser($request));
    }
]);

//ROTA DE EDIÇÃO DE UM NOVO USUARIO
$obRouter->get('/admin/users/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\User::getEditUser($request,$id));
    }
]);

//ROTA DE EDIÇÃO DE UM NOVO USUARIO (POST)
$obRouter->post('/admin/users/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\User::setEditUser($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE UM NOVO USUARIO
$obRouter->get('/admin/users/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\User::getDeleteUser($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE USUARIO (DELETE)
$obRouter->post('/admin/users/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\User::SetDeleteUser($request,$id));
    }
]);