<?php

include 'CrudMysql.php';

try{
	$crudMysql = new CrudMysql();
	var_dump($crudMysql->updateMysql("UPDATE md5 SET md5 = 1 WHERE numero = 100002"));

	var_dump($crudMysql->getColumnAffected());
}catch(Excepetion $e){
	echo $e->getMessage;
}
?>