<?php

	include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/php/bd.php");

	class Logic{

		public static function update(){
			$date="01/12/2018";
			$dateMySQL=self::dateToMySQL($date);
			$codigos=array();
			$stocks=BD::getStocks($date);
			$sales=BD::getSales($dateMySQL);			
			$productions=BD::getProductions($date);

			foreach ($stocks as $s){
				$key=$s["CODIGO"];
				if(!isset($codigos[$key])){
					$codigos[$key]=array("codigo"=>$s["CODIGO"],"fecha"=>$dateMySQL,"stock"=>"","venta"=>"","produccion"=>"");
				}
				$codigos[$key]["stock"]=$s["CANTIDAD"];
			}
			
			foreach ($sales as $s){
				$key=$s["CODIGO"];
				if(!isset($codigos[$key])){
					$codigos[$key]=array("codigo"=>$s["CODIGO"],"fecha"=>$dateMySQL,"stock"=>"","venta"=>"","produccion"=>"");
				}
				$codigos[$key]["venta"]=$s["CANTIDAD"];
			}
						
			foreach ($productions as $p){
				$key=$p["CODIGO"];
				if(!isset($codigos[$key])){
					$codigos[$p["CODIGO"]]=array("codigo"=>$s["CODIGO"],"fecha"=>$dateMySQL,"stock"=>"","venta"=>"","produccion"=>"");
				}
				$codigos[$key]["produccion"]=$p["CANTIDAD"];
			}		

			foreach ($codigos as $key=>$value) {
				BD::updateData($value);
			}			
			
		}

		public static function updateCode(){
			$codes=BD::getCodesOracle();
			foreach($codes as $c){				
				BD::updateCode($c);
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

		public static function dateToMySQL($date){
			return date_format(date_create_from_format("d/m/Y",$date),"Y-m-d");
		}

		/*
		public static function getData(){
			$codes=$_POST["codes"];
			$date=$_POST["date"];
			$data=BD::getData($codes,$date);
			$res=array();
			foreach ($data as $d) {
				$key=$d["codigo"];
				if(!isset($res[$key])){
					$res[$key]=array();
				}
				$fecha=$d["fecha"];
				if(!isset($res[$key][$fecha])){
					$res[$key][$fecha]=array();
				}
				$res[$key][$fecha]=array("stock"=>$d["stock"],"ventas"=>$d["ventas"],"produccion"=>$d["produccion"]);				
			}
			return json_encode($res);
		}
		*/

		public static function getData(){
			$codes=$_POST["codes"];
			$date=$_POST["date"];
			$res=array();
			foreach ($codes as $code){
				$bloque=array("codigo"=>$code,"fechas"=>array());
				$fechas=BD::getData($code,$date);
				foreach ($fechas as $fecha) {
					$bloque["fechas"][]=$fecha;
				}
				$res[]=$bloque;
			}
			return json_encode($res);			
		}

	}

?>