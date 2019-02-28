<?php

	include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/php/logica.php");

	if(!isset($_POST["operacion"]))
		die("Error");

	$operacion=$_POST["operacion"];

	if($operacion=="getCodes"){
		echo Logica::getCodes();
	}
	else if($operacion=="getFamily"){
		echo Ligica::getFamily();
	}
	else if($operacion=="changeVisibility"){
		echo Logica::changeVisibility();
	}
	else if($operacion=="getData"){
		echo Logica::getData();
	}

?>