<?php

namespace App\Utils\Cache;

class File{

    //MÉTODO QUE RETORNA O CAMINHO ATÉ O ARQUIVO DE CACHE
    private static function getFilePath($hash){
        //DIRETÓRIO DE CACHE
        $dir = getenv('CACHE_DIR');

        //VERIFICA A EXISTÊNCIA DO DIRETÓRIO
        if(!file_exists($dir)){
            mkdir($dir, 0755, true);
        }

        //RETORNA O CAMINHO ATÉ O ARQUIVO
        return $dir.'/'.$hash;
    }

    //MÉTODO QUE GUARDA INFORMAÇÕES DO CACHE
    private static function storageCache($hash, $content){
        //SERIALIZA O RETORNO
        $serialize = serialize($content);

        //OBTÉM O CAMINHO ATÉ O ARQUIVO CACHE
        $cacheFile = self::getFilePath($hash);
       
        //GRAVA AS INFORMAÇÕES NO ARQUIVO
        return file_put_contents($cacheFile, $serialize);
    }

    //MÉTODO QUE RETORNA O CONTEÚDO GRAVADO NO CACHE
    private static function getContentCache($hash, $expiration){
        //OBTÉM O CAMINHO DO ARQUIVO
        $cacheFile = self::getFilePath($hash);

        //VERIFICA A EXISTÊNCIA DO ARQUIVO
        if(!file_exists($cacheFile)){
            return false;
        }

        //VALIDA A EXPIRAÇÃO DO CACHE
        $createTime = filectime($cacheFile);
        $diffTime = time() - $createTime;

        if($diffTime > $expiration){
            return false;
        }

        //RETORNA O DADO REAL
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);

        echo "<pre>";
        print_r($serialize);
        echo "</pre>"; 
        exit;
    }

    //MÉTODO QUE OBTÉM UMA INFORMAÇÃO DO CACHE
    public static function getCache($hash, $expiration, $function){
        //VERIFICA O CONTEÚDO GRAVADO
        if($content = self::getContentCache($hash, $expiration)){
            return $content;
        }
        
        //EXECUÇÃO DA FUNÇÃO
        $content = $function();

        //GRAVA O RETORNO NO CACHE
        self::storageCache($hash, $content);

        //RETORNA O CONTEÚDO
        return $content;
    }
}