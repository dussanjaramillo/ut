<?php

// Responsable: Leonardo Molina
class Ejecutoriaactoadmin extends MY_Controller {

    // DOCUMENTO RECURSO->los recurso que presento el cliente
    // RESOLUCION RECURSO -> la desicion que le da el sena si fue o no aprobado
    // CITACION -> CUANDO SE HACE UNA CITACION SE ACTUALIZA EN RESOLUCION CAMPO numerocitacion
    function __construct() {
        parent::__construct();
        $this->load->library(array('datatables', 'libupload', 'form_validation', 'tcpdf/tcpdf.php'));
        $this->load->helper(array('form', 'url', 'codegen_helper', 'date', 'traza_fecha', 'template'));
        $this->load->model('ejecutoriaactoadmin_model');
        $this->load->model('numeros_letras_model');
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen','css/validationEngine.jquery.css' => 'screen',);
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js', 'js/validCampoFranz.js', 
            'js/jquery.dataTables.defaults.js', 'js/tinymce/tinymce.jquery.min.js');

        define("RUTA_INI", "./uploads/fiscalizaciones/resolucion");
        define("RUTA_DES", "uploads/fiscalizaciones/resolucion/COD_");
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
    }

    function index() {// trae la informacion del abogado
        $this->manage(1);
    }

    function coordinador() {
        $this->manage(2);
    }

    function manage($accion) {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() 
                    || $this->ion_auth->in_menu('ejecutoriaactoadmin/index') 
                    || $this->ion_auth->in_menu('ejecutoriaactoadmin/coordinador') 
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')
                    || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                //template data
                $this->data['accion'] = $accion;
                $this->template->set('title', 'Ejecutoria acto administrativo');
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'ejecutoriaactoadmin/ejecutoriaactoadmin_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function tales() {
        $cod_fisc = $this->uri->segment(3);
        echo $cod_fisc;
        $data['registros'] = $this->ejecutoriaactoadmin_model->updateLiquidacionEjecutoria($cod_fisc);
        var_dump($data['registros']);
    }

    function getData() {
        $accion = $this->input->get('accion');
        if ($accion == 1) {
            $this->informacion_abogado();
        } else if ($accion == 2) {
            $this->informacion_coordinador();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function informacion_abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() 
                     || $this->ion_auth->in_menu('ejecutoriaactoadmin/index') 
                    || $this->ion_auth->in_menu('ejecutoriaactoadmin/coordinador') 
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')
                    || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $lenght = $this->input->post('iDisplayLength');
                $accion = $this->input->get('accion');
                $this->load->model('ejecutoriaactoadmin_model');
                $this->load->library('datatables');
                $resticcion = "RS.ABOGADO";
                $state = array('51', '74', '75', '399', '410', '411', '412', '413', '414', '415', '416', '417', '418', '424', '425', '427');
                $data['registros'] = $this->ejecutoriaactoadmin_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght, $resticcion, $state);
                define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
                $TOTAL = $this->ejecutoriaactoadmin_model->totalData($this->input->post('sSearch'), $resticcion, $state);
                echo json_encode(array('aaData' => $data['registros'],
                    'iTotalRecords' => $TOTAL,
                    'iTotalDisplayRecords' => $TOTAL,
                    'sEcho' => $this->input->post('sEcho')));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function informacion_coordinador() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() 
                     || $this->ion_auth->in_menu('ejecutoriaactoadmin/index') 
                    || $this->ion_auth->in_menu('ejecutoriaactoadmin/coordinador') 
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')
                    || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $lenght = $this->input->post('iDisplayLength');
                $accion = $this->input->get('accion');
                $this->load->model('ejecutoriaactoadmin_model');
                $this->load->library('datatables');
                $resticcion = "RS.COORDINADOR";
                $state = array('398');
                $data['registros'] = $this->ejecutoriaactoadmin_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght, $resticcion, $state);
                define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
                $TOTAL = $this->ejecutoriaactoadmin_model->totalData($this->input->post('sSearch'), $resticcion, $state);
                echo json_encode(array('aaData' => $data['registros'],
                    'iTotalRecords' => $TOTAL,
                    'iTotalDisplayRecords' => $TOTAL,
                    'sEcho' => $this->input->post('sEcho')));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function devolucion_coactivo() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() 
                    || $this->ion_auth->in_menu('ejecutoriaactoadmin/index') 
                    || $this->ion_auth->in_menu('ejecutoriaactoadmin/coordinador') 
                    || $this->ion_auth->in_group('Abogados relaciones corporativas ')
                    || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
        $this->data['datos'] = $this->ejecutoriaactoadmin_model->devolucion_coactivo();
        $this->template->load($this->template_file, 'ejecutoriaactoadmin/devolucion_coactivo', $this->data);
        } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function planilla() {
        $this->data['codres'] = $this->uri->segment(3);
//        echo $this->uri->segment(3)."****";
        $this->data['gestiones'] = $this->ejecutoriaactoadmin_model->getResolucionGestion($this->uri->segment(3));
        $this->data['registros'] = $this->ejecutoriaactoadmin_model->getResolucion($this->uri->segment(3));
//                echo "<pre>";
//        echo $this->data['registros'];
//        echo "</pre>";
        $this->data['resmulta'] = $this->ejecutoriaactoadmin_model->getResolucionMulta($this->uri->segment(3));

        //var_dump($this->data['gestiones'],$this->data['registros'],$this->data['resmulta']);

        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('ejecutoriaactoadmin/planilla', $this->data);
    }
    function valorEnLetras($valor, $tipo) {
        $info = $this->numeros_letras_model->ValorEnLetras($valor, $tipo);
        return $info;
    }

    function revocatoria() {
        $this->data['codres'] = $this->uri->segment(3);

        $this->data['registros'] = $this->ejecutoriaactoadmin_model->getResolucion($this->uri->segment(3));
        if (!$this->data['registros'])
            $this->data['registros'] = $this->ejecutoriaactoadmin_model->getResolucionMulta($this->uri->segment(3));
        $this->data['resGestion'] = $this->ejecutoriaactoadmin_model->getResolucionGestion($this->uri->segment(3));
        $this->data['consulta2'] = $this->ejecutoriaactoadmin_model->num_fisca($this->uri->segment(3));
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('ejecutoriaactoadmin/revocatoria', $this->data);
    }

    function gestionRevocatoria() {
        $this->data['codres'] = $this->uri->segment(3);
        $this->data['registros'] = $this->ejecutoriaactoadmin_model->getRevocatoria($this->uri->segment(3));
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('ejecutoriaactoadmin/gestionRevocatoria', $this->data);
    }

    function ejecutoriar() {
        $this->load->library('form_validation');
        $timestamp = now();
        $timezone = 'UM5';
        $daylight_saving = FALSE;
        $datestring = "%d/%m/%Y";
        $now = gmt_to_local($timestamp, $timezone, $daylight_saving);
        $date = mdate($datestring, $now);
        $this->data['codres'] = $this->input->post('nresol');
        $this->data['registros'] = $this->ejecutoriaactoadmin_model->getResolucion($this->input->post('nresol'));
        $cfisca = $this->input->post('cfisca');
        for ($i = 0; $i < count($_FILES); $i++) {
            if (empty($_FILES['archivo' . $i]['name'])) {
                $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
            }
        }
        if ($this->form_validation->run() == false && validation_errors()) {
            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
            redirect(base_url() . 'index.php/ejecutoriaactoadmin');
        } else {

            $route_template = 'uploads/fiscalizaciones/' . $this->input->post('codfisc') . '/ejecutoriaactoadmin/';
            if (!file_exists($route_template)) {
                if (!mkdir($route_template, 0777, true)) {
                    
                }
            }
            $nombre_subcarpeta = 'fiscalizaciones/' . $this->input->post('codfisc') . '/ejecutoriaactoadmin';
            $nomArch = $this->input->post('codfisc') . "_" . $date;

            for ($i = 0; $i < count($_FILES); $i++) {
                if (!empty($_FILES['archivo' . $i]['name'])) {
                    $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|doc', 9999, 9999, 0, $nomArch);
                }
            }
            if ($this->input->post('cod_estado') == 416) {
                $cod_siguiente = 421;
                $cod_respuesta = 1106;
            } else {
                $cod_siguiente = 74;
                $cod_respuesta = $this->input->post('rgestion');
            }
            $codgest = trazar($cod_siguiente, $cod_respuesta, $this->input->post('codfisc'), $this->input->post('gnit'), "S");
            $data = array(
                'COD_FISCALIZACION' => $this->input->post('codfisc'), //$this->data['registros'],
                'COD_GESTION_COBRO' => $codgest['COD_GESTION_COBRO'], // ejecutoria generada -> validar cual es el estado Correcto!!!!!
                'DOCUMENTO_EJECUTORIA' => $respuesta[0]['data']['file_name'],
                'NUM_DOCUMENTO' => $this->input->post('nresol'),
                'TIPO_DOCUMENTO' => $this->input->post('rgestion'),
                'TIPO_CARTERA' => $this->input->post('cfisca')
            );
            $query = $this->ejecutoriaactoadmin_model->addEjecutoria($data, $this->input->post('cod_estado'), $this->input->post('codfisc'));
            $query2 = $this->ejecutoriaactoadmin_model->updateEstadoResolucion($this->input->post('nresol'), $cod_siguiente);
            if ($cfisca == 2 || $cfisca == 3) {
                $cod_prc = 6; // Ejecución Acto Administrativo Aportes, FIC y CA
            } else if ($cfisca == 5) {
                $cod_prc = 7;
            }// Ejecución Acto Administrativo Multas Ministerio

            $query3 = $this->ejecutoriaactoadmin_model->updateLiquidacionEjecutoria($this->input->post('codfisc'), $cod_prc);


            if ($query == TRUE && $query2 == TRUE) {// falta probar que la fiscalizacion este en la liquidacion para hacer las pruebas!
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La resolución se ha ejecutoriado con éxito.</div>');
                redirect(base_url() . 'index.php/ejecutoriaactoadmin');
            } else {
                $error = '<div class="form_error"><p>An Error Occured.</p></div>';
                $this->session->set_flashdata('custom_error', $error);
                redirect(base_url() . 'index.php/ejecutoriaactoadmin');
            }
        }
    }

