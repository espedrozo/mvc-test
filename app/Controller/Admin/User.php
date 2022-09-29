<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;


class User extends Page{

    //MÉTODO QUE OBTEM A RENDERIZAÇÃO DOS ITENS DE USUÁRIOS PARA A PÁGINA
    private static function getUserItems($request, &$obPagination){
        //USUÁRIOS
        $itens = '';

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd') -> fetchObject() -> qtd;

        echo "<pre>";
        print_r($quantidadeTotal);
        echo "</pre>"; 
        exit;

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

    //MÉTODO QUE RENDERIZA A VIEW DE LISTAGEM DE USUÁRIOS
    public static function getUsers($request){

        //CONTEÚDO DA HOME
        $content = View::render('admin/modules/users/index', [
            'itens'      => self::getUserItems($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Usuários > WDEV', $content, 'users');
    }

    //MÉTODO QUE RETORNA O FORMULÁRIO DE CADASTRO DE NOVO DEPOIMENTO
    public static function getNewTestimony($request){
        
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/form', [
            'title'     => 'Cadastrar Depoimento',
            'nome'      => '',
            'mensagem'  => '',
            'status'    => ''
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar Depoimento > WDEV', $content, 'testimonies');
    }

    //MÉTODO QUE CADASTRA UM DEPOIMENTO NO BANCO DE DADOS
    public static function setNewTestimony($request){
        //POST VARS
        $postVars = $request -> getPostVars();
        
        //NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony = new EntityTestimony;
        $obTestimony -> nome = $postVars['nome'] ?? '';
        $obTestimony -> mensagem = $postVars['mensagem'] ?? '';
        $obTestimony -> cadastrar();
        
        //REDIRECIONA O USUÁRIO
        $request -> getRouter() -> redirect('/admin/testimonies/'.$obTestimony -> id.'/edit?status=created');
    }

    //MÉTODO QUE RETORNA A MENSAGEM DE STATUS
    private static function getStatus($request){
        //QUERY PARAMS
        $queryParams = $request -> getQueryParams();

        //STATUS
        if(!isset($queryParams['status'])) return '';

        //MENSAGEM DE STATUS
        switch($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluído com sucesso!');
                break;
        }
    }

    //MÉTODO QUE RETORNA O FORMULÁRIO DE EDIÇÃO DE UM DEPOIMENTO
    public static function getEditTestimony($request, $id){
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony) {
            $request -> getRouter() -> redirect('/admin/testimonies');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/form', [
            'title'     => 'Editar Depoimento',
            'nome'      => $obTestimony -> nome,
            'mensagem'  => $obTestimony -> mensagem,
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Editar Depoimento > WDEV', $content, 'testimonies');
    }

    //MÉTODO QUE GRAVA A ATUALIZAÇÃO DE UM DEPOIMENTO
    public static function setEditTestimony($request, $id){
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony) {
            $request -> getRouter() -> redirect('/admin/testimonies');
        }

        //POST VARS
        $postVars = $request -> getPostVars();

        //ATUALIZA A INSTANCIA
        $obTestimony -> nome = $postVars['nome'] ?? $obTestimony -> nome;
        $obTestimony -> mensagem = $postVars['mensagem'] ?? $obTestimony -> mensagem;
        $obTestimony -> atualizar();

        //REDIRECIONA O USUÁRIO
        $request -> getRouter() -> redirect('/admin/testimonies/'.$obTestimony -> id.'/edit?status=updated');
        
    }

    //MÉTODO QUE RETORNA O FORMULÁRIO DE EXCLUSÃO DE UM DEPOIMENTO
    public static function getDeleteTestimony($request, $id){
    
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony) {
            $request -> getRouter() -> redirect('/admin/testimonies');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/testimonies/delete', [
            'nome'      => $obTestimony -> nome,
            'mensagem'  => $obTestimony -> mensagem,
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Excluir Depoimento > WDEV', $content, 'testimonies');
    }

    //MÉTODO QUE EXCLUI UM DEPOIMENTO
    public static function setDeleteTestimony($request, $id){
        
        //OBTÉM O DEPOIMENTO DO BANCO DE DADOS
        $obTestimony = EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA
        if(!$obTestimony instanceof EntityTestimony) {
            $request -> getRouter() -> redirect('/admin/testimonies');
        }

        //EXCLUIR O DEPOIMENTO
        $obTestimony -> excluir();

        //REDIRECIONA O USUÁRIO
        $request -> getRouter() -> redirect('/admin/testimonies?status=deleted');
        
    }
}