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
			$centro=$_POST["centro"];
			$tipo=$_POST["tipo"];
			$oculto=$_POST["oculto"];
			$codes=BD::getCodes($centro,$tipo,$oculto);								
			return json_encode($codes);
		}

	}

?>