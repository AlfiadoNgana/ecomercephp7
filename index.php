<?php
    session_start();
    require_once("vendor/autoload.php");
    use \Slim\Slim;
    use \src\Page;
    use \src\PageAdmin;
    use \src\Model\User;
    use \src\Model\Category;

    $app=new Slim();

    $app->config("debug",true);

    
    require_once("site.php");
    require_once("admin.php");
    require_once("admin-users.php");
    require_once("admin-category.php");
    require_once("admin-product.php");
    require_once("functions.php");
    $app->run();
?>