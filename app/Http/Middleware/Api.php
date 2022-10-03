<?php

namespace App\Http\Middleware;

class Api{

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){
        
        //ALTERA O CONTENT TYPE PARA JSON
        $request -> getRouter() -> setContentType('application/json');
       

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }
}