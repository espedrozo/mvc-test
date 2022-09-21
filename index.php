<?php
    require __DIR__.'/vendor/autoload.php';

    use \App\Http\Router;
    use \App\Utils\View;
    

    define('URL', 'http://localhost:3000/mvc');

    // echo "teste";
    // $response = new App\Http\Response(200,'OOO');
    // $response->sendResponse();
    // exit;

    //define o valor padrão das variáveis
    View::init([
        'URL' => URL
    ]);

    //inicia o router
    $obRouter = new Router(URL);

    //inclui as rotas de páginas
    include __DIR__.'/routes/pages.php';
   
    //imprime o response da rota
    $obRouter -> run()->sendResponse();
   
?>