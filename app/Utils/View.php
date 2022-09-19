<?php
 
namespace App\Utils;

//Método retorna o conteúdo de uma view
 class View{
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }



/**Método responsável por retornar o conteúdo renderizado de uma view*/
    public static function render($view, $vars = []){
        //Conteúdo da view
        $contentView = self::getContentView($view);

        //Chaves do array de variáveis
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);
       
        // echo "<pre>";
        // print_r($keys);
        // echo "</pre>"; 
        //exit;
        
        //Retorna conteúdo renderizado
        return str_replace($keys, array_values($vars), $contentView);
    }
 }