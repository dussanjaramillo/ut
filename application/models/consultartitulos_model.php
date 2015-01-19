<?php

/**
 * Modelo Consultartitulos. Contiene las consultas que son usadas por el controlador consultartitulos.php
 */
class Consultartitulos_model extends CI_Model {

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

    function Recepcion($expediente, $nit, $observaciones, $aceptado, $data) {
        if ($aceptado == 'S') {
            //  $cod = '1113';
            $cod = '172';
            $this->db->trans_begin();
            $this->db->where('NIT_EMPRESA', $nit);
            $this->db->where('COD_FISCALIZACION_EMPRESA', $expediente);
            $this->db->or_where('COD_CARTERA_NOMISIONAL', $expediente);
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
        } else {
            //$cod = '1114';
            $cod = '171';
            $this->db->trans_begin();
            $this->db->where('NIT_EMPRESA', $nit);
            $this->db->where('COD_FISCALIZACION_EMPRESA', $expediente);
            $this->db->or_where('COD_CARTERA_NOMISIONAL', $expediente);
            $this->db->set("OBSERVACIONES", utf8_encode($observaciones));
            $this->db->set("COD_TIPORESPUESTA", $cod);
            $dato = $this->db->update("RECEPCIONTITULOS");
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function traechecklist($concepto) {
        $cod = '';
        $this->db->where('NOMBRE_CONCEPTO', $concepto);
        $consulta = $this->db->get('CONCEPTO_CHECKLIST')->result_array();
        if (sizeof($consulta) > 0) {
            $cod = $consulta[0]['COD_CONCEPTO'];
        }

        $this->db->where('ID_CONCEPTO', $cod);
        $this->db->where('ACTIVO', 1);
        $this->db->order_by('ORDEN', "asc");
        $consulta = $this->db->get('MAESTRO_CHECKLIST')->result_array();
        return $consulta;
    }

    /**
     * Funci?n Empresa. Retorna la raz?n social y el tipo de documento de una empresa buscando por el nit de la misma
     * @param string $nit
     * @return mixed consulta
     */
    function Empresa($nit) {
        $this->db->select("EJECUTADO RAZON_SOCIAL, NOMBRE_TIPO_DOCUMENTO NOMBRETIPODOC, IDENTIFICACION NIT");
        $this->db->from("VW_RECEPCIONTITULOS");
        $this->db->where("IDENTIFICACION", $this->nit);
        $consulta = $this->db->get();
        return $consulta;
    }

    /**
     * Función Datatable. Retorna los datos de las fiscalizaciones de una empresa, marcadas como cobro coactivo, buscando por el nit de la misma
     * @param string $nit
     * @return mixed $consulta 
     */
    function Datatable($regional) {
        define('COD_RECEPCION', '1112');
        define('TITULO_INCOMPLETO', '171');

        $codigos = array('1112', '171');
        $query = "SELECT DISTINCT RT.COD_RECEPCIONTITULO AS COD_RECEPCION,RT.COD_FISCALIZACION_EMPRESA AS EXPEDIENTE, E.CODEMPRESA AS IDENTIFICACION, E.RAZON_SOCIAL AS EJECUTADO,
            E.REPRESENTANTE_LEGAL AS REPRESENTANTE, E.TELEFONO_FIJO AS TELEFONO, CF.NOMBRE_CONCEPTO AS CONCEPTO, TP.TIPO_PROCESO AS PROCESO,
            RG.NOMBRE_GESTION RESPUESTA, E.DIRECCION AS DIRECCION
            FROM RECEPCIONTITULOS RT,
                  FISCALIZACION F,
                  ASIGNACIONFISCALIZACION AF,
                  EMPRESA E,
                  CONCEPTOSFISCALIZACION CF,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP

            WHERE (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO)
                  AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                  AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                  AND (E.CODEMPRESA = AF.NIT_EMPRESA)                               
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)                             
                  AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
                  AND (RT.COD_TIPORESPUESTA IN ('1112','171'))
               
            UNION(
            SELECT DISTINCT RT.COD_RECEPCIONTITULO AS COD_RECEPCION, TO_CHAR(RT.COD_CARTERA_NOMISIONAL) AS EXPEDIENTE, E.COD_ENTIDAD,E.RAZON_SOCIAL, 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION
            FROM  RECEPCIONTITULOS RT,
                  CNM_CARTERANOMISIONAL NM,
                  CNM_EMPRESA E,
                  TIPOCARTERA TC,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                  AND (E.COD_ENTIDAD = NM.COD_EMPRESA)
                  AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                  AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                  AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
               AND (RT.COD_TIPORESPUESTA IN ('1112','171'))
                 
            UNION(
                SELECT DISTINCT RT.COD_RECEPCIONTITULO AS COD_RECEPCION, TO_CHAR(RT.COD_CARTERA_NOMISIONAL) AS EXPEDIENTE,TO_CHAR(E.IDENTIFICACION), E.NOMBRES || E.APELLIDOS , 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION
                FROM RECEPCIONTITULOS RT,
                      CNM_CARTERANOMISIONAL NM,
                      CNM_EMPLEADO E,
                      TIPOCARTERA TC,
                      RESPUESTAGESTION RG,
                      TIPOGESTION TG,
                      TIPOPROCESO TP
                WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                      AND (E.IDENTIFICACION = NM.COD_EMPLEADO)
                      AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                      AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                      AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                      AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
                     AND (RT.COD_TIPORESPUESTA IN ('1112','171'))
                )
            )
            ORDER BY 1, 2";

        $consulta = $this->db->query($query);
        return $consulta;
    }

    /**
     * Función Datatable. Retorna los datos de las fiscalizaciones de una empresa, marcadas como cobro coactivo, buscando por el nit de la misma
     * @param string $nit
     * @return mixed $consulta 
     */
    function Datatable2($regional, $cod_titulo) {
        $query = "SELECT DISTINCT RT.COD_RECEPCIONTITULO AS COD_RECEPCION,RT.COD_FISCALIZACION_EMPRESA AS EXPEDIENTE, E.CODEMPRESA AS IDENTIFICACION, E.RAZON_SOCIAL AS EJECUTADO,
            E.REPRESENTANTE_LEGAL AS REPRESENTANTE, E.TELEFONO_FIJO AS TELEFONO, CF.NOMBRE_CONCEPTO AS CONCEPTO, TP.TIPO_PROCESO AS PROCESO,
            RG.NOMBRE_GESTION RESPUESTA, E.DIRECCION AS DIRECCION, TG.TIPOGESTION  
            FROM RECEPCIONTITULOS RT,
                  FISCALIZACION F,
                  ASIGNACIONFISCALIZACION AF,
                  EMPRESA E,
                  CONCEPTOSFISCALIZACION CF,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP

            WHERE (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO)
                  AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                  AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                  AND (E.CODEMPRESA = AF.NIT_EMPRESA)                               
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)                             
                  AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
                  AND (RT.COD_TIPORESPUESTA IN ('1112','171'))";
        if ($cod_titulo != '')
            $query .= " AND (RT.COD_RECEPCIONTITULO=" . $cod_titulo . ")";
        $query .= "       
            UNION(
            SELECT DISTINCT RT.COD_RECEPCIONTITULO AS COD_RECEPCION, TO_CHAR(RT.COD_CARTERA_NOMISIONAL) AS EXPEDIENTE, E.COD_ENTIDAD,E.RAZON_SOCIAL, 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION, TG.TIPOGESTION 
            FROM  RECEPCIONTITULOS RT,
                  CNM_CARTERANOMISIONAL NM,
                  CNM_EMPRESA E,
                  TIPOCARTERA TC,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                  AND (E.COD_ENTIDAD = NM.COD_EMPRESA)
                  AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                  AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                  AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
               AND (RT.COD_TIPORESPUESTA IN ('1112','171'))";
        if ($cod_titulo != '')
            $query .= " AND (RT.COD_RECEPCIONTITULO=" . $cod_titulo . ")";
        $query .= "
            UNION(
                SELECT DISTINCT RT.COD_RECEPCIONTITULO AS COD_RECEPCION, TO_CHAR(RT.COD_CARTERA_NOMISIONAL) AS EXPEDIENTE,TO_CHAR(E.IDENTIFICACION), E.NOMBRES || E.APELLIDOS , 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION, TG.TIPOGESTION 
                FROM RECEPCIONTITULOS RT,
                      CNM_CARTERANOMISIONAL NM,
                      CNM_EMPLEADO E,
                      TIPOCARTERA TC,
                      RESPUESTAGESTION RG,
                      TIPOGESTION TG,
                      TIPOPROCESO TP
                WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                      AND (E.IDENTIFICACION = NM.COD_EMPLEADO)
                      AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                      AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                      AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                      AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
                     AND (RT.COD_TIPORESPUESTA IN ('1112','171'))";
        if ($cod_titulo != '')
            $query .= " AND (RT.COD_RECEPCIONTITULO=" . $cod_titulo . ") ";
        $query .= "                          
                )
            )
            ORDER BY 1, 2";

        $consulta = $this->db->query($query);
        return $consulta;
    }

