<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

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

    //método que define o prefixo das rotas
    private function setPrefix(){

        //informações da URL atual
        $parseUrl = parse_url($this -> url);
        
        //define o prefixo
        $this -> prefix = $parseUrl['path'] ?? '';
    }

    //método que adicona uma rota na classe
    private function addRoute($method, $route, $params = []){
        //validação dos parametros
        foreach($params as $key => $value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //variáveis da rota
        $params['variables'] = [];

        //padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
           
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

    //método que define rota de GET
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

        // echo "<pre>";
        // print_r($xUri);
        // echo end($xUri);
        // echo "</pre>"; 
        // exit;
        return end($xUri) ?? '/';        
    }


    private function getRoute(){ 
        //uri
        $uri = $this -> getUri();
        
        //method
        $httpMethod = $this -> request -> getHttpMethod();

        //valida as rotas
        foreach($this -> routes as $patternRoute => $methods){
            if(preg_match($patternRoute, $uri, $matches)){

                //verifica o método
                if(isset($methods[$httpMethod])){
                    //remove a primeira posição
                    unset($matches[0]);
                    
                    //variáveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this -> request;


                    //retorno parâmetros da rota
                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
        }
        //Url não encontrada
        throw new Exception("URL não encontrada", 404);
    }

    //método que executa a rota atual
    public function run(){
        try{
        $route = $this->getRoute();
        
        //verifica o controlador
        if(!isset($route['controller'])) {
            throw new Exception("A URL não pôde ser processada", 500);
        }

        //argumentos da função
        $args = [];

        //reflection
        $reflection = new ReflectionFunction($route['controller']);
        foreach($reflection -> getParameters() as $parameter){
            $name = $parameter -> getName();
            $args[$name] = $route['variables'][$name] ?? '';
        }

        //retorna a execução da função
        return call_user_func_array($route['controller'], $args);
            
        }catch(Exception $e) {
            return new Response($e -> getCode(), $e -> getMessage());
        }
    }
}