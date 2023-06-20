<?php

use \App\Http\Response;
use App\Controller\Api\Testimony;

//ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/api/v1/testimonies',[
    'middlewares' => [
        'api'
    ],
    function($request){
        return new Response(200,Testimony::getTestimonies($request),'application/json');
    }
]);

//ROTA DE CONSULTA INDIVIDUAL DE DEPOIMENTOS
$obRouter->get('/api/v1/testimonies/{id}',[
    'middlewares' => [
        'api'
    ],
    function($request,$id){
        return new Response(200,Testimony::getTestimony($request,$id),'application/json');
    }
]);

//ROTA DE CADASTRO DE DEPOIMENTOS
$obRouter->post('/api/v1/testimonies',[
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request){
        return new Response(201,Testimony::setNewTestimony($request),'application/json');
    }
]);

//ROTA DE ATUALIZAÇÃO DE DEPOIMENTOS
$obRouter->put('/api/v1/testimonies/{id}',[
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request,$id){
        return new Response(200,Testimony::setEditTestimony($request,$id),'application/json');
    }
]);

//ROTA DE EXCLUSÃO DE DEPOIMENTOS
$obRouter->delete('/api/v1/testimonies/{id}',[
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function($request,$id){
        return new Response(200,Testimony::setDeleteTestimony($request,$id),'application/json');
    }
]);
