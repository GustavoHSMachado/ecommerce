<?php
	
	namespace Hcode\Model;

	use \Hcode\Model;
	use \Hcode\DB\Sql;
	use \Hcode\Mailer;

	class Category extends Model {
		
		protected $fields = [
			"idcategory", "descategory",
		];
		
		public static function listAll() {
			
			$sql = new Sql();
			
			return $sql -> select("SELECT * FROM tb_categories ORDER BY descategory");
			
		}
		
		public function save() {
			
			$sql = new Sql();
			
			$results = $sql -> select("CALL sp_categories_save(:idcategory, :descategory)", array(
				":idcategory" => $this -> getidcategory(),
				":descategory" => $this -> getdescategory()
			));
			
		}
		
		public function get($idcategory) {
			
			$sql = new Sql();
			
			$results = $sql -> select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", [
				':idcategory' => $idcategory
			]);
			
			$this -> setData($results[0]);
			
		}
		
		public function delete() {
			
			$sql = new Sql();
			
			$sql -> query("DELETE FROM tb_categories WHERE idcategory = :idcategory", [
				':idcategory' => $this -> getidcategory()
			]);
			
		}
		
	}

?>