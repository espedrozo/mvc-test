<?php
 
    require __DIR__.'/../vendor/autoload.php';

    use \App\Utils\View;
    use \WilliamCosta\DotEnv\Environment;
    use \WilliamCosta\DatabaseManager\Database;
    use \App\Http\Middleware\Queue as MiddlewareQueue;
    
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

    //DEFINE O VALOR PADRÃO DAS VARIÁVEIS
    View::init([
        'URL' => URL
    ]);

    MiddlewareQueue::setMap([
        'maintenance' => \App\Http\Middleware\Maintenance::class,
        'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
        'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class,
        'api' => \App\Http\Middleware\Api::class,
        'user-basic-auth' => \App\Http\Middleware\UserBasicAuth::class
    ]);

    //DEFINE O MAPEAMENTO DE MIDDLEWARES PADRÕES (EXECUTADOS EM TODAS AS ROTAS)
    MiddlewareQueue::setDefault([
        'maintenance'
    ]);