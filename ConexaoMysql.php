<?php 
/**
* Include arquivo de configuração MYSQL 
*
* @var native
*/
include_once 'config/config-conexao.php';

/**
* Classe de conexão MYSQL
* @author Henrique Silva
*
*/

class ConexaoMysql{

	/**
	*
	* atributo da conexão
	*
	* @var pdo
	*/
	protected $conn;

	/**
	*
	* construtor instance PDO
	*
	*/
	public function __construct(){

		if($this->conn == null){
			try{
				$this->conn = new PDO("mysql:host=".LOCAL_HOST.";dbname=".BD_NOME.";port=".PORTA, USER_MYSQL, PASS_MYSQL);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		}

	}

	/**
	*
	* Fechar conexão PDO
	*
	*/
	protected function closeConnection(){
		$this->pdo = null;
	}
}
?>