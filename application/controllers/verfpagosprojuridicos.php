<?php

/*
 * 
 * Desarrollador					:	Felipe Camacho
 * Fecha creación					:	01/07/2014
 * Clase									:	verfpagosprojuridicos
 * Requerimiento No.			:	RQ25_CU002 //1015418621
 *
 */

class Verfpagosprojuridicos extends MY_Controller {

    private $numero_proceso = '';
    private $cod_asignado = '';
    private $cod_creador = '';
    private $fecha_creacion = '';
    private $cod_estadoAuto = '';
    private $limit_numrows = 20;
    private $idgrupo = '';
    private $cod_estadoPermitido = '';
    private $cod_pasoEstado = '';
    private $controller = 'verfpagosprojuridicos';
    private $docs = './uploads/verfpagosprojuridicos/docs_autosjuridicos/';
    private $docs_firmados = './uploads/verfpagosprojuridicos/docs_autosjuridicos_firmados/';
    private $templates = './uploads/templates/';
    private $cod_avaluo = NULL;
    private $cod_proceso_remate = NULL;

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation', 'tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'template', 'traza_fecha_helper', 'traza_fecha'));
        $this->load->model($this->controller . '_model', '', TRUE);
        $this->load->model('plantillas_model');
        $this->data['custom_error'] = '';
        $this->load->library('tcpdf/tcpdf.php', 'libupload');
        //Cargamos los javascript nesesarios
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/tinymce/tinymce.min.js'
        );
        //Cargamos las hojas de estilos nesesariass
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
        $sesion = $this->session->userdata;

        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);
        if ($this->ion_auth->logged_in()):
            $this->data['user'] = $this->ion_auth->user()->row();
            if ($this->data['user']) {
                define("ID_USUARIO", $this->data['user']->IDUSUARIO);
                define("COD_REGIONAL", $this->data['user']->COD_REGIONAL);
            }endif;
    }

    function index() {
        //verificamos login
        if ($this->ion_auth->logged_in()) :
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('verfpagosprojuridicos/index') || $this->ion_auth->in_menu('bandejaunificada/index')) :
                $this->data['limit_numrows'] = $this->limit_numrows;
                $this->data['message'] = $this->session->flashdata('message');
                $cod_coactivo = ($this->input->post('cod_coactivo')) ? $this->input->post('cod_coactivo') : FALSE;
                $this->data['registros'] = $this->verfpagosprojuridicos_model->listAutos(false, $cod_coactivo);
                $this->template->load($this->template_file, $this->controller . '/lista', $this->data);

            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            endif;
        else :
            redirect(base_url() . 'index.php/auth/login');
        endif;
    }

    function lista_procesos($post = FALSE) {

        //verificamos login
        if ($this->ion_auth->logged_in()) :
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('verfpagosprojuridicos/index') || $this->ion_auth->in_menu('bandejaunificada/index')) :
                //print_r($post);
                $cod_coactivo = $post['cod_coactivo'] ? $post['cod_coactivo'] : FALSE;

                $this->data['registros'] = $this->verfpagosprojuridicos_model->listAutos(false, $cod_coactivo);
                $this->data['cod_respuesta'] = $post['cod_respuesta'];
                $this->template->load($this->template_file, $this->controller . '/lista', $this->data);

            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            endif;
        else :
            redirect(base_url() . 'index.php/auth/login');
        endif;
    }

    function guarda_expediente($post) {
        $CI = & get_instance();
        $CI->load->model("Expediente_model");
        $model = new Expediente_model();
        $respuesta = $post['tipo_respuesta'];
        $nombre = 'Requerimiento Acercamiento';
        $ruta = $post['ruta'];
        $radicado = $post['numero_radicado'];
        $fecha_radicado = $post['fecha_radicado'];
        $tipo_expediente = $post['tipo_exp'];
        $subproceso = $post['sub_proceso'];
        $cod_coactivo = $post['cod_proceso'];
        $expediente = $model->agrega_expediente($respuesta, $nombre, $ruta, $radicado, $fecha_radicado, $tipo_expediente, $subproceso, ID_USUARIO, $cod_coactivo); //Guarda en la tabla expediente
    }

    function datatable() {
        if ($this->ion_auth->logged_in()) {
            $this->verfpagosprojuridicos_model->set_sSearch();
            $this->verfpagosprojuridicos_model->set_iDisplayStart();
            $this->verfpagosprojuridicos_model->set_limit_numrows($this->limit_numrows);
            $idusuario = $this->verfpagosprojuridicos_model->set_idusuario();
            $this->verfpagosprojuridicos_model->set_idgrupo();
            $data['registros'] = $this->verfpagosprojuridicos_model->listAutos(false, $proceso);
            $total = $this->verfpagospjuridicos_model->listAutos(true, $proceso);

            if (!empty($data['registros'])) :
                foreach ($data['registros'] as $value) :
                    if ($value->ID_ASIGNADO == $idusuario) :
                        $value->BOTON = ' <a href="javascript:void(0)" onclick="ver(\'' . $value->NUM_AUTOGENERADO . '\')" ' .
                                'class="btn btn-small" data-toggle="modal" data-target="#modal" data-keyboard="false" ' .
                                'data-backdrop="static" title="Gestionar"><i class="fa fa-eye-slash"></i></a>';
                    else :
                        $value->BOTON = '';
                    endif;
                endforeach;
            endif;

            echo json_encode(array('aaData' => $data['registros'],
                'iTotalRecords' => $total,
                'iTotalDisplayRecords' => $total,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function auto() {
        if ($this->ion_auth->logged_in()) {

            $post = $this->input->post();
            //  echo "hola"; print_r($post); die();
            $url_seg = $this->uri->segment(3);
            //$cod_fiscalizacion = $this->input->post('COD_FISCALIZACION');
            $cod_gestion = $this->input->post('COD_GESTION');
            $num_autogenerado = ( $this->input->post('NUM_AUTOGENERADO') != '' ) ? $this->input->post('NUM_AUTOGENERADO') : $url_seg;
            $cod_tipo_auto = 1;
            $cod_tipo_proceso = 1;
            $cod_gestioncobro = '';
            $documento = '';
            $codplantilla = '';
            $plantilla = NULL;
            $data = array();
            $auto = NULL;
            $fiscalizacion = NULL;

            //$idgrupo = $this->verfpagosprojuridicos_model->set_idgrupo();
            $idusuario = $this->verfpagosprojuridicos_model->set_idusuario();

            $asignar = "";
            //    print_r($post); die();    
            if (!empty($num_autogenerado)) :
                $this->data['cod_respuesta'] = $post['cod_respuesta'];
                $auto = $this->verfpagosprojuridicos_model->retrievetAuto($num_autogenerado);
                if (!empty($auto)) :
                    $this->data['subir_archivo'] = false;
//                    if ($auto->COD_RESPUESTA == 1137) :
//                        $this->data['subir_archivo'] = true;
//                    elseif ($auto->COD_GESTION == 440 and $auto->COD_RESPUESTA == 1138) :
//                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto de Terminación y Cierre al que hace referencia ya esta cerrado y el archivo subido.</div>');
//                        redirect(base_url() . 'index.php/verfpagosprojuridicos/index');
//                    endif;
                    switch ($post['cod_respuesta']):
                        case 1132://Asigna al Secretario.
                        case 1332:
                            $this->data['secretario'] = false;
                            $cod_grupo = 41;
                            $asignar = "secretario si el auto esta listo";
                            $this->data['secretarios'] = array('idsecretario' => $this->session->userdata['id_secretario']);
                            break;
                        /* Se devuelve a abogado */
                        case 1134:
                        case 383:
                            $this->data['secretario'] = false;
                            break;
                        /* Asignación al secretario */
                        case 1133:
                        case 381:
                            $this->data['secretario'] = true;
                            $cod_grupo = 62;
                            $asignar = "Ejecutor si aprueba el auto";
                            $this->data['secretarios'] = array($auto->COD_ABOGADO => $auto->NOMBRE_ABOGADO);
                            break;
                        /* Asignación al Funcionario Ejecutor */
                        case 1135:
                        case 382:

                            $this->data['secretario'] = true;
                            break;
                        case 1137:
                        case 384:
                            $this->data['subir_archivo'] = true;
                            $cod_grupo = 41;
                            $asignar = "abogado";
                            $this->data['secretario'] = false;
                            $this->data['secretarios'] = array('idsecretario' => $this->session->userdata['id_secretario']);

                            break;
                        case 1138://Se verifica si existen medidas cautelares:

                            break;
                    endswitch;

                    $file_txt = $this->docs . $auto->NOMBRE_DOC_GENERADO;
                    $cod_tipo_auto = $auto->COD_TIPO_AUTO;
                    $cod_tipo_proceso = $auto->COD_TIPO_PROCESO;
                    $codplantilla = '';
                    $cod_fiscalizacion = $auto->COD_FISCALIZACION;
                    if ($auto->NOMBRE_DOC_GENERADO):
                        if (file_exists(realpath($file_txt))) :
                            $documento = read_template($file_txt);
                        else :
                            $documento = "";
                        endif;
                    else:$documento = '';
                    endif;

                    /* if (!empty($cod_fiscalizacion)) :
                      $fiscalizacion = $this->verfpagosprojuridicos_model->retriveFiscalizacion($cod_fiscalizacion);
                      else : */
                    //$fiscalizacion->COD_FISCALIZACION = "";
                    //endif;
                    //if ($auto == NULL) :
                    $cod_gestioncobro = $auto->COD_PROCESO_COACTIVO; //$this->verfpagosprojuridicos_model->retriveGestionActual($cod_fiscalizacion);
                    /* else :
                      $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                      endif; */


                    $cod_regional = (($this->ion_auth->user()->row()->COD_REGIONAL != NULL) &&
                            ($this->ion_auth->user()->row()->COD_REGIONAL != '')) ?
                            $this->ion_auth->user()->row()->COD_REGIONAL :
                            '';

                    $this->data['plantillas'] = $this->verfpagosprojuridicos_model->retrieveAllPlantillas();
                    $this->data['auto'] = $auto;
                    $this->data['fiscalizacion'] = $fiscalizacion;
                    //   $this->data['id_grupo'] = $idgrupo;
                    $this->data['estados'] = ""; //$this->verfpagosprojuridicos_model->listEstados($where_estado);
                    $this->data['cod_tipo_auto'] = $cod_tipo_auto;
                    $this->data['cod_tipo_proceso'] = $cod_tipo_proceso;
                    $this->data['idusuario'] = $idusuario;
                    $this->data['cod_avaluo'] = $this->cod_avaluo;
                    $this->data['documento'] = $documento;
                    $this->data['cod_gestioncobro'] = $cod_gestioncobro;
                    $this->data['cod_procesoremate'] = (isset($this->cod_procesoremate)) ? $this->cod_procesoremate : "";
                    $this->data['asignar'] = $asignar;
                    $this->data['action_label'] = ($auto != NULL) ? 'Actualizar' : 'Agregar';

                    $this->data['proceso'] = $this->verfpagosprojuridicos_model->proceso($cod_gestioncobro);
                    $this->data['cod_proceso'] = $cod_gestioncobro;
                    if ($post['cod_respuesta'] == 1138):
                        $this->template->load($this->template_file, $this->controller . '/verifMedidas', $this->data);
                    else:
                        $this->template->load($this->template_file, $this->controller . '/form', $this->data);
                    endif;
                else:
                    // $this->session->set_flashdata('message', '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área o la información no existe.</div>');
                    $this->lista_procesos($post);
                endif;
            else : //echo "hola2"; print_r($post); die();
                $this->lista_procesos($post);
            endif;
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function delete($num_generado) {
        if ($this->ion_auth->logged_in()) :
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('verfpagosprojuridicos/index')) :
                $this->verfpagosprojuridicos_model->deleteAuto($num_generado);
                $this->data['message'] = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se elimino correctamente.</div>';
                $this->data['limit_numrows'] = $this->limit_numrows;
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, $this->controller . '/lista', $this->data);
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            endif;
        else :
            redirect(base_url() . 'index.php/auth/login');
        endif;
    }

    function getAjaxPlantilla() {
        $cod_plantilla = $this->uri->segment(3);
        $cod_fiscalizacion = $this->uri->segment(4);

        $plantilla = $this->verfpagosprojuridicos_model->retrievePlantilla($cod_plantilla);
        $fiscalizacion = $this->verfpagosprojuridicos_model->retriveFiscalizacion($cod_fiscalizacion);
        if ($fiscalizacion):


            $file_txt = $this->templates . $plantilla->ARCHIVO_PLANTILLA;

            $array_tags = array();
            $array_tags['nit_empresa'] = $fiscalizacion->NIT_EMPRESA;
            $array_tags['nombre_empresa'] = $fiscalizacion->NOMBRE_EMPRESA;
            $array_tags['direccion_empresa'] = $fiscalizacion->DIRECCION;
            $array_tags['representante_legal'] = $fiscalizacion->REPRESENTANTE_LEGAL;
            $array_tags['nombre_consepto'] = $fiscalizacion->NOMBRE_CONCEPTO;


            $array_tags['COD_FISCALIZACION'] = $fiscalizacion->COD_FISCALIZACION;
            $array_tags['PERIODO_INICIAL'] = $fiscalizacion->PERIODO_INICIAL;
            $array_tags['PERIODO_FINAL'] = $fiscalizacion->PERIODO_FINAL;

        //   echo template_tags($file_txt, $array_tags);
        endif;
    }

    function save() {
        $cod_proceso = $this->input->post('COD_PROCESO_COACTIVO');
        $timestamp = now();
        $timezone = 'UM5';
        $daylight_saving = FALSE;
        $datestring = "%d/%m/%Y";
        $now = gmt_to_local($timestamp, $timezone, $daylight_saving);
        $fecha = mdate($datestring, $now);
        $auto = NULL;
        $num_autogenerado = trim($this->input->post('NUM_AUTOGENERADO'));

        $name_documento = '';
        $name_documento_firmado = '';
        $subir_doc = false;
        $status_upload_doc_fir = true;
        $cod_respuesta = $this->input->post('cod_respuesta');
        $genera = true;
        $documento = trim($this->input->post('documento'));
        $auto = $this->verfpagosprojuridicos_model->retrievetAuto($num_autogenerado);
        if (empty($auto->NOMBRE_DOC_GENERADO)) {
            if (!file_exists($this->docs)) :
                mkdir($this->docs, 0777, true);
            endif;
            $name_documento = create_template($this->docs, $documento);
        } else {
            $file_txt = $this->docs . $auto->NOMBRE_DOC_GENERADO;

            if (file_exists(realpath($file_txt))) :
                $content_txt = read_template($file_txt);
                if ($documento != trim($content_txt)) :
                    $fp = fopen($file_txt, "w");
                    fputs($fp, $documento);
                    fclose($fp);
                endif;
                $name_documento = $auto->NOMBRE_DOC_GENERADO;
            else :
                $name_documento = create_template($this->docs, $documento);
            endif;
        }

        $idusuario = $this->verfpagosprojuridicos_model->set_idusuario();
        $idgrupo = $this->verfpagosprojuridicos_model->set_idgrupo();
        $asignado_a = ($this->input->post('ASIGNADO_A') == 0) ? $idusuario : $this->input->post('ASIGNADO_A');
        $devolver = $this->input->post('DEVOLVER_A');
        // print_r($name_documento);die();
        if (empty($devolver)) :
            $devolver = "N";
        endif;

        $cod_gestioncobro = (!isset($COD_GESTIONCOBRO)) ? $auto->COD_GESTIONCOBRO : $COD_GESTIONCOBRO;
        $revisado_por = $revisado = $aprobado_por = $aprobado = "";
        $button = $this->input->post("button");
        //  echo $idusuario;die();
        if ($asignado_a == $idusuario) :
            echo "1a";
            if (ID_COORDINADOR == $idusuario) :
                echo "1b";
                if ($devolver == "N") :
                    if ($button != "PDF" and $button != "Imprimir") :
                        $asignado_a = $auto->COD_ABOGADO;
                        //$cod_gestioncobro = trazar(438, 1134, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                        $cod_gestioncobro = trazarProcesoJuridico(438, 1134, "", $cod_proceso, "", "", "");
                    else :
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                else :
                    if ($button != "PDF" and $button != "Imprimir") :
                        $revisado_por = $idusuario;
                        $revisado = 1;
                        // $cod_gestioncobro = trazar(438, 1135, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                        $cod_gestioncobro = trazarProcesoJuridico(438, 1135, "", $cod_proceso, "", "", "");

                    else :
                        $revisado_por = $auto->REVISADO_POR;
                        $revisado = $auto->REVISADO;
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                endif;
            elseif (ID_SECRETARIO == $idusuario) :
                echo "1c";
                if ($devolver == "N") :
                    if ($button != "PDF" and $button != "Imprimir") :
                        $asignado_a = $auto->COD_ABOGADO;
                        // $cod_gestioncobro = trazar(439, 1136, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                        $cod_gestioncobro = trazarProcesoJuridico(439, 1136, "", $cod_proceso, "", "", "");

                    else :
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                else :
                    if ($button != "PDF" and $button != "Imprimir") :
                        $asignado_a = $auto->COD_ABOGADO;
                        $aprobado_por = $idusuario;
                        $aprobado = 1;
                        $cod_gestioncobro = trazarProcesoJuridico(439, 1137, "", $cod_proceso, "", "", "");
                    //$cod_gestioncobro = trazar(439, 1137, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                    else :
                        $aprobado_por = $auto->APROBADO_POR;
                        $aprobado = $auto->APROBADO;
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                endif;
            else :
                echo "1b";
                if ($auto->COD_GESTION == 439 and $auto->COD_RESPUESTA == 1137) :
                    $revisado_por = $auto->REVISADO_POR;
                    $aprobado_por = $auto->APROBADO_POR;
                    $aprobado = $revisado = 1;
                    $fecha_doc_firmado = true;
                    $file = $this->do_upload();
                    if (!isset($file['error'])) :
                        $name_documento_firmado = $file['upload_data']['file_name'];
                        $fecha_radicado = $this->input->post('fecha_doc_radicado');
                        $num_radicado = $this->input->post('numradicado');
                    endif;
                    if ($button != "PDF" and $button != "Imprimir") :
                        $cod_gestioncobro = trazarProcesoJuridico(440, 1138, "", $cod_proceso, "", "", "");
                    //  $cod_gestioncobro = trazar(440, 1138, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                    else :
                        $asignado_a = $auto->ASIGNADO_A;
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                else :
                    $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                endif;
            endif;
        elseif ($asignado_a != $idusuario) :
            echo "2a";
            //if (ID_COORDINADOR != $idusuario and ID_SECRETARIO != $idusuario) :
            if ($cod_respuesta == 1132):
                echo
                // $cod_gestioncobro = trazar(437, 1133, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                $cod_gestioncobro = trazarProcesoJuridico(437, 1133, "", $cod_proceso, "", "", "");
            //elseif (ID_COORDINADOR == $idusuario) :
            elseif ($cod_respuesta == 1133):
                if ($devolver == "N") :
                    if ($button != "PDF" and $button != "Imprimir") :
                        $asignado_a = $auto->COD_ABOGADO;
                        $cod_gestioncobro = trazarProcesoJuridico(438, 1134, "", $cod_proceso, "", "", "");
                    //$cod_gestioncobro = trazar(438, 1134, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                    else :
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                else :
                    if ($button != "PDF" and $button != "Imprimir") :
                        $revisado_por = $idusuario;
                        $revisado = 1;
                        $cod_gestioncobro = trazar(438, 1135, $auto->COD_FISCALIZACION, $auto->NIT_EMPRESA, "S");
                    else :
                        $revisado_por = $auto->REVISADO_POR;
                        $revisado = $auto->REVISADO;
                        $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
                    endif;
                endif;
            endif;
        endif;
        echo $cod_gestioncobro;
        die();
        if (is_array($cod_gestioncobro)) :
            $cod_gestioncobro = $cod_gestioncobro['COD_GESTION_COBRO'];
        endif;
        $data = array("ASIGNADO_A" => $asignado_a, "FECHA_GESTION" => $fecha, "COMENTARIOS" => $this->input->post('COMENTARIOS'),
            "REVISADO" => $revisado, "REVISADO_POR" => $revisado_por, "APROBADO" => $aprobado,
            "APROBADO_POR" => $aprobado_por, "NOMBRE_DOC_GENERADO" => $name_documento,
            "NOMBRE_DOC_FIRMADO" => $name_documento_firmado, "COD_GESTIONCOBRO" => $cod_gestioncobro,
            "FECHA_DOC_FIRMADO" => ($fecha_doc_firmado == true) ? $fecha_radicado : "", "NUM_RESOLUCION" => $num_radicado);
        //echo "<pre>";print_r($data);exit();
        $cod_fiscalizacion = $this->input->post('COD_PROCESO_COACTIVO');
        $save = $this->verfpagosprojuridicos_model->saveAuto($data, $num_autogenerado, $cod_fiscalizacion);
        if ($save == false) :
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área o la información no existe.</div>');
        elseif ($save == true) :
            if ($button == "PDF" || $button == "Imprimir") :
                $print = ($button == "Imprimir") ? true : false;
                $name = "AutoCierreFiscalizacion" . $cod_fiscalizacion . "-" . $auto->NIT_EMPRESA;
                createPdfTemplateOuput($name, $documento, $print);
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se guardo el Auto con éxito.</div>');
            endif;
        endif;
        redirect(base_url() . 'index.php/bandejaunificada/index');
    }

    /**
     * Funcion general de codeigniter para subir archivos, en este caso solo imágenes
     *
     * @return none
     * @param string $imagen
     */
    private function do_upload() {
        $config['upload_path'] = './uploads/verfpagosprojuridicos/docs_firmados/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '5120';
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

    function save2() {
        $post = $this->input->post();
        print_r($post);
        $cod_proceso = $this->input->post('COD_PROCESO_COACTIVO');
        $timestamp = now();
        $timezone = 'UM5';
        $daylight_saving = FALSE;
        $datestring = "%d/%m/%Y";
        $now = gmt_to_local($timestamp, $timezone, $daylight_saving);
        $fecha = mdate($datestring, $now);
        $auto = NULL;
        $num_autogenerado = trim($this->input->post('NUM_AUTOGENERADO'));
        $name_documento = '';
        $name_documento_firmado = '';
        $subir_doc = false;
        $status_upload_doc_fir = true;
        $cod_respuesta = $this->input->post('cod_respuesta');
        $genera = true;
        $documento = trim($this->input->post('documento'));
        $idusuario = $this->verfpagosprojuridicos_model->set_idusuario();
        $idgrupo = $this->verfpagosprojuridicos_model->set_idgrupo();
        $asignado_a = ($this->input->post('ASIGNADO_A') == 0) ? $idusuario : $this->input->post('ASIGNADO_A');
        $devolver = $this->input->post('DEVOLVER_A');
        $auto = $this->verfpagosprojuridicos_model->retrievetAuto($num_autogenerado);
        if (empty($auto->NOMBRE_DOC_GENERADO)) {
            if (!file_exists($this->docs)) :
                mkdir($this->docs, 0777, true);
            endif;
            $name_documento = create_template($this->docs, $documento);
        } else {
            $file_txt = $this->docs . $auto->NOMBRE_DOC_GENERADO;

            if (file_exists(realpath($file_txt))) :
                $content_txt = read_template($file_txt);
                if ($documento != trim($content_txt)) :
                    $fp = fopen($file_txt, "w");
                    fputs($fp, $documento);
                    fclose($fp);
                endif;
                $name_documento = $auto->NOMBRE_DOC_GENERADO;
            else :
                $name_documento = create_template($this->docs, $documento);
            endif;
        }

        switch ($post['cod_respuesta']):
            /* Genera auto cierre */
            case 1132:
            case 1134:
            case 1136:
                $cod_gestioncobro = trazarProcesoJuridico(437, 1133, "", $cod_proceso, "", "", "");
                break;
            /* Genera auto levantamiento medidas */
            case 1332:
            case 383:
                $cod_gestioncobro = trazarProcesoJuridico(156, 381, "", $cod_proceso, "", "", "");
                break;
            case 381:
                if ($devolver == "N") ://Asigna al funcionario ejecutor
                    $cod_gestioncobro = trazarProcesoJuridico(256, 383, "", $cod_proceso, "", "", "");
                else:

                    $cod_gestioncobro = trazarProcesoJuridico(156, 382, "", $cod_proceso, "", "", "");
                endif;
                break;
            case 382:
                if ($devolver == "N") ://Devuelve
                    $cod_gestioncobro = trazarProcesoJuridico(156, 383, "", $cod_proceso, "", "", "");
                else://Aprueba
                    $cod_gestioncobro = trazarProcesoJuridico(156, 384, "", $cod_proceso, "", "", "");
                endif;
                break;
            case 1133:
                if ($devolver == "N") :
                    $cod_gestioncobro = trazarProcesoJuridico(438, 1134, "", $cod_proceso, "", "", "");
                else:
                    $cod_gestioncobro = trazarProcesoJuridico(438, 1135, "", $cod_proceso, "", "", "");
                endif;
                break;

            case 1135:
                if ($devolver == "N") :
                    $cod_gestioncobro = trazarProcesoJuridico(439, 1136, "", $cod_proceso, "", "", "");
                else:
                    $cod_gestioncobro = trazarProcesoJuridico(439, 1137, "", $cod_proceso, "", "", "");
                endif;

                break;
            case 1137:
            case 384:
                $revisado_por = $auto->REVISADO_POR;
                $aprobado_por = $auto->APROBADO_POR;
                $aprobado = $revisado = 1;
                $fecha_doc_firmado = true;
                $file = $this->do_upload();
                if (!isset($file['error'])) :
                    $name_documento_firmado = $file['upload_data']['file_name'];
                    $fecha_radicado = $this->input->post('fecha_doc_radicado');
                    $num_radicado = $this->input->post('numradicado');
                endif;
                if ($post['cod_respuesta'] == 384):

                    $post['cod_proceso'] = $cod_proceso;
                    $cod_gestioncobro = trazarProcesoJuridico(440, 1138, "", $cod_proceso, "", "", "");
                    $cerrar_proceso = $this->verfpagosprojuridicos_model->cerrarProceso($post);

                else:
                    $cod_gestioncobro = trazarProcesoJuridico(440, 1138, "", $cod_proceso, "", "", "");

                endif;
                /*Guarda en el expediente */
                $post['tipo_exp'] = '1';
                $post['tipo_respuesta'] = $post['cod_respuesta'];
                $post['ruta'] = $file_txt;
                $post['numero_radicado'] = $post['numradicado'];
                $post['fecha_radicado'] = $post['fecha_doc_radicado'];
                $post['sub_proceso'] = 1;
                $cod_coactivo = $post['cod_proceso'];
                $expediente = $this->guarda_expediente($post);
                break;
        endswitch;
        if (is_array($cod_gestioncobro)) :
            $cod_gestioncobro = $cod_gestioncobro['COD_GESTION_COBRO'];
        endif;
        $data = array("ASIGNADO_A" => $asignado_a, "FECHA_GESTION" => $fecha, "COMENTARIOS" => $this->input->post('COMENTARIOS'),
            "REVISADO" => $revisado, "REVISADO_POR" => $revisado_por, "APROBADO" => $aprobado,
            "APROBADO_POR" => $aprobado_por, "NOMBRE_DOC_GENERADO" => $name_documento,
            "NOMBRE_DOC_FIRMADO" => $name_documento_firmado, "COD_GESTIONCOBRO" => $cod_gestioncobro,
            "FECHA_DOC_FIRMADO" => ($fecha_doc_firmado == true) ? $fecha_radicado : "", "NUM_RESOLUCION" => $num_radicado);
        //echo "<pre>";print_r($data);exit();
        $cod_fiscalizacion = $this->input->post('COD_PROCESO_COACTIVO');
        $save = $this->verfpagosprojuridicos_model->saveAuto($data, $num_autogenerado, $cod_fiscalizacion);
        if ($save == false) :
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área o la información no existe.</div>');
        elseif ($save == true) :
            if ($button == "PDF" || $button == "Imprimir") :
                $print = ($button == "Imprimir") ? true : false;
                $name = "AutoCierreFiscalizacion" . $cod_fiscalizacion . "-" . $auto->NIT_EMPRESA;
                createPdfTemplateOuput($name, $documento, $print);
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se guardo el Auto con éxito.</div>');
            endif;
        endif;
        redirect(base_url() . 'index.php/bandejaunificada/index');
    }

    function pdf() {

        $html = utf8_encode(base64_decode($this->input->post('descripcion_pdf')));
        $nombre_pdf = $this->input->post('nombre_archivo');
        $titulo = '';
        $tipo = 3;
        $data[0] = $tipo;
        $data[1] = $titulo;
        createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
    }

    function medidasCautelares() {
        $post = $this->input->post();
        $cod_proceso = $post['cod_proceso'];
        if ($post['medidas'] == 1)://Si existen
            $post['cod_gestioncobro'] = trazarProcesoJuridico(542, 1332, "", $cod_proceso, "", "", "");
            $cierre = $this->verfpagosprojuridicos_model->levantarMedidas($post);
        elseif ($post['medidas'] == 2)://No existen
            $cierre = $this->verfpagosprojuridicos_model->medidasCautelares($post);
            $cod_gestioncobro = trazarProcesoJuridico(542, 1333, "", $cod_proceso, "", "", "");
        endif;
        redirect(base_url() . 'index.php/bandejaunificada/index');
    }

}

?>