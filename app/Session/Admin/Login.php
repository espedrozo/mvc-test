<?php

namespace App\Session\Admin;

class Login{

    //MÉTODO QUE INICIA A SESSÃO
    private static function init(){
        //VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }
    
    //MÉTODO QUE CRIA O LOGIN DO USUÁRIO
    public static function login($obUser){
        //INICIA A SESSÃO
        self::init();

        //DEFINE A SESSÃO DO USUÁRIO
        $_SESSION['admin']['usuario'] = [
            'id' => $obUser -> id,
            'nome' => $obUser -> nome,
            'email' => $obUser -> email
        ];

        //SUCESSO
        return true;
    }

    //MÉTODO QUE VERIFICA SE O USUÁRIO ESTÁ LOGADO
    public static function isLogged(){
        self::init();

        //RETORNA A VERIFICAÇÃO
        return isset($_SESSION['admin']['usuario']['id']);
    }

    //MÉTODO QUE EXECUTA O LOGOUT DO USUÁRIO
    public static function logout(){
        //INICIA A SESSÃO
        self::init();

        //DESLOGA O USUÁRIO
        unset($_SESSION['admin']['usuario']);

        //SUCESSO
        return true;
    }
}