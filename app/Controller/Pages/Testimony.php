<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;

// MÉTODO RESPOSÁVEL POR RETORNAR O CONTEÚDO (VIEW) DA NOSSA HOME

class Testimony extends Page{

    //MÉTODO QUE OBTEM A RENDERIZAÇÃO DOS ITENS DE DEPOIMENTOS PARA A PÁGINA
    private static function getTestimonyItems(){
        //DEPOIMENTOS
        $itens = '';
        $resutls = EntityTestimony::getTestimonies();
        return $itens;
    }

    public static function getTestimonies(){
   
        //VIEW DE DEPOIMENTOS
        $content = View::render('pages/testimonies', [
            'itens' => self::getTestimonyItems()
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

        return self::getTestimonies();
    }

}