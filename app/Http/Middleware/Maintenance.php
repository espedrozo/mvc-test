<?php

namespace App\Http\Middleware;

class Maintenance{

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){
        echo "<pre>";
        print_r($request);
        echo "</pre>"; 
        exit;
    }
}