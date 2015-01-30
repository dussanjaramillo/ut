<?php

class Verfpagosprojuridicos_model extends CI_Model {

    private $sSearch;
    private $iDisplayStart;
    private $limit_numrows;
    private $idusuario;
    private $idgrupo;

    function __construct() {
        parent::__construct();
        $this->set_sSearch();
        $this->set_iDisplayStart();
        $this->set_limit_numrows(10);
        $this->set_idusuario();
        $this->set_idgrupo();
    }

    function set_sSearch() {
        return $this->sSearch = $this->input->post('sSearch');
    }

    function set_iDisplayStart() {
        return $this->iDisplayStart = $this->input->post('iDisplayStart');
    }

    function set_limit_numrows($limit_numrows) {
        return $this->limit_numrows = $limit_numrows;
    }

    function set_idusuario() {
        return $this->idusuario = $this->ion_auth->user()->row()->IDUSUARIO;
    }

    function set_idgrupo() {
        $this->set_idusuario();
        $this->idgrupo = $this->ion_auth->get_users_groups($this->idusuario)->result();
        return $this->idgrupo = $this->idgrupo[0]->ID;
    }

    function listAutos($total = false,$proceso=false) {
        $array = array();
        if ($total == false) :
            $this->db->select('AUTOSJURIDICOS.COD_PROCESO_COACTIVO,AUTOSJURIDICOS.NUM_AUTOGENERADO, AUTOSJURIDICOS.COD_TIPO_PROCESO, USUARIO_CREADOR.IDUSUARIO ID_CREADOR, 
												 USUARIO_CREADOR.NOMBREUSUARIO NOMBRE_CREADOR, USUARIO_ASIGNADO.IDUSUARIO ID_ASIGNADO, 
												 USUARIO_ASIGNADO.NOMBREUSUARIO NOMBRE_ASIGNADO, AUTOSJURIDICOS.FECHA_CREACION_AUTO, TIPOGESTION.TIPOGESTION, 
												 RESPUESTAGESTION.NOMBRE_GESTION, PROCESOS_COACTIVOS.ABOGADO AS COD_ABOGADO, PROCESOS_COACTIVOS.IDENTIFICACION AS NIT_EMPRESA,
												 VW_PROCESOS_COACTIVOS.EJECUTADO AS NOMBRE_EMPRESA, PROCESOS_COACTIVOS.COD_PROCESOPJ, VW_PROCESOS_COACTIVOS.CONCEPTO,VW_PROCESOS_COACTIVOS.NOMBRE_REGIONAL');
        elseif ($total == true) :
            $this->db->select('COUNT(AUTOSJURIDICOS.NUM_AUTOGENERADO) numero');
        endif;
        $this->db->from('AUTOSJURIDICOS');
        $this->db->join('PROCESOS_COACTIVOS', 'AUTOSJURIDICOS.COD_PROCESO_COACTIVO = PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS USUARIO_CREADOR', 'USUARIO_CREADOR.IDUSUARIO = AUTOSJURIDICOS.CREADO_POR', 'inner');
        $this->db->join('USUARIOS USUARIO_ASIGNADO', 'USUARIO_ASIGNADO.IDUSUARIO = AUTOSJURIDICOS.ASIGNADO_A', 'inner');
        $gestion = "TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL = AUTOSJURIDICOS.COD_GESTIONCOBRO";
        $this->db->join('TRAZAPROCJUDICIAL', $gestion);
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = RESPUESTAGESTION.COD_TIPOGESTION', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS', 'AUTOSJURIDICOS.COD_PROCESO_COACTIVO = VW_PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO '
                . ' AND VW_PROCESOS_COACTIVOS.COD_RESPUESTA =PROCESOS_COACTIVOS.COD_RESPUESTA', 'inner');
        $this->db->where('AUTOSJURIDICOS.COD_TIPO_PROCESO', 1);
        $this->db->where('AUTOSJURIDICOS.COD_TIPO_AUTO', 1);
        if(!empty($proceso)):
         $this->db->where('AUTOSJURIDICOS.COD_PROCESO_COACTIVO',$proceso);  
        endif;
        

//        if ($this->session->userdata['id_secretario'] == $this->idusuario) :
//            $this->db->where('AUTOSJURIDICOS.ASIGNADO_A', $this->idusuario);
//        elseif ($this->session->userdata['id_coordinador'] == $this->idusuario) :
//            $this->db->where('AUTOSJURIDICOS.ASIGNADO_A', $this->idusuario);
//        else :
//            $this->db->where('PROCESOS_COACTIVOS.ABOGADO', $this->idusuario);
//        endif;

        if (trim($this->sSearch) != '') {
         //   $this->db->where('(UPPER(PROCESOS_COACTIVOS.COD_PROCESOPJ)	LIKE \'%' . mb_strtoupper($this->sSearch) . '%\' OR 
	 //											 UPPER(USUARIO_CREADOR.NOMBREUSUARIO)			LIKE \'%' . mb_strtoupper($this->sSearch) . '%\' OR 
	//											 UPPER(USUARIO_ASIGNADO.NOMBREUSUARIO)		LIKE \'%' . mb_strtoupper($this->sSearch) . '%\' OR 
	//											 UPPER(AUTOSJURIDICOS.NUM_AUTOGENERADO)		LIKE \'%' . mb_strtoupper($this->sSearch) . '%\')', NULL, FALSE);
        }
        if ($total == false) :
            $this->db->limit($this->limit_numrows, $this->iDisplayStart);
        endif;
        $query = $this->db->get();
      // echo $this->db->last_query();die();
//        exit();
        $numero = 0;
//        if ($query->num_rows() > 0) {
//            $array = $query->result();
//            if ($total == true)
//                $numero = $array[0]->NUMERO;
//        }
//        if ($total == true)
//            return $numero;
//        else
//            return $array;
         $resultado = $query->result_array();
         return $resultado;
    }