//    function revocatoriaAdd($nresol,$fisc,$nit,$html){
    function revocatoriaAdd() {
        $html = $this->input->post('informacion');
        $nresol = $this->input->post('nresol');
        $fisc = $this->input->post('codfisc');
        $nit = $this->input->post('gnit');

        $nroRadicado = $this->input->post('nroRadicado');
        $codresol = $this->ejecutoriaactoadmin_model->getResolucionUnica($nresol);


        for ($i = 0; $i < count($_FILES); $i++) {
            if (empty($_FILES['archivo' . $i]['name'])) {
                $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
            }
        }
        if ($this->form_validation->run() == false && validation_errors()) {
            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
            redirect(base_url() . 'index.php/ejecutoriaactoadmin');
        } else {
            $route_template = 'uploads/fiscalizaciones/' . $fisc . '/ejecutoriaactoadmin/';
            if (!file_exists($route_template)) {
                if (!mkdir($route_template, 0777, true)) {
                    
                }
            }
            $nombreDoc = create_template($route_template, $html);
            $nombre_subcarpeta = 'fiscalizaciones/' . $fisc . '/ejecutoriaactoadmin';

            for ($i = 0; $i < count($_FILES); $i++) {
                if (!empty($_FILES['archivo' . $i]['name'])) {
                    $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|doc|jpg|jpeg', 9999, 9999, 0);
                }
            }
            // 75 -> Codigo Gestion -> Generar documento de revocatoria
            // 141 RespuestaGestion -> Revocatoria Generada
            // 142 RespuestaGestion -> Revocatoria Rechazada
            // 143 RespuestaGestion -> Revocatoria Aprobada


            $respuestaGestion = 1072;
            $Gestion = 398;
            $codgest = trazar($Gestion, $respuestaGestion, $fisc, $nit, "S");
            $data = array(
                'COD_RESOLUCION' => $codresol,
                'NRO_RADICADO' => $nroRadicado,
                'DOC_REVOCATORIA' => $respuesta[0]['data']['file_name'],
                'DOC_RESPUESTA' => $nombreDoc,
                'COD_GESTION_COBRO' => $codgest['COD_GESTION_COBRO']
            );

            $query = $this->ejecutoriaactoadmin_model->addRevocatoria($data);
            $this->ejecutoriaactoadmin_model->updateGestionResolucion($nresol, $codgest['COD_GESTION_COBRO']);
            $query2 = $this->ejecutoriaactoadmin_model->updateEstadoResolucion($this->input->post('nresol'), $Gestion);
            if ($query == TRUE) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha guardado la REVOCATORIA con éxito.</div>');
                redirect(base_url() . 'index.php/ejecutoriaactoadmin');
            } else {
                $error = '<div class="form_error"><p>An Error Occured.</p></div>';
                $this->session->set_flashdata('custom_error', $error);
                redirect(base_url() . 'index.php/ejecutoriaactoadmin');
            }
        }
    }

    function pre_revocatoria() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $info = $this->ejecutoriaactoadmin_model->pre_revocatoria($post);
