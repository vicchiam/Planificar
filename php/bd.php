<?php

    include_once($_SERVER["DOCUMENT_ROOT"]."/Planificar/libs/php/conn.php");

class BD{    

    public static function getCodesOracle(){
        $sql="
            select
                pro_codigo_producto as CODIGO,
                pro_descripcion as DESCRIPCION,
                pro_clave_estad_principal as FAMILIA,
                pro_clave_estad_alternativa as ALT
            from
                pr_producto
            where
                pro_empresa=8 and
                pro_estado='A' and
                UPPER(pro_codigo_producto)=LOWER(pro_codigo_producto) and    
                pro_articulo_venta_sn='S' and
                pro_codigo_producto>='10000' and
                pro_codigo_producto<'99999'
            order by
                pro_codigo_producto
        ";
        return CONN::getOracle($sql);        
    }

    public static function getCodes($centro,$tipo,$oculto){
        $op="";
        if(!empty($centro)){
            $op.=" and centro=:centro ";
        }
        if(!empty($tipo)){
            $op.=" and tipo=:tipo ";
        }

        $sql="
            select
                codigo,
                descripcion,
                familia,
                centro,
                tipo,
                orden
            from
                productos
            where
                oculto=:oculto 
                ".$op."
            order by
                orden
        ";        
        $db=CONN::getMySQL();
        $sth=$db->prepare($sql);
        $sth->bindParam(":oculto",$oculto);
        if(!empty($centro))
            $sth->bindParam(":centro",$centro);
        if(!empty($tipo))
            $sth->bindParam(":tipo",$tipo);
        $sth->execute();
        return $sth->fetchAll();   
    }

    public static function updateCodes($code){
        $sql="
            insert into productos (codigo, descripcion, familia, centro, tipo, orden) 
            select 
                :codigo as codigo,
                :descripcion as descripcion,
                :familia as familia,
                substring(:fam_alt,1,1) as centro,
                substring(:fam_alt,2,1) as tipo,
                (max(orden)+1) as orden
            from 
                productos
            on duplicate key update
            descripcion=:descripcion,
            familia=:familia,
            centro=substring(:fam_alt,1,1),
            tipo=substring(:fam_alt,2,1)
        ";
        try{
            $db=CONN::getMySQL();
            $sth=$db->prepare($sql);
            $sth->bindParam(":codigo",$code["CODIGO"]);
            $sth->bindParam(":descripcion",$code["DESCRIPCION"]);
            $sth->bindParam(":familia",$code["FAMILIA"]);
            $sth->bindParam(":fam_alt",$code["ALT"]);
            return $sth->execute();
        }
        catch(PDOException $e) {            
            return $e->getMessage();
        }
    }


}