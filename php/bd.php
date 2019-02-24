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

    public static function getFamilies(){
        $sql="
            select 
                cuf_codigo_familia as CODIGO, 
                cuf_denominacion as NOMBRE 
            from 
                pr_cu_familia 
            where 
                cuf_empresa=8 and 
                cuf_clave=1 
            order by 
                cuf_denominacion
        ";
        return CONN::getOracle($sql);   
    }

    public static getStock($date){
        
    }

    public static function getCodes(){
        $sql="
            select
                codigo,
                descripcion,
                familia,
                centro,
                tipo,
                orden,
                oculto
            from
                productos
            order by
                orden
        ";        
        $db=CONN::getMySQL();
        $sth=$db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll();   
    }

    public static function changeVisibility($code){
        $sql="
            update productos set oculto=if(oculto=0,1,0) where codigo=:code
        ";
        try{
            $db=CONN::getMySQL();
            $sth=$db->prepare($sql);
            $sth->bindParam(":code",$code);
            return $sth->execute();
        }
        catch(PDOException $e) {            
            return $e->getMessage();
        }
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