//        echo "<pre>";
//        print_r($info[0]['DOC_RESPUESTA']);
        $nombre_subcarpeta = './uploads/fiscalizaciones/' . $post['cod_fis'] . '/ejecutoriaactoadmin/' . $info[0]['DOC_RESPUESTA'];
        $text_file = fopen($nombre_subcarpeta, "r"); //or die("No se pudo abrir el archivo");
        $contet_file = fread($text_file, filesize($nombre_subcarpeta));
        $this->data['texto'] = $contet_file;
        $this->data['comentario'] = $this->ejecutoriaactoadmin_model->comentario($post['id']);
        $this->load->view('ejecutoriaactoadmin/pre_revocatoria', $this->data);
    }

    function correcion_aprobar() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $info = $this->ejecutoriaactoadmin_model->pre_revocatoria($post);
        $this->data['comentario'] = $this->ejecutoriaactoadmin_model->comentario($post['id']);
//        echo "<pre>";
//        print_r($info[0]['DOC_RESPUESTA']);
        $nombre_subcarpeta = './uploads/fiscalizaciones/' . $post['cod_fis'] . '/ejecutoriaactoadmin/' . $info[0]['DOC_RESPUESTA'];
        $text_file = fopen($nombre_subcarpeta, "r"); //or die("No se pudo abrir el archivo");
        $contet_file = fread($text_file, filesize($nombre_subcarpeta));
        $this->data['texto'] = $contet_file;
        $this->data['documento'] = $info[0]['DOC_RESPUESTA'];
        $this->load->view('ejecutoriaactoadmin/correcion_aprobar', $this->data);
    }

    function revocatoriaFirmado() {
        $nres = $this->input->post('nresol');
        $fisc = $this->input->post('cfisc');
        $nit = $this->input->post('gnit');
        $this->form_validation->set_rules('resp', 'Seleccione la opción', 'required');
        for ($i = 0; $i < count($_FILES); $i++) {
            if (empty($_FILES['archivo' . $i]['name'])) {
                $this->form_validation->set_rules('archivo' . $i, 'Documento firmado de revocatoria', 'required');
            }
        }
        if ($this->form_validation->run() == false && validation_errors()) {
            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
            redirect(base_url() . 'index.php/ejecutoriaactoadmin');
        } else {
            $resp = $this->input->post('resp');
            if ($resp == 'S') {
                $respuestaGestion = 143;
            } else
                $respuestaGestion = 142;

            $nombre_subcarpeta = 'fiscalizaciones/' . $fisc . '/ejecutoriaactoadmin';
            for ($i = 0; $i < count($_FILES); $i++) {
                if (!empty($_FILES['archivo' . $i]['name'])) {
                    $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|doc|jpg|jpeg', 9999, 9999, 0);
                }
            }
            $codgest = trazar(75, $respuestaGestion, $fisc, $nit, "S");

            $data = array(
                'RESPUESTA_REVOCATORIA' => $resp,
                'COD_GESTION_COBRO' => $codgest['COD_GESTION_COBRO'],
                'DOC_RESPUESTA_FIRMADO' => $respuesta[0]['data']['file_name']
            );

            if ($respuestaGestion == 142)
                $this->ejecutoriaactoadmin_model->updateEstadoResolucion($this->input->post('nresol'), 51);

            $query = $this->ejecutoriaactoadmin_model->updateRevocatoria($this->input->post('nrevoc'), $data);
            $this->ejecutoriaactoadmin_model->updateliquidacion2($fisc);
            $this->ejecutoriaactoadmin_model->updateGestionResolucion($nres, $codgest['COD_GESTION_COBRO']);

            if ($query == TRUE) {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha actualizado la REVOCATORIA con éxito.</div>');
                redirect(base_url() . 'index.php/ejecutoriaactoadmin');
            } else {
                $error = '<div class="form_error"><p>An Error Occured.</p></div>';
                $this->session->set_flashdata('custom_error', $error);
                redirect(base_url() . 'index.php/ejecutoriaactoadmin');
            }
        }
    }

    function pdf() {
        $html = $this->input->post('informacion');
        $nombre_pdf = $this->input->post('nombre');
        $fisc = $this->input->post('cfisc');
        $nit = $this->input->post('gnit');
        $rges2 = $this->input->post('rges2');
        $nresol = $this->input->post('nresol');
        $codgest = trazar(74, $rges2, $fisc, $nit, "S");
        $gestion = $codgest['COD_GESTION_COBRO'];
//        print_r($this->input->post());
        $this->ejecutoriaactoadmin_model->updateGestionResolucion($nresol, $gestion);
//        die();
        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('aqui_tu_nombre');
        // $pdf->SetTitle($sTitulo);
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');
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
        // traza documento generado



        exit();
    }

    function pdfRevocatoria() {
        $html = $this->input->post('informacion');
        $nresol = $this->input->post('nresol');
        $fisc = $this->input->post('cfisca');
        $nit = $this->input->post('gnit');
        $nombre_pdf = $this->input->post('nombre');
        $nroRadicado = $this->input->post('nroRadicado');
//            var_dump($nresol,$fisc,$nit);die;
        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        //$pdf->SetAuthor('aqui_tu_nombre');
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
        //75 -> Documento Revocatoria
        //141 -> Documento revocatoria generado
        $codgest = trazar(398, 1072, $fisc, $nit, "S");
        $gestion = $codgest['COD_GESTION_COBRO'];
        $this->ejecutoriaactoadmin_model->updateGestionResolucion($nresol, $gestion);
        //$this->revocatoriaAdd($html,$fisc,$nit,$nresol,$nroRadicado);

        exit();
    }

    function devolucion() {
        $post = $this->input->post();
        $this->ejecutoriaactoadmin_model->devolucion($post);
        $codgest = trazar(399, 1073, $post['cod_fis'], $post['nit'], "S");
    }

    function aprobar() {
        $post = $this->input->post();
        $this->ejecutoriaactoadmin_model->aprobar($post);
        $codgest = trazar(75, 141, $post['cod_fis'], $post['nit'], "S");
    }

    function citacion() {
        $post = $this->input->post();
        $this->ejecutoriaactoadmin_model->citacion($post);
        $codgest = trazar(410, 1082, $post['cod_fis'], $post['nit'], "S");
    }

    function confirmar() {
        $this->data['post'] = $this->input->post();
        $this->load->view('ejecutoriaactoadmin/confirmar', $this->data);
    }

    function gestionar_documento_juridico() {
        $this->data['post'] = $this->input->post();
        $txt = $this->ejecutoriaactoadmin_model->plantilla(110);
        if (!empty($txt)) {
            $info = $this->ejecutoriaactoadmin_model->informacion_plantilla_coactivo($this->data['post']['id']);
            $reemplazo = array();
        $reemplazo['NOMBRE_EMPRESA'] = $info['NOMBRE_EMPRESA'];
        $reemplazo['NIT'] = $info['CODEMPRESA'];
        $reemplazo['NRO_RESOLUCION'] = $info['NUMERO_RESOLUCION'];
        $reemplazo['FECHA_RESOLUCION'] = $info['FECHA_CREACION'];
        $reemplazo['FECHA_CITACION'] = $info['FECHA_ENVIO_CITACION'];
        $reemplazo['FECHA_EJECUTORIA'] = $info['FECHA_EJECUTORIA'];
        $reemplazo['REGIONAL'] = $info['NOMBRE_REGIONAL'];
        $reemplazo['NOMBRE_REGIONAL'] = $info['NOMBRE_REGIONAL'];
        $reemplazo['DIRECCION_REGIONAL'] = $info['DIRECCION_REGIONAL'];
        $reemplazo['VALOR'] = "$".number_format($info['SALDO_DEUDA'],0,',','.');
        $reemplazo['COD_REGIONAL'] = $info['COD_REGIONAL'];
        $reemplazo['TEL_REGIONAL'] = $info['TELEFONO_REGIONAL'];
        $reemplazo['CIUDAD'] = $info['CIUDAD'];
        $reemplazo['REPRESENTANTE_LEGAL'] = $info['REPRESENTANTE_LEGAL'];
        $reemplazo['DIRECCION'] = $info['DIRECCION'];
        $reemplazo['FECHA'] = date('d/m/Y');
        $reemplazo['COORDINADOR_REGIONAL'] = $info['COORDINADOR_REGIONAL'];
        $reemplazo['USUARIO'] = NOMBRE_COMPLETO;
        $informacion=$this->ion_auth->user()->row();
        $reemplazo['CORREO'] = $informacion->EMAIL;
            $this->data['txt'] = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
        }
        $this->load->view('ejecutoriaactoadmin/gestionar_documento_juridico', $this->data);
    }

    function gestionar_documento_juridico2() {
        $this->data['post'] = $this->input->post();
        $txt = $this->ejecutoriaactoadmin_model->plantilla(121);
        if (!empty($txt)) {
            $info = $this->ejecutoriaactoadmin_model->informacion_plantilla_coactivo($this->data['post']['id']);
            $reemplazo = array();
        $reemplazo['NOMBRE_EMPRESA'] = $info['NOMBRE_EMPRESA'];
        $reemplazo['NIT'] = $info['CODEMPRESA'];
        $reemplazo['NRO_RESOLUCION'] = $info['NUMERO_RESOLUCION'];
        $reemplazo['FECHA_RESOLUCION'] = $info['FECHA_CREACION'];
        $reemplazo['FECHA_CITACION'] = $info['FECHA_ENVIO_CITACION'];
        $reemplazo['FECHA_EJECUTORIA'] = $info['FECHA_EJECUTORIA'];
        $reemplazo['REGIONAL'] = $info['NOMBRE_REGIONAL'];
        $reemplazo['ciudad'] = $info['CIUDAD'];
        $reemplazo['FECHA'] = date('d/m/Y');
        $mes=array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
        $reemplazo['mes'] =date('m');
        $reemplazo['ano'] = date('Y');
        $reemplazo['dias'] = date('d');
        $reemplazo['COORDINADOR_REGIONAL'] = $info['COORDINADOR_REGIONAL'];
        $reemplazo['USUARIO'] = NOMBRE_COMPLETO;
        
        $tabla="<table><tr>"
                . "<td>N Resolución</td>"
                . "<td>Tipo de Obligación</td>"
                . "<td>Valor</td>"
                . "<td>Razón Social</td>"
                . "<td>Nit de la Empresa</td>"
                . "</tr>"
                . "<tr>"
                . "<td>".$info['NUMERO_RESOLUCION']."</td>"
                . "<td>".$info['NOMBRE_CONCEPTO']."</td>"
                . "<td>".$info['SALDO_DEUDA']."</td>"
                . "<td>".$info['NOMBRE_EMPRESA']."</td>"
                . "<td>".$info['CODEMPRESA']."</td>"
                . "<tr></table>";
                $reemplazo['tabla'] = $tabla;
            $this->data['txt'] = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
        }
        $this->load->view('ejecutoriaactoadmin/gestionar_documento_juridico2', $this->data);
    }

    function subir_documento_juridico() {
        $this->data['post'] = $this->input->post();
        $this->load->view('ejecutoriaactoadmin/subir_documento_juridico', $this->data);
    }

    function subir_documento_juridico2() {
        $this->data['post'] = $this->input->post();
        $this->load->view('ejecutoriaactoadmin/subir_documento_juridico2', $this->data);
    }

