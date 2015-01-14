<?php

class Nulidadanaliza extends MY_Controller {

    private $controller = 'nulidadanaliza';
    private $fromMail = '';
    private $toMail = '';
    private $docs = 'uploads/verfpagosprojuridicos/docs_autosjuridicos/';
    private $docs_firmados = 'uploads/verfpagosprojuridicos/docs_autosjuridicos_firmados/';
    private $templates = 'uploads/templates/';
    private $route_pdf = 'uploads/verfpagosprojuridicos/pdf/';

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'template', 'traza_fecha'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('nulidadanaliza_model', '', TRUE);
        $this->load->model('verfpagosprojuridicosModel', '', TRUE);
        $this->load->model('correo_model', '', TRUE);
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

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
        $this->data['user'] = $this->ion_auth->user()->row();
        $GRUPO = $this->data['user']->IDGRUPO;
        switch ($GRUPO) {
            case 41:
                define("NOMBRE_GRUPO", "Secretario de Cobro Coactivo");
                break;
            case 42:
                define("NOMBRE_GRUPO", "Coordinador de Cobro Coactivo");
                break;
            case 43:
                define("NOMBRE_GRUPO", "Abogado de Cobro Coactivo");
                break;            
            default:
                define("NOMBRE_GRUPO", "Director Regional");
                break;
        }
        define("COD_GRUPO", $this->data['user']->IDUSUARIO);
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            $this->listEmpresas();
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTAR EMPRESAS CON FISCALIZACION
     */

