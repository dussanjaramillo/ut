<?php

class Devolucion extends MY_Controller {

    PUBLIC $TECNICOCARTERA = 2;
    PUBLIC $COORDINADORRELACIONES = 3;

    function __construct() {
        parent::__construct();
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/chosen.csverificar_comunicacions' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/jquery.dataTables.defaults.js', 'js/chosen.jquery.min.js', 'js/jquery.confirm.js', 'js/tinymce/tinymce.jquery.min.js', 'js/funciones.js');
        $this->data['message'] = $this->session->flashdata('message');
        $this->load->library('Datatables');
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha', 'traza_fecha_helper', 'date', 'template',));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('devoluciones_model');

        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("ID_GRUPO", $this->data['user']->IDGRUPO);
        define("REGIONAL_USER", $this->data['user']->COD_REGIONAL);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);

        define("LIDER_DEVOLUCION", "148");
        define("NOMBRE_LIDER_DEVOLUCION", "Lider de Devoluciones");
        define("COORDIANDOR_GRYC", "147");
        define("NOMBRE_COORDIANDOR_GRYC", "Coordinador de Grupo de Recaudo y Cartera");
        define("TECNICO_CARTERA", "145");
        define("NOMBRE_TECNICO_CARTERA", "tecnico_profesional_cartera");

        define("SOLICITUD_DEVOLUCION_CREADA", "1229");
        define("LIDER_DEVOLUCIONES_ASIGNADO", "1230");
        define("LIDER_DEVOLUCIONES_DG_ASIGNADO", "1260");
        define("REVISION_CORRECTA", "1234");
        define("REVISION_CORRECTA_OTROSCONCEPTOS", "1353");
        define("REVISION_INCORRECTA", "1235");
        define("DISMINUCION_REALIZADA", "1237");
        define("ES_VIABLE", "1240");
        define("ES_VIABLE_OTROS_CONCEPTOS", "1355");
        define("NO_ES_VIABLE", "1241");
        define("RECURSO_INTERPUESTO", "1310");
        define("RECURSO_NO_INTERPUESTO", "1311");
        define("DEVOLUCION_APROBADA", "1319");

        define("CONCEPTO_LEY590", "1");
        define("CONCEPTO_PUNTO5", "2");
        define("CONCEPTO_IBCERRADO", "3");
        define("CONCEPTO_SALARIOINTEGRAL", "4");
    }

    function index() {
        $this->template->load($this->template_file, 'devolucion/devolucion_devolucionesgeneradas', $this->data);
    }

    function getData() {
        if ($this->ion_auth->logged_in()) {
            $lenght = $this->input->post('iDisplayLength');
            $dato1 = "";
            $dato = "";
            $parametro = $this->input->post('para');
            $apro = $this->input->post('sin_aprobar');
            if ($apro != "s")
                $apro = "n";
            $apro_con = $this->input->post('envio_conta');
            if ($apro_con != "s")
                $apro_con = "n";
            if ($parametro == "NIT")
                $dato = $this->input->post('nit');
            else if ($parametro == "NRO_RADICACION")
                $dato = $this->input->post('num_ra');
            else if ($parametro == "FECHA_RADICACION")
                $dato = $this->input->post('fecha');
            else if ($parametro == "NRO_PLANILLA")
                $dato = $this->input->post('num_pla');
            else if ($parametro == "gen1") {
                $dato = $this->input->post('gen1');
                $dato1 = $this->input->post('gen2');
            }


            $data['registros'] = $this->devoluciones_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght, $parametro, $dato, $dato1, $apro, $apro_con);
            define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
            $TOTAL = $this->devoluciones_model->totalData($this->input->post('sSearch'), $parametro, $dato, $dato1, $apro, $apro_con);
            echo json_encode(array('aaData' => $data['registros'],
                'iTotalRecords' => $TOTAL,
                'iTotalDisplayRecords' => $TOTAL,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getData1() {
        if ($this->ion_auth->logged_in()) {
            $lenght = $this->input->post('iDisplayLength');
            $nit = $this->input->post('nit_cal');
            $des = $this->input->post('des_cal');
            $has = $this->input->post('has_cal');
            $des = date("Y/d", strtotime($des));
            $has = date("Y/d", strtotime($has));
            $this->data['ibc'] = $this->input->post('ibcval_cal');
            $this->data['tipo'] = $this->input->post('tipo_cal');
            $this->data['cuadro'] = $this->input->post('cua_cal');

            $data['registros'] = $this->devoluciones_model->getDatax1($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght, $nit, $des, $has);
            define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
            $TOTAL = $this->devoluciones_model->totalData1($this->input->post('sSearch'), $nit, $des, $has);
            echo json_encode(array('aaData' => $data['registros'],
                'iTotalRecords' => $TOTAL,
                'iTotalDisplayRecords' => $TOTAL,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function modificar_planillas() {
        $codigo_planilla = $this->input->post('planilla');
        $this->data['datos_planillas'] = $this->devoluciones_model->datos_planilla($codigo_planilla);
        if ($this->data['datos_planillas']) {
            $this->template->load($this->template_file, 'devolucion/devolucion_modificarplanillas', $this->data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Planilla No Existe</div>');
            redirect(base_url() . 'index.php/pila/consultarempresa');
        }
    }

    function exportar_excel() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                header("Content-type: application/vnd.ms-excel; name='excel'");
                header("Content-Disposition: filename=ficheroExcel.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                $this->input->post('datos_a_enviar');
//echo $_POST['datos_a_enviar'];
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_modpla() {
        $data = "";
        $data1 = "";
        $cod_planilla = $this->input->post('numeroplanilla_modificar');
        $nuevo_nit = $this->input->post('nuevonit_modificarplanilla');
        $digito_verificacion = $this->input->post('digito_verificacion1');
//        $nuevo_pago = $this->input->post('ajustepago_modificar');
        $nuevo_periodo = $this->input->post('nuevoperiodo_modificar');
        if ($nuevo_nit)
            $data['N_INDENT_APORTANTE'] = $nuevo_nit;
        if ($digito_verificacion)
            $data['DIG_VERIF_NIT'] = $digito_verificacion;
        if ($nuevo_periodo)
            $data['PERIDO_PAGO'] = $nuevo_periodo;
//        if ($nuevo_pago)
//            $data1['APORTE_OBLIG'] = $nuevo_pago;
//        $this->data['modificar_planillas'] = $this->devoluciones_model->modificar_planilla($cod_planilla, $data, $data1);
        $this->data['modificar_planillas'] = $this->devoluciones_model->modificar_planilla($cod_planilla, $data, $data1);
        redirect(base_url() . 'index.php/pila/consultarempresa');
    }

    function devolucion_add() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_menu('devolucion/devolucion_add') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC)) {
                if (COD_REGIONAL == '1') {
                    $cargo = $this->TECNICOCARTERA;
                    $this->data['tipo_devolucion'] = $this->input->post('tipo_solicitud');
                    $this->data['titulo'] = '<h3>Solicitud de Devolución PILA</h3>';
                    $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                    $this->data['conceptos'] = $this->devoluciones_model->consultar_conceptoEspecifico(2); //consultar conceptos pila
                    $this->data['devoluciones'] = $this->devoluciones_model->consultar_motivoEspecifico(2); //consultar motivos pila
                    $this->template->load($this->template_file, 'devolucion/radicar_Onbase', $this->data);
                } else {
                    $this->template->load($this->template_file, 'devolucion/devolucion_index', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        }
    }

    function devoluciones_generadas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['registros'] = $this->devoluciones_model->buscar(ID_USER);
                $this->template->load($this->template_file, 'devolucion/devolucion_devolucionesgeneradas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function crear_documentos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_menu('ecollect/manage') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION) || $this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
                $respuesta = $this->data['respuesta'] = $this->input->post('respuesta');
                $this->data['devolucion'] = $this->input->post('devolucion');
                if ($this->input->post('devolucion') == '') {
                    $this->data['devolucion'] = $this->input->post('cod_devolucion');
                }
                $nit = $this->data['nit'] = $this->input->post('nit');
                // Obtiene el historial de comentarios
                @$this->data['comentario'] = $this->devoluciones_model->get_comentarios_proceso($this->data['devolucion']);

                switch ($respuesta) {
                    case '1184':
                        $this->data['titulo'] = "<h2>Certificación</h2>";
                        $this->data['tipo'] = "certificacion";
                        $cargo = $this->COORDINADORRELACIONES;
                        $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                        $this->template->load($this->template_file, 'devolucion/documentos_add', $this->data);
                        break;
                    case '1185':
                        $this->data['titulo'] = "<h2>Respuesta de Comunicación</h2>";
                        $this->data['tipo'] = "respuesta";
                        $this->template->load($this->template_file, 'devolucion/documentos_add', $this->data);
                        break;
                    case '1192':
                        $this->data['titulo'] = "<h2>Recurso</h2>";
                        $this->data['tipo'] = "recurso";
                        $cargo = $this->COORDINADORRELACIONES;
                        $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                        $this->template->load($this->template_file, 'devolucion/recurso_add', $this->data);
                        break;
                    case NO_ES_VIABLE:
                        $this->data['titulo'] = "<h2>Respuesta de Comunicación</h2>";
                        $this->data['tipo'] = "respuesta_general";
                        $this->template->load($this->template_file, 'devolucion/documentos_add', $this->data);
                        break;
                    case RECURSO_INTERPUESTO:
                        $this->data['titulo'] = "<h2>Recurso</h2>";
                        $this->data['tipo'] = "recurso_general";
                        $this->template->load($this->template_file, 'devolucion/recurso_add', $this->data);
                        break;
                    case DEVOLUCION_APROBADA:
                        $this->data['titulo'] = "<h2>Carta de Respuesta al Empresario</h2>";
                        $this->data['tipo'] = "respuesta_empresario";
                        $this->template->load($this->template_file, 'devolucion/documentos_add', $this->data);
                        break;
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_documentos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_menu('ecollect/manage') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION) || $this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[500]');

                if ($this->form_validation->run() == false) {
                    redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $devolucion = $this->input->post('devolucion');
                    $notificacion = $this->input->post('notificacion');
                    $nit = $this->input->post('nit');
                    $tipo = $this->input->post('tipo');
                    @$deudor = $this->input->post('deudor');

                    $cRuta = "./uploads/devolucion/" . $nit . "/" . $tipo . "/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $notificacion);

                    switch ($tipo) {
                        case 'certificacion':
                            $tipogestion = 457;
                            $tiporespuesta = 1186;
                            $comentarios = 'Certificación Generada';
                            $asignado = $this->input->post('asignado');
                            $dato = array(
                                'ESTADO' => '2'
                            );
                            $document = $this->devoluciones_model->actEstadoDocumentos($dato, $devolucion);
                            break;
                        case 'respuesta':
                            $tipogestion = 458;
                            $tiporespuesta = 1187;
                            $comentarios = 'Carta de Respuesta Comunicación Generada';
                            $asignado = ID_USER;
                            break;
                        case 'recurso':
                            if ($deudor == 1) {
                                $tipogestion = 465;
                                $tiporespuesta = 1196;
                                $comentarios = 'A Favor del Deudor';
                                $asignado = ID_USER;
                            } else {
                                $tipogestion = 465;
                                $tiporespuesta = 1197;
                                $comentarios = 'No Favorece al Deudor';
                                $asignado = ID_USER;
                            }
                            $dato = array(
                                'ESTADO' => '2'
                            );
                            $document = $this->devoluciones_model->actEstadoDocumentos($dato, $devolucion);
                            break;
                        case 'respuesta_general':
                            $tipogestion = 458;
                            $tiporespuesta = 1306;
                            $comentarios = 'Carta de Respuesta Comunicación Generada Direccion General';
                            $asignado = ID_USER;
                            break;
                        case 'recurso_general':
                            if ($deudor == 1) {
                                $tipogestion = 465;
                                $tiporespuesta = 1312;
                                $comentarios = 'A Favor del Deudor';
                                $asignado = ID_USER;
                            } else {
                                $tipogestion = 465;
                                $tiporespuesta = 1113;
                                $comentarios = 'No Favorece al Deudor';
                                $asignado = ID_USER;
                            }
                            break;
                        case 'respuesta_empresario':
                            $tipogestion = 496;
                            $tiporespuesta = 1238;
                            $comentarios = 'Carta de Respuesta Comunicación al empresario Direccion General';
                            $asignado = ID_USER;
                            break;
                    }

                    //$gestion = $this->TrazaDevoluciones($devolucion, $tiporespuesta, $comentarios);
                    $_comentario = $comentarios . "-" . $this->input->post('comentarios');
                    $gestion = $this->TrazaDevoluciones($devolucion, $tiporespuesta, $_comentario);
                    $_comentario = '';
                    $data = array(
                        'COD_DEVOLUCION' => $devolucion,
                        'NOMBRE_DOCUMENTO' => $sFile,
                        'FECHA_CREACION' => date("d/m/Y"),
                        'CREADO_POR' => ID_USER,
                        'REVISADO_POR' => ID_USER,
                        'COMENTADO_POR' => ID_USER,
                        'ASIGNADO' => $asignado,
                        'COMENTARIO' => $this->input->post('comentarios'),
                        'COD_RESPUESTA' => $tiporespuesta,
                        'ESTADO' => 1,
                    );
                    // echo $tipo;
                    $insert = $this->devoluciones_model->guardar_doc($data);
                    if ($insert == TRUE) {
                        $update = $this->devoluciones_model->actualizar_devolucion($data, $devolucion, $tipo);
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Creado correctamente.</div>');
                        if ($tiporespuesta == 1312 || $tiporespuesta == 1113 || $tiporespuesta == 1306 || $tiporespuesta == 1238) {
                            redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                        } else {
                            redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Documento no se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function documentos_edit() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_menu('ecollect/manage') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION) || $this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[500]');
                if ($this->form_validation->run() == false) {
                    redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $devolucion = $this->input->post('devolucion');
                    $notificacion = $this->input->post('notificacion');
                    $nit = $this->input->post('nit');
                    $tipo = $this->input->post('tipo');
                    $asignado = $this->input->post('asignado');
                    $aprobado = $this->input->post('aprobado');
                    $plantilla = $this->devoluciones_model->consultar_docs($devolucion);

                    if ($plantilla->NOMBRE_DOCUMENTO != "") {
                        $cFile = "./uploads/devolucion/" . $nit . "/" . $tipo;
                        $cFile .= "/" . $plantilla->NOMBRE_DOCUMENTO;
                        if (is_file($cFile)) {
                            $cMandamiento = read_template($cFile);
                        }
                    } else {
                        $cMandamiento = "";
                    }

                    if (trim($this->input->post('notificacion')) == trim($cMandamiento)) {
                        $bGenera = FALSE;
                    } else {
                        $bGenera = TRUE;
                    }

                    if ($bGenera == TRUE) {
                        unlink($cFile);
                        $cRuta = "./uploads/devolucion/" . $nit . "/" . $tipo . "/";
                        $cPlantilla = create_template($cRuta, $notificacion);
                    } else {
                        $cPlantilla = $plantilla->NOMBRE_DOCUMENTO;
                    }

                    $docFile = $this->do_upload($nit, $tipo);
                    if ($aprobado == 1 && $docFile["error"]) {
                        $tipogestion = 466;
                        $tiporespuesta = 1198;
                        $comentarios = 'Certificación Correcta';
                        $asignado = $this->input->post('asignado');
                        $file = $cPlantilla;
                    } else if ($aprobado == 1 && $docFile["upload_data"]) {
                        $tipogestion = 469;
                        $tiporespuesta = 1200;
                        $comentarios = 'Certificación Cargada';
                        $asignado = $this->input->post('asignado');
                        $file = $docFile["upload_data"]["file_name"];
                    } else if ($aprobado == 0) {
                        $tipogestion = 466;
                        $tiporespuesta = 1199;
                        $comentarios = 'Certificación Incorrecta';
                        $asignado = $this->input->post('asignado');
                        $file = $cPlantilla;
                    } else if ($aprobado == 2) {
                        $tipogestion = 466;
                        $tiporespuesta = 1254;
                        $comentarios = 'Certificación Pre-Aproada';
                        $asignado = $this->input->post('asignado');
                        $file = $cPlantilla;
                    }
                    $data = array(
                        'COD_DEVOLUCION' => $devolucion,
                        'NOMBRE_DOCUMENTO' => $file,
                        'CREADO_POR' => ID_USER,
                        'REVISADO_POR' => ID_USER,
                        'COMENTADO_POR' => ID_USER,
                        'COMENTARIO' => $this->input->post('comentarios'),
                        'COD_RESPUESTA' => $tiporespuesta,
                        'ASIGNADO' => $asignado,
                        'ESTADO' => 1,
                        'APROBADO' => $aprobado
                    );

                    // $insert = $this->devoluciones_model->actualizar_documentos($data, $devolucion);
                    $insert = $this->devoluciones_model->guardar_doc($data);

                    if ($insert == TRUE) {
                        $update = $this->devoluciones_model->actualizar_devolucion($data, $devolucion, $tipo);
                        /*
                         * INSERTAR EN LA TRAZA
                         */
                        $this->TrazaDevoluciones($devolucion, $tiporespuesta, $this->input->post('comentarios'));
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Creado correctamente.</div>');
                        redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Documento no se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_verificacion() {
        if ($this->ion_auth->logged_in()) {
            @$verificacion = $this->input->post('verificacion');
            @$devolucion = $this->input->post('devolucion');
            @$tipo = $this->input->post('tipo');
            switch ($tipo) {
                case 'adicionales':
                    if ($verificacion == 'S') {
                        $tipogestion = 459;
                        $tiporespuesta = 1189;
                        $comentarios = 'Se Agregan Documentos Adicionales';
                    } else {
                        $tipogestion = 459;
                        $tiporespuesta = 1188;
                        $comentarios = 'No se Agregan Documentos Adicionales';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    break;
                case 'adicionales_general':
                    if ($verificacion == 'S') {
                        $tipogestion = 459;
                        $tiporespuesta = 1307;
                        $comentarios = 'Se Agregan Documentos Adicionales Direccion General';
                    } else {
                        $tipogestion = 508;
                        $tiporespuesta = 1308;
                        $comentarios = 'No se Agregan Documentos Adicionales Direccion General';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );

                    break;
                case 'comunicacion':
                    if ($verificacion == 'S') {
                        $tipogestion = 456;
                        $tiporespuesta = 1184;
                        $comentarios = 'Comunicación y Soportes Completos';
                    } else {
                        $tipogestion = 456;
                        $tiporespuesta = 1185;
                        $comentarios = 'Comunicación y Soportes No Completos';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'VERIFICA_SOPORTES' => $verificacion,
                    );
                    break;
                case 'adicionales_recibidos':
                    if ($verificacion == 'S') {
                        $tipogestion = 460;
                        $tiporespuesta = 1190;
                        $comentarios = 'Documentos Adicionales Recibidos';
                    } else {
                        $tipogestion = 461;
                        $tiporespuesta = 1191;
                        $comentarios = 'Proceso Cerrado';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'VERIFICA_SOPORTES' => $verificacion,
                    );
                    $dato = array(
                        'ESTADO' => '2'
                    );
                    $update = $this->devoluciones_model->actEstadoDocumentos($dato, $devolucion);
                    break;
                case 'adicionales_recibidos_general':
                    if ($verificacion == 'S') {
                        $tipogestion = 460;
                        $tiporespuesta = 1309;
                        $comentarios = 'Documentos Adicionales Recibidos Direccion General';
                    } else {
                        $tipogestion = 461;
                        $tiporespuesta = 1191;
                        $comentarios = 'Proceso Cerrado';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    break;
                case 'recurso':
                    if ($verificacion == 'S') {
                        $tipogestion = 462;
                        $tiporespuesta = 1192;
                        $comentarios = 'Recursos Interpuestos';
                    } else {
                        $tipogestion = 462;
                        $tiporespuesta = 1193;
                        $comentarios = 'Recursos No Interpuestos';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    break;
                case 'recurso_general':
                    if ($verificacion == 'S') {
                        $tipogestion = 462;
                        $tiporespuesta = RECURSO_INTERPUESTO;
                        $comentarios = 'Recursos Interpuestos Direccion General';
                    } else {
                        $tipogestion = 462;
                        $tiporespuesta = 1311;
                        $comentarios = 'Recursos No Interpuestos Direccion General';
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                    );
                    break;
            }

            $gestion = $this->TrazaDevoluciones($devolucion, $tiporespuesta, $comentarios);

            $insert = $this->devoluciones_model->actualizar_devolucion($data, $devolucion, $tipo);
            if ($insert == TRUE) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución se ha Modificado correctamente.</div>');
                if ($tiporespuesta == '1307' || $tiporespuesta == '1309' || $tiporespuesta == '1308' || $tiporespuesta == RECURSO_INTERPUESTO) {
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución no se ha podido modificar.</div>');
                redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editar_documentos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_menu('ecollect/manage') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION) || $this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
                $respuesta = $this->data['respuesta'] = $this->input->post('respuesta');
                $devolucion = $this->data['devolucion'] = $this->input->post('devolucion');
                $nit = $this->data['nit'] = $this->input->post('nit');
                @$this->data['result'] = $this->devoluciones_model->consultar_docs($devolucion);
                @$this->data['comentario'] = $this->devoluciones_model->get_comentarios_proceso($devolucion);
                if (@$this->data['result']->NOMBRE_DOCUMENTO != "") {
                    $cFile = "uploads/devolucion/" . $nit . "/certificacion";
                    $cFile .= "/" . $this->data['result']->NOMBRE_DOCUMENTO;
                    $this->data['plantilla'] = read_template($cFile);
                }
                $this->data['grupo'] = ID_GRUPO;
                $this->data['iduser'] = ID_USER;

                switch ($respuesta) {
                    case '1186':
                        $this->data['titulo'] = "<h2>Certificación</h2>";
                        $this->data['tipo'] = "certificacion";
                        $cargo = $this->TECNICOCARTERA;
                        $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                        break;
                    case '1199':
                        $this->data['titulo'] = "<h2>Certificación</h2>";
                        $this->data['tipo'] = "certificacion";
                        if (ID_GRUPO == 144)
                            $cargo = $this->TECNICOCARTERA;
                        else
                            $cargo = $this->COORDINADORRELACIONES;
                        $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                        break;
                    case '1254':
                        $this->data['titulo'] = "<h2>Certificación</h2>";
                        $this->data['tipo'] = "certificacion";
                        $cargo = $this->TECNICOCARTERA;
                        $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                        break;
                    case '1198':
                        $this->data['titulo'] = "<h2>Certificación</h2>";
                        $this->data['tipo'] = "certificacion";
                        $cargo = $this->TECNICOCARTERA;
                        $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                        break;
                }

                $this->template->load($this->template_file, 'devolucion/documentos_edit', $this->data);
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * MISIONAL
     */

    function radicar_Onbase() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC)) {
                $cargo = $this->TECNICOCARTERA;
                $this->data['tipo_devolucion'] = $this->input->post('tipo_solicitud');
                $this->data['titulo'] = '<h3>Devolución PILA</h3>';
                $this->data['asignado'] = $this->devoluciones_model->lisUsuarios2('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                $this->data['conceptos'] = $this->devoluciones_model->consultar_conceptoEspecifico(2);
                $this->data['devoluciones'] = $this->devoluciones_model->consultar_motivoEspecifico(2);
                $this->template->load($this->template_file, 'devolucion/radicar_Onbase', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function realizar_calculos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/jquery.dataTables.defaults.js', 'js/chosen.jquery.min.js', 'js/jquery.confirm.js');
                $this->data['message'] = $this->session->flashdata('message');
                $this->load->library('Datatables');
                $this->load->library('form_validation');
                $this->template->load($this->template_file, 'devolucion/devolucion_realizarcalculos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function envio_contabilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['codigo'] = $this->uri->segment(3);
                $this->data['numero'] = $this->uri->segment(4);
                $this->load->view('devolucion/devolucion_enviadoconta', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_envio_contabilidad() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $codigo = $this->input->post('obser_contabilidad');
                $numero = $this->input->post('codigos');
                $fecha = $this->input->post('fecha');
                $this->data['guardar'] = $this->devoluciones_model->guardar_enviocontabilidad($codigo, $numero, $fecha);
                $this->template->load($this->template_file, 'devolucion/devolucion_devolucionesgeneradas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function envio_porcorreo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['codigo'] = $this->uri->segment(3);
                $this->data['numero'] = $this->uri->segment(4);
                $this->load->view('devolucion/devolucion_envioporcorreo', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_envio_porcorreo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $codigo = $this->input->post('obser_contabilidad');
                $numero = $this->input->post('codigos');
                $fecha = $this->input->post('fecha');
                $this->data['guardar'] = $this->devoluciones_model->guardar_enviocorreo($codigo, $numero, $fecha);
                $this->template->load($this->template_file, 'devolucion/devolucion_devolucionesgeneradas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function emailNotification() {
        $para = $this->input->post('correo');
        $asun = $this->input->post('Asunto');
        $mensaje = $this->input->post('cuerpo_mensaje');
        enviarcorreosena($para, $mensaje, $asun);
    }

    function pdf() {
        $html = $this->input->post('informacion');
        $nombre_pdf = 'Devoluciones';
        $tipo = '3';
        $data[0] = $tipo;
        $data[1] = $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    function formcorreo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $nit = $this->uri->segment(3);
                $this->data['empresa'] = $this->devoluciones_model->consultar_empresa($nit);

                if ($this->data['empresa']->CORREOELECTRONICO) {

                    $this->load->view('devolucion/devolucion_enviarcorreo', $this->data);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>La Empresa No Tiene Un Correo Registrado En El Sistema</div>');
                    $this->load->view('devolucion/devolucion_enviarcorreo', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>La Empresa no se Encuentra Registrada en el Sietema</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $nnit = $this->uri->segment(3);
                $boton = $this->input->post('generaracta_calculos');

                if ($boton) {
                    $nit = $this->input->post('nit');
                    $desde = $this->input->post('desde_calculos');
                    $hasta = $this->input->post('hasta_calculos');
                    $porcentaje = $this->input->post('cuadro_calculos');
                    $ibc = $this->input->post('ibcvalor_calculos');
                    $tipo = $this->input->post('calculos');
                    $cuadro = $this->input->post('cuadro_calculos');

                    $this->calculos1($nit, $desde, $hasta, $porcentaje, $ibc, $tipo, $cuadro, $tipo);
                } else {
                    if ($nnit) {
                        $nit = $nnit;
                    } else {
                        $nit = $this->input->post('nit');
                    }

                    $this->data['message'] = $this->session->flashdata('message');
                    $this->data['viene'] = "2";
                    $this->data['motivo_devol'] = $this->devoluciones_model->consultar_motivo();
                    $this->data['concepto_devol'] = $this->devoluciones_model->consultar_concepto();
                    $this->data['cargos'] = $this->devoluciones_model->consultar_cargos();
                    $this->data['usuarios'] = $this->devoluciones_model->consultar_usuarios();
                    $this->data['empresa'] = $this->devoluciones_model->consultar_empresa($nit);
                    if ($this->data['empresa'])
                        $this->template->load($this->template_file, 'devolucion/devolucion_add', $this->data);
                    else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Empresa no se Encuentra Registrada en el Sietema</div>');
                        redirect(base_url() . 'index.php/devolucion/realizar_calculos');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/auth/login');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_devolucion() {
        $this->data['post'] = $this->input->post();
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('devolucion/guardar_devolucion') || $this->input->post('identificacion')) {
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
                if (REGIONAL_USER != '1') {
                    $this->form_validation->set_rules('asignado', 'Asignado a', 'required');
                }
                $this->form_validation->set_rules('identificacion', 'Identificacion', 'required');
                $this->form_validation->set_rules('onbase', 'Número Onbase', 'required');

                if ($this->form_validation->run() == false) {
                    redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $identificacion = $this->input->post('identificacion');
                    $onbase = $this->input->post('onbase');
                    if (REGIONAL_USER != '1') {
                        $asignado = $this->input->post('asignado');
                    } else {
                        $asignado = ID_USER;
                    }
                    $observaciones = $this->input->post('comentarios');
                    $tipo_devolucion = $this->input->post('tipo_devolucion');
                    $conceptos = $this->input->post('conceptos');
                    $num_radicado = $this->input->post('onbase');
                    $fecha_radicado = $this->input->post('fecha_radicado');
                    $cod_devolucion = $this->input->post('devoluciones');
                    $user = $this->ion_auth->user()->row();
                    $tipogestion = 455;
                    $tiporespuesta = 1183;
                    $comentarios = 'Comunicación Asignada';
                    if (REGIONAL_USER == '1') {
                        $tiporespuesta = SOLICITUD_DEVOLUCION_CREADA;
                    } else {
                        $tiporespuesta = 1183;
                    }
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'OBSERVACIONES' => $observaciones,
                        'NIT' => $identificacion,
                        'NRO_RADICADO_ONBASE' => $onbase,
                        'TIPO_DEVOLUCION' => $tipo_devolucion,
                        'NRO_RADICACION' => $num_radicado,
                        'COD_CONCEPTO' => $conceptos,
                        'COD_MOTIVO_DEVOLUCION' => $cod_devolucion,
                        'INFORMANTE' => $user->IDUSUARIO,
                        'CARGO' => 'Coordinador Relaciones Corporativas',
                        'ASIGNADO' => $asignado,
                        'FECHA_RADICACION' => $fecha_radicado,
                        'REGIONAL_DEVOLUCION' => REGIONAL_USER
                    );

                    $insert = $this->devoluciones_model->guardarDevolucion($data);

                    // Obtiene el codigo de devolucion asignado en el ultimo insert
                    $ultCodDev = $this->devoluciones_model->get_ultimo_codigo_dev();

                    // $gestion = $this->TrazaDevoluciones($insert, $tiporespuesta, $comentarios);
                    $_comentario = $comentarios . "-" . $observaciones;
                    $gestion = $this->TrazaDevoluciones($ultCodDev, $tiporespuesta, $_comentario);
                    $_comentario = '';

                    if ($gestion != '') {
                        // Deja rastro del primer comentario cuando crea el documento
                        $dataNot = array(
                            'COD_DEVOLUCION' => $ultCodDev,
                            'NOMBRE_DOCUMENTO' => '0f0000f0f000000f000ff0f0ff0f0000.txt',
                            'FECHA_CREACION' => date("d/m/Y"),
                            'CREADO_POR' => ID_USER,
                            'REVISADO_POR' => ID_USER,
                            'COMENTADO_POR' => ID_USER,
                            'ASIGNADO' => $asignado,
                            'COMENTARIO' => $observaciones,
                            'COD_RESPUESTA' => $tiporespuesta,
                            'ESTADO' => 1,
                        );
                        $this->devoluciones_model->guardar_doc($dataNot);
                        $ultCodDev = '';
                        $dataNot = array();

                        if (REGIONAL_USER == '1') {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución se ha creado correctamente.</div>');
                            redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución se ha creado correctamente.</div>');
                            redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución no se ha podido crear.</div>');
                        redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    }
                }
            } else {
                redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_Onbase() {
        $this->data['post'] = $this->input->post();
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->is_admin() || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION) || $this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('identificacion', 'Identificacion', 'required');
                $this->form_validation->set_rules('onbase', 'Número Onbase', 'required');
                if ($this->form_validation->run() == false) {
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $identificacion = $this->input->post('identificacion');
                    $onbase = $this->input->post('onbase');
                    if (REGIONAL_USER != '1') {
                        $asignado = $this->input->post('asignado');
                    } else {
                        $asignado = ID_USER;
                    }
                    $observaciones = $this->input->post('comentarios');
                    $tipo_devolucion = $this->input->post('tipo_devolucion');
                    $conceptos = $this->input->post('conceptos');
                    $num_radicado = $this->input->post('onbase');
                    $fecha_radicado = $this->input->post('fecha_radicado');
                    $cod_devolucion = $this->input->post('devoluciones');
                    $user = $this->ion_auth->user()->row();
                    $tipogestion = 471;

                    $comentarios = 'Solicitud Desde Pila';
                    $tiporespuesta = SOLICITUD_DEVOLUCION_CREADA;
                    $data = array(
                        'COD_RESPUESTA' => $tiporespuesta,
                        'OBSERVACIONES' => $observaciones,
                        'NIT' => $identificacion,
                        'NRO_RADICADO_ONBASE' => $onbase,
                        'TIPO_DEVOLUCION' => $tipo_devolucion,
                        'NRO_RADICACION' => $num_radicado,
                        'COD_CONCEPTO' => $conceptos,
                        'COD_MOTIVO_DEVOLUCION' => $cod_devolucion,
                        'INFORMANTE' => $user->IDUSUARIO,
                        'CARGO' => 'Coordinador Relaciones Corporativas',
                        'REGIONAL_DEVOLUCION' => REGIONAL_USER,
                        'FECHA_RADICACION' => $fecha_radicado
                    );

                    $insert = $this->devoluciones_model->guardarDevolucion($data);
                    $gestion = $this->TrazaDevoluciones($insert, $tiporespuesta, $observaciones);
                    if ($gestion != '') {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Devolución no se ha podido crear.</div>');
                        redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
                    }
                }
            } else {
                redirect(base_url() . 'index.php/devolucion/devoluciones_generadas');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_aprobacion() {
        $valor = $this->input->post('aprobar');
        $comentarios = $this->input->post('comen_aprobar');
        $cod_dev = $this->input->post('cod_devo');
        $this->devoluciones_model->guardar_aproba($valor, $comentarios, $cod_dev);
        $this->template->load($this->template_file, 'devolucion/devolucion_devolucionesgeneradas', $this->data);
    }

    private function do_upload($cProceso, $cTipo) {
        $this->load->library('upload');
        $cFile = "./uploads/devolucion/" . $cProceso . "/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }
        $cFile = $cFile . $cTipo . "/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }

        $config['upload_path'] = $cFile;
        $config['allowed_types'] = '*';
        $config['max_size'] = '6048';
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload('filecolilla')) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

    function adicionar() {
        $this->data['traer_nombre'] = $this->devoluciones_model->traer_nombre($this->input->post('infor'));
        $data = array(
//                        'COD_DEVOLUCION'        => '1',
            'NIT' => $this->input->post('nit1'),
            'NRO_RADICACION' => $this->input->post('num_rad1'),
            'FECHA_RADICACION' => $this->input->post('fec_rad1'),
            'NRO_PLANILLA' => $this->input->post('num_pla1'),
            'VALOR_DEVOLUCION' => $this->input->post('val_dev1'),
            'COD_MOTIVO_DEVOLUCION' => $this->input->post('moti1'),
            'COD_CONCEPTO' => $this->input->post('concep1'),
            'IDTICKET' => $this->input->post('tikcet1'),
            'INFORMANTE' => $this->data['traer_nombre']->NOMBREUSUARIO,
            'CARGO' => $this->input->post('cargos1'),
            'PERIODO' => $this->input->post('periodo1'),
            'FECHA_REGISTRO' => $this->input->post('fecha1'),
            'VALOR_PAGADO' => $this->input->post('valor_pagado1'),
            'VALOR_CONCILIADO' => $this->input->post('valor_pagado1'),
            'verificar' => '1',
            'cedula' => $this->input->post('infor')
        );
        $this->session->set_userdata($data);
        $nit = $this->input->post('nit1');
//                 $this->devoluciones_model->adicionar_solicitud($data);
        redirect(base_url() . 'index.php/devolucion/manage/' . $nit);
    }

    function adicionar1() {
        $nit_ingresado = $this->session->userdata('NIT');
        $data = array(
            'NIT' => $nit_ingresado,
            'NRO_RADICACION' => $this->session->userdata('NRO_RADICACION'),
            'NRO_PLANILLA' => $this->session->userdata('NRO_PLANILLA'),
            'VALOR_DEVOLUCION' => $this->session->userdata('VALOR_DEVOLUCION'),
            'COD_MOTIVO_DEVOLUCION' => $this->session->userdata('COD_MOTIVO_DEVOLUCION'),
            'COD_CONCEPTO' => $this->session->userdata('COD_CONCEPTO'),
            'IDTICKET' => $this->session->userdata('IDTICKET'),
            'INFORMANTE' => $this->session->userdata('cedula'),
            'CARGO' => $this->session->userdata('CARGO'),
            'PERIODO' => $this->session->userdata('PERIODO'),
            'VALOR_PAGADO' => $this->session->userdata('VALOR_PAGADO'),
            'VALOR_CONCILIADO' => $this->session->userdata('VALOR_CONCILIADO'),
            'APROBADO' => 'n',
            'ENVIADO_CONTABILIDAD' => 'n'
        );
        $data2 = array(
            'FECHA_RADICACION' => $this->session->userdata('FECHA_RADICACION'),
            'FECHA_REGISTRO' => $this->session->userdata('FECHA_REGISTRO')
        );
        $base_de_datos = "SOLICITUDDEVOLUCION";
        $this->devoluciones_model->adicionar_solicitud($data, $base_de_datos, $data2);
        $this->data['traer'] = $this->devoluciones_model->traer_cod($nit_ingresado);
        $data1 = array(
            'NRO_RADICACION' => $this->session->userdata('NRO_RADICACION'),
            'FECHA_RADICADO' => $this->session->userdata('FECHA_RADICACION'),
            'COD_DEVOLUCION' => $this->data['traer']->COD_DEVOLUCION,
            'ENVIADO_POR' => $this->session->userdata('cedula'),
            'CARGO' => $this->session->userdata('CARGO'),
            'OBSERVACIONES' => $this->input->post('content'),
        );
        $data2 = array(
            'FECHA_RADICADO' => $this->session->userdata('FECHA_RADICACION'),
        );
        $base_de_datos = "CORRESPONDENCIADEVOLUCION";
        $this->devoluciones_model->adicionar_solicitud($data1, $base_de_datos, $data2);
        $this->session->unset_userdata($data);

        redirect(base_url() . 'index.php/devolucion/realizar_calculos');
    }

    function devolucion_detalle() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('ecollect/manage')) {
                $dato = $this->uri->segment(3);
                $this->data['codigo'] = $this->uri->segment(3);
                $this->data['valor_ticket'] = $this->input->post('nit');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['buscar'] = $this->devoluciones_model->buscar_detalle($dato);
                $this->load->view('devolucion/devolucion_detalle', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function mostrar_planilla() {
        $nit_devolucion = $this->uri->segment(3);
        $this->data['mostrarplanilla'] = $this->devoluciones_model->buscar_planilla_devolucion($nit_devolucion);
        $this->data['nit'] = $nit_devolucion;
        $this->load->view('devolucion/devolucion_mostrarplanillas', $this->data);
    }

    function mostrar_planilla1() {
        $nit_devolucion = $this->input->post('sel');
        $codigo_planilla = $this->input->post('codigo');
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['viene'] = "1";
        $this->data['motivo_devol'] = $this->devoluciones_model->consultar_motivo();
        $this->data['concepto_devol'] = $this->devoluciones_model->consultar_concepto();
        $this->data['cargos'] = $this->devoluciones_model->consultar_cargos();
        $this->data['usuarios'] = $this->devoluciones_model->consultar_usuarios();
        $this->data['mostrarplanilla1'] = $this->devoluciones_model->buscar_planilla_devolucion1($nit_devolucion, $codigo_planilla);
        $this->template->load($this->template_file, 'devolucion/devolucion_add', $this->data);
    }

    function calculos() {
        $nit = $this->input->post('nit_cal');
        $des = $this->input->post('des_cal');
        $has = $this->input->post('has_cal');
        $des = date("Y/d", strtotime($des));
        $has = date("Y/d", strtotime($has));

        $this->data['ibc'] = $this->input->post('ibcval_cal');



        $this->data['tipo'] = $this->input->post('tipo_cal');
        $this->data['cuadro'] = $this->input->post('cua_cal');
        $this->data['planilla'] = $this->devoluciones_model->buscar_planilla($nit, $des, $has);
        $this->load->view('devolucion/calculos_detalle', $this->data);
    }

    function calculos1($nit1, $des_cal, $has_cal, $porcentaje, $ibc, $tipo, $cuadro, $tipo) {
        $nit = $nit1;
        $des = $des_cal;
        $has = $has_cal;
        $des = date("Y/d", strtotime($des));
        $has = date("Y/d", strtotime($has));
        $porcentaje = $this->input->post('cuadro_calculos');
        $this->data['ibc'] = $ibc;
        $this->data['nit'] = $nit;
        $this->data['porcentaje'] = $porcentaje;
        $this->data['tipo'] = $tipo;
        $this->data['cuadro'] = $cuadro;
        $this->data['tipo'] = $tipo;
        $this->data['planilla'] = $this->devoluciones_model->buscar_planilla($nit, $des, $has);
        $this->template->load($this->template_file, 'devolucion/devolucion_generaractaenviocontabilidad', $this->data);
    }

    function gerear_actaenviocontabilidad() {
        $this->template->load($this->template_file, 'devolucion/devolucion_generaractaenviocontabilidad', $this->data);
    }

    /*
     *  MISIONAL
     */

    function asignar_comunicacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group(NOMBRE_COORDIANDOR_GRYC) || $this->ion_auth->in_group(NOMBRE_LIDER_DEVOLUCION) || $this->ion_auth->in_group(NOMBRE_TECNICO_CARTERA)) {
                $cargo = $this->TECNICOCARTERA;
                $tipo = $this->input->post('tipo_solicitud');
                $this->data['tipo_devolucion'] = $this->input->post('tipo_solicitud');
                $this->data['titulo'] = '<h3>Asignar Comunicación</h3>';
                $this->data['asignado'] = $this->devoluciones_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', REGIONAL_USER, $cargo, 'NOMBRES, APELLIDOS');
                switch ($tipo) {
                    case 'N':
                        $this->data['conceptos'] = $this->devoluciones_model->consultar_conceptoEspecifico(1);
                        $this->data['devoluciones'] = $this->devoluciones_model->consultar_motivoEspecifico(1);
                        break;
                    case 'M':
                        $this->data['conceptos'] = $this->devoluciones_model->consultar_conceptoEspecifico(0);
                        $this->data['devoluciones'] = $this->devoluciones_model->consultar_motivoEspecifico(0);
                    default:
                        break;
                }

                $this->template->load($this->template_file, 'devolucion/asignar_comunicacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        }
    }

    function buscar() {
        $dato1 = "";
        $parametro = $this->input->post('para');
        $apro = $this->input->post('sin_aprobar');
        $apro_con = $this->input->post('envio_conta');

        if ($parametro == "NIT")
            $dato = $this->input->post('nit');

        else if ($parametro == "NRO_RADICACION")
            $dato = $this->input->post('numero_ra');
        else if ($parametro == "NRO_RADICACION")
            $dato = $this->input->post('numero_ra');
        else if ($parametro == "FECHA_RADICACION")
            $dato = $this->input->post('fecha');
        else if ($parametro == "NRO_PLANILLA")
            $dato = $this->input->post('num_pla');
        else if ($parametro == "gen1") {
            $dato = $this->input->post('gen1');
            $dato1 = $this->input->post('gen2');
        }
        $this->data['buscar'] = $this->devoluciones_model->buscar($dato, $parametro, $dato1, $apro, $apro_con);
        if (!$this->data['buscar'])
            $this->data['message'] = '<div class="alert alert-error">prueba    <button type="button" class="close" data-dismiss="alert">&times;</button></div>';

        $this->load->view('devolucion/dbl_detalle', $this->data);
    }

    function traerempresa() {
        $nit = $this->input->post('nit');
        if (empty($nit)) {
            redirect(base_url() . 'index.php/agregar_condicionesremisibilidad');
        } else {
            $result = $this->devoluciones_model->consultar_empresa($nit);
            if (!empty($result)) :
                $this->output->set_content_type('application/json')->set_output(json_encode($result));
            endif;
        }
    }

    function buscar_empresa() {
        $empresa = $this->input->get("term");
        $consulta = $this->devoluciones_model->consultar_empresa($empresa);
        $temp = NULL;
        if (!is_null($consulta)) {
            foreach ($consulta as $datos) {
                $temp[] = array("value" => $datos['CODEMPRESA'], "label" => $datos['CODEMPRESA'] . " - " . $datos['RAZON_SOCIAL']);
            }
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($temp));
    }

    function verificar_comunicacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('tecnico_profesional_cartera') || $this->ion_auth->in_menu('ecollect/manage')) {
                $respuesta = $this->data['respuesta'] = $this->input->post('respuesta');
                $this->data['nit'] = $this->input->post('nit');
                $this->data['devolucion'] = $this->input->post('devolucion');
                if ($this->input->post('devolucion') == '') {
                    $this->data['devolucion'] = $this->input->post('cod_devolucion');
                }
                $this->data['razon'] = $this->input->post('razon');
                $this->data['tipo_devolucion'] = $this->input->post('tipo_devolucion');
                $this->data['soportes'] = $this->input->post('soportes');
                $this->data['fecha'] = $this->devoluciones_model->get_fechadevolucion($this->input->post('devolucion'));
                if ($respuesta == 1183 || $respuesta == 1190 || $respuesta == 1196 || $respuesta == 1235) {
                    $this->data['tipo'] = 'comunicacion';
                    $this->load->view('devolucion/verificar_comunicacion', $this->data);
                } else if ($respuesta == 1187) {
                    $this->data['tipo'] = 'adicionales';
                    $this->load->view('devolucion/verificar_adicionales', $this->data);
                } else if ($respuesta == 1188) {
                    $this->data['tipo'] = 'recurso';
                    $this->load->view('devolucion/recurso', $this->data);
                } else if ($respuesta == 1189) {
                    $this->data['tipo'] = 'adicionales_recibidos';
                    $this->load->view('devolucion/adicionales_recibidos', $this->data);
                } else if ($respuesta == 1306) {
                    $this->data['tipo'] = 'adicionales_general';
                    $this->template->load($this->template_file, 'devolucion/verificar_adicionales', $this->data);
                } else if ($respuesta == 1307) {
                    $this->data['tipo'] = 'adicionales_recibidos_general';
                    $this->template->load($this->template_file, 'devolucion/adicionales_recibidos', $this->data);
                } else if ($respuesta == 1308) {
                    $this->data['tipo'] = 'recurso_general';
                    $this->template->load($this->template_file, 'devolucion/recurso', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * VERIFICAR ROL
     */

    private function verificar_rol() {
        $usuario = $this->ion_auth->user()->row();
        $id_usuario = $usuario->IDUSUARIO;
        $rol = $this->devoluciones_model->verificar_permiso($id_usuario);
        if ($rol != FALSE) {
            return $rol[0]['IDGRUPO'];
        } else {
            return FALSE;
        }
    }

    /*
     * MENU PARA DIRECCIONAR GESTIONES
     */

    function Menu_GestionDevoluciones() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('devolucion/Menu_GestionDevoluciones')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $grupo = $this->verificar_rol();
                $this->data['grupo'] = $grupo;
                $this->data['estados_seleccionados'] = $this->devoluciones_model->get_estadosgestion(ID_USER, $grupo);
                $this->template->load($this->template_file, 'devolucion/gestionproceso_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * ASIGNAR LIDER DE DEVOLUCIONES
     */

    function Agregar_AsignacionLider() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $cod_devolucion = $this->input->post('cod_devolucion');
                if ($cod_devolucion == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay devolucion al cual asignar lider.</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('id_lider', 'Lider de Devoluciones', 'required|numeric|greater_than[0]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $data = array(
                            'COD_DEVOLUCION' => $cod_devolucion,
                            'LIDER_DEVOLUCIONES' => $this->input->post('id_lider'),
                            'COD_RESPUESTA' => LIDER_DEVOLUCIONES_ASIGNADO
                        );
                        if ($this->devoluciones_model->get_regionalorigen($cod_devolucion)) {
                            $data['COD_RESPUESTA'] = LIDER_DEVOLUCIONES_DG_ASIGNADO;
                        } else {
                            $data['COD_RESPUESTA'] = LIDER_DEVOLUCIONES_ASIGNADO;
                        }
                        if ($this->devoluciones_model->update_devolucion($data)) {
                            $this->TrazaDevoluciones($cod_devolucion, $data['COD_RESPUESTA'], 'Asignar al Lider de Devoluciones');
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha asignado correctamente al lider de Devoluciones.</div>');
                            redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                    $this->data['cod_devolucion'] = $cod_devolucion;
                    $this->data['lideres'] = $this->devoluciones_model->get_usuariosgrupo(LIDER_DEVOLUCION);
                    $this->template->load($this->template_file, 'devolucion/asignarlider_add', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/devolucion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * VERIFICACION DE DOCUMENTO
     */

    function Verificar_CertDoc() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $cod_devolucion = $this->input->post('cod_devolucion');
                $opc_verificar = $this->input->post('radio_verificacion');
                if (strlen($opc_verificar) === 0) {
                    $this->data['cod_devolucion'] = $cod_devolucion;
                    $this->data['documento'] = $this->devoluciones_model->get_documentosoporte($cod_devolucion);
                    $concepto = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                    switch ($concepto->COD_CONCEPTO) {
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 44:
                        case 45:
                            $this->template->load($this->template_file, 'devolucion/verifcertificadopila_add', $this->data);
                            break;
                        default:
                            $this->template->load($this->template_file, 'devolucion/verifcertificadoydoc_add', $this->data);
                    }
                } else {
                    if ($opc_verificar == REVISION_CORRECTA) {
                        $cod_respuesta = REVISION_CORRECTA_OTROSCONCEPTOS;
                    } else {
                        $cod_respuesta = $opc_verificar;
                    }
                    $data = array(
                        'COD_DEVOLUCION' => $cod_devolucion,
                        'COD_RESPUESTA' => $cod_respuesta
                    );
                    if ($this->devoluciones_model->update_devolucion($data)) {
                        $this->TrazaDevoluciones($cod_devolucion, $cod_respuesta, 'Verficar solicitud de la devolucion');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado correctamente el documento.</div>');
                        redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/devolucion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Verificar_CertDocPILA() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $cod_devolucion = $this->input->post('cod_devolucion');
                $camara = $this->input->post('camara');
                $nit = $this->input->post('nit');
                $banco = $this->input->post('banco');
                $comentarios = $this->input->post('comentarios');
                $this->data['cod_devolucion'] = $cod_devolucion;
                $this->data['documento'] = $this->devoluciones_model->get_documentosoporte($cod_devolucion);
                $verificacion_documentos = $nit . $banco . $camara;
                if ($verificacion_documentos == '') {
                    $cod_respuesta = REVISION_CORRECTA;
                } else {
                    $cod_respuesta = REVISION_INCORRECTA;
                }
                $data = array(
                    'COD_DEVOLUCION' => $cod_devolucion,
                    'COD_RESPUESTA' => $cod_respuesta
                );
                if ($this->devoluciones_model->update_devolucion($data)) {
                    $this->TrazaDevoluciones($cod_devolucion, $cod_respuesta, $comentarios);
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado correctamente el documento.</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/devolucion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTADO DE PLANILLAS DE UNA EMPRESA
     */

    function Listado_Planillas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $this->data['cod_devolucion'] = $cod_devolucion;
                $this->data['estados_seleccionados'] = $this->devoluciones_model->get_planillas($cod_devolucion);
                $this->data['concepto'] = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                switch ($this->data['concepto']->COD_CONCEPTO) {
                    case 1:
                    case 2:                    
                        $this->data['ruta'] = "devolucion/Calcular_Devolucion";
                        break;
                    case 3:
                    case 4:
                    case 5:
                    case 44:
                    case 45:
                        $this->data['ruta'] = "devolucion/Listado_PersonasPlanilla";
                        break;
                    default:
                        break;
                }
                $this->template->load($this->template_file, 'devolucion/planillasempresa_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTADO DE PLANILLAS DE UNA EMPRESA POR PERSONA
     */

    function Listado_PersonasPlanilla() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $planillas_devolucion = $this->input->post('id_planillas');
                $planillas = explode("-", $planillas_devolucion);
                $indice = 0;
                $datos_planilla = '';
                for ($i = 1; $i < sizeof($planillas); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($planillas); $j++) {
                        if ($planillas[$i] == $planillas[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_planillas[$indice] = $planillas[$i];
                        $datos_planilla = $datos_planilla . $planillas[$i] . '-';
                        $indice++;
                    }
                }
                $this->data['planillas'] = $planillas;
                for ($i = 1; $i < sizeof($planillas); $i++) {
                    $this->data['personas_planillas'][$i] = $this->devoluciones_model->get_personasplanilla($planillas[$i]);
                }
                $this->data['cod_devolucion'] = $cod_devolucion;
                $this->data['concepto'] = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                $salario_minimo = $this->devoluciones_model->get_salariominimo();
                $this->data['salario'] = $salario_minimo[0]['SALARIO_VIGENTE'] * 10;
                $this->template->load($this->template_file, 'devolucion/planillaspersonas_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTADO DE PLANILLAS ESCOJIDAS PARA REALIZAR DEVOLUCION
     */

    function Calcular_Devolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $concepto = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                $planillas_devolucion = $this->input->post('id_planillas');
                $planillas = explode("-", $planillas_devolucion);
                $indice = 0;
                $datos_planilla = '';
                for ($i = 1; $i < sizeof($planillas); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($planillas); $j++) {
                        if ($planillas[$i] == $planillas[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $listado_planillas[$indice] = $planillas[$i];
                        $datos_planilla = $datos_planilla . $planillas[$i] . '-';
                        $indice++;
                    }
                }
                if ($indice == 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>¡Cuidado!</strong>  No se selecciono ninguna planilla para realizar la devolución.</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    $this->data['datos_planilla'] = $datos_planilla;
                    for ($i = 0; $i < sizeof($listado_planillas); $i++) {
                        $detalle = $this->devoluciones_model->get_detalleplanilla($listado_planillas[$i]);
                        switch ($concepto->COD_CONCEPTO) {
                            case 1: // ley 590
                                $descuento = $this->input->post('descuento');
                                $aportes = $this->devoluciones_model->get_valorplanilla($listado_planillas[$i]);
                                $this->data['detalle_planilla'][$i] = $detalle;
                                $this->data['planillas'][$i] = $listado_planillas[$i];
                                $this->data['ibc_incorrecto'][$i] = $aportes->IBC;
                                $this->data['ibc_correcto'][$i] = $aportes->IBC * $descuento;
                                $this->data['diferencia_ibc'][$i] = $aportes->IBC - ($aportes->IBC * $descuento);
                                $this->data['valor_incorrecto'][$i] = $aportes->TOTAL_APORTES;
                                $this->data['valor_correcto'][$i] = $this->data['diferencia_ibc'][$i] * $detalle->TARIFA;
                                $valor_devolucion = $this->data['valor_incorrecto'][$i] - $this->data['valor_correcto'][$i];
                                if ($valor_devolucion < 0) {
                                    $valor_devolucion = 0;
                                }
                                $this->data['subtotal'][$i] = $valor_devolucion;
                                $this->data['aportes'][$i] = $aportes;
                                break;
                            case 2:
                                $descuento = 0.005;
                                $aportes = $this->devoluciones_model->get_valorplanilla($listado_planillas[$i]);
                                $this->data['detalle_planilla'][$i] = $detalle;
                                $this->data['planillas'][$i] = $listado_planillas[$i];
                                $this->data['ibc_correcto'][$i] = $aportes->IBC;
                                $this->data['valor_incorrecto'][$i] = $aportes->TOTAL_APORTES;
                                $this->data['valor_correcto'][$i] = $aportes->IBC * $descuento;
                                $valor_devolucion = $this->data['valor_incorrecto'][$i] - $this->data['valor_correcto'][$i];
                                if ($valor_devolucion < 0) {
                                    $valor_devolucion = 0;
                                }
                                $this->data['subtotal'][$i] = $valor_devolucion;
                                break;
                            default:
                                break;
                        }
                    }
                    $this->data['concepto'] = $concepto;
                    $this->data['total'] = array_sum($this->data['subtotal']);
                    $this->data['cod_devolucion'] = $cod_devolucion;
                    $this->data['cod_planillas'] = $planillas_devolucion;
                    $this->data['descuento'] = $descuento;
                    $this->template->load($this->template_file, 'devolucion/calculodevolucion_add', $this->data);
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
     * LISTADO DE PLANILLAS ESCOJIDAS PARA REALIZAR DEVOLUCION POR PERSONA
     */

    function Ingreso_DevolucionPersonas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $cod_personas = $this->input->post('personas');
                for ($i = 0; $i < sizeof($cod_personas); $i++) {
                    $mezcla_datos = explode(";", $cod_personas[$i]);
                    $personas[$i] = $mezcla_datos[0];
                    $planillas[$i] = $mezcla_datos[1];
                    $ibc[$i] = $mezcla_datos[2];
                    $aportes[$i] = $mezcla_datos[3];
                }
                $concepto = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                for ($i = 0; $i < sizeof($cod_personas); $i++) {
                    $this->data['detalle_planilla'][$i] = $this->devoluciones_model->get_detalleplanilla($planillas[$i]);
                    $this->data['planillas'][$i] = $planillas[$i];
                    $this->data['ibc_incorrecto'][$i] = $ibc[$i];
                    $this->data['aportes'][$i] = $aportes[$i];
                    $this->data['personas'][$i] = $personas[$i];
                    $this->data['nombre_persona'][$i] = $this->devoluciones_model->get_detallepersona($personas[$i]);
                }
                $this->data['concepto'] = $concepto;
                $this->data['cod_devolucion'] = $cod_devolucion;
                $this->data['cod_planillas'] = $planillas;
                $this->data['cod_personas'] = $personas;
                $this->template->load($this->template_file, 'devolucion/ingresoibcpersona_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * DISMUNUCION DE PLANILLAS= INSERTAR DEVOLUCION, GUARDAR DATOS ANTIGUOS, ACTUALIZAR LA PLANILLA
     */

    function Disminuir_Devolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $datos_planilla = $this->input->post('datos_planilla');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $descuento = $this->input->post('descuento');
                $planillas = explode("-", $datos_planilla);
                $num = sizeof($planillas) - 1;
                $motivo = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                for ($i = 0; $i < $num; $i++) {
                    $detalle = $this->devoluciones_model->get_detalleplanilla($planillas[$i]);
                    switch ($motivo->COD_CONCEPTO) {
                        case 1:
                            $valor = $this->devoluciones_model->get_valorplanilla($planillas[$i]);
                            $ibc_incorrecto = $valor->IBC;
                            $ibc_correcto = $ibc_incorrecto * $descuento;
                            $diferencia_ibc = $ibc_incorrecto - $ibc_correcto;
                            $valor_incorrecto = $valor->TOTAL_APORTES;
                            $valor_total = $diferencia_ibc * $detalle->TARIFA;
                            //REDONDEADO HACIA ARRIBA
                            $valor_correcto = round($valor_total);
                            break;
                        case 2:
                            $valor = $this->devoluciones_model->get_valorplanilla($planillas[$i]);
                            $ibc_incorrecto = 0;
                            $ibc_correcto = $valor->IBC;
                            $diferencia_ibc = 0;
                            $valor_incorrecto = $valor->TOTAL_APORTES;
                            $valor_total = $ibc_correcto * $descuento;
                            //REDONDEADO HACIA ARRIBA
                            $valor_correcto = round($valor_total);
                            break;
                        default:
                            break;
                    }
                    /*
                     * INSERTAR DEVOLUCION
                     */
                    $datos['COD_DEVOLUCION'] = $cod_devolucion;
                    $datos['COD_PLANILLAUNICA'] = $planillas[$i];
                    $datos['IBC_INCORRECTO'] = $ibc_incorrecto;
                    $datos['VALOR_INCORRECTO'] = $valor_incorrecto;
                    $datos['IBC_CORRECTO'] = $ibc_correcto;
                    $datos['VALOR_CORRECTO'] = $valor_correcto;
                    $datos['PORCENTAJE_DEVOLUCION'] = $descuento;
                    $this->devoluciones_model->insertar_devolucionplantilla($datos);
                    $data = array(
                        'COD_DEVOLUCION' => $cod_devolucion,
                        'COD_RESPUESTA' => DISMINUCION_REALIZADA
                    );
                    $this->devoluciones_model->update_devolucion($data);
                }
                $this->TrazaDevoluciones($cod_devolucion, DISMINUCION_REALIZADA, 'Devolucion Calculada, pendiente para aprobacion');
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado correctamente el documento.</div>');
                redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Disminuir_DevPersonas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $cod_devolucion = $this->input->post('cod_devolucion');
                $personas = unserialize($this->input->post('personas'));
                $planillas = unserialize($this->input->post('planillas'));
                $ibc_errados = unserialize($this->input->post('ibc_errados'));
                $aportes = unserialize($this->input->post('aportes'));
                $motivo = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                /*
                 * GUARDAR DETALLE  ---- POR CADA EMPLEADO
                 */
                switch ($motivo->COD_CONCEPTO) {
                    case 3:
                        $ibc = $this->input->post('ibc');
                        for ($i = 0; $i < sizeof($personas); $i++) {
                            $detalle = $this->devoluciones_model->get_detalleplanilla($planillas[$i]);
                            $ibc_correcto = str_replace(".", "", $ibc[$i]);
                            $datos['COD_DEVOLUCION'] = $cod_devolucion;
                            $datos['ID_EMPLEADO'] = $personas[$i];
                            $datos['COD_PLANILLAUNICA'] = $planillas[$i];
                            $datos['IBC_ERRADO'] = $ibc_errados[$i];
                            $datos['PAGO_ERRADO'] = $aportes[$i];
                            $datos['IBC_REAL'] = $ibc_correcto;
                            $datos['PAGO_CORRESPONDIENTE'] = $ibc_correcto * $detalle->TARIFA;
                            $datos['TOTAL_DEVOLUCION'] = $datos['PAGO_ERRADO'] - $datos['PAGO_CORRESPONDIENTE'];
                            $datos['ID_RESPONSABLE'] = ID_USER;
                            $this->devoluciones_model->insertar_detallesdevolucion($datos);
                        }
                        break;
                    case 5:
                    case 44:
                    case 45:
                        $ibc = $this->input->post('ibc');
                        $porcentaje = $this->input->post('porcentaje') / 100;
                        for ($i = 0; $i < sizeof($personas); $i++) {
                            $ibc_correcto = str_replace(".", "", $ibc[$i]);
                            $datos['COD_DEVOLUCION'] = $cod_devolucion;
                            $datos['ID_EMPLEADO'] = $personas[$i];
                            $datos['COD_PLANILLAUNICA'] = $planillas[$i];
                            $datos['IBC_ERRADO'] = $ibc_errados[$i];
                            $datos['PAGO_ERRADO'] = $aportes[$i];
                            $datos['IBC_REAL'] = $ibc_correcto;
                            $datos['PAGO_CORRESPONDIENTE'] = $ibc_correcto * $porcentaje;
                            $datos['TOTAL_DEVOLUCION'] = $datos['PAGO_ERRADO'] - $datos['PAGO_CORRESPONDIENTE'];
                            $datos['ID_RESPONSABLE'] = ID_USER;
                            $this->devoluciones_model->insertar_detallesdevolucion($datos);
                        }
                        break;
                    case 4:
                        $ibc_errados = unserialize($this->input->post('ibc_errados'));
                        for ($i = 0; $i < sizeof($personas); $i++) {
                            $detalle = $this->devoluciones_model->get_detalleplanilla($planillas[$i]);
                            $datos['COD_DEVOLUCION'] = $cod_devolucion;
                            $datos['ID_EMPLEADO'] = $personas[$i];
                            $datos['COD_PLANILLAUNICA'] = $planillas[$i];
                            $datos['IBC_ERRADO'] = $ibc_errados[$i];
                            $datos['PAGO_ERRADO'] = $aportes[$i];
                            $datos['IBC_REAL'] = $ibc_errados[$i] * 0.7;
                            $datos['PAGO_CORRESPONDIENTE'] = $datos['IBC_REAL'] * $detalle->TARIFA;
                            $datos['TOTAL_DEVOLUCION'] = $datos['PAGO_ERRADO'] - $datos['PAGO_CORRESPONDIENTE'];
                            $datos['ID_RESPONSABLE'] = ID_USER;
                            $this->devoluciones_model->insertar_detallesdevolucion($datos);
                        }
                        break;
                }
                $data = array(
                    'COD_DEVOLUCION' => $cod_devolucion,
                    'COD_RESPUESTA' => DISMINUCION_REALIZADA
                );
                if ($this->devoluciones_model->update_devolucion($data)) {
                    $this->TrazaDevoluciones($cod_devolucion, DISMINUCION_REALIZADA, 'Devolucion Calculada, pendiente para aprobacion');
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado correctamente el documento.</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
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
     * VERIFICACION DE DOCUMENTO
     */

    function Analizar_Devolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $cod_devolucion = $this->input->post('cod_devolucion');
                $opc_verificar = $this->input->post('radio_verificacion');
                if (strlen($opc_verificar) === 0) {
                    $motivo = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                    $this->data['motivo'] = $motivo->COD_CONCEPTO;
                    $this->data['cod_devolucion'] = $cod_devolucion;
                    $this->data['documento'] = $this->devoluciones_model->get_documentosoporte($cod_devolucion);
                    switch ($motivo->COD_CONCEPTO) {
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 44:
                        case 45:
                            $this->template->load($this->template_file, 'devolucion/analizarpila_add', $this->data);
                            break;
                        default:
                            $this->template->load($this->template_file, 'devolucion/analizardevolucion_add', $this->data);
                            break;
                    }
                } else {
                    $motivo = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                    switch ($motivo->COD_CONCEPTO) {
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 44:
                        case 45:
                            $cod_respuesta = $opc_verificar;
                            break;
                        default:
                            if ($opc_verificar == ES_VIABLE) {
                                $cod_respuesta = ES_VIABLE_OTROS_CONCEPTOS;
                            } else {
                                $cod_respuesta = $opc_verificar;
                            }
                            break;
                    }
                    $data = array(
                        'COD_DEVOLUCION' => $cod_devolucion,
                        'COD_RESPUESTA' => $cod_respuesta
                    );
                    if ($this->devoluciones_model->update_devolucion($data)) {
                        $this->TrazaDevoluciones($cod_devolucion, $cod_respuesta, 'Devolucion Calculada, pendiente para aprobacion');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado correctamente la Devolución.</div>');
                        redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/devolucion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * VERIFICACION DE DOCUMENTO
     */

    function Analizar_DevolucionPILA() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $cod_devolucion = $this->input->post('cod_devolucion');
                $camara = $this->input->post('camara');
                $nit = $this->input->post('nit');
                $banco = $this->input->post('banco');
                $comentarios = $this->input->post('comentarios');
                $verificacion_documentos = $nit . $banco . $camara;
                if ($verificacion_documentos == '') {
                    $cod_respuesta = ES_VIABLE;
                } else {
                    $cod_respuesta = NO_ES_VIABLE;
                }
                $data = array(
                    'DOCUMENTACION' => $verificacion_documentos,
                    'COD_DEVOLUCION' => $cod_devolucion,
                    'COD_RESPUESTA' => $cod_respuesta
                );
                if ($this->devoluciones_model->update_devolucion($data)) {
                    $this->TrazaDevoluciones($cod_devolucion, $cod_respuesta, $comentarios);
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha verificado correctamente la Devolución.</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Área.</div>');
                redirect(base_url() . 'index.php/devolucion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTADO DE SOLICITUDES DE DEVOLUCION
     */

    function Listado_SolicitudesDevolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $this->data['cod_devolucion'] = $cod_devolucion;
                $this->data['estados_seleccionados'] = $this->devoluciones_model->get_planillas($cod_devolucion);
                $this->data['concepto'] = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                switch ($this->data['concepto']->COD_CONCEPTO) {
                    case 1:
                    case 2:

                        $this->data['ruta'] = "devolucion/Calcular_Devolucion";
                        break;
                    case 3:
                    case 4:
                    case 5:
                    case 44:
                    case 45:
                        $this->data['ruta'] = "devolucion/Listado_PersonasPlanilla";
                        break;
                    default:
                        break;
                }
                $this->template->load($this->template_file, 'devolucion/planillasempresa_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * Listado Devoluciones Aprobar otros motivos
     */

    function Listado_AprobarOtrosConceptos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $this->data['cod_devolucion'] = $cod_devolucion;
                $this->data['concepto'] = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                $this->data['estados_seleccionados'] = $this->devoluciones_model->get_aprobarotrosconceptos($cod_devolucion);
                $this->template->load($this->template_file, 'devolucion/aprobarotrosdevolucion_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * Listado Devoluciones Aprobar
     */

    function Listado_DetalleAprobar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');

                $cod_devolucion = $this->input->post('cod_devolucion');
                $this->data['cod_devolucion'] = $cod_devolucion;

                $this->data['concepto'] = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                $this->data['estados_seleccionados'] = $this->devoluciones_model->get_devolucionesaprobar($cod_devolucion);

                $this->template->load($this->template_file, 'devolucion/aprobardevolucion_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTADO DE PLANILLAS ESCOJIDAS PARA REALIZAR DEVOLUCION
     */

    function Aprobar_Solicitudes() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $aprobadas = $this->input->post('list_planillas');
                $valor_aprobadas = str_replace(".", "", $this->input->post('devolver'));
                $concepto = $this->devoluciones_model->get_conceptodevolucion($cod_devolucion);
                $indice = 0;
                for ($i = 0; $i < sizeof($valor_aprobadas); $i++) {
                    if (isset($aprobadas[$i])) {
                        $devoluciones_aprobadas[$indice] = $aprobadas[$i];
                        $valor_aprobadas[$indice] = $valor_aprobadas[$i];
                        switch ($concepto->COD_CONCEPTO) {
                            case 1:
                            case 2:
                                $datos['COD_DEVOLUCION_PLANILLA'] = $aprobadas[$i];
                                break;
                            case 3:
                            case 4:
                            case 5:
                            case 44:
                            case 45:
                                $datos['COD_DEVOLUCION_DET'] = $aprobadas[$i];
                                break;
                            default:
                                break;
                        }
                        $datos['APROBADO_POR'] = ID_USER;
                        $datos['VALOR_APROBADO'] = $valor_aprobadas[$i];
                        $this->devoluciones_model->insertar_devolucionplanilla($datos);
                        $indice++;
                    }
                }
                if ($indice != 0) {
                    for ($i = 0; $i < sizeof($devoluciones_aprobadas); $i++) {
                        $planillas['COD_DEVOLUCION_PLANILLA'] = $devoluciones_aprobadas[$i];
                        $tercero['COD_DEVOLUCIONDET'] = $devoluciones_aprobadas[$i];
                        $planillas['APROBADO'] = 'S';
                        $tercero['APROBADO'] = 'S';
                        $this->devoluciones_model->actualizacion_aprobacionplanillas($planillas);
                        $this->devoluciones_model->actualizacion_aprobacionterceros($tercero);
                    }
                    $data = array(
                        'COD_DEVOLUCION' => $cod_devolucion,
                        'COD_RESPUESTA' => DEVOLUCION_APROBADA
                    );
                    if ($this->devoluciones_model->update_devolucion($data)) {
                        $this->TrazaDevoluciones($cod_devolucion, DEVOLUCION_APROBADA, 'Las devoluciones fueron evaluadas por el coordinador y aprobadas');
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha aprobado correctamente las solicitudes de  Devolución.</div>');
                        redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button> <strong>¡Cuidado!</strong>No se eligieron planillas para aprobar</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
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
     * LISTADO DE PLANILLAS ESCOJIDAS PARA REALIZAR DEVOLUCION
     */

    function Aprobar_SolicitudesOtrosConceptos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_devolucion')) {
                $this->template->set('title', 'Devoluciones - Direccion General');
                $cod_devolucion = $this->input->post('cod_devolucion');
                $aprobadas = $this->input->post('list_pagosdevolver');
                $listado_aprobar = $this->devoluciones_model->get_aprobarotrosconceptos($cod_devolucion);
                for ($i = 0; $i < sizeof($listado_aprobar); $i++) {
//                    echo $listado_aprobar[$i]->COD_PAGO_DEVUELTO;
                    $desaprobar['COD_PAGO_DEVUELTO'] = $listado_aprobar[$i]->COD_PAGO_DEVUELTO;
                    $desaprobar['ESTADO'] = '2'; //NO  APROBADO
                    $this->devoluciones_model->actualizacion_devolucionotrosaportes($desaprobar);
                }
                for ($i = 0; $i < sizeof($listado_aprobar); $i++) {
                    for ($j = 0; $j < sizeof($aprobadas); $j++) {
                        if ($listado_aprobar[$i]->COD_PAGO_DEVUELTO == $aprobadas[$j]) {
                            $aprobar['COD_PAGO_DEVUELTO'] = $listado_aprobar[$i]->COD_PAGO_DEVUELTO;
                            $aprobar['ESTADO'] = '1'; // APROBADO
                            $this->devoluciones_model->actualizacion_devolucionotrosaportes($aprobar);
                        }
                    }
                }
                $data = array(
                    'COD_DEVOLUCION' => $cod_devolucion,
                    'COD_RESPUESTA' => DEVOLUCION_APROBADA
                );
                if ($this->devoluciones_model->update_devolucion($data)) {
                    $this->TrazaDevoluciones($cod_devolucion, DEVOLUCION_APROBADA, 'Las devoluciones fueron evaluadas por el coordinador y aprobadas');
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha aprobado correctamente las solicitudes de  Devolución.</div>');
                    redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
                } else {
                    $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
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
     * TRAZA DE PROCESO
     */

    function TrazaDevoluciones($cod_devolucion, $cod_respuesta, $comentarios) {
        $info = $this->devoluciones_model->get_trazagestion($cod_respuesta);
        $gestion_cobro = trazarProcesoJuridico($info['COD_TIPOGESTION'], $info['COD_RESPUESTA'], '', '', '', $cod_devolucion, '', $comentarios, $usuariosAdicionales = '');
        return $gestion_cobro;
    }

}
