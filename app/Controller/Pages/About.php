<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

// método resposável por retornar o conteúdo (view) da nossa home

class About extends Page{
    public static function getAbout(){

        $obOrganization = new Organization;
   

        //view da home
        $content = View::render('pages/about', [
            'name'          => $obOrganization->name,
            'description'   => $obOrganization->description,
            'site'          => $obOrganization->site
        ]);

        //retorna a view da página
        return parent::getPage('SOBRE > WDEV', $content);
    }
}