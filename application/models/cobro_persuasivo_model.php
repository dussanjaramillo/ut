<?php

class Cobro_persuasivo_model extends CI_Model {

    private $causal;

    function __construct() {
        parent::__construct();
    }

    function set_causal($causal) {
        $this->causal = $causal;
    }

    function permiso() {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO,USUARIOS.COD_REGIONAL, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->or_where("(GRUPOS.IDGRUPO", ABOGADO);
        $this->db->or_where("GRUPOS.IDGRUPO", SECRETARIO);
        $this->db->or_where("GRUPOS.IDGRUPO", COORDINADOR . ")", FALSE);
        $this->db->where("USUARIOS.IDUSUARIO", ID_USER);
        $dato = $this->db->get("USUARIOS");
        return $dato->result_array();
    }

    function verifica_existe_req($titulo) {
        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO');
        $this->db->from('COBROPERSUASIVO');
        $this->db->where('COBROPERSUASIVO.COD_RECEPCIONTITULO', $titulo);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return '0';
        }
        return $query->num_rows();
    }

    function verifica_existe_proceso($pj) {
        $this->db->select('AP_PROCESOJUDICIAL.COD_PROCJUDICIAL');
        $this->db->from('AP_PROCESOJUDICIAL');
        $this->db->where('AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO', $pj);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return '0';
        }
        return $query->num_rows();
    }

    //envio Requerimiento Acercamiento
    function guardar_notificacion($datos) {
        //Acutaliza la tabla de COBROPERSUASIVO
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('FECHA_ENVIO_DOCUMENTO', $fecha_actual, FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');

        //Inserta en la tabla GESTION_ACERCAMIENTO
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
        $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
        $this->db->set('FECHA', $fecha_actual, FALSE);
        $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
        $this->db->set('COD_USUARIO', $datos['cod_usuario']);
        $query = $this->db->insert('GESTION_ACERCAMIENTO');
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function requerimiento_acercamiento($datos) {

        //Realizo el insert en la tabla de NOTIFICACIONCOBRO
        $notificacion = 0;
        $mym = 0;
        if ((!empty($datos['num_radicado'])) || (!empty($datos['num_radicado_dev']))) :
            $notificacion = $this->insert_notificacioncobro($datos);
        endif;
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO)://Envia a Medidas Cautelares y Mandamiento de Pago
            $mym = $this->insert_mc($datos);
        endif;

        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        if (!empty($datos['tipo_respuesta'])) :
            $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta']);
        elseif (!empty($datos['numero_documento'])) :
            $this->db->set('NRO_DOCUMENTO', $datos['numero_documento']);
        elseif (!empty($datos['observaciones'])):
            $this->db->set('OBSERVACIONES', $datos['observaciones']);
        elseif (!empty($datos['ruta'])) :
            $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        elseif (!empty($datos['fecha_devolucion'])):
            $this->db->set('FECHA_RESPUESTA', "TO_DATE('" . $datos['fecha_devolucion'] . "','YY/MM/DD')", FALSE);
        elseif (!empty($datos['fecha_recibida'])) :
            $this->db->set('FECHA_RESPUESTA', "TO_DATE('" . $datos['fecha_recibida'] . "','YY/MM/DD')", FALSE);
        elseif (!empty($datos['reorganizacion_empresarial'])) :
            $this->db->set('REORGANIZACION_EMPRESARIAL', $datos['reorganizacion_empresarial']);
        elseif (!empty($datos['obligacion_aceptada'])):
            $this->db->set('OBLIGACION_ACEPTADA', $datos['obligacion_aceptada']);
            $this->db->set('FECHA_ACEPTA_OBLIGACIONES', $fecha_actual, false);
        endif;

        if (!empty($datos['cod_cobro_persuasivo'])) :
            $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
            $this->db->update('COBROPERSUASIVO');
        else :
            $this->db->set('FECHA_CREACION', $fecha_actual, FALSE);
            $this->db->insert('COBROPERSUASIVO');
        endif;
        if ($datos['tipo_respuesta'] == 190) :
            $this->db->set('FECHA_CREACION', $fecha_actual, FALSE);
            $this->db->insert('COBROPERSUASIVO');
        endif;

        if ($this->db->affected_rows() == '1'):
            return TRUE;
        else: return FALSE;
        endif;
    }

    function verificacion_pagos($datos) {
     //   $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta']);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        //Cuando no pago se envia a medidas cautelares
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO) {
            $query = $this->insert_mc($datos);
        }
        if ($datos['tipopago'] == 3) {
            $fecha_actual = 'SYSDATE';
            $this->db->set('NUM_LIQUIDACION', $datos['num_liquidacion'], FALSE);
            $this->db->set('CODEMPRESA', $datos['nit_empresa'], FALSE);
            $this->db->set('VALOR_A_CALCULAR', $datos['total_capital'], FALSE);
            $this->db->set('VALOR_INTERESES', $datos['valor_intereses'], FALSE);
            $this->db->set('COD_CONCEPTO', $datos['concepto'], FALSE);
            $this->db->set('NUMERO_RESOLUCION', $datos['num_resolucion']);
            $this->db->set('COD_ABOGADO', $datos['cod_abogado'], FALSE);
            $this->db->set('FECHA_SOLICITUD', $fecha_actual, FALSE);
            $this->db->set('NUMERO_PROCESO', $datos['num_proceso'], FALSE);
            $this->db->insert('INGRESOACUERDOJURIDICO');
            //SE ACTUALIZA CAMPO COD_TIPOPROCESO EN LA TABLA LIQUIDACION CUANDO INGRESA A ACUERDO DE PAGO
            $this->db->set('COD_TIPOPROCESO', PROCESO_ACUERDO_JURIDICO, FALSE);
            $this->db->where('NUM_lIQUIDACION', $datos['num_liquidacion'], FALSE);
            $this->db->update('LIQUIDACION');
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
            $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
            $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
            $this->db->set('FECHA', $fecha_actual, FALSE);
            $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
            $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
            // $this->db->set('COD_USUARIO', $datos['cod_usuario']);
            if (!empty($datos['observaciones'])) {
                $this->db->set('OBSERVACIONES', $datos['observaciones']);
            }
            $this->db->insert('GESTION_ACERCAMIENTO');
        }
        return true;
    }

    function crea_req($datos) {//cuando el abogado crea el requerimiento
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($this->db->affected_rows() == '1') {
            //actualiza la liquidacion
            $this->db->set('COD_TIPOPROCESO', PROCESO_ACERCAMIENTO_PERSUASIVO, FALSE);
            $this->db->where('NUM_lIQUIDACION', $datos['num_liquidacion'], FALSE);
            $this->db->update('LIQUIDACION');
            //guarda el documento .txt
            $fecha_actual = 'SYSDATE';
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
            $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
            $this->db->set('NOMBRE_DOCUMENTO', $datos['ruta']);
            $this->db->set('FECHA', $fecha_actual, FALSE);
            $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
            $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
            $this->db->set('COD_USUARIO', $datos['cod_usuario']);
            if (!empty($datos['observaciones'])) {
                $this->db->set('OBSERVACIONES', $datos['observaciones']);
            }
            $this->db->set('CREADO_POR', $datos['cod_usuario']);
            $this->db->insert('GESTION_ACERCAMIENTO');
            if ($this->db->affected_rows() == '1') {
                return true;
            }
            return true;
        }
    }

    function revisa_gestion($datos) {//secretario revisa 
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($this->db->affected_rows() == '1') {
            $fecha_actual = 'SYSDATE';
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
            $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
            $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo'] . ".txt");
            $this->db->set('FECHA', $fecha_actual, FALSE);
            $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
            $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
            $this->db->set('COD_USUARIO', $datos['cod_usuario']);
            if (!empty($datos['observaciones'])) {
                $this->db->set('OBSERVACIONES', $datos['observaciones']);
            }
            $this->db->set('REVISADO_POR', $datos['cod_usuario']);
            $this->db->insert('GESTION_ACERCAMIENTO');
            if ($this->db->affected_rows() == '1') {
                return true;
            }
            return true;
        }
    }

    function aprueba_gestion($datos) {//APRUEBA REQUERMIENTO EL COORDINADOR
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        if (!empty($datos['numero_documento'])) {
            $this->db->set('NRO_DOCUMENTO', $datos['numero_documento']);
        }
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($this->db->affected_rows() == '1') {
            $fecha_actual = 'SYSDATE';
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
            $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
            $this->db->set('NOMBRE_DOCUMENTO', $datos['ruta']);
            $this->db->set('FECHA', $fecha_actual, FALSE);
            $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
            $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
            $this->db->set('COD_USUARIO', $datos['cod_usuario']);
            if (!empty($datos['observaciones'])) {
                $this->db->set('OBSERVACIONES', $datos['observaciones']);
            }
            $this->db->set('APROBADO_POR', $datos['cod_usuario']);
            $this->db->insert('GESTION_ACERCAMIENTO');
            if ($this->db->affected_rows() == '1') {
                return true;
            }
            return true;
        }
    }

    function observaciones($idcobro, $tipodocumento) {

        $this->db->select('GESTION_ACERCAMIENTO.FECHA,GESTION_ACERCAMIENTO.OBSERVACIONES,GESTION_ACERCAMIENTO.COD_USUARIO,'
                . 'USUARIOS.APELLIDOS, USUARIOS.NOMBRES ');
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=GESTION_ACERCAMIENTO.COD_USUARIO");
        $this->db->where("TIPO_DOCUMENTO", $tipodocumento);
        $this->db->where("COD_COBROPERSUASIVO", $idcobro);
      //  $this->db->where("OBSERVACIONES <> ''");
        $dato = $this->db->get('GESTION_ACERCAMIENTO');
        $datos = $dato->result_array();
        $valor = '';
        if ($datos) {
            foreach ($datos as $consulta) {
                $valor.=$consulta['OBSERVACIONES'] . "<br>" . $consulta['FECHA'] . "<br>" . $consulta['NOMBRES'] . " " . $consulta['APELLIDOS'] . "<hr>";
            }
        }
        return $valor;
    }

    function correccion_documento($datos) {
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
        $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
        $this->db->set('FECHA', FECHA, FALSE);
        $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
        $this->db->set('COD_USUARIO', $datos['cod_usuario']);
        $this->db->insert('GESTION_ACERCAMIENTO');
        //actualiza en cobro_persuasivo

        return true;
    }

    function guarda_correccion($datos) {

        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->set('TIPO_DOCUMENTO', DOCUMENTO_REQUERIMIENTO, FALSE);
        $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
        $this->db->set('FECHA', FECHA, FALSE);
        $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
        $this->db->set('COD_USUARIO', $datos['cod_usuario']);
        $this->db->insert('GESTION_ACERCAMIENTO');

        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('NRO_DOCUMENTO', $datos['numero_documento']);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        return  TRUE;
    }

    function generar_gestionacercamiento($datos) {

        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->set('TIPO_DOCUMENTO', $datos['tipo_documento'], FALSE);
        $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
        $this->db->set('FECHA', FECHA, FALSE);
        $this->db->set('COD_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('RUTA_DOCUMENTO', $datos['ruta']);
        $this->db->set('COD_USUARIO', $datos['cod_usuario']);

        switch ($this->datos['tipo_respuesta']) {

            case 190://
                $datos['nombre_archivo'] = $datos['nombre_archivo'] . '.pdf';
                $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
                $this->db->set('APROBADO_POR', $datos['cod_usuario']);
                $this->db->set('OBSERVACIONES', $datos['observaciones']);

                break;

            case 193://SE CREA EL DOC DE PROCESO JUDICIAL
                $this->db->set('CREADO_POR', $datos['cod_usuario']);

                break;
            case 195:
            case 194:
                $this->db->set('REVISADO_POR', $datos['cod_usuario']);
                if (!empty($datos['observaciones']))
                    $this->db->set('OBSERVACIONES', $datos['observaciones']);

                break;
            case 196:
                $datos['nombre_archivo'] = $datos['nombre_archivo'];
                $this->db->set('NOMBRE_DOCUMENTO', $datos['nombre_archivo']);
                $this->db->set('APROBADO_POR', $datos['cod_usuario']);
                if (!empty($datos['observaciones']))
                    $this->db->set('OBSERVACIONES', $datos['observaciones']);
                break;
        }
        $this->db->insert('GESTION_ACERCAMIENTO');
        return true;
    }

    function documento_correo($id) {
        $this->db->select("GESTION_ACERCAMIENTO.RUTA_DOCUMENTO");
        $this->db->where("GESTION_ACERCAMIENTO.COD_COBROPERSUASIVO", $id);
        $this->db->or_where("(GESTION_ACERCAMIENTO.TIPO_DOCUMENTO", 1);
        $this->db->or_where("GESTION_ACERCAMIENTO.TIPO_DOCUMENTO", 2 . ")", FALSE);
        $dato = $this->db->get("GESTION_ACERCAMIENTO");
        $datos = $dato->result_array;
    }

    function concepto($id) {
        $this->db->select('FISCALIZACION.COD_CONCEPTO,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO');
        $this->db->where('FISCALIZACION.COD_FISCALIZACION', $id, FALSE);
        $this->db->join('CONCEPTOSFISCALIZACION', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $dato = $this->db->get("FISCALIZACION");
        return $dato->result_array();
    }

    function insert_pj($datos) {

        $datos['idgestion'] = 536;
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        //cuando se genera, corrige, devuelve y revisa
        $this->db->set('NOMBRE_DOCUMENTO', $datos['ruta']);
        if (!empty($datos['observaciones']))
            $this->db->set('OBSERVACIONES', $datos['observaciones']);
        //cuando se firma el documento
        if (!empty($datos['doc_firmado'])) {
            $this->db->set('NOMBRE_DOC_FIRMADO', $datos['doc_firmado']);
            $this->db->set('FECHA_DOC_FIRMADO', FECHA, FALSE);
        }
        if (!empty($datos['id_pj'])) {
            $this->db->where('COD_PROCJUDICIAL', $datos['id_pj']);
            $query_pj = $this->db->update('AP_PROCESOJUDICIAL');
        } else {
            $this->db->set('FECHA_CREACION', $fecha_actual, FALSE);
            $query_pj = $this->db->insert('AP_PROCESOJUDICIAL');
        }
        $datos['tipo_documento'] = PROCESO_JUDICIAL;

        $info = $this->generar_gestionacercamiento($datos);
        return $info;
    }

    /* Función que permite verificar v si ya existe un mc para esa gestion y cod de fisccalización */

    function verificar_mc($idgestion) {
        $this->db->select('MC_MEDIDASCAUTELARES.COD_MEDIDACAUTELAR');
        $this->db->from('COD_MEDIDACAUTELAR');
        $this->where('COD_MEDIDACAUTELAR.COD_GESTIONCOBRO', $idgestion);
        $this->db->get();
        $array = array();
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        return $array[0];
    }

    function verificaobligaciones($idcobropersuasivo) {
        $this->db->select('COD_OBLIGACIONPAGO');
        $this->db->from('OBLIGACIONESCOBRO');
        $this->where('OBLIGACIONESCOBRO.COD_OBLIGACIONPAGO', $idcobropersuasivo);
        $this->db->get();
        $array = array();
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }

        return $array[0];
    }

    function guarda_respuesta_notificacion($datos) {  //  print_r($datos);
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        if (!empty($datos['fecha_recibida'])) {
            $this->db->set('FECHA_RESPUESTA', "TO_DATE('" . $datos['fecha_recibida'] . "','YY/MM/DD')", FALSE);
        }
        if (!empty($datos['reorganizacion_empresarial']))
            $this->db->set('REORGANIZACION_EMPRESARIAL', $datos['reorganizacion_empresarial']);
        if (!empty($datos['obligacion_aceptada'])) {
            $this->db->set('OBLIGACION_ACEPTADA', $datos['obligacion_aceptada']);
            $this->db->set('FECHA_ACEPTA_OBLIGACIONES', $fecha_actual, false);
        }
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        //insertar en Medidas Cautelares 
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO) {
            $query = $this->insert_mc($datos);
        }
        return true;
    }

    function insert_notificacioncobro($datos) {

        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro_persuasivo']);
        if (!empty($datos['fecha_recibida']))
            $this->db->set('FECHA_RECIBO', "TO_DATE('" . $datos['fecha_recibida'] . "','YY/MM/DD')", FALSE);
        if (!empty($datos['nombre_receptor']))
            $this->db->set('NOMBRE_RECEPTOR', $datos['nombre_receptor']);
        if (!empty($datos['nis']))
            $this->db->set('NIS', $datos['nis']);
        $this->db->set('HORA_RECEPCION', $datos['hora_recepcion']);
        if (!empty($datos['num_radicado']))
            $this->db->set('NRO_RADICADO', $datos['num_radicado'], FALSE);
        if (!empty($datos['nis_devolucion']))
            $this->db->set('NIS', $datos['nis_devolucion'], FALSE);
        if (!empty($datos['causal_devolucion']))
            $this->db->set('COD_CAUSALDEVOLUCION', $datos['causal_devolucion'], FALSE);
        if (!empty($datos['fecha_devolucion']))
            $this->db->set('FECHA_DEVOLUCION', "TO_DATE('" . $datos['fecha_devolucion'] . "','YY/MM/DD')", FALSE);
        if (!empty($datos['num_radicado_dev']))
            $this->db->set('NRO_RADICADO', $datos['num_radicado_dev'], FALSE);
        $qry = $this->db->insert('NOTIFICACIONCOBRO');
        if ($this->db->affected_rows() == '1'):
            return TRUE;
        else:
            return FALSE;
        endif;
    }

    function aceptar_obligaciones($datos) {

        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('COD_ESTADO_PROCESO', $datos['estado_proceso'], FALSE);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('OBLIGACION_ACEPTADA', $datos['obligacion_aceptada']);
//        $this->db->set('VALOR_PAGAR', $datos['valor_pago']);
        if (!empty($datos['tipopago']))
            $this->db->set('TIPO_PAGO', $datos['tipopago']);

        if (!$datos['fecha_aceptacion']) {
            $this->db->set('FECHA_ACEPTA_OBLIGACIONES', FECHA, FALSE);
        } else {
            $this->db->set('FECHA_ACEPTA_OBLIGACIONES', "TO_DATE('" . $datos['fecha_aceptacion'] . "','YY/MM/DD HH24:MI:SS')", FALSE);
        }
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro_persuasivo'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO) {
            $info = $this->insert_mc($datos);
        }
        if ($datos['obligacion_aceptada'] == 'S') {
            $this->obligacionescobro($datos);
        }
        if ($datos['tipo_gestion'] == 106) {//INGRESA ACUERDO DE PAGO
            $acuerdo = acuerdoPago($datos);
        }
        return true;
    }

    function obligacionescobro($datos_obligacion) {//realiza el insert en obligacionescobro
//        print_r($datos_obligacion); die();
        $this->db->set('COD_COBRO_PERSUASIVO', $datos_obligacion['cod_cobro_persuasivo'], FALSE);
        $this->db->set('FECHA_ACEPTACION', "TO_DATE('" . $datos_obligacion['fecha_aceptacion'] . "','YY/MM/DD ')", FALSE);
        $this->db->set('COD_TIPOPAGO', $datos_obligacion['tipopago'], FALSE);
        if ($datos_obligacion['tipopago'] == 3) {
            $this->db->set('ACUERDO_PAGO', 'S');
        } else {
            $this->db->set('ACUERDO_PAGO', 'N');
        }


        return FALSE;
    }

    function acuerdoPago($datos) {
            $this->db->select("ACUERDOPAGO");
        $this->db->from("ACUERDOPAGO");
        $this->db->where("ACUERDOPAGO.COD_FISCALIZACION=", $datos['cod_fiscalizacion'], FALSE);
        $resultado1 = $this->db->get();
        if ($resultado1->num_rows() == 0):
            $this->db->set('COD_FISCALIZACION', $datos['cod_fiscalizacion']);
            $this->db->set('NIT_EMPRESA', $datos['']);
            $this->db->set('FECHA_MEDIDAS', FECHA, FALSE);
            $this->db->set('COD_RESPUESTAGESTION', MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO);
            $query = $this->db->insert('MC_MEDIDASCAUTELARES');

        endif;
    }

    /* Función que permite enviar un proceso a Medidas Cautelares y Mandamiento de Pago */

    function insert_mc($datos) {
        //VERIFICA QUE NO EXISTA UNA   MEDIDA CAUTELAR PARA ESA FISCALIZACION

        $this->db->select("COD_MEDIDACAUTELAR");
        $this->db->from("MC_MEDIDASCAUTELARES");
        $this->db->where("MC_MEDIDASCAUTELARES.COD_FISCALIZACION=", $datos['cod_fiscalizacion'], FALSE);
        $resultado1 = $this->db->get();
        if ($resultado1->num_rows() == 0):
            $this->db->set('COD_FISCALIZACION', $datos['cod_fiscalizacion']);
            $this->db->set('COD_GESTIONCOBRO', $datos['idgestion']);
            $this->db->set('FECHA_MEDIDAS', FECHA, FALSE);
            $this->db->set('COD_RESPUESTAGESTION', MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO);
            $query = $this->db->insert('MC_MEDIDASCAUTELARES');

        endif;

        //VERIFICA QUE NO EXISTA UN MANDAMIENTO DE PAGO PARA LA FISCALIZACION.
        $this->db->select("MANDAMIENTOPAGO.COD_MANDAMIENTOPAGO");
        $this->db->from("MANDAMIENTOPAGO");
        $this->db->where("MANDAMIENTOPAGO.COD_FISCALIZACION=", $datos['cod_fiscalizacion'], FALSE);
        $resultado2 = $this->db->get();
        if ($resultado2->num_rows() == 0) :
            $this->db->set('COD_FISCALIZACION', $datos['cod_fiscalizacion']);
            $this->db->set('FECHA_MANDAMIENTO', FECHA, FALSE);
            $this->db->set('CREADO_POR', ID_USER, FALSE);
            $this->db->set('ASIGNADO_A', ID_USER, FALSE);
            $this->db->set('COD_GESTIONCOBRO', $datos['idgestion']);
            $this->db->set('ESTADO', MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO);
            $query = $this->db->insert('MANDAMIENTOPAGO');
        endif;
    }

    function consulta_requerimiento_generado($search, $reg, $cod_respuesta, $regional) {

        $this->load->library('datatables');
        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO,COBROPERSUASIVO.COD_TIPO_RESPUESTA,COBROPERSUASIVO.RUTA_DOC_ACERCAMIENTO, COBROPERSUASIVO.OBSERVACIONES, COBROPERSUASIVO.FECHA_CREACION, RECEPCIONTITULOS.COD_RECEPCIONTITULO, RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,'
                . 'COBROPERSUASIVO.NIT_EMPRESA,FISCALIZACION.COD_ABOGADO,FISCALIZACION.CODIGO_PJ,EMPRESA.NOMBRE_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL, FISCALIZACION.COD_CONCEPTO,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,EMPRESA.COD_REGIONAL, EMPRESA.AUTORIZA_NOTIFIC_EMAIL, EMPRESA.EMAILAUTORIZADO,REGIONAL.NOMBRE_REGIONAL, RESPUESTAGESTION.NOMBRE_GESTION    ');
        $this->db->from('COBROPERSUASIVO');
        $this->db->join('RECEPCIONTITULOS', 'RECEPCIONTITULOS.COD_RECEPCIONTITULO =COBROPERSUASIVO.COD_RECEPCIONTITULO', 'inner');
        $this->db->join('FISCALIZACION', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('RESPUESTAGESTION', 'COBROPERSUASIVO.COD_TIPO_RESPUESTA=RESPUESTAGESTION.COD_RESPUESTA', 'inner');
        // $this->db->where('COBROPERSUASIVO.COD_ESTADO_PROCESO', $cod_respuesta);
        $this->db->where('COBROPERSUASIVO.COD_TIPO_RESPUESTA', $cod_respuesta);
        ///  $this->db->where('COBROPERSUASIVO.COD_ESTADO_PROCESO  ', $cod_respuesta);
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        //  $this->db->where('FISCALIZACION.COD_ABOGADO', ID_USER, FALSE);
        //$this->db->like('FISCALIZACION.CODIGO_PJ', $search);
        $this->db->limit(12, $reg);

        //el estado es para realizar el filtro
        $query = $this->db->get();
        $array = array();
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        return $array;
    }

    function totalData2($search, $cod_respuesta, $regional) {
        $this->load->library('datatables');
        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO,COBROPERSUASIVO.RUTA_DOC_ACERCAMIENTO, COBROPERSUASIVO.OBSERVACIONES, COBROPERSUASIVO.FECHA_CREACION, RECEPCIONTITULOS.COD_RECEPCIONTITULO, RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,'
                . 'COBROPERSUASIVO.NIT_EMPRESA,FISCALIZACION.COD_ABOGADO,FISCALIZACION.CODIGO_PJ,EMPRESA.NOMBRE_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL, '
                . 'FISCALIZACION.COD_CONCEPTO,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,EMPRESA.COD_REGIONAL, EMPRESA.AUTORIZA_NOTIFIC_EMAIL, '
                . 'EMPRESA.EMAILAUTORIZADO,REGIONAL.NOMBRE_REGIONAL, ESTADOCOBROPERSUASIVO.NOMBRE_ESTADO      ');
        $this->db->from('COBROPERSUASIVO');
        $this->db->join('RECEPCIONTITULOS', 'RECEPCIONTITULOS.COD_RECEPCIONTITULO =COBROPERSUASIVO.COD_RECEPCIONTITULO', 'inner');
        $this->db->join('FISCALIZACION', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('ESTADOCOBROPERSUASIVO', 'ESTADOCOBROPERSUASIVO.COD_ESTADO_PROCESO=COBROPERSUASIVO.COD_ESTADO_PROCESO', 'inner');
        $this->db->where('COBROPERSUASIVO.COD_TIPO_RESPUESTA', $cod_respuesta);
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        $this->db->where('FISCALIZACION.COD_ABOGADO', ID_USER, FALSE);

        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return '0';
        }
        return $query->num_rows();
    }

    function consulta_procesosjudiciales($search, $reg, $cod_respuesta, $regional) {
        $this->load->library('datatables');
        $this->db->select(' AP_PROCESOJUDICIAL.COD_PROCJUDICIAL, COBROPERSUASIVO.COD_COBRO_PERSUASIVO, EMPRESA.COD_REGIONAL,AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO,'
                . ' AP_PROCESOJUDICIAL.NOMBRE_DOCUMENTO,AP_PROCESOJUDICIAL.FECHA_CREACION,FISCALIZACION.COD_CONCEPTO,,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,'
                . ' AP_PROCESOJUDICIAL.NOMBRE_DOC_FIRMADO,EMPRESA.REPRESENTANTE_LEGAL, AP_PROCESOJUDICIAL.FECHA_DOC_FIRMADO, '
                . 'EMPRESA.NOMBRE_EMPRESA,COBROPERSUASIVO.COD_TIPO_RESPUESTA,FISCALIZACION.CODIGO_PJ,'
                . 'COBROPERSUASIVO.NIT_EMPRESA  ,'
                . ' REGIONAL.NOMBRE_REGIONAL, RESPUESTAGESTION.NOMBRE_GESTION, COBROPERSUASIVO.COD_FISCALIZACION');
        $this->db->from('AP_PROCESOJUDICIAL ');
        $this->db->join('COBROPERSUASIVO', 'COBROPERSUASIVO.COD_COBRO_PERSUASIVO=AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO  ', 'inner');
        $this->db->join('RESPUESTAGESTION', 'COBROPERSUASIVO.COD_TIPO_RESPUESTA=RESPUESTAGESTION.COD_RESPUESTA', 'inner');

        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA', 'inner');
        $this->db->join('FISCALIZACION', 'FISCALIZACION.COD_FISCALIZACION=COBROPERSUASIVO.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->where('COBROPERSUASIVO.COD_TIPO_RESPUESTA', $cod_respuesta, FALSE);
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        //$this->db->where('FISCALIZACION.COD_ABOGADO', ID_USER, FALSE);
        $this->db->limit(12, $reg);
        $query = $this->db->get();
        $array = array();
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        return $array;
    }

    function totalDatapj($search, $cod_respuesta, $regional) {
        $this->load->library('datatables');
        $this->db->select(' AP_PROCESOJUDICIAL.COD_PROCJUDICIAL,COBROPERSUASIVO.COD_TIPO_RESPUESTA,COBROPERSUASIVO.COD_COBRO_PERSUASIVO, '
                . 'AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO,'
                . 'AP_PROCESOJUDICIAL.NOMBRE_DOCUMENTO,AP_PROCESOJUDICIAL.FECHA_CREACION,EMPRESA.COD_REGIONAL,'
                . 'AP_PROCESOJUDICIAL.NOMBRE_DOC_FIRMADO, AP_PROCESOJUDICIAL.FECHA_DOC_FIRMADO,EMPRESA.NOMBRE_EMPRESA, '
                . 'EMPRESA.REPRESENTANTE_LEGAL,FISCALIZACION.CODIGO_PJ, COBROPERSUASIVO.NIT_EMPRESA ,'
                . 'FISCALIZACION.COD_CONCEPTO,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, REGIONAL.NOMBRE_REGIONAL, RESPUESTAGESTION.NOMBRE_GESTION,'
                . ' COBROPERSUASIVO.COD_FISCALIZACION');
        $this->db->from('AP_PROCESOJUDICIAL ');
        $this->db->join('COBROPERSUASIVO', 'COBROPERSUASIVO.COD_COBRO_PERSUASIVO=AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO ', 'inner');
        $this->db->join('RESPUESTAGESTION', 'COBROPERSUASIVO.COD_TIPO_RESPUESTA=RESPUESTAGESTION.COD_RESPUESTA', 'inner');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('FISCALIZACION', 'FISCALIZACION.COD_FISCALIZACION=COBROPERSUASIVO.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->where('COBROPERSUASIVO.COD_TIPO_RESPUESTA', $cod_respuesta, FALSE);
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        //$this->db->where('FISCALIZACION.COD_ABOGADO', ID_USER, FALSE);
        //   $this->db->like('COBROPERSUASIVO.COD_COBRO_PERSUASIVO', $search);
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return '0';
        return $query->num_rows();
    }

    function get_pj($id_pj) {
        $this->db->select(' AP_PROCESOJUDICIAL.COD_PROCJUDICIAL,COBROPERSUASIVO.COD_COBRO_PERSUASIVO, AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO,'
                . ' AP_PROCESOJUDICIAL.NOMBRE_DOCUMENTO,AP_PROCESOJUDICIAL.FECHA_CREACION,'
                . ' AP_PROCESOJUDICIAL.NOMBRE_DOC_FIRMADO, AP_PROCESOJUDICIAL.FECHA_DOC_FIRMADO, '
                . ' RESPUESTAGESTION.NOMBRE_GESTION,EMPRESA.TELEFONO_FIJO,EMPRESA.DIRECCION, '
                . 'COBROPERSUASIVO.NIT_EMPRESA ,EMPRESA.NOMBRE_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL, REGIONAL.NOMBRE_REGIONAL,'
                . 'COBROPERSUASIVO.COD_FISCALIZACION, FISCALIZACION.COD_CONCEPTO,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,FISCALIZACION.CODIGO_PJ');
        $this->db->from('AP_PROCESOJUDICIAL  ');
        $this->db->join('COBROPERSUASIVO', 'COBROPERSUASIVO.COD_COBRO_PERSUASIVO=AP_PROCESOJUDICIAL.COD_COBROPERSUASIVO ', 'inner');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('FISCALIZACION', 'FISCALIZACION.COD_FISCALIZACION=COBROPERSUASIVO.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=COBROPERSUASIVO.COD_TIPO_RESPUESTA', 'inner');
        $this->db->where("AP_PROCESOJUDICIAL.COD_PROCJUDICIAL", $id_pj);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_causalesdevolucion() {
        $this->db->select("CAUSALDEVOLUCION.COD_CAUSALDEVOLUCION, CAUSALDEVOLUCION.NOMBRE_CAUSAL");
        $this->db->where('COD_ESTADO', 1);
        $dato = $this->db->get("CAUSALDEVOLUCION");
        return $dato->result_array();
    }

    function plantilla_notificacion() {
        $this->db->select('PLANTILLA.ARCHIVO_PLANTILLA');
        $this->db->where('PLANTILLA.CODPLANTILLA', 10);
        $dato = $this->db->get('PLANTILLA');
        return $dato->result_array();
    }

    function abogado($id_usuario) {
        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", ABOGADO);
        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        if ($dato->num_rows() == 0)
            return '0';
        return $dato->num_rows();
    }

    function secretario($id_usuario) {
        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", SECRETARIO);
        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->num_rows();
        }
    }

    function coordinador($id_usuario) {
        $this->db->select("USUARIOS_GRUPOS.IDUSUARIOGRUPO");
        $this->db->where("USUARIOS_GRUPOS.IDGRUPO", COORDINADOR);
        $this->db->where("USUARIOS_GRUPOS.IDUSUARIO", $id_usuario);
        $dato = $this->db->get("USUARIOS_GRUPOS");
        if ($dato->num_rows() == 0) {
            return '0';
        } else {
            return $dato->num_rows();
        }
    }

    function consulta_datosdeuda($idfiscalizacion) {
        $this->db->select('LIQUIDACION.TOTAL_LIQUIDADO , LIQUIDACION.TOTAL_INTERESES,LIQUIDACION.NUM_LIQUIDACION, LIQUIDACION.COD_CONCEPTO, LIQUIDACION.TOTAL_CAPITAL,LIQUIDACION.SALDO_DEUDA');
        $this->db->where('LIQUIDACION.COD_FISCALIZACION', $idfiscalizacion);
        $dato = $this->db->get('LIQUIDACION');
        return $dato->result_array();
    }

    function consulta_resolucion($num_liquidacion) {
        $this->db->select('RESOLUCION.NUMERO_RESOLUCION');
        $this->db->where('RESOLUCION.NUM_LIQUIDACION', $num_liquidacion);
        $dato = $this->db->get('RESOLUCION');
        return $dato->result_array();
    }

    function abogado_asignado($num_fiscalizacion) {
        $this->db->select('FISCALIZACION.COD_ABOGADO, FISCALIZACION.CODIGO_PJ');
        $this->db->where('FISCALIZACION.COD_FISCALIZACION', $num_fiscalizacion, FALSE);
        $dato = $this->db->get("FISCALIZACION");
        return $dato->result_array();
    }

    function mail_autorizado($nit) {
        $this->db->select("EMPRESA.AUTORIZA_NOTFIC_EMAIL, EMPRESA.EMAILAUTORIZADO");
        $this->db->where("USUARIOS.CODEMPRESA", $nit);
        $query = $this->db->get("EMPRESA");
        $array = array();
        if ($query->num_rows() > 0) {
            $array = $query->result();
        }
        return $array[0];
    }

    function get_cobropersuasivo($id) {
        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO,COBROPERSUASIVO.COD_RECEPCIONTITULO,COBROPERSUASIVO.VALOR_PAGAR,COBROPERSUASIVO.COD_FISCALIZACION,'
                . 'COBROPERSUASIVO.RUTA_DOC_ACERCAMIENTO,RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,'
                . 'COBROPERSUASIVO.NIT_EMPRESA,EMPRESA.NOMBRE_EMPRESA,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.AUTORIZA_NOTIFIC_EMAIL,'
                . ' EMPRESA.EMAILAUTORIZADO, EMPRESA.TELEFONO_FIJO, EMPRESA.DIRECCION, REGIONAL.NOMBRE_REGIONAL, ESTADOCOBROPERSUASIVO.NOMBRE_ESTADO,'
                . 'FISCALIZACION.COD_FISCALIZACION,FISCALIZACION.CODIGO_PJ,'
                . 'CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,COBROPERSUASIVO.COD_TIPO_RESPUESTA,RESPUESTAGESTION.NOMBRE_GESTION ');
        $this->db->from('COBROPERSUASIVO');
        $this->db->join('RECEPCIONTITULOS', 'RECEPCIONTITULOS.COD_RECEPCIONTITULO=COBROPERSUASIVO.COD_RECEPCIONTITULO');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA ', 'inner');
        $this->db->join('FISCALIZACION', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('ESTADOCOBROPERSUASIVO', 'ESTADOCOBROPERSUASIVO.COD_ESTADO_PROCESO=COBROPERSUASIVO.COD_ESTADO_PROCESO', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=COBROPERSUASIVO.COD_TIPO_RESPUESTA', 'inner');
        $this->db->where('COBROPERSUASIVO.COD_COBRO_PERSUASIVO', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function documento_req($idcobro) {
        $this->db->select('GESTION_ACERCAMIENTO.COD_GESTION,GESTION_ACERCAMIENTO.RUTA_DOCUMENTO,GESTION_ACERCAMIENTO.NOMBRE_DOCUMENTO,GESTION_ACERCAMIENTO.COD_USUARIO,'
                . 'USUARIOS.APELLIDOS, USUARIOS.NOMBRES ');
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=GESTION_ACERCAMIENTO.COD_USUARIO");
        $this->db->where("TIPO_DOCUMENTO", DOCUMENTO_REQUERIMIENTO);
        $this->db->where("COD_COBROPERSUASIVO", $idcobro);
        $this->db->where("COD_RESPUESTA", DOCUMENTO_REQUERIMIENTO_APROBADO);
        $this->db->order_by("COD_GESTION", "DESC");
        $dato = $this->db->get('GESTION_ACERCAMIENTO');
        return $dato->result_array();
    }

    function concurrencia_deudor($reg, $search, $respuesta, $regional) {

        $this->load->library('datatables');
        $this->db->select('COBROPERSUASIVO.COD_COBRO_PERSUASIVO ,COBROPERSUASIVO.RUTA_DOC_ACERCAMIENTO, COBROPERSUASIVO.OBSERVACIONES, COBROPERSUASIVO.FECHA_CREACION,'
                . 'RECEPCIONTITULOS.COD_RECEPCIONTITULO, RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA,'
                . 'COBROPERSUASIVO.NIT_EMPRESA, COBROPERSUASIVO.VALOR_PAGAR,FISCALIZACION.COD_ABOGADO,RESPUESTAGESTION.NOMBRE_GESTION,EMPRESA.NOMBRE_EMPRESA,'
                . 'EMPRESA.REPRESENTANTE_LEGAL, FISCALIZACION.COD_CONCEPTO,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,EMPRESA.COD_REGIONAL, EMPRESA.AUTORIZA_NOTIFIC_EMAIL,'
                . ' EMPRESA.EMAILAUTORIZADO,REGIONAL.NOMBRE_REGIONAL, ESTADOCOBROPERSUASIVO.NOMBRE_ESTADO');
        $this->db->from('COBROPERSUASIVO');
        $this->db->join('RECEPCIONTITULOS', 'RECEPCIONTITULOS.COD_RECEPCIONTITULO=COBROPERSUASIVO.COD_RECEPCIONTITULO ', 'inner');

        $this->db->join('FISCALIZACION', 'RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION', 'inner');
        $this->db->join('CONCEPTOSFISCALIZACION ', 'FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', 'inner');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=COBROPERSUASIVO.NIT_EMPRESA', 'inner');
        $this->db->join('REGIONAL ', 'REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
        $this->db->join('ESTADOCOBROPERSUASIVO', 'ESTADOCOBROPERSUASIVO.COD_ESTADO_PROCESO=COBROPERSUASIVO.COD_ESTADO_PROCESO', 'inner');
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=COBROPERSUASIVO.COD_TIPO_RESPUESTA', 'inner');
        $this->db->where_in('COBROPERSUASIVO.COD_TIPO_RESPUESTA', $respuesta);
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        // $this->db->where('FISCALIZACION.COD_ABOGADO', ID_USER, FALSE);
        $dato = $this->db->get();
        return $dato->result_array();
    }

}
