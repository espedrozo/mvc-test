<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page{

    //MÓDULOS DISPONÍVEIS NO PAINEL
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link'  => URL.'/admin'
        ],
        'testimonies' => [
            'label' => 'Depoimentos',
            'link'  => URL.'/admin/testimonies'
        ],
        'users' => [
            'label' => 'Usuários',
            'link'  => URL.'/admin/users'
        ]
    ];

    //MÉTODO QUE RETORNA O CONTEÚDO (VIEW) DA ESTRUTURA GENÉRICA DE PÁGINA DO PAINEL
    public static function getPage($title, $content){
        return View::render('admin/page', [
            'title'     => $title,
            'content'   => $content
        ]);
    }

    //MÉTODO QUE RENDERIZA A VIEW DO MENU DO PAINEL
    private static function getMenu($currentModule){
        
        //LINKS DO MENU
        $links = '';

        //ITERA OS MÓDULOS
        foreach(self::$modules as $hash => $module){
            $links .= View::render('admin/menu/link', [
                'label'     => $module['label'],
                'link'      => $module['link'],
                'current'   => $hash == $currentModule ? 'text-danger' : ''
            ]);
        }

        //RETORNA A RENDERIZAÇÃO DO MENU
        return View::render('admin/menu/box', [
            'links' => $links
        ]);
    }

    //MÉTODO QUE RENDERIZA A VIEW DO PAINEL COM CONTEÚDOS DINÂMICOS
    public static function getPanel($title, $content, $currentModule){
        //RENDERIZA A VIEW DO PAINEL
        $contentPanel = View::render('admin/panel', [
            'menu'      => self::getMenu($currentModule),
            'content'   => $content
        ]);

        //RETORNA A PÁGINA RENDERIZADA
        return self::getPage($title, $contentPanel);
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
            $links .= View::render('admin/pagination/link', [
                'page'  => $page['page'],
                'link'  => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        //RENDERIZA BOX DE PAGINAÇÃO 
        return View::render('admin/pagination/box', [
            'links'  => $links
        ]);
    }
}