<?php 

	namespace Hcode; //especificando onde está a classe.
	
	use Rain\Tpl; // usando a classes em outro namespace.
	
	class Page {
		private $tpl;
		private $options = [];
		private $defaults = [
			"header"=>true,
			"footer"=>true,
			"data"=>[] //criada para passar e receber dados para o template.
		];
		
		/* onde vai ficar o cabeçalho o topo da pagina. 
		 * array_merge - mescla dos arrays o ultimo array sempre sobrepoem os outros. */
		public function __construct($opts = array(), $tpl_dir = "/views/") {
		
			$this->options = array_merge($this->defaults, $opts);
			$config = array(
				"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir, // procura a pasta "views".
				"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/", // procura a pasta "views".
				"debug"         => false // false para nao gerar e exibir os comentarios gerados.
			);
		
			Tpl::configure( $config );
		
			$this->tpl = new Tpl;
		
			$this->setData($this->options["data"]);
		
			if ($this->options["header"] === true) $this->tpl->draw("header"); //criando o cabeçalho da pagina.
		
		}
		
		/* metodo criado para pegar os fazer cheve e valor nos arrays e fazer os "assing" da classe tpl um por um. */
		private function setData($data = array()) { 
			
			foreach ($data as $key => $value) {
				$this->tpl->assign($key, $value);
			}
		
		}
		
		/* Onde vai escrever as informações no template. */
		public function setTpl($name, $data = array(), $returnHTML = false) {
			
			$this->setData($data);
			
			return $this->tpl->draw($name, $returnHTML);
		
		}
		
		/* metodo que finaliza automaticamente os outros metodos e por isso e sempre o ultimo a ser executado
		 * nesse codigo tambem está fazendo(criando) o arquivo HTML de fim de pagina o rodapé. */
		public function __destruct() {
		
			if ($this->options["footer"] === true) $this->tpl->draw("footer");
		
		}
	}

?>
