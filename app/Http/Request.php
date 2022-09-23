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
        $this->router       = $router;
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
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
