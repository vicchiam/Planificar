<?php

	include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/php/bd.php");

	class Logica{

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
				$bloque=array("codigo"=>$code,"descripcion"=>"","fechas"=>self::createDates($date));
				$data=BD::getData($code,$date);
				foreach ($data as $d) {
					if(empty($bloque["descripcion"])){
						$bloque["descripcion"]=$d["descripcion"];
					}
					$key=$d["fecha"];
					$bloque["fechas"][$key]=$d;

				}
				$res[]=$bloque;
			}
			return json_encode($res);			
		}

		public static function createDates($date){
			$dates=array();
			$dates[]=date('Y-m-d', strtotime($date." -1 month"));
			$dates[]=date('Y-m-d', strtotime($date));
			for($i=1;$i<=10;$i++){
				$dates[]=date('Y-m-d', strtotime($date." +".$i." month"));
			}
			return $dates;
		}

	}

?>