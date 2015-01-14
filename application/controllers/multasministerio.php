<?php

// Responsable: Leonardo Molina
class Multasministerio extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->helper(array('form', 'url', 'codegen_helper', 'date', 'traza_fecha'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->library(array('datatables', 'libupload', 'form_validation'));
        $this->load->helper(array('form', 'url', 'codegen_helper', 'date', 'traza_fecha', 'template'));
        $this->load->model('multasministerio_model');
        $this->load->model('numeros_letras_model');
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen',
            'css/chosen.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/chosen.jquery.min.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/bootstrap-filestyle.js',
            'js/tinymce/tinymce.jquery.min.js');
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) {
                //template data
                $this->template->set('title', 'Multas Ministerio');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['custom_error'] = $this->session->flashdata('custom_error');
                $this->template->load($this->template_file, 'multasministerio/multasministerio_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function valorEnLetras($valor, $tipo) {
        $info = $this->numeros_letras_model->ValorEnLetras($valor, $tipo);
        return $info;
    }

    function getData() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) { 
                $lenght = $this->input->post('iDisplayLength');
                $data['registros'] = $this->multasministerio_model->getDatax($this->input->post('nit'), $this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght);
                define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
                $TOTAL = $this->multasministerio_model->totalData($this->input->post('nit'), $this->input->post('sSearch'));
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

    function crearMulta() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) {

                $this->form_validation->set_rules('nit', 'Nit. Empresa', 'required|numeric|trim|xss_clean');
                $this->form_validation->set_message('numeric', 'El campo %s debe ser númerico sin espacios y sin guiones.');
                if ($this->form_validation->run() == false) {
                    $this->data['message'] = $this->session->flashdata('message');
                    $error = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    $this->data['custom_error'] = $this->session->set_flashdata('custom_error', $error);
                    redirect(base_url() . 'index.php/multasministerio');
                } else {
                    $this->template->set('title', 'Multas Ministerio');
                    $nit = $this->input->post('nit');
                    $this->data['registros'] = $this->multasministerio_model->getForm($nit);
                    $this->data['grupos'] = $this->multasministerio_model->getAbogadosRC();
                    $this->data['regional'] = $this->multasministerio_model->getRegional();
                    if ($this->data['registros'])
                        $this->template->load($this->template_file, 'multasministerio/multasministerio_add', $this->data);
                    else {
                        $error = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El nit no corresponde a ninguna empresa en esta regional.</div>';
                        $this->session->set_flashdata('custom_error', $error);
                        redirect(base_url() . 'index.php/multasministerio');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    function edidtar_Multa() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) {
                $this->form_validation->set_rules('nit', 'Nit. Empresa', 'required|numeric|trim|xss_clean');
                $this->form_validation->set_message('numeric', 'El campo %s debe ser númerico sin espacios y sin guiones.');
                if ($this->form_validation->run() == false) {
                    $this->data['message'] = $this->session->flashdata('message');
                    $error = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    $this->data['custom_error'] = $this->session->set_flashdata('custom_error', $error);
                    redirect(base_url() . 'index.php/multasministerio');
                } else {
                    $this->template->set('title', 'Multas Ministerio');
                    $cod_multa = $this->input->post('cod_multa');
                    $this->data['registros'] = $this->multasministerio_model->getForm_mul_edil($cod_multa);
                    $this->data['registros2']= $this->multasministerio_model->consul_MultaFiscalizacion($cod_multa);
                    $this->data['grupos'] = $this->multasministerio_model->getAbogadosRC();
                    $this->data['regional'] = $this->multasministerio_model->getRegional();
                    if ($this->data['registros'])
                        $this->template->load($this->template_file, 'multasministerio/multasministerio_add_edid', $this->data);
                    else {
                        $error = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El nit no corresponde a ninguna empresa.</div>';
                        $this->session->set_flashdata('custom_error', $error);
                        redirect(base_url() . 'index.php/multasministerio');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function documento_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $file = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
        $nombreDoc = $file['upload_data']['file_name'];
        $this->multasministerio_model->guardar_resolucion($nombreDoc, $this->input->post('cod_multa'));
//        $this->multasministerio_model->guardar_documento_cobro_persuasibo();
        $codgest = trazar(424, 1110, $post['cod_fis'], $post['nit'], "S");
//        $this->ejecutoriaactoadmin_model->subir_archivo($this->data['post'], $file, $this->data['user'], $codgest['COD_GESTION_COBRO']);
        header("location:" . base_url('index.php/multasministerio/'));
    }

    function confirmar() {
        $this->data['post'] = $this->input->post();
        $this->load->view('multasministerio/confirmar', $this->data);
    }

    function subir_documento_juridico() {
        $this->data['post'] = $this->input->post();
        $this->load->view('multasministerio/subir_documento_juridico', $this->data);
    }

    function do_upload() {
        $post = $this->input->post();
        $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $config['upload_path'] = $route_template;
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

    function gestionar_documento_juridico2() {
        $this->data['post'] = $this->input->post();
        $info = $this->multasministerio_model->datos_resolucion($this->data['post']['id']);
        $reemplazo = array();
        $reemplazo['NOMBRE_EMPRESA'] = $info['NOMBRE_EMPRESA'];
        $reemplazo['NIT'] = $info['CODEMPRESA'];
        $reemplazo['NRO_RESOLUCION'] = $info['NUMERO_RESOLUCION'];
        $reemplazo['FECHA_RESOLUCION'] = $info['FECHA_CREACION'];
        $reemplazo['FECHA_CITACION'] = $info['FECHA_ENVIO_CITACION'];
        $reemplazo['FECHA_EJECUTORIA'] = $info['FECHA_EJECUTORIA'];
        $reemplazo['REGIONAL'] = $info['NOMBRE_REGIONAL'];
        $reemplazo['FECHA'] = date('d/m/Y');
        $reemplazo['COORDINADOR_REGIONAL'] = $info['COORDINADOR_REGIONAL'];
        $reemplazo['USUARIO'] = NOMBRE_COMPLETO;
        $mes=array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
        $reemplazo['mes'] = $mes[date('m')];
        $reemplazo['ano'] = date('Y');
        $reemplazo['dias'] = date('d');
        $reemplazo['USUARIO'] = NOMBRE_COMPLETO;
        $tabla="<table>"
                . "<tr>"
                . "<td>N Resolución</td>"
                . "<td>Tipo de Obligación</td>"
                . "<td>Valor</td>"
                . "<td>Razón Social</td>"
                . "<td>Nit de la Empresa</td>"
                . "</tr>"
                . "<tr>"
                . "<td>".$info['NUMERO_RESOLUCION']."</td>"
                . "<td>Multa del Ministerio</td>"
                . "<td></td>"
                . "<td>".$info['NOMBRE_EMPRESA']."</td>"
                . "<td>".$info['CODEMPRESA']."</td>"
                . "<tr></table>";
                $reemplazo['tabla'] = $tabla;
        $txt = $this->multasministerio_model->plantilla(121);
        $this->data['post']['nresol'] = $info['NUMERO_RESOLUCION'];
        if (!empty($txt)) {
            $this->data['txt'] = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
        }
        $this->load->view('multasministerio/gestionar_documento_juridico2', $this->data);
    }

    function gestionar_documento_juridico() {
        $this->data['post'] = $this->input->post();
        $ii=$this->ion_auth->user()->row();
        
        $info = $this->multasministerio_model->datos_resolucion($this->data['post']['id']);
        $reemplazo = array();
        $reemplazo['NOMBRE_EMPRESA'] = $info['NOMBRE_EMPRESA'];
        $reemplazo['NIT'] = $info['CODEMPRESA'];
        $reemplazo['TEL_REGIONAL'] = $info['TELEFONO_REGIONAL'];
        $reemplazo['REPRESENTANTE_LEGAL'] = $info['REPRESENTANTE_LEGAL'];
        $reemplazo['COD_REGIONAL'] = $info['COD_REGIONAL'];
        $reemplazo['CIUDAD'] = $info['NOMBREMUNICIPIO'];
        $reemplazo['DIRECCION_REGIONAL'] = $info['DIRECCION_REGIONAL'];
        $reemplazo['DIRECCION'] = $info['DIRECCION'];
        $reemplazo['CORREO'] = $ii->EMAIL;
        $reemplazo['NOMBRE_REGIONAL'] = $info['NOMBRE_REGIONAL'];
        $reemplazo['NRO_RESOLUCION'] = $info['NUMERO_RESOLUCION'];
        $reemplazo['FECHA_RESOLUCION'] = $info['FECHA_CREACION'];
        $reemplazo['FECHA_CITACION'] = $info['FECHA_ENVIO_CITACION'];
        $reemplazo['FECHA_EJECUTORIA'] = $info['FECHA_EJECUTORIA'];
        $reemplazo['REGIONAL'] = $info['NOMBRE_REGIONAL'];
        $reemplazo['FECHA'] = date('d/m/Y');
        $reemplazo['COORDINADOR_REGIONAL'] = $info['COORDINADOR_REGIONAL'];
        $reemplazo['USUARIO'] = NOMBRE_COMPLETO;
        $mes=array('','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
        $reemplazo['mes'] = $mes[date('m')];
        $reemplazo['ano'] = date('Y');
        $reemplazo['dias'] = date('d');
        $reemplazo['USUARIO'] = NOMBRE_COMPLETO;
        $tabla="<table>"
                . "<tr>"
                . "<td>N Resolución</td>"
                . "<td>Tipo de Obligación</td>"
                . "<td>Valor</td>"
                . "<td>Razón Social</td>"
                . "<td>Nit de la Empresa</td>"
                . "</tr>"
                . "<tr>"
                . "<td>".$info['NUMERO_RESOLUCION']."</td>"
                . "<td>Multa del Ministerio</td>"
                . "<td></td>"
                . "<td>".$info['NOMBRE_EMPRESA']."</td>"
                . "<td>".$info['CODEMPRESA']."</td>"
                . "<tr></table>";
                $reemplazo['tabla'] = $tabla;
        $txt = $this->multasministerio_model->plantilla(110);
        $this->data['post']['nresol'] = $info['NUMERO_RESOLUCION'];
        if (!empty($txt)) {
            $this->data['txt'] = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
        }
        $this->load->view('multasministerio/gestionar_documento_juridico', $this->data);
    }

    function guardar_paso_juridico() {
        $this->data['post'] = $this->input->post();
        $post = $this->data['post'];
        $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $nombreDoc = create_template($route_template, $post['informacion']);
        $this->multasministerio_model->guardar_resolucion($nombreDoc, $this->input->post('multa'));
        $codgest = trazar(417, 1090, $this->data['post']['cod_fis'], $this->data['post']['nit'], "S");
    }

    function add() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('nradicado', 'N. Radicado', 'required|trim|xss_clean|max_length[12]');
                $this->form_validation->set_rules('nis', 'Nis', 'required|trim|xss_clean|max_length[12]');
                $this->form_validation->set_rules('nresol', 'N. Resolución', 'required|trim|xss_clean|max_length[15]');
                $this->form_validation->set_rules('valor', 'Valor', 'required|numeric|trim|xss_clean|max_length[15]');
                $this->form_validation->set_rules('respons', 'Responsable', 'required|is_natural');
                $this->form_validation->set_rules('pinicial', 'Periodo Inicial', 'required');
//                $this->form_validation->set_rules('pfinal', 'Periodo Final',  'required');
                $this->form_validation->set_rules('fejecutoria', 'Fecha Ejecutoria', 'required');
                $this->form_validation->set_message('numeric', 'El campo %s debe ser númerico sin espacios y sin guiones.');
                $this->form_validation->set_message('is_natural', 'El campo %s debe seleccionar al menos uno.');
                $fisc = $this->input->post('fisc');
                $fisc = str_replace(" ", "", $fisc);
                $info = explode('||', $fisc);
                $fisc = $info[0];
                $this->data['post'] = $this->input->post();
                $nit = $this->input->post('nit');

                if ($this->input->post('exig')) {
                    for ($i = 0; $i < count($_FILES) + 1; $i++) {
                        if (empty($_FILES['archivo' . $i]['name']) && isset($_FILES['archivo' . $i]['name'])) {
                            $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                        }
                    }
                }

                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    $this->data['registros'] = $this->multasministerio_model->getForm($this->input->post('nit'));
                    $this->data['grupos'] = $this->multasministerio_model->getAbogadosRC();
                    $this->template->load($this->template_file, 'multasministerio/multasministerio_add', $this->data);
                } else {
//                    $codigoGestion =$codgest['COD_GESTION_COBRO'];
                    $data = array(
                        'NIT_EMPRESA' => $this->input->post('nit'),
                        'NRO_RADICADO' => $this->input->post('nradicado'),
                        'NRO_RESOLUCION' => $this->input->post('nresol'),
                        'NIS' => $this->input->post('nis'),
                        'VALOR' => $this->input->post('valor'),
                        'RESPONSABLE' => $this->input->post('respons'),
                        'APELACION_RESOLUCION' => $this->input->post('apelacion'),
                        'COD_CONCEPTO' => '4',
                        'EXIGIBILIDAD_TITULO' => $this->input->post('exig'),
                        'OBSERVACIONES' => $this->input->post('observaciones'),
                        'NUMERO_COMUNICACION' => $this->input->post('ncomunicacion'),
                        'COD_MULTAMINISTERIO' => $info[1]
                    );

                    $data2 = array(
                        'PERIODO_INICIAL' => $this->input->post('pinicial'),
//                        'PERIODO_FINAL'       => $this->input->post('pfinal'),
                        'FECHA_EJECUTORIA' => $this->input->post('fejecutoria'),
                    );

                    $route_template = 'uploads/fiscalizaciones/resolucion_multas/';
                    if (!file_exists($route_template)) {
                        if (!mkdir($route_template, 0777, true)) {
                            
                        }
                    }
                    $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/';
                    if (!file_exists($route_template)) {
                        if (!mkdir($route_template, 0777, true)) {
                            
                        }
                    }
                    $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/multas/';
                    if (!file_exists($route_template)) {
                        if (!mkdir($route_template, 0777, true)) {
                            
                        }
                    }
                    $route_template = '/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/multas/';
                    $ar = 0;
                    for ($i = 0; $i < count($_FILES) + 1; $i++) {
                        //no le hacemos caso a los vacios
                        if (!empty($_FILES['archivo' . $i]['name']) && isset($_FILES['archivo' . $i]['name'])) {
                            $ar++;
                            $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $route_template, 'pdf|doc|jpg|jpeg|png', 9999, 9999, 0);
                        }
                    }
                    if ($this->input->post('exig')) {
                        //83 = Verificar Exigibilidad de Titulo de Multas del Ministerio
                        //158= Proceso Multa Aceptado
                        $codgest = trazar(83, 158, $fisc, $nit, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                    } else {
                        //83 = Verificar Exigibilidad de Titulo de Multas del Ministerio
                        //159= Devuelto por Incompletitud de Requisitos
                        $codgest = trazar(83, 159, $fisc, $nit, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                    }
                    $query = $this->multasministerio_model->addMulta($data, $data2, $codGestion);
//                die();
//                    var_dump($query->COD_MULTAMINISTERIO);die;
//                    echo "<pre>";
//                    print_r($respuesta);
//                    echo "</pre>";
                    if ($this->input->post('exig')) {
                        for ($i = 0; $i < $ar; $i++) {
                            $nombre_archivo = $respuesta[$i]['data']['file_name'];
                            $query2 = $this->multasministerio_model->addMultaDocumento($nombre_archivo, $query->COD_MULTAMINISTERIO);
                        }
                    }

//                    $this->multasministerio_model->updateMultaResolucion($data['NRO_RESOLUCION'],$codGestion);
//                    die();
                    if ($query == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La multa se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/multasministerio');
                    } else {
                        $error = '<div class="form_error"><p>An Error Occured.</p></div>';
                        $this->session->set_flashdata('custom_error', $error);
                        redirect(base_url() . 'index.php/multasministerio/crearMulta');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    function add_edid() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('nradicado', 'N. Radicado', 'required|trim|xss_clean|max_length[12]');
                $this->form_validation->set_rules('nis', 'Nis', 'required|trim|xss_clean|max_length[12]');
                $this->form_validation->set_rules('nresol', 'N. Resolución', 'required|trim|xss_clean|max_length[15]');
                $this->form_validation->set_rules('valor', 'Valor', 'required|numeric|trim|xss_clean|max_length[15]');
                $this->form_validation->set_rules('respons', 'Responsable', 'required|is_natural');
                $this->form_validation->set_rules('pinicial', 'Periodo Inicial', 'required');
//                $this->form_validation->set_rules('pfinal', 'Periodo Final',  'required');
                $this->form_validation->set_rules('fejecutoria', 'Fecha Ejecutoria', 'required');
                $this->form_validation->set_message('numeric', 'El campo %s debe ser númerico sin espacios y sin guiones.');
                $this->form_validation->set_message('is_natural', 'El campo %s debe seleccionar al menos uno.');
                $fisc = $this->input->post('fisc');
                $fisc = str_replace(" ", "", $fisc);
                $info = explode('||', $fisc);
                $fisc = $info[0];
                $this->data['post'] = $this->input->post();
                $nit = $this->input->post('nit');

                if ($this->input->post('exig')) {
                    for ($i = 0; $i < count($_FILES) + 1; $i++) {
                        if (empty($_FILES['archivo' . $i]['name']) && isset($_FILES['archivo' . $i]['name'])) {
                            $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                        }
                    }
                }

                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    $this->data['registros'] = $this->multasministerio_model->getForm($this->input->post('nit'));
                    $this->data['grupos'] = $this->multasministerio_model->getAbogadosRC();
                    $this->template->load($this->template_file, 'multasministerio/multasministerio_add', $this->data);
                } else {
//                    $codigoGestion =$codgest['COD_GESTION_COBRO'];
                    $data = array(
                        'NIT_EMPRESA' => $this->input->post('nit'),
                        'NRO_RADICADO' => $this->input->post('nradicado'),
                        'NRO_RESOLUCION' => $this->input->post('nresol'),
                        'NIS' => $this->input->post('nis'),
                        'VALOR' => $this->input->post('valor'),
                        'RESPONSABLE' => $this->input->post('respons'),
                        'APELACION_RESOLUCION' => $this->input->post('apelacion'),
                        'COD_CONCEPTO' => '4',
                        'EXIGIBILIDAD_TITULO' => $this->input->post('exig'),
                        'OBSERVACIONES' => $this->input->post('observaciones'),
                        'NUMERO_COMUNICACION' => $this->input->post('ncomunicacion'),
                        'COD_MULTAMINISTERIO' => $info[1]
                    );

                    $data2 = array(
                        'PERIODO_INICIAL' => $this->input->post('pinicial'),
//                        'PERIODO_FINAL'       => $this->input->post('pfinal'),
                        'FECHA_EJECUTORIA' => $this->input->post('fejecutoria'),
                    );

                    $route_template = 'uploads/fiscalizaciones/resolucion_multas/';
                    if (!file_exists($route_template)) {
                        if (!mkdir($route_template, 0777, true)) {
                            
                        }
                    }
                    $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/';
                    if (!file_exists($route_template)) {
                        if (!mkdir($route_template, 0777, true)) {
                            
                        }
                    }
                    $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/multas/';
                    if (!file_exists($route_template)) {
                        if (!mkdir($route_template, 0777, true)) {
                            
                        }
                    }
                    $route_template = '/fiscalizaciones/resolucion_multas/COD_' . $this->input->post('nresol') . '/multas/';
                    $ar = 0;
                    for ($i = 0; $i < count($_FILES) + 1; $i++) {
                        //no le hacemos caso a los vacios
                        if (!empty($_FILES['archivo' . $i]['name']) && isset($_FILES['archivo' . $i]['name'])) {
                            $ar++;
                            $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $route_template, 'pdf|doc|jpg|jpeg|png', 9999, 9999, 0);
                        }
                    }
                    if ($this->input->post('exig')) {
                        //83 = Verificar Exigibilidad de Titulo de Multas del Ministerio
                        //158= Proceso Multa Aceptado
                        $codgest = trazar(83, 158, $fisc, $nit, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                    } else {
                        //83 = Verificar Exigibilidad de Titulo de Multas del Ministerio
                        //159= Devuelto por Incompletitud de Requisitos
                        $codgest = trazar(83, 159, $fisc, $nit, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                    }
                    $this->multasministerio_model->edit_Multa($data, $data2, $codGestion,$info[1]);
//                    print_r($query);
//                die();
//                    var_dump($query->COD_MULTAMINISTERIO);die;
//                    echo "<pre>";
//                    print_r($respuesta);
//                    echo "</pre>";
                    if ($this->input->post('exig')) {
                        for ($i = 0; $i < $ar; $i++) {
                            $nombre_archivo = $respuesta[$i]['data']['file_name'];
                            $query2 = $this->multasministerio_model->addMultaDocumento($nombre_archivo, $info[1]);
                        }
                    }

//                    $this->multasministerio_model->updateMultaResolucion($data['NRO_RESOLUCION'],$codGestion);
//                    die();
                    if ($query == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La multa se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/multasministerio');
                    } else {
                        $error = '<div class="form_error"><p>An Error Occured.</p></div>';
                        $this->session->set_flashdata('custom_error', $error);
                        redirect(base_url() . 'index.php/multasministerio/crearMulta');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addMultasResolucion() {
        $this->data['post'] = $this->input->post();

        $route_template = 'uploads/fiscalizaciones/resolucion_multas/COD_' . $this->data['post']['mnres'] . '/';
        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $file_route = 'fiscalizaciones/resolucion_multas/COD_' . $this->data['post']['mnres'] . '/';
        for ($i = 0; $i < count($_FILES); $i++) {
            if (!empty($_FILES['archivo' . $i]['name'])) {
                $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $file_route, 'pdf|doc|jpg|jpeg|png', 9999, 9999, 0);
            }
        }
        $dataasig = array(
            'ASIGNADO_POR' => $this->ion_auth->user()->row()->IDUSUARIO,
            'NIT_EMPRESA' => $this->data['post']['mnit'],
            'ASIGNADO_A' => $this->ion_auth->user()->row()->IDUSUARIO,
        );
        $query1 = $this->multasministerio_model->addMultaAsignacion($dataasig);
        $datafisc = array(
            'COD_ASIGNACION_FISC' => $query1->COD_ASIGNACIONFISCALIZACION,
            'COD_ABOGADO' => $this->ion_auth->user()->row()->IDUSUARIO,
            'COD_CONCEPTO' => 5,
            'COD_TIPOGESTION' => 1,
            'FECHA_ASIGNACION_ABOGADO' => $query1->FECHA_ASIGNACION,
        );
        $query2 = $this->multasministerio_model->addMultaFiscalizacion($datafisc);
        $info = explode(' || ', $query2->COD_FISCALIZACION);
        $data = array(
            'NUMERO_RESOLUCION' => $this->data['post']['mnres'],
            'RUTA_DOCUMENTO_FIRMADO' => $respuesta[0]['data']['orig_name'],
            'COD_REGIONAL' => $this->data['post']['mcreg'],
            'NOMBRE_EMPLEADOR' => $this->data['post']['mnom'],
            'NITEMPRESA' => $this->data['post']['mnit'],
            'ELABORO' => $this->ion_auth->user()->row()->IDUSUARIO,
            'COD_FISCALIZACION' => $info[0],
            'VALOR_TOTAL' => $this->data['post']['mvalo'],
            'VALOR_LETRAS' => $this->valorEnLetras($this->data['post']['mvalo'], "pesos"),
            'COD_CPTO_FISCALIZACION' => 5, // concepto fiscalizacion -> 5(autoincrement)
        );
        $query3 = $this->multasministerio_model->addMultaResolucion($data);
        echo $query2->COD_FISCALIZACION;
    }

    function detalle() {
        $multa = $this->uri->segment(3);
        $this->data['registros'] = $this->multasministerio_model->getMulta($multa);
        $this->data['resolucion'] = $this->multasministerio_model->getMultaResolucion($multa);
        $this->data['documentos'] = $this->multasministerio_model->getDocumentosMulta($multa);
        $this->load->view('multasministerio/multasministerio_details', $this->data);
    }

}
