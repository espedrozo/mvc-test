<?php

namespace App\Controller\Api;

//MÉTODO QUE RETORNA OS DETALHES DA API
class Api{

    public static function getDetails($request){
        return [
            'nome'      => 'API - WDEV',
            'versao'    => 'v1.0.0',
            'autor'     => 'Emanuel S. P.',
            'email'     => 'mr.espedrozo@gmail.com'
        ];
    }

    //MÉTODO QUE RETORNA OS DETALHES DA PAGINAÇÃO
    protected static function getPagination($request, $obPagination){
        //QUERY PARAMS
        $queryParams = $request -> getQueryParams();

        //PÁGINAS
        $pages = $obPagination -> getPages();

        //RETORNO
        return [
            'paginaAtual'       => isset($queryParams['page']) ? (int)$queryParams['page'] : 1,
            'quantidadePaginas' => !empty($pages) ? count($pages) : 1
        ];
    }
}