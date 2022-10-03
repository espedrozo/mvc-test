<?php

namespace App\Controller\Api;

use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

//MÉTODO QUE RETORNA OS DEPOIMENTOS CADASTRADOS
class Testimony extends Api{

    //MÉTODO QUE OBTEM A RENDERIZAÇÃO DOS ITENS DE DEPOIMENTOS PARA A PÁGINA
    private static function getTestimonyItems($request, &$obPagination){
        //DEPOIMENTOS
        $itens = [];

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

            $itens[] = [
                'id'        => (int)$obTestimony -> id,
                'nome'      => $obTestimony -> nome,
                'mensagem'  => $obTestimony -> mensagem,
                'data'      => $obTestimony -> data
            ];
        }

        //RETORNA OS DEPOIMENTOS
        return $itens;
    }

    public static function getTestimonies($request){
        return [
            'depoimentos'   => self::getTestimonyItems($request, $obPagination),
            'paginacao'     => parent::getPagination($request, $obPagination)
        ];
    }

    //MÉTODO QUE RETORNA OS DETALHES DE UM DEPOIMENTO
    public static function getTestimony($request, $id){

        //VALIDA O ID DO DEPOIMENTO
        if(!is_numeric($id)){
            throw new \Exception("O id '".$id."' não é válido.", 400 );
        }

        //BUSCA DEPOIMENTO
        $obTestimony = EntityTestimony::getTestimonyById($id);
       
        //VALIDA SE O DEPOIMENTO EXISTE
        if(!$obTestimony instanceof EntityTestimony){
            throw new \Exception("O depoimento ".$id." não foi encontrado", 404);
        }

        //RETORNA OS DETALHES DO DEPOIMENTO
        return [
            'id'        => (int)$obTestimony -> id,
            'nome'      => $obTestimony -> nome,
            'mensagem'  => $obTestimony -> mensagem,
            'data'      => $obTestimony -> data
        ];
    }

    //MÉTODO QUE CADASTRA UM NOVO DEPOIMENTO
    public static function setNewTestimony($request){
        return [
            'sucesso' => true
        ];
    }
}