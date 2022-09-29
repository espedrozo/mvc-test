<?php

    namespace App\Model\Entity;

    use \WilliamCosta\DatabaseManager\Database;

    class Testimony{
        //ID DO DEPOIMENTO
        public $id;

        //NOME DO USUÁRIO QUE FEZ O DEPOIMENTO
        public $nome;

        //MENSAGEM DO DEPOIMENTO
        public $mensagem;

        //DATA DE PUBLICAÇÃO DO DEPOIMENTO
        public $data;

        //MÉTODO QUE CADASTRA A INSTANCIA ATUAL DO BANCO DE DADOS
        public function cadastrar(){
            //DEFINE A DATA
            $this -> data = date('Y-m-d H:i:s');

            //INSERE O DEPOIMENTO NO BANCO DE DADOS
            $this -> id = (new Database('depoimentos')) -> insert ([
                'nome'      => $this -> nome,
                'mensagem'  => $this -> mensagem,
                'data'      => $this -> data
            ]);
      
            return true;
        }

        //MÉTODO QUE ATUALIZA OS DADOS DO DATABASE COM A INSTANCIA ATUAL
        public function atualizar(){

            //ATUALIZA O DEPOIMENTO NO BANCO DE DADOS
            return (new Database('depoimentos')) -> update ('id = '.$this -> id, [
                'nome'      => $this -> nome,
                'mensagem'  => $this -> mensagem,
            ]);
        }

        //MÉTODO QUE EXCLUI UM DEPOIMENTO DO DATABASE
        public function excluir(){

            //EXCLUI O DEPOIMENTO DO BANCO DE DADOS
            return (new Database('depoimentos')) -> delete ('id = '.$this -> id, [
            ]);
        }

        //MÉTODO QUE RETORNA UM DEPOIMENTO COM BASE EM SEU ID
        public static function getTestimonyById($id){
            
            return self::getTestimonies('id = '.$id) -> fetchObject(self::class);
        }

        //MÉTODO QUE RETORNAR DEPOIMENTOS
        //TODOS PARAM - STRING, RETURN - PDOStatment
        public static function getTestimonies($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('depoimentos')) -> select($where, $order, $limit, $fields);
        }
    }