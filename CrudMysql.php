<?php 
/**
*
* Class CRUD MYSQL
* @author Henrique Silva
*
**/

include_once 'ConexaoMysql.php';

class CrudMysql extends ConexaoMysql{

	/**
	*
	* Armazena total de de tabela afetada
	*
	* @var int
	*/
	public $columnAffected;

	public function __construct(){
		parent::__construct();
	}

	/**
	*
	* metodo prepare MYSQL
	*
	* @param 1 -> string  @param 2 -> array
	*
	* @return stmt
	*
	*/
	private function prepareMysql($sql, $param){

		$stmt = $this->conn->prepare($sql);

		foreach ($param as $key => $value) {
			$stmt->bindValue($key, $value);
		}

		// exec
		$stmt->execute();

		return $stmt;
	}

	/**
	*
	* Metodo de insert MYSQL
	*
	* @param 1 -> string  @param 2 -> array
	*
	* @return boolean
	*/
	public function insertMysql($stmt, $param = []): bool{

		$stmt_exec = $this->prepareMysql($stmt, $param);

		$countInsert = $stmt_exec->rowCount();

		$this->columnAffected = $countInsert;

		if($countInsert > 0){
			return true;
		}else{
			return false;
		}
	}

	/**
	*
	*
	*
	* @return array multi
	*
	*/
	public function selectMysql($stmt, $param = []){

		$stmt_exec = $this->prepareMysql($stmt, $param);

		return $stmt_exec->fetchAll(PDO::FETCH_ASSOC);

	}

	public function updateMysql($stmt, $param = []) : bool{

		$stmt_exec = $this->prepareMysql($stmt, $param);

		$countUpdate = $stmt_exec->rowCount();

		$this->columnAffected = $countUpdate;

		if($countUpdate > 0){
			return true;
		}else{
			return false;
		}



	}

	public function deleteMysql($stmt, $param = []){
		$stmt_exec = $this->prepareMysql($stmt, $param);

		$countDelete = $stmt_exec->rowCount();

		$this->columnAffected = $countDelete;

		if($countDelete > 0){
			return true;
		}else{
			return false;
		}
	}


	/**
	*
	*  methods GET's e SET's
	* 
	*
	*/

	public function getColumnAffected(){
		return $this->columnAffected;
	}
}
?>