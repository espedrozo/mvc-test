<?php

namespace App\Controller\Api;

use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

//MÉTODO QUE RETORNA OS DEPOIMENTOS CADASTRADOS
class User extends Api{

    //MÉTODO QUE OBTÉM A RENDERIZAÇÃO DOS ITENS DE USUÁRIOS PARA A PÁGINA
    private static function getUserItems($request, &$obPagination){
        //DEPOIMENTOS
        $itens = [];

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadeTotal = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd') -> fetchObject() -> qtd;

        //PÁGINA ATUAL
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //INSTÂNCIA DE PAGINAÇÃO
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

        //RESULTADOS DA PÁGINA
        $results = EntityUser::getUsers(null, 'id ASC', $obPagination -> getLimit());

        //RENDERIZA O ITEM
        while($obUser = $results -> fetchObject(EntityUser::class)){

            $itens[] = [
                'id'        => (int)$obUser -> id,
                'nome'      => $obUser -> nome,
                'email'  => $obUser -> email
            ];
        }

        //RETORNA OS USUÁRIOS
        return $itens;
    }

    //MÉTODO QUE RETORNA OS USUÁRIOS CADASTRADOS
    public static function getUsers($request){
        return [
            'usuarios'   => self::getUserItems($request, $obPagination),
            'paginacao'     => parent::getPagination($request, $obPagination)
        ];
    }

    //MÉTODO QUE RETORNA OS DETALHES DE UM USUÁRIO
    public static function getUser($request, $id){

        //VALIDA O ID DO USUÁRIO
        if(!is_numeric($id)){
            throw new \Exception("O id '".$id."' não é válido.", 400 );
        }

        //BUSCA USUÁRIO
        $obUser = EntityUser::getUserById($id);
       
        //VALIDA SE O USUÁRIO EXISTE
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário ".$id." não foi encontrado", 404);
        }

        //RETORNA OS DETALHES DO USUÁRIO
        return [
            'id'        => (int)$obUser -> id,
            'nome'      => $obUser -> nome,
            'email'  => $obUser -> email
        ];
    }

    //MÉTODO QUE CADASTRA UM NOVO USUÁRIO
    public static function setNewUser($request){
        //POST VARS
        $postVars = $request -> getPostVars();

        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if(!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])){
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        //VALIDA SE HÁ DUPLICAÇÃO DE USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser){
            throw new \Exception("O email '".$postVars['email']."' já está em uso.", 400);
        }

        //NOVO USUÁRIO
        $obUser = new EntityUser;
        $obUser -> nome  = $postVars['nome'];
        $obUser -> email = $postVars['email'];
        $obUser -> senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obUser -> cadastrar();

        
        //RETORNA OS DETALHES DO USUÁRIO CADASTRADO
        return [
            'id'        => (int)$obUser -> id,
            'nome'      => $obUser -> nome,
            'email'  => $obUser -> email
        ];
    }

    //MÉTODO QUE ATUALIZA UM USUÁRIO
    public static function setEditUser($request, $id){
        //POST VARS
        $postVars = $request -> getPostVars();

        //VALIDA OS CAMPOS OBRIGATÓRIOS
        if(!isset($postVars['nome']) or !isset($postVars['email']) or !isset($postVars['senha'])){
            throw new \Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        //BUSCA USUÁRIO
        $obUser = EntityUser::getUserById($id);
       
        //VALIDA SE O USUÁRIO EXISTE
        if(!$obUser instanceof EntityUser){
            throw new \Exception("O usuário ".$id." não foi encontrado", 404);
        }

        //VALIDA SE HÁ DUPLICAÇÃO DE USUÁRIO
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if($obUserEmail instanceof EntityUser && $obUserEmail -> id != $obUser -> id){
            throw new \Exception("O email '".$postVars['email']."' já está em uso.", 400);
        }

        //ATUALIZA O USUÁRIO
        $obUser -> nome  = $postVars['nome'];
        $obUser -> email = $postVars['email'];
        $obUser -> senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        $obUser -> atualizar();

        
        //RETORNA OS DETALHES DO USUÁRIO CADASTRADO
        return [
            'id'        => (int)$obUser -> id,
            'nome'      => $obUser -> nome,
            'email'  => $obUser -> email
        ];
    }

    //MÉTODO QUE EXCLUI UM USUÁRIO
    public static function setDeleteUser($request, $id){

       //BUSCA USUÁRIO
       $obUser = EntityUser::getUserById($id);
       
       //VALIDA SE O USUÁRIO EXISTE
       if(!$obUser instanceof EntityUser){
           throw new \Exception("O usuário ".$id." não foi encontrado", 404);
       }

        if($obUser -> id == $request -> user -> id){
            throw new \Exception("Não é possível excluir o cadastro atualmente conectado", 400);
       }

        //EXCLUI O USUÁRIO
        $obUser -> excluir();
        
        //RETORNA O SUCESSO DA EXCLUSÃO DO DEPOIMENTO
        return [
            'sucesso' => true 
        ];
    }
}