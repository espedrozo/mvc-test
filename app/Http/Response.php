<?php

namespace App\Http;

class Response{
    private $httpCode = 200;

    //CABEÇALHO DO RESPONSE
    private $headers = [];

    //TIPO DE CONTEÚDO RETORNADO
    private $contentType = 'text/html';

    //CONTEÚDO DO RESPONSE
    private $content;

    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this -> httpCode = $httpCode;
        $this -> content = $content;
        $this -> setContentType($contentType);
    }

    //METODO RESPONSÁVEL POR ALTERAR O CONTENT TYPE DO RESPONSE
    public function setContentType($contentType){
        $this -> contentType = $contentType;
        $this -> addHeader('Content-Type', $contentType);
    }

    //MÉTODO RESPONSÁVEL POR ADICIONAR REGISTRO NO CABEÇALHO DE RESPONSE
    public function addHeader($key, $value){
        $this -> headers[$key] = $value;
    }

    //MÉTODO RESPONSÁVEL POR ENVIAR OS HEADERS PARA O NAVEGADOR
    private function sendHeaders(){

        //STATUS
        http_response_code($this -> httpCode);

        //ENVIAR HEADERS
        foreach($this -> headers as $key=>$value){
            header($key.': '.$value);
        }
    }

    //MÉTODO RESPONSÁVEL POR ENVIAR A RESPOSTA PARA O USUÁRIO
    public function sendResponse(){
        
        $this -> sendHeaders();

        //IMPRIME O CONTEÚDO
        switch ($this -> contentType){
            case 'text/html':
                echo $this -> content;
                exit;
        }
    }
}