    function retrievetAuto($num_autogenerado) {
        $array = array();
        $this->db->select('AUTOSJURIDICOS.NUM_AUTOGENERADO, AUTOSJURIDICOS.COD_TIPO_AUTO, AUTOSJURIDICOS.COD_FISCALIZACION, 
											 AUTOSJURIDICOS.COD_ESTADOAUTO, AUTOSJURIDICOS.COD_TIPO_PROCESO, AUTOSJURIDICOS.CREADO_POR, 
											 AUTOSJURIDICOS.ASIGNADO_A, AUTOSJURIDICOS.FECHA_CREACION_AUTO, AUTOSJURIDICOS.COMENTARIOS, 
											 AUTOSJURIDICOS.REVISADO, AUTOSJURIDICOS.REVISADO_POR, AUTOSJURIDICOS.APROBADO, 
											 AUTOSJURIDICOS.APROBADO_POR, AUTOSJURIDICOS.NOMBRE_DOC_GENERADO, AUTOSJURIDICOS.NOMBRE_DOC_FIRMADO, 
											 AUTOSJURIDICOS.FECHA_SOLICITUD_PRUEBAS, AUTOSJURIDICOS.TRASLADO_ALEGATO, AUTOSJURIDICOS.DIRECTOR_REGIONAL,
											 AUTOSJURIDICOS.COD_NULIDAD, AUTOSJURIDICOS.RADICADO_ONBASE, AUTOSJURIDICOS.COD_PROCESO_COACTIVO, 
											 USUARIO_CREADOR.IDUSUARIO ID_CREADOR, USUARIO_CREADOR.NOMBREUSUARIO NOMBRE_CREADOR, 
											 USUARIO_ASIGNADO.IDUSUARIO ID_ASIGNADO, USUARIO_ASIGNADO.NOMBREUSUARIO NOMBRE_ASIGNADO, 
											 TIPOGESTION.COD_GESTION, RESPUESTAGESTION.COD_RESPUESTA, TIPOGESTION.TIPOGESTION, 
											 RESPUESTAGESTION.NOMBRE_GESTION, AUTOSJURIDICOS.COD_GESTIONCOBRO, VW_PROCESOS_COACTIVOS.ABOGADO AS COD_ABOGADO, 
											 USUARIO_ABOGADO.NOMBREUSUARIO AS NOMBRE_ABOGADO, PROCESOS_COACTIVOS.IDENTIFICACION AS NIT_EMPRESA,
											 VW_PROCESOS_COACTIVOS.EJECUTADO AS RAZON_SOCIAL, VW_PROCESOS_COACTIVOS.CONCEPTO AS NOMBRE_CONCEPTO, 
											 VW_PROCESOS_COACTIVOS.REPRESENTANTE AS REPRESENTANTE_LEGAL, VW_PROCESOS_COACTIVOS.TELEFONO AS TELEFONO_FIJO');
        $this->db->from('AUTOSJURIDICOS');
        $this->db->join('PROCESOS_COACTIVOS', 'PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO=AUTOSJURIDICOS.COD_PROCESO_COACTIVO', 'inner');
        $this->db->join('USUARIOS USUARIO_ABOGADO', 'USUARIO_ABOGADO.IDUSUARIO = PROCESOS_COACTIVOS.ABOGADO', 'inner');
        $this->db->join('USUARIOS USUARIO_CREADOR', 'USUARIO_CREADOR.IDUSUARIO = AUTOSJURIDICOS.CREADO_POR', 'inner');
        $this->db->join('USUARIOS USUARIO_ASIGNADO', 'USUARIO_ASIGNADO.IDUSUARIO = AUTOSJURIDICOS.ASIGNADO_A', 'inner');
        $gestion = "TRAZAPROCJUDICIAL.COD_TRAZAPROCJUDICIAL = AUTOSJURIDICOS.COD_GESTIONCOBRO ";
        $this->db->join('TRAZAPROCJUDICIAL', $gestion, 'inner');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = TRAZAPROCJUDICIAL.COD_TIPOGESTION', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA = TRAZAPROCJUDICIAL.COD_TIPO_RESPUESTA', 'inner');
        $this->db->join('VW_PROCESOS_COACTIVOS', 'AUTOSJURIDICOS.COD_PROCESO_COACTIVO = VW_PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO '
                . 'AND VW_PROCESOS_COACTIVOS.COD_RESPUESTA=PROCESOS_COACTIVOS.COD_RESPUESTA');
        $this->db->where('AUTOSJURIDICOS.NUM_AUTOGENERADO', $num_autogenerado);
       // $this->db->where('AUTOSJURIDICOS.ASIGNADO_A', $this->idusuario);
//1033700504	1033700504
        $query = $this->db->get(); //echo $this->db->last_query();die();

        if ($query->num_rows() > 0) {
            $array = $query->row();
        }

        return $array;
    }

