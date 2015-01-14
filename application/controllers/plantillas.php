<?php

class Plantillas extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('plantillas_model');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen',
            'css/uploadfile.css' => 'screen',
            'css/uploadfile.min.css' => 'screen',
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/jquery.uploadfile.js',
            'js/jquery.uploadfile.min.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
        //RUTAS
        define("RUTA_INI", "./uploads/plantillas");
        define("RUTA_DES", "uploads/plantillas/");
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            $this->data['informacion'] = $this->plantillas_model->plantillas();
            $this->template->load($this->template_file, 'plantillas/generar_plantillas', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function nuevaplantilla() {
        $this->load->view('plantillas/nuevaplantilla', $this->data);
    }

    function editar_plantilla() {
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['post'] = $_REQUEST;
        $this->data['informacion'] = $this->plantillas_model->plantillas($this->data['post']['id']);
        $urlplantilla2 = "uploads/plantillas/" . $this->data['informacion'][0]['ARCHIVO_PLANTILLA'];
        if (file_exists($urlplantilla2)):
            if (!empty($this->data['informacion'][0]['ARCHIVO_PLANTILLA'])) :
                $this->data['filas2'] = file($urlplantilla2);
            else :
                $this->data['filas2'] = "";
            endif;
        else :
            $this->data['filas2'] = "";
            $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>El documento de la plantilla no existe.</div>');

        endif;


        $this->load->view('plantillas/editar_plantilla', $this->data);
    }

    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES, 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES)) {
                if (!mkdir(RUTA_DES, 0777, true)) {
                    
                }
            }
        }
        $ar = fopen(RUTA_DES . $post['nombre'] . ".txt", "w+") or die();
        if (!empty($post['informacion']))
            fputs($ar, $post['informacion']);
        else
            fputs($ar, $post['infor']);
        fclose($ar);
        if ($post['opcion'] == 1)
            $this->data['informacion'] = $this->plantillas_model->guardar($this->data);
        else
            $this->data['informacion'] = $this->plantillas_model->modificar($this->data);
    }

    function view() {
        $post = $this->input->post();
        $urlplantilla2 = $post['rutas'];
        $valor = file($urlplantilla2);
        print_r($valor[0]);
    }

    function view2() {
        $post = $this->input->post();
        $urlplantilla2 = $post['rutas'];
        $valor = file($urlplantilla2);
        $datos = base64_decode($valor[0]);
        print_r($datos);
    }

}
