<?php 
	
	session_start(); //Para usar o login tem que usar sessão, aqui estou iniciando a sessão.
		
	require_once("vendor/autoload.php"); // o composer e trazer suas dependencias.
	//require_once("functions.php");
	
	/* OS "namespaces" */
	use \Slim\Slim; 
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	use Hcode\Model\User;

	$app = new Slim(); // començando as rotas.

	$app->config('debug', true);

	$app->get('/', function() { //chmando as rotas 
	
		$page = new Page(); // carregar o header

		$page->setTpl("index"); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});
	
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
	
	$app -> get('/admin/users', function() {
	
		User::verifyLogin();
		
		$users = User::listAll();
		
		$page = new PageAdmin();
		
		$page -> setTpl("users", array (
			"users" => $users
		));
	
	});
	
	$app -> get('/admin/users/create', function() {
	
		User::verifyLogin();
		
		$page = new PageAdmin();
		
		$page -> setTpl("users-create");
	
	});
	
	$app -> get('/admin/users/:iduser/delete', function($iduser) {
	
		User::verifyLogin();
		
		$user = new User();
		
		$user -> get((int)$iduser);
		
		$user -> delete();
		
		header("Location: /admin/users");
		exit;
		
	});
	
	$app -> get('/admin/users/:iduser', function($iduser) {
	
		User::verifyLogin();
		
		$user = new User();
		
		$user -> get((int)$iduser);
		
		$page = new PageAdmin();
		
		$page -> setTpl("users-update", array(
			"user" => $user -> getValues()
		));
	
	});
	
	$app -> post('/admin/users/create', function() {
	
		User::verifyLogin();
		
		$user = new User();
		
		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
		
		$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, ["cost" => 12]);
		
		$user -> setData($_POST);
		
		$user -> save();
		
		header("Location: /admin/users");
		exit;
	
	});
	
	$app -> post('/admin/users/:iduser', function($iduser) {
	
		User::verifyLogin();
		
		$user = new User();
		
		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
		
		$user -> get((int)$iduser);
		
		$user -> setData($_POST);
		
		$user -> update();
		
		header("Location: /admin/users");
		exit;
	
	});

	$app->run(); //roda tudo que foi carregado acima.

?>
