<?php

/**
 * Modelo Consultartitulos. Contiene las consultas que son usadas por el controlador consultartitulos.php
 */
class Comunicadopj extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->cod_secretario_coactivo = 41;
        $this->cod_coordinador_coactivo = 42;
        $this->cod_abogado_coactivo = 43;
        $this->cod_director = 61;
    }

    function set_estado($cod_estado) {
        $this->cod_estado = $cod_estado;
    }

    function set_titulo($cod_titulo) {
        $this->cod_titulo = $cod_titulo;
    }

    function set_regional($cod_regional) {
        $this->cod_regional = $cod_regional;
    }

    function set_fiscalizacion($cod_fiscalizacion) {
        $this->cod_fiscalizacion = $cod_fiscalizacion;
    }

    /**
     * Funci?n Empresa. Retorna la raz?n social y el tipo de documento de una empresa buscando por el nit de la misma
     * @param string $nit
     * @return mixed consulta
     */
    function Empresa($nit) {
        $this->db->select("RAZON_SOCIAL, TIPODOCUMENTO.NOMBRETIPODOC");
        $this->db->from("EMPRESA");
        $this->db->where("CODEMPRESA", $this->nit);
        $this->db->join('TIPODOCUMENTO', 'TIPODOCUMENTO.CODTIPODOCUMENTO = EMPRESA.COD_TIPODOCUMENTO');
        $consulta = $this->db->get();
        return $consulta;
    }

    /**
     * Funci?n Datetable. Retorna los datos de las fiscalizaciones de una empresa, marcadas como cobro coactivo, buscando por el nit de la misma
     * @param string $nit
     * @return mixed $consulta 
     */
    function Datatable() {

//        $this->db->select('FI.COD_FISCALIZACION AS EXPEDIENTE,EM.CODEMPRESA, EM.NOMBRE_EMPRESA, CF.NOMBRE_CONCEPTO, TG.TIPOGESTION');
//        $this->db->from('FISCALIZACION FI, CONCEPTOSFISCALIZACION CF, TIPOGESTION TG, ASIGNACIONFISCALIZACION AF, EMPRESA EM');
//        $this->db->where('CF.COD_CPTO_FISCALIZACION = FI.COD_CONCEPTO');
//        $this->db->where('FI.COD_TIPOGESTION = TG.COD_GESTION');
//        $this->db->where('FI.COD_TIPOGESTION = TG.COD_GESTION');
//        $this->db->where('FI.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION');
//        $this->db->where('AF.NIT_EMPRESA = EM.CODEMPRESA');
//        $this->db->where('FI.COD_FISCALIZACION NOT IN (
//                        SELECT F.COD_FISCALIZACION
//                        FROM FISCALIZACION F
//                        JOIN RECEPCIONTITULOS RT ON RT.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION
//                        JOIN TITULOS T ON T.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO
//                        WHERE T.COD_TIPORESPUESTA != 170)');
        // $this->db->where("LOWER(AF.NIT_EMPRESA) = $nit");

        define('COD_RECEPCION', '1112');
        define('TITULO_INCOMPLETO', '171');

        $codigos = array('1112', '171');


        $this->db->select('RT.COD_FISCALIZACION_EMPRESA AS EXPEDIENTE, EM.CODEMPRESA, EM.NOMBRE_EMPRESA, CF.NOMBRE_CONCEPTO, RG.NOMBRE_GESTION AS TIPOGESTION');
        $this->db->from('FISCALIZACION FI, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, EMPRESA EM, RECEPCIONTITULOS RT, ASIGNACIONFISCALIZACION AF');
        $this->db->where_in("RT.COD_TIPORESPUESTA", $codigos);
        $this->db->where('RT.NIT_EMPRESA = EM.CODEMPRESA');
        $this->db->where('FI.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION');
        $this->db->where('FI.COD_ASIGNACION_FISC = RT.COD_FISCALIZACION_EMPRESA');
        $this->db->where('FI.COD_CONCEPTO = CF.COD_CPTO_FISCALIZACION');
        $this->db->where('RT.COD_TIPORESPUESTA = RG.COD_RESPUESTA');




        $consulta = $this->db->get();
        return $consulta;
    }

    function Recepcion($expediente, $nit, $observaciones, $aceptado, $data) {

        if ($aceptado == 'S') {
            $cod = '1113';
        } else {
            $cod = '1114';
        }

        $this->db->trans_begin();
        $this->db->where('NIT_EMPRESA', $nit);
        $this->db->where('COD_FISCALIZACION_EMPRESA', $expediente);
        $this->db->set("OBSERVACIONES", utf8_encode($observaciones));
        $this->db->set("COD_TIPORESPUESTA", $cod);
        $dato = $this->db->update("RECEPCIONTITULOS");

        $this->db->where('NIT_EMPRESA', $nit);
        $this->db->where('COD_FISCALIZACION_EMPRESA', $expediente);
        $consultas = $this->db->get('RECEPCIONTITULOS');
        foreach ($consultas->result_array() as $consulta) {
            $id_recepcion = $consulta['COD_RECEPCIONTITULO'];
        }

        foreach ($data as $key => $value) {
            $this->db->where('NOMBRE_DOCUMENTO', $key);
            $this->db->where('COD_RECEPCIONTITULO', $id_recepcion);
            $consultas = $this->db->get('TITULOS');
            //print_r($consultas);
            //exit();
            if (sizeof($consultas->result_array()) > 0) {
                $this->db->where('NOMBRE_DOCUMENTO', $key);
                $this->db->where('COD_RECEPCIONTITULO', $id_recepcion);
                $this->db->where('REVISADO', 'N');
                $this->db->set('REVISADO', 'S');
                $this->db->update('TITULOS');
            } else {
                $this->db->set('COD_RECEPCIONTITULO', $id_recepcion);
                $this->db->set('NOMBRE_DOCUMENTO', $key);
                $this->db->set('REVISADO', 'S');
                $this->db->insert('TITULOS');
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_titulotrazar($cod_titulo) {
        $this->db->select("RT.NIT_EMPRESA, RT.COD_FISCALIZACION_EMPRESA,TG.COD_GESTION, RG.COD_RESPUESTA");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->where("RT.COD_RECEPCIONTITULO", $cod_titulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_numprocesoadjudicado($cod_titulo) {
        $this->db->select("F.CODIGO_PJ");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->where("RT.COD_RECEPCIONTITULO", $cod_titulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_documento_notificar() {
        $this->db->select("F.COD_FISCALIZACION,RT.COD_RECEPCIONTITULO, ST.NOMBRE_DOCUMENTO");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("SOPORTE_RECEPCIONTITULOS ST", "ST.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO");
        $this->db->join("GESTIONCOBRO GC", "GC.COD_GESTION_COBRO = ST.COD_GESTION_COBRO");
        $this->db->where("GC.COD_TIPO_RESPUESTA", '755');
        $this->db->where("RT.COD_RECEPCIONTITULO", $this->cod_titulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_correo_autorizacion() {
        $this->db->select("NC.NOMBRE_CONTACTO,NC.EMAIL_AUTORIZADO");
        $this->db->where("NC.AUTORIZA", 'S');
        $this->db->where("NC.COD_FISCALIZACION", $this->cod_fiscalizacion);
        $dato = $this->db->get("AUTORIZACION_NOTI_EMAIL NC");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    public function get_auto2dato1($cod_titulo) {
        $dato = $this->db->query("SELECT R.NOMBRE_REGIONAL, F.COD_FISCALIZACION, T.COD_RECEPCIONTITULO, TO_CHAR(SR.FECHA_CARGA, 'YYYY') AS A_RES, TO_CHAR(SR.FECHA_CARGA, 'MM') AS M_RES,TO_CHAR(SR.FECHA_CARGA, 'DD') AS D_RES, E.CODEMPRESA, E.RAZON_SOCIAL
                        FROM TITULOS T
                        JOIN RECEPCIONTITULOS RT ON RT.COD_RECEPCIONTITULO = T.COD_RECEPCIONTITULO
                        JOIN EMPRESA E ON CODEMPRESA = RT.NIT_EMPRESA
                        JOIN REGIONAL R ON R.COD_REGIONAL = E.COD_REGIONAL
                        JOIN FISCALIZACION F ON F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA
                        JOIN SOPORTE_RECEPCIONTITULOS SR ON SR.COD_RECEPCIONTITULO = T.COD_RECEPCIONTITULO
                        WHERE T.COD_RECEPCIONTITULO=$cod_titulo");
        $dato = $dato->result_array();
        $dato = $dato[0];
        $embargos = $this->db->query("SELECT MC_TIPOBIEN.NOMBRE_TIPO, TO_CHAR(MC_MEDIDASPRELACION.FECHA, 'YYYY') AS A_EMB, TO_CHAR(MC_MEDIDASPRELACION.FECHA, 'MM') AS M_EMB, TO_CHAR(MC_MEDIDASPRELACION.FECHA, 'DD') AS D_EMB
                                FROM MC_MEDIDASCAUTELARES
                                JOIN MC_MEDIDASPRELACION ON MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR=MC_MEDIDASPRELACION.COD_MEDIDACAUTELAR
                                JOIN MC_TIPOBIEN ON MC_TIPOBIEN.COD_TIPOBIEN=MC_MEDIDASPRELACION.COD_CONCURRENCIA
                                WHERE MC_MEDIDASPRELACION.COD_TIPOGESTION IN (617,378,379)");
        $embargos = $embargos->result_array();
        $embargos = $embargos[0];
        $usuario = $this->ion_auth->user()->row();
        $dato["A_EMB"] = $embargos["A_EMB"];
        $dato["M_EMB"] = $embargos["M_EMB"];
        $dato["D_EMB"] = $embargos["D_EMB"];
        $dato["NOMBRES"] = $usuario->NOMBRES;
        $dato["APELLIDOS"] = $usuario->APELLIDOS;
        $dato["N_DIA"] = date("d");
        $dato["N_MES"] = date("m");
        $dato["N_ANIO"] = date("Y");
        $Dia = $this->numeros_letras_model->ValorEnLetras($dato["N_DIA"], "-");
        $Ano = $this->numeros_letras_model->ValorEnLetras($dato["N_ANIO"], "-");
        $Dia = explode('-', $Dia);
        $Ano = explode('-', $Ano);
        $dato["DIA"] = $Dia[0];
        $dato["MES"] = $this->mes(date("m"));
        $dato["ANIO"] = $Ano[0];
        $dato["M_RES"] = $this->mes($dato["M_RES"]);
        return $dato;
    }

    function get_naturalezadeudor($cod_recepciontitulo) {
        $this->db->select("E.COD_TIPODOCUMENTO,TE.NOM_TIPO_ENT");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("TIPODOCUMENTO TD", "TD.CODTIPODOCUMENTO = E.COD_TIPODOCUMENTO");
        $this->db->join("TIPOENTIDAD TE", "TE.CODTIPOENTIDAD = E.COD_TIPOENTIDAD");
        $this->db->where("RT.COD_RECEPCIONTITULO", $cod_recepciontitulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function get_tiposexigibilidad() {
        $this->db->select("TE.COD_TIPOEXIGILIDAD, TE.NOMBRE_TIPOEXIGIBILIDAD");
        $this->db->join("ESTADOS E", "TE.COD_ESTADO = E.IDESTADO");
        $this->db->where("E.IDESTADO", 1);
        $dato = $this->db->get("TIPOSEXIGIBILIDAD TE");
        return $dato->result_array();
    }

    public function get_cualquierproceso($usuario) {
        $this->db->select("RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("F.COD_ABOGADO", $usuario);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_procesotransversal($estado1 = null, $estado2 = null, $estado3 = null) {
        $COD_USUARIO = COD_USUARIO;
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
        $this->db->join("ASIGNACIONFISCALIZACION AF", "AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = AF.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("GESTIONCOBRO GC", "GC.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA");
        $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
        $this->db->join("PROCESO P", "P.CODPROCESO = TG.CODPROCESO");
        $this->db->where("F.COD_ABOGADO", COD_USUARIO);
        $this->db->where("P.COD_MACROPROCESO", 2);
        $this->db->where("GC.FECHA_CONTACTO", "(SELECT MAX (GC.FECHA_CONTACTO)  FROM FISCALIZACION F 
                        JOIN CONCEPTOSFISCALIZACION CF ON CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO 
                        JOIN ASIGNACIONFISCALIZACION AF ON AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC 
                        JOIN EMPRESA E ON E.CODEMPRESA = AF.NIT_EMPRESA 
                        JOIN REGIONAL R ON R.COD_REGIONAL = E.COD_REGIONAL 
                        JOIN GESTIONCOBRO GC ON GC.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION 
                        JOIN RESPUESTAGESTION RG ON RG.COD_RESPUESTA = GC.COD_TIPO_RESPUESTA 
                        JOIN TIPOGESTION TG ON TG.COD_GESTION = RG.COD_TIPOGESTION
                        JOIN PROCESO P ON P.CODPROCESO = TG.CODPROCESO 
                        WHERE F.COD_ABOGADO = $COD_USUARIO AND p.cod_macroproceso = 2)", FALSE);
        $this->db->where("R.COD_REGIONAL", COD_REGIONAL);
        $this->db->select("RG.NOMBRE_GESTION AS RESPUESTA, P.NOMBREPROCESO AS PROCESO, F.COD_FISCALIZACION, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $this->db->group_by("RG.NOMBRE_GESTION , P.NOMBREPROCESO , F.COD_FISCALIZACION, R.COD_REGIONAL, R.NOMBRE_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO");
        $dato = $this->db->get("FISCALIZACION F");
        return $dato->result();
    }

    public function get_tituloestado($estado, $usuario) {
        $this->db->select("RT.COD_TIPORESPUESTA,RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("F.COD_ABOGADO", $usuario);
        $this->db->where("RT.COD_TIPORESPUESTA", $estado);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_multipleestadoAbogado($estado) {

        $cadena = '';
        for ($i = 0; $i < sizeof($estado); $i++) {
            if ($i == (sizeof($estado) - 1)) {
                $cadena = $cadena . "RT.COD_TIPORESPUESTA = $estado[$i]";
            } else {
                $cadena = $cadena . "RT.COD_TIPORESPUESTA = $estado[$i] OR ";
            }
        }
        $this->db->select("RT.COD_TIPORESPUESTA,RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("F.COD_ABOGADO", COD_USUARIO);
        $this->db->where($cadena);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_Abogadoestado($estado = null, $estado2 = null, $estado3 = null) {
        $this->db->select("RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("F.COD_ABOGADO", COD_USUARIO);
        $this->db->where("RT.COD_TIPORESPUESTA", $estado);
        if ($estado2 != null) {
            $this->db->or_where("RT.COD_TIPORESPUESTA", $estado2);
        }
        if ($estado3 != null) {
            $this->db->or_where("RT.COD_TIPORESPUESTA", $estado3);
        }
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_encabezado($cod_recepciontitulo) {
        $this->db->select("R.COD_REGIONAL,RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION AS RESPUESTA,CF.NOMBRE_CONCEPTO,E.DIRECCION ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("RT.COD_RECEPCIONTITULO", $cod_recepciontitulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

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

    public function get_motivosdevolucion() {
        $this->db->select("CAUSALDEVOLUCION.COD_CAUSALDEVOLUCION, CAUSALDEVOLUCION.NOMBRE_CAUSAL");
        $this->db->where('COD_ESTADO', 1);
        $dato = $this->db->get("CAUSALDEVOLUCION");
        return $dato->result_array();
    }

    public function get_tituloestado_notificaciones($estado, $usuario) {
        $this->db->select("RT.COD_RECEPCIONTITULO,R.COD_REGIONAL,R.NOMBRE_REGIONAL,E.CODEMPRESA,E.NOMBRE_EMPRESA,RG.NOMBRE_GESTION, RT.RADICADO_ONBASE, RT.NIS, F.COD_FISCALIZACION, T.NOMBRE_DOCUMENTO");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("TITULOS T", "T.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO");
        $this->db->join("NOTIFICACION_RECEPCIONTITULOS NRT", "NRT.COD_RECEPCIONTITULO = T.COD_RECEPCIONTITULO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = NRT.COD_RESPUESTA");
        $this->db->where("NRT.COD_RESPUESTA", $estado);
        $this->db->where("NRT.ENVIADO_POR", $usuario);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_tituloestado_Unico($estado_nuevo, $usuario) {
        $this->db->select("RT.COD_RECEPCIONTITULO,R.COD_REGIONAL,R.NOMBRE_REGIONAL,E.CODEMPRESA,E.NOMBRE_EMPRESA,RG.NOMBRE_GESTION, RT.RADICADO_ONBASE, RT.NIS, F.COD_FISCALIZACION, T.NOMBRE_DOCUMENTO");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_TIPORESPUESTA");
        $this->db->join("COMUNICACIONES C", "C.COD_RECEPCIONTITULO = T.COD_RECEPCIONTITULO");
        $this->db->where("C.DIRECTOR_ASIGNADO", $usuario);
        $this->db->where("RT.COD_TIPORESPUESTA", $estado_nuevo);
        $this->db->group_by("RT.COD_RECEPCIONTITULO,R.COD_REGIONAL,R.NOMBRE_REGIONAL,E.CODEMPRESA,E.NOMBRE_EMPRESA,RG.NOMBRE_GESTION, RT.RADICADO_ONBASE, RT.NIS, F.COD_FISCALIZACION, T.NOMBRE_DOCUMENTO");
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_titulosecretario($estado = null, $estado2 = null, $estado3 = null) {
        $this->db->select("RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION=F.COD_CONCEPTO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = RT.COD_TIPORESPUESTA");
        $this->db->where("E.COD_REGIONAL", REGIONAL_USUARIO);
        $this->db->where("RT.COD_TIPORESPUESTA", $estado);
        if ($estado2 != null) {
            $this->db->or_where("RT.COD_TIPORESPUESTA", $estado2);
        }
        if ($estado3 != null) {
            $this->db->or_where("RT.COD_TIPORESPUESTA", $estado3);
        }
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_tituloestado_Creado($estado, $usuario) {
        $this->db->select("T.COD_RECEPCIONTITULO,R.COD_REGIONAL,R.NOMBRE_REGIONAL,E.CODEMPRESA,E.NOMBRE_EMPRESA,RG.NOMBRE_GESTION, RT.RADICADO_ONBASE, RT.NIS, F.COD_FISCALIZACION, T.NOMBRE_DOCUMENTO");
        $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
        $this->db->join("REGIONAL R", "R.COD_REGIONAL = E.COD_REGIONAL");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("TITULOS T", "T.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO");
        $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_TIPORESPUESTA");
        $this->db->join("COMUNICACIONES C", "C.COD_RECEPCIONTITULO = T.COD_RECEPCIONTITULO");
        $this->db->where("C.CREADO_POR", $usuario);
        $this->db->where("T.COD_TIPORESPUESTA", $estado);
        $this->db->group_by("T.COD_RECEPCIONTITULO,R.COD_REGIONAL,R.NOMBRE_REGIONAL,E.CODEMPRESA,E.NOMBRE_EMPRESA,RG.NOMBRE_GESTION, RT.RADICADO_ONBASE, RT.NIS, F.COD_FISCALIZACION, T.NOMBRE_DOCUMENTO");
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    public function get_encabezado_documentos() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("F.COD_FISCALIZACION,E.COD_REGIONAL, E.CODEMPRESA, E.NOMBRE_EMPRESA, CF.COD_CPTO_FISCALIZACION, CF.NOMBRE_CONCEPTO, TG.COD_GESTION, TG.TIPOGESTION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.COD_RESPUESTA, RG.NOMBRE_GESTION");
            $this->db->join("RECEPCIONTITULOS RT", "RT.COD_RECEPCIONTITULO = T.COD_RECEPCIONTITULO ");
            $this->db->join("EMPRESA E", "E.CODEMPRESA = RT.NIT_EMPRESA");
            $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
            $this->db->join("RESPUESTAGESTION RG", "RG.COD_RESPUESTA = T.COD_TIPORESPUESTA");
            $this->db->join("TIPOGESTION TG", "TG.COD_GESTION = RG.COD_TIPOGESTION");
            $this->db->join("CONCEPTOSFISCALIZACION CF", "CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO");
            $this->db->where("T.COD_RECEPCIONTITULO", $this->cod_titulo);
            $dato = $this->db->get("TITULOS T");
            $dato = $dato->result_array();
            return $dato[0];
        endif;
    }

    public function get_plantilla_proceso() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.NOMBRE_DOCUMENTO, C.FECHA_DOCUMENTO,ABOGADO,SECRETARIO,EJECUTOR");
            $this->db->where("C.COD_RECEPCIONTITULO", $this->cod_titulo);
            $this->db->where("C.FECHA_DOCUMENTO", "(SELECT MAX(C.FECHA_DOCUMENTO) FROM COMUNICACION_RECEPCIONTITULOS C WHERE C.COD_RECEPCIONTITULO = $this->cod_titulo )", FALSE);
            $dato = $this->db->get("COMUNICACION_RECEPCIONTITULOS C");
            $dato = $dato->result_array();
            return $dato[0];
        endif;
    }

    public function get_estadoscomentarios_proceso() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.COD_ESTADO");
            $this->db->where("C.COD_RECEPCIONTITULO", $this->cod_titulo);
            $dato = $this->db->get("COMUNICACIONES C");
            $dato = $dato->result_array();
            return $dato;

        endif;
    }

    public function get_comentarios_proceso($cod_titulo) {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.COMENTARIOS, TO_CHAR(C.FECHA_DOCUMENTO, 'YYYY/MM/DD HH:MM:SS') FECHA_DOCUMENTO, U.NOMBRES, U.APELLIDOS",FALSE);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.COMENTADO_POR");
            $this->db->where("C.COD_RECEPCIONTITULO", $cod_titulo);
            $dato = $this->db->get("COMUNICACION_RECEPCIONTITULOS C");
            if ($dato->num_rows() > 0) {
                return $dato->result_array();
            }
        endif;
    }

    public function get_comentarios_finproceso() {
        if (!empty($this->cod_titulo)) :
            $dato = $this->db->query("SELECT C.COMENTARIOS, TO_CHAR(C.FECHA_DOCUMENTO, 'YYYY/MM/DD HH:MM:SS') AS FECHA_DOCUMENTO, C.CREADO_POR, U.NOMBRES, U.APELLIDOS FROM COMUNICACIONES C JOIN USUARIOS U ON U.IDUSUARIO = C.CREADO_POR WHERE C.COD_RECEPCIONTITULO = '$this->cod_titulo' AND (C.COD_ESTADO=757 OR C.COD_ESTADO=758 OR C.COD_ESTADO=759 OR C.COD_ESTADO=760 OR C.COD_ESTADO=761 OR C.COD_ESTADO=762)");
            $dato = $dato->result_array();
            return $dato;
        endif;
    }

    public function get_coordinador_proceso() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.EJECUTOR,U.NOMBRES, U.APELLIDOS");
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.EJECUTOR");
            $this->db->where("C.COD_RECEPCIONTITULO", $this->cod_titulo);
            $dato = $this->db->get("COMUNICACION_RECEPCIONTITULOS C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_secretario_proceso() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.SECRETARIO, U.NOMBRES, U.APELLIDOS");
            $this->db->where("C.COD_RECEPCIONTITULO", $this->cod_titulo);
            $this->db->join("USUARIOS U", "U.IDUSUARIO = C.SECRETARIO");
            $dato = $this->db->get("COMUNICACION_RECEPCIONTITULOS C");
            if ($dato->num_rows() > 0) {
                $dato = $dato->result_array();
                return $dato[0];
            }
        endif;
    }

    public function get_ultimaplantilla() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.NOMBRE_DOCUMENTO, C.FECHA_DOCUMENTO, C.COD_FISCALIZACION, C.ASIGNADO_A");
            $this->db->where("C.COD_RECEPCIONTITULO", $this->cod_titulo);
            $this->db->where("C.COD_ESTADO", $this->cod_estado);
            $this->db->where("C.FECHA_DOCUMENTO", "(SELECT MAX(C.FECHA_DOCUMENTO) FROM COMUNICACIONES C WHERE C.COD_RECEPCIONTITULO = $this->cod_titulo )", FALSE);
            $dato = $this->db->get("COMUNICACIONES C");
            $dato = $dato->result_array();
            return $dato[0];
        endif;
    }

    public function get_comentarios_preaprobacion() {
        if (!empty($this->cod_titulo)) :
            $this->db->select("C.COMENTARIOS, C.FECHA_DOCUMENTO, C.COD_FISCALIZACION, C.ASIGNADO_A");
            $this->db->where("C.COD_RECEPCIONTITULO", $this->cod_titulo);
            $this->db->where("C.COD_ESTADO", $this->cod_estado);
            $dato = $this->db->get("COMUNICACIONES C");
            return $dato->result_array();
        endif;
    }

    public function get_director_coordinador_regional() {
        if (!empty($this->cod_regional)) :
            $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
            $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
            $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
            $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
            $this->db->where("G.IDGRUPO", $this->cod_coordinador_coactivo);
            $this->db->or_where("G.IDGRUPO", $this->cod_director);
            $this->db->where("R.COD_REGIONAL", $this->cod_regional);
            $dato = $this->db->get("USUARIOS U");
            if ($dato->num_rows() > 0) {
                return $dato->result();
            }
        endif;
    }

    public function get_secretario_regional() {
        if (!empty($this->cod_regional)) :
            $this->db->select("U.NOMBRES,U.APELLIDOS,U.IDUSUARIO");
            $this->db->join("REGIONAL R", "R.COD_REGIONAL = U.COD_REGIONAL");
            $this->db->join("USUARIOS_GRUPOS UG", "UG.IDUSUARIO = U.IDUSUARIO");
            $this->db->join("GRUPOS G", "G.IDGRUPO = UG.IDGRUPO");
            $this->db->where("G.IDGRUPO", $this->cod_secretario_coactivo);
            $this->db->where("R.COD_REGIONAL", $this->cod_regional);
            $dato = $this->db->get("USUARIOS U");
            if ($dato->num_rows() > 0) {
                return $dato->result();
            }
        endif;
    }

    /*
     * INSERTAR UN DOCUMENTO
     */

    public function insertar_comunicacion($datos = NULL) {
        $this->db->insert("COMUNICACION_RECEPCIONTITULOS", $datos);
    }

    /*
     * INSERTAR UNA NOTIFICACION
     */

    public function insertar_notificacion($datos = NULL) {
        $this->db->insert("NOTIFICACION_RECEPCIONTITULOS", $datos);
    }

    /*
     * ACTUALIZAR EL ESTADO DEL TITULO DESPUES DE UNA GESTION
     */

    public function actualizacion_estado_titulo($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_RECEPCIONTITULO", $datos['COD_RECEPCIONTITULO']);
            unset($datos['COD_RECEPCIONTITULO']);
            $this->db->update("RECEPCIONTITULOS", $datos);
        endif;
    }

    public function actualizacion_fiscalizacion($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_FISCALIZACION", $datos['COD_FISCALIZACION']);
            unset($datos['COD_FISCALIZACION']);
            $this->db->update("FISCALIZACION", $datos);
        endif;
    }

    public function actualizacion_notificacion($datos) {
        if (!empty($datos)) :
            $traza = $this->get_titulotrazar($datos["COD_RECEPCIONTITULO"]);
            trazar($traza["COD_GESTION"], $traza["COD_RESPUESTA"], $traza["COD_FISCALIZACION_EMPRESA"], $traza["NIT_EMPRESA"], 'S', '');
            $this->db->where("COD_RECEPCIONTITULO", $datos['COD_RECEPCIONTITULO']);
            unset($datos['COD_RECEPCIONTITULO']);
            $this->db->update("NOTIFICACION_RECEPCIONTITULOS", $datos);
        endif;
    }

    public function actualizacion_notifentregada($datos) {
        if (!empty($datos)) :
            $traza = $this->get_titulotrazar($datos["COD_RECEPCIONTITULO"]);
            trazar($traza["COD_GESTION"], $traza["COD_RESPUESTA"], $traza["COD_FISCALIZACION_EMPRESA"], $traza["NIT_EMPRESA"], 'S', '-1');
            $this->db->where("COD_RECEPCIONTITULO", $datos['COD_RECEPCIONTITULO']);
            unset($datos['COD_RECEPCIONTITULO']);
            $this->db->set('FECHA_RECIBIDO', "TO_DATE('" . $datos['FECHA_RECIBIDO'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
            unset($datos['FECHA_RECIBIDO']);
            $this->db->update("NOTIFICACION_RECEPCIONTITULOS", $datos);
        endif;
    }

    public function actualizacion_notifdevuelta($datos) {
        if (!empty($datos)) :
            $traza = $this->get_titulotrazar($datos["COD_RECEPCIONTITULO"]);
            trazar($traza["COD_GESTION"], $traza["COD_RESPUESTA"], $traza["COD_FISCALIZACION_EMPRESA"], $traza["NIT_EMPRESA"], 'S', '-1');
            $this->db->where("COD_RECEPCIONTITULO", $datos['COD_RECEPCIONTITULO']);
            unset($datos['COD_RECEPCIONTITULO']);
            $this->db->set('FECHA_DEVOLUCION', "TO_DATE('" . $datos['FECHA_DEVOLUCION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
            unset($datos['FECHA_DEVOLUCION']);
            $this->db->update("NOTIFICACION_RECEPCIONTITULOS", $datos);
        endif;
    }

    public function insertar_expediente_soporte($datos = NULL) {
        $this->db->set('FECHA_CARGA', "TO_DATE('" . $datos['FECHA_CARGA'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CARGA']);
        $this->db->insert("SOPORTE_RECEPCIONTITULOS", $datos);
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

    public function insertar_exigibilidadtitulo($datos = NULL) {
        $traza = $this->get_titulotrazar($datos["COD_RECEPCIONTITULO"]);
        trazar($traza["COD_GESTION"], $traza["COD_RESPUESTA"], $traza["COD_FISCALIZACION_EMPRESA"], $traza["NIT_EMPRESA"], 'S', '-1');
        $this->db->set('FECHA_CONFIRMACION', "TO_DATE('" . $datos['FECHA_CONFIRMACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CONFIRMACION']);
        $this->db->insert("EXIGIBILIDADTITULOS", $datos);
    }

    public function insertar_cobro_persuasivo($datos = NULL) {
        $traza = $this->get_titulotrazar($datos["COD_RECEPCIONTITULO"]);
        $Gestion_cobro = trazar($traza["COD_GESTION"], $traza["COD_RESPUESTA"], $traza["COD_FISCALIZACION_EMPRESA"], $traza["NIT_EMPRESA"], 'S', '-1');
        $datos['COD_GESTION_COBRO'] = $Gestion_cobro["COD_GESTION_COBRO"];
        $this->db->set('FECHA_CREACION', "TO_DATE('" . $datos['FECHA_CREACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CREACION']);
        $datos['COD_FISCALIZACION'] = $traza["COD_FISCALIZACION_EMPRESA"];
        $datos['NIT_EMPRESA'] = $traza["NIT_EMPRESA"];
        $this->db->insert("COBROPERSUASIVO", $datos);
    }

    private function mes($mes) {
        switch ($mes) :
            case "01" : return "ENERO";
                break;
            case "02" : return "FEBRERO";
                break;
            case "03" : return "MARZO";
                break;
            case "04" : return "ABRIL";
                break;
            case "05" : return "MAYO";
                break;
            case "06" : return "JUNIO";
                break;
            case "07" : return "JULIO";
                break;
            case "08" : return "AGOSTO";
                break;
            case "09" : return "SEPTIEMBRE";
                break;
            case "10" : return "OCTUBRE";
                break;
            case "11" : return "NOVIEMBRE";
                break;
            case "12" : return "DICIEMBRE";
                break;
        endswitch;
    }

//    function permiso($id_usuario) {
//        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO,USUARIOS.COD_REGIONAL, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO");
//        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
//        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
//        $this->db->where("GRUPOS.IDGRUPO", COORDINADOR, FALSE);
//        $this->db->where("USUARIOS.IDUSUARIO", $id_usuario);
//        $dato = $this->db->get("USUARIOS");
//        return $dato->result_array;
//    }
//    function abogado($id_usuario) {
//        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
//        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", ABOGADO);
//        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
//        $dato = $this->db->get("USUARIOS_GRUPOS");
//        if ($dato->num_rows() == 0) {
//            return FALSE;
//        } else {
//            return $dato->num_rows();
//        }
//    }
//    function coordinador($id_usuario) {
//        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
//        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", COORDINADOR);
//        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
//        $dato = $this->db->get("USUARIOS_GRUPOS");
//        if ($dato->num_rows() == 0) {
//            return FALSE;
//        } else {
//            return $dato->num_rows();
//        }
//    }

    function consulta_titulos($reg, $search, $regional) {
        $cod_respuesta = array('170', '172');
        $this->load->library('datatables');
        $this->db->select('RECEPCIONTITULOS.COD_RECEPCIONTITULO,RECEPCIONTITULOS.FECHA_CONSULTAONBASE,'
                . 'REGIONAL.NOMBRE_REGIONAL, RECEPCIONTITULOS.NIT_EMPRESA, RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,'
                . 'RECEPCIONTITULOS.COD_TIPORESPUESTA, EMPRESA.COD_REGIONAL, FISCALIZACION.COD_ASIGNACION_FISC,'
                . 'EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, FISCALIZACION.COD_CONCEPTO,'
                . 'CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,ASIGNACIONFISCALIZACION.ASIGNADO_A,'
                . ' USUARIOS.NOMBRES, USUARIOS.APELLIDOS');
        $this->db->join('EMPRESA', 'RECEPCIONTITULOS.NIT_EMPRESA=EMPRESA.CODEMPRESA ', 'inner');
        $this->db->join('FISCALIZACION ', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC', 'inner');
        $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=FISCALIZACION.COD_ABOGADO', 'left');
        $this->db->join('REGIONAL ', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->where('REGIONAL.COD_REGIONAL', $regional, FALSE);
        $this->db->where('FISCALIZACION.COD_ABOGADO IS NULL');
        // $this->db->where('FISCALIZACION.COD_ABOGADO IN (SELECT USUARIOS.IDUSUARIO  FROM USUARIOS WHERE FISCALIZACION.COD_ABOGADO =USUARIOS.IDUSUARIO)  OR (FISCALIZACION.COD_ABOGADO IS NULL)', NULL, false);
        // $this->db->where('(RECEPCIONTITULOS.COD_TIPORESPUESTA <> 1114 AND  RECEPCIONTITULOS.COD_TIPORESPUESTA <> 1112)');
        $this->db->where_in('RECEPCIONTITULOS.COD_TIPORESPUESTA', $cod_respuesta);
        $this->db->group_by('RECEPCIONTITULOS.COD_RECEPCIONTITULO,RECEPCIONTITULOS.FECHA_CONSULTAONBASE,'
                . 'REGIONAL.NOMBRE_REGIONAL, RECEPCIONTITULOS.NIT_EMPRESA, RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,'
                . 'RECEPCIONTITULOS.COD_TIPORESPUESTA, EMPRESA.COD_REGIONAL, FISCALIZACION.COD_ASIGNACION_FISC,'
                . 'EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, FISCALIZACION.COD_CONCEPTO,'
                . 'CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,ASIGNACIONFISCALIZACION.ASIGNADO_A,'
                . 'USUARIOS.NOMBRES, USUARIOS.APELLIDOS');
        $this->db->order_by('RECEPCIONTITULOS.COD_RECEPCIONTITULO', 'ASC');

        $dato = $this->db->get('RECEPCIONTITULOS');

        if ($dato->num_rows() == 0)
            return '0';
        return $dato->result_array;
    }

    function consulta_abogado($regional) {
        $this->db->select("USUARIOS.NOMBRES, USUARIOS.APELLIDOS, USUARIOS.IDUSUARIO");
        $this->db->join('USUARIOS_GRUPOS', ' USUARIOS_GRUPOS.IDUSUARIO=USUARIOS.IDUSUARIO', 'inner');
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", ABOGADO);
        $this->db->where("USUARIOS.COD_REGIONAL", $regional);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array;
    }

    function guardar_asignacion($datos) {

        $fecha = 'SYSDATE';
        $titulos = count($datos['post']['asignar']);
        // echo $titulos;echo "<br>";
        $this->db->set('COD_TIPORESPUESTA', 173, FALSE);
        $this->db->where('COD_RECEPCIONTITULO', $datos['post']['cod_recepcion'], FALSE);
        $this->db->update('13RECEPCIONTITULOS');
        for ($i = 0; $i < $titulos; $i++) {

            // echo $datos['post']['asignar'][$i];"<br>";
            $this->db->set('COD_ABOGADO', $datos['post']['abogado'], FALSE);
            $this->db->set('FECHA_ASIGNACION_ABOGADO', $fecha, FALSE);
            $this->db->where('COD_FISCALIZACION', $datos['post']['asignar'][$i], FALSE);
            $this->db->update('13FISCALIZACION');
        }

        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function recepcion_titulos($reg, $search, $regional, $id_abogado) {
        $this->load->library('datatables');
        $this->db->select('RECEPCIONTITULOS.COD_RECEPCIONTITULO,RECEPCIONTITULOS.FECHA_CONSULTAONBASE,'
                . 'REGIONAL.NOMBRE_REGIONAL, RECEPCIONTITULOS.NIT_EMPRESA, '
                . 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA, EMPRESA.COD_REGIONAL, FISCALIZACION.COD_ASIGNACION_FISC,'
                . 'EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, FISCALIZACION.COD_CONCEPTO,'
                . 'CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,ASIGNACIONFISCALIZACION.ASIGNADO_A, '
                . 'EMPRESA.REPRESENTANTE_LEGAL');
        $this->db->join('EMPRESA', 'RECEPCIONTITULOS.NIT_EMPRESA=EMPRESA.CODEMPRESA ', 'inner');
        $this->db->join('FISCALIZACION ', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('ASIGNACIONFISCALIZACION ', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC', 'inner');
        $this->db->join('REGIONAL ', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->where('REGIONAL.COD_REGIONAL', $regional, FALSE);
        $this->db->where('RECEPCIONTITULOS.COD_TIPORESPUESTA', '173');
        $this->db->where('FISCALIZACION.COD_ABOGADO', $id_abogado);
        $this->db->order_by('RECEPCIONTITULOS.COD_RECEPCIONTITULO', 'ASC');
        $dato = $this->db->get('RECEPCIONTITULOS');

        if ($dato->num_rows() == 0)
            return '0';
        return $dato->result_array;
    }

    function consulta_titulos_ejecutivos($reg, $search, $id_recepcion) {
        $this->db->select('TITULOS.COD_TITULO,TITULOS.COD_RECEPCIONTITULO,TITULOS.RUTA_ARCHIVO, TITULOS.FECHA_CARGA, REGIONAL.NOMBRE_REGIONAL, '
                . 'RECEPCIONTITULOS.COD_TIPORESPUESTA,TITULOS.NOMBRE_DOCUMENTO, '
                . 'RESPUESTAGESTION.NOMBRE_GESTION AS NOMBRE_ESTADOTITULO,'
                . 'RECEPCIONTITULOS.NIT_EMPRESA,TITULOS.NUM_RADICADO,TITULOS.NIS,'
                . 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL, EMPRESA.COD_REGIONAL, FISCALIZACION.COD_ASIGNACION_FISC,'
                . 'EMPRESA.NOMBRE_EMPRESA,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, FISCALIZACION.COD_CONCEPTO,'
                . 'CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,ASIGNACIONFISCALIZACION.ASIGNADO_A ');
        $this->db->join('RECEPCIONTITULOS', 'TITULOS.COD_RECEPCIONTITULO=RECEPCIONTITULOS.COD_RECEPCIONTITULO', 'inner');
        $this->db->join('EMPRESA', 'RECEPCIONTITULOS.NIT_EMPRESA=EMPRESA.CODEMPRESA ', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('FISCALIZACION', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('ASIGNACIONFISCALIZACION', 'ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION  ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RECEPCIONTITULOS.COD_TIPORESPUESTA=RESPUESTAGESTION.COD_RESPUESTA', 'inner');
        $this->db->where('RECEPCIONTITULOS.COD_TIPORESPUESTA', TITULO_ACEPTADO, FALSE);
        $this->db->where('TITULOS.COD_RECEPCIONTITULO', $id_recepcion, FALSE);
        $this->db->order_by('TITULOS.COD_TITULO', 'ASC');
        $dato = $this->db->get('TITULOS');

        if ($dato->num_rows() == 0)
            return '0';
        return $dato->result_array;
    }

    function guardar_detalle_ejec($datos) {

        $this->db->set('COD_TIPORESPUESTA', $datos['post']['estado'], FALSE);
        $this->db->set('OBSERVACIONES', $datos['post']['observaciones']);
        $this->db->where('COD_RECEPCIONTITULO', $datos['post']['id_recepcion'], FALSE);
        $this->db->update('13RECEPCIONTITULOS');


        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function getTitulos($nit, $expediente) {
        $existentes = array();
        $this->db->where('NIT_EMPRESA', $nit);
        $this->db->where('COD_FISCALIZACION_EMPRESA', $expediente);
        $dato = $this->db->get('RECEPCIONTITULOS');
        foreach ($dato->result_array as $value) {
            $cod_recepcion = $value['COD_RECEPCIONTITULO'];
        }
        $this->db->where('COD_RECEPCIONTITULO', $cod_recepcion);
        $titulos = $this->db->get('TITULOS');
        foreach ($titulos->result_array as $titulo) {
            $nombre = explode(' ', strtolower($titulo['NOMBRE_DOCUMENTO']));
            array_push($existentes, array('titulo' => $nombre[0], 'ruta' => $titulo['RUTA_ARCHIVO']));
        }
        return $existentes;
    }

}
