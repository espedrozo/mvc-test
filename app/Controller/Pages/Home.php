<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

// método resposável por retornar o conteúdo (view) da nossa home

class Home extends Page{
    public static function getHome(){

        $obOrganization = new Organization;
   

        //view da home
        $content = View::render('pages/home', [
            'name'          => $obOrganization->name,
            'description'   => $obOrganization->description,
            'site'          => $obOrganization->site
        ]);

        //retorna a view da página
        return parent::getPage('WDEV - Canal - HOME', $content);
    }
}