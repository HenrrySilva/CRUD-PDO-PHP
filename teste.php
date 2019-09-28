<?php

include 'CrudMysql.php';

try{
	$crudMysql = new CrudMysql();
	var_dump($crudMysql->find("md5", [], null, "md5"));

}catch(Excepetion $e){
	echo $e->getMessage;
}
?>