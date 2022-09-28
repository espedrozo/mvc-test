<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Home extends Page{


    //MÉTODO QUE RENDERIZA A VIEW DE HOME DO PAINEL
    public static function getHome($request){

        //CONTEÚDO DA HOME
        $content = View::render('admin/modules/home/index', []);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Home > WDEV', $content, 'home');
    }
}