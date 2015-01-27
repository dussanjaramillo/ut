<?php

class Remisibilidad extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library('tcpdf/Plantilla_DocJuridica.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('remisibilidad_model', '', TRUE);
        $this->load->model('plantillas_model');
        $this->load->model('documentospj_model');
        $this->load->file(APPPATH . "controllers/verificarpagos.php", true);

        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.PrintArea.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
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
        define("PLANTILLA_OFICIOREMISORIO", "111");
        define("PLANTILLA_AUTOFINPROCESO", "74");

        define("ABOGADO_CC", "43");
        define("SECRETARIO_CC", "41");
        define("COORDINARDOR_CC", "42");
        define("DIRECTOR_CC", "61");
        define("SUBCOMITE_DE_DEPURACION_CONTABLE", "83");
        define("COMITE_TECNICO_SOSTENIBILIDAD", "84");
        define("DIRECTOR_ADMINISTRATIVO", "85");

        define("OFICIO_ADMINISTRATIVA", "1174");
        define("PLANTILLA_OFICIO_REMISORIO", "111");

        define("OFICIO_REMISORIO_CREADO", "1116");
        define("OFICIO_REMISORIO_CORREGIDO", "1117");
        define("OFICIO_REMISORIO_PREAPROBADO", "1118");
        define("OFICIO_REMISORIO_NO_PREAPROBADO", "1119");
        define("OFICIO_REMISORIO_APROBADO", "1120");
        define("OFICIO_REMISORIO_NO_APROBADO", "1121");
        define("OFICIO_REMISORIO_SUBIDOALEXPEDIENTE", "1122");
        define("OFICIO_REMISORIO_SUBIDODC", "1146");

        define("FICHA_ESTUDIO_RECOMENDACIONES_GENERADA", "484");
        define("FICHA_ESTUDIO_COMITE_GENERADA", "1131");
        define("REMISIBILIDAD_NOAPROBADA", "486");
        define("FICHA_SUBCOMITESUBIDO", "1139");
        define("FICHA_COMITESUBIDO", "1140");

        define("RESOLUCION_CREADA", "489");
        define("RESOLUCION_SUBIDA", "1472");

        define("AUTO_CREADO", "941");
        define("AUTO_CORREGIDO", "942");
        define("AUTO_NO_PREAPROBADO", "944");
        define("AUTO_PREAPROBADO", "943");
        define("AUTO_NO_APROBADO", "946");
        define("AUTO_APROBADO", "945");
        define("AUTO_EN_EXPEDIENTE", "947");

        define("TIPO_1", "Oficio Remisorio");
        define("TIPO_2", "Ficha De Estudio de Recomendaciones del Subcomite");
        define("TIPO_3", "Ficha De Estudio de Recomendaciones del Comite");
        define("TIPO_4", "Resolución que Declara Remisibilidad");
        define("TIPO_5", "Auto de Terminación y Cierre de Proceso");
    }

    function index() {
        redirect(base_url() . 'index.php');
    }

    /*
     * GRUPO 43 ABOGADO DE COBRO COACTIVO
     * GRUPO 83 SUBCOMITE DE DEPURACION CONTABLE
     * GRUPO 84 Comite Tecnico de Sostenibilidad del Sistema Contable
     * GRUPO 85 Director Administrativo y Financiero
     */
    /*
     * TRAZA DE PROCESO
     */

    function TrazaCoactivo($cod_respuesta, $cod_coactivo, $comentarios) {
        $info = $this->remisibilidad_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', $cod_coactivo, '', '', '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

    /*
     * CREAR DETALLE DE LA RESOLUCION INGRESANDO LOS TITULOS SELECCIONADOS A REALIZAR LA PRESCRIPCION
     */

    function Insertar_DetalleRemisibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $expediente = unserialize($this->input->post('expediente'));
                $cod_coactivo = $this->input->post('cod_coactivo');
                $info = $this->remisibilidad_model->cabecera($cod_coactivo, OFICIO_REMISORIO_CREADO);
                $dato_encabezado['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                $dato_encabezado['COD_TIPORESPUESTA'] = OFICIO_REMISORIO_CREADO;
                $dato_encabezado['GENERADA_POR'] = COD_USUARIO;
                $dato_encabezado['COD_EMPRESA'] = $info[0]['IDENTIFICACION'];
                $remisibilidad = $this->remisibilidad_model->insertar_remisibilidad($dato_encabezado);
                $dato_detalle['COD_REMISIBILIDAD'] = $remisibilidad;
                for ($i = 0; $i < sizeof($expediente); $i++) {
                    $dato_detalle['COD_RECEPCIONTITULO'] = $expediente[$i];
                    $this->remisibilidad_model->insertar_remisibilidaddet($dato_detalle);
                }
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo correctamente el oficio remisorio de la Remisibilidad</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR DETALLE DE LA RESOLUCION INGRESANDO LOS TITULOS SELECCIONADOS A REALIZAR LA PRESCRIPCION
     */

    function Insertar_DetalleRemisibilidadAdmin() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $expediente = unserialize($this->input->post('expediente'));
                $info = $this->remisibilidad_model->deudas_fiscalizacion($expediente[0]);
                $dato_encabezado['COD_TIPORESPUESTA'] = OFICIO_ADMINISTRATIVA;
                $dato_encabezado['GENERADA_POR'] = COD_USUARIO;
                $dato_encabezado['COD_EMPRESA'] = $info[0]['IDENTIFICACION'];
                $remisibilidad = $this->remisibilidad_model->insertar_remisibilidad($dato_encabezado);
                $dato_detalle['COD_REMISIBILIDAD'] = $remisibilidad;
                for ($i = 0; $i < sizeof($expediente); $i++) {
                    $dato_detalle['COD_FISCALIZACION'] = $expediente[$i];
                    $this->remisibilidad_model->insertar_remisibilidaddet($dato_detalle);
                }
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo correctamente el oficio remisorio de la Remisibilidad</div>');
                redirect(base_url() . 'index.php/remisibilidad/Menu_DeudaEmpresas');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR DETALLE DE LA RESOLUCION INGRESANDO LOS TITULOS SELECCIONADOS A REALIZAR LA PRESCRIPCION
     */

    function Insertar_DetalleResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $listado_expedientes = unserialize($this->input->post('expediente'));
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $info_deudor = $this->remisibilidad_model->get_remisibilidadgestionar($cod_remisibilidad);
                /*
                 * DATOS PARA EL ENCABEZADO
                 */
                $tipo_remisibilidad = $info_deudor[0]['TIPO'];
                /*
                 * ACTUALIZACION DE LA REMISIBILIDAD
                 */
                $datos_A1["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                $datos_A1["COD_TIPORESPUESTA"] = $cod_respuesta;
                $this->remisibilidad_model->actualizacion_remprincipal($datos_A1);
                /*
                 * ACTUALIZAR EN EL DETALLE DE REMISIBILIDAD LAS DEUDAS APROBADAS
                 */
                $detalle_general["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                $detalle_general["ESTADO"] = "0"; //DESAPROBAR PRIMERO TODAS 
                $this->remisibilidad_model->actualizacion_detallegeneral($detalle_general);
                for ($z = 0; $z < sizeof($listado_expedientes); $z++) {
                    if ($tipo_remisibilidad == 'COACTIVO') {
                        $datos_detalle["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos_detalle["COD_RECEPCIONTITULO"] = $listado_expedientes[$z];
                        $datos_detalle["ESTADO"] = "1"; //APROBADO
                        $this->remisibilidad_model->actualizacion_detallecoactivo($datos_detalle);
                    } else {
                        $datos_detalle["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos_detalle["COD_FISCALIZACION"] = $listado_expedientes[$z];
                        $datos_detalle["ESTADO"] = "1"; //APROBADO
                        $this->remisibilidad_model->actualizacion_detalledeuda($datos_detalle);
                    }
                }
                switch ($cod_respuesta) {
                    case RESOLUCION_CREADA:
                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo correctamente el oficio remisorio de la Remisibilidad</div>');
                        redirect(base_url() . 'index.php/remisibilidad/Menu_ResolucionRemisibilidad');
                        break;
                    default:
                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo correctamente el Auto de Fin de Proceso</div>');
                        redirect(base_url() . 'index.php/remisibilidad/Menu_FDP_Administrativa');
                        break;
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU COACTIVO INICIAL 
     */

    function Listado_titulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo_remisibilidad')) {
                $cod_coactivo = $this->input->post('cod_coactivo_remisibilidad');
                $this->template->set('title', 'Remisibilidad');
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['titulos'] = $this->remisibilidad_model->cabecera($cod_coactivo, 1315);
                $this->template->load($this->template_file, 'remisibilidad/tituloscoactivo_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU SOLICITUD DE REMISIBILIDAD POR ADMINISTRATIVA
     */

    function Menu_DeudaEmpresas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_DeudaEmpresas')) {
                $this->template->set('title', 'Remisibilidad');
                $this->data['titulos'] = $this->remisibilidad_model->deudas_fiscalizacion();
                $this->template->load($this->template_file, 'remisibilidad/deudasadministrativo_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU SOLICITUD DE REMISIBILIDAD POR ADMINISTRATIVA
     */

    function Menu_DeudasAdministrativa() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $nit = $this->input->post('expediente');
                $this->template->set('title', 'Remisibilidad');
                $this->data['titulos'] = $this->remisibilidad_model->deudas_empresa($nit);
                $this->template->load($this->template_file, 'remisibilidad/deudasempresa_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR OFICIO REMISORIO
     */

    function Crear_OficioAdministrativa() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $expedientes_seleccionados = $this->input->post('expediente');
                $expediente = explode("-", $expedientes_seleccionados);
                $indice = 0;
                for ($i = 1; $i < sizeof($expediente); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($expediente); $j++) {
                        if ($expediente[$i] == $expediente[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_expedientes[$indice] = $expediente[$i];
                        $indice++;
                    }
                }
                $this->Crear_Documentos(NULL, PLANTILLA_OFICIOREMISORIO, TIPO_1, OFICIO_ADMINISTRATIVA, 'remisibilidad/oficioadministrativa_add', $listado_expedientes, NULL);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Menu_SubirExpedienteAdmin() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_SubirExpedienteAdmin')) {
                $this->template->set('title', 'Remisibilidad');
                $cod_respuesta[0] = OFICIO_ADMINISTRATIVA;
                $cod_respuesta[1] = FICHA_ESTUDIO_RECOMENDACIONES_GENERADA;
                $cod_respuesta[2] = FICHA_ESTUDIO_COMITE_GENERADA;
                $cod_respuesta[3] = RESOLUCION_CREADA;
                $cod_respuesta[4] = AUTO_CREADO;
                $this->data['titulos'] = $this->remisibilidad_model->get_cabeceraunificada($cod_respuesta);
                $this->data['titulo'] = 'Subir Documentos al Expediente';
                $this->data['ruta'] = 'remisibilidad/Subir_OficioAdmin';
                $this->template->load($this->template_file, 'remisibilidad/gestiongeneral_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_OficioAdmin() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $cod_remisibilidad = $this->input->post('expediente');
                $cod_respuesta = $this->input->post('cod_respuesta');
                switch ($cod_respuesta) {
                    case OFICIO_ADMINISTRATIVA:
                        $this->Agregar_Expediente(NULL, TIPO_1, OFICIO_REMISORIO_SUBIDOALEXPEDIENTE, $cod_remisibilidad);
                        break;
                    case FICHA_ESTUDIO_RECOMENDACIONES_GENERADA:
                        $this->Agregar_Expediente(NULL, TIPO_2, FICHA_SUBCOMITESUBIDO, $cod_remisibilidad);
                        break;
                    case FICHA_ESTUDIO_COMITE_GENERADA:
                        $this->Agregar_Expediente(NULL, TIPO_3, FICHA_COMITESUBIDO, $cod_remisibilidad);
                        break;
                    case RESOLUCION_CREADA:
                        $this->Agregar_Expediente(NULL, TIPO_4, RESOLUCION_SUBIDA, $cod_remisibilidad);
                        break;
                    case AUTO_CREADO:
                        $this->Agregar_Expediente(NULL, TIPO_5, AUTO_EN_EXPEDIENTE, $cod_remisibilidad);
                        break;
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
     * CREAR OFICIO REMISORIO
     */

    function Crear_OficioRemisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $expedientes_seleccionados = $this->input->post('expediente');
                $expediente = explode("-", $expedientes_seleccionados);
                $indice = 0;
                for ($i = 1; $i < sizeof($expediente); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($expediente); $j++) {
                        if ($expediente[$i] == $expediente[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_expedientes[$indice] = $expediente[$i];
                        $indice++;
                    }
                }
                $this->Crear_Documentos($cod_coactivo, PLANTILLA_OFICIOREMISORIO, TIPO_1, OFICIO_REMISORIO_CREADO, 'remisibilidad/elaborardocumento_add', $listado_expedientes, NULL, NULL);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_OficioRemisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, OFICIO_REMISORIO_CORREGIDO, 0, 'remisibilidad/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_OficioRemisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, OFICIO_REMISORIO_PREAPROBADO, OFICIO_REMISORIO_NO_PREAPROBADO, 'remisibilidad/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_OficioRemisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, OFICIO_REMISORIO_APROBADO, OFICIO_REMISORIO_NO_APROBADO, 'remisibilidad/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_OficioRemisorio() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_1, OFICIO_REMISORIO_SUBIDOALEXPEDIENTE, NULL);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA APROBACION DEL SUBCOMITE Y CREACION DE FICHA 
     */

    function Menu_Subcomite() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_Subcomite')) {
                $this->template->set('title', 'Remisibilidad');
                $cod_respuesta[0] = OFICIO_REMISORIO_SUBIDOALEXPEDIENTE;
                $this->data['titulos'] = $this->remisibilidad_model->get_cabeceraunificada($cod_respuesta);
                $this->data['titulo'] = 'Subcomite de Depuración Contable';
                $this->data['ruta'] = 'remisibilidad/Listado_AprobarSubcomite';
                $this->template->load($this->template_file, 'remisibilidad/gestiongeneral_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU SOLICITUD DE REMISIBILIDAD POR ADMINISTRATIVA
     */

    function Listado_AprobarSubcomite() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $cod_remisibilidad = $this->input->post('expediente');
                $this->template->set('title', 'Remisibilidad');
                $this->data['titulos'] = $this->remisibilidad_model->get_remisibilidadgestionar($cod_remisibilidad);
                $this->data['ruta'] = 'remisibilidad/Crear_FichaRegistro';
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->template->load($this->template_file, 'remisibilidad/gestionesremisibles_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA APROBACION DEL COMITE Y CREACION DE FICHA 
     */

    function Menu_Comite() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_Comite')) {
                $this->template->set('title', 'Remisibilidad');
                $cod_respuesta[0] = FICHA_SUBCOMITESUBIDO;
                $this->data['titulos'] = $this->remisibilidad_model->get_cabeceraunificada($cod_respuesta);
                $this->data['titulo'] = 'Comite de Depuración Contable';
                $this->data['ruta'] = 'remisibilidad/Listado_AprobarSubcomite';
                $this->template->load($this->template_file, 'remisibilidad/gestiongeneral_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU SOLICITUD DE REMISIBILIDAD POR ADMINISTRATIVA
     */

    function Listado_AprobarComite() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $cod_remisibilidad = $this->input->post('expediente');
                $this->template->set('title', 'Remisibilidad');
                if (COD_REGIONAL == '1') {
                    $this->data['titulos'] = $this->remisibilidad_model->get_remisibilidadgestionar($cod_remisibilidad);
                } else {
                    $this->data['titulos'] = $this->remisibilidad_model->get_remisibilidadgestionaractivas($cod_remisibilidad);
                }
                $this->data['ruta'] = 'remisibilidad/Crear_FichaRegistro';
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->template->load($this->template_file, 'remisibilidad/gestionesremisibles_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA APROBACION DEL COMITE Y CREACION DE FICHA 
     */

    function Menu_ResolucionRemisibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_ResolucionRemisibilidad')) {
                $this->template->set('title', 'Remisibilidad');
                $cod_respuesta[0] = FICHA_COMITESUBIDO;
                $this->data['titulos'] = $this->remisibilidad_model->get_cabeceraunificada($cod_respuesta);
                $this->data['titulo'] = 'Resolución que Declara la Remisibilidad de la Obligación';
                $this->data['ruta'] = 'remisibilidad/Listado_AprobarResolucion';
                $this->template->load($this->template_file, 'remisibilidad/gestiongeneral_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU SOLICITUD DE REMISIBILIDAD POR ADMINISTRATIVA
     */

    function Listado_AprobarResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $cod_remisibilidad = $this->input->post('expediente');
                $this->template->set('title', 'Remisibilidad');
                $this->data['titulos'] = $this->remisibilidad_model->get_remisibilidadgestionaractivas($cod_remisibilidad);
                $this->data['ruta'] = 'remisibilidad/Crear_ResolucionRemisibilidad';
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->template->load($this->template_file, 'remisibilidad/gestionesremisibles_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_ResolucionRemisibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_remisibilidad')) {
                $expedientes_seleccionados = $this->input->post('expediente');
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $expediente = explode("-", $expedientes_seleccionados);
                $indice = 0;
                for ($i = 1; $i < sizeof($expediente); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($expediente); $j++) {
                        if ($expediente[$i] == $expediente[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_expedientes[$indice] = $expediente[$i];
                        $indice++;
                    }
                }
                $this->Crear_Documentos(NULL, PLANTILLA_OFICIOREMISORIO, TIPO_4, RESOLUCION_CREADA, 'remisibilidad/resolucionremisibilidad_add', $listado_expedientes, $cod_remisibilidad);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA carta de finde  proceso de administrativa
     */

    function Menu_FDP_Administrativa() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_Subcomite')) {
                $this->template->set('title', 'Remisibilidad');
                $cod_respuesta[0] = RESOLUCION_SUBIDA;
                $this->data['titulos'] = $this->remisibilidad_model->get_cabeceraunificada($cod_respuesta);
                $this->data['titulo'] = 'Auto de Fin de Proceso';
                $this->data['ruta'] = 'remisibilidad/Listado_AprobarSubcomite';
                $this->template->load($this->template_file, 'remisibilidad/gestiongeneral_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Listado_ResolucionesAprobadas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('expediente')) {
                $cod_remisibilidad = $this->input->post('expediente');
                $this->template->set('title', 'Remisibilidad');
                $this->data['titulos'] = $this->remisibilidad_model->get_remisibilidadgestionaractivas($cod_remisibilidad);
                $this->data['ruta'] = 'remisibilidad/Crear_FDP_Administrativa';
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->template->load($this->template_file, 'remisibilidad/gestionesremisibles_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_FDP_Administrativa() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_remisibilidad')) {
                $expedientes_seleccionados = $this->input->post('expediente');
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $expediente = explode("-", $expedientes_seleccionados);
                $indice = 0;
                for ($i = 1; $i < sizeof($expediente); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($expediente); $j++) {
                        if ($expediente[$i] == $expediente[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_expedientes[$indice] = $expediente[$i];
                        $indice++;
                    }
                }
                $this->Crear_Documentos(NULL, PLANTILLA_OFICIOREMISORIO, TIPO_5, AUTO_CREADO, 'remisibilidad/resolucionremisibilidad_add', $listado_expedientes, $cod_remisibilidad);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU PARA carta de finde  proceso de Coactivo
     */

    function Crear_FDP_Coactivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $coactivo = $this->remisibilidad_model->get_remisibilidadesfdp($cod_coactivo);
                if (sizeof($coactivo) > 0) {
                    for ($i = 0; $i < sizeof($coactivo); $i++) {
                        $titulos[$i] = $coactivo[$i]['COD_RECEPCIONTITULO'];
                    }
                    $this->verificarpagos = new Verificarpagos();
                    $this->verificarpagos->crearAutosCierre($codfiscalizacion = FALSE, $titulos);
                } else {
                    redirect(base_url() . 'index.php/bandejaunificada');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_FichaRegistro() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('expediente')) {
                $expedientes_seleccionados = $this->input->post('expediente');
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $expediente = explode("-", $expedientes_seleccionados);
                $indice = 0;
                for ($i = 1; $i < sizeof($expediente); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($expediente); $j++) {

                        if ("'.$expediente[$i].'" == "'.$expediente[$j].'") {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_expedientes[$indice] = $expediente[$i];
                        $indice++;
                    }
                }

                $this->template->set('title', 'Remisibilidad');
                $this->data['condiciones'] = $this->remisibilidad_model->get_condiciones();
                $this->data['expediente'] = $listado_expedientes;
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->data['cantidad_rem'] = sizeof($this->data['cod_remisibilidad']);
                $this->data['message'] = "Los archivos que adjunte deben ser en PDF";
                $this->template->load($this->template_file, 'remisibilidad/registrarcondiciones_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Generar_FichaRegistro() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_remisibilidad')) {
                /*
                 * RECIBIR DATOS
                 */
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $listado_expedientes = unserialize($this->input->post('expediente'));
                $info_deudor = $this->remisibilidad_model->get_remisibilidadgestionar($cod_remisibilidad);
                /*
                 * DATOS PARA EL ENCABEZADO
                 */
                $tipo_remisibilidad = $info_deudor[0]['TIPO'];
                /*
                 * DATOS DE USUARIO
                 */
                $cuenta_contable = $this->input->post('cuenta_contable');
                $cod_cuenta_contable = $this->input->post('cod_cuenta_contable');
                $asiento_contable = $this->input->post('asiento_contable');
                $fecha_asiento = $this->input->post('fecha_asiento');
                $documentos = $this->input->post('documentos');
                $cantidad_anexos = $this->input->post('cantidad_anexos');



                /*
                 * VERIFICAR SI NO HAY NINGUNA CONDICION CHECKEADA
                 */
                $cantidad = $this->input->post('cc');
                $aprobadas = 0;
                $sin_check = 0;
                for ($i = 0; $i < $cantidad; $i++) {
                    $condicion = $this->input->post('condicion' . $i);
                    if (empty($condicion)) {
                        $sin_check++;
                    }
                }
                $file = $this->do_multi_upload($cod_remisibilidad, 'Expediente');
                if (!empty($file["error"])) {
                    /*
                     * VISUALIZAR ERROR DEL PDF
                     */
                    $this->template->set('title', 'Remisibilidad');
                    $this->data['message'] = $file["error"];
                    $this->data['condiciones'] = $this->remisibilidad_model->get_condiciones();
                    $this->data['expediente'] = $listado_expedientes;
                    $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                    $this->data['cantidad_rem'] = sizeof($this->data['cod_remisibilidad']);
                    $this->data['message'] = "Los archivos que adjunte deben ser en PDF";
                    $this->template->load($this->template_file, 'remisibilidad/registrarcondiciones_add', $this->data);
                } else {
                    if ($file != false) {
                        /*
                         * VERIFICACION DEL TIPO DE FICHA A PRESENTAR
                         */
                        $fichas = $this->remisibilidad_model->get_cantidadfichas($cod_remisibilidad);
                        if ($fichas || COD_REGIONAL == '1') {
                            $cod_respuesta = FICHA_ESTUDIO_COMITE_GENERADA;
                            $titulo = 'COMITE DE DEPURACION CONTABLE';
                        } else {
                            $cod_respuesta = FICHA_ESTUDIO_RECOMENDACIONES_GENERADA;
                            $titulo = 'SUBCOMITE DE DEPURACION CONTABLE';
                        }
                        /*
                         * ACTUALIZAR LA REMISIBILIDAD CON RESPECTO A LA FICHA DEL SUBCOMITE
                         */
                        $datos_ficha["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos_ficha["NOMBRE_CTA_CONTABLE"] = $cuenta_contable;
                        $datos_ficha["CODIGO_CTA_CONTABLE"] = $cod_cuenta_contable;
                        $datos_ficha["NUM_ASIENTO_CONTABLE"] = $asiento_contable;
                        $datos_ficha["FECHA_ASIENTO_CONTABLE"] = $fecha_asiento;
                        $datos_ficha["DOCUMENTOS_ANEXOS"] = $documentos;
                        $datos_ficha["CANTIDAD_DOCUMENTOS"] = $cantidad_anexos;
                        $this->remisibilidad_model->insertar_fichaestudio($datos_ficha);
                        /*
                         * GENERAR CONSECUTIVO PARA LA REMISIBILIDAD Y ACTUALIZAR REMISIBILIDAD CON CONSECUTIVO
                         */
                        $cod_remisibilidad_temp = $cod_remisibilidad;
                        $consecutivo_remisibilidad = "REM_";
                        while (strlen($cod_remisibilidad_temp) < 6) {
                            $cod_remisibilidad_temp = "0" . $cod_remisibilidad_temp;
                        }
                        $consecutivo_remisibilidad = $consecutivo_remisibilidad . $cod_remisibilidad_temp;
                        /*
                         * INSERTAR CONDICIONES
                         */
                        $datos_2["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos_2["VERIFICADO"] = 'n';
                        $cantidad = $this->input->post('cc');
                        $aprobadas = 0;
                        for ($i = 0; $i <= $cantidad; $i++) {
                            $condicion = $this->input->post('condicion' . $i);
                            if (!empty($condicion)) {
                                $nombre_condicion = explode("-", $condicion);
                                $condiciones[$aprobadas] = $nombre_condicion[1];
                                $id_condicion = $nombre_condicion[0];
                                $datos_2["COD_CONDI_REMISIBILIDAD"] = $id_condicion;
                                $this->remisibilidad_model->insertar_condiciones_remisibilidad($datos_2);
                                $aprobadas++;
                            }
                        }
                        /*
                         * ACTUALIZACION DE LA REMISIBILIDAD
                         */
                        $datos_A1["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos_A1["COD_TIPORESPUESTA"] = $cod_respuesta;
                        $this->remisibilidad_model->actualizacion_remprincipal($datos_A1);
                        /*
                         * INSERTAR LOS SOPORTES
                         */
                        for ($z = 0; $z < sizeof($listado_expedientes); $z++) {
                            $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                            if ($tipo_remisibilidad == 'COACTIVO') {
                                $datos["COD_RECEPCIONTITULO"] = $listado_expedientes[$z];
                            } else {
                                $datos["COD_FISCALIZACION"] = $listado_expedientes[$z];
                            }
                            $datos["ID_USUARIO"] = COD_USUARIO;
                            $datos['COD_TIPO_EXPEDIENTE'] = 9; //TIPO SOPORTE
                            $datos['SUB_PROCESO'] = 'Ficha Que soporta la aprobacion de la Remisiiblidad';
                            $cantidad_documentos = sizeof($file);
                            for ($i = 0; $i < $cantidad_documentos; $i++) {
                                $datos["RUTA_DOCUMENTO"] = "uploads/soportes_remisibilidad/" . $cod_remisibilidad . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                                $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                                $this->documentospj_model->insertar_expediente($datos);
                            }
                        }
                        /*
                         * ACTUALIZAR EN EL DETALLE DE REMISIBILIDAD LAS DEUDAS APROBADAS
                         */
                        $detalle_general["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $detalle_general["ESTADO"] = "0"; //DESAPROBAR PRIMERO TODAS 
                        $this->remisibilidad_model->actualizacion_detallegeneral($detalle_general);
                        for ($z = 0; $z < sizeof($listado_expedientes); $z++) {
                            if ($tipo_remisibilidad == 'COACTIVO') {
                                $datos_detalle["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                                $datos_detalle["COD_RECEPCIONTITULO"] = $listado_expedientes[$z];
                                $datos_detalle["ESTADO"] = "1"; //APROBADO
                                $this->remisibilidad_model->actualizacion_detallecoactivo($datos_detalle);
                            } else {
                                $datos_detalle["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                                $datos_detalle["COD_FISCALIZACION"] = $listado_expedientes[$z];
                                $datos_detalle["ESTADO"] = "1"; //APROBADO
                                $this->remisibilidad_model->actualizacion_detalledeuda($datos_detalle);
                            }
                        }
                        /*
                         * CARGAR LA FICHA DE REMISIBILIDAD DE CONDICIONES
                         */
                        $this->template->set('title', 'Ficha de Registro de Condiciones');
                        $this->data['message'] = $this->session->flashdata('message');
                        $this->data['info_deudor'] = $info_deudor;
                        $this->data['num_consecutivo'] = $consecutivo_remisibilidad;
                        $this->data['cuenta_contable'] = $cuenta_contable;
                        $this->data['cod_cuenta_contable'] = $cod_cuenta_contable;
                        $this->data['asiento'] = $asiento_contable;
                        $this->data['fecha_asiento'] = $fecha_asiento;
                        $this->data['condiciones'] = $condiciones;
                        $this->data['documentos'] = $documentos;
                        $this->data['cantidad_anexos'] = $cantidad_anexos;
                        $this->data['titulo'] = $titulo;
                        $this->template->load($this->template_file, 'remisibilidad/ficharegistrocondiciones', $this->data);
                    } else {
                        $this->template->set('title', 'Remisibilidad');
                        $this->data['condiciones'] = $this->remisibilidad_model->get_condiciones();
                        $this->data['expediente'] = $listado_expedientes;
                        $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                        $this->data['cantidad_rem'] = sizeof($this->data['cod_remisibilidad']);
                        $this->data['message'] = "Los archivos que adjunte deben ser en PDF";
                        $this->template->load($this->template_file, 'remisibilidad/registrarcondiciones_add', $this->data);
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Oficio_NoRemisible() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_remisibilidad')) {
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $cod_respuesta = $this->input->post('cod_respuesta');
                $datos["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                $datos["COD_TIPORESPUESTA"] = $cod_respuesta;
                $this->remisibilidad_model->actualizacion_remisibilidad($datos);
                /*
                 * TRAZA
                 */
                $this->Traza_Proceso($cod_remisibilidad, $cod_respuesta, 'Declaracion de la no procedencia de la remisibilidad');
                redirect(base_url() . 'index.php/remisibilidad/Menu_CrearFichaSubcomite');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREACION DE LA FICHA DE COMITE
     */

    function Menu_CrearFichaComite() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Menu_CrearFichaComite')) {
                //template data
                $this->template->set('title', 'Remisibilidades');
                $this->data['ruta'] = 'index.php/remisibilidad/Generar_FichaRegistro';
                $this->data['titulo'] = 'Elaborar Ficha De Estudio Y Recomendación De Remisibilidad';
                $estado[0] = FICHA_SUBCOMITESUBIDO;
                $estado[1] = OFICIO_REMISORIO_SUBIDODC;
                $this->data['remisibilidad_seleccionados'] = $this->remisibilidad_model->get_estados_remisibilizar(NULL, NULL, $estado);
                $this->template->load($this->template_file, 'remisibilidad/estadosentrada_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREACION DE LA FICHA DE COMITE
     */

    function Generar_FichaCondiciones() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_remisibilidad')) {
                $usuario = $this->ion_auth->user()->row();
                $id_usuario = $usuario->IDUSUARIO;
                $fecha = $this->input->post('fecha');
                $boton_aprobar = $this->input->post('boton_aprobar');
                $boton_naprobar = $this->input->post('boton_naprobar');
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $this->remisibilidad_model->set_remisibilidad($cod_remisibilidad);
                $datos_remisibilidad = $this->remisibilidad_model->get_remisibles();
                if ($boton_aprobar == "aprobo") {
                    /*
                     * ACTUALIZAR REM_REMISIBILIDAD CON APROBACION
                     */
                    $nit = $datos_remisibilidad["CODEMPRESA"];
                    $fiscalizacion = $datos_remisibilidad["COD_FISCALIZACION"];
                    $datos_A2["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                    $datos_A2["APROBADA"] = 's';
                    $datos_A2["FECHA_APROBACION"] = $fecha;
                    $datos_A2["APROBADO_POR"] = $id_usuario;
                    $this->remisibilidad_model->actualizacion_rem_remisibilidad_aprobacion($datos_A2, $fiscalizacion, $nit, 196, 485);
                    /*
                     * ACTUALIZAR REM_VERIFICARREMISIBILIDAD CON VERIFICACION
                     */
                    $datos_A3["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                    $datos_A3["VERIFICADO"] = 's';
                    $this->remisibilidad_model->actualizacion_rem_validaciones_aprobacion($datos_A3);
                    /*
                     * CARGAR FORMATO PARA CREAR DECLARACION
                     */
                    $this->remisibilidad_model->set_remisibilidad($cod_remisibilidad);
                    $this->data['remisibilidad'] = $this->remisibilidad_model->get_rem_agregar_concepto();
                    $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                    $this->template->load($this->template_file, 'remisibilidad/registrardeclaracion_add', $this->data);
                } elseif ($boton_naprobar == "no_aprobo") {
                    /*
                     * ACTUALIZAR REM_REMISIBILIDAD CON APROBACION
                     */
                    $nit = $datos_remisibilidad["CODEMPRESA"];
                    $fiscalizacion = $datos_remisibilidad["COD_FISCALIZACION"];
                    $datos_A2["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                    $datos_A2["APROBADA"] = 'n';
                    $datos_A2["COD_GESTION_COBRO"] = '486';
                    $datos_A2["FECHA_APROBACION"] = $fecha;
                    $datos_A2["APROBADO_POR"] = $id_usuario;
                    $this->remisibilidad_model->actualizacion_rem_remisibilidad_aprobacion($datos_A2, $fiscalizacion, $nit, 196, 486);
                    /*
                     * ACTUALIZAR REM_VERIFICARREMISIBILIDAD CON VERIFICACION
                     */
                    $datos_A3["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                    $datos_A3["VERIFICADO"] = 's';
                    $this->remisibilidad_model->actualizacion_rem_validaciones_aprobacion($datos_A3);
                    /*
                     * CARGAR TABLA PRINCIPAL DE REMISIBILIDADES
                     */
                    $this->data['remisibilidad_seleccionados'] = $this->remisibilidad_model->get_remisibles_no_verificados();
                    $this->data['message'] = "La remisibilidad fue catalogada como no aprobada";
                    $this->template->load($this->template_file, 'remisibilidad/evaluardeclaracion_list', $this->data);
                } else {
                    $datos_condiciones = $this->remisibilidad_model->get_condiciones_remisibilidad();
                    $datos_documentos = $this->remisibilidad_model->get_documentos_remisibilidad();
                    $this->data['nit'] = $datos_remisibilidad["CODEMPRESA"];
                    $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                    $this->data['razonsocial'] = $datos_remisibilidad["NOMBRE_EMPRESA"];
                    $this->data['concepto'] = $datos_remisibilidad["NOMBRE_CONCEPTO"];
                    $this->data['instancia'] = $datos_remisibilidad["NOMBREPROCESO"];
                    $this->data['representante_legal'] = $datos_remisibilidad["REPRESENTANTE_LEGAL"];
                    $this->data['telefono'] = $datos_remisibilidad["TELEFONO_FIJO"];
                    $this->data['cuenta_contable'] = $datos_remisibilidad["NOMBRE_CTA_CONTABLE"];
                    $this->data['cod_cuenta_contable'] = $datos_remisibilidad["CODIGO_CTA_CONTABLE"];
                    $this->data['asiento'] = $datos_remisibilidad["NUM_ASIENTO_CONTABLE"];
                    $this->data['fecha_asiento'] = $datos_remisibilidad["FECHA_ASIENTO_CONTABLE"];
                    $this->data['cod_regional'] = $datos_remisibilidad["COD_REGIONAL"];
                    $this->data['regional'] = $datos_remisibilidad["NOMBRE_REGIONAL"];
                    $this->data['liquidacion'] = $this->cambio_moneda($datos_remisibilidad["TOTAL_LIQUIDADO"]);
                    $this->data['num_consecutivo'] = $datos_remisibilidad["CONSECUTIVO"];
                    $this->data['condiciones'] = $datos_condiciones;
                    $this->data['documentos'] = $datos_documentos;
                    $info = $this->remisibilidad_model->get_remisibilidadtrazar($cod_remisibilidad);
                    $this->data['cod_fiscalizacion'] = $info['COD_FISCALIZACION'];
                    $this->template->load($this->template_file, 'remisibilidad/fichaverificarcondiciones_add', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Concepto_Remisibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Agregar_Concepto_Remisibilidad') || $this->input->post('cod_remisibilidad')) {
                //template data
                $this->template->set('title', 'Remisibilidades');
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $this->remisibilidad_model->set_remisibilidad($cod_remisibilidad);
                $this->data['remisibilidad'] = $this->remisibilidad_model->get_rem_agregar_concepto();
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->template->load($this->template_file, 'remisibilidad/registrarconcepto_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Resolucion_Remisibilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('remisibilidad/Agregar_Resolucion_Remisibilidad') || $this->input->post('cod_remisibilidad')) {
                //template data
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $this->remisibilidad_model->set_remisibilidad($cod_remisibilidad);
                $this->data['remisibilidad'] = $this->remisibilidad_model->get_rem_agregar_concepto();
                $this->data['informacion'] = $this->plantillas_model->plantillas(50);
                $urlplantilla2 = "uploads/doc_remisibilidad/" . $this->data['informacion'][0]['ARCHIVO_PLANTILLA'];
                $arreglo = array();
                $arreglo['n_ficha'] = "REM_" . $cod_remisibilidad;
                $arreglo['nit'] = $this->data['remisibilidad']['CODEMPRESA'];
                $arreglo['razon_social'] = $this->data['remisibilidad']['NOMBRE_EMPRESA'];
                $arreglo['resolucion'] = $this->data['remisibilidad']['NUMERO_RESOLUCION'];
                $valor = $this->cambio_moneda($this->data['remisibilidad']['TOTAL_LIQUIDADO']);
                $usuario = $this->ion_auth->user()->row();
                $arreglo['nombre_usuario'] = $usuario->NOMBRES . " " . $usuario->APELLIDOS;
                $arreglo['valor'] = $valor;
                $archivo = template_tags($urlplantilla2, $arreglo);
                $this->data['filas2'] = $archivo;
                $this->template->set('title', 'Remisibilidades');
                $this->template->load($this->template_file, 'remisibilidad/resolucionremisibilidad_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta, $cod_remisibilidad) {
        if ($this->ion_auth->logged_in()) {
            if ($cod_remisibilidad != NULL) {
                $lista_fiscalizacion = $this->remisibilidad_model->get_remisibilidadfiscalizacion($cod_remisibilidad);
                for ($i = 0; $i < sizeof($lista_fiscalizacion); $i++) {
                    $documento = $this->documentospj_model->get_plantilla_proceso_fiscalizacion($lista_fiscalizacion[$i]['COD_FISCALIZACION']);
                    if ($documento != '') {
                        /*
                         * CONSULTAR DOCUMENTO PARA IMPRIMIR
                         */
                        $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
                        $urlplantilla2 = "uploads/remisibilidad/" . $lista_fiscalizacion[$i]['COD_FISCALIZACION'] . "/" . $documento['NOMBRE_DOCUMENTO'];
                        $arreglo = array();
                        $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
                    }
                }
                /*
                 * CARGAR VISTA
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Remisibilidad');
                $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
                $this->data['tipo'] = $tipo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['cod_remisibilidad'] = $cod_remisibilidad;
                $this->template->load($this->template_file, 'remisibilidad/subirexpedienteremisibilidad_add', $this->data);
            } else {
                /*
                 * CONSULTAR DOCUMENTO PARA IMPRIMIR
                 */
                $documento = $this->documentospj_model->get_plantilla_proceso($cod_coactivo);
                $this->data['titulo_encabezado'] = $documento['TITULO_ENCABEZADO'];
                $urlplantilla2 = "uploads/remisibilidad/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
                $arreglo = array();
                $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
                /*
                 * CARGAR VISTA
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Remisibilidad');
                $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
                $this->data['tipo'] = $tipo;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->template->load($this->template_file, 'remisibilidad/subirexpediente_add', $this->data);
            }
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
                    case OFICIO_REMISORIO_SUBIDOALEXPEDIENTE:
                        $datos['COD_TIPO_EXPEDIENTE'] = 4;
                        $datos['SUB_PROCESO'] = TIPO_1;
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SSE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/soportes_remisibilidad/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->documentospj_model->insertar_expediente($datos);
                }
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Remisibilidad');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'remisibilidad/comprobacionexp_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta �rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Soporte_ExpedienteAdministrativo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_remisibilidad')) {
                /*
                 * RECIBIR DATOS
                 */
                $cod_remisibilidad = $this->input->post('cod_remisibilidad');
                $fecha = $this->input->post('fecha');
                $numero = $this->input->post('numero');
                $cod_respuesta = $this->input->post('cod_respuesta');
                /*
                 * LISTAR FISCALIZACIONES O LISTAS TITULOS
                 */
                $info_deuda = $this->remisibilidad_model->get_remisibilidadgestionar($cod_remisibilidad);
                if ($info_deuda[0]['TIPO'] == 'FISCALIZACION') {
                    switch ($cod_respuesta) {
                        case OFICIO_REMISORIO_SUBIDOALEXPEDIENTE:
                            $lista_fiscalizacion = $this->remisibilidad_model->get_remisibilidadfiscalizacion($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_1;
                            break;
                        case FICHA_SUBCOMITESUBIDO:
                            $lista_fiscalizacion = $this->remisibilidad_model->get_remfiscalizacionaprob($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_2;
                            break;
                        case FICHA_COMITESUBIDO:
                            $lista_fiscalizacion = $this->remisibilidad_model->get_remfiscalizacionaprob($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_3;
                            break;
                        case RESOLUCION_SUBIDA:
                            $lista_fiscalizacion = $this->remisibilidad_model->get_remfiscalizacionaprob($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_4;
                            break;
                    }
                    $file = $this->do_multi_upload($cod_remisibilidad, "Expediente"); //1- pdf y archivos de imagen
                    for ($i = 0; $i < sizeof($lista_fiscalizacion); $i++) {
                        /*
                         * DATOS INSERTAR AL EXPEDIENTE DE FISCALIZACION Y COACTIVO
                         */
                        $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                        $datos['FECHA_RADICADO'] = $fecha;
                        $datos['NUMERO_RADICADO'] = $numero;
                        $datos["COD_FISCALIZACION"] = $lista_fiscalizacion[$i]['COD_FISCALIZACION'];
                        $datos["ID_USUARIO"] = COD_USUARIO;
                        /*
                         * OBTENER NOMBRE DEL ARCHIVO
                         */
                        $nombre_documento = $file[0]["upload_data"]["file_name"];
                        $datos["RUTA_DOCUMENTO"] = "uploads/soportes_remisibilidad/" . $cod_remisibilidad . "/Expediente/" . $nombre_documento;
                        $datos["NOMBRE_DOCUMENTO"] = $nombre_documento;
                        $this->documentospj_model->insertar_expediente($datos);
                    }
                } else {
                    switch ($cod_respuesta) {
                        case OFICIO_REMISORIO_SUBIDOALEXPEDIENTE:
                            $lista_titulos = $this->remisibilidad_model->get_remisibilidadtitulo($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_1;
                            break;
                        case FICHA_SUBCOMITESUBIDO:
                            $lista_titulos = $this->remisibilidad_model->get_remtituloaprob($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_2;
                            break;
                        case FICHA_COMITESUBIDO:
                            $lista_titulos = $this->remisibilidad_model->get_remtituloaprob($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_3;
                            break;
                        case RESOLUCION_SUBIDA:
                            $lista_titulos = $this->remisibilidad_model->get_remtituloaprob($cod_remisibilidad);
                            $datos['COD_TIPO_EXPEDIENTE'] = 4;
                            $datos['SUB_PROCESO'] = TIPO_4;
                            break;
                    }
                    /*
                     * OBTENER NOMBRE DEL ARCHIVO
                     */
                    $file = $this->do_multi_upload($cod_remisibilidad, "Expediente"); //1- pdf y archivos de imagen
                    $nombre_archivo = $file[0]["upload_data"]["file_name"];
                    for ($i = 0; $i < sizeof($lista_titulos); $i++) {
                        /*
                         * DATOS INSERTAR AL EXPEDIENTE DE FISCALIZACION Y COACTIVO
                         */
                        $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                        $datos['FECHA_RADICADO'] = $fecha;
                        $datos['NUMERO_RADICADO'] = $numero;
                        $datos["COD_RECEPCIONTITULO"] = $lista_titulos[$i]['COD_RECEPCIONTITULO'];
                        $datos["ID_USUARIO"] = COD_USUARIO;
                        $datos["RUTA_DOCUMENTO"] = "uploads/soportes_remisibilidad/" . $cod_remisibilidad . "/Expediente/" . $nombre_archivo;
                        $datos["NOMBRE_DOCUMENTO"] = $nombre_archivo;
                        $this->documentospj_model->insertar_expediente($datos);
                    }
                }

                /*
                 * ACTUALIZAR EL ESTADO DE LA REMISIBILIDAD
                 */
                if ($cod_respuesta == OFICIO_REMISORIO_SUBIDOALEXPEDIENTE) {
                    if (COD_REGIONAL == '1') {
                        $datos2["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos2["COD_TIPORESPUESTA"] = OFICIO_REMISORIO_SUBIDODC;
                        $this->remisibilidad_model->actualizacion_remisibilidadadmin($datos2);
                    } else {
                        $datos2["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                        $datos2["COD_TIPORESPUESTA"] = $cod_respuesta;
                        $this->remisibilidad_model->actualizacion_remisibilidadadmin($datos2);
                    }
                } else {
                    $datos2["COD_REMISIBILIDAD"] = $cod_remisibilidad;
                    $datos2["COD_TIPORESPUESTA"] = $cod_respuesta;
                    $this->remisibilidad_model->actualizacion_remisibilidadadmin($datos2);
                }
                redirect(base_url() . 'index.php/remisibilidad/Menu_SubirExpedienteAdmin');
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
            $urlplantilla2 = "uploads/remisibilidad/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('remisibilidad/visualizardocumento_add', $this->data);
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
                $this->template->set('title', 'Jurisdicción Coactiva -> Remisibilidad');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $tipo = TIPO_1;
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'remisibilidad/comprobacionexp_add', $this->data);
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
                    $estructura = "./uploads/doc_remisibilidad/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->documentospj_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                /*
                 * ACTUALIZAR EL ESTADO DE LA REMISIBILIDAD
                 */
                if (COD_REGIONAL == '1') {
                    $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos2["COD_TIPORESPUESTA"] = OFICIO_REMISORIO_SUBIDODC;
                    $this->remisibilidad_model->actualizacion_remisibilidad($datos2);
                } else {
                    $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos2["COD_TIPORESPUESTA"] = $cod_respuesta;
                    $this->remisibilidad_model->actualizacion_remisibilidad($datos2);
                }
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Insertar Remisibilidad al Expediente');
                redirect(base_url() . 'index.php/bandejaunificada/procesoscoactivos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_Documentos($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta, $lista_expediente, $cod_remisibilidad) {
        if ($this->ion_auth->logged_in()) {
            if ($cod_coactivo != NULL) {
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
                $this->data['encabezado'] = $encabezado;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_proceso'] = $cod_proceso;
            }
            $this->data['cod_remisibilidad'] = $cod_remisibilidad;
            $this->data['expediente'] = $lista_expediente;
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

            $this->data['tipo'] = $tipo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->set('title', 'Jurisdicción Coactiva -> Remisibilidad');
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
            $urlplantilla2 = "uploads/remisibilidad/" . $cod_coactivo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Remisibilidad');
            $this->template->load($this->template_file, $ruta, $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function insertar_comunicacionadministrativa() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('estado')) {
                /*
                 * INSERTAR EN TABLA COMUNICACIONES
                 */
                $datos["COD_FISCALIZACION"] = $this->input->post('cod_fiscalizacion');
                $datos["COD_RESPUESTA"] = $this->input->post('estado');
                $datos["NOMBRE_DOCUMENTO"] = $this->input->post('nombre_documento');
                $datos["COMENTADO_POR"] = COD_USUARIO;
                $datos["COMENTARIO"] = $this->input->post('comentarios');
                $this->documentospj_model->insertar_comunicacion($datos);
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
                $this->documentospj_model->insertar_comunicacion($datos);
                /*
                 * BUSCAR EN LA TABLA DE TRASLADO, TRASLADOS EXISTENTES
                 */
                $remisibilidad = $this->remisibilidad_model->get_remisibilidad($this->input->post('cod_coactivo'));
                if ($remisibilidad != '') {
                    /*
                     * ACTUALIZAR SI EXISTE UNA REMISIBILIDAD PREVIA
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos2["COD_TIPORESPUESTA"] = $this->input->post('estado');
                    $this->remisibilidad_model->actualizacion_remisibilidad($datos2);
                }
                /*
                 * INSERTAR TRAZA
                 */
                $cod_respuesta = $this->input->post('estado');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Gestion Documental de Remisibilidad');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
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

    function pdf_sin_pie() {
        $html = $this->input->post('html');
        $nombre_pdf = $this->input->post('nombre');
        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
        $pdf->setFooterData('sena');
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($nombre_pdf . '.pdf', 'D');
        exit();
    }

    private function do_multi_upload($cod_fiscalizacion, $carpeta) {
        $estructura = "./uploads/soportes_remisibilidad/" . $cod_fiscalizacion . "/" . $carpeta . "/";
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

    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $estructura = "uploads/remisibilidad/" . $post['cod_coactivo'] . "/";
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

}
