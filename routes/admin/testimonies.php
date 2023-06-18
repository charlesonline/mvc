<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/admin/testimonies',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Testimony::getTestimonies($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$obRouter->get('/admin/testimonies/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Testimony::getNewTestimony($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO (POST)
$obRouter->post('/admin/testimonies/new',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200,Admin\Testimony::setNewTestimony($request));
    }
]);

//ROTA DE EDIÇÃO DE UM NOVO DEPOIMENTO
$obRouter->get('/admin/testimonies/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\Testimony::getEditTestimony($request,$id));
    }
]);

//ROTA DE EDIÇÃO DE UM NOVO DEPOIMENTO (POST)
$obRouter->post('/admin/testimonies/{id}/edit',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\Testimony::setEditTestimony($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE UM NOVO DEPOIMENTO
$obRouter->get('/admin/testimonies/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\Testimony::getDeleteTestimony($request,$id));
    }
]);

//ROTA DE EXCLUSÃO DE DEPOIMENTO (DELETE)
$obRouter->post('/admin/testimonies/{id}/delete',[
    'middlewares' => [
        'required-admin-login'
    ],
    function($request,$id){
        return new Response(200,Admin\Testimony::SetDeleteTestimony($request,$id));
    }
]);