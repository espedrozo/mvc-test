<?php
    require __DIR__.'/vendor/autoload.php';

    use \App\Http\Router;
    use \App\Controller\Pages\Home;

    $obRouter = new Router('');
    echo "<pre>";
    print_r($obRouter);
    echo "</pre>"; 
    exit;
    echo Home::getHome();
?>