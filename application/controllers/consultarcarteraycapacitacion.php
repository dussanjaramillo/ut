<?php

// Responsable: Leonardo Molina
class Consultarcarteraycapacitacion extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('datatables', 'libupload', 'form_validation', 'tcpdf/tcpdf.php'));
        $this->load->helper(array('form', 'url', 'codegen_helper', 'date', 'traza_fecha', 'template'));
        $this->load->model('consultarcarteraycapacitacion_model');
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/bootstrap.submodal.css' => 'screen', 'css/validationEngine.jquery.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/jquery.dataTables.defaults.js', 'js/tinymce/tinymce.jquery.min.js', 'js/jquery.confirm.js');
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
    }

    function tales() {
        $this->data['registros'] = $this->consultarcarteraycapacitacion_model->getDatax(1, '');
        var_dump($this->data['registros']);
    }

    public function index() {
        $this->manage();
    }

    public function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in()
                || $this->ion_auth->in_menu('consultarcarteraycapacitacion/index')
                || $this->ion_auth->in_group('Abogados relaciones corporativas ')) {
            //template data
            $this->template->set('title', 'Consultar cartera y pago');
            $this->data['message'] = $this->session->flashdata('message');
            $this->template->load($this->template_file, 'consultarcarteraycapacitacion/consultarcarteraycapacitacion_list',
                $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button"
                    class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getData() {
        $lenght = $this->input->post('iDisplayLength');
        $data['registros'] = $this->consultarcarteraycapacitacion_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght);
        define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
        $TOTAL = $this->consultarcarteraycapacitacion_model->totalData($this->input->post('sSearch'));

        echo json_encode(array('aaData' => $data['registros'],
            'iTotalRecords' => $TOTAL,
            'iTotalDisplayRecords' => $TOTAL,
            'sEcho' => $this->input->post('sEcho')));
    }

    function consultarCartera() {
        //redirect(URL('admin','example'),client_side=True)
        // redireccionar y cargar la pagina completa

        $this->data['ejec'] = $this->uri->segment(3);
        $resol = $this->uri->segment(4);

        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        try {
            $this->data['regEjec'] = $this->consultarcarteraycapacitacion_model->getEjecutoria($this->uri->segment(3));
            $this->data['vigencias'] = $this->consultarcarteraycapacitacion_model->getVigenciasContrato($this->data['regEjec']->NIT_EMPRESA, $this->data['regEjec']->TIPO_CARTERA, $resol);
        } catch (Exception $e) {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
        $this->load->view('consultarcarteraycapacitacion/consultarcartera', $this->data);
    }

    function voluntadPago() {
        $this->data['numejec'] = $this->uri->segment(3);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('consultarcarteraycapacitacion/voluntadpago', $this->data);
    }

    function documentoCobro() {
        $this->data['numejec'] = $this->uri->segment(3);
        $resol = $this->uri->segment(4);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->data['registros'] = $this->consultarcarteraycapacitacion_model->getGestionEjecutoria($this->uri->segment(3));
        $this->load->view('consultarcarteraycapacitacion/documentocobro', $this->data);
    }

    function cargaConsulta() {
        if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in()
                    || $this->ion_auth->in_menu('consultarcarteraycapacitacion/index')
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')) {
                $ejec = $this->consultarcarteraycapacitacion_model->getEjecutoria($this->input->post('nejec'));


                if ($this->form_validation->run() == false && validation_errors()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
                    redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                } else {
                    // 77 -> consultar cartera y capacitacion
                    // 148-> Cartera consitutida
                    $codgest = trazar(77, 148, $ejec->COD_FISCALIZACION, $ejec->NIT_EMPRESA, "S");
                    $codGestion = $codgest['COD_GESTION_COBRO'];
                    $query = $this->consultarcarteraycapacitacion_model->updateStateEjecutoria($this->input->post('nejec'), $codGestion);

                    if ($query == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Cartera constituida se ha cargado con éxito.</div>');
                        redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                        redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
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

    function addVoluntadPago() {
        if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in()
                    || $this->ion_auth->in_menu('consultarcarteraycapacitacion/index')
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')) {

                $ejec = $this->consultarcarteraycapacitacion_model->getEjecutoria($this->input->post('neject'));
                $res = $this->consultarcarteraycapacitacion_model->getResolucion($ejec->NUM_DOCUMENTO);

                if ($this->input->post('resp1') == 1) {
                    $this->form_validation->set_rules('archivo0', 'Documento', 'required');
                }
                $this->form_validation->set_rules('resp', 'Voluntad de pago', 'required');

                if ($this->form_validation->run() == false && validation_errors()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
                    redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                } else {
                    if ($this->input->post('resp') == 'total') {
                        // 80 -> Registrar voluntad de pago
                        // 152-> Cartera consitutida
                        $codgest = trazar(80, 152, $ejec->COD_FISCALIZACION, $ejec->NIT_EMPRESA, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                        $query = $this->consultarcarteraycapacitacion_model->updateliquidacion($ejec->COD_FISCALIZACION);
                        $query = $this->consultarcarteraycapacitacion_model->updateEstadoResolucion($res->NUMERO_RESOLUCION, 80);
                        $query3 = $this->consultarcarteraycapacitacion_model->updateGestionResolucion($res->NUMERO_RESOLUCION, $codGestion);
                        $query2 = $this->consultarcarteraycapacitacion_model->updateStateEjecutoria($this->input->post('neject'), $codGestion);

                        if ($query2 == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cambio de estado PAGO TOTAL se ha cargado con éxito.</div>');
                            redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                            //aplicacionmanualdepago
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                            redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                        }
                    }
                    if ($this->input->post('resp') == 'acuerdo') {
                        // 80 = Registrar voluntad de pago
                        // 153= Promesa de acuerdo de pago
                        $codgest = trazar(80, 153, $ejec->COD_FISCALIZACION, $ejec->NIT_EMPRESA, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                        $query = $this->consultarcarteraycapacitacion_model->updateliquidacion($ejec->COD_FISCALIZACION);
                        $query = $this->consultarcarteraycapacitacion_model->updateEstadoResolucion($res->NUMERO_RESOLUCION, 80);
                        $query3 = $this->consultarcarteraycapacitacion_model->updateGestionResolucion($res->NUMERO_RESOLUCION, $codGestion);
                        $query2 = $this->consultarcarteraycapacitacion_model->updateStateEjecutoria($this->input->post('neject'), $codGestion);

                        if ($query == TRUE && $query2 == TRUE && $query3 == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cambio de estado ACUERDO DE PAGO se ha cargado con éxito.</div>');
                            redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                            redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                        }
                    }
                    if ($this->input->post('resp') == 'ninguna') {
                        // 80 -> Registrar voluntad de pago
                        // 154-> Sin voluntad de pago
                        $codgest = trazar(1115, 1115, $ejec->COD_FISCALIZACION, $ejec->NIT_EMPRESA, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];

                        $query = $this->consultarcarteraycapacitacion_model->updateEstadoResolucion($res->NUMERO_RESOLUCION, 433);
                        $query3 = $this->consultarcarteraycapacitacion_model->updateGestionResolucion($res->NUMERO_RESOLUCION, $codGestion);
                        $query = $this->consultarcarteraycapacitacion_model->updateStateEjecutoria($this->input->post('neject'), $codGestion);

                        if ($query == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El cambio de estado Cobro Coactivo se ha cargado con éxito.</div>');
                            redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                            redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                        }
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

    function addDocPersuasivo() {
        if ($this->ion_auth->logged_in()) {
             if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in()
                    || $this->ion_auth->in_menu('consultarcarteraycapacitacion/index')
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')) {
//                print_r($this->input->post());die;
                $ejec = $this->input->post('nejec');
                $cfisc = $this->input->post('cfisc');
                $num_resol = $this->input->post('num_resol');
                $nit = $this->input->post('nit');

//                $this->form_validation->set_rules('llamadas', 'Llamadas', 'required');
                for ($i = 0; $i < count($_FILES); $i++) {
                    if (empty($_FILES['archivo' . $i]['name'])) {
                        $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                    }
                }

                if ($this->form_validation->run() == false && validation_errors()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
                    redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                } else {
                    $route_template = 'uploads/cartera/';
                    $nombre_subcarpeta = 'cartera/cobropersuasivo/' . $cfisc . '/';

                    if (!is_dir($route_template)) {
                        @mkdir($route_template, 0777);
                    } else {

                    }

                    $route_template = 'uploads/cartera/cobropersuasivo/';
                    if (!is_dir($route_template)) {
                        @mkdir($route_template, 0777);
                    } else {

                    }

                    $route_template = 'uploads/cartera/cobropersuasivo/' . $cfisc . '/';
                    if (!is_dir($route_template)) {
                        @mkdir($route_template, 0777);
                    } else {

                    }

                    for ($i = 0; $i < count($_FILES); $i++) {
                        //no le hacemos caso a los vacios
                        if (!empty($_FILES['archivo' . $i]['name'])) {
                            $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|doc|jpg|jpeg', 9999, 9999, 0);
                        }
                    }

                    // 103 -> Gestionar Notificación del Acercamiento Persuasivo
                    // 190-> Documento Acercamiento Persuasivo Enviado

                    $cod_siguiente = 423;
                    $codgest = trazar($cod_siguiente, 1109, $cfisc, $nit, "S");
                    $codGestion = $codgest['COD_GESTION_COBRO'];

                    $data = array(
                        'DOCUMENTO_PERSUASIVO' => $respuesta[0]['data']['file_name'],
                        'HISTORICO_LLAMADAS' => $this->input->post('llamadas')
                    );
                    $query = $this->consultarcarteraycapacitacion_model->updateEjecutoriaPersuasivo($ejec, $codGestion, $data);
                    $query3 = $this->consultarcarteraycapacitacion_model->updateGestionResolucion2($num_resol, $cod_siguiente);

                    if ($query == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Documento Acercamiento Persuasivo Enviado.</div>');
                        redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                        redirect(base_url() . 'index.php/consultarcarteraycapacitacion');
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

    function pdf() {
        $html = $this->input->post('informacion');
        $fisc = $this->input->post('cfisc');
        $nit = $this->input->post('nit');
        $nombre_pdf = $this->input->post('nombre');
        $cresp = $this->input->post('cresp');
        $nejec = $this->input->post('nejec');

        if ($cresp = '186') {
            // 102 -> Generar Requerimiento de Acercamiento Persuasivo
            // 186 -> Requerimiento Acercamiento Persuasivo Generado
            $codgest = trazar(102, 186, $fisc, $nit, "S");
            $codGestion = $codgest['COD_GESTION_COBRO'];
            $this->consultarcarteraycapacitacion_model->updateStateEjecutoria($nejec, $codGestion);
        }

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('aqui_tu_nombre');
        //$pdf->SetTitle($sTitulo);
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setLanguageArray($l);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $js = 'print();';
        $pdf->IncludeJS($js);
        $pdf->Output($nombre_pdf . '.pdf', 'I');

        exit();
    }

    function pregunta_revocatoria() {
        $this->data['post'] = $this->input->post();
        $this->load->view('consultarcarteraycapacitacion/pregunta_revocatoria', $this->data);
    }

    function guardar_Pregunta_revocatoria() {
        $this->data['post'] = $this->input->post();
        $this->consultarcarteraycapacitacion_model->guardar_Pregunta_revocatoria($this->data['post']);
    }

    function llamadas() {
        $this->data['post'] = $this->input->post();
        $this->data['historico_llamadas'] = $this->consultarcarteraycapacitacion_model->historico_llamadas($this->data['post']);
        $this->load->view('consultarcarteraycapacitacion/llamadas', $this->data);
    }

    function guardar_llamadas() {
        $this->data['post'] = $this->input->post();
        $this->consultarcarteraycapacitacion_model->guardar_llamadas($this->data['post']);
    }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */
