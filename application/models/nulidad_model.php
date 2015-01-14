<?php

class Nulidad_model extends CI_Model {

    function __construct() {
        parent::__construct();
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

    public function actualizar_estados($tabla, $datos, $condicion, $valor) {
        for ($i = 0; $i < sizeof($condicion); $i++) {
            $this->db->where($condicion[$i], $valor[$i]);
        }
        $this->db->update($tabla, $datos);
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        }
        return FALSE;
    }

    /*
     * insertar detalle resolucion prescripcoon
     */

    public function insertar_nulidaddet($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("NULIDAD_DET", $datos);
        endif;
    }

    public function get_causalesnulidad() {
        $this->db->select("COD_CAUSAL_NULIDAD,NOMBRE_CAUSAL");
        $dato = $this->db->get("TIPOCAUSALESNULIDAD");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_avaluoscoactivo($cod_coactivo) {
        $this->db->select("A.COD_AVALUO");
        $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
        $this->db->where("MC.COD_PROCESO_COACTIVO", $cod_coactivo);
        $dato = $this->db->get("MC_MEDIDASCAUTELARES MC");
        $dato = $dato->result_array();
        return $dato[0];
    }

    public function insertar_nulidad($datos = NULL) {
        $this->db->insert("NULIDAD", $datos);
        /*
         * RETORNAR EL COD_NULIDAD INSERTADO
         */
        $this->db->select("N.COD_NULIDAD");
        $this->db->where("N.COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
        $this->db->where("N.ESTADO", "A");
        $dato = $this->db->get("NULIDAD N");
        $dato = $dato->result_array();
        return $dato[0]['COD_NULIDAD'];
    }

    public function insertar_documentos_soporte($datos = NULL) {
        $this->db->insert("SOPORTES_NULIDAD", $datos);
    }

    public function insertar_causales($datos = NULL) {
        $this->db->insert("CAUSALES_NULIDAD", $datos);
    }

    public function get_estados_nulidades($usuario = null, $regional = null, $estado) {
        $cadena = '';
        $this->db->select("N.COD_RESPUESTA,N.COD_NULIDAD,RG.NOMBRE_GESTION AS RESPUESTA, P.TIPO_PROCESO AS PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("NULIDAD N", "N.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = N.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        for ($i = 0; $i < sizeof($estado); $i++) {
            if ($i == (sizeof($estado) - 1)) {
                $cadena = $cadena . "N.COD_RESPUESTA = $estado[$i]";
            } else {
                $cadena = $cadena . "N.COD_RESPUESTA = $estado[$i] OR ";
            }
        }
        if ($usuario != null) {
            $this->db->where("F.COD_ABOGADO", $usuario);
        }
        if ($regional != null) {
            $this->db->where("R.COD_REGIONAL", $regional);
        }
        $this->db->where("N.ESTADO", "A");
        $this->db->where("($cadena)");
        $this->db->group_by("N.COD_RESPUESTA,N.COD_NULIDAD,RG.NOMBRE_GESTION, P.TIPO_PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_nulidadesfdp($usuario = null) {
        $cadena = '';
        $this->db->select("N.COD_RESPUESTA,N.COD_NULIDAD,RG.NOMBRE_GESTION AS RESPUESTA, P.TIPO_PROCESO AS PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("NULIDAD N", "N.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = N.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->where("N.PROCEDE", 'S');
        $this->db->where("N.APROBADO", 'S');
        if ($usuario != null) {
            $this->db->where("F.COD_ABOGADO", $usuario);
        }
        $this->db->group_by("N.COD_RESPUESTA,N.COD_NULIDAD,RG.NOMBRE_GESTION, P.TIPO_PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_nulidadesNA($usuario = null) {
        $cadena = '';
        $this->db->select("N.COD_RESPUESTA,N.COD_NULIDAD,RG.NOMBRE_GESTION AS RESPUESTA, P.TIPO_PROCESO AS PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("NULIDAD N", "N.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = N.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->where("N.PROCEDE", 'S');
        $this->db->where("N.APROBADO", 'S');
        if ($usuario != null) {
            $this->db->where("F.COD_ABOGADO", $usuario);
        }
        $this->db->group_by("N.COD_RESPUESTA,N.COD_NULIDAD,RG.NOMBRE_GESTION, P.TIPO_PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_nulidadesretorno($usuario = null, $cod_coactivo) {
        $this->db->select("TP.COD_TIPO_PROCESO,TG.COD_GESTION, RG.COD_RESPUESTA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, PC.COD_PROCESOPJ, PC.COD_PROCESO_COACTIVO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = PJ.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO TP", "TP.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->join("RESPUESTAGESTION RG", " RG.COD_TIPOGESTION = TG.COD_GESTION");
        $this->db->join("PROCESOS_COACTIVOS PC", "PC.COD_PROCESO_COACTIVO = PJ.COD_JURIDICO");
        $this->db->where("PC.COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->where("TP.AREA", 'J');
        $this->db->where("RG.RAIZ", 'S');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '14');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '15');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '16');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '18');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '19');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '20');
        $this->db->where_not_in('TP.COD_TIPO_PROCESO', '21');
        if ($usuario != null) {
            $this->db->where("PC.ABOGADO", $usuario);
        }
        $this->db->group_by("TP.COD_TIPO_PROCESO,TG.COD_GESTION, RG.COD_RESPUESTA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, PC.COD_PROCESOPJ, PC.COD_PROCESO_COACTIVO");
        $this->db->order_by("TIPO_PROCESO");
        $dato = $this->db->get("TRAZAPROCJUDICIAL PJ");
        return $dato->result();
    }

    public function get_numprocesoadjudicado($cod_fiscalizacion) {
        $this->db->select("F.CODIGO_PJ");
        $this->db->where("F.COD_FISCALIZACION", $cod_fiscalizacion);
        $dato = $this->db->get("FISCALIZACION F");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function get_permiso_regional($id_usuario, $rol) {
        $this->db->select("U.IDUSUARIO");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("UG.IDGRUPO", $rol);
        $this->db->where("UG.IDUSUARIO", $id_usuario);
        $this->db->where("U.ACTIVO", "1");
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_permiso_rol($id_usuario, $rol) {
        $this->db->select("U.IDUSUARIO");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("UG.IDGRUPO", $rol);
        $this->db->where("UG.IDUSUARIO", $id_usuario);
        $this->db->where("U.ACTIVO", "1");
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizacion_nulidad($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            unset($datos['COD_PROCESO_COACTIVO']);
            $this->db->where("ESTADO", "A");
            $this->db->update("NULIDAD", $datos);
        endif;
    }

    public function actualizacion_retroceso($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_FISCALIZACION", $datos['COD_FISCALIZACION']);
            unset($datos['COD_FISCALIZACION']);
            $this->db->update("NULIDAD", $datos);
        endif;
    }

    public function get_encabezadoproceso($cod_fiscalizacion) {
        $this->db->select("R.COD_REGIONAL,P.TIPO_PROCESO PROCESO,N.COD_NULIDAD,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION AS RESPUESTA,CF.NOMBRE_CONCEPTO,E.DIRECCION ");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = N.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = N.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->where("N.COD_FISCALIZACION", $cod_fiscalizacion);
        $dato = $this->db->get("NULIDAD N");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_secretario_regional() {
        $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("G.IDGRUPO", SECRETARIO_CC);
        $this->db->where("R.COD_REGIONAL", REGIONAL_USUARIO);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    public function get_plantilla_proceso($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $this->db->select("CN.TITULO_ENCABEZADO, CN.NOMBRE_DOCUMENTO, CN.FECHA_DOCUMENTO, N.COD_FISCALIZACION,CN.ABOGADO,CN.SECRETARIO,CN.EJECUTOR");
            $this->db->join("NULIDAD N", "N.COD_NULIDAD = CN.COD_NULIDAD");
            $this->db->where("N.COD_NULIDAD", $cod_nulidad);
            $this->db->where("CN.FECHA_DOCUMENTO", "(SELECT MAX(CN.FECHA_DOCUMENTO) FROM COMUNICACIONES_NULIDAD CN WHERE CN.COD_NULIDAD = $cod_nulidad)", FALSE);
            $dato = $this->db->get("COMUNICACIONES_NULIDAD CN");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_comentarios_proceso($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $dato = $this->db->query("SELECT CN.COMENTARIOS, TO_CHAR(CN.FECHA_DOCUMENTO, 'YYYY/MM/DD HH:MM:SS') AS FECHA_DOCUMENTO, CN.COMENTADO_POR, U.NOMBRES, U.APELLIDOS FROM COMUNICACIONES_NULIDAD CN JOIN USUARIOS U ON U.IDUSUARIO = CN.COMENTADO_POR WHERE CN.COD_NULIDAD = '$cod_nulidad'");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_abogado_proceso($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $this->db->select("CN.EJECUTOR ,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = CN.ABOGADO");
            $this->db->where("CN.COD_NULIDAD", $cod_nulidad);
            $this->db->where("CN.FECHA_DOCUMENTO", "(SELECT MAX(CN.FECHA_DOCUMENTO) FROM COMUNICACIONES_NULIDAD CN WHERE CN.COD_NULIDAD = $cod_nulidad)", FALSE);
            $dato = $this->db->get("COMUNICACIONES_NULIDAD CN");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_coordinador_proceso($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $this->db->select("CN.EJECUTOR ,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = CN.EJECUTOR");
            $this->db->where("CN.COD_NULIDAD", $cod_nulidad);
            $this->db->where("CN.FECHA_DOCUMENTO", "(SELECT MAX(CN.FECHA_DOCUMENTO) FROM COMUNICACIONES_NULIDAD CN WHERE CN.COD_NULIDAD = $cod_nulidad)", FALSE);
            $dato = $this->db->get("COMUNICACIONES_NULIDAD CN");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_secretario_proceso($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $this->db->select("CN.SECRETARIO ,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = CN.SECRETARIO");
            $this->db->where("CN.COD_NULIDAD", $cod_nulidad);
            $this->db->where("CN.FECHA_DOCUMENTO", "(SELECT MAX(CN.FECHA_DOCUMENTO) FROM COMUNICACIONES_NULIDAD CN WHERE CN.COD_NULIDAD = $cod_nulidad)", FALSE);
            $dato = $this->db->get("COMUNICACIONES_NULIDAD CN");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_director_coordinador_regional() {
        $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("G.IDGRUPO", COORDINARDOR_CC);
        $this->db->or_where("G.IDGRUPO", DIRECTOR_CC);
        $this->db->where("R.COD_REGIONAL", REGIONAL_USUARIO);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    public function insertar_expediente_soporte($datos = NULL) {
        $this->db->set('FECHA_RADICACION', "TO_DATE('" . $datos['FECHA_RADICACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_RADICACION']);
        $this->db->insert("SOPORTES_NULIDAD", $datos);
    }

    public function insertar_generable($datos = NULL) {
        $this->db->insert("COMUNICACIONES_NULIDAD", $datos);
    }

    public function get_documentoanexo($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $this->db->select("SN.NOMBRE_DOCUMENTO, SN.FECHA_CREACION");
            $this->db->where("SN.COD_NULIDAD", $cod_nulidad);
            $this->db->where("SN.FECHA_CREACION", "(SELECT MAX(SN.FECHA_CREACION) FROM SOPORTES_NULIDAD SN WHERE SN.COD_NULIDAD = $cod_nulidad)", FALSE);
            $dato = $this->db->get("SOPORTES_NULIDAD SN");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_comentariosnulidad($cod_nulidad) {
        if (!empty($cod_nulidad)) :
            $this->db->select("N.COMENTARIOS_NOTIFICAR");
            $this->db->where("N.COD_NULIDAD", $cod_nulidad);
            $dato = $this->db->get("NULIDAD N");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

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

    public function get_trazagestion($cod_respuesta) {
        $this->db->select("RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->where("RG.COD_RESPUESTA", $cod_respuesta);
        $dato = $this->db->get("RESPUESTAGESTION RG");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_motivosdevolucion() {
        $this->db->select("CAUSALDEVOLUCION.COD_CAUSALDEVOLUCION, CAUSALDEVOLUCION.NOMBRE_CAUSAL");
        $this->db->where('COD_ESTADO', 1);
        $dato = $this->db->get("CAUSALDEVOLUCION");
        return $dato->result_array();
    }

    public function eliminar_soporte($cod_soporte) {
        if (!empty($cod_soporte)) :
            $this->db->where('COD_SOPORTE', $cod_soporte);
            $this->db->delete('SOPORTES_NULIDAD');
        endif;
    }

    public function get_nulidadsoporte($cod_soporte) {
        $this->db->select("SN.COD_NULIDAD");
        $this->db->where('SN.COD_SOPORTE', $cod_soporte);
        $dato = $this->db->get("SOPORTES_NULIDAD SN");
        $dato = $dato->result_array();
        return $dato[0];
    }

    public function get_documentosnulidad($comprobar) {
        $this->db->select("SN.NOMBRE_DOCUMENTO, SN.FECHA_RADICACION, SN.NRO_RADICADO, SN.COD_RESPUESTA, N.COD_FISCALIZACION,SN.COD_SOPORTE");
        $this->db->join('NULIDAD N', 'N.COD_NULIDAD = SN.COD_NULIDAD');
        $this->db->where('SN.COD_NULIDAD', $comprobar[0]);
        $this->db->where('SN.COD_RESPUESTA', $comprobar[1]);
        $this->db->group_by("SN.NOMBRE_DOCUMENTO, SN.FECHA_RADICACION, SN.NRO_RADICADO, SN.COD_RESPUESTA, N.COD_FISCALIZACION,SN.COD_SOPORTE");
        $dato = $this->db->get("SOPORTES_NULIDAD SN");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_nulidadtrazar($cod_nulidad) {
        $this->db->select("AF.NIT_EMPRESA, N.COD_FISCALIZACION,RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = N.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("RESPUESTAGESTION RG", " RG.COD_RESPUESTA = N.COD_RESPUESTA ");
        $this->db->where("N.COD_NULIDAD", $cod_nulidad);
        $dato = $this->db->get("NULIDAD N");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_regional($cod_regional) {
        $this->db->select("R.NOMBRE_REGIONAL, R.TELEFONO_REGIONAL, R.DIRECCION_REGIONAL");
        $this->db->where("R.COD_REGIONAL", $cod_regional);
        $dato = $this->db->get("REGIONAL R");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
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

}

?>