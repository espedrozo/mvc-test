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

  

        //PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTÂNCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        //RESULTADOS DA PÁGINA
        $results = EntityUser::getUsers(null, 'id DESC', $obPagination -> getLimit());

        //RENDERIZA O ITEM
        while($obUser = $results -> fetchObject(EntityUser::class)){
        //     echo "<pre>";
        // print_r($obUser);
        // echo "</pre>"; 
        // exit;

            $itens .= View::render('admin/modules/users/item', [
                'id'        => $obUser -> id,
                'nome'      => $obUser -> nome,
                'email'     => $obUser -> email
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

    //MÉTODO QUE RETORNA O FORMULÁRIO DE CADASTRO DE NOVO USUÁRIO
    public static function getNewUser($request){
        
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form', [
            'title'     => 'Cadastrar Usuário',
            'nome'      => '',
            'email'     => '',
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Cadastrar Usuário > WDEV', $content, 'users');
    }

    //MÉTODO QUE CADASTRA UM USUÁRIO NO BANCO DE DADOS
    public static function setNewUser($request){
        //POST VARS
        $postVars = $request -> getPostVars();
        $nome   = $postVars['nome'] ?? '';
        $email  = $postVars['email'] ?? '';
        $senha  = $postVars['senha'] ?? '';

        //VALIDA O EMAIL DO USUÁRIO
        $obUser = EntityUser::getUserByEmail($email);
        if($obUser instanceof EntityUser){
            //REDIRECIONA O USUÁRIO
            $request -> getRouter() -> redirect('/admin/users/new?status=duplicated');
        }
        // echo "<pre>";
        // print_r($email);
        // echo "</pre>"; 
        // exit;
        
        //NOVA INSTANCIA DE USUÁRIO
        $obUser = new EntityUser;
        $obUser -> nome = $nome;
        $obUser -> email = $email;
        $obUser -> senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser -> cadastrar();
        
        //REDIRECIONA O USUÁRIO
        $request -> getRouter() -> redirect('/admin/users/'.$obUser -> id.'/edit?status=created');
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
                return Alert::getSuccess('Usuário criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Usuário atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
                break;
            case 'duplicated':
                return Alert::getError('O e-mail digitado já está sendo utilizado por outro usuário.');
                break;
        }
    }

    //MÉTODO QUE RETORNA O FORMULÁRIO DE EDIÇÃO DE UM USUÁRIO
    public static function getEditUser($request, $id){
        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser) {
            $request -> getRouter() -> redirect('/admin/users');
        }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/form', [
            'title'     => 'Editar Usuário',
            'nome'      => $obUser -> nome,
            'email'     => $obUser -> email,
            'status'    => self::getStatus($request)
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Editar Usuário > WDEV', $content, 'users');
    }

    //MÉTODO QUE GRAVA A ATUALIZAÇÃO DE UM USUÁRIO
    public static function setEditUser($request, $id){
        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser) {
            $request -> getRouter() -> redirect('/admin/users');
        }

        //POST VARS
        $postVars = $request -> getPostVars();
        $nome   = $postVars['nome'] ?? '';
        $email  = $postVars['email'] ?? '';
        $senha  = $postVars['senha'] ?? '';

        //VALIDA O EMAIL DO USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($email);
            if($obUserEmail instanceof EntityUser && $obUserEmail -> id != $id){
                //REDIRECIONA O USUÁRIO
                $request -> getRouter() -> redirect('/admin/users/'.$id.'/edit?status=duplicated');
        }

        //ATUALIZA A INSTANCIA
        $obUser -> nome = $nome;
        $obUser -> email = $email;
        $obUser -> senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser -> atualizar();

        //REDIRECIONA O USUÁRIO
        $request -> getRouter() -> redirect('/admin/users/'.$obUser -> id.'/edit?status=updated');
        
    }

    //MÉTODO QUE RETORNA O FORMULÁRIO DE EXCLUSÃO DE UM USUÁRIO
    public static function getDeleteUser($request, $id){
    
       //OBTÉM O USUÁRIO DO BANCO DE DADOS
       $obUser = EntityUser::getUserById($id);

       //VALIDA A INSTANCIA
       if(!$obUser instanceof EntityUser) {
           $request -> getRouter() -> redirect('/admin/users');
       }

        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('admin/modules/users/delete', [
            'nome'   => $obUser -> nome,
            'email'  => $obUser -> email
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('Excluir Usuário > WDEV', $content, 'users');
    }

    //MÉTODO QUE EXCLUI UM USUÁRIO
    public static function setDeleteUser($request, $id){
        
        //OBTÉM O USUÁRIO DO BANCO DE DADOS
        $obUser = EntityUser::getUserById($id);

        //VALIDA A INSTANCIA
        if(!$obUser instanceof EntityUser) {
            $request -> getRouter() -> redirect('/admin/users');
        }


        //EXCLUIR O USUÁRIO
        $obUser -> excluir();

        //REDIRECIONA O USUÁRIO
        $request -> getRouter() -> redirect('/admin/users?status=deleted');
        
    }
}