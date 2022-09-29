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

        //MÉTODO QUE RETORNA UM USUÁRIO COM BASE EM SEU EMAIL
        public static function getUserByEmail($email){
            return (new Database('usuarios')) -> select('email = "'.$email.'"') -> fetchObject(self::class);
        }

        //MÉTODO QUE RETORNAR USUÁRIOS
        //TODOS PARAM - STRING, RETURN - PDOStatment
        public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('usuarios')) -> select($where, $order, $limit, $fields);
        }
    }