// se guarda el documento de cobro persuasivo
    function guardar_paso_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/ejecutoriaactoadmin/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $nombreDoc = create_template($route_template, $post['informacion']);
        $data = array(
            'DOC_GENERADO' => $nombreDoc
        );
        $this->ejecutoriaactoadmin_model->updateRevocatoria($post['cod_revocatoria'], $data);
        $this->ejecutoriaactoadmin_model->updateEstadoResolucion2($post['id'], 424);
//        $this->ejecutoriaactoadmin_model->guardar_paso_juridico($post, $nombreDoc);
        $codgest = trazar(424, 1110, $post['cod_fis'], $post['nit'], "S");
//        $codgest = trazar(418, 1093, $post['cod_fis'], $post['nit'], "S");
    }

    // se guarda el documento de  para el paso a juridico 
    function guardar_paso_juridico2() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/ejecutoriaactoadmin/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $nombreDoc = create_template($route_template, $post['informacion']);
        $rutas = "";
        if(isset($post['rutas'])){
        foreach ($post['rutas'] as $value) {
            $rutas.=$value . "///";
        }
        $nombre = "|||";
        foreach ($post['nombre'] as $value) {
            $nombre.=$value . "///";
        }
        }else{
            $nombre="";
            $rutas="";
        }
            
        $data = array(
            'DOCUMENTO_COBRO_COACTIVO' => $nombreDoc,
            'DETALLE_COBRO_COACTIVO' => base64_encode(utf8_decode($rutas . $nombre)),
            'COD_ESTADO' => $post['cod_estado']
        );
        $this->ejecutoriaactoadmin_model->updateEstadoResolucion3($post['id'], $data);



