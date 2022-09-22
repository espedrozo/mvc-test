<?php
 
namespace App\Utils;

//Método retorna o conteúdo de uma view
 class View{

    //variáveis padrões da view
    private static $vars = [];

    //método responsável por definir os dados iniciais da classe
    public static function init($vars = []){
        self::$vars = $vars;
    }

    private static function getContentView($view){
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }



//MÉTODO RESPONSÁVEL POR RETORNAR O CONTEÚDO RENDERIZADO DE UMA VIEW
    public static function render($view, $vars = []){

        //CONTEÚDO DA VIEW
        $contentView = self::getContentView($view);

        //MERGE DE VARIÁVEIS DA VIEW
        $vars = array_merge(self::$vars, $vars);

        //CHAVES DO ARRAY DE VARIÁVEIS
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);
       
        // echo "<pre>";
        // print_r($keys);
        // echo "</pre>"; 
        //exit;
        
        //RETORNA CONTEÚDO RENDERIZADO
        return str_replace($keys, array_values($vars), $contentView);
    }
 }