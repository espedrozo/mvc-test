<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

// MÉTODO RESPOSÁVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA HOME

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
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 3);

        //RESULTADOS DA PÁGINA
        $results = EntityTestimony::getTestimonies(null, 'id DESC', $obPagination -> getLimit());

        while($obTestimony = $results -> fetchObject(EntityTestimony::class)){
            
            $itens .= View::render('pages/testimony/item', [
            'nome'      => $obTestimony -> nome,
            'mensagem'  => $obTestimony -> mensagem,
            'data'      => date('d/m/Y H:i:s', strtotime($obTestimony -> data))
            ]);
        }

        //RETORNA OS DEPOIMENTOS
        return $itens;
    }

    public static function getTestimonies($request){
   
        //VIEW DE DEPOIMENTOS
        $content = View::render('pages/testimonies', [
            'itens' => self::getTestimonyItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination)
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage('DEPOIMENTOS > WDEV', $content);
    }

    //MÉTODO QUE CADASTRA UM DEPOIMENTO
    public static function insertTestimony($request){
        //DADOS DO POST
        $postVars = $request->getPostVars();

        //NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony -> nome = $postVars['nome'];
        $obTestimony -> mensagem = $postVars['mensagem'];
        $obTestimony -> cadastrar();

        //RETORNA A PÁGINA DE LISTAGEM DE DEPOIMENTOS
        return self::getTestimonies($request);
    }

}