//        $this->ejecutoriaactoadmin_model->guardar_paso_juridico($post, $nombreDoc);
        $codgest = trazar(418, 1093, $post['cod_fis'], $post['nit'], "S");
//        $codgest = trazar($post['cod_estado'], 1093, $post['cod_fis'], $post['nit'], "S");
        echo "<script>window.history.back()</script>";
    }

    function pre_aprobar() {
        $post = $this->input->post();
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/ejecutoriaactoadmin/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $nombreDoc = create_template($route_template, $post['informacion']);
        $this->ejecutoriaactoadmin_model->pre_aprobar($post, $nombreDoc);
        $codgest = trazar(398, 1072, $post['cod_fis'], $post['nit'], "S");
    }

    // paso la informacion de revocatoria a acuerdo de pago o juridico
    function envio() {
        $post = $this->input->post();
        if(!empty($post['gestion'])){
        $this->ejecutoriaactoadmin_model->envio($post);
        if($post['gestion']==80){
            $codgest = trazar(272,1173, $post['cod_fis'], $post['nit'], "S");
            $this->ejecutoriaactoadmin_model->updateliquidacion($post['cod_fis']);
        }else
            $codgest = trazar(76,144, $post['cod_fis'], $post['nit'], "S");
        }else{
            $this->ejecutoriaactoadmin_model->multas($post['cod_multa']);
            $codgest = trazar(76,144, $post['cod_fis'], $post['nit'], "S");
        }
    }

    function documento_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $file = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
        $codgest = trazar(419, 1112, $post['cod_fis'], $post['nit'], "S");
        $this->ejecutoriaactoadmin_model->subir_archivo($this->data['post'], $file, $this->data['user'], $codgest['COD_GESTION_COBRO']);
