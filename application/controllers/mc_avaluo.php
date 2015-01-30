<?php

class Mc_avaluo extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'template', 'codegen_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('mc_avaluo_model');
        $this->load->file(APPPATH . "controllers/expedientes.php", TRUE);
        $this->load->library('form_validation', 'tcpdf/tcpdf.php');
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
        define("ABOGADO", "43");
        define("SECRETARIO", "41");
        define("COORDINADOR", "42");
        /*         * INICIO PROCESO Tipos de respuesta de MC_MEDIDASCAUTELARES */
        define("INICIO1", "379");
        define("INICIO2", "378");
        define("INICIO3", "617");
        /*         * TIPOS DE RESPUESTA* */
        define("GENERAR_AUTO_OPC1", "378");
        define("GENERAR_AUTO_OPC2", "1011");
        define("GENERAR_AUTO_OPC3", "617");
        define("AUTO_AVALUOBIENES_GENERADO", "385"); //GENERA AUTO EL SECRETARIO
        define("AUTO_AVALUOBIENES_RECHAZADO", "618"); //DEVUELVE EL AUTO EL COORDINADOR O SECRETARIO
        define("AUTO_AVALUOBIENES_PRE_APROBADO", "386"); //PRE-APRUEBA EL AUTO EL SECRETARIO
        define("AUTO_AVALUOBIENES_APROBADOFIRMADO", "387"); //ADJUNTA DOCUMENTO APROBADO Y FIRMADO
        define("AUTO_AVALUOBIENES_APROBADO", "1098"); //DOCUMENTO APROBADO POR EL COORDINADOR
        define("AUTO_NOMBRA_PERITO_GENERAD0", "388"); //
        define("AUTO_NOMBRA_PERITO_PRE_APROBADO", "389"); //
        define("AUTO_NOMBRA_PERITO_RECHAZADO", "390"); //
        define("AUTO_NOMBRA_PERITO_APROBADO", "1099"); // AUTO_NOMBRA_PERITO_APROBADOFIRMADO
        define("AUTO_NOMBRA_PERITO_APROBADOFIRMADO", "391");
        define("NOT_PERSONAL_G", "392"); //
        define("NOT_PERSONAL_REVISADA_APROBADA", "393"); //
        define("NOT_PERSONAL_ENVIADA", "394"); //
        define("NOTIFICACION_PERSONAL_DEVUELTA", "784"); // define("NOTIFICACION_PERSONAL_DEVUELTA", "784");El secretario o el coordinador la devuelve
        define("NOTIFICACION_PERSONAL_RECIBIDA_DEUDOR", "1417");
        define("NOTIFICACION_PERSONAL_DEVUELTA_DEUDOR", "1421");
        define("NOTIFICACION_POR_CORREO_GENERADA", "1423"); //
        define("NOTIFICACION_POR_CORREO_RECHAZADA", "1422"); //
        define("NOTIFICACION_POR_CORREO_APROBADA_FIRMADA", "1424"); //Aprueba y firma el secretario
        define("NOTIFICACION_POR_CORREO_ENVIADA", "1425");
        define("NOTIFICACION_PERSONAL_RECIBIDA", "1417"); // Notificación Personal al Deudor Informando el Resultado del Avaluo Recibida
        define("NOTIFICACION_POR_CORREO_RECIBIDA", "1426");
        define("NOTIFICACION_POR_CORREO_DEVUELTA", "1427"); //Devuelta por un motifivo diferente a Dirección Erronea
        define("NOTIFICACION_POR_PAGINA_WEB_GENERADA", "1418"); //
        define("NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA", "1419"); //
        define("NOTIFICACION_POR_PAGINA_WEB_PUBLICADA", "1420");
        define("AUTO_DICTAMEN_TRASLADO_GENERADO", "1428");
        define("AUTO_DICTAMEN_TRASLADO_PRE_APROBADO", "1429");
        define("AUTO_DICTAMEN_TRASLADO_APROBADO", "1513");
        define("AUTO_DICTAMEN_TRASLADO_APROBADO_FIRMADO", "1430");
        define("AUTO_DICTAMEN_TRASLADO_APROBADO_RECHAZADO", "1431");
        define("OBJECION_PRE_REQ_PRUEBAS", "395"); //
        define("OBJECION_PRE_NO_REQ_PRUEBAS", "396"); //
        define("AUTO_APERTURA_PRUEBAS_G", "397"); //
        define("AUTO_APERTURA_PRUEBAS_P", "398"); //
        define("AUTO_APERTURA_PRUEBAS_R", "399"); //
        define("AUTO_APERTURA_PRUEBAS_F", "400"); //
        define("AUTO_APERTURA_PRUEBAS_APROBADO", "1100");
        define("PRUEBAS_REALIZADAS_REQ_CORRECCION", "401"); //
        define("PRUEBAS_REALIZADAS_NO_REQ_CORRECCION", "402"); //
        define("AVALUO_RECIBO_PAGO_RECIBIDO", "403"); //
        define("AVALUO_RECIBO_PAGO_NO_RECIBIDO", "404"); //
        define("AUTO_CORRECION_AVALUO_G", "405"); //
        define("AUTO_CORRECION_AVALUO_P", "406"); //
        define("AUTO_CORRECION_AVALUO_R", "407"); //
        define("AUTO_CORRECION_AVALUO_F", "408"); //
        define("AUTO_RESUELVE_OBJ_AVALUO_G", "409"); //
        define("AUTO_RESUELVE_OBJ_AVALUO_P", "410"); //
        define("AUTO_RESUELVE_OBJ_AVALUO_R", "411"); //
        define("AUTO_RESUELVE_OBJ_AVALUO_F", "412"); //Aprobado y Firmado
        define("AUTO_RESUELVE_OBJ_AVALUO_A", "1102"); //Aprobado
        define("AVALUO_RECIBIDO", "413"); //
        define("NOTIFICACION_CORREO_GENERADA", "414"); //Genera el secretario
        define("NOTIFICACION_CORREO_PRE_APROBADA", "415"); //Pre aprueba el secretario define("NOTIFICACION_CORREO_APROBADA_FIRMADA", "415"); //
        define("NOTIFICACION_CORREO_APROBADA", "416"); //Aprueba el coordinador

        define("NOTIFICACION_CORREO_APROBADA_FIRMADA", "417"); //El secretario Adjunta el documento firmado
        define("NOTIFICACION_CORREO_DEVUELTA", "417"); //
        define("AUTO_DECLARA_FIRMEZA_AVALUO_G", "418"); //Generado
        define("AUTO_DECLARA_FIRMEZA_AVALUO_P", "419"); //Pre-aprobado
        define("AUTO_DECLARA_FIRMEZA_AVALUO_R", "420"); //Rechazado
        define("AUTO_DECLARA_FIRMEZA_AVALUO_F", "421"); //Aprobado y Firmado
        define("AUTO_DECLARA_FIRMEZA_AVALUO_A", "1105"); //Aprobado
        define("DEUDOR_NO_OBJETO", "763");
//tipos de autos
        define("AUTO_ORDENA_AVALUO_BIENES", "1"); //
        define("RUTA_DOC_PRUEBAS", "PRUEBAS"); //CARPETA GENERAL
        define("RUTA_PRUEBAS", "PRUEBAS/DOC_PRUEBAS_COD"); //CARPETA PARA CADA PRUEBA DEL PROCESO
//Titulo de los documentos
        define("AUTO_1", "Auto que Ordena Avalúo de Bienes");
        define("AUTO_2", "Auto que Nombra al Perito Avaluador");
        define("NOTIFICACION_PERSONAL", "Notificación Personal Informando Resultado del Avalúo");
        define("AUTO_3", "Auto Apertura de Pruebas Avalúo");
        define("AUTO_4", "Auto Correción Avalúo");
        define("AUTO_5", "Auto que Resuelve Objeción");
        define("AUTO_6", "Auto que Declara la Firmeza del Avalúo");
        define("AUTO_7", "Auto que Ordena Agregar el Dictamen del Avalúo y se Ordena Correr Traslado del Mismo");
        define("DOC_NOTIFICACION_CORREO", "Notificacion por Correo Informando Resultado del Avalúo");
        define("DOC_SOPORTE", "Documento Soporte Recepción Notificación");
        define("NOTIFICACION_WEB", "Notificación Por Pagina Web al Deudor Informando el Resultado del Avaluo");
        define("RUTA_INI", "./uploads/MC_AVALUO");
        define("RUTA_DES", "uploads/MC_AVALUO/COD_");
        define("RUTA_MC", "MC_AVALUO/COD_");

//tipos de plantilla
        define("PLANTILLA_AUTO_1", "62"); //
        define("PLANTILLA_AUTO_2", "65"); //
        define("PLANTILLA_NOTIFICAION_PERSONAL", "66");
        define("PLANTILLA_AUTO_3", "67"); //
        define("PLANTILLA_AUTO_4", "68"); //
        define("PLANTILLA_AUTO_5", "69"); //
        define("PLANTILLA_AUTO_6", "70"); //
        define("PLANTILLA_AUTO_7", "0");
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");

//tipos de gestion
        define("NOT_CORREO", "663");
        define("AUTO_ORDENA_AVALUO", "157");
        define("AUTO_NOMBRA_PERITO", "158");
        define("GESTION_NOTIFICACION_PERSONAL", "159");
        define("REVISION_OBJECION_PRESENTADA", "160");
        define("GESTION_FIRMEZA_AVALUO", "167");
        define("GESTION_APERTURA_PRUEBAS", "161");
        define("GESTION_REALIZAR_PRUEBAS", "162");
        define("GESTION_PAGO_HONORARIOS", "163");
        define("GESTION_CORRECCION_AVALUO", "164");
        define("GESTION_RESUELVE_OBJECION", "165");
        define("GESTION_AVALUO_NOTIFICACION", "166");
        define("GESTION_NOTIFICACION_CORREO", "663");
        define("GESTION_NOTIFICACION_WEB", "661");
        define("GESTION_AUTO_DICTAMEN", "664");

//tipos de documentos
        define("DOC_AUTO_ORDENA_AVALUO", "40");
        define("DOC_AUTO_NOMBRA_PERITO", "41");
        define("DOC_NOTIFICACION_PERSONAL", "42");
        define("DOC_AUTO_APERTURA_PRUEBAS", "43");
        define("DOC_AUTO_CORRECCION_AVALUO", "44");
        define("DOC_AUTO_RESUELVE_OBJECION_AVALUO", "45");
        define("DOC_AUTO_DECLARA_FIRMEZA_AVALUO", "46");
        define("DOC_REGISTRO_AVALUO_INMUEBLE", "47"); //Este documento es el soporte del avaluo del perito
        define("DOC_REGISTRO_AVALUO_MUEBLE", "48");
        define("DOC_REGISTRO_AVALUO_VEHICULO", "49");

        define("DOC_SOPORTE_RECEPCION_NOTIFICACION", "50");
        define("DOC_NOTIFICACIONCORREO", "51");
        define("DOC_NOTIFICACION_WEB", "52");
        define("DOC_DICTAMEN_AVALUO", "53");
        define("RUTA_GUARDA_ADJUNTAR_DOC", "mc_avaluo/adjunta_documento");
