<?php

class Asignarfiscalizacion extends MY_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('asigna_model');
        $this->load->file(APPPATH . "controllers/sgva_client.php", true);
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignarfiscalizacion/manage') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                //template data
                $this->template->set('title', 'Consultar Informacion Empresas');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/jquery.dataTables.columnFilter.js'
                );
                $this->data['ciudad'] = $this->codegen_model->getSelect('MUNICIPIO', 'CODMUNICIPIO,NOMBREMUNICIPIO');
                $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                $this->data['estado'] = $this->codegen_model->getSelect('ESTADOSOCIEDAD', 'CODESTADOSOCIEDAD,NOM_TIPO_SOC');
                $this->data['empresa'] = $this->codegen_model->getSelect('TIPOEMPRESA', 'CODTIPOEMPRESA,NOM_TIPO_EMP');
                $this->data['nuevas'] = $this->asigna_model->asignacionNuevas();
                $this->data['evasoras'] = $this->asigna_model->asignaEvasoras();


                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'asignarfiscalizacion/asignarfiscalizacion_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function asignar() {
        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignarfiscalizacion/asignar')) {

                $ID = $this->uri->segment(3);

                if ($ID == "") {

                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para Asignar.</div>');
                    redirect(base_url() . 'index.php/asignarfiscalizacion');
                } else {

                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('nit', 'NIT', 'required|xss_clean|trim|numeric|max_length[15]');
                    $this->form_validation->set_rules('razonsocial', 'Razón Social', 'required|xss_clean|trim|max_length[255]');
                    $this->form_validation->set_rules('observaciones', 'Observaciones', 'required|xss_clean|trim|max_length[1000]');
                    //$this->form_validation->set_rules('regional_id', 'Regional',  'required|xss_clean|numeric|greater_than[0]');
                    $this->form_validation->set_rules('fiscalizador', 'Fiscalizador', 'required|xss_clean|numeric|greater_than[0]');

                    if ($this->form_validation->run() == false) {

                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {

                        if ($this->ion_auth->evasora_check($this->input->post('nit')) || $this->ion_auth->asignada_check($this->input->post('nit'))) {
                            $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Esta empresa ya ha sido identificada como evasora</div>';
                        } else {

                            $id_fiscalizador = $this->input->post('fiscalizador');

                            $datafiscalizador = $this->codegen_model->getSelect('USUARIOS', 'EMAIL,CORREO_PERSONAL', 'WHERE IDUSUARIO = ' . $id_fiscalizador);
                            foreach ($datafiscalizador as $row) {
                                $emaile = $row->EMAIL;
                                $emailp = $row->CORREO_PERSONAL;

                                $destinatarios = array($emaile, $emailp);
                            }

                            $email_regional = $this->codegen_model->getSelect('REGIONAL', 'EMAIL_REGIONAL', 'WHERE COD_REGIONAL = ' . $this->session->userdata('regional'));
                            foreach ($email_regional as $row) {

                                $email_r = $row->EMAIL_REGIONAL;

                                $destinatarios = array($email_r);
                            }


                            $dataregional = $this->codegen_model->getSelect('REGIONAL', 'NOMBRE_REGIONAL', 'WHERE COD_REGIONAL = ' . $this->session->userdata('regional'));
                            foreach ($dataregional as $row) {
                                $regional = $row->NOMBRE_REGIONAL;
                            }


                            $nit = $this->input->post('nit');
                            $rsocial = $this->input->post('razonsocial');
                            $motivo = $this->input->post('observaciones');

                            // |:::::datos que se cargaran en la tabla EMPRESASEVASORAS
                            $dateevasoras = array(
                                'FECHA_PROCESAMIENTO' => date("d/m/Y")
                            );

                            $dataevasoras = array(
                                'COD_EMPRESA' => $this->input->post('nit'),
                                'FISCALIZADA' => 1
                            );



                            // |:::::datos que se cargaran en la tabla ASIGNACIONFISCALIZACION
                            $date_asignacion = array(
                                'FECHA_ASIGNACION' => date("d/m/Y")
                            );

                            $data_asignacion = array(
                                'NIT_EMPRESA' => $this->input->post('nit'),
                                'ASIGNADO_A' => $this->input->post('fiscalizador'),
                                //::::: IDUSUARIO Traido de la cookie de sesion.	
                                'ASIGNADO_POR' => $this->session->userdata('user_id'),
                                'COMENTARIOS_ASIGNACION' => $this->input->post('observaciones')
                            );


                            if ($this->codegen_model->add('EMPRESASEVASORAS', $dataevasoras, $dateevasoras) == TRUE) {

                                $asignacion = $this->codegen_model->get('EMPRESASEVASORAS', 'COD_EMP_EVASORA', array('COD_EMPRESA' => $this->uri->segment(3)), 1, 1, true);

                                // |:::::datos que se cargaran en la tabla EMPRESASEVASORAS_DET
                                $datadetalle = array(
                                    'COD_EMP_EVASORA' => $asignacion->COD_EMP_EVASORA,
                                    'OBSERVACIONES' => $this->input->post('observaciones')
                                );
                                $this->codegen_model->add('EMPRESASEVASORAS_DET', $datadetalle);
                                $this->codegen_model->add('ASIGNACIONFISCALIZACION', $data_asignacion, $date_asignacion);
                                // |:::::Envio de E-mail
                                $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial);
                                //Asignación de fiscalizador de Sgva
                                $this->sgva_client = new sgva_client();
                                $resultado = $this->sgva_client->AsignarFiscalizador($data_asignacion);

 								$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se realizó la asignación de fiscalización correctamente.​</div>');

                                redirect(base_url() . 'index.php/asignarfiscalizacion/');
                            } else {

                                $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            }
                        }
                    }

                    $this->data['result'] = $this->codegen_model->get('EMPRESA', 'CODEMPRESA,COD_MUNICIPIO,COD_REGIONAL,RAZON_SOCIAL,DIRECCION,CIIU,COD_TIPOENTIDAD', array('CODEMPRESA' => $this->uri->segment(3)), 1, 1, true);


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen'
                    );
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js'
                    );


                    $regional_f = $this->session->userdata('regional');
                    $f = "'S'";
                    $this->data['fiscalizador'] = $this->codegen_model->getSelect('USUARIOS', "IDUSUARIO,NOMBRES,APELLIDOS,EMAIL,CORREO_PERSONAL", "WHERE FISCALIZADOR = " . $f . " AND COD_REGIONAL ='" . $regional_f . "' AND ACTIVO = '1'");
                    $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                    $this->data['ciudad'] = $this->codegen_model->getSelect('EMPRESA E', 'M.CODMUNICIPIO,M.NOMBREMUNICIPIO', 'JOIN REGIONAL R ON E.COD_REGIONAL = R.COD_REGIONAL JOIN MUNICIPIO M ON M.COD_DEPARTAMENTO = R.COD_DEPARTAMENTO AND E.COD_MUNICIPIO = M.CODMUNICIPIO WHERE CODEMPRESA = ' . $this->uri->segment(3));


                    $this->template->load($this->template_file, 'asignarfiscalizacion/asignarfiscalizacion_asignar', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/asignarfiscalizacion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function asignarevasoras() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignarfiscalizacion/asignarevasoras') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                $ID = $this->uri->segment(3);

                if ($ID == "") {

                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para Asignar.</div>');
                    redirect(base_url() . 'index.php/asignarfiscalizacion');
                } else {

                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('nit', 'NIT', 'required|xss_clean|trim|numeric|max_length[15]');
                    $this->form_validation->set_rules('razonsocial', 'Razón Social', 'required|xss_clean|trim|max_length[255]');
                    $this->form_validation->set_rules('observaciones', 'Observaciones', 'required|xss_clean|trim|max_length[1000]');
                    //$this->form_validation->set_rules('regional_id', 'Regional',  'required|xss_clean|numeric|greater_than[0]');
                    $this->form_validation->set_rules('fiscalizador', 'Fiscalizador', 'required|xss_clean|numeric|greater_than[0]');

                    if ($this->form_validation->run() == false) {

                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {

                        if ($this->ion_auth->asignada_check($this->input->post('nit'))) {
                            $this->data['custom_error'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Esta empresa ya tiene asignado un fiscalizador</div>';
                        } else {

                            $id_fiscalizador = $this->input->post('fiscalizador');

                            $datafiscalizador = $this->codegen_model->getSelect('USUARIOS', 'EMAIL,CORREO_PERSONAL', 'WHERE IDUSUARIO = ' . $id_fiscalizador);
                            foreach ($datafiscalizador as $row) {
                                $emaile = $row->EMAIL;
                                $emailp = $row->CORREO_PERSONAL;

                                $destinatarios = array($emaile, $emailp);
                            }

                            $email_regional = $this->codegen_model->getSelect('REGIONAL', 'EMAIL_REGIONAL', 'WHERE COD_REGIONAL = ' . $this->session->userdata('regional'));
                            foreach ($email_regional as $row) {

                                $email_r = $row->EMAIL_REGIONAL;

                                $destinatarios = array($email_r);
                            }


                            $dataregional = $this->codegen_model->getSelect('REGIONAL', 'NOMBRE_REGIONAL', 'WHERE COD_REGIONAL = ' . $this->session->userdata('regional'));
                            foreach ($dataregional as $row) {
                                $regional = $row->NOMBRE_REGIONAL;
                            }


                            $nit = $this->input->post('nit');
                            $rsocial = $this->input->post('razonsocial');
                            $motivo = $this->input->post('observaciones');

                            // |:::::datos que se cargaran en la tabla EMPRESASEVASORAS

                            $dataevasoras = array(
                                'FISCALIZADA' => 1
                            );

                            // |:::::datos que se cargaran en la tabla ASIGNACIONFISCALIZACION
                            $date_asignacion = array(
                                'FECHA_ASIGNACION' => date("d/m/Y")
                            );

                            $data_asignacion = array(
                                'NIT_EMPRESA' => $this->input->post('nit'),
                                'ASIGNADO_A' => $this->input->post('fiscalizador'),
                                //::::: IDUSUARIO Traido de la cookie de sesion.	
                                'ASIGNADO_POR' => $this->session->userdata('user_id'),
                                'COMENTARIOS_ASIGNACION' => $this->input->post('observaciones')
                            );


                            if ($this->codegen_model->edit('EMPRESASEVASORAS', $dataevasoras, 'COD_EMPRESA', $this->uri->segment(3), 1, 1, true) == TRUE && $this->codegen_model->add('ASIGNACIONFISCALIZACION', $data_asignacion, $date_asignacion) == TRUE) {

                                // |:::::Envio de E-mail
                                $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial);
                                //Asignación de fiscalizador de Sgva
                                $this->sgva_client = new sgva_client();
                                $resultado = $this->sgva_client->AsignarFiscalizador($data_asignacion);
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La operación se realizó correctamente en Cartera.</div>');

                                redirect(base_url() . 'index.php/asignarfiscalizacion/');
                            } else {

                                $this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';
                            }
                        }
                    }

                    $this->data['result'] = $this->codegen_model->get('EMPRESA', 'CODEMPRESA,COD_MUNICIPIO,COD_REGIONAL,RAZON_SOCIAL,DIRECCION,CIIU,COD_TIPOENTIDAD', array('CODEMPRESA' => $this->uri->segment(3)), 1, 1, true);


                    //add style an js files for inputs selects
                    $this->data['style_sheets'] = array(
                        'css/chosen.css' => 'screen');
                    $this->data['javascripts'] = array(
                        'js/chosen.jquery.min.js'
                    );


                    $regional_f = $this->session->userdata('regional');
                    $f = "'S'";
                    $this->data['fiscalizador'] = $this->codegen_model->getSelect('USUARIOS', 'IDUSUARIO,NOMBRES,APELLIDOS,EMAIL,CORREO_PERSONAL', "WHERE FISCALIZADOR = " . $f . " AND COD_REGIONAL ='" . $regional_f . "' AND ACTIVO = '1'");
                    $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                    $this->data['ciudad'] = $this->codegen_model->getSelect('MUNICIPIO M', "M.CODMUNICIPIO,M.NOMBREMUNICIPIO", 'JOIN EMPRESA E ON M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_REGIONAL WHERE CODEMPRESA = ' . $this->uri->segment(3));


                    $this->template->load($this->template_file, 'asignarfiscalizacion/asignarfiscalizacion_asignar_evasoras', $this->data);
                }
            } else {

                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/asignarfiscalizacion');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function llenarciudades() {




        if ($this
                ->input->post('regional_id')) {
            $regional = $this->input->post('regional_id');

            $ciudad = $this->codegen_model->getSelect('MUNICIPIO', 'CODMUNICIPIO,COD_DEPARTAMENTO,NOMBREMUNICIPIO', 'WHERE COD_DEPARTAMENTO =' . $regional);


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

    function llenarfiscalizadores() {




        if ($this->input->post('regional_id')) {
            $regional = $this->input->post('regional_id');
            $f = "'S'";

            $fiscalizador = $this->codegen_model->getSelect('USUARIOS', 'IDUSUARIO,NOMBRES,APELLIDOS,EMAIL,CORREO_PERSONAL', "WHERE FISCALIZADOR = " . $f . " AND COD_REGIONAL ='" . $regional . "' AND ACTIVO = '1'");
            foreach ($fiscalizador as $row) {
                $selectf[$row->IDUSUARIO] = $row->NOMBRES . ' ' . $row->APELLIDOS;

                /* Forma alternativa de llenar el combo
                 * ?>

                  <option value="'<? echo $row->IDUSUARIO ?>'"><? echo $row->NOMBRES.' '.$row->APELLIDOS ?></option>

                  <?php */
            }
            echo form_dropdown('fiscalizador', $selectf, '', 'id="fiscalizador" class="chosen span3" placeholder="seleccione..." ');
        }
    }

    function enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial) {

        $this->load->library('email');

        $this->email
                ->clear();
        $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        $this->email->to($destinatarios);


        $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . 'Nueva asignacion - ' . $rsocial);
        $this->email->message('Nueva asignacion - ' . $rsocial . "\r\n" .
                'NIT: ' . $nit . "\r\n" .
                'Razón Social: ' . $rsocial . "\r\n" .
                'Ciudad: ' . $ciudad . "\r\n" .
                'Regional: ' . $regional . "\r\n" .
                'Motivo De Asignación: ' . $motivo . "\r\n"
        );

        $this->email->send();
        //echo $this->email->print_debugger();
    }

    function datatablenuevas() {

        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignarfiscalizacion/manage') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {



                    $this->load->library('datatables');
                    $this->datatables->select('EMPRESA.CODEMPRESA,
                                                EMPRESA.RAZON_SOCIAL,
                                                EMPRESA.TELEFONO_FIJO,
                                                EMPRESA.DIRECCION,
                                                R.NOMBRE_REGIONAL,
                                                T.NOM_TIPO_EMP,
                                                EMPRESA.CIIU,
                                                EMPRESA.NUM_EMPLEADOS,
                                                A.NOM_TIPO_ENT,
                                                E.NOMBREESTADO');
                    $this->datatables->from('EMPRESA');
                    $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = EMPRESA.ACTIVO', 'left');
                    //$this->datatables->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', 'inner');
                    $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
                    $this->datatables->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');

                    $this->datatables->where("EMPRESA.CODEMPRESA NOT IN (SELECT COD_EMPRESA FROM EMPRESASEVASORAS)");
                    $this->datatables->where("EMPRESA.CODEMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");


                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																<div class="btn-group">
																	<a href="' . base_url() . 'index.php/asignarfiscalizacion/asignar/$1" class="btn btn-small" title="Asignar Fiscalizacion"><i class="fa fa-gavel"></i></a>
																															
																</div>
															</div>', 'EMPRESA.CODEMPRESA');
                } else {

                    if ($this->ion_auth->in_menu('asignarfiscalizacion/asignar')) {

                        $this->load->library('datatables');
                        $this->datatables->select('EMPRESA.CODEMPRESA,
                                                    EMPRESA.RAZON_SOCIAL,
                                                    EMPRESA.TELEFONO_FIJO,
                                                    EMPRESA.DIRECCION,
                                                    R.NOMBRE_REGIONAL,
                                                    T.NOM_TIPO_EMP,
                                                    EMPRESA.CIIU,
                                                    EMPRESA.NUM_EMPLEADOS,
                                                    A.NOM_TIPO_ENT,
                                                    E.NOMBREESTADO');
                        $this->datatables->from('EMPRESA');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
                        $this->datatables->join('ESTADOS E', 'E.IDESTADO = EMPRESA.ACTIVO', 'left');
                        //$this->datatables->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', 'inner');
                        $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
                        $this->datatables->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');
                        $this->datatables->where("EMPRESA.CODEMPRESA NOT IN (SELECT COD_EMPRESA FROM EMPRESASEVASORAS)");
                        $this->datatables->where("EMPRESA.CODEMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                        $this->datatables->where('EMPRESA.COD_REGIONAL', $this->session->userdata('regional'));

                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																	<div class="btn-group">
																		<a href="' . base_url() . 'index.php/asignarfiscalizacion/asignar/$1" class="btn btn-small" title="Asignar Fiscalizacion"><i class="fa fa-gavel"></i></a>
																																
																	</div>
																</div>', 'EMPRESA.CODEMPRESA');
                    } else {

                        $this->load->library('datatables');
                        $this->datatables->select('EMPRESA.CODEMPRESA,
                                                    EMPRESA.RAZON_SOCIAL,
                                                    EMPRESA.TELEFONO_FIJO,
                                                    EMPRESA.DIRECCION,
                                                    R.NOMBRE_REGIONAL,
                                                    T.NOM_TIPO_EMP,
                                                    EMPRESA.CIIU,
                                                    EMPRESA.NUM_EMPLEADOS,
                                                    A.NOM_TIPO_ENT,
                                                    E.NOMBREESTADO');
                        $this->datatables->from('EMPRESA');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = EMPRESA.COD_REGIONAL', 'inner');
                        $this->datatables->join('ESTADOS E', 'E.IDESTADO = EMPRESA.ACTIVO', 'left');
                        //$this->datatables->join('MUNICIPIO M','M.CODMUNICIPIO = EMPRESA.COD_MUNICIPIO', 'inner');
                        $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = EMPRESA.COD_TIPOEMPRESA', 'left');
                        $this->datatables->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = EMPRESA.COD_TIPOENTIDAD', 'left');
                        $this->datatables->where("EMPRESA.CODEMPRESA NOT IN (SELECT COD_EMPRESA FROM EMPRESASEVASORAS)");
                        $this->datatables->where("EMPRESA.CODEMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                        $this->datatables->where('EMPRESA.COD_REGIONAL', $this->session->userdata('regional'));

                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																	<div class="btn-group">
																		<a href="#" class="btn btn-small" title="Asignar Fiscalizacion"><i class="fa fa-gavel"></i></a>
																																
																	</div>
																</div>', 'EMPRESA.CODEMPRESA');
                    }
                }

                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableevasoras() {

        if ($this->ion_auth->logged_in()) {

            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('asignarfiscalizacion/manage') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {



                    $this->load->library('datatables');

                    //$this->datatables->distinct();
                    $this->datatables->select("DISTINCT(EMPRESASEVASORAS.COD_EMPRESA) AS COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            A.NOM_TIPO_ENT,
                                                            S.NOMBREESTADO");
                    $this->datatables->from('EMPRESASEVASORAS');
                    $this->datatables->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                    $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                    $this->datatables->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                    $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                    $this->datatables->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = E.COD_TIPOENTIDAD', 'left');
                    $this->datatables->where("EMPRESASEVASORAS.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                    $this->datatables->where('EMPRESASEVASORAS.FISCALIZADA', null);

                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
                        <div class="btn-group">
                        <a href="' . base_url() . 'index.php/asignarfiscalizacion/asignarevasoras/$1" class="btn btn-small" title="Asignar Fiscalizacion"><i class="fa fa-gavel"></i></a>
                            </div></div>', 'COD_EMPRESA');
                } else {

                    if ($this->ion_auth->in_menu('asignarfiscalizacion/asignarevasoras')) {

                        $this->load->library('datatables');
//                        $this->datatables->distinct();
                        $this->datatables->select("DISTINCT(EMPRESASEVASORAS.COD_EMPRESA) AS COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            A.NOM_TIPO_ENT,
                                                            S.NOMBREESTADO");
                        $this->datatables->from('EMPRESASEVASORAS');
                        $this->datatables->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                        $this->datatables->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                        $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                        $this->datatables->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = E.COD_TIPOENTIDAD', 'left');
                        $this->datatables->where("EMPRESASEVASORAS.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                        $this->datatables->where('EMPRESASEVASORAS.FISCALIZADA', null);
                        $this->datatables->where('E.COD_REGIONAL', $this->session->userdata('regional'));

                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
                            <div class="btn-group">
                            <a href="' . base_url() . 'index.php/asignarfiscalizacion/asignarevasoras/$1" class="btn btn-small" title="Asignar Fiscalizacion"><i class="fa fa-gavel"></i></a>
                                </div></div>', 'COD_EMPRESA');
                    } else {

                        $this->load->library('datatables');
//                        $this->datatables->distinct();
                        $this->datatables->select("DISTINCT(EMPRESASEVASORAS.COD_EMPRESA) AS COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            A.NOM_TIPO_ENT,
                                                            S.NOMBREESTADO");
                        $this->datatables->from('EMPRESASEVASORAS');
                        $this->datatables->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                        $this->datatables->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                        $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                        $this->datatables->join('TIPOENTIDAD A', 'A.CODTIPOENTIDAD = E.COD_TIPOENTIDAD', 'left');
                        $this->datatables->where("EMPRESASEVASORAS.COD_EMPRESA NOT IN (SELECT NIT_EMPRESA FROM ASIGNACIONFISCALIZACION)");
                        $this->datatables->where('EMPRESASEVASORAS.FISCALIZADA', null);
                        $this->datatables->where('E.COD_REGIONAL', $this->session->userdata('regional'));

                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
                            <div class="btn-group">
                            <a href="#" class="btn btn-small" title="Asignar Fiscalizacion"><i class="fa fa-gavel"></i></a>
                            </div>
                            </div>', 'COD_EMPRESA');
                    }
                }

                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
