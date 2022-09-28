<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;


class Testimony extends Page{

    //MÉTODO QUE OBTEM A RENDERIZAÇÃO DOS ITENS DE DEPOIMENTOS PARA A PÁGINA
    private static function getTestimonyItems($request, &$obPagination){
        //DEPOIMENTOS
        $itens = '';

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityTestimony::getTestimonies(null, null, null, 'COUNT(*) as qtd') -> fetchObject() -> qtd;

        //PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTÂNCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        //RESULTADOS DA PÁGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination -> getLimit());

        //RENDERIZA O ITEM
        while($obTestimony = $results -> fetchObject(EntityTestimony::class)){

            $itens .= View::render('admin/modules/testimonies/item', [
                'id'        => $obTestimony -> id,
                'nome'      => $obTestimony -> nome,
                'mensagem'  => $obTestimony -> mensagem,
                'data'      => date('d/m/Y H:i:s', strtotime($obTestimony -> data))
            ]);
        }

        //RETORNA OS DEPOIMENTOS
        return $itens;
    }

    //MÉTODO QUE RENDERIZA A VIEW DE LISTAGEM DE DEPOIMENTOS
    public static function getTestimonies($request){

        //CONTEÚDO DA HOME
        $content = View::render('admin/modules/testimonies/index', [
            'itens'      => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Depoimentos > WDEV', $content, 'testimonies');
    }

    //MÉTODO QUE RETORNA O FORMULÁRIO DE CADASTRO DE NOVO DEPOIMENTO
    public static function getNewTestimony($request){
        
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/form', [
            'title' => 'Cadastrar Depoimento'
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar Depoimento > WDEV', $content, 'testimonies');
    }
}