<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed');
// Responsable: Leonardo Molina
class Multasministerio_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function getDatax($nit,$reg,$search,$lenght = 10){
                $this->db->select("MM.*,
            (SELECT COUNT(GESTIONCOBRO.COD_TIPO_RESPUESTA) CODIGO_RESPUESTA_LIQUIDACION FROM GESTIONCOBRO
WHERE COD_FISCALIZACION_EMPRESA=RES.COD_FISCALIZACION AND GESTIONCOBRO.COD_TIPO_RESPUESTA='791') CODIGO_RESPUESTA_LIQUIDACION,"
                . "EM.NOMBRE_EMPRESA,RES.COD_RESOLUCION,RES.COD_FISCALIZACION,RES.DOCUMENTO_COBRO_COACTIVO",FALSE);
        $this->db->from('MULTASMINISTERIO MM');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA = MM.NIT_EMPRESA', ' inner');
        $this->db->join('RESOLUCION RES',"RES.NUMERO_RESOLUCION = MM.NRO_RESOLUCION", ' left');
        if(!empty($nit)){
            $this->db->where('MM.NIT_EMPRESA',$nit);
        }
            $this->db->where('MM.RESPONSABLE',ID_USER);
        
        $this->db->where('NVL(RES.COD_ESTADO,0) not in (419,80)',"",false);
        $this->db->order_by('COD_MULTAMINISTERIO','desc');
        if($search){
            $array = array(
                'MM.NIT_EMPRESA'    => $search,
                'EM.NOMBRE_EMPRESA' => $search,
                'MM.VALOR'          => $search
                    );
            $this->db->or_like($array); 
        }
        
        $this->db->limit($lenght+1,$reg);
        $query = $this->db->get();
        
        $datos = $query->result();
        
//        echo $this->db->last_query();die;
        
