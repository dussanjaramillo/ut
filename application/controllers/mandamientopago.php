 <?php

class Mandamientopago extends MY_Controller {

    public $TIPONOTIFICACIONPERSONAL = '1';
    public $TIPONOTIFICACIONCORREO = '2';
    public $TIPONOTIFICACIONDIARIO = '3';
    public $TIPONOTIFICACIONPAGINA = '4';
    public $TIPONOTIFICACIONACTA = '5';
    public $TIPONOTIFICACIONMEDIDA = '6';
    public $TIPONOTIFICACIONADELANTE = '7';
    public $TIPONOTIFICACIONAVISO = '8';
    public $TIPONOTIFICACIONEDICTO = '9';
    public $ESTADONOTIFICACIONGENERADA = '1';
    public $ESTADONOTIFICACIONAPROBADA = '2';
    public $ESTADONOTIFICACIONANOAPROBADA = '3';
    public $ESTADONOTIFICACIONAREVISADA = '4';
    public $ESTADONOTIFICACIONENVIADO = '5';
    public $ESTADONOTIFICACIONENTREGADO = '6';
    public $ESTADONOTIFICACIONDEVUELTO = '7';

    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template_helper', 'traza_fecha_helper', 'file'));
        $this->load->model('mandamientopago_model', '', TRUE);
        $this->load->model('plantillas_model');
        $this->load->library(array('pagination', 'libupload', 'datatables', 'session', 'form_validation'));
        $this->load->file(APPPATH . "controllers/expedientes.php", TRUE);
        $this->load->file(APPPATH . "controllers/verificarpagos.php", TRUE);
        $sesion = $this->session->userdata;
        //permisos de usuarios
        define("ABOGADO", "43"); // id de la tabla uduario_gurpos para saber el secretario
        define("SECRETARIO", "41"); // id de la tabla uduario_gurpos para saber el secretario
        define("COORDINADOR", "42"); // id de la tabla uduario_gurpos para saber el secretario
        define("ADMIN", "1"); // id de la tabla uduario_gurpos para saber el secretario

