<?php

namespace App\Http\Middleware;

class Queue{

    //MAPEAMENTO DE MIDDLEWARES
    private static $map = [];

    //FILA DE MIDDLEWARES A SEREM EXECUTADOS    
    private $middlewares = [];

    //FUNÇÃO DE EXECUÇÃO DO CONTROLADOR (closure)
    private $controller;

    //ARGUMENTOS DA FUNÇÃO DO CONTROLADOR
    private $controllerArgs = [];

    //MÉTODO QUE CONSTRÓI A CLASSE DE FILA DE MIDDLEWARE
    public function __construct($middlewares, $controller, $controllerArgs){
        $this -> middlewares = $middlewares;
        $this -> controller = $controller;
        $this -> controllerArgs = $controllerArgs;
    }

    //MÉTODO QUE DEFINE O MAPEAMENTO DE MIDDLEWARES
    public static function setMap($map){
        self::$map = $map;
    }
    
    //MÉTODO QUE EXECUTA O PRÓXIMO NÍVEL DA FILA DE MIDDLEWARES
    public function next($request){
    
        echo "<pre>";
        print_r(self::$map);
        echo "</pre>"; 
        

        echo "<pre>";
        print_r($this);
        echo "</pre>"; 
        exit;
    }
}
