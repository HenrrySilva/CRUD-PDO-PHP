<?php 
/**
*
* Class CRUD MYSQL
* @author Henrique Silva
*
**/

//namespace Connect;

include __DIR__.'/ConexaoMysql.php';

class CrudMysql extends ConexaoMysql{

	private $table;
    private $columnPrimary;

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

	public function __construct(string $table, string $columnPrimary = ":id"){
        parent::__construct();
		$this->table = $table;
        $this->columnPrimary = $columnPrimary;
	}

	/**
	* Set type PDO PARAM 
	*
	* @access private
	* @param array $data
	* @return PDO CONSTANT PARAM_*
	*
	*/
	private function dataType($data){
        
		if(array_key_exists(gettype($data), self::ARRAY_PDO_CONSTANT))
			return self::ARRAY_PDO_CONSTANT[gettype($data)];
		else
			return PDO::PARAM_STR;
	}

	/**
	*
	* Metodo prepare MYSQL
	* @access private
	* @param string $sql
	* @param array $param
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
	* Method insert MYSQL
	* @access public
	* @param array $params
	* @return int
	**/
	public function save(array $params) :int{

		$values_str = implode(",", array_keys($params));

		$column = str_replace(":", "", $values_str);
		$sql = "INSERT INTO {$this->table} ({$column}) VALUES ({$values_str})";

		$stmt_exec = $this->prepareMysql($sql, $params);

		$rowCount = $stmt_exec->rowCount();

		return $rowCount;
	}

	/**
	*
	* select MYSQL
	* @access public
	* @param array $where [ NOT REQUIRE ]
	* @param string $opr [ NOT REQUIRE ]
	* @param string $columns [ NOT REQUIRE ]
	* @param string $operador [ NOT REQUIRE ]
	* @return array 
	*
	*/
	public function find(array $where = [], ?string $opr = "AND", string $columns = "*", string $operador = "=") :array{

		$sql = "SELECT {$columns} FROM {$this->table}";

		if($where != null){

			$condition = "";
			foreach ($where as $key => $value) {
				$condition .= str_replace(":", "", $key)."{$operador}".$key." {$opr} ";
			}

			$condition = substr($condition, 0, -4);

			$sql = "SELECT {$columns} FROM {$this->table} WHERE {$condition}";
		}
        
		$stmt_exec = $this->prepareMysql($sql, $where);

		return $stmt_exec->fetchAll(PDO::FETCH_ASSOC);

	}

    /**
	*
	* update MYSQL
	* @access public
	* @param array $params
	* @param array $where
	* @param string $id_table
	* @return boolean
	*
	*/
	public function update(array $params, ?array $where, string $id_table = "") : bool{

		$set = "";
		foreach ($params as $key => $value) {

			$set .= str_replace(":", "", $key)." = '{$value}', ";
		}

		$sql = "UPDATE {$this->table} SET {$set}";

		if($where != null){
			$condition = "";
			foreach ($where as $key => $value) {
				$condition .= str_replace(":", "", $key)." = {$key}";
			}

			$set = substr($set, 0, -2);
			$sql = "UPDATE {$this->table} SET {$set} WHERE {$condition}";
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
    
    public function findById(int $id){
        return $this->find([$this->columnPrimary => $id]);
    }

}

?>
