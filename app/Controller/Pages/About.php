<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

// MÉTODO RESPOSÁVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA HOME

class About extends Page{
    public static function getAbout(){

        $obOrganization = new Organization;
   

        //VIEW DA HOME
        $content = View::render('pages/about', [
            'name'          => $obOrganization->name,
            'description'   => $obOrganization->description,
            'site'          => $obOrganization->site
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('SOBRE > WDEV', $content);
    }
}