<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Traslado extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('traslado_model', '', TRUE);
        $this->load->model('plantillas_model');
        $this->load->model('documentospj_model');
        $this->load->model('numeros_letras_model');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.de|faults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        define("ABOGADO", '43');
        define("SECRETARIO", '41');
        define("COORDINADOR", '42');
        define("DIRECTOR", '61');

        define("NOMBRE_ABOGADO", 'Abogado coac');
        define("NOMBRE_SECRETARIO", 'Secretario coa');
        define("NOMBRE_COORDINADOR", 'Coordinador coa');
        define("NOMBRE_DIRECTOR", 'Director Regional');

        $this->data['user'] = $this->ion_auth->user()->row();
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);

        define("MENSAJE_TITULOS", "No existen titulos para este proceso");
        define("TITULO_COMPLETO", "886");
        define("ASIGNAR_ABOGADO", "173");

        define("TIPO_1", "Auto que ordena traslado del expediente");
        define("TIPO_2", "Comunicado a Procesos Judiciales");

        define("COMUNICADO_PJ_GENERADO", "745");
        define("COMUNICADO_PJ_CORREGIDO", "746");
        define("COMUNICADO_PJ_PREAPROBADO", "747");
        define("COMUNICADO_PJ_NOPREAPROBADO", "748");
        define("COMUNICADO_PJ_APROBADO", "749");
        define("COMUNICADO_PJ_NOAPROBADO", "750");
        define("COMUNICADO_PJ_SUBIDO", "1124");

        define("AUTO_TRANSLADO_GENERADO", "1175");
        define("AUTO_TRANSLADO_CORREGIDO", "1257");
        define("AUTO_TRANSLADO_PREAPROBADO", "1176");
        define("AUTO_TRANSLADO_NOPREAPROBADO", "1177");
        define("AUTO_TRANSLADO_APROBADO", "1178");
        define("AUTO_TRANSLADO_NOAPROBADO", "1179");
        define("AUTO_TRANSLADO_SUBIDO", "1180");

        define("PLANTILLA_COMUNICADOPJ", "63");
        define("PLANTILLA_AUTOTRASLADO", "63");


        $this->data['message'] = $this->session->flashdata('message');
    }

    function index() {
        $this->Menu_GestionAbogado();
    }

    /*
     * TRAZA DE PROCESO
     */

    function TrazaCoactivo($tipogestion, $tiporespuesta, $cod_coactivo, $comentarios) {
        $gestion_cobro = trazarProcesoJuridico($tipogestion, $tiporespuesta, '', $cod_coactivo, '', '', '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

    /*
     * MENU PARA REALIZAR LAS GESTIONES DEL ABOGADO DE COBRO COACTIVO
     */

    function Menu_GestionAbogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('traslado/Menu_GestionAbogado')) {
                $this->template->set('title', 'Traslado');
                $this->data['estados_seleccionados'] = $this->traslado_model->get_procesotransversal(TRUE, NULL);
                $this->data['grupo'] = 'Abogado de Cobro Coactivo';
                $this->template->load($this->template_file, 'traslado/gestionproceso_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA REALIZAR LAS GESTIONES DEL SECRETARIO DE COBRO COACTIVO
     */

    function Menu_GestionSecretario() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('traslado/Menu_GestionSecretario')) {
                $this->template->set('title', 'Traslado');
                $this->data['estados_seleccionados'] = $this->traslado_model->get_procesotransversal(FALSE, NULL);
                $this->data['grupo'] = 'Secretario de Cobro Coactivo';
                $this->template->load($this->template_file, 'traslado/gestionproceso_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA REALIZAR LAS GESTIONES DEL COORDINADOR DE COBRO COACTIVO O EL DIRECTOR REGIONAL
     */

    function Menu_GestionEjecutor() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('traslado/Menu_GestionEjecutor')) {
                $this->template->set('title', 'Traslado');
                $this->data['grupo'] = 'Ejecutor de Cobro Coactivo';
                $this->data['estados_seleccionados'] = $this->traslado_model->get_procesotransversal(FALSE, NULL);
                $this->template->load($this->template_file, 'traslado/gestionproceso_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR AUTO QUE ORDENA TRASLADO DEL EXPEDIENTE
     */

    function Crear_AutoTraslado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo_traslado')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo_traslado'), PLANTILLA_AUTOTRASLADO, TIPO_1, AUTO_TRANSLADO_GENERADO, 'traslado/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_AutoTraslado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_TRANSLADO_CORREGIDO, 0, 'traslado/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_AutoTraslado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_TRANSLADO_PREAPROBADO, AUTO_TRANSLADO_NOPREAPROBADO, 'traslado/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_AutoTraslado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), AUTO_TRANSLADO_APROBADO, AUTO_TRANSLADO_NOAPROBADO, 'traslado/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_AutoTraslado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_1, AUTO_TRANSLADO_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR COMUNICADO A PROCESOS JUDICIALES
     */

    function Crear_ComunicadoPJ() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Crear_Documentos($this->input->post('cod_coactivo'), PLANTILLA_COMUNICADOPJ, TIPO_2, COMUNICADO_PJ_GENERADO, 'traslado/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_ComunicadoPJ() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), COMUNICADO_PJ_CORREGIDO, 0, 'traslado/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_ComunicadoPJ() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_2, $this->input->post('cod_coactivo'), COMUNICADO_PJ_PREAPROBADO, COMUNICADO_PJ_NOPREAPROBADO, 'traslado/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_ComunicadoPJ() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Proceso_Documento(TIPO_1, $this->input->post('cod_coactivo'), COMUNICADO_PJ_APROBADO, COMUNICADO_PJ_NOAPROBADO, 'traslado/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ComunicadoPJ() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $this->Agregar_Expediente($this->input->post('cod_coactivo'), TIPO_2, COMUNICADO_PJ_SUBIDO);
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
            $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
            $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
            $urlplantilla2 = "uploads/traslado/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Traslado');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'traslado/subirexpediente_add', $this->data);
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
                /*
                 * OBTENER NOMBRE DEL ARCHIVO
                 */
                $file = $this->do_multi_upload($cod_coactivo, "Expediente"); //1- pdf y archivos de imagen
                switch ($cod_respuesta) {
                    case AUTO_TRANSLADO_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 2;
                        $datos['SUB_PROCESO'] = TIPO_1;
                        break;
                    case COMUNICADO_PJ_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 6;
                        $datos['SUB_PROCESO'] = TIPO_2;
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/traslado/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->documentospj_model->insertar_expediente($datos);
                }
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Traslado');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'traslado/comprobacionexp_add', $this->data);
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
            $urlplantilla2 = "uploads/traslado/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('traslado/visualizardocumento_add', $this->data);
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
                $this->template->set('title', 'Jurisdicción Coactiva -> Traslado');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                if ($cod_respuesta === AUTO_TRANSLADO_SUBIDO) {
                    $tipo = TIPO_1;
                } else {
                    $tipo = TIPO_2;
                }
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'traslado/comprobacionexp_add', $this->data);
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
                /* |
                 * Eliminar el Archivo de la BD y del server
                 */
                $plantillas = $this->documentospj_model->get_plantillasfiscalizacion($cod_coactivo);
                for ($i = 0; $i < sizeof($plantillas); $i++) {
                    $estructura = "./uploads/traslado/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->documentospj_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                /*
                 * ACTUALIZAR EL ESTADO DEL TRASLADO
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->traslado_model->actualizacion_traslado($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $info = $this->traslado_model->get_trazagestion($cod_respuesta);
                $this->TrazaCoactivo($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], $cod_coactivo, 'Insertar Documento al Expediente de Traslado');
                /*
                 * INSERTAR TODOS LOS TITULOS QUE CORRESPONDAN A ESA IDENTIFICACION CUANDO SEA LA COMUNICACION DE TRASLADO
                 */
                if ($cod_respuesta == COMUNICADO_PJ_SUBIDO) {
                    /* ---------------------------
                     * SE BUSCA LA IDENTIFICACION A LA CUAL CORRESPONDA EL CODIGO DE PROCESO
                     */
                    $info_coactivo = $this->documentospj_model->info_generalCabecera($cod_coactivo, $cod_respuesta);
                   // print_r($info_coactivo);die();
                    for ($i = 0; $i < sizeof($info_coactivo); $i++) {
                        if ($info_coactivo[$i]['PROCEDENCIA'] == '1') {
                            $regimen_insolvencia['COD_FISCALIZACION'] = $info_coactivo[$i]['COD_EXPEDIENTE_JURIDICA'];
                        }
                        $regimen_insolvencia['COD_RECEPCION_TITULO'] = $info_coactivo[$i]['NO_EXPEDIENTE'];
                        $regimen_insolvencia['NITEMPRESA'] = $info_coactivo[$i]['IDENTIFICACION'];
                        $regimen_insolvencia['COD_ESTADOPROCESO'] = '11';
                        $this->traslado_model->insertar_regimeninsolvencia($regimen_insolvencia);
                    }
                }
                redirect(base_url() . 'index.php/bandejaunificada/procesoscoactivos');
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
            $consecutivo = $this->documentospj_model->get_numprocesoadjudicado($cod_coactivo);
            $cod_proceso = $consecutivo['COD_PROCESOPJ'];
            /*
             * CONSULTAR DATOS DEL ENCABEZADO
             * CONSULTAR SECRETARIOS PARA ASIGNAR
             * CONSULTAR PLANTILLA
             */
            $encabezado = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta);
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
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->data['cod_proceso'] = $cod_proceso;
            $this->template->set('title', 'Jurisdicción Coactiva -> Traslado');
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
            $encabezado = $this->documentospj_model->cabecera($cod_coactivo, $cod_respuesta1);
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
            $urlplantilla2 = "uploads/traslado/" . $cod_coactivo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Traslado');
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
                $this->documentospj_model->insertar_comunicacion($datos);
                /*
                 * BUSCAR EN LA TABLA DE TRASLADO, TRASLADOS EXISTENTES
                 */
                $traslado = $this->traslado_model->get_traslado($this->input->post('cod_coactivo'));
                if ($traslado == '') {
                    /*
                     * INSERTAR SI ES UNA FISCALIZACION Q NO TIENE TRASLADO VIGENTE
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                    $datos2["COD_MOTIVOTRASLADO"] = '1';
                    $this->traslado_model->insertar_traslado($datos2);
					/*
                     * MODIFICAR EN LA TABLA DE PROCESOS COACTIVOS PARA QUE EN LA BANDEJA NO SE VISUALICE
                     */
                    $datos_traslado["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos_traslado["COD_RESPUESTA"] = $this->input->post('estado');
                    $this->traslado_model->actualizacion_coactivo($datos_traslado);
                } else {
                    /*
                     * ACTUALIZAR SI ES UN TRASLADO Q ESTA EN PROCESO
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                    $this->traslado_model->actualizacion_traslado($datos2);
                }
                /*
                 * INSERTAR TRAZA
                 */
                $info = $this->traslado_model->get_trazagestion($this->input->post('estado'));
                $this->TrazaCoactivo($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], $this->input->post('cod_coactivo'), 'Gestion Documental de Traslado');
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

        $estructura = "uploads/traslado/" . $post['cod_coactivo'] . "/";
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
        $estructura = "./uploads/traslado/" . $cod_coactivo . "/" . $carpeta . "/";
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

}
