<?php

class Gestionwf extends MY_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library();
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('gestionwf_model');
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/bootbox.js',
            'js/bootbox.min.js'
        );

        $this->load->helper(array('url', 'form'));
        //cargamos la librería form_validation
        //cargamos el modelo crud_model
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
    }

    /**
     * Funcion index
     * Redireciona a la vista principal de gestionwf
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function index() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestionwf/gestionwf')) {
                $this->data['dato1'] = $this->gestionwf_model->getdireccion();
                $this->data['dato'] = $this->gestionwf_model->getprocesos();
                $this->data['otros'] = $this->gestionwf_model->getgestion();
                $this->template->load($this->template_file, "gestionwf/gestionwf", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion traergestion
     * Trae todas las gestiones retornadas desde la funcion del modelo getgestion
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function traergestion() {
        //print_r($_REQUEST);
        $pro = $this->input->get_post('proceso');
        $nuevos = $this->gestionwf_model->getgestion($pro);

        //echo "<select name='selgestion1'>";
        $option = "";
        $option = '</option> <option value = "0">Seleccione Un Tipo Gestion</option>';
        foreach ($nuevos as $i => $nuevo)
            $option .= '<option value = "' . $i . '">' . $nuevo . '</option>';
        // echo "</select>";
        echo $option;
    }

    function traerprocesos() {
        //print_r($_REQUEST);
        $pro1 = $this->input->get_post('direccion');
        if($pro1==1){
            $pro2='A';
        }else if($pro1==2){
            $pro2='J';
        }else if($pro1==3){
            $pro2='N';
        }
        $nuevos1 = $this->gestionwf_model->getprocesos($pro2);
        print_r($nuevos1);
        //echo "<select name='selgestion1'>";
        $option1 = "";
        $option1 = '</option> <option value = "0">Seleccione el proceso</option>';
        foreach ($nuevos1 as $i => $nuevo1)
            $option1 .= '<option value = "' . $i . '">' . $nuevo1 . '</option> ';
        // echo "</select>";
        echo $option1;
    }

    /**
     * Funcion consultanoti
     * Trae todas los recordatorios retornadas desde la funcion del modelo getnotificaciones
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function consultanoti() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestionwf/gestionwf')) {
                $this->load->model('gestionwf_model');
                if ($this->input->post()) {
                    $datos = $this->input->post();
                    $_SESSION['datos'] = $datos;
                } else {
                    $datos = $_SESSION['datos'];
                }


                if (!empty($datos['selProcesos']) and !empty($datos['selgestion'])) :
                    //print_r($datos);
                    $this->data['datos'] = $this->gestionwf_model->getnotificaciones($datos);


                else :
                    $this->data['error'] = "Por favor ingrese los valores completos";
                    $this->index();
                endif;

                $this->data['gestion'] = $datos['tipogestion'];
                $this->data['gestionar'] = $datos['selgestion'];
                $this->data['tiponoti'] = $datos['tiponoti'];



                $this->template->load($this->template_file, "gestionwf/gestionnotifica", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function traerusers() {

        $recordatorio = 173;
        $this->data['recordatorio'] = $this->gestionwf_model->getusernoti($recordatorio);
        $this->load->view('gestionwf/usuarios', $this->data);
    }

    /**
     * Funcion notificaciones
     * Trae todas los usuarios y plantillas retornadas por las funciones del modelo getusuarios y getplantillas
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function notificaciones() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestionwf/addnotificaciones')) {
                $datos = $this->input->post();
                $this->data['gestionar'] = $datos['tipogestion'];
                $this->data['tiponoti'] = $datos['tiponoti'];
                //echo $noti;
                $this->data['selgestionar'] = $datos['tipogestion1'];
                $this->load->model('gestionwf_model');
                $this->data['dato'] = $this->gestionwf_model->getusuarios();
                $this->data['dato1'] = $this->gestionwf_model->getplantillas();
                $this->template->load($this->template_file, "gestionwf/addnotificaciones", $this->data);
                //$this->load->view('gestionwf/addnotificaciones',$this->data);
                //print_r($datos);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function creanotificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestionwf/crearnotificacion')) {
                $datos = $this->input->post();
                $this->data['gestionar'] = $datos['tipogestion'];
                $this->data['tiponoti'] = $datos['tiponoti'];
                //echo $noti;
                $this->data['selgestionar'] = $datos['tipogestion1'];
                $this->load->model('gestionwf_model');
                $this->data['dato'] = $this->gestionwf_model->getusuarios();
                $this->data['dato1'] = $this->gestionwf_model->getplantillas();
                $this->data['dato2'] = $this->gestionwf_model->getdireccion();
                $this->data['dato3'] = $this->gestionwf_model->getprocesos();
                $this->data['dato4'] = $this->gestionwf_model->getgestion();
                $this->template->load($this->template_file, "gestionwf/crearnotificacion", $this->data);
                //$this->load->view('gestionwf/addnotificaciones',$this->data);
                //print_r($datos);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion nuevanotificacion
     * se envian todos los datos generados por la vista para ingresar un nuevo recordatorio en la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function nuevanotificacion() {

//        echo "esta ak";die;
        $usuario = $this->input->post('selUsuarios');
        $post = $this->input->post();


        $plantilla = $this->input->post('selPlantillas');
        $data['CODACTIVIDAD'] = $post['CODACTIVIDAD'];
        $data['CODPLANTILLA'] = $plantilla;
        $data['ACTIVO'] = $post['ACTIVO'];
        $data['RECORDATORIO_CORREO'] = $post['RECORDATORIO_CORREO'];

//        var_dump($post['RECORDATORIO_PANTALLA']);die;

        $data['RECORDATORIO_PANTALLA'] = $post['RECORDATORIO_PANTALLA'];

//        echo $post['RECORDATORIO_PANTALLA'];die;
//        $data['RECORDATORIO_PANTALLA'] = $post['RECORDATORIO_PANTALLA'];
        $data['TIEMPO_MEDIDA'] = $post['TIEMPO_MEDIDA'];
        $data['TIEMPO_NUM'] = $post['TIEMPO_NUM'];
        $data['NOMBRE_RECORDATORIO'] = $post['NOMBRE_RECORDATORIO'];
        $data['TIPO_RECORDATORIO'] = $post['TIPO_RECORDATORIO'];
        $this->load->model('gestionwf_model');
        //$this->data['arrDatos'] = $this->gestionwf_model->getusuarios();
        $this->gestionwf_model->insertar_notificaciones($data);
        $idrecordatorio = $this->gestionwf_model->consultarid($data);
        //print_r($usuario);
        for ($i = 0; $i < count($usuario); $i++) {

            $this->gestionwf_model->insertar_usuariorecordatorio($idrecordatorio['CODRECORDATORIO'], $usuario[$i]);
        }

        redirect(base_url() . 'index.php/gestionwf/consultanoti');
//        $this->load->view("gestionwf/addnotificaciones");    
    }

    function nuevanotificacion1() {

//        echo "esta ak";die;
        $usuario = $this->input->post('selUsuarios');
        $post = $this->input->post();


        $plantilla = $this->input->post('selPlantillas');
        $data['CODACTIVIDAD'] = $post['CODACTIVIDAD'];
        $data['CODPLANTILLA'] = $plantilla;
        $data['ACTIVO'] = $post['ACTIVO'];
        $data['RECORDATORIO_CORREO'] = $post['RECORDATORIO_CORREO'];

//        var_dump($post['RECORDATORIO_PANTALLA']);die;

        $data['RECORDATORIO_PANTALLA'] = $post['RECORDATORIO_PANTALLA'];

//        echo $post['RECORDATORIO_PANTALLA'];die;
//        $data['RECORDATORIO_PANTALLA'] = $post['RECORDATORIO_PANTALLA'];
        $data['TIEMPO_MEDIDA'] = $post['TIEMPO_MEDIDA'];
        $data['TIEMPO_NUM'] = $post['TIEMPO_NUM'];
        $data['NOMBRE_RECORDATORIO'] = $post['NOMBRE_RECORDATORIO'];
        $data['TIPO_RECORDATORIO'] = $post['TIPO_RECORDATORIO'];
        $this->load->model('gestionwf_model');
        //$this->data['arrDatos'] = $this->gestionwf_model->getusuarios();
        $this->gestionwf_model->insertar_notificaciones($data);
        $idrecordatorio = $this->gestionwf_model->consultarid($data);
        //print_r($usuario);
        for ($i = 0; $i < count($usuario); $i++) {

            $this->gestionwf_model->insertar_usuariorecordatorio($idrecordatorio['CODRECORDATORIO'], $usuario[$i]);
        }
        
//        echo "<script>alert('Error en consulta de base de datos');location.href='".base_url('index.php/gestionwf/creanotificacion')."'</script>";die;
        header("Location:" . base_url('index.php/gestionwf/creanotificacion'));


//        $this->load->view("gestionwf/addnotificaciones");    
    }

    /**
     * Funcion delete_noti
     * se trae el id del recordatorio para poder ejecutal la funcion delete_noti del modelo y eliminar de la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function delete_noti($CODRECORDATORIO) {

        $CODRECORDATORIO = $this->input->post('col');
        echo $CODRECORDATORIO;
        $delete = $this->gestionwf_model->delete_noti($CODRECORDATORIO);
        redirect(base_url() . 'index.php/gestionwf/consultanoti');
    }

    /**
     * Funcion editarnotificacion
     * Muestra la vista para poder actualizar registro de recordatorios
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function editarnotificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {
                try {
                    $id = $this->uri->segment(3);
                    $this->data['usuarios'] = $this->gestionwf_model->getusernoti($id);
                    $this->data['editar'] = $this->gestionwf_model->editarnotificaciones($id);
                    $plantilla = $this->input->post('selPlantillas');
                    $usuarios = $this->input->post('selUsuarios');

                    $this->data['notificacion'] = $this->gestionwf_model->getplantillas();
                    $this->data['dato'] = $this->gestionwf_model->getusuarios();

                    $this->load->view('gestionwf/editarnoti', $this->data);
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }
    }

    /**
     * Funcion actualizarnotificacion
     * Trae los nuevos datos generados desde la vista para actulizar el registro en la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function actualizarnotificacion() {



        $CODRECORDATORIO = $_POST['CODRECORDATORIO'];
        $ACTIVO = $_POST['ACTIVO'];
        $CODPLANTILLA = $_POST['selPlantillas'];
        $TIEMPO_MEDIDA = $_POST['TIEMPO_MEDIDA'];
        $TIEMPO_NUM = $_POST['TIEMPO_NUM'];
        $NOMBRE_RECORDATORIO = $_POST['NOMBRE_RECORDATORIO'];
        $this->gestionwf_model->update_noti($CODRECORDATORIO, $ACTIVO, $CODPLANTILLA, $TIEMPO_MEDIDA, $TIEMPO_NUM, $NOMBRE_RECORDATORIO);
        $this->gestionwf_model->deleteuser($CODRECORDATORIO);
        $usuario = $this->input->get_post('selUsuarios');
        for ($i = 0; $i < count($usuario); $i++) {
            $this->gestionwf_model->insertar_usuariorecordatorio($CODRECORDATORIO, $usuario[$i]);
        }
        //print_r($CODRECORDATORIO);
        redirect(base_url() . 'index.php/gestionwf/consultanoti');
        //$this->template->load($this->template_file, "gestionwf/consultanoti", $this->data);
    }

}
