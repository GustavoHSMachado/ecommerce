<?php 
	
	session_start(); //Para usar o login tem que usar sessão, aqui estou iniciando a sessão.
		
	require_once("vendor/autoload.php"); // o composer e trazer suas dependencias.
	
	/* OS "namespaces" */
	use \Slim\Slim; 

	$app = new Slim(); // començando as rotas.

	$app->config('debug', true);
	
	// Chamando as rotas \\
	require_once("site.php");
	require_once("functions.php");
	require_once("admin.php");
	require_once("admin-users.php");
	require_once("admin-categories.php");
	require_once("admin-products.php");

	$app->run(); //roda tudo que foi carregado acima.

?>