//nuevos documentos
        define("DOC_SOPORTE_PAGOHONORARIOS", "52");
        define("DOC_SOPORTE_AVALUO_EXTERNO", "53");
        define("DOC_REGISTRO_AVALUO", "54");
        $this->data['url_adjuntar_documento'] = base_url() . 'index.php/mc_avaluo/adjunta_documento';
        $this->data['eliminar_archivo'] = base_url() . 'index.php/mc_avaluo/deleteFile';
        $this->data['user'] = $this->ion_auth->user()->row();
        if ($this->data['user']) {
            define("ID_USER", $this->data['user']->IDUSUARIO);
        }
        $this->data['message'] = $this->session->flashdata('message');
        define("COD_PROCESO", "12");
        define("MUEBLE", "1");
        define("INMUEBLE", "2");
        define("VEHICULO", "3");

        $this->data['user'] = $this->ion_auth->user()->row();
        if ($this->data['user']) {
            define("ID_USUARIO", $this->data['user']->IDUSUARIO);
            define("ID_GRUPO", $this->data['user']->IDGRUPO);
            define("ID_REGIONAL", $this->data['user']->COD_REGIONAL);
            $sesion = $this->session->userdata;
            define("ID_SECRETARIO", $sesion['id_secretario']);
            define("NOMBRE_SECRETARIO", $sesion['secretario']);
            define("ID_COORDINADOR", $sesion['id_coordinador']);
            define("NOMBRE_COORDINADOR", $sesion['coordinador']);
            $this->data['ruta_guarda_avaluo'] = base_url() . 'index.php/mc_avaluo/GuardaRegistroAvaluo';
        }
    }

    function index() {

        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mc_avaluo/manage')) {
                $this->template->set('title', 'Medidas Cutelares Avaluo');
                $this->abogado();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mc_avaluo/abogado')) {
                $this->data['user'] = $this->ion_auth->user()->row();
                $estados = array(GENERAR_AUTO_OPC1, GENERAR_AUTO_OPC2, GENERAR_AUTO_OPC3, AUTO_AVALUOBIENES_RECHAZADO, AUTO_AVALUOBIENES_APROBADOFIRMADO, AUTO_NOMBRA_PERITO_RECHAZADO,
                    AUTO_NOMBRA_PERITO_APROBADOFIRMADO, AVALUO_RECIBIDO, DEUDOR_NO_OBJETO, AUTO_RESUELVE_OBJ_AVALUO_F, NOTIFICACION_CORREO_APROBADA_FIRMADA,
                    AUTO_DECLARA_FIRMEZA_AVALUO_R, OBJECION_PRE_REQ_PRUEBAS, AUTO_APERTURA_PRUEBAS_F, PRUEBAS_REALIZADAS_REQ_CORRECCION, AUTO_CORRECION_AVALUO_F,
                    PRUEBAS_REALIZADAS_NO_REQ_CORRECCION, AUTO_AVALUOBIENES_RECHAZADO, AUTO_NOMBRA_PERITO_RECHAZADO, AUTO_APERTURA_PRUEBAS_R, AUTO_CORRECION_AVALUO_R,
                    AUTO_RESUELVE_OBJ_AVALUO_R, AUTO_DECLARA_FIRMEZA_AVALUO_R, AVALUO_RECIBO_PAGO_NO_RECIBIDO, OBJECION_PRE_NO_REQ_PRUEBAS,
                    AVALUO_RECIBO_PAGO_RECIBIDO, NOT_PERSONAL_ENVIADA, NOTIFICACION_PERSONAL_DEVUELTA, AUTO_AVALUOBIENES_APROBADO);
                $regional = $this->ion_auth->user()->row()->COD_REGIONAL;
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo($regional, $estados, false, $cod_coactivo);
                define('AJAX_REQUEST', 1); //truco par,a que en nginx no muestre el debug  
                $TOTAL = $this->mc_avaluo_model->totalData2($this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
                $this->template->load($this->template_file, 'mc_avaluo/gestion_abogado', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function secretario() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mc_avaluo/secretario')) {
                $this->data['user'] = $this->ion_auth->user()->row();
                $estados = array(AUTO_AVALUOBIENES_GENERADO, AUTO_NOMBRA_PERITO_GENERAD0, NOT_PERSONAL_G, NOT_PERSONAL_REVISADA_APROBADA, AUTO_DECLARA_FIRMEZA_AVALUO_G,
                    AUTO_APERTURA_PRUEBAS_G, AUTO_CORRECION_AVALUO_G, AUTO_RESUELVE_OBJ_AVALUO_G, NOTIFICACION_CORREO_GENERADA);
                $this->mc_avaluo_model->set_array_num(array(GENERAR_AUTO_OPC1, GENERAR_AUTO_OPC2, GENERAR_AUTO_OPC3, NOT_PERSONAL_REVISADA_APROBADA,));
                $regional = $this->ion_auth->user()->row()->COD_REGIONAL;
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo($regional, $estados, false, $cod_coactivo);
                define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug  
                $TOTAL = $this->mc_avaluo_model->totalData2($this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
                $this->template->load($this->template_file, 'mc_avaluo/gestion_secretario', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function coordinador() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mc_avaluo/coordinador')) {
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->mc_avaluo_model->set_array_num(array(GENERAR_AUTO_OPC2, GENERAR_AUTO_OPC3));
                $estados = array(AUTO_AVALUOBIENES_PRE_APROBADO, AUTO_NOMBRA_PERITO_PRE_APROBADO, AUTO_DECLARA_FIRMEZA_AVALUO_P,
                    AUTO_APERTURA_PRUEBAS_P, AUTO_CORRECION_AVALUO_P, AUTO_RESUELVE_OBJ_AVALUO_P, NOTIFICACION_CORREO_APROBADA);
                $regional = $this->ion_auth->user()->row()->COD_REGIONAL;
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo($regional, $estados, false, $cod_coactivo);
                define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug  
                $TOTAL = $this->mc_avaluo_model->totalData2($this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
                $this->template->load($this->template_file, 'mc_avaluo/gestion_coordinador', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function bloqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('mc_avaluo/bloquear', $this->data);
    }

    function bloqueo_por_time() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('mc_avaluo/bloqueo_por_time', $this->data);
    }

    private function Traza($id, $cod_respuesta) {
        /*
         * Función que permite guardar la traza para cada gestión realizada en el proceso de avalúo.
         */
        $gestion = $this->mc_avaluo_model->datos_gestion($cod_respuesta);
        $tipogestion = $gestion["COD_TIPOGESTION"];
        $tiporespuesta = $gestion["COD_RESPUESTA"];
        $codtitulo = NULL;
        $codjuridico = $id;
        $codcarteranomisional = NULL;
        $coddevolucion = NULL;
        $codrecepcion = NULL;
        $usuariosAdicionales = '';
        $comentarios = $gestion['NOMBRE_GESTION'];
        $traza = trazarProcesoJuridico($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios, $usuariosAdicionales);

        return $traza;
    }

    private function guarda_expediente($post) {
        $CI = & get_instance();
        $CI->load->model("Expediente_model");
        $model = new Expediente_model();
        $fecha_not_efectiva = '';
        $respuesta = $post['respuesta'];
        $nombre = $post['titulo'];
        $ruta = $post['ruta'];
        $radicado = $post['numero_radicado'];
        $fecha_radicado = $post['fecha_radicado'];
        $tipo_expediente = '2';
        $subproceso = 'Avaluo';
        $cod_coactivo = $post['id'];
        if (isset($post['notificacion_efectiva'])):
            $fecha_not_efectiva = $post['notificacion_efectiva'];
        endif;
        $expediente = $this->mc_avaluo_model->agrega_expediente($respuesta, $nombre, $ruta, $radicado, $fecha_radicado, $tipo_expediente, $subproceso, ID_USUARIO, $cod_coactivo, $fecha_not_efectiva); //Guarda en la tabla expediente
        return $expediente;
    }

    function vistas() {
        if ($this->ion_auth->logged_in()):

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/index')):
//                if ($this->input->post('cod_proceso')):
                    $post = $this->input->post();
                    $respuesta = $post['respuesta'];
                    $proceso = $post['cod_proceso'];
                    $medida_cautelar = $post['medida_cautelar'];
                    $this->data['url_generar_pdf'] = 'mc_avaluo/pdf';
                    $this->data['motivos'] = $this->mc_avaluo_model->motivos();
                    $this->data['user'] = $this->ion_auth->user()->row();
                    $this->data['cabecera'] = $this->mc_avaluo_model->cabecera($respuesta, $proceso);
                    $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
                    $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(FALSE, FALSE, FALSE, $proceso);
                    $this->data['traza'] = $this->mc_avaluo_model->historico($post); //Consula el historial de observaciones
                    $this->data['consulta_doc'] = $this->mc_avaluo_model->documento_mc($post['tipo_doc'], $proceso);
                    $this->data['post'] = $post;
                    if (!empty($this->data['consulta_doc'])):
                        $this->data['documento'] = '';
                        $ruta = $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN'];
                        $this->data['cod_oficio'] = $this->data['consulta_doc'][0]['COD_OFICIO_MC'];
                        $info = pathinfo($ruta);
                        $extension = $info['extension'];
                        if ($extension == 'txt'):
                            $this->data['documento'] = $this->leer_fichero_completo($ruta);
                        endif;
                    else:
                        $this->data['documento'] = '';
                        $this->data['cod_oficio'] = '';
                    endif;
                    switch ($respuesta):
                        case NOT_PERSONAL_REVISADA_APROBADA:
                        case NOT_PERSONAL_ENVIADA:
                            $this->data['titulo'] = 'Citación Notificación Personal';
                            $this->data['post']['cod_siguiente'] = 394;
                            $this->data['post']['nombre'] = 'Notificacion_Personal';
                            $this->load->view('mc_avaluo/editar_notificacion', $this->data);
                            break;


                        case NOTIFICACION_POR_CORREO_APROBADA_FIRMADA:
                        case NOTIFICACION_POR_CORREO_ENVIADA:
                            $this->data['titulo'] = 'Notificación Por Correo';
                            $this->data['post']['cod_siguiente'] = NOTIFICACION_POR_CORREO_ENVIADA;
                            $this->data['post']['nombre'] = 'Notificacion_Personal';
                            $this->load->view('mc_avaluo/editar_notificacion', $this->data);
                            break;

                        case NOTIFICACION_POR_CORREO_DEVUELTA:/* Gestiona el secretario revisa o complemente el contenido */
                            $this->data['titulo'] = NOTIFICACION_WEB;
                            $this->data['post']['cod_siguiente'] = NOTIFICACION_POR_PAGINA_WEB_GENERADA;
                            $this->data['post']['nombre'] = 'Notificacion_Pagina_web';
                            $this->load->view('mc_avaluo/editar_notificacion', $this->data);
                            break;

                        case NOTIFICACION_POR_PAGINA_WEB_GENERADA:
                            $this->data['titulo'] = NOTIFICACION_WEB;
                            $this->data['post']['cod_siguiente'] = NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA;
                            $this->data['post']['nombre'] = 'Notificacion_Pagina_web';
                            $this->load->view('mc_avaluo/editar_notificacion', $this->data);
                            break;

                        case NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA:
                            $this->data['titulo'] = NOTIFICACION_WEB;
                            $this->data['post']['cod_siguiente'] = NOTIFICACION_POR_PAGINA_WEB_PUBLICADA;
                            $this->data['post']['nombre'] = 'Notificacion_Pagina_web';
                            $this->load->view('mc_avaluo/editar_notificacion', $this->data);
                            break;


                        case DEUDOR_NO_OBJETO:
                        case GENERAR_AUTO_OPC1:
                        case GENERAR_AUTO_OPC2:
                        case GENERAR_AUTO_OPC3:
                        case AUTO_AVALUOBIENES_APROBADOFIRMADO:
                        case OBJECION_PRE_REQ_PRUEBAS:
                        case PRUEBAS_REALIZADAS_REQ_CORRECCION:
                        case AUTO_CORRECION_AVALUO_F:
                        case NOTIFICACION_CORREO_APROBADA_FIRMADA:
                        case AVALUO_RECIBIDO:
                        case AUTO_RESUELVE_OBJ_AVALUO_F:
                        case OBJECION_PRE_NO_REQ_PRUEBAS:
                        case AVALUO_RECIBO_PAGO_RECIBIDO:

                        case AUTO_AVALUOBIENES_RECHAZADO:
                        case AUTO_APERTURA_PRUEBAS_R:
                        case AUTO_CORRECION_AVALUO_R:
                        case AUTO_RESUELVE_OBJ_AVALUO_R:
                        case AUTO_NOMBRA_PERITO_RECHAZADO:
                        case AUTO_DECLARA_FIRMEZA_AVALUO_R:
                        case AUTO_DICTAMEN_TRASLADO_APROBADO_RECHAZADO:
                        case NOTIFICACION_PERSONAL_DEVUELTA:
                        case AVALUO_RECIBO_PAGO_NO_RECIBIDO:
                        case AUTO_NOMBRA_PERITO_APROBADOFIRMADO:
                            $this->load->view('mc_avaluo/auto', $this->data);
                            break;

//ABOGADO ADJUNTA DOCUMENTOS DE LOS AUTOS APROBADOS Y FIRMADOS
                        case AUTO_AVALUOBIENES_APROBADO:
                        case AUTO_NOMBRA_PERITO_APROBADO:
                        case AUTO_RESUELVE_OBJ_AVALUO_A:
                        case AUTO_APERTURA_PRUEBAS_APROBADO:
                        case AUTO_DECLARA_FIRMEZA_AVALUO_A:
                        case NOTIFICACION_CORREO_APROBADA;
                        case AUTO_DICTAMEN_TRASLADO_APROBADO;
                            $this->data['ruta_formulario'] = RUTA_GUARDA_ADJUNTAR_DOC;
                            $this->load->view('mc_avaluo/detalle_auto', $this->data);
                            $this->load->view('mc_avaluo/adjuntar_documento', $this->data);
                            break;
//PROCESOS DEL SECRETARIO
                        case AUTO_AVALUOBIENES_GENERADO:
                        case AUTO_DECLARA_FIRMEZA_AVALUO_G:
                        case AUTO_APERTURA_PRUEBAS_G:
                        case AUTO_NOMBRA_PERITO_GENERAD0:
                        case AUTO_CORRECION_AVALUO_G:
                        case AUTO_RESUELVE_OBJ_AVALUO_G:
                        case NOTIFICACION_CORREO_GENERADA:
                        case NOTIFICACION_POR_CORREO_GENERADA:
                        case AUTO_DICTAMEN_TRASLADO_GENERADO:

//PROCESOS DEL COORDINADOR
                        case AUTO_NOMBRA_PERITO_PRE_APROBADO:
                        case AUTO_DECLARA_FIRMEZA_AVALUO_P:
                        case AUTO_APERTURA_PRUEBAS_P:
                        case AUTO_AVALUOBIENES_PRE_APROBADO:
                        case AUTO_CORRECION_AVALUO_P:
                        case AUTO_RESUELVE_OBJ_AVALUO_P:
                        case NOTIFICACION_CORREO_APROBADA:
                        case NOT_PERSONAL_G:
                        case AUTO_DICTAMEN_TRASLADO_PRE_APROBADO:
                            $this->load->view('mc_avaluo/detalle_auto', $this->data);
                            $this->load->view('mc_avaluo/documento_auto', $this->data);
                            break;
//            case NOTIFICACION_CORREO_GENERADA:
//                break;
                        case NOT_PERSONAL_REVISADA_APROBADA://lista para enviarla
                            $this->data['doc_notificacion'] = $this->mc_avaluo_model->documento_mc(42, $proceso);

                            $this->data['ruta_doc_notificacion'] = RUTA_DES . $proceso . "/" . $this->data['doc_notificacion'][0]['RUTA_DOCUMENTO_GEN'];
                            $this->load->view('mc_avaluo/detalle_auto', $this->data);
                            $this->load->view('mc_avaluo/notificacion_enviada', $this->data);
                            break;

                    endswitch;
//                else:
//                    redirect(site_url() . '/bandejaunificada/index');
//                endif;
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(site_url() . '/inicio');
            endif;

        else:
            redirect(site_url() . '/auth/login');
        endif;
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

    function gestion_notificacion() {
        /* La función gestion_notificación, permite almacenar la información de envio, recepción, de la notificación personal, notificación por correo, publicación paginaweb,
         */
        $post = $this->input->post();
        $mensaje = 'Se ha actualizado el proceso';
        $detalle = unserialize($post['detalle']);
        $post['respuesta'] = $detalle['respuesta'];
        $post['cod_siguiente'] = $detalle['cod_siguiente'];
        $post['avaluos'] = $detalle['avaluos'];
        $post['avaluos'] = explode(',', $detalle['avaluos']);
        $post['cantidad'] = count($post['avaluos']);
        $post['tipo_gestion'] = $detalle['tipo_gestion'];
        $post['id'] = $detalle['cod_proceso'];
        $post['nombre'] = $detalle['nombre'];
        $post['titulo'] = $detalle['titulo'];
        $post['tipo_doc'] = $detalle['tipo_doc'];
        $this->data['user'] = $this->ion_auth->user()->row();
        $post['generar_oficio'] = FALSE;
        $ruta = $this->guardar_archivo();
        $post['ruta'] = $ruta;
        if ($post['cod_siguiente'] == 394):/* Cuando se envia la notificación no hay devolución */
            $post['devolucion'] = $this->input->post('devolucion') ? $this->input->post('devolucion') : 0;
            $mensaje = 'Se ha enviado la ' . NOTIFICACION_PERSONAL;
        endif;

        switch ($post['respuesta']):

            case NOT_PERSONAL_ENVIADA:
                if (($post['devolucion'] == 1) && ($post['motivo'] == 4)):
                    $post['cod_siguiente'] = NOT_PERSONAL_ENVIADA;
                    $expediente = $this->guarda_expediente($post);
                elseif (($post['devolucion'] == 1) && ($post['motivo'] != 4)): /** Si la devolución es por otro motivo se genera notificación por correo * */
                    $post['cod_siguiente'] = NOTIFICACION_POR_CORREO_GENERADA;
                    $mensaje = 'Se ha enviado la Notificación por correo. ';

                else:/* Cuando la Notificación Personal ha sido recibida por el deudor */
                    $post['cod_siguiente'] = NOTIFICACION_PERSONAL_RECIBIDA_DEUDOR;
                    $mensaje = 'Se ha enviado la Notificación Personal';
                endif;
                $post['generar_oficio'] = TRUE;
                break;
            case NOTIFICACION_POR_CORREO_ENVIADA:
                if (($post['devolucion'] == 1) && ($post['motivo'] == 4)):
                    $post['cod_siguiente'] = NOTIFICACION_POR_CORREO_ENVIADA;
                    $expediente = $this->guarda_expediente($post);
                    $post['generar_oficio'] = TRUE;
                elseif (($post['devolucion'] == 1) && ($post['motivo'] != 4)): /** Si la devolución es por otro motivo se genera notificación por correo * */
                    $post['cod_siguiente'] = NOTIFICACION_POR_CORREO_DEVUELTA;
                    $post['generar_oficio'] = FALSE;
                endif;

                break;
            case 1426:
            case 1417:
            case 1420:
                $codcoactivo = $post['id'];
                $gestion = $post['tipo_gestion'];
                $si = 'objecion';
                $no = 'vistas';
                $post['generar_oficio'] = FALSE;
                $expediente = $this->guarda_expediente($post);
                break;
            case NOTIFICACION_POR_CORREO_DEVUELTA:
                $post['cod_siguiente'] = NOTIFICACION_POR_PAGINA_WEB_GENERADA;
                $post['generar_oficio'] = TRUE;
                break;
            case NOTIFICACION_POR_PAGINA_WEB_GENERADA:
                $post['cod_siguiente'] = NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA;
                $post['generar_oficio'] = TRUE;
                break;
            case NOTIFICACION_POR_PAGINA_WEB_PRE_APROBADA:
                $post['cod_siguiente'] = NOTIFICACION_POR_PAGINA_WEB_PUBLICADA;
                $post['generar_oficio'] = TRUE;
                break;

        endswitch;
        $info = $this->mc_avaluo_model->gestion_notificacion($post);
        if ($info) :
            $idgestioncobro = $this->Traza($post['id'], $post['cod_siguiente']);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $mensaje . $idgestioncobro . '</div>');
        else:
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha presentado un error en la aplicacion</div>');
        endif;
    }

    function no_objeto() {
        $post = $this->input->post();
        $post['tipo_gestion'] = REVISION_OBJECION_PRESENTADA;
        $post['cod_siguiente'] = DEUDOR_NO_OBJETO;
        $detalle = unserialize($post['detalle']);
        $post['avaluos'] = $detalle['avaluos'];
        $post['avaluos'] = explode(',', $detalle['avaluos']);
        $post['cantidad'] = count($post['avaluos']);
        $info = $this->mc_avaluo_model->no_objeto($post);
        if ($info) {
            $idgestioncobro = $this->Traza($post['id'], $post['cod_siguiente']);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha actualizado el proceso</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha presentado un error en la aplicacion</div>');
        }
    }

    function objeto() {
        $this->data['post'] = $this->input->post();
        $detalle = unserialize($this->data['post']['detalle']);
        $this->data['post']['avaluos'] = $detalle['avaluos'];
        $this->data['post']['avaluos'] = explode(',', $detalle['avaluos']);
        $this->data['post']['cantidad'] = count($detalle['avaluos']);

        $this->data['post']['cod_proceso'] = $detalle['id'];
        $this->data['message'] = $this->session->flashdata('message');
        $decreta_pruebas = $this->data['post']['decreta_pruebas'];
        if ($decreta_pruebas == 'S') {
            $this->data['post']['tipo_gestion'] = REVISION_OBJECION_PRESENTADA;
            $this->data['post']['cod_siguiente'] = OBJECION_PRE_REQ_PRUEBAS;
        } else if ($decreta_pruebas == 'N') {
            $this->data['post']['tipo_gestion'] = REVISION_OBJECION_PRESENTADA;
            $this->data['post']['cod_siguiente'] = OBJECION_PRE_NO_REQ_PRUEBAS;
        }
        $info = $this->mc_avaluo_model->objeto($this->data);
        if ($info) {

            $idgestioncobro = $this->Traza($this->data['post']['id'], $this->data['post']['cod_siguiente']);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> Se ha guardado la información</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button> Se ha generado un error</div>');
        }
    }

    function registro_pruebas() {//visualiza el formulario para agregar pruebas
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, false, $this->data['post']['id']);
        $this->data['tipos_pruebas'] = $this->mc_avaluo_model->get_tipoprueba();
        $this->template->load($this->template_file, 'mc_avaluo/registrar_pruebas', $this->data);
    }

    function subir_documentos_pruebas() {
        $this->data['post'] = $this->input->post();
//Cada prueba tiene una carpeta para los documentos
        $this->data['info'] = $this->subir_pruebas();
        if ($this->data['info']) {
            $this->data['prueba'] = $this->data['post']['id_prueba'];
            $this->data['ruta'] = RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'];
            $this->data['ruta_eliminar'] = RUTA_MC . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'];
            $this->load->view('mc_avaluo/pruebas_subidas', $this->data);
        }
    }

    function subir_pruebas() {
        $this->load->library('libupload');
        if (!file_exists(RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_DOC_PRUEBAS)) {
            if (!mkdir(RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_DOC_PRUEBAS, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'])) {
                if (!mkdir(RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'], 0777, true)) {
                    
                }
            }
        }
        $ruta_final = 'MC_AVALUO/COD_' . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'];
        for ($i = 0; $i < count($_FILES); $i++) {
            if (!empty($_FILES['archivo' . $this->data['post']['id_prueba'] . $i]['name'])) {

                $respuesta[] = $this->libupload->doUpload($this->data['post']['id_prueba'] . $i, $_FILES['archivo' . $this->data['post']['id_prueba'] . $i], $ruta_final, 'pdf|jpg|jpeg', 9999, 9999, 0);
            }
        }
        return $respuesta;
    }

    function sube_pruebas() {
        $this->data['post'] = $this->input->post();
        $this->data['archivos_subidos'] = $this->subir_pruebas();
        $this->data['ruta_final'] = RUTA_MC . $this->data['post']['id'] . "/" . RUTA_DOC_PRUEBAS;
        $this->load->view('mc_avaluo/pruebas_subidas', $this->data);
    }

    function eliminar_pruebas() {
        $this->data['post'] = $this->input->post();
        $carpeta = "./" . RUTA_DES . $this->data['post']['id_proceso'] . "/" . RUTA_PRUEBAS . $this->data['post']['id_prueba'];
//elimino los archivos
        foreach (glob($carpeta . "/*") as $archivos_carpeta) {
            if (is_dir($archivos_carpeta)) {
                eliminarDir($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta); //Elimino la carpeta de la prueba
    }
 function bloqueos()
 {
    
     $post=$this->input->post();
     switch($post['respuesta']):
         case 1426: //Ejecutado recibio notificación pero no se presento se envia al generar el auto que declara firmeza del avaluo
         case 1420:
         case 1417:
             $post['cod_siguiente']=DEUDOR_NO_OBJETO; 
              $resultado=  $this->mc_avaluo_model->bloqueos($post);
              $idgestioncobro = $this->Traza($post['id'], DEUDOR_NO_OBJETO);
         break;
     case 402:
              $post['cod_siguiente']=404; 
              $resultado=  $this->mc_avaluo_model->bloqueos($post);
              $idgestioncobro = $this->Traza($post['id'], 404);
         break;
         break;
     endswitch;
     
 }
    function deleteFile() {
        $this->datos = $this->input->post();
        $dir = $this->datos['ruta'];
        $file = $this->datos['documento'];
        $this->delete($dir, $file, NULL);
    }

    function delete($dir, $file, $image = NULL) {
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

    function guardar_pruebas() {//RECIBE FORMULARIO CON TODAS LAS PRUEBAS.
        $this->data['post'] = $this->input->post();
        $detalle = unserialize($this->data['post']['detalle']);
        $this->data['post']['avaluos'] = $detalle['avaluos'];
        $this->data['post']['avaluos'] = explode(',', $this->data['post']['avaluos']);
        $this->data['post']['cantidad'] = count($this->data['post']['avaluos']);
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['usuario'] = $this->data['user']->IDUSUARIO;
        $resultado = $this->mc_avaluo_model->guardar_pruebas($this->data);
        if ($resultado) {
            $rutas = $this->mc_avaluo_model->rutas_pruebas($this->data);
            $cant = count($rutas);
            for ($i = 0; $i < $cant; $i++) {

/// $cantidad = count($post['post']['anotaciones']);
            }
            $requerir = $this->mc_avaluo_model->requiere_correccion($this->data);
            if ($this->data['post']['req_correccion'] == 1) {
                $requiere = 'S';
                $codigo_siguiente = PRUEBAS_REALIZADAS_REQ_CORRECCION;
            } else if ($this->data['post']['req_correccion'] == 2) {
                $requiere = 'N';
                $codigo_siguiente = PRUEBAS_REALIZADAS_NO_REQ_CORRECCION;
            }
            $idgestioncobro = $this->Traza($this->data['post']['idproceso'], $codigo_siguiente);
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se han guardado las pruebas</div>');
            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        }
    }

    function getDatamc() {
        $this->load->library('datatables');
        $data['registros'] = $this->mc_avaluo_model->consulta_mcavaluo($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
        define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug  
        $TOTAL = $this->mc_avaluo_model->totalData2($this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
        echo json_encode(array('aaData' => $data['registros'],
            'iTotalRecords' => $TOTAL,
            'iTotalDisplayRecords' => $TOTAL,
            'sEcho' => $this->input->post('sEcho')));
    }

    function genera_documento() {//Genera el auto del abogado
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mc_avaluo/abogado')) {
                $this->data['post'] = $this->input->post();
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
                $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, $this->data['post']['id']);
                $this->data['traza'] = $this->mc_avaluo_model->historico($this->data); //Consula el historial de observaciones
                $this->data['id_plantilla'] = $this->data['post']['id_plantilla'];
                $this->id = $this->data['post']['id'];

                $this->data['documento'] = '';
                switch ($this->data['post']['respuesta']) {//el codigo de respuesta actual del proceso
                    case DEUDOR_NO_OBJETO:
                    case GENERAR_AUTO_OPC1:
                    case GENERAR_AUTO_OPC2:
                    case GENERAR_AUTO_OPC3:
                    case AUTO_AVALUOBIENES_APROBADOFIRMADO:
                    case OBJECION_PRE_REQ_PRUEBAS:
                    case PRUEBAS_REALIZADAS_REQ_CORRECCION:
                    case AUTO_CORRECION_AVALUO_F:
                    case NOTIFICACION_CORREO_APROBADA_FIRMADA:
                    case AVALUO_RECIBIDO:
                    case AUTO_RESUELVE_OBJ_AVALUO_F:
                    case OBJECION_PRE_NO_REQ_PRUEBAS:
                    case AVALUO_RECIBO_PAGO_RECIBIDO:

                        $this->data['empresa'] = $this->mc_avaluo_model->get_empresa($this->data['post']['nit']);

                        $this->load->view('mc_avaluo/auto', $this->data);
                        break;

                    /* ABOGADO ADJUNTA DOCUMENTOS DE LOS AUTOS APROBADOS Y FIRMADOS */
                    case AUTO_AVALUOBIENES_APROBADO:
                    case AUTO_NOMBRA_PERITO_APROBADO:
                    case AUTO_RESUELVE_OBJ_AVALUO_A:
                    case AUTO_APERTURA_PRUEBAS_APROBADO:
                    case AUTO_DECLARA_FIRMEZA_AVALUO_A:
                        $this->data['consulta_doc'] = $this->mc_avaluo_model->documento_mc($this->data['post']['tipo_doc'], $this->data['post']['id']);
                        $this->data['nombre_documento'] = $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN'];
                        $this->data['cod_oficio'] = $this->data['consulta_doc'][0]['COD_OFICIO_MC'];
                        $name_file = trim(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN']);
                        if (file_exists($name_file)) {
                            if (filesize($name_file)) {
                                $text_file = fopen($name_file, "r");
                                $contet_file = fread($text_file, filesize($name_file));
                                $this->data['documento'] = $contet_file;
                            } else {
                                $this->data['documento'] = '';
                            }
                        } else {
                            $this->data['documento'] = '';
                        }

                        $this->data['ruta_formulario'] = RUTA_GUARDA_ADJUNTAR_DOC;
                        $this->load->view('mc_avaluo/detalle_auto', $this->data);
                        $this->load->view('mc_avaluo/adjuntar_documento', $this->data);
                        break;
//                    case AVALUO_RECIBO_PAGO_RECIBIDO:
//                         $this->data['empresa'] = $this->mc_avaluo_model->get_empresa($this->data['post']['nit']);
//                        $this->template->load($this->template_file, 'mc_avaluo/generar_notificacion', $this->data);
//                        break;
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_documento_adjunto() {
        $post = $this->input->post();
        $detalle = unserialize($post['detalle']);
        $post['avaluos'] = $detalle['avaluos'];
        $post['avaluos'] = explode(',', $post['avaluos']);
        $post['cantidad'] = count($post['avaluos']);
        $post['id'] = $detalle['cod_proceso'];
        $post['tipo_doc'] = $detalle['tipo_doc'];
        $post['cod_siguiente'] = $detalle['cod_siguiente'];
        $post['respuesta'] = $detalle['respuesta'];
        $post['titulo'] = $detalle['titulo'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('numero_radicado', 'Número Radicado', 'required');
        $this->form_validation->set_rules('fecha_radicado', 'Fecha Radicado', 'required');
        $this->form_validation->set_rules('ruta', 'Adjuntar Documento', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
            switch ($this->data['post']['respuesta']) {//el codigo de respuesta actual del proceso
                case DOC_NOTIFICACION_PERSONAL://Cuando adjunta la notificación
                    $ruta_mensaje = 'index.php/mc_avaluo/secretario';
                    break;
                case AUTO_NOMBRA_PERITO_APROBADO:
                    $ruta_mensaje = 'index.php/mc_avaluo/abogado'
                            . '';
                    break;
            }
            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        } else {
            $this->aprobacion_documento($post);
        }
    }

    function GuardaRegistroAvaluo() {
        $post = $this->input->post();
        $detalle = unserialize($post['detalle']);
        $post['avaluos'] = $detalle['avaluos'];
        $post['avaluos'] = explode(',', $post['avaluos']);
        $post['cantidad'] = count($post['avaluos']);
        $post['id'] = $detalle['cod_proceso'];
        $post['tipo_doc'] = $detalle['tipo_doc'];
        $post['cod_siguiente'] = $detalle['cod_siguiente'];
        $post['respuesta'] = $detalle['respuesta'];
        $post['titulo'] = $detalle['titulo'];
        $this->load->library('form_validation');
        $this->form_validation->set_rules('numero_radicado', 'Número Radicado', 'required');
        $this->form_validation->set_rules('fecha_radicado', 'Fecha Radicado', 'required');
        $this->form_validation->set_rules('ruta', 'Adjuntar Documento', 'required');
        $this->data['cod_coactivo'] = $post['id'];
        if ($this->form_validation->run() == false) :

            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
            redirect(base_url() . 'index.php/mc_avaluo/abogado', $this->data);
        else:
            /** Valida que todos los avaluos se han agregado */
            $valida = $this->validaRegistroAvaluo($post);

            if (!$valida || $valida == FALSE):
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button> Falta avaluós por registrar verifique nuevamente la información</div>');
                redirect(base_url() . 'index.php/mc_avaluo/abogado', $this->data);
            else:
                $this->aprobacion_documento($post);
            endif;
        endif;
    }

    private function validaRegistroAvaluo($post) {
        $existe = 0;
        foreach ($post['avaluos']as $avaluo):
            $dato = array('COD_PROCESO' => $post['id'], 'COD_AVALUO' => $avaluo);
            $existe = $this->mc_avaluo_model->propiedad($avaluo);
        endforeach;
        return $existe;
    }

    function aprobacion_documento($post) {//CUANDO SE ADJUNTA DOCUMENTO APROBADO Y FIRMADO
        $this->data['user'] = $this->ion_auth->user()->row();
        if ($post['cod_siguiente'] != AVALUO_RECIBIDO):
            $this->data['consulta_doc'] = $this->mc_avaluo_model->documento_mc($post['tipo_doc'], $post['id']);
            $post['cod_oficio'] = $this->data['consulta_doc'][0]['COD_OFICIO_MC'];
        endif;

        $resultado = $this->mc_avaluo_model->update_mc_avaluo($post);
        switch ($post['respuesta']) {//el codigo de respuesta actual del proceso
            case DOC_NOTIFICACION_PERSONAL://Cuando adjunta la notificación
                $ruta_mensaje = 'index.php/mc_avaluo/secretario';
                break;
            case AUTO_AVALUOBIENES_APROBADOFIRMADO:
                $ruta_mensaje = 'index.php/mc_avaluo/abogado';
                break;
        }
        if ($resultado) {
            $this->load->model('expediente_model');
            $idgestioncobro = $this->Traza($post['id'], $post['cod_siguiente']);
            $ruta = RUTA_DES . $post['id'] . "/" . $post['nombre'];
            $usuario = $this->data['user']->IDUSUARIO;
            $expediente = $this->guarda_expediente($post);

            if ($post['cod_siguiente'] == AVALUO_RECIBIDO):
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado la información del avalúo recibido</div>');
            else:
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado la información del documento</div>');
            endif;

            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error</div>');
            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        }
    }

    function guarda_notificacion() {
        $this->data['post'] = $this->input->post();
        $this->form_validation->set_rules('informacion', 'Contenido de la Notificación', 'required');
        $this->form_validation->set_rules('fecha', 'Fecha', 'required');
        $this->form_validation->set_rules('num_radicado', 'Número de Radicado', 'required');
        $this->form_validation->set_rules('motivo', 'Motivo', 'required');
        $this->form_validation->set_rules('fecha_recibida', 'Fecha Recibida', 'required');

        if ($this->form_validation->run() == false) {//valida los campos
            echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>';
        } else {//si los campos no estan vacios actualiza ::
            $this->data['user'] = $this->ion_auth->user()->row();
            $ruta = $this->guardar_archivo();
            $info = $this->mc_avaluo_model->guardar_mc_avaluo($this->data);
            if ($info) {
                $idgestioncobro = $this->Traza($this->data['post']['id'], $this->data['post']['cod_siguiente']);
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> Se ha generado la notificación</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button> Se ha generado un error</div>');
            }
        }
    }

    function guarda_notificacion_enviada() {
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['post'] = $this->input->post();
        $ruta_fichero = RUTA_DES . $this->data['post']['id'];
        $file = $this->sube_documento($this->data['post']);
//guarda la ruta del documento subido en oficios generados
        $this->data['user'] = $this->ion_auth->user()->row();

        if ($info) {
            $this->load->model('expediente_model');
            $idgestioncobro = $this->Traza($this->data['post']['id'], $this->data['post']['cod_siguiente']);
            $ruta = RUTA_DES . $this->data['post']['id'] . "/" . $file[0]['data']['file_name'];
            $usuario = $this->data['user']->IDUSUARIO;
            $this->data['post']['nombre'] = $file[0]['data']['file_name'];
            $this->expediente_model->guarda_expediente($this->data['post']['cod_fisc'], $this->data['post']['respuesta'], $this->data['post']['nombre'], $ruta, $this->data['post']['numero_radicado'], $this->data['post']['fecha_radicado'], 8, $this->data['post']['titulo'], $usuario);

            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> Se ha guardado la información </div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button> Se ha generado un error </div>');
        }
    }

    function sube_documento($post) {

        $this->load->library('libupload');
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $post['id'])) {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        }
        $ruta_final = 'MC_AVALUO/COD_' . $this->data['post']['id'];
        for ($i = 0; $i < count($_FILES); $i++) {
            $nombre = $_FILES['archivo' . $i]['name'];
            $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $ruta_final, 'pdf|jpg|jpeg', 9999, 9999, 0, $nombre);
        }
//
        return $respuesta;
    }

    private function do_upload($ruta_fichero) {
        try {
            if ($this->ion_auth->logged_in()):
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')):
                      if (!file_exists($ruta_fichero)) {
                        $this->crea_ruta($ruta_fichero);
                    }
                    $config['upload_path'] = $ruta_fichero;
                    $config['allowed_types'] = 'pdf|gif|jpg|png|jpeg';
                    $config['max_size'] = '20480';
                  
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload()) :
                        return $error = array('error' => $this->upload->display_errors());
                    else:
                        return $data = array('upload_data' => $this->upload->data());
                    endif;
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                    header('Location:' . base_url('index.php/inicio'));
                endif;
            else:
                header('Location:' . base_url('index.php/auth/login'));
            endif;
        } catch (Exception $e) {
            $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() . '</div>';
        }
    }

    function plantilla_auto($id, $arreglo = false) {
        $this->load->model('plantillas_model');
        $this->data['informacion'] = $this->plantillas_model->plantillas($id);
        $urlplantilla2 = "uploads/plantillas/" . $this->data['informacion'][0]['ARCHIVO_PLANTILLA'];
        if (file_exists($urlplantilla2)) {
            $arreglo = array();
            $archivo = template_tags($urlplantilla2, $arreglo);
            $this->data['filas2'] = $archivo;
            return $this->data['filas2'];
        } else {
            return false;
        }
    }

    function guardar_mc_avaluo() {
//Guarda la información del auto generado o corregido por el abogado.
        $this->data['message'] = $this->session->flashdata('message');
        $post = $this->input->post();
        $detalle = unserialize($post['detalle']);
        $post['avaluos'] = $detalle['avaluos'];
        $post['avaluos'] = explode(',', $post['avaluos']);
        $post['cantidad'] = count($post['avaluos']);
        $post['user'] = $this->ion_auth->user()->row();
//guarda el documento .txt con la información del auto
        $post['ruta'] = $this->guardar_archivo();
        $info = $this->mc_avaluo_model->guardar_mc_avaluo($post);
        if ($info) {
            $idgestioncobro = $this->Traza($post['id'], $post['cod_siguiente']);
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha creado el documento </div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error/div>');
        }
    }

    function guardar_archivo() {
        $post = $this->input->post();

        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $post['id'])) {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        }
        $ruta = RUTA_DES . $post['id'] . "/" . $post['nombre'] . ".txt";
        $crea = fopen($ruta, "w"); // abrimos el archivo como escritura
        fputs($crea, $post['informacion']); // guardamos la descripcion
        fclose($crea); // Cerramos el archivo 
        return $ruta;
    }

    function documento() {
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(FALSE, FALSE, FALSE, $this->data['post']['id']);
        $this->data['consulta_doc'] = $this->mc_avaluo_model->documento_mc($this->data['post']['tipo_doc'], $this->data['post']['id']);
        $this->data['traza'] = $this->mc_avaluo_model->historico($this->data['post']); //Es el historial de las observaciones
        $this->data['nombre_documento'] = $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN'];
        $this->data['cod_oficio'] = $this->data['consulta_doc'][0]['COD_OFICIO_MC'];
        $name_file = trim(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN']);

        if (file_exists($name_file)) {
            if (filesize($name_file)) {
                $text_file = fopen($name_file, "r");
                $contet_file = fread($text_file, filesize($name_file));
                $this->data['documento'] = $contet_file;
            } else {
                $this->data['documento'] = '';
            }
        } else {
            $this->data['documento'] = '';
        }

        switch ($this->data['post']['respuesta']) {//respuesta es el codigo de respuesta actual del proceso
//PROCESOS DEL SECRETARIO
            case AUTO_AVALUOBIENES_GENERADO:
            case AUTO_DECLARA_FIRMEZA_AVALUO_G:
            case AUTO_APERTURA_PRUEBAS_G:
            case AUTO_NOMBRA_PERITO_GENERAD0:
            case AUTO_CORRECION_AVALUO_G:
            case AUTO_RESUELVE_OBJ_AVALUO_G:
            case NOTIFICACION_CORREO_GENERADA:

//PROCESOS DEL COORDINADOR
            case AUTO_NOMBRA_PERITO_PRE_APROBADO:
            case AUTO_DECLARA_FIRMEZA_AVALUO_P:
            case AUTO_APERTURA_PRUEBAS_P:
            case AUTO_AVALUOBIENES_PRE_APROBADO:
            case AUTO_CORRECION_AVALUO_P:
            case AUTO_RESUELVE_OBJ_AVALUO_P:
            case NOTIFICACION_CORREO_APROBADA:
            case NOT_PERSONAL_G:
                $this->load->view('mc_avaluo/detalle_auto', $this->data);
                $this->load->view('mc_avaluo/documento_auto', $this->data);
                break;
            case NOTIFICACION_CORREO_GENERADA:
                break;
            case NOT_PERSONAL_REVISADA_APROBADA://lista para enviarla
                $this->data['doc_notificacion'] = $this->mc_avaluo_model->documento_mc(42, $this->data['post']['id']);
//verifica cual es el tipo de inmueble para asi mostrar el documento que corresponde
                if ($this->data['consulta'][0]['COD_TIPO_INMUEBLE'] == MUEBLE) {
                    $this->data['doc_avaluo'] = $this->mc_avaluo_model->documento_mc(48, $this->data['post']['id']);
                } else if ($this->data['consulta'][0]['COD_TIPO_INMUEBLE'] == INMUEBLE) {
                    $this->data['doc_avaluo'] = $this->mc_avaluo_model->documento_mc(47, $this->data['post']['id']);
                } else if ($this->data['consulta'][0]['COD_TIPO_INMUEBLE'] == VEHICULO) {
                    $this->data['doc_avaluo'] = $this->mc_avaluo_model->documento_mc(49, $this->data['post']['id']);
                }
                if ($this->data['doc_avaluo']) {
                    $this->data['ruta_doc_avaluo'] = RUTA_DES . $this->data['post']['id'] . "/" . $this->data['doc_avaluo'][0]['RUTA_DOCUMENTO_GEN'];
                }
                $this->data['ruta_doc_notificacion'] = RUTA_DES . $this->data['post']['id'] . "/" . $this->data['doc_notificacion'][0]['RUTA_DOCUMENTO_GEN'];
                $this->data['empresa'] = $this->mc_avaluo_model->get_empresa($this->data['post']['nit']);
                $this->load->view('mc_avaluo/detalle_auto', $this->data);
                $this->load->view('mc_avaluo/notificacion_enviada', $this->data);
                break;
        }
    }

    function objecion() {
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, false, $this->data['post']['id']);
        $this->data['cabecera'] = $this->mc_avaluo_model->cabecera($this->data['post']['respuesta'], $this->data['post']['id']);
        $this->load->view('mc_avaluo/detalle_auto', $this->data);
        $this->load->view('mc_avaluo/registrar_objecion', $this->data);
    }

    function RegistrarReciboHonorarios() {
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, false, $this->data['post']['id']);
        $this->data['cabecera'] = $this->mc_avaluo_model->cabecera($this->data['post']['respuesta'], $this->data['post']['id']);
        $this->load->view('mc_avaluo/detalle_auto', $this->data);
        $this->load->view('mc_avaluo/recibo_honorarios', $this->data);
    }

    function crea_pdf() {

        $name = $this->input->post('nombre_archivo');
        $html = utf8_encode(base64_decode($this->input->post('descripcion_pdf')));
        $this->load->library("tcpdf/tcpdf");
        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        if ($print) {
            $js = '
                print();
                ';
            $pdf->IncludeJS($js);
        }
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

    function guardar_gestion_documento() {//cuando el secretario o coodinador rechazan o aprueban el documento del auto
        $post = $this->input->post();
        $this->data['message'] = $this->session->flashdata('message');
        $post['ruta'] = $this->guardar_archivo();
        $detalle = unserialize($post['detalle']);
        $post['avaluos'] = $detalle['avaluos'];
        $post['avaluos'] = explode(',', $post['avaluos']);
        $post['cantidad'] = count($post['avaluos']);
// $this->data['post'] = $post;
        $info = $this->mc_avaluo_model->guardar_gestion_documento($post);
        if ($info) {
            $idgestioncobro = $this->Traza($post['id'], $post['cod_siguiente']);

            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado la actualización del documento</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error/div>');
        }
    }

    function registro_avaluo() {
        $post = $this->input->post();
        if ($post['id']) {
            $post['titulo'] = "REGISTRO AVALUO";
            $this->data['consulta'] = $this->mc_avaluo_model->consulta_avaluos($post);
            $this->data['tipos_inmuebles'] = $this->mc_avaluo_model->get_tipoinmueble();
            $this->data['metodos_avaluos'] = $this->mc_avaluo_model->get_metodoavaluo();
            $this->data['tipos_propiedades'] = $this->mc_avaluo_model->get_tipopropiedad();
            $this->data['post']['cod_tipo_bien'] = $this->data['ruta_cancelar'] = base_url() . 'index.php/mc_avaluo/abogado';
            $this->data['post'] = $post;
            $this->data['cod_coactivo'] = $post['id'];
            $this->template->load($this->template_file, 'mc_avaluo/detalle_avaluo', $this->data);
        } else {
            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        }
    }

    function detalle_avaluo() {
        $post = $this->input->post();
        $post['titulo'] = "REGISTRO AVALUO";

        $this->data['consulta'] = $this->mc_avaluo_model->detalle_avaluo($post);
        $this->data['tipos_inmuebles'] = $this->mc_avaluo_model->get_tipoinmueble();
        $this->data['metodos_avaluos'] = $this->mc_avaluo_model->get_metodoavaluo();
        $this->data['tipos_propiedades'] = $this->mc_avaluo_model->get_tipopropiedad();
        $this->data['post']['cod_tipo_bien'] = $this->data['ruta_cancelar'] = base_url() . 'index.php/mc_avaluo/abogado';
        $this->data['post'] = $post;
        $detalle = $this->mc_avaluo_model->detalle_propiedad($post['cod_avaluo']);
        $this->data['@$detalle'] = $detalle;

        if ($post['tipo_inmueble'] == 1) {//mueble
            $this->load->view('mc_avaluo/registro_mueble', $this->data, @$detalle);
        } elseif ($post['tipo_inmueble'] == 2) {//inmueble
            $this->load->view('mc_avaluo/registro_inmueble', $this->data, @$detalle);
        } elseif ($post['tipo_inmueble'] == 3) {//vehiculo
            $this->data['tipos_vehiculos'] = $this->mc_avaluo_model->tipo_vehiculo();
            $this->load->view('mc_avaluo/registro_vehiculo', $this->data, @$detalle);
        }
    }

    function pdf($print = true) {

        $post = $this->input->post();
        $html = $post['descripcion_pdf'];
        $html = utf8_encode(base64_decode($html));
        $nombre_pdf = $post['nombre_archivo'];
        $titulo = $this->input->post('titulo_doc');
        $tipo = $this->input->post('tipo_documento');
        $data[0] =$tipo;
        $data[1] =  $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    function guardar_registro_avaluo() {
        $post = $this->input->post();
        $info = $this->mc_avaluo_model->guarda_registro_avaluo($post);
        if ($info) {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado la informacion del avalúo</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error/div>');
        }
    }

    function registro_pagohonorarios() {//visualiza formulario para indicar la recepcion del avaluo y recibo honorarios
        $this->data['post'] = $this->input->post();
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, false, $this->data['post']['id']);
        $this->data['empresa'] = $this->mc_avaluo_model->get_empresa($this->data['post']['nit']);
        $this->load->view('mc_avaluo/recibo_honorarios', $this->data);
    }

    function recibo_honorarios() {//guarda la respuesta de recepcion del avaluo y recibo de honorarios
        $this->data['post'] = $this->input->post();
        $this->data['post']['id'] = $detalle['id'];

        if ($this->data['post']['honorarios'] == 'S') {
            $this->data['post']['cod_siguiente'] = AVALUO_RECIBO_PAGO_RECIBIDO;
        } else if ($this->data['post']['honorarios'] == 'N') {
            $this->data['post']['cod_siguiente'] = AVALUO_RECIBO_PAGO_NO_RECIBIDO;
        }
        $idgestioncobro = $this->Traza($detalle['id'], $this->data['post']['cod_siguiente']);
        $info = $this->mc_avaluo_model->guarda_recibo_honorarios($this->data);
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, false, $detalle['id']);
        if ($info) {
            if ($this->data['consulta'] [0]['COD_TIPORESPUESTA'] == AVALUO_RECIBO_PAGO_RECIBIDO) {
                // $this->data['post']['tipo_doc'] = DOC_NOTIFICACIONCORREO;
                $this->data['post']['cod_siguiente'] = NOTIFICACION_CORREO_GENERADA;
                $this->data['post']['tipo_gestion'] = GESTION_AVALUO_NOTIFICACION;
                //  $this->data['traza'] = $this->mc_avaluo_model->historico($this->data); //Es el historial de las observaciones
                $this->template->load($this->template_file, 'mc_avaluo/notificacion_correo', $this->data);
            } else if ($this->data['consulta'][0]['COD_TIPORESPUESTA'] == AVALUO_RECIBO_PAGO_NO_RECIBIDO) {
                //   echo "hola2";
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado la información </div>');
                redirect(base_url() . 'index.php/mc_avaluo/abogado');
            }
        }
    }

    function guarda_notificacion_correo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->guardar_archivo();
        $file = $this->sube_documento($this->data['post']);
        $this->data['post']['ruta_recibo_avaluo'] = $file[0]['data']['file_name'];
//subir soportes
        $info = $this->mc_avaluo_model->guardar_notificacion_correo($this->data);
        if ($info) {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado la información</div>');
            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error/div>');
            redirect(base_url() . 'index.php/mc_avaluo/abogado');
        }
    }

    function corregir_auto() {
        $this->data['post'] = $this->input->post();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['consulta'] = $this->mc_avaluo_model->consulta_mcavaluo(false, false, $this->data['post']['id']);
        $this->data['consulta_doc'] = $this->mc_avaluo_model->documento_mc($this->data['post']['tipo_doc'], $this->data['post']['id']);
        $this->data['traza'] = $this->mc_avaluo_model->historico($this->data);
        $this->data['nombre_documento'] = $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN'];
        $this->data['cod_oficio'] = $this->data['consulta_doc'][0]['COD_OFICIO_MC'];
        $this->data['user'] = $this->ion_auth->user()->row();
        $name_file = trim(RUTA_DES . $this->data['post']['id'] . "/" . $this->data['consulta_doc'][0]['RUTA_DOCUMENTO_GEN']);
        if (file_exists($name_file)) {
            if (filesize($name_file)) {
                $text_file = fopen($name_file, "r");
                $contet_file = fread($text_file, filesize($name_file));
                $this->data['documento'] = $contet_file;
            } else {
                $this->data['documento'] = '';
            }
        } else {
            $this->data['documento'] = '';
        }

        switch ($this->data['post']['respuesta']) {
            case AUTO_AVALUOBIENES_RECHAZADO:
            case AUTO_APERTURA_PRUEBAS_R:
            case AUTO_CORRECION_AVALUO_R:
            case AUTO_RESUELVE_OBJ_AVALUO_R:
            case AUTO_NOMBRA_PERITO_RECHAZADO:
            case AUTO_DECLARA_FIRMEZA_AVALUO_R:
            case NOTIFICACION_PERSONAL_DEVUELTA:

                $this->data['empresa'] = $this->mc_avaluo_model->get_empresa($this->data['post']['nit']);
                $this->load->view('mc_avaluo/auto', $this->data);
                break;
            case NOTIFICACION_PERSONAL_DEVUELTA:
                $this->data['empresa'] = $this->mc_avaluo_model->get_empresa($this->data['post']['nit']);
                $this->template->load($this->template_file, 'mc_avaluo/notificacion_correo', $this->data);
                break;
        }
    }

    function adjunta_documento() {
        try {
            if ($this->ion_auth->logged_in()):
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')):
                    $post = $this->input->post();
                    $detalle = unserialize($post['detalle']);
                    $ruta_fichero = RUTA_DES . $detalle['cod_proceso'];
                    $file = $this->do_upload($ruta_fichero);
                    $detalle['nombre'] = $file['upload_data']['orig_name'];
                    $detalle['ruta'] = $ruta_fichero . "/" . $detalle['nombre'];
                    $this->data['detalle'] = $detalle;
                    $this->load->view('mc_avaluo/documentos_subidos', $this->data);
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                endif;
            else:
            endif;
        } catch (Exception $e) {
            $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución: ' . $e->getMessage() . '</div>';
        }
    }

    function crea_ruta($ruta) {
        // echo $ruta;
        if (!file_exists($ruta)) :
            if (!file_exists(RUTA_INI)):
                mkdir(RUTA_INI, 0777, true);
            endif;
            mkdir($ruta, 0777, true);
        endif;
        return $ruta;
    }

}

?>
