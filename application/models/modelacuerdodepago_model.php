<?php

class Modelacuerdodepago_model extends CI_Model {

    function actualizar_acuerdo($data, $acuerdo, $tipo = NULL) {
        $this->db->trans_start();
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        switch ($tipo) {
            case NULL:
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                break;
            case 'pago_total':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('AUTO_ACUERDO', $data['AUTO_ACUERDO']);
                break;
            case 'pago_minimo':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('CUOTA_INICIAL_PAGADA', $data['CUOTA_INICIAL_PAGADA']);
                break;
            case 'garantia':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('TOTAL_GARANTIA', $data['TOTAL_GARANTIA']);
                break;
            case 'guardar':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('VALOR_CUOTA', $data['VALOR_CUOTA']);
                $this->db->set('NUMERO_CUOTAS', $data['NUMERO_CUOTAS']);
                break;
            case 'solicitud_ajuste':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('ACUERDO_AJUSTADO', $data['ACUERDO_AJUSTADO']);
                break;
            case 'excepcion':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('USUARIO_ASIGNADO', $data['USUARIO_ASIGNADO']);
                break;
            case 'autoriza_garantias':
                $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
                $this->db->set('USUARIO_ASIGNADO', $data['USUARIO_ASIGNADO']);
                break;
        }

        $this->db->update('ACUERDOPAGO');
        //echo $this->db->last_query();die;        
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    function actualizacuotas($acuerdo) {
        $this->db->trans_start();
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->where('PROYACUPAG_NUMCUOTA <>', 0);
        $this->db->set('PROYACUPAG_ESTADO', 0);
        $this->db->delete('PROYECCIONACUERDOPAGO');
        //echo $this->db->last_query();        
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    public function eliminar_plantillas($cod_fiscalizacion) {
        $this->db->where('COD_FISCALIZACION', $cod_fiscalizacion);
        $this->db->delete('COMUNICADOS_PJ');

        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    public function motivos() {
        $this->db->select('COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION');
        $this->db->where('IDESTADO', 1);
        $this->db->where('IDESTADO', 1);
        $this->db->where('NATURALEZA', 'N');
        $query = $this->db->get('MOTIVODEVOLUCION');
        //echo $this->db->last_query($acuerdo);   
        return $query->result();
        ;
    }

    public function insertar_expediente($data = NULL) {
//        if (!empty($datos)) :
//            $this->db->set('FECHA_RADICADO', "TO_DATE('" . $datos['FECHA_RADICADO'] . "', 'SYYYY-MM-DD HH24:MI:SS')", false);
//            unset($datos['FECHA_RADICADO']);
//            $this->db->insert("EXPEDIENTE", $datos);
//        endif;
        $this->db->set('RUTA_DOCUMENTO', $data['RUTA_DOCUMENTO']);
        $this->db->set('NOMBRE_DOCUMENTO', $data['NOMBRE_DOCUMENTO']);
        $this->db->set('COD_RESPUESTAGESTION', $data['COD_RESPUESTAGESTION']);
        $this->db->set('COD_TIPO_EXPEDIENTE', $data['COD_TIPO_EXPEDIENTE']);
        $this->db->set('ID_USUARIO', $data['ID_USUARIO']);
        $this->db->set('FECHA_RADICADO', $data['FECHA_RADICADO']);
        $this->db->set('COD_FISCALIZACION', $data['COD_FISCALIZACION']);
        $this->db->set('RADICADO_ONBASE', $data['RADICADO_ONBASE']);
        //$this->db->set('FECHA_ONBASE', $data['FECHA_ONBASE']); 
        $this->db->set('FECHA_ONBASE', "to_date('" . $data['FECHA_ONBASE'] . "','dd/mm/yyyy')", false);
        $this->db->insert('EXPEDIENTE');
        $this->db->trans_complete();
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    function acuerdoAprobado($data, $acuerdo) {
        $this->db->trans_start();
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
        $this->db->set('DOC_ACEPTACION', $data['DOC_ACEPTACION']);
        $this->db->set('ID_USUARIO_AUTORIZA', $data['ID_USUARIO_AUTORIZA']);
        $this->db->update('ACUERDOPAGO');
        //echo $this->db->last_query();        
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    function acuerdomoramenor($acuerdo, $dato, $acuerdo = null) {
        $fecha = "'" . date('d-m-Y') . "'";
        $this->db->select('ACUERDOPAGO.NRO_ACUERDOPAGO,ACUERDOPAGO.NRO_LIQUIDACION,ACUERDOPAGO.COD_FISCALIZACION,ACUERDOPAGO.AUTO_ACUERDO,
        ACUERDOPAGO.NRO_RESOLUCION,EMPRESA.RAZON_SOCIAL,PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA,
        ACUERDOPAGO.NITEMPRESA,PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO');

        $this->db->select("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) AS DIFERENCIA", false);
        $this->db->join('EMPRESA', 'ACUERDOPAGO.NITEMPRESA=EMPRESA.CODEMPRESA');
        $this->db->join('PROYECCIONACUERDOPAGO', 'PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO = ACUERDOPAGO.NRO_ACUERDOPAGO ');
        $this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO < to_date($fecha,'dd-mm-YYYY')", "", false);
        $this->db->where('PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO', 0);
        if ($acuerdo == 1)
            $this->db->where('JURIDICO', 1);
        else if ($acuerdo == 2)
            $this->db->where('JURIDICO', 2);
        if ($dato == 1)
            $this->db->where('PROYECCIONACUERDOPAGO.ATRASOACUERDO IS NULL');
        $this->db->where('PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA !=', 0);
        if (!empty($acuerdo))
            $this->db->where('ACUERDOPAGO.NRO_ACUERDOPAGO', $acuerdo);
        $this->db->where("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) <= 60", "", false);
        $this->db->where('ACUERDOPAGO.AUTO_ACUERDO', 'N');
        $acuerdo = $this->db->get('ACUERDOPAGO');
//        echo $this->db->last_query();die;

        return $acuerdo;
    }

    function reproceso($data) {

        $this->db->insert('REPROCESOACUERDOPAGO', $data);
    }

    function cuotaCero($data) {
        $this->db->insert('PROYECCIONACUERDOPAGO', $data);
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    function valoresCero($acuerdo, $fpcuota, $totaldeuda, $totalPagar, $totalCapital, $totalIntereses, $porcentaje, $superintendencia) {
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->set('FECHA_CUOTA_INICIAL ', $fpcuota);
        $this->db->set('VALOR_CUOTA_INICIAL ', $totalPagar);
        $this->db->set('VALOR_CAPITAL_FECHA ', $totalCapital);
        $this->db->set('VALO_RINTERESES_CAPITAL', $totalIntereses);
        $this->db->set('PORCENTAJE_CUOTA_INICIAL ', $porcentaje);
        $this->db->set('VALOR_TOTAL_FINANCIADO ', $totaldeuda);
        $this->db->set('TASA_INTERES ', $superintendencia);
        $this->db->set('COD_RESPUESTA ', 1261);
        $this->db->update('ACUERDOPAGO');
        //echo $this->db->last_query();        
        if ($this->db->affected_rows() >= 0) {
            $this->db->trans_complete();
            return TRUE;
        } else
            return FALSE;
    }

    function cronmora($acuerdo, $cuota) {

        $this->db->select("REGIONAL.NOMBRE_REGIONAL, EMPRESA.REPRESENTANTE_LEGAL,
                           EMPRESA.DIRECCION,ACUERDOPAGO.NRO_ACUERDOPAGO,PROYECCIONACUERDOPAGO.PROYACUPAG_CAPITALDEBE,
                           NRO_RESOLUCION, EMPRESA.CORREOELECTRONICO,EMPRESA.NOMBRE_EMPRESA,USUARIOS.NOMBRES, 
                           USUARIOS.APELLIDOS,REGIONAL.TELEFONO_REGIONAL,REGIONAL.EMAIL_REGIONAL,PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA");

        $this->db->join('PROYECCIONACUERDOPAGO', 'ACUERDOPAGO.NRO_ACUERDOPAGO=PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO');
        $this->db->join('INGRESOACUERDOJURIDICO', 'ACUERDOPAGO.NRO_LIQUIDACION=INGRESOACUERDOJURIDICO.NUM_LIQUIDACION', 'LEFT');
        $this->db->join('USUARIOS', 'INGRESOACUERDOJURIDICO.COD_ABOGADO=USUARIOS.IDUSUARIO', 'LEFT');
        $this->db->join('EMPRESA', 'ACUERDOPAGO.NITEMPRESA=EMPRESA.CODEMPRESA');
        $this->db->join('REGIONAL', 'EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL');
        $this->db->where('PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO', $acuerdo);
        $this->db->where('PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA', $cuota);
        //echo $this->db->last_query();       
        return $this->db->get('ACUERDOPAGO');
    }

    function acuerdomoramayor($acuerdo) {

        $fecha = "'" . date('d-m-Y') . "'";
        $this->db->select('ACUERDOPAGO.NRO_ACUERDOPAGO,ACUERDOPAGO.NRO_LIQUIDACION,ACUERDOPAGO.COD_FISCALIZACION,
        ACUERDOPAGO.NRO_RESOLUCION,EMPRESA.RAZON_SOCIAL,ACUERDOPAGO.AUTO_ACUERDO,
        ACUERDOPAGO.NITEMPRESA,PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO,
        PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA');
        $this->db->select("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) AS DIFERENCIA", false);
        $this->db->join('EMPRESA', 'ACUERDOPAGO.NITEMPRESA=EMPRESA.CODEMPRESA');
        $this->db->join('PROYECCIONACUERDOPAGO', 'PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO = ACUERDOPAGO.NRO_ACUERDOPAGO ');
//        $this->db->join('REPROCESOACUERDOPAGO','REPROCESOACUERDOPAGO.NRO_ACUERDOPAGO = PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO AND "REPROCESOACUERDOPAGO"."NRO_ACUERDOPAGO" != PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO AND  "REPROCESOACUERDOPAGO"."CUOTA" != PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA','LEFT');
        if ($acuerdo == 1)
            $this->db->where('ACUERDOPAGO.JURIDICO', 1);
        else if ($acuerdo == 2)
            $this->db->where('ACUERDOPAGO.JURIDICO', 2);
        $this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO < to_date($fecha,'dd-mm-YYYY')", "", false);
        $this->db->where('PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO', 0);
        $this->db->where('PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA !=', 0);
        //$this->db->where("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) >= 31","",false);
        //$this->db->where("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) <= 60","",false);
//        $this->db->where('ATRASOACUERDO',1);
        //$this->db->where("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) >= 31","",false);
        $this->db->where("(SELECT round((to_date($fecha,'dd-mm-YYYY') - to_date(PROYACUPAG_FECHALIMPAGO )))from dual) >= 60", "", false);
        $this->db->where('NOT EXISTS(SELECT' . "'" . 'X' . "'" . 'FROM REPROCESOACUERDOPAGO WHERE "REPROCESOACUERDOPAGO"."NRO_ACUERDOPAGO" = PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO AND 
        "REPROCESOACUERDOPAGO"."CUOTA" = PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA)', "", false);
        $this->db->where('ACUERDOPAGO.AUTO_ACUERDO', 'N');
        $acuerdo = $this->db->get('ACUERDOPAGO');
        //echo $this->db->last_query();die;

        return $acuerdo;
    }

    function actualizaautorizacionadministrativo($fiscalizacion, $opcion, $observacion) {
//       3 : AUTORIZO
//       4 : NO AUTORIZO     
        $datos = array('EXCEPCION' => $opcion, 'OBSERVACION_COORDINADOR' => $observacion);
        $this->db->where('COD_FISCALIZACION', $fiscalizacion);
        $this->db->update('EXCEPCIONESACUERDO', $datos);
    }

    function administraciongarantiascampos() {
        $this->db->select('CAMPOSGARANTIA.COD_CAMPO,TIPOGARANTIA.NOMBRE_TIPOGARANTIA,CAMPOSGARANTIA.NOMBRE_CAMPO,TIPOGARANTIA.COD_TIPO_GARANTIA');
        $this->db->join('TIPOGARANTIA', "CAMPOSGARANTIA.COD_TIPO_GARANTIA = TIPOGARANTIA.COD_TIPO_GARANTIA");
        $this->db->where('TIPOGARANTIA.ESTADO', 1);
        $garantia = $this->db->get('CAMPOSGARANTIA');
        return $garantia;
    }

    function consultaexistenciagarantia($garantia) {

        $garantia1 = strtoupper($garantia);
        $this->db->where('NOMBRE_TIPOGARANTIA', $garantia1);
        $garantia = $this->db->get('TIPOGARANTIA');
//        echo $this->db->last_query();die;
        return $garantia->result_array();
    }

    function consultacuotainicial($nit, $acuerdo) {
        $this->db->where('ACUERDOPAGO.NITEMPRESA', $nit);
        $this->db->where('ACUERDOPAGO.NRO_ACUERDOPAGO', $acuerdo);
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = ACUERDOPAGO.NITEMPRESA');
        return $this->db->get('ACUERDOPAGO');
    }

    function consultaDatos($acuerdo) {
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->select('NRO_RESOLUCION, NITEMPRESA,NRO_ACUERDOPAGO,COD_FISCALIZACION,COD_RESPUESTA');
        $dato = $this->db->get('ACUERDOPAGO');
        return $dato->result_array;
    }

    function numero_resolucion($coactivo) {
        $dato = $this->db->query("SELECT RADICADO_ONBASE
                            FROM EXPEDIENTE
                            WHERE COD_RESPUESTAGESTION = 1398
                            AND COD_PROCESO_COACTIVO = $coactivo
                            ORDER BY ID_EXPEDIENTE DESC;");
        return $dato->result_array;
    }

    function cuotainicial() {
        $this->db->select('PORCENTAJE');
        $this->db->order_by('IDPORCENTAJE', 'desc');
        $cuota = $this->db->get('PORCENTAJECUOTAFP');
        //echo $this->db->last_query();die;
        return $cuota->result_array();
    }

    function verificaCero($acuerdo) {
        $this->db->select('PROYACUPAG_ESTADO,PROYACUPAG_FECHALIMPAGO,CEIL(SYSDATE - PROYACUPAG_FECHALIMPAGO) AS DIAS_MORA');
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->where('PROYACUPAG_NUMCUOTA', 0);
        $acuerdo = $this->db->get('PROYECCIONACUERDOPAGO');
        //echo $this->db->last_query($acuerdo);die;
        return $acuerdo->result_array();
    }

    function plantilla($id) {
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        @$dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array[0]['ARCHIVO_PLANTILLA'];
    }

    function proyectaracuerdo($acuerdo) {
        //REGIONAL.DIRECTOR_REGIONAL,
        $this->db->select("REGIONAL.NOMBRE_REGIONAL,ACUERDOPAGO.NRO_RESOLUCION,EMPRESA.NOMBRE_EMPRESA,
          ACUERDOPAGO.NITEMPRESA,ACUERDOPAGO.NRO_ACUERDOPAGO,EMPRESA.COD_REPRESENTANTELEGAL,EMPRESA.REPRESENTANTE_LEGAL
          ,MUNICIPIO.NOMBREMUNICIPIO,EMPRESA.DIRECCION,EMPRESA.TELEFONO_FIJO,
          
          REGIONAL.CEDULA_DIRECTOR,RESOLUCION.NUMERO_RESOLUCION,RESOLUCION.FECHA_CREACION,
          ACUERDOPAGO.VALOR_TOTAL_FINANCIADO,PROYECCIONACUERDOPAGO.PROYACUPAG_VALORCUOTA,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,
          ACUERDOPAGO.VALOR_CUOTA");

        $this->db->join('PROYECCIONACUERDOPAGO', 'ACUERDOPAGO.NRO_ACUERDOPAGO=PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO');
        $this->db->join('CONCEPTOSFISCALIZACION', 'ACUERDOPAGO.COD_CONCEPTO_COBRO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION');
        $this->db->join('EMPRESA', 'ACUERDOPAGO.NITEMPRESA=EMPRESA.CODEMPRESA');
        $this->db->join('MUNICIPIO', 'EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO');
        $this->db->join('REGIONAL', 'EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL');
        $this->db->join('RESOLUCION', 'RESOLUCION.COD_REGIONAL=REGIONAL.COD_REGIONAL');
        $this->db->where('ACUERDOPAGO.NRO_ACUERDOPAGO', $acuerdo);
        $acuerdo = $this->db->get('ACUERDOPAGO');
//        echo $this->db->last_query();die;

        return $acuerdo;
    }

    function consultacuotasacuerdopago($nit, $resolucion, $liquidacion) {
        $this->db->select("PROY.PROYACUPAG_VALORINTERESESMORA,PROY.PROYACUPAG_NUMCUOTA,
        PROY.PROYACUPAG_VALORCUOTA,PROY.PROYACUPAG_SALDOCAPITAL,to_char(PROY.PROYACUPAG_FECHALIMPAGO,'DD/MM/YYYY') as PROYACUPAG_FECHALIMPAGO,
        PROYACUPAG_CAPITALDEBE,PROYACUPAG_SALDO_INTACUERDO,PROYACUPAG_SALDO_CUOTA,PROYACUPAG_CAPITAL_CUOTA,PROYACUPAG_SALDO_INTCORRIENTE,PROYACUPAG_INTCORRIENTE_CUOTA,PROYACUPAG_INTCORRIENTE,
        ACUERDOPAGO.NRO_LIQUIDACION,EMPRESA.CODEMPRESA,ACUERDOPAGO.NRO_RESOLUCION,PROY.PROYACUPAG_ESTADO, CEIL(SYSDATE - PROYACUPAG_FECHALIMPAGO) AS DIAS_MORA", false);
        $this->db->order_by("PROY.PROYACUPAG_NUMCUOTA", "asc");
        $this->db->join("ACUERDOPAGO", "ACUERDOPAGO.NITEMPRESA = EMPRESA.CODEMPRESA", "LEFT");
        $this->db->join("PROYECCIONACUERDOPAGO PROY", "ACUERDOPAGO.NRO_ACUERDOPAGO = PROY.NRO_ACUERDOPAGO", "LEFT");
        $this->db->where("EMPRESA.CODEMPRESA", $nit);
        $this->db->where("ACUERDOPAGO.NRO_LIQUIDACION", $liquidacion);
        $this->db->where("ACUERDOPAGO.NRO_RESOLUCION", $resolucion);
        $proyeccion = $this->db->get("EMPRESA");
        //echo $this->db->last_query($proyeccion);die;        
        return $proyeccion;
    }

//      function consultacuotasacuerdopago($nit, $resolucion, $liquidacion) {
//        
//        $this->db->order_by("PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA", "asc");
//        $this->db->join("PROYECCIONACUERDOPAGO", "EMPRESA.CODEMPRESA = PROYECCIONACUERDOPAGO.EMP_CODEMPRESA", "LEFT");
//        $this->db->join("ACEPTACION_ACUERDO", "ACEPTACION_ACUERDO.NRO_ACUERDOPAGO = PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO", "LEFT");
//        $this->db->where("EMPRESA.CODEMPRESA", $nit);
//        $this->db->where("PROYECCIONACUERDOPAGO.LIQ_NUMLIQUIDACION", $liquidacion);
//        $this->db->where("PROYECCIONACUERDOPAGO.NUMERO_RESOLUCION", $resolucion);
//        $proyeccion=  $this->db->get("EMPRESA");
//        
////        echo $this->db->last_query();die;
//        
//        return $proyeccion;
//    }
//    
    function ciudad() {
        $this->db->order_by('NOMBREMUNICIPIO');
        return $this->db->get("MUNICIPIO");
    }

    function consultagarantiasjuridicas($liquidacion = null, $documento) {

        $this->db->select('GARANTIA_ACUERDO.COD_GARANTIA_ACUERDO,GARANTIA_ACUERDO.COD_ACUERDO_PAGO,GARANTIA_ACUERDO.VALOR_CAMPO,GARANTIA_ACUERDO.VALOR_AVALUO,GARANTIA_ACUERDO.VALOR_COMERCIAL,'
                . 'CAMPOSGARANTIA.NOMBRE_CAMPO,TIPOGARANTIA.NOMBRE_TIPOGARANTIA,GARANTIA_ACUERDO.CODEMPRESA,GARANTIA_ACUERDO.COD_TIPO_GARANTIA,GARANTIA_ACUERDO.COD_CAMPO');
        $this->db->join('CAMPOSGARANTIA', "GARANTIA_ACUERDO.COD_CAMPO = CAMPOSGARANTIA.COD_CAMPO");
        $this->db->join('TIPOGARANTIA', 'TIPOGARANTIA.COD_TIPO_GARANTIA =  GARANTIA_ACUERDO.COD_TIPO_GARANTIA"');
        $this->db->where('GARANTIA_ACUERDO.CODEMPRESA', $documento);
        if (!empty($liquidacion))
            $this->db->where('GARANTIA_ACUERDO.NUM_LIQUIDACION', "$liquidacion");
//        $this->db->where("COD_ACUERDO_PAGO",$acuerdo);
        $this->db->where('GARANTIA_ACUERDO.ESTADO', 0);
        $garantia = $this->db->get("GARANTIA_ACUERDO");

//        echo $this->db->last_query();
        return $garantia;
    }

    function garantias() {
        $this->db->select('TIPOGARANTIA.COD_TIPO_GARANTIA,TIPOGARANTIA.NOMBRE_TIPOGARANTIA');
        $this->db->distinct('TIPOGARANTIA.COD_TIPO_GARANTIA,TIPOGARANTIA.NOMBRE_TIPOGARANTIA');
        $this->db->join('CAMPOSGARANTIA','CAMPOSGARANTIA.COD_TIPO_GARANTIA = TIPOGARANTIA.COD_TIPO_GARANTIA');
        $this->db->where('TIPOGARANTIA.ESTADO',1);
        return $this->db->get("TIPOGARANTIA");
    }

    function singarantia() {

        $this->db->join('LIQUIDACION', 'LIQUIDACION.COD_FISCALIZACION = EXCEPCIONESACUERDO.COD_FISCALIZACION');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = LIQUIDACION.NITEMPRESA');
        $this->db->where('EXCEPCION IS NULL', "", false);
        $datos = $this->db->get('EXCEPCIONESACUERDO');
//        echo $this->db->last_query();die;
        return $datos;
    }

    function documento() {

        $datos = $this->db->get('TIPODOCUMENTO');
        return $datos->result_array();
    }

    function consultacuota($cuotas) {

        $this->db->where('' . $cuotas . ' BETWEEN DESDE AND HASTA', NULL, FALSE);
        $cuota = $this->db->get('CUANTIAS');
        //echo $this->db->last_query();
        return $cuota->result_array();
    }

    function consultaautorizaciones($acuerdo) {

//        $this->db->join("INGRESOACUERDOJURIDICO","EXCEPCIONESACUERDOJURIDICO.COD_ACUERDOJURIDICO = INGRESOACUERDOJURIDICO.COD_ACUERDOJURIDICO");
        $this->db->select('EMPRESA.CODEMPRESA,EMPRESA.RAZON_SOCIAL,INGRESOACUERDOJURIDICO.NUM_LIQUIDACION,INGRESOACUERDOJURIDICO.COD_ACUERDOJURIDICO');
        $this->db->where("EXCEPCION", 1);
//        $this->db->where('NRO_ACUERDOPAGO',$acuerdo);
        $this->db->where("COD_ACUERDOJURIDICO not in (select DISTINCT COD_ACUERDOJURIDICO from EXCEPCIONESACUERDOJURIDICO)", NULL, FALSE);
//        $this->db->or_where("COD_ACUERDOJURIDICO in (select DISTINCT COD_ACUERDOJURIDICO from EXCEPCIONESACUERDOJURIDICO WHERE EXCEPCION = 4)", NULL, FALSE); 
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA = INGRESOACUERDOJURIDICO.CODEMPRESA');
        return $this->db->get('INGRESOACUERDOJURIDICO');
    }

    function consultaautorizacionesacuerdo($regional) {
        $this->db->select("RESOLUCION.NUMERO_RESOLUCION,RESOLUCION.NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,
        LIQUIDACION.NUM_LIQUIDACION,RESOLUCION.COD_FISCALIZACION,ACUERDOPAGO.FECHA_CREACION,LIQUIDACION.COD_CONCEPTO,
        RESOLUCION.DETALLE_COBRO_COACTIVO,EMPRESA.COD_REGIONAL");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = RESOLUCION.NITEMPRESA", "left");
        $this->db->join("ACUERDOPAGO", "ACUERDOPAGO.NRO_RESOLUCION = RESOLUCION.NUMERO_RESOLUCION", "left");
        $this->db->join("LIQUIDACION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->where("RESOLUCION.COD_ESTADO", 80);
        //$this->db->where("RESOLUCION.NUM_LIQUIDACION IS NOT NULL");
        $this->db->where('DOCINIACUERDO IS NULL');
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        $this->db->where('LIQUIDACION.TOTAL_LIQUIDADO >', 0);
        $this->db->where('(COD_TIPOPROCESO = 2 OR COD_TIPOPROCESO = 18)');
        $this->db->where('LIQUIDACION.BLOQUEADA', 0);
        $acuerdo = $this->db->get("RESOLUCION");
        //echo $this->db->last_query();die;        
        return $acuerdo;
    }

    function tablacuantias() {
        $this->db->select("COD_CUANTIA,HASTA,DESDE,PORCENTAJE,PLAZOMESES");
        $this->db->where('TIPO_ACUERDO', 'A');
        return $this->db->get("CUANTIAS");
    }

    function Maxcuantias() {
        $this->db->select("MAX(HASTA) as maximo");
        $this->db->where('TIPO_ACUERDO', 'A');
        $dato = $this->db->get("CUANTIAS");
        //var_dump($this->db->last_query($dato));die;
        return $dato->result_array;
    }

    function admingarantia() {

        $garantia = $this->db->get('ADMINISTRACION_GARANTIA');
        return $garantia->result_array();
    }

    function eliminacioncuantia($id) {
        $this->db->where('COD_CUANTIA', $id);
        return $this->db->delete("CUANTIAS");
    }

    function ultimagarantia() {

        $this->db->order_by('FECHA_CREACION', 'desc');
        $garantia = $this->db->get('OBLIGACION_GARANTIA');
        return $garantia->result_array();
    }

    function consultaProyeccion($acuerdo) {
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $query = $this->db->get("PROYECCIONACUERDOPAGO");
        return $query->num_rows;
    }

    function consultarResoluciones($cargo, $regional) {
        $sesion = $this->session->userdata;
        $this->db->select("NRO_ACUERDOPAGO,ACUERDOPAGO.NITEMPRESA,NRO_RESOLUCION,NRO_LIQUIDACION,ACUERDOPAGO.COD_RESPUESTA,
        ACUERDOPAGO.COD_FISCALIZACION,ESTADOACUERDO,EMPRESA.NOMBRE_EMPRESA,ACUERDOPAGO.FECHA_CREACION,EMPRESA.COD_REGIONAL,
        ACUERDOPAGO.COD_FISCALIZACION,RG.NOMBRE_GESTION,ACUERDOPAGO.COD_CONCEPTO_COBRO,RESOLUCION.COD_RESOLUCION,
        RESOLUCION.DETALLE_COBRO_COACTIVO,ACUERDO_AJUSTADO,ACUERDOPAGO.USUARIO_ASIGNADO,USUARIOS.NOMBRES,USUARIOS.APELLIDOS");
        $this->db->join('RESPUESTAGESTION RG', 'RG.COD_RESPUESTA = ACUERDOPAGO.COD_RESPUESTA');
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = ACUERDOPAGO.NITEMPRESA");
        $this->db->join("LIQUIDACION", "ACUERDOPAGO.NRO_LIQUIDACION = LIQUIDACION.NUM_LIQUIDACION");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION = LIQUIDACION.COD_FISCALIZACION");
        $this->db->join('USUARIOS', "ACUERDOPAGO.USUARIO_ASIGNADO = USUARIOS.IDUSUARIO");
        $this->db->where('(COD_TIPOPROCESO = 2 OR COD_TIPOPROCESO = 18)');
        $this->db->where('ESTADOACUERDO', 1);
        $this->db->where('JURIDICO', 0);
        $this->db->where('EMPRESA.COD_REGIONAL', $regional);
        if ($cargo == 3 || $cargo == 9 || $cargo == 21 || $cargo == 1):
            $this->db->where('USUARIO_ASIGNADO IS NOT NULL');
        else :
            $this->db->where('USUARIO_ASIGNADO', ID_USER);
        endif;
        //$this->db->set('FECHA_ONBASE',"to_date('".$data['FECHA_ONBASE']."','dd/mm/yyyy')",false);
        //DECODE("ACUERDOPAGO"."USUARIO_ASIGNADO", '$Variable_Session_IDUsuario_Actual', 0, 1), "ACUERDOPAGO"."NRO_ACUERDOPAGO"
        $acuerdo = $this->db->get("ACUERDOPAGO");
        //echo $this->db->last_query();die;        
        return $acuerdo;
    }

    function consultarAcuerdos($nit) {
        $this->db->select("NRO_ACUERDOPAGO,NITEMPRESA,NRO_RESOLUCION,NRO_LIQUIDACION,COD_RESPUESTA,COD_FISCALIZACION,ESTADOACUERDO,
        EMPRESA.NOMBRE_EMPRESA,ACUERDOPAGO.FECHA_CREACION,ACUERDOPAGO.COD_FISCALIZACION");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = ACUERDOPAGO.NITEMPRESA");
        $this->db->where('ESTADOACUERDO', 1);
        $this->db->where('NITEMPRESA', $nit);
        $query = $this->db->get("ACUERDOPAGO");
        $array = $query->result();
        return $array[0];
    }

    function consultcamposgarantia($idgarantia) {

        $this->db->join('CAMPOSGARANTIA', "TIPOGARANTIA.COD_TIPO_GARANTIA = CAMPOSGARANTIA.COD_TIPO_GARANTIA");
        $this->db->where("TIPOGARANTIA.COD_TIPO_GARANTIA", $idgarantia);

        return $this->db->get('TIPOGARANTIA');
    }

    function consultatipoconcepto() {
        return $this->db->get('CONCEPTOSFISCALIZACION');
    }

    function porcentaje($porcentaje, $idusuario) {

        $this->db->set('PORCENTAJE', $porcentaje);
        $this->db->set('IDUSUARIO', $idusuario);
        $this->db->insert('PORCENTAJECUOTAFP');
    }

    function consultar_empresa($nit) {
        $this->db->select('NUM_LIQUIDACION, CODEMPRESA, UPPER(RAZON_SOCIAL) AS RAZON_SOCIAL,LIQUIDACION.COD_CONCEPTO');
        $this->db->join("LIQUIDACION", "LIQUIDACION.NITEMPRESA = EMPRESA.CODEMPRESA");
        $this->db->join("ACUERDOPAGO", "ACUERDOPAGO.NRO_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION");
        $this->db->like('LIQUIDACION.NUM_LIQUIDACION', $nit);
        $datos = $this->db->get('EMPRESA');
        //print_r($this->db->last_query($datos));die();
        return $datos->result_array();
    }

    function verificaGarantia($id_garantia) {
        $this->db->select('COD_GARANTIA_ACUERDO, COD_ACUERDO_PAGO,COD_CAMPO,COD_TIPO_GARANTIA');
        $this->db->where("LIQUIDACION", "LIQUIDACION.NITEMPRESA = EMPRESA.CODEMPRESA");
        $liquidacion = $this->db->get('GARANTIA_ACUERDO');
    }

    function consultadeuda($identificacion) {
        $this->db->select('TOTAL_LIQUIDADO,TOTAL_INTERESES,SALDO_DEUDA');
        $this->db->where('NUM_LIQUIDACION', "$identificacion");
        $liquidacion = $this->db->get('LIQUIDACION');
        //echo $this->db->last_query($liquidacion);
        return $liquidacion->result_array();
    }

    function totalliquidacion($liquidacion) {
        $this->db->where('NUM_LIQUIDACION', "$liquidacion");
        $liquidacion = $this->db->get('LIQUIDACION');
        //echo $this->db->last_query();die;
        return $liquidacion->result_array();
    }

    function documentoresolucion($nit, $resolucion, $archivo) {
        $data = array('DOCINIACUERDO' => $archivo);
        $where = array('NITEMPRESA' => $nit, 'NUMERO_RESOLUCION' => $resolucion);
        $this->db->where($where);
        $this->db->update('RESOLUCION', $data);
        //echo $this->db->last_query();die;        
        return true;
    }

    function excepcionesAcuerdo($fiscalizacion) {
        $this->db->where('COD_FISCALIZACION', $fiscalizacion);
        $this->db->select('COD_FISCALIZACION,MOTIVOEXCEPCION,ESTADO');
        $this->db->from('EXCEPCIONESACUERDO');
        //echo $this->db->last_query();die;        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $array = $query->result();
            return $array[0];
        }
    }

    function eliminacamposeditadosadmingarantia($id) {
        $this->db->where("COD_CAMPO", $id);
        $this->db->delete("CAMPOSGARANTIA");
        //echo $this->db->last_query();die; 
    }

    function insertAcuerdo($data) {
        $this->db->trans_start();
        $this->db->set('NITEMPRESA', $data['NITEMPRESA']);
        $this->db->set('NRO_RESOLUCION', $data['NRO_RESOLUCION']);
        $this->db->set('NRO_LIQUIDACION', $data['NRO_LIQUIDACION']);
        $this->db->set('COD_FISCALIZACION', $data['COD_FISCALIZACION']);
        $this->db->set('COD_RESPUESTA', $data['COD_RESPUESTA']);
        $this->db->set('USUARIO_GENERA', $data['USUARIO_GENERA']);
        $this->db->set('FECHA_CREACION', 'SYSDATE', FALSE);
        $this->db->set('COD_REGIONAL', $data['COD_REGIONAL']);
        $this->db->set('ESTADOACUERDO', $data['ESTADOACUERDO']);
        $this->db->set('COD_CONCEPTO_COBRO', $data['COD_CONCEPTO_COBRO']);
        $this->db->set('USUARIO_ASIGNADO', $data['USUARIO_ASIGNADO']);
        $this->db->set('JURIDICO', $data['JURIDICO']);
        $this->db->insert('ACUERDOPAGO');
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }
        return FALSE;
    }

    function insertargarantias($garantia, $tipodeudor) {

        $garantia1 = strtoupper($garantia);

        $datos = array("NOMBRE_TIPOGARANTIA" => $garantia1, "ESTADO" => 1);

        $this->db->insert("TIPOGARANTIA", $datos);
    }

    function garantiasCrear() {
        $this->db->select('COD_TIPO_GARANTIA,NOMBRE_TIPOGARANTIA,ESTADO,FECHA_CREACION,PRUEBA');
        $this->db->where('ESTADO !=', 2);
        $dato = $this->db->get("TIPOGARANTIA");
        return $dato;
    }

    function existegarantia($idgarantia, $campogarantia) {

        $where = array("COD_TIPO_GARANTIA" => $idgarantia, "NOMBRE_CAMPO" => $campogarantia);
        $this->db->where($where);
        $garantia = $this->db->get('CAMPOSGARANTIA');

        return $garantia->num_rows;
    }

    function insertacamposgarantias($idgarantia, $campogarantia) {
        $data = array("COD_TIPO_GARANTIA" => $idgarantia, "NOMBRE_CAMPO" => $campogarantia);
        $this->db->insert("CAMPOSGARANTIA", $data);
    }

    function guardacamposeditadosadmingarantia($id, $garantia, $campo) {
        $data = array("NOMBRE_CAMPO" => $campo);
        $this->db->where("COD_CAMPO", $id);
        $this->db->update("CAMPOSGARANTIA", $data);
    }

    function guardarcuantia($data) {
        $this->db->trans_start();
        $this->db->set('DESDE', $data['DESDE']);
        $this->db->set('HASTA', $data['HASTA']);
        $this->db->set('PORCENTAJE', $data['PORCENTAJE']);
        $this->db->set('PLAZOMESES', $data['PLAZOMESES']);
        $this->db->set('TIPO_ACUERDO', $data['TIPO_ACUERDO']);
        $this->db->insert('CUANTIAS');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
    }

    function guardagarantia($campos) {
        $this->db->trans_start();
        $this->db->set('VALOR_CAMPO', $campos['VALOR_CAMPO']);
        $this->db->set('VALOR_AVALUO', $campos['VALOR_AVALUO']);
        $this->db->set('VALOR_COMERCIAL', $campos['VALOR_COMERCIAL']);
        $this->db->set('COD_CAMPO', $campos['COD_CAMPO']);
        $this->db->set('COD_TIPO_GARANTIA', $campos['COD_TIPO_GARANTIA']);
        $this->db->set('CODEMPRESA', $campos['CODEMPRESA']);
        $this->db->set('COD_ACUERDO_PAGO', $campos['COD_ACUERDO_PAGO']);
        $this->db->set('NUM_LIQUIDACION', $campos['NUM_LIQUIDACION']);
        $this->db->set('COD_USUARIO', $campos['COD_USUARIO']);
        $this->db->set('ESTADO', $campos['ESTADO']);
        $this->db->insert('GARANTIA_ACUERDO');
        //print_r($this->db->last_query());die();
        if ($this->db->affected_rows() == '1') {
            $this->db->trans_complete();
            return TRUE;
        }

        return FALSE;
        //$this->db->insert_batch("GARANTIA_ACUERDO", $campos);
    }

    function insertacuerdopagocuotas($acuerdo, $cuota, $fecha, $saldocapital, $intCorriente, $intCorrienteCuota, $interespormora, $valorcuota, $aportecapital, $saldofinal, $estado, $cartaauto) {
        $this->db->set('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->set('PROYACUPAG_NUMCUOTA', $cuota);
        $this->db->set('PROYACUPAG_FECHALIMPAGO', "TO_DATE('" . $fecha . "','DD/MM/RR')", false);
        $this->db->set('PROYACUPAG_SALDOCAPITAL', number_format($aportecapital, 0, ",", ""));
        //$interespormora = trim($interespormora);        
        //if($interespormora != ''):               
        $this->db->set('PROYACUPAG_VALORINTERESESMORA', number_format($interespormora, 0, ",", ""));
        $this->db->set('PROYACUPAG_INTACUERDO', number_format($interespormora, 0, ",", ""));
        $this->db->set('PROYACUPAG_SALDO_INTACUERDO', number_format($interespormora, 0, ",", ""));
        //endif;
        $this->db->set('PROYACUPAG_ESTADO', $estado);
        $this->db->set('PROACUPAG_CARTAAUT', $cartaauto);
        $this->db->set('PROYACUPAG_CAPITALDEBE', number_format($saldocapital, 0, ",", ""));

        $this->db->set('PROYACUPAG_CAPITAL_CUOTA', number_format($aportecapital, 0, ",", ""));
        $this->db->set('PROYACUPAG_SALDO_CUOTA', number_format($valorcuota, 0, ",", ""));

        $this->db->set('PROYACUPAG_VALORCUOTA', number_format($valorcuota, 0, ",", ""));

        $this->db->set('PROYACUPAG_INTCORRIENTE', number_format($intCorriente, 0, ",", ""));
        $this->db->set('PROYACUPAG_SALDO_INTCORRIENTE', number_format($intCorrienteCuota, 0, ",", ""));

        $this->db->set('PROYACUPAG_INTCORRIENTE_CUOTA', number_format($intCorrienteCuota, 0, ",", ""));

        $this->db->insert('PROYECCIONACUERDOPAGO');
        //      echo $this->db->last_query();
        //      $this->db->trans_complete();
    }

    function salariominimo() {
        $this->db->select("SALARIO_VIGENTE");
        $this->db->order_by("ID_SALARIO", "desc");
        return $this->db->get("SALARIO");
    }

    function tasaEfectiva($fechaHoy) {
        $this->db->where("to_date('" . $fechaHoy . "','dd/mm/yyyy') BETWEEN VIGENCIA_DESDE AND VIGENCIA_HASTA", NULL, FALSE);
        $this->db->where('ID_TIPO_TASA', 1);
        $cuota = $this->db->get('TASA_SUPERINTENDENCIA');
        //echo $this->db->last_query($cuota);die;
        return $cuota->result_array();
    }

    function totalgarantias($acuerdo) {

        $this->db->where('COD_ACUERDO_PAGO', $acuerdo);
        $this->db->where('ESTADO !=', 2);
        $garantia = $this->db->get('GARANTIA_ACUERDO');
        //echo $this->db->last_query();die;
        return $garantia->result_array();
    }

    function guardaexcepcion($fiscalizacion, $observacion, $acuerdo) {
        $fecha = date('d/m/Y');
        $this->db->set('NRO_ACUERDOPAGO', $acuerdo);
        $this->db->set('COD_FISCALIZACION', $fiscalizacion);
        $this->db->set('MOTIVOEXCEPCION', $observacion);
        $this->db->set('FECHA_CREACION', "to_date('" . $fecha . "','dd/mm/yyyy')", false);
        $this->db->set('ESTADO', 2);
        $this->db->insert('EXCEPCIONESACUERDO');
        if ($this->db->affected_rows() == '1') {
            return TRUE;
        }
        return FALSE;
    }

    function consultaexcepcion($acuerdo) {

        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $datos = $this->db->get('EXCEPCIONESACUERDO');
        return $datos->result_array();
    }

    function consultaexcepcion2($acuerdo) {

        $this->db->select("COUNT(*) AS CONTADOR");
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $datos = $this->db->get('EXCEPCIONESACUERDO');
        return $datos->result_array();
    }

    function consultaexcepcion3($acuerdo) {

        $this->db->order_by('COD_EXCEPCIONACUERDO', 'desc');
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $datos = $this->db->get('EXCEPCIONESACUERDO');
        return $datos->result_array();
    }

    function ultimarespuesta($acuerdo) {

        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $datos = $this->db->get('EXCEPCIONESACUERDO');
        return $datos->result_array();
    }

    function uvt() {
        $this->db->select('ANNO,SALARIO_MINIMO,VALOR_UVT,CODESTADO');
        $this->db->where('CODESTADO', 1);
        $datos = $this->db->get('HISTORICOSALARIOMINIMO_UVT');
        return $datos->result_array();
    }

    function usuarios($idusuario) {
        $this->db->select("USUARIOS.IDUSUARIO as IDUSUARIO, APELLIDOS, NOMBRES,GRUPOS.IDGRUPO,USUARIOS.COD_REGIONAL,IDCARGO");
        $this->db->join("USUARIOS_GRUPOS", "USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO");
        $this->db->join("GRUPOS", "USUARIOS_GRUPOS.IDGRUPO=GRUPOS.IDGRUPO");
        $this->db->where("USUARIOS.IDUSUARIO", $idusuario);
        $dato = $this->db->get("USUARIOS");
        //var_dump($this->db->last_query($dato));die;
        return $dato->result_array;
    }

    function consulta_acuerdo($acuerdo) {
        $this->db->select('NITEMPRESA,NRO_RESOLUCION,USUARIO_ASIGNADO,USUARIO_GENERA,VALOR_CAPITAL_FECHA,VALO_RINTERESES_CAPITAL');
        $this->db->where('NRO_ACUERDOPAGO', $acuerdo);
        $dato = $this->db->get("ACUERDOPAGO");
        //var_dump($this->db->last_query($dato));die;
        return $dato->result_array;
    }

    function eliminar_garantias($post) {

        foreach ($post['garantias'] as $garantia):
            $this->db->set('ESTADO', 2);
            $this->db->where('COD_TIPO_GARANTIA', $garantia);
            $this->db->update('TIPOGARANTIA');
          
        endforeach;
        return TRUE;
    }

}
