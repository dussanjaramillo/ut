<?php

/**
 * Modelo Consultartitulos. Contiene las consultas que son usadas por el controlador traslado.php
 */
class Prescripcion_model extends CI_Model {
    /*
     * Funcion Para Traer Titulos a los cuales corresponde el fin de proceso
     */

    public function get_prescripcionfdp($cod_coactivo) {//S
        $this->db->select("RP_DET.COD_RECEPCIONTITULO");
        $this->db->join("RESOLUCION_PRESCRIPCION RP", "RP.COD_PRESCRIPCION = RP_DET.COD_PRESCRIPCION");
        $this->db->where('RP.COD_PROCESO_COACTIVO', $cod_coactivo);
        $this->db->where('RP_DET.ESTADO', '0');
        $dato = $this->db->get("RESOLUCION_PRESCRIPCION_DET RP_DET");
        $dato = $dato->result_array();
        return $dato;
    }

    /*
     * Funcion para obtener la informacion de gestion de un codigo de respuesta
     */

    public function get_trazagestion($cod_respuesta) {
        $this->db->select("RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->where("RG.COD_RESPUESTA", $cod_respuesta);
        $dato = $this->db->get("RESPUESTAGESTION RG");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * Correo electronico del deudor
     */

    public function get_correo_autorizacion($cod_fiscalizacion) {
        $this->db->select("NC.NOMBRE_CONTACTO,NC.EMAIL_AUTORIZADO");
        $this->db->where("NC.AUTORIZA", 'S');
        $this->db->where("NC.COD_FISCALIZACION", $cod_fiscalizacion);
        $dato = $this->db->get("AUTORIZACION_NOTI_EMAIL NC");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * OBTENER EXPEDIENTE PARA ANEXAR
     */

    public function get_anexosubido($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("E.RUTA_DOCUMENTO, E.NOMBRE_DOCUMENTO");
            $this->db->where("E.COD_PROCESO_COACTIVO", $cod_coactivo);
            $this->db->where("E.FECHA_DOCUMENTO", "(SELECT MAX(E.FECHA_DOCUMENTO) FROM EXPEDIENTE E WHERE E.COD_PROCESO_COACTIVO = $cod_coactivo )", FALSE);
            $dato = $this->db->get("EXPEDIENTE E");
            $dato = $dato->result_array();
            return $dato[0];
        endif;
    }

    /*
     * OBTENER CODIGO DE FISCALIZACION DEL PROCESO
     */

    public function get_fiscalizacionproceso($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("RT.COD_FISCALIZACION_EMPRESA");
            $this->db->join("ACUMULACION_COACTIVA AC", "AC.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO");
            $this->db->join("RECEPCIONTITULOS RT", "RT.COD_RECEPCIONTITULO = AC.COD_RECEPCIONTITULO");
            $this->db->where("PC.COD_PROCESO_COACTIVO", $cod_coactivo);
            $dato = $this->db->get("PROCESOS_COACTIVOS PC");
            $dato = $dato->result_array();
            return $dato[0];
        endif;
    }

    /*
     * MOTIVOS DE DEVOLUCION DE NOTIFICACION
     */

    public function get_motivosdevolucion() {
        $this->db->select("CAUSALDEVOLUCION.COD_CAUSALDEVOLUCION, CAUSALDEVOLUCION.NOMBRE_CAUSAL");
        $this->db->where('COD_ESTADO', 1);
        $dato = $this->db->get("CAUSALDEVOLUCION");
        return $dato->result_array();
    }

    /*
     * obtener los titulos correspondientes a la acumulacion
     */

    function cabecera($proceso, $respuesta) {
        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        return $resultado;
    }

    /*
     * insertar detalle resolucion prescripcoon
     */

    public function insertar_prescripciondet($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("RESOLUCION_PRESCRIPCION_DET", $datos);
        endif;
    }

    /*
     * insertar resolucion prescripcoon
     */

    public function insertar_prescripcion($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("RESOLUCION_PRESCRIPCION", $datos);
        endif;
    }

    /*
     * obtener informacion del expediente apartir de la respuesta y de la fiscalizacion
     */

    public function get_prescripcion($cod_coactivo) {
        $this->db->select("RP.COD_PRESCRIPCION");
        $this->db->where("RP.COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->where("RP.ESTADO", '1');
        $dato = $this->db->get("RESOLUCION_PRESCRIPCION RP");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * obtener los titulos q se van a trasladar a partir de la identificacion
     */

    public function get_titulostrasladar($identificacion) {
        echo $identificacion;
        $this->db->select("RT.COD_RECEPCIONTITULO");
        $this->db->where("RT.NIT_EMPRESA", $identificacion);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato;
        }
    }

    /*
     * INSERTAR EL ESTADO DEL TITULO DESPUES DE UNA GESTION
     */

    public function insertar_traslado($datos) {
        if (!empty($datos)) :
            $this->db->insert("TRASLADO_JUDICIAL", $datos);
        endif;
    }

    /*
     * ACTUALIZAR EL ESTADO DEL TITULO DESPUES DE UNA GESTION
     */

    public function actualizacion_prescripcion($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            $this->db->where("ESTADO", '1');
            unset($datos['COD_PROCESO_COACTIVO']);
            $this->db->update("RESOLUCION_PRESCRIPCION", $datos);
        endif;
    }

}
