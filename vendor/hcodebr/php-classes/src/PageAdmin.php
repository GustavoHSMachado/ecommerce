<?php 

	namespace Hcode; 

	class PageAdmin extends Page { //Herança da classe page
		
		/* Metodo construtor para montar o template */
		public function __construct($opts = array(), $tpl_dir = "/views/admin/") { 

			parent::__construct($opts, $tpl_dir); //Usando Herança para chamar metodo construtor da classe page.

		}

	}

?>
