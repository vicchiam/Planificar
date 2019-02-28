<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include_once "php/bd.php";
	include_once "php/logica.php";

	echo "Init";

	//$res=BD::getCodesOracle();
	//$res=BD::getCodes("","",0);

	//echo var_dump($res);

	//Logic::update();

	$d=Logica::createDates("2019-02-01");
	echo var_dump($d);

?>