<?php

class Conempresasprocobro extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->file(APPPATH . "controllers/sgva_client.php", true);
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
//            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('conempresasprocobro/manage')) {
//                http://localhost/prototiposena/index.php/conempresasprocobro/manage
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('conempresasprocobro/manage') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                $this->template->set('title', 'consultar empresas en proceso de cobro');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js',
                    'js/jquery.dataTables.columnFilter.js'
                );

                $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                $this->data['empresa'] = $this->codegen_model->getSelect('TIPOEMPRESA', 'CODTIPOEMPRESA,NOM_TIPO_EMP');


                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'conempresasprocobro/conempresasprocobro_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

   function ReasignarSgva($asignado_a, $cod_asignacion) {
        /* Función que envia los parametros para reasignar un fiscalizador a una empresa en Sgva */
        $this->sgva_client = new sgva_client();
        $resultado = $this->sgva_client->ReasignarFiscalizador($asignado_a, $cod_asignacion);
        return $resultado;
    }

    function reasignar() {
        if ($this->ion_auth->logged_in()) {
//            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('conempresasprocobro/reasignar')) {
//            echo $this->ion_auth->is_admin() ."***";
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('conempresasprocobro/reasignar') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {
                $ID = $this->uri->segment(3);
                if ($ID == "") {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para Asignar.</div>');
                    redirect(base_url() . 'index.php/conempresasprocobro');
                } else {
                    $this->load->library('form_validation');
                    $this->data['custom_error'] = '';
                    $this->form_validation->set_rules('hallazgo', 'Hallazgo', 'trim|xss_clean|max_length[20]');
                    $this->form_validation->set_rules('radicado', 'Radicado', 'trim|xss_clean|max_length[20]');
                    $this->form_validation->set_rules('regional_id', 'Regional', 'required|xss_clean|numeric|greater_than[0]');
                    $this->form_validation->set_rules('fiscalizador', 'Fiscalizador', 'numeric|greater_than[0]');
                    $this->form_validation->set_rules('motivo', 'Motivo Reasignacion', 'required|numeric|greater_than[0]');
                    $this->form_validation->set_rules('comentarios', 'Observaciones y Comentarios', 'required|xss_clean|trim|max_length[200]');
                    if ($this->form_validation->run() == false) {
                        $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                    } else {
                        $seleccion = $this->input->post('motivo');
                        $id_fiscalizador = $this->input->post('fiscalizador');

                        $datafiscalizador = $this->codegen_model->getSelect('USUARIOS', 'EMAIL,CORREO_PERSONAL', 'WHERE IDUSUARIO = ' . $id_fiscalizador);
                        foreach ($datafiscalizador as $row) {
                            $emaile = $row->EMAIL;
                            $emailp = $row->CORREO_PERSONAL;

                            $destinatarios = array($emaile, $emailp);
                        }

                        $datamotivo = $this->codegen_model->getSelect('MOTIVOREASIGNACION', 'NOMBRE_MOTIVO', 'WHERE COD_MOTIVO_REASIGNACION = ' . $this->input->post('motivo'));
                        foreach ($datamotivo as $row) {
                            $motivo = $row->NOMBRE_MOTIVO;
                        }

                        $dataregional = $this->codegen_model->getSelect('REGIONAL', 'NOMBRE_REGIONAL, COD_CIUDAD, COD_DEPARTAMENTO', 'WHERE COD_REGIONAL = ' . $this->input->post('regional_id'));
                        foreach ($dataregional as $row) {
                            $regional = $row->NOMBRE_REGIONAL;
                            $nueva_ciudad = $row->COD_CIUDAD;
                            $nuevo_departamento = $row->COD_DEPARTAMENTO;
                        }

                        $dataciudad = $this->codegen_model->getSelect('MUNICIPIO', 'NOMBREMUNICIPIO', 'WHERE CODMUNICIPIO = ' . $this->input->post('city'));
                        foreach ($dataciudad as $row) {
                            $ciudad = $row->NOMBREMUNICIPIO;
                        }
//                        echo "<pre>";
//                        print_r($nuevo_departamento);
//                        echo "</pre>";

                        $nit = $this->input->post('nit');
                        $rsocial = $this->input->post('razonsocial');
                        $ciiu = $this->input->post('ciiu');





                        if ($seleccion == 6) {
                            echo "entre a la validacion del formulario onbase";
                            $this->form_validation->set_rules('radicacion', 'Numero De Radicacion', 'required|xss_clean|trim|numeric|max_length[12]');
                            $this->form_validation->set_rules('nis', 'NIS', 'required|xss_clean|trim|max_length[12]');
                            $this->form_validation->set_rules('fechar', 'Fecha De Radicado', 'required|xss_clean|trim|max_length[10]');
                            $this->form_validation->set_rules('enviado', 'Enviado Por', 'required|xss_clean|trim|max_length[80]');
                            $this->form_validation->set_rules('cargo_id', 'Cargo', 'required|xss_clean|numeric|greater_than[0]');
                            $this->form_validation->set_rules('observaciones', 'Observaciones', 'required|xss_clean|trim|max_length[500]');

                            if ($this->form_validation->run() == false) {
                                $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                            } else {

                                // |:::::datos que se cargaran en la tabla REASIGNACIONFISCALIZADOR
                                $date = array(
                                    'FECHA_REASIGNACION' => date("d/m/Y")
                                ); //00000000000002
                                $post = $this->input->post();
                                if (isset($post['fiscal_actual'])) {
                                    if (!empty($post['fiscal_actual'])) {
                                        $data = array(
                                            'COD_REASIGNACION' => $this->input->post('cod_asignacion'), //no nulo
                                            'COD_FISCALIZADOR_ACTUAL' => $this->input->post('fiscal_actual'),
                                            'COD_FISCALIZADOR_NUEVO' => set_value('fiscalizador'),
                                            'CODMOTIVOREASIGNACION' => set_value('motivo'),
                                            'COMENTARIOS' => set_value('comentarios'), //no nulo
                                            'COD_ASIG_FISCALIZACION' => $this->input->post('cod_asignacion')//no nulo
                                        );
                                        $this->codegen_model->add('REASIGNACIONFISCALIZADOR', $data, $date);
                                    }
                                }
                                // |:::::datos que se actualizaran en la tabla ASIGNACIONFISCALIZACION

                                $data_asignacion = array(
                                    'ASIGNADO_A' => set_value('fiscalizador')
                                );


                                if ($this->codegen_model->edit('ASIGNACIONFISCALIZACION', $data_asignacion, 'NIT_EMPRESA', $this->input->post('id')) == TRUE) {
                                    $reasignacion = $this->codegen_model->get('REASIGNACIONFISCALIZADOR', 'COD_REASIGNACION', 'COD_ASIG_FISCALIZACION = ' . $this->input->post('cod_asignacion'), 1, 1, true);

                                    // |:::::datos que se cargaran en la tabla DATOSENLACE
                                    $dateonbase = array(
                                        'FECHA_RADICADO' => set_value('fechar')
                                    );

                                    $dataonbase = array(
                                        'COD_REASIGNACION' => $reasignacion->COD_REASIGNACION, //campo no nulo
                                        'NRO_RADICADO_ONBASE' => set_value('radicacion'),
                                        'ENVIADO_POR' => set_value('enviado'),
                                        'COD_CARGO' => set_value('cargo_id'),
                                        'OBSERVACIONES' => set_value('observaciones')
                                    );

                                    $this->codegen_model->add('DATOSENLACE', $dataonbase, $dateonbase);
                                    /** Reasignación en Sgva * */
                                    $reasingacion = $this->ReasignarSgva($data_asignacion['ASIGNADO_A'], $this->input->post('cod_asignacion'));

                                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La re-asignacion se ha realizado con éxito.</div>');

                                    redirect(base_url() . 'index.php/conempresasprocobro');

                                    //echo "llego a enviar el email";
                                    // |:::::Envio de E-mail
                                    $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad);
                                } else {
                                    $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';
                                }
                            }
                        } else {
                            //echo "no eligio onbase";
                            // |:::::datos que se cargaran en la tabla REASIGNACIONFISCALIZADOR
                            $date = array(
                                'FECHA_REASIGNACION' => date("d/m/Y")
                            );
                            $post = $this->input->post();
                            if (isset($post['fiscal_actual'])) {
                                if (!empty($post['fiscal_actual'])) {
                                    $data = array(
                                        'COD_REASIGNACION' => $this->input->post('cod_asignacion'), //no nulo
                                        'COD_FISCALIZADOR_ACTUAL' => $this->input->post('fiscal_actual'),
                                        'COD_FISCALIZADOR_NUEVO' => set_value('fiscalizador'),
                                        'CODMOTIVOREASIGNACION' => set_value('motivo'),
                                        'COMENTARIOS' => set_value('comentarios'), //no nulo
                                        'COD_ASIG_FISCALIZACION' => $this->input->post('cod_asignacion'), //no nulo
                                        'HALLAZGO_UGPP' => set_value('hallazgo'),
                                        'RADICADO_UGPP' => set_value('radicado')
                                    );
                                    $this->codegen_model->add('REASIGNACIONFISCALIZADOR', $data, $date);
                                }
                            }
                            // |:::::datos que se actualizaran en la tabla ASIGNACIONFISCALIZACION

                            $data_asignacion = array(
                                'ASIGNADO_A' => set_value('fiscalizador')
                            );


                            //echo "insercion diferente a onbase";
                            //echo "llego a enviar el email";
                            //
                            $data_empresa = array(
                                'COD_REGIONAL' => $this->input->post('regional_id'),
                                'COD_DEPARTAMENTO' => $nuevo_departamento,
                                'COD_MUNICIPIO' => $nueva_ciudad,
                            );
//                            echo "<pre>";
//                            print_r($this->input->post());
//                            echo "</pre>";
//                            die();
                            if ($this->codegen_model->edit('ASIGNACIONFISCALIZACION', $data_asignacion, 'NIT_EMPRESA', $this->input->post('id')) == TRUE && $this->codegen_model->edit('EMPRESA', $data_empresa, 'CODEMPRESA', $this->input->post('id')) == TRUE) {

                                // |:::::Envio de E-mail
                                $this->enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad);
                                /** Reasignación en Sgva * */
                                $reasingacion = $this->ReasignarSgva($data_asignacion['ASIGNADO_A'], $this->input->post('cod_asignacion'));
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La re-asignacion se ha realizado con éxito.' . $reasingacion . ' </div>');


                                redirect(base_url() . 'index.php/conempresasprocobro');

                                //echo "llego a enviar el email";
                            } else {
                                $this->data['custom_error'] = '<div class="form_error"><p>Ha Ocurrido Un Error.</p></div>';
                            }
                        }
                    }
                }
                // |:::::Consultas que permiten conocer  los datos que se alojan en un registro segun el ID de la tabla 
                $this->data['result'] = $this->codegen_model->get('EMPRESA', 'CODEMPRESA,COD_MUNICIPIO,RAZON_SOCIAL,DIRECCION,CIIU,COD_TIPOENTIDAD,COD_TIPOEMPRESA', "CODEMPRESA = '" . $this->uri->segment(3) . "'", 1, 1, true);
                $this->data['asignada'] = $this->codegen_model->get('ASIGNACIONFISCALIZACION', 'ASIGNADO_A,COD_ASIGNACIONFISCALIZACION', 'NIT_EMPRESA = ' . $this->uri->segment(3), 1, 1, true);

//                if(count($this->data['asignada'])==0){
//                    $this->data['asignada']->ASIGNADO_A="";
//                    $this->data['asignada']->COD_ASIGNACIONFISCALIZACION="";
//                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/chosen.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/ZeroClipboard.js'
                );

                $f = "'S'";
                $this->data['fiscalizadores'] = $this->codegen_model->getSelect('USUARIOS', 'IDUSUARIO,NOMBRES,APELLIDOS,EMAIL,CORREO_PERSONAL', 'WHERE FISCALIZADOR = ' . $f);
                $this->data['ciu'] = $this->codegen_model->getSelect('CIIU', 'CLASE,DESCRIPCION');
                $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL,NOMBRE_REGIONAL');
                $this->data['cargos'] = $this->codegen_model->getSelect('CARGOS', 'IDCARGO,NOMBRECARGO');
                $this->data['motivos'] = $this->codegen_model->getSelect('MOTIVOREASIGNACION', 'COD_MOTIVO_REASIGNACION,NOMBRE_MOTIVO');
                $this->data['tiposempresa'] = $this->codegen_model->getSelect('TIPOEMPRESA', 'CODTIPOEMPRESA,NOM_TIPO_EMP');
                $this->data['ciudad'] = $this->codegen_model->getSelect('EMPRESA E', 'M.CODMUNICIPIO,M.NOMBREMUNICIPIO', 'JOIN REGIONAL R ON E.COD_REGIONAL = R.COD_REGIONAL JOIN MUNICIPIO M ON M.COD_DEPARTAMENTO = R.COD_DEPARTAMENTO AND E.COD_MUNICIPIO = M.CODMUNICIPIO WHERE CODEMPRESA = ' . $this->uri->segment(3));
                $this->data['message'] = $this->session->flashdata('message');

                $this->template->load($this->template_file, 'conempresasprocobro/conempresasprocobro_reasignar', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/conempresasprocobro');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function llenarfiscalizadores() {

        if ($this->input->post('regional_id')) {
            $regional = $this->input->post('regional_id');
            $f = "'S'";
            $fiscalizador = $this->codegen_model->getSelect('USUARIOS', 'IDUSUARIO,NOMBRES,APELLIDOS,EMAIL,CORREO_PERSONAL', 'WHERE FISCALIZADOR = ' . $f . ' AND COD_REGIONAL =' . $regional . ' AND ACTIVO = 1');
            $selectf = array('' => '');
            foreach ($fiscalizador as $row) {
                $selectf[$row->IDUSUARIO] = $row->NOMBRES . ' ' . $row->APELLIDOS;

                /* ::::: Forma alternativa de llenar el combo
                 * ?>

                  <option value="'<? echo $row->IDUSUARIO ?>'"><? echo $row->NOMBRES.' '.$row->APELLIDOS ?></option>

                  <?php */
            }
            echo form_dropdown('fiscalizador', $selectf, '', 'id="fiscalizador" class="chosen" placeholder="seleccione..." ');
        }
    }

    function llenarciudades() {




        if ($this->input->post('regional_id')) {
            $regional = $this->input->post('regional_id');

            $ciudad = $this->codegen_model->getSelect('MUNICIPIO', 'CODMUNICIPIO,COD_DEPARTAMENTO,NOMBREMUNICIPIO', 'WHERE COD_DEPARTAMENTO =' . $regional);


            foreach ($ciudad as $row) {
                $selectc[$row->CODMUNICIPIO] = $row->NOMBREMUNICIPIO;

                /* ::::: Forma alternativa de llenar el combo
                 * ?>

                  <option value="'<? echo $row->IDUSUARIO ?>'"><? echo $row->NOMBRES.' '.$row->APELLIDOS ?></option>

                  <?php */
            }
            echo form_dropdown('ciudad', $selectc, '', 'id="ciudad" class="" placeholder="seleccione..." ');
        }
    }

    function enviaremail($destinatarios, $regional, $motivo, $nit, $rsocial, $ciiu, $ciudad) {

        $this->load->library('email');

        $this->email->clear();
        $this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        $this->email->to($destinatarios);


        $this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . 'Nueva asignacion - ' . $rsocial);
        $this->email->message('Nueva asignacion - ' . $rsocial . "\r\n" .
                'NIT: ' . $nit . "\r\n" .
                'Razón Social: ' . $rsocial . "\r\n" .
                'CIIU: ' . $ciiu . "\r\n" .
                'Ciudad: ' . $ciudad . "\r\n" .
                'Regional: ' . $regional . "\r\n" .
                'Motivo De Asignación: ' . $motivo . "\r\n"
        );

        $this->email->send();
        //echo $this->email->print_debugger();
        //$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
        //$this->email->clear();
        //$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
        //$this->email->to($user->EMAIL);
        //$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
        //$this->email->message($message);
    }

    function datatable() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('conempresasprocobro/manage') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999) {
//                    echo "*******************";
                    $this->load->library('datatables');
                    $this->datatables->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                            E.RAZON_SOCIAL,
                                                            E.TELEFONO_FIJO,
                                                            E.DIRECCION,
                                                            M.NOMBREMUNICIPIO,
                                                            R.NOMBRE_REGIONAL,
                                                            T.NOM_TIPO_EMP,
                                                            E.CIIU,
                                                            E.NUM_EMPLEADOS,
                                                            S.NOMBREESTADO,
                                                            U.NOMBRES ||' '|| U.APELLIDOS AS FISCALIZADOR");

                    $this->datatables->from('EMPRESASEVASORAS');
                    $this->datatables->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                    $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                    $this->datatables->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                    $this->datatables->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_DEPARTAMENTO ', 'left');
                    $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                    $this->datatables->join('ASIGNACIONFISCALIZACION A', 'A.NIT_EMPRESA = E.CODEMPRESA');
                    $this->datatables->join('USUARIOS U', 'U.IDUSUARIO = A.ASIGNADO_A', 'left');



                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
                        <div class="btn-group">
                        <form action="' . base_url() . 'index.php/fiscalizacion/detalle_empresa_fiscalizar" method="post" name="form$1">
                            <input type="hidden" id="cod_nit" name="cod_nit" value="$1"><button class="btn btn-small" type="submit" title="Visualizar"><i class="fa fa-eye"></i></button>
                            </form>
                            <a href="' . base_url() . 'index.php/conempresasprocobro/reasignar/$1" class="btn btn-small" title="Reasignar"><i class="fa fa-gavel"></i></a>
                                </div></div>', 'EMPRESASEVASORAS.COD_EMPRESA');
                } else {
                    if ($this->ion_auth->in_menu('conempresasprocobro/manage') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS') || $this->ion_auth->in_group('Coordinadores de Relaciones Corporativas') || $this->ion_auth->in_group('Coordinador Relaciones Corporativas') || $this->ion_auth->in_group('para Administrativo') || $this->session->userdata('regional') == 999) {

                        $this->load->library('datatables');

                        $this->datatables->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                                E.RAZON_SOCIAL,
                                                                E.TELEFONO_FIJO,
                                                                E.DIRECCION,
                                                                M.NOMBREMUNICIPIO,
                                                                R.NOMBRE_REGIONAL,
                                                                T.NOM_TIPO_EMP,
                                                                E.CIIU,
                                                                E.NUM_EMPLEADOS,
                                                                S.NOMBREESTADO,
                                                                (U.NOMBRES ||' '|| U.APELLIDOS) AS FISCALIZADOR");

                        $this->datatables->from('EMPRESASEVASORAS');
                        $this->datatables->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                        $this->datatables->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                        $this->datatables->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_DEPARTAMENTO ', 'left');
                        $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                        $this->datatables->join('ASIGNACIONFISCALIZACION A', 'A.NIT_EMPRESA = E.CODEMPRESA');
                        $this->datatables->join('USUARIOS U', 'U.IDUSUARIO = A.ASIGNADO_A', 'left');
                        $this->datatables->where('E.COD_REGIONAL', $this->session->userdata('regional'));




                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																				<div class="btn-group">
																					<form action="' . base_url() . 'index.php/fiscalizacion/detalle_empresa_fiscalizar" method="post" name="form$1">
	    
																																     
																					    <input type="hidden" id="cod_nit" name="cod_nit" value="$1">
																					    <button class="btn btn-small" type="submit" title="Visualizar"><i class="fa fa-eye"></i></button>
																																	

																					</form>
																					<a href="' . base_url() . 'index.php/conempresasprocobro/reasignar/$1" class="btn btn-small" title="Reasignar"><i class="fa fa-gavel"></i></a>
																					
																														 </div>

																		</div>', 'EMPRESASEVASORAS.COD_EMPRESA');
                    } else {

                        $this->load->library('datatables');
                        $this->datatables->select("EMPRESASEVASORAS.COD_EMPRESA,
                                                        E.RAZON_SOCIAL,
                                                        E.TELEFONO_FIJO,
                                                        E.DIRECCION,
                                                        R.NOMBRE_REGIONAL,
                                                        T.NOM_TIPO_EMP,
                                                        E.CIIU,
                                                        E.NUM_EMPLEADOS,
                                                        S.NOMBREESTADO,
                                                        (U.NOMBRES ||' '|| U.APELLIDOS) AS FISCALIZADOR");

                        $this->datatables->from('EMPRESASEVASORAS');
                        $this->datatables->join('EMPRESA E', 'E.CODEMPRESA = EMPRESASEVASORAS.COD_EMPRESA', 'inner');
                        $this->datatables->join('REGIONAL R', 'R.COD_REGIONAL = E.COD_REGIONAL', 'inner');
                        $this->datatables->join('ESTADOS S', 'S.IDESTADO = E.ACTIVO', 'left');
                        $this->datatables->join('MUNICIPIO M', 'M.CODMUNICIPIO = E.COD_MUNICIPIO AND M.COD_DEPARTAMENTO = E.COD_DEPARTAMENTO ', 'left');
                        $this->datatables->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA = E.COD_TIPOEMPRESA', 'left');
                        $this->datatables->join('ASIGNACIONFISCALIZACION A', 'A.NIT_EMPRESA = E.CODEMPRESA');
                        $this->datatables->join('USUARIOS U', 'U.IDUSUARIO = A.ASIGNADO_A', 'left');
                        $this->datatables->where('E.COD_REGIONAL', $this->session->userdata('regional'));


                        $this->datatables->add_column('edit', '<div class="btn-toolbar">
																														 <div class="btn-group">
																																<a href="#" class="btn btn-small disabled" title="Reasignar"><i class="fa fa-gavel"></i></a>
																																<a href="#" class="btn btn-small disabled" title="Visualizar"><i class="fa fa-eye"></i></a>
																																
																														 </div>
																												 </div>', 'EMPRESASEVASORAS.COD_EMPRESA');
                    }
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/conempresasprocobro');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
re