    function retriveFiscalizacion($cod_fiscalizacion) {
        $array = array();
        $this->db->select('FIS.COD_FISCALIZACION, FIS.PERIODO_INICIAL, FIS.PERIODO_FINAL, FIS.COD_ABOGADO, FIS.COD_CONCEPTO, 
											 FIS.COD_TIPOGESTION, ASIG_FIS.FECHA_ASIGNACION, ASIG_FIS.ASIGNADO_A, ASIG_FIS.NIT_EMPRESA, 
											 ASIG_FIS.ASIGNADO_A, ASIG_FIS.COMENTARIOS_ASIGNACION, EMP.CODEMPRESA, EMP.NOMBRE_EMPRESA, EMP.DIRECCION, 
											 EMP.TELEFONO_FIJO, EMP.TELEFONO_CELULAR, EMP.REPRESENTANTE_LEGAL, EMP.RAZON_SOCIAL, 
											 CONS_FIS.COD_CPTO_FISCALIZACION, CONS_FIS.NOMBRE_CONCEPTO, REG.NOMBRE_REGIONAL, FIS.COD_GESTIONACTUAL, 
											 REG.DIRECCION_REGIONAL, TO_CHAR(SYSDATE, \'DD\') DIA, TO_CHAR(SYSDATE, \'MM\') MES, 
											 TO_CHAR(SYSDATE, \'YYYY\') ANO ', FALSE);
        $this->db->from('FISCALIZACION FIS');
        $this->db->join('ASIGNACIONFISCALIZACION ASIG_FIS', 'FIS.COD_ASIGNACION_FISC=ASIG_FIS.COD_ASIGNACIONFISCALIZACION', 'inner');
        $this->db->join('EMPRESA EMP', 'EMP.CODEMPRESA = ASIG_FIS.NIT_EMPRESA', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION CONS_FIS', 'CONS_FIS.COD_CPTO_FISCALIZACION = FIS.COD_CONCEPTO', 'inner');
        $this->db->join('USUARIOS USUA_ASIG', 'USUA_ASIG.IDUSUARIO = ASIG_FIS.ASIGNADO_A', 'inner');
        $this->db->join('REGIONAL REG', 'REG.COD_REGIONAL = USUA_ASIG.COD_REGIONAL', 'LEFT');
        $this->db->where("FIS.COD_FISCALIZACION", $cod_fiscalizacion);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->row();
        }

        return $array;
    }

    function listSecretarios($cod_regional = '', $cod_grupo = '41') {
        $array = array();

        $this->db->select('USUARIOS.IDUSUARIO, USUARIOS.NOMBREUSUARIO');
        $this->db->from('USUARIOS');
        $this->db->join('USUARIOS_GRUPOS', 'USUARIOS.IDUSUARIO = USUARIOS_GRUPOS.IDUSUARIO', 'inner');
        $this->db->where('USUARIOS_GRUPOS.IDGRUPO', $cod_grupo);
        if ($cod_regional != '')
            $this->db->where('USUARIOS.COD_REGIONAL', $cod_regional);

        $this->db->order_by('USUARIOS.NOMBREUSUARIO');

        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }

        return $array;
    }

    function retrieveAllPlantillas() {
        $array = array();
        $this->db->select('PLANTILLA.CODPLANTILLA, PLANTILLA.NOMBRE_PLANTILLA, PLANTILLA.TEXTO, PLANTILLA.ACTIVO, 
											 PLANTILLA.TIPO_PLANTILLA');
        $this->db->from('PLANTILLA');
        $this->db->order_by('NOMBRE_PLANTILLA');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $array = $query->result();
        }

        return $array;
    }

    function retrievePlantilla($codplantilla) {
        $this->db->select('PLANTILLA.CODPLANTILLA, PLANTILLA.NOMBRE_PLANTILLA, PLANTILLA.TEXTO, PLANTILLA.ACTIVO, 
											 PLANTILLA.TIPO_PLANTILLA, PLANTILLA.ARCHIVO_PLANTILLA');
        $this->db->from('PLANTILLA');
        $this->db->where('PLANTILLA.CODPLANTILLA', $codplantilla);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
            $array = $query->row();

        return $array;
    }

    function saveAuto($data = array(), $num_autogenerado, $cod_fiscalizacion) {
        if (!empty($data)) :
            $this->db->where("NUM_AUTOGENERADO", $num_autogenerado);
            $this->db->where("COD_PROCESO_COACTIVO", $cod_fiscalizacion);
            $this->db->set("FECHA_GESTION", "SYSDATE", FALSE);
            unset($data['FECHA_GESTION']);
            if (!empty($data['FECHA_DOC_FIRMADO'])) :
                $this->db->set("FECHA_DOC_FIRMADO", "TO_DATE('" . $data['FECHA_DOC_FIRMADO'] . "', 'YYYY/MM/DD')", FALSE);
                unset($data['FECHA_DOC_FIRMADO']);
            endif;
            $this->db->update("AUTOSJURIDICOS", $data);
           // echo $this->db->last_query();die();
            return true;
        else :
            return false;
        endif;
    }
       function proceso($cod_coactivo){
        $this->db->select('PC.COD_PROCESOPJ');
        $this->db->from('PROCESOS_COACTIVOS PC');
        $this->db->where('PC.COD_PROCESO_COACTIVO',$cod_coactivo);
        $resultado=$this->db->get('');
        $resultado=$resultado->result_array();
        return $resultado[0];
        
    }
    function levantarMedidas($post)
    {
        $this->db->set('COD_GESTIONCOBRO',$post['cod_gestioncobro']);
        $this->db->set('NOMBRE_DOC_GENERADO',FALSE);
        $this->db->where('COD_PROCESO_COACTIVO',$post['cod_proceso']);
        $this->db->update('AUTOSJURIDICOS');
        
    }
      function medidasCautelares($post)
    {
        $this->db->set('COD_GESTIONCOBRO',$post['cod_gestioncobro']);
        $this->db->where('COD_PROCESO_COACTIVO',$post['cod_proceso']);
        $this->db->update('AUTOSJURIDICOS');
        
    }
    
    
    function cerrarProceso($post)
    {
        $this->db->set('AUTO_CIERRE',1);
        $this->db->where('COD_PROCESO_COACTIVO',$post['cod_proceso']);
        $this->db->update('PROCESOS_COACTIVOS');
        return TRUE;
        
    }

}

?>