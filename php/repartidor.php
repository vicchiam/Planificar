<?php

	include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/php/logic.php");

	if(!isset($_POST["operacion"]))
		die("Error");

	$operacion=$_POST["operacion"];

	if($operacion=="getCodes"){
		echo Logic::getCodes();
	}

?>