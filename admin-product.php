<?php
    use \src\PageAdmin;
    use \src\Model\User;
    use \src\Model\Product;

    $app->get("/admin/products", function(){
        User::verifyLogin();
        $products = Product::listAll();
        $page = new PageAdmin();
        $page->setTpl("products",["products"=>$products]);
    });

    $app->get("/admin/products/create", function(){
        User::verifyLogin();
        $page = new PageAdmin();
        $page->setTpl("products-create");
    });

    $app->post("/admin/products/create", function(){
        User::verifyLogin();
        $product = new Product();
        $product->setData($_POST);
        $product->save();
        header("Location:/admin/products");
        exit;
    });

    $app->get("/admin/products/:idproduct", function($idproduct){
        User::verifyLogin();
        $products = new Product();
        $products->get((int)$idproduct);
        $page = new PageAdmin();
        $page->setTpl("products-update",["product"=>$products->getValues()]);
    });

    $app->post("/admin/products/:idproduct", function($idproduct){
        User::verifyLogin();
        $products = new Product();
        $products->get((int)$idproduct);
        $products->setData($_POST);
        $products->save();
        $products->setPhoto($_FILES['file']);
        header("Location:/admin/products");
        exit;
    });

    $app->get("/admin/products/:idproduct/delete", function($idproduct){
        User::verifyLogin();
        $products = new Product();
        $products->get((int)$idproduct);
        $products->delete();
        header("Location:/admin/products");
        exit;
    });
?>