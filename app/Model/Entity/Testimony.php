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

        //MÉTODO RESPONSÁVEL POR RETORNAR DEPOIMENTOS
        public static function getTestimonies($where = null, $order = null, $limit = null, $field = '*'){

        }
    }