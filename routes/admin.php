<?php

use \App\Http\Response;
use \App\Controller\Admin;

//ROTA ADMIN
$obRouter -> get('/admin',[
    function(){
        return new Response(200, 'Admin :)');
    }
]);

//ROTA LOGIN
$obRouter -> get('/admin/login',[
    function($request){
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

//ROTA LOGIN (POST)
$obRouter -> post('/admin/login',[
    function($request){
        echo password_hash('123456', PASSWORD_DEFAULT);

        exit;
        echo "<pre>";
        print_r($request->getPostVars());
        echo "</pre>"; 
        exit;
        return new Response(200, Admin\Login::getLogin($request));
    }
]);