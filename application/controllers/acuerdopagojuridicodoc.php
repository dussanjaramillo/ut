<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acuerdopagojuridicodoc extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('acuerdopagodocjuridico_model', '', TRUE);
        $this->load->model('modelacuerdodepagojuridico_model');
        $this->load->model('plantillas_model');
        $this->load->file(APPPATH . "controllers/verificarpagos.php", true);
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen','css/validationEngine.jquery.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js','js/jquery.dataTables.defaults.js','js/jquery.validationEngine-es.js','js/jquery.validationEngine.js','js/validateForm.js','js/tinymce/tinymce.jquery.min.js');
        define("ABOGADO_RELACIONES", '44');
//        define("COORDINADOR_RELACIONES", '144');
        
        $sesion = $this->session->userdata;       
        define("COORDINADOR_RELACIONES", $sesion['id_coordinador_relaciones']);

        
        define("NOMBRE_ABOGADO", 'Abogados relaciones corporativas');
        define("NOMBRE_COORDINADOR", 'Coordinador Relaciones Corporativas');

        $this->data['user'] = $this->ion_auth->user()->row();
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);

        define("TIPO_1", "Resolución por la cual se declara el incumplimiento de la facilidad de pago");
        define("TIPO_2", "Resolución que Resuelve Recurso");
        define("TIPO_3", "Citación Para Notificar Resolución que resuelve recurso");//define("TIPO_3", "Citación Para Notificar Recurso");//Resolución de Reanudación de la Facilidad de Pago
        define("TIPO_4", "Constancia de ejecutoria de Resolución que declara el incumplimiento de la facilidad de pago");
        define("TIPO_5", "Auto de Terminacion y cierre de Proceso");

        define("AUTO_MOTIVADO_GENERADO", "1275");
        define("AUTO_MOTIVADO_CORREGIDO", "1278");
        define("AUTO_MOTIVADO_APROBADO", "1508");
        define("AUTO_MOTIVADO_RECHAZADO", "1276");
        define("AUTO_MOTIVADO_FIRMADO", "1277");
        define("AUTO_MOTIVADO_SUBIDO", "1279");

        define("RESOLUCION_QUE_RESUELVE_GENERADO", "1280");
        define("RESOLUCION_QUE_RESUELVE_CORREGIDO", "1283");
        define("RESOLUCION_QUE_RESUELVE_APROBADO", "1509");
        define("RESOLUCION_QUE_RESUELVE_RECHAZADO", "1281");
        define("RESOLUCION_QUE_RESUELVE_FIRMADO", "1282");
        define("RESOLUCION_QUE_RESUELVE_SUBIDO", "1284");

        define("AUTO_REANUDACION_GENERADO", "1285");
        define("AUTO_REANUDACION_CORREGIDO", "1288");
        define("AUTO_REANUDACION_APROBADO", "1510");
        define("AUTO_REANUDACION_RECHAZADO", "1286");
        define("AUTO_REANUDACION_FIRMADO", "1287");
        define("AUTO_REANUDACION_SUBIDO", "1289");

        define("AUTO_QUE_ORDENA_GENERADO", "1290");
        define("AUTO_QUE_ORDENA_CORREGIDO", "1293");
        define("AUTO_QUE_ORDENA_APROBADO", "1511");
        define("AUTO_QUE_ORDENA_RECHAZADO", "1291");
        define("AUTO_QUE_ORDENA_FIRMADO", "1292");
        define("AUTO_QUE_ORDENA_SUBIDO", "1294");
        
        define("AUTO_CIERRE_GENERADO", "1314");
        define("AUTO_CIERRE_CORREGIDO", "1317");
        define("AUTO_CIERRE_APROBADO", "1512");
        define("AUTO_CIERRE_RECHAZADO", "1315");
        define("AUTO_CIERRE_FIRMADO", "1316");
        define("AUTO_CIERRE_SUBIDO", "1318");

        define("PLANTILLA_AUTOMOTIVADO", "63");
        define("PLANTILLA_RESOLUCION", "63");
        define("PLANTILLA_REANUDACION", "63");
        define("PLANTILLA_AUTOORDENA", "63");
        define("PLANTILLA_CIERREPROCESO", "63");
        
        $sesion = $this->session->userdata;
        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);        
        
        $this->data['message'] = $this->session->flashdata('message');
    }

    function index() {
        $this->Menu_GestionAbogado();
    }


    /*
     * CREAR AUTO MOTIVADO PARA DEJAR SIN EFECTO ACUERDO DE PAGO
     */

    function Crear_AutoMotivado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_AUTOMOTIVADO, TIPO_1, AUTO_MOTIVADO_GENERADO, 'acuerdopagodocjuridico/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoMotivado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_MOTIVADO_CORREGIDO, 0, 'acuerdopagodocjuridico/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Firmar_AutoMotivado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                        $acuerdo = $this->input->post('acuerdo');
                        $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                        $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_MOTIVADO_FIRMADO, AUTO_MOTIVADO_RECHAZADO, 'acuerdopagodocjuridico/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
     function Firmar_AutoCierre() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_CIERRE_FIRMADO, AUTO_CIERRE_RECHAZADO, 'acuerdopagodocjuridico/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoMotivado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_1, AUTO_MOTIVADO_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Subir_AutoCierre() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_5, AUTO_CIERRE_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR RESOLUCION QUE RESULEVE RECURSO
     */

    function Crear_ResolucionResuelve() {
        if ($this->ion_auth->logged_in()) {            
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_RESOLUCION, TIPO_2, RESOLUCION_QUE_RESUELVE_GENERADO, 'acuerdopagodocjuridico/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Crear_AutoCierre() {
        if ($this->ion_auth->logged_in()) {            
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_CIERREPROCESO, TIPO_5, AUTO_CIERRE_GENERADO, 'acuerdopagodocjuridico/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_ResolucionResuelve() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), RESOLUCION_QUE_RESUELVE_CORREGIDO, 0, 'acuerdopagodocjuridico/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Aprobar_AutoMotivado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                        $acuerdo = $this->input->post('acuerdo');
                        $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                        $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_MOTIVADO_APROBADO, AUTO_MOTIVADO_RECHAZADO, 'acuerdopagodocjuridico/preaprobardocumento');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Aprobar_AutoCierre() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_CIERRE_APROBADO, AUTO_CIERRE_RECHAZADO, 'acuerdopagodocjuridico/preaprobardocumento');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

  function Aprobar_AutoOrdena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_4, $this->input->post('cod_coactivo'), AUTO_QUE_ORDENA_FIRMADO, AUTO_QUE_ORDENA_RECHAZADO, 'acuerdopagodocjuridico/preaprobardocumento');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }


    function Aprobar_AutoReanudacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_3, $this->input->post('cod_coactivo'), AUTO_REANUDACION_APROBADO, AUTO_REANUDACION_RECHAZADO, 'acuerdopagodocjuridico/preaprobardocumento');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function Aprobar_ResolucionResuelve() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_3, $this->input->post('cod_coactivo'), RESOLUCION_QUE_RESUELVE_APROBADO, AUTO_REANUDACION_RECHAZADO, 'acuerdopagodocjuridico/preaprobardocumento');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    
    function Corregir_AutoCierre() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), AUTO_CIERRE_CORREGIDO, 0, 'acuerdopagodocjuridico/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Firmar_ResolucionResuelve() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), RESOLUCION_QUE_RESUELVE_FIRMADO, RESOLUCION_QUE_RESUELVE_RECHAZADO, 'acuerdopagodocjuridico/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ResolucionResuelve() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_2, RESOLUCION_QUE_RESUELVE_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR AUTO DE REANUDACION 
     */

    function Crear_AutoReanudacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_REANUDACION, TIPO_3, AUTO_REANUDACION_GENERADO, 'acuerdopagodocjuridico/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoReanudacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_3, $this->input->post('cod_coactivo'), AUTO_REANUDACION_CORREGIDO, 0, 'acuerdopagodocjuridico/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Firmar_AutoReanudacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_3, $this->input->post('cod_coactivo'), AUTO_REANUDACION_FIRMADO, AUTO_REANUDACION_RECHAZADO, 'acuerdopagodocjuridico/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoReanudacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_3, AUTO_REANUDACION_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR AUTO QUE ORDENA HACER EFECTIVAS LAS GARANTIAS
     */

    function Crear_AutoOrdena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_AUTOORDENA, TIPO_4, AUTO_QUE_ORDENA_GENERADO, 'acuerdopagodocjuridico/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoOrdena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_4, $this->input->post('cod_coactivo'), AUTO_QUE_ORDENA_CORREGIDO, 0, 'acuerdopagodocjuridico/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Firmar_AutoOrdena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepagojuridico_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_4, $this->input->post('cod_coactivo'), AUTO_QUE_ORDENA_FIRMADO, AUTO_QUE_ORDENA_RECHAZADO, 'acuerdopagodocjuridico/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoOrdena() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_4, AUTO_QUE_ORDENA_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }


    function Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta) {
        if ($this->ion_auth->logged_in()) {
            /*
             * CONSULTAR DOCUMENTO PARA IMPRIMIR
             */
            $documento = $this->acuerdopagodocjuridico_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/acuerdopagodocjuridico/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Acuerdo de Pago');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'acuerdopagodocjuridico/subirexpediente_add', $this->data);
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
                $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                $datos['FECHA_RADICADO'] = $fecha;
                $datos['NUMERO_RADICADO'] = $numero;
                $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos["ID_USUARIO"] = COD_USUARIO;
                $datos["CODIGO_NIS"] = $this->input->post('numero_nis');
                /*
                 * OBTENER NOMBRE DEL ARCHIVO
                 */
                $file = $this->do_multi_upload($cod_coactivo, "Expediente"); //1- pdf y archivos de imagen
              
                switch ($cod_respuesta) {
                    case AUTO_MOTIVADO_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_1;
                        break;
                    case AUTO_QUE_ORDENA_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_4;
                        break;
                    case RESOLUCION_QUE_RESUELVE_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 3;
                        $datos['SUB_PROCESO'] = TIPO_2;
                        break;
                    case AUTO_REANUDACION_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_3;
                        break;
                    case AUTO_CIERRE_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_5;
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/acuerdopagodocjuridico/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->acuerdopagodocjuridico_model->insertar_expediente($datos);
                }
                /*
                 * ACTUALIZAR EL ESTADO DEL ACUERDO DE PAGO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->acuerdopagodocjuridico_model->actualizacion_acuerdopago($datos2);
                /*
                 * INSERTAR EN LA TRAZA DEL PROCESO
                 */
                trazarProcesoJuridico(528,$cod_respuesta,'',$cod_coactivo,'', '', '', $comentarios = 'Subir al Expediente', $usuariosAdicionales = '');                        	
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Expediente -> Facilidad de Pago');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $eliminar = $this->acuerdopagodocjuridico_model->eliminar_plantilla($cod_coactivo);
                $this->data['archivos'] = $this->acuerdopagodocjuridico_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'acuerdopagodocjuridico/comprobacionexp_add', $this->data);
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
            $documento = $this->acuerdopagodocjuridico_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/acuerdopagodocjuridico/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('acuerdopagodocjuridico/visualizardocumento_add', $this->data);
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
                $info_soporte = $this->acuerdopagodocjuridico_model->get_documentosexpediente($soporte);
                $estructura = "./" . $info_soporte[0]["RUTA_DOCUMENTO"];
                if (file_exists($estructura)) {
                    unlink($estructura);
                }
                $this->acuerdopagodocjuridico_model->eliminar_soporte($cod_soporte);
                /*
                 * VOLVER A VER LOS DOCUMENTOS
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Acuerdo de Pago');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->acuerdopagodocjuridico_model->get_documentosexpediente($verificar);
                if ($cod_respuesta === AUTO_MOTIVADO_SUBIDO) {
                    $tipo = TIPO_1;
                } else if ($cod_respuesta === RESOLUCION_QUE_RESUELVE_SUBIDO) {
                    $tipo = TIPO_2;
                } else if ($cod_respuesta === AUTO_REANUDACION_SUBIDO) {
                    $tipo = TIPO_3;
                } else if ($cod_respuesta === AUTO_QUE_ORDENA_SUBIDO) {
                    $tipo = TIPO_4;
                }
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'acuerdopagodocjuridico/comprobacionexp_add', $this->data);
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
            if ($this->ion_auth->is_admin() || $this->input->post('cod_soporte')) {
                /*
                 * Recibir los Datos
                 */
                $cod_coactivo = $this->input->post('cod_coactivo');
                /*
                 * Eliminar el Archivo de la BD y del server
                 */
                $plantillas = $this->acuerdopagodocjuridico_model->get_plantillascod_coactivo($cod_coactivo);
                for ($i = 0; $i < sizeof($plantillas); $i++) {
                    $estructura = "./uploads/acuerdopagodocjuridico/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->acuerdopagodocjuridico_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                $this->Menu_GestionAbogado();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
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

    function Crear_Documentos($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta) {   
        if ($this->ion_auth->logged_in()) {
            /*
             * TRER EL CODIGO DEL PROCESO PARA VISUALIZAR
             */
//            $consecutivo = $this->acuerdopagodocjuridico_model->get_numprocesoadjudicado($cod_coactivo);
//            
//            $cod_proceso = $consecutivo['CODIGO_PJ'];
            $cod_proceso = $cod_coactivo;
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR SECRETARIOS PARA ASIGNAR
             * CONSULTAR PLANTILLA
             */
            $encabezado = $this->acuerdopagodocjuridico_model->get_procesotransversal(FALSE, $cod_coactivo,$cod_respuesta);
            $this->data['secretario'] = $this->acuerdopagodocjuridico_model->get_coordinador_regional();
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
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->data['cod_proceso'] = $cod_proceso;
            $this->template->set('title', 'Acuerdo de Pago');
            $this->template->load($this->template_file, $ruta, $this->data);  
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Proceso_Documento($tipo, $cod_coactivo, $cod_respuesta1, $cod_respuesta2, $ruta) {
        if ($this->ion_auth->logged_in()) {           /*
             * TRER EL CODIGO DEL PROCESO PARA VISUALIZAR
             */
            //$consecutivo = $this->acuerdopagodocjuridico_model->get_numprocesoadjudicado($cod_coactivo);
             $cod_proceso = $cod_coactivo;
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR DIRECTOR REGIONAL O COORDINADOR ASIGNADO
             * CONSULTAR PLANTILLA URL PLANTILLA
             */
            $encabezado = $this->acuerdopagodocjuridico_model->get_procesotransversal(TRUE, $cod_coactivo,$cod_respuesta2);
            $this->data['documento'] = $this->acuerdopagodocjuridico_model->get_plantilla_proceso($cod_coactivo);
            /*
             * TRAER COMENTARIOS Y SUS ACTORES
             */
            $this->data['comentario'] = $this->acuerdopagodocjuridico_model->get_comentarios_proceso($cod_coactivo);
            /*
             * ENVIO DE DATOS VISUALIZACION DE VISTA
             */
            $abogado = $this->acuerdopagodocjuridico_model->get_abogado_proceso($cod_coactivo);
            $secretario = $this->acuerdopagodocjuridico_model->get_secretario_proceso($cod_coactivo);
            $coordinador = $this->acuerdopagodocjuridico_model->get_coordinador_proceso($cod_coactivo);
            $this->data['nombre_a'] = $abogado['NOMBRES'] . " " . $abogado['APELLIDOS'];
            $this->data['nombre_s'] = $secretario['NOMBRES'] . " " . $secretario['APELLIDOS'];
            $this->data['nombre_c'] = $coordinador['NOMBRES'] . " " . $coordinador['APELLIDOS'];
            $this->data['secretario'] = $this->data['documento']['SECRETARIO'];
            $this->data['coordinador'] = $this->data['documento']['EJECUTOR'];
            $this->data['abogado'] = $this->data['documento']['ABOGADO'];
            $this->data['titulo_encabezado'] = $this->data['documento']['TITULO_ENCABEZADO'];
            /*
             * TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR
             */
            $urlplantilla2 = "uploads/acuerdopagodocjuridico/" . $cod_coactivo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->template->set('title', 'Acuerdo de Pago');
            $this->template->load($this->template_file, $ruta, $this->data);
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
                $acuerdo = $this->input->post('acuerdo');
                
                $this->acuerdopagodocjuridico_model->insertar_comunicacion($datos);
                /*
                 * ACTUALIZAR SI ES UN ACUERDO DE PAGO Q ESTA EN PROCESO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                $datos2["USUARIO_ASIGNADO"] = $this->input->post('asignar');
                if ($this->input->post('estado') == 1289 || $this->input->post('estado') == 1287)
                    $datos2["AUTO_ACUERDO"] = 'N'; 
                else 
                    $datos2["AUTO_ACUERDO"] = 'S';
                
                
                $this->acuerdopagodocjuridico_model->actualizacion_acuerdopago($datos2);
                
                /*
                 * INSERTAR TRAZA
                 */
                trazarProcesoJuridico(528,$this->input->post('estado'),'',$this->input->post('cod_coactivo'),'', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');                        	
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

        $estructura = "uploads/acuerdopagodocjuridico/" . $post['cod_coactivo'] . "/";
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
        $data[0] = $tipo;
        $data[1] = $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    private function do_multi_upload($cod_coactivo, $carpeta) {
        $estructura = "./uploads/acuerdopagodocjuridico/" . $cod_coactivo . "/" . $carpeta . "/";
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
