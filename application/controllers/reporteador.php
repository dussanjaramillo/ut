<?php

class Reporteador extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha_helper', 'template_helper'));
        $this->load->model('titulo_model', '', TRUE);
        $this->load->model('Reporteador_model', '', TRUE);
        $this->load->model('numeros_letras_model');
        $this->load->library('tcpdf/tcpdf.php');

        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
//            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validCampoFranz.js',
            'js/validateForm.js',
            'js/ajaxfileupload.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen',
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
        define("RUTA_INI", "./uploads/fiscalizaciones/reportes");
    }

    function index() {
        $this->Listar_Menu();
    }

    function Listar_Menu() {
        $this->activo();
        redirect(base_url() . 'index.php/inicio');
        $this->data['titulo'] = '';
        $this->template->set('title', 'Generador de Reportes');
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function activo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reporteador/index')) {
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function activo2($url) {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('reporteador/' . $url)) {
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function opcion() {
        $this->data['post'] = $this->input->post();
        switch ($this->data['post']['reporte']) {
            case 3:
                return $this->resolucion();
                break;
        }
    }

    function resolucion() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $info = $this->Reporteador_model->resolucion($this->data);
        $bodytag = array_replace($info, "", "0");

        return $this->output->set_content_type('application/json')->set_output(json_encode($bodytag));

//        $dato=$this->retornar($info);
//        return $dato;
    }

    function getmenus() {
        $id = $this->session->userdata('user_id');
        $id = intval($id);
        $this->db->select('ME.IDMENU,ME.NOMBREMENU,ME.URL,ME.ICONOMENU,MO.NOMBREMODULO,MO.IDMODULO AS MODULOID,MO.URL AS MODULOURL,MO.ICONOMODULO,AP.NOMBREAPLICACION,AP.IDAPLICACION AS APLICACIONID,AP.URL AS URLAPLICACION,AP.ICONOAPLICACION,MP.CODMACROPROCESO,MP.NOMBREMACROPROCESO,MP.ICONO AS ICONOMACROPROCESO,AP.CODPROCESO');
        $this->db->from('MENUS ME');
        $this->db->join('MODULOS MO', 'ME.IDMODULO=MO.IDMODULO AND MO.IDESTADO=1 AND MO.NOMBREMODULO="reporteador"', 'inner');
        $this->db->join('APLICACIONES AP', 'MO.IDAPLICACION=AP.IDAPLICACION AND AP.IDESTADO=1', 'inner');
        $this->db->join('MACROPROCESO MP', 'AP.CODPROCESO=MP.CODMACROPROCESO AND MP.IDESTADO=1', 'inner');
        $this->db->join('PERMISOS_USUARIOS PU', "ME.IDMENU=PU.IDMENU AND PU.IDUSUARIO='" . $id . "'", 'inner');
        $this->db->where('ME.IN_MENU', 1);
        $this->db->where('ME.IDESTADO', 1);
        $this->db->order_by("AP.IDAPLICACION", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function empresa() {
        return $this->Reporteador_model->empresa();
    }

    function concepto($dato = null) {
        return $this->Reporteador_model->concepto($dato);
    }

    function cuentas_contables() {
        return $this->Reporteador_model->cuentas_contables();
    }

    function concepto_general() {
        return $this->Reporteador_model->concepto_general();
    }

    function tipocartera() {
        return $this->Reporteador_model->tipocartera();
    }
	
	function tipocartera2() {
        return $this->Reporteador_model->tipocartera2();
    }
	function estadoempleado() {
        return $this->Reporteador_model->estadoempleado();
    }

    function TIPOCONCEPTO() {
        return $this->Reporteador_model->TIPOCONCEPTO();
    }

    function TIPOSUBCONCEPTO() {
        return $this->Reporteador_model->TIPOSUBCONCEPTO();
    }

    function tipodevolucion() {
        return $this->Reporteador_model->tipodevolucion();
    }

    function regional() {
        return $this->Reporteador_model->regional();
    }

    function info_subconcepto() {
        $post = $this->input->post();
        return $this->Reporteador_model->info_subconcepto($post);
    }

    function traer_subconcepto() {
        $post = $this->input->post();
        echo $this->Reporteador_model->traer_subconcepto($post);
    }

    function reporte() {
        $this->data['post'] = $this->input->post();
		//var_dump($this->data['post']);
		//die();
        $post = $this->data['post'];
        if (isset($post["empresa"])) {
            if ($post["empresa"] != "") {
                $nit_empresa = explode(" - ", $post["empresa"]);
                $this->db->where("EMPRESA.CODEMPRESA ", $nit_empresa[0]);
            }
        }
        if (isset($post["concepto"]))
            if ($post["concepto"] != "-1" && $post["reporte"] != 29)
                $this->db->where('CONCEPTOSFISCALIZACION.COD_CPTO_FISCALIZACION', $post["concepto"]);
        if (isset($post["regional"][0])) {
            if ($post["regional"][0] != "-1")
                $this->db->where_in('REGIONAL.COD_REGIONAL', $post["regional"]);
        }
        $array = array();
        $reporte = 0;
        switch ($post["reporte"]) {
            case 3:
                $informacion = $this->Reporteador_model->reporte_liquidacion();
                break;
            case 4:
                $a = array(1, 2, 3);
                $this->db->where_in('RESOLUCION.COD_CPTO_FISCALIZACION', $a);
                $informacion = $this->Reporteador_model->reporte_resolucion();
                break;
            case 50:
                $a = array(1, 2, 3);
                $this->db->where_in('RESOLUCION.COD_CPTO_FISCALIZACION', $a);
                $this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
                $this->db->where_in('RESOLUCION.COD_CPTO_FISCALIZACION', $a);
                $informacion = $this->Reporteador_model->reporte_resolucion();
                break;
            case 5:
                $otro_selec = "LIQUIDACION.TOTAL_LIQUIDADO AS VALOR_INICIAL_LIQUIDACION,";
                $this->db->where("LIQUIDACION.TOTAL_LIQUIDADO", "LIQUIDACION.SALDO_DEUDA", FALSE);
                $informacion = $this->Reporteador_model->reporte_liquidacion($otro_selec);
                break;

            case 6:// acuerdo de pago
                $this->db->where('ACUERDOPAGO.JURIDICO', 0);
                $informacion = $this->Reporteador_model->resolucion_acuerdopago();
                break;

            case 7://RQ014_CU007_Generar Estado de Cuenta por empresario
                $informacion = $this->Reporteador_model->liquidacion_estado_cuenta();
                break;
            case 8:// GENERAR CERTIFICACIONES PAGOS //// pagos realidados para una liquidacion
                $informacion = $this->Reporteador_model->get_pagos($this->data);
                break;
            case 9:// PAGOS RECIBIDOS 
                $informacion = $this->Reporteador_model->pago_recibidos2($this->data);
                break;
            case 10:// Edad de la cartera
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->edad_cartera_grupal();
                else
                    $informacion = $this->Reporteador_model->edad_cartera();
                break;
            case 12:// Actualizar informacion de contacto y ubicacion aportantes
                //faltan colocar datos de la empresa 
                $this->Reporteador_model->datos_tipoDocumento();
                $this->Reporteador_model->datos_empresa();
                $informacion = $this->Reporteador_model->empresa_general();
                break;
            case 13:// pendiente
                break;
            case 14:// Generar reporte desagregado
                $informacion = $this->Reporteador_model->Generar_reporte_desagregado($this->data);
                break;
            case 16: //Generar informe fiscalización por CIIU
                $informacion = $this->Reporteador_model->informe_fiscalizacion();
                break;
            case 18:// acuerdo de pago en mora
//                $this->db->where('PROYACUPAG_ESTADO', 0);
//                $this->db->where("B.ULTIMO_PAGO <", date('d/m/Y'));
                $this->db->where('ACUERDOPAGO.JURIDICO=0 HAVING MIN(PROYACUPAG_FECHALIMPAGO)< SYSDATE AND 1=', 1, false);
                $informacion = $this->Reporteador_model->resolucion_acuerdopago();
                break;
            case 22: //Generar reporte de recuperacion de cartera
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->generar_recuperacion_cartera2_grupal();
                else
                    $informacion = $this->Reporteador_model->generar_recuperacion_cartera2();
                break;
//            case 23: //Generar reporte comparativo por periodos
//                $informacion = $this->Reporteador_model->generar_recuperacion_cartera();
//                break;
            case 23:
                $informacion = $this->Reporteador_model->comparativo_periodo_model();
                break;
            case 24: //Generar reporte reclasificación de cartera
//                $informacion = $this->Reporteador_model->generar_recuperacion_cartera();
                break;
            case 25: //Generar reporte de gestión
                $informacion = $this->Reporteador_model->generar_reporte_gestion();
                break;
            case 26: //Generar reporte de gestión
                $informacion = $this->Reporteador_model->Generar_Estados_cuenta_Aprendizaje();
                break;
            case 27:
                $informacion = $this->Reporteador_model->proceso_sancionatorio();
                break;
            case 29: //Generar Informe visitas"
                $informacion = $this->Reporteador_model->Generar_Informe_visitas();
                break;
            case 30: //Generar reporte de consulta asignación fiscalización
                $informacion = $this->Reporteador_model->Generar_asignacion_fiscalizacion();
                break;
            case 31: //Generar reporte Estado de fiscalización
                $informacion = $this->Reporteador_model->estado_fiscalizacion();
                break;
            case 32://Generar reporte Gestión por abogado
                $informacion = $this->Reporteador_model->reporte_abogado();
                break;
            case 35:
                $informacion = $this->Reporteador_model->liquidacion_general();
                break;
            case 36: //Generar reporte Resoluciones devueltas Multas Ministerio
                $informacion = $this->Reporteador_model->Generar_Multas_Ministerio();
                break;
            case 37: //Generar reporte Gestión por empresa
                $this->db->select("REGIONAL.COD_REGIONAL,REGIONAL.NOMBRE_REGIONAL REGIONAL,GESTIONCOBRO.NIT_EMPRESA, EMPRESA.NOMBRE_EMPRESA,TIPOGESTION.TIPOGESTION AS INSTANCIA,"
                        . "CONCAT(USUARIOS.APELLIDOS,CONCAT(' ',USUARIOS.NOMBRES)) AS FUNCIONARIO,RESPUESTAGESTION.NOMBRE_GESTION AS ACCIONES,GESTIONCOBRO.FECHA_CONTACTO AS FECHA_GESTION", false);
                if (!isset($post["detalle"])) {
                    $this->db->join("(SELECT MAX(COD_GESTION_COBRO) AS LAST_COD_GESTION
FROM GESTIONCOBRO WHERE NIT_EMPRESA = '" . $nit_empresa[0] . "') B", "GESTIONCOBRO.COD_GESTION_COBRO = B.LAST_COD_GESTION", false);
                }
                $informacion = $this->Reporteador_model->Generar_Gestion_empresa();
                break;
            case 51:
                $this->db->where("LIQUIDACION.SALDO_DEUDA <>", "0");
                $a = array(1, 2, 3);
                $this->db->where_in('RESOLUCION.COD_CPTO_FISCALIZACION', $a);
                $this->db->select('ULTIMA_GESTION.FECHA_CONTACTO,', false);
                $this->db->where("SYSDATE-ULTIMA_GESTION.FECHA_CONTACTO>=", "30", false);
                $this->db->join('(
SELECT MAX(GESTIONCOBRO.FECHA_CONTACTO) FECHA_CONTACTO, GESTIONCOBRO.COD_FISCALIZACION_EMPRESA 
FROM GESTIONCOBRO, RESOLUCION
WHERE RESOLUCION.COD_FISCALIZACION = GESTIONCOBRO.COD_FISCALIZACION_EMPRESA 
GROUP BY COD_FISCALIZACION_EMPRESA 
) ULTIMA_GESTION', 'ULTIMA_GESTION.COD_FISCALIZACION_EMPRESA =RESOLUCION.COD_FISCALIZACION', false);
                $informacion = $this->Reporteador_model->reporte_resolucion();
                break;
            case 53:
                $informacion = $this->Reporteador_model->reporte_presunta_real();
                break;
            case 54:
                $this->db->select("LIQUIDACION.TOTAL_LIQUIDADO AS VALOR_INICIAL_LIQUIDACION");
                $this->db->where('COD_TIPOPROCESO', '5');
                $this->db->where("SYSDATE-LIQUIDACION.FECHA_VENCIMIENTO>=", "30", false);
                $informacion = $this->Reporteador_model->reporte_liquidacion();
                break;
            case 55:// acuerdo de pago coactivo
//                $this->db->where('PROYACUPAG_ESTADO', 0);
                $this->db->where('ACUERDOPAGO.JURIDICO', 1);
                $informacion = $this->Reporteador_model->resolucion_acuerdopago();
                break;
            case 56:// acuerdo de pago en mora coactivo
//                $this->db->where('ACUERDOPAGO.JURIDICO', 2);
                $this->db->where('ACUERDOPAGO.JURIDICO=1 HAVING MIN(PROYACUPAG_FECHALIMPAGO)< SYSDATE AND 1=', 1, false);
                $informacion = $this->Reporteador_model->resolucion_acuerdopago();
                break;
            case 57:// reporte acctus
                $informacion = $this->Reporteador_model->kactus_reporte();
                break;
            case 59:
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->cartera_x_aportante_consolidado();
                else
                    $informacion = $this->Reporteador_model->cartera_x_aportante();

                break;
            case 60:
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->cartera_x_concepto_consolidado();
                else
                    $informacion = $this->Reporteador_model->cartera_x_concepto();
                break;
            case 61:
                $informacion = $this->Reporteador_model->cartera_x_regional();
                break;
            case 62:
                $informacion = $this->Reporteador_model->kactus_x_aportantes();
                break;
            case 63:
                $informacion = $this->Reporteador_model->kactus_x_concepto();
                break;
            case 64:
                $informacion = $this->Reporteador_model->kactus_x_regional();
                break;
            case 65:
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->cartera_x_aportante_consolidado();
                else
                    $informacion = $this->Reporteador_model->cartera_x_abono();
                break;
            case 66:
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->cartera_x_instancia_consolidado();
                else
                    $informacion = $this->Reporteador_model->cartera_x_instancia();
                break;
            case 67:
                $informacion = $this->Reporteador_model->kactus_x_abono();
                break;
            case 68:
                $informacion = $this->Reporteador_model->kactus_x_plazo();
                break;
            case 69:
                $informacion = $this->Reporteador_model->cartera_x_dificil_recaudo();
                break;
            case 70:
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->cartera_x_prescrita_consolidado();
                else
                    $informacion = $this->Reporteador_model->cartera_x_prescrita();
                break;
            case 71:
                $informacion = $this->Reporteador_model->kactus_x_prescrita();
                break;
            case 72:
                $informacion = $this->Reporteador_model->devolucion_x_concepto();
                break;
            case 73:
                $informacion = $this->Reporteador_model->devolucion_x_concepto();
                break;
            case 74:// este reportes es el de reciprocas
                $informacion = $this->Reporteador_model->reporte_reciprocas();
                break;
            case 75:// reporte por aumento o disminucion
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->reporte_aumento_grupal();
                else
                    $informacion = $this->Reporteador_model->reporte_aumento();
                break;
            case 77:// cartera por gestion
                $informacion = $this->Reporteador_model->cartera_x_gestion();
                break;
            case 78:// cartera no misional deudores morosos
            
                $informacion = $this->Reporteador_model->r_cartera_no_mis_moroso();
                break;
            case 79:// cartera no misional deudores morosos
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->r_cartera_no_misional_r_conso();
                else
                    $informacion = $this->Reporteador_model->r_cartera_no_misional_r();
                break;
            case 80:// cartera no misional deudores morosos
                $informacion = $this->Reporteador_model->r_cartera_no_mis_liquidacion();
                break;
			case 81:// cartera no misional general de ingresos
            $informacion = $this->Reporteador_model->r_cartera_no_misional_gen();
            break;
			case 82:// cartera no misional deudores morosos
            
                $informacion = $this->Reporteador_model->r_cartera_no_mis_mensual();
                break;
			case 83:// cartera no misional deudores morosos
            
                $informacion = $this->Reporteador_model->r_cartera_no_mis_cesantias();
                break;	
				
            default :
                $informacion = null;
        }

        $this->Reporteador_model->log2($post);
        if ($reporte == 2) {
            $this->data['informacion'] = $informacion;
            if (isset($informacion[0]['NUM_LIQUIDACION']))
                $array['NUM_LIQUIDACION'] = $informacion[0]['NUM_LIQUIDACION'];
            if (isset($informacion[0]['FECHA_RESOLUCION']))
                $array['FECHA_RESOLUCION'] = $informacion[0]['FECHA_RESOLUCION'];
            if (isset($informacion[0]['FECHA_INICIO']))
                $array['FECHA_INICIO'] = $informacion[0]['FECHA_INICIO'];
            if (isset($informacion[0]['FECHA_FIN']))
                $array['FECHA_FIN'] = $informacion[0]['FECHA_FIN'];
            if (isset($informacion[0]['TOTAL_INTERESES']))
                $array['TOTAL_LIQUIDADO'] = $informacion[0]['TOTAL_INTERESES'];
            if (isset($informacion[0]['FECHA_VENCIMIENTO']))
                $array['FECHA_VENCIMIENTO'] = $informacion[0]['FECHA_VENCIMIENTO'];
            if (isset($informacion[0]['SALDO_DEUDA']))
                $array['SALDO_DEUDA'] = $informacion[0]['SALDO_DEUDA'];
            if (isset($informacion[0]['CODEMPRESA']))
                $array['CODEMPRESA'] = $informacion[0]['CODEMPRESA'];
            if (isset($informacion[0]['DIRECCION']))
                $array['DIRECCION'] = $informacion[0]['DIRECCION'];
            if (isset($informacion[0]['TELEFONO_FIJO']))
                $array['TELEFONO_FIJO'] = $informacion[0]['TELEFONO_FIJO'];
            if (isset($informacion[0]['NOMBRE_EMPRESA']))
                $array['NOMBRE_EMPRESA'] = $informacion[0]['NOMBRE_EMPRESA'];
            if (isset($informacion[0]['REPRESENTANTE_LEGAL']))
                $array['REPRESENTANTE_LEGAL'] = $informacion[0]['REPRESENTANTE_LEGAL'];
            if (isset($informacion[0]['CORREOELECTRONICO']))
                $array['CORREOELECTRONICO'] = $informacion[0]['CORREOELECTRONICO'];
            if (isset($informacion[0]['DIRECCION_REGIONAL']))
                $array['DIRECCION_REGIONAL'] = $informacion[0]['DIRECCION_REGIONAL'];
            if (isset($informacion[0]['NOMBRE_REGIONAL']))
                $array['NOMBRE_REGIONAL'] = $informacion[0]['NOMBRE_REGIONAL'];
            if (isset($informacion[0]['COORDINADOR_REGIONAL']))
                $array['COORDINADOR_REGIONAL'] = $informacion[0]['COORDINADOR_REGIONAL'];
            if (isset($informacion[0]['NOMBRE_DIRECTOR']))
                $array['NOMBRE_DIRECTOR'] = $informacion[0]['NOMBRE_DIRECTOR'];
            if (isset($informacion[0]['NOMBRE_CONCEPTO']))
                $array['NOMBRE_CONCEPTO'] = $informacion[0]['NOMBRE_CONCEPTO'];

            switch (date('m')) {
                case '01':
                    $informacion2 = "Enero";
                    break;
                case '02':
                    $informacion2 = "Febrero";
                    break;
                case '03':
                    $informacion2 = "Marzo";
                    break;
                case '04':
                    $informacion2 = "Abril";
                    break;
                case '05':
                    $informacion2 = "Mayo";
                    break;
                case '06':
                    $informacion2 = "Junio";
                    break;
                case '07':
                    $informacion2 = "Julio";
                    break;
                case '08':
                    $informacion2 = "Agosto";
                    break;
                case '09':
                    $informacion2 = "Septiembre";
                    break;
                case '10':
                    $informacion2 = "Octubre";
                    break;
                case '11':
                    $informacion2 = "Noviembre";
                    break;
                case '12':
                    $informacion2 = "Diciembre";
                    break;
            }

            $array['EXPEDIDA'] = "Expedido por el SENA,a los " . date('d') . " dias del mes de " . $informacion2 . " de 20" . date('y');

            if (isset($informacion[0]['NOMBRE_EMPRESA'])) {
                $txt = $this->Reporteador_model->plantilla('91');
                if (!empty($txt)) {
                    $texto = template_tags("./uploads/plantillas/" . $txt, $array);
                }
                if ($post['accion'] == 2) {
                    $this->pdf($texto);
//                echo $texto;
                } else
                    echo $texto;
            }else {
                echo "Datos no encontrados";
            }
        } else {

            if (!isset($post['reporte']))
                redirect(base_url() . 'index.php/inicio');
//                echo "entro";
//            echo '<pre>';
//            print_r($post);
//            
//            print_r($informacion);
//            echo "</pre>";
//                echo "**";
//                die();

            $this->data['informacion'] = $informacion;
            if ($post['accion'] == 0) {
                $a = $post['vista'];
                $this->$a();
                $this->load->view('reporteador/reporte', $this->data);
            } else if ($post['accion'] == 1) {
//                echo "ennn";
                echo $this->load->view('reporteador/reporte', $this->data);
            } else if ($post['accion'] == 2) {
                $this->template->load($this->template_file, 'reporteador/reporte', $this->data);
            } else if ($post['accion'] == 3) {
                $this->load->view('reporteador/reporte', $this->data);
//            $this->retornar($informacion);
            }
        }
    }

    function reporte_ugpp() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        switch ($post["radio1"]) {
            case 1:
                $informacion = $this->Reporteador_model->ugpp_actualizacion();
                $file = 'PASENA_' . date('d_m_Y') . '_Actualizacion.txt';
                $head = '';
                $metodo = '';
                break;
            case 3:
                $informacion = $this->Reporteador_model->ugpp_consolidado();
                $file = 'PASENA_' . date('d_m_Y') . '_Consolidado.txt';
                $head = 'CodigoAdministradora|NombreAdministradora|TipoCartera|OrigenCartera|EdadCartera|NumeroPeriodosSinPago|ValorDeLaCartera';
                $metodo = '';
                break;
            case 2:
                $informacion = $this->Reporteador_model->ugpp_desagregado();
                $file = 'PASENA_' . date('d_m_Y') . '_Desagregado.txt';
                $head = 'CodigoAdministradora|NombreAdministradora|RazonSocial|TipoIdentificacion|NumeroIdentificacion|DigitoVerificacion|TipoCartera|OrigenCartera|ValorCartera|UltimoPeriodoConPagoRegistrado|UltimaFechaPago|NumeroPeriodosSinPago|UltimaAccionCobro|FechaUltimaAccionCobro|Estado|ClasificacionEstado|ConvenioCobro|FechaProceso';
                $metodo = 'con base en las guias de uso y estructura definida por UGGP';
                break;
            default :
                $informacion = null;
        }
        $this->data['informacion'] = $informacion;
        if ($post['accion'] == 0) {
            $a = $post['vista'];
            $html = $this->load->view('reporteador/reporte', $this->data);
            $this->$a($html);
        } else if ($post['accion'] == 1) {
            $html = $this->load->view('reporteador/reporte', $this->data);
        } else if ($post['accion'] == 2) {
            $this->template->load($this->template_file, 'reporteador/reporte', $this->data);
        } else if ($post['accion'] == 3) {
            $path = "uploads/reportes/ugpp/";
            $fecha_corte = date('d/m') . '/' . (date('Y') - 1);
            $directorios = $this->directorios();
            if ($directorios) {
                if (is_dir($path . date('d_m_Y')))
                    $this->deleteDir($path . date('d_m_Y'));
                mkdir($path . date('d_m_Y'), 0777);
                $archivo = $head . "\r\n";
                $registros = 1;
                foreach ($informacion as $value) {
                    $count = sizeof($value);
                    $x = 1;
                    foreach ($value as $value2) {
                        if ($x < $count) {
                            $archivo.= $value2 . '|';
                        } else {
                            $archivo.= $value2;
                        }
                        $x++;
                    }
                    $archivo.= "\r\n";
                    $registros++;
                }
                $ar = fopen($path . date('d_m_Y') . "/" . $file, "w") or die("Problemas en la creacion");
                fputs($ar, $archivo);
                fclose($ar);
            }

            $this->load->library("PHPExcel");
            $PHPExcel = new PHPExcel();
            $PHPExcel->getProperties()->setCreator("SENA")->setTitle("ARCHIVO CONTROL UGPP ");
            $PHPExcel->setActiveSheetIndex(0);
            $PHPExcel->getActiveSheet()->setTitle('FICHA');
            $PHPExcel->getActiveSheet()->setCellValue('B7', 'FICHA DE CONTROL - REPORTES');
            $PHPExcel->getActiveSheet()->setCellValue('A8', 'CÓDIGO ADMINISTRADORA');
            $PHPExcel->getActiveSheet()->setCellValue('B8', 'PASENA');
            $PHPExcel->getActiveSheet()->setCellValue('A9', 'NOMBRE ADMINISTRADORA');
            $PHPExcel->getActiveSheet()->setCellValue('B9', 'SERVICIO NACIONAL DE APRENDIZAJE - SENA');
            $PHPExcel->getActiveSheet()->setCellValue('A10', 'RESPONSABLE DEL ENVÍO');
            $PHPExcel->getActiveSheet()->setCellValue('B10', 'MANUEL FERNANDO MONSALVE AHUMADA');
            $PHPExcel->getActiveSheet()->setCellValue('A11', 'CARGO DEL RESPONSABLE');
            $PHPExcel->getActiveSheet()->setCellValue('B11', 'COORDINADOR GRUPO RECAUDO Y CARTERA');
            $PHPExcel->getActiveSheet()->setCellValue('A12', 'CORREO ELECTRONICO DEL RESPONSABLE');
            $PHPExcel->getActiveSheet()->setCellValue('B12', 'mmonsalvea@sena.edu.co');
            $PHPExcel->getActiveSheet()->setCellValue('A13', 'TELEFONO DEL RESPONSABLE');
            $PHPExcel->getActiveSheet()->setCellValue('B13', '(1) 5461500 EXT 12032');
            $PHPExcel->getActiveSheet()->setCellValue('A14', 'NOMBRE DEL ARCHIVO TXT');
            $PHPExcel->getActiveSheet()->setCellValue('B14', $file);
            $PHPExcel->getActiveSheet()->setCellValue('A15', 'CANTIDAD DE REGISTROS DEL ARCHIVO TXT');
            $PHPExcel->getActiveSheet()->setCellValue('B15', $registros);
            $PHPExcel->getActiveSheet()->setCellValue('A16', 'FECHA DE CORTE');
            $PHPExcel->getActiveSheet()->setCellValue('B16', $fecha_corte);
            $PHPExcel->getActiveSheet()->setCellValue('A17', 'FECHA DE ENVÍO');
            $PHPExcel->getActiveSheet()->setCellValue('B17', date('d/m/Y'));
            $PHPExcel->getActiveSheet()->setCellValue('A18', 'METODOLOGÍA UTILIZADA PARA EL CÁLCULO DE LA CARTERA PRESUNTA (Exclusivo para el reporte desagregado)');
            $PHPExcel->getActiveSheet()->setCellValue('B18', $metodo);
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            ob_clean();
            $objWriter->save($path . date('d_m_Y') . '/FICHA DE CONTROL - ' . substr($file, 0, -4) . ".xlsx");
            $cmd = exec('cd ' . $path);
            ob_clean();
            $command = 'zip ' . $path . date('d_m_Y') . ".zip " . $path . date('d_m_Y') . "/*";
            $cmd = exec($command);
            $this->deleteDir($path . date('d_m_Y'), 1);
            ob_clean();
            header("Content-Disposition: attachment; filename=" . $path . date('d_m_Y') . ".zip");
            header("Content-Type: application/text");
            header("Content-Length: " . filesize($path . date('d_m_Y') . ".zip"));
            readfile($path . date('d_m_Y') . ".zip");
        }
    }

    function reporte_exogenas() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        switch ($post["reporte"]) {
            case 74://insercion
                switch ($post["tipo"]) {
                    case 1:// ingresos
                        $informacion = $this->Reporteador_model->insercion_x_ingresos();
                        break;
                    case 2://saldo_cuentas
                        $informacion = $this->Reporteador_model->insercion_x_saldo();
                        break;
                    case 3://saldo_cuentas_no_misional
                        $informacion = $this->Reporteador_model->insercion_x_saldo2('1');
                        break;
                }
                break;
            case 75://reemplazo
                switch ($post["tipo"]) {
                    case 1:// ingresos
                        $informacion = $this->Reporteador_model->reemplazo_x_ingresos();
                        break;
                    case 2://saldo_cuentas
                        $informacion = ""; // cuando se diseño la aplicacion no llevan el historico modificaciones
                        break;
                    case 3:// ingresos_no_misional
                        $informacion = $this->Reporteador_model->reemplazo_x_ingresos('1');
                        break;
                }
                break;
            default :
                $informacion = "";
        }
        $this->data['informacion'] = $informacion;
        if ($post['accion'] == 0) {
            $a = $post['vista'];
            $this->$a();
            $this->load->view('reporteador/reporte', $this->data);
        } else if ($post['accion'] == 1) {
            $html = $this->load->view('reporteador/reporte', $this->data);
        } else if ($post['accion'] == 2) {
            $this->template->load($this->template_file, 'reporteador/reporte', $this->data);
        } else if ($post['accion'] == 3)
            echo $html = $this->load->view('reporteador/reporte', $this->data);
    }

    function ingresos() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];

        if (isset($post['valor1']))
            $post['valor1'] = str_replace('.', '', $post['valor1']);
        if (isset($post['valor2']))
            $post['valor2'] = str_replace('.', '', $post['valor2']);

        $where = array();
        $where2 = "";
        if (isset($post["ciiu"]))
            if ($post["ciiu"] != "") {
                $abogado = explode(' - ', $post["ciiu"]);
                $where[] = " EMPRESA.CIIU='" . $abogado[0] . "'";
            }
        if (isset($post["municipio"]))
            if ($post["municipio"] != "") {
                $abogado = explode(' - ', $post["municipio"]);
                if ($post["reporte"] == 71)
                    $where[] = " PLANILLAUNICA_ENC.COD_CIU_O_MUN='" . $abogado[0] . "' and PLANILLAUNICA_ENC.COD_DEPARTAMENTO ='" . $abogado[1] . "'";
                else
                    $where[] = " regional.cod_ciudad='" . $abogado[0] . "' and REGIONAL.COD_DEPARTAMENTO ='" . $abogado[1] . "'";
            }
        if (!empty($post['fecha_ini'])) {
            $post['fecha_fin'] = (!empty($post['fecha_fin']) ? $post['fecha_fin'] : date('d/m/Y'));
            if ($post["reporte"] == 69 || $post["reporte"] == 70 || $post["reporte"] == 71) {
                $where[] = " PLANILLAUNICA_ENC.FECHA_CREACION BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY')";
            } else {
                $where[] = " T.FECHA_PAGO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY')";
                $where2.= " and PLANILLAUNICA_ENC.FECHA__PAGO BETWEEN to_date('" . $post['fecha_ini'] . "','DD/MM/YYYY') AND to_date('" . $post['fecha_fin'] . "','DD/MM/YYYY')";
            }
        }
        if (isset($post["archivos_nuevos"]))
            if ($post["archivos_nuevos"] != "") {
                $contenido = file_get_contents($post["archivos_nuevos"], true);
//                $contenido2 = explode("\n", $contenido);
                $contenido2 = str_replace("\n", "','", $contenido);
                $contenido2 = str_replace("\r", "", $contenido2);
                $where[] = " T.NITEMPRESA in  ('" . $contenido2 . "')";
            } else {
                if (isset($post["empresa"])) {
                    if ($post["empresa"] != "") {
                        $nit_empresa = explode(" - ", $post["empresa"]);
                        $where[] = " T.NITEMPRESA='" . $nit_empresa[0] . "'";
                    }
                }
            }
        if (isset($post["num_planilla"]))
            if ($post["num_planilla"] != "") {
                $where[] = " (T.COD_PLANILLAUNICA='" . $post["num_planilla"] . "' or ASOBANCARIA_DET.COD_DETALLE='" . $post["num_planilla"] . "')";
            }
        if (isset($post["id_banco"]))
            if ($post["id_banco"] != "") {
                $where[] = "  T.COD_ENTIDAD='" . $post["id_banco"] . "'";
            }
        if (isset($post["procedencia"]))
            if ($post["procedencia"] != "-1") {
                $where[] = " T.Procedencia='" . $post["procedencia"] . "' ";
            }

        if (isset($post["regional"][0])) {
            if ($post["regional"][0] != "-1") {
                $regional = "";
                foreach ($post["regional"] as $dato) {
                    $regional.= $dato . ",";
                }
                $regional = substr($regional, 0, -1);
                $where[] = ' REGIONAL.COD_REGIONAL in (' . $regional . ")";
            }
        }
        if (isset($post["concepto"]))
            if ($post["concepto"] != "-1" && $post["reporte"] != 29)
                $where[] = ' TIPOCONCEPTO.COD_TIPOCONCEPTO=' . $post["concepto"];


        if (isset($post["cod_operador"])) {
            if ($post["cod_operador"][0] != '-1') {
                $oper = "";
                for ($i = 0; $i < count($post["cod_operador"]); $i++) {
                    $oper.=$post["cod_operador"][$i] . ",";
                }
                $oper = substr($oper, 0, -1);
                if ($post["reporte"] == 66)
                    $where[] = " REGISTROTIPOCERO.COD_OPERADOR in (" . $oper . ") ";
                else if ($post["reporte"] == 69)
                    $where[] = " PLANILLAUNICA_ENC.COD_OPERADOR in (" . $oper . ") ";
                else
                    $where[] = " ASOBANCARIA_DET.COD_OPERADOR in (" . $oper . ") ";
                $post['cod_operador'] = $oper;
            } else {
                $post["cod_operador"] = "";
            }
        } else {
            $post['cod_operador'] = '';
        }
        if (!empty($post['fecha_periodo_ini'])) {
            if (empty($post['fecha_periodo_fin'])) {
                $post['fecha_periodo_fin'] = date('m') . '/' . date('Y');
            }
            $fechaini = explode("/", $post['fecha_periodo_ini']);
            $fechafin = explode("/", $post['fecha_periodo_fin']);
            if ($post["reporte"] == 69 || $post["reporte"] == 70 || $post["reporte"] == 71) {
                $where[] = " PLANILLAUNICA_ENC.PERIDO_PAGO BETWEEN ('" . $fechaini[1] . "-" . $fechaini[0] . "') and ('" . $fechafin[1] . "-" . $fechafin[0] . "') ";
            } else {
                $where[] = " T.PERIODO_PAGADO BETWEEN ('" . $fechaini[1] . "-" . $fechaini[0] . "') and ('" . $fechafin[1] . "-" . $fechafin[0] . "') ";
                $where2.= " and PLANILLAUNICA_ENC.PERIDO_PAGO BETWEEN ('" . $fechaini[1] . "-" . $fechaini[0] . "') and ('" . $fechafin[1] . "-" . $fechafin[0] . "')";
            }
        }
        if (isset($post["valor_select"]))
            if ($post["valor_select"] == "1") {
                if (!empty($post["valor1"])) {
                    if ($post["reporte"] == 71)
                        $where[] = " (REGISTROTIPO3.INGRESO >= " . $post["valor1"] . " and REGISTROTIPO3.INGRESO <= " . $post["valor2"] . ") ";
                    else
                        $where[] = " (T.VALOR_PAGADO >= " . $post["valor1"] . " and T.VALOR_PAGADO <= " . $post["valor2"] . ") ";
                }
            }
        if (isset($post["valor_select"]))
            if ($post["valor_select"] == "2") {
                if (!empty($post["valor1"])) {
                    if ($post["reporte"] == 71)
                        $where[] = " REGISTROTIPO3.INGRESO=" . $post["valor1"];
                    else
                        $where[] = " T.VALOR_PAGADO=" . $post["valor1"];
                }
            }
