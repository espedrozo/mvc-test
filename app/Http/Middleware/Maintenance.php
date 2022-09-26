<?php

namespace App\Http\Middleware;

class Maintenance{

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){

        // echo "<pre>";
        // print_r($request);
        // echo "</pre>"; 
        // exit;
        
        //VERIFICA O ESTADO DE MANUTENÇÃO DA PÁGINA
        if(getenv('MAINTENANCE') == 'true'){
        throw new \Exception("Página em manutenção. Tente novamente mais tarde.", 200);
        }

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }
}