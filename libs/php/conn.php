<?php

	class CONN{

		public static function getMySQL($dbname=""){
	        $str=file_get_contents($_SERVER["DOCUMENT_ROOT"]."/Planificar/conf/conf.json");
	        $cnf=json_decode($str,true);                
	        $dbhost=$cnf['db_mysql']['host'];
	        $dbuser=$cnf['db_mysql']['dbuser'];
	        $dbpass=$cnf['db_mysql']['dbpass'];
	        if(empty($dbname))
	        	$dbname=$cnf['db_mysql']['dbname'];
	        return new PDO("mysql:host=".$dbhost.";dbname=".$dbname.";charset=utf8", $dbuser, $dbpass);        
	    }

	    public static function getOracle($sql){
	        $str=file_get_contents($_SERVER["DOCUMENT_ROOT"]."/Planificar/conf/conf.json");
	        $cnf=json_decode($str,true);  
	        $tns=$cnf["db_oracle"]["tns"];        
	        $dbuser=$cnf["db_oracle"]["dbuser"];
	        $dbpass=$cnf["db_oracle"]["dbpass"];
	        $conn = OCILogon($dbuser,$dbpass,$tns);
	        $stid = OCIParse($conn, $sql);
	        OCIExecute($stid, OCI_DEFAULT);
	        $error = OCIError($stid);
	        if($error){            
	            return $error;
	        }
	        oci_fetch_all($stid,$res, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
	        return $res;
	    }

	}

?>