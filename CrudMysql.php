<?php 
/**
*
* Class CRUD MYSQL
* @author Henrique Silva
*
**/

include 'ConexaoMysql.php';

class CrudMysql extends ConexaoMysql{

	/**
	*
	* @var Array de constant PARAM_* PDO
	*/
	private const ARRAY_PDO_CONSTANT = [
		'string' => PDO::PARAM_STR,
		'integer' => PDO::PARAM_INT,
		'boolean' => PDO::PARAM_BOOL,
		'NULL' => PDO::PARAM_NULL
	];

	public function __construct(){
		parent::__construct();
	}

	/**
	*
	* @param value
	* @return PDO CONSTANT PARAM_*
	*/
	private function dataType($data){

		if(array_key_exists(gettype($data), self::ARRAY_PDO_CONSTANT))
			return self::ARRAY_PDO_CONSTANT[gettype($data)];
		else
			return PDO::PARAM_STR;
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
			$stmt->bindValue($key, $value, $this->dataType($value));
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
	public function find(string $table_name, array $where = [], ?string $opr = "AND", string $columns = "*", string $operador = "=") :array{

		$sql = "SELECT {$columns} FROM {$table_name}";

		if($where != null){

			$condition = "";
			foreach ($where as $key => $value) {
				$condition .= str_replace(":", "", $key)."{$operador}".$key." {$opr} ";
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
	public function update(string $table_name, array $params, ?array $where, string $id_table = "") : bool{

		$set = "";
		foreach ($params as $key => $value) {

			$set .= str_replace(":", "", $key)." = '{$value}', ";
		}

		$sql = "UPDATE {$table_name} SET {$set}";

		if($where != null){
			$condition = "";
			foreach ($where as $key => $value) {
				$condition .= str_replace(":", "", $key)." = {$key}";
			}

			$set = substr($set, 0, -2);
			$sql = "UPDATE {$table_name} SET {$set} WHERE {$condition}";
		}

		$stmt_exec = $this->prepareMysql($sql, $where);

		return $stmt_exec->rowCount();

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

		return $stmt_exec->rowCount();


	}

}

?>
