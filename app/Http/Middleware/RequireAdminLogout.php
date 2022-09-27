<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout{

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){

        //VERIFICA SE O USUÁRIO ESTÁ LOGADO
        if(SessionAdminLogin::isLogged()){
            $request -> getRouter() -> redirect('/admin');
        }

        //CONTINUA A EXECUÇÃO
        return $next($request);
    }
}