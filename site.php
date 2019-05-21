<?php

	/* OS "namespaces" */
	use \Hcode\Page;
	
	$app->get('/', function() { //chmando as rotas 
	
		$page = new Page(); // carregar o header

		$page->setTpl("index"); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});

?>
