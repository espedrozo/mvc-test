<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page{

    //MÉTODO QUE RETORNA A RENDERIZAÇÃO DA PÁGINA DE LOGIN
    public static function getLogin($request, $errorMessage = null){

        //STATUS
        $status = !is_null($errorMessage) ? View::render('admin/login/status',[
            'mensagem' => $errorMessage
        ]) : '';

        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('admin/login', [
            'status' => $status
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('Login > WDEV', $content);
    }

    //MÉTODO QUE DEFINE O LOGIN DO USUÁRIO
    public static function setLogin($request){

        //POST VARS
        $postVars   = $request -> getPostVars();
        $email      = $postVars['email'] ?? '';
        $senha      = $postVars['senha'] ?? '';

        //BUSCA USUÁRIO PELO EMAIL
        $obUser = User::getUserByEmail($email);
        if(!$obUser instanceof User){
            return self::getLogin($request, 'E-mail ou senha inválidos.');
        }

        //VERIFICA A SENHA DO USUÁRIO  (PODEMOS COLOCAR NO MESMO IF QUE O DE CIMA)
        if(!password_verify($senha, $obUser -> senha)){
            return self::getLogin($request, 'E-mail ou senha inválidos.');
        }

        //CRIA A SESSÃO DE LOGIN
        SessionAdminLogin::login($obUser);

        //REDIRECIONA O USUÁRIO PARA A HOME DO ADMIN
        $request -> getRouter() -> redirect('/admin');
    }

    //MÉTODO QUE DESLOGA O USUÁRIO
    public static function setLogout($request){
        
        //DESTROI A SESSÃO DE LOGIN
        SessionAdminLogin::logout();

        //REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
        $request -> getRouter() -> redirect('/admin/login');
    }
}