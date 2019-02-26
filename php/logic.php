<?php

	include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/php/bd.php");

	class Logic{

		public static function update(){
			$date="01/02/2019";
			$codigos=array();
			$stocks=BD::getStocks($date);
			$sales=BD::getSales($date);
			$productions=BD::getProductions($date);

			foreach ($stocks as $s){
				if(!isset($codigos[$s["CODIGO"]])){
					$codigos[$s["CODIGO"]]=array("codigo"=>$codigos["CODIGO"],"fecha"=>$date,"stock"=>$s["CANTIDAD"],"venta"=>"","produccion"="");
				}
				$s["CODIGO"]["stock"]=$s["CANTIDAD"];
			}

			foreach ($sales as $s){
				if(!isset($codigos[$s["CODIGO"]])){
					$codigos[$s["CODIGO"]]=array("codigo"=>$codigos["CODIGO"],"fecha"=>$date,"stock"=>"","venta"=>$s["CANTIDAD"],"produccion"="");
				}
				$s["CODIGO"]["venta"]=$s["CANTIDAD"];
			}

			foreach ($productions as $p){
				if(!isset($codigos[$p["CODIGO"]])){
					$codigos[$p["CODIGO"]]=array("codigo"=>$codigos["CODIGO"],"fecha"=>$date,"stock"=>"","venta"=>"","produccion"=$p["CANTIDAD"]);
				}
				$s["CODIGO"]["produccion"]=$s["CANTIDAD"];
			}		

			foreach ($codigos as $key=>$value) {
				$res=BD::insertData($value["codigo"],$value["fecha"],$value["stock"],$value["venta"],$value["produccion"]);
				echo $res;		
			}			

		}

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