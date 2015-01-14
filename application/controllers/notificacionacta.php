<?php

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : Generacion de resolucion contrato de aprendizaje, fic, aportes en Administrativa
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

class Notificacionacta extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha_helper', 'template_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('carteracobro_model');
        $this->load->model('numeros_letras_model');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->library('email');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/tinymce/tinymce.jquery.min.js',
            'js/validateForm.js'
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
        //RUTAS
        define("RUTA_INI", "./uploads/fiscalizaciones/resolucion");
        define("RUTA_DES", "uploads/fiscalizaciones/resolucion/COD_");
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
    }

    function index() {
        $this->asignar();
    }

    function asignar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_menu('resolucion/asignar') || $this->ion_auth->in_group('Abogados relaciones corporativas ')) {
                $this->data['asignar'] = $this->carteracobro_model->buscar_nuevas_resoluciones();
                $this->template->load($this->template_file, 'notificacionacta/asignar', $this->data);
            } else {
                redirect(base_url() . 'index.php/auth/login');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function asignar_abogado() {
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['post'] = $this->input->post();
        $this->data['abogados'] = $this->carteracobro_model->abogados($this->data['user']->COD_REGIONAL);
        $this->load->view('notificacionacta/asignar_abogado', $this->data);
    }

    function guardar_abogado() {
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['post'] = $this->input->post();
        $this->data['abogados'] = $this->carteracobro_model->guardar_abogado($this->data);
    }

    //-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : resive 2 parametros cod fiscalizacion y opc  si la segunva variable viene es porque la resolucion es de contrato de aprendizaje
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function manage() {
        $this->activo('manage');
        $this->data['user'] = $this->ion_auth->user()->row();
        $post = $this->input->post();
        $this->data['post'] = $post;
        $cod_fiscalizacion = $post['cod_fiscalizacion'];
        if (!empty($cod_fiscalizacion)) {
            $cod_gestion_liquidacion = $cod_fiscalizacion;
//            if (isset($post['opc'])) {
//                $this->data['iniciar'] = $this->carteracobro_model->sgva($cod_gestion_liquidacion);
//            } else {
//                $this->data['iniciar'] = $this->carteracobro_model->iniciar($cod_gestion_liquidacion);
//            }
            $this->data['iniciar'] = $this->carteracobro_model->iniciar($cod_gestion_liquidacion);


            if (!empty($this->data['iniciar']['NIT_EMPRESA'])) {
                $this->carteracobro_model->set_nit($this->data['iniciar']['NIT_EMPRESA']);

                $this->carteracobro_model->set_num_liquidacion($this->data['iniciar']['NUM_LIQUIDACION']);
                $this->template->set('title', 'Generar Resoluciones');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['query'] = $this->carteracobro_model->empresa();
                $this->data['liquidacion'] = $this->carteracobro_model->info_liquidacion();
                $this->data['reviso'] = $this->carteracobro_model->reviso();
                $this->data['abogados'] = $this->carteracobro_model->abogados($this->data['user']->COD_REGIONAL);
                $this->data['cordinador'] = $this->carteracobro_model->cordinador();
//                $this->carteracobro_model->set_cod_municipio($this->data['query']['COD_MUNICIPIO']);
//                $this->data['ciudad'] = $this->carteracobro_model->municipio();
//                $this->data['ciudad'] = $this->carteracobro_model->municipio();
                $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->data['query']['NOMBREMUNICIPIO'];
//                        $this->data['consulta'][0]['NOMBREMUNICIPIO'];
                $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;

                switch ($this->data['liquidacion']['COD_CONCEPTO']) {
                    case '1'://fic , aportes
                    case '2'://fic , aportes
                        $this->template->load($this->template_file, 'notificacionacta/carteracobro', $this->data);
                        break;
                    case '3'://contrato aprendizaje
                        $this->template->load($this->template_file, 'notificacionacta/contrato_aprendizaje', $this->data);
                        break;
                    default :
                        echo "Datos Incorrectos";
                }
            } else {
                echo "<script>alert('Aun No se puede Generar la Resolucion.'); window.history.back()</script>";
            }
        } else {
            echo "<script>alert('Aun No se puede Generar la Resolucion'); window.history.back()</script>";
        }
    }

    function activo($action) {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/' . $action) || $this->ion_auth->in_group('Abogados relaciones corporativas ')) {
                
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : permite enviar un valor y retorna las letras de ese valor, el segundo parametro es "pesos o dolares"
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function valorEnLetras($valor, $tipo) {
        $info = $this->numeros_letras_model->ValorEnLetras($valor, $tipo);
        return $info;
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se optiene la informacion de la resolcuion en aportes 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function resolucion_aportes() {
        $this->template->load($this->template_file, 'notificacionacta/resolucion_aportes', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se optiene la informacion de la resolcuion en fic
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function resolucion_fic() {
        $this->template->load($this->template_file, 'notificacionacta/resolucion_fic', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se optiene la informacion de la resolucion contrato de aprendizaje
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function resolucion_cont_sena() {
        $this->template->load($this->template_file, 'notificacionacta/resolucion_cont_sena', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se remplaza los valores dependienso la resolucion solucitada
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function resoluciones() {
        //$this->form_validation->set_rules('decision', 'Decision', 'required');
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['informacion'] = $this->input->post();
//        echo "<pre>";
//        print_r($this->data['informacion']);
//        echo "</pre>";
        if (isset($this->data['informacion']['nit'])) {
            switch ($this->input->post('id_concepto')) {
                case '2'://fic
                    $this->data['detalle'] = $this->carteracobro_model->detalle_fic($this->data['informacion']['liquidacion']);
                    $this->template->load($this->template_file, 'notificacionacta/resolucion_fic', $this->data);
                    break;
                case '1'://aportes
                    $this->data['detalle'] = $this->carteracobro_model->detalle_aportes($this->data['informacion']['liquidacion']);
                    $this->template->load($this->template_file, 'notificacionacta/resolucion_aportes', $this->data);
                    break;
                default :
                    switch ($this->input->post('decision')) {
                        case '1'://archivo
                            $this->template->load($this->template_file, 'notificacionacta/resolucion_cont_sena_archivo', $this->data);
                            break;
                        case '2'://sancionatorio
                            $this->template->load($this->template_file, 'notificacionacta/resolucion_cont_sena_sancionatorio', $this->data);
                            break;
                        default :
                            echo "Datos no encontrados";
                    }
            }
        } else {
            header("location:" . base_url('index.php'));
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : plantilla de las resoluciones
//Fecha : 4/06/2014        
//------------------------------------------------------------------------------

    function plantillas($id, $array) {
        $txt = $this->carteracobro_model->plantilla($id);
        $informacion = "";
        if (!empty($txt)) {
            $texto = template_tags("./uploads/plantillas/" . $txt, $array);
            $informacion = $texto;
        }
        return $informacion;
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : envia la informacion a pdf
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function resolucion_pdf() {
        $this->data['informacion'] = $this->input->post();
        $this->data['pdf'] = "1";
        switch ($this->input->post('id_concepto')) {
            case '2'://fic
                $this->template->load($this->template_file, 'notificacionacta/resolucion_fic', $this->data);
                break;
            case '1'://aportes
                $this->template->load($this->template_file, 'notificacionacta/resolucion_aportes', $this->data);
                break;
            default :
                switch ($this->input->post('decision')) {
                    case '1':
                        $this->template->load($this->template_file, 'notificacionacta/resolucion_cont_sena_archivo', $this->data);
                        break;
                    case '2':
                        $this->template->load($this->template_file, 'notificacionacta/resolucion_cont_sena_sancionatorio', $this->data);
                        break;
                    default :
                        echo "Datos no encontrados";
                }
        }
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : guarda la resolucion dependiendo el concepto
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function resolucion_guardar() {
        $this->data['informacion'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        if (empty($this->data['informacion']['id_concepto'])) {
            $id = $this->carteracobro_model->resolucion_guardar_contrato($this->data['informacion'], $this->data['user']);
        } else {
            $id = $this->carteracobro_model->resolucion_guardar_aportes_fic($this->data['informacion'], $this->data['user']);
        }

        $email_regional = $this->data['informacion']['email_regional'];

        //falta enviar los correos
        //echo $id;
        $this->guardar_archivo($id);
        header('Location:' . base_url('index.php/resolucion/datos_del_abogado'));
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : guarda la resolucion para poderla poner en firme
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function guardar_archivo($id) {
        $post = $this->input->post();
        $this->data['post'] = $post;
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $id, 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $id)) {
                if (!mkdir(RUTA_DES . $id, 0777, true)) {
                    
                }
            }
        }
        $ar = fopen(RUTA_DES . $id . "/" . $id . ".txt", "w+") or die();
        fputs($ar, $post['proyecto']);
        fclose($ar);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : se optiene la informacion de la resolcuion en pdf 
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------

    function pdf($html) {
        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('aqui_tu_nombre');
        //$pdf->SetTitle($sTitulo);
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');
        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "");
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
        $pdf->Output('datos.pdf', 'D');
        exit();
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : en contrato de paprendizaje permite obtener los considerando
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function considerando() {
        $this->data['post'] = $this->input->post();
        $this->load->view('notificacionacta/considerando', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : en contrato de paprendizaje permite obtener los resuelve
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function resuelve() {
        $this->data['post'] = $this->input->post();
        $this->load->view('notificacionacta/resuelve', $this->data);
    }

}

/* End of file motivodevolucion.php */
    /* Location: ./system/application/controllers/motivodevolucion.php */    