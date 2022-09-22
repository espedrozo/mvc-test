<?php
 
    require __DIR__.'/../vendor/autoload.php';

    use \App\Utils\View;
    use \WilliamCosta\DotEnv\Environment;
    use \WilliamCosta\DatabaseManager\Database;
    
    //CARREGA VARIÁVEIS DE AMBIENTE
    Environment::load(__DIR__.'/../');


    //DEFINE AS CONFIG DE BANCO DE DADOS
    Database::config(
        getenv('DB_HOST'),
        getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASS'),
        getenv('DB_PORT'),
    );


        // echo "<pre>";
        // print_r(getenv('URL'));
        // echo "</pre>"; 
        // exit;

    //DEFINE A CONSTANTE DE URL
    define('URL', getenv('URL'));

    // echo "teste";
    // $response = new App\Http\Response(200,'OOO');
    // $response->sendResponse();
    // exit;

    //DEFINE O VALOR PADRÃO DAS VARIÁVEIS
    View::init([
        'URL' => URL
    ]);