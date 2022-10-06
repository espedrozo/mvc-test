<?php

namespace App\Http\Middleware;

use \App\Utils\Cache\File as CacheFile;

class Cache{

    //MÉTODO QUE VERIFICA SE A REQUEST ATUAL PODE SER CACHEADA
    private function isCacheable($request){

        //VALIDA O TEMPO DE CACHE
        if(getenv('CACHE_TIME') <= 0){
            return false;
        }

        //VALIDA O MÉTODO DA REQUISIÇÃO
        if($request -> getHttpMethod() != 'GET'){
            return false;
        }

        //VALIDA O HEADER DE CACHE (permite o usuário decidir se quer ou não que o retorno seja cacheable)
        $headers = $request -> getHeaders();
        if(isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache'){
            return false;
        }
        

        //CACHEABLE
        return true;
    }

    //MÉTODO QUE RETORNA A HASH DO CACHE
    private function getHash($request){
        //URI DA ROTA
        $uri = $request -> getRouter() -> getUri();

        //QUERY PARAMS
        $queryParams = $request -> getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';

        //REMOVE AS BARRAS E RETORNA A HASH
        return preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/'));
    }

    //MÉTODO QUE EXECUTA O MIDDLEWARE
    public function handle($request, $next){
        
        //VERIFICA SE A REQUEST ATUAL É CACHEAVEL
        if(!$this -> isCacheable($request)) return $next($request);

        //HASH DO CACHE
        $hash = $this -> getHash($request);
        
        //RETORNA OS DADOS DO CACHE
        return CacheFile::getCache($hash, getenv('CACHE_TIME'), function() use($request, $next){
            return $next($request);
        });
    }
}