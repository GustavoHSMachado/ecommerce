<?php

	/* OS "namespaces" */
	use \Hcode\Page;
	use \Hcode\Model\Product;
	use \Hcode\Model\Category;
	
	$app->get('/', function() { //chmando as rotas 
		
		$products = Product::listAll();
	
		$page = new Page(); // carregar o header

		$page->setTpl("index", [
			'products' => Product::checkList($products)
		]); // chama o template.
		
		// depois que esse metodo é usado o destruct executa automaticamente e finaliza o codigo.

	});
	
	$app -> get('/categories/:idcategory', function($idcategory){
		
		$category = new Category();
		
		$category -> get((int)$idcategory);
		
		$page = new Page();

		$page->setTpl("category", [
			'category' => $category -> getValues(),
			'products' => Product::checkList($category -> getProducts())
		]);
		
	});

?>