        return $datos;
        
    }
    function totalData($nit,$search){
        $this->db->select("MM.*,
            (SELECT COUNT(GESTIONCOBRO.COD_TIPO_RESPUESTA) CODIGO_RESPUESTA_LIQUIDACION FROM GESTIONCOBRO
WHERE COD_FISCALIZACION_EMPRESA=RES.COD_FISCALIZACION AND GESTIONCOBRO.COD_TIPO_RESPUESTA='791') CODIGO_RESPUESTA_LIQUIDACION,"
                . "EM.NOMBRE_EMPRESA,RES.COD_RESOLUCION,RES.COD_FISCALIZACION,RES.DOCUMENTO_COBRO_COACTIVO",FALSE);
        $this->db->from('MULTASMINISTERIO MM');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA = MM.NIT_EMPRESA', ' inner');
        $this->db->join('RESOLUCION RES',"RES.NUMERO_RESOLUCION = MM.NRO_RESOLUCION", ' left');
        if(!empty($nit)){
            $this->db->where('MM.NIT_EMPRESA',$nit);
        }
            $this->db->where('MM.RESPONSABLE',ID_USER);
        $this->db->where('NVL(RES.COD_ESTADO,0) not in (419,80)',"",false);
        $this->db->order_by('COD_MULTAMINISTERIO','desc');
        if($search){
            $array = array(
                'MM.NIT_EMPRESA'    => $search,
                'EM.NOMBRE_EMPRESA' => $search,
                'MM.VALOR'          => $search
                    );
            $this->db->or_like($array); 
        }        
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }
    
    function getForm($nit) {
        $this->db->select('EM.*');
        $this->db->from('EMPRESA EM');
        $this->db->where('EM.CODEMPRESA',$nit);
        $this->db->where('EM.COD_REGIONAL',REGIONAL_ALTUAL);
        $query = $this->db->get();
        return $query->row();    
    }
    function getForm_mul_edil($cod_multa) {
        $this->db->select("EM.*,MUL.*,  "
                . "TO_CHAR(MUL.PERIODO_INICIAL,'DD/MM/RRRR') AS PERIODO_INICIAL2,"
                . "TO_CHAR(FECHA_EJECUTORIA,'DD/MM/RRRR') AS FECHA_EJECUTORIA2 ",FALSE);
        $this->db->from('EMPRESA EM');
        $this->db->join("MULTASMINISTERIO MUL","MUL.NIT_EMPRESA=EM.CODEMPRESA");
        $this->db->where('COD_MULTAMINISTERIO',$cod_multa);
        $query = $this->db->get();
        return $query->row();    
    }

    function getAbogadosRC(){
        // Tabla grupos -> Abogados de relaciones corporativas
        $this->db->select('USU.IDUSUARIO,USU.NOMBREUSUARIO,USU.NOMBRES,USU.APELLIDOS,GR.NOMBREGRUPO');
        $this->db->from('USUARIOS USU');
        $this->db->join('USUARIOS_GRUPOS UG','USU.IDUSUARIO = UG.IDUSUARIO', ' inner');
        $this->db->join('GRUPOS GR','GR.IDGRUPO = UG.IDGRUPO', ' inner');
        $this->db->where('GR.IDGRUPO','44');
        $this->db->where('USU.COD_REGIONAL',REGIONAL_ALTUAL);
        $this->db->where('USU.ACTIVO','1');
        $query = $this->db->get();
        return $query->result();
            
    }
    
    function getDataTable($nit=null){
        $this->db->select('MM.*,EM.NOMBRE_EMPRESA');
        $this->db->from('MULTASMINISTERIO MM');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA = MM.NIT_EMPRESA', ' inner');
        $this->db->where('MM.NIT_EMPRESA',$nit);
        $this->db->order_by('COD_MULTAMINISTERIO','desc');
        $query = $this->db->get();
        return $query;
        
    }
    
    function getMulta($multa){
        // el concepto siempre va a ser 4
        $this->db->select('MM.*,EM.NOMBRE_EMPRESA');
        $this->db->from('MULTASMINISTERIO MM');
        $this->db->join('EMPRESA EM','EM.CODEMPRESA = MM.NIT_EMPRESA', ' inner');
        $this->db->where('MM.COD_MULTAMINISTERIO',$multa);
        $query = $this->db->get();
        return $query->row();
    }
    
    function getDocumentosMulta($multa){
        $this->db->select('*');
        $this->db->from('DOCUMENTOSMULTAS');
        $this->db->where('COD_MULTAMINISTERIO',$multa);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getRegional(){
        $this->db->select('*');
        $this->db->from('REGIONAL');
        $this->db->where('COD_REGIONAL',REGIONAL_ALTUAL);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getMultaResolucion($multa){
        $this->db->select('RS.NUMERO_RESOLUCION,RS.RUTA_DOCUMENTO_FIRMADO');
        $this->db->from('MULTASMINISTERIO MM');
        $this->db->join('RESOLUCION RS','MM.NRO_RESOLUCION = RS.NUMERO_RESOLUCION');
        $this->db->where('COD_MULTAMINISTERIO',$multa);
        $query = $this->db->get();
        return $query->row();
    }
    
    function addMulta($data,$data2,$codGestion){
        
        $this->db->set('FECHA_CREACION','SYSDATE',FALSE);
        $this->db->set('FECHA_GESTION','SYSDATE',FALSE);
        $this->db->set('COD_GESTION_COBRO',$codGestion);
        $this->db->set('PERIODO_INICIAL', "to_date('" . $data2['PERIODO_INICIAL'] . "','dd/mm/yyyy HH24:MI:SS')", false);
//        $this->db->set('PERIODO_FINAL',   "to_date('" . $data2['PERIODO_FINAL'] .   "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('FECHA_EJECUTORIA',"to_date('" . $data2['FECHA_EJECUTORIA']. "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->insert('MULTASMINISTERIO', $data);
        $this->db->select('COD_MULTAMINISTERIO');
        $this->db->from('MULTASMINISTERIO');
        $this->db->where('NIT_EMPRESA',$data['NIT_EMPRESA']);
        $this->db->where('NRO_RADICADO',$data['NRO_RADICADO']);
        $this->db->where('NRO_RESOLUCION',$data['NRO_RESOLUCION']);
        $this->db->where('NIS',$data['NIS']);
        $this->db->where('RESPONSABLE',$data['RESPONSABLE']);
        $this->db->order_by('FECHA_CREACION','DESC');
        $query = $this->db->get();
        
        return $query->row();
    }
    function edit_Multa($data,$data2,$codGestion,$multa){
        
        $this->db->set('FECHA_CREACION','SYSDATE',FALSE);
        $this->db->set('FECHA_GESTION','SYSDATE',FALSE);
        $this->db->set('COD_GESTION_COBRO',$codGestion);
        $this->db->set('PERIODO_INICIAL', "to_date('" . $data2['PERIODO_INICIAL'] . "','dd/mm/yyyy HH24:MI:SS')", false);
//        $this->db->set('PERIODO_FINAL',   "to_date('" . $data2['PERIODO_FINAL'] .   "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->set('FECHA_EJECUTORIA',"to_date('" . $data2['FECHA_EJECUTORIA']. "','dd/mm/yyyy HH24:MI:SS')", false);
        $this->db->where('COD_MULTAMINISTERIO',$multa);
        $this->db->update('MULTASMINISTERIO', $data);
    }
    function plantilla($id) {
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array[0]['ARCHIVO_PLANTILLA'];
    }

    
    function addMultaDocumento($name,$multa){
        $this->db->set('COD_MULTAMINISTERIO',$multa);
        $this->db->set('NOMBRE_DOCUMENTO',$name);
        $this->db->set('FECHA_DOCUMENTO','SYSDATE',FALSE);
        $query = $this->db->insert('DOCUMENTOSMULTAS');
        return $query;
    }
    
    function addMultaAsignacion($dataAsig){
        $this->db->set('FECHA_ASIGNACION','SYSDATE',FALSE);
        $this->db->set('COMENTARIOS_ASIGNACION','Asignacion Multa ministerio');
        $this->db->insert('ASIGNACIONFISCALIZACION',$dataAsig);
        $this->db->select('COD_ASIGNACIONFISCALIZACION,FECHA_ASIGNACION');
        $this->db->from('ASIGNACIONFISCALIZACION');
        $this->db->where($dataAsig);
        $this->db->order_by('FECHA_ASIGNACION','DESC');
        $query = $this->db->get();
        return $query->row();
    }
    
    function addMultaFiscalizacion($dataFisc){
        $this->db->set('PERIODO_INICIAL','SYSDATE',FALSE);
        $this->db->set('PERIODO_FINAL','SYSDATE',FALSE);
        $this->db->insert('FISCALIZACION',$dataFisc);
        $this->db->select("COD_FISCALIZACION ||' || '|| NRO_EXPEDIENTE AS COD_FISCALIZACION");
        $this->db->from('FISCALIZACION');
        $this->db->where($dataFisc);
        $query = $this->db->get();
        return $query->row();
    }
    
    function consul_MultaFiscalizacion($dataFisc){
        $this->db->select("COD_FISCALIZACION ||' || '|| NRO_EXPEDIENTE AS COD_FISCALIZACION");
        $this->db->from('FISCALIZACION');
        $this->db->where('FISCALIZACION.NRO_EXPEDIENTE',$dataFisc);
        $query = $this->db->get();
        return $query->row();
    }
    
    function addMultaResolucion($dataRes){
        $this->db->set('FECHA_CREACION','SYSDATE',FALSE);
        $this->db->insert('RESOLUCION',$dataRes);
    }
    
    function updateMultaResolucion($numres,$codGestion){
        $this->db->set('COD_ESTADO','51');
        $this->db->set('FECHA_GESTION','SYSDATE',FALSE);
        $this->db->set('COD_GESTION_COBRO',$codGestion);
        $this->db->where('NUMERO_RESOLUCION',$numres);
        $this->db->update('RESOLUCION');
    }
    function guardar_resolucion($name, $multa){
        $this->db->set('OBSERVACIONES',$name);
        $this->db->set('NUMERO_COMUNICACION','1');
        $this->db->where('COD_MULTAMINISTERIO',$multa);
        $this->db->update('MULTASMINISTERIO');
    }
    
    function datos_resolucion($id){
        $this->db->select("REGIONAL.NOMBRE_REGIONAL,REGIONAL.TELEFONO_REGIONAL,EMPRESA.REPRESENTANTE_LEGAL,REGIONAL.COD_REGIONAL,MUNICIPIO.NOMBREMUNICIPIO,"
                . "REGIONAL.DIRECCION_REGIONAL,EMPRESA.DIRECCION,"
                . "RESOLUCION.NUMERO_RESOLUCION,RESOLUCION.FECHA_CREACION,CITACION.FECHA_ENVIO_CITACION,MULTASMINISTERIO.FECHA_EJECUTORIA,"
                . "EMPRESA.NOMBRE_EMPRESA, EMPRESA.CODEMPRESA,USUARIOS.NOMBRES COORDINADOR_REGIONAL,REGIONAL.NOMBRE_REGIONAL");
        $this->db->join("CITACION","CITACION.NUM_CITACION=RESOLUCION.NUMERO_CITACION",'left');
        $this->db->join("MULTASMINISTERIO","MULTASMINISTERIO.NRO_RESOLUCION=RESOLUCION.NUMERO_RESOLUCION",'left');
        $this->db->join("EMPRESA","MULTASMINISTERIO.NIT_EMPRESA=EMPRESA.CODEMPRESA",'left');
        $this->db->join("MUNICIPIO","MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO",FALSE);
        $this->db->join("REGIONAL","REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL",'left');
        $this->db->join('USUARIOS','USUARIOS.IDUSUARIO=REGIONAL.CEDULA_COORDINADOR');
        $this->db->where("RESOLUCION.COD_RESOLUCION",$id);
        $dato = $this->db->get('RESOLUCION');
        return $datos = $dato->result_array[0];
    }
    
    
}