<?php

    namespace src\Model;
    use src\DB\Sql;
    use src\Mailer;
    use src\Model;

    class Product extends Model{

        public static function listAll(){

            $sql = new Sql();
            return $sql->select("SELECT * FROM tb_products ORDER BY desproduct");

        }

        public static function checkList($list){

            foreach($list as &$row){
                $p = new Product();
                $p->setData($row);
                $row= $p->getValues();
            }
            return $list;
        }

        public function save(){
            $sql= new Sql();
            $results= $sql->select("CALL sp_products_save(:idproduct, :desproduct,:vlprice,:vlwidth, :vlheight,:vllength,:vlweight,:desurl)", array(
                ":idproduct"=>$this->getidproduct(), ":desproduct"=>$this->getdesproduct(),":vlprice"=>$this->getvlprice(),
                ":vlwidth"=>$this->getvlwidth(), ":vlheight"=>$this->getvlheight(),
                ":vllength"=>$this->getvllength(), ":vlweight"=>$this->getvlweight(), ":desurl"=>$this->getdesurl()
            ));
            $this->setData($results[0]);
        }

        public function get($idproduct){
            $sql = new Sql();

            $result = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(":idproduct"=>$idproduct));

            $this->setData($result[0]);
        }

        public function delete(){

            $sql = new Sql();

            $sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", array(":idproduct"=>$this->getidproduct()));
        }

        public function checkPhoto(){
            if(file_exists($_SERVER['DOCUMENT_ROOT']
            .DIRECTORY_SEPARATOR."res"
            .DIRECTORY_SEPARATOR."site"
            .DIRECTORY_SEPARATOR."img"
            .DIRECTORY_SEPARATOR."products".DIRECTORY_SEPARATOR.$this->getidproduct().".jpg"
            )){
                $url= "/res/site/img/products/".$this->getidproduct().".jpg";
            }else{
                $url= "/res/site/img/product.jpg";
            }
            return $this->setdesphoto($url);
        }

        public function getValues(){
            $this->checkPhoto();
            $values=parent::getValues();
            return $values;
        }

        public function setPhoto($file){
            $extension=explode(".",$file["name"]);
            $extension=end($extension);
            $destino=$_SERVER['DOCUMENT_ROOT']
            .DIRECTORY_SEPARATOR."res"
            .DIRECTORY_SEPARATOR."site"
            .DIRECTORY_SEPARATOR."img"
            .DIRECTORY_SEPARATOR."products".DIRECTORY_SEPARATOR.$this->getidproduct().".jpg";
            //$image="";
            switch($extension){
                case "jpg":
                case "jpeg": 
                $image=imagecreatefromjpeg($file["tmp_name"]);imagejpeg($image, $destino);imagedestroy($image);
                break;
                case "gif": 
                $image=imagecreatefromgif($file["tmp_name"]);imagejpeg($image, $destino);imagedestroy($image);
                break;
                case "png": 
                $image=imagecreatefrompng($file["tmp_name"]);imagejpeg($image, $destino);imagedestroy($image);
                break;
            }
            $this->checkPhoto();
        }

    }

?>