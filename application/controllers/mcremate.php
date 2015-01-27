<?php

class Mcremate extends MY_Controller {

    private $templates = 'uploads/templates/';
    private $docs = 'uploads/mcremate/despacho/';
    private $docs_firmados = 'uploads/mcremate/firmados/';

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library('tcpdf/Plantilla_PDF_Remisibilidad.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template', 'traza_fecha'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('mcremate_model', '', TRUE);
        $this->load->model('plantillas_model', '', TRUE);
        $this->load->model('documentospj_model', '', TRUE);
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

//Cargamos los javascript nesesarios
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/tinymce/tinymce.min.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
        );
//Cargamos las hojas de estilos nesesariass
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        define("PLANTILLA_DESPACHO_COMISORIO", 71);
        define("PLANTILLA_AUTO_DESPACHO_COMISORIO", 93);
        define("PLANTILLA_AUTO_FYH_REMATE", 94);
        define("PLANTILLA_AVISO_AUTO_FYH_REMATE", 95);
        define("PLANTILLA_ACTA_REMATE", 96);
        define("PLANTILLA_AUTO_ADJUDICACION", 97);
        define("PLANTILLA_DEVOLUCION_DINEROS", 98);
        define("PLANTILLA_IMPRUEBA_REMATE", 99);
        define("PLANTILLA_ACTO_ADJUDICA_BIEN", 100);
        define("PLANTILLA_AUTO_FIN_PROCESO", 74);
        define("PLANTILLA_AUTO_DEVOLUCION_DEUDOR", 101);
        define("PLANTILLA_DEVOLUCION_DEUDOR", 103);
        define("PLANTILLA_OFICIO_DEVOLUCION_TITULOS", 104);
        define("PLANTILLA_ACTA_ENVIO_TESORO_NACIONAL", 105);
        define("PLANTILLA_TRADICIONYLIBERTAD", 105);


        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);
        define("SECRETARIO", "41");
        define("COORDINADOR", "42");
        define("ABOGADO", "43");
        define("DIRECTOR", "61");
        define("AVALUO_FIRMADO", "421");
        define("CERTIFICADO_LIBERTADO_SOLICITADO", "491");
        define("CERTIFICADO_LIBERTAD_CORREGIDO", "916");
        define("CERTIFICADO_LIBERTAD_RECHAZADO", "915");
        define("CERTIFICADO_LIBERTAD_FIRMADO", "876");
        define("CERTIFICADO_LIBERTAD_EN_EXPEDIENTE", "917");
        define("SE_COMISIONA", "846");
        define("NO_SE_COMISIONA", "847");
        define("AUTO_COMISIONANDO_LICITACION_CREADO", "492");
        define("AUTO_COMISIONANDO_LICITACION_CORREGIDO", "895");
        define("AUTO_COMISIONANDO_LICITACION_PREAPROBADO", "493");
        define("AUTO_COMISIONANDO_LICITACION_NO_PREAPROBADO", "494");
        define("AUTO_COMISIONANDO_LICITACION_APROBADO", "495");
        define("AUTO_COMISIONANDO_LICITACION_NO_APROBADO", "896");
        define("AUTO_COMISIONANDO_LICITACION_EN_EXPEDIENTE", "918");
        define("REQUERIMIENTO_EN_DESPACHO_COMISORIO_GENERADO", "496");
        define("DESPACHO_COMISORIO_RECIBIDO", "848");
        define("DESPACHO_COMISORIO_NO_RECIBIDO", "849");
        define("DESPACHO_COMISORIO_CREADO", "496");
        define("DESPACHO_COMISORIO_RECHAZADO", "920");
        define("DESPACHO_COMISORIO_CORREGIDO", "919");
        define("DESPACHO_COMISORIO_APROBADO", "497");
        define("DESPACHO_COMISORIO_EN_EXPEDIENTE", "498");
        define("AUTO_DESPACHO_COMISORIO_CREADO", "499");
        define("AUTO_DESPACHO_COMISORIO_CORREGIDO", "921");
        define("AUTO_DESPACHO_COMISORIO_PREAPROBADO", "500");
        define("AUTO_DESPACHO_COMISORIO_NO_PREAPROBADO", "501");
        define("AUTO_DESPACHO_COMISORIO_APROBADO", "502");
        define("AUTO_DESPACHO_COMISORIO_NO_APROBADO", "922");
        define("AUTO_DESPACHO_COMISORIO_EN_EXPEDIENTE", "923");
        define("AUTO_FYH_REMATE_CREADO", "503");
        define("AUTO_FYH_REMATE_CORREGIDO", "924");
        define("AUTO_FYH_REMATE_PREAPROBADO", "504");
        define("AUTO_FYH_REMATE_NO_PREAPROBADO", "505");
        define("AUTO_FYH_REMATE_APROBADO", "506");
        define("AUTO_FYH_REMATE_NO_APROBADO", "925");
        define("AUTO_FYH_REMATE_EN_EXPEDIENTE", "926");
        define("AVISO_FYH_REMATE_CREADO", "507");
        define("AVISO_FYH_REMATE_CORREGIDO", "948");
        define("AVISO_FYH_REMATE_APROBADO", "508");
        define("AVISO_FYH_REMATE_NO_APROBADO", "509");
        define("AVISO_FYH_REMATE_SUBIDO", "949");
        define("AVISO_FYH_REMATE_COLILLA_SUBIDA", "510");
        define("ACTA_REMATE_CREADA", "512");
        define("ACTA_REMATE_LEIDA", "513");
        define("ACTA_REMATE_FIRMADA_EXPEDIENTE", "514");
        define("REMATE_DESIERTO_1", "850");
        define("REMATE_DESIERTO_2", "1013");
        define("REMATE_DESIERTO_3", "1014");
        define("REMATE_ADJUDICADO", "852");
        define("REMATE_ADJUDICADO_COMISIONADO", "851");
        define("REMATE_ADJUDICADO_NO_COMISIONADO", "853");
        define("AUTO_ADJUDICACION_CREADO", "515");
        define("AUTO_ADJUDICACION_CORREGIDO", "1015");
        define("AUTO_ADJUDICACION_PREAPROBADO", "516");
        define("AUTO_ADJUDICACION_NO_PREAPROBADO", "517");
        define("AUTO_ADJUDICACION_APROBADO", "518");
        define("AUTO_ADJUDICACION_NO_APROBADO", "1016");
        define("AUTO_ADJUDICACION_EN_EXPEDIENTE", "1017");
        define("DEVOLVER_DINERO_POSTORES", "1026");
        define("NO_DEVOLVER_DINERO_POSTORES", "1027");
        define("OFICIO_DEVOLVER_DINEROS_CREADO", "519");
        define("OFICIO_DEVOLVER_DINEROS_CORREGIDO", "1028");
        define("OFICIO_DEVOLVER_DINEROS_PREAPROBADO", "520");
        define("OFICIO_DEVOLVER_DINEROS_NO_PREAPROBADO", "521");
        define("OFICIO_DEVOLVER_DINEROS_APROBADO", "522");
        define("OFICIO_DEVOLVER_DINEROS_NO_APROBADO", "1029");
        define("OFICIO_DEVOLVER_DINEROS_EN_EXPEDIENTE", "1030");
        define("AUTO_IMPRUEBA_REMATE_CREADO", "523");
        define("AUTO_IMPRUEBA_REMATE_CORREGIDO", "1035");
        define("AUTO_IMPRUEBA_REMATE_PREAPROBADO", "524");
        define("AUTO_IMPRUEBA_REMATE_NO_PREAPROBADO", "525");
        define("AUTO_IMPRUEBA_REMATE_APROBADO", "526");
        define("AUTO_IMPRUEBA_REMATE_NO_APROBADO", "1036");
        define("AUTO_IMPRUEBA_REMATE_EN_EXPEDIENTE", "1037");
        define("CONSIGNO_SALDO", '855');
        define("NO_CONSIGNO_SALDO", '856');
        define("ACTO_ADMINISTRATIVO_ADJUDICA_CREADO", "531");
        define("ACTO_ADMINISTRATIVO_ADJUDICA_CORREGIDO", "1054");
        define("ACTO_ADMINISTRATIVO_ADJUDICA_PREAPROBADO", "532");
        define("ACTO_ADMINISTRATIVO_ADJUDICA_NO_PREAPROBADO", "533");
        define("ACTO_ADMINISTRATIVO_ADJUDICA_APROBADO", "534");
        define("ACTO_ADMINISTRATIVO_ADJUDICA_NO_APROBADO", "1055");
        define("ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE", "1056");
        define("BIEN_ADJUDICADO_ENTREGADO", "535");
        define("DEUDA_A_FAVOR_SENA", "1059");
        define("DEUDA_DEVOLVER_SENA", "1060");
        define("DEUDA_IGUAL_0", "1061");
        define("AUTO_DEVOLUCION_DEUDOR_CREADA", "527");
        define("AUTO_DEVOLUCION_DEUDOR_CORREGIDO", "1065");
        define("AUTO_DEVOLUCION_DEUDOR_PREAPROBADO", "528");
        define("AUTO_DEVOLUCION_DEUDOR_NO_PREAPROBADO", "529");
        define("AUTO_DEVOLUCION_DEUDOR_APROBADO", "530");
        define("AUTO_DEVOLUCION_DEUDOR_NO_APROBADO", "1066");
        define("AUTO_DEVOLUCION_DEUDOR_EN_EXPEDIENTE", "1067");
        define("AUTO_TERMINACION_CIERRE_PROCESO_CREADO", "536");
        define("AUTO_TERMINACION_CIERRE_PROCESO_CORREGIDO", "1062");
        define("AUTO_TERMINACION_CIERRE_PROCESO_PREAPROBADO", "537");
        define("AUTO_TERMINACION_CIERRE_PROCESO_NO_PREAPROBADO", "538");
        define("AUTO_TERMINACION_CIERRE_PROCESO_APROBADO", "539");
        define("AUTO_TERMINACION_CIERRE_PROCESO_NO_APROBADO", "1063");
        define("AUTO_TERMINACION_CIERRE_PROCESO_EN_EXPEDIENTE", "1064");
        define("FRACCIONAMIENTO_SOLICITADO", "1068");
        define("TITULOS_RECIBIDOS", "544");
        define("CITACION_DEVOLUCION_TITULOS_CREADO", "545");
        define("CITACION_DEVOLUCION_TITULOS_CORREGIDO", "1069");
        define("CITACION_DEVOLUCION_TITULOS_APROBADO", "548");
        define("CITACION_DEVOLUCION_TITULOS_NO_APROBADO", "1074");
        define("CITACION_DEVOLUCION_TITULOS_SUBIDO", "1075");
        define("SE_PRESENTO_DEUDOR", "858");
        define("NO_SE_PRESENTO_1A_VEZ", "859");
        define("NO_SE_PRESENTO_2A_VEZ", "860");
        define("OFICIO_DEVOLUCION_TITULO_CREADO", "549");
        define("OFICIO_DEVOLUCION_TITULO_CORREGIDO", "1076");
        define("OFICIO_DEVOLUCION_TITULO_PREAPROBADO", "550");
        define("OFICIO_DEVOLUCION_TITULO_RECHAZADO", "551");
        define("OFICIO_DEVOLUCION_TITULO_SUBIDO", "552");
        define("ACTA_TESORO_NACIONAL_CREADO", "553");
        define("ACTA_TESORO_NACIONAL_CORREGIDO", "1077");
        define("ACTA_TESORO_NACIONAL_PREAPROBADO", "554");
        define("ACTA_TESORO_NACIONAL_RECHAZADO", "555");
        define("ACTA_TESORO_NACIONAL_APROBADO", "556");
        define("ACTA_TESORO_NACIONAL_NO_APROBADO", "1078");
        define("ACTA_TESORO_NACIONAL_SUBIDO", "1079");
        define("ACTA_TESORO_NACIONAL_SOLICITADO", "1084");
        define("INVESTIGACION_BIENES", "204");
        define("IMPRUEBA_REMATE_FRACCIONAMIENTO", "1147");
        define("TESORO_NACIONAL_IMPRUEBA_REMATE", "1148");
        define("IMPRUEBA_REMATE_FRACCIONAMIENTO_EXPEDIENTE", "1149");
        define("SE_REALIZA_DILIGENCIA", "1168");
        define("NO_SE_REALIZA_DILIGENCIA", "1169");

        define("TIPO_1", "Certificado de Tradicion y Libertad");
        define("TIPO_2", "Auto que Comisiona para Diligencia de Remate");
        define("TIPO_3", "Requerimiento de Despacho Comisorio");
        define("TIPO_4", "Auto que incorpora el Despacho Comisorio al Expediente");
        define("TIPO_5", "Auto de Fecha y Hora del Remate");
        define("TIPO_6", "Aviso de Fecha y Hora del Remate");
        define("TIPO_7", "Acta de Remate");
        define("TIPO_8", "Oficio para devolución de Dineros a Postores");
        define("TIPO_9", "Auto de Adjudicación a favor del SENA");
        define("TIPO_10", "Acto Administrativo que Adjudica el Bien");
        define("TIPO_11", "Auto que Imprueba Remate");
        define("TIPO_12", "Auto de Devolucion al Deudor");
        define("TIPO_13", "Citación al Deudor para Devolución de Titulos");
        define("TIPO_14", "Acta de Envio de Titulos al Tesoro Nacional");
        define("TIPO_15", "Oficio de Devolucion de Titulos");
        define("TIPO_16", "Colilla de Aviso de Fecha y Hora del Remate");
        define("TIPO_17", "Fraccionamiento de Titulos");
        define("TIPO_18", "Fraccionamiento de Titulos");
    }

    /*
     * GESTION CERTIFICADO DE TRADICION Y LIBERTAD
     */

    function Crear_TradicionyLibertad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_TRADICIONYLIBERTAD, TIPO_1, CERTIFICADO_LIBERTADO_SOLICITADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_TradicionyLibertad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, CERTIFICADO_LIBERTAD_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_TradicionyLibertad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, CERTIFICADO_LIBERTAD_FIRMADO, CERTIFICADO_LIBERTAD_RECHAZADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_TradicionyLibertad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_1, CERTIFICADO_LIBERTAD_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * FORMULARIO "EL REMATE SE VA A COMISIONAR???"
     * AUTOR: CRISTIAN
     */

    function formcomirema() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_respuesta = CERTIFICADO_LIBERTAD_EN_EXPEDIENTE;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->template->load($this->template_file, 'mcremate/formcomirema', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_ComisionarRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $respuesta = $this->input->post('respuesta');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $dato['COD_AVALUO'] = $cod_avaluo;
                $dato['REMATE_COMISIONADO'] = $respuesta;
                if ($respuesta == 'S') {
                    $dato['COD_RESPUESTA'] = SE_COMISIONA;
                } else {
                    $dato['COD_RESPUESTA'] = NO_SE_COMISIONA;
                }
                $this->TrazaCoactivo($dato['COD_RESPUESTA'], $cod_coactivo, 'Gestión para Comisionar Remate');
                $this->mcremate_model->actualizar_estadoremate($dato);
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION AUTO COMISIONANDO LA LICITACION
     */

    function Crear_AutoComisionandoLicitacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_TRADICIONYLIBERTAD, TIPO_2, AUTO_COMISIONANDO_LICITACION_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoComisionandoLicitacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, AUTO_COMISIONANDO_LICITACION_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_AutoComisionandoLicitacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, AUTO_COMISIONANDO_LICITACION_PREAPROBADO, AUTO_COMISIONANDO_LICITACION_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_AutoComisionandoLicitacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, AUTO_COMISIONANDO_LICITACION_APROBADO, AUTO_COMISIONANDO_LICITACION_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoComisionandoLicitacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_2, AUTO_COMISIONANDO_LICITACION_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * DESPACHO COMISORIO RECIBIDO?
     * AUTOR:CRISTIAN
     */

    function formcomisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_respuesta = AUTO_COMISIONANDO_LICITACION_EN_EXPEDIENTE;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->template->load($this->template_file, 'mcremate/formcomisorio', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_DespachoComisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('respuesta')) {
                $respuesta = $this->input->post('respuesta');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $dato['COD_AVALUO'] = $cod_avaluo;
                if ($respuesta == 'S') {
                    $dato['COD_RESPUESTA'] = DESPACHO_COMISORIO_RECIBIDO;
                } else {
                    $dato['COD_RESPUESTA'] = DESPACHO_COMISORIO_NO_RECIBIDO;
                }
                $this->TrazaCoactivo($dato['COD_RESPUESTA'], $cod_coactivo, 'Gestión para registrar el resultado del Despacho Comisorio');
                $this->mcremate_model->actualizar_estadoremate($dato);
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTIONAR REQUERIMIENTO DE DESPACHO COMISORIO
     */

    function Crear_Requerimiento_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_DESPACHO_COMISORIO, TIPO_3, DESPACHO_COMISORIO_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Requerimiento_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_3, $cod_coactivo, DESPACHO_COMISORIO_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_Requerimiento_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_3, $cod_coactivo, DESPACHO_COMISORIO_APROBADO, DESPACHO_COMISORIO_RECHAZADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Requerimiento_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_3, DESPACHO_COMISORIO_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION DE AUTO PARA DESPACHO COMISORIO
     */

    function Crear_Auto_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_AUTO_DESPACHO_COMISORIO, TIPO_4, AUTO_DESPACHO_COMISORIO_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Auto_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_4, $cod_coactivo, AUTO_DESPACHO_COMISORIO_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_Auto_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_4, $cod_coactivo, AUTO_DESPACHO_COMISORIO_PREAPROBADO, AUTO_DESPACHO_COMISORIO_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_Auto_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_4, $cod_coactivo, AUTO_DESPACHO_COMISORIO_APROBADO, AUTO_DESPACHO_COMISORIO_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Auto_Despacho_Comisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_4, AUTO_DESPACHO_COMISORIO_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION AUTO DE FECHA Y HORA DEL REMATE
     */

    function Crear_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_AUTO_DESPACHO_COMISORIO, TIPO_5, AUTO_FYH_REMATE_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_5, $cod_coactivo, AUTO_FYH_REMATE_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_5, $cod_coactivo, AUTO_FYH_REMATE_PREAPROBADO, AUTO_FYH_REMATE_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_5, $cod_coactivo, AUTO_FYH_REMATE_APROBADO, AUTO_FYH_REMATE_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_5, AUTO_FYH_REMATE_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION AUTO DE FECHA Y HORA DEL REMATE
     */

    function Crear_Aviso_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_AVISO_AUTO_FYH_REMATE, TIPO_6, AVISO_FYH_REMATE_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Aviso_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_6, $cod_coactivo, AVISO_FYH_REMATE_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_Aviso_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_6, $cod_coactivo, AVISO_FYH_REMATE_APROBADO, AVISO_FYH_REMATE_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Aviso_Auto_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_6, AVISO_FYH_REMATE_SUBIDO, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Colilla_Aviso_FechaHora_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_16, AVISO_FYH_REMATE_COLILLA_SUBIDA, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * REGISTRAR POSTURAS
     */

    function Registrar_Posturas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo') || $this->input->post('identificacion_postura')) {
                $cod_respuesta = $this->input->post('cod_respuesta');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                if ($cod_respuesta != '') {
                    /*
                     * ACTUALIZAR EL ESTADO
                     */
                    $datos2["COD_AVALUO"] = $cod_avaluo;
                    $datos2["COD_RESPUESTA"] = $cod_respuesta;
                    $this->mcremate_model->actualizar_estadoremate($datos2);
                    /*
                     * INSERTAR TRAZA
                     */
                    $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Se Agregaron Posturas al Remate');
                    redirect(base_url() . 'index.php/bandejaunificada');
                } else
                if ($this->input->post('nombre_postura') == '') {
                    $encabezado = $this->documentospj_model->cabecera($cod_coactivo, AVISO_FYH_REMATE_COLILLA_SUBIDA);
                    $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                    $this->data['encabezado'] = $encabezado;
                    $this->data['info_avaluo'] = $info_avaluo;
                    $this->data['cod_avaluo'] = $cod_avaluo;
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->template->load($this->template_file, 'mcremate/crearposturas_add', $this->data);
                } else {
                    $cod_avaluo = $this->input->post('cod_avaluo');
                    $datos["COD_AVALUO"] = $cod_avaluo;
                    $datos["NOMBRE"] = $this->input->post('nombre_postura');
                    $datos["NUMERO_IDENTIFICACION"] = $this->input->post('identificacion_postura');
                    $datos["FECHA"] = $this->input->post('fecha_postura');
                    $datos["TITULO_DEPOSITO"] = $this->input->post('titulo_deposito');
                    $datos["VALOR_TITULO"] = $this->input->post('valor_titulo');
                    $datos["VALOR_POSTURA"] = $this->input->post('valor_postura');
                    $datos["COMENTARIOS"] = $this->input->post('comentarios');
                    $this->mcremate_model->insertar_postura_remate($datos);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION ACTA DE REMATE
     */

    function Crear_Acta_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_ACTA_REMATE, TIPO_7, ACTA_REMATE_CREADA, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Acta_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_7, $cod_coactivo, ACTA_REMATE_CREADA, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Leer_Acta_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_7, $cod_coactivo, ACTA_REMATE_LEIDA, ACTA_REMATE_CREADA, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Acta_Remate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_7, ACTA_REMATE_FIRMADA_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION PARA REVISAR SI SE HIZO O NO EL REMATE
     */

    function Form_VerificacionDiligencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, AVISO_FYH_REMATE_COLILLA_SUBIDA);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->template->load($this->template_file, 'mcremate/formdiligencia', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION RESULTADO REMATE - REULTADO DE LA DILIGENCIA
     */

    function Form_ResultadoRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, AVISO_FYH_REMATE_COLILLA_SUBIDA);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->template->load($this->template_file, 'mcremate/formresultado', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION OFICIO PARA DEVOLVER DINEROS A POSTORES
     */

    function Crear_OficioPostores() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_DEVOLUCION_DEUDOR, TIPO_8, OFICIO_DEVOLVER_DINEROS_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_OficioPostores() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_8, $cod_coactivo, OFICIO_DEVOLVER_DINEROS_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_OficioPostores() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_8, $cod_coactivo, OFICIO_DEVOLVER_DINEROS_PREAPROBADO, OFICIO_DEVOLVER_DINEROS_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_OficioPostores() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_8, $cod_coactivo, OFICIO_DEVOLVER_DINEROS_APROBADO, OFICIO_DEVOLVER_DINEROS_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_OficioPostores() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_8, OFICIO_DEVOLVER_DINEROS_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_ResultadoRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $respuesta = $this->input->post('respuesta');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                if ($respuesta == REMATE_ADJUDICADO) {
                    $comisiono = $this->mcremate_model->get_avaluocomisiono($cod_avaluo);
                    $info_comisiono = $comisiono['REMATE_COMISIONADO'];
                    $posturas = $this->mcremate_model->get_posturas($cod_avaluo);
                    $cantidad_posturas = sizeof($posturas[0]);
                    if ($info_comisiono == 'S') {
                        $dato['COD_RESPUESTA'] = REMATE_ADJUDICADO_COMISIONADO;
                    } else {
                        if ($cantidad_posturas > 1) {
                            $dato['COD_RESPUESTA'] = SE_REALIZA_DILIGENCIA;
                        } else {
                            $dato['COD_RESPUESTA'] = NO_DEVOLVER_DINERO_POSTORES;
                        }
                    }
                } else if ($respuesta == NO_SE_REALIZA_DILIGENCIA || $respuesta == SE_REALIZA_DILIGENCIA) {
                    $dato['COD_RESPUESTA'] = $respuesta;
                    $dato_postura["COD_AVALUO"] = $cod_avaluo;
                    $dato_postura["ESTADO_POSTOR"] = 'N';
                    $this->mcremate_model->actualizacion_posturas($dato_postura);
                } else {
                    $info_cantidad = $this->mcremate_model->get_cantidaddesierto($cod_avaluo);
                    $cantidad = $info_cantidad['REMATE_DESIERTO'];
                    $cantidad++;
                    if ($cantidad <= 1) {
                        $dato['COD_RESPUESTA'] = REMATE_DESIERTO_1;
                        $dato['REMATE_DESIERTO'] = $cantidad;
                    } else if ($cantidad == 2) {
                        $dato['COD_RESPUESTA'] = REMATE_DESIERTO_2;
                        $dato['REMATE_DESIERTO'] = $cantidad;
                    } else if ($cantidad >= 3) {
                        $dato['COD_RESPUESTA'] = REMATE_DESIERTO_3;
                        $dato['REMATE_DESIERTO'] = $cantidad;
                    }
                }
                /*
                 * ACTUALIZAR EL ESTADO
                 */
                $dato["COD_AVALUO"] = $cod_avaluo;
                $this->mcremate_model->actualizar_estadoremate($dato);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($dato['COD_RESPUESTA'], $cod_coactivo, 'Se Agregaron Posturas al Remate');
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION AUTO DE AJUDICACION A FAVOR DEL SENA
     */

    function Crear_AutoAdjudicacionSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_DEVOLUCION_DEUDOR, TIPO_9, AUTO_ADJUDICACION_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoAdjudicacionSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_9, $cod_coactivo, AUTO_ADJUDICACION_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_AutoAdjudicacionSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_9, $cod_coactivo, AUTO_ADJUDICACION_PREAPROBADO, AUTO_ADJUDICACION_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_AutoAdjudicacionSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_9, $cod_coactivo, AUTO_ADJUDICACION_APROBADO, AUTO_ADJUDICACION_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoAdjudicacionSena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_9, AUTO_ADJUDICACION_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * FORMULARIO PARA APROBAR QUE EL POSTOR CONSIGNO LOS DINEROS
     */

    function Form_ConsignacionDinero() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, AVISO_FYH_REMATE_COLILLA_SUBIDA);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->data['posturas'] = $this->mcremate_model->get_posturas($cod_avaluo);
                $this->template->load($this->template_file, 'mcremate/formconsignaciondinero', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_ConsignacionDinero() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $respuesta = $this->input->post('consignacion');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_postura = $this->input->post('optionsRadios');
                $remate = $this->mcremate_model->get_avaluoremate($cod_avaluo);
                $cod_remate = $remate['COD_REMATE'];
                $dato['COD_REMATE'] = $cod_remate;
                $dato['COD_RESPUESTA'] = $respuesta;
                $dato_P['COD_AVALUO'] = $cod_avaluo;
                $dato_P['NUMERO_IDENTIFICACION'] = $cod_postura;
                $file = $this->do_multi_upload($cod_coactivo, "recibos_consignacion"); //1- pdf y archivos de imagen
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $posturas = $this->mcremate_model->get_posturas($cod_avaluo);
                    if (empty($posturas)) {
                        $datos_exp['COD_RESPUESTAGESTION'] = $respuesta;
                        $datos_exp["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                        $datos_exp["ID_USUARIO"] = COD_USUARIO;
                        $datos_exp['COD_TIPO_EXPEDIENTE'] = 9;
                        $datos_exp['SUB_PROCESO'] = 'Consignación de Dineros del Remate';
                        $datos_exp["RUTA_DOCUMENTO"] = "uploads/mcremate/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                        $datos_exp["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                        $this->documentospj_model->insertar_expediente($datos_exp);
                    } else {
                        $dato_P["RECIBO_CONSIGNACION"] = $file[$i]["upload_data"]["file_name"];
                        $dato_P["BIEN_ADJUDICADO"] = 'S';
                        $this->mcremate_model->actualizar_postura($dato_P);
                    }
                }
                /*
                 * ACTUALIZAR EL ESTADO
                 */
                $datos2["COD_AVALUO"] = $cod_avaluo;
                $datos2["COD_RESPUESTA"] = $respuesta;
                $this->mcremate_model->actualizar_estadoremate($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($respuesta, $cod_coactivo, 'Consignación de Dineros del Remate');
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION DE ACTO ADMINISTRATIVO QUE ADJUDICA EL BIEN
     */

    function Crear_ActoAdministrativoAdjudicaBien() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_ACTO_ADJUDICA_BIEN, TIPO_10, ACTO_ADMINISTRATIVO_ADJUDICA_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_ActoAdministrativoAdjudicaBien() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_10, $cod_coactivo, ACTO_ADMINISTRATIVO_ADJUDICA_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_ActoAdministrativoAdjudicaBien() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_10, $cod_coactivo, ACTO_ADMINISTRATIVO_ADJUDICA_PREAPROBADO, ACTO_ADMINISTRATIVO_ADJUDICA_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_ActoAdministrativoAdjudicaBien() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_10, $cod_coactivo, ACTO_ADMINISTRATIVO_ADJUDICA_APROBADO, ACTO_ADMINISTRATIVO_ADJUDICA_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ActoAdministrativoAdjudicaBien() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_10, ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION OFICIO PARA DEVOLVER DINEROS A POSTORES
     */

    function Crear_AutoImpruebaRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_IMPRUEBA_REMATE, TIPO_11, AUTO_IMPRUEBA_REMATE_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoImpruebaRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_11, $cod_coactivo, AUTO_IMPRUEBA_REMATE_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_AutoImpruebaRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_11, $cod_coactivo, AUTO_IMPRUEBA_REMATE_PREAPROBADO, AUTO_IMPRUEBA_REMATE_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_AutoImpruebaRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_11, $cod_coactivo, AUTO_IMPRUEBA_REMATE_APROBADO, AUTO_IMPRUEBA_REMATE_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoImpruebaRemate() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_11, AUTO_IMPRUEBA_REMATE_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION VISUALIZAR SALDO
     */

    function Form_VisualizarSaldo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $deuda = $this->mcremate_model->saldo_deudacoactivo($cod_coactivo, DEUDA_A_FAVOR_SENA);
                $saldo = $deuda['SALDO_DEUDA'];
                $this->data['valor'] = $saldo;
                $this->template->load($this->template_file, 'mcremate/formsaldodeudor', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_VisualizarSaldo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $respuesta = $this->input->post('respuesta');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                /*
                 * ACTUALIZAR EL ESTADO
                 */
                $datos2["COD_AVALUO"] = $cod_avaluo;
                $datos2["COD_RESPUESTA"] = $respuesta;
                $this->mcremate_model->actualizar_estadoremate($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $cod_gestioncobro = $this->TrazaCoactivo($respuesta, $cod_coactivo, 'Consulta de saldo de los titulos');
                if ($respuesta == DEUDA_A_FAVOR_SENA) {
                    $dato_inv["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $dato_inv["COD_RESPUESTAGESTION"] = INVESTIGACION_BIENES;
                    $dato_inv["BLOQUEO"] = 0;
                    $dato_inv["COD_GESTIONCOBRO"] = $cod_gestioncobro;
                    $dato_inv["GENARADO_POR"] = COD_USUARIO;
                    $this->mcremate_model->insertar_investigacion_bienes($dato_inv);
                }
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION AUTO DEVOLUCION AL DEUDOR
     */

    function Crear_AutoDevolucionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_DEVOLUCION_DEUDOR, TIPO_12, AUTO_DEVOLUCION_DEUDOR_CREADA, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoDevolucionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_12, $cod_coactivo, AUTO_DEVOLUCION_DEUDOR_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_AutoDevolucionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_12, $cod_coactivo, AUTO_DEVOLUCION_DEUDOR_PREAPROBADO, AUTO_DEVOLUCION_DEUDOR_NO_PREAPROBADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_AutoDevolucionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_12, $cod_coactivo, AUTO_DEVOLUCION_DEUDOR_APROBADO, AUTO_DEVOLUCION_DEUDOR_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoDevolucionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_12, AUTO_DEVOLUCION_DEUDOR_EN_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION FRACCIONAMIENTO DE TITULOS
     */

    function Form_FraccionarTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['respuesta'] = FRACCIONAMIENTO_SOLICITADO;
                $remate = $this->mcremate_model->get_avaluoremate($cod_avaluo);
                $cod_remate = $remate['COD_REMATE'];
                if ($this->mcremate_model->get_remateimprobado($cod_remate)) {
                    $this->data['Persona'] = 'Postor';
                    $this->data['Entidad'] = 'Tesoro Nacional';
                } else {
                    $this->data['Persona'] = 'Deudor';
                    $this->data['Entidad'] = 'SENA';
                }
                $this->template->load($this->template_file, 'mcremate/crearfraccionamientos_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_FraccionarTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $monto_1 = $this->input->post('monto_sena');
                $titulo_1 = $this->input->post('titulo_sena');
                $monto_2 = $this->input->post('monto_deudor');
                $titulo_2 = $this->input->post('titulo_deudor');
                $fra_SENA['COD_AVALUO'] = $cod_avaluo;
                $fra_SENA['MONTO_TITULO'] = $monto_1;
                $fra_SENA['NUMERO_TITULO'] = $titulo_1;
                $fra_SENA['TITULOS_RECIBIDOS'] = 1;
                $this->mcremate_model->insertar_fraccionamiento_remate($fra_SENA);
                $fra_DEUDOR['COD_AVALUO'] = $cod_avaluo;
                $fra_DEUDOR['MONTO_TITULO'] = $monto_2;
                $fra_DEUDOR['NUMERO_TITULO'] = $titulo_2;
                $fra_DEUDOR['TITULOS_RECIBIDOS'] = 2;
                $this->mcremate_model->insertar_fraccionamiento_remate($fra_DEUDOR);
                $remate = $this->mcremate_model->get_avaluoremate($cod_avaluo);
                $respuesta = $this->input->post('respuesta');
                $cod_remate = $remate['COD_REMATE'];
                $dato['COD_AVALUO'] = $cod_avaluo;
                if ($this->mcremate_model->get_remateimprobado($cod_remate)) {
                    $dato['COD_RESPUESTA'] = IMPRUEBA_REMATE_FRACCIONAMIENTO;
                } else {
                    $dato['COD_RESPUESTA'] = $respuesta;
                }
                /*
                 * ACTUALIZAR EL ESTADO
                 */
                $this->mcremate_model->actualizar_estadoremate($dato);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($dato['COD_RESPUESTA'], $cod_coactivo, 'Consulta de saldo de los titulos');
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_FraccionamientoTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_17, TITULOS_RECIBIDOS, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ImpruebaRemateFraccionamiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_12, IMPRUEBA_REMATE_FRACCIONAMIENTO_EXPEDIENTE, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION CITACION DEVOLUCION AL DEUDOR 
     */

    function Crear_Citacion_Devolucion_Deudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_DEVOLUCION_DEUDOR, TIPO_13, CITACION_DEVOLUCION_TITULOS_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Citacion_Devolucion_Deudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_13, $cod_coactivo, CITACION_DEVOLUCION_TITULOS_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_Citacion_Devolucion_Deudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_13, $cod_coactivo, CITACION_DEVOLUCION_TITULOS_APROBADO, CITACION_DEVOLUCION_TITULOS_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_Citacion_Devolucion_Deudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_13, CITACION_DEVOLUCION_TITULOS_SUBIDO, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION PRESENTACION DEL DEUDOR
     */
    /*
     * FORMULARIO PARA APROBAR QUE EL POSTOR CONSIGNO LOS DINEROS
     */

    function Form_PresentacionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $this->data['posturas'] = $this->mcremate_model->get_posturas($cod_avaluo);
                $this->template->load($this->template_file, 'mcremate/formpresentacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_PresentacionDeudor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $respuesta = $this->input->post('respuesta');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $remate = $this->mcremate_model->get_avaluoremate($cod_avaluo);
                $cod_remate = $remate['COD_REMATE'];
                if ($respuesta == NO_SE_PRESENTO_1A_VEZ) {
                    $cantidad_remate = $this->mcremate_model->get_presentaciondeudor($cod_remate);
                    $cant_presentacion = $cantidad_remate['PRESENTACION_DEUDOR'];
                    $cant_presentacion++;
                    if ($cant_presentacion == 2) {
                        $respuesta = NO_SE_PRESENTO_2A_VEZ;
                        $fecha = $this->input->post('fecha');
                        $dato['FECHA_PRESENTACION'] = $fecha;
                    }
                }
                /*
                 * ACTUALIZAR EL ESTADO
                 */
                $dato['PRESENTACION_DEUDOR'] = $cant_presentacion;
                $dato['COD_AVALUO'] = $cod_avaluo;
                $dato['COD_RESPUESTA'] = $respuesta;
                $this->mcremate_model->actualizar_estadoremate($dato);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($dato['COD_RESPUESTA'], $cod_coactivo, 'Consulta de saldo de los titulos');
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * FORMULARIO PARA APROBAR QUE EL POSTOR CONSIGNO LOS DINEROS
     */

    function Form_EsperaPresentacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $encabezado = $this->documentospj_model->cabecera($cod_coactivo, NO_SE_PRESENTO_2A_VEZ);
                $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
                $this->data['encabezado'] = $encabezado;
                $this->data['info_avaluo'] = $info_avaluo;
                $citacion = $this->mcremate_model->get_fechacitacion($cod_avaluo);
                $fecha_citacion = $citacion['FECHA_PRESENTACION'];
                $fecha = new DateTime($fecha_citacion);
                $fecha->add(new DateInterval('P3Y'));
                $this->data['fecha_aprobar'] = $fecha->format('Y/m/d') . "\n";
                $this->template->load($this->template_file, 'mcremate/formesppresentacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_EsperaPresentacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $respuesta = $this->input->post('respuesta');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                /*
                 * ACTUALIZAR EL ESTADO
                 */
                $dato['COD_AVALUO'] = $cod_avaluo;
                $dato['COD_RESPUESTA'] = $respuesta;
                $this->mcremate_model->actualizar_estadoremate($dato);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($dato['COD_RESPUESTA'], $cod_coactivo, 'Consulta de saldo de los titulos');
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION ACTA DE ENVIO AL TESORO NACIONAL
     */

    function Crear_ActaTesoroNacional() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_ACTA_ENVIO_TESORO_NACIONAL, TIPO_14, ACTA_TESORO_NACIONAL_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_ActaTesoroNacional() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_14, $cod_coactivo, ACTA_TESORO_NACIONAL_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_ActaTesoroNacional() {
        if ($this->ion_auth->loggedPreaprobar_AutoDevolucionDeudor_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_14, $cod_coactivo, ACTA_TESORO_NACIONAL_PREAPROBADO, ACTA_TESORO_NACIONAL_RECHAZADO, 'mcremate/preaprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_ActaTesoroNacional() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_14, $cod_coactivo, ACTA_TESORO_NACIONAL_APROBADO, ACTA_TESORO_NACIONAL_NO_APROBADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ActaTesoroNacional() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_14, ACTA_TESORO_NACIONAL_SUBIDO, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * GESTION CITACION DEVOLUCION AL DEUDOR 
     */

    function Crear_OficioDevolucionTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_avaluo, PLANTILLA_OFICIO_DEVOLUCION_TITULOS, TIPO_15, OFICIO_DEVOLUCION_TITULO_CREADO, 'mcremate/elaborardocumento_add', $cod_coactivo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_OficioDevolucionTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_avaluo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_15, $cod_coactivo, OFICIO_DEVOLUCION_TITULO_CORREGIDO, 0, 'mcremate/corregirdocumentoabo_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_OficioDevolucionTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_15, $cod_coactivo, OFICIO_DEVOLUCION_TITULO_PREAPROBADO, OFICIO_DEVOLUCION_TITULO_RECHAZADO, 'mcremate/aprobardocumento_add', $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_OficioDevolucionTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_15, OFICIO_DEVOLUCION_TITULO_SUBIDO, $cod_avaluo);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * FUNCION PARAMETRIZADA PARA CREAR CUALQUIER DOCUMENTO
     */

    function Crear_Documentos($cod_avaluo, $cod_plantilla, $tipo, $cod_respuesta, $ruta, $cod_coactivo) {
        if ($this->ion_auth->logged_in()) {
            /*
             * TRER EL CODIGO DEL PROCESO PARA VISUALIZAR
             */
            $consecutivo = $this->documentospj_model->get_numprocesoadjudicado($cod_coactivo);
            $cod_proceso = $consecutivo['COD_PROCESOPJ'];
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR SECRETARIOS PARA ASIGNAR
             * CONSULTAR PLANTILLA
             */
            $this->data['cod_avaluo'] = $cod_avaluo;
            $encabezado = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta);
            $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
            $this->data['secretario'] = $this->documentospj_model->get_secretario_regional();
            $this->data['informacion'] = $this->plantillas_model->plantillas($cod_plantilla);
            /*
             * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
             */
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
            $this->data['encabezado'] = $encabezado;
            $this->data['info_avaluo'] = $info_avaluo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_avaluo'] = $cod_avaluo;
            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->data['cod_proceso'] = $cod_proceso;
            $this->template->set('title', 'Jurisdicción Coactiva -> Medidas Cautelares - Remate');
            $this->template->load($this->template_file, $ruta, $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Proceso_Documento($tipo, $cod_coactivo, $cod_respuesta1, $cod_respuesta2, $ruta, $cod_avaluo) {
        if ($this->ion_auth->logged_in()) {
            /*
             * TRER EL CODIGO DEL PROCESO PARA VISUALIZAR
             */
            $consecutivo = $this->documentospj_model->get_numprocesoadjudicado($cod_coactivo);
            $cod_proceso = $consecutivo['COD_PROCESOPJ'];
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR DIRECTOR REGIONAL O COORDINADOR ASIGNADO
             * CONSULTAR PLANTILLA URL PLANTILLA
             */
            $encabezado = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta1);
            $info_avaluo = $this->mcremate_model->detalle_avaluo($cod_avaluo, $cod_coactivo);
            $this->data['documento'] = $this->documentospj_model->get_plantilla_procesoavaluo($cod_avaluo);
            /*
             * TRAER COMENTARIOS Y SUS ACTORES
             */
            $this->data['comentario'] = $this->documentospj_model->get_comentarios_avaluo($cod_avaluo);
            /*
             * ENVIO DE DATOS VISUALIZACION DE VISTA
             */
            $abogado = $this->documentospj_model->get_abogado_avaluo($cod_avaluo);
            $secretario = $this->documentospj_model->get_secretario_avaluo($cod_avaluo);
            $coordinador = $this->documentospj_model->get_coordinador_avaluo($cod_avaluo);
            $this->data['cod_avaluo'] = $cod_avaluo;
            $this->data['info_avaluo'] = $info_avaluo;
            $this->data['nombre_a'] = $abogado['NOMBRES'] . " " . $abogado['APELLIDOS'];
            $this->data['nombre_s'] = $secretario['NOMBRES'] . " " . $secretario['APELLIDOS'];
            $this->data['nombre_c'] = $coordinador['NOMBRES'] . " " . $coordinador['APELLIDOS'];
            $this->data['secretario'] = $this->data['documento']['SECRETARIO'];
            $this->data['coordinador'] = $this->data['documento']['EJECUTOR'];
            $this->data['abogado'] = $this->data['documento']['ABOGADO'];
            $this->data['titulo_encabezado'] = $this->data['documento']['TITULO_ENCABEZADO'];
            $this->data['asignado'] = $this->documentospj_model->get_director_coordinador_regional();
            /*
             * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
             */
            $urlplantilla2 = "uploads/mcremate/" . $cod_avaluo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            /*
             * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
             */
            if (file_exists($urlplantilla2)) {
                $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
            } else {
                $this->data['filas2'] = '';
            }
            $this->data['encabezado'] = $encabezado;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'][0] = $cod_respuesta1;
            $this->data['cod_respuesta'][1] = $cod_respuesta2;
            $this->data['cod_proceso'] = $cod_proceso;
            $this->template->set('title', 'Jurisdicción Coactiva -> Medidas Cautelares - Remate');
            $this->template->load($this->template_file, $ruta, $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta, $cod_avaluo) {
        if ($this->ion_auth->logged_in()) {
            /*
             * CONSULTAR DOCUMENTO PARA IMPRIMIR
             */
            $documento = $this->documentospj_model->get_plantilla_procesoavaluo($cod_avaluo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/mcremate/" . $cod_avaluo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Medidas Cautelares - Remate');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->data['cod_avaluo'] = $cod_avaluo;
            $this->template->load($this->template_file, 'mcremate/subirexpediente_add', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Soporte_Expediente() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_coactivo')) {
                /*
                 * RECIBIR DATOS
                 */
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $fecha = $this->input->post('fecha');
                $numero = $this->input->post('numero');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                $datos['FECHA_RADICADO'] = $fecha;
                $datos['NUMERO_RADICADO'] = $numero;
                $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos["ID_USUARIO"] = COD_USUARIO;
                /*
                 * OBTENER NOMBRE DEL ARCHIVO
                 */
                $file = $this->do_multi_upload($cod_coactivo, "Expediente"); //1- pdf y archivos de imagen
                switch ($cod_respuesta) {
                    case CERTIFICADO_LIBERTAD_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_1;
                        break;
                    case AUTO_COMISIONANDO_LICITACION_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_2;
                        break;
                    case DESPACHO_COMISORIO_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_3;
                        break;
                    case AUTO_DESPACHO_COMISORIO_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_4;
                        break;
                    case AUTO_FYH_REMATE_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_5;
                        break;
                    case AVISO_FYH_REMATE_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_6;
                        break;
                    case ACTA_REMATE_FIRMADA_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_7;
                        break;
                    case OFICIO_DEVOLVER_DINEROS_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 4;
                        $datos['SUB_PROCESO'] = TIPO_8;
                        break;
                    case AUTO_ADJUDICACION_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_9;
                        break;
                    case ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_10;
                        break;
                    case AUTO_IMPRUEBA_REMATE_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_11;
                        break;
                    case AUTO_DEVOLUCION_DEUDOR_EN_EXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_12;
                        break;
                    case CITACION_DEVOLUCION_TITULOS_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 7;
                        $datos['SUB_PROCESO'] = TIPO_13;
                        break;
                    case ACTA_TESORO_NACIONAL_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_14;
                        break;
                    case OFICIO_DEVOLUCION_TITULO_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_15;
                        break;
                    case AVISO_FYH_REMATE_COLILLA_SUBIDA:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_16;
                        break;
                    case TITULOS_RECIBIDOS:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_17;
                        break;
                    case OFICIO_DEVOLUCION_TITULO_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 5;
                        $datos['SUB_PROCESO'] = TIPO_17;
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SSE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/mcremate/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->documentospj_model->insertar_expediente($datos);
                }
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Medidas Cautelares - Remate');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'mcremate/comprobacionexp_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function VistaPrevia_Documento() {
        if ($this->ion_auth->logged_in()) {
            $cod_avaluo = $this->input->post('cod_avaluo');
            $documento = $this->documentospj_model->get_plantilla_procesoavaluo($cod_avaluo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/mcremate/" . $cod_avaluo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('mcremate/visualizardocumento_add', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_EliminarSoporte() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_soporte')) {
                /*
                 * Recibir los Datos
                 */
                $cod_soporte = $this->input->post('cod_soporte');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /*
                 * Eliminar el Archivo de la BD y del server
                 */
                $soporte[0] = $cod_coactivo;
                $soporte[1] = $cod_respuesta;
                $info_soporte = $this->documentospj_model->get_documentosexpediente($soporte);
                $estructura = "./" . $info_soporte[0]["RUTA_DOCUMENTO"];
                if (file_exists($estructura)) {
                    unlink($estructura);
                }
                $this->documentospj_model->eliminar_soporte($cod_soporte);
                /*
                 * VOLVER A VER LOS DOCUMENTOS
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Prescripción de Titulos');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_avaluo'] = $cod_avaluo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                if (sizeof($this->data['archivos']) == 0) {
                    switch ($cod_respuesta) {
                        case CERTIFICADO_LIBERTAD_EN_EXPEDIENTE:
                            $tipo = TIPO_1;
                            break;
                        case AUTO_COMISIONANDO_LICITACION_EN_EXPEDIENTE:
                            $tipo = TIPO_2;
                            break;
                        case DESPACHO_COMISORIO_EN_EXPEDIENTE:
                            $tipo = TIPO_3;
                            break;
                        case AUTO_DESPACHO_COMISORIO_EN_EXPEDIENTE:
                            $tipo = TIPO_4;
                            break;
                        case AUTO_FYH_REMATE_EN_EXPEDIENTE:
                            $tipo = TIPO_5;
                            break;
                        case AVISO_FYH_REMATE_SUBIDO:
                            $tipo = TIPO_6;
                            break;
                        case ACTA_REMATE_FIRMADA_EXPEDIENTE:
                            $tipo = TIPO_7;
                            break;
                        case OFICIO_DEVOLVER_DINEROS_EN_EXPEDIENTE:
                            $tipo = TIPO_8;
                            break;
                        case AUTO_ADJUDICACION_EN_EXPEDIENTE:
                            $tipo = TIPO_9;
                            break;
                        case ACTO_ADMINISTRATIVO_ADJUDICA_EN_EXPEDIENTE:
                            $tipo = TIPO_10;
                            break;
                        case AUTO_IMPRUEBA_REMATE_EN_EXPEDIENTE:
                            $tipo = TIPO_11;
                            break;
                        case AUTO_DEVOLUCION_DEUDOR_EN_EXPEDIENTE:
                            $tipo = TIPO_12;
                            break;
                        case CITACION_DEVOLUCION_TITULOS_SUBIDO:
                            $tipo = TIPO_13;
                            break;
                        case ACTA_TESORO_NACIONAL_SUBIDO:
                            $tipo = TIPO_14;
                            break;
                        case OFICIO_DEVOLUCION_TITULO_SUBIDO:
                            $tipo = TIPO_15;
                            break;
                        case AVISO_FYH_REMATE_COLILLA_SUBIDA:
                            $tipo = TIPO_16;
                            break;
                        case TITULOS_RECIBIDOS:
                            $tipo = TIPO_17;
                            break;
                        case OFICIO_DEVOLUCION_TITULO_SUBIDO:
                            $tipo = TIPO_17;
                            break;
                    }
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta, $cod_avaluo);
                } else {
                    $this->template->load($this->template_file, 'mcremate/comprobacionexp_add', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestion_EliminarPlantillas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                /*
                 * Recibir los Datos
                 */
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_avaluo = $this->input->post('cod_avaluo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /* |
                 * Eliminar el Archivo de la BD y del server
                 */
                $plantillas = $this->documentospj_model->get_plantillasavaluo($cod_avaluo);
                for ($i = 0; $i < sizeof($plantillas); $i++) {
                    $estructura = "./uploads/mcremate/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->documentospj_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                /*
                 * ACTUALIZAR EL ESTADO DEL TRASLADO
                 */
                $datos2["COD_AVALUO"] = $cod_avaluo;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->mcremate_model->actualizar_estadoremate($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Insertar Documento de Medida Cautelar Remate al Expediente');
                redirect(base_url() . 'index.php/bandejaunificada');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function insertar_comunicacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('estado')) {
                /*
                 * INSERTAR EN TABLA COMUNICACIONES
                 */
                $datos["COD_AVALUO"] = $this->input->post('cod_avaluo');
                $datos["COD_RESPUESTA"] = $this->input->post('estado');
                $datos["NOMBRE_DOCUMENTO"] = $this->input->post('nombre_documento');
                $datos["ABOGADO"] = $this->input->post('abogado');
                $datos["SECRETARIO"] = $this->input->post('secretario');
                $datos["EJECUTOR"] = $this->input->post('coordinador');
                $datos["COMENTADO_POR"] = COD_USUARIO;
                $datos["COMENTARIO"] = $this->input->post('comentarios');
                $datos["TITULO_ENCABEZADO"] = $this->input->post('encabezado');
                $this->documentospj_model->insertar_comunicacion($datos);

                /*
                 * ACTUALIZAR REMATE
                 */
                $datos2["COD_AVALUO"] = $this->input->post('cod_avaluo');
                $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                $this->mcremate_model->actualizar_estadoremate($datos2);

                /*
                 * INSERTAR TRAZA
                 */
                $cod_respuesta = $this->input->post('estado');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, $this->input->post('comentarios'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;

        $estructura = "uploads/mcremate/" . $post['cod_avaluo'] . "/";
        if (!file_exists($estructura)) {
            if (!mkdir($estructura, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $ar = fopen($estructura . $post['nombre'] . ".txt", "w+") or die();
        if (!empty($post['informacion']))
            fputs($ar, $post['informacion']);
        else
            fputs($ar, $post['infor']);
        fclose($ar);
    }

    function pdf() {
        $html = $this->input->post('html');
        $nombre_pdf = $this->input->post('nombre');
        $titulo = $this->input->post('titulo');
        $tipo = $this->input->post('tipo');
        $cod_coactivo = $this->input->post('cod_coactivo');
        $data[0] = $tipo;
        $data[1] = $titulo;
        $data[2] = $cod_coactivo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    private function do_multi_upload($cod_coactivo, $carpeta) {
        $estructura = "./uploads/mcremate/" . $cod_coactivo . "/" . $carpeta . "/";
        if (!file_exists($estructura)) {
            if (!mkdir($estructura, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $config = array();
        $config['upload_path'] = $estructura;
        $config['allowed_types'] = '*';
        $config['max_size'] = '5048';
        $this->load->library('upload', $config);
        $files = $_FILES;
        if (sizeof($files) == 0) {
            return false;
        }
        $cpt = count($_FILES['userfile']['name']);
        for ($i = 0; $i < $cpt; $i++) {
            $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
            $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
            $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
            $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
            $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
            if (!$this->upload->do_upload()) {
                return $error = array('error' => $this->upload->display_errors());
            } else {
                $data[$i] = array('upload_data' => $this->upload->data());
            }
        }
        return $data;
    }

    private function cambio_moneda($valor) {
        $valor = str_split($valor);
        $contador = 0;
        $moneda = "";
        $i = sizeof($valor);
        $i--;
        while ($i >= 0) {
            if ($contador == 3) {
                $moneda = "." . $moneda;
            } else {
                $moneda = $valor[$i] . $moneda;
                $i--;
            }
            $contador++;
            if ($contador > 3) {
                $contador = 0;
            }
        }
        $moneda = "$" . $moneda;
        return $moneda;
    }

    /*
     * TRAZA DE PROCESO
     */

    function TrazaCoactivo($cod_respuesta, $cod_coactivo, $comentarios) {
        $info = $this->mcremate_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', $cod_coactivo, '', '', '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

}
