<?php

/**
 * Archivo para la administraciÃ³n de los modelos necesarios para generar el acercamiento persuasivo en la DirecciÃ³n Juridica
 *
 * @packageCartera
 * @subpackage Models
 * @author vferreira
 * @location./application/models/acercamientopersuasivo_model.php

 */
class Acercamientopersuasivo_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function consulta_procesos($regional, $usuario = FALSE, $cod_proceso = FALSE) {
        /**
         * Función que devuelve la información de los procesos que se encuentran en acercamientopersuasivo
         *
         * @param integer $regional. corresponde a la regional del usuario logueado.
         * @param integer $usuario, corresponde al usuario logueado.
         * @return array $cod_proceso, corresponde al codigo del proceso coactivo
         * @return array 
         */
        $query = "SELECT CP.COD_COBRO_PERSUASIVO AS COD_COBRO,CP.RUTA_DOC_ACERCAMIENTO AS RUTA,CP.COD_TIPO_RESPUESTA,
                VW.RESPUESTA AS ESTADO,
                PC.COD_PROCESO_COACTIVO AS COD_PROCESO,
                PC.ABOGADO AS ABOGADO, 
                PC.COD_PROCESOPJ ,
                VW.EJECUTADO AS NOMBRE,
                PC.IDENTIFICACION AS IDENTIFICACION, 
                US.NOMBRES, US.APELLIDOS,  TO_CHAR(CP.FECHA_ENVIO_DOCUMENTO,'DD-MM-YYYY') AS FECHA_ENVIO,  
                VW.NOMBRE_REGIONAL AS NOMBRE_REGIONAL, 
                VW.COD_REGIONAL AS COD_REGIONAL,VW.CONCEPTO,
                VW.FECHA_COACTIVO AS FECHA_RECEPCION,VW.SALDO_DEUDA,VW.SALDO_CAPITAL,VW.SALDO_INTERES,VW.NO_EXPEDIENTE,
                VW.COD_EXPEDIENTE_JURIDICA, VW.ULTIMA_ACTUACION 
                FROM  COBROPERSUASIVO CP 
                INNER JOIN PROCESOS_COACTIVOS PC ON PC.COD_PROCESO_COACTIVO=CP.COD_PROCESO_COACTIVO
                INNER JOIN VW_PROCESOS_COACTIVOS VW ON VW.COD_PROCESO_COACTIVO=PC.COD_PROCESO_COACTIVO
                INNER JOIN USUARIOS US ON US.IDUSUARIO=PC.ABOGADO
                WHERE VW.COD_RESPUESTA = CP.COD_TIPO_RESPUESTA AND  VW.COD_REGIONAL= " . $regional;
        if (!empty($cod_proceso)):
            $query = $query . " AND PC.COD_PROCESO_COACTIVO = " . $cod_proceso;
        endif;
        $resultado = $this->db->query($query);
        //echo $this->db->last_query();
        $resultado = $resultado->result_array();
        return $resultado;
    }

    function cabecera($respuesta, $proceso) {

        $this->db->select('PC.COD_PROCESOPJ ,VW.CONCEPTO,VW.COD_REGIONAL,VW.COD_CPTO_FISCALIZACION, PC.FECHA_AVOCA,VW.PROCESO, VW.RESPUESTA,VW.IDENTIFICACION, VW.EJECUTADO,VW.DIRECCION,VW.TELEFONO');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->join('PROCESOS_COACTIVOS PC','PC.COD_PROCESO_COACTIVO=VW.COD_PROCESO_COACTIVO');
        $this->db->where('VW.COD_RESPUESTA', $respuesta);
        $this->db->where('VW.COD_PROCESO_COACTIVO', $proceso);
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado[0];
    }

    function guarda_requerimieto($datos) {//cuando el abogado g el requerimiento
//         echo "<pre>";
//         print_r($datos); echo "</pre>"; 
        $fecha_actual = 'SYSDATE';
        //   $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($this->db->affected_rows() == '1') {
            //actualiza la liquidacion
            //  $this->db->set('COD_TIPOPROCESO', PROCESO_ACERCAMIENTO_PERSUASIVO, FALSE);
            //$this->db->where('NUM_lIQUIDACION', $datos['num_liquidacion'], FALSE);
            // $this->db->update('LIQUIDACION');
            //guarda el documento .txt
            $fecha_actual = 'SYSDATE';
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro'], FALSE);
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

    function observaciones($idcobro, $tipodocumento) {
        $this->db->select('GESTION_ACERCAMIENTO.FECHA,GESTION_ACERCAMIENTO.OBSERVACIONES,GESTION_ACERCAMIENTO.COD_USUARIO,'
                . 'USUARIOS.APELLIDOS, USUARIOS.NOMBRES ');
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=GESTION_ACERCAMIENTO.COD_USUARIO");
        $this->db->where("TIPO_DOCUMENTO", $tipodocumento);
        $this->db->where("COD_COBROPERSUASIVO", $idcobro);
        // $this->db->where("OBSERVACIONES <> ''");
        //$this->db->where("OBSERVACIONES IS NOT NULL");
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

    function revisa_gestion($datos) {//secretario revisa 
        $fecha_actual = 'SYSDATE';

        //  $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
        $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($this->db->affected_rows() == '1') {
            $fecha_actual = 'SYSDATE';
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro'], FALSE);
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

    /* Función que consulta los datos del   proceso de acercamiento persuasivo */

    function cobropersuasivo($idcobro) {

        $this->db->select('COD_COBRO_PERSUASIVO');
        $this->db->from('COBROPERSUASIVO CP');
        $this->db->where('CP.COD_COBRO_PERSUASIVO', $idcobro);
        $query = $this->db->get();
        return $query->result_array();
    }

    /* Función que guarda la información del documento adjunto y actualiza el proceso de acercamiento persuasivo */

    function aprueba_gestion($datos) {//APRUEBA REQUERMIENTO EL COORDINADOR
//        echo "hola";
//           echo "<pre>";
//            print_r($datos);
//            echo "</pre>";die();
        $fecha_actual = 'SYSDATE';
        $fecha_envio = "TO_DATE('" . $datos['fecha_radicado'] . "','YYYY/MM/DD')";

        $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE); /* Cuando se actualice la traza */
        if ($datos['tipo_respuesta'] != 190):
            $this->db->set('RUTA_DOC_ACERCAMIENTO', $datos['ruta']);
        endif;
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        if (!empty($datos['numero_documento'])) :
            $this->db->set('NRO_DOCUMENTO', $datos['numero_documento']);
        endif;

        if (!empty($datos['fecha_radicado'])):
            $this->db->set("FECHA_ENVIO_DOCUMENTO", $fecha_envio, FALSE);
        endif;
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($this->db->affected_rows() == '1') {
            $fecha_actual = 'SYSDATE';
            $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro'], FALSE);
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

    function causalesdevolucion() {
        $this->db->select("CAUSALDEVOLUCION.COD_CAUSALDEVOLUCION, CAUSALDEVOLUCION.NOMBRE_CAUSAL");
        $this->db->where('COD_ESTADO', 1);
        $dato = $this->db->get("CAUSALDEVOLUCION");
        return $dato->result_array();
    }

    function requerimiento_acercamiento($datos) {

        //Realizo el insert en la tabla de NOTIFICACIONCOBRO
        $notificacion = 0;
        $fecha_actual = 'SYSDATE';
        $mym = 0;
        if ((!empty($datos['num_radicado'])) || (!empty($datos['num_radicado_dev']))) :
            $notificacion = $this->guarda_notificacioncobro($datos);
        endif;
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO)://Envia a Medidas Cautelares y Mandamiento de Pago
            $mym = $this->insert_mc($datos);
        endif;

        // $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
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

        if (!empty($datos['cod_cobro'])) :
            $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro'], FALSE);
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

    function aceptar_obligaciones($datos,$titulos_facilidad=FALSE) {

        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta'], FALSE);
        $this->db->set('OBLIGACION_ACEPTADA', $datos['obligacion_aceptada']);
        if (!empty($datos['tipopago']))
            $this->db->set('TIPO_PAGO', $datos['tipopago']);
        if (!$datos['fecha_aceptacion']) {
            $this->db->set('FECHA_ACEPTA_OBLIGACIONES', FECHA, FALSE);
        } else {
            $this->db->set('FECHA_ACEPTA_OBLIGACIONES', "TO_DATE('" . $datos['fecha_aceptacion'] . "','YY/MM/DD HH24:MI:SS')", FALSE);
        }
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO) {
            $info = $this->insert_mc($datos);
        }
        if ($datos['obligacion_aceptada'] == 'S') {
      
        }
        if ($datos['tipo_gestion'] == 106) {//INGRESA ACUERDO DE PAGO
           
            $this->db->select("NRO_ACUERDOPAGO");
            $this->db->from("ACUERDOPAGO");
            $this->db->where("ACUERDOPAGO.COD_PROCESO_COACTIVO=", $datos['cod_proceso'], FALSE);
            $resultado1 = $this->db->get();
            if ($resultado1->num_rows() == 0):

                $this->db->set("NITEMPRESA", $datos['nit']);
                $this->db->set("COD_RESPUESTA", $datos['tipo_respuesta'], FALSE);
                $this->db->set("USUARIO_GENERA", ID_USUARIO, FALSE);
                $this->db->set("COD_CONCEPTO_COBRO", $datos['cod_concepto'], FALSE);
                $this->db->set("FECHA_CREACION", $fecha_actual, FALSE);
                $this->db->set("COD_REGIONAL", $datos['cod_regional'], FALSE);
                $this->db->set("ESTADOACUERDO", 1);
                $this->db->set("JURIDICO", 1);
                $this->db->set("COD_PROCESO_COACTIVO", $datos['cod_proceso'], FALSE);
                $this->db->insert("ACUERDOPAGO");
                 
                foreach($titulos_facilidad as $liquidacion):

                    $this->db->set('COD_PROCESO_COACTIVO',$datos['cod_proceso'], FALSE);
                    $this->db->set('COD_TIPOPROCESO',18);
                    $this->db->where('NUM_LIQUIDACION',$liquidacion);
                    $this->db->update('LIQUIDACION');
              
                endforeach; 
                    
                
                
            endif;
        }
        return true;
    }

    function obligacionescobro($datos_obligacion) {//realiza el insert en obligacionescobro
//        print_r($datos_obligacion); die();
        $this->db->set('COD_COBRO_PERSUASIVO', $datos_obligacion['cod_cobro'], FALSE);
        $this->db->set('FECHA_ACEPTACION', "TO_DATE('" . $datos_obligacion['fecha_aceptacion'] . "','YY/MM/DD ')", FALSE);
        $this->db->set('COD_TIPOPAGO', $datos_obligacion['tipopago'], FALSE);
        if ($datos_obligacion['tipopago'] == 3) {
            $this->db->set('ACUERDO_PAGO', 'S');
        } else {
            $this->db->set('ACUERDO_PAGO', 'N');
        }
        return FALSE;
    }

    function guarda_notificacioncobro($datos) {
        $fecha_actual = 'SYSDATE';
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro']);
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

    function correccion_documento($datos) {
        $this->db->set('COD_COBROPERSUASIVO', $datos['cod_cobro'], FALSE);
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

    function guarda_respuesta_notificacion($datos) {
//        echo "<pre>";
//        print_r($datos);
//        echo "</pre>";
//        die();
        $fecha_actual = 'SYSDATE';
        //  $this->db->set('COD_GESTION_COBRO', $datos['idgestion'], FALSE);
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
        $this->db->where('COD_COBRO_PERSUASIVO', $datos['cod_cobro'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        //insertar en Medidas Cautelares 
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO) {
            $query = $this->insert_mc($datos);
        }
        return true;
    }

    /* Función que permite consultar la deuda para una fiscalización */

    function consulta_datosdeuda($idfiscalizacion) {
        $this->db->select('LIQUIDACION.TOTAL_LIQUIDADO , LIQUIDACION.TOTAL_INTERESES,LIQUIDACION.NUM_LIQUIDACION, LIQUIDACION.COD_CONCEPTO, LIQUIDACION.TOTAL_CAPITAL,LIQUIDACION.SALDO_DEUDA');
        $this->db->where('LIQUIDACION.COD_FISCALIZACION', $idfiscalizacion);
        $dato = $this->db->get('LIQUIDACION');
        return $dato->result_array();
    }

    function insert_mc($datos) {
        //VERIFICA QUE NO EXISTA UNA   MEDIDA CAUTELAR PARA ESA FISCALIZACION
        $datos['idgestion'] = 536;
        $this->db->select("COD_MEDIDACAUTELAR");
        $this->db->from("MC_MEDIDASCAUTELARES");
        $this->db->where("MC_MEDIDASCAUTELARES.COD_PROCESO_COACTIVO=", $datos['cod_proceso'], FALSE);
        $resultado1 = $this->db->get();
        if ($resultado1->num_rows() == 0):
            $this->db->set('COD_PROCESO_COACTIVO', $datos['cod_proceso']);
            $this->db->set('COD_GESTIONCOBRO', '536');
            $this->db->set('FECHA_MEDIDAS', FECHA, FALSE);
            $this->db->set('COD_RESPUESTAGESTION', MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO);
            $query = $this->db->insert('MC_MEDIDASCAUTELARES');
        endif;

        //VERIFICA QUE NO EXISTA UN MANDAMIENTO DE PAGO PARA LA FISCALIZACION.
        $this->db->select("MANDAMIENTOPAGO.COD_MANDAMIENTOPAGO");
        $this->db->from("MANDAMIENTOPAGO");
        $this->db->where("MANDAMIENTOPAGO.COD_PROCESO_COACTIVO=", $datos['cod_proceso'], FALSE);
        $resultado2 = $this->db->get();
        if ($resultado2->num_rows() == 0) :
            $this->db->set('COD_PROCESO_COACTIVO', $datos['cod_proceso']);
            $this->db->set('FECHA_MANDAMIENTO', FECHA, FALSE);
            $this->db->set('CREADO_POR', ID_USUARIO, FALSE);
            $this->db->set('ASIGNADO_A', ID_USUARIO, FALSE);
            $this->db->set('COD_GESTIONCOBRO', $datos['idgestion']);
            $this->db->set('ESTADO', MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO);
            $query = $this->db->insert('MANDAMIENTOPAGO');
        endif;
    }

    function verificacion_pagos($datos) {

        $this->db->set('COD_TIPO_RESPUESTA', $datos['tipo_respuesta']);
        $this->db->where('COD_PROCESO_COACTIVO', $datos['cod_proceso'], FALSE);
        $this->db->update('COBROPERSUASIVO');
        //Cuando no pago se envia a medidas cautelares
        if ($datos['tipo_respuesta'] == MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO) {
            $query = $this->insert_mc($datos);
        }
        if ($datos['tipopago'] == 3) {
            $fecha_actual = 'SYSDATE';
            if (!empty($datos['observaciones'])) {
                $this->db->set('OBSERVACIONES', $datos['observaciones']);
            }
            $this->db->insert('GESTION_ACERCAMIENTO');
        }
        return true;
    }

    function titulos_coactivo($cod_coactivo) {
        $this->db->select('VW.NUM_LIQUIDACION');
        $this->db->from('VW_PROCESOS_COACTIVOS VW');
        $this->db->join('PROCESOS_COACTIVOS PC', 'PC.COD_RESPUESTA=VW.COD_RESPUESTA');
        $this->db->join('RECEPCIONTITULOS RT', 'RT.COD_RECEPCIONTITULO=VW.NO_EXPEDIENTE');
        $this->db->where('VW.COD_PROCESO_COACTIVO', $cod_coactivo);
        $this->db->where('RT.CERRADO', 0);
        $where = 'VW.SALDO_DEUDA >0';
        $this->db->where($where);
        $this->db->group_by('VW.NO_EXPEDIENTE, VW.NUM_LIQUIDACION');
        $resultado = $this->db->get();
        $resultado = $resultado->result_array();
        return $resultado;
    }

}

?>