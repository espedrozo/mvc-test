<?php

namespace App\Http;

//método http da requisição
class Request{
    private $httpMethod;

    private $uri;

    //parâmetros da url ($_GET)
    private $queryParams = [];

    //variáveis recebidas no POST da página
    private $postVars = [];

    //cabeçalho da requisição
    private $headers = [];

    public function __construct(){
        $this->queryParams  = $_GET ?? [];
        $this->postVars     = $_POST ?? [];
        $this->headers      = getallheaders();
        $this->httpMethod   = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri          = $_SERVER['REQUEST_URI'] ?? '';
    }

    //método reponsável por retornar o método http da requisição
    public function getHttpMethod(){
        return $this->httpMethod;
    }

    //método reponsável por retornar a Uri da requisição
    public function getUri(){
        return $this->uri;
    }

    //método reponsável por retornar os headers da requisição
    public function getHeaders(){
        return $this->headers;
    }

    //método reponsável por retornar os parâmetros da URL da requisição
    public function getQueryParams(){
        return $this->queryParams;
    }

    //método reponsável por retornar as variáveis do POST da requisição
    public function getPostVars(){
        return $this->postVars;
    }

}
