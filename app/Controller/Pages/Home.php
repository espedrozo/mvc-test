<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

// MÉTODO RESPOSÁVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA HOME

class Home extends Page{
    public static function getHome(){

        $obOrganization = new Organization;
   

        //VIEW DA HOME
        $content = View::render('pages/home', [
            'name'          => $obOrganization->name,
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('HOME > WDEV', $content);
    }
}