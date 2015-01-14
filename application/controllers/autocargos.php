<?php

// Responsable: Leonardo Molina
class Autocargos extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'codegen_helper', 'date', 'template', 'traza_fecha'));
        $this->load->library(array('datatables', 'libupload', 'form_validation', 'tcpdf/tcpdf.php'));
        $this->load->model('autocargos_model');
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/validCampoFranz.js', 'js/jquery.dataTables.defaults.js', 'js/tinymce/tinymce.jquery.min.js');
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('ejecutoriaactoadmin/index') || $this->ion_auth->in_menu('ejecutoriaactoadmin/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $this->template->set('title', 'Autos Cargos');
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'autocargos/autocargos_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function asignar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('ejecutoriaactoadmin/index') || $this->ion_auth->in_menu('ejecutoriaactoadmin/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $this->template->set('title', 'Autos Cargos');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['info'] = 1;
                $this->template->load($this->template_file, 'autocargos/autocargos_list2', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function tales() {
//            $this->data['registros']= $this->autocargos_model->getEmpresas($this->uri->segment(3));
        $mensaje = "Se ha generado un auto de cargos con el número de fiscalizacion: para la empresa con nit: ";
        $resp = enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);
//            
        var_dump($resp);
    }

    function generarAuto() {
        //redirect(URL('admin','example'),client_side=True)
        //redireccionar y cargar la pagina completa

        $this->data['estado_cuenta'] = $this->uri->segment(3);
        $this->data['registros'] = $this->autocargos_model->getEmpresas($this->uri->segment(3));
//            print_r($this->data['registros']);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');

        $this->load->view('autocargos/autocargos_create', $this->data);
    }

    function addAutocargos() {
        $html = $this->input->post('informacion');
        $fisc = $this->input->post('cfisc');
        $nit = $this->input->post('nit');
        $sgvanro = $this->input->post('ecuenta');
        $tipoAuto = '20';
        $estado = '1'; //AUTO generado
        $tipoProceso = '1'; //???????
        $creadoPor = $this->ion_auth->user()->row()->IDUSUARIO;
        $asignadoA = $this->ion_auth->user()->row()->IDUSUARIO;
        //trazar($tipogestion, $tiporespuesta, $codfiscalizacion, $nit, $cambiarGestionActual, $comentarios = "traza")
        // 55 -> Generar Auto de Cargos
        // 91 -> Auto de cargos Generado
//            die();
        $codgest = trazar(55, 1324, $fisc, $nit, "S");
        $codGestion = $codgest['COD_GESTION_COBRO'];
        $route_template = 'uploads/fiscalizaciones/' . $fisc . '/autocargos/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $nombreDoc = create_template($route_template, $html);
        $query = $this->autocargos_model->saveAuto($tipoAuto, $fisc, $estado, $tipoProceso, $creadoPor, $asignadoA, $codGestion, $nombreDoc, $sgvanro);
        if ($query == TRUE) {

            $mensaje = "Se ha generado un auto de cargos <br> Número de fiscalizacion: " .
                    $fisc . " <br> Empresa con nit: " . $nit;
            enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Auto de cargos generado con éxito.</div>');
            redirect(base_url() . 'index.php/autopruebas');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
            redirect(base_url() . 'index.php/autopruebas');
        }
    }

    function getData() {

        $this->load->library('datatables');
        $this->load->model('autocargos_model');
        $accion=$this->input->get('accion');
        if (isset($accion)) {
            if ($accion!=1) 
        $this->db->where("F.COD_ABOG_RELACIONES", ID_USER);
        else 
            $this->db->where("F.COD_ABOG_RELACIONES IS NULL AND 1=",1,FALSE );
        }
        $data['registros'] = $this->autocargos_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'));
        if (isset($accion)) {
            if ($accion!=1) 
        $this->db->where("F.COD_ABOG_RELACIONES", ID_USER);
        else 
            $this->db->where("F.COD_ABOG_RELACIONES IS NULL AND 1=",1,FALSE );
        }
//            define('AJAX_REQUEST', 1);//truco para que en nginx no muestre el debug  
        $TOTAL = $this->autocargos_model->totalData($this->input->post('sSearch'));

        echo json_encode(array('aaData' => $data['registros'],
            'iTotalRecords' => $TOTAL,
            'iTotalDisplayRecords' => $TOTAL,
            'sEcho' => $this->input->post('sEcho')));
    }
    function asignar_abogado() {
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['post'] = $this->input->post();
        $this->data['abogados'] = $this->autocargos_model->abogados($this->data['user']->COD_REGIONAL);
        $this->load->view('autocargos/asignar_abogado', $this->data);
    }
    function guardar_abogado() {
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['post'] = $this->input->post();
        $this->data['abogados'] = $this->autocargos_model->guardar_abogado($this->data);
    }

    function pdf() {

        $html = $this->input->post('descripcion_pdf');
        $nombre_pdf = $this->input->post('nombre');
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
        $pdf->Output($nombre_pdf . '.pdf', 'I');

        exit();
    }

}
