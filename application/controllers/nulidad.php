<?php

class Nulidad extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library('tcpdf/Plantilla_DocJuridica.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('nulidad_model', '', TRUE);
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
        $this->data['user'] = $this->ion_auth->user()->row();
        define("PLANTILLA_OFICIOREMISORIO", "111");
        define("PLANTILLA_AUTOFINPROCESO", "74");

        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);


        define("ABOGADO", '43');
        define("SECRETARIO", '41');
        define("COORDINADOR", '42');
        define("DIRECTOR", '61');

        define("NULIDAD_A_PETICION", "462");
        define("NO_NULIDAD_A_PETICION", "464");
        define("NULIDAD_DE_OFICIO", "461");
        define("NO_NULIDAD_DE_OFICIO", "463");

        define("TIPO_1", "Acto Administrativo que Soporta la Nulidad");
        define("TIPO_2", "Notificación del Acto Administrativo que Soporta la Nulidad");

        define("ACTO_ADMINISTRATIVO_CREADO", "465");
        define("ACTO_ADMINISTRATIVO_CORREGIDO", "1151");
        define("ACTO_ADMINISTRATIVO_PREAPROBADO", "466");
        define("ACTO_ADMINISTRATIVO_APROBADO", "468");
        define("ACTO_ADMINISTRATIVO_RECHAZADO", "467");
        define("ACTO_ADMINISTRATIVO_SUBIDO", "1150");

        define("NOTIFICACION_CORREO", "478");
        define("NOTIFICACION_WEB_GENERADA", "481");
        define("NOTIFICACION_WEB_ENVIADA", "482");
        define("NOTIFICACION_FISICO_GENERADA", "477");
        define("NOTIFICACION_FISICO_CORREGIDA", "1416");
        define("NOTIFICACION_FISICO_APROBADA", "1171");
        define("NOTIFICACION_FISICO_ENVIADA", "1172");
        define("NOTIFICACION_FISICO_RECIBIDA", "479");
        define("NOTIFICACION_FISICO_DEVUELTA", "480");
        define("NOTIFICACION_FISICO_RECHAZADO", "483");

        define("PLANTILLA_ACTOADMINISTRATIVO", "63");
    }

    function index() {
        $this->Verificar_Causales();
    }

    /*
     * TRAZA DE PROCESO
     */

    function TrazaCoactivo($cod_respuesta, $cod_coactivo, $comentarios) {
        $info = $this->nulidad_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', $cod_coactivo, '', '', '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

    /*
     * INICIO DEL PROCESO VERIFICACION DE CAUSALES DE NULIDAD
     */

    function Verificar_Causales() {
        if ($this->ion_auth->logged_in()) {
            if (true) {
                /*
                 * RECIBIR DATOS
                 */
                $cod_coactivo = $this->input->post('cod_coactivo_nulidad');
                $causales = $this->input->post('causales');
                /*
                 * VERIFICAR SI NO HAY NINGUNA CONDICION CHECKEADA
                 */
                if ($causales == '') {
                    $this->data['encabezado'] = $this->documentospj_model->cabecera($cod_coactivo, ACTO_ADMINISTRATIVO_CREADO);
                    $this->data['message'] = "Para Registrar La Nulidad Debe Seleccionar Minimo Una Causal";
                    $this->data['causales'] = $this->nulidad_model->get_causalesnulidad();
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->template->load($this->template_file, 'nulidad/regcausalesnulidad_add', $this->data);
                } else {
                    /*
                     * DECIDIR Q TIPO DE NULIDAD ES
                     */
                    $peticion = $this->input->post('peticion');
                    $procede = $this->input->post('procede');
                    $cod_fiscalizacion = $this->input->post('cod_fiscalizacion');
                    if ($peticion == 0 && $procede == 0) {
                        $cod_respuesta = NULIDAD_A_PETICION;
                        $datos["PROCEDE"] = 'S';
                    }if ($peticion == 0 && $procede == 1) {
                        $cod_respuesta = NO_NULIDAD_A_PETICION;
                    }if ($peticion == 1 && $procede == 0) {
                        $cod_respuesta = NULIDAD_DE_OFICIO;
                        $datos["PROCEDE"] = 'S';
                    }if ($peticion == 1 && $procede == 1) {
                        $cod_respuesta = NO_NULIDAD_DE_OFICIO;
                    }
                    /*
                     * INSERTAR LA NULIDAD
                     */
                    $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos["COD_RESPUESTA"] = $cod_respuesta;
                    $cod_nulidad = $this->nulidad_model->insertar_nulidad($datos);
                    /*
                     * INSERTAR SOPORTES
                     */
                    $file = $this->do_multi_upload($cod_coactivo, 'Expediente');
                    $datos_s['COD_RESPUESTAGESTION'] = $cod_respuesta;
                    $datos_s["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos_s["ID_USUARIO"] = COD_USUARIO;
                    $datos_s['COD_TIPO_EXPEDIENTE'] = 9;
                    $datos_s['SUB_PROCESO'] = 'Causales que Soportan la Nulidad';
                    if ($file) {
                        for ($i = 0; $i < sizeof($file); $i++) {
                            // Cuando no selecciona archivo a cargar no inserta el registro
                            if (!empty($file[$i]["upload_data"]["file_name"])) {
                                $datos_s["RUTA_DOCUMENTO"] = "uploads/soportes_nulidad/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                                $datos_s["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                                $this->documentospj_model->insertar_expediente($datos_s);
                            }
                        }
                    }

                    /*
                     * INSERTAR CAUSALES
                     */
                    $datos_c["COD_NULIDAD"] = $cod_nulidad;
                    if ($file != FALSE) {
                        for ($i = 0; $i < sizeof($causales); $i++) {
                            $datos_c["TIPO_CAUSAL_NULIDAD"] = $causales[$i];
                            $this->nulidad_model->insertar_causales($datos_c);
                        }
                    }
                    /*
                     * TRAZA
                     */
                    $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Creacion de Causales de Nulidad');
                    redirect(base_url('index.php/bandejaunificada/procesos'));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR AUTO QUE ORDENA TRASLADO DEL EXPEDIENTE
     */

    function Crear_ActoAdministrativo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Crear_Documentos($cod_coactivo, PLANTILLA_ACTOADMINISTRATIVO, TIPO_1, ACTO_ADMINISTRATIVO_CREADO, 'nulidad/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_ActoAdministrativo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, ACTO_ADMINISTRATIVO_CORREGIDO, 0, 'nulidad/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_ActoAdministrativo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, ACTO_ADMINISTRATIVO_PREAPROBADO, ACTO_ADMINISTRATIVO_RECHAZADO, 'nulidad/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_ActoAdministrativo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, ACTO_ADMINISTRATIVO_APROBADO, ACTO_ADMINISTRATIVO_RECHAZADO, 'nulidad/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ActoAdministrativo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_1, ACTO_ADMINISTRATIVO_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * NOTIFICACION DEL ACTO ADMINISTRATIVO
     */

    function Crear_Notificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Notificaciones($cod_coactivo, PLANTILLA_ACTOADMINISTRATIVO, TIPO_2, NOTIFICACION_FISICO_GENERADA, 'nulidad/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_Notificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, NOTIFICACION_FISICO_CORREGIDA, 0, 'nulidad/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_NotificacionFisica() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, NOTIFICACION_FISICO_APROBADA, NOTIFICACION_FISICO_DEVUELTA, 'nulidad/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_NotificacionFisica() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_2, NOTIFICACION_FISICO_ENVIADA);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Respuesta_NotificacionFisica() {
        if ($this->ion_auth->logged_in()) {
            if (true) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                if ($this->input->post('nis') == '') {
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->data["class"] = '';
                    $this->data['causales'] = $this->nulidad_model->get_motivosdevolucion();
                    $this->template->set('title', 'Nulidad');
                    $this->template->load($this->template_file, 'nulidad/notificacionenviada_add', $this->data);
                } else {
                    /*
                     * ACTUALIZAR EL ESTADO DE LA RESOLUCION
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos2["COD_RESPUESTA"] = $this->input->post('cod_respuesta');
                    $this->nulidad_model->actualizacion_nulidad($datos2);
                    /*
                     * INSERTAR TRAZA
                     */
                    $cod_respuesta = $this->input->post('cod_respuesta');
                    $cod_coactivo = $this->input->post('cod_coactivo');
                    $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Notificación del envio fisico de la Resolución que declara la Nulidad');
                    redirect(base_url() . 'index.php/bandejaunificada/procesos');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Agregar_Notificaciones($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta) {
        if ($this->ion_auth->logged_in()) {
            $this->data['anexo'] = $this->nulidad_model->get_anexosubido($cod_coactivo);
            $cod_fiscalizacion = $this->nulidad_model->get_fiscalizacionproceso($cod_coactivo);
            $correo = $this->nulidad_model->get_correo_autorizacion($cod_fiscalizacion['COD_FISCALIZACION_EMPRESA']);
            if ($correo == '') {
                $this->Crear_Documentos($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta, NULL);
            } else {
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data["class"] = '';
                $this->data["correo"] = $correo;
                $this->template->set('title', 'Nulidad');
                $this->template->load($this->template_file, 'nulidad/enviarnotificacion_add', $this->data);
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Enviar_Correo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->input->post('cod_coactivo')) {
                /*
                 * ACTUALIZAR TITULO EN TABLA NOTIFICACIONES
                 */
                $cod_coactivo = $this->input->post('cod_coactivo');
                $correo = $this->input->post('correo');
                $mensaje = $this->input->post('comentarios');
                $asunto = $this->input->post('asunto');
                $adjunto = $this->input->post('ruta');
                //enviarcorreosena($correo, $mensaje, $asunto, $adjunto); //ENVIO DE CORREOS CDESCOMENTARIAR CUANDO HAYA SERVIDOR
                /*
                 * ACTUALIZAR EL ESTADO DE LA NULIDAD
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = NOTIFICACION_CORREO;
                $this->nulidad_model->actualizacion_nulidad($datos2);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function NulidadesFDP() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_nulidad')) {
                $cod_nulidad = $this->input->post('cod_nulidad');
                $traza = $this->nulidad_model->get_nulidadtrazar($cod_nulidad);
                $cod_fiscalizacion = $traza["COD_FISCALIZACION"];
                $this->verificarpagos = new Verificarpagos();
                $this->verificarpagos->crearAutosCierre($cod_fiscalizacion);
            } else {
                redirect(base_url() . 'index.php/auth/login');
            }
        }
    }

    function NotificacionWeb() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                if ($cod_respuesta == '') {
                    $this->data['anexo'] = $this->nulidad_model->get_anexosubido($cod_coactivo);
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->data["class"] = '';
                    $this->template->set('title', 'Nulidades');
                    $this->template->load($this->template_file, 'nulidad/enviarnotifiweb_add', $this->data);
                } else {
                    /*
                     * ACTUALIZAR EL ESTADO DE LA NULIDAD
                     */
                    $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos["COD_RESPUESTA"] = $cod_respuesta;
                    $this->nulidad_model->actualizacion_nulidad($datos);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function ListadoActividades() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                //template data
                $this->data['titulo'] = "Retornar Proceso";
                $this->template->set('title', 'Nulidades');
                $this->data["message"] = 'Seleccione la Nulidad para retroceder a un estado especifico';
                $this->data["ruta"] = 'index.php/nulidad/CambioEstado';
                $this->data['nulidad_seleccionada'] = $this->nulidad_model->get_nulidadesretorno(COD_USUARIO, $cod_coactivo);
                $this->template->load($this->template_file, 'nulidad/retornogestion_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function CambioEstado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_proceso = $this->input->post('cod_gestion');
                $cod_respuesta = $this->input->post('cod_respuesta');
                switch ($cod_proceso) {
                    case 8:
                        $tabla = 'PROCESOS_COACTIVOS';
                        $condicion[0] = 'COD_PROCESO_COACTIVO';
                        $datos['COD_RESPUESTA'] = $cod_respuesta;
                        $datos['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                        $actualizar = true;
                        break;
                    case 9:
                        $tabla = 'COBROPERSUASIVO';
                        $condicion[0] = 'COD_PROCESO_COACTIVO';
                        $datos['COD_TIPO_RESPUESTA'] = $cod_respuesta;
                        $datos['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                        $actualizar = true;
                        break;
                    case 10:
                        $tabla = 'MANDAMIENTOPAGO';
                        $condicion[0] = 'COD_PROCESO_COACTIVO';
                        $datos['ESTADO'] = $cod_respuesta;
                        $datos['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                        $actualizar = true;
                        break;
                    case 11:
                        $tabla = 'MC_MEDIDASCAUTELARES';
                        $condicion[0] = 'COD_PROCESO_COACTIVO';
                        $condicion[1] = 'BLOQUEO';
                        $valor[1] = 0;
                        $datos['COD_RESPUESTAGESTION'] = $cod_respuesta;
                        $datos['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                        $actualizar = true;
                    case 12:
                        $tabla = 'MC_AVALUO';
                        $condicion[0] = 'COD_PROCESO_COACTIVO';
                        $datos['ESTADO'] = $cod_respuesta;
                        $datos['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                        $actualizar = true;
                        break;
                    case 17:
                        $avaluos = $this->nulidad_model->get_avaluoscoactivo($cod_coactivo);
                        for ($i = 0; $i < sizeof($avaluos); $i++) {
                            $tabla = 'MC_REMATE';
                            $condicion[0] = 'COD_AVALUO';
                            $datos['COD_RESPUESTA'] = $cod_respuesta;
                            $datos['COD_AVALUO'] = $avaluos['COD_AVALUO'];
                            $valor[0] = $avaluos['COD_AVALUO'];
                            $this->nulidad_model->actualizar_estados($tabla, $datos, $condicion, $valor);
                            $actualizar = false;
                        }
                        break;
                }
                if ($actualizar) {
                    $valor[0] = $cod_coactivo;
                    $this->nulidad_model->actualizar_estados($tabla, $datos, $condicion, $valor);
                }
                /*
                 * ACTUALIZAR EL ESTADO DE LA NULIDAD
                 */
                $datos['PROCEDE'] = 'N';
                $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos["COD_RESPUESTA"] = $cod_respuesta;
                $this->nulidad_model->actualizacion_nulidad($datos);
				redirect(base_url() . 'index.php/bandejaunificada/procesoscoactivos');
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
            $urlplantilla2 = "uploads/doc_nulidad/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Nulidad');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'nulidad/subirexpediente_add', $this->data);
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
                    case ACTO_ADMINISTRATIVO_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 3;
                        $datos['SUB_PROCESO'] = TIPO_1;
                        break;
                    case NOTIFICACION_FISICO_ENVIADA:
                        $datos['COD_TIPO_EXPEDIENTE'] = 8;
                        $datos['SUB_PROCESO'] = TIPO_2;
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SSE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/soportes_nulidad/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->documentospj_model->insertar_expediente($datos);
                }
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Nulidad');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'nulidad/comprobacionexp_add', $this->data);
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
            $urlplantilla2 = "uploads/doc_nulidad/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('nulidad/visualizardocumento_add', $this->data);
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
                $this->template->set('title', 'Jurisdicción Coactiva -> Nulidad');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $tipo = TIPO_1;
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'nulidad/comprobacionexp_add', $this->data);
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
                    $estructura = "./uploads/doc_nulidad/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
                    if (file_exists($estructura)) {
                        unlink($estructura);
                    }
                    $this->documentospj_model->eliminar_plantilla($plantillas[$i]["COMUNICADO_PJ"]);
                }
                /*
                 * ACTUALIZAR EL ESTADO DE LA NULIDAD
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = $cod_respuesta;
                $this->nulidad_model->actualizacion_nulidad($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Insertar Nulidad al Expediente');
                redirect(base_url() . 'index.php/bandejaunificada/procesoscoactivos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Nulidad');
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
            $urlplantilla2 = "uploads/doc_nulidad/" . $cod_coactivo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Nulidad');
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
                 * ACTUALIZAR EL ESTADO DE LA NULIDAD
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                $this->nulidad_model->actualizacion_nulidad($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $cod_respuesta = $this->input->post('estado');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Gestion Documental de la nulidad Creacion de Resolucion de Prescripcion');
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

    private function do_multi_upload($cod_coactivo, $carpeta) {
        $estructura = "./uploads/soportes_nulidad/" . $cod_coactivo . "/" . $carpeta . "/";
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
        $estructura = "uploads/doc_nulidad/" . $post['cod_coactivo'] . "/";
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
