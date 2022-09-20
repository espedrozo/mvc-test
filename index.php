<?php
    require __DIR__.'/vendor/autoload.php';

    use \App\Http\Router;
    use \App\Http\Response;
    use \App\Controller\Pages\Home;

    define('URL', 'http://localhost/mvc');

    // echo "teste";
    // $response = new App\Http\Response(200,'OOO');
    // $response->sendResponse();

    //exit;

    $obRouter = new Router(URL);

    //ROTA HOME
    $obRouter -> get('/', [
        function(){
            return new Response(200, Home::getHome());
        }
    ]);

    //ROTA SOBRE
    $obRouter -> get('/sobre', [
        function(){
            return new Response(200, Home::getHome());
        }
    ]);

    //imprime o response da rota
    $obRouter -> run()->sendResponse();
   
?>