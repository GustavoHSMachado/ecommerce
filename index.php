<?php 

	require_once("vendor/autoload.php"); // o composer e trazer suas dependencias.

	/* OS "namespaces"*/
	use \Slim\Slim; 
	use \Hcode\Page;
	use \Hcode\PageAdmin;

	$app = new Slim(); // començando as rotas.

	$app->config('debug', true);

	$app->get('/', function() { //chmando as rotas 
	
		$page = new Page(); // carregar o header

		$page->setTpl("index"); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});
	
	$app->get('/admin', function() { //chmando as rotas para chamar a pagina do administrador
	
		$page = new PageAdmin(); // carregar o header

		$page->setTpl("index"); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});

	$app->run(); //roda tudo que foi carregado acima.

?>
