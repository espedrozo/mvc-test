<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Login extends Page{

    //MÉTODO QUE RETORNA A RENDERIZAÇÃO DA PÁGINA DE LOGIN
    public static function getLogin($request){

        //CONTEÚDO DA PÁGINA DE LOGIN
        $content = View::render('admin/login', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPage('Login > WDEV', $content);
    }
}