    public function get_regional() {
        $this->db->select("R.COD_REGIONAL,R.NOMBRE_REGIONAL");
        $this->db->where("COD_REGIONAL", REGIONAL_USUARIO);
        $dato = $this->db->get("REGIONAL R");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
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

    public function get_documento_notificar() {
        $this->db->select("F.COD_FISCALIZACION,RT.COD_RECEPCIONTITULO, ST.NOMBRE_DOCUMENTO");
        $this->db->join("FISCALIZACION F", "F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA");
        $this->db->join("SOPORTE_RECEPCIONTITULOS ST", "ST.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO");
        $this->db->join("GESTIONCOBRO GC", "GC.COD_GESTION_COBRO = ST.COD_GESTION_COBRO");

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
        $this->db->where("TE.COD_ESTADO", 1);
        $dato = $this->db->get("TIPOSEXIGIBILIDAD TE");
        return $dato->result();
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

    public function get_procesotransversal() {
        $dato = $this->db->query('SELECT P.NOMBREPROCESO,P.COD_MACROPROCESO,RT.COD_RECEPCIONTITULO, E.CODEMPRESA, E.NOMBRE_EMPRESA, F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION, CF.NOMBRE_CONCEPTO 
                                    FROM PROCESO P, TIPOGESTION TG, ASIGNACIONFISCALIZACION AF, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, EMPRESA E, RECEPCIONTITULOS RT, FISCALIZACION F,(
                                    SELECT A.* FROM GestionCobro A,(
                                    SELECT Cod_Fiscalizacion_Empresa, COUNT(*), MAX(Cod_Gestion_Cobro) AS Cod_Gestion_Cobro
                                    FROM GestionCobro 
                                    GROUP BY Cod_Fiscalizacion_Empresa
                                    ORDER BY COUNT(*) DESC) B
                                    WHERE A.Cod_Gestion_Cobro = B.Cod_Gestion_Cobro) H
                                    WHERE H.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION
                                    AND F.COD_ASIGNACION_FISC = AF.COD_ASIGNACIONFISCALIZACION
                                    AND AF.NIT_EMPRESA = E.CODEMPRESA
                                    AND RT.COD_FISCALIZACION_EMPRESA = F.COD_FISCALIZACION
                                    AND CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO
                                    AND H.COD_TIPOGESTION = TG.COD_GESTION
                                    AND H.COD_TIPO_RESPUESTA = RG.COD_RESPUESTA
                                    AND TG.CODPROCESO = P.CODPROCESO
                                    AND P.COD_MACROPROCESO = 2 
                                    AND F.COD_ABOGADO = ' . COD_USUARIO . '
                                    AND E.COD_REGIONAL = ' . REGIONAL_USUARIO . '
                                    AND RG.COD_RESPUESTA NOT IN(751,752,753,754,755,756,745,746,747,748,749,750,484)');
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

    public function get_multipleestadoregional($estado) {

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
        $this->db->where("E.COD_REGIONAL", REGIONAL_USUARIO);
        $this->db->where($cadena);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        return $dato->result();
    }

    function historico($cod_titulo) {
        $this->db->select("NR.FECHA_RECIBIDO, NR.OBSERVACIONES");
        $this->db->where("NR.OBSERVACIONES IS NOT NULL");
        $this->db->where("NR.COD_TITULO", $cod_titulo);
        $dato = $this->db->get("NOTIFICACION_RECEPCIONTITULOS NR");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato;
        }
    }

    public function get_Abogadoestado($estado = null, $estado2 = null, $estado3 = null) {
        $this->db->select("RT.COD_TIPORESPUESTA,RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
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

    public function get_encabezado_titulo($cod_recepcion, $cod_respuesta) {
        $dato = $this->db->query("SELECT DISTINCT 'JURIDICA' AS TIPO_PERSONA, E.CODEMPRESA AS IDENTIFICACION, E.RAZON_SOCIAL AS EJECUTADO, E.REPRESENTANTE_LEGAL AS REPRESENTANTE, E.TELEFONO_FIJO AS TELEFONO, CF.NOMBRE_CONCEPTO AS CONCEPTO, TP.TIPO_PROCESO AS PROCESO, RG.NOMBRE_GESTION RESPUESTA, E.DIRECCION AS DIRECCION
            FROM RECEPCIONTITULOS RT,
                  FISCALIZACION F,
                  ASIGNACIONFISCALIZACION AF,
                  EMPRESA E,
                  CONCEPTOSFISCALIZACION CF,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP
            WHERE (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO)
                  AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                  AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                  AND (E.CODEMPRESA = AF.NIT_EMPRESA)                               
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)                             
                  AND (RG.COD_RESPUESTA = '$cod_respuesta')
                  AND (RT.COD_RECEPCIONTITULO = '$cod_recepcion')
            UNION(
            SELECT DISTINCT 'JURIDICA', E.COD_ENTIDAD, E.RAZON_SOCIAL, 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION
            FROM  RECEPCIONTITULOS RT,
                  CNM_CARTERANOMISIONAL NM,
                  CNM_EMPRESA E,
                  TIPOCARTERA TC,
                  RESPUESTAGESTION RG,
                  TIPOGESTION TG,
                  TIPOPROCESO TP
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                  AND (E.COD_ENTIDAD = NM.COD_EMPRESA)
                  AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                  AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                  AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                  AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                  AND (RG.COD_RESPUESTA = '$cod_respuesta')
                  AND (RT.COD_RECEPCIONTITULO = '$cod_recepcion')
            UNION(
                SELECT DISTINCT 'NATURAL', TO_CHAR(E.IDENTIFICACION), E.NOMBRES || E.APELLIDOS , 'No hay Representante', E.TELEFONO, TC.NOMBRE_CARTERA, TP.TIPO_PROCESO, RG.NOMBRE_GESTION, E.DIRECCION
                FROM RECEPCIONTITULOS RT,
                      CNM_CARTERANOMISIONAL NM,
                      CNM_EMPLEADO E,
                      TIPOCARTERA TC,
                      RESPUESTAGESTION RG,
                      TIPOGESTION TG,
                      TIPOPROCESO TP
                WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                      AND (E.IDENTIFICACION = NM.COD_EMPLEADO)
                      AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                      AND (TG.COD_GESTION = RG.COD_TIPOGESTION)
                      AND (TG.CODPROCESO = TP.COD_TIPO_PROCESO)
                      AND (RG.COD_RESPUESTA = '$cod_respuesta')
                      AND (RT.COD_RECEPCIONTITULO = '$cod_recepcion')
                )
            )
            ORDER BY 1, 2");
//        echo $dato;die();
        $dato = $dato->result();
        return $dato[0];
    }

    public function get_codprocesojuridico($cod_titulo) {
        $dato = $this->db->query("SELECT DISTINCT TO_CHAR(F.COD_FISCALIZACION) AS NO_CARTERA, CF.COD_CPTO_FISCALIZACION CONCEPTO, RT.PROXIMO_PRESCRIBIR AS PRESCRIPCION, '1' AS PROCEDENCIA, E.CODEMPRESA AS IDENTIFICACION,E.COD_REGIONAL AS REGIONAL, CF.COD_OBLIGACIONCOAC AS OBLIGACION, E.COD_TIPOENTIDAD AS NATURALEZA, TO_CHAR(SYSDATE, 'YY') AS COBRO
            FROM RECEPCIONTITULOS RT,
                  FISCALIZACION F,
                  ASIGNACIONFISCALIZACION AF,
                  EMPRESA E,
                  CONCEPTOSFISCALIZACION CF
            WHERE (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO)
                  AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA)
                  AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC)
                  AND (E.CODEMPRESA = AF.NIT_EMPRESA)      
                  AND (RT.COD_RECEPCIONTITULO = $cod_titulo)
            UNION(
            SELECT DISTINCT TO_CHAR(NM.COD_CARTERA_NOMISIONAL) AS NO_CARTERA, TC.COD_TIPOCARTERA,RT.PROXIMO_PRESCRIBIR, '2' AS PROCEDENCIA, E.COD_ENTIDAD,E.COD_REGIONAL, TC.COD_OBLIGACIONCOAC, E.CODTIPOENTIDAD, TO_CHAR(SYSDATE, 'YY') 
            FROM  RECEPCIONTITULOS RT,
                  CNM_CARTERANOMISIONAL NM,
                  CNM_EMPRESA E,
                  TIPOCARTERA TC
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                  AND (E.COD_ENTIDAD = NM.COD_EMPRESA)
                  AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                  AND (NM.COD_EMPRESA = E.COD_ENTIDAD)
                  AND (RT.COD_RECEPCIONTITULO = $cod_titulo)
            UNION(
                SELECT DISTINCT TO_CHAR(NM.COD_CARTERA_NOMISIONAL) AS NO_CARTERA, TC.COD_TIPOCARTERA, RT.PROXIMO_PRESCRIBIR, '2' AS PROCEDENCIA, TO_CHAR(E.IDENTIFICACION),E.COD_REGIONAL, TC.COD_OBLIGACIONCOAC, 2, TO_CHAR(SYSDATE, 'YY') 
                FROM RECEPCIONTITULOS RT,
                      CNM_CARTERANOMISIONAL NM,
                      CNM_EMPLEADO E,
                      TIPOCARTERA TC
                WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL)
                      AND (E.IDENTIFICACION = NM.COD_EMPLEADO)
                      AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA)
                      AND (RT.COD_RECEPCIONTITULO = $cod_titulo)
                )
            )
            ORDER BY 1, 2");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        } else {
            return 0;
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
        $this->db->select("RT.COD_TIPORESPUESTA,RT.COD_RECEPCIONTITULO,E.CODEMPRESA,E.NOMBRE_EMPRESA,F.COD_FISCALIZACION, E.REPRESENTANTE_LEGAL, E.TELEFONO_FIJO, RG.NOMBRE_GESTION,CF.NOMBRE_CONCEPTO ");
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
            $this->db->select("C.COMENTARIOS, TO_CHAR(C.FECHA_DOCUMENTO, 'YYYY/MM/DD HH:MM:SS') FECHA_DOCUMENTO, U.NOMBRES, U.APELLIDOS", FALSE);
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
     * INSERTAR REGISTRO DE LA PLANILLA EN BD
     */

    public function insertar_comunicacion($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("COMUNICADOS_PJ", $datos);
        endif;
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

    public function actualizacion_recepcion($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_PROCESO_COACTIVO", $datos['COD_PROCESO_COACTIVO']);
            unset($datos['COD_PROCESO_COACTIVO']);
            $this->db->update("PROCESOS_COACTIVOS", $datos);
        endif;
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

    /*
     * ACTUALIZAR EL ESTADO DEL TITULO DESPUES DE UNA GESTION
     */

    public function actualizacion_NoMisional($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_CARTERA_NOMISIONAL", $datos['COD_CARTERA_NOMISIONAL']);
            unset($datos['COD_CARTERA_NOMISIONAL']);
            $this->db->update("CNM_CARTERANOMISIONAL", $datos);
        endif;
    }

    public function actualizacion_fiscalizacion($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_FISCALIZACION", $datos['COD_FISCALIZACION']);
            unset($datos['COD_FISCALIZACION']);
            $this->db->update("FISCALIZACION", $datos);
        endif;
    }

    public function actualizacion_notifentregada($datos) {
        if (!empty($datos)) :
            $this->db->where("COD_TITULO", $datos['COD_TITULO']);
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
        $this->db->set('FECHA_CONFIRMACION', "TO_DATE('" . $datos['FECHA_CONFIRMACION'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
        unset($datos['FECHA_CONFIRMACION']);
        $this->db->insert("EXIGIBILIDADTITULOS", $datos);
    }

    public function insertar_cobro_persuasivo($datos = NULL) {
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

    function consulta_titulos($reg, $search, $regional, $proceso = FALSE) {
        $cod_respuesta = "('170', '172', '1113','1375')";
        if (!empty($proceso)):
            $recepcion = ' AND ( RT.COD_RECEPCIONTITULO=' . $proceso . ')';
        else:
            $recepcion = '';
        endif;

        $query = '
            SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
            RT.FECHA_CONSULTAONBASE,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
            RG.NOMBRE_GESTION AS RESPUESTA, 
            TO_NUMBER(F.COD_FISCALIZACION) AS COD_PROCESO,
            TO_NUMBER(F.COD_FISCALIZACION) AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            E.RAZON_SOCIAL AS NOMBRE, 
            E.CODEMPRESA AS IDENTIFICACION,

            \'\' AS NOMBRES,
            \'\' AS APELLIDOS,

            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
            CF.NOMBRE_CONCEPTO AS NOMBRE_CONCEPTO
            FROM
            
            RECEPCIONTITULOS RT,
            FISCALIZACION F, ASIGNACIONFISCALIZACION AF, EMPRESA E, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, TIPOGESTION TG,
            TIPOPROCESO TP , REGIONAL  REG
            
            WHERE 
            
            (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO) AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA) 
            AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC) AND (E.CODEMPRESA = AF.NIT_EMPRESA) AND
            (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND 
           
            (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
            AND (RT.COD_TIPORESPUESTA IN 
            ' . $cod_respuesta . ')
             AND (E.COD_REGIONAL=REG.COD_REGIONAL) 
            AND (E.COD_REGIONAL=' . $regional . ')' . $recepcion . '
                
            UNION( SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
            RT.FECHA_CONSULTAONBASE,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
              RG.NOMBRE_GESTION AS RESPUESTA, 
            NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
            NM.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            \'\' AS NOMBRES,
            \'\' AS APELLIDOS,
            E.RAZON_SOCIAL AS NOMBRE,
              E.COD_ENTIDAD  AS IDENTIFICACION, 
            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
             TC.NOMBRE_CARTERA AS NOMBRE_CONCEPTO
             
            FROM 
            RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPRESA E, TIPOCARTERA TC, RESPUESTAGESTION RG,
            TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) AND
           (E.COD_ENTIDAD = NM.COD_EMPRESA) AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND (NM.COD_EMPRESA = E.COD_ENTIDAD) AND 
           (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
           
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) 
            AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) AND 
            (RT.COD_TIPORESPUESTA IN 
            ' . $cod_respuesta . ')
            AND            (E.COD_REGIONAL=REG.COD_REGIONAL) 
            AND (E.COD_REGIONAL=' . $regional . ') ' . $recepcion . '
             UNION( 
             SELECT DISTINCT 
             RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
             RT.FECHA_CONSULTAONBASE,
             RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
             RG.NOMBRE_GESTION AS RESPUESTA, 
             NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
             NM.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
             RT.COD_ABOGADO AS ABOGADO,
              E.NOMBRES || E.APELLIDOS AS NOMBRE , 
             TO_CHAR(E.IDENTIFICACION) AS IDENTIFICACION,
             
             \'\' AS NOMBRES,
             \'\' AS APELLIDOS,

             REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
             REG.COD_REGIONAL AS COD_REGIONAL,
             TC.NOMBRE_CARTERA AS NOMBRE_CONCEPTO
             FROM 
             RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPLEADO E, TIPOCARTERA TC,
             RESPUESTAGESTION RG, TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG
             
             WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) 
             AND (E.IDENTIFICACION = NM.COD_EMPLEADO) AND
             (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND 
             (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
             
             (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND
             (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
              AND (RT.COD_TIPORESPUESTA IN 
              ' . $cod_respuesta . ')
              AND (E.COD_REGIONAL=REG.COD_REGIONAL) 
              AND (E.COD_REGIONAL=' . $regional . ')  ' . $recepcion . '

            ) ) ORDER BY 1, 2'
        ;
        //echo $query; die()
        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function consulta_abogado($regional) {
        $this->db->select("USUARIOS.NOMBRES, USUARIOS.APELLIDOS, USUARIOS.IDUSUARIO");

        $this->db->join('USUARIOS_GRUPOS', ' USUARIOS_GRUPOS.IDUSUARIO=USUARIOS.IDUSUARIO', 'inner');
        // $this->db->where("USUARIOS.ABOGADO", 1); /* Se debe cambiar a 1 */
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", ABOGADO);
        $this->db->where("USUARIOS.COD_REGIONAL", $regional);
        $dato = $this->db->get("USUARIOS");
        $d = $this->db->last_query();
        return $dato->result_array();
    }

    function guardar_asignacion($datos) {

        $titulos = count($datos['post']['asignar']);
        for ($i = 0; $i < $titulos; $i++) {
            $this->db->set('COD_ABOGADO', $datos['post']['abogado'], FALSE);
            $this->db->set('COD_TIPORESPUESTA', 173, FALSE);
            $this->db->where('COD_RECEPCIONTITULO', $datos['post']['asignar'][$i], FALSE);
            $this->db->update('RECEPCIONTITULOS');
            // $r = $this->db->last_query();
            $tipogestion = 426;
            $tiporespuesta = 1113;
            $codtitulo = '';
            // echo $cod_titulo;
            $codjuridico = '';
            $codcarteranomisional = '';
            $coddevolucion = '';
            $codrecepcion = $datos['post']['asignar'][$i];
            $traza = trazarProcesoJuridico($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios = "Se realiza la asignación del abogado", $usuariosAdicionales = '');
        }

        return $titulos;
    }

    function titulos_ejecutivos($reg, $search, $regional, $id_abogado, $proceso, $cod_respuesta) {
//        $this->load->library('datatables');

        if (!empty($proceso)):
            $recepcion = ' AND (RT.COD_RECEPCIONTITULO=' . $proceso . ')';
        else:
            $recepcion = '';
        endif;
        $query = '
            SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
            RT.FECHA_CONSULTAONBASE,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
            RG.NOMBRE_GESTION AS RESPUESTA, 
            TO_NUMBER(F.COD_FISCALIZACION) AS COD_PROCESO,
            TO_NUMBER(F.COD_FISCALIZACION) AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            E.RAZON_SOCIAL AS NOMBRE, 
            E.CODEMPRESA AS IDENTIFICACION,
           
            \'\' AS NOMBRES,
            \'\' AS APELLIDOS,

            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
            CF.NOMBRE_CONCEPTO AS NOMBRE_CONCEPTO,
            TIT.FECHA_CARGA, TIT.NOMBRE_DOCUMENTO, TIT.RUTA_ARCHIVO,TIT.COD_TITULO
            FROM
            
            RECEPCIONTITULOS RT,
            FISCALIZACION F, ASIGNACIONFISCALIZACION AF, EMPRESA E, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, TIPOGESTION TG,
            TIPOPROCESO TP , REGIONAL  REG,TITULOS TIT
            
            WHERE 
            
            (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO) AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA) 
            AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC) AND (E.CODEMPRESA = AF.NIT_EMPRESA) AND
            (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND 
           
            (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
            AND (RT.COD_TIPORESPUESTA IN 
            ' . $cod_respuesta . ')
             AND (E.COD_REGIONAL=REG.COD_REGIONAL) '
                //AND (E.COD_REGIONAL=' . $regional . ') 
                . ' AND (RT.COD_ABOGADO=' . $id_abogado . ') 
                 ' . $recepcion . '   
            AND (TIT.COD_RECEPCIONTITULO=RT.COD_RECEPCIONTITULO)        
            UNION( SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
            RT.FECHA_CONSULTAONBASE,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
              RG.NOMBRE_GESTION AS RESPUESTA, 
            NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
            NM.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            \'\' AS NOMBRES,
            \'\' AS APELLIDOS,
            E.RAZON_SOCIAL AS NOMBRE,
              E.COD_ENTIDAD  AS IDENTIFICACION, 
            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
             TC.NOMBRE_CARTERA AS NOMBRE_CONCEPTO,
              TIT.FECHA_CARGA,  TIT.NOMBRE_DOCUMENTO,
               TIT.RUTA_ARCHIVO,TIT.COD_TITULO
             
            FROM 
            RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPRESA E, TIPOCARTERA TC, RESPUESTAGESTION RG,
            TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG, TITULOS TIT
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) AND
           (E.COD_ENTIDAD = NM.COD_EMPRESA) AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND (NM.COD_EMPRESA = E.COD_ENTIDAD) AND 
           (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
           
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) 
            AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) AND 
            (RT.COD_TIPORESPUESTA IN 
            ' . $cod_respuesta . ')
            AND            (E.COD_REGIONAL=REG.COD_REGIONAL) '
                //  AND (E.COD_REGIONAL=' . $regional . ') 
                . ' AND (RT.COD_ABOGADO=' . $id_abogado . ')  
                     ' . $recepcion . '   
                     AND (TIT.COD_RECEPCIONTITULO=RT.COD_RECEPCIONTITULO)     
             UNION( 
             SELECT DISTINCT 
             RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
             RT.FECHA_CONSULTAONBASE,
             RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
             RG.NOMBRE_GESTION AS RESPUESTA, 
             NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
             NM.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
             RT.COD_ABOGADO AS ABOGADO,
              E.NOMBRES || E.APELLIDOS AS NOMBRE , 
             TO_CHAR(E.IDENTIFICACION) AS IDENTIFICACION,
             
             \'\' AS NOMBRES,
             \'\' AS APELLIDOS,

             REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
             REG.COD_REGIONAL AS COD_REGIONAL,
             TC.NOMBRE_CARTERA AS NOMBRE_CONCEPTO,
              TIT.FECHA_CARGA,
              TIT.NOMBRE_DOCUMENTO,TIT.RUTA_ARCHIVO,TIT.COD_TITULO
             FROM 
             RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPLEADO E, TIPOCARTERA TC,
             RESPUESTAGESTION RG, TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG, TITULOS TIT
             
             WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) 
             AND (E.IDENTIFICACION = NM.COD_EMPLEADO) AND
             (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND 
             (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
             
             (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND
             (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
              AND (RT.COD_TIPORESPUESTA IN 
              ' . $cod_respuesta . ')
              AND (E.COD_REGIONAL=REG.COD_REGIONAL) '
                // AND (E.COD_REGIONAL=' . $regional . ')  
                . ' AND (RT.COD_ABOGADO=' . $id_abogado . ')  
             ' . $recepcion . ' 
                  AND (TIT.COD_RECEPCIONTITULO=RT.COD_RECEPCIONTITULO)     
            ) ) ORDER BY 1, 2'
        ;
        // echo $query;
        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function recepcion_titulos($reg, $search, $regional, $id_abogado, $proceso, $cod_respuesta) {
//        $this->load->library('datatables');

        if (!empty($proceso)):
            $recepcion = ' AND (RT.COD_RECEPCIONTITULO=' . $proceso . ')';
        else:
            $recepcion = '';
        endif;
        $query = '
            SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
            RT.FECHA_CONSULTAONBASE,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
            RG.NOMBRE_GESTION AS RESPUESTA, 
            TO_NUMBER(F.COD_FISCALIZACION) AS COD_PROCESO,
            TO_NUMBER(F.COD_FISCALIZACION) AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            E.RAZON_SOCIAL AS NOMBRE, 
            E.CODEMPRESA AS IDENTIFICACION,
           
            \'\' AS NOMBRES,
            \'\' AS APELLIDOS,

            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
            CF.NOMBRE_CONCEPTO AS NOMBRE_CONCEPTO
            
            FROM
            
            RECEPCIONTITULOS RT,
            FISCALIZACION F, ASIGNACIONFISCALIZACION AF, EMPRESA E, CONCEPTOSFISCALIZACION CF, RESPUESTAGESTION RG, TIPOGESTION TG,
            TIPOPROCESO TP , REGIONAL  REG
            
            WHERE 
            
            (CF.COD_CPTO_FISCALIZACION = F.COD_CONCEPTO) AND (F.COD_FISCALIZACION = RT.COD_FISCALIZACION_EMPRESA) 
            AND (AF.COD_ASIGNACIONFISCALIZACION = F.COD_ASIGNACION_FISC) AND (E.CODEMPRESA = AF.NIT_EMPRESA) AND
            (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND 
           
            (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
            AND (RT.COD_TIPORESPUESTA IN 
            ' . $cod_respuesta . ')
             AND (E.COD_REGIONAL=REG.COD_REGIONAL) '
                //AND (E.COD_REGIONAL=' . $regional . ') 
                . ' AND (RT.COD_ABOGADO=' . $id_abogado . ') 
                 ' . $recepcion . '   
                    
            UNION( SELECT DISTINCT 
            RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
            RT.FECHA_CONSULTAONBASE,
            RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
              RG.NOMBRE_GESTION AS RESPUESTA, 
            NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
            NM.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
            RT.COD_ABOGADO AS ABOGADO,
            \'\' AS NOMBRES,
            \'\' AS APELLIDOS,
            E.RAZON_SOCIAL AS NOMBRE,
              E.COD_ENTIDAD  AS IDENTIFICACION, 
            REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
            REG.COD_REGIONAL AS COD_REGIONAL,
             TC.NOMBRE_CARTERA AS NOMBRE_CONCEPTO
             
            FROM 
            RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPRESA E, TIPOCARTERA TC, RESPUESTAGESTION RG,
            TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG
            WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) AND
           (E.COD_ENTIDAD = NM.COD_EMPRESA) AND (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND (NM.COD_EMPRESA = E.COD_ENTIDAD) AND 
           (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
           
            (TG.CODPROCESO = TP.COD_TIPO_PROCESO) 
            AND (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) AND 
            (RT.COD_TIPORESPUESTA IN 
            ' . $cod_respuesta . ')
            AND            (E.COD_REGIONAL=REG.COD_REGIONAL) '
                //   AND (E.COD_REGIONAL=' . $regional . ') 
                . ' AND (RT.COD_ABOGADO=' . $id_abogado . ')  
                     ' . $recepcion . '   
             UNION( 
             SELECT DISTINCT 
             RT.COD_RECEPCIONTITULO AS COD_RECEPCIONTITULO,
             RT.FECHA_CONSULTAONBASE,
             RT.COD_TIPORESPUESTA AS COD_RESPUESTA,
             RG.NOMBRE_GESTION AS RESPUESTA, 
             NM.COD_CARTERA_NOMISIONAL AS COD_PROCESO,
             NM.COD_CARTERA_NOMISIONAL AS PROCESOPJ,
             RT.COD_ABOGADO AS ABOGADO,
              E.NOMBRES || E.APELLIDOS AS NOMBRE , 
             TO_CHAR(E.IDENTIFICACION) AS IDENTIFICACION,
             
             \'\' AS NOMBRES,
             \'\' AS APELLIDOS,

             REG.NOMBRE_REGIONAL AS NOMBRE_REGIONAL,
             REG.COD_REGIONAL AS COD_REGIONAL,
             TC.NOMBRE_CARTERA AS NOMBRE_CONCEPTO
             FROM 
             RECEPCIONTITULOS RT, CNM_CARTERANOMISIONAL NM, CNM_EMPLEADO E, TIPOCARTERA TC,
             RESPUESTAGESTION RG, TIPOGESTION TG, TIPOPROCESO TP, REGIONAL REG
             
             WHERE (RT.COD_CARTERA_NOMISIONAL = NM.COD_CARTERA_NOMISIONAL) 
             AND (E.IDENTIFICACION = NM.COD_EMPLEADO) AND
             (NM.COD_TIPOCARTERA = TC.COD_TIPOCARTERA) AND 
             (TG.COD_GESTION = RG.COD_TIPOGESTION) AND
             
             (TG.CODPROCESO = TP.COD_TIPO_PROCESO) AND
             (RG.COD_RESPUESTA=RT.COD_TIPORESPUESTA) 
              AND (RT.COD_TIPORESPUESTA IN 
              ' . $cod_respuesta . ')
              AND (E.COD_REGIONAL=REG.COD_REGIONAL) '
                // AND (E.COD_REGIONAL=' . $regional . ')  
                . ' AND (RT.COD_ABOGADO=' . $id_abogado . ')  
             ' . $recepcion . '   
            ) ) ORDER BY 1, 2'
        ;
        // echo $query;
        $resultado = $this->db->query($query);
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function guardar_detalle_ejec($datos) {

        $this->db->set('COD_TIPORESPUESTA', $datos['post']['estado'], FALSE);
        $this->db->set('OBSERVACIONES', $datos['post']['observaciones']);
        $this->db->where('COD_RECEPCIONTITULO', $datos['post']['id_recepcion'], FALSE);
        $this->db->update('RECEPCIONTITULOS');
        $tipogestion = 312;
        $tiporespuesta = 886;
        $codtitulo = $datos['post']['id_recepcion'];
        // echo $cod_titulo;
        $codjuridico = NULL;
        $codcarteranomisional = NULL;
        $coddevolucion = NULL;
        $codrecepcion = $datos['post']['id_recepcion'];
        $traza = trazarProcesoJuridico($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios = "Se realiza la asignaciÃ³n del abogado", $usuariosAdicionales = '');

        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function getTitulos($nit, $expediente) {
        $existentes = array();
        $this->db->where('NIT_EMPRESA', $nit);
        $this->db->where('COD_FISCALIZACION_EMPRESA', $expediente);
        $this->db->or_where('COD_CARTERA_NOMISIONAL', $expediente);
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

    function insertar_mandamientopago($datos) {
        if (!empty($datos)) :
            $this->db->insert("MANDAMIENTOPAGO", $datos);
        endif;
    }

    public function insertar_investigacion_bienes($datos) {
        if (!empty($datos)) :
            $this->db->insert("MC_MEDIDASCAUTELARES", $datos);
        endif;
    }

    public function get_naturalezacoactivo($cod_coactivo) {
        $this->db->select("RT.PROXIMO_PRESCRIBIR");
        $this->db->join('ACUMULACION_COACTIVA AC', 'AC.COD_RECEPCIONTITULO = RT.COD_RECEPCIONTITULO');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_PROCESO_COACTIVO = AC.COD_PROCESO_COACTIVO');
        $this->db->where("PC.COD_PROCESO_COACTIVO", $cod_coactivo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    function envioareparto($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios, $usuariosAdicionales) {
        $this->db->where('COD_RECEPCIONTITULO', $codrecepcion);
        $this->db->set('OBSERVACIONES', $comentarios);
        $this->db->set('COD_TIPORESPUESTA', $tiporespuesta);
        $this->db->trans_begin();
        $this->db->update('RECEPCIONTITULOS');
        trazarProcesoJuridico($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios, $usuariosAdicionales);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
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
     * sabes si viene de no misional
     */

    public function get_procedenciadeuda($cod_titulo) {
        $this->db->select("RT.NOMISIONAL,RT.COD_CARTERA_NOMISIONAL");
        $this->db->where("RT.COD_RECEPCIONTITULO", $cod_titulo);
        $dato = $this->db->get("RECEPCIONTITULOS RT");
        if ($dato->num_rows() > 0) {
            $dato = $dato->result_array();
            return $dato[0];
        }
    }

    /*
     * INSERTAR TITULOS REQUERIMIENTO PARA RECHAZO
     */

    public function insertar_titulosrechazo($datos = NULL) {
        if (!empty($datos)) :
            $this->db->insert("TITULOS", $datos);
        endif;
    }

}
