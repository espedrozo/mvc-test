<?php

namespace App\Controller\Pages;

use \App\Utils\View;

// MÉTODO RESPOSÁVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA PAGINA GENÉRICA
class Page{

    private static function getHeader(){
        return View::render('pages/header');
    }

    private static function getFooter(){
        return View::render('pages/footer');
    }

    //MÉTODO QUE RENDERIZA O LAYOUT DE PAGINAÇÃO
    public static function getPagination($request, $obPagination){
        //PÁGINAS
        $pages = $obPagination -> getPages();
        
        //VERIFICA A QUANTIDADE DE PÁGINAS
        if(count($pages) <= 1) return '';

        //LINKS
        $links = '';

        //URL ATUAL (SEM GETS)
        $url = $request -> getRouter() -> getCurrentUrl();
        
        //GET
        $queryParams = $request -> getQueryParams();
       
        //RENDERIZA OS LINKS
        foreach($pages as $page){

            //ALTERA A PÁGINA
            $queryParams['page'] = $page['page'];

            //LINK
            $link = $url.'?'.http_build_query($queryParams);

            //VIEW
            $links .= View::render('pages/pagination/link', [
                'page'  => $page['page'],
                'link'  => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        //RENDERIZA BOX DE PAGINAÇÃO 
        return View::render('pages/pagination/box', [
            'links'  => $links
        ]);
    }

    public static function getPage($title, $content){
        return View::render('pages/page', [
            'title'   => $title,
            'header'  => self::getHeader(),
            'content' => $content,
            'footer'  => self::getFooter()
        ]);
    }
}