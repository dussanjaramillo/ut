<?php

class Titulo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*
     * Funcion para obtener los datos para la tabla de la gestion de usuarios y obtener los datos del encabezado dependiendo si recibe fiscalizacion
     */

    public function get_procesotransversal($cod_fiscalizacion = NULL) {
        $condicion = '';
        if ($cod_fiscalizacion != NULL) {
            $condicion = ' AND CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL = ' . $cod_fiscalizacion;
        }
        $dato = $this->db->query("SELECT CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_EMPLEADO.IDENTIFICACION, CNM_EMPLEADO.NOMBRES, CNM_EMPLEADO.APELLIDOS, CNM_EMPLEADO.DIRECCION, 
            CNM_EMPLEADO.TELEFONO, CNM_EMPLEADO.TELEFONO_CELULAR, CNM_EMPLEADO.CORREO_ELECTRONICO, 
            CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.COD_TIPOCARTERA, CNM_CARTERA_PRESTAMO_HIPOTEC.FECHA_ESCRITURA,
            CNM_CARTERA_PRESTAMO_HIPOTEC.NUMERO_ESCRITURA  
            FROM CNM_CARTERANOMISIONAL 
            JOIN CNM_CARTERA_PRESTAMO_HIPOTEC ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CARTERA_PRESTAMO_HIPOTEC.COD_CARTERA 
            JOIN CNM_EMPLEADO ON CNM_CARTERANOMISIONAL.COD_EMPLEADO=CNM_EMPLEADO.IDENTIFICACION 
            AND CNM_CARTERANOMISIONAL.COD_ESTADO=7 
            WHERE CNM_CARTERANOMISIONAL.COD_TIPOCARTERA = '8' $condicion");
        return $dato->result();
    }

    public function get_titulotrazar($cod_titulo) {
        $this->db->select("TG.COD_GESTION, RG.COD_RESPUESTA");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->where("T.COD_TITULO", $cod_titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        $dato = $dato->result_array();
        return $dato[0];
    }

    function verificar_permiso($id_usuario, $rol) {
        $this->db->select("UG.IDUSUARIO");
        $this->db->where("UG.IDGRUPO", $rol);
        $this->db->where("UG.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS UG");
        if ($dato->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_respuestatitulo($titulo) {
        $this->db->select("T.COD_RESPUESTA");
        $this->db->where("T.COD_TITULO", $titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        }
    }

    function get_respuestaacuerdo($titulo) {
        $this->db->select("T.ACUERDO_PAGO");
        $this->db->where("T.COD_TITULO", $titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result();
            return $dato[0];
        }
    }

    function datatable_titulos($estado) {
        $this->db->select("T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, RG.NOMBRE_GESTION, TO_CHAR(T.FECHA_GESTION, 'YYYY-MM-DD') AS FECHA_GESTION", FALSE);
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = T.COD_REGIONAL");
        $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = T.COD_CIUDAD");
        $this->db->join("DEPARTAMENTO D", "D.COD_DEPARTAMENTO = T.COD_DEPARTAMENTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->where("M.COD_DEPARTAMENTO", "D.COD_DEPARTAMENTO", FALSE);
        $this->db->where("T.COD_RESPUESTA", $estado);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function datatable_titulosmodificar() {
        $this->db->select("T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, RG.NOMBRE_GESTION, TO_CHAR(T.FECHA_GESTION, 'YYYY-MM-DD') AS FECHA_GESTION", FALSE);
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = T.COD_REGIONAL");
        $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = T.COD_CIUDAD");
        $this->db->join("DEPARTAMENTO D", "D.COD_DEPARTAMENTO = T.COD_DEPARTAMENTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->where("M.COD_DEPARTAMENTO", "D.COD_DEPARTAMENTO", FALSE);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function datatable_acuerdodepago() {
        $this->db->select("T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, RG.NOMBRE_GESTION, TO_CHAR(T.FECHA_GESTION, 'YYYY-MM-DD') AS FECHA_GESTION", FALSE);
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = T.COD_REGIONAL");
        $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = T.COD_CIUDAD");
        $this->db->join("DEPARTAMENTO D", "D.COD_DEPARTAMENTO = T.COD_DEPARTAMENTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->where("M.COD_DEPARTAMENTO", "D.COD_DEPARTAMENTO", FALSE);
        $this->db->where("T.COD_ABOGADO_ASIGNADO", COD_USUARIO);
        $this->db->where("T.ACUERDO_PAGO", NULL);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function datatable_titulosabo($estado, $cod_usuario) {
        $this->db->select("T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, RG.NOMBRE_GESTION, TO_CHAR(T.FECHA_GESTION, 'YYYY-MM-DD') AS FECHA_GESTION", FALSE);
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = T.COD_REGIONAL");
        $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = T.COD_CIUDAD");
        $this->db->join("DEPARTAMENTO D", "D.COD_DEPARTAMENTO = T.COD_DEPARTAMENTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->where("M.COD_DEPARTAMENTO", "D.COD_DEPARTAMENTO", FALSE);
        $this->db->where("T.COD_RESPUESTA", $estado);
        $this->db->where("T.COD_ABOGADO_ASIGNADO", $cod_usuario);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function datatable_titulos_dobleestado($estado1, $estado2, $estado3 = null, $cod_usuario) {
        $this->db->select("T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, RG.NOMBRE_GESTION, TO_CHAR(T.FECHA_GESTION, 'YYYY-MM-DD') AS FECHA_GESTION", FALSE);
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = T.COD_REGIONAL");
        $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = T.COD_CIUDAD");
        $this->db->join("DEPARTAMENTO D", "D.COD_DEPARTAMENTO = T.COD_DEPARTAMENTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->where("M.COD_DEPARTAMENTO", "D.COD_DEPARTAMENTO", FALSE);
        $this->db->where("T.COD_ABOGADO_ASIGNADO", $cod_usuario);
        if ($estado3 != null) {
            $this->db->where("(T.COD_RESPUESTA = $estado1 OR T.COD_RESPUESTA = $estado2 OR T.COD_RESPUESTA = $estado3)");
        } else {
            $this->db->where("(T.COD_RESPUESTA = $estado1 OR T.COD_RESPUESTA = $estado2)");
        }
        $this->db->group_by("T.COD_TITULO, T.COD_REGIONAL, R.NOMBRE_REGIONAL, T.NUM_ESCRITURA, T.NOTARIA, M.NOMBREMUNICIPIO, T.COD_PROPIETARIO, T.NOMBRE_PROPIETARIO, RG.NOMBRE_GESTION, T.FECHA_GESTION");
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function agregar_titulosexegibles($informacion) {
        $this->db->set('COD_TITULO', $informacion['cod_titulo']);
        $this->db->set('COD_TIPOEXIGILIDAD', $informacion['cod_tipoexigilidad']);
        $this->db->set('OBSERVACIONES', $informacion['observaciones']);
        $this->db->set('CONFIRMADO', $informacion['confirmado']);
        $this->db->set('CONFIRMADO_POR', $informacion['confirmado_por']);
        $this->db->set('FECHA_CONFIRMACION', "TO_DATE('" . $informacion['fecha_confirmacion'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        $this->db->insert('TITULOSEXEGIBLES');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_abogadoproceso($cod_titulo) {
        $this->db->select("T.COD_ABOGADO_ASIGNADO");
        $this->db->where("T.COD_TITULO", $cod_titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function get_tipos_exigibilidad() {
        $this->db->select("TE.COD_TIPOEXIGILIDAD, TE.NOMBRE_TIPOEXIGIBILIDAD");
        $this->db->join("ESTADOS E", "TE.COD_ESTADO = E.IDESTADO");
        $this->db->where("E.IDESTADO", 1);
        $dato = $this->db->get("TIPOSEXIGIBILIDAD TE");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function get_usuariosgrupo($grupo, $regional) {
        $this->db->select("U.IDUSUARIO,U.NOMBRES,U.APELLIDOS");
        $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
        $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
        $this->db->where("UG.IDGRUPO", $grupo);
        $this->db->where("U.COD_REGIONAL", $regional);
        $dato = $this->db->get("USUARIOS U");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function get_regionaltitulo($cod_titulo) {
        $this->db->select("T.COD_REGIONAL");
        $this->db->where("T.COD_TITULO", $cod_titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    public function actualizar_titulo($datos) {
        if (!empty($datos)) :
            $cod_titulo = $datos['COD_TITULO'];
            $this->db->where("COD_TITULO", $datos['COD_TITULO']);
            unset($datos['COD_TITULO']);
            $this->db->update("TITULOSJUDICIALES", $datos);
            if ($this->db->affected_rows() >= 0) {
//                $info = $this->get_titulotrazar($cod_titulo);
//                $cod_gestion = $info["COD_GESTION"];
//                $cod_respuesta = $info["COD_RESPUESTA"];
//                trazarProcesoJuridico($cod_gestion, $cod_respuesta, $cod_titulo);
                return TRUE;
            } else {
                return FALSE;
            }
        endif;
    }

    function obtener_departamentos() {
        $this->db->select("D.COD_DEPARTAMENTO,D.NOM_DEPARTAMENTO");
        $dato = $this->db->get("DEPARTAMENTO D");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function obtener_regional() {
        $this->db->select("R.COD_REGIONAL,R.NOMBRE_REGIONAL");
        $dato = $this->db->get("REGIONAL R");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function obtener_ciudad($id_departamento) {
        $this->db->select("C.CODMUNICIPIO,C.NOMBREMUNICIPIO");
        $this->db->where("C.COD_DEPARTAMENTO", $id_departamento);
        $dato = $this->db->get("MUNICIPIO C");
        if ($dato->num_rows() > 0) {
            return $dato->result();
        }
    }

    function obtener_correo_Onbase($cod_titulo) {
        $this->db->select("T.CORREO_PROPIETARIO");
        $this->db->where("T.COD_TITULO", $cod_titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function get_titulo($cod_titulo) {
        $this->db->select("R.COD_REGIONAL, R.NOMBRE_REGIONAL, M.CODMUNICIPIO, M.NOMBREMUNICIPIO, D.COD_DEPARTAMENTO, D.NOM_DEPARTAMENTO ,T.COD_PROPIETARIO, T.NUM_ESCRITURA, T.NOMBRE_PROPIETARIO, T.NOTARIA, T.CORREO_PROPIETARIO, T.NUM_MATRICULA, T.DIRECCION_INMUEBLE");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = T.COD_REGIONAL");
        $this->db->join("MUNICIPIO M", "M.CODMUNICIPIO = T.COD_CIUDAD");
        $this->db->join("DEPARTAMENTO D", "D.COD_DEPARTAMENTO = D.COD_DEPARTAMENTO");
        $this->db->where("T.COD_TITULO", $cod_titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function adicionar_documentosoporte($informacion) {
        $this->db->set('COD_TITULO', $informacion['cod_titulo']);
        $this->db->set('NOMBRE_DOCUMENTO', $informacion['nombre_documento']);
        $this->db->set('OBSERVACIONES', $informacion['observaciones']);
        $this->db->insert('SOPORTESTITULOS');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function adicionar_gestionestado($informacion) {
        $this->db->set('COD_TITULO', $informacion['id_titulo']);
        $this->db->set('FECHA_GESTION', "TO_DATE('" . $informacion['fecha'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        $this->db->set('COD_ESTADOANTERIOR', $informacion['cod_estadoanterior']);
        $this->db->set('RESPONSABLE', $informacion['responsable']);
        $this->db->set('OBSERVACIONES', $informacion['observaciones']);
        $this->db->insert('GESTIONTITULOS');
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function adicionar_titulo($informacion) {
        $this->db->set('COD_REGIONAL', $informacion['cod_regional']);
        $this->db->set('COD_RESPUESTA', $informacion['cod_respuesta']);
        $this->db->set('COD_CIUDAD', $informacion['cod_ciudad']);
        $this->db->set('COD_PROPIETARIO', $informacion['cod_propietario']);
        $this->db->set('NUM_ESCRITURA', $informacion['num_escritura']);
        $this->db->set('NOTARIA', $informacion['notaria']);
        $this->db->set('NOMBRE_PROPIETARIO', $informacion['nombre_propietario']);
        $this->db->set('NUM_MATRICULA', $informacion['num_matricula']);
        $this->db->set('DIRECCION_INMUEBLE', $informacion['direccion_inmueble']);
        $this->db->set('NUM_TP_ABOGADO', $informacion['num_tp_abogado']);
        $this->db->set('CLARIDAD_TITULO', $informacion['claridad_titulo']);
        $this->db->set('TITULO_EXPRESO', $informacion['titulo_expreso']);
        $this->db->set('TITULO_CARGADO', $informacion['titulo_cargado']);
        $this->db->set('COD_DEPARTAMENTO', $informacion['cod_departamento']);
        $this->db->set('CORREO_PROPIETARIO', $informacion['correo_propietario']);
        $this->db->set('FECHA_GESTION', "TO_DATE('" . $informacion['fecha'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        $this->db->insert('TITULOSJUDICIALES');
        if ($this->db->affected_rows() >= 0) {
//            $this->db->select("T.COD_TITULO");
//            $this->db->where("T.FECHA_GESTION", "(SELECT MAX(T.FECHA_GESTION) FROM TITULOSJUDICIALES T)", FALSE);
//            $dato = $this->db->get("TITULOSJUDICIALES T");
//            $dato = $dato->result_array();
//            $cod_titulo = $dato[0]["COD_TITULO"];
//            trazarProcesoJuridico(168, 422, $cod_titulo);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function modificar_titulo($informacion) {
        $this->db->set('COD_REGIONAL', $informacion['cod_regional']);
        $this->db->set('COD_RESPUESTA', $informacion['cod_respuesta']);
        $this->db->set('COD_CIUDAD', $informacion['cod_ciudad']);
        $this->db->set('COD_PROPIETARIO', $informacion['cod_propietario']);
        $this->db->set('NUM_ESCRITURA', $informacion['num_escritura']);
        $this->db->set('NOTARIA', $informacion['notaria']);
        $this->db->set('NOMBRE_PROPIETARIO', $informacion['nombre_propietario']);
        $this->db->set('NUM_MATRICULA', $informacion['num_matricula']);
        $this->db->set('DIRECCION_INMUEBLE', $informacion['direccion_inmueble']);
        $this->db->set('COD_DEPARTAMENTO', $informacion['cod_departamento']);
        $this->db->set('CORREO_PROPIETARIO', $informacion['correo_propietario']);
        $this->db->set('FECHA_GESTION', "TO_DATE('" . $informacion['fecha'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        $this->db->where('COD_TITULO', $informacion['cod_titulo']);
        $this->db->update("TITULOSJUDICIALES");
        if ($this->db->affected_rows() >= 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Obtiene el codigo del tipo de gestion para un codigo de respuesta
     * @access public
     * @param integer
     * @return integer
     * @autor German E. Perez H 20141002
     */
    public function get_tipo_gestion($ultimo_cod_titulo, $cod_respuesta) {
        $this->db->select("TG.COD_GESTION");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->where("T.COD_TITULO", $ultimo_cod_titulo);
        $dato = $this->db->get("TITULOSJUDICIALES T");
        $dato = $dato->result_array();
        return $dato[0]['COD_GESTION'];
    }

    /**
     * Obtiene el ultimo codigo del titulo juridico insertado 
     * @access public
     * @param integer
     * @return integer
     * @autor German E. Perez H 20141002
     */
    public function get_ultimo_codigo_tit() {
        $query = $this->db->query("SELECT TitulosJudicial_cod_titulo_SEQ.CURRVAL FROM Dual");
        $row = $query->row_array();
        $id = $row['CURRVAL'];
        return $id;
    }

}
