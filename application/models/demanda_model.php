<?php

class Demanda_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function adicionar_demanda($informacion) {
        $this->db->set('COD_TITULO', $informacion['cod_titulo']);
        $this->db->set('COD_CIUDAD', $informacion['cod_ciudad']);
        $this->db->set('COD_DEMANDADO', $informacion['cod_demandado']);
        $this->db->set('COD_RESPUESTA', $informacion['cod_estadodemanda']);
        $this->db->set('NUM_RADI_PROCESO', $informacion['num_radi_proceso']);
        $this->db->set('COD_ABOGADO', $informacion['cod_abogado']);
        $this->db->set('JUZGADO', $informacion['juzgado']);
        $this->db->set('NUM_TP_ABOGADO', $informacion['num_tp_abogado']);
        $this->db->set('NOMBRE_DEMANDADO', $informacion['nombre_demandado']);
        $this->db->set('COD_DEPARTAMENTO', $informacion['cod_departamento']);
        $this->db->insert('DEMANDA');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function adicionar_resultadodemanda($informacion) {
        $this->db->set('COD_DEMANDA', $informacion['cod_demanda']);
        $this->db->set('LIBERAR_MANDAMIENTO', $informacion['liberar_mandamiento']);
        $this->db->set('INADMITIDA', $informacion['inadmitida']);
        $this->db->set('RECHAZO', $informacion['rechazo']);
        $this->db->set('POR_COMPETENCIA', $informacion['por_competencia']);
        $this->db->insert('RESULTADODEMANDA');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_obtenerdemanda($titulo, $estado) {
        $this->db->select("D.COD_DEMANDA");
        $this->db->join("TITULOSJUDICIALES T", "D.COD_TITULO = T.COD_TITULO");
        $this->db->join("RESPUESTAGESTION RG", "D.COD_RESPUESTA = RG.COD_RESPUESTA");
        $this->db->where("D.COD_RESPUESTA", $estado);
        $this->db->where("T.COD_TITULO", $titulo);
        $dato = $this->db->get("DEMANDA D");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function registrar_demandasubsanada($informacion) {
        $this->db->set('COD_DEMANDA', $informacion['cod_demanda']);
        $this->db->set('FECHA_SUBSANACION', "TO_DATE('" . $informacion['fecha_subsanacion'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        $this->db->set('SUBSANADA_POR', $informacion['subsanada_por']);
        $this->db->set('OBSERVACIONES_SUBSANACION', $informacion['observaciones_subsanacion']);
        $this->db->insert('DEMANDASUBSANADA');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function registrar_demandarechazada($informacion) {
        $this->db->set('COD_DEMANDA', $informacion['cod_demanda']);
        $this->db->set('FECHA_RETIRO', "TO_DATE('" . $informacion['fecha_retiro'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        $this->db->set('RETIRADA_POR', $informacion['retirada_por']);
        $this->db->set('OBSERVACIONES_RETIRO', $informacion['observaciones_retiro']);
        $this->db->insert('DEMANDARETIRADA');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function actualizar_demanda($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_DEMANDA", $datos['COD_DEMANDA']);
            unset($datos['COD_DEMANDA']);
            $this->db->update("DEMANDA", $datos);
            if ($this->db->affected_rows() >= 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        endif;
    }

}
