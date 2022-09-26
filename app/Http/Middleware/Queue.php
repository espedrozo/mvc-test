<?php

namespace App\Http\Middleware;

class Queue{

    //MAPEAMENTO DE MIDDLEWARES
    private static $map = [];

    //MAPEAMENTO DE MIDDLEWARES QUE SERÃO CARREGADOS EM TODAS AS ROTAS
    private static $default = [];

    //FILA DE MIDDLEWARES A SEREM EXECUTADOS    
    private $middlewares = [];

    //FUNÇÃO DE EXECUÇÃO DO CONTROLADOR (closure)
    private $controller;

    //ARGUMENTOS DA FUNÇÃO DO CONTROLADOR
    private $controllerArgs = [];

    //MÉTODO QUE CONSTRÓI A CLASSE DE FILA DE MIDDLEWARE
    public function __construct($middlewares, $controller, $controllerArgs){
        $this -> middlewares    = array_merge(self::$default, $middlewares);
        $this -> controller     = $controller;
        $this -> controllerArgs = $controllerArgs;
    }

    //MÉTODO QUE DEFINE O MAPEAMENTO DE MIDDLEWARES
    public static function setMap($map){
        self::$map = $map;
    }

     //MÉTODO QUE DEFINE O MAPEAMENTO DE MIDDLEWARES PADRÕES
     public static function setDefault($default){
        self::$default = $default;
    }
    
    //MÉTODO QUE EXECUTA O PRÓXIMO NÍVEL DA FILA DE MIDDLEWARES
    public function next($request){
        // echo "<pre>";
        // print_r($this);
        // echo "</pre>"; 
        // exit;
    
        //VERIFICA SE A FILA ESTÁ VAZIA
        if(empty($this -> middlewares)) return call_user_func_array($this -> controller, $this -> controllerArgs);

        // echo "<pre>";
        // print_r($this->middlewares);
        // echo "</pre>"; 
        // ;

        //SE NÃO VAZIA, MIDDLEWARES
        $middleware = array_shift($this -> middlewares);
        
        // echo "<pre>";
        // print_r($this->middlewares);
        // echo "</pre>"; 
        // ;
                
        // echo "<pre>";
        // print_r($middleware);
        // echo "</pre>"; 
        // exit;

        //VERIFICA O MAPEAMENTO
        if(!isset(self::$map[$middleware])){
            throw new \Exception("Problemas ao processar o middleware da requisição", 500);
        }
     
        //NEXT
        $queue = $this;
        $next = function($request) use($queue){
            return $queue -> next($request);
        };

        //EXECUTA O MIDDLEWARE
        return (new self::$map[$middleware]) -> handle($request, $next);
    }
}