        define("ID_SECRETARIO", $sesion['id_secretario']);
        define("NOMBRE_SECRETARIO", $sesion['secretario']);
        define("ID_COORDINADOR", $sesion['id_coordinador']);
        define("NOMBRE_COORDINADOR", $sesion['coordinador']);

        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
        $this->data['permiso'] = $this->mandamientopago_model->permiso();
        define("PERFIL", @$this->data['permiso'][0]['IDGRUPO']);
        define("REGIONAL", @$this->data['permiso'][0]['COD_REGIONAL']);



        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/chosen.css' => 'screen'
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/tinymce/plugins/moxienitsr/plugin.min.js',
            'js/tinymce/tinymce.js',
            'js/chosen.jquery.min.js',
            'js/tinymce/tinymce.min.js',
            'js/tinymce/tinymce.jquery.min.js',
            'js/jquery.dataTables.columnFilter.js',
            'js/jquery.dataTables.rowReordering.js'
        );
    }

    function index() {
        $this->nits();
    }

    function acta() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
        $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
        $this->data['titulo'] = "<h2>Acta de Notificación personal Mandamiento de Pago</h2>";
        $this->data['tipo'] = "acta";
        $this->data['instancia'] = "Acta Notificación Personal";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/acta/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/acta";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/acta/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 210;
                    $mensaje = 'Acta de Notificación Personal Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(109, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
                    $fileElim = $this->elimTemp($this->input->post('cod_fiscalizacion'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);

                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acta de Notificaci&oacute;n personal se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_acta', $this->data);
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosProceso($this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actaExc() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Acta de Notificación Personal de la Resolución que Resuelve Excepciones</h2>";
        $this->data['tipo'] = "actaexc";
        $this->data['instancia'] = "Acta Excepción Notificación Personal";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/actaexc/';
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '1', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaexc";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaexc/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 659;
                    $mensaje = 'Acta de Notificación de Excepcion Personal Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(247, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '1',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acta de Notificaci&oacute;n personal se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
//		$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_acta', $this->data);
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actaRec() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Acta de Notificación de Recurso</h2>";
        $this->data['tipo'] = "actaRec";
        $this->data['instancia'] = "Acta Notificación Personal de Recurso";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/actaRec/';
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '1', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaRec";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaRec/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 272;
                    $mensaje = 'Acta de Notificación Personal que Resuelve el Recurso Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(126, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_fiscalizacion'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '1',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acta de Notificaci&oacute;n personal se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_acta', $this->data);
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));

                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actaResosa() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['titulo'] = "<h2>Acta de Notificación de Resolucion ordenando seguir adelante</h2>";
        $this->data['tipo'] = "actaresosa";
        $this->data['instancia'] = "Acta Notificación Personal de Resolución Ordenando a seguir adelante";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/actaresosa/';
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '1', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaresosa";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaresosa/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 1454;
                    $mensaje = 'Notificacion Acta Resolucion Seguir Adelante Generada';
                    //echo $estado;die;
                    $this->datos['idgestion'] = trazarProcesoJuridico(668, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '1',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Acta de Notificaci&oacute;n personal se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_acta', $this->data);
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la adición de mandamientos de pago
     */
    function add() {
        //Variables
        $this->data['post'] = $this->input->post();
        $nit = $this->input->post('nit');
        //Id del usuario
        $user = $this->ion_auth->user()->row();
        $estado = $this->input->post('aprobado');
        $gestion = $this->input->post('gestion');
        $idmandamiento = $this->input->post('clave');
        $this->data['idmandamiento'] = $idmandamiento;
        $this->data['gestion'] = $gestion;
        $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
        $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
        //Numero de Proceso
    
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] =  $gestion;
   
        //Titulo del proceso
        $this->data['titulo'] = "<h2>Mandamiento de Pago</h2>";
        //Tipo del proceso
        $this->data['tipo'] = "mandamiento";
        $this->data['instancia'] = "Mandamiento de Pago";
        //Ruta del archivo Temporal
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_proceso') . '/mandamiento/';
        /* CONSULTAR PLANTILLA */
        $this->data['plantilla'] = "rq18_cu001.txt";
        /* TRAER EL CONTENIDO DEL ARCHIVO DE LA PLANTILLA CON UN ARREGLO DE LAS VARIABLES QUE SE VAN A MOSTRAR */
        $this->data['CONSECUTIVO'] = $consec['CONSECUTIVO'] = $this->input->post('cod_coactivo');
        $urlplantilla2 = "uploads/plantillas/" . $this->data['plantilla'];
        $this->data['filas2'] = template_tags($urlplantilla2, $consec);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/mandamiento/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }
                    $sFile = create_template($cRuta, $this->input->post('notificacion'));

                    //verifica la funcionalidad de la vista y el estado que va a generar
                    if ($estado == '') {
                        $estado = 205;
                        $mensaje = 'Mandamiento de Pago Generado';
                    } else if ($estado == 'APROBADO') {
                        $estado = 208;
                        $mensaje = 'Mandamiento de Pago Aprobado y Firmado';
                    } else if ($estado == 'REVISADO') {
                        $estado = 206;
                        $mensaje = 'Mandamiento de Pago Pre-aprobado';
                    } else if ($estado == 'DEVOLVER') {
                        $estado = 207;
                        $mensaje = 'Mandamiento de Pago Rechazado';
                    }
                    // Funcion de Trazabilidad                                                                                 
                    $this->datos['idgestion'] = trazarProcesoJuridico(108, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');

                    //Elimina archivo temporal
                    $fileElim = $this->elimTemp($this->input->post('cod_proceso'), $this->input->post('tipo'));
//                    echo "<pre>";
//                    print_r($this->input->post('titulos'));
//                    echo "</pre>"; die();
                    $data = array(
                        'FECHA_MODIFICA_MANDAMIENTO' => date("d/m/Y"),
                        'COMENTARIOS' => $this->input->post('comentarios'),
                        'APROBADO' => $this->input->post('aprobado'),
                        'REVISADO' => $this->input->post('revisado'),
                        'ASIGNADO_A' => ID_SECRETARIO,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        'ESTADO' => $estado,
                        'PLANTILLA' => $sFile,
                        'EXCEPCION' => '0',
                        'TITULOS' => $this->input->post('titulos')
                    );

                    try {
                        $insert = $this->mandamientopago_model->addMandamiento($this->input->post('idmandamiento'), $data);
                        //$insert=true;
                        if ($insert != TRUE)
                            throw new Exception("Error al Insertar datos en la Tabla de MANDAMIENTOS<BR>");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El mandamiento de pago se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    } catch (Exception $e) {
                        $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() . '</div>';
                        $this->template->load($this->template_file, 'mandamientopago/mandamientopago_base', $this->data);
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosProceso($this->input->post('cod_coactivo'));

                $this->template->load($this->template_file, 'mandamientopago/mandamientopago_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//Fin add

    function addRec() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $estado = $this->input->post('aprobado');
        $nit = $this->input->post('nit');
        $user = $this->ion_auth->user()->row();
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['titulo'] = "<h2>Resolución que Resuelve Recursos</h2>";
        $this->data['instancia'] = "Resolver Recursos";
        $this->data['tipo'] = "recurso";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/recurso/';
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['idmandamiento'] = $this->input->post('clave');
        $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/recurso/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }
                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    //verifica la funcionalidad de la vista y el estado que va a generar
                    if ($estado == '') {
                        $estado = 264;
                        $mensaje = 'Resolución que Resuelve Recurso Generada';
                    }

                    // Funcion de Trazabilidad                 
                    $this->datos['idgestion'] = trazarProcesoJuridico(124, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');

                    //Elimina archivo temporal
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $data = array(
                        'COD_RESOLUCION' => $this->mandamientopago_model->getSequence('RESOLUCIONRECURSOMANDAMIENTO', 'MandamientoPa_cod_mandamie_SEQ.NEXTVAL') + 1,
                        'COD_ESTADO' => $estado,
                        'PLANTILLA' => $sFile,
                        'COD_PROCESO_COACTIVO' => $this->input->post('cod_coactivo'),
                        'APROBADO' => $this->input->post('aprobado'),
                        'REVISADO' => $this->input->post('revisado'),
                        'ASIGNADO_A' => ID_USER,
                        'CREADO_POR' => $user->IDUSUARIO,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        'COMENTARIOS' => $this->input->post('comentarios'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'FECHA_RECURSO' => date("d/m/Y"),
                    );

                    $datoMan = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                    );
                    try {
                        $insert = $this->mandamientopago_model->addResolucionRecurso('RESOLUCIONRECURSOMANDAMIENTO', $data);
                        $updateManda = $this->mandamientopago_model->editMandamiento($datoMan, $this->input->post('idmandamiento'));
                        if ($insert != TRUE)
                            throw new Exception("Error al Insertar datos en la Tabla de MANDAMIENTOS<BR>");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Resolución de Excepción se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/mandamientopago/nits');
                    } catch (Exception $e) {
                        $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() . '</div>';
                        $this->template->load($this->template_file, 'mandamientopago/mandamientopago_base', $this->data);
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->template->load($this->template_file, 'mandamientopago/mandamientopago_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//Fin add

    /**
     * Funcion para cargar la adición de resolucion seguir adelante
     */
    function addRes() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $estado = $this->input->post('aprobado');
        $nit = $this->input->post('nit');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['titulo'] = "<h2>Resolucion Ordenando Seguir Adelante</h2>";
        $this->data['tipo'] = "adelante";
        $this->data['instancia'] = "Seguir Adelante";
        $this->data['rutaTemporal'] = ''
                . './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/adelante/';
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['idmandamiento'] = $this->input->post('clave');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $user = $this->ion_auth->user()->row();
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/adelante/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));

                    if ($estado == '') {
                        $estado = 246;
                        $mensaje = 'Resolución Ordenando Seguir Adelante Creada';
                    } else if ($estado == 'Aprobado') {
                        $estado = 249;
                        $mensaje = 'Resolución Ordenando Seguir Adelante Aprobada y Firmada';
                    } else if ($estado == 'Pre-aprobado') {
                        $estado = 206;
                        $mensaje = 'Resolución Ordenando Seguir Adelante Pre-aprobada';
                    } else if ($estado == 'Rechazado') {
                        $estado = 248;
                        $mensaje = 'Resolución Ordenando Seguir Adelante Rechazada';
                    }

                    $this->datos['idgestion'] = trazarProcesoJuridico(119, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                    $data = array(
                        'COD_MANDAMIENTO_ADELANTE' => $this->mandamientopago_model->getSequence('MANDAMIENTO_ADELANTE', 'MandamientoPa_cod_mandamie_SEQ.NEXTVAL') + 1,
                        'COD_PROCESO_COACTIVO' => $this->input->post('cod_coactivo'),
                        'FECHA_MANDAMIENTO_ADELANTE' => date("d/m/Y"),
                        'CREADO_POR' => $user->IDUSUARIO,
                        'COMENTARIOS' => $this->input->post('comentarios'),
                        'APROBADO' => $this->input->post('aprobado'),
                        'REVISADO' => $this->input->post('revisado'),
                        'ASIGNADO_A' => ID_USER,
                        'ESTADO' => $estado,
                        'PLANTILLA' => $sFile,
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            //'ASIGNADO_A' => $asignado[0],     
                    );

                    $insert = $this->mandamientopago_model->addMandamientoAdelante('MANDAMIENTO_ADELANTE', $data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));

                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA MANDAMIENTO<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La resoluci&oacute;n se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/mandamientopago/nits');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                //$this->template->load($this->template_file, 'mandamientopagoexcepcion/mandamientopago_addRes', $this->data);
                $this->template->load($this->template_file, 'mandamientopago/mandamientopago_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addEdicto() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Edicto</h2>";
        $this->data['tipo'] = "edicto";
        $this->data['instancia'] = "Aviso de Fijación";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/edicto/';
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONEDICTO, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/edicto";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/edicto/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 268;
                    $mensaje = 'Notificación de Resolución de Recurso por Edicto Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(125, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONEDICTO,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/mandamientopago/nits');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addExc() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $nit = $this->input->post('nit');
        $user = $this->ion_auth->user()->row();
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['titulo'] = "<h2>Resolución que Resuelve Excepciones</h2>";
        $this->data['instancia'] = "Resolver Excepciones";
        $this->data['tipo'] = "excepcion";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/excepcion/';
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['idmandamiento'] = $this->input->post('clave');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/excepcion/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }
                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    //verifica la funcionalidad de la vista y el estado que va a generar
                    $estado = 1018;
                    $mensaje = 'Resolucion que resuelve Excepciones Generado';


                    // Funcion de Trazabilidad                 

                    $this->datos['idgestion'] = trazarProcesoJuridico(116, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                    //Elimina archivo temporal
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $data = array(
                        'COD_RESOLUCION' => $this->mandamientopago_model->getSequence('RESOLUCIONEXCEPCION', 'MandamientoPa_cod_mandamie_SEQ.NEXTVAL') + 1,
                        'COD_TIPOEXCEPCION' => $this->input->post('Tipoexcepcion'),
                        'COMENTARIOS' => $this->input->post('comentarios'),
                        'COD_ESTADO' => $estado,
                        'PLANTILLA' => $sFile,
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'COD_PROCESO_COACTIVO' => $this->input->post('cod_coactivo'),
                        'APROBADO' => $this->input->post('aprobado'),
                        'REVISADO' => $this->input->post('revisado'),
                        'ASIGNADO_A' => ID_USER,
                        'FECHA_EXCEPCION' => date("d/m/Y"),
                        'CREADO_POR' => $user->IDUSUARIO,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                            //'ASIGNADO_A' => $asignado,
                    );
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));


                    try {
                        $insert = $this->mandamientopago_model->addResolucion('RESOLUCIONEXCEPCION', $data);
                        if ($insert != TRUE)
                            throw new Exception("Error al Insertar datos en la Tabla de MANDAMIENTOS<BR>");
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Resolución de Excepción se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/mandamientopago/nits');
                    } catch (Exception $e) {
                        $this->data['message'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ha ocurrido un problema de ejecución : ' . $e->getMessage() . '</div>';
                        $this->template->load($this->template_file, 'mandamientopago/mandamientopago_base', $this->data);
                    }
                }
                //add style an js files for inputs selects
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->template->load($this->template_file, 'mandamientopago/mandamientopago_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//Fin add

    /**
     * Funcion para cargar la citación al deudor
     */
    function aviso() {
        $this->data['post'] = $this->input->post();
        $this->data['fiscalizacion'] = $this->input->post('cod_fiscalizacion');
        $this->data['titulo'] = "<h2>Notificación de Mandamiento de Pago por Aviso</h2>";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_fiscalizacion') . '/aviso/';
        $this->data['tipo'] = "aviso";
        $this->data['instancia'] = "Notificacion por Aviso";
        $nit = $this->input->post('nit');
        $gestion = $this->input->post('gestion');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/aviso/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));

                    if (isset($file['error'])) {
                        //var_dump($file);
                        $this->data['message'] = $this->session->flashdata('message');
                        $this->data['mensaje'] = validation_errors();
                        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' . $this->data['message'] . '</div>');
                    } else {
                        $fileElim = $this->elimTemp($this->input->post('cod_fiscalizacion'), $this->input->post('tipo'));
                        $estado = '228';

                        $data = array(
                            'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONAVISO,
                            'FECHA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => '1',
                            'OBSERVACIONES' => $this->input->post('observacion'),
                            'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                            'NOMBRE_DOC_CARGADO' => ' ',
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'ESTADO_NOTIFICACION' => 'ACTIVO',
                            'PLANTILLA' => $sFile
                        );
                        $this->datos['idgestioncobro'] = trazar(114, $estado, $this->input->post('cod_fiscalizacion'), $this->input->post('nit'), $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = "Citacion Generado");
                        $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
                        $asignado = explode("-", $this->input->post('asignado'));
                        $dato = array(
                            'ESTADO' => $estado,
                            'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            'ASIGNADO_A' => $asignado[0],
                        );
                    }

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('idmandamiento'));
                    if ($insert == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El aviso de Citaci&oacute;n se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    } else {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_aviso', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la citación al deudor
     */
    function citacion() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $gestion = $this->input->post('gestion');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['titulo'] = "<h2>Citación para la Notificaci&oacute;n personal</h2>";
        $this->data['tipo'] = "citacion";
        $this->data['instancia'] = "Citación Personal";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/citacion/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacion";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacion/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = '220';
                    $mensaje = 'Notificación de Mandamiento de Pago ';


                    $this->datos['idgestion'] = trazarProcesoJuridico(112, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        'ASIGNADO_A' => ID_SECRETARIO
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'), $ID);
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function citacionex() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['titulo'] = "<h2>Citación de Resolución de Excepción</h2>";
        $this->data['tipo'] = "citacionex";
        $this->data['instancia'] = "Citacion Personal de Excepcion";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/citacionex/';
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '1', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionex";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionex/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 238;
                    $mensaje = 'Citación de Resolución de Excepción Generada';


                    $this->datos['idgestion'] = trazarProcesoJuridico(117, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        'EXCEPCION' => '1',
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '1',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function citacionrec() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Citación para notificación personal de la resolución que resuelve el recurso de reposición</h2>";
        $this->data['tipo'] = "citacionrec";
        $this->data['instancia'] = "Citacion Personal de Recurso";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/citacionrec/';
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '1', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionrec";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionrec/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 260;
                    $mensaje = 'Citación de Resolución de Citación Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(123, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '1',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function citacionResosa() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['titulo'] = "<h2>Citación Notificaci&oacute;n personal</h2>";
        $this->data['tipo'] = "resosa";
        $this->data['instancia'] = "Citación Personal";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/resosa/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/resosa";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/resosa/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 1433;
                    $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';


                    $this->datos['idgestion'] = trazarProcesoJuridico(665, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        'ASIGNADO_A' => ID_SECRETARIO
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la citación al deudor
     */
    function correo() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Notificación por Correo</h2>";
        $this->data['tipo'] = "correo";
        $this->data['instancia'] = "Notificación por Correo";
        $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/correo/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correo";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    echo $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correo/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 216;
                    $comentarios = 'Notificación de Mandamiento de Pago por Correo Generado';


                    $this->datos['idgestion'] = trazarProcesoJuridico(111, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');

                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONCORREO,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function correoExc() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Notificación de Excepción por Correo</h2>";
        $this->data['tipo'] = "correoexc";
        $this->data['instancia'] = "Citacion de Excepcion por Correo";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/correoexc/';
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '1', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoexc";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoexc/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 242;
                    $mensaje = 'Notificación de Resolución de Excepción por Correo Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(118, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONCORREO,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '1',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($update == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function correoResosa() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['titulo'] = "<h2>Notificación de Resolución Ordenando a Seguir Adelante por Correo</h2>";
        $this->data['tipo'] = "correoresosa";
        $this->data['instancia'] = "Citacion de Resolución Ordenando a Seguir Adelante por Correo";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/correoresosa/';
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '1', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 1440;
                    $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(666, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONCORREO,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '1',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($update == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_citacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function autosCierre() {
        $cod_coactivo = $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['clave'] = $this->input->post('clave');
        $this->data['fecha'] = $this->input->post('fecha');
        $this->data['gestion'] = $this->input->post('gestion');
        $this->data['nit'] = $this->input->post('nit');
        $titulos = $this->mandamientopago_model->getTitulos($cod_coactivo);
        //verificarpagos::crearAutosCierre($cod_coactivo,$titulos);   
        $this->verificarpagos = new Verificarpagos();
        $this->verificarpagos->crearAutosCierre($cod_coactivo, $titulos);
    }

    /**
     * Funcion para listar los datos del DataTable de seguir adelante
     */
    function datatableRes() {
        $this->data['post'] = $this->input->post();
        if ($this->ion_auth->logged_in()) {
            $this->data['style_sheets'] = array(
                'css/jquery.dataTables_themeroller.css' => 'screen'
            );
            $this->data['javascripts'] = array(
                'js/jquery.dataTables.min.js',
                'js/jquery.dataTables.defaults.js'
            );
            $this->load->library('datatables');
            $this->load->model('mandamientopago_model');
            $this->input->post('iDisplayStart');
            //$this->data['idusuario'] = $_POST['idusuario'];

            $tipoproceso = $this->input->post('tipoproceso');
            $numproceso = $this->input->post('numproceso');
            $usuarios = $this->input->post('usuarios');
            $asignado = $this->input->post('asignado');
            $fechacreacion = $this->input->post('fechacreacion');
            $estadoid = $this->input->post('estado_id');
            $this->data['registros'] = $this->mandamientopago_model->getDataTableAdelante($tipoproceso, $numproceso, $usuarios, $asignado, $fechacreacion, $estadoid);
            $this->load->view('mandamientopagoexcepcion/mandamientopago_table', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function documentos() {
        $idmandamiento = $this->input->post('clave');
        $idgestion = $this->input->post('estado_gestion');
        $this->data['fiscalizacion'] = $this->input->post('fisca');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['documentos'] = $this->mandamientopago_model->getDocumentos($idmandamiento);
                $this->data['excepcionDoc'] = $this->mandamientopago_model->getRecursoExcep($idmandamiento);
                $this->data['recursoDoc'] = $this->mandamientopago_model->getRecurso($idmandamiento);
                $this->data['pago'] = $this->mandamientopago_model->getPagoDoc($idmandamiento);
                $this->data['pruebaExcep'] = $this->mandamientopago_model->getRecursoDoc($idmandamiento);
                $this->load->view('mandamientopago/mandamientopago_documentos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar un archivo PDF de mandamiento de pago
     * @param string $cProceso Codigo del Mandamiento o Excepcion
     * @param string $cTipo Tipo: Mandamiento o Resolución
     */
    private function do_upload($cProceso, $cTipo) {
        $this->load->library('upload');
        $cFile = "./uploads/mandamientos/$cProceso/pdf/$cTipo/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }

        $config['upload_path'] = $cFile;
        $config['allowed_types'] = '*';
        $config['max_size'] = '6048';
        $config['encrypt_name'] = true;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('filecolilla')) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

// Fin do_upload

    /**
     * Funcion para cargar un archivo PDF de documentos
     * @param string $cProceso Codigo del proceso
     * @param string $cTipo Tipo de aviso
     */
    private function do_uploadDoc($cProceso, $cTipo1, $cTipo2) {
        // Cargamos la libreria Upload
        $this->load->library('upload');
        $cFile = "./uploads/mandamientos/" . $cProceso . "/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }
        /*
         * Revisamos si el archivo fue subido
         * Comprobamos si existen errores en el archivo subido
         */
        if (@$_FILES['filecolilla']['name'] != '') {
            $cFile1 = $cFile . $cTipo1 . "/";
            if (!is_dir($cFile1)) {
                mkdir($cFile1, 0777, true);
            }
            // Configuración para el Archivo 1
            $config['upload_path'] = $cFile1;
            $config['allowed_types'] = '*';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;

            // Cargamos la configuración del Archivo 1
            $this->upload->initialize($config);

            // Subimos archivo 1
            if ($this->upload->do_upload('filecolilla')) {

                $data1 = $this->upload->data();
            } else {
                echo $this->upload->display_errors();
            }
        }

        if (@$_FILES['doccolilla']['name'] != '') {
            $cFile2 = $cFile . $cTipo2 . "/";
            if (!is_dir($cFile2)) {
                mkdir($cFile2, 0777, true);
            }
            // Configuración para el Archivo 1
            $config['upload_path'] = $cFile2;
            $config['allowed_types'] = '*';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;

            // Cargamos la configuración del Archivo 1
            $this->upload->initialize($config);

            // Subimos archivo 1
            if ($this->upload->do_upload('doccolilla')) {
                $data2 = $this->upload->data();
            } else {
                echo $this->upload->display_errors();
            }
        }
        @$dataArray = array($data1, $data2);
        return $dataArray;
    }

//fin do_uploadDoc      

    private function do_uploadImg($cProceso, $cTipo1, $cTipo2) {
        // Cargamos la libreria Upload        
        $this->load->library('upload');
        $cFile = "./uploads/mandamientos/" . $cProceso . "/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }

        /*
         * Revisamos si el archivo fue subido
         * Comprobamos si existen errores en el archivo subido
         */
        if (@$_FILES['filecolilla']['name'] != '') {
            $cFile1 = $cFile . $cTipo1 . "/";
            if (!is_dir($cFile1)) {
                mkdir($cFile1, 0777, true);
            }
            // Configuración para el Archivo 1
            $config['upload_path'] = $cFile1;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;

            // Cargamos la configuración del Archivo 1
            $this->upload->initialize($config);

            // Subimos archivo 1
            if ($this->upload->do_upload('filecolilla')) {

                $data1 = $this->upload->data();
            } else {
                echo $this->upload->display_errors();
            }
        }

        if (@$_FILES['doccolilla']['name'] != '') {
            $cFile2 = $cFile . $cTipo2 . "/";
            if (!is_dir($cFile2)) {
                mkdir($cFile2, 0777, true);
            }

            // Configuración para el Archivo 1
            $config['upload_path'] = $cFile2;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;

            // Cargamos la configuración del Archivo 1
            $this->upload->initialize($config);

            // Subimos archivo 1
            if ($this->upload->do_upload('doccolilla')) {
                $data2 = $this->upload->data();
            } else {
                echo $this->upload->display_errors();
            }
        }
        @$dataArray = array($data1, $data2);
        return $dataArray;
    }

    /**
     * Funcion para cargar la edición de mandamientos
     */
    function edit() {
        //Variables
        $this->data['post'] = $this->input->post();
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $gestion = $this->input->post('gestion');
        $this->data['gestion'] = $gestion;
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $aprobado = $this->input->post('aprobado');
        $revisado = $this->input->post('revisado');
        //Perfil del Usuario
        $perfil = $this->input->post('perfil');
        $asignado = explode("-", $this->input->post('asignado'));
        //Numero de Proceso
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        //Titulo del proceso
        $this->data['titulo'] = "<h2>Mandamiento de Pago</h2>";
        $this->data['instancia'] = "Mandamiento de Pago";
        //Tipo de Proceso
        $this->data['tipo'] = "mandamiento";
        //Documentos Firmado
        $this->data['rutaArchivo'] = './uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/mandamiento/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/mandamientopago/nits');
                } else {
                    $this->data['result'] = $this->mandamientopago_model->getMandamiento($ID);
                    $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                    $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        if ($this->data['result']->PLANTILLA != "") {

                            $cFile = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/mandamiento";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;

                            if (is_file($cFile)) {
                                $cMandamiento = read_template($cFile);
                            }
                        } else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('mandamiento')) == trim($cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }

                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/mandamiento/";
                            $cPlantilla = create_template($cRuta, $this->input->post('mandamiento'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }
                        $gestion = $this->input->post('gestion');

                        if ($gestion == 205 && $revisado == 'REVISADO') {
                            $respuesta = 206;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 205 && $revisado == 'DEVOLVER') {
                            $respuesta = 207;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 206 && $aprobado == 'RECHAZADO') {
                            $respuesta = 207;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 206 && $aprobado == 'REVISADO') {
                            $respuesta = 209;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 207 && $revisado == 'REVISADO') {
                            $respuesta = 205;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 207) {
                            $respuesta = 205;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 209) {
                            $respuesta = 208;
                            $file = $this->do_upload($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $cPlantilla = $file["upload_data"]["file_name"];
                            $fileElim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $nombre = $cPlantilla;
                            $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/mandamiento/';
                            $tipo_expediente = 7;
                            $subproceso = 'Editar Mandamiento';
                            //$expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                            $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'), FALSE, $this->input->post('numero_resolucion'), $this->input->post('fecha_resolucion'));
                        }/////////////////////////////
                        //echo $respuesta;die;
                        $this->datos['idgestion'] = trazarProcesoJuridico(108, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = 'Editar Mandamiento', $usuariosAdicionales = '');

                        $data = array(
                            'COMENTARIOS' => $this->input->post('comentarios'),
                            'FECHA_MODIFICA_MANDAMIENTO' => date("d/m/Y"),
                            'REVISADO' => $this->input->post('revisado'),
                            'APROBADO' => $this->input->post('aprobado'),
                            'ESTADO' => $respuesta,
                            'NUM_RESOLUCION' => $this->input->post('colilla'),
                            'FECHA_RESOLUCION' => $this->input->post('fechaonbase'),
                            'PLANTILLA' => $cPlantilla,
                            'COD_GESTIONCOBRO' => $this->datos['idgestion']
                        );
                        if ($this->mandamientopago_model->editManda($data, $this->input->post('id')) == TRUE) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El mandamiento de pago se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }

                    $this->data['ID'] = $this->input->post('clave');
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/mandamiento";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tipnymce.jquery.min.js'
                    );

                    $cod_mandamiento = $this->data['result']->COD_MANDAMIENTOPAGO;
                    //ssecho $cod_mandamiento;
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($cod_mandamiento, $this->input->post('cod_coactivo'));
//echo "<pre>"; print_r($this->data['titulos'] ); die();
                    $this->template->load($this->template_file, 'mandamientopago/mandamientopago_edit', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la edicion de citación al deudor
     */
    function editCitResosa() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        //$this->data['titulo'] = "<h2>Notificaci&oacute;n personal</h2>";
        $this->data['titulo'] = "<h2>Citación para Notificación Resolución seguir adelante con la ejecución</h2>";
        $this->data['tipo'] = "correoresosa";
        $this->data['instancia'] = "Citación Personal";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/correoresosa/";
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }

                        if ($bGenera == TRUE) {
                            $cRuta = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "correoresosa/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 1436;
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Enviada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1436;
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Entregada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1435;
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Rechazada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 1433;
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 1433;
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 1437;
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Entregada';
                                $estado_notificacion = 'ACTIVO';
                                $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $elim;
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = 'Notificación Personal de Resolucion a Seguir Adelante de Acuerdo de Pago Devuelta';
                                if ($this->input->post('motivo') == 4) {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'INACTIVO';
                                    $respuesta = 1439;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/correoresosa/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Resolucion Seguir Adelante';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                } else {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'ACTIVO';
                                    $respuesta = 1438;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/resosa/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Resolucion Seguir Adelante';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                }
                                break;
                        }
                        //echo $respuesta;die;
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $estado,
                            'OBSERVACIONES' => $this->input->post('observacion'),
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        $notifica = $this->input->post('notifica');
                        $consulta = $this->mandamientopago_model->editAvi($data, $notifica);

                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(665, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = 'Editar Notificacion Personal', $usuariosAdicionales = '');

                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion']
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $cod_mandamiento = $this->data['result']->COD_MANDAMIENTOPAGO;
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($cod_mandamiento, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editCit() {
      
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Citación Notificaci&oacute;n personal</h2>";
        $this->data['tipo'] = "citacion";
        $this->data['instancia'] = "Citación Personal";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/citacion/";
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));

             
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacion";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacion";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }

                        if ($bGenera == TRUE) {
                            $cRuta = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "citacion/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 221;
                                $mensaje = 'Notificación de Mandamiento de Pago por Publicación Aviso Enviado';
                                //$asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 744;
                                $mensaje = 'Notificación de Mandamiento de Pago por Publicación Aviso Aprobado';
                                //$asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 952;
                                $mensaje = 'Notificacion de Mandamiento de Pago por Publicacion Aviso No Aprobado';
                                //$asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 220;
                                $mensaje = 'Notificación de Mandamiento de Pago por Publicación Aviso Generado';
                                //$asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 220;
                                $mensaje = 'Notificación de Mandamiento de Pago por Publicación Aviso Generado';
                                //$asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 223;
                                $mensaje = 'Notificación de Mandamiento de Pago por Publicación Aviso Publicada';
                                //$asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $nombre = $file[1]['file_name'];
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Citacion';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                $cPlantilla = $elim;
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = 'Notificación de Mandamiento de Pago por Publicación Aviso Devuelto';
                                //$asignado = $this->input->post('iduser');
                                if ($this->input->post('motivo') == 4) {
                                    $respuesta = 208;
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Citacion';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'INACTIVO';
                                } else {
                                    $respuesta = 222;
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Citacion';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'ACTIVO';
                                }
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $estado,
                            'OBSERVACIONES' => $this->input->post('observacion'),
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        $notifica = $this->input->post('notifica');
                        $consulta = $this->mandamientopago_model->editAvi($data, $notifica);

                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(112, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = 'Editar Notificacion Personal', $usuariosAdicionales = '');

                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion']
                                    //'ASIGNADO_A' => $asignado,
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacion";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    //Citación de Resolución de Excepción
    function editCitex() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Citación de Resolución de Excepción</h2>";
        $this->data['tipo'] = "citacionex";
        $this->data['instancia'] = "Citacion Personal de Excepcion";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/citacionex/";
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '1', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '1', '0', 'INACTIVO');
                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionex";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionex";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionex/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 239;
                                $mensaje = 'Citación de Resolución de Excepción Enviada';
                                //$asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1032;
                                $mensaje = 'Citación de Resolución de Excepción Aprobado';
                                //$asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1033;
                                $mensaje = 'Citación de Resolución de Excepción No Aprobado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 238;
                                $mensaje = 'Citación de Resolución de Excepción Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 238;
                                $mensaje = 'Citación de Resolución de Excepción Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 241;
                                $mensaje = 'Citación de Resolución de Excepción Entregada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $elim;
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = 'Citación de Resolución de Excepción Devuelta';
//                                    $asignado = $this->input->post('iduser');
                                if ($this->input->post('motivo') == 4) {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'INACTIVO';
                                    $respuesta = 1021;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Pagina';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                } else {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'ACTIVO';
                                    $respuesta = 240;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Pagina';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                }
                                break;
                        }
                        //echo $estado.'----'.$respuesta;die; 
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '1',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );
                        $consulta = $this->mandamientopago_model->editAvi($data, $this->input->post('notifica'));
                        //var_dump($consulta);die;
                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(117, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                                'EXCEPCION' => '1',
                                    //'ASIGNADO_A' => $asignado,
                            );
                            $update = $this->mandamientopago_model->editMandamientoExc($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                    $regional = $this->data['permiso'][0]['COD_REGIONAL'];

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionex";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editCitrec() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Citación de Resolución de Recurso</h2>";
        $this->data['tipo'] = "citacionrec";
        $this->data['instancia'] = "Citacion Personal de Recurso";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/citacionrec/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '1', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPERSONAL, '0', '1', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionrec";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionrec";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "citacionrec/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 261;
                                $mensaje = 'Citación de Resolución de Citación Enviada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1086;
                                $mensaje = 'Citación de Resolución de Citación Aprobada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1087;
                                $mensaje = 'Citación de Resolución de Citación No Aprobada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 260;
                                $mensaje = 'Citación de Resolución de Citación Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 260;
                                $mensaje = 'Citación de Resolución de Citación Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 263;
                                $mensaje = 'Citación de Resolución de Citación Entregada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                $nombre = $file[1]['file_name'];
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/citacionrec/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Citacion de Recurso';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = $mensaje = 'Citación de Resolución de Citación Devuelta';
//                                    $asignado = $this->input->post('iduser');
                                if ($this->input->post('motivo') == 4) {
                                    $respuesta = 267;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/citacionrec/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Citacion de Recurso';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                    $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $file;
                                    $estado_notificacion = 'INACTIVO';
                                } else {
                                    $respuesta = 262;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/citacionrec/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Citacion de Recurso';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                    $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $file;
                                    $estado_notificacion = 'ACTIVO';
                                }
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPERSONAL,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '1',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        $consulta = $this->mandamientopago_model->editAvi($data, $this->input->post('notifica'));
                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(112, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/citacionrec";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editCorreoex() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Notificación de Excepción por Correo</h2>";
        $this->data['tipo'] = "correoexc";
        $this->data['instancia'] = "Notificación de Excepción por Correo";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/correoexc/";
        $this->data['gestion'] = $gestion;
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '1', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '1', '0', 'INACTIVO');
                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoexc";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoexc";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "correoexc/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 243;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Enviado';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1070;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Aprobado';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1071;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo No Aprobado';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 242;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Generada';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 242;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Generada';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 245;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Enviada';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Devuelto';
                                $asignado = $this->input->post('iduser');
                                if ($this->input->post('motivo') == 4) {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'INACTIVO';
                                    $respuesta = 240;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Pagina';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                } else {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'ACTIVO';
                                    $respuesta = 244;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Pagina';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                }
                                break;
                        }
//                            echo $mensaje."<br>";
//                            echo $asignado;die;
                        //echo $respuesta;die;
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONCORREO,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '1',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );
                        $consulta = $this->mandamientopago_model->editAvi($data, $this->input->post('notifica'));
                        //var_dump($consulta);die;
                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(117, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                                'EXCEPCION' => '1',
                            );
                            $update = $this->mandamientopago_model->editMandamientoExc($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');


                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoexc";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editCorResosa() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Notificación de Excepción por Correo</h2>";
        $this->data['tipo'] = "correoresosa";
        $this->data['instancia'] = "Notificación de Excepción por Correo";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/correoresosa/";
        $this->data['gestion'] = $gestion;
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '1', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '1', '0', 'INACTIVO');
                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "correoresosa/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 1443;
                                $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Enviada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1443;
                                $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Entregada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1442;
                                $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Rechazada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 1440;
                                $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 1440;
                                $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 1444;
                                $mensaje = 'Notificación de Resolución de Excepción por Correo Enviada';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = 'Notificación por Correo de Resolucion a Seguir Adelante de Acuerdo de Pago Devuelta';
                                $asignado = $this->input->post('iduser');
                                if ($this->input->post('motivo') == 4) {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'INACTIVO';
                                    $respuesta = 1446;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/correoresosa/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Pagina';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                } else {
                                    $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $elim;
                                    $estado_notificacion = 'ACTIVO';
                                    $respuesta = 1445;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/correoresosa/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Pagina';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                }
                                break;
                        }
//                          
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONCORREO,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '1',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );
                        $consulta = $this->mandamientopago_model->editAvi($data, $this->input->post('notifica'));
                        //var_dump($consulta);die;
                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(666, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                                'EXCEPCION' => '1',
                            );
                            $update = $this->mandamientopago_model->editMandamientoExc($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');


                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correoresosa";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editEdicto() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Edicto</h2>";
        $this->data['tipo'] = "edicto";
        $this->data['instancia'] = "Aviso de Fijación";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/edicto/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave')); 
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONEDICTO, '0', '0', 'ACTIVO');


                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/edicto";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "edicto/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }
                        //echo $estado;die;
                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 270;
                                $mensaje = 'Notificación de Resolución de Recurso por Edicto Fijado';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1088;
                                $mensaje = 'Notificación de Resolución de Recurso por Edicto Aprobada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1089;
                                $mensaje = 'Notificación de Resolución de Recurso por Edicto No Aprobada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 268;
                                $mensaje = 'Notificación de Resolución de Recurso por Edicto Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 268;
                                $mensaje = 'Notificación de Resolución de Recurso por Edicto Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 271;
                                $mensaje = 'Notificación de Resolución de Recurso por Edicto Desfijado';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/edicto/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Edicto';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));

                                break;
                        }
                        //echo $respuesta;die;
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONEDICTO,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );
                        $consulta = $this->mandamientopago_model->editAvi($data, $this->input->post('notifica'));
                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(112, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/edicto";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamiento_edit_edicto', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    //Resolución que Resuelve Excepciones
    function editExc() {
        //Variables
        $this->data['post'] = $this->input->post();
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $gestion = $this->input->post('gestion');
        $this->data['gestion'] = $gestion;
        $aprobado = $this->input->post('aprobado');
        $revisado = $this->input->post('revisado');
        //Perfil del Usuario
        $perfil = $this->input->post('perfil');
        //Numero de Proceso
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        //Titulo del proceso
        $this->data['titulo'] = "<h2>Resolución que Resuelve Excepciones</h2>";
        $this->data['tipo'] = "excepcion";
        $this->data['instancia'] = "Resolución que Resuelve Excepciones";
        //Documentos Firmado
        $this->data['rutaArchivo'] = './uploads/mandamientos/pdf/' . $this->input->post('cod_coactivo') . '/excepcion/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->data['result'] = $this->mandamientopago_model->getRecursoExcep($ID);
                    $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                    $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {

                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/excepcion";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;

                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }
                        if (trim($this->input->post('mandamiento')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "excepcion/";
                            $cPlantilla = create_template($cRuta, $this->input->post('mandamiento'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }


                        if ($gestion == 1018 && $revisado == 'REVISADO') {
                            $respuesta = 1019;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 1018 && $revisado == 'DEVOLVER') {
                            $respuesta = 1020;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 1020 && $revisado == 'REVISADO') {
                            $respuesta = 1018;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 1019 && $aprobado == 'RECHAZADO') {
                            $respuesta = 1020;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 1019 && $aprobado == 'REVISADO') {
                            $respuesta = 1022;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 1020) {
                            $respuesta = 1018;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 1022) {
                            echo "aca";
                            $respuesta = 1021;
                            $file = $this->do_upload($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $cPlantilla = $file["upload_data"]["file_name"];
                            $nombre = $file["upload_data"]['file_name'];
                            $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/excepcion/';
                            $tipo_expediente = 9;
                            $subproceso = 'Editar Resolucion';
                            $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                        }
                        //echo $this->input->post('id')."<->".$respuesta;die;

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        $this->datos['idgestion'] = trazarProcesoJuridico(116, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');
                        $data = array(
                            'COMENTARIOS' => $this->input->post('comentarios'),
                            'ASIGNADO_A' => ID_USER,
                            'REVISADO' => $this->input->post('revisado'),
                            'APROBADO' => $this->input->post('aprobado'),
                            'COD_ESTADO' => $respuesta,
                            'PLANTILLA' => $cPlantilla,
                            'NUM_RESOLUCION' => $this->input->post('numreso'),
                            'FECHA_RESOLUCION' => $this->input->post('fecharesolu'),
                            'COD_GESTIONCOBRO' => $this->datos['idgestion']
                        );
                        $datoMan = array(
                            'ESTADO' => $respuesta,
                            //'ASIGNADO_A' => $asignado[0],
                            'COD_GESTIONCOBRO' => $this->datos['idgestion']
                        );
                        $insert = $this->mandamientopago_model->editResolucion($data, $this->input->post('id'));

                        if ($insert == TRUE) {
                            $this->mandamientopago_model->editMandamiento($datoMan, $this->input->post('id'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Resolución de Excepción se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }

                    $this->data['permiso'] = $this->mandamientopago_model->permiso();
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $regional = $this->data['permiso'][0]['COD_REGIONAL'];


                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['ID'] = $this->input->post('clave');
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/excepcion";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopago/mandamientopago_edit', $this->data);
                    //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_excep', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editLevanta() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $this->data['fiscalizacion'] = $this->input->post('cod_fiscalizacion');
        $estado = $this->input->post('id_estado');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['cod_respuesta'] = $this->input->post('gestion');
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['instancia'] = "Cierre de Proceso";
        $this->data['tipo'] = "levanta";
        $this->data['titulo'] = '<h2>Auto de Cierre de Proceso</h2>';
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/pdf/levanta/";
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $nit = $this->input->post('nit');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $ID = $this->input->post('clave');
                $this->data['result'] = $this->mandamientopago_model->getNotificaCautelares($ID, $this->TIPONOTIFICACIONMEDIDA);
                $this->data['resultMedida'] = $this->mandamientopago_model->getMedidaCautelar($this->data['fiscalizacion'], '0');

                $this->load->library('form_validation');
                $this->data['custom_error'] = '';

                if (!isset($_POST['codigo'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/levanta";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        if (is_file($cFile))
                            $cMandamiento = read_template($cFile);
                    } else {
                        $cMandamiento = "";
                    }

                    if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                        $bGenera = FALSE;
                    } else
                        $bGenera = TRUE;
                    if ($bGenera == TRUE) {
                        unlink($cFile);
                        $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/";
                        $cRuta .= "levanta/";
                        $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                    } else {
                        $cPlantilla = $this->data['result']->PLANTILLA;
                    }
                    @$file = $this->do_uploadDoc($this->input->post('cod_fiscalizacion'), 'colilla', $this->input->post('tipo'));

                    $documento = $file[1]['file_name'];
                    $colilla = $file[0]['file_name'];

                    if (@$documento == '') {
                        @$documento = ' ';
                    }
                    if (@$colilla == '') {
                        @$$colilla = ' ';
                    }

//                            public $ESTADONOTIFICACIONGENERADA    = '1';
//                            public $ESTADONOTIFICACIONAPROBADA    = '2';
//                            public $ESTADONOTIFICACIONANOAPROBADA = '3';
//                            public $ESTADONOTIFICACIONAREVISADA   = '4';
//                            public $ESTADONOTIFICACIONENVIADO     = '5';
//                            public $ESTADONOTIFICACIONENTREGADO   = '6';
//                            public $ESTADONOTIFICACIONDEVUELTO    = '7';   
                    switch ($estado) {
                        case $this->ESTADONOTIFICACIONGENERADA:
                            $estado = 252;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Creado';
                            $asignado = $asignado[0];
                            break;
                        case $this->ESTADONOTIFICACIONAPROBADA:
                            $estado = 253;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Pre-Aprobado';
                            $asignado = $asignado[0];
                            break;
                        case $this->ESTADONOTIFICACIONANOAPROBADA:
                            $estado = 254;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Rechazado';
                            $asignado = $asignado[0];
                            break;
                        case $this->ESTADONOTIFICACIONAREVISADA:
                            $estado = 1058;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Revisada';
                            $asignado = $asignado[0];
                            break;
                        case $this->ESTADONOTIFICACIONENTREGADO:
                            $file = $this->elimTxt($this->input->post('cod_fiscalizacion'), $this->input->post('tipo'));
                            $cPlantilla = $file;
                            $estado = 255;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Aprobado y Firmado';
                            $asignado = $this->input->post('iduser');
//                                    $this->load->model('expediente_model');
//                                    $this->expediente_model->guarda_expediente($this->input->post('cod_fiscalizacion'), $estado, $file["upload_data"]["file_name"], $file["upload_data"]["file_path"], FALSE, FALSE, 2, 'Auto de Cierre de Proceso', ID_USER);   
                            $medida = array(
                                'BLOQUEO' => '1'
                            );
                            $cautelares = $this->mandamientopago_model->edit('MC_MEDIDASCAUTELARES', $medida, 'COD_MEDIDACAUTELAR', $this->input->post('medida'));
                            break;
                        case $this->ESTADONOTIFICACIONDEVUELTO:
                            $estado = 1338;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Devuelto';
                            $asignado = $asignado[0];
                            break;
                        case $this->ESTADONOTIFICACIONENVIADO:
                            $estado = 1337;
                            $mensaje = 'Auto de Levantamiento de Medidas Cautelares Aprobado';
                            $asignado = $asignado[0];
                            break;
                    }

                    $data = array(
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONMEDIDA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                        'COD_ESTADO' => $this->input->post('id_estado'),
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                        'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_fiscalizacion') . '/pdf/colilla/',
                        'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_fiscalizacion') . '/pdf/' . $this->input->post('tipo') . '/',
                        'NOMBRE_DOC_CARGADO' => $documento,
                        'NOMBRE_COL_CARGADO' => $colilla,
                        'COD_COMUNICACION' => $this->input->post('comunicacion'),
                        'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $cPlantilla
                    );

                    if ($this->mandamientopago_model->editAvi('AVISONOTIFICACION', $data, 'COD_AVISONOTIFICACION', $this->input->post('notifica')) == TRUE) {
                        $this->datos['idgestioncobro'] = trazar(121, $estado, $this->input->post('cod_fiscalizacion'), $this->input->post('nit'), $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $mensaje);
                        $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
                        $dato = array(
                            'ESTADO' => $estado,
                            'ASIGNADO_A' => $asignado,
                            'COD_GESTIONCOBRO' => $this->datos['idgestion']
                        );
                        $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('clave'));
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Medida Cautelar se ha modificado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    } else {
                        $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        echo $this->data['custom_error'];
                    }
                }
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                if ($this->data['result']->PLANTILLA != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/levanta";
                    $cFile .= "/" . $this->data['result']->PLANTILLA;
                    $this->data['plantilla'] = read_template($cFile);
                }

                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagomedidas/mandamientopago_edit_medidas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editPag() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Notificaci&oacute;n en p&aacute;gina Web del SENA</h2>";
        $this->data['tipo'] = "pagina";
        $this->data['instancia'] = "Notificación por Página Web";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/pagina/";
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '0', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '0', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pagina";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadImg($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pagina";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "pagina/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        $post = $this->input->post();
                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 224;
                                $mensaje = $this->input->post('observacion');
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 225;
                                $mensaje = $this->input->post('observacion');
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 225;
                                $mensaje = $this->input->post('observacion');
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 226;
                                $mensaje = $this->input->post('observacion');
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA :
                                $respuesta = 224;
                                $mensaje = $this->input->post('observacion');
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 227;
//                                    $nombre = $file[1]['file_name'];
//                                    $ruta = 'uploads/mandamientos/'.$this->input->post('cod_coactivo').'/pdf/pagina/';
//                                    $tipo_expediente = 7;
//                                    $subproceso = 'Editar Pagina';                                        
//                                    $expediente = $this->guarda_expediente($respuesta,$nombre,$ruta,$tipo_expediente,$subproceso,ID_USER,$this->input->post('cod_coactivo'));
                                $mensaje = $this->input->post('observacion');
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                //$this->guarda_expediente($post);
                                $cPlantilla = $file;
                                break;
                        }
                        //echo $mensaje;die;
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPAGINA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        $notifica = $this->input->post('notifica');
                        $consulta = $this->mandamientopago_model->editAvi($data, $notifica);

                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(113, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');

                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                                    //'ASIGNADO_A' => $asignado,
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');


                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pagina";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_pagina', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function paginaResosa() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Notificación de Excepción en página Web del SENA</h2>";
        $this->data['tipo'] = "paginaresosa";
        $this->data['instancia'] = "Notificación de Excepción por Página Web";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/paginaresosa/';
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));   
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaresosa";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaresosa/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 1447;
                    $mensaje = 'Notificacion Pagina Web de Resolucion a Seguir Adelante de Acuerdo de Pago Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(667, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPAGINA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '1',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificaci&oacute;n web se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_pagina', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function edPaginaExc() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Notificaci&oacute;n de Excepción en p&aacute;gina Web del SENA</h2>";
        $this->data['tipo'] = "paginaexc";
        $this->data['instancia'] = "Notificación por Página Web";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->data['cod_coactivo'] . "/pdf/mandamiento/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->data['cod_coactivo'] . "/mandamiento/paginaexc/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '1', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '1', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaexc";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadImg($this->input->post('cod_fiscalizacion'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/paginaexc";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "paginaexc/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }


                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 231;
                                $mensaje = 'Notificación de Resolución de Excepción en Pagina Web Enviado';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1080;
                                $mensaje = 'Notificación de Resolución de Excepción en Pagina Web Aprobado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1081;
                                $mensaje = 'Notificación de Resolución de Excepción en Pagina Web No Aprobado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA :
                                $respuesta = 230;
                                $mensaje = 'Notificación de Resolución de Excepción en Pagina Web Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 230;
                                $mensaje = 'Notificación de Resolución de Excepción en Pagina Web Generada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $elim;
                                $respuesta = 233;
                                $mensaje = 'Notificación de Resolución de Excepción en Pagina Web Enviada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPAGINA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '1',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        if ($this->mandamientopago_model->editAvi($data, $this->input->post('notifica')) == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(115, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                    $regional = $this->data['permiso'][0]['COD_REGIONAL'];

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaexc";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_pagina', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function edPaginaResosa() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Notificaci&oacute;n de Excepción en p&aacute;gina Web del SENA</h2>";
        $this->data['tipo'] = "paginaresosa";
        $this->data['instancia'] = "Notificación por Página Web";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->data['cod_coactivo'] . "/pdf/mandamiento/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->data['cod_coactivo'] . "/mandamiento/paginaresosa/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/mandamientopago/nits');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '1', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '1', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaresosa";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadImg($this->input->post('cod_fiscalizacion'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/paginaresosa";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "paginaresosa/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONANOAPROBADA:
                                $respuesta = 1449;
                                $mensaje = 'Notificacion Pagina Web de Resolucion a Seguir Adelante de Acuerdo de Pago Rechazada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1450;
                                $mensaje = 'Notificacion Pagina Web de Resolucion a Seguir Adelante de Acuerdo de Pago Rechazada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 1447;
                                $mensaje = 'Notificacion Pagina Web de Resolucion a Seguir Adelante de Acuerdo de Pago Rechazada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $elim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $elim;
                                $respuesta = 1450;
                                $mensaje = 'Notificacion Pagina Web de Resolucion a Seguir Adelante de Acuerdo de Pago Enviada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPAGINA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '1',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        if ($this->mandamientopago_model->editAvi($data, $this->input->post('notifica')) == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(667, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                    $regional = $this->data['permiso'][0]['COD_REGIONAL'];

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaresosa";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_pagina', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editCor() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Notificación por Correo</h2>";
        $this->data['tipo'] = "correo";
        $this->data['instancia'] = "Notificación por Correo";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/correo/";
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/mandamientopago/nits');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '0', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONCORREO, '0', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correo";
                        $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correo";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "correo/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        //echo $estado;die;
                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENTREGADO :
                                $respuesta = 219;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Entregada';
//                                    $asignado = $this->input->post('iduser');
                                $nombre = $file[1]['file_name'];
                                $ruta = 'uploads/mandamientos/37/pdf/citacion/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Citacion';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 217;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Enviado';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 958;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Aprobado';
                                //$asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 959;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo No Aprobado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 216;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Generado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 216;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Generado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 216;
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Generado';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $mensaje = 'Notificación de Mandamiento de Pago por Correo Devuelto';
                                if ($this->input->post('motivo') == 4) {
                                    $respuesta = 223;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Correo';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                    $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $file;
                                    $estado_notificacion = 'INACTIVO';

//                                        $asignado = $this->input->post('iduser');
                                } else {
                                    $respuesta = 218;
                                    $nombre = $file[1]['file_name'];
                                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pagina/';
                                    $tipo_expediente = 7;
                                    $subproceso = 'Editar Correo';
                                    $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                    $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                    $cPlantilla = $file;
                                    $estado_notificacion = 'ACTIVO';

//                                        $asignado = $this->input->post('iduser');
                                }
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONCORREO,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );
//                        echo $estado;
//                        echo $respuesta;die;
                        $notifica = $this->input->post('notifica');
                        $consulta = $this->mandamientopago_model->editAvi($data, $notifica);

                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(111, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion']
                                    //'ASIGNADO_A' => $asignado,
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');


                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/correo";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editActaExc() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Acta de Notificación Personal - Excepciones</h2>";
        $this->data['tipo'] = "actaexc";
        $this->data['instancia'] = "Acta Excepción Notificación Personal";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/actaexc/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '1', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '1', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaexc";
                        $cFile .= "/" . $this->data['acta']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaexc";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "actaexc/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 660;
                                $mensaje = 'Acta de Notificación de Excepcion Personal Enviada';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1038;
                                $mensaje = 'Acta de Notificación de Excepcion Personal Aprobado';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 659;
                                $mensaje = 'Acta de Notificación de Excepcion Personal Generada';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 659;
                                $mensaje = 'Acta de Notificación de Excepcion Personal Generada';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 1039;
                                $mensaje = 'Acta de Notificación de Excepcion Personal No Aprobado';
                                $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaexc/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Citacion';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 662;
                                $mensaje = 'Acta de Notificación de Excepcion Persona Entregada';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaexc/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Acta Citacion';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $respuesta = 661;
                                $mensaje = 'Acta de Notificación de Excepcion Personal Devuelta';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaexc/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Acta Citacion';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => date("d/m/Y"),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '1',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        if ($this->mandamientopago_model->editAvi($data, $this->input->post('notifica')) == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(247, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaexc";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_acta', $this->data);
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));

                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editActa() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Acta de Notificación personal</h2>";
        $this->data['tipo'] = "acta";
        $this->data['instancia'] = "Acta Notificación Personal";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pdf/acta/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '0', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '0', 'INACTIVO');

                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/acta";
                        $cFile .= "/" . $this->data['acta']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/acta";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "acta/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 211;
                                $mensaje = 'Acta de Notificación Persona Enviada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 210;
                                $mensaje = 'Acta de Notificación Persona Generada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 950;
                                $mensaje = 'Acta de Notificación Personal Aprobada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 951;
                                $mensaje = 'Acta de Notificación Personal No Aprobada';
//                                    $asignado = $this->input->post('asignado');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 210;
                                $mensaje = 'Acta de Notificación Personal Enviada';
//                                    $asignado = $this->input->post('asignado');
                                $filem = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $filem;
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/citacion/';
                                $tipo_expediente = 3;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 213;
                                $mensaje = 'Acta de Notificación Personal Entregada';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $filem = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $filem;
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/citacion/';
                                $tipo_expediente = 3;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                            case $this->ESTADONOTIFICACIONDEVUELTO:
                                $respuesta = 212;
                                $mensaje = 'Acta de Notificación Personal Devuelta';
//                                    $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $filem = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $filem;
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/citacion/';
                                $tipo_expediente = 3;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                        }
                        //echo $mensaje;die;
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => date("d/m/Y"),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );


                        $consulta = $this->mandamientopago_model->editAvi($data, $this->input->post('notifica'));
                        if ($consulta == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(109, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');


                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/acta";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_acta', $this->data);
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editActaRec() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $this->data['gestion'] = $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Acta de Notificación de Recurso</h2>";
        $this->data['tipo'] = "actaRec";
        $this->data['instancia'] = "Acta Notificación Personal de Recurso";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/pdf/actaRec/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
               $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));   
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '1', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '1', 'INACTIVO');
                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaRec";
                        $cFile .= "/" . $this->data['acta']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaRec";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "actaRec/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }
                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONENVIADO:
                                $respuesta = 273;
                                $mensaje = 'Acta de Notificación de Excepcion Personal Enviada';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 273;
                                $mensaje = 'Acta de Notificación Personal que Resuelve el Recurso Pre - Aprobada';
                                $asignado = explode("-", $this->input->post('asignado'));
                                $asignado = $asignado[0];
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA:
                                $respuesta = 272;
                                $mensaje = 'Acta de Notificación Personal que Resuelve el Recurso Generada';
                                $asignado = explode("-", $this->input->post('asignado'));
                                $asignado = $asignado[0];
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 272;
                                $mensaje = 'Acta de Notificación Personal que Resuelve el Recurso Generada';
                                $asignado = explode("-", $this->input->post('asignado'));
                                $asignado = $asignado[0];
                                $estado_notificacion = 'ACTIVO';
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaRec/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $respuesta = 274;
                                $mensaje = 'Acta de Notificación Personal que Resuelve el Recurso Rechazada';
                                $asignado = explode("-", $this->input->post('asignado'));
                                $asignado = $asignado[0];
                                $estado_notificacion = 'ACTIVO';
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaRec/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 275;
                                $mensaje = 'Acta de Notificación Personal que Resuelve el Recurso Aprobado y Firmado';
                                $asignado = $this->input->post('iduser');
                                $estado_notificacion = 'ACTIVO';
                                $file = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaRec/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                        }
//                            $this->load->model('expediente_model');
//                            $this->expediente_model->guarda_expediente($this->input->post('cod_fiscalizacion'), $estado, $file["upload_data"]["file_name"], $file["upload_data"]["file_path"], FALSE, FALSE, 2, 'Acta de Notificación de Recurso', ID_USER);
                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => date("d/m/Y"),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '1',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        if ($this->mandamientopago_model->editAvi($data, $this->input->post('notifica')) == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(126, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaRec";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_actaRec', $this->data);
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editActaResosa() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $this->data['gestion'] = $estado = $this->input->post('id_estado');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Acta de Notificación de Resolucion ordenando seguir adelante</h2>";
        $this->data['tipo'] = "actaresosa";
        $this->data['instancia'] = "Acta Notificación Personal de Recurso";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/pdf/actaresosa/";
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['resolucion'] = $this->mandamientopago_model->getRecursoResol($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));      
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '1', 'ACTIVO');
                    $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONACTA, '0', '1', 'INACTIVO');
                    if ($this->data['inactivo'] != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaresosa";
                        $cFile .= "/" . $this->data['acta']->PLANTILLA;
                        @$this->data['plantillaInac'] = read_template($cFile);
                    }

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_uploadDoc($this->input->post('cod_coactivo'), 'colilla', $this->input->post('tipo'));
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaresosa";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/";
                            $cRuta .= "actaresosa/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }
                        //echo $estado;die;
                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONANOAPROBADA:
                                $respuesta = 1456;
                                $mensaje = 'Notificacion Acta Resolucion Seguir Adelante Rechazada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $respuesta = 1454;
                                $mensaje = 'Notificacion Acta Resolucion Seguir Adelante Generada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $respuesta = 1455;
                                $mensaje = 'Notificacion Acta Resolucion Seguir Adelante Pre-Aprobada';
                                $estado_notificacion = 'ACTIVO';
                                break;
                            case $this->ESTADONOTIFICACIONENTREGADO:
                                $respuesta = 1457;
                                $mensaje = 'Notificacion Acta Resolucion Seguir Adelante Entregada';
                                $estado_notificacion = 'ACTIVO';
                                $nombre = $documento;
                                $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/actaresosa/';
                                $tipo_expediente = 7;
                                $subproceso = 'Editar Acta';
                                $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONACTA,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => date("d/m/Y"),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                            'EXCEPCION' => '0',
                            'RECURSO' => '1',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'ESTADO_NOTIFICACION' => $estado_notificacion,
                            'PLANTILLA' => $cPlantilla
                        );

                        if ($this->mandamientopago_model->editAvi($data, $this->input->post('notifica')) == TRUE) {
                            $this->datos['idgestion'] = trazarProcesoJuridico(668, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                            $dato = array(
                                'ESTADO' => $respuesta,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            );
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error...</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/actaresosa";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    //$this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_actaRec', $this->data);
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_citacion', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editAvi() {
        $this->data['post'] = $this->input->post();
        $this->data['titulo'] = "<h2>Notificación de Mandamiento de Pago por Aviso</h2>";
        $this->data['rutaColilla'] = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/pdf/colilla/";
        $this->data['rutaDocumento'] = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/pdf/aviso";
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $this->data['fiscalizacion'] = $this->input->post('cod_fiscalizacion');
        $gestion = $this->input->post('gestion');
        $this->data['instancia'] = "Notificacion por Aviso";
        $this->data['tipo'] = "aviso";
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->data['result'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONAVISO, '0', '0', 'ACTIVO');
                    $this->load->library('form_validation');
                    $file = $this->do_uploadDoc($this->input->post('cod_fiscalizacion'), 'colilla', $this->input->post('tipo'));
                    $this->data['custom_error'] = '';

                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/aviso";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        }else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/";
                            $cRuta .= "aviso/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        $documento = $file[1]['file_name'];
                        $colilla = $file[0]['file_name'];

                        if (@$documento == '') {
                            @$documento = ' ';
                        }
                        if (@$colilla == '') {
                            @$$colilla = ' ';
                        }

                        $estado = $this->input->post('id_estado');
                        switch ($estado) {
                            case $this->ESTADONOTIFICACIONGENERADA:
                                $estado = 229;
                                $mensaje = 'Notificación de Mandamiento de Pago por Aviso Publicada';
                                $asignado = $this->input->post('iduser');
                                $file = $this->elimTxt($this->input->post('cod_fiscalizacion'), $this->input->post('tipo'));
                                $cPlantilla = $file;
                                break;
                            case $this->ESTADONOTIFICACIONAPROBADA:
                                $estado = 741;
                                $mensaje = 'Notificación de Mandamiento de Pago por Aviso Aprobado';
                                $asignado = $this->input->post('asignado');
                                break;
                            case $this->ESTADONOTIFICACIONANOAPROBADA :
                                $estado = 742;
                                $mensaje = 'Notificación de Mandamiento de Pago por Aviso No Aprobado';
                                $asignado = $this->input->post('asignado');
                                break;
                            case $this->ESTADONOTIFICACIONAREVISADA :
                                $estado = 228;
                                $mensaje = 'Notificación de Mandamiento de Pago por Aviso Generado';
                                $asignado = $this->input->post('asignado');
                                break;
                        }

                        $data = array(
                            'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONAVISO,
                            'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                            'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                            'COD_ESTADO' => $this->input->post('id_estado'),
                            'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                            'OBSERVACIONES' => $this->input->post('observacion') . ' ',
                            'DOC_COLILLA' => 'uploads/mandamientos/' . $this->input->post('cod_fiscalizacion') . '/pdf/colilla/',
                            'DOC_FIRMADO' => 'uploads/mandamientos/' . $this->input->post('cod_fiscalizacion') . '/pdf/' . $this->input->post('tipo') . '/',
                            'NOMBRE_DOC_CARGADO' => $documento,
                            'NOMBRE_COL_CARGADO' => $colilla,
                            'EXCEPCION' => '0',
                            'RECURSO' => '0',
                            'ESTADO_NOTIFICACION' => 'ACTIVO',
                            'COD_COMUNICACION' => $this->input->post('comunicacion'),
                            'PLANTILLA' => $cPlantilla
                        );


                        if ($this->mandamientopago_model->editAvi('AVISONOTIFICACION', $data, 'COD_AVISONOTIFICACION', $this->input->post('notifica')) == TRUE) {
                            $this->datos['idgestioncobro'] = trazar(114, $estado, $this->input->post('cod_fiscalizacion'), $this->input->post('nit'), $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $mensaje);
                            $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];

                            $dato = array(
                                'ESTADO' => $estado,
                                'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                                'ASIGNADO_A' => $asignado,
                            );
                            $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El aviso se ha modificado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');

                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/aviso";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }

                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_edit_aviso', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la edición de resoluciones de seguir adelante
     */
    function editRes() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $nit = $this->input->post('nit');
        $aprobado = $this->input->post('aprobado');
        $revisado = $this->input->post('revisado');
        $perfil = $this->input->post('perfil');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['titulo'] = "<h2>Resolucion Ordenando Seguir Adelante</h2>";
        $this->data['tipo'] = "adelante";
        $this->data['instancia'] = "Resolver Excepciones";
        $this->data['rutaArchivo'] = './uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/adelante/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));     
                $ID = $this->input->post('clave');
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->data['result'] = $this->mandamientopago_model->getMandamientoAdelante($ID);
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/adelante";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;
                            if (is_file($cFile))
                                $cMandamiento = read_template($cFile);
                        } else {
                            $cMandamiento = "";
                        }
                        if (trim($this->input->post('mandamiento')) == trim($cMandamiento)) {
                            $bGenera = FALSE;
                        } else
                            $bGenera = TRUE;
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/";
                            $cRuta .= "mandamientos/" . $this->input->post('cod_coactivo') . "/adelante/";
                            $cPlantilla = create_template($cRuta, $this->input->post('mandamiento'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }

                        if ($gestion == 246 && $revisado == 'REVISADO') {
                            $respuesta = 247;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 246 && $revisado == 'DEVOLVER') {
                            $respuesta = 248;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 247 && $aprobado == 'RECHAZADO') {
                            $respuesta = 248;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 247 && $aprobado == 'REVISADO') {
                            $respuesta = 743;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 248 && $revisado == 'REVISADO') {
                            $respuesta = 246;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 248) {
                            $respuesta = 246;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 743) {
                            $respuesta = 249;
                            $file = $this->do_upload($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $cPlantilla = $file["upload_data"]["file_name"];
                            $fileElim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $nombre = $cPlantilla;
                            $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/adelante/';
                            $tipo_expediente = 7;
                            $subproceso = 'Editar Seguir Adelante';
                            $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                        }/////////////////////////////
                        //echo $respuesta;die;
                        $this->datos['idgestion'] = trazarProcesoJuridico(119, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');

                        $data = array(
                            'COMENTARIOS' => $this->input->post('comentarios'),
                            //'ASIGNADO_A' => ID_USER,
                            'REVISADO' => $this->input->post('revisado'),
                            'APROBADO' => $this->input->post('aprobado'),
                            'ESTADO' => $respuesta,
                            'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                            'FECHA_MODIFICA_ADELANTE' => date("d/m/Y"),
                            'PLANTILLA' => $cPlantilla
                        );
                        $dato = array(
                            'ESTADO' => $respuesta,
                            'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        );
                        $consulta = $this->mandamientopago_model->editMandaAd($data, $this->input->post('clave'));
                        if ($consulta == TRUE) {
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Resolucion se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                        }
                    }
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');

                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['ID'] = $this->input->post('clave');
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/adelante/";
                        $cFile .= $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/tinymce/tinymce.min.js',
                        'js/tinymce/tinymce.jquery.min.js'
                    );
                    $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                    $this->template->load($this->template_file, 'mandamientopago/mandamientopago_edit', $this->data);
                    //$this->template->load($this->template_file, 'mandamientopagoexcepcion/mandamientopago_editRes', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function editRec() {
        //Variables
        $this->data['post'] = $this->input->post();
        $ID = $this->input->post('clave');
        $nit = $this->input->post('nit');
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $aprobado = $this->input->post('aprobado');
        $revisado = $this->input->post('revisado');
        //Perfil del Usuario
        $perfil = $this->input->post('perfil');
        $asignado = explode("-", $this->input->post('asignado'));
        //Numero de Proceso
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        //Titulo del proceso
        $this->data['titulo'] = "<h2>Resolución que Resuelve Recursos</h2>";
        $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));

        $this->data['instancia'] = "Resolver Recursos";
        //Tipo de Proceso
        $this->data['tipo'] = "recurso";
        //Documentos Firmado
        $this->data['rutaArchivo'] = './uploads/mandamientos/pdf/' . $this->input->post('cod_coactivo') . '/recurso/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                } else {
                    $this->data['result'] = $this->mandamientopago_model->getRecurso($ID);
                    $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                    $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));   
                    $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    if (!isset($_POST['codigo'])) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $file = $this->do_upload($this->input->post('cod_coactivo'), 'resolrecurso');
                        if ($this->data['result']->PLANTILLA != "") {
                            $cFile = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/recurso";
                            $cFile .= "/" . $this->data['result']->PLANTILLA;

                            if (is_file($cFile)) {
                                $cMandamiento = read_template($cFile);
                            }
                        } else {
                            $cMandamiento = "";
                        }

                        if (trim($this->input->post('mandamiento')) == trim($cMandamiento)) {
                            $bGenera = FALSE;
                        } else {
                            $bGenera = TRUE;
                        }

                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/recurso/";
                            $cPlantilla = create_template($cRuta, $this->input->post('mandamiento'));
                        } else {
                            $cPlantilla = $this->data['result']->PLANTILLA;
                        }


                        if ($gestion == 264 && $revisado == 'REVISADO') {
                            $respuesta = 265;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 264 && $revisado == 'DEVOLVER') {
                            $respuesta = 266;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 265 && $aprobado == 'RECHAZADO') {
                            $respuesta = 266;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 265 && $aprobado == 'REVISADO') {
                            $respuesta = 1085;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 266 && $revisado == 'REVISADO') {
                            $respuesta = 264;
                            $cPlantilla = $cPlantilla;
                        } else if ($gestion == 266) {
                            $respuesta = 264;
                            $cPlantilla = $cPlantilla;
                        }/////////////////////////////
                        else if ($gestion == 1085) {
                            $respuesta = 267;
                            $file = $this->do_upload($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $cPlantilla = $file["upload_data"]["file_name"];
                            $fileElim = $this->elimTxt($this->input->post('cod_coactivo'), $this->input->post('tipo'));
                            $nombre = $file["upload_data"]["file_name"];
                            $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/recurso/';
                            $tipo_expediente = 3;
                            $subproceso = 'Editar Recurso';
                            $expediente = $this->guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                        }/////////////////////////////

                        $this->datos['idgestion'] = trazarProcesoJuridico(124, $respuesta, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('comentarios'), $usuariosAdicionales = '');

                        $data = array(
                            'COMENTARIOS' => $this->input->post('comentarios'),
                            'ASIGNADO_A' => ID_USER,
                            'REVISADO' => $this->input->post('revisado'),
                            'APROBADO' => $this->input->post('aprobado'),
                            'COD_ESTADO' => $respuesta,
                            'PLANTILLA' => $cPlantilla,
                            'NUM_RESOLUCION' => $this->input->post('colilla'),
                            'FECHA_RESOLUCION' => $this->input->post('fechaonbase'),
                            'COD_GESTIONCOBRO' => $this->datos['idgestion']
                        );
                        $dato = array(
                            'ESTADO' => $respuesta,
                            'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        );
                        $consulta = $this->mandamientopago_model->editResolExcep($data, $this->input->post('id'));
                        if ($consulta == TRUE) {
                            $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Recurso se ha editado correctamente.</div>');
                            redirect(base_url() . 'index.php/bandejaunificada/index');
                        } else {
                            $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            echo $this->data['custom_error'];
                        }
                    }

                    $this->data['permiso'] = $this->mandamientopago_model->permiso();
                    $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');

                    $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                    $this->data['ID'] = $this->input->post('clave');
                    if ($this->data['result']->PLANTILLA != "") {
                        $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/recurso";
                        $cFile .= "/" . $this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');

                    $this->template->load($this->template_file, 'mandamientopago/mandamientopago_edit', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function elimTemp($proceso, $tipo) {
        $cRuta = "./uploads/mandamientos/temporal/" . $proceso . "/" . $tipo . "/";
        $cRuta2 = "./uploads/mandamientos/temporal/" . $proceso . "/";
        if (is_dir($cRuta)) {
            $handle = opendir($cRuta);
            while ($file = readdir($handle)) {
                if (is_file($cRuta . $file)) {
                    unlink($cRuta . $file);
                    if (is_dir($cRuta2)) {
                        $handle2 = opendir($cRuta2);
                        while ($file2 = readdir($handle2)) {
                            if (is_dir($cRuta2 . $file2)) {
                                rmdir($cRuta2 . $file2); //                                             
                            }
                        }
                    }
                }
            }
        }
    }

//Fin elimTemp
    //$cFile  = "./uploads/mandamientos/".$this->input->post('cod_fiscalizacion')."/mandamientopago";

    function elimTxt($proceso, $tipo) {
        $cRuta = "./uploads/mandamientos/" . $proceso . "/" . $tipo . "/";
        $cRuta2 = "./uploads/mandamientos/" . $proceso . "/pdf/" . $tipo . "/";
        if (is_dir($cRuta)) {
            $handle = opendir($cRuta);
            while ($file = readdir($handle)) {
                if (is_file($cRuta . $file)) {
                    unlink($cRuta . $file);
                    if (is_dir($cRuta2)) {
                        $handle2 = opendir($cRuta2);
                        while ($file2 = readdir($handle2)) {
                            if (is_file($cRuta2 . $file2)) {
                                return $file2;
                            }
                        }
                    }
                }
            }
        }
    }

//Fin elimTemp

    /*
     * Funcion que carga respuesta de gestion por mandamiento
     */

    function gestion() {
        $this->data['post'] = $this->input->post();
        $this->data['empresa'] = $this->session->userdata['datosempresa'];
        $this->data['gestion'] = $this->input->post('estado_gestion');
        $this->data['nit'] = $this->input->post('nit');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['nombre'] = $this->input->post('name');
        $this->data['mandamiento'] = $this->input->post('clave');
        $this->data['url1'] = $this->input->post('url1');
        $this->data['url2'] = $this->input->post('url2');
        $nit = $this->input->post('nit');
        $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
        $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  

        if ($this->ion_auth->logged_in()) {
            $this->load->view('mandamientopago/mandamientopago_gestion', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guarda_gestion() {
        $gestion = $this->data['gestion'] = $this->input->post('gestion');
        $this->data['nit'] = $this->input->post('nit');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['razon'] = $this->input->post('razon');
        $this->data['clave'] = $this->input->post('clave');
        $tipo = $this->data['tipo'] = $this->input->post('tipo');
        //var_dump($_POST);die;
        if ($gestion == 222 || $gestion == 223) {
            if ($tipo == 's'):
                $respuesta = 112;
                $estado = 1458;
            else:
                $respuesta = 112;
                $estado = 1459;
            endif;
        }
        $this->datos['idgestion'] = trazarProcesoJuridico($respuesta, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = 'Validacion de Citacion', $usuariosAdicionales = '');
        $dato = array(
            'ESTADO' => $estado,
            'COD_GESTIONCOBRO' => $this->datos['idgestion'],
        );

        $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('clave'));
        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha modificado el Mandamiento de Pago.</div>');
        redirect(base_url() . 'index.php/bandejaunificada/index');
    }

    function guarda_expediente($respuesta, $nombre, $ruta, $tipo_expediente, $subproceso, $usuario, $cod_coactivo) {
        $CI = & get_instance();
        $CI->load->model("Expediente_model");
        $model = new Expediente_model();
        $radicado = FALSE;
        $fecha_radicado = FALSE;
        $expediente = $model->agrega_expediente($respuesta, $nombre, $ruta, $radicado, $fecha_radicado, $tipo_expediente, $subproceso, $usuario, $cod_coactivo); //Guarda en la tabla expediente
        return $expediente;
    }

    function levanta() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['instancia'] = "Cierre de Proceso";
        $this->data['titulo'] = '<h2>Auto de Cierre de Proceso</h2>';
        $this->data['tipo'] = 'levanta';
        $this->data['fiscalizacion'] = $this->input->post('cod_fiscalizacion');
        $this->data['idmandamiento'] = $this->input->post('clave');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_fiscalizacion') . '/levanta/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $nit = $this->input->post('nit');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_fiscalizacion') . "/levanta/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }
                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 252;
                    $mensaje = 'Auto de Levantamiento de Medidas Cautelares Creado';

                    $this->datos['idgestioncobro'] = trazar(119, $estado, $this->input->post('cod_fiscalizacion'), $this->input->post('nit'), $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $mensaje);
                    $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
                    $fileElim = $this->elimTemp($this->input->post('cod_fiscalizacion'), $this->input->post('tipo'));
                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                        'ASIGNADO_A' => $asignado[0],
                    );
                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONMEDIDA,
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => $this->ESTADONOTIFICACIONGENERADA,
                        'OBSERVACIONES' => $this->input->post('comentarios'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'RECURSO' => '0',
                        'EXCEPCION' => '0',
                        'PLANTILLA' => $sFile
                    );
                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->edit('MANDAMIENTOPAGO', $dato, 'COD_MANDAMIENTOPAGO', $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El levantamiento de medidas cautelares se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->template->load($this->template_file, 'mandamientopago/mandamientopago_add', $this->data);
                //$this->template->load($this->template_file, 'mandamientopagomedidas/mandamientopago_levanta', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar los datos de un archivo
     */
    function load() {
        $this->data['post'] = $this->input->post();
        $plantilla = $this->input->post('plantilla');
        $proceso = $this->input->post('clave');
        $gestion = $this->input->post('gestion');
        if ($this->ion_auth->logged_in()) {
            if ($gestion == 205 || $gestion == 206 || $gestion == 207 || $gestion == 209) {
                $datos = '';
                $cFile = "uploads/mandamientos/" . $proceso . "/mandamiento/" . $plantilla;
                $datos = read_template($cFile);
                echo $datos;
            } else {
                $cFile = "uploads/mandamientos/" . $proceso . "/pdf/mandamiento/" . $plantilla;
                $this->data['pdf'] = $cFile;
                $this->data['proceso'] = $proceso;
                $this->load->view('mandamientopago/mandamientopago_view_pdf', $this->data);
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar los datos de un archivo
     */
    function loadRes() {
        $this->data['post'] = $this->input->post();
        $plantilla = $this->input->post('plantilla');
        $proceso = $this->input->post('clave');
        echo $gestion = $this->input->post('gestion');
        die;
        if ($this->ion_auth->logged_in()) {
            if ($gestion == 246 || $gestion == 247 || $gestion == 248) {
                $datos = '';
                $cFile = "uploads/mandamientos/" . $proceso . "/mandamiento/" . $plantilla;
                $datos = read_template($cFile);
                echo $datos;
            } else {
                $cFile = "uploads/mandamientos/" . $proceso . "/pdf/adelante/" . $plantilla;
                $this->data['pdf'] = $cFile;
                $this->data['proceso'] = $proceso;
                $this->load->view('mandamientopago/mandamientopago_view_pdf', $this->data);
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function medidas() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['instancia'] = "Medidas Cautelares";
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $nit = $this->input->post('nit');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));   
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
                $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
                $this->data['resultMedida'] = $this->mandamientopago_model->getMedidaCautelar($this->data['cod_coactivo'], '1');
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('cautelar', 'Existen medidas', 'required');
                $this->data['result'] = $this->mandamientopago_model->getMedidaCautelar($this->data['cod_coactivo']);
                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $obligacion = $this->input->post('obligacion');
                    switch ($obligacion) {
                        case '1':
                            $estado = 250;
                            $mensaje = 'Otras Obligaciones Existentes';
                            break;
                        case '0';
                            $estado = 251;
                            $mensaje = 'No Existen Otras Obligaciones';
                            break;
                    }

                    $this->datos['idgestion'] = trazarProcesoJuridico(120, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                    $data = array(
                        'APLICA_MEDIDA_CAUTELAR' => $this->input->post('cautelar'),
                        'FECHA_MEDIDA_CAUTERLAR' => date('d/m/Y'),
                        'ESTADO' => $estado,
                        'OBSERVACIONES_MEDIDAS' => $this->input->post('observacion'),
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                    );

                    $insert = $this->mandamientopago_model->editMandaCautelar($data, $this->input->post('idmandamiento'));

                    $medida = array(
                        'BLOQUEO' => '1'
                    );

                    $cautelares = $this->mandamientopago_model->edit('MC_MEDIDASCAUTELARES', $medida, 'COD_MEDIDACAUTELAR', $this->input->post('medida'));

                    if ($insert == FALSE) {
                        $this->template->load($this->template_file, 'mandamientopagomedidas/mandamientopago_medidas', $this->data);
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA MANDAMIENTOPAGO<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La verificaci&oacute;n de medidas cautelares se ha efectuado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $this->template->load($this->template_file, 'mandamientopagomedidas/mandamientopago_medidas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    //Carga la pantalla de los 
    function nits() {
        $this->data['post'] = $this->input->post();
        //$fiscaizacion = $this->data['fiscalizacion'] = '48';
        //echo $fiscaizacion = $this->input->post('fiscalizacion')."<br>";  
        $cod_proceso = $this->input->post('cod_coactivo');
        //$cod_proceso = 37;
        $this->data['cod_respuesta'] = $this->input->post('cod_respuesta');
        $this->data['perfil'] = PERFIL;
        @$user = $this->data['permiso'][0]['IDUSUARIO'];
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->template->set('title', 'NITS');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                //$nuevos = $this->data['nuevos'] = $this->mandamientopago_model->getNitsMandamiento($fiscaizacion);                                                
                $nuevos = $this->data['nuevos'] = $this->mandamientopago_model->getNitsMandamiento($cod_proceso);
                $this->data['nit'] = $nuevos[0]['IDENTIFICACION'];
                $this->data['cod_coactivo'] = $nuevos[0]['COD_PROCESO'];
                $this->data['razon'] = $nuevos[0]['NOMBRE'];
                $this->data['cod_respuesta'] = $nuevos[0]['ESTADO'];
                $this->data['mandamiento'] = $nuevos[0]['COD_MANDAMIENTOPAGO'];
                $this->data['excepcion'] = $nuevos[0]['TIPO_EXCEPCION'];
                $this->data['recurso'] = $nuevos[0]['TIPO_RECURSO'];
                $this->data['fecha'] = $nuevos[0]['FECHA_MANDAMIENTO'];
                $this->data['cautelar'] = $nuevos[0]['APLICA_MEDIDA_CAUTELAR'];
                $this->data['direccion'] = $nuevos[0]['DIRECCION'];
                $this->data['url'] = $nuevos[0]['URLGESTION'];

                $this->load->library('datatables');
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'mandamientopago/mandamientopago_index', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pagina() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Notificaci&oacute;n en p&aacute;gina Web del SENA</h2>";
        $this->data['tipo'] = "pagina";
        $this->data['instancia'] = "Notificación por Página Web";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/pagina/';
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pagina";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/pagina/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 224;
                    $mensaje = 'Notificación de Mandamiento de Pago por Página Web Generado';

                    $this->datos['idgestion'] = trazarProcesoJuridico(113, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $mensaje, $usuariosAdicionales = '');

                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPAGINA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '0',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));

                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificaci&oacute;n web se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_pagina', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function paginaExc() {
        $this->data['post'] = $this->input->post();
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $ID = $this->input->post('clave');
        $gestion = $this->input->post('gestion');
        $asignado = explode("-", $this->input->post('asignado'));
        $this->data['titulo'] = "<h2>Notificación de Excepción en página Web del SENA</h2>";
        $this->data['tipo'] = "paginaexc";
        $this->data['instancia'] = "Notificación de Excepción por Página Web";
        $this->data['rutaTemporal'] = './uploads/mandamientos/temporal/' . $this->input->post('cod_coactivo') . '/paginaexc/';
        $this->data['resolucion'] = $this->mandamientopago_model->getResolucionExcep($ID);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['inactivo'] = $this->mandamientopago_model->getNotifica($ID, $this->TIPONOTIFICACIONPAGINA, '0', '0', 'INACTIVO');

                if ($this->data['inactivo'] != "") {
                    $cFile = "uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaexc";
                    $cFile .= "/" . $this->data['inactivo']->PLANTILLA;
                    @$this->data['plantillaInac'] = read_template($cFile);
                }

                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    // Guardamos la Citacion en el archivo txt
                    $cRuta = "./uploads/mandamientos/" . $this->input->post('cod_coactivo') . "/paginaexc/";
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta, 0777, true);
                    }

                    $sFile = create_template($cRuta, $this->input->post('notificacion'));
                    $estado = 230;
                    $mensaje = 'Notificación de Resolución de Excepción en Pagina Web Generada';

                    $this->datos['idgestion'] = trazarProcesoJuridico(115, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                    $fileElim = $this->elimTemp($this->input->post('cod_coactivo'), $this->input->post('tipo'));

                    $dato = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    );

                    $data = array(
                        'COD_AVISONOTIFICACION' => $this->mandamientopago_model->getSequence('AVISONOTIFICACION', 'AvisoNotifica_cod_avisonot_SEQ.NEXTVAL') + 1,
                        'COD_TIPONOTIFICACION' => $this->TIPONOTIFICACIONPAGINA,
                        'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                        'FECHA_NOTIFICACION' => date("d/m/Y"),
                        'COD_ESTADO' => '1',
                        'OBSERVACIONES' => $this->input->post('observacion'),
                        'COD_MANDAMIENTOPAGO' => $this->input->post('idmandamiento'),
                        'DOC_COLILLA' => ' ',
                        'NOMBRE_DOC_CARGADO' => ' ',
                        'EXCEPCION' => '1',
                        'RECURSO' => '0',
                        'ESTADO_NOTIFICACION' => 'ACTIVO',
                        'PLANTILLA' => $sFile
                    );

                    $insert = $this->mandamientopago_model->addAviso($data);
                    $update = $this->mandamientopago_model->editMandamiento($dato, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA AVISONOTIFICACION<BR>';
                    } else {

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificaci&oacute;n web se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = '7';
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($ID, $this->input->post('cod_coactivo'));
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->template->load($this->template_file, 'mandamientopagoaviso/mandamientopago_pagina', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la verificación del pago
     */
    function pago() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $nit = $this->input->post('nit');
        $user = $this->ion_auth->user()->row();
        $this->data['instancia'] = "Verificación de Pago";
        $this->data['tipo'] = "pago";
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {

                    $data = array(
                        'COD_EXCEPCION' => $this->mandamientopago_model->getSequence('EXCEPCIONESNOTIFICACION', 'ExcepcionesNo_cod_excepcio_SEQ.NEXTVAL') + 1,
                        'COD_MANDAMIENTO' => $this->input->post('idmandamiento'),
                        'FECHA_EXCEPCION' => date('d/m/Y'),
                        'PAGO_REALIZADO' => $this->input->post('pago'),
                        'PRESENTA_EXCEPCIONES' => $this->input->post('excepcion'),
                        'OBSERVACIONES' => $this->input->post('observacion'),
                    );

                    if ($this->input->post('pago') == '1') {
                        $estado = 214;
                        $tipo = 110;
                        $mensaje = 'Deudor Pago la Deuda';
                    } else {
                        if ($this->input->post('excepcion') == '0') {
                            $estado = 235;
                            $tipo = 116;
                            $mensaje = 'No Presenta excepciones';
                        } else {
                            $estado = 215;
                            $excep = 1;
                            $tipo = 110;
                            $mensaje = 'Deudor No Pago la Deuda';
                        }
                    }

                    $this->datos['idgestion'] = trazarProcesoJuridico($tipo, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    $datoMan = array(
                        'ESTADO' => $estado,
                        'EXCEPCION' => $excep,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                    );
                    $insert = $this->mandamientopago_model->addExcepciones($data);
                    $updateManda = $this->mandamientopago_model->editMandamientoExc($datoMan, $this->input->post('idmandamiento'));
                    if ($insert == FALSE) {
                        $this->template->load($this->template_file, 'mandamientopagoexcepcion/mandamientopago_pago', $this->data);
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA EXCEPCIONESNOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La verificaci&oacute;n de pago se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }

                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoexcepcion/mandamientopago_pago', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar la verificación del pago
     */
    function pagoEdit() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $nit = $this->input->post('nit');
        $user = $this->ion_auth->user()->row();
        $excepcion = $this->input->post('Tipoexcepcion');
        $this->data['instancia'] = "Verificación de Pago";
        $this->data['tipo'] = "pago";
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $this->data['gestion'] = $this->mandamientopago_model->getRespuesta($gestion);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('notificacion', 'Notificacion', 'required|trim|xss_clean');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if (!isset($_POST['idmandamiento'])) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $pago = 0;
                    $exce = 1;
                    $file = $this->do_upload($this->input->post('cod_coactivo'), 'pago');

                    switch ($excepcion) {
                        case '1':
                            $estado = 236;
                            $excep = 1;
                            $tipo_excep = 1;
                            $mensaje = 'Excepción Procede';
                            break;
                        case '2';
                            $estado = 237;
                            $excep = 1;
                            $tipo_excep = 0;
                            $mensaje = 'Excepción Procede Parcialmente';
                            break;
                        case '3':
                            $estado = 237;
                            $excep = 1;
                            $tipo_excep = 0;
                            $mensaje = 'Excepción No Procede';
                            break;
                    }

                    $nombre = $file["upload_data"]['file_name'];
                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/pago/';
                    $tipo_expediente = 10;
                    $subproceso = 'Veriicar Pago';
                    $expediente = $this->guarda_expediente($estado, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                    $data = array(
                        'NUM_FOLIOS' => $this->input->post('folios'),
                        'PRESENTA_EXCEPCIONES' => '1',
                        'RUTA_ARCHIVO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                        'NOM_DOCUMENTO' => $file['upload_data']['file_name'],
                        'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                        'OBSERVACIONES' => $this->input->post('observacion'),
                    );

                    $this->datos['idgestion'] = trazarProcesoJuridico(116, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    //echo $tipo_excep.'-'.$mensaje;die;
                    $datoMan = array(
                        'ESTADO' => $estado,
                        'EXCEPCION' => $excep,
                        'TIPO_EXCEPCION' => $tipo_excep,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                    );

                    $insert = $this->mandamientopago_model->editExcepciones('EXCEPCIONESNOTIFICACION', $data, 'COD_MANDAMIENTO', $this->input->post('idmandamiento'));
                    $updateManda = $this->mandamientopago_model->editMandamientoTipoExc($datoMan, $this->input->post('idmandamiento'));

                    if ($updateManda == FALSE) {
                        $this->template->load($this->template_file, 'mandamientopago/nits', $this->data);
                        echo 'ERROR AL INSERTAR DATOS EN LA TABLA EXCEPCIONESNOTIFICACION<BR>';
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La verificaci&oacute;n de Excepcion se ha creado correctamente.</div>');
                        redirect(base_url() . 'index.php/bandejaunificada/index');
                    }
                }

                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['motivos'] = $this->mandamientopago_model->getSelect('MOTIVODEVOLUCION', 'COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION', "IDESTADO = 1 AND NATURALEZA = 'N' ", 'MOTIVO_DEVOLUCION');
                $regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo = 7;
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagoexcepcion/mandamientopago_pago_edit', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para generar el documento PDF con el componente tinymce
     */
    function pdf() {
        if ($this->ion_auth->logged_in()) {
            $post = $this->input->post();
            $this->load->library('form_validation');
            $this->load->library("tcpdf/tcpdf");
            $html = $this->input->post('mandamiento');
            $html = str_replace("'", "\"", $html);
            $nombre_pdf = '';
            $titulo = $this->input->post('titulo_doc');
            $tipo = $this->input->post('tipo_documento');
            $data[0] =$tipo;
            $data[1] =  $titulo;
            createPdfTemplateOuput($nombre_pdf, $html, false, $data);
        exit();
            //$this->template->load($this->template_file, 'mandamientopago/mandamientopago_pdf', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

//Fin PDF

    /*
     * Funcion que carga respuesta de gestion por mandamiento
     */

    function respuesta() {
        $idmandamiento = $this->input->post('clave');
        $idgestion = $this->input->post('estado_gestion');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['respuesta'] = $this->mandamientopago_model->getRespuesta($idgestion);
                $this->load->view('mandamientopago/mandamientopago_respuesta', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * Funcion que carga respuesta de gestion por mandamiento
     */

    function respuestaRes() {
        $idmandamiento = $this->input->post('clave');
        $idgestion = $this->input->post('estado_gestion');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->data['respuesta'] = $this->mandamientopago_model->getRespuesta($idgestion);
                $this->load->view('mandamientopago/mandamientopago_respuesta', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function recurso() {
        $this->data['post'] = $this->input->post();
        $this->data['gestion'] = $gestion = $this->input->post('gestion');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $this->data['instancia'] = 'Verificación de Recurso';
        $this->data['titulo'] = '<h2>Verificación de Recurso</h2>';
        $this->data['tipo'] = 'recurso';
        $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['codrespuesta'] = $this->input->post('cod_respuesta');
        $asignado = $this->input->post('iduser');
        $user = $this->ion_auth->user()->row();
        $nit = $this->input->post('nit');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('recurso', 'Presenta recurso', 'required');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));
                if (!isset($_POST['idmandamiento'])) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
                } else {
                    if ($this->input->post('recurso') == '1') {
                        $estado = 257;
                        $mensaje = "Presento Recurso";
                    } else {
                        $estado = 256;
                        $mensaje = "No presento Recurso";
                    }
                    $data = array(
                        'COD_RECURSO' => $this->mandamientopago_model->getSequence('EXCEPCIONESNOTIFICACION', 'ExcepcionesNo_cod_excepcio_SEQ.NEXTVAL') + 1,
                        'COD_MANDAMIENTO' => $this->input->post('idmandamiento'),
                        'FECHA_RECURSO' => date('d/m/Y'),
                        'PRESENTA_RECURSOS' => $this->input->post('excepcion'),
                        'OBSERVACIONES' => $this->input->post('observacion'),
                    );
                    // se inserta la trazabilidad en la tabla gestioncobro

                    $this->datos['idgestion'] = trazarProcesoJuridico(122, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');

                    //Arreglo que contiene los datos para modificar el estado y gestion del mandamiento de pago
                    $datoMan = array(
                        'ESTADO' => $estado,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                    );
                    $insert = $this->mandamientopago_model->addRecursos($data);
                    $updateManda = $this->mandamientopago_model->editMandamiento($datoMan, $this->input->post('idmandamiento'));
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La verificaci&oacute;n de recurso se ha creado correctamente.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array('css/chosen.css' => 'screen');
                $this->data['javascripts'] = array('js/chosen.jquery.min.js', 'js/tinymce/tinymce.min.js', 'js/tinymce/tinymce.jquery.min.js');
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->template->load($this->template_file, 'mandamientopagomedidas/mandamientopago_recurso', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function recursoEdit() {
        $this->data['post'] = $this->input->post();
        $gestion = $this->input->post('gestion');
        $this->data['gestiones'] = $this->mandamientopago_model->getRespuesta($gestion);
        $user = $this->ion_auth->user()->row();
        $nit = $this->input->post('nit');
        $mandamiento = $this->input->post('clave');
        $this->data['instancia'] = 'Verificación de Recurso';
        $this->data['titulo'] = '<h2>Verificación de Recurso</h2>';
        $this->data['tipo'] = 'recurso';
        $this->data['cod_coactivo'] = $this->input->post('cod_coactivo');
        $this->data['recursoNotif'] = $this->mandamientopago_model->getRecursoNotifi($mandamiento);
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $this->form_validation->set_rules('presenta', 'Tipo recurso', 'required');
                $this->data['informacion'] = $this->mandamientopago_model->getEmpresa($nit);
                $this->data['resolucion_man']=$this->mandamientopago_model->Resolución($this->input->post('clave'));  
                if ($this->form_validation->run() == false) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>');
                } else {
                    $file = $this->do_upload($this->input->post('cod_fiscalizacion'), 'recurso');

                    //////se asigna el valor del estado para insertar en la trazabilidad                            
                    switch ($this->input->post('presenta')) {
                        case 1:
                            $estado = 258;
                            $mensaje = "Recurso Confirmado";
                            $asignado = $this->input->post('iduser');
                            $tipo_recurso = 0;
                            break;
                        case 2:
                            $estado = 259;
                            $mensaje = "Recurso Revocado";
                            $asignado = $this->input->post('iduser');
                            $tipo_recurso = 1;
                            break;
                        case 3:
                        case 2:
                            $estado = 259;
                            $mensaje = "Recurso Revocado";
                            $asignado = $this->input->post('iduser');
                            $tipo_recurso = 1;
                            break;
                    }

                    $nombre = $file['upload_data']['file_name'];
                    $ruta = 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/recurso/';
                    $tipo_expediente = 10;
                    $subproceso = 'Editar Solicitud Recurso';
                    $expediente = $this->guarda_expediente($estado, $nombre, $ruta, $tipo_expediente, $subproceso, ID_USER, $this->input->post('cod_coactivo'));
                    $data = array(
                        'NUM_FOLIOS' => $this->input->post('folios'),
                        'RUTA_ARCHIVO' => 'uploads/mandamientos/' . $this->input->post('cod_coactivo') . '/pdf/' . $this->input->post('tipo') . '/',
                        'NOM_DOCUMENTO' => $file['upload_data']['file_name'],
                        'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                        'OBSERVACIONES' => $this->input->post('observacion'),
                    );

                    $insert = $this->mandamientopago_model->editRecursos($data, $this->input->post('idmandamiento'));

                    // se inserta la trazabilidad en la tabla gestioncobro
                    $this->datos['idgestion'] = trazarProcesoJuridico(122, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = $this->input->post('observacion'), $usuariosAdicionales = '');
                    // se valida si el usuario presento recurso
                    //Arreglo que contiene los datos para modificar el estado y gestion del mandamiento de pago
                    $datoMan = array(
                        'ESTADO' => $estado,
                        'TIPO_RECURSO' => $tipo_recurso,
                        'COD_GESTIONCOBRO' => $this->datos['idgestion']
                    );
                    $update = $this->mandamientopago_model->editMandamientoRecurso($datoMan, $this->input->post('idmandamiento'));
                    //$updateManda = $this->mandamientopago_model->edit('MANDAMIENTOPAGO',$datoMan,'COD_MANDAMIENTOPAGO',$this->input->post('idmandamiento'));
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La verificaci&oacute;n de recurso se ha creado correctamente.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/tinymce/tinymce.min.js',
                    'js/tinymce/tinymce.jquery.min.js'
                );
                $this->data['concepto'] = $this->mandamientopago_model->getSelect('CONCEPTOSFISCALIZACION', 'COD_CPTO_FISCALIZACION,NOMBRE_CONCEPTO', '', 'NOMBRE_CONCEPTO');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['titulos'] = $this->mandamientopago_model->getTitulosMandamiento($this->input->post('idmandamiento'), $this->input->post('cod_coactivo'));
                $this->template->load($this->template_file, 'mandamientopagomedidas/mandamientopago_recurso_edit', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar el listado de Mandamientos con buscador
     */
    function resolucionLis() {
        $this->data['permiso'] = $this->mandamientopago_model->permiso();
        @$this->data['perfil'] = $this->data['permiso'][0]['IDGRUPO'];
        $this->data['numproceso'] = $this->input->post('numproceso');
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                //template data
                $this->template->set('title', 'Resoluciones');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                @$regional = $this->data['permiso'][0]['COD_REGIONAL'];
                $cargo1 = '8';
                $cargo2 = '7';
                $cargo = '8';
                $this->data['asignado'] = $this->mandamientopago_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo1, $cargo2, 'NOMBRES, APELLIDOS');
                $this->data['usuarios'] = $this->mandamientopago_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO', $regional, $cargo, 'NOMBRES, APELLIDOS');
                $this->data['estados'] = $this->mandamientopago_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'mandamientopagoexcepcion/mandamientopago_listex', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para generar el documento PDF con el componente tinymce
     */
    function temp() {
        $temporal = $this->input->post('temporal');
        $proceso = $this->input->post('cod_coactivo');
        $tipo = $this->input->post('tipo');

        $cRuta = "./uploads/mandamientos/temporal/" . $proceso . "/" . $tipo . "/";
        if (is_dir($cRuta)) {
            $handle = opendir($cRuta);
            while ($file = readdir($handle)) {
                if (is_file($cRuta . $file)) {
                    unlink($cRuta . $file);
                }
            }
            mkdir($cRuta, 0777, true);
            $sFile = create_template($cRuta, $temporal);
        }

        if (!is_dir($cRuta)) {
            mkdir($cRuta, 0777, true);
            $sFile = create_template($cRuta, $temporal);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' . ucfirst($tipo) . ' Creado Correctamente.</div>');
        redirect(base_url() . 'index.php/bandejaunificada/index');
    }

//Fin temp              

    /**
     * Funcion para cargar el la vista de adicion de archivos
     */
    function up() {
        $this->data['post'] = $this->input->post();
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $file = $this->do_upload($this->input->post('fiscalizacion'), 'mandamiento');
                $this->load->library('datatables');
                $this->load->model('mandamientopago_model');
                if (isset($file['error'])) {
                    $this->data['numProceso'] = $this->input->post('clave');
                    $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                    $this->template->set('title', 'Subir Documento Mandamiento');
                    $this->data['title'] = 'CARGUE MANDAMIENTO';
                    $this->data['stitle'] = 'Fecha Actual: ' . date("d-m-Y");
                    $this->data['class'] = 'error';
                    $this->data['message'] = $this->session->flashdata('message');
                    $this->data['mensaje'] = validation_errors();
                    $this->data['error'] = true;
                    $this->data['file_error'] = $file;
                    $this->data['custom_error'] = "";
                    $this->data['tipo'] = 'up';

                    $this->load->view('mandamientopago/mandamientopago_up', $this->data);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El archivo se subio correctamente.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para subir archivos.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion para cargar el la vista de adicion de archivos
     */
    function upRes() {
        $this->data['post'] = $this->input->post();
        if ($this->ion_auth->logged_in()) {
            $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $this->input->post('fiscalizacion');
                $file = $this->do_upload($this->input->post('fiscalizacion'), 'adelante');
                $this->load->library('datatables');
                $this->load->model('mandamientopago_model');
                if (isset($file['error'])) {
                    $this->data['numProceso'] = $this->input->post('clave');
                    $this->template->set('title', 'Subir Documento Mandamiento');
                    $this->data['title'] = 'CARGUE RESOLUCION';
                    $this->data['stitle'] = 'Fecha Actual: ' . date("d-m-Y");
                    $this->data['class'] = 'error';
                    $this->data['message'] = $this->session->flashdata('message');
                    $this->data['mensaje'] = validation_errors();
                    $this->data['error'] = true;
                    $this->data['file_error'] = $file;
                    $this->data['custom_error'] = "";
                    $this->data['tipo'] = 'upRes';

                    $this->load->view('mandamientopago/mandamientopago_upRes', $this->data);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El archivo se subio correctamente.</div>');
                    redirect(base_url() . 'index.php/bandejaunificada/index');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para subir archivos.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verificaMedida() {
        $this->data['post'] = $this->input->post();
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('mandamientopago/nits')) {
                $fiscalizacion = $this->input->post('cod_coactivo');
                $nit = $this->input->post('nit');
                $razon = $this->input->post('name');
                $mandamiento = $this->input->post('clave');
                $this->datos['idgestion'] = trazarProcesoJuridico(119, 1323, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = 'Verifica Existencia Medidas', $usuariosAdicionales = '');
                $datos = array(
                    'COD_FISCALIZACION' => $fiscalizacion,
                    'COD_GESTIONCOBRO' => $this->datos['idgestion'],
                    'FECHA_MEDIDAS' => date("d/m/Y")
                );

                $insert = $this->mandamientopago_model->verificaMedida($fiscalizacion, $mandamiento, $datos);

                if ($insert == TRUE) {
                    $estado = 1322;
                    $mensaje = 'Existen Medidas Cautelares';
                } else {
                    $estado = 1321;
                    $mensaje = 'No Existen Medidas Cautelares';
                }

                $this->datos['idValidacionGestion'] = trazarProcesoJuridico(122, $estado, '', $this->input->post('cod_coactivo'), '', '', '', $comentarios = 'Verifica Existencia Medidas', $usuariosAdicionales = '');
                $datoMan = array(
                    'ESTADO' => $estado,
                    'COD_GESTIONCOBRO' => $this->datos['idValidacionGestion']
                );

                $update = $this->mandamientopago_model->editMandamiento($datoMan, $mandamiento);
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Verificación Efectuada correctamente.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El archivo se subio correctamente.</div>');
                redirect(base_url() . 'index.php/bandejaunificada/index');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
