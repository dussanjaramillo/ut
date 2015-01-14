<?php

class Gestioninstancias extends MY_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library();
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('gestioninstancias_model');
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestioninstancias/gestioninstancia')) {
                $this->data['direccion'] = $this->gestioninstancias_model->getdireccion();
                $this->data['procesos'] = $this->gestioninstancias_model->getprocesos();
                $this->data['instancias'] = $this->gestioninstancias_model->getinstancias();
                $this->template->load($this->template_file, "gestioninstancias/gestioninstancia", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getestados() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestioninstancias/gestionestado')) {
                $this->data['estados'] = $this->gestioninstancias_model->getestados();
                $this->template->load($this->template_file, "gestioninstancias/gestionestado", $this->data);
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
        $nuevos = $this->gestioninstancias_model->getgestion($pro);

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
        if ($pro1 == 1) {
            $pro2 = 'A';
        } else if ($pro1 == 2) {
            $pro2 = 'J';
        } else if($pro1 == 3){
            $pro2 = 'N';
        }  else {
            $pro2 = '';
        }
        $nuevos1 = $this->gestioninstancias_model->getprocesos($pro2);
        print_r($nuevos1);
        //echo "<select name='selgestion1'>";
        $option1 = "";
        $option1 = '</option> <option value = "0">Seleccione el proceso</option>';
        foreach ($nuevos1 as $i => $nuevo1)
            $option1 .= '<option value = "' . $i . '">' . $nuevo1 . '</option> ';
        // echo "</select>";
        echo $option1;
    }

    function nuevoestado() {


        $post = $this->input->post();

        $data['NUM_INSTANCIA'] = $post['NUM_INSTANCIA'];
        $data['NOM_INSTANCIA'] = $post['NOM_INSTANCIA'];
        $estado = $this->input->post('NUM_INSTANCIA');
        $this->load->model('gestioninstancias_model');
        $existe = $this->gestioninstancias_model->si_existe_estado($estado);
        $otro = count($existe);

//        echo $otro;die();

        if ($otro == 0) {

            $this->gestioninstancias_model->insertar_estados($data);
            echo "<script>alert('El Registro sera Creado Exitosamente');</script>";
            //echo "<script>window.location.href =" .base_url('index.php/gestioninstancias/getestados').";</script>"; 
            echo "<script>window.location ='" . base_url() . "index.php/gestioninstancias/getestados';" . "</script>";
//            echo "<script>alert('El Registro sera insertado correctamente');"
//            . " location.href=".base_url() . 'index.php/gestioninstancias/getestados'.";</script>";
//            header("Location:".base_url() . 'index.php/gestioninstancias/getestados');
        } else if (count($otro) == 1) {
            echo "<script>alert('El Codigo Ya Existe');</script>";
            echo "<script>window.location ='" . base_url() . "index.php/gestioninstancias/creanestados';" . "</script>";
            //echo      //  "<script>window.location='http://localhost/prototiposena/index.php/gestioninstancias/nuevoestado'; </script>";
//            header("Location:".base_url() . 'index.php/gestioninstancias/creanestados');
        } else {
            redirect(base_url() . 'index.php/gestioninstancias/');
        }
    }

    function nuevainstancia() {


        $post = $this->input->post();

        $data['direccion'] = $post['direccion'];
        $data['selProcesos'] = $post['selProcesos'];
        $data['instancia'] = $post['instancia'];

//        print_r($data);die();
        $this->load->model('gestioninstancias_model');

        $this->gestioninstancias_model->insertar_instancias($data);
        redirect(base_url() . 'index.php/gestioninstancias/');
//            echo "<script>bootbox.alert('El Codigo Ya Existe');</script>";
    }

    function activar_estado($estado) {

        $estado = $this->input->post('est');
//        echo $instancia;
        $delete = $this->gestioninstancias_model->activa_estado($estado);
        redirect(base_url() . 'index.php/gestioninstancias/getestados');
    }

    function inactivar_estado($estado1) {

        $estado1 = $_POST['est1'];
//        echo 'uhybgegiougwnerougn'.$estado;die();
        $delete = $this->gestioninstancias_model->inactiva_estado($estado1);
        redirect(base_url() . 'index.php/gestioninstancias/getestados');
    }

    function activar_instancia($estado) {

        $estado = $this->input->post('est');
//        echo $instancia;
        $delete = $this->gestioninstancias_model->activa_instancia($estado);
        redirect(base_url() . 'index.php/gestioninstancias/gestioninstancias');
    }

    function inactivar_instancia($estado1) {

        $estado1 = $_POST['est1'];
//        echo 'uhybgegiougwnerougn'.$estado;die();
        $delete = $this->gestioninstancias_model->inactiva_instancia($estado1);
        redirect(base_url() . 'index.php/gestioninstancias/gestioninstancias');
    }

    /**
     * Funcion consultanoti
     * Trae todas los recordatorios retornadas desde la funcion del modelo getnotificaciones
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function consultanoti() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestioninstancias/gestioninstancias')) {
                $this->load->model('gestioninstancias_model');
                if ($this->input->post()) {
                    $datos = $this->input->post();
                    $_SESSION['datos'] = $datos;
                } else {
                    $datos = $_SESSION['datos'];
                }


                if (!empty($datos['selProcesos']) and !empty($datos['selgestion'])) :
                    //print_r($datos);
                    $this->data['datos'] = $this->gestioninstancias_model->getnotificaciones($datos);


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
        $this->data['recordatorio'] = $this->gestioninstancias_model->getusernoti($recordatorio);
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
                $this->load->model('gestioninstancias_model');
                $this->data['dato'] = $this->gestioninstancias_model->getusuarios();
                $this->data['dato1'] = $this->gestioninstancias_model->getplantillas();
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

    function creaninstancias() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestioninstancias/crearinstancias')) {
                $datos = $this->input->post();
                $this->load->model('gestioninstancias_model');
                $this->data['dato2'] = $this->gestioninstancias_model->getdireccion();
                $this->data['dato3'] = $this->gestioninstancias_model->getprocesos();
                $this->data['dato4'] = $this->gestioninstancias_model->getinstan();
                $this->template->load($this->template_file, "gestioninstancias/crearinstancias", $this->data);
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

    function creanestados() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestioninstancias/crearestados')) {
                $datos = $this->input->post();
                $this->load->model('gestioninstancias_model');
                $this->template->load($this->template_file, "gestioninstancias/crearestados", $this->data);
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
     * Funcion delete_noti
     * se trae el id del recordatorio para poder ejecutal la funcion delete_noti del modelo y eliminar de la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function delete_instancia($instancia) {

        $instancia = $this->input->post('ins');
//        echo $instancia;
        $delete = $this->gestioninstancias_model->delete_instancia($instancia);
        redirect(base_url() . 'index.php/gestioninstancias/gestioninstancias');
    }

    /**
     * Funcion editarnotificacion
     * Muestra la vista para poder actualizar registro de recordatorios
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function editarestado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {
                try {
                    $id = $this->uri->segment(3);
                    $this->data['editar'] = $this->gestioninstancias_model->editarestados($id);
                    $this->load->view('gestioninstancias/editarestado', $this->data);
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
    function actualizarestado() {
        $NUM_INSTANCIA = $this->input->post('COD_TIPO_INSTANCIA');
        $NOM_INSTANCIA = $this->input->post('NOMBRE_TIPO_INSTANCIA');
        $instancia = $this->input->post('instancia');
        $estado = $this->input->post('COD_TIPO_INSTANCIA');

        $existe = $this->gestioninstancias_model->si_existe_estado($estado);
        $otro = count($existe);
//        echo $otro;die();

//        if ($otro == 0) {
            $this->gestioninstancias_model->update_estado($NUM_INSTANCIA, $NOM_INSTANCIA, $instancia);
            echo "<script>alert('Su Actualizacion se realizara con Exito');</script>";
            echo "<script>window.location ='" . base_url() . "index.php/gestioninstancias/getestados';" . "</script>";
//            redirect(base_url() . 'index.php/gestioninstancias/getestados');
//            echo "<script>bootbox.alert('El Codigo Ya Existe');</script>";
//        } else if ($otro == 1) {
//            echo "<script>alert('Su Actualizacion No se realizara con Exito');</script>";
//            echo "<script>window.location ='" . base_url() . "index.php/gestioninstancias/getestados';" . "</script>";
//            redirect(base_url() . 'index.php/gestioninstancias/getestados');
//           echo "<script>bootbox.alert('El Codigo Ya Existe');</script>"; 
//        } else {
//            redirect(base_url() . 'index.php/gestioninstancias/');
//        }
    }

    function editarinstancia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin()) {
                try {
                    $id = $this->uri->segment(3);
                    $this->data['editarins'] = $this->gestioninstancias_model->editarinstancias($id);
                    $this->data['dato2'] = $this->gestioninstancias_model->getdireccion();
                    $this->data['dato3'] = $this->gestioninstancias_model->getprocesos1();
                    $this->data['dato4'] = $this->gestioninstancias_model->getinstan();
                    $this->load->view('gestioninstancias/editarinstancia', $this->data);
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
    function actualizarinstancia() {
        $PROCESO = $this->input->post('selProcesos');
        $INSTANCIA = $this->input->post('instancia');
        $TIPOINSTANCIA = $this->input->post('instanciaproceso');

        $this->gestioninstancias_model->update_instancia($PROCESO, $INSTANCIA, $TIPOINSTANCIA);
        redirect(base_url() . 'index.php/gestioninstancias/');
    }

}
