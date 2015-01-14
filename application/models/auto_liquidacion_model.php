<?php

class auto_liquidacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function actualizaAvaluo($cod_coactivo,$estado){
        $this->db->trans_start();
        $this->db->set("COD_TIPORESPUESTA"  , $estado);
        $this->db->set("AUTO_LIQUIDACION"   , 1);
        $this->db->where('COD_GESTION_COBRO', $cod_coactivo);
        $query = $this->db->update('MC_AVALUO');
        //print_r($this->db->last_query($query));die();
        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }
    
    function getAuto ($auto){
        $this->db->select('NUM_AUTOGENERADO,COD_FISCALIZACION,FECHA_CREACION_AUTO,CREADO_POR,COMENTARIOS,REVISADO,REVISADO_POR,
        APROBADO,APROBADO_POR,ASIGNADO_A,NOMBRE_DOC_GENERADO,COD_GESTIONCOBRO,COD_ESTADOAUTO,NOMBRE_DOC_FIRMADO,COD_TIPO_AUTO');
        $this->db->from("AUTOSJURIDICOS");
        $this->db->where('NUM_AUTOGENERADO', $auto);
        $query =  $this->db->get();
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }
    
    function getDocumentos($num_auto){
        $this->db->select('COD_AVISONOTIFICACION,NOMBRE_DOC_CARGADO,COD_ESTADO,COD_TIPONOTIFICACION,ESTADO_NOTIFICACION,
        DOC_FIRMADO,NUM_AUTOGENERADO');
        $this->db->where('ESTADO_NOTIFICACION','ACTIVO');
        $this->db->where('NUM_AUTOGENERADO',$num_auto);
        $query =  $this->db->get('NOTIFICACION_LIQUIDACION');       
        //print_r($this->db->last_query($query));die();
        if($query->num_rows() > 0)
        {
            return $query;   
        }
                
    }
    
    function getEmpresa($nit){
        $this->db->select("NOMBRE_EMPRESA, CODEMPRESA, REPRESENTANTE_LEGAL,TELEFONO_FIJO,ACTIVO,DIRECCION");
        $this->db->where("CODEMPRESA", $nit);
        $dato = $this->db->get("EMPRESA");
        //var_dump($dato);
        return $dato->result_array;
    }
    
    function getRespuesta($idGestion) {
        $array = array();
        $this->db->select('NOMBRE_GESTION,COD_TIPOGESTION,COD_RESPUESTA');
        $this->db->from("RESPUESTAGESTION");
        $this->db->where('COD_RESPUESTA', $idGestion);
        $query =  $this->db->get();
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }
    
    function getSelect($table,$fields,$where='',$order='') {
        $sql = "SELECT ".$fields."  FROM ".$table." ";
        if ($where != '')
            $sql .= "WHERE ".$where." ";
        if ($order != '')
            $sql .= "ORDER BY ".$order." ";
        $query = $this->db->query($sql);
        //echo '<br>'.$sql;
        return $query->result();
    }        

    function guardarAuto($table,$data){
        $this->db->trans_start();
        $this->db->set("COD_TIPO_AUTO"          , $data['COD_TIPO_AUTO']);
        $this->db->set("COD_PROCESO_COACTIVO"   , $data['COD_PROCESO_COACTIVO']);
        $this->db->set("COD_ESTADOAUTO"         , $data['COD_ESTADOAUTO']);
        $this->db->set("COD_TIPO_PROCESO"       , $data['COD_TIPO_PROCESO']);
        $this->db->set("CREADO_POR"             , $data['CREADO_POR']);
        $this->db->set("ASIGNADO_A"             , $data['ASIGNADO_A']);
        $this->db->set('FECHA_GESTION'          ,'SYSDATE', FALSE);
        $this->db->set('FECHA_CREACION_AUTO'    ,'SYSDATE', FALSE);
	$this->db->set("COD_GESTIONCOBRO"       , $data['COD_GESTIONCOBRO']);
        $this->db->set("COMENTARIOS"            , $data['COMENTARIOS']);
        $this->db->set("NOMBRE_DOC_GENERADO"    , $data['NOMBRE_DOC_GENERADO']);
        $query = $this->db->insert($table);
        //print_r($this->db->last_query($query));die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    } 
    
    function guardarNotificacion ($data,$dataAuto){
        $this->db->trans_start();
        $this->db->set('COD_TIPONOTIFICACION', $data['COD_TIPONOTIFICACION']);        
        $this->db->set('OBSERVACIONES', $data['OBSERVACIONES']);
        $this->db->set('PLANTILLA', $data['PLANTILLA']);
        $this->db->set('FECHA_NOTIFICACION','SYSDATE', FALSE);
        $this->db->set('COD_ESTADO', $data['COD_ESTADO']);        
        $this->db->set('ESTADO_NOTIFICACION', $data['ESTADO_NOTIFICACION']);        
        $this->db->set('NUM_AUTOGENERADO', $data['NUM_AUTOGENERADO']);        
        $this->db->set('NOMBRE_DOC_CARGADO', $data['NOMBRE_DOC_CARGADO']);
        $this->db->insert('NOTIFICACION_LIQUIDACION');
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();    
                $this->db->trans_start();
                $this->db->set("COD_GESTIONCOBRO",$dataAuto['COD_GESTIONCOBRO']);
                $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
                //$this->db->set("ASIGNADO_A",$dataAuto['ASIGNADO_A']);
                $this->db->where('NUM_AUTOGENERADO', $data['NUM_AUTOGENERADO']);
                $this->db->update('AUTOSJURIDICOS');
                if ($this->db->affected_rows() == '1') {
                    $this->db->trans_complete(); 
                    return TRUE;
                }                            
        }        
        return FALSE;
    }
    
    function guardarObjecion ($data,$where){
            $this->db->trans_start();
            $this->db->set("COD_GESTIONCOBRO",$data['COD_GESTIONCOBRO']);
            $this->db->set('FECHA_GESTION',"to_date('".$data['FECHA_GESTION']."','dd/mm/yyyy')",false);
            $this->db->where('NUM_AUTOGENERADO', $where);
            $this->db->update('AUTOSJURIDICOS');
            //print_r($this->db->last_query());die();
            if ($this->db->affected_rows() == '1') {
                $this->db->trans_complete(); 
                return TRUE;
            }                            
      
        return FALSE;
    }
        
    function liquidacionesView ($cod_coactivo) {        
        $this->db->select('AJ.NUM_AUTOGENERADO,AJ.COD_TIPO_AUTO,AJ.FECHA_CREACION_AUTO,AJ.COD_PROCESO_COACTIVO, 
        TO_NUMBER(GC.COD_TIPO_RESPUESTA) AS COD_RESPUESTA,
        VW.RESPUESTA AS RESPUESTA, VW.EJECUTADO AS NOMBRE_EMPRESA,
        PC.COD_PROCESO_COACTIVO AS COD_FISCALIZACION,PC.ABOGADO AS ABOGADO, PC.COD_PROCESOPJ AS PROCESOPJ, PC.IDENTIFICACION AS CODEMPRESA, 
        US.NOMBRES, US.APELLIDOS, 
        VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, VW.COD_REGIONAL AS COD_REGIONAL, TA.DESCRIPCION_AUTO,
        RES.NOMBRE_GESTION');
        $this->db->from('AUTOSJURIDICOS AJ');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=AJ.COD_PROCESO_COACTIVO');
        $this->db->join('TRAZAPROCJUDICIAL GC', 'GC.COD_TRAZAPROCJUDICIAL=AJ.COD_GESTIONCOBRO');
        $this->db->join('RESPUESTAGESTION RES', 'RES.COD_RESPUESTA=GC.COD_TIPO_RESPUESTA');
        //$this->db->join('VW_PROCESOS_BANDEJA VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=PC.ABOGADO');
        $this->db->join('TIPOAUTO TA', 'TA.COD_TIPO_AUTO = AJ.COD_TIPO_AUTO');
        $where = 'VW.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA';
        $this->db->where('(AJ.COD_TIPO_AUTO = \'3\' OR AJ.COD_TIPO_AUTO = \'24\' OR AJ.COD_TIPO_AUTO = \'25\' )');
        $this->db->where('AJ.COD_TIPO_PROCESO',14);
//        $this->db->where('COD_RESPUESTA <>',1477);
//        $this->db->where('COD_RESPUESTA <>',1471);
        //$this->db->where('AJ.COD_PROCESO_COACTIVO',$cod_coactivo);
        $this->db->where($where);
        $this->db->order_by('AJ.FECHA_CREACION_AUTO DESC');
        $query = $this->db->get("");
        //print_r($this->db->last_query($query));die();
        
        return $query->result_array;
//        
//        $query6 = $this->db->get('');
//        //print_r($this->db->last_query($query6));die();
//        $subQuery6 = $this->db->last_query();
//        $query6 = $query6->result_array();
        
        
        
        
//        $this->db->select('NUM_AUTOGENERADO,AU.COD_TIPO_AUTO,AU.COD_FISCALIZACION,COD_ESTADOAUTO,COD_TIPO_PROCESO,
//        CREADO_POR,VW.IDENTIFICACION as CODEMPRESA,VW.EJECUTADO AS NOMBRE_EMPRESA,
//        AU.ASIGNADO_A,FECHA_CREACION_AUTO,
//        REVISADO,REVISADO_POR,APROBADO,APROBADO_POR,NOMBRE_DOC_GENERADO,NOMBRE_DOC_FIRMADO,FECHA_DOC_FIRMADO,TG.TIPOGESTION,RG.COD_RESPUESTA,RG.NOMBRE_GESTION,                
//        TA.DESCRIPCION_AUTO');                        
//        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = AU.COD_GESTIONCOBRO');
//        $this->db->join('TIPOGESTION TG', 'TG.COD_GESTION = GC.COD_TIPOGESTION');
//        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA');
//        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO = AU.COD_PROCESO_COACTIVO');
//        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO');
//        $this->db->join('USUARIOS US', 'US.IDUSUARIO = PC.ABOGADO');
//        $this->db->join('TIPOAUTO TA', 'TA.COD_TIPO_AUTO = AU.COD_TIPO_AUTO');
//        //$this->db->where('AU.ASIGNADO_A',$user);
//        $this->db->where('COD_TIPO_PROCESO','14');
//        $this->db->where('(AU.COD_TIPO_AUTO = \'3\' OR AU.COD_TIPO_AUTO=\'24\' )');
//        if ($perfil == ABOGADO){
//            $this->db->where('(COD_RESPUESTA = \'1136\' OR COD_RESPUESTA = \'1137\' OR COD_RESPUESTA = \'1138\' OR COD_RESPUESTA = \'1153\'
//            OR COD_RESPUESTA = \'1152\' OR COD_RESPUESTA = \'451\' OR COD_RESPUESTA = \'452\' OR COD_RESPUESTA = \'453\' OR COD_RESPUESTA = \'1154\' 
//            OR COD_RESPUESTA = \'1155\' OR COD_RESPUESTA = \'454\' OR COD_RESPUESTA = \'800\' OR COD_RESPUESTA = \'1160\' OR COD_RESPUESTA = \'840\' )');
//        }else if ($perfil == COORDINADOR){
//            $this->db->where('(COD_RESPUESTA = \'1135\')');
//        }else if ($perfil == SECRETARIO){
//            $this->db->where('(COD_RESPUESTA = \'1132\' OR COD_RESPUESTA = \'1133\' OR COD_RESPUESTA = \'1134\' OR COD_RESPUESTA = \'450\' 
//            OR COD_RESPUESTA = \'453\'            )');
//        }        
        $this->db->order_by('NUM_AUTOGENERADO', 'ASC');
        $query = $this->db->get("AUTOSJURIDICOS AU");
        //print_r($this->db->last_query($query));die();
        if($query->num_rows() > 0)
        {
            return $query;   
        }
    }
        
    
    function lisUsuarios($fields,$regional,$cargo,$order='') {
        $this->db->select($fields);
        $this->db->where('COD_REGIONAL',$regional);
        $this->db->where('IDCARGO',$cargo);
        $this->db->order_by($order);
        $query = $this->db->get("USUARIOS");
        //print_r($this->db->last_query($query));die();
        return $query->result();  
    }             
    
      function lisUsuariosecre($fields,$regional,$cargo1,$cargo2,$order='') {
        $this->db->select($fields);
        $this->db->where('COD_REGIONAL',$regional);
        $this->db->where('(IDCARGO = '.$cargo1.' OR IDCARGO = '.$cargo2.')');
        $this->db->order_by($order);
        $query = $this->db->get("USUARIOS");
        //print_r($this->db->last_query($query));die();
        return $query->result();  
    }  
    
    function modificarAuto($table,$data,$where){
        $this->db->trans_start();
        $this->db->where('NUM_AUTOGENERADO', $where);
        $this->db->set("COD_ESTADOAUTO"         , $data['COD_ESTADOAUTO']);
        $this->db->set("FECHA_GESTION"          , 'SYSDATE', FALSE);
        //$this->db->set("ASIGNADO_A"             , $data['ASIGNADO_A']);
        $this->db->set("REVISADO"               , $data['REVISADO']);
        $this->db->set("APROBADO"               , $data['APROBADO']);
        $this->db->set("COMENTARIOS"            , $data['COMENTARIOS']);
	$this->db->set("COD_GESTIONCOBRO"       , $data['COD_GESTIONCOBRO']);
        $this->db->set("COMENTARIOS"            , $data['COMENTARIOS']);
        $this->db->set("NOMBRE_DOC_GENERADO"    , $data['NOMBRE_DOC_GENERADO']);
        if ($data['NOMBRE_DOC_FIRMADO'] != ''){
        $this->db->set("NOMBRE_DOC_FIRMADO"     , $data['NOMBRE_DOC_FIRMADO']);
        $this->db->set("FECHA_DOC_FIRMADO"      , 'SYSDATE', FALSE);
        
        }
        $this->db->update($table);
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }
    
     function getNotifica($id,$tipo,$estado) {        
        $array = array();
        $this->db->select('COD_AVISONOTIFICACION,ESTADO_NOTIFICACION,NOMBRE_DOC_CARGADO,DOC_FIRMADO,FECHA_NOTIFICACION,COD_ESTADO,
        COD_TIPONOTIFICACION,PLANTILLA,NUM_AUTOGENERADO,NUM_RADICADO_ONBASE,OBSERVACIONES');
        $this->db->from("NOTIFICACION_LIQUIDACION");
        $this->db->where('NUM_AUTOGENERADO',$id);
        $this->db->where('COD_TIPONOTIFICACION',$tipo);
        $this->db->where('ESTADO_NOTIFICACION',$estado);        
        $query =  $this->db->get();
        //print_r($this->db->last_query($query));die();
        if ($query->num_rows() > 0) {
            $array = $query->result();            
            return $array[0];
        }
    }
    
    function modificarNotific($data,$dataAuto,$auto,$notificacion){
       $this->db->trans_start();  
       $this->db->where('COD_AVISONOTIFICACION', $notificacion);
         switch ($data['COD_ESTADO']){
            default:
                $this->db->set("FECHA_MODIFICA_NOTIFICACION"    , 'SYSDATE', FALSE);
                $this->db->set("COD_ESTADO"                     , $data['COD_ESTADO']);
                $this->db->set("OBSERVACIONES"                  , $data['OBSERVACIONES']);
                $this->db->set("PLANTILLA"                      , $data['PLANTILLA']);
                break;            
            case 5:
                $this->db->set("FECHA_MODIFICA_NOTIFICACION"    , 'SYSDATE', FALSE);
                $this->db->set("COD_ESTADO"                     , $data['COD_ESTADO']);
                $this->db->set("OBSERVACIONES"                  , $data['OBSERVACIONES']);
                $this->db->set("PLANTILLA"                      , $data['PLANTILLA']);
                $this->db->set("NUM_RADICADO_ONBASE"            , $data['NUM_RADICADO_ONBASE']);
                break;
            case 6:
                $this->db->set("FECHA_MODIFICA_NOTIFICACION"    , 'SYSDATE', FALSE);
                $this->db->set("COD_ESTADO"                     , $data['COD_ESTADO']);
                $this->db->set("OBSERVACIONES"                  , $data['OBSERVACIONES']);
                $this->db->set("PLANTILLA"                      , $data['PLANTILLA']);
                $this->db->set("NUM_RADICADO_ONBASE"            , $data['NUM_RADICADO_ONBASE']);
                if ($data['FECHA_ONBASE'] != 0){
                $this->db->set('FECHA_ONBASE',"to_date('".$data['FECHA_ONBASE']."','dd/mm/yyyy')",false);
                }
                $this->db->set("DOC_COLILLA"                    , $data['DOC_COLILLA']);
                $this->db->set("DOC_FIRMADO"                    , $data['DOC_FIRMADO']);
                $this->db->set("NOMBRE_DOC_CARGADO"             , $data['NOMBRE_DOC_CARGADO']);
                $this->db->set("NOMBRE_COL_CARGADO"             , $data['NOMBRE_COL_CARGADO']);                
                $this->db->set("ESTADO_NOTIFICACION"            , $data['ESTADO_NOTIFICACION']);
                break;
            case 7:
                $this->db->set("FECHA_MODIFICA_NOTIFICACION"    , 'SYSDATE', FALSE);
                $this->db->set("COD_ESTADO"                     , $data['COD_ESTADO']);
                $this->db->set("OBSERVACIONES"                  , $data['OBSERVACIONES']);
                $this->db->set("PLANTILLA"                      , $data['PLANTILLA']);
                $this->db->set("NUM_RADICADO_ONBASE"            , $data['NUM_RADICADO_ONBASE']);
                if ($data['FECHA_ONBASE'] != 0){
                $this->db->set('FECHA_ONBASE',"to_date('".$data['FECHA_ONBASE']."','dd/mm/yyyy')",false);       
                }
                $this->db->set("DOC_COLILLA"                    , $data['DOC_COLILLA']);
                $this->db->set("DOC_FIRMADO"                    , $data['DOC_FIRMADO']);
                $this->db->set("NOMBRE_DOC_CARGADO"             , $data['NOMBRE_DOC_CARGADO']);
                $this->db->set("NOMBRE_COL_CARGADO"             , $data['NOMBRE_COL_CARGADO']);
                $this->db->set("COD_MOTIVODEVOLUCION"           , $data['COD_MOTIVODEVOLUCION']);        
                $this->db->set("ESTADO_NOTIFICACION"            , $data['ESTADO_NOTIFICACION']);
                break;
        }                        
        $this->db->update('NOTIFICACION_LIQUIDACION');  
        //print_r($this->db->last_query());die();
        $this->db->trans_complete(); 
         if ($this->db->affected_rows() == '1') {               
                $this->db->trans_start();
                $this->db->where('NUM_AUTOGENERADO', $auto);
                $this->db->set("COD_GESTIONCOBRO",$dataAuto['COD_GESTIONCOBRO']);
                $this->db->set("FECHA_GESTION", 'SYSDATE', FALSE);
                //$this->db->set("ASIGNADO_A",$dataAuto['ASIGNADO_A']);
                $this->db->update('AUTOSJURIDICOS');
                //print_r($this->db->last_query($query));die();
                if ($this->db->affected_rows() == '1') {
                    $this->db->trans_complete(); 
                    return TRUE;
                }                            
        }
        return FALSE;
    }
}