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
	* total de tabela afetada
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
	* function insert MYSQL
	*
	* @param string table_name - name table
	* @param array params - ex
	* [':coloumn' => $value]
	*
	* @return int - Tabela afetada
	**/
	public function save(string $table_name, array $params) :int{

		$keys = array_keys($params);

		$values_str = implode(",", $keys);
		$column = str_replace(":", "", $values_str);
		$sql = "INSERT INTO {$table_name} ({$column}) VALUES ({$values_str})";

		$stmt_exec = $this->prepareMysql($sql, $params);

		$rowCount = $stmt_exec->rowCount();

		return $rowCount;
	}

	/**
	*
	* select MYSQL
	*
	* @param string table_name - Nome da tabela
	* @param array where - condição do select
	* @param string opr = operador
	* @param string columns = colunas a ser consultadas
	* @return array multi
	*
	*/
	public function find(string $table_name, array $where = [], ?string $opr = "AND", string $columns = "*") :array{

		$sql = "SELECT {$columns} FROM {$table_name}";

		if($where != null){

			$condition = "";
			foreach ($where as $key => $value) {
				$condition .= str_replace(":", "", $key)."=".$key." {$opr} ";
			}

			$condition = substr($condition, 0, -4);

			$sql = "SELECT {$columns} FROM {$table_name} WHERE {$condition}";
		}

		$stmt_exec = $this->prepareMysql($sql, $where);

		return $stmt_exec->fetchAll(PDO::FETCH_ASSOC);

	}

    /**
	*
	* update MYSQL
	*
	* @return boolean
	*
	*/
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

    /**
	*
	* delete MYSQL
	*
	* @return boolean
	*
	*/
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
