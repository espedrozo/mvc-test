<?php

namespace App\Http;

use \Closure;
use \Exception;

class Router {
    private $url = '';

    private $prefix = '';

    private $routes = [];

    //instancia de request
    private $request;

    //metodo responsavel por iniciar a classe
    public function __construct($url){
        $this -> request = new Request();
        $this -> url = $url;
        $this -> setPrefix();
    }

    private function setPrefix(){
        $parseUrl = parse_url($this -> url);
        $this -> prefix = $parseUrl['path'] ?? '';
    }

    private function addRoute($method, $route, $params = []){
        //validação dos parametros
        foreach($params as $key => $value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        
        //padrão validação da url
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        
        //adiciona rota dentro da classe
        $this -> routes[$patternRoute][$method] = $params;
        // echo "<pre>";
        // print_r($this);
        // echo "</pre>"; 
        // exit;
        
    }

    public function get($route, $params = []){
        return $this -> addRoute('GET', $route, $params);
    }

    public function post($route, $params = []){
        return $this -> addRoute('POST', $route, $params);
    }

    public function put($route, $params = []){
        return $this -> addRoute('PUT', $route, $params);
    }

    public function delete($route, $params = []){
        return $this -> addRoute('DELETE', $route, $params);
    }

    //retorna uri sem prefixo
    private function getUri(){
        //uri da request - instanciada
        $uri = $this -> request -> getUri();

        $xUri = strlen($this -> prefix) ? explode($this -> prefix, $uri) : [$uri];

        return end($xUri);        
    }


    private function getRoute(){ 
        //uri
        $uri = $this -> getUri();
        
        //method
        $httpMethod = $this -> request -> getHttpMethod();

        //valida as rotas
        foreach($this -> routes as $patternRoute => $methods){
            if(preg_match($patternRoute, $uri)){

                //verifica o método
                if($methods[$httpMethod]){

                    //retorno parâmetros da rota
                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
        }
        //Url não encontrada
        throw new Exception("URL não encontrada", 404);
    }

    
    public function run(){
        try{
        $route = $this->getRoute();
        
        //verifica o controlador
        if(!isset($route['controller'])) {
            throw new Exception("A URL não pôde ser processada", 500);
        }

        //argumentos da função
        $args = [];

        //retorna a execução da função
        return call_user_func_array($route['controller'], $args);
            
        
        }catch(Exception $e) {
            return new Response($e -> getCode(), $e -> getMessage());
        }
    }
}