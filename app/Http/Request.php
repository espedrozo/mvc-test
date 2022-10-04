<?php

namespace App\Http;

//MÉTODO HTTP DA REQUISIÇÃO
class Request{

    //INSTÂNCIA DO ROUTER
    private $router;

    //MÉTODO HTTP DA REQUISIÇÃO
    private $httpMethod;

    //URI DA PÁGINA
    private $uri;

    //PARÂMETROS DA URL ($_GET)
    private $queryParams = [];

    //VARIÁVEIS RECEBIDAS NO POST DA PÁGINA
    private $postVars = [];

    //CABEÇALHO DA REQUISIÇÃO
    private $headers = [];

    //CONSTRUTOR DA CLASSE
    public function __construct($router){
        $this -> router       = $router;
        $this -> queryParams  = $_GET ?? [];
        $this -> headers      = getallheaders();
        $this -> httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this -> setUri();
        $this -> setPostVars();
    }

    //MÉTODO QUE DEFINE AS VARIÁVEIS DO POST
    private function setPostVars(){
        
        //VERIFICA O MÉTODO DA REQUISIÇÃO
        if($this -> httpMethod == 'GET') return false;

        //POST PADRÃO
        $this->postVars = $_POST ?? [];

        //POST JSON
        $inputRaw = file_get_contents('php://input');
        $this -> postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this -> postVars;
    }

    //MÉTODO QUE DEFINE A URI
    private function setUri(){
        //URI COMPLETA (COM GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        //REMOVE GETS DA URI
        $xURI = explode('?', $this->uri);
        $this->uri = $xURI[0];
    }

    //MÉTODO QUE RETORNA A INSTÂNCIA DE ROUTER
    public function getRouter(){
        return $this->router;
    }

    //MÉTODO REPONSÁVEL POR RETORNAR O MÉTODO HTTP DA REQUISIÇÃO
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    //MÉTODO REPONSÁVEL POR RETORNAR A URI DA REQUISIÇÃO
    public function getUri(){
        return $this->uri;
    }

    //MÉTODO REPONSÁVEL POR RETORNAR OS HEADERS DA REQUISIÇÃO
    public function getHeaders(){
        return $this->headers;
    }

    //MÉTODO REPONSÁVEL POR RETORNAR OS PARÂMETROS DA URL DA REQUISIÇÃO
    public function getQueryParams(){
        return $this->queryParams;
    }

    //MÉTODO REPONSÁVEL POR RETORNAR AS VARIÁVEIS DO POST DA REQUISIÇÃO
    public function getPostVars(){
        return $this->postVars;
    }

}
