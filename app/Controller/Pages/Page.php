<?php

namespace App\Controller\Pages;

use \App\Utils\View;

// MÉTODO RESPOSÁVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA PAGINA GENÉRICA
class Page{

    private static function getHeader(){
        return View::render('pages/header');
    }

    //MÉTODO QUE RENDERIZA O RODAPÉ DA PÁGINA
    private static function getFooter(){
        return View::render('pages/footer');
    }

    //MÉTODO QUE RETORNA UM LINK DA PAGINAÇÃO
    private static function getPaginationLink($queryParams, $page, $url, $label = null){
        //ALTERA A PÁGINA
        $queryParams['page'] = $page['page'];

        //LINK
        $link = $url.'?'.http_build_query($queryParams);

        //VIEW
        return View::render('pages/pagination/link', [
            'page'  => $label ?? $page['page'],
            'link'  => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
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
       
        //PÁGINA ATUAL
        $currentPage = $queryParams['page'] ?? 1;

        //LIMITE DE PÁGINAS
        $limit = getenv('PAGINATION_LIMIT');

        //MEIO DA PAGINAÇÃO
        $middle = ceil($limit/2);

        //INÍCIO DA PAGINAÇÃO
        $start = $middle > $currentPage ? 0 : $currentPage - $middle;

        //AJUSTA O FINAL DA PAGINAÇÃO
        $limit = $limit + $start;
        // echo "<pre>";
        // print_r($limit);
        // echo "</pre>"; 
        // exit;

        //AJUSTA O INÍCIO DA PAGINAÇÃO
        if($limit > count($pages)){
            $diff = $limit - count($pages);
            $start = $start - $diff;
        }

        //LINK INICIAL
        if($start > 0){
            $links .= self::getPaginationLink($queryParams, reset($pages), $url, '<<');
        }

        //RENDERIZA OS LINKS
        foreach($pages as $page){

            //VERIFICA O START DA PAGINAÇÃO
            if($page['page'] <= $start) continue;

            //VERIFICA O LIMITE DE PAGINAÇÃO
            if($page['page'] > $limit){
                $links .= self::getPaginationLink($queryParams, end($pages), $url, '>>');
                break;   
            }

            $links .= self::getPaginationLink($queryParams, $page, $url);
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