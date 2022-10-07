<?php

use \App\Http\Response;
use \App\Controller\Api;

//ROTA DE LISTAGEM DE USUÁRIOS
$obRouter -> get('/api/v1/users', [
    'middlewares' => [
        'api',
        'jwt-auth',
        'cache'
    ],
    function($request){
        return new Response(200, Api\User::getUsers($request), 'application/json');
    }
]);

//ROTA DE CONSULTA DO USUÁRIO ATUAL
$obRouter -> get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(200, Api\User::getCurrentUser($request), 'application/json');
    }
]);

//ROTA DE CONSULTA INDIVIDUAL DE USUÁRIOS
$obRouter -> get('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth',
        'cache'
    ],

    function($request, $id){
        return new Response(200, Api\User::getUser($request, $id), 'application/json');
    }
]);

//ROTA DE CADASTRO DE USUÁRIOS
$obRouter -> post('/api/v1/users', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request){
        return new Response(201, Api\User::setNewUser($request), 'application/json');
    }
]);

//ROTA DE ATUALIZAÇÃO DE USUÁRIOS
$obRouter -> put('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request, $id){
        return new Response(200, Api\User::setEditUser($request, $id), 'application/json');
    }
]);

//ROTA DE EXCLUSÃO DE USUÁRIOS
$obRouter -> delete('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function($request, $id){
        return new Response(200, Api\User::setDeleteUser($request, $id), 'application/json');
    }
]);