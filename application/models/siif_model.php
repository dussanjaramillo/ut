<?php

class Siif_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function cuentassiif() {
        $this->db->select('MA.NUMERO_CUENTA AS CUENTA, BA.NOMBREBANCO, MA.DESCRIPCION, MA.FECHA_CREACION');
        $this->db->from('MAESTRO_CUENTAS_BANCARIAS MA, BANCO BA');
        $this->db->where('MA.IDBANCO = BA.IDBANCO');
        $this->db->where('MA.ACTIVO = 1');
        $consulta = $this->db->get();
        return $consulta;
    }

    function traerpagos($fecha, $conceptos, $reciprocas) {
        if ($reciprocas == '1') {
            $adicional .= " AND EXISTS(SELECT '1' FROM Empresa WHERE CodEmpresa = NitEmpresa AND Reciproca = 1 )";
        }else{
            $adicional .= '';
        }

        $fecha2 = "to_date('" . $fecha . "','dd/mm/yyyy')";
        $where = '';
        //$where = 'FECHA_PAGO = ' . $fecha2;
        if (sizeof($conceptos) > 1) {
            foreach ($conceptos as $key => $value) {
                if ($key = 0) {
                    $where2 = ' (PROCEDENCIA = ' . $value['procedencia'] . ' AND COD_SUBCONCEPTO = ' . $value['procedencia'] . ' ) ';
                } else {
                    $where2 .= ' OR (PROCEDENCIA = ' . $value['procedencia'] . ' AND COD_SUBCONCEPTO = ' . $value['procedencia'] . ' ) ';
                }
            }
            $where = 'FECHA_PAGO = ' . $fecha2 . ' AND (' . $where2 . ') AND CONCILIADO = 1 '.$adicional;
        } else {
            $where = 'FECHA_PAGO = ' . $fecha2 . " AND (PROCEDENCIA = '" . $conceptos[0]['procedencia'] . "' AND COD_SUBCONCEPTO = " . $conceptos[0]['concepto'] . ") AND CONCILIADO = 1 ".$adicional;
        }
        $this->db->where($where);
        $consultas = $this->db->get('PAGOSRECIBIDOS');
        return $consultas;
    }

    function tipoidentificacion($nit = "") {
        $tiponit = '';
        $this->db->where("N_INDENT_APORTANTE = '" . $nit . "'");
        $consultas = $this->db->get('PLANILLAUNICA_ENC');
        foreach ($consultas->result_array as $consulta) {
            $tiponit = $consulta['TIPO_DOC_APORTANTE'];
        }
        if ($tiponit == 'NI')
            return '01';
        else
            return '03';
    }

    function bancos() {
        $this->db->select('BA.IDBANCO, BA.NOMBREBANCO');
        $this->db->from('BANCO BA');
        $this->db->where('BA.IDESTADO', '1');
        $this->db->order_by('BA.NOMBREBANCO', 'asc');
        $consultas = $this->db->get();
        return $consultas;
    }

    function adicionacuenta($data) {
        if (is_array($data)) {
            $c = $this->db->insert('MAESTRO_CUENTAS_BANCARIAS', $data);
            if ($c)
                return true;
            else
                return false;
        }else {
            return false;
        }
    }

    function conceptos() {
        $this->db->select('CR.COD_CONCEPTO_RECAUDO, TC.NOMBRE_TIPO, CR.NOMBRE_CONCEPTO');
        $this->db->from('TIPOCONCEPTO tc, CONCEPTORECAUDO cr');
        $this->db->where('CR.CODTIPOCONCEPTO = TC.COD_TIPOCONCEPTO');
        $consulta = $this->db->get();
        return $consulta;
    }

    function traeconceptoscuenta($id) {
        $this->db->select('TC.NOMBRE_TIPO, CR.NOMBRE_CONCEPTO, MCC.FECHA_CREACION, MCC.COD_CONCEPTO_RECAUDO, MCC.PROCEDENCIA');
        $this->db->from('TIPOCONCEPTO TC, CONCEPTORECAUDO CR, MAESTRO_CUENTAS_CONCEPTOS MCC');
        $this->db->where('MCC.NUMERO_CUENTA', $id);
        $this->db->where('CR.CODTIPOCONCEPTO = TC.COD_TIPOCONCEPTO');
        $this->db->where('MCC.COD_CONCEPTO_RECAUDO = CR.COD_CONCEPTO_RECAUDO');
        $consulta = $this->db->get();
        return $consulta;
    }

    function conceptosxcuenta($cuenta) {
        $this->db->from('MAESTRO_CUENTAS_CONCEPTOS MCC');
        $this->db->where('MCC.NUMERO_CUENTA', $cuenta);
        $consulta = $this->db->get();
        return $consulta;
    }

    function codsiif() {
        $consulta = $this->db->get('CONCEPTORECAUDO');
        return $consulta;
    }

}
