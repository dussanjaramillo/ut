<?php

/**
 * Archivo para la administración de los metodos necesarios para visulizar los diferentes procesos en la Dirección Jurídica
 *
 * @packageCartera
 * @subpackage Controllers
 * @author vferreira
 * @location./application/controllers/bandejaunificada.php

 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Bandejaunificada Extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'template', 'codegen_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('bandejaunificada_model');


        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        if ($this->data['user']) {
            define("ID_USUARIO", $this->data['user']->IDUSUARIO);
            define("ID_GRUPO", $this->data['user']->IDGRUPO);
            define("ID_REGIONAL", $this->data['user']->COD_REGIONAL);
        }
        $this->load->file(APPPATH . "controllers/verificarpagos.php", true);
        $sesion = $this->session->userdata;
        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);
        define("NOMBRE_USUARIO", $this->data['user']->NOMBRES . " " . $this->data['user']->APELLIDOS);
        $this->data['ruta_traza'] = base_url() . 'index.php/consultarprocesos/actualizatrazajuridico';
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['ruta_resolucion_prescripcion'] = base_url() . 'index.php/resolucionprescripcion/Listado_titulos';
        $this->data['ruta_expediente'] = base_url() . 'index.php/expedientes/proceso_coactivo';
        $this->data['ruta_liquidacion'] = base_url() . 'index.php/liquidaciones_credito/index';
        $this->data['ruta_nulidad'] = base_url() . 'index.php/nulidad/Verificar_Causales';
        $this->data['ruta_terminacion'] = base_url() . 'index.php/bandejaunificada/EnviarTerminacionProceso';
        $this->data['ruta_remisibilidad'] = base_url() . 'index.php/remisibilidad/Listado_titulos';
        $this->data['ruta_facilidad'] = base_url() . 'index.php/bandejaunificada/CrearFacilidadPago';
    }

    function index() {
        if ($this->ion_auth->logged_in()) {
            $cod_coactivo = $this->input->post('cod_coactivo') ? $this->input->post('cod_coactivo') : FALSE;
            // echo $cod_coactivo;die();
            $titulo = $this->input->post('cod_titulo') ? $this->input->post('cod_titulo') : FALSE;
            $this->data['consulta'] = $this->bandejaunificada_model->procesos_coactivos(ID_REGIONAL, ID_USUARIO, $cod_coactivo, $titulo);
            $this->data['ruta_traslado_judicial'] = base_url() . 'index.php/traslado/Crear_AutoTraslado';
            $this->template->load($this->template_file, 'bandeja/bandejaprocesos', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function ProcesosCoactivos() {
        if ($this->ion_auth->logged_in()) {
            $cod_coactivo = $this->input->post('cod_coactivo') ? $this->input->post('cod_coactivo') : FALSE;
            // echo $cod_coactivo;die();
            $titulo = $this->input->post('cod_titulo') ? $this->input->post('cod_titulo') : FALSE;
            $this->data['consulta'] = $this->bandejaunificada_model->procesos_coactivos(ID_REGIONAL, ID_USUARIO, $cod_coactivo, $titulo);
            $this->data['ruta_traslado_judicial'] = base_url() . 'index.php/traslado/Crear_AutoTraslado';
            $this->template->load($this->template_file, 'bandeja/bandejaprocesos', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function procesos() {
        if ($this->ion_auth->logged_in()) {
            $cod_coactivo = $this->input->post('cod_coactivo') ? $this->input->post('cod_coactivo') : FALSE;
            $titulo = $this->input->post('cod_titulo') ? $this->input->post('cod_titulo') : FALSE;
            $this->data['consulta'] = $this->bandejaunificada_model->procesos_coactivos(ID_REGIONAL, ID_USUARIO, $cod_coactivo, $titulo);
            $this->data['ruta_traslado_judicial'] = base_url() . 'index.php/traslado/Crear_AutoTraslado';
            $this->template->load($this->template_file, 'bandeja/bandejaprocesos', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Metodo que permite gestionar cada uno de los estados de la bandeja unificada */

    function Funcionarios() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
                $post = $this->input->post();
                // echo "<pre>";print_r($post); echo "</pre>";
                $cod_regional = $post['regional'];
                $respuesta = $post['cod_respuesta'];
                $cod_coactivo = $post['cod_proceso'];
                $cod_abogado = $post['cod_abogado'];
                $post['recepcion_id'] = $post['cod_proceso'];
                $regional = $this->bandejaunificada_model->regional($cod_regional);
                if ($respuesta):
                    $this->data['responsable'] = $this->bandejaunificada_model->consulta_responsable($respuesta);
                    $cargo = $this->data['responsable'][0]['IDCARGO'];

                    switch ($respuesta):
                        case 1332:
                        case 383:
                        case 384:
                            $cargo = 8;
                            break;
                        case 381:
                            $cargo = 7;
                            break;
                        case 382:
                            $cargo = 9;
                            break;
                    endswitch;

                    $url = base_url() . 'index.php/' . $this->data['responsable'][0]['URLGESTION'];
                    $responsable = $this->bandejaunificada_model->consulta_responsable($cod_regional);
                    switch ($cargo):
                        case '7'://SECRETARIO
                            echo "<li>" . strtoupper($regional['NOMBRE_SECRETARIO']) . "(Secretario)" . "</li>";
                            if (ID_SECRETARIO == ID_USUARIO):
                                switch ($respuesta):

                                    case 381 :
                                        $html_mcexistentes = ' <form name="form" id="form" method="post" target="_top" action="' . base_url() . 'index.php/verfpagosprojuridicos/auto' . '">
                                                            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                            <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                            <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                                            
                                                            </form>';
                                        echo $html_mcexistentes;
                                        break;
                                    default:
                                        $html = ' <form name="form" id="form" method="post" target="_top" action="' . $url . '">
                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                        <input type="hidden" name="cod_titulo" id="cod_titulo" value="' . $cod_coactivo . '">
                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                        <input type="hidden" name="cod_coactivo_prescripcion" id="cod_coactivo_prescripcion" value="' . $cod_coactivo . '">
                                        <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                        </form>';
                                        echo $html;
                                        break;
                                endswitch;


                            endif;
                            break;
                        case '8'://ABOGADO
                            if ($cod_abogado):
                                $datos = $this->bandejaunificada_model->abogado($cod_abogado);
                                echo "<li>" . strtoupper($datos['NOMBRES'] . " " . $datos['APELLIDOS']) . "(Abogado)" . "</li>";
                                if ($cod_abogado == ID_USUARIO):
                                    switch ($respuesta):
                                        case 193:
                                            $html_0 = '<form name="form" id="form" method="post" target="_top" action="' . $url . '">
                                                       <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                       <input type="hidden" name="cod_coactivo_traslado" id="cod_coactivo_traslado" value="' . $cod_coactivo . '">
                                                       <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                               <input type="hidden" name="cod_coactivo_prescripcion" id="cod_coactivo_prescripcion" value="' . $cod_coactivo . '">
                                                       <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                                       </form>';
                                            echo $html_0;
                                            break;
                                        case 173:
                                        case 886:
                                            $html_1 = ' <form name="form" id="form" method="post" target="_top" action="' . $url . '">
                                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                        <input type="hidden" name="nit" id="nit" value="' . $post['nit'] . '">
                                                        <input type="hidden" name="expediente" id="expediente" value="' . $post['expediente'] . '">
                                                        <input type="hidden" name="recepcion_id" id="recepcion_id" value="' . $post['recepcion_id'] . '">
                                                        <input type="hidden" name="cod_coactivo_prescripcion" id="cod_coactivo_prescripcion" value="' . $cod_coactivo . '">
                                                        <input type="hidden" name="concepto" id="concepto" value="' . $post['concepto'] . '">
                                                        <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                                        </form>';
                                            echo $html_1;
                                            break;
                                        case 204:
                                            //verifico si el proceso fue gestionado en cada uno de los procesos
                                            $mandamiento = $this->bandejaunificada_model->mandamiento($cod_coactivo);
                                            $medidas = $this->bandejaunificada_model->medidas($cod_coactivo);
                                            $acercamiento = $this->bandejaunificada_model->acercamiento($cod_coactivo);
                                            if ($mandamiento):
                                                $html_mc = '<form name="form" id="form" method="post" target="_top" action="' . base_url() . 'index.php/mandamientopago/nits' . '">
                                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                        <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Mandamiento">
                                                        </form>';
                                                echo $html_mc;
                                            endif;
                                            if ($medidas):
                                                $html_mp = '<form name="form" id="form" method="post" target="_top" action="' . base_url() . 'index.php/mcinvestigacion/abogado' . '">
                                                         <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                             
                                                         <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                        <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Mc_Investigacion">
                                                        </form>';
                                                echo $html_mp;
                                            endif;
                                            break;
                                        case 184:
                                            $html_acercamiento = ' <form name="form" id="form" method="post" target="_top" action="' . base_url() . 'index.php/acercamientopersuasivo/abogado' . '">
                                                            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                            <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                            <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Acercamiento">
                                                            
                                                            </form>';
                                            echo $html_acercamiento;
                                            break;

                                        case 1332://Medidas Cautelares Existentes
                                        case 381:
                                        case 383:
                                        case 384:    
                                            $html_mcexistentes = ' <form name="form" id="form" method="post" target="_top" action="' . base_url() . 'index.php/verfpagosprojuridicos/auto' . '">
                                                            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                            <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                            <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                                            
                                                            </form>';
                                            echo $html_mcexistentes;
                                            break;
                                        default:
                                            $html = ' <form name="form" id="form" method="post" target="_top" action="' . $url . '">
                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                        <input type="hidden" name="cod_titulo" id="cod_titulo" value="' . $cod_coactivo . '">
                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                        <input type="hidden" name="cod_coactivo_prescripcion" id="cod_coactivo_prescripcion" value="' . $cod_coactivo . '">
                                        <input type="hidden" name="cod_coactivo_traslado" id="cod_coactivo_traslado" value="' . $cod_coactivo . '">
                                        <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                        </form>';
                                            echo $html;
                                            break;
                                    endswitch;
                                endif;
                            endif;
                            break;
                        case '9'://COORDINADOR
                            echo "<li>" . strtoupper($regional['NOMBRE_COORDINADOR']) . "(Funcionario Ejecutor)" . "</li>";
                            if (ID_COORDINADOR == ID_USUARIO):
                                switch ($respuesta):
                                    case 382:
                                        $html_mcexistentes = ' <form name="form" id="form" method="post" target="_top" action="' . base_url() . 'index.php/verfpagosprojuridicos/auto' . '">
                                                            <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                                            <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                                            <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                                            
                                                            </form>';
                                        echo $html_mcexistentes;
                                        break;
                                    default:

                                        $html = ' <form name="form" id="form" method="post" target="_top" action="' . $url . '">
                                        <input type="hidden" name="cod_coactivo" id="cod_coactivo" value="' . $cod_coactivo . '">
                                         <input type="hidden" name="cod_titulo" id="cod_titulo" value="' . $cod_coactivo . '">    
                                        <input type="hidden" name="cod_respuesta" id="cod_respuesta" value="' . $respuesta . '">
                                        <input type="submit" class="btn btn-info" name="Gestionar" id="Gestionar"  value="Gestionar">
                                        </form>';
                                        echo $html;
                                        break;
                                endswitch;
                            endif;
                            break;
                        default :
                            if ($cod_abogado):
                                $datos = $this->bandejaunificada_model->abogado($cod_abogado);
                                echo "<li>" . $datos['NOMBRES'] . " " . $datos['APELLIDOS'] . "</li>";
                            endif;
                            break;
                    endswitch;
                endif;
            }else {

                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                echo '<script>' . "setTimeout('finalizar()', 1000);" . '</script>';
            }
        } else {
            echo '<script>' . "setTimeout('finalizar()', 1000);" . '</script>';
        }
    }

    function Remate() {
        /* Función que lista cada uno de los remates para un proceso coactivo */
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) {
                $cod_coactivo = $this->input->post('cod_coactivo') ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['remate'] = $this->bandejaunificada_model->detalle_remate($cod_coactivo);
                $this->template->load($this->template_file, 'bandeja/detalle_remate', $this->data);
                // $this->load->view('bandejaunificada/detalle_remate', $this->data);
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Area.</div>');
                echo '<script>' . "setTimeout('finalizar()', 1000);" . '</script>';
            }
        } else {
            echo '<script>' . "setTimeout('finalizar()', 1000);" . '</script>';
        }
    }

    function CrearFacilidadPago() {
        /* Función que crea una Facilidad de Pago para un proceso coactivo */
        if ($this->ion_auth->logged_in()) :
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) :
                $cod_coactivo = $this->input->post('cod_coactivo_facilidad') ? $this->input->post('cod_coactivo_facilidad') : FALSE;
                $ejecutado = $this->bandejaunificada_model->cabecera(201, $cod_coactivo);
                $datos = array('cod_proceso' => $cod_coactivo, 'nit' => $ejecutado['IDENTIFICACION'], 'tipo_respuesta' => 201,
                    'cod_regional' => $ejecutado['COD_REGIONAL'], 'cod_concepto' => $ejecutado['COD_CPTO_FISCALIZACION']);
                /* Debo verificar que la deuda del titulo no sea 0 */
                $titulos = $this->bandejaunificada_model->titulos_coactivo($cod_coactivo);
                $titulos_f = array();
                $a = 0;
                foreach ($titulos as $titulo):
                    foreach ($titulo as $dato):
                        $titulos_facilidad[$a] = $dato;
                        $a++;
                    endforeach;
                endforeach;
                $facilidad = $this->bandejaunificada_model->CrearFacilidadPago($datos, $titulos_facilidad);
                if ($facilidad):
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha creado la Facilidad de Pago para el ejecutado ' . $ejecutado['EJECUTADO'] . '</div>');
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe una Facilidad de Pago para el proceso coactivo .</div>');
                endif;
                redirect(base_url() . 'index.php/bandejaunificada');
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            endif;
        else :
            redirect(base_url() . 'index.php/auth/login');
        endif;
    }

    function EnviarTerminacionProceso() {
        /* Función que envia a terminación de proceso, los titulos de un proceso coactivo */
        /*         * Se realiza solo un auto para cada proceso coactivo         */
        if ($this->ion_auth->logged_in()) :
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/procesos')) :
                $cod_coactivo = $this->input->post('cod_coactivo_liquidacion') ? $this->input->post('cod_coactivo_liquidacion') : FALSE;
                $titulos = $this->bandejaunificada_model->titulos_coactivo($cod_coactivo);
                $titulos_terminacion = array();
                $a = 0;
                // print_r( $titulos);
                foreach ($titulos as $titulo):
                    //   print_r( $titulo);
                    foreach ($titulo as $dato):
                        /* Verificar si un titulo ya tiene un auto de cierre creado */
                        //  print_r($dato);
                        $datos = array('TITULO' => $dato, 'COD_PROCESO' => $cod_coactivo);
                        $existe = $this->bandejaunificada_model->AutoTerminacionTitulo($datos);
                        // print_r($existe);
                        // echo "<br>";
                        if (empty($existe) || $existe == FALSE):
                            $titulos_terminacion[$a] = $dato;
                            $a++;
                        endif;
                    endforeach;
                endforeach;
                //print_r($titulos_terminacion); die();
                if (count($titulos_terminacion) > 0):

                    $this->verificarpagos = new verificarpagos();
                    $resultado = $this->verificarpagos->crearAutosCierre($cod_coactivo, $titulos_terminacion);
                    if ($resultado):
                        /* Actualizo el proceso_coactivo */
                        $actualizar = $this->bandejaunificada_model->ActualizaProcesoCoactivo($cod_coactivo);
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se creo el auto de cierre.</div>');
                    else:
                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No se ha realizado el auto de cierre, confirme la información nuevamente</div>');

                    endif;
                else:
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Ya existe un auto de terminación para el proceso.</div>');

                endif;
                redirect(base_url() . 'index.php/bandejaunificada');
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            endif;
        else :
            redirect(base_url() . 'index.php/auth/login');
        endif;
    }

    function ListaTerminacionProceso() { /* Función que lista los titulos que estan en terminación de proceso para un proceso coactivo */
        if ($this->ion_auth->logged_in()) :
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('bandejaunificada/index')) :

                $this->data['consulta'] = $this->bandejaunificada_model->ListadoTerminacion($cod_coactivo);
                $this->template->load($this->template_file, 'bandeja/terminacion_procesos_list', $this->data);
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');

            endif;
        else :
            redirect(base_url() . 'index.php/auth/login');
        endif;
    }

    function ListaProcesos() {
        if ($this->ion_auth->logged_in()) {
            $cod_coactivo = $this->input->post('cod_coactivo') ? $this->input->post('cod_coactivo') : FALSE;
            $titulo = $this->input->post('cod_titulo') ? $this->input->post('cod_titulo') : FALSE;
            $this->data['consulta'] = $this->bandejaunificada_model->Procesos(ID_REGIONAL, ID_USUARIO, $cod_coactivo, $titulo);
            $this->data['ruta_traslado_judicial'] = base_url() . 'index.php/traslado/Crear_AutoTraslado';
            $this->template->load($this->template_file, 'bandeja/procesos', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}

/* End  bandejaunificada.php */
?>

