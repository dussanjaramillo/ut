<?php

/**
 * Archivo para la administración de los metodos necesarios para generar acercamiento persuasivo en la Dirección Jurídica
 *
 * @packageCartera
 * @subpackage Controllers
 * @author vferreira
 * @location./application/controllers/acercamientopersuasivo.php

 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Acercamientopersuasivo extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation', 'tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template', 'traza_fecha'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('acercamientopersuasivo_model');
        $this->load->model('numeros_letras_model');
        $this->load->model('plantillas_model');
       // $this->load->file(APPPATH . "application/config/database.php", true);

        $this->load->library('tcpdf/tcpdf.php', 'libupload');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen',
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/tinymce/plugins/moxiemanager/plugin.min.js',
            'js/tinymce/tinymce.js',
            'js/ajaxfileupload.js',
        );
        define("PLANTILLA_CARTA_COACTIVO", "123");
        define("TIPO_EXPEDIENTE", "10"); //
        define("RUTA_REQ_GEN", "uploads/AcercamientoPersuasivo/Req_Generado/");
        define("RUTA_PJ_GEN", "uploads/AcercamientoPersuasivo/PJGenerado/");
        define("RUTA_REQ_FIRMADO", "uploads/AcercamientoPersuasivo/C/");
        define("RUTA_PJ_FIRMADO", "uploads/AcercamientoPersuasivo/PJFirmado/");
        //permisos
        define("PROCESO_ACUERDO_JURIDICO", "18");
        define("PROCESO_ACERCAMIENTO_PERSUASIVO", "9");
        define("ABOGADO", "43");
        define("SECRETARIO", "41");
        define("COORDINADOR", "42");
        define("TIPO_RESPUESTA_TITULO", "184");
        define("RUTAINI", "./uploads/AcercamientoPersuasivo");
        define("RUTA_DES", "uploads/AcercamientoPersuasivo/COD_");
        define("DOCUMENTO_REQUERIMIENTO", "1");
        define("NOTIFICACION_PERSONAL", "2");
        define("PROCESO_JUDICIAL", "3");
        define("DOCUMENTO_REQUERIMIENTO_APROBADO", "1091");
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
        define("CONCURRENCIA_DEUDOR", "197"); //Tipo de respuesta:Deudor acepta las obligaciones
        define("PENDIENTE_PAGO", "199"); //Tipo de respuesta:Pendiente de pago
        define("MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO", "204");
        define("REQUERIMIENTO_ACERCAMIENTO_DEVUELTO", "187");
        $this->data['user'] = $this->ion_auth->user()->row();
        if ($this->data['user']) {
            define("ID_USUARIO", $this->data['user']->IDUSUARIO);
            define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);
        }

        $this->data['message'] = $this->session->flashdata('message');

        $sesion = $this->session->userdata;
        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);

        $permiso_sec = FALSE;
        $permiso_cor = FALSE;
        if (ID_USUARIO == ID_SECRETARIO):
            $permiso_sec = TRUE;
        endif;
        if (ID_USUARIO == ID_COORDINADOR):
            $permiso_cor = TRUE;
        endif;
        $this->data['bandeja'] = FALSE;

        $this->ion = new Ion_auth_model();
        $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
    }

    /* Función que permite visualizar los procesos para gestión del abogado */

    function index() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['titulo'] = 'Acercamiento Persuasivo';
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['nom_usuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
                $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, NULL, $cod_coactivo);
                $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);
            }
        }
    }

    /*Metodo que permite visualizar los procesos del abogado*/
    function abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {

                $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
                $this->data['titulo'] = 'Gestion Requerimientos Abogado';
                $this->data['nom_usuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['cod_coactivo'] = $cod_coactivo;
                if ($this->input->post('cod_coactivo')):
                    $proceso = $this->input->post('cod_coactivo');
                    $respuesta = $this->input->post('cod_respuesta');
                    $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO,NULL, $cod_coactivo);
                    $cod_cobro = $this->data['consulta'][0]['COD_COBRO'];

                    //echo  $this->data['titulo'];die();
                    $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);
                else:
                    $this->data['user'] = $this->ion_auth->user()->row();

                    $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, NULL,$cod_coactivo);
                    $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);
                endif;
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Metodo que permite visualizar los procesos para gestión del secretario */

    function secretario() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
                $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['titulo'] = 'Gestion Requerimientos Secretario';
                $this->data['nom_usuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
                if ($this->input->post('cod_coactivo')):
                    $proceso = $this->input->post('cod_coactivo');
                    $respuesta = $this->input->post('cod_respuesta');
                    $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
                    $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, $proceso);
                    $cod_cobro = $this->data['consulta'][0]['COD_COBRO'];
                    $this->data['bandeja'] = TRUE;
                    $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);
                else:
                    $this->data['user'] = $this->ion_auth->user()->row();
                    $this->data['titulo'] = 'Gestion Secretario';
                    $this->data['nom_usuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
                    $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, NULL);
                    $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);

                endif;
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Metodo que permite visualizar los procesos para gestión del coordinador */

    function coordinador() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
                $this->data['titulo'] = 'Gestion Requerimientos Funcionario Ejecutor';
                $this->data['nom_usuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
                $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['cod_coactivo'] = $cod_coactivo;
                if ($this->input->post('cod_coactivo')):
                    $proceso = $this->input->post('cod_coactivo');
                    $respuesta = $this->input->post('cod_respuesta');
                    $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, $proceso);
                    $cod_cobro = $this->data['consulta'][0]['COD_COBRO'];
                    $this->data['bandeja'] = TRUE;
                    $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);
                else:
                    $this->data['titulo'] = 'Gestion Funcionario Ejecutor';
                    $this->data['user'] = $this->ion_auth->user()->row();
                    $this->data['consulta'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, NULL);
                    $this->data['nom_usuario'] = $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS;
                    $this->template->load($this->template_file, 'acercamientopersuasivo/procesos', $this->data);
                endif;
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function leer_plantilla($cod_plantilla) {
         $this->data['informacion'] = $this->plantillas_model->plantillas($cod_plantilla);
        $urlplantilla2 = "uploads/plantillas/" . $this->data['informacion'][0]['ARCHIVO_PLANTILLA'];
        $arreglo = array();
        /*
         * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
         */
        if (file_exists($urlplantilla2)) {
            $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
        } else {
            $this->data['filas2'] = '';
        }
        return $this->data['filas2'];
    }

    function vistas() {
   if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
        $this->data['post'] = $this->input->post();
        $respuesta = $this->data['post']['cod_respuesta'];
        $proceso = $this->data['post']['cod_proceso'];
        $cod_persuasivo = $this->data['post']['cod_cobro'];
        $this->data['url_generar_pdf'] = base_url() . 'index.php/acercamientopersuasivo/pdf';
        $this->data['cabecera'] = $this->acercamientopersuasivo_model->cabecera($respuesta, $proceso);
        $ruta = $this->data['post']['ruta'];
        if ($ruta):
             $this->data['documento'] = $this->leer_fichero_completo($ruta);
            $this->data['documento'] = $this->leer_plantilla(PLANTILLA_CARTA_COACTIVO);
       
        else:
            $this->data['documento'] = $this->leer_plantilla(PLANTILLA_CARTA_COACTIVO);

        endif;
        switch ($respuesta):
            case 184:/* Generar Documento de Acercamiento */
            case 187:/* Corregir Documento de Acercamiento */
                $this->data['url'] = 'acercamientopersuasivo/guardar';
                $this->data['rta_siguiente'] = 186;
                $ruta = $this->data['post']['ruta'];
                if ($respuesta == 187):/* Consulta el contenido del documento que se debe corregir */
                    $this->data['traza'] = $this->acercamientopersuasivo_model->observaciones($cod_persuasivo, DOCUMENTO_REQUERIMIENTO);
                endif;
                if ($ruta):
                     $this->data['documento'] = $this->leer_fichero_completo($ruta);

                else:
                    $this->data['documento'] = $this->leer_plantilla(PLANTILLA_CARTA_COACTIVO);
                endif;
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->load->view('acercamientopersuasivo/genera_documento', $this->data);
                break;
            case 186:
                $this->data['url'] = 'acercamientopersuasivo/guardar';
                $this->data['cod_aprobacion'] = 188;
                $this->data['cod_devolucion'] = 187;
                $this->data['traza'] = $this->acercamientopersuasivo_model->observaciones($cod_persuasivo, DOCUMENTO_REQUERIMIENTO);
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->load->view('acercamientopersuasivo/revisar_documento', $this->data);
                break;
            case 188:
                $this->data['url'] = 'acercamientopersuasivo/guardar';
                $this->data['cod_aprobacion'] = 1091;
                $this->data['cod_devolucion'] = 187;
                $this->data['traza'] = $this->acercamientopersuasivo_model->observaciones($cod_persuasivo, DOCUMENTO_REQUERIMIENTO);
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->load->view('acercamientopersuasivo/revisar_documento', $this->data);
                break;
            case 1091: /* Documento Aprobado */
                $this->data['url'] = base_url() . 'index.php/acercamientopersuasivo/sube_documento';
                $this->data['rta_siguiente'] = 189; /* Requerimiento Acercamiento Persuasivo Aprobado y Firmado */
                $this->data['traza'] = $this->acercamientopersuasivo_model->observaciones($cod_persuasivo, DOCUMENTO_REQUERIMIENTO);
                $ruta = $this->data['post']['ruta'];
                if ($ruta):
                    $this->data['documento'] = $this->leer_fichero_completo($ruta);
                else:
                    $this->data['documento'] =$this->leer_plantilla(PLANTILLA_CARTA_COACTIVO);
                endif;
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->load->view('acercamientopersuasivo/adjuntar_documento', $this->data);
                break;

            case 190:
                $this->data['url'] = base_url('index.php/acercamientopersuasivo/correccion_documento');
                $this->data['causales'] = $this->acercamientopersuasivo_model->causalesdevolucion();
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->data['proceso'] = $this->acercamientopersuasivo_model->consulta_procesos(COD_REGIONAL, ID_USUARIO, $proceso);
                $time = time();
                $fecha_envio = $this->data['proceso'][0]['FECHA_ENVIO'];
                $fecha_actual = date("d-m-Y");
                $datetime1 = date_create($fecha_envio);
                $datetime2 = date_create($fecha_actual);
                $interval = date_diff($datetime1, $datetime2);
                $this->data['dias'] = $interval->format('%a');
                $this->load->view('acercamientopersuasivo/documento_enviado', $this->data);
                break;
            case 191://Requerimiento Enviado
                $this->data['url'] = base_url('index.php/acercamientopersuasivo/guarda_notificacion_recibida');
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->load->view('acercamientopersuasivo/requerimiento_recibido', $this->data);
                
                
                break;
            case 197://Concurrencia deudor

                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->data['url1'] = base_url('index.php/acercamientopersuasivo/acuerdo');
                $this->data['url2'] = base_url('index.php/acercamientopersuasivo/guarda_no_aceptarobligaciones');
                $this->data['url3'] = base_url('index.php/acercamientopersuasivo/guarda_aceptarobligaciones');
                // $this->template->load($this->template_file, 'acercamientopersuasivo/concurrencia_deudor', $this->data);
                $this->load->view('acercamientopersuasivo/concurrencia_deudor', $this->data);
                break;
            case 199:/* Verificación de pagos */
                $this->load->view('acercamientopersuasivo/cabecera', $this->data);
                $this->load->view('acercamientopersuasivo/verificacion_pago', $this->data);
        endswitch;
            }
        else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
   

    function guarda_no_aceptarobligaciones() {// Cuando  el deudor no acepta obligaciones se envia a Medidas Cautelares y Mandamiento de Pago
        $post = $this->input->post();
        $detalle = unserialize($this->input->post('detalle'));
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $post['cod_proceso'] = $detalle['cod_proceso'];
        $post['comentarios'] = 'Deudor no acepta obligaciones, se remite a Medidas Cautelares y Mandamiento de Pago';
        $post['tipo_gestion'] = 107;
        $post['tipo_respuesta'] = MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO;
        $post['obligacion_aceptada'] = 'N';

//        $post['idgestioncobro'] = trazar($this->datos['tipo_gestion'], $this->datos['tipo_respuesta'], $this->datos['cod_fiscalizacion'], $this->datos['nit_empresa'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $this->datos['comentarios']);
//        $post['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
        $post['idgestion'] = $this->traza($post);
        $resultado = $this->acercamientopersuasivo_model->aceptar_obligaciones($post);
        if ($resultado):
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha actualizado la información.</div>');
        else:
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error.</div>');
        endif;
    }

    function guarda_aceptarobligaciones() {
        $post = $this->input->post();
        $detalle = unserialize($this->input->post('detalle'));
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $post['cod_proceso'] = $detalle['cod_proceso'];
        $cabecera = unserialize($this->input->post('cabecera'));
        $post['nit'] = $cabecera['IDENTIFICACION'];
        $post['cod_regional'] = $cabecera['COD_REGIONAL'];
        $post['cod_concepto'] = $cabecera['COD_CPTO_FISCALIZACION'];
        $accion = $post['tipopago'];
        switch ($accion) :
            case '1'://Pago Total (Se debio haber generado la liquidación, y el proceso cambia a Pendiente de Pago)  
                $post['estado_proceso'] = 17; //estado pendiente de pago
                $post['comentarios'] = ' Deudor va a realizar Pago Total';
                $post['tipo_gestion'] = 106;
                $post['tipo_respuesta'] = PENDIENTE_PAGO;
                $post['obligacion_aceptada'] = 'S';
                break;
            case '2'://Pago Parcial (Se debio haber generado la liquidación, y el proceso cambia a Pendiente de Pago)   
                $post['estado_proceso'] = 17; //estado pendiente de pago
                $post['comentarios'] = 'Deudor va a realizar Pago Parcial';
                $post['tipo_gestion'] = 106;
                $post['tipo_respuesta'] = PENDIENTE_PAGO;
                $post['obligacion_aceptada'] = 'S';
                break;
            case '3'://Acuerdo de Pago (Se debio haber generado la liquidación, y el proceso cambia a Pendiente de Pago)   
                $post['estado_proceso'] = 19; //estado pendiente de pago
                $post['comentarios'] = 'Se solicita generar Acuerdo de Pago';
                $post['tipo_gestion'] = 106;
                $post['tipo_respuesta'] = 201;
                $post['obligacion_aceptada'] = 'S';
                $titulos = $this->acercamientopersuasivo_model->titulos_coactivo( $post['cod_proceso']);
                $titulos_f = array();
                $a = 0;
                foreach ($titulos as $titulo):
                    foreach ($titulo as $dato):
                        $titulos_facilidad[$a] = $dato;
                        $a++;
                    endforeach;
                endforeach;
            
                break;
            case '4'://No Pago (remite a medidas cautelares y mandamiento de pago)
                $post['estado_proceso'] = 21; //estado pendiente de pago
                $post['comentarios'] = 'Deudor no va a realizar pago, se remite a Medidas Cautelares y Mandamiento de Pago';
                $post['tipo_gestion'] = 107;
                $post['tipo_respuesta'] = MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO;
                $post['obligacion_aceptada'] = 'N';
                break;
        endswitch;

        $this->datos['idgestion'] = $this->traza($post);
        $resultado = $this->acercamientopersuasivo_model->aceptar_obligaciones($post,$titulos_facilidad);
        if ($resultado) :
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha actualizado la información.</div>');
        else :
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error.</div>');
        endif;
    }

    function guarda_notificacion_recibida() {
        $this->data['message'] = $this->session->flashdata('message');
        $post = $this->input->post();
        $detalle = unserialize($this->input->post('detalle'));
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $post['cod_proceso'] = $detalle['cod_proceso'];
        $post['comentarios'] = 'Sin Respuesta';
        $this->load->library('form_validation');
        $post['cod_usuario'] = $this->ion_auth->user()->row()->IDUSUARIO;
        $this->form_validation->set_rules('respuesta', 'Tipo de Respuesta', 'required');
        if ($this->form_validation->run() == false) {
            echo '<div class="alert danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>';
        } else {
            if ($post['respuesta'] == 3) {//Cuando es sin respuesta envia
                $post['tipo_gestion'] = 107;
                $post['tipo_respuesta'] = MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO;
                $post['fecha_recibida'] = '';
                $this->datos['idgestion'] = $this->traza($post);
                $resultado = $this->acercamientopersuasivo_model->guarda_respuesta_notificacion($post);
                if ($resultado):
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha realizado la actualización de la respuesta</div>');
                else :
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No Se ha realizado la actualización de la respuesta</div>');
                endif;
            } else {

                $this->form_validation->set_rules('fecha_recibida', 'Fecha Recibida', 'required');
                $this->form_validation->set_rules('respuesta2', 'Procesos Concursales y/o Liquidatorios', 'required');
                if ($this->form_validation->run() == false) {//valida los campos
                    echo '<div class="alert alert-danger"><button >&times;</button>' . validation_errors() . '</div>';
                } else {//si los campos no estan vacios actualiza ::
                    if ($post['respuesta2'] == 1) {//esta en reorganizacion envia a procesos judiciales
                        $post['reorganizacion_empresarial'] = 'S';
                        $post['tipo_gestion'] = 104;
                        $post['tipo_respuesta'] = 193;
                    } else if ($post['respuesta2'] == 2) {//No esta en reorganizacion cambia a acepta obligaciones
                        $post['reorganizacion_empresarial'] = 'N';
                        $post['tipo_gestion'] = 105;
                        $post['tipo_respuesta'] = 197;
                    }
//                    $this->datos['idgestioncobro'] = trazar($this->datos['tipo_gestion'], $this->datos['tipo_respuesta'], $this->datos['cod_fiscalizacion'], $this->datos['nit_empresa'], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = 'traza');
                    $post['idgestion'] = $this->traza($post);
                    $resultado = $this->acercamientopersuasivo_model->guarda_respuesta_notificacion($post);
                    if ($resultado) :
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha realizado la actualización de la respuesta</div>');
                    else :
                        $this->session->set_flashdata('message', '<divclass="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No Se ha realizado la actualización de la respuesta</div>');
                    endif;
                }
            }
        }
    }

    function guarda_verificacionpagos() {
        $this->datos = $this->input->post();
        $post = $this->input->post();
//        echo "<pre>";
//        print_r($post);
//        echo "</pre>";die();
        $detalle = unserialize($this->input->post('detalle'));
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $post['cod_proceso'] = $detalle['cod_proceso'];
        $post['cod_usuario'] = $this->ion_auth->user()->row()->IDUSUARIO;

        $this->form_validation->set_rules('tipopago', 'Tipo de Pago', 'required');
        if ($this->form_validation->run() == false) {//valida los campos
            echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>';
        } else {
            if ($post['tipopago'] == 1) {//pagototal cambia a total pagado 18
                $post['tipo_gestion'] = 106;
                $post['tipo_respuesta'] = 200;
            } else if ($post['tipopago'] == 2) {//pago parcial vuelve a iniciar el proceso
                $post['tipo_gestion'] = 105;
                $post['tipo_respuesta'] = 197;
            } else if ($post['tipopago'] == 3) {
                $post['comentarios'] = 'Acuerdo Pago';
                $post['tipo_gestion'] = 106;
                $post['tipo_respuesta'] = 201;
            } else
            if ($post['tipopago'] == 4) {//no pago medidas cautelares y mandamiento pago
                $post['comentarios'] = 'Deudor no va a realizar pago, se remite a Medidas Cautelares y Mandamiento de Pago';
                $post['tipo_gestion'] = 107;
                $post['tipo_respuesta'] = MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO;
                $post['estado_proceso'] = 21;
            }


            $resultado = $this->acercamientopersuasivo_model->verificacion_pagos($post);
            if ($resultado) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha actualizado la información.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error.</div>');
            }
        }
    }

    // imprime 'Esto es una cadena, y algo más.'


    function sube_documento() {
        $post = array();
        $post = $this->input->post();
        $idcobro = $post['cod_cobro'];
        $ruta_final = 'AcercamientoPersuasivo/COD_' . $post['cod_cobro'];
        $ruta_f = RUTA_DES . $post['cod_cobro'];
        $this->load->library('libupload');
        if (!file_exists($ruta_f)) {
            $this->crea_ruta($ruta_f);
        }
        // print_r($post);
        for ($i = 0; $i < count($_FILES); $i++) {
            if (!empty($_FILES['archivo' . $i]['name'])) {
                $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $ruta_final, 'pdf|jpg|jpeg|doc|txt', 9999, 9999, 0);
            }
        }
        $ruta_documento = RUTA_DES . $post['cod_cobro'] . "/" . $respuesta[0]['data']['orig_name'];
        $post['ruta'] = 'AcercamientoPersuasivo/COD_' . $post['cod_cobro'] . "/";
        $post['nombre'] = $respuesta[0]['data']['orig_name'];
        $post['nombre_archivo'] . ".pdf";
        $post['ruta_formulario'] = base_url() . 'index.php/acercamientopersuasivo/borrar_archivo';
        $post['tipo_documento'] = DOCUMENTO_REQUERIMIENTO;
        $this->data['post'] = $post;
        if (file_exists($ruta_documento)) {
            $this->data['post']['ruta_doc'] = $ruta_documento;
            $this->data['post']['ruta_form'] = 'index.php/acercamientopersuasivo/guardar_documento';
            $this->load->view('acercamientopersuasivo/documento_subido', $this->data);
        }
        $consulta = $this->acercamientopersuasivo_model->cobropersuasivo($idcobro);
    }

    /* Función que guarda la información del documento de requerimiento adjunto */

    function guardar_documento() {
        $this->data['message'] = $this->session->flashdata('message');
        $post = $this->input->post();
        $detalle = unserialize($this->input->post('detalle'));
        $post['comentarios'] = 'Requerimiento Acercamiento Aprobado y Firmado';
        $post['tipo_respuesta'] = 190;
        $post['tipo_gestion'] = 103;
        $post['tipo_exp'] = TIPO_EXPEDIENTE;
        $post['sub_proceso'] = 'Requerimeinto Acercamiento';
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $post['ruta'] = $detalle['ruta'];
        $post['cod_usuario'] = $this->ion_auth->user()->row()->IDUSUARIO;
        $post['tipo_documento'] = $detalle['tipo_documento'];
        $post['cod_proceso'] = $detalle['cod_proceso'];
        $post['idgestion'] = $this->traza($post);
        $post['fecha_radicado'] = $post['fecha_radicado'];
        $this->resultado = $this->acercamientopersuasivo_model->aprueba_gestion($post); //Realiza el update en la tabla de cobropersuasivo
        $expediente = $this->guarda_expediente($post);
        $mensaje = 'Se adjuntó el Requerimiento de Acercamiento Aprobado y Firmado';
        //Guardo el documento en expediente

        if ($this->resultado) :
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $mensaje . '</div>');
        else:
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error.</div>');
        endif;
    }

    function adjuntar_documento() {
        $ruta_final = 'AcercamientoPersuasivo/COD_' . $post['cod_cobro'];
        $ruta_f = RUTA_DES . $post['cod_cobro'];
        $this->load->library('libupload');
        if (!file_exists($ruta_f)) {
            $this->crea_ruta($ruta_f);
        }
        for ($i = 0; $i < count($_FILES); $i++) {
            if (!empty($_FILES['archivo' . $i]['name'])) {
                $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $ruta_final, 'pdf|jpg|jpeg', 9999, 9999, 0);
            }
        }
        return $respuesta;
    }

    function crea_ruta($ruta) {
        echo $ruta;
        if (!file_exists($ruta)) :
            if (!file_exists(RUTAINI)):
                mkdir(RUTAINI, 0777, true);
            endif;
            mkdir($ruta, 0777, true);
        endif;
        return $ruta;
    }

    function respuesta_notificacion() {
        $this->data['message'] = $this->session->flashdata('message');
        $post = $this->input->post();
        $post['cod_usuario'] = ID_USUARIO;
        //verifico el tipo de respuesta 
        if ($post['acciones'] == 'Notificacion Recibida') ://cambia estado a Notificaciï¿½n de Acercamiento Persuasivo Recibida
            $detalle = unserialize($post['detalle']);
            $post['hora_recepcion'] = $post['hora'] . ":" . $post['minutos'];
            $post['comentarios'] = 'Requerimiento Acercamiento Persuasivo Recibido';
            $post['tipo_gestion'] = 103;
            $post['tipo_respuesta'] = 191;
        elseif ($post['acciones'] == 'Notificacion Devuelta') ://envia a Medidas Cautelares y Mandamiento de Pago
            $detalle = unserialize($post['detalle2']);
            $post['comentarios'] = 'Requerimiento Acercamiento Persuasivo Devuelto, se remite a Medidas Cautelares y Mandamiento de Pago';
            $post['tipo_gestion'] = 107;
            $post['tipo_respuesta'] = MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO;
            $post['hora_recepcion'] = $post['hora_dev'] . ":" . $post['minutos_dev'];
        endif;
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $post['cod_proceso'] = $detalle['cod_proceso'];
        $post['idgestion'] = $this->traza($post);
        $resultado = $this->acercamientopersuasivo_model->requerimiento_acercamiento($post);
        if ($resultado) :
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha realizado la actualización de la información</div>');
        else :
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No se ha realizado la actualización de la información</div>');
        endif;
    }

    function guarda_temporal() {//Cuando el requerimiento es devuelto se puede corregir para ser enviado nuevamente
        $post = $this->input->post();
        $post['cod_usuario'] = ID_USUARIO;
        $archivo = $post['nombre_archivo'];  // el nombre de tu archivo
        $ruta = RUTA_DES . $post['cod_cobro'] . "/" . $archivo . ".txt"; //ruta donde guardaremos el archivo
        $post['ruta'] = $ruta;
        $genera_fichero = $this->generar_fichero($ruta, $post['descripcion'], $post);
        $post['nombre_archivo'] = $post['nombre_archivo'] . ".txt";
        $post['tipo_documento'] = DOCUMENTO_REQUERIMIENTO;
        $post['tipo_respuesta'] = 189;

        $resultado = $this->acercamientopersuasivo_model->correccion_documento($post);
        if ($resultado) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado el texto del documento</div>');
        }
        //actualizamos la ruta 
    }

    function correccion_documento() {
        $post = $this->input->post();
        $nombre = $this->sube_documento();
        $ruta_final = 'uploads/AcercamientoPersuasivo/COD_' . $post['cod_cobro'] . "/" . $nombre;
        $post['tipo_respuesta'] = 190;
        $post = $ruta_final;
        $post['tipo_gestion'] = 103;
        $post['estado_proceso'] = 5;
        $post['cod_usuario'] = IDUSUARIO;
        $this->resultado = $this->acercamientopersuasivo_model->guarda_correccion($this->datos);
        if ($this->resultado) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha corregido el requerimiento</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha presentado un error</div>');
        }
    }

    function guardar() {
        $this->data['post'] = $this->input->post();
        $rta_siguiente = $this->data['post']['rta_siguiente'];
        $respuesta = $this->data['post']['respuesta'];
        $this->data['post']['cod_usuario'] = $this->ion_auth->user()->row()->IDUSUARIO;
        $this->data['post']['tipo_documento'] = DOCUMENTO_REQUERIMIENTO;
        $archivo = $this->data['post']['nombre_archivo']; // el nombre de tu archivo
        $ruta = RUTA_DES . $this->data['post']['cod_cobro'] . "/" . $archivo . ".txt"; //ruta donde guardaremos el archivo
        $this->data['post']['ruta'] = $ruta;
        switch ($respuesta):
            case 184:/* Generado */
                $mensaje = 'Requerimiento de acercamiento generado exitosamente';
            case 187:/* Corregido */
                $mensaje = 'Se ha corregido el requerimiento';
                $genera_fichero = $this->generar_fichero($ruta, $this->data['post']['descripcion'], $this->data['post']);
                $this->data['post']['tipo_gestion'] = 102;
                $this->data['post']['tipo_respuesta'] = $rta_siguiente;
                $post['idgestion'] = $this->traza($post);
                $resultado = $this->acercamientopersuasivo_model->guarda_requerimieto($this->data['post']);
                if ($resultado) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="info">&times;</button>' . $mensaje . '</div>');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Error!</div>');
                }

                break;
            case 186:/* Revisado */
            case 188:/* Aprobado */
                $this->data['post']['comentarios'] = $this->input->post('observaciones');
                $genera_fichero = $this->generar_fichero($ruta, $this->data['post']['descripcion'], $this->data['post']);
                if ($rta_siguiente == 188) :
                    $this->data['post']['acciones'] = 'Requerimiento Acercamiento Persuasivo Pre-aprobado';
                    $this->data['post']['tipo_gestion'] = 102;
                    $this->data['post']['tipo_respuesta'] = $rta_siguiente;
                    $mensaje = 'Se ha revisado el Requerimiento de Acercamiento';
                elseif ($rta_siguiente == 187) :
                    $this->data['post']['tipo_gestion'] = 102;
                    $this->data['post']['tipo_respuesta'] = $rta_siguiente;
                    $this->data['post']['acciones'] = 'Requerimiento Acercamiento Persuasivo Devuelto';
                    $mensaje = 'Se devuelve el requerimiento para ser corregido';
                elseif ($rta_siguiente == 1091) :
                    $this->data['post']['tipo_gestion'] = 102;
                    $this->data['post']['tipo_respuesta'] = $rta_siguiente;
                    $this->data['post']['acciones'] = 'Requerimiento Acercamiento Persuasivo Aprobado';
                    $mensaje = 'Se Aprueba el requerimiento exitosamente';
                endif;
                $resultado = $this->acercamientopersuasivo_model->revisa_gestion($this->data['post']);
                // $post['idgestion'] = $this->traza($this->data['post']);
                if ($resultado):
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $mensaje . '</div>');
                else :
                    $this->session->set_flashdata('message', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Error!Por favor Revise la información Enviada</div>');
                endif;
                break;
        endswitch;
    }

    function guarda_notif_recibida() {//notificacion_recibida
        $this->datos = $this->input->post();
        $post['cod_cobro'] = $detalle['cod_cobro'];
        $this->load->library('form_validation');
        $this->datos['cod_usuario'] = $this->ion_auth->user()->row()->IDUSUARIO;
        $this->form_validation->set_rules('respuesta', 'Tipo de Respuesta', 'required');
        if ($this->form_validation->run() == false) {
            echo '<div class="alert danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>';
        } else {
            if ($this->datos['respuesta'] == 3) {//Cuando es sin respuesta envia
                $this->datos['tipo_gestion'] = 107;
                $this->datos['tipo_respuesta'] = MEDIDAS_CAUTELARES_MANDAMIENTO_PAGO;
                $this->datos['fecha_recibida'] = '';
                $post['idgestion'] = $this->traza($post);
                $resultado = $this->acercamientopersuasivo_model->guarda_respuesta_notificacion($this->datos);
                if ($resultado) {
                    echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha realizado la actualización de la respuesta</div>';
                } else {
                    echo '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No Se ha realizado la actualización de la respuesta</div>';
                    echo '<script>' . "setTimeout('finalizar()', 5000);" . '</script>';
                }
            } else {

                $this->form_validation->set_rules('fecha_recibida', 'Fecha Recibida', 'required');
                $this->form_validation->set_rules('respuesta2', 'Procesos Concursales y/o Liquidatorios', 'required');
                if ($this->form_validation->run() == false) {//valida los campos
                    echo '<div class="alert alert-danger"><button >&times;</button>' . validation_errors() . '</div>';
                } else {//si los campos no estan vacios actualiza ::
                    if ($this->datos['respuesta2'] == 1) {//esta en reorganizacion envia a procesos judiciales
                        $this->datos['reorganizacion_empresarial'] = 'S';
                        $this->datos['tipo_gestion'] = 104;
                        $this->datos['tipo_respuesta'] = 18;
                        $this->datos['estado_proceso'] = 11;
                    } else if ($this->datos['respuesta2'] == 2) {//No esta en reorganizacion cambia a acepta obligaciones
                        $this->datos['reorganizacion_empresarial'] = 'N';
                        $this->datos['tipo_gestion'] = 105;
                        $this->datos['tipo_respuesta'] = 197;
                        $this->datos['estado_proceso'] = 15;
                    }
                    $this->datos['idgestion'] = $this->traza($post);
                    $resultado = $this->acercamientopersuasivo_model->guarda_respuesta_notificacion($this->datos);
                    if ($resultado) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha realizado la actualización de la respuesta</div>');
                        // echo '<script>' . "setTimeout('finalizar()', 5000);" . '</script>';
                    } else {
                        $this->session->set_flashdata('message', '<divclass="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No Se ha realizado la actualización de la respuesta</div>');
                        echo '<script>' . "setTimeout('finalizar()', 5000);" . '</script>';
                    }
                }
            }
        }
    }

    function pdf() {

        $html = utf8_encode(base64_decode($this->input->post('descripcion_pdf')));
        $nombre_pdf = $this->input->post('nombre_archivo');
        //$titulo = $this->input->post('titulo');
        //$tipo = $this->input->post('tipo');
        $titulo = '';
        $tipo = 3;
        $data[0] = $tipo;
        $data[1] = $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    private function generar_fichero($ruta, $descripcion, $datos) {
        if (!file_exists('./uploads/AcercamientoPersuasivo')) {
            if (!mkdir('./uploads/AcercamientoPersuasivo', 0777, true)) {
                
            } else {
                if (!mkdir('uploads/AcercamientoPersuasivo/COD_' . $datos['cod_cobro'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists('uploads/AcercamientoPersuasivo/COD_' . $datos['cod_cobro'])) {
                if (!mkdir('uploads/AcercamientoPersuasivo/COD_' . $datos['cod_cobro'], 0777, true)) {
                    
                }
            }
        }
        $crea = fopen($ruta, "w"); // abrimos el archivo como escritura
        /* como ves ahora le envio $ruta en ves de archivo que incluye la ruta completa y el archivo */
        fwrite($crea, $descripcion); // guardamos la descripcion
        fclose($crea); // Cerramos el archivo 
        if (file_exists($ruta)) {
            return $ruta;
        } else {
            return false;
        }
    }

    private function leer_fichero_completo($nombre_fichero) {
        if (file_exists($nombre_fichero)) {
            //abrimos el archivo de texto y obtenemos el identificador
            $fichero_texto = fopen($nombre_fichero, "r");
            $contenido_fichero = fread($fichero_texto, filesize($nombre_fichero));
            return $contenido_fichero;
        } else {
            return false;
        }
    }

    function borrar_archivo() {
        $this->load->library('libupload');
        $this->datos = $this->input->post();
        $dir = $this->datos['ruta'];
        $file = $this->datos['documento'];
        $this->borrar($dir, $file, NULL);
    }

    function traza($post) {
        $tipogestion = $post['tipo_gestion'];
        $tiporespuesta = $post['tipo_respuesta'];
        $codtitulo = '';
        $codjuridico = '';
        $codcarteranomisional = '';
        $coddevolucion = '';
        $codrecepcion = $post['cod_proceso'];
        $comentarios = $post['comentarios'];
        $usuariosAdicionales = '';
        $traza = trazarProcesoJuridico($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios, $usuariosAdicionales);
        return $traza;
    }

    function guarda_expediente($post) {
        $CI = & get_instance();
        $CI->load->model("Expediente_model");
        $model = new Expediente_model();
        $respuesta = $post['tipo_respuesta'];
        $nombre = 'Requerimiento Acercamiento';
        $ruta = $post['ruta'];
        $radicado = $post['numero_radicado'];
        $fecha_radicado = $post['fecha_radicado'];
        $tipo_expediente = $post['tipo_exp'];
        $subproceso = $post['sub_proceso'];
        $cod_coactivo = $post['cod_proceso'];
        $expediente = $model->agrega_expediente($respuesta, $nombre, $ruta, $radicado, $fecha_radicado, $tipo_expediente, $subproceso, ID_USUARIO, $cod_coactivo); //Guarda en la tabla expediente
    }

    function borrar($dir, $file, $image = NULL) {
        $return = array();
        $archivo1 = './uploads/' . $dir . '/' . $file;
        if (file_exists($archivo1)) {
            if (unlink($archivo1)) {
                $return[0] = TRUE;
            } else {
                $return[0] = FALSE;
            }
        }
        if ($image == TRUE) {
            $archivo2 = './' . $dir . '/thumb/' . $file;
            if (file_exists($archivo2)) {
                if (unlink($archivo2)) {
                    $return[1] = TRUE;
                } else {
                    $return[2] = FALSE;
                }
            }
        }
    }

}
