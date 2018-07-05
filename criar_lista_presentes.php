<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$conexao =  mysqli_connect('localhost','root','i-eccb7429', 'sain3_app');
//$conexao =  mysqli_connect('localhost', 'root','i-eccb7429', 'sain3_app');


if ($_GET) {

	// Fazer a senha de no minimo 6 digitos na area restrita da esperovoce.

	$senha 		 = $_GET['s'];
	$email 		 = $_GET['email'];
	$nome  		 = urldecode($_GET['nome']);	
	$n 			 = explode(' ', $nome);

	if (array_key_exists('0', $n)) {
		$nome 		 = $n[0];
		$sobrenome   = $n[1];
	} else {
		$sobrenome   = ' ';
	}

	$data_aniver = $_GET['dtaniversario'];
	$hora 		 = $_GET['hora'];
	$endereco 	 = $_GET['endereco'];	

	$sql 	= "SELECT * FROM mgt_customer_entity WHERE email like '".$email."'";
	$result = mysqli_query($conexao,$sql);

	if (mysqli_num_rows($result) <= 0) {	

		$sql = "INSERT INTO `mgt_customer_entity` (entity_type_id, attribute_set_id, website_id, email, group_id, increment_id, store_id, created_at, updated_at, is_active, disable_auto_group_change) VALUES (1,0,1,'".$email."',1,NULL,1,'".date('Y-m-d h:i:s')."','".date('Y-m-d h:i:s')."',1,0)";

		$result 	= mysqli_query($conexao,$sql);

	}
	
	$sql2 		= "SELECT * FROM mgt_customer_entity WHERE email like '".$email."' LIMIT 1";	
	$result 	= mysqli_query($conexao,$sql2);
	$row 		= mysqli_fetch_array($result, MYSQLI_ASSOC);
	$idCostumer = $row['entity_id'];

	$sql = "INSERT INTO mgt_customer_entity_varchar (entity_type_id, attribute_id, entity_id, value) VALUES (1,5,".$idCostumer.",'".$nome."')";
	$res = mysqli_query($conexao, $sql);		

	

	$sql = "INSERT INTO mgt_customer_entity_varchar (entity_type_id, attribute_id, entity_id, value) VALUES (1,6,".$idCostumer.",' ')";
	$res = mysqli_query($conexao, $sql);		

	

	$sql = "INSERT INTO `mgt_customer_entity_varchar` (entity_type_id, attribute_id, entity_id, value) VALUES (1,7,".$idCostumer.",'".$sobrenome."')";
	$res = mysqli_query($conexao, $sql);		

	

	$sql = "INSERT INTO `mgt_customer_entity_varchar` (entity_type_id, attribute_id, entity_id, value) VALUES (1,12,".$idCostumer.",'".$senha."')";
	$res = mysqli_query($conexao, $sql);

	

	$sql = "INSERT INTO `mgt_customer_entity_varchar` (entity_type_id, attribute_id, entity_id, value) VALUES (1,3,".$idCostumer.",'Default Store View')";
	$res = mysqli_query($conexao, $sql);	 		

	

	$sql = "INSERT INTO `mgt_am_gift_event` (event_title, password, event_date, event_time, event_location, additional_information, customer_id, shipping_address_id, searchable, created_at) VALUES ('Festa do ". $nome ."', '','".$data_aniver."', '".$hora."', '".$endereco."', ' ', ".$idCostumer.", NULL, 1, '".date('Y-m-d')."')";
	$res = mysqli_query($conexao, $sql);

	

	$sql2 	 = "SELECT event_id AS id FROM mgt_am_gift_event ORDER BY id DESC LIMIT 1";
	$result  = mysqli_query($conexao,$sql2);
	$row 	 = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$idEvent = $row['id'];
	$dado 	 = array('url'=> 'http://loja.esperovoce.com.br/amgiftreg/gift/view/id/'.$idEvent.'/');

	die(json_encode($dado));

}

die;

?>