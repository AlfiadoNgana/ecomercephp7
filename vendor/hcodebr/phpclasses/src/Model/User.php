<?php

    namespace src\Model;
    use src\DB\Sql;
    use src\Mailer;
    use src\Model;

    class User extends Model{
        const SESSION="User";
        const SECRET ="HcodePhp7_Secret"; //tem que ter pelomenos 16 caracters

        public static function login($usuario, $password){

            $sql=new Sql();

            $results = $sql->select("SELECT * FROM tb_users WHERE deslogin= :USUARIO", array(":USUARIO"=>$usuario));

            if(count($results) === 0) throw new \Exception("Usuario inexistente ou senha Invalida");
            
            $data= $results[0];
            if(password_verify($password,$data['despassword']) ===true){

                $user=new User();
                $user->setData($data);
                $_SESSION[User::SESSION] =$user->getValues();
                return $user;

            }else{
                throw new \Exception("Usuario inexistente ou senha Invalida");
            }
        }

        public static function verifyLogin($inadmin=true){

            if(!isset($_SESSION[User::SESSION]) || !$_SESSION[User::SESSION] || !(int)$_SESSION[User::SESSION]["iduser"]>0 || (bool)$_SESSION[User::SESSION]["inadmin"]!==$inadmin){
                header("Location: /admin/login");
                exit;
            }
        }

        public static function logOut(){

            session_destroy();
        }

        public static function listAll(){

            $sql = new Sql();
            return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

        }

        public function save(){
            $sql = new Sql();
            $resultado = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
                ":desperson"=>$this->getdesperson(),
                ":deslogin"=>$this->getdeslogin(),
                ":despassword"=>password_hash($this->getdespassword(),PASSWORD_DEFAULT,["cost"=>12]),
                ":desemail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":inadmin"=>$this->getinadmin()
            ));

            $this->setData($resultado[0]);
        }

        public function get($iduser){

            $sql = new Sql();

            $result=$sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser=:iduser",array(
                ":iduser"=>$iduser
            ));
            $this->setData($result[0]);
        }

        public function update(){

            $sql = new Sql();
            $resultado = $sql->select("CALL sp_usersupdate_save(:iduser,:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
                ":iduser"=>$this->getiduser(),
                ":desperson"=>$this->getdesperson(),
                ":deslogin"=>$this->getdeslogin(),
                ":despassword"=>$this->getdespassword(),
                ":desemail"=>$this->getdesemail(),
                ":nrphone"=>$this->getnrphone(),
                ":inadmin"=>$this->getinadmin()
            ));
            $this->setData($resultado);
        }

        public function delete(){
            $sql = new Sql();

            $sql->query("CALL sp_users_delete(:iduser)", array(":iduser"=>$this->getiduser()));
        }

        public static function getForgot($email){

            $sql = new Sql();

            $results = $sql->select("SELECT * FROM tb_persons a INNER JOIN tb_users b USING(idperson) WHERE a.desemail = :email", array(":email"=>$email));

            if(count($results)===0){
                throw new \Exception("Nao foi possivel especificar a senha");
            }else{
                $data = $results[0];
                $result2=$sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(":iduser"=>$data['iduser'], ":desip"=>$_SERVER['REMOTE_ADDR']));
                if(count($result2)===0){
                    throw new \Exception("Nao foi possivel especificar a senha");
                }else{
                    $dataRecovery = $result2[0];

                    $code=base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,User::SECRET, $dataRecovery["idrecovery"], MCRYPT_MODE_ECB));
                    $link="http://www.phpecomerce.co.mz/admin/forgot/reset?code=$code";

                    $mailer = new Mailer($data['desemail'], $data['desperson'], "Redefinir senha da Hcode Store","forgot",array(
                        "name"=>$data['desperson'],
                        "link"=>$link
                    ));
                    $mailer->send();

                    return $data;
                }
            }
        }

        public static function validForgotDecrypt($code){

            $idrecovery=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,User::SECRET,base64_decode($code), MCRYPT_MODE_ECB);
            $sql= new Sql();
            $results=$sql->select("
                SELECT * FROM tb_userspasswordsrecoveries a 
                INNER JOIN tb_users b USING(iduser)
                INNER JOIN tb_persons c USING(IDPERSON)
                WHERE a.idrecovery = :idrecovery AND a.dtrecovery IS NULL AND DATE_ADD(a.dtregister, INTERVAL 1 HOUR)>=NOW();
            ",array(":idrecovery"=>$idrecovery));

            if(count($results)===0){
                throw new \Exception("Nao foi possivel recuperar a senha");
            }else{
                return $results[0];
            }

        }

        public static function setForgotUsed($idrecovery){
            $sql = new Sql();
            $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery",array(":idrecovery"=>$idrecovery));

        }

        public function setPassword($password){
            $sql = new Sql();
            $sql->query("UPDATE tb_users SET despassword = :newpassword WHERE iduser = :iduser", array(":newpassword"=>$password,":iduser"=>$this->getiduser()));
        }

    }

?>