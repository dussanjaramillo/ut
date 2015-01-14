<?php
class Insolvencia_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    function actualiza_regimen($datos,$where){
        $this->db->trans_start();
        $this->db->where('COD_RECEPCION_TITULO', $where);
        $this->db->set('COD_GESTIONCOBRO', $datos['COD_GESTIONCOBRO']);
        $this->db->update('RI_REGIMENINSOLVENCIA');
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }
        
        return FALSE;
    }
        
    
    function consultar_usuarios() {
        $this->db->select('IDUSUARIO,NOMBRES,APELLIDOS');
        $this->db->from('USUARIOS');
        $this->db->where('IDCARGO','12');
        $this->db->where('ACTIVO','1');
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
    }
    function consultar_usuarios1() {
        $this->db->select('IDUSUARIO,NOMBRES,APELLIDOS');
        $this->db->from('USUARIOS US');
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
    }
    function consultar_regimen($cod_tit){
        $this->db->select('TI.*,RT.*,EM.*,RI.*,TD.*');
        $this->db->from('TITULOS TI');
        $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=TI.COD_RECEPCIONTITULO','inner');
        $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.COD_FISCALIZACION =RT.COD_FISCALIZACION_EMPRESA','inner');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA =RT.NIT_EMPRESA','inner');
        $this->db->join('TIPODOCUMENTO TD','TD.CODTIPODOCUMENTO =EM.COD_TIPODOCUMENTO','inner');
        $this->db->join('RI_CLASIFICACIONCREDITOS CC','CC.COD_REGIMENINSOLVENCIA =RI.COD_REGIMENINSOLVENCIA','inner');
        $this->db->where('RT.NIT_EMPRESA',$cod_tit);
        $query = $this->db->get();
        
            return $query->row();
        
    }
    function consultar_regimenel($cod_tit){
        $this->db->select('RT.COD_RECEPCIONTITULO,EM.RAZON_SOCIAL,CC.TELEFONO,TD.NOMBRETIPODOC,CC.PROMOTOR,
        RI.COD_REGIMENINSOLVENCIA,EM.CODEMPRESA,RI.COD_FISCALIZACION,RI.NUM_PROCESO');//,AU.COD_AUDIENCIA
        $this->db->from('RECEPCIONTITULOS RT');
        $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.COD_FISCALIZACION =RT.COD_FISCALIZACION_EMPRESA','inner');
        //$this->db->join('RI_AUDIENCIA AU','RI.COD_REGIMENINSOLVENCIA = AU.COD_REGIMENINSOLVENCIA','inner');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA =RT.NIT_EMPRESA','inner');
        $this->db->join('TIPODOCUMENTO TD','TD.CODTIPODOCUMENTO =EM.COD_TIPODOCUMENTO','inner');
        $this->db->join('RI_CLASIFICACIONCREDITOS CC','CC.COD_REGIMENINSOLVENCIA =RI.COD_REGIMENINSOLVENCIA','inner');
        $this->db->where('RT.COD_RECEPCIONTITULO',$cod_tit);
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
            return $query->row();
        
    }
    function rec_doc($reg_ins){
        $this->db->select('*');
        $this->db->from('RI_REGIMENINSOLVENCIA RI');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA =RI.NITEMPRESA','inner');
        $this->db->where('RI.COD_REGIMENINSOLVENCIA',$reg_ins);
        $query = $this->db->get();
        return $query->row();
    }
    
    function guardar_asignacionabogado($data,$where){
        $this->db->set('FECHA_ASIGNACION',"to_date('".$data['FECHA_ASIGNACION']."','dd/mm/yyyy')",false);
        $this->db->set('ABOGADO_ASIGNADO', $data['ABOGADO_ASIGNADO']);
        $this->db->set('COMENTARIOS_ASIGNACION', $data['COMENTARIOS_ASIGNACION']);
        $this->db->set('COD_ESTADOPROCESO', $data['COD_ESTADOPROCESO']);
        $this->db->set('COD_GESTIONCOBRO', $data['COD_GESTIONCOBRO']);
        $this->db->where('COD_REGIMENINSOLVENCIA', $where);
        $query = $this->db->update('RI_REGIMENINSOLVENCIA');
        //print_r($this->db->last_query());die();
        return $query;
    }
    
        function guardar_regimen_estado($titulo){
            $arreglo = array('COD_ESTADOPROCESO'=>'9');
            $this->db->where('COD_REGIMENINSOLVENCIA', $titulo);
            $query = $this->db->update('RI_REGIMENINSOLVENCIA', $arreglo);
            return $query;
        }
        
    function consultar_titulos1($dato){
        $this->db->select('*');
        $this->db->from('RECEPCIONTITULOS RT');
        $this->db->join('TITULOS TI','TI.COD_RECEPCIONTITULO =RT.COD_RECEPCIONTITULO','inner');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA =RT.NIT_EMPRESA','inner');
        $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.NITEMPRESA =RT.NIT_EMPRESA','inner');
        $this->db->where('RT.NIT_EMPRESA',$dato);
        $query = $this->db->get();
        return $query->row();
    }
    function cobro_coactivo($cod_tit){
        $this->db->select('*');
        $this->db->from('COBROPERSUASIVO CP');
        $this->db->where('CP.COD_TITULO',$cod_tit);
        $query = $this->db->get();
        return $query->row();
    }
    function doc_fis($reg_ins){
        $arreglo = array('DOCUMENTOS_FISICOS_RECIBIDOS'=>'S');
        $this->db->where('COD_REGIMENINSOLVENCIA', $reg_ins);
        $this->db->update('RI_REGIMENINSOLVENCIA', $arreglo);
    }
    
    function info_audiencia($reg_ins,$data){
        $this->db->where('COD_AUDIENCIA', $reg_ins);
        $this->db->set('VALOR_BIENES', $data['VALOR_BIENES']);
        $this->db->set('ADJUDICA_BIEN', $data['ADJUDICA_BIEN']);
        $this->db->set('RUTA_INFO', $data['RUTA_INFO']);
        $this->db->set('DOC_INFO', $data['DOC_INFO']);        
        $this->db->update('RI_AUDIENCIA');
        //print_r($this->db->last_query());die();
    }
    
    function consultar_motivo($motivo){
        $this->db->select('TM.COD_MOTIVOMEMORIAL');
        $this->db->from('TIPOMOTIVOMEMORIAL TM');
        $this->db->where('TM.NOMBRE_MOTIVO',$motivo);
        $query = $this->db->get();
        return $query->row()->COD_MOTIVOMEMORIAL;        
    }
    function consultar_liquidacion($nit){
        $this->db->select('*');
        $this->db->from('LIQUIDACION LI');
        $this->db->where('LI.NITEMPRESA',$nit);
        $query = $this->db->get();
        return $query->row();
    }
    function consultar_not($nit){
        $this->db->select('*');
        $this->db->from('EMPRESA EM');
        $this->db->where('EM.CODEMPRESA',$nit);
        $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.NITEMPRESA =EM.CODEMPRESA','inner');
        $this->db->join('TIPODOCUMENTO TD','TD.CODTIPODOCUMENTO =EM.COD_TIPODOCUMENTO','inner');
        $query = $this->db->get();
        return $query->row();
        
    }
    function consultar_titulos($dato,$parametro,$dato1){       
        $this->db->select('*');
        $this->db->from('COBROPERSUASIVO CP');
        $this->db->where('CP.COD_TIPO_RESPUESTA','196');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA =CP.NIT_EMPRESA','inner');
        $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.NITEMPRESA =CP.NIT_EMPRESA','inner');
        $this->db->join('FISCALIZACION FI','FI.COD_FISCALIZACION =RI.COD_FISCALIZACION','inner');
        $this->db->join('RECEPCIONTITULOS RT','RT.COD_FISCALIZACION_EMPRESA=FI.COD_FISCALIZACION','right');
        $this->db->join('TITULOS TI','TI.COD_RECEPCIONTITULO =RT.COD_RECEPCIONTITULO','right');
        
        
                
        if($dato1==false){
            if($parametro=="COD_TITULO")
                $this->db->where('TI.'.$parametro,$dato);
            if($parametro=="NIT_EMPRESA"){
                $this->db->where('RT.'.$parametro,$dato);
                $this->db->order_by("TI.COD_TITULO", "asc"); 
               // $this->db->where('RI.COD_FISCALIZACION','RT.COD_FISCALIZACION_EMPRESA');
            }
            if($parametro=="RAZON_SOCIAL")
                $this->db->where('EM.'.$parametro,$dato);
        }
        else{
            $this->db->where('TI.FECHA_CARGA >=',$dato);
            $this->db->where('TI.FECHA_CARGA <=',$dato1);           
        }        
        
        $query = $this->db->get();
//        if($query->num_rows() > 0)
//        {
            return $query;
        //}

    }
    function reg_insol($dato,$parametro,$dato1){       
        $this->db->select('*');
        $this->db->from('RI_REGIMENINSOLVENCIA RI');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA =RI.NITEMPRESA','inner');
        $this->db->join('FISCALIZACION FI','FI.COD_FISCALIZACION =RI.COD_FISCALIZACION','inner');
//        $this->db->join('RECEPCIONTITULOS RT','RT.COD_FISCALIZACION_EMPRESA=FI.COD_FISCALIZACION','right');
//        $this->db->join('TITULOS TI','TI.COD_RECEPCIONTITULO =RT.COD_RECEPCIONTITULO','right');
                
        if($dato1==false){
            
            if($parametro=="NITEMPRESA"){
                $this->db->where('RI.'.$parametro,$dato);
               
               // $this->db->where('RI.COD_FISCALIOZACION','RT.COD_FISCALIZACION_EMPRESA');
            }
            if($parametro=="RAZON_SOCIAL")
                $this->db->where('EM.'.$parametro,$dato);
        }
        else{
            $this->db->where('TI.FECHA_CARGA >=',$dato);
            $this->db->where('TI.FECHA_CARGA <=',$dato1);           
        }        
        
        $query = $this->db->get();

            return $query->row();
 
    }
    function remitir_doc($cod_tit){
            $this->db->select('TD.NOMBRETIPODOC,RI.NUM_PROCESO,RI.NITEMPRESA,RP.COD_RECEPCIONTITULO,EM.RAZON_SOCIAL,RI.COD_REGIMENINSOLVENCIA,RI.COD_FISCALIZACION,RI.TIPO_REGIMEN');
            $this->db->from('RECEPCIONTITULOS RP');
            $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.COD_RECEPCION_TITULO  = RP.COD_RECEPCIONTITULO','inner');
            $this->db->join('EMPRESA EM','EM.CODEMPRESA  =RI.NITEMPRESA','inner');
            $this->db->join('TIPODOCUMENTO TD','TD.CODTIPODOCUMENTO  =EM.COD_TIPODOCUMENTO','inner');
            $this->db->where('COD_RECEPCION_TITULO',$cod_tit);
            $query = $this->db->get();
           // print_r($this->db->last_query($query));
//            die();
            return $query->row();
    }
    function objetar_Pruebas($cod_tit){
            $this->db->select('*');
            $this->db->from('RECEPCIONTITULOS RP');
            $this->db->join('RI_REGIMENINSOLVENCIA RI','RI.COD_RECEPCION_TITULO  = RP.COD_RECEPCIONTITULO','inner');
            $this->db->join('EMPRESA EM','EM.CODEMPRESA  = RI.NITEMPRESA','inner');
            $this->db->join('TIPODOCUMENTO TD','TD.CODTIPODOCUMENTO = EM.COD_TIPODOCUMENTO','inner');
            $this->db->join('RI_CLASIFICACIONCREDITOS CC','CC.COD_REGIMENINSOLVENCIA = RI.COD_REGIMENINSOLVENCIA','inner');
            $this->db->where('RP.COD_RECEPCIONTITULO',$cod_tit);
            $query = $this->db->get();
//            print_r($this->db->last_query($query));die();
            return $query->row();
    }
    function guardar_remision($data,$data1){
                $this->db->set('FECHA_REMISION',"to_date('" . $data['FECHA_REMISION']."','dd/mm/yyyy HH24:MI:SS')", false);
                $this->db->insert('RI_REMISIONSUPERINTENDENCIA',$data);
                $arreglo = array('COD_ESTADOPROCESO'=>'3');
                $this->db->where('COD_REGIMENINSOLVENCIA', $data['COD_REGIMENINSOLVENCIA']);
                $query = $this->db->update('RI_REGIMENINSOLVENCIA', $arreglo);
//                print_r($this->db->last_query($query));die();
                return $query;
        
    }
    
    function actualiza_regimenActa($datos,$where){
        $this->db->trans_start();
        $this->db->where('COD_FISCALIZACION', $where);
        $this->db->set('COD_GESTIONCOBRO', $datos['COD_GESTIONCOBRO']);
        $this->db->update('RI_REGIMENINSOLVENCIA');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }
        
        return FALSE;
    }
    
    public function insertar_expediente($data = NULL) {
//        if (!empty($datos)) :
//            $this->db->set('FECHA_RADICADO', "TO_DATE('" . $datos['FECHA_RADICADO'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
//            unset($datos['FECHA_RADICADO']);
//            $this->db->insert("EXPEDIENTE", $datos);
//        endif;
         $this->db->set('RUTA_DOCUMENTO', $data['RUTA_DOCUMENTO']);                
         $this->db->set('NOMBRE_DOCUMENTO', $data['NOMBRE_DOCUMENTO']);                
         $this->db->set('COD_RESPUESTAGESTION', $data['COD_RESPUESTAGESTION']);                
         $this->db->set('COD_TIPO_EXPEDIENTE', $data['COD_TIPO_EXPEDIENTE']);                
         $this->db->set('ID_USUARIO', $data['ID_USUARIO']);                
         $this->db->set('FECHA_RADICADO', $data['FECHA_RADICADO']);                
         $this->db->set('COD_FISCALIZACION', $data['COD_FISCALIZACION']); 
         $this->db->set('RADICADO_ONBASE', $data['RADICADO_ONBASE']); 
         //$this->db->set('FECHA_ONBASE', $data['FECHA_ONBASE']); 
         $this->db->set('FECHA_ONBASE',"to_date('".$data['FECHA_ONBASE']."','dd/mm/yyyy')",false);
         $this->db->insert('EXPEDIENTE');
         $this->db->trans_complete();
            if ($this->db->affected_rows() >= 0){        
            $this->db->trans_complete();
            return TRUE;            
            }
            else 
               return FALSE;  
    }
    
    function guardar_titfis($data,$cod_fis){
        $this->db->set('FECHA',"to_date('" . $data['FECHA']."','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->where('COD_FISCALIZACION',$cod_fis);
        $this->db->update('RI_REGIMENINSOLVENCIA',$data);        
        //print_r($this->db->last_query());die();
    }
    
    function guardar_rev_pro($data,$arreglo,$bienin_regins,$fiscalizacion){
        $query = $this->db->insert('RI_CLASIFICACIONCREDITOS', $data);
        
        if($bienin_regins=='S')
            //$arreglo = array('COD_ESTADOPROCESO'=>'7');
            $this->db->set('COD_ESTADOPROCESO', 7);
        else {
            $this->db->set('COD_ESTADOPROCESO', 5);
        }
        $this->db->where('COD_FISCALIZACION', $fiscalizacion);
        $this->db->set('COD_GESTIONCOBRO', $arreglo['COD_GESTIONCOBRO']);
        $query = $this->db->update('RI_REGIMENINSOLVENCIA');
        //print_r($this->db->last_query($query));die();
        return $query;
    }

        
    function guardar_actaaudiencia($data,$data1){
        $this->db->set('FECHA_CREACION',"to_date('" . $data1['FECHA_CREACION']."','dd/mm/yyyy HH24:MI:SS')", false);
        @$this->db->set('FECHA_AUDIENCIA',"to_date('" . $data1['FECHA_AUDIENCIA']."','dd/mm/yyyy HH24:MI:SS')", false);
        $query = $this->db->insert('RI_AUDIENCIA', $data);
        //print_r($this->db->last_query($query));die();
        return $query;
    }
    function memorial($data,$data1){        
        $query = $this->db->insert('RI_MEMORIALPRUEBAS', $data);        
        //print_r($this->db->last_query($query));die();
        $this->db->set('COD_ESTADOPROCESO', 6);
        
        $this->db->set('COD_GESTIONCOBRO', $data1['COD_GESTIONCOBRO']);
        $this->db->where('COD_REGIMENINSOLVENCIA', $data['COD_REGIMENINSOLVENCIA']);
        $query = $this->db->update('RI_REGIMENINSOLVENCIA');
        //print_r($this->db->last_query($query));die();
        return $query;
    }
    function buscar_audiencia($cod_tit){
        $this->db->select_max('COD_AUDIENCIA');
        $this->db->from('RI_AUDIENCIA AU');
        $this->db->where('AU.COD_REGIMENINSOLVENCIA',$cod_tit);
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        return $query->row()->COD_AUDIENCIA;
    }
   function guardar_comentarios($comentarios,$regimen){
        $arreglo = array('DATOS_AUDIENCIA'=>$comentarios);
        $this->db->where('COD_AUDIENCIA', $regimen);
        $query = $this->db->update('RI_AUDIENCIA', $arreglo);
        return $query;
    }
    function guardar_reorga($data,$data1){
        if($data1){
        $this->db->set('FECHA_AUDIENCIA',"to_date('" . $data1['FECHA_AUDIENCIA']."','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('FECHA_PAGO',"to_date('" . $data1['FECHA_PAGO']."','dd/mm/yyyy HH24:MI:SS')", false);
        }
        $query = $this->db->insert('RI_AUDIENCIARI', $data);
        //print_r($this->db->last_query($query));die();
        return $query;
        
    }
    function getDatax($reg,$search,$lenght = 10,$viene){
        if($viene==1){
//          $this->db->select('CP.NIT_EMPRESA,EM.RAZON_SOCIAL,TI.COD_TITULO,TI.NUM_RADICADO,TI.FECHA_CARGA,
//          RT.OBSERVACIONES,FI.COD_FISCALIZACION,CP.COD_COBRO_PERSUASIVO,CP.COD_ESTADO_PROCESO,
//          (select RI.NUM_PROCESO FROM RI_REGIMENINSOLVENCIA RI WHERE RI.COD_TITULO = TI.COD_TITULO) AS NUM_PROCESO,
//          (select RI.COMENTARIOS FROM RI_REGIMENINSOLVENCIA RI WHERE RI.COD_TITULO = TI.COD_TITULO) AS COMENTARIOS',true);
//          $this->db->from('COBROPERSUASIVO CP'); 
//          $this->db->join('TITULOS TI','TI.COD_TITULO =CP.COD_TITULO','right'); 
//          $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=TI.COD_RECEPCIONTITULO','right');
//          $this->db->join('FISCALIZACION FI','FI.COD_FISCALIZACION =RT.COD_FISCALIZACION_EMPRESA','inner');
//          $this->db->join('ASIGNACIONFISCALIZACION AF','AF.COD_ASIGNACIONFISCALIZACION =FI.COD_ASIGNACION_FISC','inner');
//          $this->db->join('EMPRESA EM','EM.CODEMPRESA =AF.ANIT_EMPRESA','inner');
//          $this->db->where('CP.COD_TIPO_RESPUESTA','196');
//          $this->db->order_by("CP.COD_TITULO", "asc");             
          
//            $this->db->select('CP.NIT_EMPRESA,EM.RAZON_SOCIAL,CP.COD_RECEPCIONTITULO,CP.COD_FISCALIZACION,
//            RT.FECHA_RECEPCION AS FECHA_CARGA ,CP.COD_COBRO_PERSUASIVO,CP.COD_ESTADO_PROCESO,FI.CODIGO_PJ',true);
//            $this->db->from('COBROPERSUASIVO CP'); 
//            $this->db->join('EMPRESA EM','EM.CODEMPRESA = CP.NIT_EMPRESA','inner');
//            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO = CP.COD_RECEPCIONTITULO','inner');
//            $this->db->join('FISCALIZACION FI','FI.COD_FISCALIZACION = CP.COD_FISCALIZACION','inner');
//            $this->db->where('CP.COD_TIPO_RESPUESTA','196');
//            $this->db->order_by("CP.COD_RECEPCIONTITULO", "asc");             
            $this->db->select('RI.NITEMPRESA AS NIT_EMPRESA,EM.RAZON_SOCIAL,RI.NUM_RADICADO,RI.FECHA AS FECHA_CARGA,
            RI.COMENTARIOS,RI.NUM_PROCESO,RI.COD_FISCALIZACION,RT.COD_RECEPCIONTITULO,CP.COD_PROCESO_COACTIVO,
            RI.COD_ESTADOPROCESO,GC.COD_TIPO_RESPUESTA,RI.TIPO_REGIMEN');
            $this->db->from('RI_REGIMENINSOLVENCIA RI');
            $this->db->join('GESTIONCOBRO GC',"GC.COD_GESTION_COBRO = RI.COD_GESTIONCOBRO",'left');
            $this->db->join('TIPOGESTION TG',"TG.COD_GESTION = GC.COD_TIPOGESTION",'left');            
            $this->db->join('RESPUESTAGESTION RG',"RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA",'left');    
            $this->db->join('EMPRESA EM','EM.CODEMPRESA = RI.NITEMPRESA','inner');
            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER','ER.COD_ESTADO_PROCESO = RI.COD_ESTADOPROCESO','inner');
            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO = RI.COD_RECEPCION_TITULO','inner');
            $this->db->join('COBROPERSUASIVO CP','CP.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO','left');    
            $this->db->where('(RI.COD_ESTADOPROCESO = \'11\' OR RI.COD_ESTADOPROCESO = \'14\')');
            //$this->db->join('COBROPERSUASIVO',);
            //$this->db->where('RI.COD_ESTADOPROCESO',11);                        
            
        }
        else if($viene!=1){
//            $this->db->select('*');
//            $this->db->from('RI_REGIMENINSOLVENCIA RI');
//            $this->db->join('EMPRESA EM','EM.CODEMPRESA =RI.NITEMPRESA','inner');
//            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER','ER.COD_ESTADO_PROCESO =RI.COD_ESTADOPROCESO','inner');
//            $this->db->join('TITULOS TI','TI.COD_TITULO =RI.COD_TITULO','inner'); 
//            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=TI.COD_RECEPCIONTITULO','inner');
//            $this->db->where('RI.COD_ESTADOPROCESO',$viene);
//            $this->db->order_by("RI.COD_TITULO", "asc"); 
            $this->db->select('*');
            $this->db->from('RI_REGIMENINSOLVENCIA RI');
            $this->db->join('EMPRESA EM','EM.CODEMPRESA =RI.NITEMPRESA','inner');
            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER','ER.COD_ESTADO_PROCESO =RI.COD_ESTADOPROCESO','inner');
            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=RI.COD_RECEPCION_TITULO','inner');
            $this->db->where('RI.COD_ESTADOPROCESO',$viene);
            $this->db->order_by("RI.COD_RECEPCION_TITULO", "asc"); 
        }
       
        if($search){
            $array = array(
                'CP.COD_TITULO'    => $search
                    );
            $this->db->or_like($array); 
        } 
        $this->db->limit($lenght,$reg);
        $query = $this->db->get();
        //print_r($this->db->last_query($query));die();
        return $query->result();
        
    }
    
    function viewRegimenData(){
            $this->db->select('RI.COD_REGIMENINSOLVENCIA", RI.COD_FISCALIZACION, RI.NITEMPRESA, RI.NUM_PROCESO, RI.COD_ESTADOPROCESO, RI.FECHA, 
            RI.COMENTARIOS, RI.COD_RECEPCION_TITULO, RI.EXISTE_TITULO, RI.COD_GESTIONCOBRO, GC.COD_TIPO_RESPUESTA,RG.NOMBRE_GESTION, 
            RG.COD_TIPOGESTION, EM.RAZON_SOCIAL, EM.TELEFONO_FIJO, EM.DIRECCION, RI.EXISTE_TITULO, 
            F.CODIGO_PJ,ER.NOMBRE_ESTADO,CP.COD_PROCESO_COACTIVO,RI.TIPO_REGIMEN');
            $this->db->where('COD_ESTADOPROCESO != \'14\' ');
            $this->db->from('RI_REGIMENINSOLVENCIA RI');
            $this->db->join('GESTIONCOBRO GC',"GC.COD_GESTION_COBRO = RI.COD_GESTIONCOBRO",'inner');
            $this->db->join('TIPOGESTION TG',"TG.COD_GESTION = GC.COD_TIPOGESTION",'inner');            
            $this->db->join('RESPUESTAGESTION RG',"RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA",'inner');            
            $this->db->join('FISCALIZACION F',"RI.COD_FISCALIZACION = F.COD_FISCALIZACION",'inner');           
            $this->db->join('EMPRESA EM',"EM.CODEMPRESA = RI.NITEMPRESA",'inner');            
            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER',"ER.COD_ESTADO_PROCESO = RI.COD_ESTADOPROCESO",'inner');            
            $this->db->join('RECEPCIONTITULOS RT',"RT.COD_RECEPCIONTITULO = RI.COD_RECEPCION_TITULO",'inner');
            $this->db->join('COBROPERSUASIVO CP','CP.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO','inner');
            $this->db->order_by("RI.COD_RECEPCION_TITULO", "asc"); 
            $query = $this->db->get();
            //print_r($this->db->last_query($query));die();
            return $query;            
    }
    
    function totalData($search,$viene){        
        if($viene==1){
            $this->db->select('RI.NITEMPRESA AS NIT_EMPRESA,EM.RAZON_SOCIAL,RI.NUM_RADICADO,RI.FECHA AS FECHA_CARGA,
            RI.COMENTARIOS,RI.NUM_PROCESO,RI.COD_FISCALIZACION,RT.COD_RECEPCIONTITULO,CP.COD_PROCESO_COACTIVO,
            RI.COD_ESTADOPROCESO,GC.COD_TIPO_RESPUESTA,RI.TIPO_REGIMEN');
            $this->db->from('RI_REGIMENINSOLVENCIA RI');
            $this->db->join('GESTIONCOBRO GC',"GC.COD_GESTION_COBRO = RI.COD_GESTIONCOBRO",'left');
            $this->db->join('TIPOGESTION TG',"TG.COD_GESTION = GC.COD_TIPOGESTION",'left');            
            $this->db->join('RESPUESTAGESTION RG',"RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA",'left');    
            $this->db->join('EMPRESA EM','EM.CODEMPRESA = RI.NITEMPRESA','inner');
            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER','ER.COD_ESTADO_PROCESO = RI.COD_ESTADOPROCESO','inner');
            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO = RI.COD_RECEPCION_TITULO','inner');
            $this->db->join('COBROPERSUASIVO CP','CP.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO','inner');    
            $this->db->where('(RI.COD_ESTADOPROCESO = \'11\' OR RI.COD_ESTADOPROCESO = \'14\')');
            
//            $this->db->select('CP.NIT_EMPRESA,EM.RAZON_SOCIAL,TI.COD_TITULO,TI.NUM_RADICADO,TI.FECHA_CARGA,RT.OBSERVACIONES,FI.COD_FISCALIZACION,CP.COD_COBRO_PERSUASIVO,CP.COD_ESTADO_PROCESO,(select RI.NUM_PROCESO FROM RI_REGIMENINSOLVENCIA RI WHERE RI.COD_TITULO = TI.COD_TITULO) AS NUM_PROCESO,(select RI.COMENTARIOS FROM RI_REGIMENINSOLVENCIA RI WHERE RI.COD_TITULO = TI.COD_TITULO) AS COMENTARIOS',true);
//            $this->db->from('COBROPERSUASIVO CP');
//            $this->db->join('TITULOS TI','TI.COD_TITULO =CP.COD_TITULO','inner'); 
//            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=TI.COD_RECEPCIONTITULO','inner');
//            $this->db->join('FISCALIZACION FI','FI.COD_FISCALIZACION =RT.COD_FISCALIZACION_EMPRESA','inner');
//            $this->db->join('ASIGNACIONFISCALIZACION AF','AF.COD_ASIGNACIONFISCALIZACION =FI.COD_ASIGNACION_FISC','inner');
//            $this->db->join('EMPRESA EM','EM.CODEMPRESA =AF.NIT_EMPRESA','inner');
//            $this->db->where('CP.COD_TIPO_RESPUESTA','196'); 
//            $this->db->order_by("CP.COD_TITULO", "asc"); 
//            $this->db->select('CP.NIT_EMPRESA,EM.RAZON_SOCIAL,CP.COD_RECEPCIONTITULO,CP.COD_FISCALIZACION,RT.FECHA_RECEPCION AS FECHA_CARGA ,CP.COD_COBRO_PERSUASIVO,CP.COD_ESTADO_PROCESO',true);
//            $this->db->from('COBROPERSUASIVO CP'); 
//            $this->db->join('EMPRESA EM','EM.CODEMPRESA = CP.NIT_EMPRESA','inner');
//            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO = CP.COD_RECEPCIONTITULO','inner');
//            $this->db->where('CP.COD_TIPO_RESPUESTA','196');
//            $this->db->order_by("CP.COD_RECEPCIONTITULO", "asc"); 
        }
        else if($viene!=1){
//            $this->db->select('*');
//            $this->db->from('RI_REGIMENINSOLVENCIA RI');
//            $this->db->join('EMPRESA EM','EM.CODEMPRESA =RI.NITEMPRESA','inner');
//            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER','ER.COD_ESTADO_PROCESO =RI.COD_ESTADOPROCESO','inner');
//            $this->db->join('TITULOS TI','TI.COD_TITULO =RI.COD_TITULO','inner'); 
//            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=TI.COD_RECEPCIONTITULO','inner');
//            $this->db->where('RI.COD_ESTADOPROCESO',$viene);
//            $this->db->order_by("RI.COD_TITULO", "asc"); 
            $this->db->select('*');
            $this->db->from('RI_REGIMENINSOLVENCIA RI');
            $this->db->join('EMPRESA EM','EM.CODEMPRESA =RI.NITEMPRESA','inner');
            $this->db->join('ESTADO_REGIMENINSOLVENCIA ER','ER.COD_ESTADO_PROCESO =RI.COD_ESTADOPROCESO','inner');
            $this->db->join('RECEPCIONTITULOS RT','RT.COD_RECEPCIONTITULO=RI.COD_RECEPCION_TITULO','inner');
            $this->db->where('RI.COD_ESTADOPROCESO',$viene);
            $this->db->order_by("RI.COD_RECEPCION_TITULO", "asc");             
        }
        if($search){
            $array = array(                
                'CP.COD_TITULO'    => $search
                    );
            $this->db->or_like($array); 
        }        
        $query = $this->db->get();
        //print_r($this->db->last_query());die();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
        
        }
    
    
}

?>
