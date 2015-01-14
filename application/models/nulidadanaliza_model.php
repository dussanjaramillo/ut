<?php
class Nulidadanaliza_model extends CI_Model{
    
    function listCausales($cod_nulidad = '', $especifico = false){        
        
        $array = array();
        
        $cod_nulidad = ($cod_nulidad != '') ? ',' . $cod_nulidad : '';
        
        $this->db->select('
                            TICAU.COD_CAUSAL_NULIDAD,
                            TICAU.NOMBRE_CAUSAL,
                            TICAU.FECHA_CREACION,
                            TICAU.COD_ESTADO,
                            TICAU.COD_NULIDAD,
                            TICAU.ESPECIFICO
                        ');
        
        $this->db->from('13TIPOCAUSALESNULIDAD TICAU');
        $this->db->where('TICAU.COD_NULIDAD in (0' . $cod_nulidad . ')');
        $this->db->where('TICAU.COD_ESTADO', 0);
        
        if(!$especifico){
            $this->db->where('TICAU.ESPECIFICO', 0);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        } 
        
        return $array;
        
        
    }
    
    
    function listCausalesNulidad($cod_nulidad){        
        
        $array = array();
        
        $this->db->select('
                            TIPCAU.COD_CAUSAL_NULIDAD,
                            TIPCAU.NOMBRE_CAUSAL,
                            TIPCAU.FECHA_CREACION
                        ');
        
        $this->db->from('13TIPOCAUSALESNULIDAD TIPCAU');
        
        $this->db->join(    '13CAUSALES_NULIDAD CAUNUL', 
                            'CAUNUL.TIPO_CAUSAL_NULIDAD = TIPCAU.COD_CAUSAL_NULIDAD', 
                            'inner');
        
        
        $this->db->where('CAUNUL.COD_NULIDAD', $cod_nulidad);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        } 
        
        return $array;
        
        
    }
    
    function addCausal($nombre_causal, $cod_nulidad, $especifico = 0, $fecha_creacion = 'sysdate'){

        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);
        
        $this->db->set("NOMBRE_CAUSAL"          , $nombre_causal);
        $this->db->set("COD_NULIDAD"            , $cod_nulidad);
        $this->db->set("ESPECIFICO"             , $especifico);
        
        if ($fecha_creacion == 'SYSDATE') {
            $this->db->set("FECHA_CREACION = " . $fecha_creacion, null, false);
        } else {
            $this->db->set("FECHA_CREACION", "TO_DATE('" . $fecha_creacion ."', 'DD-MM-YY')", false);
        }

        $this->db->insert("13AUTOSJURIDICOS");
        
        //Adquirir cod_interes_multamin_enc
        $query = $this->db->query("SELECT TipoCausalesN_cod_causal_n_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];
        
        
         if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return null;
         }else{
            $this->db->trans_commit();
            return $id;
         }
    }
    
    function listTiposNulidad(){
       
        $this->db->select('
                            TIPNUL.COD_TIPO_NULIDAD,
                            TIPNUL.NOMBRE_TIPO_NULIDAD
                        ');
        
        $this->db->from('13TIPONULIDAD TIPNUL');
        
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        } else {
            return null;
        }
        
    }
    
    function  saveTipoNulidad($cod_regional, $existe_nulidad, $cod_tipo_nulidad, $causal_nulidad_temp, $asignado_por, $cod_gestion, $cod_nulidad = null, $fecha_radicacion = '', $fecha_asignacion = '', $fecha_revision = ''){
        
        $this->db->set("13AUTOSJURIDICOS.EXISTE_NULIDAD"         , $existe_nulidad);
        $this->db->set("13AUTOSJURIDICOS.COD_TIPO_NULIDAD"       , $cod_tipo_nulidad);
        $this->db->set("13AUTOSJURIDICOS.CAUSAL_NULIDAD_TEMP"    , $causal_nulidad_temp);
        $this->db->set("13AUTOSJURIDICOS.ASIGANADA_A = (select IDUSUARIO from 13USUARIOS USU where ROWNUM = 1  and COD_REGIONAL = " . $cod_regional . " and IDGRUPO = 41)"            , NULL, FALSE);
        $this->db->set("13AUTOSJURIDICOS.ASIGNADA_POR"           , $asignado_por);
        $this->db->set("13AUTOSJURIDICOS.COD_GESTION"            , $cod_gestion);
        
        if ($fecha_radicacion == 'sysdate') {
            $this->db->set("13AUTOSJURIDICOS.FECHA_RADICACION = " . $fecha_radicacion, null, false);
        } else {
            $this->db->set("13AUTOSJURIDICOS.FECHA_RADICACION", "TO_DATE('" . $fecha_radicacion ."', 'DD-MM-YY')", false);
        }
        
        if($fecha_asignacion != ''){
           if ($fecha_asignacion == 'sysdate') {
                $this->db->set("13AUTOSJURIDICOS.FECHA_RADICACION = " . $fecha_asignacion, null, false);
            } else {
                $this->db->set("13AUTOSJURIDICOS.FECHA_RADICACION",  "TO_DATE('" .$fecha_asignacion."', 'DD-MM-YY')", false);
            } 
        }
        
        if($fecha_revision != ''){
            if ($fecha_revision == 'sysdate') {
                $this->db->set("13AUTOSJURIDICOS.FECHA_REVISION = " . $fecha_revision, null, false);
            } else {
                $this->db->set("13AUTOSJURIDICOS.FECHA_REVISION", "TO_DATE('" . $fecha_revision ."', 'DD-MM-YY')", false);
            }
        }

        if($cod_gestion == ''){
            $this->db->set("13AUTOSJURIDICOS.NUM_EXPEDIENTE = (select FIS.COD_FISCALIZACION from 13FISCALIZACION FIS WHERE COD_GESTIONACTUAL = " . $cod_gestion . ")", 
                            NULL, 
                            FALSE);
            
            
            $this->db->insert("13AUTOSJURIDICOS");
             //verificacion de la transacción
            
            
        }else{ 
            $this->db->where("13AUTOSJURIDICOS.COD_NULIDAD", $cod_nulidad);
            $this->db->update("13AUTOSJURIDICOS");
            return $cod_nulidad;
        }

        
    }
    
