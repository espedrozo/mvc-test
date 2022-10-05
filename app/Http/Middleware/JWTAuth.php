<?php

namespace App\Http\Middleware;

use \App\Model\Entity\User;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JWTAuth{

    //MÉTODO QUE RETORNA UMA INSTANCIA DE USUÁRIO AUTENTICADO
    private function getJWTAuthUser($request){

        //HEADERS
        $headers = $request -> getHeaders();
        
        //TOKEN PURO EM JWT
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';
        
        try{
        
            //DECODE
            //$decode = JWT::decode($jwt, getenv('JWT_KEY'), ['HS256']);
            $decode = (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));

        }catch(\Exception $e){
            throw new \Exception("Token inválido", 403);
        }

       

        //EMAIL
        $email = $decode['email'] ?? '';

        //BUSCA O USUÁRIO PELO EMAIL
        $obUser = User::getUserByEmail($email);
        
       //RETORNA O USUÁRIO
       return $obUser instanceof User ? $obUser : false;
    }

    //MÉTODO QUE VALIDA O ACESSO VIA JWT
    private function auth($request){
        //VERIFICA O USUÁRIO RECEBIDO
        if($obUser = $this -> getJWTAuthUser($request)){
        
            $request -> user = $obUser;
            return true;
        }

        //EMITE O ERRO DE SENHA INVÁLIDA
        throw new \Exception("Acesso negado", 403);

    }

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){

        //REALIZA A VALIDAÇÃO DO ACESSO VIA JWT
        $this -> auth($request);       

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }
}