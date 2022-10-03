<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;

class UserBasicAuth{

    //MÉTODO QUE RETORNA UMA INSTANCIA DE USUÁRIO AUTENTICADO
    private function getBasicAuthUser(){
      
        //VERIFICA A EXISTÊNCIA DOS DADOS DE ACESSO
        if(!isset($_SERVER['PHP_AUTH_USER']) or !isset($_SERVER['PHP_AUTH_PW'])){
            return false;
        }

        //BUSCA O USUÁRIO PELO EMAIL
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        
        //VERIFICA A INSTANCIA
        if(!$obUser instanceof User){
            return false;
        }

        //VALIDA A SENHA E RETORNA O USUÁRIO
        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser -> senha) ? $obUser : false;
    }

    //MÉTODO QUE VALIDA O ACESSO VIA BASIC AUTH
    private function basicAuth($request){
        //VERIFICA O USUÁRIO RECEBIDO
        if($obUser = $this -> getBasicAuthUser()){
        
            $request -> user = $obUser;
            return true;
        }

        //EMITE O ERRO DE SENHA INVÁLIDA
        throw new \Exception("Usuário ou senha inválidos", 403);

    }

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){

        //REALIZA A VALIDAÇÃO DO ACESSO VIA BASIC AUTH
        $this -> basicAuth($request);       

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }
}