<?php

/**
 * Modelo Consultartitulos. Contiene las consultas que son usadas por el controlador traslado.php
 */
class Acuerdopagodocjuridico_model extends CI_Model {
    /*
     * Funcion para obtener los datos de una fiscalizacion
     */

    public function get_titulotrazar($cod_fiscalizacion) {
        $this->db->select("AF.NIT_EMPRESA, TJ.COD_PROCESO_COACTIVO,TG.COD_GESTION, RG.COD_RESPUESTA");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = TJ.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("FISCALIZACION F", "F.COD_PROCESO_COACTIVO = TJ.COD_PROCESO_COACTIVO");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION");
        $this->db->where("TJ.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
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
     * Funcion para obtener los datos para la tabla de la gestion de usuarios y obtener los datos del encabezado dependiendo si recibe fiscalizacion
     */

    public function get_procesotransversal($usuario, $cod_fiscalizacion = NULL, $cod_respuesta) {
        $this->db->select('CP.NOMBRE_CONCEPTO,VW.IDENTIFICACION AS CODEMPRESA, VW.COD_PROCESO_COACTIVO, VW.COD_REGIONAL,VW.EJECUTADO AS NOMBRE_EMPRESA, 
            VW.DIRECCION, VW.REPRESENTANTE AS REPRESENTANTE_LEGAL, VW.TELEFONO AS TELEFONO_FIJO, PROCESO, NOMBRE_GESTION AS RESPUESTA');        
        $this->db->join('CONCEPTOSFISCALIZACION CP','VW.COD_CPTO_FISCALIZACION = CP.COD_CPTO_FISCALIZACION');
        $this->db->join('RESPUESTAGESTION RP','RP.COD_RESPUESTA = VW.COD_RESPUESTA');
        $this->db->where('VW.COD_RESPUESTA',$cod_respuesta);
        $this->db->where('COD_PROCESO_COACTIVO',$cod_fiscalizacion);
        $dato = $this->db->get('VW_PROCESOS_COACTIVOS VW');
        //echo $this->db->last_query($dato);die;
//        $linea_1 = '';
//        $linea_2 = '';
//        $linea_3 = '';
//        if ($cod_fiscalizacion != NULL) {
//            $linea_1 = "AND F.COD_PROCESO_COACTIVO = $cod_fiscalizacion";
//        } else {
//            if ($usuario) {
//                $linea_2 = "AND F.COD_ABOGADO = " . COD_USUARIO . " ";
//            } else {
//                $linea_2 = "AND TL.COD_PROCESO_COACTIVO = F.COD_PROCESO_COACTIVO";
//            }
//        }
//        $dato = $this->db->query("SELECT DISTINCT RG.COD_RESPUESTA, RG.IDGRUPO, RG.URLGESTION, P.COD_TIPO_PROCESO, P.TIPO_PROCESO PROCESO, E.CODEMPRESA, E.NOMBRE_EMPRESA,E.RAZON_SOCIAL, F.COD_PROCESO_COACTIVO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION RESPUESTA, CF.NOMBRE_CONCEPTO, E.DIRECCION
//                                    FROM TRASLADO_JUDICIAL TL, TIPOPROCESO P, TIPOGESTION TG, ASIGNACIONFISCALIZACION AF, SENA.CONCEPTOSFISCALIZACION CF, SENA.RESPUESTAGESTION RG, SENA.EMPRESA E, SENA.FISCALIZACION F,(
//                                    SELECT A.* FROM GESTIONCOBRO A,(
//                                    SELECT COD_PROCESO_COACTIVO_EMPRESA, COUNT(*), MAX(COD_GESTION_COBRO) AS COD_GESTION_COBRO
//                                    FROM GESTIONCOBRO 
//                                    GROUP BY COD_PROCESO_COACTIVO_EMPRESA
//                                    ORDER BY COUNT(*) DESC) B
//                                    WHERE A.COD_GESTION_COBRO = B.COD_GESTION_COBRO) H
//                                    WHERE H.COD_PROCESO_COACTIVO_EMPRESA = F.COD_PROCESO_COACTIVO
//                                    AND F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION
//                                    AND AF.NIT_EMPRESA = E.CODEMPRESA
//                                    AND CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO
//                                    AND H.COD_TIPOGESTION = TG.COD_GESTION
//                                    AND H.COD_TIPO_RESPUESTA = RG.COD_RESPUESTA
//                                    AND TG.CODPROCESO = P.COD_TIPO_PROCESO
//                                    $linea_2 $linea_3
//                                    AND E.COD_REGIONAL =  " . COD_REGIONAL . " 
//                                    AND F.CODIGO_PJ IS NOT NULL
//                                    AND P.AREA = 'J'
//
//                                    $linea_1");
        return $dato->result();
    }

    /*
     * Obtener el numero de proceso judicial deacuerdo a la fiscalizacion
     */

    public function get_numprocesoadjudicado($cod_fiscalizacion) {
        $this->db->select("F.CODIGO_PJ");
        $this->db->where("F.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
        $dato = $this->db->get("VW_PROCESOS_COACTIVOS VW");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * obtener el secretario de la regional en la que se encuentra 
     */

    public function get_coordinador_regional() {
        $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("G.IDGRUPO", COORDINADOR_RELACIONES);
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
            $this->db->set('FECHA_RADICADO', "TO_DATE('" . $datos['FECHA_RADICADO'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
            unset($datos['FECHA_RADICADO']);
            $this->db->insert("EXPEDIENTE", $datos);
        endif;
    }

    /*
     * obtener informacion del expediente apartir de la respuesta y de la fiscalizacion
     */

    public function get_documentosexpediente($comprobar) {
        $this->db->select("E.NOMBRE_DOCUMENTO, E.RUTA_DOCUMENTO, E.FECHA_RADICADO, E.NUMERO_RADICADO, E.COD_RESPUESTAGESTION, E.COD_PROCESO_COACTIVO, E.ID_EXPEDIENTE");
        $this->db->where('E.COD_PROCESO_COACTIVO', $comprobar[0]);
        $this->db->where('E.COD_RESPUESTAGESTION', $comprobar[1]);
        $this->db->group_by("E.NOMBRE_DOCUMENTO, E.RUTA_DOCUMENTO, E.FECHA_RADICADO, E.NUMERO_RADICADO, E.COD_RESPUESTAGESTION, E.COD_PROCESO_COACTIVO, E.ID_EXPEDIENTE");
        $dato = $this->db->get("EXPEDIENTE E");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_traslado($cod_fiscalizacion) {
        $this->db->select("TJ.COD_TRASLADO");
        $this->db->where("TJ.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
        $this->db->where("TJ.ESTADO", 'S');
        $dato = $this->db->get("TRASLADO_JUDICIAL TJ");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
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
            $this->db->where('COD_PROCESO_COACTIVO', $cod_plantilla);
            $this->db->delete('COMUNICADOS_PJ');
            //echo $this->db->last_query($consul);die;
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
     * ACTUALIZAR EL ESTADO DEL TITULO DESPUES DE UNA GESTION
     */

    public function actualizacion_acuerdopago($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            unset($datos['COD_PROCESO_COACTIVO']);
            $consul = $this->db->update("ACUERDOPAGO", $datos);
            //echo $this->db->last_query($consul);die;
        endif;
    }

    /*
     * Ultima Plantilla del proceso
     */

    public function get_plantilla_proceso($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.TITULO_ENCABEZADO, C.NOMBRE_DOCUMENTO, C.CREACION_FECHA,ABOGADO,SECRETARIO,EJECUTOR,C.RADICADO_ONBASE,C.FECHA_ONBASE");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
            $this->db->where("C.CREACION_FECHA", "(SELECT MAX(C.CREACION_FECHA) FROM COMUNICADOS_PJ C WHERE C.COD_PROCESO_COACTIVO = $cod_fiscalizacion )", FALSE);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            $dato = $dato->result_array();
            return @$dato[0];
        endif;
    }

    /*
     * Todas las plantillas del proceso
     */

    public function get_plantillasfiscalizacion($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.COMUNICADO_PJ, C.NOMBRE_DOCUMENTO");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            $dato = $dato->result_array();
            return $dato;
        endif;
    }

    public function get_comentarios_proceso($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.COMENTARIO, TO_CHAR(C.CREACION_FECHA, 'YYYY/MM/DD HH:MM:SS') CREACION_FECHA, U.NOMBRES, U.APELLIDOS", FALSE);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.COMENTADO_POR");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_coordinador_proceso($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.EJECUTOR,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.EJECUTOR");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_secretario_proceso($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.SECRETARIO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.SECRETARIO");
            $dato = $this->db->get("COMUNICADOS_PJ C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_abogado_proceso($cod_fiscalizacion) {
        if (!empty($cod_fiscalizacion)) :
            $this->db->select("C.ABOGADO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_PROCESO_COACTIVO", $cod_fiscalizacion);
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
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("G.IDGRUPO", COORDINADOR);
        $this->db->or_where("G.IDGRUPO", DIRECTOR);
        $this->db->where("R.COD_REGIONAL", COD_REGIONAL);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

}
