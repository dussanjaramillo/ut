<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acuerdopagodoc extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('acuerdopagodoc_model', '', TRUE);
        $this->load->model('modelacuerdodepago_model');
        $this->load->model('plantillas_model');
        $this->load->file(APPPATH . "controllers/verificarpagos.php", true);
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
        define("ABOGADO_RELACIONES", '44');
//        define("COORDINADOR_RELACIONES", '144');
        
        $sesion = $this->session->userdata;       
        define("COORDINADOR_RELACIONES", $sesion['id_coordinador_relaciones']);

        
        define("NOMBRE_ABOGADO", 'Abogados relaciones corporativas');
        define("NOMBRE_COORDINADOR", 'Coordinador Relaciones Corporativas');

        $this->data['user'] = $this->ion_auth->user()->row();
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);

        define("TIPO_1", "Resolución que deja sin efecto la Facilidad de Pago");
        define("TIPO_2", "Resolucion que Resuelve Recurso");
        define("TIPO_3", "Auto de Reanudación de la Faciliad de Pago");
        define("TIPO_4", "Auto que Ordena Hacer Efectivas las Garantías");
        define("TIPO_5", "Auto de Terminacion y cierre de Proceso");

        define("AUTO_MOTIVADO_GENERADO", "1275");
        define("AUTO_MOTIVADO_CORREGIDO", "1278");
        define("AUTO_MOTIVADO_RECHAZADO", "1276");
        define("AUTO_MOTIVADO_FIRMADO", "1277");
        define("AUTO_MOTIVADO_SUBIDO", "1279");

        define("RESOLUCION_QUE_RESUELVE_GENERADO", "1280");
        define("RESOLUCION_QUE_RESUELVE_CORREGIDO", "1283");
        define("RESOLUCION_QUE_RESUELVE_RECHAZADO", "1281");
        define("RESOLUCION_QUE_RESUELVE_FIRMADO", "1282");
        define("RESOLUCION_QUE_RESUELVE_SUBIDO", "1284");

        define("AUTO_REANUDACION_GENERADO", "1285");
        define("AUTO_REANUDACION_CORREGIDO", "1288");
        define("AUTO_REANUDACION_RECHAZADO", "1286");
        define("AUTO_REANUDACION_FIRMADO", "1287");
        define("AUTO_REANUDACION_SUBIDO", "1289");

        define("AUTO_QUE_ORDENA_GENERADO", "1290");
        define("AUTO_QUE_ORDENA_CORREGIDO", "1293");
        define("AUTO_QUE_ORDENA_RECHAZADO", "1291");
        define("AUTO_QUE_ORDENA_FIRMADO", "1292");
        define("AUTO_QUE_ORDENA_SUBIDO", "1294");
        
        define("AUTO_CIERRE_GENERADO", "1314");
        define("AUTO_CIERRE_CORREGIDO", "1317");
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
        define("ID_COORDINADOR", $sesion['id_coordinador_relaciones']);        
        


        $this->data['message'] = $this->session->flashdata('message');
    }

    function index() {
        $this->Menu_GestionAbogado();
    }

    function Traza_Proceso($cod_fiscalizacion, $cod_respuesta, $comentarios) {
        /*
         * FUNCION DE TRAZA
         */
        $traza = $this->acuerdopagodoc_model->get_titulotrazar($cod_fiscalizacion);
        $gestion = $this->acuerdopagodoc_model->get_trazagestion($cod_respuesta);
        $this->datos['idgestioncobro'] = trazar($gestion["COD_TIPOGESTION"], $gestion["COD_RESPUESTA"], $cod_fiscalizacion, $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios);
        $idgestioncobro = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
        return $idgestioncobro;
    }

    /*
     * CREAR AUTO MOTIVADO PARA DEJAR SIN EFECTO ACUERDO DE PAGO
     */

    function Crear_AutoMotivado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_fiscalizacion')) {
                $this->Crear_Documentos($this->input->post('cod_fiscalizacion'), PLANTILLA_AUTOMOTIVADO, TIPO_1, AUTO_MOTIVADO_GENERADO, 'acuerdopagodoc/elaborardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('fiscalizacion'), AUTO_MOTIVADO_CORREGIDO, 0, 'acuerdopagodoc/corregirdocumentoabo_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                        $acuerdo = $this->input->post('acuerdo');
                        $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
                        $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_1, $this->input->post('fiscalizacion'), AUTO_MOTIVADO_FIRMADO, AUTO_MOTIVADO_RECHAZADO, 'acuerdopagodoc/aprobardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_1, $this->input->post('fiscalizacion'), AUTO_CIERRE_FIRMADO, AUTO_CIERRE_RECHAZADO, 'acuerdopagodoc/aprobardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Agregar_Expediente($this->input->post('fiscalizacion'), TIPO_1, AUTO_MOTIVADO_SUBIDO);
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Agregar_Expediente($this->input->post('fiscalizacion'), TIPO_5, AUTO_CIERRE_SUBIDO);
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Crear_Documentos($this->input->post('fiscalizacion'), PLANTILLA_RESOLUCION, TIPO_2, RESOLUCION_QUE_RESUELVE_GENERADO, 'acuerdopagodoc/elaborardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Crear_Documentos($this->input->post('fiscalizacion'), PLANTILLA_CIERREPROCESO, TIPO_5, AUTO_CIERRE_GENERADO, 'acuerdopagodoc/elaborardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('fiscalizacion'), RESOLUCION_QUE_RESUELVE_CORREGIDO, 0, 'acuerdopagodoc/corregirdocumentoabo_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('fiscalizacion'), AUTO_CIERRE_CORREGIDO, 0, 'acuerdopagodoc/corregirdocumentoabo_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_2, $this->input->post('fiscalizacion'), RESOLUCION_QUE_RESUELVE_FIRMADO, RESOLUCION_QUE_RESUELVE_RECHAZADO, 'acuerdopagodoc/aprobardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Agregar_Expediente($this->input->post('fiscalizacion'), TIPO_2, RESOLUCION_QUE_RESUELVE_SUBIDO);
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Crear_Documentos($this->input->post('fiscalizacion'), PLANTILLA_REANUDACION, TIPO_3, AUTO_REANUDACION_GENERADO, 'acuerdopagodoc/elaborardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Proceso_Documento(TIPO_3, $this->input->post('fiscalizacion'), AUTO_REANUDACION_CORREGIDO, 0, 'acuerdopagodoc/corregirdocumentoabo_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_3, $this->input->post('fiscalizacion'), AUTO_REANUDACION_FIRMADO, AUTO_REANUDACION_RECHAZADO, 'acuerdopagodoc/aprobardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Agregar_Expediente($this->input->post('fiscalizacion'), TIPO_3, AUTO_REANUDACION_SUBIDO);
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Crear_Documentos($this->input->post('fiscalizacion'), PLANTILLA_AUTOORDENA, TIPO_4, AUTO_QUE_ORDENA_GENERADO, 'acuerdopagodoc/elaborardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Proceso_Documento(TIPO_4, $this->input->post('fiscalizacion'), AUTO_QUE_ORDENA_CORREGIDO, 0, 'acuerdopagodoc/corregirdocumentoabo_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $acuerdo = $this->input->post('acuerdo');
                $usuario_asignado = $this->modelacuerdodepago_model->consulta_acuerdo($acuerdo);
                $this->data['abogado'] = $usuario_asignado[0]['USUARIO_GENERA']; 
                $this->Proceso_Documento(TIPO_4, $this->input->post('fiscalizacion'), AUTO_QUE_ORDENA_FIRMADO, AUTO_QUE_ORDENA_RECHAZADO, 'acuerdopagodoc/aprobardocumento_add');
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
            if ($this->ion_auth->is_admin() || $this->input->post('fiscalizacion')) {
                $this->Agregar_Expediente($this->input->post('fiscalizacion'), TIPO_4, AUTO_QUE_ORDENA_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }


    function Agregar_Expediente($cod_fiscalizacion, $tipo, $cod_respuesta) {
        if ($this->ion_auth->logged_in()) {
            /*
             * CONSULTAR DOCUMENTO PARA IMPRIMIR
             */
            $documento = $this->acuerdopagodoc_model->get_plantilla_proceso($cod_fiscalizacion);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/acuerdopagodoc/" . $cod_fiscalizacion . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Acuerdo de Pago');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_fiscalizacion'] = $cod_fiscalizacion;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'acuerdopagodoc/subirexpediente_add', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Soporte_Expediente() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_fiscalizacion')) {
                /*
                 * RECIBIR DATOS
                 */
                $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
                $fecha = $this->input->post('fecha');
                $numero = $this->input->post('numero');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                $datos['FECHA_RADICADO'] = $fecha;
                $datos['NUMERO_RADICADO'] = $numero;
                $datos["COD_FISCALIZACION"] = $cod_fiscalizacion;
                $datos["ID_USUARIO"] = COD_USUARIO;
                $datos["CODIGO_NIS"] = $this->input->post('numero_nis');
                /*
                 * OBTENER NOMBRE DEL ARCHIVO
                 */
                $file = $this->do_multi_upload($cod_fiscalizacion, "Expediente"); //1- pdf y archivos de imagen
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
                    $datos["RUTA_DOCUMENTO"] = "uploads/acuerdopagodoc/" . $cod_fiscalizacion . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->acuerdopagodoc_model->insertar_expediente($datos);
                }
                /*
                 * ACTUALIZAR EL ESTADO DEL ACUERDO DE PAGO
                 */
                $datos2["COD_FISCALIZACION"] = $cod_fiscalizacion;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->acuerdopagodoc_model->actualizacion_acuerdopago($datos2);
                /*
                 * INSERTAR EN LA TRAZA DEL PROCESO
                 */
                $this->Traza_Proceso($cod_fiscalizacion, $cod_respuesta, 'Agregar Soporte Al Expediente de Acuerdo de Pago');
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Expediente -> Acuerdo de Pago');
                $verificar[0] = $cod_fiscalizacion;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_fiscalizacion'] = $cod_fiscalizacion;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->acuerdopagodoc_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'acuerdopagodoc/comprobacionexp_add', $this->data);
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
            $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
            $documento = $this->acuerdopagodoc_model->get_plantilla_proceso($cod_fiscalizacion);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/acuerdopagodoc/" . $cod_fiscalizacion . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('acuerdopagodoc/visualizardocumento_add', $this->data);
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
                $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /*
                 * Eliminar el Archivo de la BD y del server
                 */
                $soporte[0] = $cod_fiscalizacion;
                $soporte[1] = $cod_respuesta;
                $info_soporte = $this->acuerdopagodoc_model->get_documentosexpediente($soporte);
                $estructura = "./" . $info_soporte[0]["RUTA_DOCUMENTO"];
                if (file_exists($estructura)) {
                    unlink($estructura);
                }
                $this->acuerdopagodoc_model->eliminar_soporte($cod_soporte);
                /*
                 * VOLVER A VER LOS DOCUMENTOS
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Acuerdo de Pago');
                $verificar[0] = $cod_fiscalizacion;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_fiscalizacion'] = $cod_fiscalizacion;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->acuerdopagodoc_model->get_documentosexpediente($verificar);
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
                    $this->Agregar_Expediente($cod_fiscalizacion, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'acuerdopagodoc/comprobacionexp_add', $this->data);
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
                $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
                /*
                 * Eliminar el Archivo de la BD y del server
                 */
                $plantillas = $this->acuerdopagodoc_model->get_plantillasfiscalizacion($cod_fiscalizacion);
                for ($i = 0; $i < sizeof($plantillas); $i++) {
                    $estructura = "./uploads/acuerdopagodoc/" . $cod_fiscalizacion . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->acuerdopagodoc_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
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

    function Crear_Documentos($cod_fiscalizacion, $cod_plantilla, $tipo, $cod_respuesta, $ruta) {   
        
        if ($this->ion_auth->logged_in()) {
            /*
             * TRER EL CODIGO DEL PROCESO PARA VISUALIZAR
             */
            $consecutivo = $this->acuerdopagodoc_model->get_numprocesoadjudicado($cod_fiscalizacion);
            
            $cod_proceso = $consecutivo['CODIGO_PJ'];
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR SECRETARIOS PARA ASIGNAR
             * CONSULTAR PLANTILLA
             */
            $encabezado = $this->acuerdopagodoc_model->get_procesotransversal(FALSE, $cod_fiscalizacion);
            $this->data['secretario'] = $this->acuerdopagodoc_model->get_coordinador_regional();
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
            $this->data['cod_fiscalizacion'] = $cod_fiscalizacion;
            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->data['cod_proceso'] = $cod_proceso;
            $this->template->set('title', 'Acuerdo de Pago');
            $this->template->load($this->template_file, $ruta, $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Proceso_Documento($tipo, $cod_fiscalizacion, $cod_respuesta1, $cod_respuesta2, $ruta) {
        if ($this->ion_auth->logged_in()) {
                        /*
             * TRER EL CODIGO DEL PROCESO PARA VISUALIZAR
             */
            $consecutivo = $this->acuerdopagodoc_model->get_numprocesoadjudicado($cod_fiscalizacion);
            $cod_proceso = $consecutivo['CODIGO_PJ'];
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR DIRECTOR REGIONAL O COORDINADOR ASIGNADO
             * CONSULTAR PLANTILLA URL PLANTILLA
             */
            $encabezado = $this->acuerdopagodoc_model->get_procesotransversal(TRUE, $cod_fiscalizacion);
            $this->data['documento'] = $this->acuerdopagodoc_model->get_plantilla_proceso($cod_fiscalizacion);
            /*
             * TRAER COMENTARIOS Y SUS ACTORES
             */
            $this->data['comentario'] = $this->acuerdopagodoc_model->get_comentarios_proceso($cod_fiscalizacion);
            /*
             * ENVIO DE DATOS VISUALIZACION DE VISTA
             */
            $abogado = $this->acuerdopagodoc_model->get_abogado_proceso($cod_fiscalizacion);
            $secretario = $this->acuerdopagodoc_model->get_secretario_proceso($cod_fiscalizacion);
            $coordinador = $this->acuerdopagodoc_model->get_coordinador_proceso($cod_fiscalizacion);
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
            $urlplantilla2 = "uploads/acuerdopagodoc/" . $cod_fiscalizacion . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->data['cod_fiscalizacion'] = $cod_fiscalizacion;
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
                $datos["COD_FISCALIZACION"] = $this->input->post('cod_fiscalizacion');
                $datos["COD_RESPUESTA"] = $this->input->post('estado');
                $datos["NOMBRE_DOCUMENTO"] = $this->input->post('nombre_documento');
                $datos["ABOGADO"] = $this->input->post('abogado');
                $datos["SECRETARIO"] = $this->input->post('secretario');
                $datos["EJECUTOR"] = $this->input->post('coordinador');
                $datos["COMENTADO_POR"] = COD_USUARIO;
                $datos["COMENTARIO"] = $this->input->post('comentarios');
                $datos["TITULO_ENCABEZADO"] = $this->input->post('encabezado');
                $acuerdo = $this->input->post('acuerdo');
                $this->acuerdopagodoc_model->insertar_comunicacion($datos);
                /*
                 * ACTUALIZAR SI ES UN ACUERDO DE PAGO Q ESTA EN PROCESO
                 */
                $datos2["COD_FISCALIZACION"] = $this->input->post('cod_fiscalizacion');
                $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                $datos2["USUARIO_ASIGNADO"] = $this->input->post('asignar');
                if ($this->input->post('estado') == 1289 || $this->input->post('estado') == 1287)
                    $datos2["AUTO_ACUERDO"] = 'N'; 
                else 
                    $datos2["AUTO_ACUERDO"] = 'S';
                
                
                $this->acuerdopagodoc_model->actualizacion_acuerdopago($datos2);
                
                /*
                 * INSERTAR TRAZA
                 */
                $this->Traza_Proceso($this->input->post('cod_fiscalizacion'), $this->input->post('estado'), 'Gestion Documental proceso de Acuerdo de Pago');
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

        $estructura = "uploads/acuerdopagodoc/" . $post['fiscalizacion'] . "/";
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

    private function do_multi_upload($cod_fiscalizacion, $carpeta) {
        $estructura = "./uploads/acuerdopagodoc/" . $cod_fiscalizacion . "/" . $carpeta . "/";
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
