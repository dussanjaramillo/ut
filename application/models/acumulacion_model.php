<?php

class Acumulacion_model extends CI_Model {
    /*
     * FUNCION PARA TRAER EMPRESAS
     */

    public function get_empresasrecepcion() {
        $dato = $this->db->query("SELECT DISTINCT E.CodEmpresa AS IDENTIFICACION, E.Razon_Social AS NOMBRE, E.Representante_Legal AS REPRESENTANTE, E.Telefono_Fijo AS TELEFONO, 'Misional' AS Origen
                                FROM RecepcionTitulos RT,
                                FISCALIZACION F,
                                ASIGNACIONFISCALIZACION AF,
                                EMPRESA E
                                WHERE (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                                AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                                AND (E.CODEMPRESA = AF.NIT_EMPRESA)
                                AND (RT.COD_TIPORESPUESTA = 180)
                                UNION(
                                SELECT DISTINCT E.Cod_Entidad, E.Razon_Social, 'No hay representante', E.Telefono, 'No Misional' AS Origen
                                FROM RecepcionTitulos RT,
                                CNM_CarteraNoMisional NM,
                                CNM_Empresa E
                                WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                                AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                                AND (RT.COD_TIPORESPUESTA = 180)
                                )
                                ORDER BY 1, 2");
        return $dato->result();
    }

    public function get_titulos($identificacion,$naturaleza,$concepto) {
        $dato = $this->db->query("SELECT DISTINCT F.COD_FISCALIZACION AS EXPEDIENTE, RT.COD_RECEPCIONTITULO, CF.NOMBRE_CONCEPTO AS OBLIGACION, TO_CHAR(RT.FECHA_RECEPCION, 'YYYY/MM/DD HH:MM:SS') FECHA_RECEPCION, 'Acumulación de Titulos' AS PROCESO,  'Titulo Valido para Agrupar' AS ESTADO
                                FROM RecepcionTitulos RT,
                                FISCALIZACION F,
                                ASIGNACIONFISCALIZACION AF,
                                CONCEPTOSFISCALIZACION CF,
                                EMPRESA E
                                WHERE (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                                AND (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO)
                                AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                                AND (E.CODEMPRESA = AF.NIT_EMPRESA)
                                AND (E.CODEMPRESA = '$identificacion')
                                AND (RT.COD_TIPORESPUESTA = 180)
                                AND (RT.PROXIMO_PRESCRIBIR = '$naturaleza')
                                    AND (CF.COD_CPTO_FISCALIZACION = '$concepto')
                                UNION(
                                    SELECT DISTINCT TO_CHAR(NM.COD_CARTERA_NOMISIONAL), RT.COD_RECEPCIONTITULO, TC.NOMBRE_CARTERA, TO_CHAR(RT.FECHA_RECEPCION, 'YYYY/MM/DD HH:MM:SS') FECHA_RECEPCION,  'Acumulación de Titulos' AS PROCESO,  'Titulo Valido para Agrupar' AS ESTADO
                                    FROM RECEPCIONTITULOS RT,
                                    CNM_CARTERANOMISIONAL NM,
                                    CNM_EMPRESA E,
                                    TIPOCARTERA TC
                                    WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                                    AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                                    AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                                    AND (E.COD_ENTIDAD = '$identificacion')
                                    AND (RT.PROXIMO_PRESCRIBIR = '$naturaleza')
                                    AND (RT.COD_TIPORESPUESTA = 180)
                                     AND (TC.COD_TIPOCARTERA = '$concepto')
                                    UNION(
                                        SELECT DISTINCT TO_CHAR(NM.COD_CARTERA_NOMISIONAL), RT.COD_RECEPCIONTITULO, TC.NOMBRE_CARTERA, TO_CHAR(RT.FECHA_RECEPCION, 'YYYY/MM/DD HH:MM:SS') FECHA_RECEPCION,  'Acumulación de Titulos' AS PROCESO,  'Titulo Valido para Agrupar' AS ESTADO
                                        FROM RECEPCIONTITULOS RT,
                                        CNM_CARTERANOMISIONAL NM,
                                        CNM_EMPLEADO E,
                                        TIPOCARTERA TC
                                        WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                                        AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                                        AND (E.IDENTIFICACION = NM.COD_EMPLEADO)
                                        AND (E.IDENTIFICACION = '$identificacion')
                                        AND (RT.PROXIMO_PRESCRIBIR = '$naturaleza')
                                        AND (RT.COD_TIPORESPUESTA = 180)
                                        AND (TC.COD_TIPOCARTERA = '$concepto')
                                    )
                                )
                                ORDER BY 1, 2");
        return $dato->result();
    }

    /*
     * CODIGO DEL PROCESO PARA EL TITULO MAS ANTIGUO
     */

    public function get_codprocesojuridico($cod_recepciontitulo) {
        $this->db->select("R.COD_REGIONAL,CF.COD_CPTO_FISCALIZACION, TE.CODTIPOENTIDAD, TO_CHAR(L.FECHA_LIQUIDACION,'YY') FECHA,RT.COD_RECEPCIONTITULO,F.COD_FISCALIZACION", FALSE);
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("TIPOENTIDAD TE", "TE.CODTIPOENTIDAD = E.COD_TIPOENTIDAD");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("LIQUIDACION L", "L.COD_FISCALIZACION = F.COD_FISCALIZACION");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("RT.COD_RECEPCIONTITULO", $cod_recepciontitulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * INSERTAR CODIGO DE PROCESO TITULOS ACUMULADOS
     */

    public function insertar_acumulacion($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("PROCESOS_COACTIVOS", $datos);
            $this->db->select("PC.COD_PROCESO_COACTIVO");
            $this->db->where("PC.FECHA_CREACION", "(SELECT MAX(PC.FECHA_CREACION) FROM PROCESOS_COACTIVOS PC)", FALSE);
            $dato = $this->db->get("PROCESOS_COACTIVOS PC");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result();
                return $dato[0];
            } else {
                return false;
            }

        endif;
    }

    /*
     * INSERTAR TITULOS RECIBIDOS EN ACUMULACION
     */

    public function insertar_titulosacumulacion($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("ACUMULACION_COACTIVA", $datos);
        endif;
    }

    /*
     * ENCABEZADO
     */

    function cabecera($respuesta, $proceso) {
        $this->db->select('');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }
     /*
     * ENCABEZADO
     */

    function cabecera_titulo($cod_titulo) {
        $this->db->select('');
        $this->db->from('VW_RECEPCIONTITULOS VW');
        $this->db->where('VW.NO_EXPEDIENTE', $cod_titulo);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }
     function encabezado($cod_titulo) {
        $this->db->select('');
        $this->db->from('VW_RECEPCIONTITULOS VW');
        $this->db->where('VW.NO_EXPEDIENTE', $cod_titulo);
        $resultado = $this->db->get();
        if ($resultado->num_rows() > 0) {
                $dato = $resultado->result();
                return $dato[0];
            } else {
                return false;
            }
    }

}
