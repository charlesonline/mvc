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
