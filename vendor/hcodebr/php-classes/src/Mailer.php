<?php

	namespace Hcode;
	
	Use Rain\Tpl;
	
	class Mailer {
	
		const USERNAME = "gustavo.hsmachado@gmail.com";
		const PASSWORD = "" ;
		const NAME_FROM = "Hcode Store";
		
		private $mail;
		
		public function __construct($toAddress, $toName, $subject, $tplName, $data = array()) {
			
			$config = array(
				"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/", // procura a pasta "views".
				"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/", // procura a pasta "views".
				"debug"         => false // false para nao gerar e exibir os comentarios gerados.
			);
		
			Tpl::configure($config);
		
			$tpl = new Tpl;
			
			foreach ($data as $key => $value) {
				
				$tpl -> assign($key, $value);
				
			}
			
			$html = $tpl -> draw($tplName, true);
		
			$this -> mail = new \PHPMailer;
			
			$this -> mail -> isSMTP();
			
			$this -> mail -> SMTPDebug = 0;
			
			$this -> mail -> Host = 'smtp.gmail.com';
			
			$this -> mail -> Port = 587;
			
			$this -> mail -> SMTPSecure = 'tls';
			
			$this -> mail -> SMTPAuth = true;
			
			$this -> mail -> Username = Mailer::USERNAME; 
			
			$this -> mail -> Password = Mailer::PASSWORD;
			
			$this -> mail -> setFrom(Mailer::USERNAME, Mailer::NAME_FROM);
			
			$this -> mail -> addAddress($toAddress, $toName); 
			
			$this -> mail -> Subject = $subject;
			
			$this -> mail -> msgHTML($html); 
			
			$this -> mail -> AltBody = 'This is a plain-text message body'; //caso o servidor não tenha html use aqui para a mensagem
			
		}
		
		public function send() {
		
			return $this -> mail -> send();
		
		}
	
	}

?>
