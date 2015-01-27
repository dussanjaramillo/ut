<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Resolucionprescripcion extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('prescripcion_model', '', TRUE);
        $this->load->model('plantillas_model');
        $this->load->model('documentospj_model');
        $this->load->model('numeros_letras_model');
        $this->load->file(APPPATH . "controllers/verificarpagos.php", true);
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

        define("TIPO_1", "Resolucion de Prescripcion");
        define("TIPO_2", "Notificacion de Resolucion de Prescripcion");

        define("PRESCRIPCION_GENERADO", "751");
        define("PRESCRIPCION_CORREGIDO", "752");
        define("PRESCRIPCION_PREAPROBADO", "753");
        define("PRESCRIPCION_NOPREAPROBADO", "754");
        define("PRESCRIPCION_APROBADO", "755");
        define("PRESCRIPCION_NOAPROBADO", "756");
        define("PRESCRIPCION_SUBIDO", "1381");

        define("NOTIFICACION_CORREO", "1382");
        define("NOTIFICACION_FISICA_CREADA", "1383");
        define("NOTIFICACION_FISICA_APROBADA", "1384");
        define("NOTIFICACION_FISICA_ENVIADA", "1385");
        define("NOTIFICACION_FISICA_RECIBIDA", "1386");
        define("NOTIFICACION_FISICA_RECHAZADO", "1387");
        define("NOTIFICACION_FISICA_DEVUELTA", "1389");
        define("NOTIFICACION_WEB", "1388");

        define("AUTO_FIN_PROCESO", "1514");

        define("PLANTILLA_RESOLUCIONPRESCRIPCION", "63");


        $this->data['message'] = $this->session->flashdata('message');
    }

    function index() {
        $this->Menu_GestionAbogado();
    }

    /*
     * TRAZA DE PROCESO
     */

    function TrazaCoactivo($cod_respuesta, $cod_coactivo, $comentarios) {
        $info = $this->prescripcion_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', $cod_coactivo, '', '', '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

    /*
     * CREAR DETALLE DE LA RESOLUCION INGRESANDO LOS TITULOS SELECCIONADOS A REALIZAR LA PRESCRIPCION
     */

    function Insertar_DetalleResolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $expediente = unserialize($this->input->post('expediente'));
                $cod_coactivo = $this->input->post('cod_coactivo');
                $dato_encabezado['COD_PROCESO_COACTIVO'] = $cod_coactivo;
                $dato_encabezado['COD_RESPUESTA'] = PRESCRIPCION_GENERADO;
                $this->prescripcion_model->insertar_prescripcion($dato_encabezado);
                $prescripcion = $this->prescripcion_model->get_prescripcion($cod_coactivo);
                $dato_detalle['COD_PRESCRIPCION'] = $prescripcion['COD_PRESCRIPCION'];
                for ($i = 0; $i < sizeof($expediente); $i++) {
                    $dato_detalle['COD_RECEPCIONTITULO'] = $expediente[$i];
                    $this->prescripcion_model->insertar_prescripciondet($dato_detalle);
                }
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo correctamente la resolución de Prescripción</div>');
                redirect(base_url() . 'index.php/bandejaunificada/procesos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MENU 
     */

    function Listado_titulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo_prescripcion')) {
                $cod_coactivo = $this->input->post('cod_coactivo_prescripcion');
                $this->template->set('title', 'Resolución de Prescripción');
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['titulos'] = $this->prescripcion_model->cabecera($cod_coactivo, 1315);
                $this->template->load($this->template_file, 'prescripcion/tituloscoactivo_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREAR RESOLUCION QUE DECLARA PRESCRIPCION DE LA DEUDA
     */

    function Crear_ResolucionPrescripcion() {
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
                $this->Crear_Documentos($cod_coactivo, PLANTILLA_RESOLUCIONPRESCRIPCION, TIPO_1, PRESCRIPCION_GENERADO, 'prescripcion/elaborardocumento_add', $listado_expedientes);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_ResolucionPrescripcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, PRESCRIPCION_CORREGIDO, 0, 'prescripcion/corregirdocumentoabo_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Preaprobar_ResolucionPrescripcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, PRESCRIPCION_PREAPROBADO, PRESCRIPCION_NOPREAPROBADO, 'prescripcion/preaprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Aprobar_ResolucionPrescripcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_1, $cod_coactivo, PRESCRIPCION_APROBADO, PRESCRIPCION_NOAPROBADO, 'prescripcion/aprobardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Subir_ResolucionPrescripcion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Expediente($cod_coactivo, TIPO_1, PRESCRIPCION_SUBIDO);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_Notificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Agregar_Notificaciones($cod_coactivo, PLANTILLA_RESOLUCIONPRESCRIPCION, TIPO_2, NOTIFICACION_FISICA_CREADA, 'prescripcion/elaborardocumento_add');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Corregir_NotificacionFisica() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, NOTIFICACION_FISICA_CREADA, 0, 'prescripcion/corregirdocumentoabo_add');
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
                $this->Proceso_Documento(TIPO_2, $cod_coactivo, NOTIFICACION_FISICA_APROBADA, NOTIFICACION_FISICA_RECHAZADO, 'prescripcion/aprobardocumento_add');
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
                $this->Agregar_Expediente($cod_coactivo, TIPO_2, NOTIFICACION_FISICA_ENVIADA);
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
            $this->data['anexo'] = $this->prescripcion_model->get_anexosubido($cod_coactivo);
            $cod_fiscalizacion = $this->prescripcion_model->get_fiscalizacionproceso($cod_coactivo);
            $correo = $this->prescripcion_model->get_correo_autorizacion($cod_fiscalizacion['COD_FISCALIZACION_EMPRESA']);
            if ($correo == '') {
                $this->Crear_Documentos($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta, NULL);
            } else {

                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data["class"] = '';
                $this->data["correo"] = $correo;
                $this->template->set('title', 'Resolución de Prescripción');
                $this->template->load($this->template_file, 'prescripcion/enviarnotificacion_add', $this->data);
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
                 * ACTUALIZAR EL ESTADO DE LA RESOLUCION
                 */
                $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                $datos2["COD_RESPUESTA"] = NOTIFICACION_CORREO;
                $this->prescripcion_model->actualizacion_prescripcion($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Insertar Resolución de Prescripción al Expediente');
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
                    $this->data['causales'] = $this->prescripcion_model->get_motivosdevolucion();
                    $this->template->set('title', 'Resolución de Prescripción');
                    $this->template->load($this->template_file, 'prescripcion/notificacionenviada_add', $this->data);
                } else {
                    /*
                     * ACTUALIZAR EL ESTADO DE LA RESOLUCION
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos2["COD_RESPUESTA"] = $this->input->post('cod_respuesta');
                    $this->prescripcion_model->actualizacion_prescripcion($datos2);
                    /*
                     * INSERTAR TRAZA
                     */
                    $cod_respuesta = $this->input->post('cod_respuesta');
                    $cod_coactivo = $this->input->post('cod_coactivo');
                    $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Notificación del envio fisico de la resolución de prescripción');
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

    function Crear_FDP_Coactivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $coactivo = $this->prescripcion_model->get_prescripcionfdp($cod_coactivo);
                if (sizeof($coactivo) > 0) {
                    for ($i = 0; $i < sizeof($coactivo); $i++) {
                        $titulos[$i] = $coactivo[$i]['COD_RECEPCIONTITULO'];
                    }
                    /*
                     * ACTUALIZAR EL ESTADO DE LA RESOLUCION
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos2["COD_RESPUESTA"] = AUTO_FIN_PROCESO;
                    $this->prescripcion_model->actualizacion_prescripcion($datos2);
                    /*
                     * INSERTAR TRAZA
                     */
                    $this->TrazaCoactivo(AUTO_FIN_PROCESO, $cod_coactivo, 'Auto de Fin de Proceso para resolución de prescripción');
                    $this->verificarpagos = new Verificarpagos();
                    $this->verificarpagos->crearAutosCierre($codfiscalizacion = FALSE, $titulos);
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo el auto de cierre.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada');
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

    function NotificacionWeb() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_coactivo')) {
                $cod_coactivo = $this->input->post('cod_coactivo');
                $cod_respuesta = $this->input->post('cod_respuesta');
                if ($cod_respuesta == '') {
                    $this->data['anexo'] = $this->prescripcion_model->get_anexosubido($cod_coactivo);
                    $this->data['cod_coactivo'] = $cod_coactivo;
                    $this->data["class"] = '';
                    $this->template->set('title', 'Nulidades');
                    $this->template->load($this->template_file, 'prescripcion/enviarnotifiweb_add', $this->data);
                    /*
                     * INSERTAR TRAZA
                     */
                    $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Notificación del envio fisico de la resolución de prescripción');
                    redirect(base_url() . 'index.php/bandejaunificada/procesos');
                } else {
                    /*
                     * ACTUALIZAR EL ESTADO DE LA PRESCRIPCION
                     */
                    $datos["COD_PROCESO_COACTIVO"] = $cod_coactivo;
                    $datos["COD_RESPUESTA"] = $cod_respuesta;
                    $this->prescripcion_model->actualizacion_prescripcion($datos);
                }
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
            $urlplantilla2 = "uploads/prescripcion/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            /*
             * CARGAR VISTA
             */
            $this->template->set('title', 'Jurisdicción Coactiva -> Prescripcion de Titulos');
            $this->data['message'] = "Los archivos de soporte a subir son únicamente PDF";
            $this->data['tipo'] = $tipo;
            $this->data['cod_coactivo'] = $cod_coactivo;
            $this->data['cod_respuesta'] = $cod_respuesta;
            $this->template->load($this->template_file, 'prescripcion/subirexpediente_add', $this->data);
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
                    case PRESCRIPCION_SUBIDO:
                        $datos['COD_TIPO_EXPEDIENTE'] = 3;
                        $datos['SUB_PROCESO'] = TIPO_1;
                        break;
                    case NOTIFICACION_FISICA_ENVIADA:
                        $datos['COD_TIPO_EXPEDIENTE'] = 8;
                        $datos['SUB_PROCESO'] = TIPO_2;
                        break;
                }
                /*
                 * POR CUANTOS ARCHIVOS SSE ENCUENTRE UN INSERT SE REALIZA
                 */
                $cantidad_documentos = sizeof($file);
                for ($i = 0; $i < $cantidad_documentos; $i++) {
                    $datos["RUTA_DOCUMENTO"] = "uploads/prescripcion/" . $cod_coactivo . "/Expediente/" . $file[$i]["upload_data"]["file_name"];
                    $datos["NOMBRE_DOCUMENTO"] = $file[$i]["upload_data"]["file_name"];
                    $this->documentospj_model->insertar_expediente($datos);
                }
                /*
                 * CARGAR TABLA DE EXPEDIENTES
                 */
                $this->template->set('title', 'Jurisdicción Coactiva -> Prescripcion de Titulos');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $this->template->load($this->template_file, 'prescripcion/comprobacionexp_add', $this->data);
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
            $urlplantilla2 = "uploads/prescripcion/" . $cod_coactivo . "/" . $documento['NOMBRE_DOCUMENTO'];
            $arreglo = array();
            $this->data['documento'] = template_tags($urlplantilla2, $arreglo);
            $this->load->view('prescripcion/visualizardocumento_add', $this->data);
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
                $this->template->set('title', 'Jurisdicción Coactiva -> Prescripción de Titulos');
                $verificar[0] = $cod_coactivo;
                $verificar[1] = $cod_respuesta;
                $this->data['cod_coactivo'] = $cod_coactivo;
                $this->data['cod_respuesta'] = $cod_respuesta;
                $this->data['archivos'] = $this->documentospj_model->get_documentosexpediente($verificar);
                $tipo = TIPO_1;
                if (sizeof($this->data['archivos']) == 0) {
                    $this->Agregar_Expediente($cod_coactivo, $tipo, $cod_respuesta);
                } else {
                    $this->template->load($this->template_file, 'prescripcion/comprobacionexp_add', $this->data);
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
                    $estructura = "./uploads/prescripcion/" . $cod_coactivo . "/" . $plantillas[$i]["NOMBRE_DOCUMENTO"];
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
                $this->prescripcion_model->actualizacion_prescripcion($datos2);
                /*
                 * INSERTAR TRAZA
                 */
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Insertar Resolución de Prescripción al Expediente');
                redirect(base_url() . 'index.php/bandejaunificada/procesoscoactivos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Crear_Documentos($cod_coactivo, $cod_plantilla, $tipo, $cod_respuesta, $ruta, $lista_expediente) {
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
            $this->data['expediente'] = $lista_expediente;
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Prescripcion de Titulos');
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
            $urlplantilla2 = "uploads/prescripcion/" . $cod_coactivo . "/" . $this->data['documento']['NOMBRE_DOCUMENTO'];
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
            $this->template->set('title', 'Jurisdicción Coactiva -> Prescripcion de Titulos');
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
                $prescripcion = $this->prescripcion_model->get_prescripcion($this->input->post('cod_coactivo'));
                if ($prescripcion != '') {
                    /*
                     * ACTUALIZAR SI ES UN TRASLADO Q ESTA EN PROCESO
                     */
                    $datos2["COD_PROCESO_COACTIVO"] = $this->input->post('cod_coactivo');
                    $datos2["COD_RESPUESTA"] = $this->input->post('estado');
                    $this->prescripcion_model->actualizacion_prescripcion($datos2);
                }
                /*
                 * INSERTAR TRAZA
                 */
                $cod_respuesta = $this->input->post('estado');
                $cod_coactivo = $this->input->post('cod_coactivo');
                $this->TrazaCoactivo($cod_respuesta, $cod_coactivo, 'Gestion Documental de Resolucion de Prescripcion');
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

        $estructura = "uploads/prescripcion/" . $post['cod_coactivo'] . "/";
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
        $estructura = "./uploads/prescripcion/" . $cod_coactivo . "/" . $carpeta . "/";
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
