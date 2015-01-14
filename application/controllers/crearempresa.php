<?php

class Crearempresa extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'url',
            'codegen_helper'
        ));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->file(APPPATH . "controllers/sgva_client.php", true);
    }

    function index() {
        $this->manage();
    }

    function manage() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('crearempresa/manage')) {

                $this->add();

                $this->data['message'] = $this->session->flashdata('message');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /* Este metodo envia la información de la empresa,para ser creada en Sgva a través del web service */

    function crear_empresa_sgva($data_empresa) {
        $this->sgva_client = new sgva_client();
        $resultado = $this->sgva_client->CrearEmpresa($data_empresa);
    }

    function add() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('crearempresa/add')) {

                $this->load->library('form_validation');
                $this->data['custom_error'] = '';

                // |:::::Validaciones EMPRESA
                //$this->form_validation->set_rules('codigo', 'Código', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('nombreempresa', 'Nombre de la empresa', 'required|trim|max_length[255]');
                $this->form_validation->set_rules('direccion', 'Dirección', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('telefonofijo', 'Teléfono fijo', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('telefonomovil', 'Teléfono movil', 'required|trim|numeric|xss_clean|max_length[15]');
								//Datos razón social
                $this->form_validation->set_rules('razon', 'Razón social', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('documento', 'Documento', 'required|trim|xss_clean|numeric|max_length[15]');
								//Datos representante legal
                $this->form_validation->set_rules('representante', 'Representante legal', 'required|trim|xss_clean|max_length[255]');
                $this->form_validation->set_rules('tipodocumento_id', 'Tipo de documento', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('documentoRl', 'Documento', 'required|trim|xss_clean|numeric|max_length[15]');
								//Otros datos empresa								
                $this->form_validation->set_rules('regional_id', 'Regional', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('ciu', 'CIIU', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('actividad', 'Sector Económico', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('email_emp', 'Correo electrónico', 'required|trim|xss_clean|max_length[200]|valid_email');
                $this->form_validation->set_rules('fax', 'Fax', 'required|trim|xss_clean|numeric|max_length[50]');
                $this->form_validation->set_rules('afiliados', 'Afiliados', 'required|trim|xss_clean');
                $this->form_validation->set_rules('nombrecaja', 'Caja de compenzación', 'trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('nueva', 'Nueva empresa', 'required|trim|xss_clean');
                $this->form_validation->set_rules('escritura', 'Número de escritura pública', 'trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('notaria', 'Notaría', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('cuota', 'Cuota aprendiz', 'trim|xss_clean|max_length[5]');
                $this->form_validation->set_rules('resolucion', 'Resolución deRegulación', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('planta', 'Planta personal', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('autoriza', '¿Autoriza notificaciones por E-mail?', 'required|trim|xss_clean');
                $this->form_validation->set_rules('principal', 'Nombre de empresa principal', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('registrocam', 'Registro cámara de comercio', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('sedes', 'N. sedes', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('empleados', 'N.empleados', 'trim|xss_clean|numeric|max_length[50]');
                $this->form_validation->set_rules('tipoempresa_id', 'Tipo de empresa', 'required|trim|xss_clean|greater_than[0]');
                $this->form_validation->set_rules('departamento_id', 'Departamento', 'required|trim|xss_clean|greater_than[0]');
                $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|trim|xss_clean|greater_than[0]');
                $this->form_validation->set_rules('zona', 'Zona', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('barrio', 'Barrio', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('apartado', 'Apartado aéreo', 'trim|xss_clean|max_length[10]');
                $this->form_validation->set_rules('postal', 'Código postal', 'trim|xss_clean|max_length[10]');
                $this->form_validation->set_rules('web', 'Página web', 'trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('telefono_a', 'Teléfono alternativo', 'trim|xss_clean|numeric|max_length[15]');

                // |:::::Validaciones CONTACTO

                $this->form_validation->set_rules('n_contacto', 'Nombres Contacto', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('pa_contacto', 'Primer Apellido', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('sa_contacto', 'Segundo Apellido', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('tipodocumento_cont', 'Tipo de documento', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('id_cont', 'Identificación', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('cargo_id_con', 'Cargo', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('m_contacto', 'Metodo de Contacto', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('email_pri_cont', 'E-mail Principal', 'required|trim|xss_clean|max_length[50]|valid_email');
                $this->form_validation->set_rules('email_alt_cont', 'E-mail Alternativo', 'trim|xss_clean|max_length[50]|valid_email');
                $this->form_validation->set_rules('tel_mov_con', 'Teléfono Movil', 'trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('tel_conemp', 'Teléfono Contacto Empresa', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('fax_con', 'Fax', 'trim|xss_clean|numeric|max_length[15]');

                if ($this->form_validation->run() == false) {

                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $nombrempresaexist = $this->nombre_empresa(strtoupper($this->input->post('nombreempresa')));

                    if ($nombrempresaexist['EXIST'] > 0) {
                        $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El NOMBRE de esta empresa ya se encuentra registrado</div>';
                    } else {

                        if ($this->ion_auth->empresa_check($this->input->post('codigo'))) {

                            $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El NIT de esta empresa ya se encuentra registrada</div>';
                        } else {

                            // |:::::datos que se subiran a la tabla EMPRESA
                            $date_empresa = array(
                                'FECHA_CREACION' => date("d/m/Y")
                            );

                            $data_empresa = array(
                                'CODEMPRESA' => set_value('documento'),
                                'NOMBRE_EMPRESA' => set_value('nombreempresa'),
                                'DIRECCION' => set_value('direccion'),
                                'TELEFONO_FIJO' => set_value('telefonofijo'),
                                'TELEFONO_CELULAR' => set_value('telefonomovil'),
                                'REPRESENTANTE_LEGAL' => set_value('representante'),
                                'COD_TIPODOCUMENTO' => set_value('tipodocumento_id'),
                                'RAZON_SOCIAL' => set_value('razon'),
                                'CIIU' => set_value('ciu'),
                                'COD_REPRESENTANTELEGAL' => set_value('documentoRl'),
                                'COD_REGIONAL' => set_value('regional_id'),
                                'CORREOELECTRONICO' => set_value('email_emp'),
                                'ACTIVIDADECONOMICA' => set_value('actividad'),
                                'FAX' => set_value('fax'),
                                'AFILIADO_CAJACOMPENSACION' => set_value('afiliados'),
                                'NOM_CAJACOMPENSACION' => set_value('nombrecaja'),
                                'EMPRESA_NUEVA' => set_value('nueva'),
                                'NRO_ESCRITURAPUBLICA' => set_value('escritura'),
                                'NOTARIA' => set_value('notaria'),
                                'CUOTA_APRENDIZ' => set_value('cuota'),
                                'RESOLUCION' => set_value('resolucion'),
                                'PLANTA_PERSONAL' => set_value('planta'),
                                'AUTORIZA_NOTIFIC_EMAIL' => set_value('autoriza'),
                                //'FECHA_AUTORIZACIONENVIOMAIL'   => set_value(''), // |:::::Fecha  <---------------
                                //'RUTA_DOCUMENTO_AUTORIZACION'   => set_value(''),
                                'COD_TIPOEMPRESA' => set_value('tipoempresa_id'),
                                'EMAILAUTORIZADO' => set_value('email_emp'),
                                'NUM_EMPLEADOS' => set_value('empleados'),
                                'NOMBRE_EMP_PRINCIPAL' => set_value('principal'),
                                'NUM_SEDES' => set_value('sedes'),
                                'REG_CAM_COMERCIO' => set_value('registrocam'),
                                //'COD_PAIS'                      => set_value(''), // |:::::Codigo del pais tabla no existe     <-------------------
                                'COD_DEPARTAMENTO' => set_value('departamento_id'),
                                'COD_MUNICIPIO' => set_value('ciudad'),
                                'ZONA' => set_value('zona'),
                                'BARRIO' => set_value('barrio'),
                                'APARTADO_AEREO' => set_value('apartado'),
                                'COD_POSTAL' => set_value('postal'),
                                'PAGINA_WEB' => set_value('web'),
                                'TEL_ALTERNATIVO' => set_value('telefono_a'),
                                //'FECHA_MOD_CARTERA'             => set_value(''),
                                //'COD_EST_CARTERA'               => set_value(''),
                                //'COD_TIPOSOCIEDAD'              => set_value(''),
                                //'COD_ESTADO_SOCIEDAD'           => set_value(''),
                                //'COD_TIP_EMP_SENA'              => set_value(''),
                                //'COD_REGIMEN'                   => set_value(''),
                                //'COD_TIPOENTIDAD'               => set_value(''),
                                //'ACTIVO'                        => set_value(''),
                                'NOMBRE_CONTACTO' => set_value('n_contacto') . ' ' . set_value('pa_contacto') . ' ' . set_value('sa_contacto')
                            );
                            // |:::::datos que se subiran a la tabla CONTACTOEMPRESA
                            $data_contacto = array(
                                'COD_CONTACTO_EMPRESA' => set_value('id_cont'),
                                'COD_METODO_CONTACTO' => set_value('m_contacto'),
                                'NIT_EMPRESA' => set_value('documento'),
                                'NOMBRE_CONTACTO' => set_value('n_contacto'),
                                'CORREO_PRINCIPAL' => set_value('email_pri_cont'),
                                'CORREO_SECUNDARIO' => set_value('email_alt_cont'),
                                'COD_TIPO_DOCUMENTO' => set_value('tipodocumento_cont'),
                                'NUMERO_IDENTIFICACION' => set_value('id_cont'),
                                'CARGO_CONTACTO' => set_value('cargo_id_con'),
                                'TELEFONO_EMPRESA' => set_value('tel_conemp'),
                                'CELULAR' => set_value('tel_mov_con'),
                                'FAX' => set_value('fax_con'),
                                'PRIMER_APELLIDO' => set_value('pa_contacto'),
                                'SEGUNDO_APELLIDO' => set_value('sa_contacto')
                            );

                            if ($this->codegen_model->add('EMPRESA', $data_empresa, $date_empresa) == TRUE && $this->codegen_model->add('CONTACTOEMPRESA', $data_contacto) == TRUE) {
																$this->db->set("COD_EMPRESA", $data_empresa['CODEMPRESA']);
																$this->db->set("FECHA_PROCESAMIENTO", "SYSDATE", FALSE);
																$this->db->insert('EMPRESASEVASORAS');
																$res = $this->db->query("SELECT EmpresasEvaso_cod_emp_evas_SEQ.CURRVAL AS ID FROM Dual");
																$res = $res->row();
																$this->db->set("COD_EMP_EVASORA", $res->ID);
																$this->db->set("OBSERVACIONES", "Se creo la empresa manualmente");
																$this->db->insert("EMPRESASEVASORAS_DET");
                                $resultado = $this->crear_empresa_sgva($data_empresa);
                                if ($resultado):
																	$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> La empresa se ha creado con éxito. ' . $resultado. '</div>');
                                else:
																	$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La empresa se ha creado con éxito.</div>');
                                endif;

                                redirect(base_url() . 'index.php/crearempresa');
                            } else {
                                $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';
                            }
                        }
                    }
                }
                // |:::::add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/validCampoFranz.js'
                );

                //Correcion tipos de documentos
                $this->db->select('CODTIPODOCUMENTO,NOMBRETIPODOC');
                $this->db->from('TIPODOCUMENTO');
                $this->db->where('CODTIPODOCUMENTO IN (1,2,4,6)');
                $this->db->order_by('NOMBRETIPODOC');
                $dato = $this->db->get();
                //
                $this->data['metodo'] = $this->codegen_model->getSelect('METODOCONTACTO', 'COD_METODO_CONTACTO,DESCRIPCION');
                $this->data['cargos'] = $this->codegen_model->getSelect('CARGOS', 'IDCARGO,DESCRIPCIONCARGO,NOMBRECARGO');
                $this->data['tiposdocumento'] = $dato->result(); //$this->codegen_model->getSelect('TIPODOCUMENTO', 'CODTIPODOCUMENTO,NOMBRETIPODOC','WHERE CODTIPODOCUMENTO = IN(1,2,4,6)','ORDER BY NOMBRETIPODOC');
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('crearempresa/coordinador')) {
                    $this->data['regionales'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL', 'WHERE COD_REGIONAL=' . $this->ion_auth->user()->row()->COD_REGIONAL . '');
                } else {
                    $this->data['regionales'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL', '', 'ORDER BY 2 ASC');
                }
                $this->data['tiposempresa'] = $this->codegen_model->getSelect('TIPOEMPRESA', 'CODTIPOEMPRESA,NOM_TIPO_EMP');
                //$this->data['paises']  = $this->codegen_model->getSelect('PAIS','CODPAIS,NOMBREPAIS');
                $this->data['departamentos'] = $this->codegen_model->getSelect('DEPARTAMENTO', 'COD_DEPARTAMENTO,NOM_DEPARTAMENTO', '', 'ORDER BY 2 ASC');
                $this->data['municipios'] = $this->codegen_model->getSelect('MUNICIPIO', 'CODMUNICIPIO,NOMBREMUNICIPIO');
                $this->data['ciu'] = $this->codegen_model->getSelect('CIIU', 'CLASE,DESCRIPCION');
                $this->data['message'] = $this->session->flashdata('message');

                $this->template->load($this->template_file, 'crearempresa/crearempresa_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function edit() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('crearempresa/edit')) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
                    redirect(base_url() . 'index.php/consultarinfoempresas');
                } else {

                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';

                    // |:::::Validaciones EMPRESA
                //$this->form_validation->set_rules('codigo', 'Código', 'required|trim|xss_clean|numeric|max_length[15]');
								//echo $this->input->post('nombreempresa');exit;
                $this->form_validation->set_rules('nombreempresa', 'Nombre de la empresa', 'required|trim|max_length[255]');
                $this->form_validation->set_rules('direccion', 'Dirección', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('telefonofijo', 'Teléfono fijo', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('telefonomovil', 'Teléfono movil', 'required|trim|numeric|xss_clean|max_length[15]');
								//Datos razón social
                $this->form_validation->set_rules('razon', 'Razón social', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('documento', 'Documento', 'required|trim|xss_clean|numeric|max_length[15]');
								//Datos representante legal
                $this->form_validation->set_rules('representante', 'Representante legal', 'required|trim|xss_clean|max_length[255]');
                $this->form_validation->set_rules('tipodocumento_id', 'Tipo de documento', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('documentoRl', 'Documento', 'required|trim|xss_clean|numeric|max_length[15]');
								//Otros datos empresa								
                $this->form_validation->set_rules('regional_id', 'Regional', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('ciu', 'CIIU', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('actividad', 'Sector Económico', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('email_emp', 'Correo electrónico', 'required|trim|xss_clean|max_length[200]|valid_email');
                $this->form_validation->set_rules('fax', 'Fax', 'required|trim|xss_clean|numeric|max_length[50]');
                $this->form_validation->set_rules('afiliados', 'Afiliados', 'required|trim|xss_clean');
                $this->form_validation->set_rules('nombrecaja', 'Caja de compenzación', 'trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('nueva', 'Nueva empresa', 'required|trim|xss_clean');
                $this->form_validation->set_rules('escritura', 'Número de escritura pública', 'trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('notaria', 'Notaría', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('cuota', 'Cuota aprendiz', 'trim|xss_clean|max_length[5]');
                $this->form_validation->set_rules('resolucion', 'Resolución deRegulación', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('planta', 'Planta personal', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('autoriza', '¿Autoriza notificaciones por E-mail?', 'required|trim|xss_clean');
                $this->form_validation->set_rules('principal', 'Nombre de empresa principal', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('registrocam', 'Registro cámara de comercio', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('sedes', 'N. sedes', 'trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('empleados', 'N.empleados', 'trim|xss_clean|numeric|max_length[50]');
                $this->form_validation->set_rules('tipoempresa_id', 'Tipo de empresa', 'required|trim|xss_clean|greater_than[0]');
                $this->form_validation->set_rules('departamento_id', 'Departamento', 'required|trim|xss_clean|greater_than[0]');
                $this->form_validation->set_rules('ciudad', 'Ciudad', 'required|trim|xss_clean|greater_than[0]');
                $this->form_validation->set_rules('zona', 'Zona', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('barrio', 'Barrio', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('apartado', 'Apartado aéreo', 'trim|xss_clean|max_length[10]');
                $this->form_validation->set_rules('postal', 'Código postal', 'trim|xss_clean|max_length[10]');
                $this->form_validation->set_rules('web', 'Página web', 'trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('telefono_a', 'Teléfono alternativo', 'trim|xss_clean|numeric|max_length[15]');

                // |:::::Validaciones CONTACTO

                $this->form_validation->set_rules('n_contacto', 'Nombres Contacto', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('pa_contacto', 'Primer Apellido', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('sa_contacto', 'Segundo Apellido', 'required|trim|xss_clean|max_length[50]');
                $this->form_validation->set_rules('tipodocumento_cont', 'Tipo de documento', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('id_cont', 'Identificación', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('cargo_id_con', 'Cargo', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('m_contacto', 'Metodo de Contacto', 'required|trim|xss_clean|numeric|greater_than[0]');
                $this->form_validation->set_rules('email_pri_cont', 'E-mail Principal', 'required|trim|xss_clean|max_length[50]|valid_email');
                $this->form_validation->set_rules('email_alt_cont', 'E-mail Alternativo', 'trim|xss_clean|max_length[50]|valid_email');
                $this->form_validation->set_rules('tel_mov_con', 'Teléfono Movil', 'trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('tel_conemp', 'Teléfono Contacto Empresa', 'required|trim|xss_clean|numeric|max_length[15]');
                $this->form_validation->set_rules('fax_con', 'Fax', 'trim|xss_clean|numeric|max_length[15]');

                    if ($this->form_validation->run() == false) {

                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {

                        if ($this->ion_auth->empresa_check($this->input->post('codigo'))) {

                            $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>El NIT de esta empresa ya se encuentra registrada</div>';
                        } else {

                            // |:::::datos que se subiran a la tabla EMPRESA

                            $data_empresa = array(
                                'CODEMPRESA' => set_value('documento'),
                                'NOMBRE_EMPRESA' => set_value('nombreempresa'),
                                'DIRECCION' => set_value('direccion'),
                                'TELEFONO_FIJO' => set_value('telefonofijo'),
                                'TELEFONO_CELULAR' => set_value('telefonomovil'),
                                'REPRESENTANTE_LEGAL' => set_value('representante'),
                                'COD_TIPODOCUMENTO' => set_value('tipodocumento_id'),
                                'RAZON_SOCIAL' => set_value('razon'),
                                'CIIU' => set_value('ciu'),
                                'COD_REPRESENTANTELEGAL' => set_value('documentoRl'),
                                'COD_REGIONAL' => set_value('regional_id'),
                                'CORREOELECTRONICO' => set_value('email_emp'),
                                'ACTIVIDADECONOMICA' => set_value('actividad'),
                                'FAX' => set_value('fax'),
                                'AFILIADO_CAJACOMPENSACION' => set_value('afiliados'),
                                'NOM_CAJACOMPENSACION' => set_value('nombrecaja'),
                                'EMPRESA_NUEVA' => set_value('nueva'),
                                'NRO_ESCRITURAPUBLICA' => set_value('escritura'),
                                'NOTARIA' => set_value('notaria'),
                                'CUOTA_APRENDIZ' => set_value('cuota'),
                                'RESOLUCION' => set_value('resolucion'),
                                'PLANTA_PERSONAL' => set_value('planta'),
                                'AUTORIZA_NOTIFIC_EMAIL' => set_value('autoriza'),
                                //'FECHA_AUTORIZACIONENVIOMAIL'   => set_value(''), // |:::::Fecha  <---------------
                                //'RUTA_DOCUMENTO_AUTORIZACION'   => set_value(''),
                                'COD_TIPOEMPRESA' => set_value('tipoempresa_id'),
                                'EMAILAUTORIZADO' => set_value('email_emp'),
                                'NUM_EMPLEADOS' => set_value('empleados'),
                                'NOMBRE_EMP_PRINCIPAL' => set_value('principal'),
                                'NUM_SEDES' => set_value('sedes'),
                                'REG_CAM_COMERCIO' => set_value('registrocam'),
                                //'COD_PAIS'                      => set_value(''), // |:::::Codigo del pais tabla no existe     <-------------------
                                'COD_DEPARTAMENTO' => $this->input->post('departamento_id'),
                                'COD_MUNICIPIO' => set_value('ciudad'),
                                'ZONA' => set_value('zona'),
                                'BARRIO' => set_value('barrio'),
                                'APARTADO_AEREO' => set_value('apartado'),
                                'COD_POSTAL' => set_value('postal'),
                                'PAGINA_WEB' => set_value('web'),
                                'TEL_ALTERNATIVO' => set_value('telefono_a'),
                                //'FECHA_MOD_CARTERA'             => set_value(''),
                                //'COD_EST_CARTERA'               => set_value(''),
                                //'COD_TIPOSOCIEDAD'              => set_value(''),
                                //'COD_ESTADO_SOCIEDAD'           => set_value(''),
                                //'COD_TIP_EMP_SENA'              => set_value(''),
                                //'COD_REGIMEN'                   => set_value(''),
                                //'COD_TIPOENTIDAD'               => set_value(''),
                                //'ACTIVO'                        => set_value(''),
                                'NOMBRE_CONTACTO' => set_value('n_contacto') . ' ' . set_value('pa_contacto') . ' ' . set_value('sa_contacto')
                            );
                            // |:::::datos que se subiran a la tabla CONTACTOEMPRESA
                            $data_contacto = array(
                                'COD_CONTACTO_EMPRESA' => set_value('id_cont'),
                                'COD_METODO_CONTACTO' => set_value('m_contacto'),
                                'NIT_EMPRESA' => set_value('documento'),
                                'NOMBRE_CONTACTO' => set_value('n_contacto'),
                                'CORREO_PRINCIPAL' => set_value('email_pri_cont'),
                                'CORREO_SECUNDARIO' => set_value('email_alt_cont'),
                                'COD_TIPO_DOCUMENTO' => set_value('tipodocumento_cont'),
                                'NUMERO_IDENTIFICACION' => set_value('id_cont'),
                                'CARGO_CONTACTO' => set_value('cargo_id_con'),
                                'TELEFONO_EMPRESA' => set_value('tel_conemp'),
                                'CELULAR' => set_value('tel_mov_con'),
                                'FAX' => set_value('fax_con'),
                                'PRIMER_APELLIDO' => set_value('pa_contacto'),
                                'SEGUNDO_APELLIDO' => set_value('sa_contacto')
                            );



                            if ($this->codegen_model->edit('EMPRESA', $data_empresa, 'CODEMPRESA', $this->input->post('id_empresa')) == TRUE && $this->codegen_model->edit('CONTACTOEMPRESA', $data_contacto, 'NIT_EMPRESA', $this->input->post('id_empresa')) == TRUE) {
															$this->db->select("COD_EMPRESA");
															$this->db->where("COD_EMPRESA", $data_empresa['CODEMPRESA']);
															$res = $this->db->get("EMPRESASEVASORAS");
															if($res->num_rows() == 0) :
																$this->db->set("COD_EMPRESA", $data_empresa['CODEMPRESA']);
																$this->db->set("FECHA_PROCESAMIENTO", "SYSDATE", FALSE);
																$this->db->insert('EMPRESASEVASORAS');
																$res = $this->db->query("SELECT EmpresasEvaso_cod_emp_evas_SEQ.CURRVAL AS ID FROM Dual");
																$res = $res->row();
																$this->db->set("COD_EMP_EVASORA", $res->ID);
																$this->db->set("OBSERVACIONES", "Se creo la empresa manualmente");
																$this->db->insert("EMPRESASEVASORAS_DET");
															endif;
                                //Acutalizar empresa sgva
                                $this->sgva_client = new sgva_client();
                                $resultado = $this->sgva_client->AcutalizarEmpresa($data_empresa);
                                if ($resultado):
																	$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La empresa se ha actualizado con éxito. ' . $resultado . '</div>');
                                else:
                                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La empresa se ha actualizado con éxito.</div>');

                                endif;


                                redirect(base_url() . 'index.php/consultarinfoempresas');
                            } else {
                                $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';
                            }
                        }
                    }

                    $this->data['result_empresa'] = $this->codegen_model->get('EMPRESA', '*', "CODEMPRESA = '" . $this->uri->segment(3) . "'", 1, 1, true);
                    $this->data['result_contacto'] = $this->codegen_model->get('CONTACTOEMPRESA', '*', 'NIT_EMPRESA = ' . $this->uri->segment(3), 1, 1, true);

                    // |:::::add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js',
                        'js/validCampoFranz.js'
                    );


                    $this->data['metodo'] = $this->codegen_model->getSelect('METODOCONTACTO', 'COD_METODO_CONTACTO,DESCRIPCION');
                    $this->data['cargos'] = $this->codegen_model->getSelect('CARGOS', 'IDCARGO,DESCRIPCIONCARGO,NOMBRECARGO');
                    $this->data['tiposdocumento'] = $this->codegen_model->getSelect('TIPODOCUMENTO', 'CODTIPODOCUMENTO,NOMBRETIPODOC');
                    $this->data['regionales'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL', '', 'ORDER BY 2 ASC');
                    $this->data['tiposempresa'] = $this->codegen_model->getSelect('TIPOEMPRESA', 'CODTIPOEMPRESA,NOM_TIPO_EMP');
                    //$this->data['paises']  = $this->codegen_model->getSelect('PAIS','CODPAIS,NOMBREPAIS');
                    $this->data['departamentos'] = $this->codegen_model->getSelect('DEPARTAMENTO', 'COD_DEPARTAMENTO,NOM_DEPARTAMENTO', '', 'ORDER BY 2 ASC');
                    $this->data['municipios'] = $this->codegen_model->getSelect('MUNICIPIO M', 'M.CODMUNICIPIO,M.NOMBREMUNICIPIO', 'INNER JOIN EMPRESA E ON M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_DEPARTAMENTO WHERE E.CODEMPRESA = ' . $this->uri->segment(3));
                    $this->data['ciu'] = $this->codegen_model->getSelect('CIIU', 'CLASE,DESCRIPCION');
                    $this->data['message'] = $this->session->flashdata('message');

                    $this->template->load($this->template_file, 'crearempresa/crearempresa_edit', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function llenarCiudades() {

        if ($this->input->post('departamento_id')) {
            $regional = $this->input->post('departamento_id');

            $ciudad = $this->codegen_model->getSelect('MUNICIPIO', 'CODMUNICIPIO,COD_DEPARTAMENTO,NOMBREMUNICIPIO', 'WHERE COD_DEPARTAMENTO =' . $regional, ' ORDER BY 3 ASC');


            foreach ($ciudad as $row) {
                $selectc[$row->CODMUNICIPIO] = $row->NOMBREMUNICIPIO;

                /* Forma alternativa de llenar el combo
                 * ?>

                  <option value="'<? echo $row->IDUSUARIO ?>'"><? echo $row->NOMBRES.' '.$row->APELLIDOS ?></option>

                  <?php */
            }
            echo form_dropdown('ciudad', $selectc, '', 'id="ciudad" class="" placeholder="seleccione..." ');
        }
    }

    function id_check() {

        if ($this->ion_auth->empresa_check($this->input->post('c'))) {
            echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>El Documento ya se encuentra registrado en la base de datos.</p>';
        }
    }

    function contacto_check() {

        if ($this->ion_auth->contacto_check($this->input->post('c'))) {
            echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><p>La Identificación del Contacto ya se encuentra registrada en la base de datos.</p>';
        }
    }

    function nombre_empresa($nombre) {
        $query = $this->db->query(" SELECT COUNT(CODEMPRESA) AS EXIST FROM EMPRESA WHERE NOMBRE_EMPRESA='" . $nombre . "' ");
        return $query->result_array[0];
    }

}
