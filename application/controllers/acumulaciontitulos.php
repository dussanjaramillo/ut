<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acumulaciontitulos extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->library('form_validation');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('acumulacion_model', '', TRUE);
        $this->load->model('consultartitulos_model', '', TRUE);
        $this->load->model('numeros_letras_model');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.de|faults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        define("ABOGADO", '43');
        define("SECRETARIO", '41');
        define("COORDINADOR", '42');
        define("DIRECTOR", '61');

        define("NOMBRE_ABOGADO", 'Abogado coac');
        define("NOMBRE_SECRETARIO", 'Secretario coa');
        define("NOMBRE_COORDINADOR", 'Coordinador coa');
        define("NOMBRE_DIRECTOR", 'Director Regional');

        $this->data['user'] = $this->ion_auth->user()->row();
        define("COD_USUARIO", $this->data['user']->IDUSUARIO);
        define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);

        define("MENSAJE_TITULOS", "No existen titulos para este proceso");
        define("TITULO_COMPLETO", "886");
        define("ASIGNAR_ABOGADO", "173");
        define("TITULOS_ACUMULADOS", "1325");




        $this->data['message'] = $this->session->flashdata('message');
    }

    function index() {
        $this->ListadoEmpresas();
    }

    function Traza_Proceso($cod_fiscalizacion, $cod_respuesta, $comentarios) {
        /*
         * FUNCION DE TRAZA
         */
        $traza = $this->traslado_model->get_titulotrazar($cod_fiscalizacion);
        $gestion = $this->traslado_model->get_trazagestion($cod_respuesta);
        $this->datos['idgestioncobro'] = trazar($gestion["COD_TIPOGESTION"], $gestion["COD_RESPUESTA"], $cod_fiscalizacion, $traza["NIT_EMPRESA"], $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios);
        $idgestioncobro = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
        return $idgestioncobro;
    }

    /*
     * MENU PARA REALIZAR LAS GESTIONES DEL ABOGADO DE COBRO COACTIVO
     */

    function ListadoEmpresas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('acumulaciontitulos/ListadoEmpresas')) {
                $this->template->set('title', 'Acumulacion de Titulos');
                $this->data['estados_seleccionados'] = $this->acumulacion_model->get_empresasrecepcion();
                $this->data['grupo'] = 'Abogado de Cobro Coactivo';
                $this->template->load($this->template_file, 'acumulacion/empresasrecepcion_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function ListadoAcumular() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('cod_titulo')) {
                $this->template->set('title', 'Acumulacion de Titulos');
                $cod_titulo = $this->input->post('cod_titulo');
                $proceso = $this->consultartitulos_model->get_codprocesojuridico($cod_titulo);
                $empresa = $proceso['IDENTIFICACION'];
                $proximo_prescribir = $proceso['PRESCRIPCION'];
                $concepto = $proceso['CONCEPTO'];
                $this->data['titulos_acumulados'] = $this->acumulacion_model->cabecera_titulo($cod_titulo);
                $this->data['encabezado'] = $this->acumulacion_model->encabezado($cod_titulo);
                $this->data['estados_seleccionados'] = $this->acumulacion_model->get_titulos($empresa, $proximo_prescribir,$concepto);             
                $this->data['grupo'] = 'Abogado de Cobro Coactivo';
                $this->template->load($this->template_file, 'acumulacion/titulosrecepcion_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function Acumular_titulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->input->post('id_titulos')) {
                $this->template->set('title', 'Acumulacion de Titulos');
                $titulos_acumular = $this->input->post('id_titulos');
                $titulos = explode("-", $titulos_acumular);
                $indice = 0;
                $datos_titulos = '';
                for ($i = 1; $i < sizeof($titulos); $i++) {
                    $contador = 0;
                    for ($j = 1; $j < sizeof($titulos); $j++) {
                        if ($titulos[$i] == $titulos[$j]) {
                            $contador++;
                        }
                    }
                    if (($contador % 2) != 0) {
                        $datos_titulos[$indice] = $titulos[$i];
                        $indice++;
                    }
                }
                $proceso = $this->consultartitulos_model->get_codprocesojuridico($datos_titulos[0]);
                /*
                 * CON EL CODIGO INSERTADO SE ACTUALIZA EL COD_PJ
                 */
                $dato['ABOGADO'] = COD_USUARIO;
                $dato['COD_RESPUESTA'] = TITULOS_ACUMULADOS;
                $dato['IDENTIFICACION'] = $proceso['IDENTIFICACION'];
                $dato['PROCEDENCIA'] = $proceso['PROCEDENCIA']; //-1 MISIONAL -2 NO MISIONAL
                $dato['ACUMULACION'] = '1'; //-0 NO HAY ACUMULACION -1 HAY ACUMULACION
                $cod_recepciontitulos = $this->acumulacion_model->insertar_acumulacion($dato);
                $cod_pj = $proceso['REGIONAL'] . '-' . $proceso['OBLIGACION'] . '-' . $proceso['NATURALEZA'] . '-' . $proceso['COBRO'] . '-' . $cod_recepciontitulos->COD_PROCESO_COACTIVO . '-00';
                /*
                 * ACTUALIZAR CODIGO DE PROCESO JURIDICO
                 */
                $datos_pj['COD_PROCESO_COACTIVO'] = $cod_recepciontitulos->COD_PROCESO_COACTIVO;
                $datos_pj['COD_PROCESOPJ'] = $cod_pj;
                $this->consultartitulos_model->actualizacion_recepcion($datos_pj);
                /*
                 * INSERTAR TITULOS ACUMLADOS
                 */
                $acumulacion_coactiva['COD_PROCESO_COACTIVO'] = $cod_recepciontitulos->COD_PROCESO_COACTIVO;
                $acumulacion_coactiva['COD_ESTADO_TITULO'] = 2; //activo
                for ($i = 0; $i < sizeof($datos_titulos); $i++) {
                    $acumulacion_coactiva['COD_RECEPCIONTITULO'] = $datos_titulos[$i];
                    $this->acumulacion_model->insertar_titulosacumulacion($acumulacion_coactiva); //condicionar
                    /*
                     * ACTUALIZAR CADA TITULO
                     */
                    $datos['COD_RECEPCIONTITULO'] = $datos_titulos[$i];
                    $datos['COD_TIPORESPUESTA'] = TITULOS_ACUMULADOS; //titulo no preinscrito o vigente                            
                    $this->consultartitulos_model->actualizacion_estado_titulo($datos);
                }
                redirect(base_url() . 'index.php/bandejaunificada/procesoscoactivos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta ?rea.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
