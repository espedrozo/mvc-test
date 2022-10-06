<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;
use \App\Http\Middleware\Queue as MiddlewareQueue;

class Router {
    private $url = '';

    private $prefix = '';

    private $routes = [];

    //INSTANCIA DE REQUEST
    private $request;

    //CONTENT TYPE PADRÃO DO RESPONSE
    private $contentType = 'text/html';

    //MÉTODO RESPONSAVEL POR INICIAR A CLASSE
    public function __construct($url){
        $this -> request = new Request($this);
        $this -> url = $url;
        $this -> setPrefix();
    }

    //MÉTODO QUE ALTERA O VALOR DO CONTENT TYPE
    public function setContentType($contentType){
        $this -> contentType = $contentType;
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



        //MIDDLEWARES DA ROTA
        $params['middlewares'] = $params['middlewares'] ?? [];

        // echo "<pre>";
        // print_r($params);
        // echo "</pre>"; 
        // exit;

        //VARIÁVEIS DA ROTA
        $params['variables'] = [];

        //PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        //REMOVE BARRA NO FINAL DA ROTA
        $route = rtrim($route, '/');
        
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
    public function getUri(){
        //URI DA REQUEST - INSTANCIADA
        $uri = $this -> request -> getUri();

        // FATIAR A URI COM O PREFIXO
        $xUri = strlen($this -> prefix) ? explode($this -> prefix, $uri) : [$uri];

        //RETORNA A URI SEM PREFIXO
        return rtrim(end($xUri), '/');        
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

            // echo "<pre>";
            // print_r($args);
            // echo "</pre>"; 
            // exit;

            //RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES
            return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args)) -> next($this->request);  
        }catch(Exception $e) {
            return new Response($e -> getCode(), $this -> getErrorMessage($e -> getMessage()), $this -> contentType);
        }
    }

    //MÉTODO QUE RETORNA A MENSAGEM DE ERRO DE ACORDO COM O CONTENT TYPE
    private function getErrorMessage($message){
        switch ($this -> contentType){
            case 'application/json':
                return [
                    'error' => $message
                ];
                break;
            default:
                return $message;
                break;
            
        }
    }

    //MÉTODO QUE RETORNA A URL ATUAL
    public function getCurrentUrl(){
        return $this -> url.$this -> getUri();
    }

    //MÉTODO QUE REDIRECIONA A URL
    public function redirect($route){
        //URL
        $url = $this -> url.$route;
        
        //EXECUTA O REDIRECT
        header('location: '.$url);
        exit;
    }
}