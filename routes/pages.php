<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA HOME
$obRouter -> get('/',[
    'middlewares' => [
        'cache'
    ],
    function(){
        return new Response(200, Pages\Home::getHome());
    }
]);

//ROTA SOBRE
$obRouter -> get('/sobre', [
    'middlewares' => [
        'cache'
    ],
    function(){
        return new Response(200, Pages\About::getAbout());
    }
]);

//ROTA DEPOIMENTOS
$obRouter -> get('/depoimentos', [
    'middlewares' => [
        'cache'
    ],
    function($request){
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

//ROTA DEPOIMENTOS (INSERT)
$obRouter -> post('/depoimentos', [
    function($request){
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);



// //ROTA DINÂMICA
// $obRouter -> get('/pagina/{idPagina}/{acao}', [
//     function($idPagina, $acao){
//         return new Response(200, 'Página '.$idPagina.' - '.$acao);
//     }
// ]);
