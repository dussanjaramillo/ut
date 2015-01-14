<?php

class Mcremate_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->cod_secretario_coactivo = 41;
        $this->cod_coordinador_coactivo = 42;
        $this->cod_abogado_coactivo = 43;
        $this->cod_director = 61;
    }

    function set_avaluo($cod_avaluo) {
        $this->cod_avaluo = $cod_avaluo;
    }

    function set_tipoauto($cod_auto) {
        $this->cod_auto = $cod_auto;
    }

    function set_regional($cod_regional) {
        $this->cod_regional = $cod_regional;
    }

    function detalle_avaluo($cod_avaluo, $cod_coactivo) {//S
        $query = "SELECT AVALUADAS.COSTO_TOTAL,MA.USO, MA.COD_AVALUO,MA.COD_MEDIDACAUTELAR,"
                . " MA.UBICACION_BIEN,"
                . " MA.DIRECCION_BIEN,"
                . " MA.LOCALIZACION,MA.ELABORO,MA.COD_AVALUADOR,MA.DIRECCION,MA.FECHA_AVALUO,MA.AREA_TOTAL,MA.PROFESION,MA.LICENCIA_NRO,MA.OBSERVACIONES,"
                . " TB.NOMBRE_TIPO,MA.COD_TIPO_INMUEBLE,PC.COD_PROCESOPJ,VW.NOMBRE_REGIONAL,"
                . " VW.IDENTIFICACION, VW.EJECUTADO "
                . " FROM MC_AVALUO MA, MC_TIPOBIEN TB, VW_PROCESOS_BANDEJA VW,PROCESOS_COACTIVOS PC, MC_PROPIEDADESAVALUADAS AVALUADAS, MC_AVALUOPROPIEDADES MCA"
                . " WHERE MA.COD_AVALUO = " . $cod_avaluo
                . " AND TB.COD_TIPOBIEN = MA.COD_TIPO_INMUEBLE"
                . " AND MCA.COD_AVALUO = MA.COD_AVALUO"
                . " AND MCA.COD_PROPIEDAD = AVALUADAS.COD_PROPIEDAD"
                . " AND MA.COD_TIPORESPUESTA=VW.COD_RESPUESTA"
                . " AND PC.COD_PROCESO_COACTIVO=" . $cod_coactivo
                . " AND VW.COD_PROCESO_COACTIVO=" . $cod_coactivo;
        // echo $query;
        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        return $resultado[0];
    }

    public function get_presentaciondeudor($cod_remate) {
        $this->db->select("R.PRESENTACION_DEUDOR");
        $this->db->where("R.COD_REMATE", $cod_remate);
        $dato = $this->db->get("MC_REMATE R");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_rematetrazar($cod_remate) {
        $this->db->select("AF.NIT_EMPRESA, F.COD_FISCALIZACION,RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = MC.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("MC_REMATE R", " R.COD_AVALUO = A.COD_AVALUO");
        $this->db->join("RESPUESTAGESTION RG", " RG.COD_RESPUESTA = R.COD_RESPUESTA ");
        $this->db->where("R.COD_REMATE", $cod_remate);
        $dato = $this->db->get("MC_MEDIDASCAUTELARES MC");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function insertar_estado_remate($datos) {
        if (!empty($datos)) :
            $this->db->insert("MC_REMATE", $datos);
        endif;
    }

    public function insertar_fraccionamiento_remate($datos) {
        if (!empty($datos)) :
            $this->db->insert("MC_TITULOS_FRACCIONADOS", $datos);
        endif;
    }

    public function insertar_investigacion_bienes($datos) {//S
        if (!empty($datos)) :
            $this->db->insert("MC_MEDIDASCAUTELARES", $datos);
        endif;
    }

    public function actualizacion_estado_remate($datos) {
        if (!empty($datos)) :
            $cod_remate = $datos['COD_REMATE'];
            /*
             * FUNCION DE TRAZA
             */
            $traza = $this->get_rematetrazar($cod_remate);
            $gestion = $this->get_trazagestion($datos['COD_RESPUESTA']);
            $this->datos['idgestioncobro'] = trazar($gestion["COD_TIPOGESTION"], $gestion["COD_RESPUESTA"], $traza["COD_FISCALIZACION"], $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = 'Traza Medidas Cautelares Remate');
            $idgestioncobro = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
            $this->db->where("COD_REMATE", $datos['COD_REMATE']);
            unset($datos['COD_REMATE']);
            $this->db->update("MC_REMATE", $datos);
            return $idgestioncobro;
        endif;
    }

    public function actualizacion_documento($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_AVALUO", $datos['COD_AVALUO']);
            $this->db->where("COD_TIPO_AUTO", $datos['COD_TIPO_AUTO']);
            unset($datos['COD_AVALUO']);
            unset($datos['COD_TIPO_AUTO']);
            $this->db->update("AUTOSJURIDICOS", $datos);
        endif;
    }

    public function get_remateimprobado($cod_remate) {//s
        if (!empty($cod_remate)) :
            $this->db->select("R.COD_REMATE");
            $this->db->where("R.COD_REMATE", $cod_remate);
            $this->db->where("R.IMPRUEBA_REMATE", 'S');
            $dato = $this->db->get("MC_REMATE R");
            if ($dato->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        endif;
    }

    public function get_avaluoremate($cod_avaluo) {//s
        if (!empty($cod_avaluo)) :
            $this->db->select("R.COD_REMATE");
            $this->db->join("MC_AVALUO A", "A.COD_AVALUO = R.COD_AVALUO");
            $this->db->where("R.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("MC_REMATE R");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_avaluocomisiono($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("R.REMATE_COMISIONADO");
            $this->db->join("MC_AVALUO A", "A.COD_AVALUO = R.COD_AVALUO");
            $this->db->where("R.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("MC_REMATE R");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_posturas($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("P.*");
            $this->db->join("MC_POSTURA P", "P.COD_AVALUO = A.COD_AVALUO");
            $this->db->where("A.COD_AVALUO", $cod_avaluo);
            $this->db->where("P.ESTADO_POSTOR", 'S');
            $dato = $this->db->get("MC_AVALUO A");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
            }
        endif;
    }

    public function actualizacion_posturas($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_AVALUO", $datos['COD_AVALUO']);
            unset($datos['COD_AVALUO']);
            $this->db->update("MC_POSTURA", $datos);
        endif;
    }

    public function get_cantidaddesierto($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("R.REMATE_DESIERTO");
            $this->db->join("MC_AVALUO A", "A.COD_AVALUO = R.COD_AVALUO");
            $this->db->where("R.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("MC_REMATE R");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_avaluorespuesta($cod_avaluo) {
        if (!empty($cod_avaluo)) :
            $this->db->select("R.COD_RESPUESTA");
            $this->db->where("R.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("MC_REMATE R");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function insertar_postura_remate($datos = NULL) {
        $this->db->set('FECHA', "TO_DATE('" . $datos['FECHA'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA']);
        $this->db->insert("MC_POSTURA", $datos);
    }

    public function insertar_documento_remate($datos = NULL) {
        $this->db->set('FECHA_CREACION', "TO_DATE('" . $datos['FECHA_CREACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CREACION']);
        $this->db->insert("MC_DOCUMENTOS_REMATE", $datos);
    }

    public function insertar_expendiente($datos = NULL) {
        $this->db->set('FECHA_CARGA', "TO_DATE('" . $datos['FECHA_CARGA'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CARGA']);
        $this->db->insert("MC_EXPEDIENTE", $datos);
    }

    public function insertar_certificados($datos = NULL) {
        $this->db->set('FECHA_SOLICITUD', "TO_DATE('" . $datos['FECHA_SOLICITUD'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_SOLICITUD']);
        $this->db->insert("MC_SOLICITUDCERTIFICADOS", $datos);
    }

    public function actualizacion_certificados($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_AVALUO", $datos['COD_AVALUO']);
            unset($datos['COD_AVALUO']);
            $this->db->update("MC_SOLICITUDCERTIFICADOS", $datos);
        endif;
    }

    public function get_autoordenacomision() {
        if (!empty($this->$cod_avaluo)) :
            $this->db->select("F.COD_FISCALIZACION,E.COD_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, TG.COD_GESTION, TG.TIPOGESTION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.COD_RESPUESTA, RG.NOMBRE_GESTION");
            $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = MC.COD_FISCALIZACION");
            $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
            $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
            $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = E.COD_MUNICIPIO");
            $this->db->join("AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
            $this->db->join("MC_REMATE MCR", "MCR.COD_AVALUO = A.COD_AVALUO");
            $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = MCR.COD_RESPUESTA");
            $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
            $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
            $this->db->where("MCR.COD_AVALUO", $this->$cod_avaluo);
            $dato = $this->db->get("MEDIDASCAUTELARES MC");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_encabezado_documentos() {
        if (!empty($this->cod_avaluo)) :
            $this->db->select("A.ELABORO, A.COD_AVALUADOR,F.COD_FISCALIZACION,E.COD_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, TG.COD_GESTION, TG.TIPOGESTION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.COD_RESPUESTA, RG.NOMBRE_GESTION");
            $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = MC.COD_FISCALIZACION");
            $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
            $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
            $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
            $this->db->join("MC_REMATE MCR", "MCR.COD_AVALUO = A.COD_AVALUO");
            $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = MCR.COD_RESPUESTA");
            $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
            $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
            $this->db->where("MCR.COD_AVALUO", $this->cod_avaluo);
            $dato = $this->db->get("MC_MEDIDASCAUTELARES MC");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_documentoproceso($cod_remate) {
        if (!empty($this->cod_avaluo)) :
            $this->db->select("DR.NOMBRE_DOCUMENTO");
            $this->db->where("DR.COD_REMATE", $cod_remate);
            $dato = $this->db->get("MC_DOCUMENTOS_REMATE DR");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
            }
        endif;
    }

    public function eliminar_documentos($cod_remate) {
        if (!empty($cod_remate)) :
            $this->db->where('COD_REMATE', $cod_remate);
            $this->db->delete('MC_DOCUMENTOS_REMATE');
        endif;
    }

    public function get_saldofecha($cod_avaluo) {
        if (!empty($this->cod_avaluo)) :
            $this->db->select("L.SALDO_DEUDA");
            $this->db->join("MC_MEDIDASCAUTELARES MC", "MC.COD_MEDIDACAUTELAR = A.COD_MEDIDACAUTELAR");
            $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = MC.COD_FISCALIZACION");
            $this->db->where("A.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("MC_AVALUO A");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_fechacitacion($cod_avaluo) {
        if (!empty($this->cod_avaluo)) :
            $this->db->select("R.FECHA_PRESENTACION");
            $this->db->where("R.COD_AVALUO", $cod_avaluo);
            $dato = $this->db->get("MC_REMATE R");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_encabezado_avaluo() {
        if (!empty($this->cod_avaluo)) :
            $this->db->select("MA.NOMBRE_METODO_AVALUO, A.ELABORO, A.COD_AVALUADOR,A.UBICACION_BIEN, A.DIRECCION_BIEN, A.LOCALIZACION, A.USO, TI.NOMBRE_INMUEBLE, A.AREA_TOTAL, A.VALOR_TOTAL_AVALUO, F.COD_FISCALIZACION,E.COD_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, TG.COD_GESTION, TG.TIPOGESTION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, E.DIRECCION,RG.COD_RESPUESTA, RG.NOMBRE_GESTION");
            $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = MC.COD_FISCALIZACION");
            $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
            $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
            $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
            $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = A.COD_TIPORESPUESTA");
            $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
            $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
            $this->db->join("MC_TIPOINMUEBLE TI", "TI.COD_TIPOINMUEBLE = A.COD_TIPO_INMUEBLE");
            $this->db->join("MC_METODOAVALUO MA", "MA.COD_METODOAVALUO = A.COD_METODOAVALUO");
            $this->db->where("A.COD_AVALUO", $this->cod_avaluo);
            $dato = $this->db->get("MC_MEDIDASCAUTELARES MC");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }

        endif;
    }

    public function get_secretario_regional() {
        $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("G.IDGRUPO", SECRETARIO);
        $this->db->where("R.COD_REGIONAL", COD_REGIONAL);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    public function actualizar_remate($datos) {
        if (!empty($datos)) :
            if ($datos['COD_RESPUESTA'] == AVISO_FYH_REMATE_COLILLA_SUBIDA) {
                $this->db->set('FECHA_AVISOREMATE', "TO_DATE('" . $datos['FECHA_AVISOREMATE'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
                unset($datos['FECHA_AVISOREMATE']);
            }
            if ($datos['COD_RESPUESTA'] == NO_SE_PRESENTO_2A_VEZ) {
                $this->db->set('FECHA_PRESENTACION', "TO_DATE('" . $datos['FECHA_PRESENTACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
                unset($datos['FECHA_PRESENTACION']);
            }
            $this->db->where("COD_REMATE", $datos['COD_REMATE']);
            unset($datos['COD_REMATE']);
            $this->db->update("MC_REMATE", $datos);
            if ($this->db->affected_rows() >= 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        endif;
    }

    public function actualizar_estadoremate($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_AVALUO", $datos['COD_AVALUO']);
            unset($datos['COD_AVALUO']);
            $this->db->update("MC_REMATE", $datos);
            if ($this->db->affected_rows() >= 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        endif;
    }

    public function get_trazagestion($cod_respuesta) {//s
        $this->db->select("RG.COD_TIPOGESTION, RG.COD_RESPUESTA");
        $this->db->where("RG.COD_RESPUESTA", $cod_respuesta);
        $dato = $this->db->get("RESPUESTAGESTION RG");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function actualizar_postura($datos) {//s
        if (!empty($datos)) :
            $this->db->where("COD_AVALUO", $datos['COD_AVALUO']);
            $this->db->where("NUMERO_IDENTIFICACION", $datos['NUMERO_IDENTIFICACION']);
            unset($datos['COD_AVALUO']);
            unset($datos['NUMERO_IDENTIFICACION']);
            $this->db->update("MC_POSTURA", $datos);
            if ($this->db->affected_rows() >= 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        endif;
    }

    public function get_estados_abogado($estados, $rol) {
        $this->db->select("F.COD_FISCALIZACION,R.COD_AVALUO, R.COD_REMATE, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.CODPROCESO, P.NOMBREPROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, MCGR.PARAMETRO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION ");
        $this->db->join("ASIGNACIONFISCALIZACION AF ", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("PROCESO P", "P.CODPROCESO = TG.CODPROCESO");
        $this->db->join("MC_MEDIDASCAUTELARES MC", "MC.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
        $this->db->join("MC_REMATE R", "R.COD_AVALUO = A.COD_AVALUO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = R.COD_RESPUESTA"); //OR A.COD_TIPORESPUESTA = RG.COD_RESPUESTA
        $this->db->join("MC_GESTIONREMATE MCGR", "MCGR.COD_RESPUESTA = RG.COD_RESPUESTA");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDGRUPO = MCGR.IDGRUPO");
        $this->db->join("USUARIOS U", "U.IDUSUARIO= F.COD_ABOGADO");
        $this->db->where("U.COD_REGIONAL", COD_REGIONAL);
        $this->db->where("UG.IDUSUARIO", COD_USUARIO);
        $this->db->where("UG.IDGRUPO", $rol);
        $this->db->where("F.COD_ABOGADO", COD_USUARIO);
        $this->db->group_by("F.COD_FISCALIZACION,R.COD_AVALUO, R.COD_REMATE, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.CODPROCESO, P.NOMBREPROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, MCGR.PARAMETRO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_estados_secretario($estados, $rol) {
        $this->db->select("R.COD_AVALUO, R.COD_REMATE, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.CODPROCESO, P.NOMBREPROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, MCGR.PARAMETRO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION ");
        $this->db->join("ASIGNACIONFISCALIZACION AF ", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("PROCESO P", "P.CODPROCESO = TG.CODPROCESO");
        $this->db->join("MC_MEDIDASCAUTELARES MC", "MC.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
        $this->db->join("MC_REMATE R", "R.COD_AVALUO = A.COD_AVALUO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = R.COD_RESPUESTA OR A.COD_TIPORESPUESTA = RG.COD_RESPUESTA");
        $this->db->join("MC_GESTIONREMATE MCGR", "MCGR.COD_RESPUESTA = RG.COD_RESPUESTA");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDGRUPO = MCGR.IDGRUPO");
        $this->db->join("USUARIOS U", "U.IDUSUARIO = UG.IDUSUARIO");
        $this->db->where("UG.IDUSUARIO", COD_USUARIO);
        $this->db->where("UG.IDGRUPO", $rol);
        $this->db->where("U.COD_REGIONAL", COD_REGIONAL);
        $this->db->group_by("R.COD_AVALUO, R.COD_REMATE, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.CODPROCESO, P.NOMBREPROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, MCGR.PARAMETRO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_estados_DC($estados, $rol, $rol2 = NULL) {
        $this->db->select("R.COD_AVALUO, R.COD_REMATE, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.CODPROCESO, P.NOMBREPROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, MCGR.PARAMETRO");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = F.COD_TIPOGESTION ");
        $this->db->join("ASIGNACIONFISCALIZACION AF ", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("PROCESO P", "P.CODPROCESO = TG.CODPROCESO");
        $this->db->join("MC_MEDIDASCAUTELARES MC", "MC.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("MC_AVALUO A", "A.COD_MEDIDACAUTELAR = MC.COD_MEDIDACAUTELAR");
        $this->db->join("MC_REMATE R", "R.COD_AVALUO = A.COD_AVALUO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = R.COD_RESPUESTA OR A.COD_TIPORESPUESTA = RG.COD_RESPUESTA");
        $this->db->join("MC_GESTIONREMATE MCGR", "MCGR.COD_RESPUESTA = RG.COD_RESPUESTA");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDGRUPO = MCGR.IDGRUPO");
        $this->db->join("USUARIOS U", "U.IDUSUARIO = UG.IDUSUARIO");
        $this->db->where("U.COD_REGIONAL", COD_REGIONAL);
        $this->db->where("UG.IDUSUARIO", COD_USUARIO);
        $this->db->where("UG.IDGRUPO", $rol);
        if ($rol2 != NULL) {
            $this->db->or_where("UG.IDGRUPO", $rol2);
        }
        $this->db->group_by("R.COD_AVALUO, R.COD_REMATE, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, P.CODPROCESO, P.NOMBREPROCESO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, MCGR.PARAMETRO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_plantilla_proceso() {
        if (!empty($this->cod_avaluo)) :
            $this->db->select("A.NOMBRE_DOC_GENERADO, A.FECHA_CREACION_AUTO, A.COD_FISCALIZACION,A.CREADO_POR,A.REVISADO_POR,A.APROBADO_POR");
            $this->db->where("A.COD_AVALUO", $this->cod_avaluo);
            $this->db->where("A.COD_TIPO_AUTO", $this->cod_auto);
            $this->db->where("A.FECHA_CREACION_AUTO", "(SELECT MAX(A.FECHA_CREACION_AUTO) FROM AUTOSJURIDICOS A WHERE A.COD_AVALUO = $this->cod_avaluo )", FALSE);
            $dato = $this->db->get("AUTOSJURIDICOS A");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
            }
        endif;
    }

    public function get_plantilla_remate($cod_remate, $gestion_proceso) {
        $cadena = '';
        for ($i = 0; $i < sizeof($gestion_proceso); $i++) {
            if ($i == (sizeof($gestion_proceso) - 1)) {
                $cadena = $cadena . "DR.COD_RESPUESTA = $gestion_proceso[$i]";
            } else {
                $cadena = $cadena . "DR.COD_RESPUESTA = $gestion_proceso[$i] OR ";
            }
        }
        if (!empty($cod_remate)) :
            $this->db->select("DR.NOMBRE_DOCUMENTO, DR.FECHA_CREACION, MC.COD_FISCALIZACION,DR.COD_ABOGADO,DR.COD_SECRETARIO,COD_DIRECTOR");
            $this->db->join("MC_REMATE R", "R.COD_REMATE=DR.COD_REMATE");
            $this->db->join("MC_AVALUO A", "A.COD_AVALUO=R.COD_AVALUO");
            $this->db->join("MC_MEDIDASCAUTELARES MC", "MC.COD_MEDIDACAUTELAR=MC.COD_MEDIDACAUTELAR");
            $this->db->where("DR.COD_REMATE", $cod_remate);
            $this->db->where("DR.FECHA_CREACION", "(SELECT MAX(DR.FECHA_CREACION) FROM MC_DOCUMENTOS_REMATE DR WHERE DR.COD_REMATE = $cod_remate AND ($cadena))", FALSE);
            $dato = $this->db->get("MC_DOCUMENTOS_REMATE DR");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
            }
        endif;
    }

    public function get_comentarios_proceso() {
        if (!empty($this->cod_avaluo)) :
            $dato = $this->db->query("SELECT A.COMENTARIOS, TO_CHAR(A.FECHA_CREACION_AUTO, 'YYYY/MM/DD HH:MM:SS') AS FECHA_CREACION_AUTO, A.ASIGNADO_A, U.NOMBRES, U.APELLIDOS FROM AUTOSJURIDICOS A JOIN USUARIOS U ON U.IDUSUARIO = A.ASIGNADO_A WHERE A.COD_AVALUO = '$this->cod_avaluo'");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
            }
        endif;
    }

    public function get_comentarios_remate($cod_remate, $gestion_proceso) {
        $cadena = '';
        for ($i = 0; $i < sizeof($gestion_proceso); $i++) {
            if ($i == (sizeof($gestion_proceso) - 1)) {
                $cadena = $cadena . "DR.COD_RESPUESTA = $gestion_proceso[$i]";
            } else {
                $cadena = $cadena . "DR.COD_RESPUESTA = $gestion_proceso[$i] OR ";
            }
        }
        if (!empty($cod_remate)) :
            $dato = $this->db->query("SELECT DR.COMENTARIOS, TO_CHAR(DR.FECHA_CREACION, 'YYYY/MM/DD HH:MM:SS') AS FECHA_CREACION, DR.COMENTADO_POR, U.NOMBRES, U.APELLIDOS FROM MC_DOCUMENTOS_REMATE DR JOIN USUARIOS U ON U.IDUSUARIO = DR.COMENTADO_POR WHERE DR.COD_REMATE= '$cod_remate' AND $cadena");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
            }
        endif;
    }

    public function get_usuario($cod_usuario) {
        if (!empty($cod_usuario)) :
            $this->db->select("U.NOMBRES, U.APELLIDOS");
            $this->db->where("U.IDUSUARIO", $cod_usuario);
            $dato = $this->db->get("USUARIOS U");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato;
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

    function saldo_deudacoactivo($proceso, $respuesta) {
        $this->db->select('SUM(SALDO_DEUDA) AS SALDO_DEUDA');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }

}

?>