<?php

/**
 * Modelo Consultartitulos. Contiene las consultas que son usadas por el controlador traslado.php
 */
class Traslado_model extends CI_Model {
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
     * Funcion para obtener los datos para la tabla de la gestion de usuarios y obtener los datos del encabezado dependiendo si recibe fiscalizacion
     */

    public function get_procesotransversal($usuario, $cod_fiscalizacion = NULL) {
        $linea_1 = '';
        $linea_2 = '';
        $linea_3 = '';
        if ($cod_fiscalizacion != NULL) {
            $linea_1 = "AND F.COD_FISCALIZACION = '$cod_fiscalizacion'";
        } else {
            if ($usuario) {
                $linea_2 = "AND F.COD_ABOGADO = '" . COD_USUARIO . "' ";
            } else {
                $linea_2 = "AND TL.COD_FISCALIZACION = F.COD_FISCALIZACION";
                $linea_3 = "TRASLADO_JUDICIAL TL, ";
            }
        }
        $dato = $this->db->query("SELECT DISTINCT RG.COD_RESPUESTA, RG.IDGRUPO, RG.URLGESTION, P.COD_TIPO_PROCESO, P.TIPO_PROCESO PROCESO, E.CODEMPRESA, E.NOMBRE_EMPRESA,E.RAZON_SOCIAL, F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION RESPUESTA, CF.NOMBRE_CONCEPTO, E.DIRECCION
                                    FROM $linea_3 TIPOPROCESO P, TIPOGESTION TG, ASIGNACIONFISCALIZACION AF, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, EMPRESA E, FISCALIZACION F,(
                                    SELECT A.* FROM GESTIONCOBRO A,(
                                    SELECT COD_FISCALIZACION_EMPRESA, COUNT(*), MAX(COD_GESTION_COBRO) AS COD_GESTION_COBRO
                                    FROM GESTIONCOBRO 
                                    GROUP BY COD_FISCALIZACION_EMPRESA
                                    ORDER BY COUNT(*) DESC) B
                                    WHERE A.COD_GESTION_COBRO = B.COD_GESTION_COBRO) H
                                    WHERE H.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION
                                    AND F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION
                                    AND AF.NIT_EMPRESA = E.CODEMPRESA
                                    AND CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO
                                    AND H.COD_TIPOGESTION = TG.COD_GESTION
                                    AND H.COD_TIPO_RESPUESTA = RG.COD_RESPUESTA
                                    AND TG.CODPROCESO = P.COD_TIPO_PROCESO
                                    $linea_2
                                    AND E.COD_REGIONAL =  '" . COD_REGIONAL . "' 
                                    AND NVL(F.CODIGO_PJ, '***') <> '***'
                                    AND P.AREA = 'J'
                                    $linea_1");
        return $dato->result();
    }

    /*
     * insertar a regimen de insovencia -- realizar traslado 
     */

    public function insertar_regimeninsolvencia($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("RI_REGIMENINSOLVENCIA", $datos);
        endif;
    }

    /*
     * obtener informacion del expediente apartir de la respuesta y de la fiscalizacion
     */

    public function get_traslado($cod_coactivo) {
        $this->db->select("TJ.COD_TRASLADO");
        $this->db->where("TJ.COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->where("TJ.ESTADO", 'S');
        $dato = $this->db->get("TRASLADO_JUDICIAL TJ");
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

    public function actualizacion_traslado($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            $this->db->where("ESTADO", 'S');
            unset($datos['COD_PROCESO_COACTIVO']);
            $this->db->update("TRASLADO_JUDICIAL", $datos);
        endif;
    }    
	/*
     * ACTUALIZAR EN PROCESOS COACTIVOS CUANDO YA SE HAYA INSERTADO EN TRASLADO
     */

    public function actualizacion_coactivo($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            unset($datos['COD_PROCESO_COACTIVO']);
            $this->db->update("PROCESOS_COACTIVOS", $datos);
        endif;
    }

}
