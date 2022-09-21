<?php

namespace App\Http;

class Response{
    private $httpCode = 200;

    //cabeçalho do response
    private $headers = [];

    //tipo de conteúdo retornado
    private $contentType = 'text/html';

    //conteúdo do response
    private $content;

    public function __construct($httpCode, $content, $contentType = 'text/html'){
        $this -> httpCode = $httpCode;
        $this -> content = $content;
        $this -> setContentType($contentType);
    }

    //metodo responsável por alterar o content type do response
    public function setContentType($contentType){
        $this -> contentType = $contentType;
        $this -> addHeader('Content-Type', $contentType);
    }

    //método responsável por adicionar registro no cabeçalho de response
    public function addHeader($key, $value){
        $this -> headers[$key] = $value;
    }

    //método responsável por enviar os headers para o navegador
    private function sendHeaders(){
        //STATUS
        http_response_code($this -> httpCode);

        //enviar headers
        foreach($this -> headers as $key=>$value){
            header($key.': '.$value);
        }
    }

    //método responsável por enviar a resposta para o usuário
    public function sendResponse(){
        
        $this -> sendHeaders();

        //imprime o conteúdo
        switch ($this -> contentType){
            case 'text/html':
                echo $this -> content;
                exit;
        }
    }
}