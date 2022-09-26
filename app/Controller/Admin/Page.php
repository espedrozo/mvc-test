<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page{

    //MÉTODO QUE RETORNA O CONTEÚDO (VIEW) DA ESTRUTURA GENÉRICA DE PÁGINA DO PAINEL
    public static function getPage($title, $content){
        return View::render('admin/page', [
            'title'     => $title,
            'content'   => $content
        ]);
    }
}