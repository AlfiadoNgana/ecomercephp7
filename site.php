<?php
    use \src\Page;
    use \src\Model\Product;
    use \src\Model\Category;
    use \src\Model\Cart;
    use src\Model\Address;
    use src\Model\User;
    $app->get("/", function(){

        $products= Product::listAll();
        $page= new Page();
        $page->setTpl("index",["products"=>Product::checkList($products)]); 
    });

    $app->get("/categories/:idcategory", function($idcategory){
        $page = (isset($_GET["page"])) ? (int)$_GET["page"]:1;
        $category =  new Category();
        $category->get((int)$idcategory);
        $pagination = $category->getProductsPage($page);
        $pages = [];
        for($i=1; $i<=$pagination['pages']; $i++){
            array_push($pages,[
                'link'=>'/categories/'.$category->getidcategory().'?page='.$i,
                'page'=>$i
            ]);
        }
        $page =new Page();
        $page->setTpl("category",['category'=>$category->getValues(),'products'=>$pagination['data'], 'pages'=>$pages]);
    });

    $app->get("/products/:desurl",function($desurl){
        $product = new Product();
        $product->getFromUrl($desurl);
        $page = new Page();
        $page->setTpl('product-detail',['product'=>$product->getValues(),'categories'=>$product->getCategories()]);
    });

    $app->get("/cart", function(){
        $cart = Cart::getFromSession();
        $page = new Page();
        $page->setTpl("cart",['cart'=>$cart->getValues(),'products'=>$cart->getProducts(),'error'=>Cart::getMsgError()]);
    });

    $app->get("/cart/:idproduct/add",function($idproduct){
        $product = new Product();
        $product->get((int)$idproduct);
        $cart = Cart::getFromSession();
        $qtd = (isset($_GET['qtd']))?$_GET['qtd'] : 1;
        for($i = 0; $i<$qtd; $i++){
            $cart->addProduct($product);
        }
        header("Location: /cart");
        exit;
    });
    
    $app->get("/cart/:idproduct/minus",function($idproduct){
        $product = new Product();
        $product->get((int)$idproduct);
        $cart = Cart::getFromSession();
        $cart->removeProduct($product);
        header("Location: /cart");
        exit;
    });

    $app->get("/cart/:idproduct/remove",function($idproduct){
        $product = new Product();
        $product->get((int)$idproduct);
        $cart = Cart::getFromSession();
        $cart->removeProduct($product, true);
        header("Location: /cart");
        exit;
    });

    $app->post("/cart/freight", function(){
        $cart = Cart::getFromSession();
        $cart->setFreight($_POST['zipcode']);
        header("Location:/cart");
        exit;
    });

    $app->get("/checkout", function(){
        User::verifyLogin(false);
        $cart = Cart::getFromSession();
        $address = new Address();
        $page = new Page();
        $page->setTpl("checkout",[
            'cart'=>$cart->getValues(),
            'address'=>$address->getValues()
        ]);
    });
    $app->get("/login", function(){
        $page = new Page();
        $page->setTpl("login",[
            'error'=>User::getError()
        ]);
    });

    $app->post("/login", function(){
        try{
            $user=User::login($_POST['login'],$_POST['password']);
            header("Location: /checkout");
            exit;
        }catch(Exception $ex){
            User::setError($ex->getMessage());
        }
    });

    $app->get("/logout", function(){
        User::logout();
        header("Location: /login");
        exit;
    });

    $app->get("/forgot", function(){
        $page= new Page();
        $page->setTpl("forgot");
    });

    $app->post("/forgot", function(){
        
        $user = User::getForgot($_POST['email'],false);

        header("Location: /forgot/sent");
        exit;
    });

    $app->get("/forgot/sent", function(){
        $page= new Page();
        $page->setTpl("forgot-sent");
    });

    $app->get("/forgot/reset", function(){
        $user = User::validForgotDecrypt(str_replace(' ','+',$_GET['code']));
        
        $page= new Page();
        $page->setTpl("forgot-reset", array(
            "name"=>$user["desperson"],
            "code"=>$_GET["code"]
        ));
    });

    $app->post("/forgot/reset", function(){
        
        $forgot = User::validForgotDecrypt(str_replace(' ','+',$_POST['code']));

        User::setForgotUsed($forgot["idrecovery"]);

        $user=new User();
        
        $user->get((int)$forgot["iduser"]);

        $password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
            "cost"=>12
        ]);

        $user->setPassword($password);

        $page= new Page();
        $page->setTpl("forgot-reset-success");
    });
?>