//        if (!empty($post["cod_concepto_sub"])) {
//            $where[] = " T.COD_FORMAPAGO=" . $post["cod_concepto_sub"];
//        }
        if (!empty($post["n_trabajadores1"])) {
            if ($post["n_trabajadores0"] == 2) {
                if ($post["reporte"] == 71)
                    $where[] = " PLANILLAUNICA_ENC.N_TOTAL_EMPLEADOS IN (" . $post["n_trabajadores1"] . ")";
                else
                    $where[] = " EMPRESA.NUM_EMPLEADOS IN (" . $post["n_trabajadores1"] . ")";
            }
            if ($post["n_trabajadores0"] == 1) {
                if ($post["reporte"] == 71)
                    $where[] = " PLANILLAUNICA_ENC.N_TOTAL_EMPLEADOS BETWEEN (" . $post["n_trabajadores1"] . ") and (" . $post["n_trabajadores2"] . ")";
                else
                    $where[] = " EMPRESA.NUM_EMPLEADOS BETWEEN (" . $post["n_trabajadores1"] . ") and (" . $post["n_trabajadores2"] . ")";
            }
        }
        if (isset($post["tipo_empresa"]))
            if ($post["tipo_empresa"] != -1) {
                $where[] = " EMPRESA.COD_TIPOENTIDAD=" . $post["tipo_empresa"];
            }
        $anos = "";
        if (isset($post['ano'])) {
            foreach ($post["ano"] as $dato) {
                $anos.= $dato . ",";
            }
        } else {
            $anos = date('Y') . ',';
        }
        $anos = substr($anos, 0, -1);
        if ($post['reporte'] == 59) {
            $where[] = " TO_CHAR(T.Fecha_Pago, 'YYYY') IN (" . $anos . ")";
        }
        // conceptos
        $conceptos = "";
        if (isset($post['tipo_concepto_destino'])) {
            foreach ($post["tipo_concepto_destino"] as $dato) {
                $conceptos.= $dato . ",";
            }
            $conceptos = substr($conceptos, 0, -1);
            $where[] = " T.COD_CONCEPTO IN (" . $conceptos . ")";
        }
        // subconceptos
        $subconceptos = "";
        if (isset($post['tipo_subconcepto_destino'])) {
            foreach ($post["tipo_subconcepto_destino"] as $dato) {
                $subconceptos.= $dato . ",";
            }
            $subconceptos = substr($subconceptos, 0, -1);
            $where[] = " T.COD_SUBCONCEPTO IN (" . $subconceptos . ")";
        }
        if ($post['accion'] == 0 && $post["reporte"] != 69 && !isset($post["consolidado"])) {
            $where[] = " ROWNUM<400";
        }
        if ($post['accion'] == 3 && $post["reporte"] == 76) {
            $where[] = " ROWNUM<400";
        }
        if (isset($post['tipo_doc']))
            if ($post['tipo_doc'] != '-1') {
                if ($post['tipo_doc'] == '1')
                    if ($post["reporte"] == 70 || $post["reporte"] == 71)
                        $where[] = " TIPO_IDENT_COTIZ<>'NI'";
                    else
                        $where[] = " EMPRESA.COD_TIPODOCUMENTO<>2";
                if ($post['tipo_doc'] == '2')
                    if ($post["reporte"] == 70 || $post["reporte"] == 71)
                        $where[] = " TIPO_IDENT_COTIZ='NI'";
                    else
                        $where[] = " EMPRESA.COD_TIPODOCUMENTO=2";
            }

