<?php

class Expediente_model extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function proceso_coactivo() {
        $this->db->select('PC.COD_PROCESOPJ AS COD_PROCESO,VW.IDENTIFICACION,VW.EJECUTADO,VW.NOMBRE_REGIONAL,E.SUB_PROCESO AS NOMBRE_PROCESO,VW.RESPUESTA');
        $this->db->from('EXPEDIENTE E');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=E.COD_PROCESO_COACTIVO');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=VW.COD_PROCESO_COACTIVO');
        $this->db->where('VW.COD_RESPUESTA', 'E.COD_RESPUESTAGESTION');
        $this->db->get('');
        $resultado = $this->db->last_query();
        return $resultado;
    }

    function documentos($cod_proceso) {
        $where = 'VW.COD_RESPUESTA = E.COD_RESPUESTAGESTION';
        $this->db->select('PC.COD_PROCESOPJ AS COD_PROCESO,VW.IDENTIFICACION,VW.EJECUTADO,VW.NOMBRE_REGIONAL,E.SUB_PROCESO AS NOMBRE_PROCESO,'
                . 'VW.RESPUESTA, E.NUMERO_RADICADO,E.FECHA_RADICADO,E.NOMBRE_DOCUMENTO,E.FECHA_DOCUMENTO,E.RUTA_DOCUMENTO,VW.TELEFONO,VW.DIRECCION,'
                . 'US.NOMBRES,US.APELLIDOS,E.FECHA_NOTIFICACION_EFECTIVA, E.NUMERO_RESOLUCION,E.FECHA_RESOLUCION');
        $this->db->from('EXPEDIENTE E');
        $this->db->join('VW_PROCESOS_COACTIVOS VW', 'VW.COD_PROCESO_COACTIVO=E.COD_PROCESO_COACTIVO');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO=VW.COD_PROCESO_COACTIVO');
        $this->db->join('USUARIOS US', 'US.IDUSUARIO=E.ID_USUARIO');
        $this->db->where('E.COD_PROCESO_COACTIVO', $cod_proceso);
        $this->db->where($where);
        $resultado = $this->db->get('');
//       echo $resultado = $this->db->last_query();
//       die();
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function consulta_procesos($reg, $search, $regional) {
        $this->load->library('datatables');
        $this->db->select('RECEPCIONTITULOS.COD_RECEPCIONTITULO,RECEPCIONTITULOS.FECHA_CONSULTAONBASE,'
                . 'REGIONAL.NOMBRE_REGIONAL, FISCALIZACION.CODIGO_PJ,RECEPCIONTITULOS.NIT_EMPRESA, '
                . 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA, EMPRESA.COD_REGIONAL, FISCALIZACION.COD_ASIGNACION_FISC,'
                . 'EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, FISCALIZACION.COD_CONCEPTO,'
                . 'CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,ASIGNACIONFISCALIZACION.ASIGNADO_A,'
                . 'EMPRESA.REPRESENTANTE_LEGAL, GESTIONCOBRO.COD_GESTION_COBRO, RESPUESTAGESTION.NOMBRE_GESTION,'
                . 'RESPUESTAGESTION.COD_TIPOGESTION,TIPOGESTION.CODPROCESO,TIPOPROCESO.TIPO_PROCESO ');
        $this->db->join('EMPRESA', 'RECEPCIONTITULOS.NIT_EMPRESA=EMPRESA.CODEMPRESA ', 'inner');
        $this->db->join('FISCALIZACION ', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('ASIGNACIONFISCALIZACION ', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC', 'inner');
        $this->db->join('REGIONAL ', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_GESTION_COBRO=FISCALIZACION.COD_GESTIONACTUAL', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=GESTIONCOBRO.COD_TIPO_RESPUESTA', 'inner');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION=RESPUESTAGESTION.COD_TIPOGESTION', 'inner');
        $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO=TIPOGESTION.CODPROCESO', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        // $this->db->where('REGIONAL.COD_REGIONAL', $regional, FALSE);
        $this->db->where('(RECEPCIONTITULOS.COD_TIPORESPUESTA <> 1114 AND  RECEPCIONTITULOS.COD_TIPORESPUESTA <> 1112)');
        //$this->db->where('RECEPCIONTITULOS.COD_TIPORESPUESTA', '173');
        //  $this->db->where('FISCALIZACION.COD_ABOGADO', $id_abogado);
        $this->db->order_by('RECEPCIONTITULOS.COD_RECEPCIONTITULO', 'ASC');
        $dato = $this->db->get('RECEPCIONTITULOS');

        if ($dato->num_rows() == 0)
            return '0';
        return $dato->result_array();
    }

    function lista_procesos($reg, $search, $regional) {
        
    }

    function guarda_expediente3($cod_fiscalizacion = FALSE, $cod_respuesta, $nom_doc, $ruta_doc, $num_radicado = FALSE, $fecha_radicado = FALSE, $cod_tipoexp, $subproceso, $id_usuario) {
        $this->db->set("COD_FISCALIZACION", $cod_fiscalizacion);
        $this->db->set("COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->set("COD_RESPUESTAGESTION", $cod_respuesta);
        $this->db->set("NOMBRE_DOCUMENTO", $nom_doc);
        $this->db->set("RUTA_DOCUMENTO", $ruta_doc);
        $this->db->set("FECHA_DOCUMENTO", 'SYSDATE', FALSE);
        if (!empty($num_radicado))
            $this->db->set("NUMERO_RADICADO", $num_radicado);
        if (!empty($fecha_radicado))
            $this->db->set('FECHA_RADICADO', "TO_DATE('" . $fecha_radicado . "','yyyy/mm/dd')", FALSE);
        $this->db->set("COD_TIPO_EXPEDIENTE", $cod_tipoexp);
        $this->db->set("SUB_PROCESO", $subproceso);
        $this->db->set("ID_USUARIO", $id_usuario);
        $this->db->insert("EXPEDIENTE");
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function agrega_expediente($cod_respuesta, $nom_doc, $ruta_doc, $num_radicado = FALSE, $fecha_radicado = FALSE, $cod_tipoexp, $subproceso, $id_usuario, $cod_coactivo) {

        $this->db->set("COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->set("COD_RESPUESTAGESTION", $cod_respuesta);
        $this->db->set("NOMBRE_DOCUMENTO", $nom_doc);
        $this->db->set("RUTA_DOCUMENTO", $ruta_doc);
        $this->db->set("FECHA_DOCUMENTO", 'SYSDATE', FALSE);
        if (!empty($num_radicado))
            $this->db->set("NUMERO_RADICADO", $num_radicado);
        if (!empty($fecha_radicado))
            $this->db->set('FECHA_RADICADO', "TO_DATE('" . $fecha_radicado . "','yyyy/mm/dd')", FALSE);
        $this->db->set("COD_TIPO_EXPEDIENTE", $cod_tipoexp);
        $this->db->set("SUB_PROCESO", $subproceso);
        $this->db->set("ID_USUARIO", $id_usuario);
        $this->db->insert("EXPEDIENTE");
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function consulta_expediente($reg, $search, $regional, $id_fiscalizacion) {
        $this->db->select('EXPEDIENTE.COD_RESPUESTAGESTION,EXPEDIENTE.NOMBRE_DOCUMENTO,EXPEDIENTE.FECHA_DOCUMENTO, EXPEDIENTE.RUTA_DOCUMENTO, EXPEDIENTE.NUMERO_RADICADO,'
                . ' EXPEDIENTE.FECHA_RADICADO,EXPEDIENTE.COD_TIPO_EXPEDIENTE, EXPEDIENTE.ID_USUARIO, USUARIOS.NOMBRES, USUARIOS.APELLIDOS,'
                . ' RESPUESTAGESTION.NOMBRE_GESTION, RESPUESTAGESTION.COD_TIPOGESTION,TIPOGESTION.CODPROCESO,TIPOPROCESO.TIPO_PROCESO,EXPEDIENTE.FECHA_NOTIFICACION_EFECTIVA,'
                . ' EXPEDIENTE.NUMERO_RESOLUCION,EXPEDIENTE.FECHA_RESOLUCION');
        $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=EXPEDIENTE.ID_USUARIO', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=EXPEDIENTE.COD_RESPUESTAGESTION', 'inner');
        $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION=RESPUESTAGESTION.COD_TIPOGESTION', 'inner');
        $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO=TIPOGESTION.CODPROCESO', 'inner');
        $this->db->where('EXPEDIENTE.COD_FISCALIZACION', $id_fiscalizacion);
        $this->db->order_by('EXPEDIENTE.ID_EXPEDIENTE', 'ASC');
        $dato = $this->db->get('EXPEDIENTE');
        if ($dato->num_rows() == 0)
            return '0';
        return $dato->result_array();
    }

    function consulta_expediente2() {
        
    }

    function empresa_expediente($codempresa) {
        $this->db->select("EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, EMPRESA.TELEFONO_FIJO, EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.DIRECCION");
        $this->db->where('EMPRESA.CODEMPRESA', $codempresa);
        $dato = $this->db->get("EMPRESA");
        return $dato->result_array();
    }
    
     function cabecera($cod_proceso) {

        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', 180);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $cod_proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }

}
