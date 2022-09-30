<?php

    namespace App\Model\Entity;

    use \WilliamCosta\DatabaseManager\Database;

    class User{
    
        //ID DO USUÁRIO
        public $id;

        //NOME DO USUÁRIO
        public $nome;

        //EMAIL DO USUÁRIO
        public $email;

        //SENHA DO USUÁRIO
        public $senha;

        //MÉTODO QUE CADASTRA A INSTANCIA ATUAL DO USUÁRIO NO BANCO DE DADOS
        public function cadastrar(){
            //INSERE A INSTANCIA NO BANCO
            $this -> id = (new Database('usuarios')) -> insert([
                'nome'  => $this -> nome,
                'email' => $this -> email,
                'senha' => $this -> senha
            ]);
            //SUCESSO
            return true;
        }

        //MÉTODO QUE ATUALIZA OS DADOS NO BANCO
        public function atualizar(){
            return (new Database('usuarios')) -> update('id = '.$this -> id, [
                'nome'  => $this -> nome,
                'email' => $this -> email,
                'senha' => $this -> senha
            ]);
        }

        //MÉTODO QUE EXCLUI UM USUÁRIO DO BANCO
        public function excluir(){
            return (new Database('usuarios')) -> delete('id = '.$this -> id);
        }

        //MÉTODO QUE RETORNA UMA INSTANCIA DE USER COM BASE NO ID
        public function getUserById($id){
            return self::getUsers('id = '.$id) -> fetchObject(self::class);
        }

        //MÉTODO QUE RETORNA UM USUÁRIO COM BASE EM SEU EMAIL
        public static function getUserByEmail($email){
            return self::getUsers('email = "'.$email.'"') -> fetchObject(self::class);
        }

        //MÉTODO QUE RETORNAR USUÁRIOS
        //TODOS PARAM - STRING, RETURN - PDOStatment
        public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('usuarios')) -> select($where, $order, $limit, $fields);
        }
    }
