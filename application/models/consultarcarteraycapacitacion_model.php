<?php

// Responsable: Leonardo Molina
class Consultarcarteraycapacitacion_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getDatax($reg, $search, $lenght = 10) {
        $this->db->select('EJ.COD_EJECUTORIA,RS.COD_FISCALIZACION, RS.DETALLE_COBRO_COACTIVO,RV.COD_REVOCATORIA,RS.COD_RESOLUCION,RS.NUMERO_RESOLUCION,RS.COD_CPTO_FISCALIZACION,RS.VALOR_TOTAL,RS.NOMBRE_EMPLEADOR,RS.NITEMPRESA,CF.NOMBRE_CONCEPTO,'
                . 'RG.COD_RESPUESTA,RG.NOMBRE_GESTION,RS.COD_ESTADO AS COD_TIPOGESTION');
        $this->db->from('EJECUTORIA EJ');
        $this->db->join('RESOLUCION RS', "RS.NUMERO_RESOLUCION = EJ.NUM_DOCUMENTO");
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = EJ.TIPO_CARTERA', ' inner');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = EJ.COD_GESTION_COBRO', 'inner');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA', 'inner');
        $this->db->join('REVOCATORIA RV', 'RS.COD_RESOLUCION = RV.COD_RESOLUCION', 'LEFT');
        // $this->db->where('RG.COD_RESPUESTA !=',143);
        $this->db->where('Rs.COD_ESTADO not in (80,419)', "", false);
        $this->db->where('EJ.ESTADO', 0);
        $this->db->where('RS.ABOGADO', ID_USER);
        $this -> db -> or_where('RS.COORDINADOR', ID_USER);
        $this->db->order_by('FECHA_EJECUTORIA', 'desc');
        $this->db->limit($lenght, $reg);
        if ($search) {
            $this->db->like('EJ.COD_EJECUTORIA', $search);
            $this->db->or_like('RS.NITEMPRESA', $search);
            $this->db->or_like('RS.NOMBRE_EMPLEADOR', $search);
            $this->db->or_like('RS.VALOR_TOTAL', $search);
            $this->db->or_like('CF.NOMBRE_CONCEPTO', $search);
            $this->db->or_like('RG.NOMBRE_GESTION', $search);
        }
        $query = $this->db->get();
        // echo $this -> db -> last_query();
        // die();

        return $query->result();
    }

    function totalData($search) {
        $this->db->select('EJ.COD_EJECUTORIA,RS.COD_FISCALIZACION, RS.DETALLE_COBRO_COACTIVO,RV.COD_REVOCATORIA,RS.COD_RESOLUCION,RS.NUMERO_RESOLUCION,RS.COD_CPTO_FISCALIZACION,RS.VALOR_TOTAL,RS.NOMBRE_EMPLEADOR,RS.NITEMPRESA,CF.NOMBRE_CONCEPTO,'
                . 'RG.COD_RESPUESTA,RG.NOMBRE_GESTION,RS.COD_ESTADO AS COD_TIPOGESTION');
        $this->db->from('EJECUTORIA EJ');
        $this->db->join('RESOLUCION RS', "RS.NUMERO_RESOLUCION = EJ.NUM_DOCUMENTO");
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = EJ.TIPO_CARTERA', ' inner');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = EJ.COD_GESTION_COBRO', 'inner');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA', 'inner');
        $this->db->join('REVOCATORIA RV', 'RS.COD_RESOLUCION = RV.COD_RESOLUCION', 'LEFT');
        // $this->db->where('RG.COD_RESPUESTA !=',143);
        $this->db->where('Rs.COD_ESTADO not in (80,419)', "", false);
        $this->db->where('EJ.ESTADO', 0);
        $this->db->where('RS.ABOGADO', ID_USER);
        $this -> db -> or_where('RS.COORDINADOR', ID_USER);
        $this->db->order_by('FECHA_EJECUTORIA', 'desc');

        if ($search) {
            $this->db->like('EJ.COD_EJECUTORIA', $search);
            $this->db->or_like('RS.NITEMPRESA', $search);
            $this->db->or_like('RS.NOMBRE_EMPLEADOR', $search);
            $this->db->or_like('RS.VALOR_TOTAL', $search);
            $this->db->or_like('CF.NOMBRE_CONCEPTO', $search);
            $this->db->or_like('RG.NOMBRE_GESTION', $search);
        }
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
//        echo $query->num_rows();
        return $query->num_rows();
    }

    ////// funciones para traer datos

    function getResolucion($cod) {
        $this->db->select('RS.NUMERO_RESOLUCION,RS.COD_CPTO_FISCALIZACION,RS.FECHA_ACTUAL,TG.TIPOGESTION,TG.COD_GESTION,CF.NOMBRE_CONCEPTO,TG2.TIPOGESTION as CITACION,TG2.COD_GESTION AS CGESTION,RS.NUM_RECURSO,EM.*');
        $this->db->from('RESOLUCION RS');
        $this->db->join('EMPRESA EM', 'EM.CODEMPRESA = RS.NITEMPRESA', ' inner');
        $this->db->join('TIPOGESTION TG', 'TG.COD_GESTION = RS.COD_ESTADO', ' inner');
        $this->db->join('CONCEPTOSFISCALIZACION CF', 'CF.COD_CPTO_FISCALIZACION = RS.COD_CPTO_FISCALIZACION', ' inner');
        $this->db->join('CITACION CT', 'CT.NUM_CITACION = RS.NUMERO_CITACION');
        $this->db->join('TIPOGESTION TG2', 'TG2.COD_GESTION = CT.TIPOGESTION');
        $this->db->where('RS.NUMERO_RESOLUCION', $cod);
        $query = $this->db->get();
        return $query->row();
    }

    function getEjecutoria($numejec) {
        $this->db->select('EJ.*,RS.NITEMPRESA AS NIT_EMPRESA,RS.VALOR_TOTAL, L.SALDO_DEUDA');
        $this->db->from('EJECUTORIA EJ');
        $this->db->join('LIQUIDACION L', 'L.COD_FISCALIZACION = EJ.COD_FISCALIZACION');
        $this->db->join('RESOLUCION RS', 'RS.NUMERO_RESOLUCION = EJ.NUM_DOCUMENTO');
        $this->db->where('L.BLOQUEADA',0);
        $this->db->where('EJ.COD_EJECUTORIA', $numejec);
        $query = $this->db->get();
        return $query->row();
    }

    function getGestionEjecutoria($numejec) {
        $this->db->select('EJ.*,RS.NUMERO_RESOLUCION,RS.NITEMPRESA AS NIT_EMPRESA,RS.VALOR_TOTAL,RG.*');
        $this->db->from('EJECUTORIA EJ');
        $this->db->join('GESTIONCOBRO GC', 'GC.COD_GESTION_COBRO = EJ.COD_GESTION_COBRO');
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA');
        $this->db->join('RESOLUCION RS', 'RS.NUMERO_RESOLUCION = EJ.NUM_DOCUMENTO');
        $this->db->where('EJ.COD_EJECUTORIA', $numejec);
        $query = $this->db->get();
        return $query->row();
    }

    function getVigenciasContrato($nit, $tipoCartera, $resol = NULL) {
        if ($tipoCartera == 3) {// contrato
            $this->db->select('*');
            $this->db->from('SGVA_ESTDO_DE_CUENTA SE');
            $this->db->where('SE.ESTADO_NIT_EMPRESA', $nit);
            $query = $this->db->get();
            return $query->row();
        } else if ($tipoCartera == 1 || $tipoCartera == 2) {// aportes o fic
            $this->db->select('PR.*,(select max(PR2.PERIODO_PAGADO) from PAGOSRECIBIDOS PR2) AS PERIODOMAX', FALSE);
            $this->db->from('PAGOSRECIBIDOS PR');
            $this->db->where('PR.NITEMPRESA', $nit);
            $this->db->order_by('PR.PERIODO_PAGADO', 'asc');
            $query = $this->db->get();
            return $query->result();
        } else if ($tipoCartera == 5) {// multas
            $this->db->select('VALOR,NIT_EMPRESA');
            $this->db->from('MULTASMINISTERIO');
            $this->db->where('NIT_EMPRESA', $nit);
            $this->db->where('NRO_RESOLUCION', $resol);
            $query = $this->db->get();
            return $query->row();
        }
    }

    function updateStateEjecutoria($numejec, $codGestion) {
        $this->db->set("COD_GESTION_COBRO", $codGestion);
        $this->db->where('COD_EJECUTORIA', $numejec);
        $query = $this->db->update('EJECUTORIA');
        return $query;
    }

    function updateEstadoResolucion($res, $estado) {
        $this->db->set("COD_ESTADO", $estado);
        $this->db->where('NUMERO_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION');
        return $query;
    }

    function updateGestionResolucion($res, $codGestion) {
        $this->db->set("COD_GESTION_COBRO", $codGestion);
        $this->db->where('NUMERO_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION');
        return $query;
    }

    function updateGestionResolucion2($res, $codGestion) {
        $this->db->set("COD_ESTADO", $codGestion);
        $this->db->where('NUMERO_RESOLUCION', $res);
        $query = $this->db->update('RESOLUCION');
        return $query;
    }

    function updateEjecutoriaPersuasivo($ejec, $codGestion, $data) {
        $this->db->set("COD_GESTION_COBRO", $codGestion);
        $this->db->set('FECHA_PERSUASIVO', 'SYSDATE', FALSE);
        $this->db->where('COD_EJECUTORIA', $ejec);
        $query = $this->db->update('EJECUTORIA', $data);
        return $query;
    }

    function guardar_Pregunta_revocatoria($post) {
        $this->db->set("COD_ESTADO", $post['cod_siguiente']);
        $this->db->where('NUMERO_RESOLUCION', $post['num_resolucion']);
        $query = $this->db->update('RESOLUCION');
        return $query;
    }

    function guardar_llamadas($post) {
        $this->db->set("HISTORICO_LLAMADAS", "CONCAT(HISTORICO_LLAMADAS,'" . $post['informacion'] . "')", false);
        $this->db->where('COD_EJECUTORIA', $post['id_ejecutoria']);
        $query = $this->db->update('EJECUTORIA');
    }

    function updateliquidacion($cod_fis) {
        $this->db->set("COD_TIPOPROCESO", "2");
        $this->db->where('COD_FISCALIZACION', $cod_fis);
        $query = $this->db->update('LIQUIDACION');
    }

    function historico_llamadas($post) {
        $this->db->select("HISTORICO_LLAMADAS");
        $this->db->where('COD_EJECUTORIA', $post['id_ejecutoria']);
        $query = $this->db->get('EJECUTORIA');
        $query = $query->result_array[0]['HISTORICO_LLAMADAS'];
        $datos = "";
        if (!empty($query)) {
            $query2 = explode("////", $query);
            for ($i = 1; $i < count($query2); $i++) {
                $query3 = explode("///", $query2[$i]);
//                for($i=0;$i<count($query2);$i++){
                $datos .=$query3[0] . "<br>" . $query3[1] . "<hr>";
//                }
            }
        }
        return $datos;
    }

}