    function listEmpresas() {
        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listEmpresas', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableEmpresas() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;

            $empresas = $this->nulidadanaliza_model->retrieveEmpresas($this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);
            $num_empresas = $this->nulidadanaliza_model->coutEmpresas($this->input->post('sSearch'));

            echo json_encode(array('aaData' => $empresas,
                'iTotalRecords' => $num_empresas,
                'iTotalDisplayRecords' => $num_empresas,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * LISTAR LAS FISCALIZACION DE CADA EMPRESA
     */

    function listFiscalizaciones() {

        if ($this->ion_auth->logged_in()) {
            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';
            $idgrupo = 43;

            $this->data['empresa'] = $this->nulidadanaliza_model->empresa($this->uri->segment(3));
            $this->data['idgrupo'] = $idgrupo;
            $this->template->load($this->template_file, $this->controller . '/listfiscalizaciones', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableFiscalizaciones() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $idgrupo = 61;

            $fiscalizaciones = $this->nulidadanaliza_model->retriveAllFiscaXEmp(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10, $this->uri->segment(3), $idgrupo);



            $num_fiscalizaciones = $this->nulidadanaliza_model->countFiscaXEmp($this->uri->segment(3), $this->input->post('sSearch'), $idgrupo);




            echo json_encode(array('aaData' => $fiscalizaciones,
                'iTotalRecords' => $num_fiscalizaciones,
                'iTotalDisplayRecords' => $num_fiscalizaciones,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function listNulidades() {

        if ($this->ion_auth->logged_in()) {
            $this->data['fiscalizacion'] = $this->nulidadanaliza_model->fiscalizacion($this->uri->segment(3));
            $this->data['empresa'] = $this->nulidadanaliza_model->empresa($this->data['fiscalizacion']->NIT_EMPRESA);
            $this->template->load($this->template_file, $this->controller . '/listnulidades', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidades() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->retrievetNulidades(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10, $this->uri->segment(3));



            $num_nulidades = $this->nulidadanaliza_model->countNulidades($this->input->post('sSearch'), $this->uri->segment(3));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function form() {
        if ($this->ion_auth->logged_in()) {

            $this->data['id_gestion_cobro'] = $this->uri->segment(3);
            $this->data['id_fiscalizacion'] = $this->nulidadanaliza_model->gestionCobro($this->uri->segment(3));
            $this->data['fiscalizacion'] = $this->nulidadanaliza_model->fiscalizacion($this->data['id_fiscalizacion']);
            $this->data['empresa'] = $this->nulidadanaliza_model->empresa($this->data['fiscalizacion']->NIT_EMPRESA);
            $this->data['fiscalizacion'] = $this->verfpagosprojuridicosModel->retriveFiscalizacion($this->data['id_fiscalizacion']);
            $this->data['fiscalizacion'] = $this->data['fiscalizacion'][0];
            $cod_fiscalizacion = $this->input->post('COD_FISCALIZACION');
            $cod_gestion = $this->input->post('COD_GESTION');
            $cod_nulidad = $this->input->post('COD_NULIDAD');

            $cod_fiscalizacion = 1;
            $cod_gestion = 1;

            $this->data['causales'] = $this->nulidadanaliza_model->listCausales();
            $this->data['secretarios'] = $this->nulidadanaliza_model->retrieveUsuariosXgrupo(41);

            $this->template->load($this->template_file, $this->controller . '/form', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function save() {

        if ($this->ion_auth->logged_in()) {

            $id_nulidad_old = $this->input->post('id_nulidad');
            $id_gestion = $this->input->post('ID_GESTION');
            $causales = (is_array($this->input->post('causalesIds'))) ? $this->input->post('causalesIds') : array();
            $cod_nuevos_ca = (is_array($this->input->post('nuevoCausales'))) ? $this->input->post('nuevoCausales') : array();
            $asignado_por = $this->ion_auth->user()->row()->IDUSUARIO;
            $asignado_a = $this->input->post('ASIGNADO_A');

            $cod_regional = $this->ion_auth->user()->row()->COD_REGIONAL;
            $cod_regional = 5;

            $existe_nulidad = $this->input->post('EXISTE_NULIDAD');
            //$cod_tipo_nulidad       = ($this->input->post('COD_TIPO_NULIDAD') == '' ) ? 1 : $this->input->post('COD_TIPO_NULIDAD');
            $causal_nulidad_temp = '';
            $cod_gestion = $this->input->post('ID_GESTION');
            $cod_nulidad = '';
            $fecha_radicacion = ( $id_nulidad_old == '') ? 'SYSDATE' : '';
            $fecha_revision = ( $id_nulidad_old == '') ? 'SYSDATE' : '';

            $id_nulidad = $this->nulidadanaliza_model->saveNulidad(
                    $existe_nulidad, $cod_regional, $cod_gestion, $cod_nulidad, $fecha_radicacion, $fecha_revision, $asignado_a, $asignado_por
            );

            if ($id_nulidad_old == '') {


                foreach ($cod_nuevos_ca as $id) {
                    $text = $this->input->post('nCualsalTex' . $id);

                    $id_insert = $this->nulidadanaliza_model->saveTipoCausal($id_nulidad, $text);
                    array_push($causales, $id_insert);
                }


                foreach ($causales as $id) {
                    $this->nulidadanaliza_model->saveCausalNulidad($id_nulidad, $id);
                }
            }

            $secretario_asignado = $this->nulidadanaliza_model->retrieveUsuario($asignado_a);

//             $fromMail  = $secretario_asignado->EMAIL;
//             $fromName  = $secretario_asignado->NOMBRES + ' ' + $secretario_asignado->APELLIDOS;
//             $toMail    = 'juridicos@sena.edu.co';
//             $subject   = 'Sistema de recaudo y cobro (nueva nulidad)';
//             $message   = 'Buenos dias ' . $fromName . '<br> se a creado una nueva nulidad, para revisar las nulidades creadas en el sistema de click <a href="' . base_url() . '/index.php/nulidadanaliza/listnulidadesanaliza">aqui</a>';
//             //$this->sendMail($fromMail, $fromName, $toMail, $subject, $message);
//             enviarcorreosena($toMail,$message,$subject);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function formGestion() {
        if ($this->ion_auth->logged_in()) {

            $cod_nulidad = $this->uri->segment(3);
            $this->data['id_nulidad'] = $cod_nulidad;
            $this->data['nulidad'] = $this->nulidadanaliza_model->retriveNulidad($cod_nulidad);
            $this->data['id_fiscalizacion'] = $this->nulidadanaliza_model->gestionCobro($this->data['nulidad']->COD_GESTION_COBRO);
            $this->data['fiscalizacion'] = $this->nulidadanaliza_model->fiscalizacion($this->data['id_fiscalizacion']);
            $this->data['empresa'] = $this->nulidadanaliza_model->empresa($this->data['fiscalizacion']->NIT_EMPRESA);
            $this->data['fiscalizacion'] = $this->verfpagosprojuridicosModel->retriveFiscalizacion($this->data['id_fiscalizacion']);
            $this->data['fiscalizacion'] = $this->data['fiscalizacion'][0];
            $this->data['causales'] = $this->nulidadanaliza_model->listCausalesNulidad($cod_nulidad);

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idgrupo = 41;
            $this->data['idgrupo'] = $idgrupo;


            $this->template->load($this->template_file, $this->controller . '/formgestion', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function saveGestion() {
        $cod_nulidad = $this->input->post('id_nulidad');
        $existe_nulidad = $this->input->post('EXISTE_NULIDAD');
        $cod_tipo_nulidad = $this->input->post('COD_TIPO_NULIDAD');

        $tipo_acto_administrativo = null;

        if (($existe_nulidad == 1) && ($cod_tipo_nulidad == 2)) {
            $tipo_acto_administrativo = 3;
        } else if (($existe_nulidad == 0) == ($cod_tipo_nulidad == 1)) {
            $tipo_acto_administrativo = 1;
        } else if (($existe_nulidad == 1) == ($cod_tipo_nulidad == 1)) {
            $tipo_acto_administrativo = 2;
        }

        $this->nulidadanaliza_model->saveGestion($cod_nulidad, $existe_nulidad, $cod_tipo_nulidad, $tipo_acto_administrativo);

        $nulidad = $this->nulidadanaliza_model->retriveNulidad($cod_nulidad);

        $secretario_asignado = $this->nulidadanaliza_model->retrieveUsuario($nulidad->ASIGNADA_A);

        if ($tipo_acto_administrativo != null) {

            $nulidad = $this->nulidadanaliza_model->retriveNulidad($cod_nulidad);
            $secretario_asignado = $this->nulidadanaliza_model->retrieveUsuario($nulidad->ASIGNADO_POR);

            $fromMail = $secretario_asignado->EMAIL;
            $fromName = $secretario_asignado->NOMBRES + ' ' + $secretario_asignado->APELLIDOS;
            $toMail = 'juridicos@sena.edu.co';
            $subject = 'Sistema de recaudo y cobro (Nulidad Respondida)';
            $message = 'Buenos dias ' . $fromName . '<br> Le fue respondida una nulidad, porfavor proceda a crear el acto administrativo, en el siguiente link encontrara todas las nulidades que esperan por crear el acto administratio <a href="' . base_url() . '/index.php/nulidadanaliza/listnulidadessecretarioactoadmin">aqui</a>';
            //enviarcorreosena($toMail,$message,$subject);
            //$this->sendMail($fromMail, $fromName, $toMail, $subject, $message);
        }
    }

    function sendMail($fromMail, $fromName, $toMail, $subject, $message) {
        $this->load->library('email');
        $this->email->from($fromMail, $fromName);
        $this->email->to($toMail);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }

    /*
     * VERIFICAR EXISTENCIA DE LA NULIDAD
     */

    function listNulidadesAnaliza() {

        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listnulidadesanaliza', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesAnaliza() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->analizaNulidadList(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->analizaNulidadCount($this->input->post('sSearch'));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * CREACION DE DOCUMENTOS
     */

    function listNulidadesCreaActoAdmin() {

        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listcreaactoadmin', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesCreaActoAdmin() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->listCreaActoAdministrativo(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->countCreaActoAdministrativo($this->input->post('sSearch'));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function formActoAdministrativo() {
        if ($this->ion_auth->logged_in()) {

            $cod_nulidad = $this->uri->segment(3);
            $num_autogenerado = trim($this->uri->segment(4));

            $nulidad = $this->nulidadanaliza_model->retrieveNulidad($cod_nulidad);
            $cod_fiscalizacion = $nulidad->COD_FISCALIZACION;


            $cod_gestioncobro = '';

            $documento = '';

            $cod_tipo_proceso = ($this->input->post('COD_TIPO_PROCESO') != '') ?
                    $this->input->post('COD_TIPO_PROCESO') :
                    0;


            $cod_tipo_auto = 1;

            $codplantilla = '';
            $plantilla = null;

            $data = array();

            $auto = null;
            $fiscalizacion = null;

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';
            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            //$num_autogenerado = 37;// Test

            /* $cod_fiscalizacion  = 10; //Test
              $cod_gestion        = 1; //Test */

            //$readonly = ($idgrupo != 61) ? 'readonly="readonly"' : '';

            $where_estado = '';

            $valid_doc = false;

            $view = 'style="display: none"';
            if ($num_autogenerado != '') {
                $auto = $this->verfpagosprojuridicosModel->retrievetAuto($num_autogenerado);
                $auto = $auto[0];

                $file_txt = $this->docs . $auto->NOMBRE_DOC_GENERADO;
                $cod_tipo_auto = $auto->COD_TIPO_AUTO;
                $cod_tipo_proceso = $auto->COD_TIPO_PROCESO;
                $codplantilla = '';
                $cod_fiscalizacion = $auto->COD_FISCALIZACION;
                $documento = read_template($file_txt);
                $view = '';
            }

            if ($cod_fiscalizacion != '' /* && $cod_gestion != '' */) {
                $fiscalizacion = $this->verfpagosprojuridicosModel->retriveFiscalizacion($cod_fiscalizacion);
                $fiscalizacion = $fiscalizacion[0];
            }

            if ($auto == null) {
                $cod_gestioncobro = $this->verfpagosprojuridicosModel->retriveGestionActual($cod_fiscalizacion);
            } else {
                $cod_gestioncobro = $auto->COD_GESTIONCOBRO;
            }
            switch ($idgrupo) {
                case 41:
                    if (($auto != null) && ($nulidad->COD_TIPO_ADMIN == 3)) {
                        $valid_doc = true;
                        $where_estado = '3,4';
                    } else {
                        $where_estado = '2,4';
                    }

                    break;
                case 61:
                    if ($auto != null) {
                        if ($auto->COD_ESTADOAUTO == 2) {
                            $where_estado = '3,4';
                        } else {
                            $where_estado = '2,4';
                        }
                    } else {
                        $where_estado = '2,4';
                    }
                    break;
            }

            $cod_regional = (($this->ion_auth->user()->row()->COD_REGIONAL != null) &&
                    ($this->ion_auth->user()->row()->COD_REGIONAL != '')) ?
                    $this->ion_auth->user()->row()->COD_REGIONAL :
                    '';

            $this->data['secretarios'] = $this->verfpagosprojuridicosModel->listSecretarios($cod_regional);
            $this->data['coordinadores'] = $this->nulidadanaliza_model->retrieveUsuariosXgrupo(61);
            $this->data['plantillas'] = $this->verfpagosprojuridicosModel->retrieveAllPlantillas();
            $this->data['valid_doc'] = $valid_doc;
            $this->data['auto'] = $auto;
            $this->data['fiscalizacion'] = $fiscalizacion;
            $this->data['view'] = $view;
            $this->data['id_grupo'] = $idgrupo;
            $this->data['estados'] = $this->verfpagosprojuridicosModel->listEstados($where_estado);
            $this->data['cod_tipo_auto'] = $cod_tipo_auto;
            $this->data['cod_tipo_proceso'] = $cod_tipo_proceso;
            $this->data['idusuario'] = $idusuario;
            $this->data['documento'] = $documento;
            $this->data['cod_gestioncobro'] = $cod_gestioncobro;
            $this->data['cod_nulidad'] = $cod_nulidad;
            $this->data['causales'] = $this->nulidadanaliza_model->listCausalesNulidad($cod_nulidad);
            $this->data['acto_administrativo'] = $nulidad->COD_TIPO_ADMIN;
            $this->data['action_label'] = ($auto != null) ?
                    'Actualizar' :
                    'Agregar';

            $this->template->load($this->template_file, $this->controller . '/formactoadministrativo', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function saveActoAdministrativo() {

        $timestamp = now();
        $timezone = 'UM5';
        $daylight_saving = FALSE;
        $datestring = "%d-%m-%y";
        $now = gmt_to_local($timestamp, $timezone, $daylight_saving);
        $fecha = mdate($datestring, $now);



        $auto = null;
        $num_autogenerado = trim($this->input->post('NUM_AUTOGENERADO'));
        $cod_nulidad = trim($this->input->post('COD_NULIDAD'));
        $tipo_proceso = '';
        $nulidad = $this->nulidadanaliza_model->retrieveNulidad($cod_nulidad);

        switch ($nulidad->COD_TIPO_ADMIN) {
            case 1:
                $tipo_proceso = 4;
                break;
            case 2:
                $tipo_proceso = 3;
                break;
            case 3:
                $tipo_proceso = 2;
                break;
        }

        $name_documento = '';
        $name_documento_firmado = '';
        $subir_doc = false;
        $status_upload_doc_fir = true;

        $genera = true;
        if ($num_autogenerado == '') {
            $genera = true;
        } else {

            $auto = $this->verfpagosprojuridicosModel->retrievetAuto($num_autogenerado);
            $auto = $auto[0];
            $file_txt = $this->docs . $auto->NOMBRE_DOC_GENERADO;
            $content_txt = read_template($file_txt);

            if (trim($this->input->post('documento')) == trim($content_txt)) {
                $genera = false;
                $name_documento = $auto->NOMBRE_DOC_GENERADO;
            } else {
                $genera = true;
            }

            if (($auto->COD_ESTADOAUTO == 2) && ($this->input->post('COD_ESTADOAUTO') == 3)) {
                $subir_doc = true;
            }
        }

        if ($genera) {

            if ($auto != null) {
                unlink($this->docs . $auto->NOMBRE_DOC_GENERADO);
            }

            $name_documento = create_template($this->docs, trim($this->input->post('documento')));
        }

        if ($subir_doc) {

            $name = md5(date('ymdGisu')) . '.txt';
            $archivo = $_FILES["nombre_doc_firmado_upload"]['name'];
            $arr_name = explode('.', $archivo);
            $name = md5(date('ymdGis') . microtime('get_as_float')) . '.' . $arr_name[1];

            if ($archivo != "") {
                // guardamos el archivo a la carpeta files
                $destino = $this->docs_firmados . $name;

                if (!copy($_FILES['nombre_doc_firmado_upload']['tmp_name'], $destino)) {
                    $status_upload_doc_fir = false;
                    $name_documento_firmado = $name;
                }
            }
        }

        if ($status_upload_doc_fir) {

            $asignado_a = ($this->input->post('ASIGNADO_A') == 0) ? null : $this->input->post('ASIGNADO_A');
            $revisado_por = ($this->input->post('REVISADO_POR') == 0) ? null : $this->input->post('REVISADO_POR');
            $aprobado_por = ($this->input->post('APROBADO_POR') == 0) ? null : $this->input->post('APROBADO_POR');

            $num_autogenerado = $this->verfpagosprojuridicosModel->saveAuto(21, $this->input->post('COD_FISCALIZACION'), $this->input->post('COD_ESTADOAUTO'), $tipo_proceso, $this->input->post('CREADO_POR'), $asignado_a, $fecha, $this->input->post('COMENTARIOS'), 1, $revisado_por, 1, $aprobado_por, $name_documento, $name_documento_firmado, '0', $fecha, '', $fecha, $num_autogenerado, $this->input->post('COD_GESTIONCOBRO'), $cod_nulidad);

            $message = '';
            $fromMail = '';
            $id_usuario = '';
            $notificacion_usuario = true;
            $notificacion_empresa = false;
            $mail_autorizado_empresa = NULL;
            switch ($this->input->post('COD_ESTADOAUTO')) {
                case 1:
                    $message = 'Buenos dias <%-fromName-%>;<br> Se a creado un nuevo acto administrativo y es nesesario que realice la gestion, para ver actos administrativos creados por favor de click <a href="' . base_url() . '/index.php/nulidadanaliza//index.php/nulidadanaliza/listNulidadesSecretarioActoAdmin">aqui</a>';
                    $id_usuario = $asignado_a;
                    break;
                case 2:
                    $message = 'Buenos dias <%-fromName-%>;<br> Tiene actos administrativos por revisas y es nesesario que realice la gestion, para ver actos administrativos creados por favor de click <a href="' . base_url() . '/index.php/nulidadanaliza/listNulidadesCoordinadorActoAdmin">aqui</a>';
                    $id_usuario = $asignado_a;
                    break;
                case 3:
                    /* $message    = 'Buenos dias <%-fromName-%>;<br> Se termino de gestionar una nulidad, porfavor revise el envio de la respuesta y gestione el debido documento, para ver actos administrativos creados por favor de click <a href="' . base_url() . '/index.php/nulidadanaliza/listNulidadesNotiCorFisicoActoAdmin">aqui</a>';
                      $id_usuario = $asignado_a;
                      $cod_fiscalizacion = $this->input->post('COD_FISCALIZACION');
                      $mail_autorizado_empresa = $this->nulidadanaliza_model->retrieveAutorizacion($cod_fiscalizacion);
                      $cod_metodo_contacto = 2;

                      if( ( !is_null($mail_autorizado_empresa)) && ($mail_autorizado_empresa->AUTORIZA == 'S')){
                      $cod_metodo_contacto = 3;
                      $notificacion_empresa = true;
                      $notificacion_usuario = false;
                      }

                      $this->nulidadanaliza_model->actualizaMetodEnvio($cod_nulidad, $cod_metodo_contacto);
                     */
                    $notificacion_usuario = false;
                    break;
                case 4:
                    $message = 'Buenos dias <%-fromName-%>;<br> Se a rechazado un nuevo acto administrativo y es nesesario que realice la gestion, para ver actos administrativos creados por favor de click <a href="' . base_url() . '/index.php/nulidadanaliza/listNulidadesRechazadoActoAdmin">aqui</a>';
                    $id_usuario = $nulidad->CREADO_POR;
                    break;
            }

            if ($notificacion_usuario) {
                $secretario_asignado = $this->nulidadanaliza_model->retrieveUsuario($id_usuario);
                $mail_to = $secretario_asignado->EMAIL;
                $name = $secretario_asignado->NOMBRES + ' ' + $secretario_asignado->APELLIDOS;
                $message = str_replace('<%-fromName-%>', $name, $message);
                $toMail = 'juridicos@sena.edu.co';
                $subject = 'Sistema de recaudo y cobro (Nulidades)';

                //mandar mail $this->mailSend($message, $subject, $mail_to);
                $usuario = $this->nulidadanaliza_model->retrieveNulidad($cod_nulidad);
            }

            if ($notificacion_empresa) {
                $txt_file = $this->docs . $name_documento;
                $pdf_name = createPdfTemplateSave($txt_file, $this->route_pdf);
                $attach = array();
                $attach[] = $this->route_pdf . $pdf_name;
                $mail_to = $mail_autorizado_empresa->EMAIL_AUTORIZADO;
                $subject = 'El sistema de Gesti&oacuote;n de Recardo y Cobro le notifica que que se a gestionado una nulidad';
                $message = 'Buenos dias ' . $mail_autorizado_empresa->NOMBRE_CONTACTO . ';<br> Se a gestionado una nulidad, le enviamos el acto administrativo generado como documento adjunto.';
                //mandar mail$this->mailSend($message, $subject, $mail_to, $attach);
            }

            if ($this->input->post('COD_ESTADOAUTO') == 3) {
                //$this->template->load($this->template_file, base_url() . '/index.php/' . $this->controller.'/formNotificacion', $this->data);

                $this->load->helper('url');
                redirect(base_url() . 'index.php/nulidadanaliza/formnotificacion/' . $num_autogenerado, 'refresh');
//redirect(base_url() . '/index.php/nulidadanaliza/formnotificacion/' . $num_autogenerado, 'refresh');
            } else {
                if ($this->input->post('COD_ESTADOAUTO') == 1) {
                    redirect(base_url() . 'index.php/nulidadanaliza/listNulidadesCreaActoAdmin', 'refresh');
                }
                if ($this->input->post('COD_ESTADOAUTO') == 2) {
                    redirect(base_url() . 'index.php/nulidadanaliza/listNulidadesSecretarioActoAdmin', 'refresh');
                }
                if ($this->input->post('COD_ESTADOAUTO') == 4) {
                    redirect(base_url() . 'index.php/nulidadanaliza/listNulidadesRechazadoActoAdmin', 'refresh');
                }
            }
        } else {
            echo 'archivo_no';
        }
    }

    function formNotificacion() {

        if (!$this->ion_auth->logged_in()) {
            redirect(base_url() . 'index.php/auth/login');
        }

        $cod_acto_administrativo = trim($this->uri->segment(3));
        $cod_notificacion = trim($this->uri->segment(4));
        $action_label = ($cod_notificacion == '') ? 'Crear' : 'Gestionar';

        $acto_administrativo = $this->verfpagosprojuridicosModel->retrievetAuto($cod_acto_administrativo);
        $acto_administrativo = $acto_administrativo[0];
        $acto_notificacion = null;
        $documento = '';

        if ($cod_notificacion != '') {
            $acto_notificacion = $this->verfpagosprojuridicosModel->retrievetAuto($cod_notificacion);
            $acto_notificacion = $acto_notificacion[0];
            $file_txt = $this->docs . $acto_notificacion->NOMBRE_DOC_GENERADO;
            $documento = read_template($file_txt);
        }

        $cod_nulidad = $acto_administrativo->COD_NULIDAD;
        $nulidad = $this->nulidadanaliza_model->retrieveNulidad($cod_nulidad);
        $cod_fiscalizacion = $acto_administrativo->COD_FISCALIZACION;
        $fiscalizacion = $this->nulidadanaliza_model->retriveFiscalizacion($cod_fiscalizacion);

        $cod_gestioncobro = '';

        $cod_tipo_proceso = ($this->input->post('COD_TIPO_PROCESO') != '') ?
                $this->input->post('COD_TIPO_PROCESO') :
                0;
        $estados = array();
        $estados[''] = 'SELECCIONE';
        $estados[5] = 'NOPTIFICACION RECIBIDA';
        $estados[6] = 'NOTIFICACION DEVUELTA';

        $mail_autorizado_empresa = $this->nulidadanaliza_model->retrieveAutorizacion($cod_fiscalizacion);

        $email = (($mail_autorizado_empresa != null)) ? $mail_autorizado_empresa->AUTORIZA : 'N';

        $this->data['causales'] = $this->nulidadanaliza_model->listCausalesNulidad($cod_nulidad);
        $this->data['cod_acto_administrativo'] = $cod_acto_administrativo;
        $this->data['plantillas'] = $this->verfpagosprojuridicosModel->retrieveAllPlantillas();
        $this->data['acto_notificacion'] = $acto_notificacion;
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['view'] = ($acto_notificacion == null) ? 'style="display:none"' : '';
        $this->data['estados'] = $estados;
        $this->data['cod_tipo_auto'] = 20;
        $this->data['cod_tipo_proceso'] = 1;
        $this->data['documento'] = $documento;
        $this->data['cod_gestioncobro'] = $cod_gestioncobro;
        $this->data['cod_nulidad'] = $cod_nulidad;
        $this->data['causales'] = $this->nulidadanaliza_model->listCausalesNulidad($cod_nulidad);
        $this->data['action_label'] = $action_label;
        $this->data['email'] = $email;

        $this->template->load($this->template_file, $this->controller . '/formnotificacion', $this->data);

        if ($this->input->post('COD_ESTADOAUTO') == 3) {
            $this->template->load($this->template_file, $this->controller . '/formnotificacion', $this->data);
        }
    }

    function saveNotificacion() {

        $timestamp = now();
        $timezone = 'UM5';
        $daylight_saving = FALSE;
        $datestring = "%d/%m/%Y";
        $now = gmt_to_local($timestamp, $timezone, $daylight_saving);
        $fecha = mdate($datestring, $now);
        $auto = null;
        $num_autogenerado = trim($this->input->post('NUM_AUTOGENERADO'));
        $cod_nulidad = trim($this->input->post('COD_NULIDAD'));
        $tipo_proceso = '';
        $nulidad = $this->nulidadanaliza_model->retrieveNulidad($cod_nulidad);
        $fiscalizacion = $this->nulidadanaliza_model->retriveFiscalizacion($nulidad->COD_FISCALIZACION);
        $cod_fiscalizacion = $nulidad->COD_FISCALIZACION;
        $asignado_a = $this->ion_auth->user()->row()->IDUSUARIO;
        $revisado_por = $asignado_a;
        $aprobado_por = $asignado_a;
        $fecha_solicitud_pruebas = $this->input->post('FECHA_SOLICITUD_PRUEBAS');
        $radicado_onbase = $this->input->post('RADICADO_ONBASE');


        switch ($nulidad->COD_TIPO_ADMIN) {
            case 1:
                $tipo_proceso = 4;
                break;
            case 2:
                $tipo_proceso = 3;
                break;
            case 3:
                $tipo_proceso = 2;
                break;
        }

        $name_documento = '';
        $name_documento_firmado = '';
        $subir_doc = false;
        $status_upload_doc_fir = true;

        $genera = true;
        if ($num_autogenerado == '') {
            $genera = true;
        } else {

            $auto = $this->verfpagosprojuridicosModel->retrievetAuto($num_autogenerado);
            $auto = $auto[0];
            $file_txt = $this->docs . $auto->NOMBRE_DOC_GENERADO;
            $content_txt = read_template($file_txt);

            if (trim($this->input->post('documento')) == trim($content_txt)) {
                $genera = false;
                $name_documento = $auto->NOMBRE_DOC_GENERADO;
            } else {
                $genera = true;
            }

            if (($auto->COD_ESTADOAUTO == 2) && ($this->input->post('COD_ESTADOAUTO') == 3)) {
                $subir_doc = true;
            }
        }

        if ($genera) {

            if ($auto != null) {
                unlink($this->docs . $auto->NOMBRE_DOC_GENERADO);
            }

            $name_documento = create_template($this->docs, trim($this->input->post('documento')));
        }

        if ($subir_doc) {

            $name = md5(date('ymdGisu')) . '.txt';
            $archivo = $_FILES["nombre_doc_firmado_upload"]['name'];
            $arr_name = explode('.', $archivo);
            $name = md5(date('ymdGis') . microtime('get_as_float')) . '.' . $arr_name[1];

            if ($archivo != "") {
                // guardamos el archivo a la carpeta files
                $destino = $this->docs_firmados . $name;

                if (!copy($_FILES['nombre_doc_firmado_upload']['tmp_name'], $destino)) {
                    $status_upload_doc_fir = false;
                    $name_documento_firmado = $name;
                }
            }
        }

        if ($status_upload_doc_fir) {

            $cod_estadoauto = ($this->input->post('COD_ESTADOAUTO') == '') ? 1 : $this->input->post('COD_ESTADOAUTO');
            $num_autogenerado = $this->verfpagosprojuridicosModel->saveAuto(20, $this->input->post('COD_FISCALIZACION'), $cod_estadoauto, $tipo_proceso, $asignado_a, $asignado_a, $fecha, $this->input->post('COMENTARIOS'), 1, $revisado_por, 1, $aprobado_por, $name_documento, $name_documento_firmado, '0', $fecha_solicitud_pruebas, '', $fecha, $num_autogenerado, $fiscalizacion->COD_GESTIONACTUAL, null, $this->input->post('COD_ACTOADMINISTRATIVO'), $this->input->post('COD_METODO_CONTACTO'), $radicado_onbase);

            if (($this->input->post('COD_ESTADOAUTO') == 6) || ($this->input->post('COD_ESTADOAUTO') == 5)) {
                $this->template->load($this->template_file, $this->controller . '/end_auntojuridiconotificacion', $this->data);
            } else {

                if ($this->input->post('COD_METODO_CONTACTO') == 7) {
                    /* $txt_file = $this->docs . $name_documento;
                      $name = createPdfTemplateSave($txt_file, $this->route_pdf);
                      $doc = $this->route_pdf . $name;

                      $attach = array();
                      $attach[0] = $doc;
                      $autorizacion = $this->nulidadanaliza_model->retrieveAutorizacion($cod_fiscalizacion);
                      echo $autorizacion;die();
                      $message = 'Buenas tardes ' . $autorizacion->NOMBRE_CONTACTO . '<br> El sistema de gesti&oacute;n y recaudo del sena te envia una notificacion, para revisarla descargue el archivo adjunto';
                      $subject = 'El sistema de gesti&oacute;n y recaudo del sena (notificacion)';
                      $mail_to = $autorizacion->EMAIL_AUTORIZADO;
                      $this->mailSend($message, $subject, $mail_to, $attach);
                      unlink($doc); */
                }

                echo 'ok';
            }
        } else {
            echo 'archivo_no';
        }
    }

    function mailSend($message, $subject, $mail_to, $attach = array()) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'carterasena@gmail.com',
            'smtp_pass' => '7demarzo',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE);

        $this->load->library('email', $config);
        $mail_from = 'carterasena@gmail.com';
        $name_from = 'Sena';
        $this->email->from($mail_from, $name_from);
        $this->email->to($mail_to);
        $this->email->subject($subject);
        $this->email->message($message);

        foreach ($attach as $value) {
            $this->email->attach($value);
        }

        $this->email->send();

        echo $this->email->print_debugger();
    }

    /*
     * TAREAS PEDIENTES PARA EL SECRETARIO
     */

    function listNulidadesSecretarioActoAdmin() {

        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listsecretarioactoadmin', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesSecretarioActoAdmin() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->listSecretarioActoAdministrativo(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->countSecretarioActoAdministrativo($this->input->post('sSearch'));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * TAREAS PENDIENTES PARA EL COORDINADOR
     */

    function listNulidadesCoordinadorActoAdmin() {

        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listcoordinadoractoadmin', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesCoordinadorActoAdmin() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->listCoordinadorActoAdministrativo(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->countCoordinadorActoAdministrativo($this->input->post('sSearch'));

            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /*
     * 
     */

    function listNulidadesRechazadoActoAdmin() {

        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listrechazadoactoadmin', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesRechazadoActoAdmin() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->listRechazadoActoAdministrativo(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->countRechazadoActoAdministrativo($this->input->post('sSearch'));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function listNulidadesNotiCorFisicoActoAdmin() {

        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listnulidadesnoticorfisicoactoadmin', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesNotiCorFisicoActoAdmin() {

        if ($this->ion_auth->logged_in()) {

            $idgrupo = ( ($this->ion_auth->user()->row()->IDGRUPO != null) ||
                    (trim($this->ion_auth->user()->row()->IDGRUPO) != '')) ?
                    $this->ion_auth->user()->row()->IDGRUPO : '';

            $idusuario = $this->ion_auth->user()->row()->IDUSUARIO;


            $nulidades = $this->nulidadanaliza_model->listNulidadesNotiCorFisicoActoAdmin(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->countNulidadesNotiCorFisicoActoAdmin($this->input->post('sSearch'));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function listNulidadesNotificacionesFisico() {
        if ($this->ion_auth->logged_in()) {
            $this->template->load($this->template_file, $this->controller . '/listnulidadesnoticorfisicoactoadmin', $this->data);
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableNulidadesNotificacionesFisico() {

        if ($this->ion_auth->logged_in()) {

            $nulidades = $this->nulidadanaliza_model->listNulidadesNotificacionesFisico(
                    $this->input->post('sSearch'), $this->input->post('iDisplayStart'), 10);



            $num_nulidades = $this->nulidadanaliza_model->countNulidadesNotificacionesFisico($this->input->post('sSearch'));


            echo json_encode(array('aaData' => $nulidades,
                'iTotalRecords' => $num_nulidades,
                'iTotalDisplayRecords' => $num_nulidades,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pruebaPdf() {
        //$nombre = createPdfTemplateSave('uploads/verfpagosprojuridicos/docs_autosjuridicos/test.txt', 'uploads/verfpagosprojuridicos/docs_autosjuridicos/');
        //createPdfTemplateSave('prueba.pdf', 'uploads/verfpagosprojuridicos/docs_autosjuridicos/test.txt', true);
        $name = createPdfTemplateSave('uploads/verfpagosprojuridicos/docs_autosjuridicos/test.txt', $this->route_pdf);
        $this->mailSend('esto es una puta prueba', 'prueba', 'cristiansrc@gmail.com', array(0 => $this->route_pdf . $name));
        //$this->sendMail('cristiansrc@hotmail.com', 'Cristhiam Reina', 'cristiansrc@gmail.com', 'prueba', 'Esto es una puta prueba');
        //echo $nombre;
    }

    function imprimeActoAdministrativo() {
        $cod_actoadministrativo = $this->uri->segment(3);
        $actoadministrativo = $this->verfpagosprojuridicosModel->retrievetAuto($cod_actoadministrativo);
        $actoadministrativo = $actoadministrativo[0];
        $txt_file = $this->templates . $actoadministrativo->NOMBRE_DOC_GENERADO;
        createPdfTemplateOuput('Acto administrativo', $txt_file, true);
    }

    function preVisualizarActoAdministrativo() {
        $cod_actoadministrativo = $this->uri->segment(3);
        $actoadministrativo = $this->verfpagosprojuridicosModel->retrievetAuto($cod_actoadministrativo);
        $actoadministrativo = $actoadministrativo[0];
        $txt_file = $this->templates . $actoadministrativo->NOMBRE_DOC_GENERADO;
        createPdfTemplateOuput('Acto administrativo', $txt_file, false);
    }

    function descargarActoAdministrativo() {
        $cod_actoadministrativo = $this->uri->segment(3);
        $actoadministrativo = $this->verfpagosprojuridicosModel->retrievetAuto($cod_actoadministrativo);
        $actoadministrativo = $actoadministrativo[0];
        $txt_file = $this->templates . $actoadministrativo->NOMBRE_DOC_GENERADO;
        $name = createPdfTemplateSave($txt_file, $this->route_pdf);
        $enlace = $this->route_pdf . $name;
        header("Contenszt-Disposition: attachment; filename=Acto_Administrativo.pdf");
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . filesize($enlace));
        ob_clean();
        readfile($enlace);
        unlink($enlace);
    }

    function descargarActoAdministrativoFirmado() {
        $cod_actoadministrativo = $this->uri->segment(3);
        $actoadministrativo = $this->verfpagosprojuridicosModel->retrievetAuto($cod_actoadministrativo);
        $actoadministrativo = $actoadministrativo[0];
        $enlace = $this->docs_firmados . $actoadministrativo->NOMBRE_DOC_FIRMADO;
        $ext = explode('.', $actoadministrativo->NOMBRE_DOC_FIRMADO);
        header("Content-Disposition: attachment; filename=Acto_Administrativo." . $ext[1]);
        header("Content-Type: application/octet-stream");
        header("Content-Length: " . filesize($enlace));
        ob_clean();
        readfile($enlace);
    }

}

?>