//        echo "<pre>";
//        print_r($where);
//        echo "</pre>";
//        die();

        $whe = "1=1 ";
        if (count($where) != 0) {
            for ($i = 0; $i < count($where); $i++) {
                $whe.=" and " . $where[$i];
            }
        }

        $array = array();
        $reporte = 0;
        $informacion_cantidad = 0;
//echo $post["reporte"]."***";
        switch ($post["reporte"]) {
            case 58:
                if (isset($post["consolidado"])) {
                    $informacion = $this->Reporteador_model->control_cargue_grupal($anos, $whe);
                } else {
                    $informacion = $this->Reporteador_model->control_cargue($anos, $whe);
                }
                $informacion_cantidad = $this->Reporteador_model->control_cargue_cantidad($anos, $whe);
                break;
            case 59:
                if (isset($post["consolidado"])) {
                    $informacion = $this->Reporteador_model->comparativo_con_periodo_grupal($anos, $whe);
                } else
                    $informacion = $this->Reporteador_model->comparativo_con_periodo($anos, $whe);
                $informacion_cantidad = $this->Reporteador_model->comparativo_con_periodo_cantidad($anos, $whe);
                break;
            case 60://Ingresos por fuentes, aportantes y numero de trabajadores
                $informacion = $this->Reporteador_model->ingreso_fuentes_aportantes($anos, $whe);
                break;
            case 61://3.	INGRESOS POR OPERADOR
                $operador = "";
                if (empty($post['cod_operador'])) {
//                         $operador = $this->Reporteador_model->operadores();
                }
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->ingreso_por_operador_grupal($anos, $whe, $post, $operador);
                else
                    $informacion = $this->Reporteador_model->ingreso_por_operador($anos, $whe, $post, $operador);
                $informacion_cantidad = $this->Reporteador_model->ingreso_por_operador_cantidad($anos, $whe, $post, $operador);
//                print_r($informacion_cantidad);
                break;
            case 62:
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->control_cargue_grupal($anos, $whe);
                else
                    $informacion = $this->Reporteador_model->control_cargue($anos, $whe);
                $informacion_cantidad = $this->Reporteador_model->control_cargue_cantidad_valor($anos, $whe);
                break;
            case 63://11.	RELACION APORTANTES POR RANGOS O MONTOS DEL APORTE
                $informacion = $this->Reporteador_model->relacion_aportantes_por_rango($anos, $whe, $post);
                break;
            case 64://Ingresos Por Empresas Publicos Y Privados O Mixta 
                $informacion = $this->Reporteador_model->ingresos_por_tipo_empresa($anos, $whe, $post);
                break;
            case 65://12.	INGRESOS DE EMPRESAS CON SMLV
                $informacion = $this->Reporteador_model->ingresos_por_empresa_smlv($anos, $whe, $post, $where2);
                break;
            case 66://Ingresos Por Empresas Publicos Y Privados O Mixta 
                $informacion = $this->Reporteador_model->ingresos_por_tipo_REGISTRO_0($anos, $whe, $post);
                break;
//            case 66://Ingresos Por Empresas Publicos Y Privados O Mixta       //////// no borrar 
//                $informacion = $this->Reporteador_model->ingresos_por_tipo_empresa($anos, $whe, $post);
//                break;
            case 67://Ingresos Por banco
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->ingresos_por_banco_consolidado($anos, $whe, $post);
                else
                    $informacion = $this->Reporteador_model->ingresos_por_banco($anos, $whe, $post);
                $informacion_cantidad = $this->Reporteador_model->ingresos_por_banco_grupal($anos, $whe, $post);
                break;
            case 68://Ingresos Por regional
                $informacion = $this->Reporteador_model->ingresos_por_regional($anos, $whe, $post);
                break;
            case 69://Ingresos Por regional
                echo "";
                $informacion = $this->Reporteador_model->ingresos_control_cargue_pila($anos, $whe, $post);
                break;
            case 70://•	Se debe agregar el reporte por Tipo de planilla, 1 electrónica 2 Asistida y se sacan de registro 1. (Reiteración solicitud.) detalle y consolidado
                if (isset($post["consolidado"]))
                    $informacion = $this->Reporteador_model->ingresos_tipo_planilla_grupal($anos, $whe, $post);
                else
                    $informacion = $this->Reporteador_model->ingresos_tipo_planilla($anos, $whe, $post);
                break;
            case 71://tipo de registro tipo 1 tipo 2 tipo 3
//                if (isset($post["consolidado"]))
//                    $informacion = $this->Reporteador_model->ingresos_tipo_planilla_grupal($anos, $whe, $post);
//                else
                $informacion = $this->Reporteador_model->ingresos_tipo_registro($anos, $whe, $post);
                break;
            case 76://REPORTEADOR INGRESOS
                $informacion = $this->Reporteador_model->ingresos_por_tipo_empresa2($anos, $whe, $post);
                break;
            case 77:
                $informacion = $this->Reporteador_model->ingresos_por_sql($post);
                break;
            default :
                $informacion = null;
        }
        if (count($informacion) == 399) {
            echo "<script>alert('El sistema contiene mucha información por favor exportarlo en excel para obtener la informacion completa')</script>";
        }
        $this->data['informacion'] = $informacion;
