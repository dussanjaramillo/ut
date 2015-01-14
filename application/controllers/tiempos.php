<?php

//ruta prototiposena: application/controllers/tiempos.php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tiempos extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->helper('traza_fecha');
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
        );
    }

    function index() {
        
    }

    /**
     * Método mostrarpantalla
     * 
     * Muestra en pantalla las notificaciones de actividades pendientes por realizar
     * 
     */
    function mostrarpantalla() {
        if ($this->ion_auth->logged_in()) {
            $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/validationEngine.jquery.css' => 'screen');
            $codigo = $this->session->userdata('user_id');
            //print_r($codigo);
            $this->load->model('flujo_model');
            $this->data['datos'] = $this->flujo_model->mostrarecordatorio($codigo);
            if ($this->data['datos'] === false) {
                redirect(base_url() . 'index.php/inicio');
            } else {
               
                $this->template->load($this->template_file, "bloqueo/mostrarrecordatorio", $this->data);
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Método bloqueo
     * Establece el bloqueo por pantalla
     *
     * 
     * @return type
     */
    function bloqueo() {
        if ($this->ion_auth->logged_in()) {
            $codfiscalizacion = $this->input->post("codfiscalizacion");
            if (empty($codfiscalizacion)) {
                $codfiscalizacion = $this->input->get("codfiscalizacion");
            }
            
            $codjudicial = $this->input->post("codjudicial");
            if (empty($codjudicial)) {
                $codjudicial = $this->input->get("codjudicial");
            }
            
            $codcoactivo = $this->input->post("codcoactivo");
            if (empty($codcoactivo)) {
                $codcoactivo = $this->input->get("codcoactivo");
            }
            
            $coddevolucion = $this->input->post("coddevolucion");
            if (empty($coddevolucion)) {
                $coddevolucion = $this->input->get("coddevolucion");
            }
            
            $codrecepcion = $this->input->post("codrecepcion");
            if (empty($codrecepcion)) {
                $codrecepcion = $this->input->get("codrecepcion");
            }
            
            $codnomisional = $this->input->post("codnomisional");
            if (empty($codnomisional)) {
                $codnomisional = $this->input->get("codnomisional");
            }

            $gestion = $this->input->post("gestion");
            if (empty($gestion)) {
                $gestion = $this->input->get("gestion");
            }

            $mostrar = $this->input->post("mostrar");
            if (empty($mostrar)) {
                $mostrar = $this->input->get("mostrar");
            }

            $fecha = $this->input->post("fecha");
            if (empty($fecha)) {
                $fecha = $this->input->get("fecha");
            }

            $si = $this->input->post("si");
            if (empty($si)) {
                $si = $this->input->get("si");
            }

            $no = $this->input->post("no");
            if (empty($no)) {
                $no = $this->input->get("no");
            }

            $parametros = $this->input->post("parametros");
            if (empty($parametros)) {
                $parametros = $this->input->get("parametros");
            }

            $bd = $this->input->post("BD");
            if (empty($bd)) {
                $bd = $this->input->get("BD");
            }

            $cc = new Traza_fecha_helper();
            if (!empty($fecha)) {//YYYY-MM-DD
                if (strpos($fecha, "-") !== false) {
                    $f = explode("-", $fecha);
                    $cc->ponerFecha($f[0], $f[1], $f[2]);
                } else if (strpos($fecha, "/") !== false) { // DD/MM/AA
                    $f = explode("/", $fecha);
                    if (($f[2] * 1) < 100) {
                        $f[2] = 2000 + ($f[2] * 1);
                    }
                    $cc->ponerFecha($f[2], $f[1], $f[0]);
                }
            } else {
                $cc->ponerFecha(date("Y"), date("m"), date("d"));
            }

            $bloq = $cc->poneBloqueo($codfiscalizacion, $codjudicial, $codcoactivo,$coddevolucion,$codrecepcion,$codnomisional, $gestion, $cc, $tipo = 'pos', $opc = '', $mostrar, $si, $no, $parametros, $bd);
//            echo "<pre>**";
//            print_r($bloq);
//            echo "</pre>**";
//            die;
            if ($mostrar == "SI") {    //$bloq = $cc->poneBloqueoFlujo($codfiscalizacion,$gestion,$cc,$tipo='pos',$opc='',"http://yes.com","http://no.com");
                if (!empty($parametros)) {
                    $arrParametros = explode(";", $parametros);
                    $inputshidden = "";
                    foreach ($arrParametros as $par) {
                        $hidden = explode(":", $par);
                        $inputshidden .= "<input type=hidden id='" . $hidden[0] . "' name='" . $hidden[0] . "' value='" . $hidden[1] . "'> ";
                    }
                }
                if ($bloq['bandera'] > 0 && $bloq['vencido'] != 0) {//S? TIENE BLOQUEOS POR REALIZAR   //if($bloq['bandera']>0){//S? TIENE BLOQUEOS POR REALIZAR
                    $diaSem[1] = 'Lunes';
                    $diaSem[2] = 'Martes';
                    $diaSem[3] = 'Mi&eacute;rcoles';
                    $diaSem[4] = 'Jueves';
                    $diaSem[5] = 'Viernes';
                    $diaSem[6] = 'S&aacute;bado';
                    $diaSem[0] = $diaSem[7] = 'Domingo';

                    $this->data['diaSem'] = $diaSem;

                    $this->data['bloq'] = $bloq;
                    if (!empty($parametros)) {
                        $this->data['parametros'] = $inputshidden;
                    }
                    //$this->template->load($this->template_file, "bloqueo/crono", $this->data);
                    $this->load->view("bloqueo/crono", $this->data);
                }
            } else {
//                echo "entro bn";
                return $this->output->set_content_type('application/json')->set_output(json_encode($bloq));
                //echo json_encode($bloq);
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pruebabloqueo() {
        //$bloq = $cc->poneBloqueo($codfiscalizacion,$gestion,$cc,$tipo='pos',$opc='',$mostrar,$si,$no,$parametros,$bd);
        $coddispararecordatorio = 268;
        $usuariosAdicionales = '';
        $regional = '';       
        $this->load->model('flujo_model');
        $this->data['prueba'] = $this->flujo_model->traeRecUsuarios(268,'','');
        $this->data['prueba'] = $this->flujo_model->traeRecUsuarios($coddispararecordatorio,$usuariosAdicionales,$regional);
        //$this->load->view("bloqueo/crono", $this->data);

//        $cc = new Traza_fecha_helper();
//        $parametros['TIEMPONUM'] = 5;
//        $parametros['TIEMPOMEDIDA'] = 'Dias';
//        $parametros['TIPOGESTION'] = 33;
//        $parametros['NOMBRES'] = 'Haider';
//        $parametros['APELLIDOS'] = 'Oviedo';
//        $parametros['NOMBRERESPUESTA'] = 'Tal Gestion';
//        $parametros['NOMBREPROCESO'] = 'Este Proceso';
//        $bloq = $cc->poneRecordar(120, 78, '1094899620', 'pos', 'C', 'SI', 'si', 'no', $parametros, 'BD');
//        print_r($bloq);die;
        $this->template->load($this->template_file, "bloqueo/pruebabloqueo",$this->data);
       // $this->load->view("bloqueo/pruebabloqueo",$this->data);
    }

    function pruebatraza() {
        //function trazar($tipogestion, $tiporespuesta, $codfiscalizacion, $nit, $cambiarGestionActual, $comentarios = "traza"){
        //trazar(98, 13, 17, 801612878,"S","trazaDDD");
        //$res = trazar(34, 49, 63, 801612878,"S","trazaDDD");//print_r($res);
        //$res = trazar(34, 49, 63, 801612878,"S","trazaEE");//print_r($res);
        //$res = trazar(74, 120, 63, 801612878,"S",'9999988888',"trazaA33");//print_r($res);            
        //$res = trazar(243, 653, 63, 801612878,"S",'80161287',"trazaB".rand(1,999));
        $res = trazar(74, 120,4402518010000000000776, 100235865, "S", '-1');
    }

    function pruebaponerRecordatorio() {
        $cc = new Traza_fecha_helper();    //$cc->poneRecordar(13000, 903, 3,'2012-11-13');
        $prec = $cc->poneRecordar(1244, 33, 'tAL cOSA', '2014-03-06', '1094899620', 10); //print_r($prec);
    }

    function pruebaregional() {
        $this->load->model('flujo_model');
        $this->flujo_model->traeRegional(62);
    }

    function pruebaabogado() {
        $this->load->model('flujo_model');
        $this->flujo_model->traeAbogado(76);
    }

    function pruebaquitarRecordatorio() {
        $cc = new Traza_fecha_helper(); //$cc->quitarCorreoRecordatorio(13000, 510, '');
        $cc->quitarCorreoRecordatorio(13000);
    }

    function pruebahola() {
        echo "HOLA_mundo";
    }

    function pruebasumardias() {
        $cc = new Traza_fecha_helper();
        //echo " sDH: ";
        echo "<br>" . $cc->sumarDiasHabiles("2013-01-01", 1, "a");
        echo "<br>" . $cc->sumarDiasHabiles("2013-12-01", 1, "m");
        echo "<br>" . $cc->sumarDiasHabiles("2014-01-01", 2, "dc");
        echo "<br>" . $cc->sumarDiasHabiles("2014-01-02", 7, "dc");
        echo "<br>" . $cc->sumarDiasHabiles("2014-04-24", 2, "dc");
        echo "<br>" . $cc->sumarDiasHabiles("2014-04-22", 7, "dc");
    }

    function pruebaenvia() {

        $mensaje = "";
        if (!empty($nombre) || !empty($apellido)) {
            $nombres = $apellido . " " . $nombre;
            $mensaje = $nombres . ":<br> " . $mensaje;
        }

        if (!empty($creacion)) {

            $mensaje.="<br>Fecha de creaci&oacute;n:" . $creacion;
        }
        if (!empty($vencimiento)) {
            $mensaje.="<br>Fecha de vencimiento:" . $vencimiento;
        }

        $mensaje = "Prueba de correo:<br> <h3>Salmo 25:14</h3>
            <p>'Los secretos del Se&ntilde;or son
            <br> para los que le temen,</b>.
            <br>y &Eacute;l les dar&aacute; a conocer su pacto'. 
            <br><sup>Biblia de las Am&eacute;ricas</sup>.<p>
            'Porque de tal manera am&oacute; Dios a <em>" . $this->ion_auth->user()->row()->NOMBRES . "</em> <sup>*</sup>. Que ha dado su hijo unig&eacute;nito para que todo aquel que en &eacute;l cree no se pierda m&aacute;s tenga vida eterna.
            Porque no envi&oacute; Dios a su Hijo al mundo para condenar al mundo, sino para que el mundo sea salvo por &eacute;l.
            El que en &eacute;l cree no es condenado; pero el que no cree, ya ha sido condenado, porque no ha cre&iacute;do en el nombre del unig&eacute;nito Hijo de Dios.' Juan 3:16-18
            <br><sup>Reina Valera</sup><br><sub>* al mundo</sub>";

        //$adjunto[] = 'C:\Users\felipe.puerto\Pictures\tablas.png';
        //$adjunto[] = 'C:\Users\felipe.puerto\Desktop\5Fiscalizacion_5.1.png';
        $adjunto = 'C:\xampp\htdocs\apache_pb.gif';
        if (!empty($this->ion_auth->user()->row()->EMAIL)) {
            $correocopia = $this->ion_auth->user()->row()->EMAIL;
        } else {
            $correocopia = "";
        }
        //"carterasena@yopmail.com"
        $ecs = enviarcorreosena("senacartera@gmail.com", $mensaje, " Prueba de correo ", $correocopia, $adjunto, "carterasena@yopmail.com");
        //$ecs = enviarcorreosena("pruebasena@yopmail.com", $mensaje, " Hola mudo ", "" , $adjunto, "correoocultosena@yopmail.com");
        if ($ecs === true) {
            echo "true";
        }
        if ($ecs === false) {
            echo "false";
        }
    }

    function pruebaProcesoJuridico() {
        //trazarProcesoJuridico(1, 1, 1, "TARZAN PJ");
        
//        $tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion, $comentarios = "trazaPJ", $usuariosAdicionales = ''
        
        $codtitulo = '';
        $codjuridico = '';
        $codcarteranomisional = 453 ;
        $coddevolucion = '';
        $codrecepcion = '';
        $tipogestion = 168;
        $tiporespuesta = 512;
        $comentarios = '';
        
        trazarProcesoJuridico($tipogestion, $tiporespuesta, $codtitulo, $codjuridico, $codcarteranomisional, $coddevolucion,$codrecepcion);
    }

    /*
     * Función parametros
     * 
     * Trasladada a correo/parámetros
     * Establece los parámetros de correo electrónico
     * 
      function parametros(){
      $this->load->model('correo_model');
      $proceso = $this->input->post('proceso');
      if(empty($proceso)){
      $proceso = $this->input->get('proceso');
      }

      $actividad = $this->input->post('actividad');
      if(empty($actividad)){
      $actividad = $this->input->get('actividad');
      }

      $id = $this->input->post('id');
      if(empty($id)){
      $id = $this->input->get('id');
      }

      $matriz  = $this->correo_model->traeParametrosCorreo();

      $this->data['proceso'] = $proceso;
      $this->data['actividad'] = $actividad;
      $this->data['matriz'] = $matriz;
      $this->data['cod'] = $id;

      $this->load->view("correo/parametros", $this->data);
      } */
}

?>