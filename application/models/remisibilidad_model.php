<?php

class Remisibilidad_model extends CI_Model {

    private $nit;
    private $codfiscalizacion;

    function __construct() {
        parent::__construct();
    }

    function set_nit($nit) {
        $this->nit = $nit;
    }

    function set_fiscalizacion($cod_fiscalizacion) {
        $this->codfiscalizacion = $cod_fiscalizacion;
    }

    function set_remisibilidad($cod_remisibilidad) {
        $this->cod_remisibilidad = $cod_remisibilidad;
    }

    function set_regional($cod_regional) {
        $this->cod_regional = $cod_regional;
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
     * obtener empresas en administrativa para radicar una solicitud de remisibilidad
     */

    function deudas_fiscalizacion($cod_fiscalizacion = NULL) {
        $this->db->select('VW.IDENTIFICACION, VW.EJECUTADO, VW.REPRESENTANTE, VW.DIRECCION, VW.TELEFONO');
        $this->db->from('VW_DEUDAS VW');
        $this->db->where('VW.COD_REGIONAL', COD_REGIONAL);
        if ($cod_fiscalizacion != NULL) {
            $this->db->where('VW.COD_FISCALIZACION', $cod_fiscalizacion);
        }
        $this->db->group_by('VW.IDENTIFICACION, VW.EJECUTADO, VW.REPRESENTANTE, VW.DIRECCION, VW.TELEFONO');
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        return $resultado;
    }

    /*
     * obtener deudas de la empresa en administrativa para radicar una solicitud de remisibilidad
     */

    function deudas_empresa($nit) {
        $this->db->select('VW.COD_FISCALIZACION, VW.CONCEPTO, VW.NUM_LIQUIDACION, VW.VALOR_DEUDA, VW.FECHA_LIQUIDACION');
        $this->db->from('VW_DEUDAS VW');
        $this->db->where('VW.IDENTIFICACION', $nit);
        $this->db->group_by('VW.COD_FISCALIZACION, VW.CONCEPTO, VW.NUM_LIQUIDACION, VW.VALOR_DEUDA, VW.FECHA_LIQUIDACION');
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        return $resultado;
    }

    /*
     * obtener los titulos de la acumulacion activa pero solo los elejidos por el abogado de cc
     */

    function titulos_remisibles($proceso) {
        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->join("REM_REMISIBILIDAD_DET RD", "RD.COD_RECEPCIONTITULO = VW.NO_EXPEDIENTE");
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $this->db->where('RD.ESTADO', '0');
        $resultado = $this->db->get('');
        $resultado = $resultado->result_array();
        return $resultado;
    }

    /*
     * obtener los titulos de la acumulacion activa pero solo los elejidos por el abogado de cc
     */

    function get_remisibilidadgestionar($cod_remisibilidad) {//s
        $dato = $this->db->query("SELECT DISTINCT DEUDA.EJECUTADO, DEUDA.COD_REGIONAL, DEUDA.NOMBRE_REGIONAL, 'FISCALIZACION' AS TIPO, TO_CHAR(DETALLE.COD_FISCALIZACION) AS HIJO,DETALLE.COD_REMISIBILIDAD_DET, DEUDA.CONCEPTO, DEUDA.NUM_LIQUIDACION, DEUDA.VALOR_DEUDA, DEUDA.FECHA_LIQUIDACION, DEUDA.IDENTIFICACION
                                    FROM VW_DEUDAS DEUDA, REM_REMISIBILIDAD R, REM_REMISIBILIDAD_DET DETALLE
                                    WHERE DEUDA.COD_FISCALIZACION = DETALLE.COD_FISCALIZACION
                                    AND R.COD_REMISIBILIDAD = DETALLE.COD_REMISIBILIDAD
                                    AND R.COD_REMISIBILIDAD = $cod_remisibilidad
                                    UNION (
                                    SELECT DISTINCT COACTIVO.EJECUTADO, COACTIVO.COD_REGIONAL, COACTIVO.NOMBRE_REGIONAL, 'COACTIVO', TO_CHAR(DETALLE.COD_RECEPCIONTITULO), DETALLE.COD_REMISIBILIDAD_DET, COACTIVO.CONCEPTO, COACTIVO.NUM_LIQUIDACION, COACTIVO.VALOR_DEUDA, COACTIVO.FECHA_DEUDA, COACTIVO.IDENTIFICACION
                                    FROM VW_PROCESOS_COACTIVOS COACTIVO, REM_REMISIBILIDAD R, REM_REMISIBILIDAD_DET DETALLE
                                    WHERE COACTIVO.NO_EXPEDIENTE = DETALLE.COD_RECEPCIONTITULO 
                                    AND R.COD_REMISIBILIDAD = Detalle.COD_REMISIBILIDAD
                                    AND R.COD_REMISIBILIDAD = $cod_remisibilidad
                                    AND COACTIVO.COD_RESPUESTA = 1122
                                    )");
        $dato = $dato->result_array();
        if (!empty($dato)) :
            return $dato;
        endif;
    }

    /*
     * obtener los titulos de la acumulacion activa pero solo los elejidos por el abogado de cc
     */

    function get_remisibilidadgestionaractivas($cod_remisibilidad) {//s
        $dato = $this->db->query("SELECT DISTINCT DEUDA.EJECUTADO, DEUDA.COD_REGIONAL, DEUDA.NOMBRE_REGIONAL, 'FISCALIZACION' AS TIPO, TO_CHAR(DETALLE.COD_FISCALIZACION) AS HIJO,DETALLE.COD_REMISIBILIDAD_DET, DEUDA.CONCEPTO, DEUDA.NUM_LIQUIDACION, DEUDA.VALOR_DEUDA, DEUDA.FECHA_LIQUIDACION, DEUDA.IDENTIFICACION
                                    FROM VW_DEUDAS DEUDA, REM_REMISIBILIDAD R, REM_REMISIBILIDAD_DET DETALLE
                                    WHERE DEUDA.COD_FISCALIZACION = DETALLE.COD_FISCALIZACION
                                    AND R.COD_REMISIBILIDAD = DETALLE.COD_REMISIBILIDAD
                                    AND R.COD_REMISIBILIDAD = $cod_remisibilidad
                                    AND DETALLE.ESTADO = '1'
                                    UNION (
                                    SELECT DISTINCT COACTIVO.EJECUTADO, COACTIVO.COD_REGIONAL, COACTIVO.NOMBRE_REGIONAL, 'COACTIVO', TO_CHAR(DETALLE.COD_RECEPCIONTITULO), DETALLE.COD_REMISIBILIDAD_DET, COACTIVO.CONCEPTO, COACTIVO.NUM_LIQUIDACION, COACTIVO.VALOR_DEUDA, COACTIVO.FECHA_DEUDA, COACTIVO.IDENTIFICACION
                                    FROM VW_PROCESOS_COACTIVOS COACTIVO, REM_REMISIBILIDAD R, REM_REMISIBILIDAD_DET DETALLE
                                    WHERE COACTIVO.NO_EXPEDIENTE = DETALLE.COD_RECEPCIONTITULO 
                                    AND R.COD_REMISIBILIDAD = Detalle.COD_REMISIBILIDAD
                                    AND R.COD_REMISIBILIDAD = $cod_remisibilidad
                                    AND COACTIVO.COD_RESPUESTA = 1122
                                    AND DETALLE.ESTADO = '1'
                                    )");
        $dato = $dato->result_array();
        if (!empty($dato)) :
            return $dato;
        endif;
    }

    /*
     * obtener cabecera para cabecera de todas las procedencia
     */

    public function get_cabeceraunificada($cod_respuesta) {
        $consultar = '';
        for ($i = 0; $i < sizeof($cod_respuesta); $i++) {
            if ($i == sizeof($cod_respuesta) - 1) {
                $consultar = $consultar . "R.COD_TIPORESPUESTA = $cod_respuesta[$i]";
            } else {
                $consultar = $consultar . "R.COD_TIPORESPUESTA = $cod_respuesta[$i] OR ";
            }
        }
        $dato = $this->db->query("SELECT DISTINCT DEUDA.IDENTIFICACION, DEUDA.EJECUTADO, DEUDA.REPRESENTANTE, DEUDA.TELEFONO, DEUDA.DIRECCION, R.COD_REMISIBILIDAD,R.COD_TIPORESPUESTA
                                    FROM VW_DEUDAS DEUDA, REM_REMISIBILIDAD R, REM_REMISIBILIDAD_DET DETALLE
                                    WHERE DEUDA.COD_FISCALIZACION = DETALLE.COD_FISCALIZACION
                                    AND R.COD_REMISIBILIDAD = Detalle.COD_REMISIBILIDAD
                                    AND ($consultar)
                                    UNION (
                                    SELECT DISTINCT COACTIVO.IDENTIFICACION, COACTIVO.EJECUTADO, COACTIVO.REPRESENTANTE, COACTIVO.TELEFONO,COACTIVO.DIRECCION, R.COD_REMISIBILIDAD,R.COD_TIPORESPUESTA
                                    FROM VW_PROCESOS_COACTIVOS COACTIVO, REM_REMISIBILIDAD R, REM_REMISIBILIDAD_DET DETALLE
                                    WHERE COACTIVO.NO_EXPEDIENTE = DETALLE.COD_RECEPCIONTITULO 
                                    AND R.COD_REMISIBILIDAD = Detalle.COD_REMISIBILIDAD
                                    AND ($consultar)
                                    )");
        $dato = $dato->result_array();
        if (!empty($dato)) :
            return $dato;
        endif;
    }

    /*
     * consultar las fiscalizaciones que contiene la remisibilidad - aadministrativa
     */

    public function get_remisibilidadfiscalizacion($cod_remisibilidad) {//S
        $this->db->select("RD.COD_FISCALIZACION");
        $this->db->where('RD.COD_REMISIBILIDAD', $cod_remisibilidad);
        $dato = $this->db->get("REM_REMISIBILIDAD_DET RD");
        $dato = $dato->result_array();
        return $dato;
    }
      /*
     * consultar titulos aprobados en la remisibilidad
     */

    public function get_remisibilidadesfdp($cod_coactivo) {//S
        $this->db->select("RD.COD_RECEPCIONTITULO");
        $this->db->join("REM_REMISIBILIDAD R","R.COD_REMISIBILIDAD = RD.COD_REMISIBILIDAD");
        $this->db->where('R.COD_PROCESO_COACTIVO', $cod_coactivo);
        $this->db->where('RD.ESTADO', '1');
        $dato = $this->db->get("REM_REMISIBILIDAD_DET RD");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_remfiscalizacionaprob($cod_remisibilidad) {//S
        $this->db->select("RD.COD_FISCALIZACION");
        $this->db->where('RD.COD_REMISIBILIDAD', $cod_remisibilidad);
        $this->db->where('RD.ESTADO', '1');
        $dato = $this->db->get("REM_REMISIBILIDAD_DET RD");
        $dato = $dato->result_array();
        return $dato;
    }

    /*
     * CONSULTAR CADA TITULO DE LA REMISIBILIDAD
     */

    public function get_remisibilidadtitulo($cod_remisibilidad) {
        $this->db->select("RD.COD_RECEPCIONTITULO");
        $this->db->where('RD.COD_REMISIBILIDAD', $cod_remisibilidad);
        $dato = $this->db->get("REM_REMISIBILIDAD_DET RD");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_remtituloaprob($cod_remisibilidad) {
        $this->db->select("RD.COD_RECEPCIONTITULO");
        $this->db->where('RD.COD_REMISIBILIDAD', $cod_remisibilidad);
        $this->db->where('RD.ESTADO', '1');
        $dato = $this->db->get("REM_REMISIBILIDAD_DET RD");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_documentosremisibilidad($comprobar) {
        $this->db->select("RE.NOMBRE_DOCUMENTO, RE.FECHA_RADICADO, RE.NUMERO_RADICADO, RE.COD_TIPOGENERADO, REM.COD_FISCALIZACION,RE.COD_EXPEDIENTE");
        $this->db->join('REM_REMISIBILIDAD REM', 'REM.COD_REMISIBILIDAD = RE.COD_REMISIBILIDAD');
        $this->db->where('RE.COD_REMISIBILIDAD', $comprobar[0]);
        $this->db->where('RE.COD_TIPOGENERADO', $comprobar[1]);
        $dato = $this->db->get("REM_EXPEDIENTEREMISIBILIDAD RE");
        $dato = $dato->result_array();
        return $dato;
    }

    public function eliminar_soporte($cod_soporte) {
        if (!empty($cod_soporte)) :
            $this->db->where('COD_EXPEDIENTE', $cod_soporte);
            $this->db->delete('REM_EXPEDIENTEREMISIBILIDAD');
        endif;
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

    public function get_numprocesoadjudicado($cod_fiscalizacion) {
        $this->db->select("F.CODIGO_PJ");
        $this->db->where("F.COD_FISCALIZACION", $cod_fiscalizacion);
        $dato = $this->db->get("FISCALIZACION F");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_numprocesoremisibilidad($cod_remisibilidad) {
        $this->db->select("F.CODIGO_PJ");
        $this->db->join("REM_REMISIBILIDAD R", "F.COD_FISCALIZACION = R.COD_FISCALIZACION");
        $this->db->where("R.COD_REMISIBILIDAD", $cod_remisibilidad);
        $dato = $this->db->get("FISCALIZACION F");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function get_tipoarchivos($tipo) {
        $this->db->select("TG.COD_TIPOGENERADO, TG.NOMBRE_TIPO");
        $this->db->where("TG.COD_TIPOGENERADO", $tipo);
        $dato = $this->db->get("REM_TIPOSGENERADOS TG");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function get_consecutivo($consecutivo) {
        $this->db->select("GR.CONSECUTIVO");
        $this->db->where("GR.CONSECUTIVO", $consecutivo);
        $dato = $this->db->get("REM_GENERADOSREMISIBILIDAD GR");
        $dato = $dato->result_array();
        if (!empty($dato)) :
            return $dato[0];
        endif;
    }

    public function actualizacion_remisibilidad($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            unset($datos['COD_PROCESO_COACTIVO']);
            $this->db->update("REM_REMISIBILIDAD", $datos);
        endif;
    }

    public function actualizacion_remprincipal($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->update("REM_REMISIBILIDAD", $datos);
        endif;
    }

    public function actualizacion_detalledeuda($datos) {//S
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->where("COD_FISCALIZACION", $datos['COD_FISCALIZACION']);
            unset($datos['COD_FISCALIZACION']);
            $this->db->update("REM_REMISIBILIDAD_DET", $datos);
        endif;
    }

    public function actualizacion_detallecoactivo($datos) {//S
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->where("COD_RECEPCIONTITULO", $datos['COD_RECEPCIONTITULO']);
            unset($datos['COD_RECEPCIONTITULO']);
            $this->db->update("REM_REMISIBILIDAD_DET", $datos);
        endif;
    }

    public function actualizacion_detallegeneral($datos) {//S
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->update("REM_REMISIBILIDAD_DET", $datos);
        endif;
    }

    public function actualizacion_remisibilidadadmin($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->update("REM_REMISIBILIDAD", $datos);
        endif;
    }

    public function actualizacion_rem_remisibilidad_aprobacion($datos, $fiscalizacion, $nit, $tg, $tr) {
        $Gestion_cobro = trazar($tg, $tr, $fiscalizacion, $nit, 'S', '-1');
        $datos["COD_GESTION_COBRO"] = $Gestion_cobro["COD_GESTION_COBRO"];
        $this->db->set('FECHA_APROBACION', "TO_DATE('" . $datos['FECHA_APROBACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_APROBACION']);
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->update("REM_REMISIBILIDAD", $datos);
        endif;
    }

    public function actualizacion_rem_validaciones_aprobacion($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_REMISIBILIDAD", $datos['COD_REMISIBILIDAD']);
            unset($datos['COD_REMISIBILIDAD']);
            $this->db->update("REM_VALIDACIONESREMISIBILIDAD", $datos);
        endif;
    }

    public function get_condiciones_remisibilidad() {
        $this->db->select("CR.COD_CONDICONREMISIBILIDAD,CR.NOMBRE_CONDICION");
        $this->db->join("REM_CONDICIONESREMISIBILIDAD CR", "CR.COD_CONDICONREMISIBILIDAD = VR.COD_CONDI_REMISIBILIDAD");
        $this->db->join("REM_REMISIBILIDAD R", "R.COD_REMISIBILIDAD = VR.COD_REMISIBILIDAD");
        $this->db->where("VR.COD_REMISIBILIDAD", $this->cod_remisibilidad);
        $dato = $this->db->get("REM_VALIDACIONESREMISIBILIDAD VR");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_documentos_remisibilidad() {
        $this->db->select("SR.NOMBRE_DOCUMENTO");
        $this->db->join("REM_REMISIBILIDAD R", "R.COD_REMISIBILIDAD = SR.COD_REMISIBILIDAD");
        $this->db->where("SR.COD_REMISIBILIDAD", $this->cod_remisibilidad);
        $dato = $this->db->get("REM_SOPORTES_REMISIBILIDAD SR");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_remisibles() {
        $this->db->select("F.COD_FISCALIZACION,L.NUM_LIQUIDACION,L.TOTAL_LIQUIDADO,R.CONSECUTIVO,RE.COD_REGIONAL,RE.NOMBRE_REGIONAL,R.NOMBRE_CTA_CONTABLE,R.CODIGO_CTA_CONTABLE,R.NUM_ASIENTO_CONTABLE,R.FECHA_ASIENTO_CONTABLE,R.COD_REMISIBILIDAD, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION,CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO,P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL , E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->join("REM_REMISIBILIDAD R", "R.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("REGIONAL RE", "RE.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->where("R.COD_REMISIBILIDAD", $this->cod_remisibilidad);
        $dato = $this->db->get("FISCALIZACION F");
        $dato = $dato->result_array();
        return $dato[0];
    }

    function buscarnits() {
        $this->db->select('CODEMPRESA, RAZON_SOCIAL');
        if (!empty($this->nit) and empty($this->razon)) {
            $this->db->like('CODEMPRESA', $this->nit);
        }
        $datos = $this->db->get('EMPRESA');
        $datos = $datos->result_array();
        if (!empty($datos)) :
            $tmp = NULL;
            foreach ($datos as $nit) :
                $tmp[] = array("value" => $nit['CODEMPRESA'], "label" => $nit['CODEMPRESA'] . " :: " . $nit['RAZON_SOCIAL']);
            endforeach;
            $datos = $tmp;
        endif;
        return $datos;
    }

    public function get_estados_remisibilizar($usuario = null, $regional = null, $estado) {
        $cadena = '';
        $this->db->select("REM.COD_REMISIBILIDAD,RG.NOMBRE_GESTION AS RESPUESTA, P.TIPO_PROCESO AS PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("REM_REMISIBILIDAD REM", "REM.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = REM.COD_TIPORESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->where("P.AREA", "J");
        for ($i = 0; $i < sizeof($estado); $i++) {
            if ($i == (sizeof($estado) - 1)) {
                $cadena = $cadena . "REM.COD_TIPORESPUESTA = $estado[$i]";
            } else {
                $cadena = $cadena . "REM.COD_TIPORESPUESTA = $estado[$i] OR ";
            }
        }
        if ($usuario != null) {
            $this->db->where("F.COD_ABOGADO", $usuario);
        }
        if ($regional != null) {
            $this->db->where("R.COD_REGIONAL", $regional);
        }
        $this->db->where("($cadena)");
        $this->db->group_by("REM.COD_REMISIBILIDAD,RG.NOMBRE_GESTION, P.TIPO_PROCESO, F.COD_FISCALIZACION, L.NUM_LIQUIDACION, L.TOTAL_LIQUIDADO, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_encabezado($cod_fiscalizacion) {
        $cod_usuario = COD_USUARIO;
        $cod_regional = REGIONAL_USUARIO;
        $dato = $this->db->query("SELECT DISTINCT P.TIPO_PROCESO PROCESO, E.COD_REGIONAL, E.DIRECCION, E.CODEMPRESA, E.NOMBRE_EMPRESA, F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION RESPUESTA, CF.NOMBRE_CONCEPTO 
                                    FROM LIQUIDACION L, TIPOPROCESO P, TIPOGESTION TG, ASIGNACIONFISCALIZACION AF, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, EMPRESA E, FISCALIZACION F,(
                                    SELECT A.* FROM GestionCobro A,(
                                    SELECT Cod_Fiscalizacion_Empresa, COUNT(*), MAX(Cod_Gestion_Cobro) AS Cod_Gestion_Cobro
                                    FROM GestionCobro 
                                    GROUP BY Cod_Fiscalizacion_Empresa
                                    ORDER BY COUNT(*) DESC) B
                                    WHERE A.Cod_Gestion_Cobro = B.Cod_Gestion_Cobro) H
                                    WHERE H.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION
                                    AND L.COD_FISCALIZACION = F.COD_FISCALIZACION
                                    AND F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION
                                    AND AF.NIT_EMPRESA = E.CODEMPRESA
                                    AND CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO
                                    AND H.COD_TIPOGESTION = TG.COD_GESTION
                                    AND H.COD_TIPO_RESPUESTA = RG.COD_RESPUESTA
                                    AND TG.CODPROCESO = P.COD_TIPO_PROCESO
                                    AND F.COD_ABOGADO = '$cod_usuario'
                                    AND E.COD_REGIONAL = '$cod_regional'
                                    AND F.COD_FISCALIZACION = '$cod_fiscalizacion'");
        $dato = $dato->result_array();
        if (!empty($dato)) :
            return $dato[0];
        endif;
    }

    public function get_encabezadoproceso($cod_fiscalizacion) {
        $this->db->select("R.COD_REGIONAL,P.TIPO_PROCESO PROCESO,REM.COD_REMISIBILIDAD,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION AS RESPUESTA,CF.NOMBRE_CONCEPTO,E.DIRECCION ");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = REM.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = REM.COD_TIPORESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->where("REM.COD_FISCALIZACION", $cod_fiscalizacion);
        $dato = $this->db->get("REM_REMISIBILIDAD REM");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_remisibilidad_creada($Regional) {
        $this->db->select("R.COD_REMISIBILIDAD, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO, P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION ");
        $this->db->join("ASIGNACIONFISCALIZACION AF ", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->join("REM_REMISIBILIDAD R", "R.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->where("E.COD_REGIONAL", $Regional);
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_tablaremisibilidades($Regional, $estado, $estado2 = null, $estado3 = null) {
        $this->db->select("F.COD_FISCALIZACION, R.COD_REMISIBILIDAD, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO, P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION ");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA ");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO ");
        $this->db->join("REM_REMISIBILIDAD R", "R.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("REGIONAL REG", " REG.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->where("REG.COD_REGIONAL", $Regional);
        $this->db->where("R.COD_TIPORESPUESTA", $estado);
        if ($estado2 != null) {
            $this->db->or_where("R.COD_TIPORESPUESTA", $estado2);
        }
        if ($estado3 != null) {
            $this->db->or_where("R.COD_TIPORESPUESTA", $estado3);
        }
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_remisibles_sin_concepto($Regional) {
        $dato = $this->db->query("SELECT REM.COD_REMISIBILIDAD, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO, P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION 
                             FROM FISCALIZACION F 
                             JOIN CONCEPTOSFISCALIZACION CF ON CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO 
                             JOIN TIPOGESTION TG ON TG.COD_GESTION = F.COD_TIPOGESTION 
                             JOIN ASIGNACIONFISCALIZACION AF ON AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC 
                             JOIN EMPRESA E ON E.CODEMPRESA = AF.NIT_EMPRESA 
                             JOIN TIPOPROCESO P ON P.COD_TIPO_PROCESO = TG.CODPROCESO 
                             JOIN REGIONAL R ON R.COD_REGIONAL = E.COD_REGIONAL 
                             JOIN REM_REMISIBILIDAD REM ON REM.COD_FISCALIZACION = F.COD_FISCALIZACION 
                             WHERE REM.APROBADA = 's' 
                             AND R.COD_REGIONAL = $Regional
                             AND REM.COD_REMISIBILIDAD NOT IN (SELECT GR.COD_REMISIBILIDAD FROM REM_GENERADOSREMISIBILIDAD GR JOIN REM_TIPOSGENERADOS TP ON TP.COD_TIPOGENERADO = GR.COD_TIPOGENERADO WHERE GR.COD_TIPOGENERADO<>3)");
        return $dato->result();
    }

    public function get_remisibles_sin_resolucion($Regional) {
        $this->db->select("REM.COD_REMISIBILIDAD, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO, P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("REM_REMISIBILIDAD REM", "REM.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("REM_GENERADOSREMISIBILIDAD GR", "GR.COD_REMISIBILIDAD = REM.COD_REMISIBILIDAD");
        $this->db->join("REM_TIPOSGENERADOS TG", "TG.COD_TIPOGENERADO = GR.COD_TIPOGENERADO");
        $this->db->where("REM.RESOLUCION_GENERADA", NULL);
        $this->db->where("R.COD_REGIONAL", $Regional);
        $this->db->where("TG.COD_TIPOGENERADO", '1');
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_remisibles_con_resolucion() {
        $this->db->select("F.COD_FISCALIZACION, REM.COD_REMISIBILIDAD, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO, P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("REM_REMISIBILIDAD REM", "REM.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("REM_GENERADOSREMISIBILIDAD GR", "GR.COD_REMISIBILIDAD = REM.COD_REMISIBILIDAD");
        $this->db->join("REM_TIPOSGENERADOS TG", "TG.COD_TIPOGENERADO = GR.COD_TIPOGENERADO");
        $this->db->where("GR.COD_TIPOGENERADO", "2");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_cantidadfichas($cod_remisibilidad) {
        $this->db->select("FR.COD_FICHA_REMISIBILIDAD");
        $this->db->where("FR.COD_REMISIBILIDAD", $cod_remisibilidad);
        $dato = $this->db->get("REM_FICHA_REMISIBILIDAD FR");
        if ($dato->num_rows() >= 1) {
            return true;
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

    public function get_rem_agregar_concepto() {
        if (!empty($this->cod_remisibilidad)) :
            $this->db->select("R.COD_REGIONAL,F.COD_FISCALIZACION, RES.NUMERO_RESOLUCION,L.TOTAL_LIQUIDADO,REM.COD_REMISIBILIDAD,R.COD_REGIONAL,R.NOMBRE_REGIONAL,E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION,CF.NOMBRE_CONCEPTO, P.COD_TIPO_PROCESO,P.TIPO_PROCESO, E.REPRESENTANTE_LEGAL , E.TELEFONO_FIJO, TG.COD_GESTION, TG.TIPOGESTION,REM.NOMBRE_CTA_CONTABLE,REM.CODIGO_CTA_CONTABLE,REM.NUM_ASIENTO_CONTABLE,REM.FECHA_ASIENTO_CONTABLE");
            $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
            $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION");
            $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
            $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
            $this->db->join("TIPOPROCESO P", "P.COD_TIPO_PROCESO = TG.CODPROCESO");
            $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
            $this->db->join("REM_REMISIBILIDAD REM", "REM.COD_FISCALIZACION = F.COD_FISCALIZACION");
            $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
            $this->db->join("RESOLUCION RES", "RES.COD_FISCALIZACION = F.COD_FISCALIZACION");
            $this->db->where("REM.COD_REMISIBILIDAD", $this->cod_remisibilidad);
            $dato = $this->db->get("FISCALIZACION F");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }

        endif;
    }

    public function get_condiciones() {
        $this->db->select("COD_CONDICONREMISIBILIDAD,NOMBRE_CONDICION");
        $dato = $this->db->get("REM_CONDICIONESREMISIBILIDAD");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_liquidacion() {
        $this->db->select("L.NUM_LIQUIDACION");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION=L.COD_FISCALIZACION");
        $dato = $this->db->get("LIQUIDACION L");
        $dato = $dato->result_array();
        return $dato;
    }

    public function get_estadoscomentarios_proceso() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.COD_ESTADO");
            $this->db->where("C.COD_TITULO", $this->cod_titulo);
            $dato = $this->db->get("COMUNICACIONES C");
            $dato = $dato->result_array();
            return $dato;

        endif;
    }

    public function get_comentarios_proceso() {
        if (!empty($this->cod_remisibilidad)) :
            $dato = $this->db->query("SELECT GR.COMENTARIOS, TO_CHAR(GR.FECHA_CREACION, 'YYYY/MM/DD HH:MM:SS') AS FECHA_CREACION, GR.COMENTADO_POR, U.NOMBRES, U.APELLIDOS FROM REM_GENERADOSREMISIBILIDAD GR JOIN USUARIOS U ON U.IDUSUARIO = GR.COMENTADO_POR WHERE GR.COD_REMISIBILIDAD = '$this->cod_remisibilidad'");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_coordinador_proceso() {
        if (!empty($this->cod_remisibilidad)) :
            $this->db->select("GR.COD_EJECUTOR ,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = GR.COD_EJECUTOR");
            $this->db->where("GR.COD_REMISIBILIDAD", $this->cod_remisibilidad);
            $this->db->where("GR.FECHA_CREACION", "(SELECT MAX(GR.FECHA_CREACION) FROM REM_GENERADOSREMISIBILIDAD GR WHERE GR.COD_REMISIBILIDAD = $this->cod_remisibilidad )", FALSE);
            $dato = $this->db->get("REM_GENERADOSREMISIBILIDAD GR");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_secretario_proceso() {
        if (!empty($this->cod_remisibilidad)) :
            $this->db->select("GR.COD_SECRETARIO_COACTIVO ,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = GR.COD_SECRETARIO_COACTIVO");
            $this->db->where("GR.COD_REMISIBILIDAD", $this->cod_remisibilidad);
            $this->db->where("GR.FECHA_CREACION", "(SELECT MAX(GR.FECHA_CREACION) FROM REM_GENERADOSREMISIBILIDAD GR WHERE GR.COD_REMISIBILIDAD = $this->cod_remisibilidad )", FALSE);
            $dato = $this->db->get("REM_GENERADOSREMISIBILIDAD GR");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_director_coordinador_regional() {
        if (!empty($this->cod_regional)) :
            $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
            $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
            $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
            $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
            $this->db->where("G.IDGRUPO", COORDINARDOR_CC);
            $this->db->or_where("G.IDGRUPO", DIRECTOR_CC);
            $this->db->where("R.COD_REGIONAL", $this->cod_regional);
            $dato = $this->db->get("USUARIOS U");
            if ($dato->num_rows() > 0) {
                return $dato->result();
            }
        endif;
    }

    public function insertar_remisibilidad($datos = NULL) {
        $this->db->insert("REM_REMISIBILIDAD", $datos);
        /*
         * RETORNAR EL COD_REMISIBILIDAD INSERTADO
         */
        $this->db->select("COD_REMISIBILIDAD");
        $this->db->where("R.COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
        $this->db->where("R.FECHA_GENERACION", "(SELECT MAX(R.FECHA_GENERACION) FROM REM_REMISIBILIDAD R)", FALSE);
        $dato = $this->db->get("REM_REMISIBILIDAD R");
        $dato = $dato->result_array();
        return $dato[0]['COD_REMISIBILIDAD'];
    }

    /*
     * insertar detalle resolucion prescripcoon
     */

    public function insertar_remisibilidaddet($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("REM_REMISIBILIDAD_DET", $datos);
        endif;
    }

    public function insertar_expediente_soporte($datos = NULL) {
        $this->db->set('FECHA_CARGA', "TO_DATE('" . $datos['FECHA_CARGA'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CARGA']);
        $this->db->set('FECHA_RADICADO', "TO_DATE('" . $datos['FECHA_RADICADO'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_RADICADO']);
        $this->db->insert("REM_EXPEDIENTEREMISIBILIDAD", $datos);
    }

    public function insertar_documentos_soporte($datos = NULL) {
        $this->db->set('FECHA_CARGA', "TO_DATE('" . $datos['FECHA_CARGA'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CARGA']);
        $this->db->insert("REM_SOPORTES_REMISIBILIDAD", $datos);
    }

    public function insertar_fichaestudio($datos = NULL) {
        $this->db->set('FECHA_ASIENTO_CONTABLE', "TO_DATE('" . $datos['FECHA_ASIENTO_CONTABLE'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_ASIENTO_CONTABLE']);
        $this->db->insert("REM_FICHA_REMISIBILIDAD", $datos);
    }

    public function insertar_generable($datos = NULL) {
        $this->db->insert("REM_GENERADOSREMISIBILIDAD", $datos);
    }

    public function insertar_condiciones_remisibilidad($datos = NULL) {
        $this->db->insert("REM_VALIDACIONESREMISIBILIDAD", $datos);
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

    /*
     * obtener informacion del expediente apartir de la respuesta y de la fiscalizacion
     */

    public function get_remisibilidad($cod_coactivo) {
        $this->db->select("R.COD_REMISIBILIDAD");
        $this->db->where("R.COD_PROCESO_COACTIVO", $cod_coactivo);
        $this->db->where("R.ESTADO", '1');
        $dato = $this->db->get("REM_REMISIBILIDAD R");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_remisibilidadtrazar($cod_remisibilidad) {
        $this->db->select("AF.NIT_EMPRESA, R.COD_FISCALIZACION,RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = R.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("RESPUESTAGESTION RG", " RG.COD_RESPUESTA = R.COD_TIPORESPUESTA ");
        $this->db->where("R.COD_REMISIBILIDAD", $cod_remisibilidad);
        $dato = $this->db->get("REM_REMISIBILIDAD R");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

}

?>