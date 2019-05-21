<?php

	/* OS "namespaces" */
	use \Hcode\PageAdmin;
	use Hcode\Model\User;
	
	$app->get('/admin', function() { //chmando as rotas para chamar a pagina do administrador
		
		User::verifyLogin(); // usando constante para chamar método criado na Classe User para verificar se o usuario logou mesmo. 
		
		$page = new PageAdmin(); // carregar o header

		$page->setTpl("index"); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});
	
	$app->get('/admin/login', function() {
    
		$page = new PageAdmin([ //Array Para Desabilitar header e o foooter padrão pois a pagina de login não precisa nesse caso.
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("login");

	});
	
	$app->post('/admin/login', function() {

		User::login($_POST["deslogin"], $_POST["despassword"]); //chamando o metodo para conferir se o login e senha existe, criado na User.php.

		header("Location: /admin"); //se ele existir ele manda para pagina admin.
		exit;

	});

	$app->get('/admin/logout', function() {

		User::logout(); //Usando constante para chamar e usar o metodo de logout criado na User.php.

		header("Location: /admin/login"); //Se der certo ele manda para a pagina de login do administrador.
		exit;

	});
	
	$app -> get('/admin/forgot', function() {
		
		$page = new PageAdmin ([
			"header" => false,
			"footer" => false
		]);
		
		$page -> setTpl("forgot"); 
	
	});
	
	$app -> post('/admin/forgot', function(){
		
		$user = User::getForgot($_POST["email"]);
		
		header("Location: /admin/forgot/sent");
		exit;
	
	});
	
	$app -> get('/admin/forgot/sent', function(){
	
		$page = new PageAdmin ([
			"header" => false,
			"footer" => false
		]);
		
		$page -> setTpl("forgot-sent");
	
	});
	
	$app -> get('/admin/forgot/reset', function(){
		
		$user = User::validForgotDecrypt($_GET["code"]);
		
		$page = new PageAdmin ([
			"header" => false,
			"footer" => false
		]);
		
		$page -> setTpl("forgot-reset", array(
			"name" => $user["desperson"],
			"code" => $_GET["code"]
		));
	
	});
	
	$app -> post('/admin/forgot/reset', function(){
	
		$forgot = User::validForgotDecrypt($_POST["code"]);
		
		User::setFogotUsed($forgot["idrecovery"]);
		
		$user = new User();
		
		$user -> get((int) $forgot["iduser"]);
		
		$password = password_hash($_POST["password"], PASSWORD_DEFAULT, ["cost" => 12]);
		
		$user -> setPassword($password);
		
		$page = new PageAdmin ([
			"header" => false,
			"footer" => false
		]);
		
		$page -> setTpl("forgot-reset-success");
	
	});

?>