//        echo $post['accion'];
////        echo "<pre>";
////        print_r($informacion);
////        echo "</pre>";
//        echo $post['accion'];
        if (!isset($post['reporte'])) {
            redirect(base_url() . 'index.php/inicio');
        }

        if ($post['accion'] == 0) {
            $a = $post['vista'];
            $this->$a();
            $this->load->view('reporteador/reporte', $this->data);

            if ($informacion_cantidad != 0) {
                $this->data['informacion'] = $informacion_cantidad;
                $this->load->view('reporteador/reporte2', $this->data);
            }
//            $a = $post['vista'];
//            $html = $this->load->view('reporteador/reporte', $this->data);
//            $this->$a($html);
        } else if ($post['accion'] == 1) {
            $html = $this->load->view('reporteador/reporte', $this->data);
        } else if ($post['accion'] == 2) {
            $this->template->load($this->template_file, 'reporteador/reporte', $this->data);
        } else if ($post['accion'] == 3 || $post['accion'] == '3') {
            echo $html = $this->load->view('reporteador/reporte', $this->data);
            if ($informacion_cantidad != 0) {
                $this->data['informacion'] = $informacion_cantidad;
                echo $html = $this->load->view('reporteador/reporte2', $this->data);
            }
        }
    }

    function retornar($bloq) {
        return $this->output->set_content_type('application/json')->set_output(json_encode($bloq));
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se optiene la informacion de la resolcuion en pdf 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function pdf($html, $id = null, $cod = null) {
        if (!file_exists('uploads/fiscalizaciones/certificados/')) {
            if (!mkdir('uploads/fiscalizaciones/certificados/', 0777, true)) {
                
            }
        }
        if ($id == null) {
            $id = "";
        }
        if ($cod == null) {
            $cod = "informacion";
        }
        $post = $this->input->post();
        $this->data['post'] = $this->input->post();

        $inform2 = "Servicio Nacional de Aprendizaje SENA";
        $inform = "";
        ob_clean();
        if (isset($this->data['post']['name_reporte']) || isset($post['name_reporte'])) {
            $inform.=$this->data['post']['name_reporte'];
            if ($post['vista'] == 'certificados1' || $post['vista'] == 'certificados2' || $post['vista'] == 'certificados3' || $post['vista'] == 'certificados4' || $post['vista'] == 'certificados5' || $post['vista'] == 'certificados6')
                $inform = "" . $inform;
            else
                $inform = "Reporte: " . $inform;
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        } else {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        }
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, $inform2, $inform . "   " . date('d/m/y') . '                                        ' . $id);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setLanguageArray($l);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $file = 'img/fondosena2.png';
        $pdf->SetAlpha(0.5);
        $pdf->Image($file, 40, 90, 130, 130, '', '', '', false, 300, '', FALSE);
        $pdf->Output("uploads/fiscalizaciones/certificados/" . $cod . '.pdf', 'F');
        $pdf->Output($cod . '.pdf', 'D');
        exit();
    }

    function generador_reportes() {
        $this->activo();
        $this->data['tablas'] = $this->Reporteador_model->consulta_tablas();
        $this->template->load($this->template_file, 'reporteador/generador_reportes', $this->data);
    }

    function ayuda() {
        $this->data['post'] = $this->input->post();
        $key_primaria = $this->Reporteador_model->key_primaria($this->data);
        $key_foranea = $this->Reporteador_model->key_foranea($this->data);
        $datos_tabla = $this->Reporteador_model->datos_tabla($this->data);
        $this->retornar2($key_primaria, $key_foranea, $datos_tabla);
    }

    function datos_tabla($tabla) {
        $this->data['post']['tabla'] = $tabla;
        $datos_tabla = $this->Reporteador_model->datos_tabla($this->data);
        return $datos_tabla;
    }

    function retornar2($bloq, $bloq2, $bloq3) {
        $this->data['post'] = $this->input->post();
        $dato[] = $bloq;
        $dato[] = $bloq2;
        $dato[] = $bloq3;
        $this->data['consulta'] = $dato;
        return $html = $this->load->view('reporteador/generar_consulta', $this->data);
//         $this->output->set_content_type('application/json')->set_output(json_encode($dato));
    }

    function consultar() {
        $this->data['post'] = $this->input->post();
        $consulta = $this->Reporteador_model->consultar($this->data);
        $this->data['general'] = $consulta['consulta'];
        $this->data['consulta'] = $consulta['sql'];
        return $html = $this->load->view('reporteador/consultar', $this->data);
    }

    function importar() {
        $this->data['post'] = $this->input->post();
        if ($this->data['post']['accion'] == 1 || $this->data['post']['accion'] == 2) {
            $this->data['general'] = $this->Reporteador_model->consultar2($this->data);
            $this->load->view('reporteador/consultar2', $this->data);
        } else if ($this->data['post']['accion'] == 3) {
            $this->data['general'] = $this->Reporteador_model->guardar_reporte($this->data);
            $this->visualizar();
        } else {
            $this->visualizar();
        }
    }

    function visualizar() {
        $this->activo();
        $this->data['post'] = $this->input->post();
        $this->data['general'] = $this->Reporteador_model->consulta_reporte();
        $this->template->load($this->template_file, 'reporteador/consultar3', $this->data);
    }

    function consultar2() {
        $this->data['post'] = $this->input->post();
        $this->data['general'] = $this->Reporteador_model->consultar3($this->data);
        return $html = $this->load->view('reporteador/consultar', $this->data);
    }

    function general($infor, $general) {
        $this->template->set('title', 'Generador de Reportes');
        $post = $this->input->post();
        $dato = "";
        if ($infor == 3 || $general == "todos") {
            $dato.='<option value="3">Reporte Liquidacion</option>';
        }
        if ($infor == 4 || $general == "todos") {
            $dato.='<option value="4">Reporte Resolucion</option>';
        }

        if ($infor == 5 || $general == "todos") {
            $dato.='<option value="5">Liquidaciones pendientes de pago</option>';
        }

        if ($infor == '6' || $general == "todos") {
            $dato.='<option value="6">Reporte Acuerdos pago en persuasivo</option>';
        }

        if ($infor == 7 || $general == "todos") {
            $dato.='<option value="7">Estado de Cartera</option>';
        }
        if ($infor == 8 || $general == "todos") {
            $dato.='<option value="8">Generar Certificaciones</option>';
        }
        if ($infor == 9 || $general == "todos") {
            $dato.='<option value="9">Reportes de Conciliación de Cartera</option>';
//                        <option value="9a">9a certificacion tributaria por vigencia</option>
//                        <option value="9b">9b Generar Pagos Por Nit</option>
        }
        if ($infor == 10 || $general == "todos") {
            $dato.='<option value="10">Edad de la cartera</option>';
        }
        if ($infor == 11 || $general == "todos") {
            $dato.='<option value="12">Actualizar informacion de contacto y ubicacion aportantes</option>
                        <option value="14">Generar reporte desagregado</option>';
        }
        if ($infor == 16 || $general == "todos") {
            $dato.='<option value="16">Generar informe fiscalización por CIIU</option>';
        }
        if ($infor == 18 || $general == "todos") {
            $dato.='<option value="18">Incumplimiento de acuerdo de pago</option>';
        }
        if ($infor == 21 || $general == "todos") {
            $dato.='<option value="23">Generar reporte comparativo por periodos</option>';
        }
        if ($infor == 22 || $general == "todos") {
            $dato.='<option value="22">Estado Cartera</option>';
        }
        if ($infor == 23 || $general == "todos") {
            $dato.='<option value="23">Reporte comparativo por periodos</option>';
        }
        if ($infor == 25 || $general == "todos") {
            $dato.='<option value="25">Generar reporte de gestión</option>';
        }
        if ($infor == 26 || $general == "todos") {
            $dato.='<option value="26">Contrato de Aprendizaje</option>';
        }
        if ($infor == 27 || $general == "todos") {
            $dato.='<option value="27">Procesos Sancionatorios</option>';
        }
        if ($infor == 29 || $general == "todos") {
            $dato.='<option value="29">Generar Informe visita</option>';
        }
        if ($infor == 31 || $general == "todos") {
            $dato.='<option value="31">Generar reporte Estado de fiscalización</option>';
        }
        if ($infor == 30 || $general == "todos") {
            $dato.='<option value="30">Generar reporte de consulta asignación fiscalización</option>';
        }
        if ($infor == 32 || $general == "todos") {
            $dato.='<option value="32">Reporte Abogado</option>';
        }
        if ($infor == 34 || $general == "todos") {
            $dato.='<option value="34">sin informacion</option><!--PENDIENTE CON FREDI-->';
        }
        if ($infor == 35 || $general == "todos") {
            $dato.='<option value="35">Reporte de contratos FIC</option>';
        }
        if ($infor == 36 || $general == "todos") {
            $dato.='<option value="36">Multas del Ministerio</option>';
        }
        if ($infor == 37 || $general == "todos") {
            $dato.='<option value="37">Gestión por empresa</option>';
        }
        if ($infor == 38 || $general == "todos") {
            $dato.='';
        }
        if ($infor == 39 || $general == "todos") {
            $dato.='';
        }
        if ($infor == 50 || $general == "todos") {
            $dato.='<option value="50">Resoluciones Pendientes por Pago</option>';
        }
        if ($infor == 51 || $general == "todos") {
            $dato.='<option value="51">Resoluciones Pendientes por Gestión</option>';
        }
        if ($infor == 53 || $general == "todos") {
            $dato.='<option value="53">Cartera Real</option>';
        }
        if ($infor == 54 || $general == "todos") {
            $dato.='<option value="54">Liquidaciones pendientes por Gestión (Más de 30 días)</option>';
        }
        if ($infor == 55 || $general == "todos") {
            $dato.='<option value="55">Reporte Acuerdos pago en persuasivo</option>';
        }
        if ($infor == 56 || $general == "todos") {
            $dato.='<option value="56">Incumplimiento de acuerdo de pago</option>';
        }
        if ($infor == 57 || $general == "todos") {
            $dato.='<option value="57">Reporte kactus</option>';
        }
        if ($infor == 62 || $general == "todos") {// no misional
            $dato.='<option value="62">Cartera por Aportante</option>';
            $dato.='<option value="63">Cartera por Concepto</option>';
            $dato.='<option value="64">Cartera por Regional</option>';
            $dato.='<option value="67">Abonos de Cartera</option>';
            $dato.='<option value="68">Cartera por Plazo</option>';
            $dato.='<option value="71">Cartera Prescrita</option>';
        }
        if ($infor == 58 || $general == "todos") {
            $selec = "";
            if (isset($post['reporte'])) {
                $selec = $post['reporte'];
            }
            $dato.='<option ' . ($selec == 66 ? 'selected="selected"' : '') . ' value="66">General...</option>';
            $dato.='<option ' . ($selec == 58 ? 'selected="selected"' : '') . ' value="58">Reporte Control de Cargue</option>';
            $dato.='<option ' . ($selec == 59 ? 'selected="selected"' : '') . ' value="59">Comparaci&oacute;n de vigencia</option>';
            $dato.='<option ' . ($selec == 60 ? 'selected="selected"' : '') . ' value="60">Ingresos por fuentes, aportantes y numero de trabajadores</option>';
            $dato.='<option ' . ($selec == 61 ? 'selected="selected"' : '') . ' value="61">Ingresos por Operador</option>';
            $dato.='<option ' . ($selec == 62 ? 'selected="selected"' : '') . ' value="62">Ingresos por Fuentes</option>';
            $dato.='<option ' . ($selec == 63 ? 'selected="selected"' : '') . ' value="63">Relacion Aportantes por Rangos o Montos del Aporte</option>';
            $dato.='<option ' . ($selec == 64 ? 'selected="selected"' : '') . ' value="64">Ingresos Por Empresas Publicos Y Privados O Mixta</option>';
            $dato.='<option ' . ($selec == 65 ? 'selected="selected"' : '') . ' value="65">Ingresos de Empresas con SMLV</option>';
            $dato.='<option ' . ($selec == 67 ? 'selected="selected"' : '') . ' value="67">Ingresos por Banco</option>';
            $dato.='<option ' . ($selec == 68 ? 'selected="selected"' : '') . ' value="68">Ingresos por Regional</option>';
            $dato.='<option ' . ($selec == 69 ? 'selected="selected"' : '') . ' value="69">Ingresos Control Cargue Pila</option>';
            $dato.='<option ' . ($selec == 70 ? 'selected="selected"' : '') . ' value="70">Tipo de planilla</option>';
            $dato.='<option ' . ($selec == 71 ? 'selected="selected"' : '') . ' value="71">Tipo de Registro</option>';
//            echo "****".$dato;
        }
        if ($infor == 76 || $general == "todos") {
            $dato.='<option value="76">Reporte Personalizado</option>';
        }
        if ($infor == 59 || $general == "todos") {//cartera
            $dato.='<option value="59">Cartera por Aportante</option>';
        }
        if ($infor == 60 || $general == "todos") {//cartera
            $dato.='<option value="60">Cartera por Concepto</option>';
        }
        if ($infor == 61 || $general == "todos") {//cartera
            $dato.='<option value="61">Cartera por Regional</option>';
        }
        if ($infor == 65 || $general == "todos") {//cartera
            $dato.='<option value="65">Abonos de Cartera</option>';
        }
        if ($infor == 66 || $general == "todos") {//cartera
            $dato.='<option value="66">Cartera por Instancia</option>';
        }
        if ($infor == 69 || $general == "todos") {//cartera
            $dato.='<option value="69">Cartera Dificil Recaudo</option>';
        }
        if ($infor == 70 || $general == "todos") {//cartera
            $dato.='<option value="70">Cartera en seguimiento Especial.</option>';
        }
        if ($infor == 75 || $general == "todos") {//cartera
            $dato.='<option value="75">Informe de cartera por aumento o disminución</option>';
        }
        if ($infor == 77 || $general == "todos") {//cartera
            $dato.='<option value="77">Gestion cartera</option>';
        }
        if ($infor == 72 || $general == "todos") {//ingresos por devolucion
            $dato.='<option value="72">Devolucion por Concepto </option>';
            $dato.='<option value="73">Devolucion No Viables</option>';
        }
        if ($infor == 74 || $general == "todos") {//EXOGENAS
            $dato.='<option value="74">Inserción </option>';
            $dato.='<option value="75">Reemplazo</option>';
        }
        if ($infor == 78 || $general == "todos") {//EXOGENAS
            $dato.='<option value="78">Resumen de Morosidad</option>';
        }
        if ($infor == 79 || $general == "todos") {//EXOGENAS
            $dato.='<option value="79">Resumen de la Cartera no Misional</option>';
        }
        if ($infor == 81 || $general == "todos") {//EXOGENAS
            $dato.='<option value="81">Resumen de la Cartera no Misional</option>';
        }
		if ($infor == 82 || $general == "todos") {//EXOGENAS
            $dato.='<option value="82">Informe Cartera Mensual Detallado</option>';
        }
        if ($infor == 83 || $general == "todos") {//EXOGENAS
            $dato.='<option value="83">Reporte Anual Saldo Deuda Para Cesantias Ordinarias</option>';
        }
        return $dato;
    }

    function autocompleteemrpesas() {
        $empresa = $this->input->get("term");
        $consulta = $this->Reporteador_model->razonsocial($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['CODEMPRESA'] . " - " . $datos['RAZON_SOCIAL'], "label" => $datos['CODEMPRESA'] . "::" . $datos['RAZON_SOCIAL']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function autocomplete_fiscalizador() {
        $empresa = $this->input->get("term");
        $consulta = $this->Reporteador_model->autocomplete_fiscalizador($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['IDUSUARIO'] . " - " . $datos['NOMBRES'] . ' ' . $datos['APELLIDOS'], "label" => $datos['IDUSUARIO'] . " - " . $datos['NOMBRES'] . ' ' . $datos['APELLIDOS']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function autocomplete_ciiu() {
        $empresa = $this->input->get("term");
        $consulta = $this->Reporteador_model->autocomplete_ciiu($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['CLASE'] . " - " . $datos['DESCRIPCION'], "label" => $datos['CLASE'] . " - " . $datos['DESCRIPCION']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function autocomplete_municipio() {
        $empresa = $this->input->get("term");
        $consulta = $this->Reporteador_model->autocomplete_municipio($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['CODMUNICIPIO'] . " - " . $datos['NOMBREMUNICIPIO'], "label" => $datos['CODMUNICIPIO'] . " - " . $datos['NOMBREMUNICIPIO']);
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function autocompleteempleado() {
        $empresa = $this->input->get("term");
        $consulta = $this->Reporteador_model->autocompleteempleado($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['IDUSUARIO'] . " - " . $datos['NOMBRES'] . ' ' . $datos['APELLIDOS'], "label" => $datos['IDUSUARIO'] . " - " . $datos['NOMBRES'] . ' ' . $datos['APELLIDOS']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function autocomplete_abogado() {
        $empresa = $this->input->get("term");
        $consulta = $this->Reporteador_model->autocomplete_abogado($empresa);
        $temp = NULL;
        if (!is_null($consulta)) :
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['IDUSUARIO'] . " - " . $datos['NOMBRES'] . ' ' . $datos['APELLIDOS'], "label" => $datos['IDUSUARIO'] . " - " . $datos['NOMBRES'] . ' ' . $datos['APELLIDOS']);
//        echo "<pre>";
//        var_dump($consulta->result_array);die;
            }
        endif;
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function personalizar_datos() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        if ($post["empresa"] != "") {
            $nit_empresa = explode(" - ", $post["empresa"]);
            $this->db->where("EMPRESA.CODEMPRESA LIKE '%" . $nit_empresa[0] . "%'", '', FALSE);
        }
        if ($post["regional"] != "-1")
            $this->db->where('REGIONAL.COD_REGIONAL', $post["regional"]);

        $this->Reporteador_model->datos_empresa();
        $this->Reporteador_model->datos_regional();
        $this->Reporteador_model->datos_tipo_proceso();

        $this->Reporteador_model->datos_cod_fiscalizacion();
        $this->data['consulta'] = $this->Reporteador_model->personalizar_datos($post);
        $this->data['titulo'] = 'Personalizados';
        $this->activo2('generar_Reportes_Personalizados');
        $this->data['desplegable'] = $this->general(33, "");
        $this->data['informacion'] = $this->data['consulta'];
        if (!isset($post['accion']))
            $post['accion'] = 4;

        if ($post['accion'] == 0) {
            $this->template->load($this->template_file, 'reporteador/personalizado', $this->data);
            echo $html = $this->load->view('reporteador/reporte', $this->data);
        } else if ($post['accion'] == 2 || $post['accion'] == 1) {
            echo $html = $this->load->view('reporteador/reporte', $this->data);
        } else if ($post['accion'] == 4) {
            $this->template->load($this->template_file, 'reporteador/personalizado', $this->data);
        }
    }

// certificado 1 -> no existe , periodo pendiente de pago , al dia
    function certificados1() {
        $this->data['input'] = "1";
        $this->data['vista'] = 'certificados1';
        $this->activo2('certificados1');
        $this->data['titulo'] = "Certificado Aportes Parafiscales";
        $this->template->load($this->template_file, 'reporteador/certificados', $this->data);
    }

// certificado 2 -> no existe , pagos realizados x año , en caso de que no se encuentren pagos
    function certificados2() {
        $this->data['titulo'] = "Certificado Tributario y Recaudo de Pagos";
        $this->data['vista'] = 'certificados2';
        $this->activo2('certificados2');
        $this->data['input'] = "2";
        $this->template->load($this->template_file, 'reporteador/certificados', $this->data);
    }

//certificado 3 -> no existe , periodos cancelados por fic
    function certificados3() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados3';
        $this->activo2('certificados3');
        $this->data['input'] = "3";
        $this->load->view('reporteador/certificados', $this->data);
    }

// certificado 1 -> no existe , periodo pendiente de pago , al dia // masivo
    function certificados4() {
        $this->data['titulo'] = "Certificado Aportes Parafiscales";
        $this->data['vista'] = 'certificados4';
        $this->activo2('certificados4');
        $this->data['input'] = "4";
        $this->template->load($this->template_file, 'reporteador/certificados1', $this->data);
    }

// certificado 2 -> no existe , pagos realizados x año , en caso de que no se encuentren pagos// masivo
    function certificados5() {
        $this->data['titulo'] = "Certificado Tributario";
        $this->data['vista'] = 'certificados5';
        $this->activo2('certificados5');
        $this->data['input'] = "5";
        $this->template->load($this->template_file, 'reporteador/certificados1', $this->data);
    }

//certificado 3 -> no existe , periodos cancelados por fic // masivo 
    function certificados6() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados6';
        $this->activo2('certificados6');
        $this->data['input'] = "6";
        $this->template->load($this->template_file, 'reporteador/certificados1', $this->data);
    }

    function certificados7() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados7';
        $this->activo2('certificados7');
        $this->data['input'] = "7";
        $this->load->view('reporteador/certificados', $this->data);
//        $this->template->load($this->template_file, 'reporteador/certificados', $this->data);
    }

    function certificados8() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados8';
        $this->activo2('certificados8');
        $this->data['input'] = "8";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados9() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados9';
        $this->activo2('certificados9');
        $this->data['input'] = "9";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados10() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados10';
        $this->activo2('certificados10');
        $this->data['input'] = "10";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados11() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados11';
        $this->activo2('certificados11');
        $this->data['input'] = "11";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados12() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados12';
        $this->activo2('certificados12');
        $this->data['input'] = "12";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados13() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados13';
        $this->activo2('certificados13');
        $this->data['input'] = "13";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados14() {
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados14';
        $this->activo2('certificados14');
        $this->data['input'] = "14";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados15() {
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados15';
        $this->activo2('certificados15');
        $this->data['input'] = "15";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados16() {
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados FIC";
        $this->data['vista'] = 'certificados16';
        $this->activo2('certificados16');
        $this->data['input'] = "16";
        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados17() {
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados Recíprocas";
        $this->data['vista'] = 'certificados17';
        $this->activo2('certificados17');
        $this->data['input'] = "17";
        $this->template->load($this->template_file, 'reporteador/certificados', $this->data);
//        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados18() {
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados Recíprocas";
        $this->data['vista'] = 'certificados18';
        $this->activo2('certificados18');
        $this->data['input'] = "18";
        $this->template->load($this->template_file, 'reporteador/certificados1', $this->data);
//        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados19() {
        $post = $this->input->post();
        $this->data['titulo'] = "Certificados Cartera no Misional";
        $this->data['vista'] = 'certificados19';
        $this->activo2('certificados19');
        $this->data['input'] = "19";
        $this->template->load($this->template_file, 'reporteador/certificado_no_misional', $this->data);
//        $this->load->view('reporteador/certificados', $this->data);
    }

    function certificados_no() {
        //no paz y saldo sin cartera
        $this->template->load($this->template_file, 'reporteador/certificados', $this->data);
    }

    function certificados_estado() {
        //estado de cuenta por empresario
        $this->template->load($this->template_file, 'reporteador/certificados', $this->data);
    }

    function imprimir_certificacion() {
        $post = $this->input->post();
        switch ($post['accion']) {
            case 1:
                $texto = $this->restpuesta_certificado1();
                break;
            case 2:
                $texto = $this->restpuesta_certificado2();
                break;
            case 3:
                $texto = $this->restpuesta_certificado3();
                break;
            case 4:
                if ($post["archivos_nuevos"] != "") {
                    $contenido = file_get_contents($post["archivos_nuevos"], true);
//                $contenido2 = explode("\n", $contenido);
                    $contenido2 = str_replace("\n", ",", $contenido);
                    $contenido2 = str_replace("\r", "", $contenido2);
                }
//                print_r($contenido2);
                $contenido2 = explode(',', $contenido2);
                $texto = "";
                for ($i = 0; $i < count($contenido2); $i++) {
                    $post["empresa"] = $contenido2[$i];
                    $texto.= $this->restpuesta_certificado1($post["empresa"]);
                    $texto.= '<br style="page-break-after: always;" />';
                }
                break;
            case 5:
                if ($post["archivos_nuevos"] != "") {
                    $contenido = file_get_contents($post["archivos_nuevos"], true);
//                $contenido2 = explode("\n", $contenido);
                    $contenido2 = str_replace("\n", ",", $contenido);
                    $contenido2 = str_replace("\r", "", $contenido2);
                }
//                print_r($contenido2);
                $contenido2 = explode(',', $contenido2);
                $texto = "";
                for ($i = 0; $i < count($contenido2); $i++) {
                    if ($contenido2[$i] != '') {
                        $post["empresa"] = $contenido2[$i];
                        $texto.= $this->restpuesta_certificado2($post["empresa"]);
                        $texto.= '<br style="page-break-after: always;" />';
                    }
                }
                break;
            case 6:
                if ($post["archivos_nuevos"] != "") {
                    $contenido = file_get_contents($post["archivos_nuevos"], true);
//                $contenido2 = explode("\n", $contenido);
                    $contenido2 = str_replace("\n", ",", $contenido);
                    $contenido2 = str_replace("\r", "", $contenido2);
                }
//                print_r($contenido2);
                $contenido2 = explode(',', $contenido2);
                $texto = "";
                for ($i = 0; $i < count($contenido2); $i++) {
                    $post["empresa"] = $contenido2[$i];
                    $texto.= $this->restpuesta_certificado3($post["empresa"]);
                    $texto.= '<br style="page-break-after: always;" />';
                }
                break;
            case 7:
                $texto = $this->restpuesta_certificado7();
                break;
            case 8:
                $texto = $this->restpuesta_certificado8();
                break;
            case 9:
                $texto = $this->restpuesta_certificado9();
                break;
            case 10:
                $texto = $this->restpuesta_certificado10();
                break;
            case 11:
                $texto = $this->restpuesta_certificado11();
                break;
            case 12:
                $texto = $this->restpuesta_certificado12();
                break;
            case 13:
                $texto = $this->restpuesta_certificado13();
                break;
            case 14:
                $texto = $this->restpuesta_certificado14();
                break;
            case 15:
                $texto = $this->restpuesta_certificado15();
                break;
            case 16:
                $texto = $this->restpuesta_certificado16();
                break;
            case 17:
                $texto = $this->restpuesta_certificado17();
                break;
            case 18:

                if ($post["archivos_nuevos"] != "") {
                    $contenido = file_get_contents($post["archivos_nuevos"], true);
//                $contenido2 = explode("\n", $contenido);
                    $contenido2 = str_replace("\n", ",", $contenido);
                    $contenido2 = str_replace("\r", "", $contenido2);
                }
//                print_r($contenido2);
                $contenido2 = explode(',', $contenido2);
                $texto = "";
                for ($i = 0; $i < count($contenido2); $i++) {
                    $post["empresa"] = $contenido2[$i];
                    $texto.= $this->restpuesta_certificado17($post["empresa"]);
                    $texto.= '<br style="page-break-after: always;" />';
                }

//                $texto = $this->restpuesta_certificado17();
                break;
            case 19:
                $texto = $this->restpuesta_certificado19();
				//echo $texto;
				//die();
                $array = "La presente se expide a solicitud del Grupo de Procesos y Conciliaciones de La Dirección Jurídica del SENA a los " . strtolower($this->numeros_letras_model->ValorEnLetras(date('d'), "")) . " (" . date('d') . ") dias del mes de " . $this->modificar_mes(date('m')) . " de " . strtolower($this->numeros_letras_model->ValorEnLetras(date('Y'), "")) . " (" . date('Y') . ")";
                $texto = str_replace('%-EXPEDIDA-%', $array, $texto);
                break;
        }
	
        $cod = crc32($texto);
		if ($cod < 0)
            $cod = $cod * -1;
        $id = $this->Reporteador_model->insertar_certificados($cod);
        $array = "Expedido por el SENA, a los " . strtolower($this->numeros_letras_model->ValorEnLetras(date('d'), "")) . " (" . date('d') . ") dias del mes de " . $this->modificar_mes(date('m')) . " de " . date('Y');
        $texto = str_replace('%-EXPEDIDA-%', $array, $texto);
        $texto = str_replace('%-COD_VERIFICACION2-%', $cod, $texto);
        $cod = $cod . $id;
        $this->pdf($texto, $id = "No:" . $id, $cod);
    }

    function imprimir_BDME() {
        $post = $this->input->post();
        $this->data['titulo'] = $post['titulo'];
        $this->data['post'] = $post;
        if (isset($post['bdme'])) {
            switch ($post['bdme']) {
                case 'bdme_incumplimiento_semestral':
                    $texto = $this->Reporteador_model->bdme_incumplimiento_semestral();
                    break;
                case 'bdme_reporte_semestral':
                    $texto = $this->Reporteador_model->bdme_reporte_semestral();
                    break;
                case 'bdme_actualizacion':
                    $texto = $this->Reporteador_model->bdme_actualizacion();
                    break;
                case 'bdme_cancelacion_acuerdo_de_pago':
                    $texto = $this->Reporteador_model->bdme_cancelacion_acuerdo_de_pago();
                    break;
                case 'bdme_retiro':
                    $texto = $this->Reporteador_model->bdme_retiro();
                    break;
            }
            $this->data['informacion'] = $texto;
            if ($post['accion'] == 0) {
                $this->template->load($this->template_file, 'reporteador/bdme', $this->data);
                $this->load->view('reporteador/reporte', $this->data);
            } else if ($post['accion'] == 2 || $post['accion'] == 1) {
                $this->load->view('reporteador/reporte', $this->data);
            } else {
                $this->template->load($this->template_file, 'reporteador/bdme', $this->data);
            }
        } else {
            $this->template->load($this->template_file, 'reporteador/bdme', $this->data);
        }
    }

    function restpuesta_certificado3($empresa = null) {

        $post = $this->input->post();
        if ($empresa != null) {
            $nit_empresa[0] = $empresa;
        } else {
            $nit_empresa = $post["empresa"];
            $nit_empresa = explode(" - ", $post["empresa"]);
        }

        $empresa = $this->Reporteador_model->empresa_consulta($nit_empresa[0]);
        if (empty($empresa[0]['NITEMPRESA'])) {
            $txt = $this->Reporteador_model->plantilla(112);
            $array['CODEMPRESA'] = $nit_empresa[0];
        } else {
            $periodos = $this->periodos3($nit_empresa[0]);
            $array['CODEMPRESA'] = $nit_empresa[0];
            $txt = $this->Reporteador_model->plantilla(117);
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            $array['ano'] = $post['ano'];
            $array['TABLA_INFORMACION'] = $periodos['html'];
            $array['VALOR'] = number_format($periodos['valor']);
            $array['VALOR_LETRAS'] = $this->numeros_letras_model->ValorEnLetras($periodos['valor'], "PESOS");
        }
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado7() {
        $post = $this->input->post();
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta($nit_empresa[0]);
//        echo "<pre>";
//        print_r($empresa);
        if (empty($empresa[0]['NITEMPRESA'])) {
            $txt = $this->Reporteador_model->plantilla(112);
            $array['CODEMPRESA'] = $nit_empresa[0];
        } else {// 17// 62
            $empresa = $this->Reporteador_model->empresa_consulta2($nit_empresa[0], $post['obra']);
            $tabla = '<table border="1"><tr><td>Nom. Obra</td><td>Empleados</td><td>Valor Pago</td><td>Periodo</td></tr>';
            foreach ($empresa as $empresa2) {
                $tabla.="<tr>"
                        . "<td>" . $empresa2['NOM_OBRA'] . "</td>"
                        . "<td>" . $empresa2['EMPLEADOS'] . "</td>"
                        . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                        . "<td>" . $empresa2['PERIODO'] . "</td>"
                        . "</tr>";
            }
            $tabla.='</table>';

            $array['CODEMPRESA'] = $nit_empresa[0];
            $array['OBRA'] = $post['obra'];
            $txt = $this->Reporteador_model->plantilla(125);
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            $array['ano'] = $post['ano'];
            $array['TABLA_INFORMACION'] = $tabla;
//            $array['VALOR'] = number_format($periodos['valor']);
//            $array['VALOR_LETRAS'] = $this->numeros_letras_model->ValorEnLetras($periodos['valor'], "PESOS");
        }
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
//        echo $texto;
//        die();
        return $texto;
    }

    function restpuesta_certificado8() {

        $post = $this->input->post();
        $empresa = $this->Reporteador_model->empresa_consulta3($post['transac']);
        $tabla = '<table border="1">'
                . '<tr><td>NIT</td>'
                . '<td>Razón Social</td>'
                . '<td>No. Obra</td>'
                . '<td>Regional</td>'
                . '<td>Trab.</td>'
                . '<td>Pago</td>'
                . '<td>Periodo Obra</td>'
                . '</tr>';
        if (count($empresa) != 0)
            foreach ($empresa as $empresa2) {
                $tabla.="<tr>"
                        . "<td>" . $empresa2['NITEMPRESA'] . "</td>"
                        . "<td>" . $empresa2['NOMBRE_EMPRESA'] . "</td>"
                        . "<td>" . $empresa2['NRO_LICENCIA_CONTRATO'] . "</td>"
                        . "<td>" . $empresa2['NOMBRE_REGIONAL'] . "</td>"
                        . "<td>" . $empresa2['EMPLEADOS'] . "</td>"
                        . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                        . "<td>" . $empresa2['PERIODO'] . "</td>"
                        . "</tr>";
            }
        $tabla.='</table>';
        $array['TRANSACCION'] = $post['transac'];
        $txt = $this->Reporteador_model->plantilla(126);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);

        return $texto;
    }

    function restpuesta_certificado9() {

        $post = $this->input->post();
        $empresa = $this->Reporteador_model->empresa_consulta3($post['transac']);
        $tabla = '<table border="1">'
                . '<tr>'
                . '<td>No. Obra</td>'
                . '<td>Nom. Obra</td>'
                . '<td>Empleados</td>'
                . '<td>Valor Pago</td>'
                . '<td>Periodo</td>'
                . '</tr>';
        $array['CODEMPRESA'] = '';
        $array['NOMBRE_EMPRESA'] = '';
        if (count($empresa) != 0) {
            foreach ($empresa as $empresa2) {
                $tabla.="<tr>"
                        . "<td>" . $empresa2['NRO_LICENCIA_CONTRATO'] . "</td>"
                        . "<td>" . $empresa2['NOM_OBRA'] . "</td>"
                        . "<td>" . $empresa2['EMPLEADOS'] . "</td>"
                        . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                        . "<td>" . $empresa2['PERIODO'] . "</td>"
                        . "</tr>";
            }
            $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
        }
        $tabla.='</table>';
        $array['TRANSACCION'] = $post['transac'];
        $txt = $this->Reporteador_model->plantilla(127);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);

        return $texto;
    }

    function restpuesta_certificado10() {

        $post = $this->input->post();
        $empresa = $this->Reporteador_model->empresa_consulta_obra($post['obra']);
        $tabla = '<table border="1">'
                . '<tr>'
                . '<td>NIT</td>'
                . '<td>Razón Social</td>'
                . '<td>Transacción</td>'
                . '<td>Empleados</td>'
                . '<td>Valor Pago</td>'
                . '<td>Periodo</td>'
                . '</tr>';
        $array['CODEMPRESA'] = '';
        $array['NOMBRE_EMPRESA'] = '';
        if (count($empresa) != 0) {
            foreach ($empresa as $empresa2) {
                $tabla.="<tr>"
                        . "<td>" . $empresa2['NITEMPRESA'] . "</td>"
                        . "<td>" . $empresa2['NOMBRE_EMPRESA'] . "</td>"
                        . "<td>" . $empresa2['NUM_DOCUMENTO'] . "</td>"
                        . "<td>" . number_format($empresa2['EMPLEADOS']) . "</td>"
                        . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                        . "<td>" . $empresa2['PERIODO'] . "</td>"
                        . "</tr>";
            }
            $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
        }
        $tabla.='</table>';
        $array['LICENCIA'] = $post['obra'];
        $txt = $this->Reporteador_model->plantilla(128);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);

        return $texto;
    }

    function restpuesta_certificado11() {

        $post = $this->input->post();
//        echo "<pre>";
//        print_r($post);
//        echo "</pre>";
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta_nit($nit_empresa[0]);
        $tabla = '<table border="1">'
                . '<tr>'
                . '<td>Empleados</td>'
                . '<td>Pago</td>'
                . '<td>Transacción</td>'
                . '<td>Periodo</td>'
                . '</tr>';
        $array['CODEMPRESA'] = $nit_empresa[0];
        $array['NOMBRE_EMPRESA'] = '';
        if (count($empresa) != 0) {
            foreach ($empresa as $empresa2) {
                $tabla.="<tr>"
                        . "<td>" . number_format($empresa2['EMPLEADOS']) . "</td>"
                        . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                        . "<td>" . $empresa2['NUM_DOCUMENTO'] . "</td>"
                        . "<td>" . $empresa2['PERIODO'] . "</td>"
                        . "</tr>";
            }
            $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
        }
        $tabla.='</table>';
//        $array['LICENCIA'] = $post['obra'];
        $txt = $this->Reporteador_model->plantilla(129);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado12() {

        $post = $this->input->post();
//        echo "<pre>";
//        print_r($post);
//        echo "</pre>";
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta_nit($nit_empresa[0]);
        $tabla = '<table border="1">'
                . '<tr>'
                . '<td>Num Obra</td>'
                . '<td>Nombre Obra</td>'
                . '<td>Pago</td>'
                . '<td>Periodo</td>'
                . '<td>Trab</td>'
                . '</tr>';
        $array['CODEMPRESA'] = $nit_empresa[0];
        $array['NOMBRE_EMPRESA'] = '';
        if (count($empresa) != 0) {
            foreach ($empresa as $empresa2) {
                $tabla.="<tr>"
                        . "<td>" . $empresa2['NRO_LICENCIA_CONTRATO'] . "</td>"
                        . "<td>" . $empresa2['NOM_OBRA'] . "</td>"
                        . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                        . "<td>" . $empresa2['PERIODO'] . "</td>"
                        . "<td>" . number_format($empresa2['EMPLEADOS']) . "</td>"
                        . "</tr>";
            }
            $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
        }
        $tabla.='</table>';
//        $array['LICENCIA'] = $post['obra'];
        $txt = $this->Reporteador_model->plantilla(130);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado13() {

        $post = $this->input->post();
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta_nit_resolu($nit_empresa[0], $post['num_proceso']);
        $array['CODEMPRESA'] = $nit_empresa[0];
        $array['NUM_PROCESO'] = $post['num_proceso'];
//        echo count($empresa);
//        die();
        if (count($empresa) > 0) {
            $tabla = '<table border="1">'
                    . '<tr>'
                    . '<td>Fecha</td>'
                    . '<td>No. Transacción o Nota</td>'
                    . '<td>Valor</td>'
                    . '</tr>';
            $array['NOMBRE_EMPRESA'] = '';
            if (count($empresa) != 0) {
                foreach ($empresa as $empresa2) {
                    $tabla.="<tr>"
                            . "<td>" . $empresa2['FECHA_PAGO'] . "</td>"
                            . "<td>" . $empresa2['NUM_DOCUMENTO'] . "</td>"
                            . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                            . "</tr>";
                }
                $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
                $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            }
            $tabla.='</table>';
        } else {
            $tabla = "NO HAY PAGOS PARA ESTE NIT";
        }
//        $array['LICENCIA'] = $post['obra'];
        $txt = $this->Reporteador_model->plantilla(131);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado14() {

        $post = $this->input->post();
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta_nit_resolu2($nit_empresa[0]);
        $array['CODEMPRESA'] = $nit_empresa[0];
        $array['NUM_PROCESO'] = $post['num_proceso'];
//        echo count($empresa);
//        die();
        if (count($empresa) > 0) {
            $tabla = '<table border="1">'
                    . '<tr>'
                    . '<td>Liquidación / Resolucion</td>'
                    . '<td>Razón Social</td>'
                    . '<td>Fecha</td>'
                    . '<td>No. Transacción o Nota</td>'
                    . '<td>Valor</td>'
                    . '</tr>';
            $array['NOMBRE_EMPRESA'] = '';
            if (count($empresa) != 0) {
                foreach ($empresa as $empresa2) {
                    $tabla.="<tr>"
                            . "<td>" . $empresa2['NUM_LIQUIDACION'] . " / " . $empresa2['NUMERO_RESOLUCION'] . "</td>"
                            . "<td>" . $empresa2['NOMBRE_EMPRESA'] . "</td>"
                            . "<td>" . $empresa2['FECHA_PAGO'] . "</td>"
                            . "<td>" . $empresa2['NUM_DOCUMENTO'] . "</td>"
                            . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                            . "</tr>";
                }
                $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
                $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            }
            $tabla.='</table>';
        } else {
            $tabla = "NO HAY PAGOS PARA ESTE NIT";
        }
//        $array['LICENCIA'] = $post['obra'];
        $txt = $this->Reporteador_model->plantilla(132);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado15() {

        $post = $this->input->post();
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta_nit_resolu3($post['num_proceso']);
        $array['CODEMPRESA'] = $nit_empresa[0];
        $array['NUM_PROCESO'] = $post['num_proceso'];
//        echo count($empresa);
//        die();
        if (count($empresa) > 0) {
            $tabla = '<table border="1">'
                    . '<tr>'
                    . '<td>NIT</td>'
                    . '<td>Fecha</td>'
                    . '<td>Valor</td>'
                    . '<td>No. Transacción o Nota</td>'
                    . '</tr>';
            $array['NOMBRE_EMPRESA'] = '';
            if (count($empresa) != 0) {
                foreach ($empresa as $empresa2) {
                    $tabla.="<tr>"
                            . "<td>" . $empresa2['NITEMPRESA'] . "</td>"
                            . "<td>" . $empresa2['FECHA_PAGO'] . "</td>"
                            . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                            . "<td>" . $empresa2['NUM_DOCUMENTO'] . "</td>"
                            . "</tr>";
                }
                $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
                $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            }
            $tabla.='</table>';
        } else {
            $tabla = "NO HAY PAGOS PARA ESTE NIT";
        }
//        $array['LICENCIA'] = $post['obra'];
        $txt = $this->Reporteador_model->plantilla(133);
        $array['TABLA_INFORMACION'] = $tabla;
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado16() {
        $post = $this->input->post();
        $nit_empresa = explode(" - ", $post["empresa"]);
        $empresa = $this->Reporteador_model->empresa_consulta($nit_empresa[0]);
//        echo "<pre>";
//        print_r($empresa);
        if (empty($empresa[0]['NITEMPRESA'])) {
            $txt = $this->Reporteador_model->plantilla(112);
            $array['CODEMPRESA'] = $nit_empresa[0];
        } else {// 17// 62
            $empresa = $this->Reporteador_model->empresa_consulta_periodo($nit_empresa[0], $post['periodo']);
            if ($empresa[0]['VALOR_PAGADO'] != "") {
                $tabla = '<table border="1"><tr>'
                        . '<td>Num. Licencia</td>'
                        . '<td>Nom. Obra</td>'
                        . '<td>Empleados</td>'
                        . '<td>Valor Pago</td>'
                        . '</tr>';
                foreach ($empresa as $empresa2) {
                    $tabla.="<tr>"
                            . "<td>" . $empresa2['NRO_LICENCIA_CONTRATO'] . "</td>"
                            . "<td>" . $empresa2['NOM_OBRA'] . "</td>"
                            . "<td>" . $empresa2['EMPLEADOS'] . "</td>"
                            . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                            . "</tr>";
                }
                $tabla.='</table>';
            } else {
                $tabla = "NO HAY PAGOS PARA ESTE NIT";
            }
            $array['CODEMPRESA'] = $nit_empresa[0];
//            $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            $array['OBRA'] = $post['obra'];
            $txt = $this->Reporteador_model->plantilla(134);
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
//            $array['ano'] = $post['ano'];
            $post['periodo'] = explode('-', $post['periodo']);
            $array['MES'] = $this->modificar_mes($post['periodo'][1]);
            $array['ANO'] = $post['periodo'][0];
            $array['TABLA_INFORMACION'] = $tabla;
//            $array['VALOR'] = number_format($periodos['valor']);
//            $array['VALOR_LETRAS'] = $this->numeros_letras_model->ValorEnLetras($periodos['valor'], "PESOS");
        }
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
//        echo $texto;
//        die();
        return $texto;
    }

    function restpuesta_certificado17($empresa = null) {
        $post = $this->input->post();
        if ($empresa != null) {
            $nit_empresa[0] = $empresa;
        } else {
            $nit_empresa = $post["empresa"];
            $nit_empresa = explode(" - ", $post["empresa"]);
        }
        $empresa = $this->Reporteador_model->empresa_consulta($nit_empresa[0]);
        $array['NOMBRE_EMPRESA'] = "";
        $v_total = 0;
//        echo "<pre>";
//        print_r($empresa);
        if (empty($empresa[0]['NITEMPRESA'])) {
            $txt = $this->Reporteador_model->plantilla(112);
            $array['CODEMPRESA'] = $nit_empresa[0];
        } else {// 17// 62
            $empresa = $this->Reporteador_model->empresa_consulta_resiprocas($nit_empresa[0], $post['ano']);
            if (count($empresa) > 0) {
                $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
                if ($empresa[0]['VALOR_PAGADO'] != "") {
                    $tabla = '<table border="1"><tr>'
                            . '<td>Fecha</td>'
                            . '<td>Periodo</td>'
                            . '<td>Metodologia</td>'
                            . '<td>Aporte</td>'
                            . '<td>Concepto</td>'
                            . '<td>No. pago</td>'
                            . '<td>Sucursal</td>'
                            . '</tr>';
                    foreach ($empresa as $empresa2) {
                        $tabla.="<tr>"
                                . "<td>" . $empresa2['FECHA_PAGO'] . "</td>"
                                . "<td>" . $empresa2['PERIODO_PAGADO'] . "</td>"
                                . "<td>" . $empresa2['PROCEDENCIA'] . "</td>"
                                . "<td>" . number_format($empresa2['VALOR_PAGADO']) . "</td>"
                                . "<td>" . $empresa2['NOMBRE_TIPO'] . "</td>"
                                . "<td>" . $empresa2['COD_PAGO'] . "</td>"
                                . "<td>" . $empresa2['NOMBREBANCO'] . "</td>"
                                . "</tr>";
                        $v_total+=$empresa2['VALOR_PAGADO'];
                    }
                    $tabla.='</table>';
                } else {
                    $tabla = "NO HAY PAGOS PARA ESTE NIT";
                }
            } else {
                $tabla = "NO HAY PAGOS PARA ESTE NIT";
            }
            $array['CODEMPRESA'] = $nit_empresa[0];
//            $array['CODEMPRESA'] = $empresa[0]['NITEMPRESA'];
            $array['VALOR_TOTAL'] = $v_total;
            $array['ANO'] = $post['ano'];
            $array['OBRA'] = $post['obra'];
            $txt = $this->Reporteador_model->plantilla(143);
            $array['ANO'] = $post['ano'];
            $array['TABLA_INFORMACION'] = $tabla;
//            $array['VALOR'] = number_format($periodos['valor']);
            $array['VALOR_LETRAS'] = $this->numeros_letras_model->ValorEnLetras($v_total, "PESOS");
        }
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
//        echo $texto;
//        die();
        return $texto;
    }

    function restpuesta_certificado19() {
        $post = $this->input->post();
        $deuda = $this->Reporteador_model->empresa_consulta_no_misional($post);
		$deuda2 = $this->Reporteador_model->empresa_consulta_no_misional_2($post);
		$deudaultimopago = $this->Reporteador_model->ultimo_pago_cert($post);
		$ultimo_pago=explode('/', $deudaultimopago[0]['MAYOR']) ;
		
		$array['MES_ULT_PAGO'] = $this->modificar_mes($ultimo_pago[1]);
        $array['DIA_ULT_PAGO'] = $ultimo_pago[0];
        $array['ANO_ULT_PAGO'] = '20'.$ultimo_pago[2];

		$array['CARGO_COORD'] = $post['cargo_coord'];
		$array['NOMBRE_COORD'] = $post['nombre_coord'];
		$array['GENERA'] = $post['genera'];
		$array['REGIONAL'] = $deuda[0]['NOMBRE_REGIONAL'];
        $array['DEUDOR'] = $deuda[0]['NOMBRE_DEUDOR'];
        $array['COD_DEUDOR'] = $deuda[0]['COD_DEUDOR'];
        $array['CONCEPTO'] = $deuda[0]['TIPO_CARTERA'];
        $array['ACLARACIONES'] = $post['aclaraciones'];
        $array['MES'] = $this->modificar_mes(date('n'));
        $array['DIA'] = date('d');
        $array['ANO'] = date('Y');
        $array['VALOR_ACTUAL'] = number_format($deuda[0]['SALDO']);
        
        if(!empty($deuda2))
		{
		$array['INTERES_C_ACUMULADO_MORA'] = $deuda2[0]['INTERES_C'];	
		}
		else{
		$array['INTERES_C_ACUMULADO_MORA'] = 0;
		}
		$array['VALOR_INTERESES'] = number_format($deuda[0]['INTERESES_A_LA_FECHA']+$array['INTERES_C_ACUMULADO_MORA']);
        $array['VALOR_TOTAL'] = number_format($deuda[0]['SALDO'] + $deuda[0]['INTERESES_A_LA_FECHA']+$array['INTERES_C_ACUMULADO_MORA']);
        $array['USUARIO'] = NOMBRE_COMPLETO;
        $table = '<table border="1"><tr>'
                . "<td>PERIODOS EN MORA</td>"
                . "<td>CUOTA MENSUAL PACTADA NO CANCELADA</td>"
                . "</tr>";
        foreach ($deuda as $value) {
            $table.="<tr>"
                    . "<td>" . $value['PERIODO_EN_MORA'] . "</td>"
                    . "<td>" . number_format($value['SALDO_CUOTA']) . "</td>"
                    . "</tr>";
        }
        $table.='</table>';
        $array['TABLA_INFORMACION'] = $table;
//        die();
        switch ($post['tipo_certificacion']) {
            case 1:
                $txt = $this->Reporteador_model->plantilla(144);
                break;
            case 2:
                $txt = $this->Reporteador_model->plantilla(145);
                break;
            case 3:
                $txt = $this->Reporteador_model->plantilla(146);
                break;
            case 4:
                $txt = $this->Reporteador_model->plantilla(147);
                break;
        }

        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        //echo $texto;
        //die();
        return $texto;
    }

    function restpuesta_certificado2($empresa='') {
        $post = $this->input->post();
        if (isset($post["empresa"])) {
            $nit_empresa = explode(" - ", $post["empresa"]);
        } else {
            $nit_empresa[0] = $empresa;
        }
        $empresa = $this->Reporteador_model->empresa_consulta($nit_empresa[0]);
        if (empty($empresa[0]['NITEMPRESA'])) {
            $txt = $this->Reporteador_model->plantilla(112);
            $array['CODEMPRESA'] = $nit_empresa[0];
        } else {
            $periodos = $this->periodos2($nit_empresa[0]);
            $array['CODEMPRESA'] = $nit_empresa[0];
            if ($periodos['valor'] != 0) {
                $txt = $this->Reporteador_model->plantilla(115);
                $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
                $array['TABLA_INFORMACION'] = $periodos['html'];
                $array['VALOR'] = number_format($periodos['valor']);
                $array['VALOR_LETRAS'] = $this->numeros_letras_model->ValorEnLetras($periodos['valor'], "PESOS");
            } else {
                $txt = $this->Reporteador_model->plantilla(116);
            }
        }
        $array['ANO'] = $post['ano'];
        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function restpuesta_certificado1($empresa = null) {

        $post = $this->input->post();
        if ($empresa != null) {
            $nit_empresa[0] = $empresa;
        } else {
            $nit_empresa = $post["empresa"];
            $nit_empresa = explode(" - ", $post["empresa"]);
        }
        $empresa = $this->Reporteador_model->empresa_consulta($nit_empresa[0]);
        $array['NOMBRE_EMPRESA'] = "";
        $array['VALOR_TOTAL'] = "";
        $array['TABLA_INFORMACION'] = "";

        if (empty($empresa[0]['NITEMPRESA'])) {
            $txt = $this->Reporteador_model->plantilla(112);
        } else {
            $periodos = $this->periodos($nit_empresa[0]);

            if ($periodos === 0) {
                if ($empresa[0]['SALDO_DEUDA'] <= 0) {
                    $txt = $this->Reporteador_model->plantilla(113);
                    $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
                } else
                    $txt = $this->Reporteador_model->plantilla(135);
            } else {
                $txt = $this->Reporteador_model->plantilla(114);
            }
            $array['NOMBRE_EMPRESA'] = $empresa[0]['NOMBRE_EMPRESA'];
            $array['TABLA_INFORMACION'] = $periodos;
        }



        $array['DEUDAS_ANTERIORES'] = "";
        if ($empresa[0]['SALDO_DEUDA'] > 0) {
            $empresa = $this->Reporteador_model->empresa_consulta_resolucion($nit_empresa[0]);
            $array['DEUDAS_ANTERIORES'] = "Registra las siguientes carteras en: ";
            foreach ($empresa as $value) {
                $array['DEUDAS_ANTERIORES'].=$value['NOMBRE_REGIONAL'] . ' con Resolución ' . $value['NUMERO_RESOLUCION'] . ' por concepto de ' . $value['NOMBRE_CONCEPTO'] . "; ";
            }
            $array['DEUDAS_ANTERIORES'] = substr($array['DEUDAS_ANTERIORES'], 0, -1);
        }
        $array['CODEMPRESA'] = $nit_empresa[0];

        $texto = template_tags("./uploads/plantillas/" . $txt, $array);
        return $texto;
    }

    function r_liquidacion() {
        $this->data['titulo'] = 'Reporte Liquidaci&oacute;n';
        $this->data['vista'] = 'r_liquidacion';
        $this->activo2('r_liquidacion');
        $this->data['desplegable'] = $this->general(3, "");
//        $post = $this->input->post();
//        if (isset($post['empresa']))
//            $this->data['akiva'] = $this->reporte();
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_ugpp() {
        $this->data['titulo'] = 'REPORTE UGPP';
        $this->data['vista'] = 'r_ugpp';
        $this->activo2('r_ugpp');
        $this->template->load($this->template_file, 'reporteador/r_ugpp', $this->data);
    }

    function r_resolucion2() {
//        echo "entro";die;
        $this->data['titulo'] = 'Reporte Resoluci&oacute;n';
        $this->data['vista'] = 'r_resolucion2';
        $this->activo2('r_resolucion2');
        $this->data['desplegable'] = $this->general(4, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function fiscalizacion_por_CIIU() {
        $this->data['titulo'] = 'Generar informe fiscalización por CIIU';
        $this->data['vista'] = 'fiscalizacion_por_CIIU';
        $this->activo2('fiscalizacion_por_CIIU');
        $this->data['desplegable'] = $this->general(16, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_resunta_real() {
        $this->data['titulo'] = 'Cartera Real';
        $this->data['vista'] = 'r_cartera_resunta_real';
        $this->activo2('r_cartera_resunta_real');
        $this->data['desplegable'] = $this->general(53, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_acuerdo_pago_persuasivo() {
        $this->data['titulo'] = 'Reporte Acuerdos de Pago en Persuasivo';
        $this->data['vista'] = 'r_acuerdo_pago_persuasivo';
        $this->activo2('r_acuerdo_pago_persuasivo');
        $this->data['desplegable'] = $this->general(6, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_contrato_fig() {
        $this->data['titulo'] = 'Contratos FIC';
        $this->data['vista'] = 'r_contrato_fig';
        $this->activo2('r_contrato_fig');
        $this->data['desplegable'] = $this->general(35, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_liquidaciones_pendiente_pago() {
        $this->data['titulo'] = 'Liquidaciones pendientes de Pago';
        $this->data['vista'] = 'r_liquidaciones_pendiente_pago';
        $this->activo2('r_liquidaciones_pendiente_pago');
        $this->data['desplegable'] = $this->general(5, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_liquidaciones_pendiente_gestion() {
        $this->data['titulo'] = 'Liquidaciones Pendientes por Gestión';
        $this->data['vista'] = 'r_liquidaciones_pendiente_gestion';
        $this->activo2('r_liquidaciones_pendiente_gestion');
        $this->data['desplegable'] = $this->general(54, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_informe_visitas() {
        $this->data['titulo'] = 'Visitas General';
        $this->data['vista'] = 'r_informe_visitas';
        $this->activo2('r_informe_visitas');
        $this->data['desplegable'] = $this->general(29, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_recuperacion_cartera() {
        $this->data['titulo'] = 'Recuperación Cartera';
        $this->data['vista'] = 'r_recuperacion_cartera';
        $this->activo2('r_recuperacion_cartera');
        $this->data['desplegable'] = $this->general(22, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_gestion() {
        $this->data['titulo'] = 'Gesti&oacute;n por Resultados';
        $this->data['vista'] = 'r_gestion';
        $this->activo2('r_gestion');
        $this->data['desplegable'] = $this->general(25, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_contrato_aprendizaje() {
        $this->data['titulo'] = 'Contrato de Aprendizaje';
        $this->data['vista'] = 'r_contrato_aprendizaje';
        $this->activo2('r_contrato_aprendizaje');
        $this->data['desplegable'] = $this->general(26, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_gestion_por_empresa() {
        $this->data['titulo'] = 'Reporte Gestión por Empresa';
        $this->data['vista'] = 'r_gestion_por_empresa';
        $this->activo2('r_gestion_por_empresa');
        $this->data['desplegable'] = $this->general(37, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_estado_cartera() {
        $this->data['titulo'] = 'Reporte Estado de Cartera';
        $this->data['vista'] = 'r_estado_cartera';
        $this->activo2('r_estado_cartera');
        $this->data['desplegable'] = $this->general(7, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_incumplimiento_pago() {
        $this->data['titulo'] = 'Incumplimiento de Acuerdo de Pago';
        $this->data['vista'] = 'r_incumplimiento_pago';
        $this->activo2('r_incumplimiento_pago');
        $this->data['desplegable'] = $this->general(18, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_proceso_sancionatorio() {
        $this->data['titulo'] = 'Reporte Procesos Sancionatorios';
        $this->data['vista'] = 'r_proceso_sancionatorio';
        $this->activo2('r_proceso_sancionatorio');
        $this->data['desplegable'] = $this->general(27, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_asignacion_fiscalizacion() {
        $this->data['titulo'] = 'Reporte Fiscalizaci&oacute;n';
        $this->data['vista'] = 'r_asignacion_fiscalizacion';
        $this->activo2('r_asignacion_fiscalizacion');
        $this->data['desplegable'] = $this->general(30, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_estado_fiscalizacion() {
        $this->data['titulo'] = 'Reporte Estado de Fiscalización';
        $this->data['vista'] = 'r_estado_fiscalizacion';
        $this->activo2('r_estado_fiscalizacion');
        $this->data['desplegable'] = $this->general(31, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_resoluciones_coac() {
        $this->data['titulo'] = 'Resolución';
        $this->data['vista'] = '';
        $this->activo2('r_resoluciones_coac');
        $this->data['desplegable'] = $this->general(4, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_acuerdo_pago_coac() {
        $this->data['titulo'] = 'Acuerdo de pago Coactivo';
        $this->data['vista'] = 'r_acuerdo_pago_coac';
        $this->activo2('r_acuerdo_pago_coac');
        $this->data['desplegable'] = $this->general(55, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_resoluciones_pendiente_pago() {
        $this->data['titulo'] = 'Resoluciones Pendientes por Pago';
        $this->data['vista'] = 'r_resoluciones_pendiente_pago';
        $this->activo2('r_resoluciones_pendiente_pago');
        $this->data['desplegable'] = $this->general(50, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_resoluciones_pendiente_pago_gestion() {
        $this->data['titulo'] = 'Resoluciones Pendientes por Gestión';
        $this->data['vista'] = 'r_resoluciones_pendiente_pago_gestion';
        $this->activo2('r_resoluciones_pendiente_pago_gestion');
        $this->data['desplegable'] = $this->general(51, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_abogado_coac() {
        $this->data['titulo'] = 'Reporte Abogado';
        $this->data['vista'] = 'r_abogado_coac';
        $this->activo2('r_abogado');
        $this->data['desplegable'] = $this->general(32, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_multas_devolucion() {
        $this->data['titulo'] = 'Multas del Ministerio';
        $this->data['vista'] = 'r_multas_devolucion';
        $this->activo2('r_multas_devolucion');
        $this->data['desplegable'] = $this->general('36', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_devolucion() {
        $this->data['titulo'] = 'Ingreso Por Devolución';
        $this->data['vista'] = 'r_devolucion';
        $this->activo2('r_devolucion');
        $this->data['desplegable'] = $this->general('72', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_incumplimiento_pago_coac() {
        $this->data['titulo'] = 'Incumplimiento de Acuerdo de Pago';
        $this->data['vista'] = 'r_incumplimiento_pago_coac';
        $this->activo2('r_incumplimiento_pago_coac');
        $this->data['desplegable'] = $this->general(56, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_recuperacion_cartera_coac() {
        $this->data['titulo'] = 'Recuperación de Cartera';
        $this->data['vista'] = 'r_recuperacion_cartera_coac';
        $this->activo2('r_recuperacion_cartera_coac');
        $this->data['desplegable'] = $this->general(22, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_abogado() {
        $this->data['titulo'] = 'Reporte Abogado';
        $this->data['vista'] = 'r_abogado';
        $this->activo2('r_abogado');
        $this->data['desplegable'] = $this->general(32, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_sansionatorio() {
        $this->data['titulo'] = 'Procesos Sancionatorios';
        $this->data['vista'] = 'r_sansionatorio';
        $this->activo2('r_sansionatorio');
        $this->data['desplegable'] = $this->general(27, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_edad_cartera() {
        $this->data['titulo'] = 'Cartera por Edad';
        $this->data['vista'] = 'r_edad_cartera';
        $this->activo2('r_edad_cartera');
        $this->data['desplegable'] = $this->general(10, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_estado_cuenta() {
        $this->data['titulo'] = 'Estado de Cartera';
        $this->data['vista'] = 'r_estado_cuenta';
        $this->activo2('r_estado_cuenta');
        $this->data['desplegable'] = $this->general('', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_reciprocas() {
        $this->data['titulo'] = 'Reporte Reciprocas';
        $this->data['vista'] = 'r_estado_cuenta';
        $this->activo2('r_estado_cuenta');
        $this->data['desplegable'] = $this->general('', "");
        $this->template->load($this->template_file, 'reporteador/reciprocas', $this->data);
    }

    function admin_reciprocas() {
        $this->data['titulo'] = 'Admin. Asignar Cuentas';
        $this->data['vista'] = 'admin_reciprocas';
        $this->activo2('admin_reciprocas');
        $this->data['desplegable'] = $this->general('', "");
        $this->data['tables'] = $this->Reporteador_model->info_subconcepto();
        $this->template->load($this->template_file, 'reporteador/admin_reciprocas', $this->data);
    }

    function r_pagos_recibidos() {
        $this->data['titulo'] = 'Reportes de Conciliación de Cartera';
        $this->data['vista'] = 'r_pagos_recibidos';
        $this->activo2('r_pagos_recibidos');
        $this->data['desplegable'] = $this->general(9, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_generar_pagos_nit() {
        $this->data['titulo'] = 'Generar Pagos por NIT';
        $this->data['vista'] = 'r_generar_pagos_nit';
        $this->activo2('r_generar_pagos_nit');
        $this->data['desplegable'] = $this->general(9, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_comparativo_periodos() {
        $this->data['titulo'] = 'Reporte Comparativo por Periodos';
        $this->data['vista'] = 'r_comparativo_periodos';
        $this->activo2('r_comparativo_periodos');
        $this->data['desplegable'] = $this->general(23, "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_detallado_cobro() {
        $this->personalizar_datos();
//        $this->data['titulo'] = 'Reporte Detallado de Cobro';
//        $this->activo2('r_detallado_cobro');
//        $this->data['desplegable'] = $this->general('', "");
//        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_BDME() {
        $this->activo2('r_BDME');
        $this->data['titulo'] = 'REPORTE RIESGOS - BDME';
        $this->template->load($this->template_file, 'reporteador/bdme', $this->data);
    }

    function r_exogeno() {
        $this->data['vista'] = 'r_exogeno';
        $this->data['titulo'] = 'Reporte Información Exógena';
        $this->activo2('r_exogeno');
        $this->data['desplegable'] = $this->general('74', "");
        $this->template->load($this->template_file, 'reporteador/exogenas', $this->data);
    }

    function r_kactus() {
        $this->data['vista'] = 'r_kactus';
        $this->data['titulo'] = 'REPORTE KACTUS';
        $this->activo2('r_kactus');
        $this->data['desplegable'] = $this->general('57', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_no_misional() {
        $this->data['vista'] = 'r_cartera_no_misional';
        $this->data['titulo'] = 'Reporte General de Ingresos Cartera No Misional';
        $this->activo2('r_cartera_no_misional');
        $this->data['desplegable'] = $this->general('62', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }
	
	    function r_cartera_no_misional_gen() {
        $this->data['vista'] = 'r_cartera_no_misional_gen';
        $this->data['titulo'] = 'Reporte General de Ingresos Cartera No Misional';
        $this->activo2('r_cartera_no_misional_gen');
        $this->data['desplegable'] = $this->general('81', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_no_mis_moroso() {
        $this->data['vista'] = 'r_cartera_no_mis_moroso';
        $this->data['titulo'] = 'Reporte Informe de Morosos';
        $this->activo2('r_cartera_no_mis_moroso');
        $this->data['desplegable'] = $this->general('78', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_no_mis_cesantias() {
        $this->data['vista'] = 'r_cartera_no_mis_cesantias';
        $this->data['titulo'] = 'Reporte Anual Saldo Deuda Para Cesantias Ordinarias';
        $this->activo2('r_cartera_no_mis_cesantias');
        $this->data['desplegable'] = $this->general('83', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

function r_cartera_no_mis_mensual() {
        $this->data['vista'] = 'r_cartera_no_mis_mensual';
        $this->data['titulo'] = 'Reporte Mensual Detallado';
        $this->activo2('r_cartera_no_mis_mensual');
        $this->data['desplegable'] = $this->general('82', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }
    function r_cartera_no_liquidacion() {
        $this->data['vista'] = 'r_cartera_no_liquidacion';
        $this->data['titulo'] = 'Reporte de Cartera no Misional';
        $this->activo2('r_cartera_no_liquidacion');
        $this->data['desplegable'] = $this->general('80', "");
        $this->template->load($this->template_file, 'reporteador/no_misional_liquidacion', $this->data);
    }

    function r_cartera_no_misional_r() {
        $this->data['vista'] = 'r_cartera_no_misional_r';
        $this->data['titulo'] = 'Reporte General de Cartera no Misional';
        $this->activo2('r_cartera_no_misional_r');
        $this->data['desplegable'] = $this->general('79', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_aportante() {
        $this->data['vista'] = 'r_cartera_aportante';
        $this->data['titulo'] = 'Cartera por Aportante';
        $this->activo2('r_cartera_aportante');
        $this->data['desplegable'] = $this->general('59', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_concepto() {
        $this->data['vista'] = 'r_cartera_concepto';
        $this->data['titulo'] = 'Cartera por Concepto';
        $this->activo2('r_cartera_concepto');
        $this->data['desplegable'] = $this->general('60', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_regional() {
        $this->data['vista'] = 'r_cartera_regional';
        $this->data['titulo'] = 'Cartera por Regional';
        $this->activo2('r_cartera_regional');
        $this->data['desplegable'] = $this->general('61', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_abonos() {
        $this->data['vista'] = 'r_cartera_abonos';
        $this->data['titulo'] = 'Abonos de Cartera';
        $this->activo2('r_cartera_abonos');
        $this->data['desplegable'] = $this->general('65', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_instancia() {
        $this->data['vista'] = 'r_cartera_instancia';
        $this->data['titulo'] = 'Cartera por Instancia';
        $this->activo2('r_cartera_instancia');
        $this->data['desplegable'] = $this->general('66', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_dificil_r() {
        $this->data['vista'] = 'r_cartera_dificil_r';
        $this->data['titulo'] = 'Cartera Dificil Recaudo';
        $this->activo2('r_cartera_dificil_r');
        $this->data['desplegable'] = $this->general('69', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_prescrita() {
        $this->data['vista'] = 'r_cartera_prescrita';
        $this->data['titulo'] = 'Cartera en Seguimiento Especial.';
        $this->activo2('r_cartera_prescrita');
        $this->data['desplegable'] = $this->general('70', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_aumento() {
        $this->data['vista'] = 'r_cartera_aumento';
        $this->data['titulo'] = 'Informe de Cartera por Aumento o Disminución';
        $this->activo2('r_cartera_aumento');
        $this->data['desplegable'] = $this->general('75', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function r_cartera_gestion() {
        $this->data['vista'] = 'r_cartera_gestion';
        $this->data['titulo'] = 'Cartera en Proceso a Coactivo';
        $this->activo2('r_cartera_gestion');
        $this->data['desplegable'] = $this->general('77', "");
        $this->template->load($this->template_file, 'reporteador/view', $this->data);
    }

    function periodos($nit) {
        $fachas = array();
        $ano = date('Y');
        $mes = date('n') + 1;
//        $mes = 1;
        $ano = $ano - 5;
        for ($i = 0; $i < 5; $i++) {
            for ($j = $mes; $j < 13; $j++) {
                if ($j <= 9)
                    $fachas[] = $ano . "-0" . $j;
                else
                    $fachas[] = $ano . "-" . $j;
            }
            $mes = 1;
            $ano++;
        }

        $mes = date('n') + 1;
        for ($i = 0; $i < 1; $i++) {
            for ($j = 1; $j < $mes; $j++) {
                if ($j <= 9)
                    $fachas[] = $ano . "-0" . $j;
                else
                    $fachas[] = $ano . "-" . $j;
            }
            $ano++;
        }

        $cantidad = count($fachas);
        $this->data['post'] = $this->input->post();




        $info = $this->Reporteador_model->periodos($this->data, $fachas, $nit);


        $ano = 1;
        $j = -1;
        $mes1 = array();
        for ($i = 0; $i < $cantidad; $i++) {
            if (isset($info[$i])) {
                $explo = explode('-', $info[$i]);
                $mes = $this->modificar_mes($explo[1]);
                $mes = substr($mes, 0, 3);
//                echo $mes."<br>";
                if ($ano != $explo[0]) {
                    $j++;
                    $anos[] = $explo[0];
                    $mes1[$j] = '';
                    $mes1[$j].=$mes . " - ";
                    $ano = $explo[0];
                } else {
                    $mes1[$j].=$mes . " - ";
                }
            }
        }
        $html = '<table border="1">';
        $html.='<tr>'
                . '<td>Vigencia</td>';
        if (isset($anos[0]))
            $html.='<td >' . $anos[0] . '</td>';
        if (isset($anos[1]))
            $html.='<td>' . $anos[1] . '</td>';
        if (isset($anos[2]))
            $html.='<td>' . $anos[2] . '</td>';
        if (isset($anos[3]))
            $html.='<td>' . $anos[3] . '</td>';
        if (isset($anos[4]))
            $html.='<td>' . $anos[4] . '</td>';
        if (isset($anos[5]))
            $html.='<td>' . $anos[5] . '</td>';
        $html.= '</tr>';
        $html.="<tr>"
                . "<td>Meses</td>";
        if (isset($mes1[0]))
            $html.="<td>" . substr($mes1[0], 0, -2) . "</td>";
        if (isset($mes1[1]))
            $html.="<td>" . substr($mes1[1], 0, -2) . "</td>";
        if (isset($mes1[2]))
            $html.="<td>" . substr($mes1[2], 0, -2) . "</td>";
        if (isset($mes1[3]))
            $html.="<td>" . substr($mes1[3], 0, -2) . "</td>";
        if (isset($mes1[4]))
            $html.="<td>" . substr($mes1[4], 0, -2) . "</td>";
        if (isset($mes1[5]))
            $html.="<td>" . substr($mes1[5], 0, -2) . "</td>";
        $html.="</tr>";
        $html.="</table>";
//        die();
//        echo count($info);
//        echo "<pre>";
//        print_r($info);
//        echo "</pre>";
//        die();
        if (count($info) == 0) {
            $html = 0;
        }
        return $html;
    }

    function anadir($num, $style = '') {
        $html = "";
        for ($i = 1; $i < $num; $i++) {
            $html.='<td ' . $style . '>&nbsp;</td>';
        }
        return $html;
    }

    function periodos2($nit) {
        $this->data['post'] = $this->input->post();
        $fachas = array();
        for ($j = 1; $j < 13; $j++) {
            if ($j <= 9)
                $fachas[] = $this->data['post']['ano'] . "-0" . $j;
            else
                $fachas[] = $this->data['post']['ano'] . "-" . $j;
        }


        $info = $this->Reporteador_model->periodos2($this->data, $fachas, $nit);


        $html = "<table><tr><td>";
        $html.= '<table border="1" width="280px">';
        $html.='<tr  align="center"><td>Periodo</td><td>Valor</td></tr>';
        $valor = 0;
        $j = 0;
        for ($i = 0; $i < count($fachas); $i++) {

            if ($i == 6) {
                $html.='<tr><td colspan="2" align="center">Total por Vigencia</td></tr></table></td><td>';
                $html.= '<table border="1" width="280px">';
                $html.='<tr  align="center"><td>Periodo</td><td>Valor</td></tr>';
            }

            if (isset($info[$j]['PERIODO_PAGADO'])) {
                if ($info[$j]['PERIODO_PAGADO'] == $fachas[$i]) {
                    $explo = explode('-', $info[$j]['PERIODO_PAGADO']);
                    $mes = $this->modificar_mes($explo[1]);
                    $valor+=$info[$j]['VALOR_PAGADO'];
                    $html.='<tr><td>' . $mes . '</td><td align="right">' . number_format($info[$j]['VALOR_PAGADO']) . '</td></tr>';
                    $j++;
                } else {
                    $explo = explode('-', $fachas[$i]);
                    $mes = $this->modificar_mes($explo[1]);
                    $valor+=0;
                    $html.='<tr><td>' . $mes . '</td><td align="right">0</td></tr>';
                }
            } else {
                $explo = explode('-', $fachas[$i]);
                $mes = $this->modificar_mes($explo[1]);
                $valor+=0;
                $html.='<tr><td>' . $mes . '</td><td align="right">0</td></tr>';
            }
        }
        $html.='<tr><td colspan="2" align="right">' . number_format($valor) . '</td></tr>';
        $html.='</table></td></tr>';
        $html.='</table>';
        $html2['html'] = $html;
        $html2['valor'] = $valor;
//        die();
        return $html2;
    }

    function periodos3($nit) {
        $this->data['post'] = $this->input->post();
        $fachas = array();
        for ($j = 1; $j < 13; $j++) {
            if ($j <= 9)
                $fachas[] = $this->data['post']['ano'] . "-0" . $j;
            else
                $fachas[] = $this->data['post']['ano'] . "-" . $j;
        }


        $info = $this->Reporteador_model->periodos3($this->data, $fachas, $nit);

        $html = '<table border="1" width="100%">';
        $html.='<tr  align="center">'
                . '<td>Periodo</td>'
                . '<td>Fecha</td>'
                . '<td>Num Obra</td>'
                . '<td>Nombre Obra</td>'
                . '<td>Trab</td>'
                . '<td>No. Pago</td>'
                . '<td>Valor</td>'
                . '</tr>';
        $valor = 0;
        for ($i = 0; $i < count($info); $i++) {
            if (isset($info[$i])) {

                $valor+=$info[$i]['VALOR_PAGADO'];
                $html.='<tr>'
                        . '<td>' . $info[$i]['PERIODO_PAGADO'] . '</td>'
                        . '<td>' . $info[$i]['FECHA_PAGO'] . '</td>'
                        . '<td>No suministrada</td>'
                        . '<td>No suministrada</td>'
                        . '<td></td>'
                        . '<td>' . $info[$i]['COD_PAGO'] . '</td>'
                        . '<td align="right">' . number_format($info[$i]['VALOR_PAGADO']) . '</td>'
                        . '</tr>';
            }
        }
        $html.='<tr><td colspan="4">Total</td><td align="right" colspan="3">' . number_format($valor) . '</td></tr>';
        $html.='</table>';
        $html2['html'] = $html;
        $html2['valor'] = $valor;

        return $html2;
    }

    function modificar_mes($datos) {
        switch ($datos) {
            case '01':
                $informacion2 = "Enero";
                break;
            case '02':
                $informacion2 = "Febrero";
                break;
            case '03':
                $informacion2 = "Marzo";
                break;
            case '04':
                $informacion2 = "Abril";
                break;
            case '05':
                $informacion2 = "Mayo";
                break;
            case '06':
                $informacion2 = "Junio";
                break;
            case '07':
                $informacion2 = "Julio";
                break;
            case '08':
                $informacion2 = "Agosto";
                break;
            case '09':
                $informacion2 = "Septiembre";
                break;
            case '10':
                $informacion2 = "Octubre";
                break;
            case '11':
                $informacion2 = "Noviembre";
                break;
            case '12':
                $informacion2 = "Diciembre";
                break;
        }
        return $informacion2;
    }

    //reporte de ingreso control de cargue por pila y ecolet 
    function r_ingresos_control_cargue() {
//        echo 'prueba';
        $this->data['vista'] = 'r_ingresos_control_cargue';
        $this->data['titulo'] = 'REPORTES DE INGRESOS';
        $this->activo2('r_ingresos_control_cargue');
        $this->data['desplegable'] = $this->general('58', "");
        $this->data['info_bancos'] = $this->Reporteador_model->bancos($this->data);
        $this->template->load($this->template_file, 'reporteador/ingresos', $this->data);
    }

    //reporte de ingreso control de cargue por pila y ecolet 
    function reporteador_ingresos() {
//        echo 'prueba';
        $this->data['vista'] = 'reporteador_ingresos';
        $this->data['titulo'] = 'REPORTES DE INGRESOS';
        $this->activo2('reporteador_ingresos');
        $this->data['desplegable'] = $this->general('76', "");
        $this->data['info_bancos'] = $this->Reporteador_model->bancos($this->data);
        $this->template->load($this->template_file, 'reporteador/reporteador_ingresos', $this->data);
    }

    function doUploadFile() {
        $this->data['post'] = $this->input->get();
        $post = $this->data['post'];

        if (!is_dir(RUTA_INI . ID_USER)) {
            @mkdir(RUTA_INI . ID_USER, 0777);
        }

        $status = '';
        $message = '';
        $background = '';
        $file_element_name = 'userFile';

        if ($status != 'error') {
            $config['upload_path'] = RUTA_INI . ID_USER . '/';
            $config['allowed_types'] = 'txt';
//            $config['allowed_types'] = 'png|jpg|gif|pdf';
            $config['max_size'] = '10000';
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($file_element_name)) {
                return false;
            } else {
                $data = $this->upload->data();
            }
            @unlink($_FILES[$file_element_name]);
        }
//        $file = fopen("1.txt", "r") or exit("Unable to open file!");
        $tabla = "";
        echo $json_encode = json_encode(array('message' => $data['file_name'], 'ruta' => RUTA_INI . ID_USER . '/' . $data['file_name']));
    }

    function salario() {
        $this->data['post'] = $this->input->post();
        $info = $this->Reporteador_model->salario($this->data['post']);
        return $this->output->set_content_type('application/json')->set_output(json_encode($info));
    }

    function reemplazar($infor) {
//        $infor = str_replace('NUMERO_RESOLUCION', 'RESOLUCION', $infor);
        $infor = str_replace('PERIODO_INICIAL', 'FECHA INICIO', $infor);
        $infor = str_replace('PERIODO_FINAL', 'FECHA FIN', $infor);
        $infor = str_replace('CODEMPRESA', 'NIT', $infor);
        $infor = str_replace('NITEMPRESA', 'NIT', $infor);
        $infor = str_replace('ESTADO_VALOR_FINAL', 'VALOR', $infor);
        $infor = str_replace('ESTADO_NRO_ESTADO', 'NUMERO', $infor);
        $infor = str_replace('TOTAL_CAPITAL', 'TOTAL OBLIGACION', $infor);
        $infor = str_replace('ESTADO_ESTADO', 'ESTADO', $infor);
        $infor = str_replace('NOMBREMUNICIPIO', 'CIUDAD', $infor);
        $infor = str_replace('ANO', 'AÑO', $infor);
//        $infor = str_replace('ANO', 'TOTAL ANO', $infor);
        $infor = str_replace('NOMBRE_EMPRESA', 'RAZON SOCIAL', $infor);
        $infor = str_replace('PROYACUPAG_NUMCUOTA', 'NUM CUOTAS', $infor);
        $infor = str_replace('PROYACUPAG_VALORCUOTA', 'VALOR CUOTA', $infor);
        $infor = str_replace('CORREOELECTRONICO', 'CORREO', $infor);
        $infor = str_replace('NRO_ACUERDOPAGO', 'NRO OBLIGACION', $infor);
        $infor = str_replace('NOMBREPROCESO', 'NOMBRE PROCESO', $infor);
        $infor = str_replace('NOMBRETIPODOC', 'TIPO DOC', $infor);
        $infor = str_replace('VLR_CONTRATO_TODOCOSTO', 'VLR CONTRATO', $infor);
        $infor = str_replace('MAÑO', 'MANO', $infor);
        $infor = str_replace('ZON', 'ZÓN', $infor);
        $infor = str_replace('CION', 'CIÓN', $infor);
        $infor = str_replace('CIÓNE', 'CIONE', $infor);
        $infor = str_replace('CIÓNA', 'CIONA', $infor);
        $infor = str_replace('_', ' ', $infor);
        $infor = str_replace('PERIODO', 'PERÍODO', $infor);
        return $infor;
    }

    function reporte_cartera() {
        $this->data['titulo'] = 'Reporte de Cartera';
        $this->data['vista'] = 'reporte_cartera';
        $this->activo2('reporte_cartera');
        $this->template->load($this->template_file, 'reporteador/reporte_cartera', $this->data);
    }

    function guardar_concepto_contable() {
        $info = $this->Reporteador_model->guardar_concepto_contable();
//        $tables = $this->Reporteador_model->info_subconcepto();
//        echo $tables;
    }

    function operdore() {
        return $operador = $this->Reporteador_model->operadore();
    }

    function ciiu() {
        return $operador = $this->Reporteador_model->ciiu();
    }

    function procedencia() {
        return $operador = $this->Reporteador_model->procedencia();
    }

    function generador_certificados() {
        $this->template->load($this->template_file, 'reporteador/generador_certificados', $this->data);
    }

    function mirar_certificados() {
        $this->template->load($this->template_file, 'reporteador/mirar_certificados', $this->data);
    }

    function buscar_certificado() {
        $this->activo2('buscar_certificado');
        $datos = $this->Reporteador_model->buscar_certificado();
        echo $datos;
    }

    private function directorios() {
        $errores = 0;
        $paths = array();
        $paths[] = "uploads/reportes/ugpp";
        foreach ($paths as $path) {
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, TRUE)) {
                    $errores++;
                }
            }
        }
        if ($errores > 0)
            return false;
        else
            return true;
    }

    function burcar_no_misional() {
        $info = $this->Reporteador_model->burcar_no_misional_t_cartera();
        return $this->output->set_content_type('application/json')->set_output(json_encode($info));
    }

    function burcar_no_misional2($dato = null) {
        return $info = $this->Reporteador_model->burcar_no_misional_t_cartera2($dato);
//        return $this->output->set_content_type('application/json')->set_output(json_encode($info));
    }

    function burcar_no_misional_deuda() {
        $info = $this->Reporteador_model->burcar_no_misional_deuda();
        return $this->output->set_content_type('application/json')->set_output(json_encode($info));
    }
    
	function buscar_tipo_cert_nm() {
        $info = $this->Reporteador_model->buscar_tipo_cert_nm();
        return $this->output->set_content_type('application/json')->set_output(json_encode($info));
    }

    function burcar_no_misional_deuda2($concepto2 = null, $idusuario = null) {
        return $info = $this->Reporteador_model->burcar_no_misional_deuda2($concepto2, $idusuario);
//        return $this->output->set_content_type('application/json')->set_output(json_encode($info));
    }

    function deleteDir($path, $tipo = 0) {
        if (!is_dir($path)) {
            throw new InvalidArgumentException("$path is not a directory");
        }
        if (substr($path, strlen($path) - 1, 1) != '/') {
            $path .= '/';
        }
        $dotfiles = glob($path . '.*', GLOB_MARK);
        $files = glob($path . '*', GLOB_MARK);
        $files = array_merge($files, $dotfiles);
        foreach ($files as $file) {
            if (basename($file) == '.' || basename($file) == '..') {
                continue;
            } else if (is_dir($file)) {
                self::deleteDir($file, '1');
            } else {
                unlink($file);
            }
        }
        if ($tipo == 1) {
            rmdir($path);
        }
    }

}
