<?php

	/* OS "namespaces" */
	use \Hcode\Page;
	use \Hcode\Model\Product;
	
	$app->get('/', function() { //chmando as rotas 
		
		$products = Product::listAll();
	
		$page = new Page(); // carregar o header

		$page->setTpl("index", [
			'products' => Product::checkList($products)
		]); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});

?>
