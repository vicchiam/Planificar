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

    public static function getStocks($date){
        $sql="
            select 
                CODIGO,
                CANTIDAD*(
                    case UMA 
                        when 'UND' then CONVERSION 
                        when 'CAJ' then CONVERSION
                        when 'BAN' then CONVERSION
                        else 1 
                        end
                ) as CANTIDAD           
            from
                (
                    select 
                        mal_codigo_producto CODIGO,                 
                        sum(case mal_entrada_salida when 'S' then mal_cantidad * -1 else mal_cantidad end) as CANTIDAD
                    from 
                        pr_movimientos_almacen
                    where 
                        mal_fecha_movimiento <'".$date."'  and 
                        mal_empresa=8 and
                        (
                            mal_codigo_almacen=3 OR                         
                            (mal_codigo_almacen=5 and mal_codigo_ubicacion<>'49') OR
                            mal_codigo_almacen=14 OR
                            mal_codigo_almacen=825 OR
                            (mal_codigo_almacen=101 and mal_codigo_ubicacion='FINAL')                 
                        )                   
                        group by 
                            mal_codigo_producto
                ),
                (            
                    select 
                        pro_codigo_producto CODIGO_CONV,
                        pro_unidad_med_almacen UMA,
                        cup_cantidad CONVERSION                
                    from  
                        pr_producto,
                        PR_CONVERSION_UME_PRODUCTO c
                    where 
                        PRO_EMPRESA=8
                        AND CUP_EMPRESA=PRO_EMPRESA                    
                        AND CUP_PRODUCTO=PRO_CODIGO_PRODUCTO
                        AND cup_ume=PRO_UNIDAD_MED_ALMACEN
                        AND CUP_UME_DESTINO='KGM'
                        AND CUP_FECHA_CONVERSION=(
                            SELECT 
                                MAX(c2.cup_fecha_conversion)
                            FROM
                                PR_CONVERSION_UME_PRODUCTO c2
                            WHERE
                                c2.CUP_EMPRESA=c.CUP_EMPRESA AND
                                c2.CUP_PRODUCTO=c.CUP_PRODUCTO AND
                                c2.CUP_UME=c.CUP_UME AND
                                c2.CUP_UME_DESTINO=c.CUP_UME_DESTINO 
                        )
                )
            where
                CODIGO=CODIGO_CONV(+)
                and CANTIDAD >0
        ";
        return CONN::getOracle($sql);
    }

    public static function getSales($date){        
        $sql="
            select
                CODIGO,
                SUM(KILOS) as CANTIDAD
            from
                export_xls2
            where
                FECHA_FAC>=CONCAT(date_format(date_add('".$date."',INTERVAL -1 DAY),'%Y-%m'),'-01') and
                FECHA_FAC<'".$date."' and
                Dia>=01 and
                Dia<=31 and
                CODIGO>=10000 and
                CODIGO<99999
            group by
                CODIGO,
                Mes;
        ";
        $db=CONN::getMySQL("estadisticas");
        $sth=$db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getProductions($date){
        $sql="
            select
                CODIGO,
                CANTIDAD*(
                    case UMA 
                    when 'UND' then CONVERSION 
                    when 'CAJ' then CONVERSION
                    when 'BAN' then CONVERSION
                    else 1 
                    end
                ) as CANTIDAD
            FROM
            (
                select 
                    of_codigo_producto as CODIGO,
                    sum(NVL(of_cantidad_fabricada,0)) as CANTIDAD
                from 
                    fb_orden_fabricacion      
                where 
                    of_empresa=8 and
                    of_fecha_finalizacion>=CONCAT('01/',to_char((to_date('".$date."')-1),'mm/yyyy')) and  
                    of_fecha_finalizacion<'".$date."' and    
                    of_cantidad_fabricada>2
                group by
                    of_codigo_producto
            ),
            (            
                select 
                    pro_codigo_producto CODIGO_CONV,
                    pro_unidad_med_fabricacion UMA,
                    cup_cantidad CONVERSION                
                from  
                    pr_producto,
                    PR_CONVERSION_UME_PRODUCTO c
                where 
                    PRO_EMPRESA=8
                    AND CUP_EMPRESA=PRO_EMPRESA                    
                    AND CUP_PRODUCTO=PRO_CODIGO_PRODUCTO
                    AND cup_ume=PRO_UNIDAD_MED_FABRICACION
                    AND CUP_UME_DESTINO='KGM'
                    AND CUP_FECHA_CONVERSION=(
                        SELECT 
                            MAX(c2.cup_fecha_conversion)
                        FROM
                            PR_CONVERSION_UME_PRODUCTO c2
                        WHERE
                            c2.CUP_EMPRESA=c.CUP_EMPRESA AND
                            c2.CUP_PRODUCTO=c.CUP_PRODUCTO AND
                            c2.CUP_UME=c.CUP_UME AND
                            c2.CUP_UME_DESTINO=c.CUP_UME_DESTINO 
                    )
            )
            where
              CODIGO=CODIGO_CONV(+)
        ";
        return CONN::getOracle($sql);
    }

    /*
    public static function insertData($data){
        $sql="
            insert into datos (codigo,fecha,stock,ventas,produccion) values(:codigo,:fecha,:stock,:ventas,:produccion)
        ";
        try{
            $db=CONN::getMySQL();
            $sth=$db->prepare($sql);
            $sth->bindParam(":codigo",$data["codigo"]);
            $sth->bindParam(":fecha",$data["fecha"]);
            $sth->bindParam(":stock",$data["stock"]);
            $sth->bindParam(":ventas",$data["venta"]);
            $sth->bindParam(":produccion",$data["produccion"]);
            return $sth->execute();
        }
        catch(PDOException $e) {            
            return $e->getMessage();
        }
    }
    */

    public static function updateData($data){
        $sql="
            insert into datos (codigo,fecha,stock,ventas,produccion) values(:codigo,:fecha,:stock,:ventas,:produccion)
            on duplicate key update stock=:stock, ventas=:ventas, produccion=:produccion
        ";
        try{
            $db=CONN::getMySQL();
            $sth=$db->prepare($sql);
            $sth->bindParam(":codigo",$data["codigo"]);
            $sth->bindParam(":fecha",$data["fecha"]);
            $sth->bindParam(":stock",$data["stock"]);
            $sth->bindParam(":ventas",$data["venta"]);
            $sth->bindParam(":produccion",$data["produccion"]);
            return $sth->execute();
        }
        catch(PDOException $e) {            
            return $e->getMessage();
        }
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

    public static function updateCode($code){
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

    /*
    public static function getData($codes,$date){
        $or=array();
        for($i=1;$i<=count($codes);$i++){
            $or[]=" codigo=:code".$i." ";     
        }
        $op="(".join(" or ",$or).")";

        $sql="
            select
                id,
                fecha,
                codigo,                
                stock,
                ventas,
                produccion
            from
                datos
            where
                fecha<=:fecha and
                ".$op."
            order by                
                codigo,
                fecha
        ";        
        try{
            $db=CONN::getMySQL();
            $sth=$db->prepare($sql);
            $sth->bindParam(":fecha",$date);
            for($i=1;$i<=count($codes);$i++){
                $sth->bindParam(":code".$i,$codes[($i-1)]);   
            }
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);   
        }
        catch(PDOException $e) {            
            return $e->getMessage();
        }
    }
    */

     public static function getData($code,$date){
        $sql="
            select
                id,
                fecha,
                codigo,                
                stock,
                ventas,
                produccion
            from
                datos
            where
                codigo=:codigo and
                fecha<=:fecha                
            order by 
                fecha
        ";        
        try{
            $db=CONN::getMySQL();
            $sth=$db->prepare($sql);
            $sth->bindParam(":codigo",$code);
            $sth->bindParam(":fecha",$date);
            $sth->execute();
            return $sth->fetchAll(PDO::FETCH_ASSOC);   
        }
        catch(PDOException $e) {            
            return $e->getMessage();
        }
    }

}