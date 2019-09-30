<?php

include 'CrudMysql.php';

try{
	$crudMysql = new CrudMysql();
	var_dump($crudMysql->update("md5", ['pedro' => 'Henri']));

}catch(Excepetion $e){
	echo $e->getMessage;
}
?>