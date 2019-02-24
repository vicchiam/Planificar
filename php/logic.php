<?php

	include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/php/bd.php");

	class Logic{

		public static function updateCodes(){
			$codes=BD::getCodesOracle();
			foreach($codes as $c){				
				BD::updateCodes($c);
			}
		}

		public static function getCodes(){
			$codes=BD::getCodes();								
			return json_encode($codes);
		}

		public static function getFamilies(){
			return BD::getFamilies();
		}

		public static function changeVisibility(){
			$code=$_POST["code"];
			$res=BD::changeVisibility($code);
			return (($res)?"ok":$res);
		}

	}

?>