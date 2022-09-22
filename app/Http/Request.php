<?php

namespace App\Http;

//MÉTODO HTTP DA REQUISIÇÃO
class Request{
    private $httpMethod;

    private $uri;

    //PARÂMETROS DA URL ($_GET)
    private $queryParams = [];

    //VARIÁVEIS RECEBIDAS NO POST DA PÁGINA
    private $postVars = [];

    //CABEÇALHO DA REQUISIÇÃO
    private $headers = [];

    public function __construct(){
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri          = $_SERVER['REQUEST_URI'] ?? '';
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
