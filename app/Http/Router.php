<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router {
    private $url = '';

    private $prefix = '';

    private $routes = [];

    //INSTANCIA DE REQUEST
    private $request;

    //MÉTODO RESPONSAVEL POR INICIAR A CLASSE
    public function __construct($url){
        $this -> request = new Request();
        $this -> url = $url;
        $this -> setPrefix();
    }

    //MÉTODO QUE DEFINE O PREFIXO DAS ROTAS
    private function setPrefix(){

        //INFORMAÇÕES DA URL ATUAL
        $parseUrl = parse_url($this -> url);
        
        //DEFINE O PREFIXO
        $this -> prefix = $parseUrl['path'] ?? '';
    }

    //MÉTODO QUE ADICONA UMA ROTA NA CLASSE
    private function addRoute($method, $route, $params = []){

        //VALIDAÇÃO DOS PARAMETROS
        foreach($params as $key => $value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        //VARIÁVEIS DA ROTA
        $params['variables'] = [];

        //PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
           
        }
        
        //PADRÃO VALIDAÇÃO DA URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        
        //ADICIONA ROTA DENTRO DA CLASSE
        $this -> routes[$patternRoute][$method] = $params;
        // echo "<pre>";
        // print_r($this);
        // echo "</pre>"; 
        // exit;
        
    }

    //MÉTODO QUE DEFINE ROTA DE GET
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

    //RETORNA URI SEM PREFIXO
    private function getUri(){
        //URI DA REQUEST - INSTANCIADA
        $uri = $this -> request -> getUri();

        // FATIAR A URI COM O PREFIXO
        $xUri = strlen($this -> prefix) ? explode($this -> prefix, $uri) : [$uri];

        // echo "<pre>";
        // print_r($xUri);
        // echo "</pre>"; 
        // exit;

        //RETORNA A URI SEM PREFIXO
        return end($xUri) ?? '/';        
    }


    private function getRoute(){

        //URI
        $uri = $this -> getUri();
        
        //METHOD
        $httpMethod = $this -> request -> getHttpMethod();

        //VALIDA AS ROTAS
        foreach($this -> routes as $patternRoute => $methods){
            if(preg_match($patternRoute, $uri, $matches)){

                //VERIFICA O MÉTODO
                if(isset($methods[$httpMethod])){

                    //REMOVE A PRIMEIRA POSIÇÃO
                    unset($matches[0]);
                    
                    //VARIÁVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this -> request;


                    //RETORNO PARÂMETROS DA ROTA
                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
        }
        //URL NÃO ENCONTRADA
        throw new Exception("URL não encontrada", 404);
    }

    //MÉTODO QUE EXECUTA A ROTA ATUAL
    public function run(){
        try{
        $route = $this->getRoute();
        
        //VERIFICA O CONTROLADOR
        if(!isset($route['controller'])) {
            throw new Exception("A URL não pôde ser processada", 500);
        }

        //ARGUMENTOS DA FUNÇÃO
        $args = [];

        //REFLECTION
        $reflection = new ReflectionFunction($route['controller']);
        foreach($reflection -> getParameters() as $parameter){
            $name = $parameter -> getName();
            $args[$name] = $route['variables'][$name] ?? '';
        }

        //RETORNA A EXECUÇÃO DA FUNÇÃO
        return call_user_func_array($route['controller'], $args);
            
        }catch(Exception $e) {
            return new Response($e -> getCode(), $e -> getMessage());
        }
    }
}