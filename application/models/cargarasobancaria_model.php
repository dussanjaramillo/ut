<?php

/**
 * Modelo 
 */
class Cargarasobancaria_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function Cargar($dates, $numbers, $data, $data2, $data3) {
        if ($dates != '') {
            foreach ($dates as $keyf => $valuef) {
                $this->db->set($keyf, "to_date('" . $valuef . "','dd/mm/yyyy HH24:MI:SS')", false);
            }
        }
        if ($numbers != '') {
            foreach ($numbers as $keyf => $valuef) {
                $this->db->set($keyf, "to_number('" . $valuef . "')", false);
            }
        }

        foreach ($data as $key => $value) {
            $this->db->set($key, $value);
        }

        // COMIENZO DE LA TRANSACCION
        $this->db->trans_begin();

        //INSERTA ENCABEZADO
        $this->db->insert('ASOBANCARIA_ENC');

        //ADQUIRIR COD_ASOBANCARIA
        $query = $this->db->query("SELECT COD_ASO_SEQ.CURRVAL FROM dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];

        //INSERTA DETALLE Y SE EJECUTA EL TRIGGER AFTER INSERT PARA LLENAR PAGOSRECIBIDOS
        $x = 1;
        $ValorTotal = sizeof($data2);
        foreach ($data2 as $value) {
            foreach ($value as $key => $value2) {
                $this->db->set('COD_ASOBANCARIA', $id);
                $this->db->set($key, $value2);
            }
            ob_start();
            $this->db->insert('ASOBANCARIA_DET');
            $porcentaje = $x * 100 / $ValorTotal;
            //echo "<script>javascript:callprogress(" . number_format(round($porcentaje)) . ")</script>";
            ob_flush();
            flush();
            ob_end_flush();
            $x++;
        }

        //INSERTA LOGCARGAASOBANCARIA
        $this->db->set('COD_ASOBANCARIA', $id);
        $this->db->set('CODIGO_ENTIDAD', $numbers['COD_ENTIDAD']);
        $this->db->set('FECHA_CARGUE', "to_date('" . $dates['FECHA_CARGA'] . "','dd/mm/yyyy HH24:MI:SS')", false);
        foreach ($data3 as $key => $value) {
            $this->db->set($key, $value);
        }
        $this->db->insert('LOGCARGAASOBANCARIA');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function vercargados() {
        $this->db->select('LGA.COD_ASOBANCARIA, BAN.NOMBREBANCO AS BANCO, LGA.FECHA_CARGUE, ASO.TOTAL_PLANILLAS, LGA.NRO_FILAS_CARGADAS, CONCAT( "USU"."NOMBRES" , USU.APELLIDOS ) AS USUARIO, LGA.ARCHIVO, ASO.TOTAL_RECAUDADO, ASO.FECHA_RECAUDO ');
        $this->db->from('LOGCARGAASOBANCARIA LGA, BANCO BAN, USUARIOS USU, ASOBANCARIA_ENC ASO');
        $this->db->where('"LGA"."CODIGO_ENTIDAD" = "BAN"."IDBANCO"');
        $this->db->where('"LGA"."USUARIO" = "USU"."IDUSUARIO"');
        $this->db->where('"LGA"."COD_ASOBANCARIA" = "ASO"."COD_ASOBANCARIA"');

        $consulta = $this->db->get();
        return $consulta;
    }

    function detallecarga($id) {
        $this->db->from('ASOBANCARIA_DET');
        $this->db->where('COD_ASOBANCARIA = ' . $id);
        $consulta = $this->db->get();
        return $consulta;
    }

    function verconciliacion($id) {
        $this->db->select(' PAG.NITEMPRESA as NIT, ASO.COD_OPERADOR as OPERADOR, PAG.PERIODO_PAGADO as PERIODO, ASO.NRO_PLANILLA as PLANILLA, PAG.VALOR_PAGADO as VALORPAGO, PAG.CONCILIADO AS ESTADO, ASE.FECHA_RECAUDO as FPAGO, BA.NOMBREBANCO as BANCO');
        $this->db->from(' PAGOSRECIBIDOS PAG, ASOBANCARIA_DET ASO, ASOBANCARIA_ENC ASE, BANCO BA ');
        $this->db->where('"PAG"."COD_PROCEDENCIA" = "ASO"."COD_DETALLE"');
        $this->db->where('"ASO"."COD_ASOBANCARIA" = "ASE"."COD_ASOBANCARIA"');
        $this->db->where('"ASE"."COD_ENTIDAD" = "BA"."IDBANCO"');
        $this->db->where('"PAG"."CONCILIADO" =' . $id);
        $consulta = $this->db->get();
        return $consulta;
    }

    function resumenconciliados() {
        $this->db->select('CONCILIADO AS ESTADO, COUNT(*) AS REGISTROS, SUM(VALOR_PAGADO) AS MONTO');
        $this->db->from(' PAGOSRECIBIDOS ');
        $this->db->group_by('CONCILIADO');
        $this->db->order_by('CONCILIADO');
        $consulta = $this->db->get();
        return $consulta;
    }

    function conciliar() {
        $conciliados = array();
        $this->db->select('ASD.COD_DETALLE, PL.COD_PLANILLAUNICA');
        $this->db->from('PLANILLAUNICA_ENC PL, ASOBANCARIA_DET ASD');
        $this->db->where('ASD.CONCILIADO IS NULL');
        $this->db->where('ASD.COD_APORTANTE = PL.N_INDENT_APORTANTE');
        $this->db->where('ASD.NRO_PLANILLA = PL.N_RADICACION');
        $this->db->where("ASD.PERIODO_PAGO = REPLACE(PL.PERIDO_PAGO, '-')");
        $this->db->where("ASD.COD_OPERADOR = PL.COD_OPERADOR");

        $consulta = $this->db->get();
        
        $date = date('d/m/Y H:i:s');
        foreach ($consulta->result_array as $value) {
            $this->db->set('FECHA_APLICACION', "to_date('" . $date . "' ,'dd/mm/yyyy HH24:MI:SS')", false);
            $this->db->set('CONCILIADO', '1');
            $this->db->set('COD_PLANILLAUNICA', $value['COD_PLANILLAUNICA']);
            $this->db->set('APLICADO', '1');
            $this->db->where('COD_PROCEDENCIA', $value['COD_DETALLE']);
            $this->db->where('CONCILIADO = 0');
            $this->db->update('PAGOSRECIBIDOS');

            $data = array(
                'CONCILIADO' => 1,
            );
            $this->db->where('COD_DETALLE', $value['COD_DETALLE']);
            $this->db->update('ASOBANCARIA_DET', $data);
        }
    }

    function vplanilla($planillas) {
        $con = array();
        $this->db->from('ASOBANCARIA_DET');
        $this->db->where('NRO_PLANILLA = ' . $planillas['numplanillaliq']);
        $this->db->where('COD_OPERADOR = ' . $planillas['codoperadorinf']);
        $this->db->where('PERIODO_PAGO = ' . $planillas['periodopago']);
        $con[0] = $this->db->count_all_results();

        $this->db->from('ASOBANCARIA_DET');
        $this->db->where('NRO_PLANILLA = ' . $planillas['numplanillaliq']);
        $this->db->where('COD_OPERADOR = ' . $planillas['codoperadorinf']);
        $this->db->where('PERIODO_PAGO = ' . $planillas['periodopago']);
        $consulta = $this->db->get();
        foreach ($consulta->result_array as $value) {
            $con[1] = $value['COD_ASOBANCARIA'];
        }
        return $con;
    }

}
