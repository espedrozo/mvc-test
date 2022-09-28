<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Alert{
    
    //MÉTODO QUE RETORNA UMA MENSAGEM DE ERRO
    public static function getError($message){
        return View::render('admin/alert/status', [
            'tipo' => 'danger',
            'mensagem' => $message
        ]);
    }

    //MÉTODO QUE RETORNA UMA MENSAGEM DE SUCESSO
    public static function getSuccess($message){
        return View::render('admin/alert/status', [
            'tipo' => 'success',
            'mensagem' => $message
        ]);
    }
}