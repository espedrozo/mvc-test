<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter -> get('/admin/testimonies', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO DEPOIMENTO
$obRouter -> get('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request){
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);