<?php

/**
 * Modelo Consultartitulos. Contiene las consultas que son usadas por el controlador traslado.php
 */
class Documentospj_model extends CI_Model {
    /*
     * Funcion para obtener los datos de una fiscalizacion
     */

    public function get_titulotrazar($cod_fiscalizacion) {
        $this->db->select("AF.NIT_EMPRESA, TJ.COD_FISCALIZACION,TG.COD_GESTION, RG.COD_RESPUESTA");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = TJ.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = TJ.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION");
        $this->db->where("TJ.COD_FISCALIZACION", $cod_fiscalizacion);
        $this->db->where("TJ.ESTADO", 'S');
        $dato = $this->db->get("TRASLADO_JUDICIAL TJ");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
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
     * Obtener el numero de proceso judicial deacuerdo a la fiscalizacion
     */

    public function get_numprocesoadjudicado($cod_coactivo) {
        $this->db->select("PC.COD_PROCESOPJ");
        $this->db->where("PC.COD_PROCESO_COACTIVO", $cod_coactivo);
        $dato = $this->db->get("PROCESOS_COACTIVOS PC");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * obtener el secretario de la regional en la que se encuentra 
     */

    public function get_secretario_regional() {
        $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
        $this->db->join("REGIONAL R", "R.CEDULA_SECRETARIO= U.IDUSUARIO");
        $this->db->where("R.COD_REGIONAL", COD_REGIONAL);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    /*
     * insertar el archivo txt a la aplicacion 
     */

    public function insertar_comunicacion($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("COMUNICADOS_PJ", $datos);
        endif;
    }

    /*
     * insertar un archivo al expediente
     */

    public function insertar_expediente($datos = NULL) {
        if (!empty($datos)) :
            if (isset($datos['FECHA_RADICADO'])) {
                $this->db->set('FECHA_RADICADO', "TO_DATE('" . $datos['FECHA_RADICADO'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
                unset($datos['FECHA_RADICADO']);
            }
            $this->db->insert("EXPEDIENTE", $datos);
        endif;
    }

    /*
     * obtener informacion del expediente apartir de la respuesta y de la fiscalizacion
     */

    public function get_documentosexpediente($comprobar) {
        $this->db->select("E.NOMBRE_DOCUMENTO, E.RUTA_DOCUMENTO, E.FECHA_RADICADO, E.NUMERO_RADICADO, E.COD_RESPUESTAGESTION, E.COD_FISCALIZACION, E.ID_EXPEDIENTE");
        $this->db->where('E.COD_PROCESO_COACTIVO', $comprobar[0]);
        $this->db->where('E.COD_RESPUESTAGESTION', $comprobar[1]);
        $this->db->group_by("E.NOMBRE_DOCUMENTO, E.RUTA_DOCUMENTO, E.FECHA_RADICADO, E.NUMERO_RADICADO, E.COD_RESPUESTAGESTION, E.COD_FISCALIZACION, E.ID_EXPEDIENTE");
        $dato = $this->db->get("EXPEDIENTE E");
        $dato = $dato->result_array();
        return $dato;
    }

    /*
     * Eliminar Soporte del expediente
     */

    public function eliminar_soporte($cod_soporte) {
        if (!empty($cod_soporte)) :
            $this->db->where('ID_EXPEDIENTE', $cod_soporte);
            $this->db->delete('EXPEDIENTE');
        endif;
    }

    /*
     * Eliminar plantillas
     */

    public function eliminar_plantilla($cod_plantilla) {
        if (!empty($cod_plantilla)) :
            $this->db->where('COMUNICADO_PJ', $cod_plantilla);
            $this->db->delete('COMUNICADOS_PJ');
        endif;
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
     * Ultima Plantilla del proceso
     */

    public function get_plantilla_proceso($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("C.TITULO_ENCABEZADO, C.NOMBRE_DOCUMENTO, C.CREACION_FECHA,ABOGADO,SECRETARIO,EJECUTOR");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_coactivo);
            $this->db->where("C.CREACION_FECHA", "(SELECT MAX(C.CREACION_FECHA) FROM COMUNICADOS_PJ C WHERE C.COD_PROCESO_COACTIVO = $cod_coactivo )", FALSE);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            $dato = $dato->result_array();
            return $dato[0];
        endif;
    }
    
     /*
     * Ultima Plantilla del avaluo
     */

    public function get_plantilla_procesoavaluo($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("C.TITULO_ENCABEZADO, C.NOMBRE_DOCUMENTO, C.CREACION_FECHA,ABOGADO,SECRETARIO,EJECUTOR");
            $this->db->where("C.COD_AVALUO", $cod_avaluo);
            $this->db->where("C.CREACION_FECHA", "(SELECT MAX(C.CREACION_FECHA) FROM COMUNICADOS_PJ C WHERE C.COD_AVALUO = $cod_avaluo )", FALSE);
            $dato = $this->db->get("COMUNICADOS_PJ C");
              if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
        endif;
    }

    /*
     * Ultima Plantilla del proceso
     */

    public function get_plantilla_proceso_fiscalizacion($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.TITULO_ENCABEZADO, C.NOMBRE_DOCUMENTO, C.CREACION_FECHA,ABOGADO,SECRETARIO,EJECUTOR");
            $this->db->where("C.COD_FISCALIZACION", $cod_fiscalizacion);
            $this->db->where("C.CREACION_FECHA", "(SELECT MAX(C.CREACION_FECHA) FROM COMUNICADOS_PJ C WHERE C.COD_FISCALIZACION = $cod_fiscalizacion )", FALSE);
            $dato = $this->db->get("COMUNICADOS_PJ C");

            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }

        endif;
    }

    /*
     * Todas las plantillas del proceso
     */

    public function get_plantillasfiscalizacion($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("C.COMUNICADO_PJ, C.NOMBRE_DOCUMENTO");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_coactivo);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            $dato = $dato->result_array();
            return $dato;
        endif;
    }
    
    public function get_plantillasavaluo($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("C.COMUNICADO_PJ, C.NOMBRE_DOCUMENTO");
            $this->db->where("C.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            $dato = $dato->result_array();
            return $dato;
        endif;
    }

    public function get_comentarios_proceso($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("C.COMENTARIO, TO_CHAR(C.CREACION_FECHA, 'YYYY/MM/DD HH:MM:SS') CREACION_FECHA, U.NOMBRES, U.APELLIDOS", FALSE);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.COMENTADO_POR");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_coactivo);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }
    public function get_comentarios_avaluo($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("C.COMENTARIO, TO_CHAR(C.CREACION_FECHA, 'YYYY/MM/DD HH:MM:SS') CREACION_FECHA, U.NOMBRES, U.APELLIDOS", FALSE);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.COMENTADO_POR");
            $this->db->where("C.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_coordinador_proceso($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("C.EJECUTOR,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.EJECUTOR");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_coactivo);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_secretario_proceso($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("C.SECRETARIO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_coactivo);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.SECRETARIO");
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_abogado_proceso($cod_coactivo) {
        if (!empty($cod_coactivo)) :
            $this->db->select("C.ABOGADO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_coactivo);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.ABOGADO");
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }
    
     public function get_coordinador_avaluo($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("C.EJECUTOR,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.EJECUTOR");
            $this->db->where("C.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_secretario_avaluo($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("C.SECRETARIO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_AVALUO", $cod_avaluo);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.SECRETARIO");
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_abogado_avaluo($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("C.ABOGADO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_AVALUO", $cod_avaluo);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.ABOGADO");
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_director_coordinador_regional() {
        $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
        $this->db->join("REGIONAL R", "R.CEDULA_COORDINADOR = U.IDUSUARIO");
        $this->db->where("R.COD_REGIONAL", COD_REGIONAL);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    /*
     * ENCABEZADO DE LOS DOCUMENTOS
     */

    public function get_encabezado_documentos($cod_coactivo, $cod_respuesta) {
        $dato = $this->db->query("SELECT DISTINCT E.CODEMPRESA AS IDENTIFICACION, E.RAZON_SOCIAL AS EJECUTADO, E.REPRESENTANTE_LEGAL AS REPRESENTANTE, E.TELEFONO_FIJO AS TELEFONO, CF.NOMBRE_CONCEPTO AS CONCEPTO, TP.TIPO_PROCESO AS PROCESO, RG.NOMBRE_GESTION RESPUESTA, E.DIRECCION AS DIRECCION
            FROM RECEPCIONTITULOS RT,
                  FISCALIZACION F,
                  ASIGNACIONFISCALIZACION AF,
                  EMPRESA E,
                  CONCEPTOSFISCALIZACION CF,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP,
                  PROCESOS_COACTIVOS PC,
                  ACUMULACION_COACTIVA AC
            WHERE (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO)
                  AND (AC.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO)
                  AND (RT.COD_RECEPCIONTITULO = AC.COD_RECEPCIONTITULO)
                  AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                  AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                  AND (E.CODEMPRESA = AF.NIT_EMPRESA)                               
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)                             
                  AND (RG.COD_RESPUESTA = '$cod_respuesta')
                  AND (PC.COD_PROCESO_COACTIVO = '$cod_coactivo')
            UNION(
            SELECT DISTINCT E.COD_ENTIDAD, E.RAZON_SOCIAL, 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION
            FROM  RECEPCIONTITULOS RT,
                  CNM_CARTERANOMISIONAL NM,
                  CNM_EMPRESA E,
                  TIPOCARTERA TC,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP,
                  PROCESOS_COACTIVOS PC,
                  ACUMULACION_COACTIVA AC
            WHERE  (AC.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO)
                  AND (RT.COD_RECEPCIONTITULO = AC.COD_RECEPCIONTITULO)
                  AND (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                  AND (E.COD_ENTIDAD = NM.COD_EMPRESA)
                  AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                  AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                  AND (RT.COD_TIPORESPUESTA = 180)
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                  AND (RG.COD_RESPUESTA = '$cod_respuesta')
                  AND (PC.COD_PROCESO_COACTIVO = '$cod_coactivo')
            UNION(
                SELECT DISTINCT TO_CHAR(E.IDENTIFICACION), E.NOMBRES || E.APELLIDOS , 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION
                FROM RECEPCIONTITULOS RT,
                      CNM_CARTERANOMISIONAL NM,
                      CNM_EMPLEADO E,
                      TIPOCARTERA TC,
                      RESPUESTAGESTION RG,
                      TIPOGESTION TG,
                      TIPOPROCESO TP,
                      PROCESOS_COACTIVOS PC,
                      ACUMULACION_COACTIVA AC
                WHERE (AC.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO)
                      AND (RT.COD_RECEPCIONTITULO = AC.COD_RECEPCIONTITULO)
                      AND (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                      AND (E.IDENTIFICACION = NM.COD_EMPLEADO)
                      AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                      AND (RT.COD_TIPORESPUESTA = 180)
                      AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                      AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                      AND (AC.COD_PROCESO_COACTIVO = PC.COD_PROCESO_COACTIVO)
                      AND (RG.COD_RESPUESTA = '$cod_respuesta')
                      AND (PC.COD_PROCESO_COACTIVO = '$cod_coactivo')
                )
            )
            ORDER BY 1, 2");
		if ($dato->num_rows() > 0) {
            $dato = $dato->result();
			return $dato[0];
        }
        
    }

    function cabecera($proceso, $respuesta) {
        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }
      function info_generalCabecera($proceso, $respuesta) {
        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado;
    }
     
    

}
