<?php

use \App\Http\Response;
use \App\Controller\Api\Auth;

//ROTA DE AUTORIZAÇÃO DA API
$obRouter->post('/api/v1/auth',[
    'middlewares' => [
        'api'
    ],
    function($request){
        return new Response(201,Auth::generateToken($request),'application/json');
    }
]);