<?php 

	namespace Hcode\Model;

	use \Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Mailer;

	class User extends Model {

		const SESSION = "User"; //Constante criado para facilitar codgio e ser chamada em outros lugares.
		const SECRET = "HcodePhp7_Secret";

		protected $fields = [
			"iduser", "idperson", "deslogin", "despassword", "inadmin", "dtergister", "desperson", "desemail", "nrphone"
		];

		public static function login($login, $password) { //Método para verificar se login e senha existem.

			$db = new Sql();

			$results = $db->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array( //Verificando se o login existe e esta correta.
				":LOGIN"=>$login
			));

			if (count($results) === 0) {
				
				throw new \Exception("Não foi possível fazer login."); //A Barra invertida "\" e para ele buscar o erro em outra pasta.
			}

			$data = $results[0];

			if (password_verify($password, $data["despassword"]) === true) { //Verificando se a senha existe e está correta.

				$user = new User();
				$user->setData($data);

				$_SESSION[User::SESSION] = $user->getValues();

				return $user;

			} else {

				throw new \Exception("Não foi possível fazer login."); //A Barra invertida "\" e para ele buscar o erro em outra pasta.

			}

		}

		public static function logout() {

			$_SESSION[User::SESSION] = NULL; //Método para finalizar uma sessão, e deslogar.

		}

		public static function verifyLogin($inadmin = true) {

			if (
				!isset($_SESSION[User::SESSION]) //Verificando se existe uma sessao.
				|| 
				!$_SESSION[User::SESSION] //Verificando se existe uma sessao logada.
				||
				!(int)$_SESSION[User::SESSION]["iduser"] > 0 //Verificando se tem o iduser logado.
				||
				(bool)$_SESSION[User::SESSION]["iduser"] !== $inadmin //Verificando se o iduser é um admin.
			) {
			
				header("Location: /admin/login"); //se ele não passar em alguma das verificações ele retorna para a pagina de login.
				exit;

			}

		}
		
		public static function listAll() {
		
			$sql = new Sql();
			
			return $sql -> select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
		
		}
		
		public function save() {
		
			$sql = new Sql();
			
			$results = $sql -> select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", 
				array(
					":desperson" => utf8_decode($this -> getdesperson()),
					":deslogin" => $this -> getdeslogin(),
					":despassword" => $this -> getdespassword(),
					":desemail" => $this -> getdesemail(),
					":nrphone" => $this -> getnrphone(),
					":inadmin" => $this -> getinadmin()
			
			));
			
			$this -> setData($results[0]);
		
		}
		
		/*public function get($iduser) {
		
			$sql = new Sql();
			
			$results = $sql -> select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
				":iduser" => $iduser
			));
			
			$this -> setData($results[0]);
		
		}*/
		
		public function get($iduser)
		{
 
			$sql = new Sql();
 
			$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser;", array(
			":iduser"=>$iduser
			));
 
			$data = $results[0];
 
			$this->setData($data);
 
		}
		
		public function update() {
		
			$sql = new Sql();
			
			$results = $sql -> select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, 
				:inadmin)", array(
					":iduser" => $this -> getiduser(),
					":desperson" => utf8_decode($this -> getdesperson()),
					":deslogin" => $this -> getdeslogin(),
					":despassword" => $this -> getdespassword(),
					":desemail" => $this -> getdesemail(),
					":nrphone" => $this -> getnrphone(),
					":inadmin" => $this -> getinadmin()
					
			));
			
			
			
			$this -> setData($results[0]);
		
		}
		
		public function delete() {
		
			$sql = new Sql();
			
			$sql -> query("CALL sp_users_delete(:iduser)", array(
				"iduser" => $this -> getiduser()
			));
		
		}
		
		public static function getForgot($email, $inadmin = true) {
			$sql = new Sql();
			
			$results = $sql->select("SELECT *FROM tb_persons a INNER JOIN tb_users b USING(idperson) WHERE a.desemail = :email;", array(
				":email"=>$email
			));
			 
			if (count($results) === 0){
				
				 throw new \Exception("Não foi possível recuperar a senha.");
				 
			} else {
				$data = $results[0];
				$results2 = $sql->select("CALL sp_userspasswordsrecoveries_create(:iduser, :desip)", array(
					":iduser"=>$data['iduser'],
					":desip"=>$_SERVER['REMOTE_ADDR']
				));
				if (count($results2) === 0) {
					
					 throw new \Exception("Não foi possível recuperar a senha.");
					 
				} else {
					 
					$dataRecovery = $results2[0];
					
					$iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
					
					$code = openssl_encrypt($dataRecovery['idrecovery'], 'aes-256-cbc', User::SECRET, 0, $iv);
					
					$result = base64_encode($iv.$code);
					
					if ($inadmin === true) {
						
						$link = "http://www.hcodecommerce.com.br/admin/forgot/reset?code=$result";
						
					} else {
						
						$link = "http://www.hcodecommerce.com.br/forgot/reset?code=$result";
						
					}
					 
					$mailer = new Mailer($data['desemail'], $data['desperson'], "Redefinir senha da Hcode Store", "forgot", array(
						"name" => $data['desperson'],
						"link" => $link
					)); 
					 
					$mailer -> send();
					
					return $link;
				}
			}
		}
		
		public static function validForgotDecrypt($result) {
			
			$result = base64_decode($result);
			
			$code = mb_substr($result, openssl_cipher_iv_length('aes-256-cbc'), null, '8bit');
			
			$iv = mb_substr($result, 0, openssl_cipher_iv_length('aes-256-cbc'), '8bit');;
			
			$idrecovery = openssl_decrypt($code, 'aes-256-cbc', User::SECRET, 0, $iv);
			
			$sql = new Sql();
			
			$results = $sql->select("SELECT * FROM tb_userspasswordsrecoveries a INNER JOIN tb_users b USING(iduser) INNER JOIN 
				tb_persons c USING(idperson) WHERE a.idrecovery = :idrecovery AND a.dtrecovery IS NULL AND 
				DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();", array(":idrecovery" => $idrecovery)
			);
			
			if (count($results) === 0) {
				
				throw new \Exception("Não foi possível recuperar a senha.");
			
			} else {
			
				return $results[0];
			
			}
		}
		
		public static function setFogotUsed($idrecovery) {
		
			$sql = new Sql();
			
			$sql -> query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
				":idrecovery" => $idrecovery
			));
		
		}
		
		public function setPassword($password) {
		
			$sql = new Sql();
			
			$sql -> query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser", array(
				":password" => $password,
				":iduser" => $this -> getiduser()
			));
		
		}

	}

?>
