<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recepciontitulos extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library('tcpdf/Plantilla_PDF_Remisibilidad.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->file(APPPATH . "controllers/verificarpagos.php", true);
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('consultartitulos_model', '', TRUE);
        $this->load->model('documentospj_model', '', TRUE);
        $this->load->model('plantillas_model');
        $this->load->model('acumulacion_model');
        $this->load->model('numeros_letras_model');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        define("ABOGADO", '43');
        define("SECRETARIO", '41');
        define("COORDINADOR", '42');
        define("DIRECTOR", '61');


        define("TITULO_ACEPTADO", "173"); //ABOGADO ASIGNADO
        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);
        define("REGIONAL_USUARIO", $this->data['user']->COD_REGIONAL);
        define("MENSAJE_TITULOS", "No existen titulos para este proceso");

        define("PLANTILLA_AUTO_AVOCA_CONOCIMIENTO", "124");
        define("PLANTILLA_TRASLADO", "124");
        define("TIPO_1", "Auto que Avoca Conocimiento al Expediente");
        define("TIPO_2", "Documento De Rechazo a Instancia Administrativa");


        define("TITULO_COMPLETO", "886");
        define("ASIGNAR_ABOGADO", "173");

        define("TITULO_EXIGIBLE", "174");
        define("TITULO_NO_EXIGIBLE", "175");
        define("TITULO_REORGANIZACION", "176");
        define("TITULO_REGIMEN_INSOLVENCIA", "1367");
        define("TITULO_NOREORGANIZACION", "177");
        define("TITULO_PRESCRITO", "178");
        define("TITULO_VIGENTE", "179");
        define("ACUMULACION_TITULOS", "180");
        define("NO_ACUMULACION_TITULOS", "623");

        define("AUTO_AVOCA_GENERADO", "653");
        define("AUTO_AVOCA_CORREGIDO", "654");
        define("AUTO_AVOCA_PREAPROBADO", "655");
        define("AUTO_AVOCA_NOPREAPROBADO", "656");
        define("AUTO_AVOCA_APROBADO", "657");
        define("AUTO_AVOCA_NOAPROBADO", "658");

        define("DOC_RECHAZO_SOLICITADO", "1494");
        define("DOC_RECHAZO_CREADO", "1487");
        define("DOC_RECHAZO_PREAPROBADO", "1488");
        define("DOC_RECHAZO_APROBADO", "1489");
        define("DOC_RECHAZO_CORREGIDO", "1490");
        define("DOC_RECHAZO_RECHAZADO", "1491");

        define("AUTO_AVOCA_SUBIDO", "1123");
        define("COMUNICADO_PJ_SUBIDO", "1124");
        define("RESOLUCION_SUBIDO", "1125");
        define("DOC_RECHAZO_SUBIDO", "1114");


        define("TITULO_PROXIMO_PRESCRIBIR", "183");
        define("TITULO_NO_PROXIMO_PRESCRIBIR", "184");

        define("INVESTIGACION_BIENES", "204");

        $this->data['message'] = $this->session->flashdata('message');


        $sesion = $this->session->userdata;
        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);
        $permiso_sec = FALSE;
        $permiso_cor = FALSE;
        if (ID_USER == ID_SECRETARIO):
            $permiso_sec = TRUE;
        endif;
        if (ID_USER == ID_COORDINADOR):
            $permiso_cor = TRUE;
        endif;
        $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/';
    }

    function manage() {
        if ($this->ion_auth->is_admin()) {
            return true;
        } else {
            return false;
        }
    }

    function index() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/index')) {
                $this->nit = $this->input->post('nit');
                $this->template->set('title', 'Recepción de Titulos');
//                $Rempresa = $this->consultartitulos_model->Empresa();
//                foreach ($Rempresa->result_array() as $empresa) {
//                    $this->data['razonsocial'] = $empresa['RAZON_SOCIAL'];
//                    $this->data['tipodoc'] = $empresa['NOMBRETIPODOC'];
//                    $this->data['nit'] = $this->nit;
//                }

                $this->data['data1'] = $this->consultartitulos_model->Datatable(COD_REGIONAL);

                $this->template->load($this->template_file, 'recepciontitulos/fiscalizaciones', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta area.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function areparto() {
        if ($this->ion_auth->logged_in()) {
            $cod_titulo = ($this->input->post('cod_titulo')) ? $this->input->post('cod_titulo') : '';
            $this->data['data1'] = $this->consultartitulos_model->Datatable2(REGIONAL_USUARIO, $cod_titulo);
            $this->data['cod_titulo'] = $cod_titulo;
            $this->data['style_sheets'] = array(
                'css/jquery.dataTables_themeroller.css' => 'screen',
            );
            $this->data['javascripts'] = array(
                'js/jquery-1.9.1.js',
                'js/jquery-ui-1.10.2.custom.min.js',
                'js/jquery.dataTables.min.js',
            );

            $this->template->load($this->template_file, 'recepciontitulos/areparto', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function fisico() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/index')) {
                $accion = ($this->input->post('accion')) ? $this->input->post('accion') : '';
                $codrecepcion = $this->input->post('cod_recepcion');
                $expediente = $this->uri->segment(5);
                $this->data['expediente'] = $expediente;
                if ($accion == 1) {
                    $tipogestion = 557;
                    $tiporespuesta = 1375;
                    $codtitulo = '';
                    $codjuridico = '';
                    $codcarteranomisional = '';
                    $coddevolucion = '';
                    $comentarios = "SE ENVÍA TÍTULO A SECRETARIO PARA REPARTO";
                    $usuariosAdicionales = '';
                    $consulta = $this->consultartitulos_model->envioareparto($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios, $usuariosAdicionales);
                    if ($consulta) {
                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $comentarios . '</div>');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR AL GUARDAR LA INFORMACIÓN</div>');
                    }
                    $this->data['cod_titulo'] = $codrecepcion;
                    $this->data['message'] = $this->session->flashdata('message');
                    redirect(base_url() . 'index.php/bandejaunificada/index', $this->data);
                } elseif ($accion == 2) {
                    $tipogestion = 426;
                    $tiporespuesta = 1114;
                    $codtitulo = '';
                    $codjuridico = '';
                    $codcarteranomisional = '';
                    $coddevolucion = '';
                    $comentarios = "SE RECHAZA TÍTULO POR EL EJECUTOR";
                    $usuariosAdicionales = '';
                    $consulta = $this->consultartitulos_model->envioareparto($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $codrecepcion, $comentarios, $usuariosAdicionales);
                    $expediente = $this->input->post('expediente');
                    $datos_nomisional['COD_CARTERA_NOMISIONAL'] = $expediente;
                    $datos_nomisional['COD_ESTADO'] = '6';
                    $this->consultartitulos_model->actualizacion_NoMisional($datos_nomisional);
                    if ($consulta) {
                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $comentarios . '</div>');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR AL GUARDAR LA INFORMACIÓN</div>');
                    }
                    $this->data['cod_titulo'] = $codrecepcion;
                    $this->data['message'] = $this->session->flashdata('message');
                    redirect(base_url() . 'index.php/bandejaunificada/index', $this->data);
                } elseif ($accion == '') {
                    $this->data['cod_recepcion'] = $this->uri->segment(3);
                    $this->load->view('recepciontitulos/titulos_fisicos', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function RecepcionTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/RecepcionTitulos')) {
                $this->nit = $this->input->post('nit');
                $this->template->set('title', 'Recepción de Titulos');
                $Rempresa = $this->consultartitulos_model->Empresa($this->nit);
                foreach ($Rempresa->result_array() as $empresa) {
                    $this->data['razonsocial'] = $empresa['RAZON_SOCIAL'];
                    $this->data['tipodoc'] = $empresa['NOMBRETIPODOC'];
                    $this->data['nit'] = $this->nit;
                }
                $this->data['data1'] = $this->consultartitulos_model->Datatable(REGIONAL_USUARIO);
                $this->template->load($this->template_file, 'recepciontitulos/fiscalizaciones', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function AsignacionAbogado() {
        if ($this->ion_auth->logged_in()) {


            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/AsignacionAbogado') || ($permiso_sec == TRUE)) {

                $this->template->set('title', 'Asignacion Abogado');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
                $proceso = FALSE;
                if ($this->input->post()):
                    $proceso = $this->input->post('cod_coactivo');
                    $respuesta = $this->input->post('cod_respuesta');
                endif;
                $this->data['proceso'] = $proceso;
                $this->data['consulta'] = $this->consultartitulos_model->consulta_titulos($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL, $proceso);
                $this->data['abogados'] = $this->consultartitulos_model->consulta_abogado($this->ion_auth->user()->row()->COD_REGIONAL);
                $this->data['ruta'] = base_url() . 'index.php/recepciontitulos/guardar_asignacion';


                $this->template->load($this->template_file, 'recepciontitulos/consultartitulos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function coordinador() {
        if ($this->ion_auth->logged_in()) {
            $this->data['user'] = $this->ion_auth->user()->row();
            $this->data['consulta'] = $this->consultartitulos_model->consulta_titulos($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $this->ion_auth->user()->row()->COD_REGIONAL);
            $this->data['abogados'] = $this->consultartitulos_model->consulta_abogado($this->ion_auth->user()->row()->COD_REGIONAL);
            $this->data['ruta'] = base_url() . 'index.php/recepciontitulos/guardar_asignacion';
            $this->template->load($this->template_file, 'recepciontitulos/consultartitulos_list', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_asignacion() {
        $this->data['post'] = $this->input->post();
        $this->data['proceso'] = $proceso;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('abogado', 'Abogado', 'required');
        $this->form_validation->set_rules('asignar', 'Proceso', 'required');
        $this->data['ruta_bandeja'] = base_url() . 'index.php/bandejaunificada/procesos';
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
            redirect(base_url() . 'index.php/bandejaunificada/index');
        } else {
            //$this->Traza_Proceso($this->data['post']['cod_recepcion'], ASIGNAR_ABOGADO);
            $info = $this->consultartitulos_model->guardar_asignacion($this->data);
            if ($info) {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha realizado la asignación del abogado.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index', $this->data);
            } else
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado un error.</div>');
            redirect(base_url() . 'index.php/bandejaunificada', $this->data);
        }
    }

    function titulos_ejecutivos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/titulos_ejecutivos')) {
                $this->data['ruta'] = base_url() . 'index.php/recepciontitulos/detalle_titulos';
                $this->data['proceso'] = FALSE;
                if ($this->input->post())://Cuando proviene de la bandeja unificada
                    $this->data['proceso'] = $this->input->post('cod_coactivo');
                    $respuesta = $this->input->post('cod_respuesta');
                endif;
                $cod_respuesta = 173;
                $this->data['consulta'] = $this->consultartitulos_model->recepcion_titulos($this->input->post('iDisplayStart'), $this->input->post('sSearch'), COD_REGIONAL, ID_USER, $this->data['proceso'], $cod_respuesta);
                $this->template->load($this->template_file, 'recepciontitulos/titulos_ejecutivos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/recepciontitulos/titulos_ejecutivos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function detalle_titulos() {
        if ($this->ion_auth->logged_in()) {
            $this->datos = $this->input->post();
            $this->data['ruta_titulos'] = base_url() . 'index.php/recepciontitulos/titulos_ejecutivos';
            if ($this->datos['id_recepcion']) {
                $cod_respuesta = 173;
                if ($this->input->post())://Cuando proviene de la bandeja unificada
                    $this->data['proceso'] = $this->input->post('id_recepcion');
                endif;
                $this->data['consulta'] = $this->consultartitulos_model->titulos_ejecutivos($this->input->post('iDisplayStart'), $this->input->post('sSearch'), COD_REGIONAL, ID_USER, $this->datos['id_recepcion'], $cod_respuesta);
                $this->data['instancia'] = 'RECEPCION TITULOS';
                if ($this->data['consulta']) {
                    $this->data['ruta'] = base_url() . 'index.php/recepciontitulos/guarda_detalle';
                    $this->data['comentarios'] = $this->consultartitulos_model->historico($this->datos['id_recepcion']);

                    $this->template->load($this->template_file, 'recepciontitulos/detalle_titulos', $this->data);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay registros para este proceso.</div>');
                    redirect(base_url() . 'index.php/recepciontitulos/titulos_ejecutivos');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta area.</div>');
                redirect(base_url() . 'index.php/recepciontitulos/titulos_ejecutivos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guarda_detalle() {
        if ($this->ion_auth->logged_in()) {
            $this->datos = $this->input->post();
            $this->data['post'] = $this->input->post();
            if ($this->data['post']['id_recepcion']) {
                $info = $this->consultartitulos_model->guardar_detalle_ejec($this->data);
                if ($info) {         //si actualizo la informacion
                    //   $this->TrazaTitulo($this->data['post']['id_recepcion'], $this->data['post']['estado']);
                    $this->data['mensaje'] = 'Se ha actualizado la infomación';
                } else {
                    $this->data['mensaje'] = 'No se ha actualizado la infomación';
                }
                $this->data['consulta'] = $this->consultartitulos_model->titulos_ejecutivos($this->input->post('iDisplayStart'), $this->input->post('sSearch'), COD_REGIONAL, ID_USER, $this->datos['id_recepcion'], 173);

                if ($this->data['consulta']) { //Si hay titulos
                    //  $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->data['mensaje']. '</div>');
                    $this->data['ruta'] = base_url() . 'index.php/recepciontitulos/guarda_detalle';
                    $this->template->load($this->template_file, 'recepciontitulos/detalle_titulos', $this->data);
                } else if (!$this->data['consulta']) {//si no hay titulos
                    //$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay registros para este proceso.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_RegistrarEstados() {//CU005
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_RegistrarEstados')) {
                //template data
                $this->template->set('title', 'Recepción Estudio de Títulos Avoque Conocimiento');
                $this->template->load($this->template_file, 'recepciontitulos/menuregistrarestados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR AUTO QUE AVOCA CONOCIMIENTO AL EXPEDIENTE
     * $this->input->post('cod_coactivo')
     */

    function Crear_AutoAvocaConocimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_AUTO_AVOCA_CONOCIMIENTO, TIPO_1, AUTO_AVOCA_GENERADO, 'recepciontitulos/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoAvocaConocimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_AVOCA_CORREGIDO, 0, 'recepciontitulos/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_AutoAvocaConocimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_AVOCA_PREAPROBADO, AUTO_AVOCA_NOPREAPROBADO, 'recepciontitulos/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function ValidarAprobar_AutoAvocaConocimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->template->load($this->template_file, 'recepciontitulos/gestionar_rechazo', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        }
    }

    function Aprobar_AutoAvocaConocimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_AVOCA_APROBADO, AUTO_AVOCA_NOAPROBADO, 'recepciontitulos/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoAvocaConocimiento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_1, AUTO_AVOCA_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR AUTO QUE AVOCA CONOCIMIENTO AL EXPEDIENTE
     * $this->input->post('cod_coactivo')
     */

    function Crear_DocRechazo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_AUTO_AVOCA_CONOCIMIENTO, TIPO_2, DOC_RECHAZO_CREADO, 'recepciontitulos/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_DocRechazo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), DOC_RECHAZO_CORREGIDO, 0, 'recepciontitulos/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_DocRechazo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), DOC_RECHAZO_PREAPROBADO, DOC_RECHAZO_RECHAZADO, 'recepciontitulos/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_DocRechazo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), DOC_RECHAZO_APROBADO, DOC_RECHAZO_RECHAZADO, 'recepciontitulos/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_DocRechazo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_2, DOC_RECHAZO_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function RecepcionFDP() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_remisibilidad')) {
                $cod_titulo = $this->input->post('cod_titulo');
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                $cod_fiscalizacion = $info["COD_FISCALIZACION_EMPRESA"];
                $this->verificarpagos = new Verificarpagos();
                $this->verificarpagos->crearAutosCierre($cod_fiscalizacion);
            } else {
                redirect(base_url() . 'index.php/auth/login');
            }
        }
    }

    function VerificarExigibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_titulo')) {
                $cod_titulo = $this->input->post('cod_titulo');
                if ($cod_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual verificar exigibilidad.' . $id_titulo . '</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/');
                } else {
                    $this->data['cod_titulo'] = $cod_titulo;
                    $this->data['encabezado'] = $this->consultartitulos_model->get_encabezado_titulo($cod_titulo, TITULO_COMPLETO);
                    $this->data['naturaleza'] = $this->consultartitulos_model->get_naturalezadeudor($cod_titulo);
                    $this->data['tipo_exigibilidad'] = $this->consultartitulos_model->get_tiposexigibilidad();
                    $this->template->load($this->template_file, 'recepciontitulos/verificarexigible_add', $this->data); //vista caso de uso 3
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Guardar_ExigibilidadTitulo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        if ($this->ion_auth->logged_in()) {
            if ($post['cod_titulo']) {
                $cod_titulo = $post['cod_titulo'];
                $cod_respuesta = $post['cod_respuesta'];
                $tiempo = $post['tiempo'];
                $comentarios_exigibilidad = $post['comentarios_exigibilidad'];
                $comentarios_reorganizacion = $post['comentarios_reorganizacion'];
                $reorganizacion = $post['reorganizacion'];
                $proximo_prescribir = $post['proximo_prescribir'];
                /*
                 * Si Avoca Generar el numero para el expediente
                 */
                if ($cod_respuesta == NO_ACUMULACION_TITULOS) {
                    /*
                     * INSERTAR CODIGO PARA PROCESOS EN TODO COACTIVO
                     */
                    $proceso = $this->consultartitulos_model->get_codprocesojuridico($cod_titulo);
                    $dato_coactivo['ABOGADO'] = COD_USUARIO;
                    $dato_coactivo['COD_RESPUESTA'] = NO_ACUMULACION_TITULOS;
                    $dato_coactivo['IDENTIFICACION'] = $proceso['IDENTIFICACION'];
                    $dato_coactivo['PROCEDENCIA'] = $proceso['PROCEDENCIA']; //-1 MISIONAL -2 NO MISIONAL
                    $dato_coactivo['ACUMULACION'] = '0'; //-0 NO HAY ACUMULACION -1 HAY ACUMULACION
                    $cod_procesocoac = $this->acumulacion_model->insertar_acumulacion($dato_coactivo);
					if($proceso['NATURALEZA'] == ''){
						$proceso['NATURALEZA'] = '1';
					}
                    /*
                     * CON EL CODIGO INSERTADO SE ACTUALIZA EL COD_PJ
                     */
                    $cod_pj = $proceso['REGIONAL'] . '-' . $proceso['OBLIGACION'] . '-' . $proceso['NATURALEZA'] . '-' . $proceso['COBRO'] . '-' . $cod_procesocoac->COD_PROCESO_COACTIVO . '-00';
                    /*
                     * ACTUALIZAR CODIGO DE PROCESO JURIDICO
                     */
                    $datos_pj['COD_PROCESO_COACTIVO'] = $cod_procesocoac->COD_PROCESO_COACTIVO;
                    $datos_pj['COD_PROCESOPJ'] = $cod_pj;
                    $this->consultartitulos_model->actualizacion_recepcion($datos_pj);
                    /*
                     * INSERTAR TITULOS ACUMLADOS
                     */
                    $acumulacion_coactiva['COD_PROCESO_COACTIVO'] = $cod_procesocoac->COD_PROCESO_COACTIVO;
                    $acumulacion_coactiva['COD_ESTADO_TITULO'] = 2; //activo
                    $acumulacion_coactiva['COD_RECEPCIONTITULO'] = $cod_titulo;
                    $this->acumulacion_model->insertar_titulosacumulacion($acumulacion_coactiva); //condicionar
					/*
					*	TRAZA CON CODIGO COACTIVO
					*/
					$this->TrazaCoactivo($cod_procesocoac->COD_PROCESO_COACTIVO, NO_ACUMULACION_TITULOS, $post['comentarios_exigibilidad']);
                } else if ($cod_respuesta == TITULO_REGIMEN_INSOLVENCIA) {
                    /*
                     * INSERTAR CODIGO PARA PROCESOS EN TODO COACTIVO
                     */
                    $proceso = $this->consultartitulos_model->get_codprocesojuridico($cod_titulo);
					print_r($proceso);
                    $dato_coactivo['ABOGADO'] = COD_USUARIO;
                    $dato_coactivo['COD_RESPUESTA'] = TITULO_REGIMEN_INSOLVENCIA;
                    $dato_coactivo['IDENTIFICACION'] = $proceso['IDENTIFICACION'];
                    $dato_coactivo['PROCEDENCIA'] = $proceso['PROCEDENCIA']; //-1 MISIONAL -2 NO MISIONAL
                    $dato_coactivo['ACUMULACION'] = '0'; //-0 NO HAY ACUMULACION -1 HAY ACUMULACION
                    $cod_procesocoac = $this->acumulacion_model->insertar_acumulacion($dato_coactivo);
					if($proceso['NATURALEZA'] == ''){
						$proceso['NATURALEZA'] = '1';
					}
                    /*
                     * CON EL CODIGO INSERTADO SE ACTUALIZA EL COD_PJ
                     */
                    $cod_pj = $proceso['NO_CARTERA'];
                    /*
                     * ACTUALIZAR CODIGO DE PROCESO JURIDICO
                     */
                    $datos_pj['COD_PROCESO_COACTIVO'] = $cod_procesocoac->COD_PROCESO_COACTIVO;
                    $datos_pj['COD_PROCESOPJ'] = $cod_pj;
                    $this->consultartitulos_model->actualizacion_recepcion($datos_pj);
                    /*
                     * INSERTAR TITULOS ACUMLADOS
                     */
                    $acumulacion_coactiva['COD_PROCESO_COACTIVO'] = $cod_procesocoac->COD_PROCESO_COACTIVO;
                    $acumulacion_coactiva['COD_ESTADO_TITULO'] = 2; //activo
                    $acumulacion_coactiva['COD_RECEPCIONTITULO'] = $cod_titulo;
                    $this->acumulacion_model->insertar_titulosacumulacion($acumulacion_coactiva); //condicionar
					/*
					*	TRAZA CON CODIGO COACTIVO
					*/
					$this->TrazaCoactivo($cod_procesocoac->COD_PROCESO_COACTIVO, TITULO_REGIMEN_INSOLVENCIA, $post['comentarios_exigibilidad']);
                } else if ($cod_respuesta == TITULO_PRESCRITO) {
                    /*
                     * INSERTAR CODIGO PARA PROCESOS EN TODO COACTIVO
                     */
                    $proceso = $this->consultartitulos_model->get_codprocesojuridico($cod_titulo);
                    $dato_coactivo['ABOGADO'] = COD_USUARIO;
                    $dato_coactivo['COD_RESPUESTA'] = TITULO_PRESCRITO;
                    $dato_coactivo['IDENTIFICACION'] = $proceso['IDENTIFICACION'];
                    $dato_coactivo['PROCEDENCIA'] = $proceso['PROCEDENCIA']; //-1 MISIONAL -2 NO MISIONAL
                    $dato_coactivo['ACUMULACION'] = '0'; //-0 NO HAY ACUMULACION -1 HAY ACUMULACION
                    $cod_procesocoac = $this->acumulacion_model->insertar_acumulacion($dato_coactivo);
                    /*
                     * INSERTAR TITULOS ACUMULADOS
                     */
                    $acumulacion_coactiva['COD_PROCESO_COACTIVO'] = $cod_procesocoac->COD_PROCESO_COACTIVO;
                    $acumulacion_coactiva['COD_ESTADO_TITULO'] = 2; //activo
                    $acumulacion_coactiva['COD_RECEPCIONTITULO'] = $cod_titulo;
                    $this->acumulacion_model->insertar_titulosacumulacion($acumulacion_coactiva); //condicionar
					/*
					*	TRAZA CON CODIGO COACTIVO
					*/
					$this->TrazaCoactivo($cod_procesocoac->COD_PROCESO_COACTIVO, TITULO_PRESCRITO, $post['comentarios_exigibilidad']);
                }

                /*
                 * ACTUALIZAR TABLA RECEPCIONTITULOS
                 */
                $datos_actualizacion['COD_RECEPCIONTITULO'] = $cod_titulo;
                $datos_actualizacion['COD_TIPORESPUESTA'] = $cod_respuesta;
                $datos_actualizacion['PROCESO_REORGANIZACION'] = $reorganizacion;
                $datos_actualizacion['TITULO_PRESCRITO'] = $tiempo;
                $datos_actualizacion['COMENTARIOS_EXIGIBILIDAD'] = $comentarios_exigibilidad;
                $datos_actualizacion['COMENTARIOS_REORGANIZACION'] = $comentarios_reorganizacion;
                $datos_actualizacion['PROXIMO_PRESCRIBIR'] = $proximo_prescribir;
                $this->consultartitulos_model->actualizacion_estado_titulo($datos_actualizacion);
				/*
				*	TRAZA CON CODIGO DEL TITULO
				*/
					$this->TrazaTitulo($cod_titulo, $cod_respuesta, $comentarios_exigibilidad);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/recepciontitulos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Listado_RegistrarEstados() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Listado_RegistrarEstados')) {
                //template data
                $this->template->set('title', 'Recepción Estudio de TÃ­tulos Avoque Conocimiento');
                $this->data["message"] = 'Seleccione la Empresa a la cual desea realizar la gestion';
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                switch ($this->input->post('id_opcion')) {
                    case 1:
                        $this->data["ruta"] = 'index.php/recepciontitulos/Proceso_Reorganizacion';
                        $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado('174', $id_usuario);
                        break;
                    case 2:
                        $this->data["ruta"] = 'index.php/recepciontitulos/Titulo_Prescrito';
                        $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado('177', $id_usuario);
                        break;
                    case 3:
                        $this->data["ruta"] = 'index.php/recepciontitulos/Acumulacion_Titulos';
                        $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado('179', $id_usuario);
                        break;
                    default:
                        break;
                }
                $this->data['titulo'] = "Titulos Seleccionables";
                $this->template->load($this->template_file, 'recepciontitulos/registrarestados_list', $this->data);
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
                $datos["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                $datos["COD_RESPUESTA"] = $this->input->post('estado');
                $datos["NOMBRE_DOCUMENTO"] = $this->input->post('nombre_documento');
                $datos["ABOGADO"] = $this->input->post('abogado');
                $datos["SECRETARIO"] = $this->input->post('secretario');
                $datos["EJECUTOR"] = $this->input->post('coordinador');
                $datos["COMENTADO_POR"] = COD_USUARIO;
                $datos["COMENTARIO"] = $this->input->post('comentarios');
                $datos["TITULO_ENCABEZADO"] = $this->input->post('encabezado');
                $this->consultartitulos_model->insertar_comunicacion($datos);
                /*
                 * ACTUALIZAR ESTADO DEL AVOCA CONOCIMIENTO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                $this->consultartitulos_model->actualizacion_recepcion($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($this->input->post('cod_coactivo'), $this->input->post('estado'), $this->input->post('comentarios'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Gestionar_Rechazo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_coactivo')) {
                /*
                 * ACTUALIZAR ESTADO DEL AVOCA CONOCIMIENTO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                $datos2["COD_RESPUESTA"] = DOC_RECHAZO_SOLICITADO;
                $this->consultartitulos_model->actualizacion_recepcion($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($this->input->post('cod_coactivo'), DOC_RECHAZO_SOLICITADO, 'Rechazo Gestionado');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function enviar_correo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('correo')) {
                /*
                 * ACTUALIZAR TITULO EN TABLA NOTIFICACIONES
                 */
                $correo = $this->input->post('correo');
                $mensaje = $this->input->post('comentarios');
                $asunto = $this->input->post('asunto');
                $adjunto = $this->input->post('ruta');
                enviarcorreosena($correo, $mensaje, $asunto, $adjunto);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Traer_VerificacionCorreo() {
        $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->consultartitulos_model->set_fiscalizacion($cod_fiscalizacion);
        $result = $this->consultartitulos_model->get_correo_autorizacion();
        if (!empty($result)) :
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        else:
            $result["EMAIL_AUTORIZADO"] = 'envio_fisico';
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
        endif;
    }

    function listado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('nit')) {
                $this->nit = $this->input->post('nit');
                $this->expediente = $this->input->post('expediente');
                $this->concepto = $this->input->post('concepto');
                $observaciones = $this->input->post('observaciones');
                $this->load->helper('form');
                $this->load->library('form_validation');

                $recepcion_id = $this->input->post('recepcion_id');
                $this->data['expediente'] = $this->expediente;
                $this->data['concepto'] = $this->concepto;

                $this->data['titulos'] = $this->consultartitulos_model->getTitulos($this->nit, $this->expediente);

                $checklist = $this->consultartitulos_model->traechecklist(strtoupper($this->concepto));
                $this->data['controles'] = array();

                foreach ($checklist as $value) {
                    $ct = array(
                        'name' => $value['DESCRIPCION'],
                        'id' => $value['DESCRIPCION'],
                        'value' => '1',
                        'label' => $value['TEXTO'],
                    );
                    array_push($this->data['controles'], $ct);
                }

                $this->data['observaciones'] = array(
                    'name' => 'observaciones',
                    'id' => 'observaciones',
                    'value' => $observaciones,
                    'cols' => '40',
                    'rows' => '3',
                    'style' => 'height: 50; width: 100%; margin-left: 0; background-color: #E6E6E6;'
                );

                $Rempresa = $this->consultartitulos_model->Empresa($this->nit);
                foreach ($Rempresa->result_array() as $empresa) {
                    $this->data['razonsocial'] = $empresa['RAZON_SOCIAL'];
                    $this->data['tipodoc'] = $empresa['NOMBRETIPODOC'];
                    $this->data['nit'] = $this->nit;
                }

                $this->data['comentarios'] = $this->consultartitulos_model->historico($recepcion_id);

                $this->form_validation->set_rules('observaciones', 'Observaciones', 'required|trim|xss_clean');
                $fecha = date('Y-m-d');

                if ($this->form_validation->run() === FALSE) {
                    $this->data['cabecera'] = $this->consultartitulos_model->get_encabezado_titulo($recepcion_id, 173);
                    $this->template->load($this->template_file, 'recepciontitulos/titulos_list', $this->data);
                } else {
                    $data = array();
                    foreach ($this->input->post() as $key => $campo) {
                        if ($key != 'nit' && $key != 'expediente' && $key != 'observaciones' && $key != 'aceptar' && $key != 'rechazar') {
                            $data[$key] = $campo;
                        }
                    }
                    if ($this->input->post('aceptar') == 'ACEPTAR TITULO') {
                        $recepcion = $this->consultartitulos_model->Recepcion($this->expediente, $this->nit, $observaciones, 'S', $data);
                    } else {
                        $recepcion = $this->consultartitulos_model->Recepcion($this->expediente, $this->nit, $observaciones, 'N', $data);
                    }
                    redirect(base_url() . 'index.php/bandejaunificada');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Â¡rea.</div>');
                redirect(base_url() . 'index.php/bandejaunificada');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_Documentos($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta) {
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
            $Cabecera = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta);
            $encabezado = $this->documentospj_model->get_encabezado_documentos($cod_coactivo, $cod_respuesta);
            $this->data['secretario'] = $this->documentospj_model->get_secretario_regional();
            $this->data['informacion'] = $this->plantillas_model->plantillas($cod_plantilla);
            /*
             * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
             */
            $urlplantilla2 = "uploads/plantillas/" . $this->data['informacion'][0]['ARCHIVO_PLANTILLA'];
            $arreglo = array();
            $arreglo['identificacion'] = $Cabecera['IDENTIFICACION'];
            $arreglo['ejecutado'] = $Cabecera['EJECUTADO'];
            $arreglo['concepto'] = $Cabecera['CONCEPTO'];
            $arreglo['telefono'] = $Cabecera['TELEFONO'];
            $arreglo['direccion'] = $Cabecera['DIRECCION'];
            $arreglo['saldo_deuda'] = $Cabecera['SALDO_DEUDA'];
            /*
             * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
             */
            if (file_exists($urlplantilla2)) {
                $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
            } else {
                $this->data['filas2'] = '';
            }
            $this->data['titulos_acumulados'] = $this->consultartitulos_model->cabecera($cod_coactivo, $cod_respuesta);
            $this->data['informacion'] = $this->plantillas_model->plantillas($cod_plantilla);
            $this->data['encabezado'] = $encabezado;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->data['cod_proceso'] = $cod_proceso;
            $this->template->set('title', 'Jurisdicción Coactiva');
            $this->template->load($this->template_file, $ruta, $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Proceso_Documento($tipo, $cod_coactivo, $cod_respuesta1, $cod_respuesta2, $ruta) {
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
            $encabezado = $this->documentospj_model->get_encabezado_documentos($cod_coactivo, $cod_respuesta1);
            $this->data['documento'] = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
            /*
             * TRAER COMENTARIOS Y SUS ACTORES
             */
            $this->data['comentario'] = $this->documentospj_model->get_comentarios_proceso($cod_coactivo);
            /*
             * ENVIO DE DATOS VISUALIZACION DE VISTA
             */
            $abogado = $this->documentospj_model->get_abogado_proceso($cod_coactivo);
            $secretario = $this->documentospj_model->get_secretario_proceso($cod_coactivo);
            $coordinador = $this->documentospj_model->get_coordinador_proceso($cod_coactivo);
            $this->data['titulos_acumulados'] = $this->consultartitulos_model->cabecera($cod_coactivo, $cod_respuesta1);
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
            $urlplantilla2 = "uploads/recepciontitulos/" . $cod_coactivo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Avoca Conocimiento al Expediente');
            $this->template->load($this->template_file, $ruta, $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta) {
        if ($this->ion_auth->logged_in()) {
            /*
             * CONSULTAR DOCUMENTO PARA IMPRIMIR
             */
            $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/recepciontitulos/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'recepciontitulos/subirexpediente_add', $this->data);
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
                $fecha = $this->input->post('fecha');
                $numero = $this->input->post('numero');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $archivos = $this->documentospj_model->get_documentosexpediente($verificar);
                if (sizeof($archivos) > 0) {
                    /*
                     * CARGAR TABLA DE EXPEDIENTES
                     */
                    switch ($cod_respuesta) {
                        case AUTO_AVOCA_SUBIDO:
                            $this->data['tipo'] = TIPO_1;
                            break;
                        default :
                            $this->data['tipo'] = TIPO_2;
                            break;
                    }

                    $this->template->set('title', 'Jurisdicción Coactiva -> Auto que Avoca Conocimiento al Expediente');
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->data['cod_respuesta'] = $cod_respuesta;
                    $this->data['archivos'] = $archivos;
                    $this->template->load($this->template_file, 'recepciontitulos/comprobacionexp_add', $this->data);

                } else {
                    $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                    $datos['FECHA_RADICADO'] = $fecha;
                    $datos['NUMERO_RADICADO'] = $numero;
                    $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos["ID_USUARIO"] = COD_USUARIO;
                    /*
                     * OBTENER NOMBRE DEL ARCHIVO
                     */
                    $file = $this->do_multi_upload($cod_coactivo, "Expediente"); //1- pdf y archivos de imagen
                    if(isset($file['error']) && $file['error']!= ''):
                        $this->data['message2'] = $file['error'];
                         switch ($cod_respuesta) {
                        case AUTO_AVOCA_SUBIDO:
                            $this->data['tipo'] = TIPO_1;
                            break;
                        default :
                            $this->data['tipo'] = TIPO_2;
                            break;
                    }
                        /*
                         * CONSULTAR DOCUMENTO PARA IMPRIMIR
                         */
                        $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
                        $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
                        $urlplantilla2 = "uploads/recepciontitulos/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
                        $arreglo = array();
                        $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
                        /*
                         * CARGAR VISTA
                         */
                        $this->template->set('title', 'Jurisdicción Coactiva');
                        $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
                        $this->data['cod_coactivo'] = $cod_coactivo;
                        $this->data['cod_respuesta'] = $cod_respuesta;
                        $this->template->load($this->template_file, 'recepciontitulos/subirexpediente_add', $this->data);
                        else:
                    switch ($cod_respuesta) {
                        case AUTO_AVOCA_SUBIDO:
                            $datos['COD_TIPO_EXPEDIENTE'] = 2;
                            $datos['SUB_PROCESO'] = TIPO_1;
                            $this->data['tipo'] = TIPO_1;
                            break;
                        default :
                            $datos['COD_TIPO_EXPEDIENTE'] = 6;
                            $datos['SUB_PROCESO'] = TIPO_2;
                            $this->data['tipo'] = TIPO_2;
                            break;
                    }
                    /*
                     * POR CUANTOS ARCHIVOS SE ENCUENTRE UN INSERT SE REALIZA
                     */
                    $cantidad_documentos = sizeof($file);
                    for ($i = 0; $i < $cantidad_documentos; $i++) {
                        $datos["RUTA_DOCUMENTO"] = "uploads/recepciontitulos/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                        $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                        $this->documentospj_model->insertar_expediente($datos);
                    }
                    /*
                     * CARGAR TABLA DE EXPEDIENTES
                     */
                    $this->template->set('title', 'Jurisdicción Coactiva -> Auto que Avoca Conocimiento al Expediente');
                    $verificar[0] = $cod_coactivo;
                    $verificar[1] = $cod_respuesta;
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->data['cod_respuesta'] = $cod_respuesta;
                    $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                    $this->template->load($this->template_file, 'recepciontitulos/comprobacionexp_add', $this->data);
                    endif;
                }
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
            $cod_coactivo = $this->input->post('cod_coactivo');
            $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/recepciontitulos/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('recepciontitulos/visualizardocumento_add', $this->data);
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
                $this->template->set('title', 'Jurisdicción Coactiva ');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                switch ($cod_respuesta) {
                    case AUTO_AVOCA_SUBIDO:
                        $this->data['tipo'] = TIPO_1;
                        $tipo = TIPO_1;
                        break;
                    default :
                        $this->data['tipo'] = TIPO_2;
                        $tipo = TIPO_2;
                        break;
                }
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'recepciontitulos/comprobacionexp_add', $this->data);
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
                $cod_respuesta = $this->input->post('cod_respuesta');
                /*
                 * Eliminar el Archivo de la BD y del server
                 */
                $plantillas = $this->documentospj_model->get_plantillasfiscalizacion($cod_coactivo);
                for ($i = 0; $i < sizeof($plantillas); $i++) {
                    $estructura = "./uploads/recepciontitulos/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->documentospj_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                /*
                 * ACTUALIZAR ESTADO DEL AVOCA CONOCIMIENTO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->consultartitulos_model->actualizacion_recepcion($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $cod_gestioncobro = $this->TrazaCoactivo($cod_coactivo, $cod_respuesta, 'Auto que Avoca conocimiento subido al expediente');
                switch ($cod_respuesta) {
                    case AUTO_AVOCA_SUBIDO:
                        $info_coactivo = $this->consultartitulos_model->get_naturalezacoactivo($cod_coactivo, $cod_respuesta);
                        $naturaleza = $info_coactivo['PROXIMO_PRESCRIBIR'];
                        /*
                         * 0 = NO PROXIMO A PRESCRIBIR
                         * 1 = PROXIMO A PRESCRIBIR
                         */
                        if ($naturaleza == '0') {
                            /*
                             * INSERTAR EN ACERCAMIENTO PERSUASIVO
                             */
                            $datos_persuasivo['COD_GESTION_COBRO'] = $cod_gestioncobro;
                            $datos_persuasivo['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                            $datos_persuasivo['COD_TIPO_RESPUESTA'] = TITULO_NO_PROXIMO_PRESCRIBIR; //titulo no preinscrito o vigente                            
                            $this->consultartitulos_model->insertar_cobro_persuasivo($datos_persuasivo);
                            /*
                             * INSERTAR EN MANDAMIENTO DE PAGO
                             */
                            $datos_mandamiento['CREADO_POR'] = COD_USUARIO;
                            $datos_mandamiento['ASIGNADO_A'] = COD_USUARIO;
                            $datos_mandamiento['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                            $datos_mandamiento['COD_GESTIONCOBRO'] = $cod_gestioncobro;
                            $datos_mandamiento['ESTADO'] = INVESTIGACION_BIENES;
                            $this->consultartitulos_model->insertar_mandamientopago($datos_mandamiento);
                            /*
                             * INSERTAR MEDIDAS CAUTELARES
                             */
                            $dato_inv["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                            $dato_inv["COD_RESPUESTAGESTION"] = INVESTIGACION_BIENES;
                            $dato_inv["BLOQUEO"] = 0;
                            $dato_inv["COD_GESTIONCOBRO"] = $cod_gestioncobro;
                            $dato_inv["GENARADO_POR"] = COD_USUARIO;
                            $this->consultartitulos_model->insertar_investigacion_bienes($dato_inv);
                        } else {
                            /*
                             * INSERTAR EN MANDAMIENTO DE PAGO
                             */
                            $datos_mandamiento['CREADO_POR'] = COD_USUARIO;
                            $datos_mandamiento['ASIGNADO_A'] = COD_USUARIO;
                            $datos_mandamiento['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                            $datos_mandamiento['COD_GESTIONCOBRO'] = $cod_gestioncobro;
                            $datos_mandamiento['ESTADO'] = INVESTIGACION_BIENES;
                            $this->consultartitulos_model->insertar_mandamientopago($datos_mandamiento);
                        }
                        break;
                    default :
                        $titulos = $this->consultartitulos_model->cabecera($cod_coactivo, $cod_respuesta);
                        for ($i = 0; $i < sizeof($titulos); $i++) {
                            $cod_titulo = $titulos[$i]['NO_EXPEDIENTE'];
                            $procedencia = $this->consultartitulos_model->get_procedenciadeuda($cod_titulo);
                            if ($procedencia['NOMISIONAL'] == 'S') {
                                $datos_titulo['COD_TIPORESPUESTA'] = $cod_respuesta;
                                $datos_titulo['COD_RECEPCIONTITULO'] = $cod_titulo;
                                $this->consultartitulos_model->actualizacion_estado_titulo($datos_titulo);
                                $datos_nomisional['COD_CARTERA_NOMISIONAL'] = $procedencia['COD_CARTERA_NOMISIONAL'];
                                $datos_nomisional['COD_ESTADO'] = '6';
                                $this->consultartitulos_model->actualizacion_NoMisional($datos_nomisional);
                            } else {
                                $datos_titulo['COD_TIPORESPUESTA'] = $cod_respuesta;
                                $datos_titulo['COD_RECEPCIONTITULO'] = $cod_titulo;
                                $this->consultartitulos_model->actualizacion_estado_titulo($datos_titulo);
                                /*
                                 * INSERT EN TITULOS
                                 */
                                $soporte[0] = $cod_coactivo;
                                $soporte[1] = $cod_respuesta;
                                $info_soporte = $this->documentospj_model->get_documentosexpediente($soporte);
                                /*
                                 * INSERTAR RECHAZO 
                                 */
                                $dato_titulo['COD_RECEPCIONTITULO'] = $cod_titulo;
                                $dato_titulo['NOMBRE_DOCUMENTO'] = $info_soporte[0]["NOMBRE_DOCUMENTO"];
                                $dato_titulo['NUM_RADICADO'] = $info_soporte[0]["NUMERO_RADICADO"];
                                $dato_titulo['RUTA_ARCHIVO'] = $info_soporte[0]["RUTA_DOCUMENTO"];
                                $this->consultartitulos_model->insertar_titulosrechazo($dato_titulo);
                            }
                        }
                        break;
                }
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;

        $estructura = "uploads/recepciontitulos/" . $post['cod_coactivo'] . "/";
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
    }

     private function do_multi_upload($cod_fiscalizacion, $carpeta) {
        $estructura = "./uploads/recepciontitulos/" . $cod_fiscalizacion . "/" . $carpeta . "/";
        if (!file_exists($estructura)) {
            if (!mkdir($estructura, 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $config = array();
        $config['upload_path'] = $estructura;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '5120';
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

    /*
     * TRAZA DE PROCESO
     */

    function TrazaCoactivo($cod_coactivo, $cod_respuesta, $comentarios) {
        $info = $this->consultartitulos_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', $cod_coactivo, '', '', '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

    function TrazaTitulo($cod_titulo, $cod_respuesta, $comentarios) {
        $info = $this->consultartitulos_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', '', '', '', $cod_titulo, $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

}