    function saveNulidad(   
                            $existe_nulidad,
                            $cod_regional,
                            $cod_gestion,
                            $cod_nulidad,
                            $fecha_radicacion,
                            $fecha_revision,
                            $asignada_a,
                            $asignado_por
                            ){
        
        $this->db->set("13NULIDAD.NUM_EXPEDIENTE", "(select COD_FISCALIZACION from 13FISCALIZACION WHERE COD_GESTIONACTUAL = " . $cod_gestion . ")", false);
        $this->db->set("13NULIDAD.EXISTE_NULIDAD"        , $existe_nulidad);
        $this->db->set("13NULIDAD.CAUSAL_NULIDAD_TEMP"   , '');
        $this->db->set("13NULIDAD.ASIGNADA_A ", $asignada_a);        
        $this->db->set("13NULIDAD.COD_FISCALIZACION", "(select COD_FISCALIZACION from 13FISCALIZACION WHERE COD_GESTIONACTUAL = " . $cod_gestion . ")", false);
        $this->db->set("13NULIDAD.COD_GESTION_COBRO"           , $cod_gestion);
        $this->db->set("13NULIDAD.ASIGNADO_POR", $asignado_por);
        
        if($fecha_radicacion != ''){
            if($fecha_radicacion != 'SYSDATE'){
                $this->db->set("13NULIDAD.FECHA_RADICACION", "TO_DATE('" . $fecha_radicacion . "', 'DD-MM-YY')", false);
            }else{
                $this->db->set("13NULIDAD.FECHA_RADICACION", "SYSDATE ", FALSE);
            }
            
        }
        
        if($fecha_revision != ''){
            if($fecha_revision != 'SYSDATE'){
                $this->db->set("13NULIDAD.FECHA_REVISION"      , "TO_DATE('" . $fecha_revision ."', 'DD-MM-YY')", false);
            }else{
                $this->db->set("13NULIDAD.FECHA_REVISION", "SYSDATE ", FALSE);
            }
            
        }
       
        if($cod_nulidad == ''){
            $this->db->trans_begin();
            $this->db->trans_strict(TRUE);
            $this->db->insert("13NULIDAD");
             //Adquirir cod_interes_multamin_enc
            $query = $this->db->query("SELECT Nulidad_cod_nulidad_SEQ.CURRVAL FROM dual");
            $row = $query->row_array();
            $id = $row['CURRVAL'];
        
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return $this->db->last_query();
            }else{
                $this->db->trans_commit();
                return $id;
            }
        }else{
            $this->db->where('13NULIDAD.COD_NULIDAD' , $cod_nulidad);
            $this->db->update("13NULIDAD");
            return $id;
        }
        

    }
    
    function saveTipoCausal($cod_nulidad, $nombre_causal){
        $this->db->set("13TIPOCAUSALESNULIDAD.NOMBRE_CAUSAL"                 , $nombre_causal);
        $this->db->set("13TIPOCAUSALESNULIDAD.FECHA_CREACION"                , 'SYSDATE', FALSE);
        $this->db->set("13TIPOCAUSALESNULIDAD.COD_ESTADO"                    , 1);
        $this->db->set("13TIPOCAUSALESNULIDAD.COD_NULIDAD"                   , $cod_nulidad);
        $this->db->set("13TIPOCAUSALESNULIDAD.ESPECIFICO"                    , 0);
        
        $this->db->trans_begin();
        $this->db->trans_strict(TRUE);
        
        $this->db->insert("13TIPOCAUSALESNULIDAD");
        
        $query = $this->db->query("SELECT TipoCausalesN_cod_causal_n_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return $this->db->last_query();
        }else{
            $this->db->trans_commit();
            return $id;
        }
        
        
    }
    
    function saveCausalNulidad($cod_nulidad,$tipo_causal_nulidad){
        $this->db->set("13CAUSALES_NULIDAD.COD_NULIDAD"               , $cod_nulidad);
        $this->db->set("13CAUSALES_NULIDAD.TIPO_CAUSAL_NULIDAD"                , $tipo_causal_nulidad);
        
        $this->db->insert("13CAUSALES_NULIDAD");
        
    }
            
    function retrieveAllNullidad(){
        $array = array();
        
        $this->db->select('
                            TICAU.COD_CAUSAL_NULIDAD,
                            TICAU.NOMBRE_CAUSAL,
                            TICAU.FECHA_CREACION,
                            TICAU.COD_ESTADO,
                            TICAU.COD_NULIDAD,
                            TICAU.ESPECIFICO
                        ');
        
        $this->db->from('13TIPOCAUSALESNULIDAD TICAU');
        $this->db->where('TICAU.COD_NULIDAD in (0' . $cod_nulidad . ')');
        $this->db->where('TICAU.COD_ESTADO', 1);
        
        if(!$especifico){
            $this->db->where('TICAU.ESPECIFICO', 0);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        } 
        
        return $array;
    }
    
    function retriveFiscalizacion($cod_fiscalizacion){
        $this->db->select('
                            FIS.COD_FISCALIZACION,
                            FIS.PERIODO_INICIAL,
                            FIS.PERIODO_FINAL,
                            FIS.COD_ABOGADO,
                            FIS.COD_CONCEPTO,
                            FIS.COD_TIPOGESTION,
                            FIS.COD_GESTIONACTUAL,
                            ASIG_FIS.FECHA_ASIGNACION,
                            ASIG_FIS.ASIGNADO_A,
                            ASIG_FIS.NIT_EMPRESA,
                            ASIG_FIS.ASIGNADO_A,
                            ASIG_FIS.COMENTARIOS_ASIGNACION,
                            EMP.CODEMPRESA,
                            EMP.NOMBRE_EMPRESA,
                            EMP.DIRECCION,
                            EMP.TELEFONO_FIJO,
                            EMP.TELEFONO_CELULAR,
                            EMP.REPRESENTANTE_LEGAL,
                            EMP.RAZON_SOCIAL,
                            CONS_FIS.COD_CPTO_FISCALIZACION,
                            CONS_FIS.NOMBRE_CONCEPTO
                ');
        
        $this->db->from('13FISCALIZACION FIS');
        
        $this->db->join(    '13ASIGNACIONFISCALIZACION ASIG_FIS', 
                            'FIS.COD_ASIGNACION_FISC = ASIG_FIS.COD_ASIGNACIONFISCALIZACION', 
                            'inner');
        
        $this->db->join(    '13EMPRESA EMP', 
                            'EMP.CODEMPRESA = ASIG_FIS.NIT_EMPRESA', 
                            'inner');
        
        $this->db->join(    '13CONCEPTOSFISCALIZACION CONS_FIS', 
                            'CONS_FIS.COD_CPTO_FISCALIZACION = FIS.COD_CONCEPTO', 
                            'inner');
        
        $this->db->where("FIS.COD_FISCALIZACION", $cod_fiscalizacion);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }else{
            return false;
        }
    }
    
    function retrieveEmpresas($fill,$iDisplayStart,$limit){
        
        $array = array();
        $this->db->select('
                            EMP.CODEMPRESA,
                            EMP.NOMBRE_EMPRESA,
                            EMP.DIRECCION,
                            EMP.TELEFONO_FIJO,
                            EMP.TELEFONO_CELULAR,
                            EMP.REPRESENTANTE_LEGAL,
                            EMP.COD_TIPODOCUMENTO,
                            TIPDOC.NOMBRETIPODOC,
                            EMP.RAZON_SOCIAL,
                            EMP.CIIU,
                            EMP.COD_REPRESENTANTELEGAL,
                            EMP.COD_REGIONAL,
                            REG.NOMBRE_REGIONAL NOMBRE_REGIONAL,
                            REG.DIRECTOR_REGIONAL,
                            REG.COORDINADOR_REGIONAL,
                            REG.TELEFONO_REGIONAL,
                            REG.CELULAR_REGIONAL,
                            EMP.CORREOELECTRONICO,
                            EMP.ACTIVIDADECONOMICA,
                            EMP.FAX,
                            EMP.AFILIADO_CAJACOMPENSACION,
                            EMP.EMPRESA_NUEVA,
                            EMP.NRO_ESCRITURAPUBLICA,
                            EMP.NOTARIA,
                            EMP.CUOTA_APRENDIZ,
                            EMP.RESOLUCION,
                            EMP.PLANTA_PERSONAL 
                ');
        
        $this->db->from('13EMPRESA EMP');
        
        $this->db->join(    '13TIPODOCUMENTO TIPDOC', 
                            'EMP.COD_TIPODOCUMENTO = TIPDOC.CODTIPODOCUMENTO', 
                            'inner');
        
        $this->db->join(    '13REGIONAL REG', 
                            'EMP.COD_REGIONAL = REG.COD_REGIONAL', 
                            'inner');
        
        if(trim($fill) != ''){
            $this->db->where(   '(      EMP.CODEMPRESA          like \'%' . $fill . '%\'
                                    or  EMP.NOMBRE_EMPRESA      like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;

    }
    
    function empresa($codempresa){
        
        $this->db->select('
                            EMP.CODEMPRESA,
                            EMP.NOMBRE_EMPRESA,
                            EMP.DIRECCION,
                            EMP.TELEFONO_FIJO,
                            EMP.TELEFONO_CELULAR,
                            EMP.REPRESENTANTE_LEGAL,
                            EMP.COD_TIPODOCUMENTO,
                            TIPDOC.NOMBRETIPODOC,
                            EMP.RAZON_SOCIAL,
                            EMP.CIIU,
                            EMP.COD_REPRESENTANTELEGAL,
                            EMP.COD_REGIONAL,
                            REG.NOMBRE_REGIONAL NOMBRE_REGIONAL,
                            REG.DIRECTOR_REGIONAL,
                            REG.COORDINADOR_REGIONAL,
                            REG.TELEFONO_REGIONAL,
                            REG.CELULAR_REGIONAL,
                            EMP.CORREOELECTRONICO,
                            EMP.ACTIVIDADECONOMICA,
                            EMP.FAX,
                            EMP.AFILIADO_CAJACOMPENSACION,
                            EMP.EMPRESA_NUEVA,
                            EMP.NRO_ESCRITURAPUBLICA,
                            EMP.NOTARIA,
                            EMP.CUOTA_APRENDIZ,
                            EMP.RESOLUCION,
                            EMP.PLANTA_PERSONAL 
                ');
        
        $this->db->from('13EMPRESA EMP');
        
        $this->db->join(    '13TIPODOCUMENTO TIPDOC', 
                            'EMP.COD_TIPODOCUMENTO = TIPDOC.CODTIPODOCUMENTO', 
                            'inner');
        
        $this->db->join(    '13REGIONAL REG', 
                            'EMP.COD_REGIONAL = REG.COD_REGIONAL', 
                            'inner');
        
        $this->db->where(   'EMP.CODEMPRESA', $codempresa);
        
        
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
        
        return null;

    }
    
    function coutEmpresas($fill){
        
        $array = array();
        $this->db->select('
                            count(EMP.CODEMPRESA) num
                            
                ');
        
        $this->db->from('13EMPRESA EMP');
        
        $this->db->join(    '13TIPODOCUMENTO TIPDOC', 
                            'EMP.COD_TIPODOCUMENTO = TIPDOC.CODTIPODOCUMENTO', 
                            'inner');
        
        $this->db->join(    '13REGIONAL REG', 
                            'EMP.COD_REGIONAL = REG.COD_REGIONAL', 
                            'inner');
        
        if(trim($fill) != ''){
            $this->db->where(   '(      EMP.CODEMPRESA          like \'%' . $fill . '%\'
                                    or  EMP.NOMBRE_EMPRESA      like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }else{
            return null;
        }
        
    

    }
    
    function retriveAllFiscaXEmp($fill,$iDisplayStart,$limit,$nit_empresa, $cod_grupo){
        $array = array();
        $this->db->select('
                            FIS.COD_FISCALIZACION,
                            FIS.PERIODO_INICIAL,
                            FIS.PERIODO_FINAL,
                            TIPGE.TIPOGESTION GESTION_NOMBRE,
                            FIS.COD_ABOGADO,
                            FIS.FECHA_ASIGNACION_ABOGADO,
                            FIS.COD_CONCEPTO,
                            FIS.COD_TIPOGESTION,
                            TIPGES.TIPOGESTION,
                            FIS.COD_GESTIONACTUAL,
                            FIS.COD_ASIGNACION_FISC,
                            ASIGFIS.FECHA_ASIGNACION,
                            ASIGFIS.ASIGNADO_POR,
                            ASIGFIS.NIT_EMPRESA,
                            ASIGFIS.ASIGNADO_A COD_ASIG_A,
                            USU_ASIG_A.NOMBRES NOM_ASIG_A,
                            USU_ASIG_A.APELLIDOS APE_ASIG_A,
                            ASIGPOR.NOMBRES NOMBRE_ASIDA,
                            ASIGPOR.APELLIDOS APELLIDOS_ASIDA,
                            TIPGES.TIPOGESTION TIP_GESTION_ACTUAL
                ');
        
        $this->db->from('13FISCALIZACION FIS');
        
        $this->db->join(    '13TIPOGESTION TIPGES', 
                            'FIS.COD_TIPOGESTION = TIPGES.COD_GESTION', 
                            'inner');
        
        $this->db->join(    '13ASIGNACIONFISCALIZACION ASIGFIS', 
                            'ASIGFIS.COD_ASIGNACIONFISCALIZACION = FIS.COD_ASIGNACION_FISC', 
                            'inner');
        
        $this->db->join(    '13USUARIOS ASIGPOR', 
                            'ASIGPOR.IDUSUARIO = ASIGFIS.ASIGNADO_POR', 
                            'inner');
                            
         $this->db->join(   '13TIPOGESTION TIPGE', 
                            'FIS.COD_TIPOGESTION = TIPGE.COD_GESTION', 
                            'inner');
         
         $this->db->join(   '13EMPRESA EMP', 
                            'EMP.CODEMPRESA = ASIGFIS.NIT_EMPRESA', 
                            'inner');
         
         $this->db->join(   '13USUARIOS USU_ASIG_A', 
                            'USU_ASIG_A.IDUSUARIO = ASIGFIS.ASIGNADO_A', 
                            'inner');
         
         $this->db->join(   '13GESTIONCOBRO GESCOB', 
                            'GESCOB.COD_GESTION_COBRO = FIS.COD_GESTIONACTUAL', 
                            'inner');
         
         
         
        if(trim($fill) != ''){
            $this->db->where(   '(      FIS.COD_FISCALIZACION          like \'%' . $fill . '%\')
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->where( 'ASIGFIS.NIT_EMPRESA', $nit_empresa); 
        
        $this->db->where('FIS.COD_FISCALIZACION NOT IN ( SELECT 
                                                            NUL.COD_FISCALIZACION 
                                                        FROM 13NULIDAD NUL
                                                            LEFT JOIN 13AUTOSJURIDICOS AUJUR
                                                                ON AUJUR.COD_NULIDAD = NUL.COD_NULIDAD 
                                                                AND AUJUR.COD_TIPO_PROCESO IN (2,3,4) AND (AUJUR.COD_ESTADOAUTO != 3)
                                                        WHERE NUL.COD_FISCALIZACION = FIS.COD_FISCALIZACION)', null, false);
        
        $this->db->order_by('FIS.COD_FISCALIZACION', 'DESC');
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countFiscaXEmp($nit_empresa,$fill,$cod_grupo){
        $this->db->select('
                            count(FIS.COD_FISCALIZACION) NUM
                ');
        
        $this->db->from('13FISCALIZACION FIS');
        
        $this->db->join(    '13TIPOGESTION TIPGES', 
                            'FIS.COD_TIPOGESTION = TIPGES.COD_GESTION', 
                            'inner');
        
        $this->db->join(    '13ASIGNACIONFISCALIZACION ASIGFIS', 
                            'ASIGFIS.COD_ASIGNACIONFISCALIZACION = FIS.COD_ASIGNACION_FISC', 
                            'inner');
        
        $this->db->join(    '13USUARIOS ASIGPOR', 
                            'ASIGPOR.IDUSUARIO = ASIGFIS.ASIGNADO_POR', 
                            'inner');
                            
         $this->db->join(   '13TIPOGESTION TIPGE', 
                            'FIS.COD_TIPOGESTION = TIPGE.COD_GESTION', 
                            'inner');
         
         $this->db->join(   '13EMPRESA EMP', 
                            'EMP.CODEMPRESA = ASIGFIS.NIT_EMPRESA', 
                            'inner');
         
         $this->db->join(   '13USUARIOS USU_ASIG_A', 
                            'USU_ASIG_A.IDUSUARIO = ASIGFIS.ASIGNADO_A', 
                            'inner');
         
         $this->db->join(   '13GESTIONCOBRO GESCOB', 
                            'GESCOB.COD_GESTION_COBRO = FIS.COD_GESTIONACTUAL', 
                            'inner');
         
        if(trim($fill) != ''){
            $this->db->where(   '(      EMP.CODEMPRESA          like \'%' . $fill . '%\'
                                    or  EMP.NOMBRE_EMPRESA      like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->where('FIS.COD_FISCALIZACION NOT IN ( SELECT 
                                                            NUL.COD_FISCALIZACION 
                                                        FROM 13NULIDAD NUL
                                                            LEFT JOIN 13AUTOSJURIDICOS AUJUR
                                                                ON AUJUR.COD_NULIDAD = NUL.COD_NULIDAD 
                                                                AND AUJUR.COD_TIPO_PROCESO IN (2,3,4) AND (AUJUR.COD_ESTADOAUTO != 3)
                                                        WHERE NUL.COD_FISCALIZACION = FIS.COD_FISCALIZACION)', null, false);
        
        $this->db->where( 'ASIGFIS.NIT_EMPRESA', $nit_empresa);   
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
 
    }
    
    function fiscalizacion($cod_fiscalizacion){

        $this->db->select('
                            FIS.COD_FISCALIZACION,
                            FIS.PERIODO_INICIAL,
                            FIS.PERIODO_FINAL,
                            FIS.COD_ABOGADO,
                            FIS.FECHA_ASIGNACION_ABOGADO,
                            FIS.COD_CONCEPTO,
                            FIS.COD_TIPOGESTION,
                            TIPGES.TIPOGESTION,
                            FIS.COD_GESTIONACTUAL,
                            FIS.COD_ASIGNACION_FISC,
                            ASIGFIS.FECHA_ASIGNACION,
                            ASIGFIS.ASIGNADO_POR,
                            ASIGFIS.NIT_EMPRESA,
                            FIS.COD_FISCALIZACION,
                            FIS.PERIODO_INICIAL,
                            FIS.PERIODO_FINAL,
                            FIS.COD_ABOGADO,
                            FIS.FECHA_ASIGNACION_ABOGADO,
                            FIS.COD_CONCEPTO,
                            FIS.COD_TIPOGESTION,
                            TIPGES.TIPOGESTION,
                            FIS.COD_GESTIONACTUAL,
                            FIS.COD_ASIGNACION_FISC,
                            ASIGFIS.FECHA_ASIGNACION,
                            ASIGFIS.ASIGNADO_POR,
                            ASIGFIS.NIT_EMPRESA,
                            ASIGFIS.ASIGNADO_A COD_ASIG_A,
                            USU_ASIG_A.NOMBRES NOM_ASIG_A,
                            USU_ASIG_A.APELLIDOS APE_ASIG_A,
                            ASIGPOR.NOMBRES NOMBRE_ASIDA,
                            ASIGPOR.APELLIDOS APELLIDOS_ASIDA,
                            FIS.COD_TIPOGESTION,
                            TIPGES.TIPOGESTION TIP_GESTION_ACTUAL,
                            FIS.COD_GESTIONACTUAL,
                            ASIGFIS.NIT_EMPRESA,
                            EMP.NOMBRE_EMPRESA
                ');
        
        $this->db->from('13FISCALIZACION FIS');
        
        $this->db->join(    '13TIPOGESTION TIPGES', 
                            'FIS.COD_TIPOGESTION = TIPGES.COD_GESTION', 
                            'inner');
        
        $this->db->join(    '13ASIGNACIONFISCALIZACION ASIGFIS', 
                            'ASIGFIS.COD_ASIGNACIONFISCALIZACION = FIS.COD_ASIGNACION_FISC', 
                            'inner');
        
        $this->db->join(    '13USUARIOS ASIGPOR', 
                            'ASIGPOR.IDUSUARIO = ASIGFIS.ASIGNADO_POR', 
                            'inner');
                            
         $this->db->join(   '13TIPOGESTION TIPGE', 
                            'FIS.COD_TIPOGESTION = TIPGE.COD_GESTION', 
                            'inner');
         
         $this->db->join(   '13EMPRESA EMP', 
                            'EMP.CODEMPRESA = ASIGFIS.NIT_EMPRESA', 
                            'inner');
         
         $this->db->join(   '13USUARIOS USU_ASIG_A', 
                            'USU_ASIG_A.IDUSUARIO = ASIGFIS.ASIGNADO_A', 
                            'inner');
         
         $this->db->join(   '13GESTIONCOBRO GESCOB', 
                            'GESCOB.COD_GESTION_COBRO = FIS.COD_GESTIONACTUAL', 
                            'inner');
         
        
        
        $this->db->where( 'FIS.COD_FISCALIZACION', $cod_fiscalizacion);   
        
        
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
        
        return null;
        
        
    }
    
   function retrievetNulidades($fill,$iDisplayStart,$limit,$cod_fiscalizacion){
        
        $array = array();

        $this->db->select('
                            13NULIDAD.COD_NULIDAD,
                            to_char(FECHA_RADICACION, \'yyyy-mm-dd\') FECHA_RADICACION,
                            13NULIDAD.COD_FISCALIZACION,
                            13AUTOSJURIDICOS.NUM_AUTOGENERADO,
                            13AUTOSJURIDICOS.COD_TIPO_AUTO,
                            13AUTOSJURIDICOS.COD_ESTADOAUTO,
                            13AUTOSJURIDICOS.COD_TIPO_PROCESO,
                            13AUTOSJURIDICOS.CREADO_POR,
                            13AUTOSJURIDICOS.ASIGNADO_A,
                            13AUTOSJURIDICOS.FECHA_CREACION_AUTO,
                            13AUTOSJURIDICOS.COMENTARIOS,
                            13AUTOSJURIDICOS.REVISADO,
                            13AUTOSJURIDICOS.REVISADO_POR,
                            13AUTOSJURIDICOS.APROBADO,
                            13AUTOSJURIDICOS.APROBADO_POR,
                            13AUTOSJURIDICOS.NOMBRE_DOC_GENERADO,
                            13AUTOSJURIDICOS.NOMBRE_DOC_FIRMADO,
                            13AUTOSJURIDICOS.FECHA_SOLICITUD_PRUEBAS,
                            13AUTOSJURIDICOS.TRASLADO_ALEGATO,
                            13AUTOSJURIDICOS.DIRECTOR_REGIONAL,
                            13FISCALIZACION.COD_ASIGNACION_FISC,
                            USUARIO_CREADOR.IDUSUARIO ID_CREADOR,
                            USUARIO_CREADOR.NOMBREUSUARIO NOMBRE_CREADOR,
                            USUARIO_ASIGNADO.IDUSUARIO ID_ASIGNADO,
                            USUARIO_ASIGNADO.NOMBREUSUARIO NOMBRE_ASIGNADO,
                            DECODE(
                                ESTADOAUTO.NOMBRE_ESTADO, NULL, \'Esperando Revision del Secretario\',
                                                                ESTADOAUTO.NOMBRE_ESTADO
                            ) NOMBRE_ESTADO,
                            DECODE(13NULIDAD.FECHA_ASIGNACION,       NULL,   CONCAT(\'nulidadanaliza/formGestion/\',
                                                                                    13NULIDAD.COD_NULIDAD),
                                                                                CONCAT(CASE 13NULIDAD.COD_TIPO_NULIDAD
                                                                                          WHEN 1 THEN \'nulidadnoprocedencia/form/\'
                                                                                          WHEN 2 THEN \'nulidadordenandorsubsanacion/form/\'
                                                                                          WHEN 3 THEN \'nulidadrealizanotificacion/form/\'
                                                                                          WHEN 4 THEN \'nulidadsubsana/form/\'
                                                                                        END, CONCAT(13NULIDAD.COD_NULIDAD,
                                                                                                    CONCAT(\'/\',
                                                                                                            13AUTOSJURIDICOS.NUM_AUTOGENERADO
                                                                                                    )
                                                                                                    ))
                                                                                        )  URL
                        ',false);
        
        $this->db->from(    '13NULIDAD');
        
        $this->db->join(    '13AUTOSJURIDICOS', 
                            '13NULIDAD.COD_NULIDAD = 13AUTOSJURIDICOS.COD_NULIDAD and 13AUTOSJURIDICOS.COD_TIPO_PROCESO IN (2,3,4,5) and 13AUTOSJURIDICOS.COD_ESTADOAUTO != 3', 
                            'left');
        
        $this->db->join(    '13FISCALIZACION', 
                            '13AUTOSJURIDICOS.COD_FISCALIZACION = 13FISCALIZACION.COD_FISCALIZACION', 
                            'left');
        
        $this->db->join(    '13USUARIOS USUARIO_CREADOR', 
                            'USUARIO_CREADOR.IDUSUARIO = 13AUTOSJURIDICOS.CREADO_POR', 
                            'left');
        
        $this->db->join(    '13USUARIOS USUARIO_ASIGNADO', 
                            'USUARIO_ASIGNADO.IDUSUARIO = 13AUTOSJURIDICOS.ASIGNADO_A', 
                            'left');
        
        $this->db->join(    '13ESTADOAUTO', 
                            '13ESTADOAUTO.COD_ESTADOAUTO = 13AUTOSJURIDICOS.COD_ESTADOAUTO ', 
                            'left');
        
        $this->db->where ('13NULIDAD.COD_FISCALIZACION' , $cod_fiscalizacion);
        $this->db->order_by('13NULIDAD.COD_NULIDAD', 'desc');
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD    like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countNulidades($fill, $cod_fiscalizacion){
        
        $array = array();
        $this->db->select('
                            count(13AUTOSJURIDICOS.NUM_AUTOGENERADO) NUM
                            
                        ');
        
        $this->db->from(    '13NULIDAD');
        
        $this->db->join(    '13AUTOSJURIDICOS', 
                            '13NULIDAD.COD_NULIDAD = 13AUTOSJURIDICOS.COD_NULIDAD and 13AUTOSJURIDICOS.COD_TIPO_PROCESO IN (2,3,4,5) and 13AUTOSJURIDICOS.COD_ESTADOAUTO != 3', 
                            'left');
        
        $this->db->join(    '13FISCALIZACION', 
                            '13AUTOSJURIDICOS.COD_FISCALIZACION = 13FISCALIZACION.COD_FISCALIZACION', 
                            'left');
        
        $this->db->join(    '13USUARIOS USUARIO_CREADOR', 
                            'USUARIO_CREADOR.IDUSUARIO = 13AUTOSJURIDICOS.CREADO_POR', 
                            'left');
        
        $this->db->join(    '13USUARIOS USUARIO_ASIGNADO', 
                            'USUARIO_ASIGNADO.IDUSUARIO = 13AUTOSJURIDICOS.ASIGNADO_A', 
                            'left');
        
        $this->db->join(    '13ESTADOAUTO', 
                            '13ESTADOAUTO.COD_ESTADOAUTO = 13AUTOSJURIDICOS.COD_ESTADOAUTO ', 
                            'left');
        
        $this->db->where ('13NULIDAD.COD_FISCALIZACION' , $cod_fiscalizacion);
        $this->db->order_by('13NULIDAD.COD_NULIDAD', 'desc');
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD    like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
           
            return $array[0]->NUM;
        }
        
        return null;
        
        
        
    }
    
    function nulidad($num_autogenerado) {
        
        $this->db->select('
                            13AUTOSJURIDICOS.NUM_AUTOGENERADO,
                            13AUTOSJURIDICOS.COD_TIPO_AUTO,
                            13AUTOSJURIDICOS.COD_FISCALIZACION,
                            13AUTOSJURIDICOS.COD_ESTADOAUTO,
                            13AUTOSJURIDICOS.COD_TIPO_PROCESO,
                            13AUTOSJURIDICOS.CREADO_POR,
                            13AUTOSJURIDICOS.ASIGNADO_A,
                            13AUTOSJURIDICOS.FECHA_CREACION_AUTO,
                            13AUTOSJURIDICOS.COMENTARIOS,
                            13AUTOSJURIDICOS.REVISADO,
                            13AUTOSJURIDICOS.REVISADO_POR,
                            13AUTOSJURIDICOS.APROBADO,
                            13AUTOSJURIDICOS.APROBADO_POR,
                            13AUTOSJURIDICOS.NOMBRE_DOC_GENERADO,
                            13AUTOSJURIDICOS.NOMBRE_DOC_FIRMADO,
                            13AUTOSJURIDICOS.FECHA_SOLICITUD_PRUEBAS,
                            13AUTOSJURIDICOS.TRASLADO_ALEGATO,
                            13AUTOSJURIDICOS.DIRECTOR_REGIONAL,
                            13FISCALIZACION.COD_ASIGNACION_FISC,
                            USUARIO_CREADOR.IDUSUARIO ID_CREADOR,
                            USUARIO_CREADOR.NOMBREUSUARIO NOMBRE_CREADOR,
                            USUARIO_ASIGNADO.IDUSUARIO ID_ASIGNADO,
                            USUARIO_ASIGNADO.NOMBREUSUARIO NOMBRE_ASIGNADO,
                            13ESTADOAUTO.NOMBRE_ESTADO
                        ');
        
        $this->db->from(    '13AUTOSJURIDICOS');
        $this->db->join(    '13FISCALIZACION', 
                            '13AUTOSJURIDICOS.COD_FISCALIZACION = 13FISCALIZACION.COD_FISCALIZACION', 
                            'inner');
        
        $this->db->join(    '13USUARIOS USUARIO_CREADOR', 
                            'USUARIO_CREADOR.IDUSUARIO = 13AUTOSJURIDICOS.CREADO_POR', 
                            'inner');
        
        $this->db->join(    '13USUARIOS USUARIO_ASIGNADO', 
                            'USUARIO_ASIGNADO.IDUSUARIO = 13AUTOSJURIDICOS.ASIGNADO_A', 
                            'inner');
        
        $this->db->join(    '13ESTADOAUTO', 
                            '13ESTADOAUTO.COD_ESTADOAUTO = 13AUTOSJURIDICOS.COD_ESTADOAUTO ', 
                            'inner');
        
        $this->db->where(   '13AUTOSJURIDICOS.COD_TIPO_PROCESO in (2,3,4,5)', 
                                null,
                                false);
        
        $this->db->where ('13AUTOSJURIDICOS.COD_FISCALIZACION' , $cod_fiscalizacion);
        $this->db->where ('13AUTOSJURIDICOS.COD_ESTADOAUTO != 3', null, false);
        
        
        if(trim($fill) != ''){
            $this->db->where(   '(     
                                    EMP.NUM_AUTOGENERADO    like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->where ('13AUTOSJURIDICOS.NUM_AUTOGENERADO', $num_autogenerado);
        
        $query = $this->db->get(); 
        
         if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
         }
    }
    
    function gestionCobro($cod_gestion_cobro){
         $this->db->select('
                            13GESTIONCOBRO.COD_FISCALIZACION_EMPRESA
                 ');
         $this->db->from('13GESTIONCOBRO');
         $this->db->where ('13GESTIONCOBRO.COD_GESTION_COBRO' , $cod_gestion_cobro);
         
         $query = $this->db->get(); 
         
         if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->COD_FISCALIZACION_EMPRESA;
         }else{
             return false;
         }
    }
    
    function retriveNulidad($cod_nulidad){
        
        $array = array();
        
        $this->db->select('
                            NUL.COD_NULIDAD,
                            NUL.NUM_EXPEDIENTE,
                            NUL.FECHA_RADICACION,
                            NUL.EXISTE_NULIDAD,
                            NUL.COD_TIPO_NULIDAD,
                            TIPNUL.NOMBRE_TIPO_NULIDAD,
                            NUL.CAUSAL_NULIDAD_TEMP,
                            NUL.ASIGNADA_A,
                            NUL.FECHA_ASIGNACION,
                            NUL.ASIGNADO_POR,
                            NUL.FECHA_REVISION,
                            NUL.COD_FISCALIZACION,
                            NUL.COD_GESTION_COBRO
                 ');
         $this->db->from('13NULIDAD NUL');
         
         $this->db->join(   '13TIPONULIDAD TIPNUL', 
                            'NUL.COD_TIPO_NULIDAD = TIPNUL.COD_TIPO_NULIDAD', 
                            'left');
         
         $this->db->where ('NUL.COD_NULIDAD' , $cod_nulidad);
         
         $query = $this->db->get(); 
         
         if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
         }   
    }

    function saveGestion(   $cod_nulidad, 
                            $existe_nulidad, 
                            $cod_tipo_nulidad, 
                            $tipo_acto_administrativo){

        $this->db->set("13NULIDAD.EXISTE_NULIDAD"            , $existe_nulidad);
        $this->db->set("13NULIDAD.COD_TIPO_NULIDAD"          , $cod_tipo_nulidad);
        $this->db->set("13NULIDAD.COD_TIPO_ADMIN"            , $tipo_acto_administrativo);
        $this->db->where('13NULIDAD.COD_NULIDAD'             , $cod_nulidad);
        $this->db->update("13NULIDAD");
    }
    
    
    function analizaNulidadList($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select('NUL.*');
        $this->db->from('13NULIDAD NUL');
        $this->db->where('NUL.COD_TIPO_NULIDAD IS NULL', null, false);
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->order_by('NUL.COD_NULIDAD', 'DESC');
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function analizaNulidadCount(){
        $array = array();
        
        $this->db->select('count(NUL.COD_NULIDAD) NUM');
        $this->db->from('13NULIDAD NUL');
        $this->db->where('NUL.COD_TIPO_NULIDAD IS NULL', null, false);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
    
    
    function listCreaActoAdministrativo($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select(' NUL.*,
                            TIPACTAD.DESCRIPCION_ACTO');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        
        
        $this->db->where('NUL.COD_TIPO_NULIDAD IS NOT NULL', null, false);
        $this->db->where('NUL.COD_NULIDAD NOT IN (    SELECT 
                                                            AUTJUR.COD_NULIDAD 
                                                        FROM 13AUTOSJURIDICOS AUTJUR
                                                        WHERE AUTJUR.COD_ESTADOAUTO != 3
                                                        AND AUTJUR.COD_NULIDAD IS NOT NULL
                                                        AND AUTJUR.COD_NULIDAD = NUL.COD_NULIDAD
                                                    )', null, false);
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    NUL.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    NUL.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->order_by('NUL.COD_NULIDAD','DESC');
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countCreaActoAdministrativo(){
        $array = array();
        
        $this->db->select('count(NUL.COD_NULIDAD) NUM');
        $this->db->from('13NULIDAD NUL');
        $this->db->where('NUL.COD_TIPO_NULIDAD IS NOT NULL', null, false);
        $this->db->where('NUL.COD_NULIDAD NOT IN (    SELECT 
                                                            AUTJUR.COD_NULIDAD 
                                                        FROM 13AUTOSJURIDICOS AUTJUR
                                                        WHERE AUTJUR.COD_ESTADOAUTO != 3
                                                        AND AUTJUR.COD_NULIDAD IS NOT NULL
                                                        AND AUTJUR.COD_NULIDAD = NUL.COD_NULIDAD
                                                    )', null, false);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
    
    function retrieveNulidad($cod_nulidad){
        $array = array();
        
        $this->db->select(' NUL.*,
                            TIPACTAD.DESCRIPCION_ACTO');
        
        $this->db->from('13NULIDAD NUL');
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->where('NUL.COD_NULIDAD', $cod_nulidad);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }else{
            return null;
        }
        
    }
    
    function listSecretarioActoAdministrativo($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select(' 
                            NUL.*,
                            TIPACTAD.DESCRIPCION_ACTO,
                            AUJUR.NUM_AUTOGENERADO
                            ');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 1);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countSecretarioActoAdministrativo($fill){
        $array = array();
        
        $this->db->select('count(NUL.COD_NULIDAD) NUM');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 1);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
    
    function retrievetAuto($num_autogenerado){
        
        $array = array();
        $this->db->select('
                            13AUTOSJURIDICOS.NUM_AUTOGENERADOs,
                            13AUTOSJURIDICOS.COD_TIPO_AUTO,
                            13AUTOSJURIDICOS.COD_FISCALIZACION,
                            13AUTOSJURIDICOS.COD_ESTADOAUTO,
                            13AUTOSJURIDICOS.COD_TIPO_PROCESO,
                            13AUTOSJURIDICOS.CREADO_POR,
                            13AUTOSJURIDICOS.ASIGNADO_A,
                            13AUTOSJURIDICOS.FECHA_CREACION_AUTO,
                            13AUTOSJURIDICOS.COMENTARIOS,
                            13AUTOSJURIDICOS.REVISADO,
                            13AUTOSJURIDICOS.REVISADO_POR,
                            13AUTOSJURIDICOS.APROBADO,
                            13AUTOSJURIDICOS.APROBADO_POR,
                            13AUTOSJURIDICOS.NOMBRE_DOC_GENERADO,
                            13AUTOSJURIDICOS.NOMBRE_DOC_FIRMADO,
                            13AUTOSJURIDICOS.FECHA_SOLICITUD_PRUEBAS,
                            13AUTOSJURIDICOS.TRASLADO_ALEGATO,
                            13AUTOSJURIDICOS.DIRECTOR_REGIONAL,
                            13FISCALIZACION.COD_ASIGNACION_FISC,
                            USUARIO_CREADOR.IDUSUARIO ID_CREADOR,
                            USUARIO_CREADOR.NOMBREUSUARIO NOMBRE_CREADOR,
                            USUARIO_ASIGNADO.IDUSUARIO ID_ASIGNADO,
                            USUARIO_ASIGNADO.NOMBREUSUARIO NOMBRE_ASIGNADO,
                            13ESTADOAUTO.NOMBRE_ESTADO,  
                            13AUTOSJURIDICOS.COD_GESTIONCOBRO
                        ');
        
        $this->db->from(    '13AUTOSJURIDICOS');
        $this->db->join(    '13FISCALIZACION', 
                            '13AUTOSJURIDICOS.COD_FISCALIZACION = 13FISCALIZACION.COD_FISCALIZACION', 
                            'inner');
        
        $this->db->join(    '13USUARIOS USUARIO_CREADOR', 
                            'USUARIO_CREADOR.IDUSUARIO = 13AUTOSJURIDICOS.CREADO_POR', 
                            'inner');
        
        $this->db->join(    '13USUARIOS USUARIO_ASIGNADO', 
                            'USUARIO_ASIGNADO.IDUSUARIO = 13AUTOSJURIDICOS.ASIGNADO_A', 
                            'inner');
        
        $this->db->join(    '13ESTADOAUTO', 
                            '13ESTADOAUTO.COD_ESTADOAUTO = 13AUTOSJURIDICOS.COD_ESTADOAUTO ', 
                            'inner');
        
        
        $this->db->where(   '13AUTOSJURIDICOS.NUM_AUTOGENERADO', 
                             $num_autogenerado);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function listCoordinadorActoAdministrativo($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select(' 
                            NUL.*,
                            TIPACTAD.DESCRIPCION_ACTO,
                            AUJUR.NUM_AUTOGENERADO
                            ');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 2);
        $this->db->order_by('AUJUR.NUM_AUTOGENERADO', 'DESC');
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countCoordinadorActoAdministrativo($fill){
        $array = array();
        
        $this->db->select('count(NUL.COD_NULIDAD) NUM');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 2);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
    
    function listRechazadoActoAdministrativo($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select(' 
                            NUL.*,
                            TIPACTAD.DESCRIPCION_ACTO,
                            AUJUR.NUM_AUTOGENERADO
                            ');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 4);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countRechazadoActoAdministrativo($fill){
        $array = array();
        
        $this->db->select('count(NUL.COD_NULIDAD) NUM');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 4);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
    
    
    function retrieveUsuariosXgrupo($idgrupo){
        $array = array();
        
        $this->db->select(' U.*');
        $this->db->from('13USUARIOS U');
        $this->db->join('13USUARIOS_GRUPOS UG','UG.IDUSUARIO = U.IDUSUARIO');
        $this->db->where('UG.IDGRUPO', $idgrupo);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
    }
    
    function retrieveUsuario($idusuario){
        $array = array();
        
        $this->db->select(' USU.*');
        $this->db->from('13USUARIOS USU');
        $this->db->where('USU.IDUSUARIO', $idusuario);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }else{
            return null;
        }
        
    }
    
    function retrieveAutorizacion($cod_fiscalizacion){
        $array = array();
        
        $this->db->select(' AUNOTEM.*,  
                            EMP.NOMBRE_EMPRESA');
        $this->db->from('13AUTORIZACION_NOTI_EMAIL AUNOTEM');
        $this->db->join(   '13EMPRESA EMP', 
                           'AUNOTEM.NITEMPRESA = EMP.CODEMPRESA', 
                           'inner');
        $this->db->where('AUNOTEM.COD_FISCALIZACION', $cod_fiscalizacion);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }else{
            return null;
        }
        
    }
    
    function actualizaMetodEnvio($cod_nulidad, $cod_metodo_contacto){
        $this->db->set("NUL.COD_METODO_CONTACTO"            , $cod_metodo_contacto);
        $this->db->where('NUL.COD_NULIDAD'             , $cod_nulidad);
        $this->db->update("13NULIDAD NUL");
    }
    
    
    function listNulidadesNotiCorFisicoActoAdmin($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select(' 
                            NUL.*,
                            TIPACTAD.DESCRIPCION_ACTO,
                            AUJUR.NUM_AUTOGENERADO
                            ');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 3);
        $this->db->where('NUL.COD_METODO_CONTACTO', 2);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countNulidadesNotiCorFisicoActoAdmin($fill){
        $array = array();
        
        $this->db->select('count(NUL.COD_NULIDAD) NUM');
        $this->db->from('13NULIDAD NUL');
        
        $this->db->join(    '13TIPOACTOADMINISTRATIVO TIPACTAD', 
                            'NUL.COD_TIPO_ADMIN = TIPACTAD.COD_TIPO_ADMIN', 
                            'inner');
        
        $this->db->join(    '13AUTOSJURIDICOS AUJUR', 
                            'AUJUR.COD_NULIDAD = NUL.COD_NULIDAD', 
                            'inner');

        $this->db->where('AUJUR.COD_ESTADOAUTO', 3);
        $this->db->where('NUL.COD_METODO_CONTACTO', 2);
        
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    13NULIDAD.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    13NULIDAD.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
    
   function listNulidadesNotificacionesFisico($fill,$iDisplayStart,$limit){
        $array = array();
        
        $this->db->select(' 
                            NOTI_NULIDADES.*,
                            ACTO_JURIDICO.COD_NULIDAD COD_NUL,
                            ACTO_JURIDICO.NUM_AUTOGENERADO COD_ACTO_ADMINITRATIVO
                            ');
        $this->db->from('13AUTOSJURIDICOS NOTI_NULIDADES');
        
        $this->db->join(    '13AUTOSJURIDICOS ACTO_JURIDICO', 
                            'NOTI_NULIDADES.COD_ACTOADMINISTRATIVO = ACTO_JURIDICO.NUM_AUTOGENERADO', 
                            'inner');

        $this->db->where('NOTI_NULIDADES.COD_ESTADOAUTO', 1);
        $this->db->where('NOTI_NULIDADES.COD_TIPOCOMUNICACION', 8);
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    ACTO_JURIDICO.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    ACTO_JURIDICO.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $this->db->limit($limit,$iDisplayStart);
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        
        return $array;
        
    }
    
    function countNulidadesNotificacionesFisico($fill){
        $array = array();
        
        $this->db->select('count(NOTI_NULIDADES.NUM_AUTOGENERADO) NUM');
        $this->db->from('13AUTOSJURIDICOS NOTI_NULIDADES');
        
        $this->db->join(    '13AUTOSJURIDICOS ACTO_JURIDICO', 
                            'NOTI_NULIDADES.COD_ACTOADMINISTRATIVO = ACTO_JURIDICO.NUM_AUTOGENERADO', 
                            'inner');

        $this->db->where('NOTI_NULIDADES.COD_ESTADOAUTO', 1);
        $this->db->where('NOTI_NULIDADES.COD_TIPOCOMUNICACION', 8);
        
        if(trim($fill) != ''){
           
            $this->db->where(   '(     
                                    ACTO_JURIDICO.COD_NULIDAD        like \'%' . $fill . '%\' or
                                    ACTO_JURIDICO.COD_FISCALIZACION  like \'%' . $fill . '%\'
                                 )
                                ',
                                NULL,
                                FALSE);
        }
        
        $query = $this->db->get(); 
        
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0]->NUM;
        }
        
        return null;
        
    }
}
?>
