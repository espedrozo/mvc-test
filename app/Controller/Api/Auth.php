<?php

namespace App\Controller\Api;

use \App\Model\Entity\User;

class Auth extends Api{

    //MÉTODO QUE GERA UM TOKEN JWT
    public static function generateToken($request){

        //POST VARS
        $postVars = $request -> getPostVars();
       
        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if(!isset($postVars['email']) or !isset($postVars['senha'])){
            throw new \Exception("Os campos 'email' e 'senha' são obrigatórios", 400);
        }

        //BUSCA USUÁRIO PELO EMAIL
        $obUser = User::getUserByEmail($postVars['email']);
        if(!$obUser instanceof User){
            throw new \Exception("Usuário ou senha são inválidos", 400);
        }

        //VALIDA A SENHA DO USUÁRIO
        if(!password_verify($postVars['senha'], $obUser -> senha)){
            throw new \Exception("Usuário ou senha são inválidos", 400);
        }

        return [
            'token' => '123123123'
        ];
    }
}