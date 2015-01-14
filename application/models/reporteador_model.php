<?php

class Reporteador_model extends CI_Model {

    var $cod_municipio;
    var $array_num;

    function __construct() {
        parent::__construct();
    }

    function empresa() {
        $this->db->select("CODEMPRESA,NOMBRE_EMPRESA");
        $this->db->order_by('NOMBRE_EMPRESA', "asc");
        $dato = $this->db->get("EMPRESA");
        return $datos = $dato->result_array;
    }

    function cuentas_contables() {
        $this->db->select("COD_CUENTA,NOMBRE_CUENTA");
//        $this->db->where("LENGTH(COD_CUENTA)",9,false);
        $this->db->order_by('COD_CUENTA', "asc");
        $dato = $this->db->get("CUENTAS_CONTABLES");
        return $datos = $dato->result_array;
    }

    function guardar_concepto_contable() {
        $post = $this->input->post();

        $this->db->set("COD_CONCEPTO", '');
        $this->db->where("COD_CONCEPTO", $post['sub_concepto']);
        $this->db->update("CUENTAS_CONTABLES");


        $this->db->where("COD_CUENTA", $post['cuenta']);
        $this->db->set("CORRIENTE", $post['valor_estado']);
        $this->db->set("COD_CONCEPTO", $post['sub_concepto']);
        $this->db->update("CUENTAS_CONTABLES");
    }

    function info_subconcepto($post = null) {
        $this->db->select("COD_CUENTA,NOMBRE_CUENTA,CORRIENTE,CONCEPTORECAUDO.NOMBRE_CONCEPTO");
        $this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=CUENTAS_CONTABLES.COD_CONCEPTO", 'left');
        $this->db->order_by('COD_CUENTA', "asc");
        if ($post != null) {
            $this->db->where("COD_CONCEPTO", $post['sub_concepto']);
            $this->db->where("COD_CUENTA", $post['cuenta']);
        }
        $dato = $this->db->get("CUENTAS_CONTABLES");
        $datos = $dato->result_array;
        $cantidad = count($datos);
        $tabla = "";
        if ($cantidad != 0) {
            if ($post == null) {
                $tabla .= '<table id="tablas"  border="0" style="margin: 0 auto;">';
                $stylo = "";
            } else {
                $tabla .= "<center>Para este concepto se encuentra registrada las siguiente cuenta</center>";
                $tabla = '<table  width="600px" border="0" style="margin: 0 auto;border:1px solid #000">';
                $stylo = "style='background-color: #fc7323;color: #FFF'";
            }
            $tabla .= "<thead " . $stylo . "><th>Cod. Cuenta</th><th>Nombre Cuenta</th><th >Sub Concepto</th><th >Estado</th></thead>";
            foreach ($datos as $value) {
                if ($value['CORRIENTE'] == '0') {
                    $corr = 'CORRIENTE';
                } else {
                    $corr = 'NO CORRIENTE';
                }
                $tabla.="<tr>"
                        . "<td align='center'>" . $value['COD_CUENTA'] . "</td>"
                        . "<td>" . $value['NOMBRE_CUENTA'] . "</td>"
                        . "<td>" . $value['NOMBRE_CONCEPTO'] . "</td>"
                        . "<td>" . $corr . "</td>"
                        . "</tr>";
            }
            $tabla.="</table>";
            if ($post != null)
                echo $tabla.="<input type='hidden' id='hay' name='hay' value='1'>";
            else
                $tabla.="<input type='hidden' id='hay' name='hay' value='1'>";
        } else {
            if ($post != null)
                echo $tabla = "<input type='hidden' id='hay' name='hay' value='0'>";
            else
                $tabla = "<input type='hidden' id='hay' name='hay' value='0'>";
        }
        return $tabla;
    }

    function concepto($dato) {
        $this->db->select("COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO");
        if (!empty($dato))
            $this->db->where("COD_CPTO_FISCALIZACION", $dato);
        $this->db->order_by('NOMBRE_CONCEPTO', "asc");
        $dato = $this->db->get("CONCEPTOSFISCALIZACION");
        return $datos = $dato->result_array;
    }

    function traer_subconcepto($post) {
        $this->db->select("COD_CONCEPTO_RECAUDO,NOMBRE_CONCEPTO");
        $this->db->where("CODTIPOCONCEPTO", $post['concepto']);
        $dato = $this->db->get("CONCEPTORECAUDO");
        $datos = $dato->result_array;
        $info_general.="<option value='' ></option>";
        foreach ($datos as $info) {
            $info_general.="<option value='" . $info['COD_CONCEPTO_RECAUDO'] . "' >" . $info['NOMBRE_CONCEPTO'] . "</option>";
        }
        return $info_general;
    }

    function concepto_general() {
        $this->db->select("COD_TIPOCONCEPTO,NOMBRE_TIPO");
        $this->db->order_by('NOMBRE_TIPO', "asc");
        $dato = $this->db->get("TIPOCONCEPTO");
        return $datos = $dato->result_array;
    }

    function tipocartera() {
        $this->db->select("COD_TIPOCARTERA AS COD_CPTO_FISCALIZACION,NOMBRE_CARTERA AS NOMBRE_CONCEPTO");
        $this->db->where("COD_TIPOCARTERA  not in ('2','9','12','13') and 1=", 1, false);
        $this->db->where("COD_TIPOCARTERA <", '14', false);
        $this->db->order_by('NOMBRE_CARTERA', "asc");
        $dato = $this->db->get("TIPOCARTERA");
        return $datos = $dato->result_array;
    }
	
	    function tipocartera2() {
        $this->db->select("COD_TIPOCARTERA AS COD_CPTO_FISCALIZACION,NOMBRE_CARTERA AS NOMBRE_CONCEPTO");
        $this->db->where("COD_TIPOCARTERA  not in ('12','13') and 1=", 1, false);
        $this->db->where("COD_TIPOCARTERA <", '14', false);
        $this->db->order_by('NOMBRE_CARTERA', "asc");
        $dato = $this->db->get("TIPOCARTERA");
        return $datos = $dato->result_array;
    }
	
	    function estadoempleado() {
        $this->db->select("COD_ESTADO_E,NOMBRE_ESTADO_E");
        $dato = $this->db->get("CNM_ESTADO_EMP");
        return $datos = $dato->result_array;
    }

    function TIPOCONCEPTO() {
        $this->db->select("NOMBRE_TIPO,COD_TIPOCONCEPTO");
        $this->db->order_by('NOMBRE_TIPO', "asc");
        $dato = $this->db->get("TIPOCONCEPTO");
        return $datos = $dato->result_array;
    }

    function TIPOSUBCONCEPTO() {
        $this->db->select("COD_CONCEPTO_RECAUDO,NOMBRE_CONCEPTO");
        $this->db->order_by('NOMBRE_CONCEPTO', "asc");
        $dato = $this->db->get("CONCEPTORECAUDO");
        return $datos = $dato->result_array;
    }

    function tipodevolucion() {
        $this->db->select("COD_CONCEPTO AS COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO");
        $this->db->order_by('NOMBRE_CONCEPTO', "asc");
        $dato = $this->db->get("CONCEPTOSDEVOLUCION");
        return $datos = $dato->result_array;
    }

    function regional() {
        $this->db->select("COD_REGIONAL,NOMBRE_REGIONAL");
        $this->db->order_by('NOMBRE_REGIONAL', "asc");
        $dato = $this->db->get("REGIONAL");
        return $datos = $dato->result_array;
    }

    function datos_resolucion() {
        $this->db->select("NOMBRE_CONCEPTO,"
                . "RESOLUCION.NUMERO_RESOLUCION,RESOLUCION.FECHA_CREACION AS FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,"
                . "RESOLUCION.VALOR_TOTAL AS VALOR_RESOLUCION,LIQUIDACION.TOTAL_INTERESES,LIQUIDACION.SALDO_DEUDA,"
                . "");
    }

    function datos_empresa() {
        $this->db->select("EMPRESA.CODEMPRESA,"
                . "EMPRESA.NOMBRE_EMPRESA");
    }

    //empresa sin representante
    function datos_empresa2() {
        $this->db->select("EMPRESA.CODEMPRESA,"
                . "EMPRESA.NOMBRE_EMPRESA");
    }

    function datos_regional() {
        $this->db->select("REGIONAL.NOMBRE_REGIONAL");
    }

    function datos_gestion() {
        $this->db->select("TIPOGESTION.TIPOGESTION AS TIPO_GESTION");
    }

    function datos_usuarios() {
        $this->db->select("concat(USUARIOS.APELLIDOS,concat(' ',USUARIOS.NOMBRES)) as RESPONSABLE", false);
    }

    function datos_liquidacion() {
        $this->db->select("LIQUIDACION.NUM_LIQUIDACION,LIQUIDACION.FECHA_LIQUIDACION,LIQUIDACION.FECHA_INICIO || ' A ' || LIQUIDACION.FECHA_FIN AS VIGENCIA,ROUND(LIQUIDACION.TOTAL_CAPITAL,0) AS  TOTAL_CAPITAL,LIQUIDACION.TOTAL_INTERESES,ROUND(LIQUIDACION.TOTAL_LIQUIDADO,0) AS TOTAL_LIQUIDADO,"
                . "LIQUIDACION.SALDO_DEUDA,LIQUIDACION.FECHA_VENCIMIENTO", FALSE);
    }

    function datos_liquidacion2() {
        $this->db->select("LIQUIDACION.NUM_LIQUIDACION,LIQUIDACION.FECHA_INICIO,LIQUIDACION.FECHA_FIN", FALSE);
    }

    function datos_tipo_proceso() {
        $this->db->select("NOMBREPROCESO");
    }

    function razonsocial($term) {
        if (!empty($term)) :
            $this->db->select('CODEMPRESA, UPPER(RAZON_SOCIAL) AS RAZON_SOCIAL');
            $this->db->where('ROWNUM<=10', '', false);
            $this->db->like('CODEMPRESA', $term);
            $this->db->or_like('RAZON_SOCIAL', $term);
            $datos = $this->db->get('EMPRESA'); //echo $this->db->last_query();
            return $datos->result_array();
        endif;
        return NULL;
    }

    function autocomplete_fiscalizador($term) {
        if (!empty($term)) :
            $this->db->select('USUARIOS.IDUSUARIO,UPPER(USUARIOS.NOMBRES) NOMBRES,UPPER(USUARIOS.APELLIDOS) APELLIDOS');
            $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=ASIGNACIONFISCALIZACION.ASIGNADO_A');
            $this->db->where('ROWNUM<=10', '', false);
            $this->db->where("(UPPER(USUARIOS.NOMBRES) LIKE UPPER('%" . $term . "%') "
                    . "OR UPPER (USUARIOS.APELLIDOS) LIKE UPPER('%" . $term . "%') "
                    . "OR UPPER (USUARIOS.IDUSUARIO) LIKE UPPER('%" . $term . "%'))   ", '', false);
//            $this->db->like('UPPER(USUARIOS.NOMBRES)', $term, FALSE);
//            $this->db->or_like('UPPER (USUARIOS.IDUSUARIO)', $term, FALSE);
            $this->db->group_by('USUARIOS.IDUSUARIO,USUARIOS.NOMBRES,USUARIOS.APELLIDOS');
            $datos = $this->db->get('ASIGNACIONFISCALIZACION'); //echo $this->db->last_query();
            return $datos->result_array();
        endif;
        return NULL;
    }

    function autocomplete_ciiu($term) {
        if (!empty($term)) :
            $this->db->select('CLASE,DESCRIPCION');
            $this->db->where('ROWNUM<=10', '', false);
            $this->db->where("(UPPER(CIIU.CLASE) LIKE UPPER('%" . $term . "%') "
                    . "OR UPPER (CIIU.DESCRIPCION) LIKE UPPER('%" . $term . "%'))   ", '', false);
//            $this->db->like('UPPER(USUARIOS.NOMBRES)', $term, FALSE);
//            $this->db->or_like('UPPER (USUARIOS.IDUSUARIO)', $term, FALSE);
            $this->db->group_by('CLASE,DESCRIPCION');
            $datos = $this->db->get('CIIU'); //echo $this->db->last_query();
            return $datos->result_array();
        endif;
        return NULL;
    }

    function ciiu() {

        $this->db->select('CLASE,DESCRIPCION');
        $this->db->where('CLASE is not null');
        $datos = $this->db->get('CIIU'); //echo $this->db->last_query();
        return $datos->result_array();
    }

    function autocomplete_municipio($term) {
        if (!empty($term)) :
            $this->db->select("MUNICIPIO.CODMUNICIPIO || ' - ' || DEPARTAMENTO.COD_DEPARTAMENTO CODMUNICIPIO , NOMBREMUNICIPIO || ' ' || DEPARTAMENTO.NOM_DEPARTAMENTO NOMBREMUNICIPIO,DEPARTAMENTO.COD_DEPARTAMENTO");
            $this->db->join('DEPARTAMENTO', 'MUNICIPIO.COD_DEPARTAMENTO=DEPARTAMENTO.COD_DEPARTAMENTO');
            $this->db->where('ROWNUM<=10', '', false);
            $this->db->where("(UPPER(NOMBREMUNICIPIO) LIKE UPPER('%" . $term . "%') "
                    . "OR UPPER (NOMBREMUNICIPIO) LIKE UPPER('%" . $term . "%'))   ", '', false);
//            $this->db->like('UPPER(USUARIOS.NOMBRES)', $term, FALSE);
//            $this->db->or_like('UPPER (USUARIOS.IDUSUARIO)', $term, FALSE);
            $datos = $this->db->get('MUNICIPIO'); //echo $this->db->last_query();
            return $datos->result_array();
        endif;
        return NULL;
    }

    function autocomplete_abogado($term) {
        if (!empty($term)) :
            $this->db->select('USUARIOS.IDUSUARIO,UPPER(USUARIOS.NOMBRES) NOMBRES,UPPER(USUARIOS.APELLIDOS) APELLIDOS');
//            $this->db->like('UPPER(USUARIOS.NOMBRES)', 'UPPER(',$term.')', FALSE);
            $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=USUARIOS_GRUPOS.IDUSUARIO');
            $this->db->where('ROWNUM<=10', '', false);
//            $this->db->or_like('UPPER (USUARIOS.IDUSUARIO)', 'UPPER(',$term.')', FALSE);
            $this->db->where("(UPPER(USUARIOS.NOMBRES) LIKE UPPER('%" . $term . "%') OR UPPER (USUARIOS.APELLIDOS) LIKE UPPER('%" . $term . "%') OR UPPER (USUARIOS.IDUSUARIO) LIKE UPPER('%" . $term . "%'))", '', false);
            $this->db->group_by('USUARIOS.IDUSUARIO,USUARIOS.NOMBRES,USUARIOS.APELLIDOS');
            $a = array(44, 43);
            $this->db->where_in('IDGRUPO');
            $datos = $this->db->get('USUARIOS_GRUPOS'); //echo $this->db->last_query();
            return $datos->result_array();
        endif;
        return NULL;
    }

    function datos_cod_fiscalizacion() {
        $this->db->select("CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO");
    }

    function datos_acuerdo_pago() {
//        $this->db->select("ACUERDOPAGO.NRO_ACUERDOPAGO,ACUERDOPAGO.NRO_RESOLUCION,ACUERDOPAGO.VALOR_CAPITAL_FECHA AS VALOR_CAPITAL_INICIA,"
//                . "ACUERDOPAGO.VALO_RINTERESES_CAPITAL AS VALOR_INTERES_INICIA, ACUERDOPAGO.FECHA_EJECUTORIA,"
//                . "ACUERDOPAGO.VALOR_CUOTA_INICIAL,ACUERDOPAGO.VALOR_TOTAL_FINANCIADO,"
//                . "ACUERDOPAGO.TASA_INTERES,ACUERDOPAGO.FECHA_CUOTA_INICIAL,ACUERDOPAGO.NUMERO_CUOTAS,ACUERDOPAGO.VALOR_CUOTA");
        $this->db->select("ACUERDOPAGO.NRO_ACUERDOPAGO,ACUERDOPAGO.VALOR_CAPITAL_FECHA AS VALOR_CAPITAL_INICIA,"
                . "ACUERDOPAGO.VALO_RINTERESES_CAPITAL AS VALOR_INTERES_INICIA,"
                . "ACUERDOPAGO.VALOR_TOTAL_FINANCIADO,"
                . "");
    }

    function datos_pago_recibidos() {
        $this->db->select("PAGOSRECIBIDOS.PROCEDENCIA,PAGOSRECIBIDOS.COD_PROCEDENCIA,PAGOSRECIBIDOS.VALOR_PAGADO,"
                . "PAGOSRECIBIDOS.PERIODO_PAGADO,PAGOSRECIBIDOS.FECHA_PAGO,PAGOSRECIBIDOS.APLICADO");
    }

    function datos_tipoDocumento() {
        $this->db->select('TIPODOCUMENTO.NOMBRETIPODOC');
    }

    function datos_fiscalizacion() {
        $this->db->select("FISCALIZACION.PERIODO_INICIAL,FISCALIZACION.PERIODO_FINAL,FISCALIZACION.COD_FISCALIZACION");
    }

    function datos_multas() {
        $this->db->select(""
//                . "MULTASMINISTERIO.NRO_RADICADO,"
//                . "MULTASMINISTERIO.NRO_RESOLUCION,"
//                . "MULTASMINISTERIO.VALOR,"
                . "MULTASMINISTERIO.FECHA_CREACION,"
//                . "MULTASMINISTERIO.PERIODO_INICIAL,MULTASMINISTERIO.PERIODO_FINAL"
                . "");
    }

    function INTERES_MULTAMIN_ENC() {
        $this->db->select(""
                . "INTERES_MULTAMIN_ENC.TOTAL_CAPITAL,"
//                . "INTERES_MULTAMIN_ENC.TOTAL_INTERESES,"
                . "INTERES_MULTAMIN_ENC.VALOR_TOTAL,"
//                . "INTERES_MULTAMIN_ENC.TOTAL_DIAS_MORA,"
                . "");
    }

    function datos_municipio() {
        $this->db->select("MUNICIPIO.NOMBREMUNICIPIO");
    }

