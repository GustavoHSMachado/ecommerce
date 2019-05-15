<?php 

	namespace Hcode;

	class Model {

		private $values = [];
		
		/* metodo para criar dinamicamete as informações do BD criando atributo e já colocando o valor/informação no atributo.
		 * Ex: no BD tem idusuario com valor 1 dentro dele. esse metodo vai criar o atibuto idusario recebendo 1 com valor/informação. */

		public function setData($data) {

			foreach ($data as $key => $value){

				$this->{"set".$key}($value); //criando atributo e passando valor/informação dinamicamente. 
				//TUDO QUE FOR CRIADO DINAMICAMENTE TEM QUE SER DENTO/ENTRE CHAVES "{}".

			}

		}
		
		/* __call() Método Mágico que é disparado ao invocar métodos inacessíveis em um contexto de objeto. Verifica toda vez que um 
		 * método é chamado. recebe alguns paramentros o nome do metodo e o os argumentos que foram passados.*/

		public function __call($name, $args) { 

			$method = substr($name, 0, 3); //nome do método.
			$fieldName = substr($name, 3, strlen($name)); //campo se foi chamado (se é ex: idusuario, dessenha, deslogin).

			if (in_array($fieldName, $this->fields)) {
			
				/*Getter e Setter */
				switch ($method) {

					case "get":
						return (isset ($this->values[$fieldName])) ? $this->values[$fieldName] : NULL;
					break;

					case "set":
						$this->values[$fieldName] = $args[0];
					break;

				}

			}

		}
		
		//Passa o retorno do atributo values desta classe, ele não é acessado direto porque não é um boa pratica. \\
		public function getValues() { 

			return $this -> values;

		}

	}

?>
