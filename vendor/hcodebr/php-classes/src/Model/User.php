<?php 

	namespace Hcode\Model;

	use \Hcode\Model;
	use \Hcode\DB\Sql;

	class User extends Model {

		const SESSION = "User"; //Constante criado para facilitar codgio e ser chamada em outros lugares.

		protected $fields = [
			"iduser", "idperson", "deslogin", "despassword", "inadmin", "dtergister"
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

	}

?>
