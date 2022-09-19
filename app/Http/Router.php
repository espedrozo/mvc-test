<?php

namespace App\Http;

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
    }
}