    function PROYECCIONACUERDOPAGO() {
        $this->db->select("PROYECCIONACUERDOPAGO.PROYACUPAG_VALORCUOTA,PROYECCIONACUERDOPAGO.PROYACUPAG_FECHALIMPAGO,
PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL,PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOFINAL,
PROYECCIONACUERDOPAGO.PROYACUPAG_VALORINTERESESMORA");
    }

    function resolucion_general() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("RESOLUCION.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if ($post["abogado_resol"] != "") {
            $abogado = explode(' - ', $post["abogado_resol"]);
            $this->db->where("(RESOLUCION.ABOGADO LIKE UPPER('%" . $abogado[0] . "%') OR USUARIOS.NOMBRES LIKE upper('%" . $abogado[0] . "%'))", '', FALSE);
        }
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=RESOLUCION.COD_CPTO_FISCALIZACION");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=RESOLUCION.ABOGADO");
        $this->db->join("TIPOGESTION", "TIPOGESTION.COD_GESTION=RESOLUCION.COD_ESTADO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=RESOLUCION.COD_REGIONAL");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=RESOLUCION.NITEMPRESA");
        $this->db->join("LIQUIDACION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION AND SALDO_DEUDA <> 0");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->where('RESOLUCION.COD_ESTADO NOT IN (419,80)');
        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("RESOLUCION");
        return $datos = $dato->result_array;
    }

    function reporte_resolucion() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("RESOLUCION.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if ($post["abogado_resol"] != "") {
            $abogado = explode(' - ', $post["abogado_resol"]);
            $this->db->where("(RESOLUCION.ABOGADO LIKE UPPER('%" . $abogado[0] . "%') OR USUARIOS.NOMBRES LIKE upper('%" . $abogado[0] . "%'))", '', FALSE);
        }
        $array_select = array('0', 'COD. REGIONAL', 'REGIONAL', 'NIT', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'INSTANCIA', 'CIUDAD', 'NÃšMERO RESOLUCIÃ“N',
            'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'VALOR CAPITAL INICIAL', 'VALOR INTERESES INICIAL', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES',
            'SALDO PENDIENTE TOTAL', 'PAGO/ABONO', 'RESPONSABLE');

        $this->db->select(" REGIONAL.COD_REGIONAL,
REGIONAL.NOMBRE_REGIONAL as REGIONAL, 
EMPRESA.CODEMPRESA AS NIT, EMPRESA.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
NOMBRE_CONCEPTO AS CONCEPTO, '' AS INSTANCIA,
MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD , 
RESOLUCION.NUMERO_RESOLUCION, 
RESOLUCION.FECHA_CREACION AS FECHA_RESOLUCION, 
LIQUIDACION.FECHA_EJECUTORIA, RESOLUCION.VALOR_TOTAL AS VALOR_CAPITAL_INICIA, 
LIQUIDACION.TOTAL_INTERESES AS VALOR_INTERESES_INICIAL, 
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
LIQUIDACION.SALDO_DEUDA AS SALDO_PENDIENTE_TOTAL, ABONOS AS PAGO_ABONO,
concat(USUARIOS.APELLIDOS, concat(' ', USUARIOS.NOMBRES)) as RESPONSABLE", FALSE);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=RESOLUCION.COD_CPTO_FISCALIZACION");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=RESOLUCION.ABOGADO");
//        $this->db->join("TIPOGESTION", "TIPOGESTION.COD_GESTION=RESOLUCION.COD_ESTADO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=RESOLUCION.COD_REGIONAL");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=RESOLUCION.NITEMPRESA");
        $this->db->join("LIQUIDACION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION AND SALDO_DEUDA <> 0");
        $this->db->join("(
 SELECT C.NRO_REFERENCIA, SUM(C.VALOR_PAGADO) AS ABONOS
 FROM PAGOSRECIBIDOS C
 GROUP BY C.NRO_REFERENCIA
) B", "LIQUIDACION.NUM_LIQUIDACION=B.NRO_REFERENCIA", 'left', false);
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->where('RESOLUCION.COD_ESTADO NOT IN (419,80)');
        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("RESOLUCION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function proceso_sancionatorio() {
        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("RESOLUCION.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }
        $array_select = array('0', 'ABOGADO', 'REGIONAL', 'PARTE DEL PROCESO', 'INICIO DEL PROCESO', 'NIT', 'RAZÃ“N SOCIAL', 'VALOR DEL PROCESO', 'NUMERO DEL ESTADO CUENTA');

        $this->db->select("(USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS) as ABOGADO,REGIONAL.NOMBRE_REGIONAL AS REGIONAL,'' AS PARTE_DEL_PROCESO,
RESOLUCION.FECHA_CREACION AS INICIO_DEL_PROCESO, EMPRESA.CODEMPRESA AS NIT,EMPRESA.NOMBRE_EMPRESA AS RAZON_SOCIAL, LIQUIDACION.SALDO_DEUDA,
LIQUIDACION.NUM_LIQUIDACION AS NUMERO_DEL_ESTADO_CUENTA ", false);
        $this->db->join("LIQUIDACION", "LIQUIDACION.NUM_LIQUIDACION=RESOLUCION.NUM_LIQUIDACION");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=ASIGNACIONFISCALIZACION.ASIGNADO_A");
        $this->db->where("RESOLUCION.DECISION_RESO_CONTRATO", '2');
        $dato = $this->db->get("RESOLUCION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function insertar_certificados($COD) {
        $this->db->set("COD_VERIFICACION", $COD);
        $this->db->insert("CERTIFICACIONES");
        $this->db->select("MAX(COD_CERTIFICADO) COD_VERIFICACION");
        $dato = $this->db->get("CERTIFICACIONES");
        $datos = $dato->result_array[0]['COD_VERIFICACION'];
        return $datos;
    }

    function pago_recibidos2() {
        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'NUMERO RESOLUCIÃ“N',
            'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'VALOR INICIAL', 'TRANSACCIÃ“N', 'MEDIO ORIGEN',
            'PERÃ�ODO PAGADO', 'VALOR', 'COD OPERADOR', 'FECHA PAGO', 'FECHA APLICADO', 'ESTADO');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL ,EMPRESA.CODEMPRESA AS NIT,Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA as RAZON_SOCIAL,
            CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO,RESOLUCION.NUMERO_RESOLUCION,LIQUIDACION.FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,
            LIQUIDACION.TOTAL_LIQUIDADO VALOR_INICIAL,PAGOSRECIBIDOS.NUM_DOCUMENTO AS TRANSACCION,PAGOSRECIBIDOS.PROCEDENCIA AS MEDIO_ORIGEN,
            PAGOSRECIBIDOS.PERIODO_PAGADO,PAGOSRECIBIDOS.VALOR_PAGADO AS VALOR,"
                //ASOBANCARIA_DET.COD_ASOBANCARIA AS COD_OPERADOR,
                . "PAGOSRECIBIDOS.FECHA_PAGO,PAGOSRECIBIDOS.FECHA_APLICACION AS FECHA_APLICADO,
            DECODE(PAGOSRECIBIDOS.APLICADO, 1, 'CONCILIADO', 'NO CONCILIADO') AS ESTADO
            ", FALSE);
        $this->db->join("PAGOSRECIBIDOS", "LIQUIDACION.NUM_LIQUIDACION=PAGOSRECIBIDOS.NRO_REFERENCIA", 'left');
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
//        $this->db->join("ASOBANCARIA_DET", "ASOBANCARIA_DET.COD_DETALLE=PAGOSRECIBIDOS.COD_PROCEDENCIA", 'left');
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION", 'left');
//        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
//        $this->db->where("LIQUIDACION.FECHA_RESOLUCION IS NOT NULL");
        $this->db->order_by("PAGOSRECIBIDOS.FECHA_PAGO", 'desc');
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function liquidacion_general() {

        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
//        if (!isset($post['check_fecha'])) {
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }

        $array_select = array('0', 'NIT', 'RAZÃ“N SOCIAL', 'REGIONAL', 'FECHA INICIAL', 'FECHA FINAL', 'VALOR CONTRATO',
            'VALOR A PAGAR FIC', 'INTERESES', 'ORIGEN');

        $this->db->select("EMPRESA.CODEMPRESA as NIT, EMPRESA.NOMBRE_EMPRESA AS RAZON_SOCIAL,REGIONAL.NOMBRE_REGIONAL AS REGIONAL,
                    LF.PERI_INICIAL AS PERIODO_INICIAL, LF.PERI_FINAL AS PERIODO_FINAL,
                    (SELECT VLR_CONTRATO_TODOCOSTO 
FROM LIQ_FIC_PRESUNTIVA
WHERE CODLIQUIDACIONFIC=LIQUIDACION.NUM_LIQUIDACION AND RowNum=1 
)  VALOR_CONTRATO, LF.VALOR_TOTAL_FIC AS VALOR_A_PAGAR_FIC, LF.INTERESES_FIC AS INTERESES,
                    TIPOPROCESO.TIPO_PROCESO AS ORIGEN");
        $this->db->join('LIQUIDACION_FIC LF', 'LF.CODLIQUIDACIONFIC=LIQUIDACION.NUM_LIQUIDACION');
//        $this->db->join('LIQ_FIC_NORMATIVA LFN', 'LFN.CODLIQUIDACIONFIC=LIQUIDACION.NUM_LIQUIDACION');
//        $this->db->join('LIQ_FIC_PRESUNTIVA LFP', 'LFP.CODLIQUIDACIONFIC=LIQUIDACION.NUM_LIQUIDACION');
//        $this->db->select("USUARIOS.APELLIDOS || ' '|| USUARIOS.NOMBRES as RESPONSABLE", false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("TIPOPROCESO", "LIQUIDACION.COD_TIPOPROCESO=TIPOPROCESO.COD_TIPO_PROCESO ", "LEFT");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join("RESOLUCION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION", 'LEFT');
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.COD_CONCEPTO", "2");
//        $this->db->where("LIQUIDACION.EN_FIRME", "S");
//        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function reporte_presunta_real() {

        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }
        $array_select = array('0', 'COD. REGIONAL', 'REGIONAL', 'NIT', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'INSTANCIA', 'NUM. RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA',
            'VALOR RESOLUCIÃ“N', 'SALDO RESOLUCIÃ“N', 'PAGO/ABONO', 'VALOR INICIAL LIQUIDACIÃ“N', 'ESTADO LIQUIDACIÃ“N', 'PERIODO', 'VIGENCIA', 'FECHA CORTE',
            'FECHA PAGO', 'RESPONSABLE');

        $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL AS REGIONAL, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO, "
                . "MUNICIPIO.NOMBREMUNICIPIO , "
                . "(SELECT COD_TIPO_INSTANCIA FROM INSTANCIAS_PROCESOS WHERE COD_TIPO_PROCESO=COD_TIPO_PROCESO AND ROWNUM=1 and ESTADO='A')  AS INSTANCIA,RESOLUCION.NUMERO_RESOLUCION AS NUM_RESOLUCION, RESOLUCION.FECHA_CREACION AS FECHA_RESOLUCION, LIQUIDACION.FECHA_EJECUTORIA, 
            RESOLUCION.VALOR_TOTAL AS VALOR_RESOLUCION, LIQUIDACION.SALDO_DEUDA AS SALDO_RESOLUCION, ABONOS AS  PAGO_ABONOS, 
            LIQUIDACION.TOTAL_LIQUIDADO AS VALOR_INICIAL_LIQUIDACION, TIPOPROCESO.TIPO_PROCESO AS ESTADO_LIQUIDACION, LIQUIDACION.FECHA_INICIO || ' A ' || LIQUIDACION.FECHA_FIN AS VIGENCIA,
            LIQUIDACION.FECHA_VENCIMIENTO AS FECHA_DE_CORTE,FECHA_PAGO, USUARIOS.APELLIDOS || ' '|| USUARIOS.NOMBRES as RESPONSABLE ", false);
        $this->db->join("(
 SELECT C.NRO_REFERENCIA, MAX(C.FECHA_PAGO) AS FECHA_PAGO
 FROM PAGOSRECIBIDOS C
 GROUP BY C.NRO_REFERENCIA
) B", "LIQUIDACION.NUM_LIQUIDACION=B.NRO_REFERENCIA", 'left', false);
        $this->db->join("(
 SELECT C.COD_FISCALIZACION, SUM(C.VALOR_PAGADO) AS ABONOS
 FROM PAGOSRECIBIDOS C
 GROUP BY C.COD_FISCALIZACION
) D", "LIQUIDACION.COD_FISCALIZACION=D.COD_FISCALIZACION", 'left', false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("TIPOPROCESO", "LIQUIDACION.COD_TIPOPROCESO=TIPOPROCESO.COD_TIPO_PROCESO", "LEFT");
//        $this->db->join("INSTANCIAS_PROCESOS", "INSTANCIAS_PROCESOS.COD_TIPO_PROCESO=TIPOPROCESO.COD_TIPO_PROCESO", "LEFT");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join("RESOLUCION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION", 'LEFT');
        $this->db->join("USUARIOS", "RESOLUCION.ABOGADO=USUARIOS.IDUSUARIO");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
//        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function reporte_abogado() {
        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("ASIGNACIONFISCALIZACION.FECHA_ASIGNACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["abogado_resol"] != "") {
            $abogado = explode(' - ', $post["abogado_resol"]);
            $this->db->where("(USUARIOS.IDUSUARIO LIKE UPPER('%" . $abogado[0] . "%') OR USUARIOS.NOMBRES LIKE upper('%" . $abogado[0] . "%')"
                    . "USU.IDUSUARIO LIKE UPPER('%" . $abogado[0] . "%') OR USU.NOMBRES LIKE upper('%" . $abogado[0] . "%'))", '', FALSE);
        }
        $array_select = array('0', 'REGIONAL', 'NIT', 'RAZÃ“N SOCIAL', 'ESTADO ABOGADO', 'VIA ADMINISTRATIVA', 'CIUDAD', 'CODIGO CIIU', 'DESCRIPCIÃ“N ACTIVIDAD',
            'FECHA FISCALIZACIÃ“N', 'NOMBRE DEL FISCALIZADOR', 'ABOGADO_RELACIONES_CORP', 'NUMERO_RESOLUCION');

        $this->db->select("REGIONAL.NOMBRE_REGIONAL AS REGIONAL,ASIGNACIONFISCALIZACION.NIT_EMPRESA AS NIT, EMPRESA.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
            CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO,
DECODE(USUARIOS.ACTIVO,1,'ACTIVO','INACTIVO') AS ESTADO_ABOGADO, 
DECODE(B.COD_GESTION_COBRO,NULL,'DOCUMENTO NO GENERADO','DOCUMENTO GENERADO') AS VIA_ADMINISTRATIVA,
MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD, EMPRESA.CIIU AS CODIGO_CIIU, TIPOGESTION.TIPOGESTION AS DESCRIPCION_ACTIVIDAD,
ASIGNACIONFISCALIZACION.FECHA_ASIGNACION AS FECHA_FISCALIZACION,
USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS AS NOMBRE_DEL_FISCALIZADOR,LIQUIDACION.NUM_LIQUIDACION NUMERO_LIQUIDACION,
USU.NOMBRES || ' ' || USU.APELLIDOS AS ABOGADO_RELACIONES_CORP,RESOLUCION.NUMERO_RESOLUCION
", false);
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION = FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = ASIGNACIONFISCALIZACION.NIT_EMPRESA");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A = USUARIOS.IDUSUARIO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL = USUARIOS.COD_REGIONAL");
        $this->db->join('RESOLUCION', 'FISCALIZACION.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION');
        $this->db->join('LIQUIDACION', 'FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION', 'LEFT');
        $this->db->join("USUARIOS USU", "RESOLUCION.ABOGADO= USU.IDUSUARIO", 'LEFT');
        $this->db->join("CONCEPTOSFISCALIZACION", "FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO = REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO = REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("(SELECT GESTIONCOBRO.COD_FISCALIZACION_EMPRESA,GESTIONCOBRO.NIT_EMPRESA,PROCESOGUBERNATIVO.COD_GESTION_COBRO
            FROM PROCESOGUBERNATIVO 
            JOIN GESTIONCOBRO ON PROCESOGUBERNATIVO.COD_GESTION_COBRO = GESTIONCOBRO.COD_GESTION_COBRO) B", "B.COD_FISCALIZACION_EMPRESA = FISCALIZACION.COD_FISCALIZACION", 'LEFT');
        $this->db->join("(SELECT COD_FISCALIZACION_EMPRESA,MAX(COD_GESTION_COBRO) COD_GESTION_COBRO
FROM GESTIONCOBRO
GROUP BY COD_FISCALIZACION_EMPRESA) C", "C.COD_FISCALIZACION_EMPRESA = FISCALIZACION.COD_FISCALIZACION");
        $this->db->join("GESTIONCOBRO", "GESTIONCOBRO.COD_GESTION_COBRO=C.COD_GESTION_COBRO");
        $this->db->join("TIPOGESTION", "TIPOGESTION.COD_GESTION = GESTIONCOBRO.COD_TIPOGESTION");
        $dato = $this->db->get("FISCALIZACION");

        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function reporte_liquidacion($otro_select = NULL) {

        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }

        $array_select = array('0', 'COD. REGIONAL', 'REGIONAL', 'NUM. LIQUIDACIÃ“N', 'CONCEPTO', 'CIUDAD', 'NIT', 'RAZÃ“N SOCIAL', 'VALOR CAPITAL INICIAL',
            'VALOR INTERESES INCIAL', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE TOTAL', 'ESTADO', 'PERIODO',
            'FECHA LIQUIDACION', 'FECHA DE CORTE', 'FECHA ULTIMO PAGO', 'ASIGNADO A', 'FECHA ASIGNACIÃ“N');
        $this->db->select("REGIONAL.COD_REGIONAL,
REGIONAL.NOMBRE_REGIONAL AS REGIONAL,
LIQUIDACION.NUM_LIQUIDACION,
CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO,
MUNICIPIO.NOMBREMUNICIPIO, 
EMPRESA.CODEMPRESA, 
EMPRESA.NOMBRE_EMPRESA, 
" . $otro_select . "
    ROUND(LIQUIDACION.TOTAL_CAPITAL, 0) AS VALOR_CAPITAL_INICIAL, 
LIQUIDACION.TOTAL_INTERESES AS VALOR_INTERES_INICIAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
LIQUIDACION.SALDO_DEUDA AS SALDO_PENDIENTE_TOTAL,
CASE WHEN FLOOR(SYSDATE-LIQUIDACION.FECHA_VENCIMIENTO) > 30 THEN 'VENCIDA' ELSE 'GENERADA' END  AS ESTADO,
LIQUIDACION.FECHA_INICIO || ' A ' || LIQUIDACION.FECHA_FIN AS PERIODO, 
LIQUIDACION.FECHA_LIQUIDACION, 
ADD_MONTHS(LIQUIDACION.FECHA_VENCIMIENTO,1) AS FECHA_DE_CORTE, ULTIMO_PAGO AS FECHA_ULTIMO_ABONO,
USUARIOS.APELLIDOS || ' '|| USUARIOS.NOMBRES as ASIGNADO_A,ASIGNACIONFISCALIZACION.FECHA_ASIGNACION", false);
        $this->db->join("(
 SELECT C.NRO_REFERENCIA, MAX(C.FECHA_PAGO) AS ULTIMO_PAGO
 FROM PAGOSRECIBIDOS C
 GROUP BY C.NRO_REFERENCIA
) B", "LIQUIDACION.NUM_LIQUIDACION=B.NRO_REFERENCIA", 'left', false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join("RESOLUCION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION", 'LEFT');
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
//        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;

        if (count($datos) == 0) {
            $datos = $array_select;
        }

        return $datos;
    }

    function edad_cartera() {

        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }

        $array_select = array('0', 'COD_REGIONAL', 'REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'INSTANCIA', 'GESTION ACTUAL',
            'VALOR CAPITAL INICIA RESOLUCIÃ“N',
            'VALOR CAPITAL VENCIDA 0 30', 'VALOR CAPITAL VENCIDA 31 60', 'VALOR CAPITAL VENCIDA 61 90', 'VALOR CAPITAL VENCIDA > 90', 'TOTAL OBLIGACIÃ“N');

        $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, MUNICIPIO.NOMBREMUNICIPIO, EMPRESA.CODEMPRESA, Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION,EMPRESA.NOMBRE_EMPRESA, "
                . "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, NOMBRE_CONCEPTO, RESOLUCION.NUMERO_RESOLUCION, RESOLUCION.FECHA_CREACION AS FECHA_RESOLUCION, "
                . "LIQUIDACION.FECHA_EJECUTORIA, 
                    TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA AS INSTANCIA,
                    RESOLUCION.VALOR_TOTAL AS VALOR_RESOLUCION, LIQUIDACION.TOTAL_INTERESES, LIQUIDACION.SALDO_DEUDA, 
                    decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,"
                . "RESOLUCION.FECHA_CREACION, LIQUIDACION.NUM_LIQUIDACION, FLOOR((SYSDATE-LIQUIDACION.FECHA_EJECUTORIA)) as DIAS,"
                . "C.TIPOGESTION as FECHA_ULTIMA_ACCION_COBRO ", false);
//        $this->db->select("concat(USUARIOS.APELLIDOS,concat(' ',USUARIOS.NOMBRES)) as NOMBRE_FISCALIZADOR", false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join("RESOLUCION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION");
        $this->db->join("(SELECT COD_TIPO_PROCESO,MAX(COD_TIPO_INSTANCIA ) COD_TIPO_INSTANCIA 
FROM INSTANCIAS_PROCESOS 
GROUP BY COD_TIPO_PROCESO )B", "B.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO", 'LEFT', FALSE);
        $this->db->join("TIPOS_INSTANCIAS", "TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA=B.COD_TIPO_INSTANCIA", 'LEFT');
        $this->db->join('(SELECT TIPOGESTION.TIPOGESTION,GESTIONCOBRO.COD_FISCALIZACION_EMPRESA
FROM GESTIONCOBRO
JOIN (select COD_FISCALIZACION_EMPRESA,MAX(COD_GESTION_COBRO) COD_GESTION_COBRO
from GESTIONCOBRO
GROUP BY COD_FISCALIZACION_EMPRESA) S ON S.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO
JOIN TIPOGESTION ON TIPOGESTION.COD_GESTION=GESTIONCOBRO.COD_TIPOGESTION ) C', 'C.COD_FISCALIZACION_EMPRESA=LIQUIDACION.COD_FISCALIZACION');
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->where("LIQUIDACION.FECHA_RESOLUCION IS NOT NULL");
        $this->db->where("LIQUIDACION.SALDO_DEUDA >", "0");
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;

        $new_array = array();
        $j = 0;
        $h = 0;
        $cantidad = count($datos);
        for ($i = 0; $i < $cantidad; $i++) {
//            if ($datos[$i]['NUM_LIQUIDACION'] != ($j == $i ? 0 : $datos[$i - 1]['NUM_LIQUIDACION'])) {
            $new_array[$h]['COD_REGIONAL'] = $datos[$i]['COD_REGIONAL'];
            $new_array[$h]['REGIONAL'] = $datos[$i]['NOMBRE_REGIONAL'];
            $new_array[$h]['CODEMPRESA'] = $datos[$i]['CODEMPRESA'];
            $new_array[$h]['DIGITO_VERIFICACION'] = $datos[$i]['DIGITO_VERIFICACION'];
            $new_array[$h]['RAZON_SOCIAL'] = $datos[$i]['NOMBRE_EMPRESA'];
            $new_array[$h]['CONCEPTO'] = $datos[$i]['NOMBRE_CONCEPTO'];
            $new_array[$h]['CONCEPTO'] = $datos[$i]['NOMBRE_CONCEPTO'];
            $new_array[$h]['NUMERO_RESOLUCION'] = $datos[$i]['NUMERO_RESOLUCION'];
            $new_array[$h]['FECHA_RESOLUCION'] = $datos[$i]['FECHA_CREACION'];
            $new_array[$h]['FECHA_EJECUTORIA'] = $datos[$i]['FECHA_EJECUTORIA'];
            $new_array[$h]['INSTANCIA'] = $datos[$i]['INSTANCIA'];
            $new_array[$h]['GESTION_ACTUAL'] = $datos[$i]['FECHA_ULTIMA_ACCION_COBRO'];
            $new_array[$h]['VALOR_INICIAL'] = $datos[$i]['VALOR_RESOLUCION'];
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_0_30'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_0_30'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_31_60'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_31_60'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_61_90'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_61_90'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_90_360'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_90_360'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_361_720'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_361_720'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_721_1080'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_721_1080'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_1081_1440'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_1081_1440'] = 0;
            $new_array[$h]['VALOR_CAPITAL_VENCIDA_>_1441'] = 0;
            $new_array[$h]['VALOR_INTERES_VENCIDA_>_1441'] = 0;
            $new_array[$h]['TOTAL_OBLIGACION'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'] + $datos[$i]['SALDO_PENDIENTE_INTERESES'];
//                $h++;
//            } else {
//                $j++;
//            } 


            if ($datos[$i]['DIAS'] > 1441) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_>_1441'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_>_1441'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else
            if ($datos[$i]['DIAS'] > 1080) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_1081_1440'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_1081_1440'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else
            if ($datos[$i]['DIAS'] > 720) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_721_1080'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_721_1080'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else
            if ($datos[$i]['DIAS'] > 360) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_361_720'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_361_720'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else
            if ($datos[$i]['DIAS'] > 90) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_90_360'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_90_360'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else
            if ($datos[$i]['DIAS'] > 60) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_61_90'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_61_90'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else if ($datos[$i]['DIAS'] > 30) {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_31_60'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_31_60'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            } else {
                $new_array[$h]['VALOR_CAPITAL_VENCIDA_0_30'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['VALOR_INTERES_VENCIDA_0_30'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
            }
            $h++;
        }
//        echo "<pre>";
//        print_r($new_array);
//        echo "</pre>";

        $datos = $new_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function edad_cartera_grupal() {

        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }

        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'PERÃ�ODO', 'CANTIDAD DE CARTERAS', 'SALDO DEUDA');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,DECODE( FLOOR( FLOOR((SYSDATE -1) - FECHA_EJECUTORIA) / 30),
0, 'Menor a 30 dÃ­as',
1, 'De 31 a 60 dÃ­as',
2, 'De 61 a 90 dÃ­as',
3, 'De 91 a 360 dÃ­as',
4, 'De 361 a 720 dÃ­as',
5, 'De 721 a 1080 dÃ­as',
6, 'De 1081 a 1440 dÃ­as',
'Mayor a 1441 dÃ­as'
) AS PERIODO, COUNT(*) CANTIDAD_DE_CARTERAS, SUM(LIQUIDACION.SALDO_DEUDA) SALDO_DEUDA", false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("RESOLUCION", "RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->where("LIQUIDACION.FECHA_RESOLUCION IS NOT NULL");
        $this->db->where("LIQUIDACION.SALDO_DEUDA >0  GROUP BY REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,DECODE( FLOOR( FLOOR((SYSDATE -1) - FECHA_EJECUTORIA) / 30),
0, 'Menor a 30 dÃ­as',
1, 'De 31 a 60 dÃ­as',
2, 'De 61 a 90 dÃ­as',
3, 'De 91 a 360 dÃ­as',
4, 'De 361 a 720 dÃ­as',
5, 'De 721 a 1080 dÃ­as',
6, 'De 1081 a 1440 dÃ­as',
'Mayor a 1441 dÃ­as'
)", false, false);

        $dato = $this->db->get("LIQUIDACION");
//        echo $this->db->last_query();
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function liquidacion_general2() {

        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
//        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
//        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION");
        return $datos = $dato->result_array;
    }

    function liquidacion_estado_cuenta() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }

        $array_select = array('0', 'CONCEPTO', 'TIPO', 'NUMERO', 'FECHA', 'REGIONAL', 'ESTADO', 'VALOR', 'INTERES', 'DIAS MORA', 'TOTAL');

        $this->db->select("CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO, TIPOPROCESO.TIPO_PROCESO AS TIPO, 
    LIQUIDACION.NUM_LIQUIDACION AS NUMERO, LIQUIDACION.FECHA_LIQUIDACION AS FECHA, REGIONAL.NOMBRE_REGIONAL AS REGIONAL," .
                "CASE WHEN LIQUIDACION.COD_TIPOPROCESO='5' THEN 'GENERADA' ELSE 
     CASE WHEN (SYSDATE-LIQUIDACION.FECHA_VENCIMIENTO) > 0 THEN 'VENCIDA' ELSE '' END 
    END AS ESTADO,
    decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS VALOR,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS INTERES,
CEIL(SYSDATE-LIQUIDACION.FECHA_VENCIMIENTO) AS DIAS_MORA, 
LIQUIDACION.SALDO_DEUDA AS TOTAL", false);
        $this->db->where("((LIQUIDACION.NITEMPRESA=EMPRESA.CODEMPRESA) AND (REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL) AND(LIQUIDACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION(+)) 
    AND (LIQUIDACION.COD_TIPOPROCESO=TIPOPROCESO.COD_TIPO_PROCESO(+))) and 1=", "1", false);
        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION,REGIONAL, TIPOPROCESO, CONCEPTOSFISCALIZACION,EMPRESA");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function comparativo_periodo_model() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'REGIONAL', 'RAZON_SOCIAL', 'NIT', 'SALDO_ANTERIOR', 'TOTAL_PAGOS', 'SALDO_ACTUAL');

        $this->db->select("REGIONAL.NOMBRE_REGIONAL AS REGIONAL,NOMBRE_EMPRESA AS RAZON_SOCIAL,
            EMPRESA.CODEMPRESA AS NIT,LIQUIDACION.TOTAL_LIQUIDADO AS SALDO_ANTERIOR,
                                    B.TOTAL_PAGOS,LIQUIDACION.SALDO_DEUDA AS SALDO_ACTUAL", false);
        $this->db->join("EMPRESA", "LIQUIDACION.NITEMPRESA = EMPRESA.CODEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("
(SELECT PAGOSRECIBIDOS.NRO_REFERENCIA, SUM(PAGOSRECIBIDOS.VALOR_PAGADO) AS TOTAL_PAGOS
from PAGOSRECIBIDOS 
where PAGOSRECIBIDOS.NRO_REFERENCIA is not null
GROUP BY PAGOSRECIBIDOS.NRO_REFERENCIA
ORDER BY PAGOSRECIBIDOS.NRO_REFERENCIA
)B", "LIQUIDACION.NUM_LIQUIDACION=B.NRO_REFERENCIA", false);
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function reporte_aumento() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if ($post['estado'] == 2)
            $this->db->where("LIQUIDACION.SALDO_DEUDA<=", 0, false);
        if ($post['estado'] == 3)
            $this->db->where("LIQUIDACION.COD_TIPOPROCESO", '16');
        if ($post['estado'] == 4)
            $this->db->where("LIQUIDACION.COD_TIPOPROCESO", '4');
        if ($post['estado'] == 1)
            $this->db->where("LIQUIDACION.COD_TIPOPROCESO  not in (4,16) and LIQUIDACION.SALDO_DEUDA >", '0', false);


        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_EJECUTORIA BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO',
            'FAX', 'CONCEPTO', 'NÃšMERO RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'INSTANCIA', 'ABONOS EFECTUADOS', 'CANTIDAD DE ABONOS', 'VALOR INICIAL', 'PAGOS EFECTUADOS', 'SALDO PENDIENTE CAPITAL',
            'SALDO PENDIENTE INTERESES', 'SALDO ACTUAL DEUDA');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,
CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU,
EMPRESA.CODEMPRESA NIT,Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL,
CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO CONCEPTO,RESOLUCION.NUMERO_RESOLUCION,  
LIQUIDACION.FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,TIPOPROCESO.TIPO_PROCESO INSTANCIA,
B.VALOR_PAGADO ABONOS_EFECTUADOS,B.CANTIDAD CANTIDAD_DE_ABONOS,B.FECHA_PAGO FECHA_ABONO,
RESOLUCION.VALOR_TOTAL AS VALOR_INICIAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
SALDO_DEUDA SALDO_ACTUAL_DEUDA,
CASE 
WHEN LIQUIDACION.SALDO_DEUDA<=0 THEN
	'Cancelada'
WHEN LIQUIDACION.COD_TIPOPROCESO=23 THEN
	'ResoluciÃ³n de PrescripciÃ³n'
WHEN LIQUIDACION.COD_TIPOPROCESO=16 THEN
	'Remisibilidad'
ELSE
	'Activa'
END  AS ESTADO ", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO", FALSE);
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO,COUNT(VALOR_PAGADO) CANTIDAD, MAX(FECHA_PAGO) FECHA_PAGO
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function reporte_aumento_grupal() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if ($post['estado'] == 2)
            $this->db->where("LIQUIDACION.SALDO_DEUDA<=", 0, false);
        if ($post['estado'] == 3)
            $this->db->where("LIQUIDACION.COD_TIPOPROCESO", '16');
        if ($post['estado'] == 4)
            $this->db->where("LIQUIDACION.COD_TIPOPROCESO", '4');
        if ($post['estado'] == 1)
            $this->db->where("LIQUIDACION.COD_TIPOPROCESO  not in (4,16) and LIQUIDACION.SALDO_DEUDA >", '0', false);


        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_EJECUTORIA BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'COD VERIFICACIÃ“N', 'RAZÃ“N SOCIAL', 'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO',
            'FAX', 'CONCEPTO', 'NÃšMERO RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'INSTANCIA', 'ABONOS EFECTUADOS', 'CANTIDAD DE ABONOS', 'VALOR INICIAL', 'PAGOS EFECTUADOS', 'SALDO PENDIENTE CAPITAL',
            'SALDO PENDIENTE INTERESES', 'SALDO ACTUAL DEUDA');

        $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL REGIONAL, 
COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DE_DEUDORES, 
COUNT(LIQUIDACION.COD_CONCEPTO) CANTIDAD_CARTERAS, 
sum(B.VALOR_PAGADO) ABONOS_EFECTUADOS, 
sum(B.CANTIDAD) CANTIDAD_DE_ABONOS, 
sum(SALDO_DEUDA) SALDO_ACTUAL_DEUDA, 
CASE WHEN LIQUIDACION.SALDO_DEUDA<=0 THEN 
'Cancelada' 
WHEN LIQUIDACION.COD_TIPOPROCESO=23 THEN 
'ResoluciÃ³n de PrescripciÃ³n' 
WHEN LIQUIDACION.COD_TIPOPROCESO=16 THEN 
'Remisibilidad' 
ELSE 'Activa' END AS ESTADO  ", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO", FALSE);
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO,COUNT(VALOR_PAGADO) CANTIDAD, MAX(FECHA_PAGO) FECHA_PAGO
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
//        $this->db->where('LIQUIDACION.SALDO_DEUDA >',0,false);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL GROUP BY  REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL , CASE  
WHEN LIQUIDACION.SALDO_DEUDA<=0 THEN 
'Cancelada' 
WHEN LIQUIDACION.COD_TIPOPROCESO=23 THEN 
'ResoluciÃ³n de PrescripciÃ³n' 
WHEN LIQUIDACION.COD_TIPOPROCESO=16 THEN 
'Remisibilidad' 
ELSE 
'Activa' 
END", false, false);
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function resolucion_acuerdopago() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("B.FECHA_ACUERDO    BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }

        if (!empty($post['num_obligacion'])) {
            $this->db->where('ACUERDOPAGO.NRO_ACUERDOPAGO', $post["num_obligacion"]);
        }
        if ($post["abogado_resol"] != "") {
            $abogado = explode(' - ', $post["abogado_resol"]);
            $this->db->where("(USUARIOS.IDUSUARIO LIKE UPPER('%" . $abogado[0] . "%') OR USUARIOS.NOMBRES LIKE upper('%" . $abogado[0] . "%'))", '', FALSE);
        }

        $array_select = array('0', 'COD. REGIONAL', 'NOMBRE REGIONAL', 'NIT', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'ESTADO', 'CIUDAD', 'NO. ACUERDO DE PAGO',
            'VALOR CAPITAL INICIAL', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE TOTAL', 'SALDO PENDIENTE TOTAL', 'FECHA ACUERDO DE PAGO', 'NRO TOTAL CUOTAS',
            'ULTIMA CUOTA CANCELADA', 'DIAS MORA', 'FECHA CORTE', 'FECHA DE PAGO', 'TOTAL GARANTIA', 'FECHA INICIO', 'FECHA FIN ACUERDO PAGO', 'ABOGADO');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,EMPRESA.CODEMPRESA NIT,EMPRESA.NOMBRE_EMPRESA,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO,
CASE WHEN CEIL(SYSDATE - MIN(PROYACUPAG_FECHALIMPAGO))<0 THEN 'GENERADA' ELSE 'VENCIDA' END  AS ESTADO,
MUNICIPIO.NOMBREMUNICIPIO,ACUERDOPAGO.NRO_ACUERDOPAGO NRO_DE_ACUERDO_PAGO,ACUERDOPAGO.VALOR_CAPITAL_FECHA VALOR_CAPITAL_INICIAL,ACUERDOPAGO.VALO_RINTERESES_CAPITAL SALDO_PENDIENTE_INTERES,
SUM(PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL) SALDO_PENDIENTE_CAPITAL,SUM(PROYECCIONACUERDOPAGO.PROYACUPAG_VALORINTERESESMORA) SALDO_PENDIENTE_INTERES,
SUM(PROYECCIONACUERDOPAGO.PROYACUPAG_SALDOCAPITAL)+SUM(PROYECCIONACUERDOPAGO.PROYACUPAG_VALORINTERESESMORA) SALDO_PENDIENTE_TOTAL,
ACUERDOPAGO.FECHA_CREACION FECHA_ACUERDO_DE_PAGO,
ACUERDOPAGO.NUMERO_CUOTAS NRO_TOTAL_CUOTAS,
MIN(PROYECCIONACUERDOPAGO.PROYACUPAG_NUMCUOTA)-1 ULTIMA_CUOTA_CANCELADA,
CASE WHEN CEIL(SYSDATE - MIN(PROYACUPAG_FECHALIMPAGO))<0 THEN 0 ELSE CEIL(SYSDATE - MIN(PROYACUPAG_FECHALIMPAGO)) END  AS DIAS_MORA,
MIN(PROYACUPAG_FECHALIMPAGO) FECHA_CORTE,MIN(PROYACUPAG_FECHAPAGO) FECHA_DE_PAGO,ACUERDOPAGO.TOTAL_GARANTIA,ACUERDOPAGO.FECHA_CREACION FECHA_INICIO,MAX(PROYACUPAG_FECHALIMPAGO) FECHA_FIN_ACUERDO_PAGO,
USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS ABOGADO,SUM(PAGOSRECIBIDOS.VALOR_PAGADO) ABONOS ", false);
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=ACUERDOPAGO.USUARIO_GENERA");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=ACUERDOPAGO.COD_CONCEPTO_COBRO");
        $this->db->join("PROYECCIONACUERDOPAGO", "PROYECCIONACUERDOPAGO.NRO_ACUERDOPAGO=ACUERDOPAGO.NRO_ACUERDOPAGO", FALSE);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=ACUERDOPAGO.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join("PAGOSRECIBIDOS", 'PAGOSRECIBIDOS.NRO_REFERENCIA=ACUERDOPAGO.NRO_LIQUIDACION', 'LEFT');
        $this->db->where("PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", 0);
        $this->db->group_by(array("REGIONAL.COD_REGIONAL", "REGIONAL.NOMBRE_REGIONAL", "EMPRESA.CODEMPRESA", "EMPRESA.NOMBRE_EMPRESA", "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO", "
MUNICIPIO.NOMBREMUNICIPIO", "ACUERDOPAGO.NRO_ACUERDOPAGO", "ACUERDOPAGO.VALOR_CAPITAL_FECHA", "ACUERDOPAGO.VALO_RINTERESES_CAPITAL", "
ACUERDOPAGO.FECHA_CREACION", "ACUERDOPAGO.NUMERO_CUOTAS", "PROYECCIONACUERDOPAGO.PROYACUPAG_ESTADO", "ACUERDOPAGO.TOTAL_GARANTIA", "USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS"));
        $dato = $this->db->get("ACUERDOPAGO");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    public function get_pagos($post) {
        //if ($post['post']['empresa'] != "-1") :
        $this->db->select("TO_CHAR(LIQUIDACION.FECHA_LIQUIDACION, 'DD/MM/RR') AS FECHA_LIQUIDACION", FALSE);
        $this->db->select("LIQUIDACION.NUM_LIQUIDACION, (LIQUIDACION.TOTAL_LIQUIDADO-LIQUIDACION.TOTAL_INTERESES) AS TOTAL_CAPITAL, "
                . "LIQUIDACION.TOTAL_INTERESES, LIQUIDACION.COD_CONCEPTO, LIQUIDACION.FECHA_VENCIMIENTO, "
                . "LIQUIDACION.SALDO_DEUDA AS VALOR_DEUDA_ACTUAL, "
                . "PAGOSRECIBIDOS.VALOR_PAGADO AS VALOR_RECIBIDO, PAGOSRECIBIDOS.NUM_DOCUMENTO, PAGOSRECIBIDOS.FECHA_APLICACION, PAGOSRECIBIDOS.FECHA_PAGO, "
                . "PAGOSRECIBIDOS.PROCEDENCIA, PAGOSRECIBIDOS.DISTRIBUCION_CAPITAL, PAGOSRECIBIDOS.DISTRIBUCION_INTERES, "
                . "PAGOSRECIBIDOS.DISTRIBUCION_INTERES_MORA, FORMAPAGO.NOMBE_FORMAPAGO, "
                . "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO");
        if ($post['post']['concepto'] != "-1")
            $this->db->where("LIQUIDACION.COD_CONCEPTO", $post['post']['concepto']);
        if (!empty($post['post']['fecha_ini']))
            $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO >=", " TO_DATE('" . $post['post']['fecha_ini'] . "', 'DD/MM/RR')", FALSE);
        if (!empty($post['post']['fecha_fin']))
            $this->db->where("PAGOSRECIBIDOS.FECHA_PAGO <=", " TO_DATE('" . $post['post']['fecha_fin'] . "', 'DD/MM/RR')", FALSE);
//        if(!empty($this->estado)) $this->db->where("PAGOSRECIBIDOS.APLICADO", $this->estado);
        $this->db->order_by("LIQUIDACION.FECHA_LIQUIDACION", "ASC");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("PAGOSRECIBIDOS", "PAGOSRECIBIDOS.NITEMPRESA=LIQUIDACION.NITEMPRESA AND PAGOSRECIBIDOS.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION");
        $this->db->join("FORMAPAGO", "PAGOSRECIBIDOS.COD_FORMAPAGO=FORMAPAGO.COD_FORMAPAGO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        $dato = $this->db->get("LIQUIDACION");
        $dato = $dato->result_array();
        if (!empty($dato)) : return $dato;
        else : return $dato = NULL;
        endif;
        //endif;
    }

    function pago_recibidos() {
        $post = $this->input->post();
        $this->db->select("COD_PAGO, VALOR_PAGADO, PERIODO_PAGADO, PROCEDENCIA, TO_CHAR(FECHA_PAGO, 'YYYY-MM-DD') AS FECHA_PAGO, 
      CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO AS CONCEPTO, CONCEPTORECAUDO.NOMBRE_CONCEPTO AS SUBCONCEPTO, 
      CONCILIADO, EMPRESA.CODEMPRESA,EMPRESA.RAZON_SOCIAL AS NOMBRE_EMPRESA, REGIONAL.NOMBRE_REGIONAL, 
      DEPARTAMENTO.NOM_DEPARTAMENTO, MUNICIPIO.NOMBREMUNICIPIO", false);
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $fechaini = $post['fecha_ini'];
            $fechaini = explode("/", $fechaini);
            $fechafin = $post['fecha_fin'];
            $fechafin = explode("/", $fechafin);
            $this->db->where("PAGOSRECIBIDOS.PERIODO_PAGADO BETWEEN ('" . $fechaini[2] . "-" . $fechaini[1] . "') AND ('" . $fechafin[2] . "-" . $fechafin[1] . "') AND 1=", "1", false);
        }
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=PAGOSRECIBIDOS.COD_CONCEPTO ");
        $this->db->join("CONCEPTORECAUDO", "CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=PAGOSRECIBIDOS.COD_SUBCONCEPTO ");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=PAGOSRECIBIDOS.NITEMPRESA AND EMPRESA.CODEMPRESA = " . $post['empresa'], "LEFT", FALSE);
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL", "LEFT");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO ", "LEFT");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $num = array(1, 2, 3);
        $this->db->where_in("PAGOSRECIBIDOS.COD_CONCEPTO", $num, false);
        $num = array(17, 19, 21);
        $this->db->where_in("PAGOSRECIBIDOS.COD_SUBCONCEPTO", $num, false);
        $this->db->order_by('FECHA_PAGO');
        $dato = $this->db->get("PAGOSRECIBIDOS");
        return $datos = $dato->result_array;
    }

    function empresa_general() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("EMPRESA.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $this->db->join("TIPODOCUMENTO", "TIPODOCUMENTO.CODTIPODOCUMENTO=EMPRESA.COD_TIPODOCUMENTO");
        $dato = $this->db->get("EMPRESA");
        return $datos = $dato->result_array;
    }

    function informe_fiscalizacion() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("ASIGNACIONFISCALIZACION.FECHA_ASIGNACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["ciiu"] != "") {
            $abogado = explode(' - ', $post["ciiu"]);
            $this->db->where("EMPRESA.CIIU", $abogado[0]);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }

        $array_select = array('0', 'REGIONAL', 'CIIU', 'NIT', 'RAZÃ“N SOCIAL', 'NOMBRE CONCEPTO', 'TIPO GESTION', 'NUMERO EXPEDEINTE',
            'NUMERO ESTADO DE CUENTA', 'FECHA DE ESTADO CUENTA',
            'RESPONSABLE', 'FECHA ASIGNACIÃ“N');

        $this->db->select("REGIONAL.NOMBRE_REGIONAL REGIONAL,"
                . "EMPRESA.CIIU, EMPRESA.CODEMPRESA, EMPRESA.NOMBRE_EMPRESA, "
                . "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, TIPOGESTION.TIPOGESTION AS TIPO_GESTION, "
                . "FISCALIZACION.COD_FISCALIZACION NUMERO_EXPEDEINTE,LIQUIDACION.NUM_LIQUIDACION AS NUMERO_ESTADO_DE_CUENTA, "
                . "FISCALIZACION.FECHA_ASIGNACION_ABOGADO AS FECHA_DE_ESTADO_CUENTA,"
                . "USUARIOS.APELLIDOS || ' ' ||  USUARIOS.NOMBRES as RESPONSABLE,ASIGNACIONFISCALIZACION.FECHA_ASIGNACION ", false);
        $this->db->join("EMPRESA", "ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("FISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("CONCEPTOSFISCALIZACION", "FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION");
        $this->db->join("TIPOGESTION", "FISCALIZACION.COD_TIPOGESTION=TIPOGESTION.COD_GESTION");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("LIQUIDACION", "LIQUIDACION.COD_FISCALIZACION=FISCALIZACION.COD_FISCALIZACION");
        $dato = $this->db->get("ASIGNACIONFISCALIZACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function generar_recuperacion_cartera() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $this->db->select("MUNICIPIO.NOMBREMUNICIPIO,EMPRESA.CODEMPRESA,
            EMPRESA.NOMBRE_EMPRESA, SUM(LIQUIDACION.TOTAL_LIQUIDADO) AS TOTAL,SUM(LIQUIDACION.SALDO_DEUDA) AS SALDO,
            SUM(LIQUIDACION.TOTAL_LIQUIDADO-LIQUIDACION.SALDO_DEUDA) AS TOTAL_PAGADO,SUM(LIQUIDACION.TOTAL_INTERESES) AS INTERES");
        $this->db->join("EMPRESA", "LIQUIDACION.NITEMPRESA=EMPRESA.CODEMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("MUNICIPIO", "EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->group_by("MUNICIPIO.NOMBREMUNICIPIO,EMPRESA.CODEMPRESA,EMPRESA.NOMBRE_EMPRESA");
        $this->db->order_by("MUNICIPIO.NOMBREMUNICIPIO,EMPRESA.NOMBRE_EMPRESA");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION");
        return $datos = $dato->result_array;
    }

    function generar_recuperacion_cartera2() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_LIQUIDACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'APORTES CAPITAL', 'APORTES INTERES', 'FIC CAPITAL', 'FIC INTERES',
            'MULTAS MINISTERIO CAPITAL', 'MULTAS MINISTERIO INTERES', 'CONTRATO APRENDIZAJE CAPITAL', 'CONTRATO INTERES', 'TOTAL');

        $this->db->select("regional.cod_regional,REGIONAL.NOMBRE_REGIONAL,LIQUIDACION.COD_CONCEPTO,LIQUIDACION.NUM_LIQUIDACION,
            CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, MUNICIPIO.NOMBREMUNICIPIO, EMPRESA.CODEMPRESA, 
            Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION,
            EMPRESA.NOMBRE_EMPRESA, LIQUIDACION.TOTAL_LIQUIDADO AS TOTAL2, 
            decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_DEUDA,
            decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO,
            decode(SALDO_DEUDA, 0, 0, ROUND(LIQUIDACION.SALDO_DEUDA * (LIQUIDACION.TOTAL_INTERESES / LIQUIDACION.TOTAL_CAPITAL))) AS INTERES ", FALSE);
        $this->db->join("EMPRESA", "LIQUIDACION.NITEMPRESA=EMPRESA.CODEMPRESA");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("MUNICIPIO", "EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->order_by("EMPRESA.CODEMPRESA");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        //$this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
        $dato = $this->db->get("LIQUIDACION");
        $datos = $dato->result_array;
        $cantidad = count($datos);

        $new_array = array();
        $j = 0;
        $h = 0;
        for ($i = 0; $i < $cantidad; $i++) {
            if ($datos[$i]['CODEMPRESA'] != ($j == $i ? 0 : $datos[$i - 1]['CODEMPRESA'])) {
                $new_array[$h]['COD_REGIONAL'] = $datos[$i]['COD_REGIONAL'];
                $new_array[$h]['REGIONAL'] = $datos[$i]['NOMBRE_REGIONAL'];
                $new_array[$h]['CODEMPRESA'] = $datos[$i]['CODEMPRESA'];
                $new_array[$h]['DIGITO_VERIFICACION'] = $datos[$i]['DIGITO_VERIFICACION'];
                $new_array[$h]['RAZON_SOCIAL'] = $datos[$i]['NOMBRE_EMPRESA'];
                $new_array[$h]['VALOR_INICIAL'] = 0;
                $new_array[$h]['APORTES_CAPITAL'] = 0;
                $new_array[$h]['APORTES_INTERES'] = 0;
                $new_array[$h]['FIC_CAPITAL'] = 0;
                $new_array[$h]['FIC_INTERES'] = 0;
//                $new_array[$h]['INTERESES'] = 0;
                $new_array[$h]['MULTAS_MINISTERIO_CAPITAL'] = 0;
                $new_array[$h]['MULTAS_MINISTERIO_INTERES'] = 0;
                $new_array[$h]['CONTRATO_APRENDIZAJE_CAPITAL'] = 0;
                $new_array[$h]['CONTRATO_APRENDIZAJE_INTERES'] = 0;
                $new_array[$h]['TOTAL'] = 0;

                $h++;
            } else {
                $j++;
            }
            switch ($datos[$i]['COD_CONCEPTO']) {
                case 1:
                    $new_array[($h == 0 ? $h : $h - 1)]['APORTES_CAPITAL']+=$datos[$i]['SALDO_DEUDA'];
                    $new_array[($h == 0 ? $h : $h - 1)]['APORTES_INTERES']+=$datos[$i]['INTERES'];
                    break;
                case 2:
                    $new_array[($h == 0 ? $h : $h - 1)]['FIC_CAPITAL']+=$datos[$i]['SALDO_DEUDA'];
                    $new_array[($h == 0 ? $h : $h - 1)]['FIC_INTERES']+=$datos[$i]['INTERES'];
                    break;
                case 3:
                    $new_array[($h == 0 ? $h : $h - 1)]['CONTRATO_APRENDIZAJE_CAPITAL']+=$datos[$i]['SALDO_DEUDA'];
                    $new_array[($h == 0 ? $h : $h - 1)]['MULTAS_MINISTERIO_INTERES']+=$datos[$i]['INTERES'];
                    break;
                case 5:
                    $new_array[($h == 0 ? $h : $h - 1)]['MULTAS_MINISTERIO_CAPITAL']+=$datos[$i]['SALDO_DEUDA'];
                    $new_array[($h == 0 ? $h : $h - 1)]['MULTAS_MINISTERIO_INTERES']+=$datos[$i]['INTERES'];
                    break;
            }
//            $new_array[($h == 0 ? $h : $h - 1)]['INTERESES']+=$datos[$i]['INTERES'];
            $new_array[($h == 0 ? $h : $h - 1)]['TOTAL']+=$datos[$i]['SALDO_DEUDA'] + $datos[$i]['INTERES'];
            $new_array[($h == 0 ? $h : $h - 1)]['VALOR_INICIAL']+=$datos[$i]['TOTAL2'];
        }
//        echo "<pre>";
//        print_r($new_array);
//        echo "</pre>";
//        return $datos = $dato->result_array;
        $datos = $new_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function generar_recuperacion_cartera2_grupal() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_LIQUIDACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'NOMBRE CONCEPTO', 'CANTIDAD DE DEUDORES', 'CANTIDAD CARTERAS', 'VALOR TOTAL CARTERA');

        $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL REGIONAL, 
COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DE_DEUDORES, 
COUNT(LIQUIDACION.COD_CONCEPTO) CANTIDAD_CARTERAS, 
sum(B.VALOR_PAGADO) ABONOS_EFECTUADOS, 
sum(B.CANTIDAD) CANTIDAD_DE_ABONOS, 
sum(SALDO_DEUDA) SALDO_ACTUAL_DEUDA, 
CASE WHEN LIQUIDACION.SALDO_DEUDA<=0 THEN 
'Cancelada' 
WHEN LIQUIDACION.COD_TIPOPROCESO=23 THEN 
'Resolucion de Prescripcion' 
WHEN LIQUIDACION.COD_TIPOPROCESO=16 THEN 
'Remisibilidad' 
ELSE 'Activa' END AS ESTADO  ", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO", FALSE);
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO,COUNT(VALOR_PAGADO) CANTIDAD, MAX(FECHA_PAGO) FECHA_PAGO
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL 
            group by REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,CASE WHEN LIQUIDACION.SALDO_DEUDA<=0 THEN 
'Cancelada' 
WHEN LIQUIDACION.COD_TIPOPROCESO=23 THEN 
'Resolucion de Prescripcion' 
WHEN LIQUIDACION.COD_TIPOPROCESO=16 THEN 
'Remisibilidad' 
ELSE 'Activa' END", false, false);
//        $this->db->group_by("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,LIQUIDACION.COD_TIPOPROCESO");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;

        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function generar_reporte_gestion() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'REGIONAL', 'CONCEPTO', 'TIPO', 'CANTIDAD', 'PAGOS RECIBIDOS', 'PAGOS PENDIENTES', 'TOTAL RECAUDO', 'TOTAL SALDO');

        $this->db->select("REGIONAL.NOMBRE_REGIONAL AS REGIONAL, NOMBRE_CONCEPTO AS CONCEPTO, 
    DECODE(LIQUIDACION.COD_TIPOPROCESO, 5, 'LIQUIDACIONES', 'RESOLUCIONES') AS TIPO,
    COUNT(*) as CANTIDAD, SUM(DECODE(PAGOSRECIBIDOS.NRO_REFERENCIA, NULL, 0, 1)) AS PAGOS_RECIBIDOS, 
    (COUNT(*) - SUM(DECODE(PAGOSRECIBIDOS.NRO_REFERENCIA, NULL, 0, 1))) AS PAGOS_PENDIENTES,
    SUM(NVL(VALOR_PAGADO, 0)) AS TOTAL_RECAUDO, 
    SUM(NVL(LIQUIDACION.SALDO_DEUDA, 0)) AS TOTAL_SALDO", FALSE);
        $this->db->where("((REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL)
    AND (EMPRESA.CodEMPRESA = LIQUIDACION.NITEMPRESA)
    AND (LIQUIDACION.COD_CONCEPTO = CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION)
    AND (LIQUIDACION.NUM_LIQUIDACION = PAGOSRECIBIDOS.NRO_REFERENCIA(+))) and 1=", "1", false);
        $group = array("REGIONAL.NOMBRE_REGIONAL", "NOMBRE_CONCEPTO", "DECODE(LIQUIDACION.COD_TIPOPROCESO, 5, 'LIQUIDACIONES', 'RESOLUCIONES')");
        $this->db->group_by($group, FALSE);
        $dato = $this->db->get("REGIONAL, EMPRESA, LIQUIDACION, PAGOSRECIBIDOS, CONCEPTOSFISCALIZACION");
//        exit($this->db->last_query());
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function Generar_reporte_desagregado() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $this->db->select("EMPRESA.NOMBRE_EMPRESA,TIPODOCUMENTO.NOMBRETIPODOC,EMPRESA.CODEMPRESA,LIQUIDACION.COD_TIPOPROCESO as TIPO_CARTERA,
LIQUIDACION.SALDO_DEUDA as ORIGEN_CARTERA, LIQUIDACION.SALDO_DEUDA,LIQUIDACION.TOTAL_LIQUIDADO,
(SELECT MAX(FECHA_PAGO) as ULTIMA_FECHA_PAGO FROM PAGOSRECIBIDOS WHERE COD_CONCEPTO=LIQUIDACION.COD_CONCEPTO AND NITEMPRESA=EMPRESA.CODEMPRESA ) as ULTIMA_FECHA_PAGO,
(SELECT MAX(PAGOSRECIBIDOS.PERIODO_PAGADO) as PERIODO_PAGADO FROM PAGOSRECIBIDOS WHERE COD_CONCEPTO=LIQUIDACION.COD_CONCEPTO AND NITEMPRESA=EMPRESA.CODEMPRESA ) as PERIODO_PAGADO,
PROCESO.NOMBREPROCESO as ULTIMA_ACCION_COBRO,
(SELECT MAX(GESTIONCOBRO.FECHA_CONTACTO) as FECHA_CONTACTO from GESTIONCOBRO WHERE GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=1) as FECHA_ULTIMA_ACCION_COBRO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA = LIQUIDACION.NITEMPRESA ");
        $this->db->join("TIPODOCUMENTO", "TIPODOCUMENTO.CODTIPODOCUMENTO=EMPRESA.COD_TIPODOCUMENTO");
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $this->db->where("LIQUIDACION.BLOQUEADA", "0");
        $this->db->where("LIQUIDACION.EN_FIRME", "S");
        $dato = $this->db->get("LIQUIDACION");
        return $datos = $dato->result_array;
    }

    function Generar_Estados_cuenta_Aprendizaje() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("ASIGNACIONFISCALIZACION.FECHA_ASIGNACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('LIQUIDACION.NUM_LIQUIDACION', $post["num_obligacion"]);
        }
        $array_select = array('0', 'REGIONAL', 'RAZÃ“N SOCIAL', 'NIT', 'ESTADO DE CUENTA SGVA', 'NUMERO DE EXPEDIENTE',
            'FECHA_DE_ESTADO_CUENTA', 'SALDO DEUDA', 'NUMERO ESTADO DE CUENTA', 'ESTADO', 'RESPONSABLE', 'FECHA ASIGNACIÃ“N');

        $this->db->select("REGIONAL.NOMBRE_REGIONAL AS REGIONAL, EMPRESA.NOMBRE_EMPRESA AS RAZON_SOCIAL, EMPRESA.CODEMPRESA AS NIT,'SI' AS ESTADO_DE_CUENTA_SGVA,
            FISCALIZACION.COD_FISCALIZACION AS NUMERO_DE_EXPEDIENTE,FISCALIZACION.FECHA_ASIGNACION_ABOGADO AS FECHA_DE_ESTADO_CUENTA,LIQUIDACION.SALDO_DEUDA, 
            LIQUIDACION.NUM_LIQUIDACION AS NUMERO_ESTADO_DE_CUENTA,(SELECT TIPOGESTION FROM GESTIONCOBRO 
            JOIN TIPOGESTION on COD_TIPOGESTION=COD_GESTION 
            WHERE COD_GESTION_COBRO=(SELECT MAX(COD_GESTION_COBRO) FROM GESTIONCOBRO WHERE COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION  )) AS ESTADO,
            USUARIOS.APELLIDOS || ' '|| USUARIOS.NOMBRES as RESPONSABLE,ASIGNACIONFISCALIZACION.FECHA_ASIGNACION", false);
        $this->db->join("ASIGNACIONFISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=ASIGNACIONFISCALIZACION.NIT_EMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("LIQUIDACION", "FISCALIZACION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=ASIGNACIONFISCALIZACION.ASIGNADO_A");
        $this->db->join("CONCEPTOSFISCALIZACION", "FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION");
        $this->db->where("FISCALIZACION.COD_CONCEPTO", "3");
        $dato = $this->db->get("FISCALIZACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function Generar_asignacion_fiscalizacion() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("ASIGNACIONFISCALIZACION.FECHA_ASIGNACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }

        $array_select = array('0', 'COD. REGIONAL', 'REGIONAL', 'FISCALIZADOR', 'RAZÃ“N SOCIAL', 'NIT', 'FECHA ASIGNACIÃ“N', 'NOMBRE CONCEPTO', 'ESTADO');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL AS REGIONAL, EMPRESA.COD_REGIONAL, 
            USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS AS FISCALIZADOR , EMPRESA.RAZON_SOCIAL,
            EMPRESA.CODEMPRESA, ASIGNACIONFISCALIZACION.FECHA_ASIGNACION, CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO");
        $this->db->select("(SELECT RESPUESTAGESTION.NOMBRE_GESTION "
                . "FROM RESPUESTAGESTION JOIN GESTIONCOBRO ON GESTIONCOBRO.COD_TIPO_RESPUESTA=RESPUESTAGESTION.COD_RESPUESTA "
                . "WHERE GESTIONCOBRO.COD_GESTION_COBRO=(SELECT MAX(COD_GESTION_COBRO) "
                . "FROM GESTIONCOBRO WHERE COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION)) AS ESTADO");
        $this->db->join("EMPRESA", "ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA ");
        $this->db->join("USUARIOS", "ASIGNACIONFISCALIZACION.ASIGNADO_A=USUARIOS.IDUSUARIO");
        $this->db->join("FISCALIZACION", "ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION=FISCALIZACION.COD_ASIGNACION_FISC");
        $this->db->join("REGIONAL", "EMPRESA.COD_REGIONAL=REGIONAL.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION");
        $dato = $this->db->get("ASIGNACIONFISCALIZACION");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function Generar_Multas_Ministerio() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("MULTASMINISTERIO.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post['estado_multa'] != '-1') {
            $this->db->where("MULTASMINISTERIO.EXIGIBILIDAD_TITULO", $post['estado_multa']);
        }
        $array_select = array('0',
            'RAZON SOCIAL', 'CIUDAD', 'DIRECCION', 'TIPO DE DOCUMENTO', 'NUMERO DOCUMENTO', 'REPRESENTANTE LEGAL',
            'NUMERO DE DOCUMENTO RL', 'NRO RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'FECHA_RECEPCION_DOCUMENTOS', 'ESTADO MULTA', 'INSTANCIA', 'MOTIVO DEVOLUCIÃ“N', 'NUMERO COMUNICACIÃ“N',
            'FECHA GESTION', 'VALOR INICIAL MINISTERIO', 'VALOR LIQUIDADA', 'TOTAL INTERESES', 'SALDO DEUDA', 'ESTADO PAGO');

        $this->db->select("EMPRESA.NOMBRE_EMPRESA AS RAZON_SOCIAL, MUNICIPIO.NOMBREMUNICIPIO AS CIUDAD, EMPRESA.DIRECCION, 
            TIPODOCUMENTO.NOMBRETIPODOC AS TIPO_DE_DOCUMENTO, EMPRESA.CODEMPRESA AS NUMERO_DOCUMENTO,  EMPRESA.REPRESENTANTE_LEGAL, 
            EMPRESA.COD_REPRESENTANTELEGAL AS NUMERO_DE_DOCUMENTO_RL,
            MULTASMINISTERIO.NRO_RESOLUCION,MULTASMINISTERIO.FECHA_EJECUTORIA,
            MULTASMINISTERIO.FECHA_CREACION FECHA_RECEPCION_DOCUMENTOS,
            DECODE(EXIGIBILIDAD_TITULO,1,'ACEPTADA','DEVUELTA') ESTADO_MULTA,
            PROCESO.NOMBREPROCESO INSTANCIA,
            DECODE(EXIGIBILIDAD_TITULO,0,MULTASMINISTERIO.OBSERVACIONES,'') MOTIVO_DEVOLUCION,
            MULTASMINISTERIO.NUMERO_COMUNICACION,MULTASMINISTERIO.FECHA_GESTION,
            MULTASMINISTERIO.VALOR AS VALOR_INICIAL_MINISTERIO,
            INTERES_MULTAMIN_ENC.VALOR_CAPITAL AS VALOR_LIQUIDADO,INTERES_MULTAMIN_ENC.TOTAL_INTERESES,SALDO_DEUDA,
            decode(SALDO_DEUDA,0,'PAGADA','NO PAGADA') ESTADO_PAGO", FALSE);
        $this->db->join("EMPRESA", "MULTASMINISTERIO.NIT_EMPRESA=EMPRESA.CODEMPRESA", 'LEFT');
        $this->db->join("INTERES_MULTAMIN_ENC", "MULTASMINISTERIO.COD_MULTAMINISTERIO=INTERES_MULTAMIN_ENC.COD_MULTAMINISTERIO", 'LEFT');
        $this->db->join("LIQUIDACION", "LIQUIDACION.NUM_LIQUIDACION=INTERES_MULTAMIN_ENC.COD_MULTAMINISTERIO", 'LEFT');
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $this->db->join("TIPODOCUMENTO", "TIPODOCUMENTO.CODTIPODOCUMENTO=EMPRESA.COD_TIPODOCUMENTO", 'LEFT');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL", 'LEFT');
        $this->db->join("RESOLUCION", "RESOLUCION.NUMERO_RESOLUCION=MULTASMINISTERIO.NRO_RESOLUCION");
        $this->db->join("MUNICIPIO", "EMPRESA.COD_MUNICIPIO=MUNICIPIO.CODMUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", 'LEFT');
//        $this->db->join("LIQUIDACION", "INTERES_MULTAMIN_ENC.COD_INTERES_MULTA_MIN=LIQUIDACION.NUM_LIQUIDACION");
        $dato = $this->db->get("MULTASMINISTERIO");
        $datos = $dato->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function Generar_Gestion_empresa() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("GESTIONCOBRO.FECHA_CONTACTO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["abogado_resol"] != "") {
            $abogado = explode(' - ', $post["abogado_resol"]);
            $this->db->where("(USUARIOS.IDUSUARIO LIKE UPPER('%" . $abogado[0] . "%') OR USUARIOS.NOMBRES LIKE upper('%" . $abogado[0] . "%'))", '', FALSE);
        }

        $array_select = array('0', 'NIT EMPRESA', 'RAZÃ“N SOCIAL', 'INSTANCIA', 'FUNCIÃ“NARIO', 'ACCIÃ“NES', 'FECHA_GESTION');

        $this->db->join("EMPRESA", "GESTIONCOBRO.NIT_EMPRESA = EMPRESA.CODEMPRESA", 'LEFT');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO", "LEFT");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO = GESTIONCOBRO.COD_USUARIO", 'LEFT');
        $this->db->join("TIPOGESTION", "GESTIONCOBRO.COD_TIPOGESTION = TIPOGESTION.COD_GESTION ");
        $this->db->join("PROCESO", "TIPOGESTION.CODPROCESO = PROCESO.CODPROCESO", 'LEFT');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("FISCALIZACION", "FISCALIZACION.COD_FISCALIZACION=GESTIONCOBRO.COD_FISCALIZACION_EMPRESA");
        $this->db->join("CIIU", "EMPRESA.CIIU=CIIU.COD_CIUU", 'LEFT');
        $this->db->join("CONCEPTOSFISCALIZACION", "FISCALIZACION.COD_CONCEPTO=CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION");
        $this->db->join("RESPUESTAGESTION", "GESTIONCOBRO.COD_TIPO_RESPUESTA = RESPUESTAGESTION.COD_RESPUESTA ", 'LEFT');
        $this->db->order_by("GESTIONCOBRO.FECHA_CONTACTO", "desc ");
        $dato = $this->db->get("GESTIONCOBRO");

        $new_array = $dato->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function Generar_Informe_visitas() {
        $post = $this->input->post();


        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("NOTIFICACIONVISITA.FECHA_VISITA BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["concepto"] != "-1" && $post["reporte"] == 29)
            $this->db->where('FISCALIZACION.COD_CONCEPTO', $post["concepto"]);
        if ($post["fiscalizador"] != "") {
            $post["fiscalizador"] = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $post["fiscalizador"][0]);
        }
        $array_select = array('0', 'NOMBRE REGIONAL', 'NOMBRE CONCEPTO', 'RAZÃ“N SOCIAL', 'FECHA VISITA', 'NRO VISITAS', 'FISCALIZADOR', 'COMENTARIOS');

        $this->db->select("REGIONAL.NOMBRE_REGIONAL,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,"
                . "EMPRESA.NOMBRE_EMPRESA,"
//                . "EMPRESA.DIRECCION,EMPRESA.TELEFONO_FIJO"
                . "", false);
        $this->db->select("to_char(NOTIFICACIONVISITA.FECHA_VISITA,'dd/mm/yyyy') AS FECHA_VISITA,COUNT(*) AS NRO_VISITAS,USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS as FISCALIZADOR,RESPUESTAGESTION.NOMBRE_GESTION AS COMENTARIOS", FALSE);
//        $this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_INICIO,'hh24:mi') AS HORA_INICIO", FALSE);
//        $this->db->select("to_char(NOTIFICACIONVISITA.HORA_VISITA_FIN,'hh24:mi') AS HORA_FIN", FALSE);
        $this->db->join('GESTIONCOBRO', 'NOTIFICACIONVISITA.COD_GESTION_COBRO=GESTIONCOBRO.COD_GESTION_COBRO');
        $this->db->join('GESTIONCOBRO GES', 'NOTIFICACIONVISITA.COD_GESTION_COBRO=GES.COD_GESTION_COBRO');
        $this->db->join('FISCALIZACION', 'GESTIONCOBRO.COD_FISCALIZACION_EMPRESA=FISCALIZACION.COD_FISCALIZACION');
        $this->db->join('ASIGNACIONFISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION');
        $this->db->join('EMPRESA', 'ASIGNACIONFISCALIZACION.NIT_EMPRESA=EMPRESA.CODEMPRESA');
        $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=ASIGNACIONFISCALIZACION.ASIGNADO_A');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join('RESPUESTAGESTION', 'RESPUESTAGESTION.COD_RESPUESTA=GESTIONCOBRO.COD_TIPO_RESPUESTA');
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=FISCALIZACION.COD_CONCEPTO");
        $this->db->group_by("REGIONAL.NOMBRE_REGIONAL,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO, USUARIOS.NOMBRES || ' ' || USUARIOS.APELLIDOS, EMPRESA.NOMBRE_EMPRESA", false);
        $this->db->group_by("NOTIFICACIONVISITA.FECHA_VISITA,RESPUESTAGESTION.NOMBRE_GESTION", FALSE);
        $this->db->order_by("NOTIFICACIONVISITA.FECHA_VISITA", 'desc');
//        $this->db->group_by("to_char(NOTIFICACIONVISITA.FECHA_VISITA,'dd/mm/yyyy') AS FECHA_VISITA" , FALSE);
        $datos = $this->db->get('NOTIFICACIONVISITA');
        $new_array = $datos->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function consulta_tablas() {
        $consulta = $this->db->query("SELECT TABLE_NAME FROM all_tables WHERE owner = 'SENA' ORDER BY TABLE_NAME");
        return $consulta->result_array;
    }

    function key_foranea($post) {
        $consulta = $this->db->query("SELECT  A.TABLE_NAME,C.COLUMN_NAME AS COLUMNA, B.TABLE_NAME AS TABLA_FORANEA, 
            D.COLUMN_NAME AS COLUMNA_FORANEA 
            FROM ALL_CONSTRAINTS A, ALL_CONSTRAINTS B, ALL_CONS_COLUMNS C,  ALL_CONS_COLUMNS D 
            WHERE A.CONSTRAINT_TYPE = 'R' 
            AND A.R_CONSTRAINT_NAME = B.CONSTRAINT_NAME 
            AND A.CONSTRAINT_NAME = C.CONSTRAINT_NAME 
            AND B.CONSTRAINT_NAME = D.CONSTRAINT_NAME 
            AND C.POSITION = D.POSITION 
            AND A.TABLE_NAME='" . $post['post']['tabla'] . "' 
            ORDER BY A.TABLE_NAME, B.TABLE_NAME, C.COLUMN_NAME, D.COLUMN_NAME");
        return $consulta->result_array;
    }

    function key_primaria($post) {
        $consulta = $this->db->query("SELECT A.CONSTRAINT_NAME AS restriccion, A.COLUMN_NAME AS columna
            FROM ALL_CONS_COLUMNS A JOIN ALL_CONSTRAINTS C  ON A.CONSTRAINT_NAME = C.CONSTRAINT_NAME
            WHERE C.CONSTRAINT_TYPE = 'P' 
            AND A.TABLE_NAME = '" . $post['post']['tabla'] . "' 
            ORDER BY A.CONSTRAINT_NAME, A.TABLE_NAME, A.COLUMN_NAME");
        return $consulta->result_array;
    }

    function datos_tabla($post) {
        $CONSULTA2 = $this->db->query("SELECT USER FROM DUAL");
        $CONSULTA2 = $CONSULTA2->result_array;
        $consulta = $this->db->query("SELECT col.column_name, col.data_type,com.comments
            FROM all_tab_columns col, all_col_comments com 
            WHERE  col.table_name = com.table_name 
            AND  col.column_name = com.column_name AND col.owner=com.owner 
            AND col.table_name='" . $post['post']['tabla'] . "' 
            AND col.owner = '" . $CONSULTA2[0]['USER'] . "' ORDER BY col.table_name, col.column_id ");
        return $consulta->result_array;
    }

    function consultar($post) {
        $tabla = array();
        $tablas_info = "";
        $select = substr($post['post']['tarea'], 0, -1);
        $SQL = "SELECT " . $select . " FROM " . $post['post']['tabla_inicial'] . " " . $post['post']['join'];
        if (!empty($post['post']['tarea_where'])) {
            $where = $post['post']['tarea_where'];
            $where = explode('|||||', $where);
            $contar1 = count($where);
            $nom_tabla = "";
            $columna = "";
            $accion = "";
            $valor = "";
            for ($i = 1; $i < $contar1; $i++) {
                $where1 = explode('||||', $where[$i]);
                for ($j = 1; $j < count($where1); $j++) {
                    $where2 = explode('|||', $where1[$j]);
                    for ($h = 1; $h < count($where2); $h++) {
                        $columna[] = $where2[0];
                        $accion[] = $where2[1];
                        $valor[] = $where2[2];
                    }
                }
            }
            $where = "";
            for ($i = 0; $i < count($columna); $i++) {
                $valor[$i] = mb_strtoupper($valor[$i]);
                switch ($accion[$i]) {
                    case 1:
                        $accion[$i] = " < '" . $valor[$i] . "' AND  ";
                        break;
                    case 2:
                        $accion[$i] = " > '" . $valor[$i] . "' AND  ";
                        break;
                    case 3:
                        $accion[$i] = " >= '" . $valor[$i] . "' AND  ";
                        break;
                    case 4:
                        $accion[$i] = " <= '" . $valor[$i] . "' AND  ";
                        break;
                    case 5:
                        $accion[$i] = " = '" . $valor[$i] . "' AND  ";
                        break;
                    case 6:
                        $accion[$i] = " != '" . $valor[$i] . "' AND  ";
                        break;
                    case 7:
                        $accion[$i] = " = '" . $valor[$i] . "' AND  ";
                        break;
                    case 8:
                        $accion[$i] = " LIKE '" . $valor[$i] . "%' AND  ";
                        break;
                    case 9:
                        $accion[$i] = " LIKE '%" . $valor[$i] . "' AND  ";
                        break;
                    case 10:
                        $accion[$i] = " LIKE '%" . $valor[$i] . "%' AND  ";
                        break;
                }
                $where.="UPPER(" . $columna[$i] . ")" . $accion[$i];
            }
            $where = substr($where, 0, -5);
        } else
            $where = "1=1";

        $SQL = $SQL . " Where " . $where;
        $consulta = $this->db->query($SQL);
        $arreglo['sql'] = $SQL;
        $arreglo['consulta'] = $consulta->result_array;
        $post['post']['consulta_info'] = base64_encode($SQL);
        $this->log($post);
        return $arreglo;
    }

    function consultar2($post) {
        $consulta = $this->db->query(base64_decode($post['post']['consulta_info']));
        return $consulta->result_array;
    }

    function consultar3($post) {
        $consulta = $this->db->query(base64_decode($post['post']['consulta_info']));
        $this->log($post);
        return $consulta->result_array;
    }

    function log($post) {
        $this->db->set('IDUSUARIO', ID_USER);
        $this->db->set('NOMBRE_REPORTE', NOMBRE_COMPLETO);
        $this->db->set('OBSERVACIONES', $post['post']['consulta_info']);
        $this->db->set('FECHA_CREACION', FECHA, false);
        $this->db->set('ACCION', '1');
        $this->db->insert('LOG_REPORTES');
    }

    function log2($post) {
        $this->db->set('IDUSUARIO', ID_USER);
        $this->db->set('NOMBRE_REPORTE', $post['name_reporte']);
        $this->db->set('OBSERVACIONES', "Reporte de generado del Generador de reporte");
        $this->db->set('ACCION', '2');
        $this->db->set('FECHA_CREACION', FECHA, false);
        $this->db->insert('LOG_REPORTES');
    }

    function consulta_reporte() {
        $consulta = $this->db->query("SELECT CONSULTA_REPORTES_GUARDADOS.NOMBRE_REPORTE,CONSULTA_REPORTES_GUARDADOS.FECHA_CREACION,
            CONSULTA_REPORTES_GUARDADOS.OBSERVACIONES AS CONSULTAR , CONCAT(USUARIOS.NOMBRES, USUARIOS.APELLIDOS) AS PROPIETARIO,DECODE(ACCION,1,'Privado','General') ACCION
            FROM CONSULTA_REPORTES_GUARDADOS
            JOIN USUARIOS on USUARIOS.IDUSUARIO =CONSULTA_REPORTES_GUARDADOS.IDUSUARIO 
            WHERE CONSULTA_REPORTES_GUARDADOS.IDUSUARIO='" . ID_USER . "' OR CONSULTA_REPORTES_GUARDADOS.ACCION='2'"
                . "ORDER BY CONSULTA_REPORTES_GUARDADOS.COD_CONSULTA");
        return $consulta->result_array;
    }

    function guardar_reporte($post) {

        $this->db->set('IDUSUARIO', ID_USER);
        $this->db->set('NOMBRE_REPORTE', $post['post']['nombre']);
        $this->db->set('ACCION', $post['post']['guardar']);
        $this->db->set('OBSERVACIONES', $post['post']['consulta_info']);
        $this->db->set('FECHA_CREACION', FECHA, false);
        $this->db->insert('CONSULTA_REPORTES_GUARDADOS');
    }

    function plantilla($id) {
        $this->db->select('ARCHIVO_PLANTILLA');
        $this->db->where('CODPLANTILLA', $id);
        $dato = $this->db->get('PLANTILLA');
        return $datos5 = $dato->result_array['0']['ARCHIVO_PLANTILLA'];
    }

    function estado_fiscalizacion() {
        $post = $this->input->post();
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("ASIGNACIONFISCALIZACION.FECHA_ASIGNACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post["fiscalizador"] != "") {
            $fiscalizador = explode(' - ', $post["fiscalizador"]);
            $this->db->where("ASIGNACIONFISCALIZACION.ASIGNADO_A", $fiscalizador[0]);
        }
        $this->db->select('FISCALIZACION.COD_FISCALIZACION,REGIONAL.NOMBRE_REGIONAL,USUARIOS.IDUSUARIO,LIQUIDACION.TOTAL_LIQUIDADO,LIQUIDACION.SALDO_DEUDA');
        $this->db->select('0 as TOTAL_EMPRESAS, 0 AS GESTION, 0 AS LIQUIDACION,0 AS SIN_GESTION,0 AS PAGO,0 AS VIA_GUBERNATIVA', FALSE);
        $this->db->select("concat(USUARIOS.APELLIDOS,concat(' ',USUARIOS.NOMBRES)) as NOMBRE", FALSE);
        $this->db->join('USUARIOS', 'USUARIOS.IDUSUARIO=ASIGNACIONFISCALIZACION.ASIGNADO_A');
        $this->db->join('FISCALIZACION', 'FISCALIZACION.COD_ASIGNACION_FISC=ASIGNACIONFISCALIZACION.COD_ASIGNACIONFISCALIZACION', 'LEFT');
        $this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=USUARIOS.COD_REGIONAL', 'LEFT');
        $this->db->join('LIQUIDACION', 'LIQUIDACION.COD_FISCALIZACION=FISCALIZACION.COD_FISCALIZACION', 'LEFT');
        $this->db->order_by('REGIONAL.NOMBRE_REGIONAL,USUARIOS.IDUSUARIO');
        $dato = $this->db->get('ASIGNACIONFISCALIZACION');
        $datos = $dato->result_array;
        $cantidad = count($datos);
        for ($i = 0; $i < $cantidad; $i++) {
            if (!empty($datos[$i]['COD_FISCALIZACION'])) {
                $this->db->select("COUNT(GESTIONCOBRO.COD_FISCALIZACION_EMPRESA) AS CANTIDAD", FALSE);
                $this->db->join('GESTIONCOBRO', 'GESTIONCOBRO.COD_GESTION_COBRO=PROCESOGUBERNATIVO.COD_GESTION_COBRO');
                $this->db->where('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA', $datos[$i]['COD_FISCALIZACION']);
                $this->db->where('GESTIONCOBRO.COD_TIPOGESTION', 9);
                $this->db->group_by('GESTIONCOBRO.COD_FISCALIZACION_EMPRESA');
                $dato = $this->db->get('PROCESOGUBERNATIVO');
                $dato = $dato->result_array;
                if (!empty($dato[0]['CANTIDAD'])) {
                    $datos[$i]['VIA_GUBERNATIVA'] = $datos[$i]['VIA_GUBERNATIVA'] + 1;
                } else {
                    $this->db->select("COUNT(LIQUIDACION.COD_FISCALIZACION) AS CANTIDAD", FALSE);
                    $this->db->where('LIQUIDACION.COD_FISCALIZACION', $datos[$i]['COD_FISCALIZACION']);
                    $dato = $this->db->get('LIQUIDACION');
                    $dato = $dato->result_array;
                    if (!empty($dato[0]['CANTIDAD'])) {
                        $datos[$i]['LIQUIDACION'] = $datos[$i]['LIQUIDACION'] + 1;
                    }
                }
            } else {
                $datos[$i]['SIN_GESTION']+=1;
            }
        }
        $new_array = array();
        $j = 0;
        $h = 0;

        $array_select = array('0', 'REGIONAL', 'COD. FISCALIZADOR', 'FISCALIZADOR', 'TOTAL EMPRESAS', 'GESTIÃ“N', 'LIQUIDACIÃ“N', 'VÃ�A ADMINISTRATIVA',
            'SIN GESTIÃ“N', 'PAGO', 'TOTAL RECAUDADO');

        for ($i = 0; $i < $cantidad; $i++) {
            if ($datos[$i]['IDUSUARIO'] != ($j == $i ? 0 : $datos[$i - 1]['IDUSUARIO'])) {
                $new_array[$h]['REGIONAL'] = $datos[$i]['NOMBRE_REGIONAL'];
                $new_array[$h]['COD_FISCALIZADOR'] = $datos[$i]['IDUSUARIO'];
                $new_array[$h]['FISCALIZADOR'] = $datos[$i]['NOMBRE'];
                $new_array[$h]['TOTAL_EMPRESAS'] = 0;
                $new_array[$h]['GESTION'] = 0;
                $new_array[$h]['LIQUIDACION'] = 0;
                $new_array[$h]['VIA_ADMINISTRATIVA'] = 0;
                $new_array[$h]['SIN_GESTION'] = 0;
                $new_array[$h]['PAGO'] = 0;
                $new_array[$h]['TOTAL_LIQUIDADO'] = 0;
                $h++;
            } else {
                $j++;
            }
            $new_array[($h == 0 ? $h : $h - 1)]['VIA_ADMINISTRATIVA']+=$datos[$i]['VIA_GUBERNATIVA'];
            $new_array[($h == 0 ? $h : $h - 1)]['LIQUIDACION']+=$datos[$i]['LIQUIDACION'];
            $new_array[($h == 0 ? $h : $h - 1)]['TOTAL_EMPRESAS']+=1;
//            echo "**".$datos[$i]['SALDO_DEUDA']."<br>";
            if ($datos[$i]['SALDO_DEUDA'] == 0 && $datos[$i]['SALDO_DEUDA'] != '') {
                $new_array[($h == 0 ? $h : $h - 1)]['PAGO']+=1;
            }
            $new_array[($h == 0 ? $h : $h - 1)]['TOTAL_EMPRESAS']+=1;
            $new_array[($h == 0 ? $h : $h - 1)]['SIN_GESTION']+=$datos[$i]['SIN_GESTION'];
            $new_array[($h == 0 ? $h : $h - 1)]['GESTION'] = $new_array[($h == 0 ? $h : $h - 1)]['TOTAL_EMPRESAS'] - ($new_array[($h == 0 ? $h : $h - 1)]['VIA_ADMINISTRATIVA'] + $new_array[($h == 0 ? $h : $h - 1)]['LIQUIDACION']) - $new_array[($h == 0 ? $h : $h - 1)]['SIN_GESTION'];
            $new_array[($h == 0 ? $h : $h - 1)]['TOTAL_LIQUIDADO']+=$datos[$i]['TOTAL_LIQUIDADO'];
        }
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function personalizar_datos($post) {
//        echo "<pre>";
//        print_r($post);
        $liquidacion = "";
        $resolucion = "";
        $ejecutoria = "";
        $revocatoria = "";

        if (isset($post['concepto'])) {
            $this->db->where_in('CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', $post['concepto']);
        }
        if (isset($post['liquidacion'])) {
            for ($i = 0; $i < count($post['liquidacion']); $i++) {
                $liquidacion.=$post['liquidacion'][$i] . ",";
            }
        }
        if (isset($post['resolucion'])) {
            for ($i = 0; $i < count($post['resolucion']); $i++) {
                $resolucion.=$post['resolucion'][$i] . ",";
            }
        }
        if (isset($post['ejecutoria'])) {
            for ($i = 0; $i < count($post['ejecutoria']); $i++) {
                $ejecutoria.=$post['ejecutoria'][$i] . ",";
            }
        }
        if (isset($post['revocatoria'])) {
            for ($i = 0; $i < count($post['revocatoria']); $i++) {
                $revocatoria.=$post['revocatoria'][$i] . ",";
            }
        }
        if (!empty($post['fecha_ini_liq']))
            $this->db->where("LIQUIDACION.FECHA_LIQUIDACION BETWEEN to_date('" . $post['fecha_ini_liq'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin_liq'] . "','DD/MM/YYYY') AND 1=", "1", false);
        if (!empty($post['fecha_ini_res']))
            $this->db->where("RESOLUCION.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini_res'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin_res'] . "','DD/MM/YYYY') AND 1=", "1", false);
        if (!empty($post['fecha_ini_eje']))
            $this->db->where("EJECUTORIA.FECHA_EJECUTORIA BETWEEN to_date('" . $post['fecha_ini_eje'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin_eje'] . "','DD/MM/YYYY') AND 1=", "1", false);
        if (!empty($post['fecha_ini_rev']))
            $this->db->where("REVOCATORIA.FECHA_REVOCATORIA BETWEEN to_date('" . $post['fecha_ini_rev'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin_rev'] . "','DD/MM/YYYY') AND 1=", "1", false);

        $this->db->select($liquidacion);
        $this->db->select($resolucion);
        $this->db->select($ejecutoria);
        $this->db->select($revocatoria);
        $this->db->join('RESOLUCION', 'RESOLUCION.NUM_LIQUIDACION=LIQUIDACION.NUM_LIQUIDACION', 'LEFT');
        $this->db->join('REVOCATORIA', 'REVOCATORIA.COD_RESOLUCION=RESOLUCION.COD_RESOLUCION', 'LEFT');
        $this->db->join('EJECUTORIA', 'EJECUTORIA.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION', 'LEFT');
        $this->db->join('CONCEPTOSFISCALIZACION', 'CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO', 'LEFT');
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("PROCESO", "LIQUIDACION.COD_TIPOPROCESO=PROCESO.CODPROCESO", "LEFT");
        $dato = $this->db->get('LIQUIDACION');
//        print_r($dato->result_array);
        return $dato->result_array;
    }

    function empresa_consulta($nit) {
        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA, NVL(SUM(LIQUIDACION.SALDO_DEUDA),0) AS SALDO_DEUDA,EMPRESA.NOMBRE_EMPRESA
            FROM EMPRESA
            left JOIN LIQUIDACION  ON LIQUIDACION.NITEMPRESA = EMPRESA.CODEMPRESA and LIQUIDACION.SALDO_DEUDA>0 and LIQUIDACION.FECHA_EJECUTORIA is not null
            JOIN REGIONAL ON REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
            WHERE EMPRESA.CODEMPRESA = '" . $nit . "' 
            GROUP BY EMPRESA.CODEMPRESA,EMPRESA.NOMBRE_EMPRESA";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_resolucion($nit) {
        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA, LIQUIDACION.SALDO_DEUDA,EMPRESA.NOMBRE_EMPRESA,
            REGIONAL.NOMBRE_REGIONAL,RESOLUCION.NUMERO_RESOLUCION,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO
            FROM EMPRESA
            JOIN LIQUIDACION  ON LIQUIDACION.NITEMPRESA = EMPRESA.CODEMPRESA
            JOIN REGIONAL ON REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
            JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION
            JOIN CONCEPTOSFISCALIZACION ON CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO
            WHERE EMPRESA.CODEMPRESA = '" . $nit . "'  AND LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL and LIQUIDACION.SALDO_DEUDA > 0";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta2($nit, $obra) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA and PAGOSRECIBIDOS.COD_SUBCONCEPTO in (17,62)
WHERE EMPRESA.CODEMPRESA ='" . $nit . "' and NRO_LICENCIA_CONTRATO='" . $obra . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_periodo($nit, $periodo) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA and PAGOSRECIBIDOS.COD_SUBCONCEPTO in (17,62) and PERIODO_PAGADO='" . $periodo . "'
WHERE EMPRESA.CODEMPRESA ='" . $nit . "' ";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_resiprocas($nit, $ano) {

        $SQL = "SELECT 
 EM.CODEMPRESA, PR.FECHA_PAGO, PR.PERIODO_PAGADO, PR.PROCEDENCIA, PR.VALOR_PAGADO, TP.NOMBRE_TIPO, PR.COD_PAGO, BA.NOMBREBANCO
FROM
  EMPRESA EM, PAGOSRECIBIDOS PR, TIPOCONCEPTO TP, BANCO BA 
WHERE
 EM.RECIPROCA = '1'
AND
  EM.CODEMPRESA = PR.NITEMPRESA
AND
  PR.COD_CONCEPTO = TP.COD_TIPOCONCEPTO
AND
  PR.COD_ENTIDAD = BA.IDBANCO
AND 
 EM.CODEMPRESA ='" . $nit . "' and TO_CHAR(PR.Fecha_Pago, 'YYYY')='" . $ano . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_nit($nit) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA and PAGOSRECIBIDOS.COD_SUBCONCEPTO in (17,62)
WHERE EMPRESA.CODEMPRESA ='" . $nit . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_nit_resolu($nit, $resul) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO,RESOLUCION.NUMERO_RESOLUCION,
LIQUIDACION.NUM_LIQUIDACION,PAGOSRECIBIDOS.FECHA_PAGO,NUM_DOCUMENTO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA 
LEFT JOIN LIQUIDACION on LIQUIDACION.NUM_LIQUIDACION=PAGOSRECIBIDOS.NRO_REFERENCIA
LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION
WHERE EMPRESA.CODEMPRESA ='" . $nit . "' AND (RESOLUCION.NUMERO_RESOLUCION='" . $resul . "' OR LIQUIDACION.NUM_LIQUIDACION='" . $resul . "')";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_nit_resolu3($resul) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO,RESOLUCION.NUMERO_RESOLUCION,
LIQUIDACION.NUM_LIQUIDACION,PAGOSRECIBIDOS.FECHA_PAGO,NUM_DOCUMENTO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA 
LEFT JOIN LIQUIDACION on LIQUIDACION.NUM_LIQUIDACION=PAGOSRECIBIDOS.NRO_REFERENCIA
LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION
WHERE (RESOLUCION.NUMERO_RESOLUCION='" . $resul . "' OR LIQUIDACION.NUM_LIQUIDACION='" . $resul . "')";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_nit_resolu2($nit) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,LIQUIDACION.NUM_LIQUIDACION,RESOLUCION.NUMERO_RESOLUCION,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO,RESOLUCION.NUMERO_RESOLUCION,
LIQUIDACION.NUM_LIQUIDACION,PAGOSRECIBIDOS.FECHA_PAGO,NUM_DOCUMENTO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA 
LEFT JOIN LIQUIDACION on LIQUIDACION.NUM_LIQUIDACION=PAGOSRECIBIDOS.NRO_REFERENCIA
LEFT JOIN RESOLUCION ON RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION
WHERE LIQUIDACION.EN_FIRME='S' AND LIQUIDACION.SALDO_DEUDA>0 AND  EMPRESA.CODEMPRESA ='" . $nit . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_nit_vigencia($nit, $ano) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO,PAGOSRECIBIDOS.FECHA_PAGO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA and PAGOSRECIBIDOS.COD_SUBCONCEPTO in (17,62)
WHERE EMPRESA.CODEMPRESA ='" . $nit . "' and TO_CHAR(PAGOSRECIBIDOS.Fecha_Pago, 'YYYY')='" . $ano . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta_obra($obra) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,
            PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,
            NRO_LICENCIA_CONTRATO,NUM_DOCUMENTO
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA and PAGOSRECIBIDOS.COD_SUBCONCEPTO in (17,62)
WHERE NRO_LICENCIA_CONTRATO='" . $obra . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function empresa_consulta3($obra) {

        $SQL = "SELECT EMPRESA.CODEMPRESA as NITEMPRESA,EMPRESA.NOMBRE_EMPRESA,PAGOSRECIBIDOS.NOMBRE_OBRA NOM_OBRA,PAGOSRECIBIDOS.NRO_TRABAJADORES_PERIODO EMPLEADOS, 
PAGOSRECIBIDOS.VALOR_PAGADO,PAGOSRECIBIDOS.PERIODO_PAGADO PERIODO,NRO_LICENCIA_CONTRATO,REGIONAL.NOMBRE_REGIONAL
FROM EMPRESA 
LEFT JOIN PAGOSRECIBIDOS ON PAGOSRECIBIDOS.NITEMPRESA=EMPRESA.CODEMPRESA and PAGOSRECIBIDOS.COD_SUBCONCEPTO in (17,62)
JOIN REGIONAL ON REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
WHERE NUM_DOCUMENTO='" . $obra . "'";
//        die;
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function periodos($post, $fechas, $nit) {

        $this->db->select('PAGOSRECIBIDOS.PERIODO_PAGADO');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=PAGOSRECIBIDOS.NITEMPRESA');
        $this->db->where('EMPRESA.CODEMPRESA', $nit);
        $this->db->where('(PAGOSRECIBIDOS.APLICADO=1 OR PAGOSRECIBIDOS.APLICADO=NULL) AND 1=', 1, FALSE);
        $this->db->where_in('PAGOSRECIBIDOS.PERIODO_PAGADO', $fechas);
        $this->db->where_in('PAGOSRECIBIDOS.COD_CONCEPTO', 1);
        $this->db->where_in('PAGOSRECIBIDOS.COD_SUBCONCEPTO', 21);
        $consulta = $this->db->get('PAGOSRECIBIDOS');

        $resul = "";

//        echo "<pre>";
//        print_r($consulta->result_array);
//        echo "</pre>";
//        die();

        if (count($consulta->result_array) > 0) {
            foreach ($consulta->result_array as $consultas) {
                $fechas = array_diff($fechas, $consultas);
                $resul = $fechas;
            }
        } else {
            $resul = $fechas;
        }
        return $resul;
    }

    function periodos2($post, $fechas, $nit) {

//        830507528
//        print_r($fechas);
        $this->db->select('PAGOSRECIBIDOS.PERIODO_PAGADO,SUM(PAGOSRECIBIDOS.VALOR_PAGADO) VALOR_PAGADO');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=PAGOSRECIBIDOS.NITEMPRESA');
        $this->db->where('EMPRESA.CODEMPRESA', $nit);
        $this->db->where('(PAGOSRECIBIDOS.APLICADO=1 OR PAGOSRECIBIDOS.APLICADO=NULL) AND 1=', 1, FALSE);
        $this->db->where_in('PAGOSRECIBIDOS.PERIODO_PAGADO', $fechas);
        $this->db->where_in('PAGOSRECIBIDOS.COD_CONCEPTO', 1);
//        $this->db->where_in('PAGOSRECIBIDOS.COD_SUBCONCEPTO', 21);
        $this->db->group_by('PAGOSRECIBIDOS.PERIODO_PAGADO');
        $this->db->order_by('PAGOSRECIBIDOS.PERIODO_PAGADO');
        $consulta = $this->db->get('PAGOSRECIBIDOS');

        return $consulta->result_array;
    }

    function periodos3($post, $fechas, $nit) {

//        830507528
//        print_r($fechas);
        $this->db->select('PAGOSRECIBIDOS.PERIODO_PAGADO,PAGOSRECIBIDOS.COD_PAGO,PAGOSRECIBIDOS.FECHA_PAGO,PAGOSRECIBIDOS.VALOR_PAGADO');
        $this->db->join('EMPRESA', 'EMPRESA.CODEMPRESA=PAGOSRECIBIDOS.NITEMPRESA');
        $this->db->where('EMPRESA.CODEMPRESA', $nit);
        $this->db->where('(PAGOSRECIBIDOS.APLICADO=1 OR PAGOSRECIBIDOS.APLICADO=NULL) AND 1=', 1, FALSE);
        $this->db->where_in('PAGOSRECIBIDOS.PERIODO_PAGADO', $fechas);
//        $this->db->where_in('PAGOSRECIBIDOS.COD_CONCEPTO', 2);
        $num = array(17, 19, 21);
        $this->db->where_in("PAGOSRECIBIDOS.COD_SUBCONCEPTO", $num, false);
        $this->db->where_in('PAGOSRECIBIDOS.PROCEDENCIA', 'MANUAL');
        $consulta = $this->db->get('PAGOSRECIBIDOS');

        return $consulta->result_array;
    }

    function bdme_incumplimiento_semestral() {
        $post = $this->input->post();
        $array_select = array('0', 'CONCEPTO', 'TIPO DEUDOR', 'NUMERO DE OBLIGACIÓN', 'NRO IDENTIFICACIÓN', 'TIPO IDENTIFICACIÓN', 'NOMBRE O RAZÓN SOCIAL', 'VALOR DE LA OBLIGACIÓN', 'ESTADO DE LA DEUDA');

        $SQL = "SELECT 
  1 AS CONCEPTO, 
  DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1') AS TIPO_DEUDOR, 
  NUMERO_RESOLUCION AS NUMERO_OBLIGACION, 
  E.CODEMPRESA AS NUMERO_IDENTIFICACION, 
  E.COD_TIPODOCUMENTO AS TIPO_IDENTIFICACION, 
  E.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
  SUM(SALDO_DEUDA) AS VALOR_OBLIGACION, 
  1 AS ESTADO_DEUDA
FROM SENA_CAPACITACION.LIQUIDACION L 
INNER JOIN SENA_CAPACITACION.RESOLUCION R ON R.NUM_LIQUIDACION = L.NUM_LIQUIDACION
INNER JOIN SENA_CAPACITACION.VW_PERSONAS E ON E.CODEMPRESA = L.NITEMPRESA
WHERE TO_CHAR(FECHA_EJECUTORIA, 'YYYYMM') <= TO_CHAR(ADD_MONTHS(TO_DATE('" . $post['anop'] . "', 'YYYY-MM'), -6), 'YYYYMM') AND EXISTS(
        SELECT 'X'
        FROM SENA_CAPACITACION.ACUERDOPAGO
        WHERE ACUERDOPAGO.NRO_LIQUIDACION =L.NUM_LIQUIDACION
              AND ACUERDOPAGO.AUTO_ACUERDO = 'S'
      )
HAVING SUM(SALDO_DEUDA) > (616000 * 5)
GROUP BY   DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1'), 
  NUMERO_RESOLUCION, 
  E.CODEMPRESA, 
  E.COD_TIPODOCUMENTO, 
  E.NOMBRE_EMPRESA";
        $consulta = $this->db->query($SQL);

        if (sizeof($consulta->result_array) > 0) {
            $datos = $consulta->result_array;
        } else {
            $datos = $array_select;
        }

        return $datos;
    }

    function bdme_reporte_semestral() {
        $post = $this->input->post();
        $array_select = array('0', 'CONCEPTO', 'TIPO DEUDOR', 'NUMERO DE OBLIGACIÓN', 'NRO IDENTIFICACIÓN', 'TIPO IDENTIFICACIÓN', 'NOMBRE O RAZÓN SOCIAL', 'VALOR DE LA OBLIGACIÓN', 'ESTADO DE LA DEUDA');

        $SQL = "SELECT 1 AS CONCEPTO, DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1') AS TIPO_DEUDOR, NUMERO_RESOLUCION AS NUMERO_OBLIGACION, 
  E.CODEMPRESA AS NUMERO_IDENTIFICACION,   E.COD_TIPODOCUMENTO AS TIPO_IDENTIFICACION,   E.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
  SUM(SALDO_DEUDA) AS VALOR_OBLIGACION, 1 AS ESTADO_DEUDA
FROM SENA_CAPACITACION.LIQUIDACION L 
INNER JOIN SENA_CAPACITACION.RESOLUCION R ON R.NUM_LIQUIDACION = L.NUM_LIQUIDACION
INNER JOIN SENA_CAPACITACION.VW_PERSONAS E ON E.CODEMPRESA = L.NITEMPRESA
WHERE TO_CHAR(FECHA_EJECUTORIA, 'YYYYMM') <= TO_CHAR(ADD_MONTHS(TO_DATE('" . $post['anop'] . "', 'YYYY-MM'), -6), 'YYYYMM')
HAVING SUM(SALDO_DEUDA) > (616000 * 5)
GROUP BY   DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1'), 
  NUMERO_RESOLUCION, 
  E.CODEMPRESA, 
  E.COD_TIPODOCUMENTO, 
  E.NOMBRE_EMPRESA";
        $consulta = $this->db->query($SQL);
        if (sizeof($consulta->result_array) > 0) {
            $datos = $consulta->result_array;
        } else {
            $datos = $array_select;
        }
        return $datos;
    }

    function bdme_cancelacion_acuerdo_de_pago() {
        $post = $this->input->post();
        $array_select = array('0', 'CONCEPTO', 'TIPO DEUDOR', 'NUMERO DE OBLIGACIÓN', 'NRO IDENTIFICACIÓN', 'TIPO IDENTIFICACIÓN', 'NOMBRE O RAZÓN SOCIAL', 'VALOR DE LA OBLIGACIÓN', 'ESTADO DE LA DEUDA');

        $SQL = "SELECT 
  1 AS CONCEPTO, 
  DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1') AS TIPO_DEUDOR, 
  NUMERO_RESOLUCION AS NUMERO_OBLIGACION, 
  E.CODEMPRESA AS NUMERO_IDENTIFICACION, 
  E.COD_TIPODOCUMENTO AS TIPO_IDENTIFICACION, 
  E.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
  SUM(SALDO_DEUDA) AS VALOR_OBLIGACION, 
  1 AS ESTADO_DEUDA
FROM SENA_CAPACITACION.LIQUIDACION L 
INNER JOIN SENA_CAPACITACION.RESOLUCION R ON R.NUM_LIQUIDACION = L.NUM_LIQUIDACION
INNER JOIN SENA_CAPACITACION.VW_PERSONAS E ON E.CODEMPRESA = L.NITEMPRESA
WHERE TO_CHAR(FECHA_EJECUTORIA, 'YYYYMM') <= TO_CHAR(ADD_MONTHS(TO_DATE('" . $post['anop'] . "', 'YYYY-MM'), -6), 'YYYYMM') 
AND (L.SALDO_DEUDA = 0) 
HAVING SUM(SALDO_DEUDA) > (616000 * 5)
GROUP BY   DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1'), 
  NUMERO_RESOLUCION, 
  E.CODEMPRESA, 
  E.COD_TIPODOCUMENTO, 
  E.NOMBRE_EMPRESA";
        $consulta = $this->db->query($SQL);
        if (sizeof($consulta->result_array) > 0) {
            $datos = $consulta->result_array;
        } else {
            $datos = $array_select;
        }
        return $datos;
    }

    function bdme_retiro() {
        $post = $this->input->post();
        $array_select = array('0', 'CONCEPTO', 'TIPO DEUDOR', 'NUMERO DE OBLIGACIÓN', 'NRO IDENTIFICACIÓN', 'TIPO IDENTIFICACIÓN', 'NOMBRE O RAZÓN SOCIAL', 'VALOR DE LA OBLIGACIÓN', 'ESTADO DE LA DEUDA');

        $SQL = "SELECT 
  1 AS CONCEPTO, 
  DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1') AS TIPO_DEUDOR, 
  NUMERO_RESOLUCION AS NUMERO_OBLIGACION, 
  E.CODEMPRESA AS NUMERO_IDENTIFICACION, 
  E.COD_TIPODOCUMENTO AS TIPO_IDENTIFICACION, 
  E.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
  SUM(SALDO_DEUDA) AS VALOR_OBLIGACION, 
  1 AS ESTADO_DEUDA
FROM SENA_CAPACITACION.LIQUIDACION L 
INNER JOIN SENA_CAPACITACION.RESOLUCION R ON R.NUM_LIQUIDACION = L.NUM_LIQUIDACION
INNER JOIN SENA_CAPACITACION.VW_PERSONAS E ON E.CODEMPRESA = L.NITEMPRESA
WHERE TO_CHAR(FECHA_EJECUTORIA, 'YYYYMM') <= TO_CHAR(ADD_MONTHS(TO_DATE('" . $post['anop'] . "', 'YYYY-MM'), -6), 'YYYYMM') 
HAVING SUM(SALDO_DEUDA) > (616000 * 5) 
AND (SUM(L.SALDO_DEUDA) BETWEEN 1 AND  (616000 * 5)) 
GROUP BY   DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1'), 
  NUMERO_RESOLUCION, 
  E.CODEMPRESA, 
  E.COD_TIPODOCUMENTO, 
  E.NOMBRE_EMPRESA";
        $consulta = $this->db->query($SQL);
        if (sizeof($consulta->result_array) > 0) {
            $datos = $consulta->result_array;
        } else {
            $datos = $array_select;
        }
        return $datos;
    }

    function bdme_actualizacion() {
        $post = $this->input->post();
        $array_select = array('0', 'CONCEPTO', 'TIPO DEUDOR', 'NUMERO DE OBLIGACIÓN', 'NRO IDENTIFICACIÓN', 'TIPO IDENTIFICACIÓN', 'NOMBRE O RAZÓN SOCIAL', 'VALOR DE LA OBLIGACIÓN', 'ESTADO DE LA DEUDA');

        $SQL = "SELECT 
  1 AS CONCEPTO, 
  DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1') AS TIPO_DEUDOR, 
  NUMERO_RESOLUCION AS NUMERO_OBLIGACION, 
  E.CODEMPRESA AS NUMERO_IDENTIFICACION, 
  E.COD_TIPODOCUMENTO AS TIPO_IDENTIFICACION, 
  E.NOMBRE_EMPRESA AS RAZON_SOCIAL, 
  SUM(SALDO_DEUDA) AS VALOR_OBLIGACION, 
  1 AS ESTADO_DEUDA
FROM SENA_CAPACITACION.LIQUIDACION L 
INNER JOIN SENA_CAPACITACION.RESOLUCION R ON R.NUM_LIQUIDACION = L.NUM_LIQUIDACION
INNER JOIN SENA_CAPACITACION.VW_PERSONAS E ON E.CODEMPRESA = L.NITEMPRESA
WHERE TO_CHAR(FECHA_EJECUTORIA, 'YYYYMM') <= TO_CHAR(ADD_MONTHS(TO_DATE('" . $post['anop'] . "', 'YYYY-MM'), -6), 'YYYYMM') 
    AND 1 = 2 
HAVING SUM(SALDO_DEUDA) > (616000 * 5) 
AND (SUM(L.SALDO_DEUDA) BETWEEN 1 AND  (616000 * 5)) 
GROUP BY   DECODE( E.COD_TIPODOCUMENTO, '1', '2', '1'), 
  NUMERO_RESOLUCION, 
  E.CODEMPRESA, 
  E.COD_TIPODOCUMENTO, 
  E.NOMBRE_EMPRESA ";
        $consulta = $this->db->query($SQL);
        if (sizeof($consulta->result_array) > 0) {
            $datos = $consulta->result_array;
        } else {
            $datos = $array_select;
        }
        return $datos;
    }

    function kactus_reporte() {
        $post = $this->input->post();

        $array_select = array('0', 'NOMBRE REGIONAL', 'NOMBRE CONCEPTO', 'RAZÓN SOCIAL', 'FECHA VISITA', 'NRO VISITAS', 'FISCALIZADOR', 'COMENTARIOS');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }

        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('CNM_EMPLEADO.IDENTIFICACION', $cedula[0]);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }

        $this->db->select("CNM_CUOTAS.ID_DEUDA_E AS NUMERO_DE_OBLIGACION, CNM_CUOTAS.NO_CUOTA AS NRO_CUOTA, 
            TIPOCARTERA.NOMBRE_CARTERA,CNM_CUOTAS.VALOR_CUOTA AS VALOR_CUOTA, 
CNM_CARTERANOMISIONAL.COD_EMPLEADO AS COD_EMPLEADO, CCK.VALOR_PAGADO AS VALOR_DESCUENTO, CCK.FECHA_PAGO AS FECHA_DESCUENTO,REGIONAL.NOMBRE_REGIONAL AS REGIONAL");
//        $this->db->join("CNM_CARTERANOMISIONAL", "CNM_CUOTAS.ID_DEUDA_E=CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL");
        $this->db->join("CNM_CUOTAS_KACTUS CCK", "CCK.ID_DEUDA_K=CNM_CUOTAS.ID_DEUDA_E AND CCK.NO_CUOTA=CNM_CUOTAS.NO_CUOTA AND CCK.CONCEPTO =CNM_CUOTAS.CONCEPTO");
        $this->db->join("REGIONAL", "CCK.COD_REGIONAL=REGIONAL.COD_REGIONAL");
        $this->db->join("CNM_CARTERANOMISIONAL", "CNM_CUOTAS.ID_DEUDA_E=CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL ");
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $datos = $this->db->get('CNM_CUOTAS');
        $new_array = $datos->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function autocompleteempleado($empleado) {
        $empleado = strtoupper($empleado);
        $this->db->select('IDENTIFICACION AS IDUSUARIO, NOMBRES, APELLIDOS');
        $this->db->where("(IDENTIFICACION like '%" . $empleado . "%' or NOMBRES LIKE '%" . $empleado . "%') AND 1=", 1, FALSE);
//            $this->db->like_or('NOMBRES', $empleado);
        $datos = $this->db->get('CNM_EMPLEADO');
        $datos = $datos->result_array();
        return $datos;
    }

    function comparativo_con_periodo($anos, $whe) {
        $this->ayuda();
        $array_select = array('0', 'NOMBRE REGIONAL', 'RAZÓN SOCIAL', 'NIT', 'DIGITO VERIFICACION', 'CONCEPTO', 'CATEGORIA', 'NÚMERO TRABAJADORES');

        $SQL = "SELECT * FROM (
   SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,EMPRESA.NOMBRE_EMPRESA,T.NITEmpresa,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION, 
   TO_CHAR(T.Fecha_Pago, 'YYYY') AS Pago,TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA, 
   EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES,  Valor_pagado
   FROM SENA.PagosRecibidos T
	 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
         JOIN REGIONAL on REGIONAL.COD_REGIONAL=T.COD_REGIONAL
        LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
         join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
         join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
where " . $whe . ")PIVOT( SUM(Valor_Pagado) FOR Pago IN (" . $anos . ") )ORDER BY NITEmpresa";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function comparativo_con_periodo_grupal($anos, $whe) {
        $this->ayuda();
        $array_select = array('0', 'REGIONAL', 'FECHA PAGO', 'NUMERO DE PAGOS', 'CONCEPTO', 'VALOR PAGADO');

        $SQL = "
   SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL, TO_CHAR(T.Fecha_Pago, 'YYYY') AS Vigencia,COUNT(TIPOCONCEPTO.NOMBRE_TIPO) NUMERO_DE_PAGOS,
   TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,SUM(EMPRESA.NUM_EMPLEADOS) NUMERO_TRABAJADORES,  sum(Valor_pagado) Valor_pagado
   FROM SENA.PagosRecibidos T
	 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
         JOIN REGIONAL on REGIONAL.COD_REGIONAL=T.COD_REGIONAL
         left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
        
         join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
where " . $whe . " GROUP BY  REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,TO_CHAR(T.Fecha_Pago, 'YYYY'),TIPOCONCEPTO.NOMBRE_TIPO order by TO_CHAR(T.Fecha_Pago, 'YYYY')";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function comparativo_con_periodo_cantidad($anos, $whe) {
        $this->ayuda();
//echo $whe;


        $SQL = "SELECT * FROM (
   SELECT TO_CHAR(T.Fecha_Pago, 'YYYY') AS Pago, Valor_pagado
   FROM SENA.PagosRecibidos T
	 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
         JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
         left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
        
         join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
where " . $whe . ")PIVOT( SUM(Valor_Pagado) FOR Pago IN (" . $anos . ") )";

        $consulta = $this->db->query($SQL);

        return $consulta->result_array;
    }

    function control_cargue($anos, $whe) {
        $this->ayuda();
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'FECHA PAGO', 'PERÃ�ODO PAGADO',
            'COD OPERADOR', 'PLANILLA', 'FORMA_PAGO', 'CONCEPTO', 'CATEGORIA', 'ASOBANCARIA', 'MANUAL', 'ECOLLECT');
        $SQL = "SELECT *
FROM (
  SELECT REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,T.NITEmpresa as NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION,EMPRESA.NOMBRE_EMPRESA,
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA,FORMAPAGO.NOMBE_FORMAPAGO FORMA_PAGO,
TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,
 Procedencia, Valor_Pagado
  FROM PAGOSRECIBIDOS T
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
 LEFT join FORMAPAGO on FORMAPAGO.COD_FORMAPAGO=T.COD_FORMAPAGO
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 left join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
where " . $whe . "
)
PIVOT(
  SUM (Valor_Pagado)
  FOR Procedencia IN ('ASOBANCARIA', 'MANUAL','ECOLLECT')
)";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function control_cargue_grupal($anos, $whe) {
        $this->ayuda();
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'FUENTE', 'VALOR PAGADO', 'CANTIDAD DE PAGOS');
        $SQL = "
  SELECT REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,T.PROCEDENCIA FUENTE,
  sum(Valor_Pagado) Valor_Pagado, count(Valor_Pagado) CANTIDAD_PAGOS
  FROM PAGOSRECIBIDOS T
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
where " . $whe . "
GROUP BY REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, T.PROCEDENCIA
";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function control_cargue_cantidad($anos, $whe) {
        $this->ayuda();
        $SQL = "SELECT *
FROM (
  SELECT Procedencia,Valor_Pagado
  FROM PAGOSRECIBIDOS T
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA

 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
where " . $whe . "
)
PIVOT(
  sum(Valor_Pagado)
  FOR Procedencia IN ('ASOBANCARIA', 'MANUAL','ECOLLECT')
)";

        $consulta = $this->db->query($SQL);

        return $consulta->result_array;
    }

    function control_cargue_cantidad_valor($anos, $whe) {
        $this->ayuda();

        $SQL = "SELECT *
FROM (
  SELECT Procedencia, t.Valor_Pagado
  FROM PAGOSRECIBIDOS T
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA

LEFT join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
where " . $whe . "
)
PIVOT(
  SUM (Valor_Pagado)
  FOR Procedencia IN ('ASOBANCARIA', 'MANUAL','ECOLLECT')
)";

        $consulta = $this->db->query($SQL);

        return $consulta->result_array;
    }

    function ingreso_fuentes_aportantes($anos, $whe) {
        $this->ayuda();

        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'CATEGORIA', 'FECHA PAGO',
            'PERÃ�ODO PAGADO', 'COD OPERADOR', 'PLANILLA', 'FUENTE', 'VALOR PAGADO', 'NÃšMERO TRABAJADORES', 'FORMA PAGO');

        $SQL = "SELECT REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,T.NITEmpresa as NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION,
            EMPRESA.NOMBRE_EMPRESA, TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO, CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA, Procedencia FUENTE, 
Valor_Pagado,EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES,FORMAPAGO.NOMBE_FORMAPAGO FORMA_PAGO
FROM PAGOSRECIBIDOS T 
join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
 LEFT join FORMAPAGO on FORMAPAGO.COD_FORMAPAGO=T.COD_FORMAPAGO
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
where " . $whe;

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function operadores() {
        $this->ayuda();
        $operador = " ";
        $SQL = "SELECT COD_OPERADOR ,ENTIDAD_RECAUDO from TIPO_OPERADOR_PILA";
        $consulta = $this->db->query($SQL);
        foreach ($consulta->result_array as $consulta) {
            $operador.="'" . $consulta['ENTIDAD_RECAUDO'] . "',";
        }
        $operador = substr($operador, 0, -1);
        return $operador;
    }

    function operadore() {
        $this->ayuda();
        $operador = " ";
        $SQL = "SELECT COD_OPERADOR,ENTIDAD_RECAUDO from TIPO_OPERADOR_PILA";
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function ingreso_por_operador($anos, $whe, $post, $operador) {
        $this->ayuda();
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'NOMBRE BANCO', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'FECHA PAGO',
            'PERÃ�ODO PAGADO', 'COD OPERADOR', 'NOMBRE OPERADOR', 'PROCEDENCIA', 'CONCEPTO', 'CATEGORIA', 'VALOR PAGADO');
        $SQL = "
  SELECT REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,BANCO.NOMBREBANCO NOMBRE_BANCO,T.NITEmpresa as NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION,
  EMPRESA.NOMBRE_EMPRESA,
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,TIPO_OPERADOR_PILA.ENTIDAD_RECAUDO NOMBRE_OPERADOR,Procedencia, TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,
 Valor_Pagado
  FROM PAGOSRECIBIDOS T
 left JOIN BANCO ON BANCO.IDBANCO=T.COD_ENTIDAD
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
left join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
left join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
join TIPO_OPERADOR_PILA on TIPO_OPERADOR_PILA.COD_OPERADOR=ASOBANCARIA_DET.COD_OPERADOR
where " . $whe . "
";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingreso_por_operador_grupal($anos, $whe, $post, $operador) {
        $this->ayuda();
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'COD OPERADOR', 'NOMBRE_OPERADOR', 'VALOR PAGADO');
        $SQL = "SELECT REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, ASOBANCARIA_DET.COD_OPERADOR,TIPO_OPERADOR_PILA.ENTIDAD_RECAUDO NOMBRE_OPERADOR, SUM(Valor_Pagado) Valor_Pagado
  FROM PAGOSRECIBIDOS T
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
 left join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 join TIPO_OPERADOR_PILA on TIPO_OPERADOR_PILA.COD_OPERADOR=ASOBANCARIA_DET.COD_OPERADOR
where " . $whe . "
 and ASOBANCARIA_DET.COD_OPERADOR IS NOT NULL
 GROUP BY REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, ASOBANCARIA_DET.COD_OPERADOR,TIPO_OPERADOR_PILA.ENTIDAD_RECAUDO
";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingreso_por_operador_cantidad($anos, $whe, $post, $operador) {
        $this->ayuda();
        $SQL = "
  SELECT ASOBANCARIA_DET.COD_OPERADOR,TIPO_OPERADOR_PILA.ENTIDAD_RECAUDO NOMBRE_OPERADOR,
 Procedencia, SUM(Valor_Pagado) Valor_Pagado
  FROM PAGOSRECIBIDOS T
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
 JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA
 left join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 join TIPO_OPERADOR_PILA on TIPO_OPERADOR_PILA.COD_OPERADOR=ASOBANCARIA_DET.COD_OPERADOR
where " . $whe . "
    GROUP BY ASOBANCARIA_DET.COD_OPERADOR, TIPO_OPERADOR_PILA.ENTIDAD_RECAUDO,Procedencia
";
        $consulta = $this->db->query($SQL);
        return $consulta->result_array;
    }

    function relacion_aportantes_por_rango($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'CONCEPTO', 'CATEGORIA', 'FECHA PAGO',
            'PERÃ�ODO PAGADO', 'COD OPERADOR', 'PLANILLA', 'PROCEDENCIA', 'VALOR PAGADO', 'NRO TRABAJADORES PERÃ�ODO');
        $SQL = "SELECT *
FROM (
SELECT REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,T.NITEmpresa as NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION,EMPRESA.NOMBRE_EMPRESA,  
TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA, Procedencia, 
Valor_Pagado,EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES
FROM PAGOSRECIBIDOS T 
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 left join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
where " . $whe . "
ORDER BY Valor_Pagado " . $post['ordenar'] . ")
WHERE RowNum <= " . $post['num_registros'];
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_por_banco_grupal($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'REGIONAL', 'CIUDAD', 'BANCO', 'PROCEDENCIA', 'VALOR PAGADO');

        $SQL = "SELECT BANCO.NOMBREBANCO AS BANCO,Sum(Valor_Pagado) Valor_Pagado
FROM PAGOSRECIBIDOS T 
 join BANCO ON BANCO.IDBANCO=T.COD_ENTIDAD
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
where " . $whe . " group by BANCO.NOMBREBANCO";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_por_banco_consolidado($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'BANCO', 'PROCEDENCIA', 'VALOR PAGADO', 'NUMERO_CUENTA');

        $SQL = "SELECT 
            BANCO.NOMBREBANCO AS BANCO,MAESTRO_CUENTAS_CONCEPTOS.NUMERO_CUENTA,T.Procedencia,Sum(T.Valor_Pagado) Valor_Pagado
FROM PAGOSRECIBIDOS T 
join MAESTRO_CUENTAS_CONCEPTOS on MAESTRO_CUENTAS_CONCEPTOS.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
join MAESTRO_CUENTAS_BANCARIAS on MAESTRO_CUENTAS_BANCARIAS.IDBANCO=T.COD_ENTIDAD
and MAESTRO_CUENTAS_BANCARIAS.NUMERO_CUENTA=MAESTRO_CUENTAS_CONCEPTOS.NUMERO_CUENTA
JOIN BANCO ON BANCO.IDBANCO=T.COD_ENTIDAD
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
where " . $whe . " group by BANCO.NOMBREBANCO,MAESTRO_CUENTAS_CONCEPTOS.NUMERO_CUENTA,T.Procedencia";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_por_banco($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'COD_REGIONAL', 'REGIONAL', 'CIUDAD', 'BANCO', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'FECHA PAGO',
            'PERÃ�ODO PAGADO', 'COD OPERADOR', 'PLANILLA', 'CONCEPTO', 'CATEGORIA', 'PROCEDENCIA', 'VALOR PAGADO', 'NÃšMERO TRABAJADORES',
            'TIPO DE EMPRESA', 'NUMERO_CUENTA');

        $SQL = "SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL as REGIONAL,MUNICIPIO.NOMBREMUNICIPIO,BANCO.NOMBREBANCO AS BANCO,
             T.NITEmpresa as NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION,
            EMPRESA.NOMBRE_EMPRESA, 
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA,
TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,
T.Procedencia, 
Valor_Pagado,EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES,TIPOENTIDAD.NOM_TIPO_ENT as Tipo_de_Empresa,MAESTRO_CUENTAS_CONCEPTOS.NUMERO_CUENTA
FROM PAGOSRECIBIDOS T 
JOIN BANCO ON BANCO.IDBANCO=T.COD_ENTIDAD
 join MAESTRO_CUENTAS_CONCEPTOS on MAESTRO_CUENTAS_CONCEPTOS.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
 join MAESTRO_CUENTAS_BANCARIAS on MAESTRO_CUENTAS_BANCARIAS.IDBANCO=T.COD_ENTIDAD
and MAESTRO_CUENTAS_BANCARIAS.NUMERO_CUENTA=MAESTRO_CUENTAS_CONCEPTOS.NUMERO_CUENTA
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 left join SENA.CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
 
where " . $whe;
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_por_regional($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'COD_REGIONAL', 'REGIONAL', 'CIUDAD', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'FECHA PAGO', 'PERÃ�ODO PAGADO', 'COD OPERADOR',
            'PLANILLA', 'CONCEPTO', 'CATEGORIA', 'PROCEDENCIA', 'VALOR PAGADO', 'NÃšMERO TRABAJADORES', 'TIPO DE EMPRESA');

        $SQL = "SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL as REGIONAL,MUNICIPIO.NOMBREMUNICIPIO,T.NITEmpresa as NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA, 
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA,
TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,Procedencia, 
Valor_Pagado,EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES,TIPOENTIDAD.NOM_TIPO_ENT as Tipo_de_Empresa
FROM PAGOSRECIBIDOS T 
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=T.COD_REGIONAL 
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
 left join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
where " . $whe;
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_control_cargue_pila($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'ARCHIVO', 'VALOR_TOTAL_ARCHIVO', 'NRO_REGISTROS', 'FECHA_CREACION', 'USUARIO');

        $SQL = "SELECT 
PLANILLAUNICA_ENC.ARCHIVO, 
sum(PLANILLAUNICA_DET.APORTE_OBLIG) VALOR_TOTAL_ARCHIVO,
count(PLANILLAUNICA_DET.APORTE_OBLIG) NRO_REGISTROS,
max(PLANILLAUNICA_ENC.FECHA_CREACION) FECHA_CREACION, USUARIOS.NOMBRES || '' || USUARIOS.APELLIDOS USUARIO
FROM PLANILLAUNICA_ENC
INNER JOIN PAGOSRECIBIDOS T ON T.PERIODO_PAGADO=PLANILLAUNICA_ENC.PERIDO_PAGO 
AND PLANILLAUNICA_ENC.N_INDENT_APORTANTE=T.NITEMPRESA
LEFT JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
LEFT join REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
join PLANILLAUNICA_DET on PLANILLAUNICA_DET.COD_PLANILLAUNICA=PLANILLAUNICA_ENC.COD_PLANILLAUNICA
join USUARIOS on USUARIOS.IDUSUARIO=PLANILLAUNICA_ENC.ID_USUARIO
where " . $whe . " 
GROUP BY PLANILLAUNICA_ENC.ARCHIVO,USUARIOS.NOMBRES || '' || USUARIOS.APELLIDOS";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_tipo_planilla_grupal($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'ARCHIVO', 'VALOR_TOTAL_ARCHIVO', 'NRO_REGISTROS', 'FECHA_CREACION', 'USUARIO');

        $SQL = "SELECT 
PLANILLAUNICA_ENC.ARCHIVO, 
sum(PLANILLAUNICA_DET.APORTE_OBLIG) VALOR_TOTAL_ARCHIVO,
count(PLANILLAUNICA_DET.APORTE_OBLIG) NRO_REGISTROS,
max(PLANILLAUNICA_ENC.FECHA_CREACION) FECHA_CREACION, USUARIOS.NOMBRES || '' || USUARIOS.APELLIDOS USUARIO,
DECODE(MODALID_PLANILLA,1,'electrÃ³nica','Asistida') TIPO_PLANILLA
FROM PAGOSRECIBIDOS T
left JOIN PLANILLAUNICA_ENC ON T.PERIODO_PAGADO=PLANILLAUNICA_ENC.PERIDO_PAGO 
AND PLANILLAUNICA_ENC.N_INDENT_APORTANTE=T.NITEMPRESA
LEFT JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
LEFT join REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
join PLANILLAUNICA_DET on PLANILLAUNICA_DET.COD_PLANILLAUNICA=PLANILLAUNICA_ENC.COD_PLANILLAUNICA
join USUARIOS on USUARIOS.IDUSUARIO=PLANILLAUNICA_ENC.ID_USUARIO
where " . $whe . " 
GROUP BY PLANILLAUNICA_ENC.ARCHIVO,USUARIOS.NOMBRES || '' || USUARIOS.APELLIDOS,PLANILLAUNICA_ENC.MODALID_PLANILLA";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_tipo_planilla($anos, $whe, $post) {
        $this->ayuda();
        $array_select = array('0', 'ARCHIVO', 'VALOR_TOTAL_ARCHIVO', 'NRO_REGISTROS', 'FECHA_CREACION', 'USUARIO');

        $SQL = "SELECT PLANILLAUNICA_ENC.ARCHIVO,  PLANILLAUNICA_DET.APORTE_OBLIG,PLANILLAUNICA_DET.TIPO_IDENT_COTIZ COD_COTIZANTE,PLANILLAUNICA_DET.N_IDENT_COTIZ,PLANILLAUNICA_DET.PRIMER_APELLIDO,PLANILLAUNICA_DET.PRIMER_NOMBRE,
PLANILLAUNICA_ENC.FECHA_CREACION,
DECODE(PLANILLAUNICA_ENC.MODALID_PLANILLA,1,'electrÃ³nica','Asistida') TIPO_PLANILLA
FROM PAGOSRECIBIDOS T
left JOIN PLANILLAUNICA_ENC ON T.PERIODO_PAGADO=PLANILLAUNICA_ENC.PERIDO_PAGO 
AND PLANILLAUNICA_ENC.N_INDENT_APORTANTE=T.NITEMPRESA
LEFT JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA
LEFT join REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL
join PLANILLAUNICA_DET on PLANILLAUNICA_DET.COD_PLANILLAUNICA=PLANILLAUNICA_ENC.COD_PLANILLAUNICA
join USUARIOS on USUARIOS.IDUSUARIO=PLANILLAUNICA_ENC.ID_USUARIO
where " . $whe . " ";

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_por_tipo_empresa($anos, $whe, $post) {
        $this->ayuda();

        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL',
            'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO', 'FAX', 'CONCEPTO', 'CATEGORIA', 'FECHA PAGO', 'PERÃ�ODO PAGADO', 'COD OPERADOR',
            'PLANILLA', 'PROCEDENCIA', 'NÃšMERO_TRABAJADORES', 'TIPO DE EMPRESA');

        $SQL = "SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,
                MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU,
T.NITEmpresa NIT,Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL, EMPRESA.DIRECCION,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.EMAILAUTORIZADO E_MAIL,
EMPRESA.TELEFONO_FIJO TELEFONO,EMPRESA.FAX FAX,TIPOCONCEPTO.NOMBRE_TIPO CONCEPTO,CONCEPTORECAUDO.NOMBRE_CONCEPTO CATEGORIA,
            
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA, Procedencia, 
EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES,TIPOENTIDAD.NOM_TIPO_ENT as Tipo_de_Empresa
FROM PAGOSRECIBIDOS T 
 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 join DEPARTAMENTO on DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN CIIU on CIIU.CLASE=EMPRESA.CIIU
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=T.COD_REGIONAL 
 left JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 left join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
where " . $whe;

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ingresos_por_tipo_REGISTRO_0($anos, $whe, $post) {
        $this->ayuda();

        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL',
            'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO', 'FAX', 'CONCEPTO', 'CATEGORIA', 'FECHA PAGO', 'PERÃ�ODO PAGADO', 'COD OPERADOR',
            'PLANILLA', 'PROCEDENCIA', 'NÃšMERO_TRABAJADORES', 'TIPO DE EMPRESA');

        $SQL = "SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,
MUNICIPIO.NOMBREMUNICIPIO CIUDAD,CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU, 
REGISTROTIPOCERO.N_IDEN_APORTANTE NIT, REGISTROTIPOCERO.DIG_VERIFCACION DIGITO_VERIFICACION,
REGISTROTIPOCERO.NOM_APORTANTE RAZON_SOCIAL,REGISTROTIPOCERO.DIR_CORRESPON DIRECCION,
REGISTROTIPOCERO.N_IDEN_REPRE IDENTIFICACION_REPRESENTANTE,PRIMER_APELL PRIMER_APELLIDO, SEGUNDO_APELL SEGUNDO_APELLIDO, 
PRIMER_NOMBRE PRIMER_NOMBRE, SEGUNDO_NOMBRE,REGISTROTIPOCERO.CORREO,REGISTROTIPOCERO.TELEFONO,REGISTROTIPOCERO.FAX,
REGISTROTIPOCERO.FECHA_CREACION,REGISTROTIPOCERO.PERIODO_PAGO,REGISTROTIPOCERO.COD_OPERADOR,EMPRESA.NUM_EMPLEADOS NUMERO_TRABAJADORES
from REGISTROTIPOCERO
join PAGOSRECIBIDOS T on T.NITEMPRESA=REGISTROTIPOCERO.N_IDEN_APORTANTE AND T.PERIODO_PAGADO=REGISTROTIPOCERO.PERIODO_PAGO
left join EMPRESA on EMPRESA.CODEMPRESA=REGISTROTIPOCERO.N_IDEN_APORTANTE
left JOIN CIIU on CIIU.CLASE=EMPRESA.CIIU
JOIN REGIONAL ON REGIONAL.COD_CIUDAD=REGISTROTIPOCERO.COD_CIUDAD 
AND REGIONAL.COD_DEPARTAMENTO=REGISTROTIPOCERO.COD_DEPARTA
JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO=REGISTROTIPOCERO.COD_DEPARTA
JOIN MUNICIPIO ON MUNICIPIO.CODMUNICIPIO=REGISTROTIPOCERO.COD_CIUDAD and MUNICIPIO.COD_DEPARTAMENTO=REGISTROTIPOCERO.COD_DEPARTA
AND REGISTROTIPOCERO.COD_DEPARTA=MUNICIPIO.COD_DEPARTAMENTO
where " . $whe;

        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

//    function ingresos_por_tipo_empresa($anos, $whe, $post) {
//        $this->ayuda();
//        
//        $array_select = array('0', 'REGIONAL', 'CIUDAD', 'NIT', 'RAZÃ“N SOCIAL', 'FECHA PAGO', 'PERÃ�ODO PAGADO', 'COD OPERADOR',
//            'PLANILLA', 'PROCEDENCIA', 'VALOR PAGADO', 'NRO TRABAJADORES PERÃ�ODO', 'TIPO DE EMPRESA');
//
//        $SQL = "SELECT REGIONAL.NOMBRE_REGIONAL as REGIONAL,MUNICIPIO.NOMBREMUNICIPIO,T.NITEmpresa as NIT,EMPRESA.NOMBRE_EMPRESA, 
//T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA, Procedencia, 
//Valor_Pagado,EMPRESA.NUM_EMPLEADOS NRO_TRABAJADORES_PERIODO,TIPOENTIDAD.NOM_TIPO_ENT as Tipo_de_Empresa
//FROM PAGOSRECIBIDOS T 
//LEFT JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
//LEFT JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
//LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
//
//LEFT JOIN CONCEPTOSFISCALIZACION on CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=T.COD_CONCEPTO 
//LEFT JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
//LEFT JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
//where " . $whe;
//        
//        $consulta = $this->db->query($SQL);
//        $datos = $consulta->result_array;
//        if (count($datos) == 0) {
//            $datos = $array_select;
//        }
//        return $datos;
//    }

    function ingresos_por_tipo_empresa2($anos, $whe, $post) {
        $this->ayuda();
        $select = " REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL as REGIONAL,MUNICIPIO.NOMBREMUNICIPIO,";
        if (isset($post['empresa_destino'])) {
            for ($i = 0; $i < count($post['empresa_destino']); $i++) {
                $select.=$post['empresa_destino'][$i] . ",";
            }
        }
        if (isset($post['pagos_destino'])) {
            for ($i = 0; $i < count($post['pagos_destino']); $i++) {
                $select.=$post['pagos_destino'][$i] . ",";
            }
        }
        if (isset($post['asobancariae_destino'])) {
            for ($i = 0; $i < count($post['asobancariae_destino']); $i++) {
                $select.=$post['asobancariae_destino'][$i] . ",";
            }
        }
        if (isset($post['asobancariad_destino'])) {
            for ($i = 0; $i < count($post['asobancariad_destino']); $i++) {
                $select.=$post['asobancariad_destino'][$i] . ",";
            }
        }

        $select = substr($select, 0, -1);

        $select2 = explode(',', $select);
        for ($i = 0; $i < count($select2); $i++) {
            $select3 = explode('.', $select2[$i]);
            $select3 = explode('as', $select3[1]);
            $select3[0] = Reporteador::reemplazar($select3[0]);
            $select4[] = $select3[0];
        }
        $array_select = array_merge(array('0'), $select4);

        $SQL = "SELECT " . $select . "
FROM PAGOSRECIBIDOS T  
 JOIN EMPRESA on EMPRESA.CODEMPRESA = T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL = EMPRESA.COD_REGIONAL 
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO = T.COD_CONCEPTO
 join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO = T.COD_SUBCONCEPTO
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO = EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
 LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA  
 left JOIN BANCO ON BANCO.IDBANCO=T.COD_ENTIDAD
where " . $whe;
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        echo "<input type='hidden' id='consultar_ya' name='consultar_ya' value='".base64_encode($SQL)."'>";
        return $datos;
    }

    function bancos() {
        $this->db->select("IDBANCO,NOMBREBANCO");
        $dato = $this->db->get("BANCO");
        return $dato->result_array;
    }

    function ingresos_por_empresa_smlv($anos, $whe, $post, $where2) {
        if ($post['valor_salario'] != "") {
            $whe.=" and REGISTROTIPO3.INGRESO " . $post['salario_enc'] . " " . $post['valor_salario'];
        }
        $this->ayuda();
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'CIUDAD', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'FECHA PAGO', 'PERÃ�ODO PAGADO', 'COD OPERADOR',
            'PLANILLA', 'PROCEDENCIA', 'VALOR PAGADO', 'NRO TRABAJADORES PERÃ�ODO', 'TIPO DE EMPRESA', 'IBC', 'VALOR_TOTAL_NOMINA');

        $SQL = "SELECT REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL as REGIONAL,MUNICIPIO.NOMBREMUNICIPIO CIUDAD,T.NITEmpresa as NIT,
              Utilidades.NIT_dv(T.NITEmpresa) DIGITO_VERIFICACION,EMPRESA.NOMBRE_EMPRESA, 
T.FECHA_PAGO,T.PERIODO_PAGADO,ASOBANCARIA_DET.COD_OPERADOR,ASOBANCARIA_DET.COD_DETALLE AS PLANILLA, T.Procedencia, 
Valor_Pagado,EMPRESA.NUM_EMPLEADOS NRO_TRABAJADORES,TIPOENTIDAD.NOM_TIPO_ENT as Tipo_de_Empresa,REGISTROTIPO3.INGRESO VALOR_TOTAL_NOMINA
FROM PAGOSRECIBIDOS T 

join PLANILLAUNICA_ENC on T.PERIODO_PAGADO=PLANILLAUNICA_ENC.PERIDO_PAGO and T.NITEMPRESA=PLANILLAUNICA_ENC.N_INDENT_APORTANTE 
and T.COD_PLANILLAUNICA=PLANILLAUNICA_ENC.COD_PLANILLAUNICA
join REGISTROTIPO3 on  PLANILLAUNICA_ENC.COD_PLANILLAUNICA=REGISTROTIPO3.COD_CAMPO

 JOIN EMPRESA on EMPRESA.CODEMPRESA=T.NITEMPRESA 
 JOIN REGIONAL on REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL 
LEFT JOIN ASOBANCARIA_DET ON ASOBANCARIA_DET.COD_DETALLE=T.COD_PROCEDENCIA 
 JOIN CONCEPTOSFISCALIZACION on CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=T.COD_CONCEPTO 
 JOIN MUNICIPIO on MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND MUNICIPIO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO
 JOIN TIPOENTIDAD on EMPRESA.COD_TIPOENTIDAD=TIPOENTIDAD.CODTIPOENTIDAD
 join TIPOCONCEPTO on TIPOCONCEPTO.COD_TIPOCONCEPTO=T.COD_CONCEPTO
 left join CONCEPTORECAUDO on CONCEPTORECAUDO.COD_CONCEPTO_RECAUDO=T.COD_SUBCONCEPTO
where " . $whe;
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ayuda() {
        $SQL = "ALTER SESSION SET NLS_SORT=BINARY";
        $this->db->query($SQL);
    }

    function salario($post) {
        $SQL = "SELECT SALARIO_MINIMO FROM HISTORICOSALARIOMINIMO_UVT WHERE ANNO='" . $post['ano_salario'] . "'";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        return $datos[0]['SALARIO_MINIMO'];
    }

    function cartera_x_aportante() {
        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD_REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL',
            'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO', 'FAX', 'CONCEPTO', 'NÃšMERO RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA',
            'PROCESO', 'INSTANCIA', 'VALOR INICIAL', 'PAGOS EFECTUADOS', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO ACTUAL DEUDA');

        $this->db->select("regional.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,
            MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU,
EMPRESA.CODEMPRESA NIT,Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL, EMPRESA.DIRECCION,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.EMAILAUTORIZADO E_MAIL,
EMPRESA.TELEFONO_FIJO TELEFONO,EMPRESA.FAX FAX,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO CONCEPTO,RESOLUCION.NUMERO_RESOLUCION,  
LIQUIDACION.FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,TIPOPROCESO.TIPO_PROCESO PROCESO,
D.COD_TIPO_INSTANCIA || ' ' || TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA INSTANCIA,
RESOLUCION.VALOR_TOTAL AS VALOR_INICIAL,
B.VALOR_PAGADO PAGOS_EFECTUADOS,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
SALDO_DEUDA SALDO_ACTUAL_DEUDA", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO", FALSE);
        $this->db->join("(SELECT  COD_TIPO_PROCESO,max(COD_TIPO_INSTANCIA) COD_TIPO_INSTANCIA
FROM INSTANCIAS_PROCESOS 
WHERE ESTADO='A'
GROUP BY COD_TIPO_PROCESO) D", "D.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO", FALSE);
        $this->db->join("TIPOS_INSTANCIAS", "TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA=D.COD_TIPO_INSTANCIA");
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
//        $this->db->where("LIQUIDACION.SALDO_DEUDA <>",0,false);
//        $this->db->where("LIQUIDACION.EN_FIRME", 'S');
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_aportante_consolidado() {
        $post = $this->input->post();
        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD_REGIONAL', 'REGIONAL', 'CIIU', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL',
            'CANTIDAD CARTERA', 'CANTIDAD ABONOS',
            'VALOR ABONOS', 'SALDO ACTUAL CARTERA', 'VALOR_INICIAL');

        $this->db->select("regional.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL REGIONAL, 
 CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU, EMPRESA.CODEMPRESA NIT, 
Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL, 
  
COUNT(CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO) CANTIDAD_CARTERA, 
SUM(LIQUIDACION.TOTAL_LIQUIDADO) AS VALOR_INICIAL,
SUM(B.CANTIDAD_ABONOS) CANTIDAD_ABONOS,SUM(B.VALOR_PAGADO) VALOR_ABONOS,SUM(SALDO_DEUDA) SALDO_ACTUAL_CARTERA, SUM(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("(select NRO_REFERENCIA, COUNT(VALOR_PAGADO) CANTIDAD_ABONOS ,SUM(VALOR_PAGADO) VALOR_PAGADO 
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
//        $this->db->where("LIQUIDACION.SALDO_DEUDA <>",0,false);
//        $this->db->where("LIQUIDACION.EN_FIRME", 'S');
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->group_by(array("REGIONAL.COD_REGIONAL", " REGIONAL.NOMBRE_REGIONAL", " CIIU.CLASE || ' - ' || CIIU.DESCRIPCION ", " EMPRESA.CODEMPRESA ", " 
Utilidades.NIT_dv(EMPRESA.CODEMPRESA)", " EMPRESA.NOMBRE_EMPRESA "));
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_prescrita() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'NUM LIQUIDACIÃ“N', 'NOMBRE CONCEPTO', 'PERÃ�ODOS LIQUIDADOS', 'SALDO PENDIENTE CAPITAL',
            'SALDO PENDIENTE INTERESES');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,
            MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU,
EMPRESA.CODEMPRESA NIT,Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL, EMPRESA.DIRECCION,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.EMAILAUTORIZADO E_MAIL,
EMPRESA.TELEFONO_FIJO TELEFONO,EMPRESA.FAX FAX,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO CONCEPTO,RESOLUCION.NUMERO_RESOLUCION,  
LIQUIDACION.FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,TIPOPROCESO.TIPO_PROCESO INSTANCIA,
RESOLUCION.VALOR_TOTAL AS VALOR_CAPITAL_INICIAL,
B.VALOR_PAGADO PAGOS_EFECTUADOS,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
SALDO_DEUDA SALDO_ACTUAL_DEUDA", false);
        $this->db->join("RECEPCIONTITULOS", "RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO");
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
        $this->db->where("RECEPCIONTITULOS.TITULO_PRESCRITO", 1);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_prescrita_consolidado() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'NOMBRE CONCEPTO', 'CANTIDAD CARTERA', 'VALOR INICIAL', 'PAGOS EFECTUADOS', 'SALDO ACTUAL DEUDA');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL, CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,
COUNT(CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO) CANTIDAD_CARTERA, SUM(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL,
sum(B.VALOR_PAGADO) PAGOS_EFECTUADOS, 
sum(SALDO_DEUDA) SALDO_ACTUAL_DEUDA", false);
        $this->db->join("RECEPCIONTITULOS", "RECEPCIONTITULOS.COD_FISCALIZACION_EMPRESA=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO");
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
        $this->db->where("RECEPCIONTITULOS.TITULO_PRESCRITO", 1);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->group_by(array("REGIONAL.COD_REGIONAL", "REGIONAL.NOMBRE_REGIONAL", "CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO"));
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_dificil_recaudo() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'NIT', 'RAZÃ“N SOCIAL', 'NUM LIQUIDACIÃ“N', 'NOMBRE CONCEPTO', 'PERÃ�ODOS LIQUIDADOS', 'SALDO PENDIENTE CAPITAL',
            'SALDO PENDIENTE INTERESES', 'SALDO_ACTUAL');

        $this->db->select("EMPRESA.CODEMPRESA NIT,EMPRESA.NOMBRE_EMPRESA,LIQUIDACION.NUM_LIQUIDACION,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,
LIQUIDACION.FECHA_INICIO || ' A ' || LIQUIDACION.FECHA_FIN AS PERIODOS_LIQUIDADOS,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
SALDO_DEUDA SALDO_ACTUAL", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->where("SYSDATE-LIQUIDACION.FECHA_EJECUTORIA>=180 and 1=", 1, false);
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_gestion() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_EJECUTORIA BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'DIGITO VERIFICACION',
            'RAZÃ“N SOCIAL', 'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO', 'FAX', 'CONCEPTO', 'NÃšMERO RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N',
            'FECHA EJECUTORIA', 'INSTANCIA', 'ABONOS EFECTUADOS', 'CANTIDAD DE ABONOS', 'VALOR INICIAL', 'SALDO PENDIENTE CAPITAL',
            'SALDO PENDIENTE INTERESES', 'SALDO ACTUAL DEUDA', 'RESPONSABLE');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,
CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU,
EMPRESA.CODEMPRESA NIT,Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL, EMPRESA.DIRECCION,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.EMAILAUTORIZADO E_MAIL,
EMPRESA.TELEFONO_FIJO TELEFONO,EMPRESA.FAX FAX,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO CONCEPTO,RESOLUCION.NUMERO_RESOLUCION,  
LIQUIDACION.FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,TIPOPROCESO.TIPO_PROCESO INSTANCIA,
B.VALOR_PAGADO ABONOS_EFECTUADOS,B.CANTIDAD CANTIDAD_DE_ABONOS,
RESOLUCION.VALOR_TOTAL AS VALOR_INICIAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
SALDO_DEUDA SALDO_ACTUAL_DEUDA,
concat(USUARIOS.APELLIDOS, concat(' ', USUARIOS.NOMBRES)) as RESPONSABLE", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("USUARIOS", "USUARIOS.IDUSUARIO=RESOLUCION.ABOGADO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO", FALSE);
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO,COUNT(VALOR_PAGADO) CANTIDAD
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
        $this->db->where("LIQUIDACION.SALDO_DEUDA >", 0, false);
        $this->db->where("SYSDATE-LIQUIDACION.FECHA_EJECUTORIA >", 90, FALSE);
        $this->db->where("LIQUIDACION.COD_TIPOPROCESO", 6, FALSE);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_abono() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_EJECUTORIA BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'CIIU', 'NIT', 'DIGITO VERIFICACION', 'RAZÃ“N SOCIAL', 'DIRECCIÃ“N', 'REPRESENTANTE LEGAL', 'E MAIL', 'TELEFONO',
            'FAX', 'CONCEPTO', 'NÃšMERO RESOLUCIÃ“N', 'FECHA RESOLUCIÃ“N', 'FECHA EJECUTORIA', 'INSTANCIA', 'ABONOS EFECTUADOS', 'CANTIDAD DE ABONOS', 'VALOR INICIAL', 'PAGOS EFECTUADOS', 'SALDO PENDIENTE CAPITAL',
            'SALDO PENDIENTE INTERESES', 'SALDO ACTUAL DEUDA');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,
CIIU.CLASE || ' - ' || CIIU.DESCRIPCION CIIU,
EMPRESA.CODEMPRESA NIT,Utilidades.NIT_dv(EMPRESA.CODEMPRESA) DIGITO_VERIFICACION, EMPRESA.NOMBRE_EMPRESA RAZON_SOCIAL, EMPRESA.DIRECCION,EMPRESA.REPRESENTANTE_LEGAL,EMPRESA.EMAILAUTORIZADO E_MAIL,
EMPRESA.TELEFONO_FIJO TELEFONO,EMPRESA.FAX FAX,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO CONCEPTO,RESOLUCION.NUMERO_RESOLUCION,  
LIQUIDACION.FECHA_RESOLUCION,LIQUIDACION.FECHA_EJECUTORIA,TIPOPROCESO.TIPO_PROCESO INSTANCIA,
B.VALOR_PAGADO ABONOS_EFECTUADOS,B.CANTIDAD CANTIDAD_DE_ABONOS,
RESOLUCION.VALOR_TOTAL AS VALOR_INICIAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_CAPITAL,
decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL))) AS SALDO_PENDIENTE_INTERESES,
SALDO_DEUDA SALDO_ACTUAL_DEUDA", false);
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("CIIU", "CIIU.CLASE=EMPRESA.CIIU");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND EMPRESA.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO", FALSE);
        $this->db->join("(select NRO_REFERENCIA, SUM(VALOR_PAGADO) VALOR_PAGADO,COUNT(VALOR_PAGADO) CANTIDAD
from PAGOSRECIBIDOS
WHERE NRO_REFERENCIA IS NOT NULL
GROUP BY  NRO_REFERENCIA) B", "B.NRO_REFERENCIA=LIQUIDACION.NUM_LIQUIDACION", 'LEFT', FALSE);
//        $this->db->where("LIQUIDACION.SALDO_DEUDA <>",0,false);
//        $this->db->where("LIQUIDACION.EN_FIRME", 'S');
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_concepto() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'NOMBRE CONCEPTO', 'CANTIDAD DE DEUDORES', 'CANTIDAD CARTERAS', 'VALOR INICIAL', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO_ACTUAL');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,
            COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DE_DEUDORES,
            count(RESOLUCION.NUMERO_RESOLUCION) CANTIDAD_CARTERAS,
            sum(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL,
SUM(decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL))))) AS SALDO_PENDIENTE_CAPITAL,
SUM(decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_INTERESES,SUM(SALDO_DEUDA) VALOR_TOTAL_DE_CARTERA", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->group_by("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_concepto_consolidado() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'NOMBRE CONCEPTO', 'CANTIDAD DE DEUDORES', 'CANTIDAD CARTERAS', 'VALOR INICIAL', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO_ACTUAL');

        $this->db->select("CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO,
            COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DE_DEUDORES,
            count(RESOLUCION.NUMERO_RESOLUCION) CANTIDAD_CARTERAS,
            sum(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL,
SUM(decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL))))) AS SALDO_PENDIENTE_CAPITAL,
SUM(decode(SALDO_DEUDA,0,0,ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_INTERESES,SUM(SALDO_DEUDA) VALOR_TOTAL_DE_CARTERA", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->group_by("CONCEPTOSFISCALIZACION.NOMBRE_CONCEPTO");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_regional() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'CANTIDAD DEUDORES', 'CANTIDAD OBLIGACIONES', 'VALOR INICIAL', 'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO ACTUAL');

        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,
            COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DEUDORES,COUNT(LIQUIDACION.NUM_LIQUIDACION) CANTIDAD_OBLIGACIONES,
            sum(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL,
sum(decode(SALDO_DEUDA, 0, 0, ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL))))) AS SALDO_PENDIENTE_CAPITAL, 
sum(decode(SALDO_DEUDA, 0, 0, ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_INTERESES, 
SUM(SALDO_DEUDA) SALDO_ACTUAL ", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("RESOLUCION", "RESOLUCION.COD_FISCALIZACION=LIQUIDACION.COD_FISCALIZACION");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->group_by("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function cartera_x_instancia() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'PROCESO', 'INSTANCIA', 'CANTIDAD DEUDORES', 'CANTIDAD CARTERA', 'VALOR_INICIAL',
            'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO_ACTUAL');


        $this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, TIPOPROCESO.TIPO_PROCESO INSTANCIA, LIQUIDACION.COD_TIPOPROCESO,
            count(RESOLUCION.NUMERO_RESOLUCION) CANTIDAD_CARTERA,
            COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DEUDORES,
            SUM(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL,
sum(decode(SALDO_DEUDA, 0, 0, ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL))))) AS SALDO_PENDIENTE_CAPITAL, 
sum(decode(SALDO_DEUDA, 0, 0, ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_INTERESES, 
sum (SALDO_DEUDA) SALDO_ACTUAL ", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join('RESOLUCION', 'LIQUIDACION.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->where("LIQUIDACION.SALDO_DEUDA >", 0, false);
        $this->db->where("LIQUIDACION.EN_FIRME", 'S');
        $this->db->group_by("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,TIPOPROCESO.TIPO_PROCESO,LIQUIDACION.COD_TIPOPROCESO");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        } else {
            $new_array = array();
            $j = 0;
            $h = 0;
            $cantidad = count($datos);
            for ($i = 0; $i < $cantidad; $i++) {
//            if ($datos[$i]['NUM_LIQUIDACION'] != ($j == $i ? 0 : $datos[$i - 1]['NUM_LIQUIDACION'])) {
                $new_array[$h]['COD_REGIONAL'] = $datos[$i]['COD_REGIONAL'];
                $new_array[$h]['NOMBRE_REGIONAL'] = $datos[$i]['NOMBRE_REGIONAL'];
                $new_array[$h]['PROCESO'] = $datos[$i]['INSTANCIA'];



                $this->db->select('TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA,TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA');
                $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA=INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
                $this->db->where('INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', $datos[$i]['COD_TIPOPROCESO']);
                $consulta = $this->db->get('INSTANCIAS_PROCESOS');
                $consulta2 = $consulta->result_array;
                $cantidad3 = count($consulta2);
                $new_array[$h]['INSTANCIA'] = "";
                if ($cantidad3 > 0) {
                    foreach ($consulta2 as $datos2) {
                        $new_array[$h]['INSTANCIA'].=$datos2['COD_TIPO_INSTANCIA'] . " - " . $datos2['NOMBRE_TIPO_INSTANCIA'] . " / ";
                    }
                    $new_array[$h]['INSTANCIA'] = substr($new_array[$h]['INSTANCIA'], 0, -2);
                }

                $new_array[$h]['CANTIDAD_DEUDORES'] = $datos[$i]['CANTIDAD_DEUDORES'];
                $new_array[$h]['CANTIDAD_CARTERA'] = $datos[$i]['CANTIDAD_CARTERA'];
                $new_array[$h]['VALOR_INICIAL'] = $datos[$i]['VALOR_INICIAL'];
                $new_array[$h]['SALDO_PENDIENTE_CAPITAL'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['SALDO_PENDIENTE_INTERESES'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
                $new_array[$h]['SALDO_ACTUAL'] = $datos[$i]['SALDO_ACTUAL'];
                $h++;
//            } else {
//                $j++;
//            } 
//                if ($datos[$i]['DIAS'] > 90) {
//                    $new_array[$h]['VALOR_CAPITAL_VENCIDA_>_90'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
//                }
            }
            return $new_array;
        }
        return $datos;
    }

    function cartera_x_instancia_consolidado() {
        $post = $this->input->post();

        if (!empty($post['num_obligacion'])) {
            $this->db->where('RESOLUCION.NUMERO_RESOLUCION', $post["num_obligacion"]);
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where("LIQUIDACION.FECHA_INICIO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        $array_select = array('0', 'PROCESO', 'INSTANCIA', 'CANTIDAD DEUDORES', 'CANTIDAD CARTERA', 'VALOR_INICIAL',
            'SALDO PENDIENTE CAPITAL', 'SALDO PENDIENTE INTERESES', 'SALDO_ACTUAL');


        $this->db->select(" TIPOPROCESO.TIPO_PROCESO INSTANCIA, LIQUIDACION.COD_TIPOPROCESO,
            count(RESOLUCION.NUMERO_RESOLUCION) CANTIDAD_CARTERA,
            COUNT(DISTINCT LIQUIDACION.NITEMPRESA) CANTIDAD_DEUDORES,
            SUM(LIQUIDACION.TOTAL_LIQUIDADO) VALOR_INICIAL,
sum(decode(SALDO_DEUDA, 0, 0, ROUND(SALDO_DEUDA * (1 - (TOTAL_INTERESES / TOTAL_CAPITAL))))) AS SALDO_PENDIENTE_CAPITAL, 
sum(decode(SALDO_DEUDA, 0, 0, ROUND(SALDO_DEUDA * (TOTAL_INTERESES / TOTAL_CAPITAL)))) AS SALDO_PENDIENTE_INTERESES, 
sum (SALDO_DEUDA) SALDO_ACTUAL ", false);
        $this->db->join("EMPRESA", "EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA");
        $this->db->join('RESOLUCION', 'LIQUIDACION.COD_FISCALIZACION=RESOLUCION.COD_FISCALIZACION');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=EMPRESA.COD_REGIONAL");
        $this->db->join("CONCEPTOSFISCALIZACION", "CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION=LIQUIDACION.COD_CONCEPTO");
        $this->db->join("TIPOPROCESO", "TIPOPROCESO.COD_TIPO_PROCESO=LIQUIDACION.COD_TIPOPROCESO");
        $this->db->where("LIQUIDACION.SALDO_DEUDA >", 0, false);
        $this->db->where("LIQUIDACION.EN_FIRME", 'S');
        $this->db->group_by("TIPOPROCESO.TIPO_PROCESO,LIQUIDACION.COD_TIPOPROCESO");
        $consulta = $this->db->get('LIQUIDACION');
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        } else {
            $new_array = array();
            $j = 0;
            $h = 0;
            $cantidad = count($datos);
            for ($i = 0; $i < $cantidad; $i++) {
//            if ($datos[$i]['NUM_LIQUIDACION'] != ($j == $i ? 0 : $datos[$i - 1]['NUM_LIQUIDACION'])) {
//                $new_array[$h]['COD_REGIONAL'] = $datos[$i]['COD_REGIONAL'];
//                $new_array[$h]['NOMBRE_REGIONAL'] = $datos[$i]['NOMBRE_REGIONAL'];
                $new_array[$h]['PROCESO'] = $datos[$i]['INSTANCIA'];



                $this->db->select('TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA,TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA');
                $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA=INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
                $this->db->where('INSTANCIAS_PROCESOS.COD_TIPO_PROCESO', $datos[$i]['COD_TIPOPROCESO']);
                $consulta = $this->db->get('INSTANCIAS_PROCESOS');
                $consulta2 = $consulta->result_array;
                $cantidad3 = count($consulta2);
                $new_array[$h]['INSTANCIA'] = "";
                if ($cantidad3 > 0) {
                    foreach ($consulta2 as $datos2) {
                        $new_array[$h]['INSTANCIA'].=$datos2['COD_TIPO_INSTANCIA'] . " - " . $datos2['NOMBRE_TIPO_INSTANCIA'] . " / ";
                    }
                    $new_array[$h]['INSTANCIA'] = substr($new_array[$h]['INSTANCIA'], 0, -2);
                }

                $new_array[$h]['CANTIDAD_DEUDORES'] = $datos[$i]['CANTIDAD_DEUDORES'];
                $new_array[$h]['CANTIDAD_CARTERA'] = $datos[$i]['CANTIDAD_CARTERA'];
                $new_array[$h]['VALOR_INICIAL'] = $datos[$i]['VALOR_INICIAL'];
                $new_array[$h]['SALDO_PENDIENTE_CAPITAL'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
                $new_array[$h]['SALDO_PENDIENTE_INTERESES'] = $datos[$i]['SALDO_PENDIENTE_INTERESES'];
                $new_array[$h]['SALDO_ACTUAL'] = $datos[$i]['SALDO_ACTUAL'];
                $h++;
//            } else {
//                $j++;
//            } 
//                if ($datos[$i]['DIAS'] > 90) {
//                    $new_array[$h]['VALOR_CAPITAL_VENCIDA_>_90'] = $datos[$i]['SALDO_PENDIENTE_CAPITAL'];
//                }
            }
            return $new_array;
        }
        return $datos;
    }

    function kactus_x_aportantes() {
        $post = $this->input->post();

        $array_select = array('0', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'COD CARTERA NO MISIONAL', 'COD DEUDOR', 'NOMBRE DEUDOR',
            'DIRECCIÃ“N', 'E MAIL', 'TELEFONO', 'CONCEPTO', 'VALOR DEUDA', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE CAPITAL',
            'SALDO ACTUAL');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }
        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }
        $this->db->select("REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, 
NVL(CNM_EMPRESA.COD_ENTIDAD,CNM_EMPLEADO.IDENTIFICACION) COD_DEUDOR, 
NVL(CNM_EMPRESA.RAZON_SOCIAL,CNM_EMPLEADO.NOMBRES || ' '|| CNM_EMPLEADO.APELLIDOS) AS NOMBRE_DEUDOR, 
NVL(CNM_EMPLEADO.DIRECCION, CNM_EMPRESA.DIRECCION) DIRECCION,
NVL(CNM_EMPLEADO.CORREO_ELECTRONICO, CNM_EMPRESA.CORREO_ELECTRONICO) E_MAIL,
NVL(CNM_EMPLEADO.TELEFONO, CNM_EMPRESA.TELEFONO) TELEFONO,
TIPOCARTERA.NOMBRE_CARTERA CONCEPTO, 
CNM_CARTERANOMISIONAL.VALOR_DEUDA,
SUM(CNM_CUOTAS.SALDO_INTERES_C) SALDO_PENDIENTE_INTERESES, 
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL, 
(SUM(CNM_CUOTAS.SALDO_INTERES_C) + SUM(CNM_CUOTAS.SALDO_AMORTIZACION) ) SALDO_ACTUAL,(SUM(CNM_CUOTAS.VALOR_CUOTA)-SUM(CNM_CUOTAS.SALDO_CUOTA)) VALOR_ABONO", false);
        $this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", 'LEFT');
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
//        $this->db->where("CNM_CUOTAS.SALDO_AMORTIZACION<>", "0", false);
        $this->db->group_by(array("REGIONAL.NOMBRE_REGIONAL", "DEPARTAMENTO.NOM_DEPARTAMENTO", "MUNICIPIO.NOMBREMUNICIPIO",
            "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", " NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)",
            "NVL(CNM_EMPRESA.COD_ENTIDAD,CNM_EMPLEADO.IDENTIFICACION)", "NVL(CNM_EMPRESA.RAZON_SOCIAL,CNM_EMPLEADO.NOMBRES || ' '|| CNM_EMPLEADO.APELLIDOS)",
            "NVL(CNM_EMPLEADO.DIRECCION, CNM_EMPRESA.DIRECCION)", "NVL(CNM_EMPLEADO.CORREO_ELECTRONICO, CNM_EMPRESA.CORREO_ELECTRONICO)",
            "NVL(CNM_EMPLEADO.TELEFONO, CNM_EMPRESA.TELEFONO)", "CNM_CARTERANOMISIONAL.VALOR_DEUDA", "TIPOCARTERA.NOMBRE_CARTERA"));
        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function r_cartera_no_mis_moroso() {
        $post = $this->input->post();
		
        $array_select = array('0', 'COD DEUDOR', 'NOMBRE DEUDOR', 'COD DEUDA', 'CONCEPTO', 'FECHA ULTIMO PAGO', 'ULTIMA CUOTA CANCELADA', 'SALDO DEUDA DE CAPITAL',
            'INTERESES ACOMULADOS NO PAGO', 'VALOR TOTAL DEUDA', 'CUOTAS MES MORA', 'ESTADO ACTUAL DEL COBRO', 'ESTADO FUNCIONARIO');

        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        if ($post['conceptonm'] != "-1") {

            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['conceptonm']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }

		if(!empty($regional)&&isset($post['regional'][0])){
		if ($post['regional'][0] != "-1") {
            $regional = $post['regional'][0];
			$this->db->where('CNM_CARTERANOMISIONAL.COD_REGIONAL', $regional, FALSE);
        }
		}
		if ($post['estado_e'] != "-1") {
            $estado_e = $post['estado_e'][0];
			$this->db->where('CNM_EMPLEADO.COD_ESTADO_E', $estado_e);
        }
			
		 $this->db->where("CNM_CUOTAS.CONCEPTO  not in ('12','13','2','9') and 1=", 1, false);


        $this->db->select("NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR, 
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)) AS NOMBRE_DEUDOR, 
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL COD_DEUDA, 
TIPOCARTERA.NOMBRE_CARTERA CONCEPTO, 
MAX(DECODE(CNM_CUOTAS.SALDO_CUOTA, 0,CNM_CUOTAS.FECHA_LIM_PAGO,NULL))	FECHA_ULTIMO_PAGO,
MAX(DECODE(CNM_CUOTAS.SALDO_CUOTA, 0,CNM_CUOTAS.MES_PROYECTADO,NULL))	ULTIMA_CUOTA_CANCELADA,
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_DEUDA_DE_CAPITAL,
SUM(CNM_CUOTAS.SALDO_INTERES_C) INTERESES_ACOMULADOS_NO_PAGO,

SUM(CNM_CUOTAS.SALDO_AMORTIZACION + CNM_CUOTAS.SALDO_INTERES_C) VALOR_TOTAL_DEUDA,
SUM(DECODE(CNM_CUOTAS.SALDO_CUOTA,0,0,1)) CUOTAS_MES_MORA,
ESTADOCARTERA.DESC_EST_CARTERA ESTADO_ACTUAL_DEL_COBRO,
CNM_ESTADO_EMP.NOMBRE_ESTADO_E ESTADO_FUNCIONARIO", false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", 'LEFT');
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
		$this->db->join("CNM_ESTADO_EMP", "CNM_ESTADO_EMP.COD_ESTADO_E=CNM_EMPLEADO.COD_ESTADO_E", 'LEFT');
		$this->db->join("ESTADOCARTERA", "ESTADOCARTERA.COD_EST_CARTERA=CNM_CARTERANOMISIONAL.COD_ESTADO", 'LEFT');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");


        $this->db->where("SYSDATE-CNM_CUOTAS.FECHA_LIM_PAGO > 0 AND CNM_CARTERANOMISIONAL.COD_ESTADO  not in ('1','3','4') 
		HAVING SUM(DECODE(CNM_CUOTAS.SALDO_CUOTA, 0, 0, 1))>0 group by 
		CNM_CUOTAS.ID_DEUDA_E, NOMBRE_ESTADO_E, CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, DESC_EST_CARTERA,
		NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO),
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)),TIPOCARTERA.NOMBRE_CARTERA", false, false);

        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
		//echo $this->db->last_query();
		//die();
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }
	
	

	    function r_cartera_no_mis_cesantias() {
        $post = $this->input->post();
		$mes="#02";
		$periodo=date('Y').$mes;
		$periodo=str_replace('#', '-', $periodo);
		$array_select = array('0', 'COD DEUDOR', 'NOMBRE DEUDOR', 'CODIGO DEUDA', 'SALDO DEUDA', 'FECHA SALDO DEUDA');

        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }

		if(!empty($regional)&&isset($post['regional'][0])){
		if ($post['regional'][0] != "-1") {
            $regional = $post['regional'][0];
			$this->db->where('CNM_CARTERANOMISIONAL.COD_REGIONAL', $regional, FALSE);
        }
		}
		
			
		 $this->db->where("CNM_CUOTAS.CONCEPTO  in ('8') and 1=", 1, false);


        $this->db->select("NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR, 
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)) AS NOMBRE_DEUDOR, 
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL AS CODIGO_DEUDA, 
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_DEUDA_DE_CAPITAL", false);
$this->db->select("'".$periodo."' FECHA_SALDO_DEUDA",false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", 'LEFT');
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");


        $this->db->where("
		CNM_CUOTAS.MES_PROYECTADO>'".$periodo."' AND
		CNM_CARTERANOMISIONAL.COD_ESTADO in ('2') 
		HAVING SUM(DECODE(CNM_CUOTAS.SALDO_CUOTA, 0, 0, 1))>0 group by 
		NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO),
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)),
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", false, false);

        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
		//echo $this->db->last_query();
		//die();
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }
	
    function r_cartera_no_misional_r() {
        $post = $this->input->post();

        $array_select = array('0', 'NOMBRE REGIONAL', 'TIPO DE CARTERA', 'CANTIDAD', 'SALDO PENDIENTE CAPITAL', 'CUOTAS MES MORA',
            'TOTAL ADEUDADO POR MOROSOS');

        /*$periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }
        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }*/
        if ($post['conceptonm'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['conceptonm']);
        }
		/*
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }*/
		
				if(!empty($regional)&&isset($post['regional'][0])){
		if ($post['regional'][0] != "-1") {
            $regional = $post['regional'][0];
			$this->db->where('CNM_CARTERANOMISIONAL.COD_REGIONAL', $regional, FALSE);
        }
		}
        $this->db->select(" REGIONAL.NOMBRE_REGIONAL, TIPOCARTERA.NOMBRE_CARTERA TIPO_DE_CARTERA,
COUNT(DISTINCT CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL) CANTIDAD,
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) TOTAL_SALDOS,
SUM(DECODE(CNM_CARTERANOMISIONAL.SALDO_INTERES_MORATORIO,0,0,1)) CANTIDAD_MOROSOS
", false);
		 $this->db->where("CNM_CUOTAS.CONCEPTO  not in ('12','13') AND 
		 CNM_CARTERANOMISIONAL.COD_ESTADO  not in ('1','3','4') and 1=", 1, false);
 

        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", 'LEFT');
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");


        $this->db->group_by(array("REGIONAL.NOMBRE_REGIONAL", "TIPOCARTERA.NOMBRE_CARTERA"));

        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
		//echo $this->db->last_query();
		//die();
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function r_cartera_no_misional_r_conso() {
        $post = $this->input->post();

        $array_select = array('0', 'TIPO DE CARTERA', 'CANTIDAD', 'SALDO PENDIENTE CAPITAL', 'CUOTAS MES MORA',
            'TOTAL ADEUDADO POR MOROSOS');
/*
        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }
        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }*/
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }/*
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }*/
		if(!empty($regional)&&isset($post['regional'][0])){
		if ($post['regional'][0] != "-1") {
            $regional = $post['regional'][0];
			$this->db->where('CNM_CARTERANOMISIONAL.COD_REGIONAL', $regional, FALSE);
        }
		}
        $this->db->select(" REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL,
COUNT(DISTINCT CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL) CANTIDAD, 
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL,
SUM(DECODE(CNM_CARTERANOMISIONAL.SALDO_INTERES_MORATORIO,0,0,1)) CANTIDAD_MOROSOS
", false);
$this->db->where("CNM_CUOTAS.CONCEPTO  not in ('12','13') AND 
		 CNM_CARTERANOMISIONAL.COD_ESTADO  not in ('1','3','4') and 1=", 1, false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", 'LEFT');
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");


        $this->db->group_by(array("REGIONAL.COD_REGIONAL", "REGIONAL.NOMBRE_REGIONAL"));

        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function kactus_x_prescrita() {
        $post = $this->input->post();

        $array_select = array('0', 'COD CARTERA NOMISIONAL', 'COD DEUDOR', 'NOMBRE DEUDOR', 'NOMBRE CARTERA', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE CAPITAL');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }
        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }
        $this->db->select("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL,
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR,
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)) AS NOMBRE_DEUDOR,
TIPOCARTERA.NOMBRE_CARTERA,SUM(CNM_CUOTAS.SALDO_INTERES_C) SALDO_PENDIENTE_INTERESES,
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL", false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->join("RECEPCIONTITULOS", "RECEPCIONTITULOS.COD_CARTERA_NOMISIONAL=CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL");
        $this->db->where("CNM_CUOTAS.SALDO_AMORTIZACION<>", "0", false);
        $this->db->where("RECEPCIONTITULOS.TITULO_PRESCRITO", 1);
        $this->db->group_by(array("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL",
            "NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)",
            "fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO))",
            "TIPOCARTERA.NOMBRE_CARTERA"));
        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function kactus_x_abono() {
        $post = $this->input->post();

        $array_select = array('0', 'REGIONAL', 'DEPARTAMENTO', 'MUNICIPIO', 'COD CARTERA NO MISIONAL', 'COD DEUDOR', 'NOMBRE DEUDOR',
            'DIRECCIÓN', 'E MAIL', 'TELEFONO', 'CONCEPTO', 'VALOR DEUDA', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE CAPITAL',
            'SALDO ACTUAL');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }
        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }
        $this->db->select("REGIONAL.NOMBRE_REGIONAL REGIONAL,DEPARTAMENTO.NOM_DEPARTAMENTO DEPARTAMENTO,MUNICIPIO.NOMBREMUNICIPIO MUNICIPIO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, 
NVL(CNM_EMPRESA.COD_ENTIDAD,CNM_EMPLEADO.IDENTIFICACION) COD_DEUDOR, 
NVL(CNM_EMPRESA.RAZON_SOCIAL,CNM_EMPLEADO.NOMBRES || ' '|| CNM_EMPLEADO.APELLIDOS) AS NOMBRE_DEUDOR, 
NVL(CNM_EMPLEADO.DIRECCION, CNM_EMPRESA.DIRECCION) DIRECCION,
NVL(CNM_EMPLEADO.CORREO_ELECTRONICO, CNM_EMPRESA.CORREO_ELECTRONICO) E_MAIL,
NVL(CNM_EMPLEADO.TELEFONO, CNM_EMPRESA.TELEFONO) TELEFONO,
TIPOCARTERA.NOMBRE_CARTERA CONCEPTO, 
CNM_CARTERANOMISIONAL.VALOR_DEUDA,
SUM(CNM_CUOTAS.SALDO_INTERES_C) SALDO_PENDIENTE_INTERESES, 
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL, 
(SUM(CNM_CUOTAS.SALDO_INTERES_C) + SUM(CNM_CUOTAS.SALDO_AMORTIZACION) ) SALDO_ACTUAL,(SUM(CNM_CUOTAS.VALOR_CUOTA)-SUM(CNM_CUOTAS.SALDO_CUOTA)) VALOR_ABONO", false);
        $this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", 'LEFT');
        $this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", 'LEFT');
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
//        $this->db->where("CNM_CUOTAS.SALDO_AMORTIZACION<>", "0", false);
        $this->db->group_by(array("REGIONAL.NOMBRE_REGIONAL", "DEPARTAMENTO.NOM_DEPARTAMENTO", "MUNICIPIO.NOMBREMUNICIPIO",
            "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", " NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)",
            "NVL(CNM_EMPRESA.COD_ENTIDAD,CNM_EMPLEADO.IDENTIFICACION)", "NVL(CNM_EMPRESA.RAZON_SOCIAL,CNM_EMPLEADO.NOMBRES || ' '|| CNM_EMPLEADO.APELLIDOS)",
            "NVL(CNM_EMPLEADO.DIRECCION, CNM_EMPRESA.DIRECCION)", "NVL(CNM_EMPLEADO.CORREO_ELECTRONICO, CNM_EMPRESA.CORREO_ELECTRONICO)",
            "NVL(CNM_EMPLEADO.TELEFONO, CNM_EMPRESA.TELEFONO)", "CNM_CARTERANOMISIONAL.VALOR_DEUDA", "TIPOCARTERA.NOMBRE_CARTERA"));
        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function kactus_x_plazo() {
        $post = $this->input->post();

        $array_select = array('0', 'NOMBRE REGIONAL', 'NOMBRE CONCEPTO', 'RAZÓN SOCIAL', 'FECHA VISITA', 'NRO VISITAS', 'FISCALIZADOR', 'COMENTARIOS');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }
        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }
        $this->db->select("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, CNM_CARTERANOMISIONAL.PLAZO_CUOTAS,CNM_PLAZO.NOMBRE_PLAZO TIPO_PLAZO,COUNT(CNM_CUOTAS.SALDO_AMORTIZACION) CUOTAS_PENDIENTES,
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR, 
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)) AS NOMBRE_DEUDOR,
 TIPOCARTERA.NOMBRE_CARTERA, SUM(CNM_CUOTAS.SALDO_INTERES_C) SALDO_PENDIENTE_INTERESES, 
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL, (SUM(CNM_CUOTAS.VALOR_CUOTA)-SUM(CNM_CUOTAS.SALDO_CUOTA)) VALOR_ABONO ", false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->join("CNM_PLAZO", "CNM_PLAZO.COD_PLAZO=CNM_CARTERANOMISIONAL.COD_PLAZO");
        $this->db->where("CNM_CUOTAS.SALDO_AMORTIZACION<>", "0", false);
        $this->db->group_by(array("CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL", "CNM_CARTERANOMISIONAL.PLAZO_CUOTAS", "CNM_PLAZO.NOMBRE_PLAZO",
            "NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)",
            "fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO))",
            "TIPOCARTERA.NOMBRE_CARTERA"));
        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function kactus_x_concepto() {
        $post = $this->input->post();

        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'NOMBRE CARTERA', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE CAPITAL');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }

        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('CNM_EMPLEADO.IDENTIFICACION', $cedula[0]);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }
        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,
TIPOCARTERA.NOMBRE_CARTERA,
SUM(CNM_CUOTAS.SALDO_INTERES_C) SALDO_PENDIENTE_INTERESES,
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL", false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->where("CNM_CUOTAS.SALDO_AMORTIZACION<>", "0", false);
        $this->db->group_by('REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,
TIPOCARTERA.NOMBRE_CARTERA');
        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function kactus_x_regional() {
        $post = $this->input->post();

        $array_select = array('0', 'COD REGIONAL', 'NOMBRE REGIONAL', 'SALDO PENDIENTE INTERESES', 'SALDO PENDIENTE CAPITAL');

        $periodo = $post['periodo'];
        if (!empty($periodo)) {
            $periodo = explode("/", $periodo);
            $this->db->where("CNM_CUOTAS.MES_PROYECTADO", $periodo[1] . "-" . $periodo[0]);
        }

        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('CNM_EMPLEADO.IDENTIFICACION', $cedula[0]);
        }
        if ($post['concepto2'] != "-1") {
            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['concepto2']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }
        $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL,
SUM(CNM_CUOTAS.SALDO_INTERES_C) SALDO_PENDIENTE_INTERESES,
SUM(CNM_CUOTAS.SALDO_AMORTIZACION) SALDO_PENDIENTE_CAPITAL", false);
        $this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E");
        $this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL");
        $this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
        $this->db->where("CNM_CUOTAS.SALDO_AMORTIZACION<>", "0", false);
        $this->db->group_by('REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL');
        $consulta = $this->db->get("CNM_CARTERANOMISIONAL");
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ugpp_actualizacion() {
        $ano = Date('Y');
        $ano_ant = $ano - 1;
        $array_select = array('Administradora', 'Nombre_administradora', 'Nombre_o_razon_social', 'Tipo_de_documento',
            'nro_documento_del_aportante', 'numero_verificacion', 'Direccion_1', 'Codigo_departamento_1', 'Codigo_municipio_1',
            'Nombre_departamento_1', 'Nombre_municipio_1', 'Direccion_2', 'Codigo_departamento_2', 'Codigo_municipio_2',
            'Nombre_departamento_2', 'Nombre_municipio_2', 'Telefono_fijo_1', 'Indicativo_telefono_1', 'Telefono_fijo_2',
            'Indicativo_telefono_2', 'Celular_1', 'Celular_2', 'Correo_electronico_1', 'Correo_electronico_2', 'Ultima_fecha_de_actualizacion'
        );
        $query = "
            'PASENA' Administradora, 
            'SERVICIO NACIONAL DE APRENDIZAJE' Nombre_administradora,
            EM.NOMBRE_EMPRESA Nombre_o_razon_social,
            TD.DESCRIPCION Tipo_de_documento,
            EM.CODEMPRESA  nro_documento_del_aportante,
            decode(TD.DESCRIPCION, 'NI', Utilidades.NIT_dv(EM.CODEMPRESA),'') numero_verificacion,
            DIRECCION  Direccion_1,
            EM.COD_DEPARTAMENTO  Codigo_departamento_1,
            EM.COD_MUNICIPIO  Codigo_municipio_1,
            DP.NOM_DEPARTAMENTO Nombre_departamento_1,
            MN.NOMBREMUNICIPIO Nombre_municipio_1, 
            '' Direccion_2, 
            '' Codigo_departamento_2, 
            '' Codigo_municipio_2, 
            '' Nombre_departamento_2,
            '' Nombre_municipio_2,
            EM.TELEFONO_FIJO  Telefono_fijo_1,
            '' Indicativo_telefono_1, 
            '' Telefono_fijo_2, 
            '' Indicativo_telefono_2,
            EM.TELEFONO_CELULAR Celular_1,
            '' Celular_2,
            EM.EMAILAUTORIZADO  Correo_electronico_1, 
            '' Correo_electronico_2,
            EM.FECHA_ACT_UGPP  Ultima_fecha_de_actualizacion";
        $this->db->select($query, false);
        $this->db->from("EMPRESA EM, TIPODOCUMENTO TD, DEPARTAMENTO DP, MUNICIPIO MN");
        $this->db->where("MN.CODMUNICIPIO = EM.COD_MUNICIPIO");
        $this->db->where("MN.COD_DEPARTAMENTO = EM.COD_DEPARTAMENTO");
        $this->db->where("DP.COD_DEPARTAMENTO = EM.COD_DEPARTAMENTO");
        $this->db->where("TD.CODTIPODOCUMENTO = EM.COD_TIPODOCUMENTO");
        $this->db->where("FECHA_ACT_UGPP BETWEEN to_date('01-11-" . $ano_ant . "','DD-MM-YYYY') and to_date('31-10-" . $ano . "','DD-MM-YYYY') and 1=", 1, false);
        $consulta = $this->db->get();
        $datos = $consulta->result_array();
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ugpp_desagregado() {
        $ano = Date('Y');
        $ano_ant = $ano - 1;
        $array_select = array('Administradora', 'Nombre_administradora', 'Nombre_o_razon_social', 'Tipo_de_documento',
            'nro_documento_del_aportante', 'numero_verificacion', 'tipo_de_cartera', 'origen_de_cartera', 'valor_de_la_cartera',
            'ultimo_periodo_con_pago', 'ultima_Fecha_de_Pago', 'numero_periodos_sin_pago', 'numero_periodos_sin_pago',
            'fecha_ultima_accion_de_cobro', 'estado_del_aportante', 'clasificacion_del_estado', 'convenio_de_cobro'
        );
        $query = "
            'PASENA' Administradora, 
            'SERVICIO NACIONAL DE APRENDIZAJE' Nombre_administradora,
            EM.NOMBRE_EMPRESA Nombre_o_razon_social,
            TD.DESCRIPCION Tipo_de_documento, 
            EM.CODEMPRESA  nro_documento_del_aportante,
            decode(TD.DESCRIPCION,'NI',Utilidades.NIT_dv(EM.CODEMPRESA),'') numero_verificacion,
            '2' tipo_de_cartera,
            '1' origen_de_cartera,
            LQ.SALDO_DEUDA valor_de_la_cartera,
            MAX(PR.PERIODO_PAGADO) ultimo_periodo_con_pago,
            MAX(PR.FECHA_PAGO)  ultima_Fecha_de_Pago,
            '' numero_periodos_sin_pago,
            MAX(GC.FECHA_CONTACTO) fecha_ultima_accion_de_cobro,
            'A' estado_del_aportante,
            '' clasificacion_del_estado,
            DECODE(AP.NRO_ACUERDOPAGO,NULL,'N','S') convenio_de_cobro
        ";
        $this->db->select($query, false);
        $this->db->from("EMPRESA EM, TIPODOCUMENTO TD, LIQUIDACION LQ, PAGOSRECIBIDOS PR, GESTIONCOBRO GC, ACUERDOPAGO AP");
        $this->db->where("LQ.SALDO_DEUDA >", 0);
        $this->db->where("LQ.COD_CONCEPTO", 1);
        $this->db->where("LQ.FECHA_EJECUTORIA IS NOT NULL");
        $this->db->where("GC.COD_FISCALIZACION_EMPRESA = LQ.COD_FISCALIZACION");
        $this->db->where("PR.COD_FISCALIZACION = LQ.COD_FISCALIZACION");
        $this->db->where("LQ.FECHA_EJECUTORIA BETWEEN ADD_MONTHS(SYSDATE, -12) AND SYSDATE");
        $this->db->where("LQ.NITEMPRESA = EM.CODEMPRESA");
        $this->db->where("TD.CODTIPODOCUMENTO = EM.COD_TIPODOCUMENTO");
        $this->db->where("AP.COD_FISCALIZACION(+) = LQ.COD_FISCALIZACION");
        $this->db->group_by(array("LQ.NUM_LIQUIDACION", "EM.NOMBRE_EMPRESA", "TD.DESCRIPCION", "EM.CODEMPRESA", "LQ.SALDO_DEUDA", "DECODE(AP.NRO_ACUERDOPAGO,NULL,'N','S')"));
        $consulta = $this->db->get();
        $datos = $consulta->result_array();

//        $query2 = "PR.NIT, COUNT(*) AS PAGOS, AVG(PR.VALOR_PAGADO) AS PROMEDIO";
//        $this->db->select();
//        $this->db->from('PAGOSRECIBIDOS PR');

        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function ugpp_consolidado() {
        $array_select = array('Administradora', 'Nombre Administradora', 'Tipo Cartera', 'Origen Cartera', 'Edad Cartera', 'Periodos Sin Pago', 'Valor Cartera');
        $query = "
            'PASENA' Administradora, 
            'SERVICIO NACIONAL DE APRENDIZAJE' Nombre_administradora,
            '2' Tipo_de_la_cartera,
            '1' origen_de_cartera,
            CASE 
                WHEN (SYSDATE-LIQUIDACION.FECHA_LIQUIDACION) BETWEEN 0 AND 90 THEN '1'
                WHEN (SYSDATE-LIQUIDACION.FECHA_LIQUIDACION) BETWEEN 91 AND 180 THEN '2'
                WHEN (SYSDATE-LIQUIDACION.FECHA_LIQUIDACION) > 180 THEN '3'
            END  AS Edad_de_la_cartera,
            '' nro_periodo_sin_pago,
            LIQUIDACION.SALDO_DEUDA valor_de_la_cartera
        ";
        $this->db->select($query, false);
        $this->db->where("LIQUIDACION.SALDO_DEUDA >", 0);
        $this->db->where("LIQUIDACION.COD_CONCEPTO", 1);
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA BETWEEN ADD_MONTHS(SYSDATE, -12) AND SYSDATE");
        $this->db->where("LIQUIDACION.FECHA_EJECUTORIA IS NOT NULL");
        $consulta = $this->db->get("LIQUIDACION");
        $datos = $consulta->result_array();



        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;
    }

    function devolucion_x_concepto() {
        $post = $this->input->post();

        $where = "";

        if ($post['empresa'] != '') {
            $nit_empresa = explode(" - ", $post["empresa"]);
            $where.=" and (SD.NIT='" . $nit_empresa[0] . "') ";
        }
        if (!empty($post['fecha_ini'])) {
            if (empty($post['fecha_fin'])) {
                $post['fecha_fin'] = date('d/m/Y');
            }
            $this->db->where(" AND SD.FECHA_RADICACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY') AND 1=", "1", false);
        }
        if ($post['num_obligacion'] != '') {
            $where.=" and (SD.COD_DEVOLUCION='" . $post['num_obligacion'] . "') ";
        }
        $where2 = "";
        if ($post['concepto'] != '-1') {
            $where2 = " and (CF.COD_CPTO_FISCALIZACION='" . $post['concepto'] . "') ";
        }
        $where3 = "";
        if ($post['concepto2'] != '-1') {
            $where3 = " and (CD.COD_CONCEPTO='" . $post['concepto2'] . "') ";
        }
        $where4 = "";
        if ($post['reporte'] == '73') {
            $where4 = " and (DD.APROBADO='N') ";
        }
        $where5 = "";
        if ($post['reporte'] == '73') {
            $where5 = " and (DP.APROBADO='N') ";
        }
        $where6 = "";
        if ($post['reporte'] == '73') {
            $where6 = " and (DP.ESTADO='2') ";
        }
        if (isset($post["regional"][0])) {
            if ($post["regional"][0] != "-1") {
                $regional = "";
                foreach ($post["regional"] as $dato) {
                    $regional.= $dato . ",";
                }
                $regional = substr($regional, 0, -1);
                $where.= ' AND EM.COD_REGIONAL in (' . $regional . ")";
            }
        }


        $array_select = array('0', 'COD_REGIONAL', 'NOMBRE_REGIONAL', 'COD DEVOLUCIÓN', 'TOTAL DEVUELTO', 'NIT', 'DIGITO VERIFICACION', 'RAZÓN SOCIAL', 'PLANILLA', 'NOMBRE CONCEPTO', 'APROBADO');

        $SQL = "SELECT REG.COD_REGIONAL,REG.NOMBRE_REGIONAL,SD.COD_DEVOLUCION,TO_CHAR( TO_NUMBER('00581600'), '999,990') AS SALARIO, 
            TO_CHAR(PD.DIAS_COTIZ) AS DIAS_TRABAJADOS , DD.PAGO_ERRADO AS TOTAL_PAGADO,DD.PAGO_ERRADO-DD.PAGO_CORRESPONDIENTE AS TOTAL_DEVUELTO,
            SD.NIT,Utilidades.NIT_dv(SD.NIT) DIGITO_VERIFICACION,EM.NOMBRE_EMPRESA, TO_CHAR(DD.COD_PLANILLAUNICA) PLANILLA, CD.NOMBRE_CONCEPTO, DD.APROBADO, PUE.PERIDO_PAGO
FROM SOLICITUDDEVOLUCION SD, DEVOLUCION_DET DD, CONCEPTOSDEVOLUCION CD, EMPRESA EM,REGIONAL REG, PLANILLAUNICA_DET PD, PLANILLAUNICA_ENC PUE
WHERE PD.COD_PLANILLAUNICA = DD.COD_PLANILLAUNICA AND PUE.COD_PLANILLAUNICA = DD.COD_PLANILLAUNICA AND
SD.COD_DEVOLUCION = DD.COD_DEVOLUCION AND CD.COD_CONCEPTO = SD.COD_CONCEPTO AND EM.CODEMPRESA=SD.NIT AND REG.COD_REGIONAL=EM.COD_REGIONAL  " . $where . "" . $where3 . "" . $where4 . "
UNION(
SELECT REG.COD_REGIONAL,REG.NOMBRE_REGIONAL,SD.COD_DEVOLUCION, '', '',DP.VALOR_INCORRECTO, DP.VALOR_INCORRECTO - DP.VALOR_CORRECTO, 
SD.NIT,Utilidades.NIT_dv(SD.NIT) DIGITO_VERIFICACION,EM.NOMBRE_EMPRESA, TO_CHAR(DP.COD_PLANILLAUNICA), CD.NOMBRE_CONCEPTO, DP.APROBADO, PUE.PERIDO_PAGO 
FROM SOLICITUDDEVOLUCION SD, DEVOLUCION_PLANILLAS DP, CONCEPTOSDEVOLUCION CD,EMPRESA EM,REGIONAL REG, PLANILLAUNICA_ENC PUE
WHERE SD.COD_DEVOLUCION = DP.COD_DEVOLUCION AND PUE.COD_PLANILLAUNICA = DP.COD_PLANILLAUNICA
AND CD.COD_CONCEPTO = SD.COD_CONCEPTO AND EM.CODEMPRESA=SD.NIT AND REG.COD_REGIONAL=EM.COD_REGIONAL  " . $where . "" . $where3 . "" . $where5 . "
)
UNION(
SELECT REG.COD_REGIONAL,REG.NOMBRE_REGIONAL,SD.COD_DEVOLUCION AS IDENTIFICACION, '', '',PR.VALOR_PAGADO,DP.VALOR_DEVUELTO, SD.NIT,Utilidades.NIT_dv(SD.NIT) DIGITO_VERIFICACION,EM.NOMBRE_EMPRESA, PR.PROCEDENCIA, 
CF.NOMBRE_CONCEPTO,decode(DP.ESTADO,1,'S','N'), PR.PERIODO_PAGADO from SOLICITUDDEVOLUCION SD, DEVOLUCION_PAGOS DP, PAGOSRECIBIDOS PR, 
CONCEPTOSFISCALIZACION CF, EMPRESA EM,REGIONAL REG where DP.COD_DEVOLUCION=SD.COD_DEVOLUCION AND DP.COD_PAGOSRECIBIDOS = PR.COD_PAGO 
AND CF.COD_CPTO_FISCALIZACION = PR.COD_CONCEPTO AND EM.CODEMPRESA=SD.NIT  AND REG.COD_REGIONAL=EM.COD_REGIONAL " . $where . "" . $where2 . "" . $where6 . "
)
ORDER BY 1,2";
        $consulta = $this->db->query($SQL);
        $new_array = $consulta->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function reemplazo_x_ingresos() {
        $post = $this->input->post();

        $array_select = array('0', 'TIPO DOCUMENTO', 'NIT', 'PRIMER APELLIDO', 'SEGUNDO APELLIDO', 'PRIMER NOMBRE', 'OTROS NOMBRES', 'RAZÓN SOCIAL',
            'PAIS', 'INGRESOS BRUTOS', 'INGRESOS A TRAVES DE CONSORCIO', 'INGRESOS A TRAVES DE CONTRATOS', 'INGRESOS POR EXPLORACIÓN',
            'INGRESOS A TRAVES DE FIDUCIA', 'INGRESOS RECIBIDOS');
        $SQL = "SELECT Tipo_documento,NIT,primer_apellido,segundo_apellido,primer_nombre,otros_nombres,
NOMBRE_EMPRESA,pais, Ingresos_brutos,Ingresos_a_traves_de_consorcio,
Ingresos_a_traves_de_contratos, Ingresos_por_exploracion,Ingresos_a_traves_de_fiducia,
SUM(Ingresos_recibidos) Ingresos_recibidos
from VW_INGRESOS_RECIBIDOS_EXOGENAS
WHERE TO_CHAR(FECHA_RADICACION, 'YYYY') = '" . $post['ano'] . "'
GROUP BY Tipo_documento,NIT,primer_apellido,segundo_apellido,primer_nombre,otros_nombres,
NOMBRE_EMPRESA,pais, Ingresos_brutos,Ingresos_a_traves_de_consorcio,
Ingresos_a_traves_de_contratos, Ingresos_por_exploracion,Ingresos_a_traves_de_fiducia
ORDER BY nit";
        $consulta = $this->db->query($SQL);
        $new_array = $consulta->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function insercion_x_ingresos() {
        $post = $this->input->post();
        $monto = $post['monto'];
        $array_select = array('0', 'TIPO DOCUMENTO', 'NIT', 'PRIMER APELLIDO', 'SEGUNDO APELLIDO', 'PRIMER NOMBRE', 'OTROS NOMBRES', 'RAZÓN SOCIAL',
            'PAIS', 'INGRESOS BRUTOS', 'INGRESOS A TRAVES DE CONSORCIO', 'INGRESOS A TRAVES DE CONTRATOS', 'INGRESOS POR EXPLORACIÓN',
            'INGRESOS A TRAVES DE FIDUCIA', 'INGRESOS RECIBIDOS');
        $SQL = "SELECT 
CONCEPTO,
TIPO_DOCUMENTO AS TIPO_DE_DOCUMENTO,
NIT AS  NITEMPRESA,
SENA_CAPACITACION.UTILIDADES.NIT_DV(NIT) AS DIGITO,
PRIMER_APELLIDO,
SEGUNDO_APELLIDO,
PRIMER_NOMBRE,
OTROS_NOMBRES,
NOMBRE_EMPRESA,
PAIS AS PAIS,
INGRESOS_BRUTOS,
INGRESOS_A_TRAVES_DE_CONSORCIO,
INGRESOS_A_TRAVES_DE_CONTRATOS,
INGRESOS_POR_EXPLORACION,
INGRESOS_A_TRAVES_DE_FIDUCIA,
INGRESOS_RECIBIDOS AS VALOR_PAGADO
FROM VW_INGRESOS_RECIBIDOS_EXOGENAS 
WHERE TO_CHAR(FECHA_RADICACION, 'YYYY') = '" . $post['ano'] . "'";
        if ($monto != '')
            $SQL.=" AND INGRESOS_RECIBIDOS > " . $monto;
        $consulta = $this->db->query($SQL);
        $new_array = $consulta->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function insercion_x_saldo2() {
        $post = $this->input->post();
        $monto = $post['monto'];
        $SQL = 'select * from pagosrecibidos where COD_PAGO = 10';
        if ($monto != '')
            $SQL.=" AND VALOR_PAGADO > " . $monto;
        $consulta = $this->db->query($SQL);
        $new_array = $consulta->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function insercion_x_saldo() {
        $post = $this->input->post();
        $monto = $post['monto'];
        $array_select = array('0', 'CONCEPTO', 'TIPO DOCUMENTO', 'NIT', 'PRIMER APELLIDO', 'SEGUNDO APELLIDO', 'PRIMER NOMBRE', 'OTROS NOMBRES',
            'RAZÓN SOCIAL', 'DIRECCIÓN', 'COD DEPARTAMENTO', 'COD MUNICIPIO', 'PAIS', 'SALDO CUENTA POR COBRAR');

        $SQL = "
            SELECT '4001' Concepto,CASE EMpresa.COD_TIPODOCUMENTO
WHEN 1 THEN
'13'
WHEN 2 THEN
'31'
WHEN 4 THEN
'21'
WHEN 5 THEN
'12'
END Tipo_documento,LIQUIDACION.NITEMPRESA NIT,UTILIDADES.NIT_DV(LIQUIDACION.NITEMPRESA) digito,
            '' primer_apellido,'' segundo_apellido,
'' primer_nombre,'' otros_nombres, EMPRESA.NOMBRE_EMPRESA,EMPRESA.DIRECCION,EMPRESA.COD_DEPARTAMENTO,
EMPRESA.COD_MUNICIPIO,EMPRESA.COD_PAIS pais,LIQUIDACION.SALDO_DEUDA Saldo_cuenta_por_cobrar 
from LIQUIDACION
join EMPRESA on EMPRESA.CODEMPRESA=LIQUIDACION.NITEMPRESA
where LIQUIDACION.SALDO_DEUDA>0 AND  TO_CHAR(LIQUIDACION.FECHA_LIQUIDACION, 'YYYY')<='" . $post['ano'] . "'";
        if ($monto != '')
            $SQL.=" AND SALDO_DEUDA > " . $monto;
        $consulta = $this->db->query($SQL);
        $new_array = $consulta->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function reporte_reciprocas() {
        $post = $this->input->post();

        $where = "";
        if ($post['concepto'] != "") {
            $where.=" AND PAGOSRECIBIDOS.COD_CONCEPTO='" . $post['concepto'] . "'";
        }
        if ($post['sub_concepto'] != "") {
            $where.=" AND PAGOSRECIBIDOS.COD_SUBCONCEPTO='" . $post['sub_concepto'] . "'";
        }
        if ($post['trimestre'] != "") {
            $where.=" AND TO_CHAR(PAGOSRECIBIDOS.FECHA_PAGO, 'MM') <= '" . $post['trimestre'] . "'";
        }
        $array_select = array('0', 'CONSTANTE', 'COD CUENTA', 'NIT', 'VALOR CORRIENTE', 'VALOR NO CORRIENTE');

        $SQL = "SELECT 'D' constante,CUENTAS_CONTABLES.COD_CUENTA, PAGOSRECIBIDOS.NITEMPRESA NIT,
sum(DECODE(CUENTAS_CONTABLES.CORRIENTE,0, PAGOSRECIBIDOS.VALOR_PAGADO,0)) Valor_corriente,
sum(DECODE(CUENTAS_CONTABLES.CORRIENTE,1, PAGOSRECIBIDOS.VALOR_PAGADO,0)) Valor_no_corriente
from PAGOSRECIBIDOS, CUENTAS_CONTABLES, EMPRESA 
WHERE CUENTAS_CONTABLES.COD_CONCEPTO=PAGOSRECIBIDOS.COD_SUBCONCEPTO
AND EMPRESA.CODEMPRESA=PAGOSRECIBIDOS.NITEMPRESA
AND EMPRESA.RECIPROCA = '1' 
AND TO_CHAR(PAGOSRECIBIDOS.FECHA_PAGO, 'YYYY') = '" . $post['ano_salario'] . "'" . $where . "
GROUP BY CUENTAS_CONTABLES.COD_CUENTA, PAGOSRECIBIDOS.NITEMPRESA
ORDER BY CUENTAS_CONTABLES.COD_CUENTA, PAGOSRECIBIDOS.NITEMPRESA";

        $consulta = $this->db->query($SQL);
        $new_array = $consulta->result_array;
        if (count($new_array) == 0) {
            $new_array = $array_select;
        }
        return $new_array;
    }

    function procedencia() {
        $this->db->select('PROCEDENCIA');
        $this->db->group_by('PROCEDENCIA');
        $dato = $this->db->get("PAGOSRECIBIDOS");
        return $datos = $dato->result_array;
    }

    function buscar_certificado() {
        $post = $this->input->post();
        $this->db->select('COUNT(COD_VERIFICACION) COD_VERIFICACION');
        $this->db->where('COD_CERTIFICADO', $post['numero']);
        $this->db->where('COD_VERIFICACION', $post['codigo']);
        $dato = $this->db->get("CERTIFICACIONES");
        return $datos = $dato->result_array[0]['COD_VERIFICACION'];
    }

    function burcar_no_misional_t_cartera2($post = null) {
//        idusuario
        $post = explode(" - ", $post);
        $SQL = "SELECT TIPOCARTERA.COD_TIPOCARTERA,TIPOCARTERA.NOMBRE_CARTERA TIPO_CARTERA
from CNM_CARTERANOMISIONAL
JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
where (CNM_CARTERANOMISIONAL.COD_EMPRESA='" . $post[0] . "' or CNM_CARTERANOMISIONAL.COD_EMPLEADO='" . $post[0] . "')"
                . "GROUP BY TIPOCARTERA.COD_TIPOCARTERA,TIPOCARTERA.NOMBRE_CARTERA 
                    ORDER BY TIPOCARTERA.NOMBRE_CARTERA ";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;

        return $datos;
    }

    function burcar_no_misional_t_cartera() {
        $post = $this->input->post();
//        idusuario
        $post['idusuario'] = explode(" - ", $post['idusuario']);
        $SQL = "SELECT TIPOCARTERA.COD_TIPOCARTERA,TIPOCARTERA.NOMBRE_CARTERA TIPO_CARTERA
from CNM_CARTERANOMISIONAL
JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
where (CNM_CARTERANOMISIONAL.COD_EMPRESA='" . $post['idusuario'][0] . "' or CNM_CARTERANOMISIONAL.COD_EMPLEADO='" . $post['idusuario'][0] . "') 
		AND CNM_CARTERANOMISIONAL.COD_ESTADO  not in ('1','3','4')"
                . "GROUP BY TIPOCARTERA.COD_TIPOCARTERA,TIPOCARTERA.NOMBRE_CARTERA 
                    ORDER BY TIPOCARTERA.NOMBRE_CARTERA ";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        $html = "<option value=''></option>";
        foreach ($datos as $value) {
            $html.="<option value='" . $value['COD_TIPOCARTERA'] . "'>" . $value['TIPO_CARTERA'] . "</option>";
        }
        return $html;
    }

    function burcar_no_misional_deuda() {
        $post = $this->input->post();
//        idusuario
        $post['idusuario'] = explode(" - ", $post['idusuario']);
        $SQL = "SELECT COD_CARTERA_NOMISIONAL
from CNM_CARTERANOMISIONAL
JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
JOIN CNM_CUOTAS ON CNM_CUOTAS.ID_DEUDA_E=CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL
where (CNM_CARTERANOMISIONAL.COD_EMPRESA='" . $post['idusuario'][0] . "' or CNM_CARTERANOMISIONAL.COD_EMPLEADO='" . $post['idusuario'][0] . "') and "
                . "TIPOCARTERA.COD_TIPOCARTERA='" . $post['concepto2'] . "' "
                . "GROUP BY COD_CARTERA_NOMISIONAL ORDER BY COD_CARTERA_NOMISIONAL ";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
        $html = "<option value=''></option>";
        foreach ($datos as $value) {
            $html.="<option value='" . $value['COD_CARTERA_NOMISIONAL'] . "'>" . $value['COD_CARTERA_NOMISIONAL'] . "</option>";
        }
        return $html;
    }
	
	    function buscar_tipo_cert_nm() {
        $post = $this->input->post();
//        idusuario

        $SQL = "SELECT COD_ESTADO
FROM CNM_CARTERANOMISIONAL
where CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL='" . $post['id_deuda'] . "'";
        $consulta = $this->db->query($SQL);
        $datos = $consulta->result_array;
		$html = "<option value=''></option>";
		if($post['concepto2']==8){
		if($datos[0]['COD_ESTADO']==4){
		$html.="<option value='1'>Certificación Estado de Cuenta a la fecha</option>";
		$html.="<option value='2'>Certificación Estado de Cuenta Anual</option>";
		$html.="<option value='3'>Certificación Deuda Saldada</option>";
		$html.="<option value='4'>Certificación Deuda Saldada para liberación</option>";
		}
		else{
		$html.="<option value='1'>Certificación Estado de Cuenta a la fecha</option>";
		$html.="<option value='2'>Certificación Estado de Cuenta Anual</option>";
		}
		
		}
		else{
		$html.="<option value='1'>Certificación Estado de Cuenta a la fecha</option>";
		$html.="<option value='2'>Certificación Estado de Cuenta Anual</option>";
		}

        return $html;
    }

    function burcar_no_misional_deuda2($concepto2, $idusuario) {
        $post = $this->input->post();
//        idusuario
        $idusuario = explode(" - ", $idusuario);
        $SQL = "SELECT COD_CARTERA_NOMISIONAL
from CNM_CARTERANOMISIONAL
JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA
JOIN CNM_CUOTAS ON CNM_CUOTAS.ID_DEUDA_E=CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL
where (CNM_CARTERANOMISIONAL.COD_EMPRESA='" . $idusuario[0] . "' or CNM_CARTERANOMISIONAL.COD_EMPLEADO='" . $idusuario[0] . "') and "
                . "TIPOCARTERA.COD_TIPOCARTERA='" . $concepto2 . "' "
                . "GROUP BY COD_CARTERA_NOMISIONAL ORDER BY COD_CARTERA_NOMISIONAL ";
        $consulta = $this->db->query($SQL);
        return $datos = $consulta->result_array;
    }

	    function r_cartera_no_mis_mensual() {
        $post = $this->input->post();

        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
        if ($post['conceptonm'] != "-1") {

            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['conceptonm']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }

		if(!empty($regional)&&isset($post['regional'][0])){
		if ($post['regional'][0] != "-1") {
            $regional = $post['regional'][0];
			$this->db->where('CNM_CARTERANOMISIONAL.COD_REGIONAL', $regional, FALSE);
        }
		}
		if ($post['estado_e'] != "-1") {
            $estado_e = $post['estado_e'][0];
			$this->db->where('CNM_EMPLEADO.COD_ESTADO_E', $estado_e);
        }

        $this->db->select(' 
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR,

TIPOCARTERA.NOMBRE_CARTERA CONCEPTO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL IDENTIFICACION_DE_LA_DEUDA, 
CNM_CUOTAS.NO_CUOTA NUM_CUOTA,
CNM_CUOTAS.MES_PROYECTADO PERIODO,
CNM_CUOTAS.CAPITAL VALOR_ANT_CAPITAL,
CNM_CUOTAS.VALOR_CUOTA CUOTA_PACTADA,
CNM_CUOTAS.VALOR_CUOTA-CNM_CUOTAS.SALDO_CUOTA CUOTA_CANCELADA,
DECODE(CNM_CUOTAS.CESANTIAS_APLICADAS, 1,CNM_CUOTAS.CESANTIAS,0) CESANTIAS_APLICADAS,
CNM_CUOTAS.VALOR_INTERES_C-CNM_CUOTAS.SALDO_INTERES_C INTERES_CORRIENTE_CANCELADO,
CNM_CUOTAS.AMORTIZACION-CNM_CUOTAS.SALDO_AMORTIZACION AMORTIZACION_A_CAPITAL,
CNM_CUOTAS.SALDO_FINAL_CAP NUEVO_SALDO_CAPITAL,
CNM_CUOTAS.VALOR_INTERES_NO_PAGOS SALDO_INTERESES_NO_PAGOS_ACUM', false);
$this->db->join('TIPO_TASA_HISTORICA', 'TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST=CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE', 'left');
$this->db->join('TIPOTASAINTERES', 'TIPOTASAINTERES.COD_TIPO_TASAINTERES=CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE', 'left');
$this->db->join('CNM_CUOTAS', 'CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E');
$this->db->join('REGIONAL', 'REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL');
$this->db->join('TIPOCARTERA', 'TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA');
$this->db->join('CNM_EMPRESA', 'CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA', 'left');
$this->db->join('CNM_EMPLEADO', 'CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO', 'left');
$this->db->join("CNM_ESTADO_EMP", "CNM_ESTADO_EMP.COD_ESTADO_E=CNM_EMPLEADO.COD_ESTADO_E", 'LEFT');
$this->db->join('MUNICIPIO', 'MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO');
$this->db->join('DEPARTAMENTO', 'DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO');
$this->db->where("CNM_CUOTAS.CONCEPTO  not in ('12','13') AND (CNM_CUOTAS.VALOR_CUOTA<>CNM_CUOTAS.SALDO_CUOTA OR CNM_CUOTAS.CESANTIAS_APLICADAS=1) 
AND 1=", 1, false);
		
$this->db->order_by("CNM_CUOTAS.MES_PROYECTADO", false);

$consulta = $this->db->get("CNM_CARTERANOMISIONAL");

        return $datos = $consulta->result_array;
    }
	
    function r_cartera_no_mis_liquidacion() {
        $post = $this->input->post();

        $SQL="SELECT 
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR,

TIPOCARTERA.NOMBRE_CARTERA CONCEPTO,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL IDENTIFICACION_DE_LA_DEUDA, 
CNM_CUOTAS.NO_CUOTA NUM_CUOTA,
CNM_CUOTAS.MES_PROYECTADO PERIODO,
CNM_CUOTAS.CAPITAL VALOR_ANT_CAPITAL,
CNM_CUOTAS.VALOR_CUOTA CUOTA_PACTADA,
CNM_CUOTAS.VALOR_CUOTA-CNM_CUOTAS.SALDO_CUOTA CUOTA_CANCELADA,
DECODE(CNM_CUOTAS.CESANTIAS_APLICADAS, 1,CNM_CUOTAS.CESANTIAS,0) CESANTIAS_APLICADAS,
CNM_CUOTAS.VALOR_INTERES_C-CNM_CUOTAS.SALDO_INTERES_C INTERES_CORRIENTE_CANCELADO,
CNM_CUOTAS.AMORTIZACION-CNM_CUOTAS.SALDO_AMORTIZACION AMORTIZACION_A_CAPITAL,
CNM_CUOTAS.SALDO_FINAL_CAP NUEVO_SALDO_CAPITAL,
CNM_CUOTAS.VALOR_INTERES_NO_PAGOS SALDO_INTERESES_NO_PAGOS_ACUM
FROM CNM_CARTERANOMISIONAL
LEFT JOIN TIPO_TASA_HISTORICA ON  TIPO_TASA_HISTORICA.COD_TIPO_TASA_HIST=CNM_CARTERANOMISIONAL.TIPO_T_V_CORRIENTE
LEFT JOIN TIPOTASAINTERES ON TIPOTASAINTERES.COD_TIPO_TASAINTERES=CNM_CARTERANOMISIONAL.TIPO_T_F_CORRIENTE
JOIN CNM_CUOTAS ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E 
JOIN REGIONAL ON REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL 
JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA 
LEFT JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA 
LEFT JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO 
JOIN MUNICIPIO ON MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO 
JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO 
where COD_CARTERA_NOMISIONAL='" . $post['id_deuda'] . "' AND CNM_CUOTAS.CONCEPTO  not in ('12','13') 
AND (CNM_CUOTAS.VALOR_CUOTA<>CNM_CUOTAS.SALDO_CUOTA OR CNM_CUOTAS.CESANTIAS_APLICADAS=1)
ORDER BY CNM_CUOTAS.MES_PROYECTADO ";
        $consulta = $this->db->query($SQL);
        return $datos = $consulta->result_array;
    }
	
	    function r_cartera_no_misional_gen() {
		$array_select = array('0', 'COD DEUDOR', 'CONCEPTO', 'FECHA TRANSACCIÓN', 'IDENTIFICACION DE LA DEUDA', 'NUM CUOTA',
            'PERIODO', 'VALOR CANCELADO', 'NUMERO DE TRANSACCIÓN', 'PROCEDENCIA', 'REGIONAL', 'ESTADO FUNCIONARIO');
		
        $post = $this->input->post();
				        if (!empty($post['empleado'])) {
            $cedula = explode(' - ', $post['empleado']);
            $this->db->where('NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA,CNM_CARTERANOMISIONAL.COD_EMPLEADO)', $cedula[0], FALSE);
        }
		
        if ($post['conceptonm'] != "-1") {

            $this->db->where('CNM_CUOTAS.CONCEPTO', $post['conceptonm']);
        }
        if (!empty($post['num_obligacion'])) {
            $this->db->where('CNM_CUOTAS.ID_DEUDA_E', $post["num_obligacion"]);
        }

		
		if ($post['estado_e'] != "-1") {
            $estado_e = $post['estado_e'][0];
			$this->db->where('CNM_EMPLEADO.COD_ESTADO_E', $estado_e);
        }
		
		if(!empty($regional)&&isset($post['regional'][0])){
		if ($post['regional'][0] != "-1") {
            $regional = $post['regional'][0];
			$this->db->where('CNM_CARTERANOMISIONAL.COD_REGIONAL', $regional, FALSE);
        }
		}
        $this->db->select(' 
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO ) COD_DEUDOR,
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)) AS NOMBRE_DEUDOR, 
TIPOCARTERA.NOMBRE_CARTERA CONCEPTO,
to_char(PAGOSRECIBIDOS.FECHA_TRANSACCION) AS FECHA_TRANSACCION,
CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL IDENTIFICACION_DE_LA_DEUDA, 
CNM_CUOTAS.NO_CUOTA NUM_CUOTA,
CNM_CUOTAS.MES_PROYECTADO PERIODO,
PAGOSRECIBIDOS.VALOR_PAGADO VALOR_CANCELADO,
PAGOSRECIBIDOS.COD_PAGO NUMERO_TRANSACCION,
PAGOSRECIBIDOS.PROCEDENCIA PROCEDENCIA,
REGIONAL.NOMBRE_REGIONAL REGIONAL,
CNM_ESTADO_EMP.NOMBRE_ESTADO_E ESTADO_FUNCIONARIO', false);

$this->db->join("CNM_CUOTAS", "CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E"); 
$this->db->join("PAGOSRECIBIDOS", "CNM_CUOTAS.ID_DEUDA_E=PAGOSRECIBIDOS.NRO_REFERENCIA AND PAGOSRECIBIDOS.NRO_CUOTA=CNM_CUOTAS.NO_CUOTA");
$this->db->join("REGIONAL", "REGIONAL.COD_REGIONAL=PAGOSRECIBIDOS.COD_REGIONAL"); 
$this->db->join("TIPOCARTERA", "TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA");
$this->db->join("CNM_EMPRESA", "CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA", "left"); 
$this->db->join("CNM_EMPLEADO", "CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO", "left"); 
$this->db->join("MUNICIPIO", "MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO"); 
$this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO");
$this->db->join("CNM_ESTADO_EMP", "CNM_ESTADO_EMP.COD_ESTADO_E=CNM_EMPLEADO.COD_ESTADO_E", "left");
$this->db->where ("CNM_CUOTAS.CONCEPTO  not in ('12','13') 
AND (CNM_CUOTAS.VALOR_CUOTA<>CNM_CUOTAS.SALDO_CUOTA OR CNM_CUOTAS.CESANTIAS_APLICADAS=1) AND 1=", 1, false);
$this->db->order_by("CNM_CUOTAS.MES_PROYECTADO, fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO )) ", false);
$consulta = $this->db->get("CNM_CARTERANOMISIONAL");
		//echo $this->db->last_query();
		//die();
        $datos = $consulta->result_array;
        if (count($datos) == 0) {
            $datos = $array_select;
        }
        return $datos;

    }

    function ingresos_tipo_registro($anos, $whe, $post) {

//        echo "<pre>";
//        print_r($post);
//        echo "</pre>";

        if ($post['tipo_registro'] == 1) {
            $select = "PLANILLAUNICA_ENC.COD_PLANILLAUNICA	,
PLANILLAUNICA_ENC.N_REGISTROAPORTANTE	REGISTRO_APORTANTE,
PLANILLAUNICA_ENC.TIPO_REGISTRO	,
PLANILLAUNICA_ENC.COG_FORMATO	C_FORMATO,
PLANILLAUNICA_ENC.N_IDENT_CCF_ICBF_	CCF_ICBF ,
PLANILLAUNICA_ENC.DIG_VERIF_NIT	 DIGITO_VERIFICACION,
PLANILLAUNICA_ENC.NOM_APORTANTE	NOMBRE_APORTANTE,
PLANILLAUNICA_ENC.TIPO_DOC_APORTANTE	,
PLANILLAUNICA_ENC.N_INDENT_APORTANTE	IDENTIFICACION_APORTANTE,
PLANILLAUNICA_ENC.DIG_VERIF_APORTANTE	,
PLANILLAUNICA_ENC.TIPO_APORTANTE	,
PLANILLAUNICA_ENC.DIREC_CORRESPONDENCIA	DIRECCION_CORRESPONDENCIA,
PLANILLAUNICA_ENC.COD_CIU_O_MUN	 COD_CIUDAD,
PLANILLAUNICA_ENC.COD_DEPARTAMENTO	,
PLANILLAUNICA_ENC.TELEFONO	,
PLANILLAUNICA_ENC.FAX	,
PLANILLAUNICA_ENC.CORREO_ELECTRO	CORREO_ELECTRONICO,
PLANILLAUNICA_ENC.PERIDO_PAGO	,
PLANILLAUNICA_ENC.TIPO_PLANILLA	,
PLANILLAUNICA_ENC.FECHA_PAGO_PLANILLA	,
PLANILLAUNICA_ENC.FECHA__PAGO	,
PLANILLAUNICA_ENC.N_PLANILLA_	NUMERO_DE_LA_PLANILLA ,
PLANILLAUNICA_ENC.N_RADICACION	NUMERO_DE_RADICADO,
PLANILLAUNICA_ENC.FORMA_PRESENTACION	,
PLANILLAUNICA_ENC.COD_SUCURSAL	,
PLANILLAUNICA_ENC.NOM_SUCURSAL	,
PLANILLAUNICA_ENC.N_TOTAL_EMPLEADOS	,
PLANILLAUNICA_ENC.N_TOTAL_APFIALIADO N_TOTAL_AFIALIADO	,
PLANILLAUNICA_ENC.COD_OPERADOR	,
PLANILLAUNICA_ENC.MODALID_PLANILLA	MODALIDAD_PLANILLA,
PLANILLAUNICA_ENC.DIAS_MORA	,
PLANILLAUNICA_ENC.N_REGIS_TIPO_2	,
PLANILLAUNICA_ENC.ESTADO	,
PLANILLAUNICA_ENC.ARCHIVO	,
PLANILLAUNICA_ENC.APORTE_OBLIG	,
PLANILLAUNICA_ENC.FECHA_CREACION	,
PLANILLAUNICA_ENC.ID_USUARIO	,
PLANILLAUNICA_ENC.ID_USUARIO
";
            $group = " Group by PLANILLAUNICA_ENC.COD_PLANILLAUNICA	,
PLANILLAUNICA_ENC.N_REGISTROAPORTANTE ,
PLANILLAUNICA_ENC.TIPO_REGISTRO	,
PLANILLAUNICA_ENC.COG_FORMATO	,
PLANILLAUNICA_ENC.N_IDENT_CCF_ICBF_	,
PLANILLAUNICA_ENC.DIG_VERIF_NIT	,
PLANILLAUNICA_ENC.NOM_APORTANTE	,
PLANILLAUNICA_ENC.TIPO_DOC_APORTANTE	,
PLANILLAUNICA_ENC.N_INDENT_APORTANTE	,
PLANILLAUNICA_ENC.DIG_VERIF_APORTANTE	,
PLANILLAUNICA_ENC.TIPO_APORTANTE	,
PLANILLAUNICA_ENC.DIREC_CORRESPONDENCIA	,
PLANILLAUNICA_ENC.COD_CIU_O_MUN	,
PLANILLAUNICA_ENC.COD_DEPARTAMENTO	,
PLANILLAUNICA_ENC.TELEFONO	,
PLANILLAUNICA_ENC.FAX	,
PLANILLAUNICA_ENC.CORREO_ELECTRO	,
PLANILLAUNICA_ENC.PERIDO_PAGO	,
PLANILLAUNICA_ENC.TIPO_PLANILLA	,
PLANILLAUNICA_ENC.FECHA_PAGO_PLANILLA	,
PLANILLAUNICA_ENC.FECHA__PAGO	,
PLANILLAUNICA_ENC.N_PLANILLA_	,
PLANILLAUNICA_ENC.N_RADICACION	,
PLANILLAUNICA_ENC.FORMA_PRESENTACION	,
PLANILLAUNICA_ENC.COD_SUCURSAL	,
PLANILLAUNICA_ENC.NOM_SUCURSAL	,
PLANILLAUNICA_ENC.N_TOTAL_EMPLEADOS	,
PLANILLAUNICA_ENC.N_TOTAL_APFIALIADO	,
PLANILLAUNICA_ENC.COD_OPERADOR	,
PLANILLAUNICA_ENC.MODALID_PLANILLA	,
PLANILLAUNICA_ENC.DIAS_MORA	,
PLANILLAUNICA_ENC.N_REGIS_TIPO_2	,
PLANILLAUNICA_ENC.ESTADO	,
PLANILLAUNICA_ENC.ARCHIVO	,
PLANILLAUNICA_ENC.APORTE_OBLIG	,
PLANILLAUNICA_ENC.FECHA_CREACION	,
PLANILLAUNICA_ENC.ID_USUARIO	,
PLANILLAUNICA_ENC.ID_USUARIO";
        } else if ($post['tipo_registro'] == 2) {
            $select = "PLANILLAUNICA_DET.COD_PLANILLAUNICA	,
PLANILLAUNICA_DET.SECUENCIA	,
PLANILLAUNICA_DET.TIPO_REGISTRO	,
PLANILLAUNICA_DET.TIPO_IDENT_COTIZ	TIPO_TIDENTIFICACION_COTIZANTE,
PLANILLAUNICA_DET.N_IDENT_COTIZ	 N_IDENTTIFICACION_COTIZANTE,
PLANILLAUNICA_DET.TIPO_COTIZ	TIPO_COTIZANTE,
PLANILLAUNICA_DET.SUBTIPO_COTIZ	SUBTIPO_COTIZANTE,
PLANILLAUNICA_DET.EXTRAN_NO_OBLIG_COTIZ_PENS	EXTRANGERO_NO_OBLIGADO_COTIZAR,
PLANILLAUNICA_DET.COLOM_RESID_EXTER COLOMBIANO_RESIDENTE_EXTERIOR	,
PLANILLAUNICA_DET.COD_DEPARTA_UBI_LAB	COD_DEPARTAMENTO,
PLANILLAUNICA_DET.COD_MUNI_UBI_LAB_	COD_MUNICIPIO,
PLANILLAUNICA_DET.PRIMER_APELLIDO	,
PLANILLAUNICA_DET.SEGUN_APELLIDO	SEGUNDO_APELLIDO,
PLANILLAUNICA_DET.PRIMER_NOMBRE	,
PLANILLAUNICA_DET.SEGUN_NOMBRE	 SEGUNDO_NOMBRE,
PLANILLAUNICA_DET.ING	INGRESO,
PLANILLAUNICA_DET.RET	RETIRO,
PLANILLAUNICA_DET.VSP	VARIACION_SALARIO_PERMANENTE,
PLANILLAUNICA_DET.VST	VARIACION_SALARIO_TOTAL,
PLANILLAUNICA_DET.SLN	SUSPENCION_CONTRATO_TEMPORAL,
PLANILLAUNICA_DET.IGE	INCAP_TEMPORAL_POR_ENFERMEDAD,
PLANILLAUNICA_DET.LMA	LICENCIA_DE_MATERNIDAD,
PLANILLAUNICA_DET.CORRECCIONES	,
PLANILLAUNICA_DET.IRP	,
PLANILLAUNICA_DET.DIAS_COTIZ	DIAS_COTIZADOS,
PLANILLAUNICA_DET.SALARIO_BASICO	,
PLANILLAUNICA_DET.ING_BASE_COTIZ	INGRESO_BASE_COTIZANTE,
PLANILLAUNICA_DET.TARIFA	,
PLANILLAUNICA_DET.APORTE_OBLIG	APORTE_OBLIGACION,
PLANILLAUNICA_DET.VARIACIONES	,
PLANILLAUNICA_DET.SALARIO_INTEGRAL	,
PLANILLAUNICA_DET.ARCHIVO	
";
            $group = "";
        } else {
            $select = "REGISTROTIPO3.COD_CAMPO	COD_PLANILLAUNICA,
REGISTROTIPO3.REGIS_TOTAL_PERI_DECLA	,
REGISTROTIPO3.TIPO_REGISTRO	,
REGISTROTIPO3.INGRESO	,
REGISTROTIPO3.APORTE_OBLIG	,
REGISTROTIPO3.REGIS_INTERES_MORA	REGISTRO_INTERES_MORA,
REGISTROTIPO3.N_DIAS_MORA_LIQUIDADO	,
REGISTROTIPO3.MORA_APORTES	,
REGISTROTIPO3.REGIS_TOTAL_PAGAR	 REGISTRO_TOTAL_PAGAR,
REGISTROTIPO3.TOTAL_APORTES	,
REGISTROTIPO3.ARCHIVO	,
REGISTROTIPO3.FECHA_CREACION";
            $group = " Group by REGISTROTIPO3.COD_CAMPO	,
REGISTROTIPO3.REGIS_TOTAL_PERI_DECLA	,
REGISTROTIPO3.TIPO_REGISTRO	,
REGISTROTIPO3.INGRESO	,
REGISTROTIPO3.APORTE_OBLIG	,
REGISTROTIPO3.REGIS_INTERES_MORA	,
REGISTROTIPO3.N_DIAS_MORA_LIQUIDADO	,
REGISTROTIPO3.MORA_APORTES	,
REGISTROTIPO3.REGIS_TOTAL_PAGAR	,
REGISTROTIPO3.TOTAL_APORTES	,
REGISTROTIPO3.ARCHIVO	,
REGISTROTIPO3.FECHA_CREACION	
";
        }

        $SQL = "select " . $select . "
from PLANILLAUNICA_ENC
JOIN REGISTROTIPO3 ON REGISTROTIPO3.COD_CAMPO=PLANILLAUNICA_ENC.COD_PLANILLAUNICA
JOIN PLANILLAUNICA_DET ON PLANILLAUNICA_DET.COD_PLANILLAUNICA=PLANILLAUNICA_ENC.COD_PLANILLAUNICA
where " . $whe . " " . $group;
        $consulta = $this->db->query($SQL);
        return $datos = $consulta->result_array;
    }

    function empresa_consulta_no_misional($post) {
        $empleado = $post["empleado"];
        $empleado = explode(" - ", $post["empleado"]);
        $SQL = "SELECT 
            CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL, REGIONAL.NOMBRE_REGIONAL,
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO) COD_DEUDOR, 
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)) AS NOMBRE_DEUDOR, 
TIPOCARTERA.NOMBRE_CARTERA TIPO_CARTERA,
CNM_CARTERANOMISIONAL.VALOR_DEUDA VALOR_PRESTAMO,
CNM_CARTERANOMISIONAL.FECHA_ACTIVACION FECHA_DESEMBOLSO,
CNM_CARTERANOMISIONAL.SALDO_DEUDA SALDO,
CNM_CARTERANOMISIONAL.SALDO_INTERES_ACUMULADO+CNM_CARTERANOMISIONAL.SALDO_INTERES_MORATORIO INTERESES_A_LA_FECHA
FROM CNM_CARTERANOMISIONAL
JOIN CNM_CUOTAS ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E
JOIN REGIONAL ON REGIONAL.COD_REGIONAL=CNM_CARTERANOMISIONAL.COD_REGIONAL 
JOIN TIPOCARTERA ON TIPOCARTERA.COD_TIPOCARTERA=CNM_CARTERANOMISIONAL.COD_TIPOCARTERA 
LEFT JOIN CNM_EMPRESA ON CNM_EMPRESA.COD_ENTIDAD=CNM_CARTERANOMISIONAL.COD_EMPRESA 
LEFT JOIN CNM_EMPLEADO ON CNM_EMPLEADO.IDENTIFICACION=CNM_CARTERANOMISIONAL.COD_EMPLEADO 
JOIN MUNICIPIO ON MUNICIPIO.CODMUNICIPIO=REGIONAL.COD_CIUDAD AND MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO 
JOIN DEPARTAMENTO ON DEPARTAMENTO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO 
where (CNM_CARTERANOMISIONAL.COD_EMPRESA='".$empleado[0]."' or CNM_CARTERANOMISIONAL.COD_EMPLEADO='".$empleado[0]."') "
                . " and CNM_CARTERANOMISIONAL.COD_TIPOCARTERA='".$post['concepto2']."' 
				and CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL='".$post['id_deuda']."'"
                . " 
				group BY CNM_CUOTAS.ID_DEUDA_E, CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL,
NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO),
fn_Razon_Social(NVL(CNM_CARTERANOMISIONAL.COD_EMPRESA, CNM_CARTERANOMISIONAL.COD_EMPLEADO)), 
TIPOCARTERA.NOMBRE_CARTERA, CNM_CARTERANOMISIONAL.VALOR_DEUDA, 
CNM_CARTERANOMISIONAL.FECHA_ACTIVACION, CNM_CARTERANOMISIONAL.SALDO_DEUDA, 
CNM_CARTERANOMISIONAL.SALDO_INTERES_ACUMULADO+CNM_CARTERANOMISIONAL.SALDO_INTERES_MORATORIO, REGIONAL.NOMBRE_REGIONAL";
        $consulta = $this->db->query($SQL);
        return $datos = $consulta->result_array;
    }
	
	    function empresa_consulta_no_misional_2($post) {
        $empleado = $post["empleado"];
        $empleado = explode(" - ", $post["empleado"]);
        $SQL = "SELECT 
            CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL,
SUM(CNM_CUOTAS.SALDO_INTERES_C) INTERES_C
FROM CNM_CARTERANOMISIONAL
JOIN CNM_CUOTAS ON CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL=CNM_CUOTAS.ID_DEUDA_E
where (CNM_CARTERANOMISIONAL.COD_EMPRESA='".$empleado[0]."' or CNM_CARTERANOMISIONAL.COD_EMPLEADO='".$empleado[0]."') "
                . " and CNM_CARTERANOMISIONAL.COD_TIPOCARTERA='".$post['concepto2']."' 
				and CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL='".$post['id_deuda']."'"
                . " 
				and SYSDATE-CNM_CUOTAS.FECHA_LIM_PAGO > 0 AND CNM_CARTERANOMISIONAL.COD_ESTADO  not in ('1','3','4') 
		HAVING SUM(DECODE(CNM_CUOTAS.SALDO_CUOTA, 0, 0, 1))>0
				
				group BY CNM_CUOTAS.ID_DEUDA_E, CNM_CARTERANOMISIONAL.COD_CARTERA_NOMISIONAL";
        $consulta = $this->db->query($SQL);

        return $datos = $consulta->result_array;
    }
	
		    function ultimo_pago_cert($post) {
        $deuda = $post["id_deuda"];

        $SQL = "SELECT MAX(FECHA_APLICACION) AS Mayor
FROM (
SELECT MAX(FECHA_PAGO) AS FECHA_APLICACION 
FROM SENA.CNM_CUOTAS_KACTUS 
WHERE (ID_DEUDA_K= 587) 
AND CONCEPTO NOT IN (12, 13)
UNION ALL(
SELECT MAX(FECHA_APLICACION) AS FECHA_APLICACION 
FROM SENA.CNM_CESANTIAS_APLICADAS 
WHERE (ID_DEUDA= 587) 
)
UNION ALL(
SELECT MAX(FECHA_APLICACION) AS FECHA_APLICACION 
FROM SENA.PAGOSRECIBIDOS 
WHERE (NRO_REFERENCIA= 587) 
)
)";
        $consulta = $this->db->query($SQL);

        return $datos = $consulta->result_array;
    }
    function ingresos_por_sql($post){
        //echo $post['consultass'].'<br>';
         $SQL=  base64_decode($post['consultass']);
        
         $SQL=  str_replace("and  ROWNUM<400", '', $SQL);
        $consulta = $this->db->query($SQL);
        return $datos = $consulta->result_array;
    }

}