//        header("location:" . base_url('index.php/ejecutoriaactoadmin/'));
        echo "<script>window.history.back()</script>";
    }

    function documento_juridico2() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $file = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->ejecutoriaactoadmin_model->subir_archivo2($this->data['post'], $file, $this->data['user']);
        $codgest = trazar(425, 1111, $post['cod_fis'], $post['nit'], "S");
        header("location:" . base_url('index.php/ejecutoriaactoadmin/'));
    }

    function do_upload() {
        $post = $this->input->post();
        //echo $post['id']; 
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $post['id'])) {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        }
        $config['upload_path'] = RUTA_DES . $post['id'] . "/";
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '20480';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

    function llamadas() {
        $this->data['post'] = $this->input->post();
        $this->data['historico_llamadas'] = $this->ejecutoriaactoadmin_model->historico_llamadas($this->data['post']);
        $this->load->view('ejecutoriaactoadmin/llamadas', $this->data);
    }

    function enviar_correcciones() {
        $this->data['post'] = $this->input->post();
        $this->ejecutoriaactoadmin_model->enviar_correcciones($this->data['post']);
    }

    function observaciones() {
        $this->data['post'] = $this->input->post();
        $this->data['titulos'] = $this->ejecutoriaactoadmin_model->titulos($this->data['post']['id']);
        $this->data['observaciones'] = $this->ejecutoriaactoadmin_model->observaciones($this->data['post']['id']);
        $this->load->view('ejecutoriaactoadmin/observaciones', $this->data);
    }

    function guardar_llamadas() {
        $this->data['post'] = $this->input->post();
        $this->ejecutoriaactoadmin_model->guardar_llamadas($this->data['post']); 
        $codgest = trazar(423,1109, $this->data['post']['cod_fis'], $this->data['post']['nit'], "S");
    }

    function documentacion() {
        $post = $this->input->post();
        $documentos = "";
        $input = "";
        if ($post['resolucion'] == 'true') {
            $datos_resolucion = $this->ejecutoriaactoadmin_model->datos_resolucion($post['id']);
            $documentos.=$datos_resolucion['tabla'];
            $input.=$datos_resolucion['input'];
        }
        if ($post['citacion'] == 'true') {
            $datos_citacion = $this->ejecutoriaactoadmin_model->datos_citacion($post['id']);
            $documentos.=$datos_citacion['tabla'];
            $input.=$datos_citacion['input'];
        }
        if ($post['recurso'] == 'true') {
            $datos_recurso = $this->ejecutoriaactoadmin_model->datos_recurso($post['id']);
            $documentos.=$datos_recurso['tabla'];
            $input.=$datos_recurso['input'];
        }
        if ($post['ejecutoria'] == 'true') {
            $datos_ejecutoria = $this->ejecutoriaactoadmin_model->datos_ejecutoria($post['cod_fis']);
            $documentos.=$datos_ejecutoria['tabla'];
            $input.=$datos_ejecutoria['input'];
        }
        if ($post['revocatoria'] == 'true') {
            $datos_revocatoria = $this->ejecutoriaactoadmin_model->datos_revocatoria($post['id'], $post['cod_fis']);
            $documentos.=$datos_revocatoria['tabla'];
            $input.=$datos_revocatoria['input'];
        }
        if ($post['liquidacion'] == 'true') {
            $datos_liquidacion= $this->ejecutoriaactoadmin_model->datos_liquidacion($post['id'], $post['cod_fis']);
            $documentos.=$datos_liquidacion['tabla'];
            $input.=$datos_liquidacion['input'];
        }

//        echo  $documentos;
//        echo json_encode(array('total' => "2",'input' => "1"));
        return $this->output->set_content_type('appliation/json')->set_output(json_encode(array('total' => $documentos, 'input' => $input)));
    }

    function guardar_documento_diridico_paso_cobro_coac() {
        $post = $this->input->post();
//        echo "<pre>";
//        print_r($post);
    }

}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */