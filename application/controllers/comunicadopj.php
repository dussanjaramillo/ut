<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comunicadopj extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library('tcpdf/Plantilla_PDF_Remisibilidad.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('comunicadopj', '', TRUE);
        $this->load->model('plantillas_model');
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
        define("ABOGADO", "43");
        define("SECRETARIO", "41");
        define("COORDINADOR", "42");
       
        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("REGIONAL_USUARIO", $this->data['user']->COD_REGIONAL);
        define("MENSAJE_TITULOS", "No existen titulos para este proceso");
        define("TITULO_COMPLETO", "886");
        define("ASIGNAR_ABOGADO", "173");

        define("TITULO_REORGANIZACION", "176");
        
        define("COMUNICADO_PJ_GENERADO", "745");
        define("COMUNICADO_PJ_CORREGIDO", "746");
        define("COMUNICADO_PJ_PREAPROBADO", "747");
        define("COMUNICADO_PJ_NOPREAPROBADO", "748");
        define("COMUNICADO_PJ_APROBADO", "749");
        define("COMUNICADO_PJ_NOAPROBADO", "750");
        define("COMUNICADO_PJ_SUBIDO", "1124");
        
        define("AUTO_TRANSLADO_GENERADO", "745");
        define("AUTO_TRANSLADO_CORREGIDO", "746");
        define("AUTO_TRANSLADO_PREAPROBADO", "747");
        define("AUTO_TRANSLADO_NOPREAPROBADO", "748");
        define("AUTO_TRANSLADO_APROBADO", "749");
        define("AUTO_TRANSLADO_NOAPROBADO", "750");
        define("AUTO_TRANSLADO_SUBIDO", "1124");
        
        define("PLANTILLA_COMUNICADOPJ", "1124");
        define("PLANTILLA_AUTOTRASLADO", "1124");


        $this->data['message'] = $this->session->flashdata('message');
    }
function prueba($cod_fiscalizacion){
    echo $cod_fiscalizacion;
}
    function Traza_Proceso($cod_titulo, $cod_respuesta) {
        /*
         * FUNCION DE TRAZA
         */
        $traza = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
        $gestion = $this->consultartitulos_model->get_trazagestion($cod_respuesta);
        $this->datos['idgestioncobro'] = trazar($gestion["COD_TIPOGESTION"], $gestion["COD_RESPUESTA"], $traza["COD_FISCALIZACION_EMPRESA"], $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = 'Traza Recepcion de Titulos');
        $idgestioncobro = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
        return $idgestioncobro;
    }

    function Menu_SubirExpediente() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_SubirExpediente')) {
                //template data
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                //$this->data["ruta"] = 'index.php/recepciontitulos/Agregar_Expediente';
                $estados[0] = AUTO_AVOCA_APROBADO;
                $estados[1] = COMUNICADO_PJ_APROBADO;
                $estados[2] = RESOLUCION_APROBADO;
                $this->data['ruta'] = 'index.php/recepciontitulos/Visualizar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '-';
                $this->data['tipo'] = '-';
                $this->data['cod_respuesta'] = '-';
                $this->data['titulo'] = '-';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoAbogado($estados);
                $this->data['titulo'] = "Subir Soporte al Expediente";
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Expediente() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_titulo')) {
                $this->data['message'] = "Los archivos de soporte a subir son �nicamente PDF";
                $cod_titulo = $this->input->post('cod_titulo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                switch ($cod_respuesta) {
                    case AUTO_AVOCA_APROBADO:
                        $this->data['tipo'] = 'Auto que Avoca Conocimiento';
                        $cod_respuesta = AUTO_AVOCA_SUBIDO;
                        break;
                    case COMUNICADO_PJ_APROBADO:
                        $this->data['tipo'] = 'Comunicado a Procesos Judiciales';
                        $cod_respuesta = COMUNICADO_PJ_SUBIDO;
                        break;
                    case RESOLUCION_APROBADO:
                        $this->data['tipo'] = 'Resoluci�n  de Prescripcion';
                        $cod_respuesta = RESOLUCION_SUBIDO;
                        break;
                    default:
                        break;
                }
                $this->Traza_Proceso($cod_titulo, $cod_respuesta);
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->template->load($this->template_file, 'recepciontitulos/subirexpediente_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function manage() {
        if ($this->ion_auth->is_admin()) {
            return true;
        } else {
            return false;
        }
    }
     function CrearComunicadoPJ() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_fiscalizacion')) {
                $this->Crear_Documentos($this->input->post('cod_fiscalizacion'), PLANTILLA_COMUNICADOPJ, COMUNICADO_PJ_GENERADO, 13, 17, 'Oficio de Devoluci�n de T�tulos', 'mcremate/creardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    function Menu_CrearComunicadoPJ() {//CU006 - Gestionar Auto - ABOGADO
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_CrearComunicadoPJ')) {
                //template data
                $this->template->set('title', 'Comunicado a Procesos Judiciales');
                $this->data['ruta'] = 'index.php/recepciontitulos/Agregar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '72';
                $this->data['tipo'] = 'Comunicado a Procesos Judiciales';
                $this->data['cod_respuesta'] = '745';
                $this->data['titulo'] = 'Crear Comunicado a Procesos Judiciales';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_procesotransversal();
                $this->template->load($this->template_file, 'recepciontitulos/gestionartransversal_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_PreaprobarComunicadoPJ() {//CU006 - Gestionar Auto - ABOGADO
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_PreaprobarComunicadoPJ')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Preaprobar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '747';
                $this->data['tipo'] = 'Comunicado de Procesos Judiciales';
                $this->data['cod_respuesta'] = '748';
                $this->data['titulo'] = 'Preaprobar Comunicado de Procesos Judiciales';
                $estados[0] = COMUNICADO_PJ_GENERADO;
                $estados[1] = COMUNICADO_PJ_CORREGIDO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoregional($estados);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_CorregirComunicadoPJ() {//CU006 - Gestionar Auto - ABOGADO
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_CorregirComunicadoPJ')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Corregir_Documento_Abogado';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '-';
                $this->data['tipo'] = 'Comunicado de Procesos Judiciales';
                $this->data['cod_respuesta'] = '746';
                $this->data['titulo'] = 'Corregir Comunicado de Procesos Judiciales';
                $estados[0] = COMUNICADO_PJ_NOPREAPROBADO;
                $estados[1] = COMUNICADO_PJ_NOAPROBADO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoAbogado($estados);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_AprobarComunicadoPJ() {//CU006 - Gestionar Auto -DIRECTOR
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_AprobarComunicadoPJ')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Aprobar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '749';
                $this->data['tipo'] = 'Comunicado de Procesos Judiciales';
                $this->data['cod_respuesta'] = '750';
                $this->data['titulo'] = 'Aprobar Comunicado de Procesos Judiciales';
                $estados[0] = COMUNICADO_PJ_PREAPROBADO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoregional($estados);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_CrearResolucionPreinscripcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_CrearResolucionPreinscripcion')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Agregar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '73';
                $this->data['tipo'] = 'Resolucion de Prescripci�n';
                $this->data['cod_respuesta'] = '751';
                $this->data['titulo'] = 'Crear Resoluci�n de Prescripci�n ';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_procesotransversal();
                $this->template->load($this->template_file, 'recepciontitulos/gestionartransversal_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_PreaprobarResolucionPreinscripcion() {//CU006 - Gestionar Auto - ABOGADO
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_PreaprobarResolucionPreinscripcion')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Preaprobar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '753';
                $this->data['tipo'] = 'Resolucion de Prescripci�n ';
                $this->data['cod_respuesta'] = '754';
                $this->data['titulo'] = 'Preaprobar Resoluci�n de Prescripci�n';
                $estados[0] = RESOLUCION_GENERADO;
                $estados[1] = RESOLUCION_CORREGIDO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoregional($estados);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_CorregirResolucionPreinscripcion() {//CU006 - Gestionar Auto - ABOGADO
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_CorregirResolucionPreinscripcion')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Corregir_Documento_Abogado';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '-';
                $this->data['tipo'] = 'Resolucion de Prescripci�n ';
                $this->data['cod_respuesta'] = '752';
                $this->data['titulo'] = 'Corregir Resoluci�n de Prescripci�n';
                $estados[0] = RESOLUCION_NOPREAPROBADO;
                $estados[1] = RESOLUCION_NOAPROBADO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoAbogado($estados);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_AprobarResolucionPreinscripcion() {//CU006 - Gestionar Auto -DIRECTOR
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_AprobarResolucionPreinscripcion')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Aprobar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '755';
                $this->data['tipo'] = 'Resolucion de Prescripci�n ';
                $this->data['cod_respuesta'] = '756';
                $this->data['titulo'] = 'Aprobar Resoluci�n de Prescripci�n';
                $estados[0] = RESOLUCION_PREAPROBADO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_multipleestadoregional($estados);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_CrearFinProceso() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_CrearFinProceso')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Agregar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '74';
                $this->data['tipo'] = 'Auto de Terminacion y Cierre del Proceso';
                $this->data['cod_respuesta'] = '757';
                $this->data['titulo'] = 'Crear Auto de Terminacion y Cierre del Proceso';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado('755', $cod_usuario);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_PreaprobarFinProceso() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_PreaprobarFinProceso')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Preaprobar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '759';
                $this->data['tipo'] = 'Auto de Terminacion y Cierre del Proceso';
                $this->data['cod_respuesta'] = '760';
                $this->data['titulo'] = 'Preaprobar Auto de Terminacion y Cierre del Proceso';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado_Preaprobar('757', '758', '762', $cod_usuario);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_CorregirFinProceso() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_CorregirFinProceso')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Corregir_Documento_Abogado';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '-';
                $this->data['tipo'] = 'Auto de Terminacion y Cierre del Proceso';
                $this->data['cod_respuesta'] = '758';
                $this->data['titulo'] = 'Corregir Auto de Terminacion y Cierre del Proceso';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado_Creado('760', $cod_usuario);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_AprobarFinProceso() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_AprobarFinProceso')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data['ruta'] = 'index.php/recepciontitulos/Aprobar_Documento';
                $this->data['consecutivo'] = 'AEC';
                $this->data['cod_plantilla'] = '761';
                $this->data['tipo'] = 'Auto de Terminacion y Cierre del Proceso';
                $this->data['cod_respuesta'] = '762';
                $this->data['titulo'] = 'Aprobar Auto de Terminacion y Cierre del Proceso';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado('759', $cod_usuario);
                $this->template->load($this->template_file, 'recepciontitulos/gestionardocumento_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_TituloProximoPrescribir() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_TituloProximoPrescribir')) {
                //template data
                $usuario = $this->ion_auth->user()->row();
                $cod_usuario = $usuario->IDUSUARIO;
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data["message"] = 'Seleccione la Empresa a registrar si el titulo esta proximo a Prescribe';
                $this->data["ruta"] = 'index.php/recepciontitulos/Titulo_ProximoPrescribir';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado(AUTO_AVOCA_SUBIDO, $cod_usuario);
                $this->data['titulo'] = "Titulos Proximos a Prescribir";
                $this->template->load($this->template_file, 'recepciontitulos/registrarestados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_NotificarResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_NotificarResolucion')) {
                //template data
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data["message"] = 'Seleccione la Empresa a registrar si el titulo esta proximo a Prescribe';
                $this->data["ruta"] = 'index.php/recepciontitulos/NotificarResolucion';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado(RESOLUCION_SUBIDO, COD_USUARIO);
                $this->data['titulo'] = "Resoluciones Para Notificar al Deudor";
                $this->template->load($this->template_file, 'recepciontitulos/registrarestados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_NotificacionFisica() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_NotificacionFisica')) {
                //template data
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data["message"] = 'Seleccione la empresa a revisar el resultado de la notificacion';
                $this->data["ruta"] = 'index.php/recepciontitulos/NotificacionFisica';
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado('772', $id_usuario);
                $this->data['titulo'] = "Resoluciones Para Notificar al Deudor";
                $this->template->load($this->template_file, 'recepciontitulos/registrarestados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_VerificarExigibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_VerificarExigibilidad')) {
                //template data
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data["message"] = 'Seleccione la empresa a verificar la exigibilidad';
                $this->data["ruta"] = 'index.php/recepciontitulos/VerificarExigibilidad';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado(TITULO_COMPLETO, COD_USUARIO);
                $this->data['titulo'] = "Estudio de Verificaci�n de Titulos";
                $this->template->load($this->template_file, 'recepciontitulos/registrarestados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_TitulosAcumulables() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Menu_VerificarExigibilidad')) {
                //template data
                $this->template->set('title', 'Recepci�n Estudio de Titulos Avoque Conocimiento');
                $this->data["message"] = 'Seleccione la empresa a verificar la exigibilidad';
                $this->data["ruta"] = 'index.php/recepciontitulos';
                $this->data['titulos_seleccionados'] = $this->consultartitulos_model->get_tituloestado(ACUMULACION_TITULOS, COD_USUARIO);
                $this->data['titulo'] = "Acumulaci�n de T?tulos";
                $this->template->load($this->template_file, 'recepciontitulos/registrarestados_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function VerificarExigibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                $cod_titulo = $this->input->post('cod_titulo');
                if ($cod_titulo == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>  No hay titulo al cual verificar exigibilidad.' . $id_titulo . '</div>');
                    redirect(base_url() . 'index.php/recepciontitulos/Menu_VerificarExigibilidad');
                } else {
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('ta_observacion', 'Observaciones', 'required|max_length[300]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $cantidad_aprobada = 0;
                        $usuario = $this->ion_auth->user()->row();
                        $id_usuario = $usuario->IDUSUARIO;
                        $datos["COD_RECEPCIONTITULO"] = $cod_titulo;
                        $datos["OBSERVACIONES"] = $this->input->post('ta_observacion');
                        $datos["CONFIRMADO_POR"] = $id_usuario;
                        $datos["FECHA_CONFIRMACION"] = $this->input->post('fecha');
                        $tipo_exigibilidad = $this->consultartitulos_model->get_tiposexigibilidad();
                        for ($i = 0; $i <= $this->input->post('tb_cantidad'); $i++) {
                            if ($this->input->post('id_opcion' . $i) != '') {
                                $datos["COD_TIPOEXIGILIDAD"] = $this->input->post('id_opcion' . $i);
                                $datos["CONFIRMADO"] = 'S';
                                $cantidad_aprobada++;
                            } else {
                                $datos["COD_TIPOEXIGILIDAD"] = $tipo_exigibilidad[$i]["COD_TIPOEXIGILIDAD"];
                                $datos["CONFIRMADO"] = 'N';
                            }
                            $this->consultartitulos_model->insertar_exigibilidadtitulo($datos);
                        }
                        $datos_actualizacion["COD_RECEPCIONTITULO"] = $cod_titulo;
                        if ($cantidad_aprobada == sizeof($tipo_exigibilidad)) {
                            $datos_actualizacion["COD_TIPORESPUESTA"] = '174'; //EXIGIBLE
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado la exigibilidad del titulo.</div>');
                        } else {
                            $datos_actualizacion["COD_TIPORESPUESTA"] = '175'; //NO EXIGIBLE
                            $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                            $cod_fiscalizacion = $info["COD_FISCALIZACION_EMPRESA"];
                            $this->consultartitulos_model->set_fiscalizacion($cod_fiscalizacion);
                            $correo = $this->consultartitulos_model->get_correo_autorizacion();
                            $correo_autorizado = $correo['EMAIL_AUTORIZADO'];
                            if ($correo_autorizado == '') {
                                $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado la no exigibilidad del titulo.</div>');
                            } else {
                                enviarcorreosena($correo_autorizado, 'Notificacion de Titulo Judicial SENA', 'Su titulo ha entrado en evaluacion y ha sido evaluado como no exigible');
                                $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha enviado por correo  la no exigibilidad del titulo.</div>');
                            }
                        }
                        $this->consultartitulos_model->actualizacion_estado_titulo($datos_actualizacion);
                        redirect(base_url() . 'index.php/recepciontitulos/Menu_VerificarExigibilidad');
                    }
                    $this->data['cod_titulo'] = $cod_titulo;
                    $this->data['titulo'] = $this->consultartitulos_model->get_encabezado($cod_titulo);
                    $this->data['naturaleza'] = $this->consultartitulos_model->get_naturalezadeudor($cod_titulo);
                    $this->data['tipo_exigibilidad'] = $this->consultartitulos_model->get_tiposexigibilidad();
                    $this->template->load($this->template_file, 'recepciontitulos/verificarexigible_add', $this->data); //vista caso de uso 3
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/recepciontitulos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Documento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                /*
                 * TRAER DATOS DEL DATATABLE CORRESPONDIENTE
                 */
                $cod_titulo = $this->input->post('cod_titulo');
                $cod_plantilla = $this->input->post('cod_plantilla');
                $consecutivo = $this->consultartitulos_model->get_numprocesoadjudicado($cod_titulo);
                $cod_proceso = $consecutivo['CODIGO_PJ'];
                $tipo = $this->input->post('tipo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /*
                 * CONSULTAR DATOS DEL ENCABEZADO
                 * CONSULTAR SECRETARIOS PARA ASIGNAR
                 * CONSULTAR PLANTILLA
                 */
                $this->consultartitulos_model->set_titulo($cod_titulo);
                $this->data['titulo'] = $this->consultartitulos_model->get_encabezado($cod_titulo);
                $this->consultartitulos_model->set_regional($this->data['titulo']['COD_REGIONAL']);
                $this->data['secretario'] = $this->consultartitulos_model->get_secretario_regional();
                $this->data['informacion'] = $this->plantillas_model->plantillas($cod_plantilla);
                /*
                 * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
                 */
                $urlplantilla2 = "uploads/plantillas/" . $this->data['informacion'][0]['ARCHIVO_PLANTILLA'];
                $arreglo = array();
                if ($tipo == 'Auto de Terminacion y Cierre del Proceso') {
                    $arreglo = $this->consultartitulos_model->get_auto2dato1($cod_titulo);
                }
                /*
                 * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
                 */
                if (file_exists($urlplantilla2)) {
                    $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
                } else {
                    $this->data['filas2'] = '';
                }
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data['tipo'] = $tipo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['cod_proceso'] = $cod_proceso;
                $this->template->set('title', 'Recepcion, Estudio de Titulos y Avoca Conocimiento al Expediente');
                $this->template->load($this->template_file, 'recepciontitulos/elaborardocumento_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_Documento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                /*
                 * TRAER DATOS DEL DATATABLE CORRESPONDIENTE
                 */
                $cod_titulo = $this->input->post('cod_titulo');
                $cod_respuesta1 = $this->input->post('cod_plantilla');
                $cod_respuesta2 = $this->input->post('cod_respuesta');
                $consecutivo = $this->input->post('consecutivo');
                $tipo = $this->input->post('tipo');
                $consecutivo_cod = $this->consultartitulos_model->get_numprocesoadjudicado($cod_titulo);
                $cod_proceso = $consecutivo_cod['CODIGO_PJ'];
                $this->data['cod_proceso'] = $cod_proceso;
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                /*
                 * CONSULTAR DATOS DEL ENCABEZADO
                 * CONSULTAR DIRECTOR REGIONAL O COORDINADOR ASIGNADO
                 * CONSULTAR PLANTILLA URL PLANTILLA
                 */
                $this->consultartitulos_model->set_titulo($cod_titulo);
                $this->data['titulo'] = $this->consultartitulos_model->get_encabezado($cod_titulo);
                $this->data['documento'] = $this->consultartitulos_model->get_plantilla_proceso();
                /*
                 * TRAER COMENTARIOS Y SUS ACTORES
                 */
                $this->data['comentario'] = $this->consultartitulos_model->get_comentarios_proceso($cod_titulo);
                /*
                 * ENVIO DE DATOS VISUALIZACION DE VISTA
                 */
                $this->data['secretario'] = $this->consultartitulos_model->get_secretario_proceso();
                $this->data['coordinador'] = $this->consultartitulos_model->get_coordinador_proceso();
                $this->data['nombre_s'] = $this->data['secretario']['NOMBRES'] . " " . $this->data['secretario']['APELLIDOS'];
                $this->data['nombre_c'] = $this->data['coordinador']['NOMBRES'] . " " . $this->data['coordinador']['APELLIDOS'];
                $this->consultartitulos_model->set_regional($this->data['titulo']['COD_REGIONAL']);
                $this->data['secretario'] = $this->data['documento']['SECRETARIO'];
                $this->data['coordinador'] = $this->data['documento']['EJECUTOR'];
                $this->data['abogado'] = $this->data['documento']['ABOGADO'];
                $this->data['asignado'] = $this->consultartitulos_model->get_director_coordinador_regional();
                /*
                 * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
                 */
                $urlplantilla2 = "uploads/recepciontitulos/" . $info['COD_FISCALIZACION_EMPRESA'] . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
                $arreglo = array();
                $arreglo['n_ficha'] = $consecutivo . "_" . $cod_titulo;
                /*
                 * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
                 */
                $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data['tipo'] = $tipo;
                $this->data['cod_respuesta'][0] = $cod_respuesta1;
                $this->data['cod_respuesta'][1] = $cod_respuesta2;
                $this->template->set('title', 'Recepcion, Estudio de Titulos y Avoca Conocimiento al Expediente');
                $this->template->load($this->template_file, 'recepciontitulos/preaprobardocumento_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Documento_Abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                /*
                 * TRAER DATOS DEL DATATABLE CORRESPONDIENTE
                 */
                $cod_titulo = $this->input->post('cod_titulo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $consecutivo = $this->input->post('consecutivo');
                $tipo = $this->input->post('tipo');
                $consecutivo_cod = $this->consultartitulos_model->get_numprocesoadjudicado($cod_titulo);
                $cod_proceso = $consecutivo_cod['CODIGO_PJ'];
                $this->data['cod_proceso'] = $cod_proceso;
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                /*
                 * CONSULTAR DATOS DEL ENCABEZADO
                 * CONSULTAR DIRECTOR REGIONAL O COORDINADOR GRUPO DE GESTION DE COBRO COACTIVO PARA ASIGNAR
                 * CONSULTAR PLANTILLA URL PLANTILLA
                 */
                $this->consultartitulos_model->set_titulo($cod_titulo);
                $this->consultartitulos_model->set_estado('656');
                $this->data['titulo'] = $this->consultartitulos_model->get_encabezado($cod_titulo);
                $this->data['documento'] = $this->consultartitulos_model->get_plantilla_proceso();
                /*
                 * TRAER COMENTARIOS Y SUS ACTORES
                 */
                $this->data['comentario'] = $this->consultartitulos_model->get_comentarios_proceso($cod_titulo);
                $this->consultartitulos_model->set_regional($this->data['titulo']['COD_REGIONAL']);
                $this->data['secretario'] = $this->data['documento']['SECRETARIO'];
                $this->data['coordinador'] = $this->data['documento']['EJECUTOR'];
                $this->data['abogado'] = $this->data['documento']['ABOGADO'];
                /*
                 * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
                 */
                $urlplantilla2 = "uploads/recepciontitulos/" . $info['COD_FISCALIZACION_EMPRESA'] . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
                $arreglo = array();
                $arreglo['n_ficha'] = $consecutivo . "_" . $cod_titulo;
                /*
                 * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
                 */
                $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data['tipo'] = $tipo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->template->set('title', 'Recepcion, Estudio de Titulos y Avoca Conocimiento al Expediente');
                $this->template->load($this->template_file, 'recepciontitulos/corregirdocumentoabo_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_Documento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                /*
                 * TRAER DATOS DEL DATATABLE CORRESPONDIENTE
                 */
                $usuario = $this->ion_auth->user()->row();
                $cod_titulo = $this->input->post('cod_titulo');
                $cod_respuesta1 = $this->input->post('cod_plantilla');
                $cod_respuesta2 = $this->input->post('cod_respuesta');
                $consecutivo = $this->input->post('consecutivo');
                $tipo = $this->input->post('tipo');
                $consecutivo_cod = $this->consultartitulos_model->get_numprocesoadjudicado($cod_titulo);
                $cod_proceso = $consecutivo_cod['CODIGO_PJ'];
                $this->data['cod_proceso'] = $cod_proceso;
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                /*
                 * CONSULTAR DATOS DEL ENCABEZADO
                 * CONSULTAR DIRECTOR REGIONAL O COORDINADOR GRUPO DE GESTION DE COBRO COACTIVO PARA ASIGNAR
                 * CONSULTAR PLANTILLA URL PLANTILLA
                 */
                $this->consultartitulos_model->set_titulo($cod_titulo);
                $this->data['titulo'] = $this->consultartitulos_model->get_encabezado($cod_titulo);
                $this->data['documento'] = $this->consultartitulos_model->get_plantilla_proceso();
                $this->data['secretario'] = $this->consultartitulos_model->get_secretario_proceso();
                $this->data['coordinador'] = $this->consultartitulos_model->get_coordinador_proceso();
                /*
                 * TRAER COMENTARIOS Y SUS ACTORES
                 */
                $this->data['comentario'] = $this->consultartitulos_model->get_comentarios_proceso($cod_titulo);
                $this->consultartitulos_model->set_regional($this->data['titulo']['COD_REGIONAL']);
                $this->data['nombre_s'] = $this->data['secretario']['NOMBRES'] . " " . $this->data['secretario']['APELLIDOS'];
                $this->data['nombre_c'] = $this->data['coordinador']['NOMBRES'] . " " . $this->data['coordinador']['APELLIDOS'];
                $this->data['abogado'] = $this->data['documento']['ABOGADO'];
                /*
                 * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
                 */
                $urlplantilla2 = "uploads/recepciontitulos/" . $info['COD_FISCALIZACION_EMPRESA'] . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
                $arreglo = array();
                $arreglo['n_ficha'] = $consecutivo . "_" . $cod_titulo;
                /*
                 * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
                 */
                $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data['tipo'] = $tipo;
                $this->data['cod_respuesta'][0] = $cod_respuesta1;
                $this->data['cod_respuesta'][1] = $cod_respuesta2;
                $this->template->set('title', 'Recepcion, Estudio de Titulos y Avoca Conocimiento al Expediente');
                $this->template->load($this->template_file, 'recepciontitulos/aprobardocumento_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Visualizar_Documento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                /*
                 * TRAER DATOS DEL DATATABLE CORRESPONDIENTE
                 */
                $cod_titulo = $this->input->post('cod_titulo');
                $cod_respuesta1 = $this->input->post('cod_plantilla');
                $cod_respuesta2 = $this->input->post('cod_respuesta');
                $consecutivo = $this->input->post('consecutivo');
                $tipo = $this->input->post('tipo');
                $cod_respuesta_actual = $this->input->post('respuesta_actual');
                $consecutivo_cod = $this->consultartitulos_model->get_numprocesoadjudicado($cod_titulo);
                $cod_proceso = $consecutivo_cod['CODIGO_PJ'];
                $this->data['cod_proceso'] = $cod_proceso;
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                /*
                 * CONSULTAR DATOS DEL ENCABEZADO
                 * CONSULTAR DIRECTOR REGIONAL O COORDINADOR GRUPO DE GESTION DE COBRO COACTIVO PARA ASIGNAR
                 * CONSULTAR PLANTILLA URL PLANTILLA
                 */
                $this->consultartitulos_model->set_titulo($cod_titulo);
                $this->data['titulo'] = $this->consultartitulos_model->get_encabezado($cod_titulo);
                $this->data['documento'] = $this->consultartitulos_model->get_plantilla_proceso();
                $this->data['secretario'] = $this->consultartitulos_model->get_secretario_proceso();
                $this->data['coordinador'] = $this->consultartitulos_model->get_coordinador_proceso();
                /*
                 * TRAER COMENTARIOS Y SUS ACTORES
                 */
                $this->data['comentario'] = $this->consultartitulos_model->get_comentarios_proceso($cod_titulo);
                $this->consultartitulos_model->set_regional($this->data['titulo']['COD_REGIONAL']);
                $this->data['nombre_s'] = $this->data['secretario']['NOMBRES'] . " " . $this->data['secretario']['APELLIDOS'];
                $this->data['nombre_c'] = $this->data['coordinador']['NOMBRES'] . " " . $this->data['coordinador']['APELLIDOS'];
                $this->data['abogado'] = $this->data['documento']['ABOGADO'];
                /*
                 * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
                 */
                $urlplantilla2 = "uploads/recepciontitulos/" . $info['COD_FISCALIZACION_EMPRESA'] . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
                $arreglo = array();
                $arreglo['n_ficha'] = $consecutivo . "_" . $cod_titulo;
                /*
                 * DATOS A ENVIAR Y VISUALIZAR VISTA DE ELABORACION DE DOCUMENTO
                 */
                $this->data['filas2'] = template_tags($urlplantilla2, $arreglo);
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data['tipo'] = $tipo;
                $this->data['cod_respuesta'][0] = $cod_respuesta1;
                $this->data['cod_respuesta'][1] = $cod_respuesta2;
                $this->data['respuesta_actual']= $cod_respuesta_actual;
                $this->template->set('title', 'Subir al Expediente');
                $this->template->load($this->template_file, 'recepciontitulos/visualizardocumento_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Listado_RegistrarEstados() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/Listado_RegistrarEstados')) {
                //template data
                $this->template->set('title', 'Recepción Estudio de Títulos Avoque Conocimiento');
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

    function Titulo_ProximoPrescribir() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_titulo')) {
                //template data
                $id_opcion = $this->input->post('id_opcion');
                if (!empty($id_opcion)) {
                    switch ($id_opcion) {
                        case 1://183
                            $this->Traza_Proceso($this->input->post('cod_titulo'), TITULO_PROXIMO_PRESCRIBIR);
                            $datos['COD_RECEPCIONTITULO'] = $this->input->post('cod_titulo');
                            $datos['COD_TIPORESPUESTA'] = TITULO_PROXIMO_PRESCRIBIR;                            
                            $this->consultartitulos_model->actualizacion_estado_titulo($datos);
                            break;
                        default://184
                            $datos_persuasivo['COD_GESTION_COBRO']=$this->Traza_Proceso($this->input->post('cod_titulo'), TITULO_NO_PROXIMO_PRESCRIBIR);
                            $datos['COD_RECEPCIONTITULO'] = $this->input->post('cod_titulo');
                            $datos['COD_TIPORESPUESTA'] = TITULO_NO_PROXIMO_PRESCRIBIR; //titulo no preinscrito o vigente                            
                            $this->consultartitulos_model->actualizacion_estado_titulo($datos);
                            $info=$this->consultartitulos_model->get_titulotrazar($this->input->post('cod_titulo'));
                            $datos_persuasivo['NIT_EMPRESA']=$info["NIT_EMPRESA"];
                            $datos_persuasivo['COD_FISCALIZACION']=$info["COD_FISCALIZACION_EMPRESA"];
                            $datos_persuasivo['FECHA_CREACION'] = $this->input->post('fecha');
                            $datos_persuasivo['COD_ESTADO_PROCESO'] = '23';
                            $datos_persuasivo['COD_RECEPCIONTITULO'] = $this->input->post('cod_titulo');
                            $datos_persuasivo['COD_TIPO_RESPUESTA'] = TITULO_NO_PROXIMO_PRESCRIBIR; //titulo no preinscrito o vigente                            
                            $this->consultartitulos_model->insertar_cobro_persuasivo($datos_persuasivo);
                            break;
                    }
                    redirect(base_url() . 'index.php/recepciontitulos/Menu_TituloProximoPrescribir');
                } else {
                    $this->data["cod_titulo"] = $this->input->post('cod_titulo');
                    $this->data["class"] = '';
                    $this->template->set('title', 'Recepci�n Estudio de T�tulos Avoque Conocimiento');
                    $this->template->load($this->template_file, 'recepciontitulos/tituloproxprescribir_add', $this->data);
                }
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
                $datos["ABOGADO"] = $this->input->post('abogado');
                $datos["SECRETARIO"] = $this->input->post('secretario');
                $datos["EJECUTOR"] = $this->input->post('coordinador');
                $datos["NOMBRE_DOCUMENTO"] = $this->input->post('nombre_documento');
                $datos["COMENTARIOS"] = $this->input->post('comentarios');
                $datos["COD_RECEPCIONTITULO"] = $this->input->post('cod_titulo');
                $datos["COMENTADO_POR"] = COD_USUARIO;
                $this->consultartitulos_model->insertar_comunicacion($datos);
                /*
                 * ACTUALIZAR EN TABLA TITULOS ESTADO AL QUE ENTRO EL TITULO
                 */
                $datos2["COD_RECEPCIONTITULO"] = $this->input->post('cod_titulo');
                $datos2["COD_TIPORESPUESTA"] = $this->input->post('estado');
                $this->consultartitulos_model->actualizacion_estado_titulo($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->Traza_Proceso($this->input->post('cod_titulo'), $this->input->post('estado'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function insertar_notificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_respuesta')) {
                /*
                 * INSERTAR EN TABLA NOTIFICACIONES
                 */
                $datos["CREADO_POR"] = $this->input->post('coordinador');
                $datos["ENVIADO_POR"] = $this->input->post('abogado');
                $datos["COD_RECEPCIONTITULO"] = $this->input->post('cod_titulo');
                $datos["COD_RESPUESTA"] = $this->input->post('cod_respuesta');
                $datos["OBSERVACIONES"] = $this->input->post('observaciones');
                $this->consultartitulos_model->insertar_notificacion($datos);
                $this->Traza_Proceso($this->input->post('cod_titulo'), $this->input->post('cod_respuesta'));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizar_notificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                /*
                 * ACTUALIZAR TITULO EN TABLA NOTIFICACIONES
                 */
                $datos["COD_RECEPCIONTITULO"] = $this->input->post('cod_titulo');
                $datos["COD_TIPORESPUESTA"] = $this->input->post('cod_respuesta');                
                //$datos["OBSERVACIONES"] = $this->input->post('comentarios');
                $this->consultartitulos_model->actualizacion_estado_titulo($datos);
                $this->Traza_Proceso($this->input->post('cod_titulo'), $this->input->post('cod_respuesta'));
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

    function Soporte_Expediente() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                /*
                 * RECIBIR DATOS
                 */
                $cod_titulo = $this->input->post('cod_titulo');
                $comentarios = $this->input->post('comentarios');
                $fecha = $this->input->post('fecha');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                $cod_fiscalizacion = $info['COD_FISCALIZACION_EMPRESA'];
                $file = $this->do_multi_upload($cod_fiscalizacion, "Expediente"); //1- pdf y archivos de imagen
                $datos['COD_GESTION_COBRO'] = $this->Traza_Proceso($cod_titulo, $cod_respuesta);
                $datos["COD_RECEPCIONTITULO"] = $cod_titulo;
                $datos["OBSERVACIONES"] = $comentarios;
                $datos["CARGADO_POR"] = COD_USUARIO;
                $datos["FECHA_CARGA"] = $fecha;
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->consultartitulos_model->insertar_expediente_soporte($datos);
                }
                $datos2["COD_RECEPCIONTITULO"] = $cod_titulo;
                $datos2["COD_TIPORESPUESTA"] = $cod_respuesta;
                $this->consultartitulos_model->actualizacion_estado_titulo($datos2);
                redirect(base_url() . 'index.php/recepciontitulos/Menu_SubirExpediente');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
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

    function NotificarResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recepciontitulos/NotificarResolucion')) {
                $cod_titulo = $this->input->post('cod_titulo');
                $this->consultartitulos_model->set_titulo($cod_titulo);
                $this->data['titulo'] = $this->consultartitulos_model->get_documento_notificar();
                $this->consultartitulos_model->set_fiscalizacion($this->data['titulo']['COD_FISCALIZACION']);
                $this->data['correo'] = $this->consultartitulos_model->get_correo_autorizacion();
                $this->data['cod_titulo'] = $cod_titulo;
                $this->data["class"] = '';
                $this->template->set('title', 'Recepción Estudio de Títulos Avoque Conocimiento');
                $this->template->load($this->template_file, 'recepciontitulos/enviarnotificacion_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function NotificacionFisica() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_titulo')) {
                $cod_titulo = $this->input->post('cod_titulo');
                $entregado = $this->input->post('respuesta_recibida');
                $devuelto = $this->input->post('respuesta_devuelta');
                if (empty($entregado) and empty($devuelto)) {
                    $this->data['cod_titulo'] = $cod_titulo;
                    $this->data["class"] = '';
                    $this->data['causales'] = $this->consultartitulos_model->get_motivosdevolucion();
                    $this->template->set('title', 'Recepción Estudio de Títulos Avoque Conocimiento');
                    $this->template->load($this->template_file, 'recepciontitulos/notificacionenviada_add', $this->data);
                } else {
                    $nis = $this->input->post('nis');
                    $radicado = $this->input->post('num_radicado');
                    $datos["COD_RECEPCIONTITULO"] = $cod_titulo;
                    $datos["NIS"] = $nis;
                    $datos["NRO_RADICADO"] = $radicado;
                    if (!empty($entregado)) {
                        $fecha = $this->input->post('fecha');
                        $hora = $this->input->post('hora');
                        $minuto = $this->input->post('minutos');
                        $fecha = $fecha . " " . $hora . ":" . $minuto . ":00";
                        $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                        $file = $this->do_multi_upload($info["COD_FISCALIZACION_EMPRESA"], "Expediente_Notif");
                        $nombre = $this->input->post('nombre_receptor');
                        $cantidad_documentos = sizeof($file);
                        $datos["COD_RESPUESTA"] = $entregado;
                        $datos["FECHA_RECIBIDO"] = $fecha;
                        $datos["NOMBRE_RECEPTOR"] = $nombre;
                        for ($i = 0; $i < $cantidad_documentos; $i++) {
                            $datos["COLILLA_ENTREGA"] = $file[$i]["upload_data"]["file_name"];
                            //$this->consultartitulos_model->actualizacion_notifentregada($datos);
                        }
                    }
                    if (!empty($devuelto)) {
                        $fecha = $this->input->post('fecha_dev');
                        $hora = $this->input->post('hora');
                        $minuto = $this->input->post('minutos');
                        $fecha = $fecha . " " . $hora . ":" . $minuto . ":00";
                        $causal = $this->input->post('causal_devolucion');
                        $datos["COD_RESPUESTA"] = $devuelto;
                        $datos["FECHA_DEVOLUCION"] = $fecha;
                        $datos["COD_CAUSALDEVOLUCION"] = $causal;
                        //$this->consultartitulos_model->actualizacion_notifdevuelta($datos);
                    }
                    //VOLVER AL MENU
                   redirect(base_url() . 'index.php/recepciontitulos/Menu_NotificacionFisica');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function listado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('nit')) {
                $this->nit = $this->input->post('nit');
                $this->expediente = $this->input->post('expediente');
                $this->concepto = $this->input->post('concepto');
                $this->load->helper('form');
                $this->load->library('form_validation');

                $recepcion_id = $this->input->post('recepcion_id');
                $this->data['expediente'] = $this->expediente;
                $this->data['concepto'] = $this->concepto;
                // $this->data['concepto'] = 'multas del ministerio';
                // $this->data['concepto'] = 'doble mesada pensional';
                // $this->data['concepto'] = 'cuotas partes pensionales';
                // $this->data['concepto'] = 'contratos';

                $this->data['titulos'] = $this->consultartitulos_model->getTitulos($this->nit, $this->expediente);

                if ($this->input->post('complejo'))
                    $complejo = TRUE;
                else
                    $complejo = FALSE;
                if ($this->input->post('resolucion_titulo'))
                    $resolucion_titulo = TRUE;
                else
                    $resolucion_titulo = FALSE;
                if ($this->input->post('constancia_ejecutoria'))
                    $constancia_ejecutoria = TRUE;
                else
                    $constancia_ejecutoria = FALSE;
                if ($this->input->post('observaciones') != '')
                    $observaciones = $this->input->post('observaciones');
                else
                    $observaciones = '';
                if ($this->input->post('resolucion_pension_sena'))
                    $resolucion_pension_sena = TRUE;
                else
                    $resolucion_pension_sena = FALSE;
                if ($this->input->post('resolucion_reconocimiento_pension'))
                    $resolucion_reconocimiento_pension = TRUE;
                else
                    $resolucion_reconocimiento_pension = FALSE;
                if ($this->input->post('resolucion_perdidafuerza_ejecutoria_pension'))
                    $resolucion_perdidafuerza_ejecutoria_pension = TRUE;
                else
                    $resolucion_perdidafuerza_ejecutoria_pension = FALSE;
                if ($this->input->post('resolucion_recurso'))
                    $resolucion_recurso = TRUE;
                else
                    $resolucion_recurso = FALSE;
                if ($this->input->post('comunicacion_consulta_cuota'))
                    $comunicacion_consulta_cuota = TRUE;
                else
                    $comunicacion_consulta_cuota = FALSE;
                if ($this->input->post('comunicacion_acepta_cuota'))
                    $comunicacion_acepta_cuota = TRUE;
                else
                    $comunicacion_acepta_cuota = FALSE;
                if ($this->input->post('comunicacion_objeta_cuota'))
                    $comunicacion_objeta_cuota = TRUE;
                else
                    $comunicacion_objeta_cuota = FALSE;
                if ($this->input->post('contrato'))
                    $contrato = TRUE;
                else
                    $contrato = FALSE;
                if ($this->input->post('resolucion_incumplimiento_caducidad'))
                    $resolucion_incumplimiento_caducidad = TRUE;
                else
                    $resolucion_incumplimiento_caducidad = FALSE;
                if ($this->input->post('aceptar') == 'ACEPTAR') {
                    $this->form_validation->set_rules('resolucion_titulo', 'Resoluci�n Titulo Ejecutivo', 'required');
//                    $this->form_validation->set_rules('estado_cartera', 'Estado Cartera', 'required');
                }

                $this->data['complejo'] = array(
                    'name' => 'complejo',
                    'id' => 'complejo',
                    'value' => '1',
                    'checked' => $complejo,
                    'style' => 'margin:8px',
                );
                $this->data['resolucion_titulo'] = array(
                    'name' => 'resolucion_titulo',
                    'id' => 'resolucion_titulo',
                    'value' => '1',
                    'checked' => $resolucion_titulo,
                    'style' => 'margin:8px',
                );
                $this->data['constancia_ejecutoria'] = array(
                    'name' => 'constancia_ejecutoria',
                    'id' => 'constancia_ejecutoria',
                    'value' => '1',
                    'checked' => $constancia_ejecutoria,
                    'style' => 'margin:8px',
                );
                $this->data['observaciones'] = array(
                    'name' => 'observaciones',
                    'id' => 'observaciones',
                    'value' => $observaciones,
                    'cols' => '40',
                    'rows' => '3',
                    'style' => 'height: 50; width: 100%; margin-left: 0; background-color: #E6E6E6;'
                );
                if (strtolower($this->concepto) == 'multas del ministerio') {
                    
                } elseif (strtolower($this->concepto) == 'doble mesada pensional') {
                    $this->data['resolucion_pension_sena'] = array(
                        'name' => 'resolucion_pension_sena',
                        'id' => 'resolucion_pension_sena',
                        'value' => '1',
                        'checked' => $resolucion_pension_sena,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_reconocimiento_pension'] = array(
                        'name' => 'resolucion_reconocimiento_pension',
                        'id' => 'resolucion_reconocimiento_pension',
                        'value' => '1',
                        'checked' => $resolucion_reconocimiento_pension,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_perdidafuerza_ejecutoria_pension'] = array(
                        'name' => 'resolucion_perdidafuerza_ejecutoria_pension',
                        'id' => 'resolucion_perdidafuerza_ejecutoria_pension',
                        'value' => '1',
                        'checked' => $resolucion_perdidafuerza_ejecutoria_pension,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_recurso'] = array(
                        'name' => 'resolucion_recurso',
                        'id' => 'resolucion_recurso',
                        'value' => '1',
                        'checked' => $resolucion_recurso,
                        'style' => 'margin:8px',
                    );
                } elseif (strtolower($this->concepto) == 'cuotas partes pensionales') {
                    $this->data['comunicacion_consulta_cuota'] = array(
                        'name' => 'comunicacion_consulta_cuota',
                        'id' => 'comunicacion_consulta_cuota',
                        'value' => '1',
                        'checked' => $comunicacion_consulta_cuota,
                        'style' => 'margin:8px',
                    );
                    $this->data['comunicacion_acepta_cuota'] = array(
                        'name' => 'comunicacion_acepta_cuota',
                        'id' => 'comunicacion_acepta_cuota',
                        'value' => '1',
                        'checked' => $comunicacion_acepta_cuota,
                        'style' => 'margin:8px',
                    );
                    $this->data['comunicacion_objeta_cuota'] = array(
                        'name' => 'comunicacion_objeta_cuota',
                        'id' => 'comunicacion_objeta_cuota',
                        'value' => '1',
                        'checked' => $comunicacion_objeta_cuota,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_pension_sena'] = array(
                        'name' => 'resolucion_pension_sena',
                        'id' => 'resolucion_pension_sena',
                        'value' => '1',
                        'checked' => $resolucion_pension_sena,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_reconocimiento_pension'] = array(
                        'name' => 'resolucion_reconocimiento_pension',
                        'id' => 'resolucion_reconocimiento_pension',
                        'value' => '1',
                        'checked' => $resolucion_reconocimiento_pension,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_perdidafuerza_ejecutoria_pension'] = array(
                        'name' => 'resolucion_perdidafuerza_ejecutoria_pension',
                        'id' => 'resolucion_perdidafuerza_ejecutoria_pension',
                        'value' => '1',
                        'checked' => $resolucion_perdidafuerza_ejecutoria_pension,
                        'style' => 'margin:8px',
                    );
                } elseif (strtolower($this->concepto) == 'contratos') {
                    $this->data['contrato'] = array(
                        'name' => 'contrato',
                        'id' => 'contrato',
                        'value' => '1',
                        'checked' => $contrato,
                        'style' => 'margin:8px',
                    );
                    $this->data['resolucion_incumplimiento_caducidad'] = array(
                        'name' => 'resolucion_incumplimiento_caducidad',
                        'id' => 'resolucion_incumplimiento_caducidad',
                        'value' => '1',
                        'checked' => $resolucion_incumplimiento_caducidad,
                        'style' => 'margin:8px',
                    );
                }

                $Rempresa = $this->consultartitulos_model->Empresa($this->nit);
                foreach ($Rempresa->result_array() as $empresa) {
                    $this->data['razonsocial'] = $empresa['RAZON_SOCIAL'];
                    $this->data['tipodoc'] = $empresa['NOMBRETIPODOC'];
                    $this->data['nit'] = $this->nit;
                }
                
                $this->data['comentarios'] = $this->consultartitulos_model->historico($recepcion_id);

                $this->form_validation->set_rules('observaciones', 'Observaciones', 'required|trim|xss_clean');
                $resolucion_titulotitulo = ($resolucion_titulo == TRUE) ? 'S' : 'N';
                $fecha = date('Y-m-d');

                if ($this->form_validation->run() === FALSE) {
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
                    redirect(base_url() . 'index.php/recepciontitulos/index');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/recepciontitulos');
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
                /*
                 * INFORMACION PARA TRAZA
                 */
                $info = $this->consultartitulos_model->get_titulotrazar($cod_titulo);
                /*
                 * Si Avoca Generar el numero para el expediente
                 */
                if ($cod_respuesta == NO_ACUMULACION_TITULOS) {
                    $proceso = $this->consultartitulos_model->get_codprocesojuridico($cod_titulo);
                    $cod_proceso = $proceso['COD_REGIONAL'] . '-' . $proceso['COD_CPTO_FISCALIZACION'] . '-' . $proceso['CODTIPOENTIDAD'] . '-' . $proceso['FECHA'] . '-' . $proceso['COD_RECEPCIONTITULO'] . '-' . $proceso['COD_FISCALIZACION'];
                    $dato['CODIGO_PJ'] = $cod_proceso;
                    $dato['COD_FISCALIZACION'] = $info['COD_FISCALIZACION_EMPRESA'];
                    $this->consultartitulos_model->actualizacion_fiscalizacion($dato);
                }
                /*
                 * INSERTAR TRAZA
                 */
                $this->Traza_Proceso($cod_titulo, $cod_respuesta);
                /*
                 * ACTUALIZAR TABLA RECEPCIONTITULOS
                 */
                $datos_actualizacion['COD_RECEPCIONTITULO'] = $cod_titulo;
                $datos_actualizacion['COD_TIPORESPUESTA'] = $cod_respuesta;
                $datos_actualizacion['PROCESO_REORGANIZACION'] = $reorganizacion;
                $datos_actualizacion['TITULO_PRESCRITO'] = $tiempo;
                $datos_actualizacion['COMENTARIOS_EXIGIBILIDAD'] = $comentarios_exigibilidad;
                $datos_actualizacion['COMENTARIOS_REORGANIZACION'] = $comentarios_reorganizacion;
                $this->consultartitulos_model->actualizacion_estado_titulo($datos_actualizacion);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/recepciontitulos');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;

        $estructura = "uploads/recepciontitulos/" . $post['fiscalizacion'] . "/";
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

        ob_clean();
        $pdf = new Plantilla_PDF_Remisibilidad(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->setFooterData();
        $pdf->AddPage('P', 'pt', 'Legal');
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($nombre_pdf . '.pdf', 'D');
        exit();
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
        $config['allowed_types'] = '*';
        $config['max_size'] = '2048';
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

}
