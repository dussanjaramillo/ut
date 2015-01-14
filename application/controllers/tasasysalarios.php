<?php

class Tasasysalarios extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'url',
            'codegen_helper'
        ));
        $this->load->model('codegen_model', '', TRUE);
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/manage')) {
                //template data
                $this->template->set('title', 'Tasas y Salarios');
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.defaults.js'
                );
                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'tasasysalarios/tasasysalarios_list.php', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addtasasuperintendencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/addtasasuperintendencia')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm = 0;
                $this->form_validation->set_rules('tasasuper', 'Tasa Superintendencia', 'required|numeric');
                $this->form_validation->set_rules('tipotasa', 'Tipo Tasa', 'required|numeric');
                $this->form_validation->set_rules('vigenciadesde', 'Vigencia Desde', 'required');
                $this->form_validation->set_rules('vigenciahasta', 'Vigencia Hasta', 'required');
                $this->form_validation->set_rules('fechacreacion', 'Fecha Creación', 'required');
                $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');


                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $date = array(
                        'VIGENCIA_DESDE' => set_value('vigenciadesde'),
                        'VIGENCIA_HASTA' => set_value('vigenciahasta'),
                        'FECHACREACION' => set_value('fechacreacion')
                    );

                    $data = array(
                        'ID_TASA_SUPERINTENDENCIA' => $this->codegen_model->getSequence('dual', 'ID_TASA_SUPERINTENDENCIA_SEQ.NEXTVAL'),
                        'TASA_SUPERINTENDENCIA' => set_value('tasasuper'),
                        'ID_TIPO_TASA' => set_value('tipotasa'),
                        'IDESTADO' => set_value('estado_id')
                    );

                    if ($this->codegen_model->add('TASA_SUPERINTENDENCIA', $data, $date) == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de superintendencia se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/tasasysalarios/');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/datepicker.css' => 'screen',
                    'css/bootstrap.css' => 'screen',
                    'css/datepicker.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/bootstrap-datepicker.js'
                );


                $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['tasas'] = $this->codegen_model->getSelect('TIPO_TASA', 'COD_TIPO_TASA,DESCRIPCION');


                $this->template->load($this->template_file, 'tasasysalarios/tasasysalarios_super_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addtasas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/addtasas')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm = 0;
                $this->form_validation->set_rules('tasa', 'Tasa', 'required|numeric');
                $this->form_validation->set_rules('tipotasa', 'Tipo Tasa', 'required|numeric');
                $this->form_validation->set_rules('vigenciadesde', 'Vigencia Desde', 'required');
                $this->form_validation->set_rules('vigenciahasta', 'Vigencia Hasta', 'required');
                $this->form_validation->set_rules('fechacreacion', 'Fecha Creación', 'required');
                $this->form_validation->set_rules('acuerdores', 'Acuerdo/Resolución', 'required|trim|xss_clean|max_length[200]');
                $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $this->db->trans_begin();
                    $date = array(
                        'VIGENCIA_DESDE' => set_value('vigenciadesde'),
                        'VIGENCIA_HASTA' => set_value('vigenciahasta'),
                        'FECHACREACION' => set_value('fechacreacion')
                    );

                    $data = array(
                        'IDTASA' => $this->codegen_model->getSequence('dual', 'IDTASA_SEQ.NEXTVAL'),
                        'VALOR_TASA' => set_value('tasa'),
                        'ID_TIPO_TASA' => set_value('tipotasa'),
                        'ACUERDO' => set_value('acuerdores'),
                        'IDESTADO' => set_value('estado_id')
                    );

                    $this->codegen_model->add('TASA_PARAMETRIZADO', $data, $date);
                    
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/tasasysalarios/');
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/datepicker.css' => 'screen',
                    'css/bootstrap.css' => 'screen',
                    'css/datepicker.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/bootstrap-datepicker.js'
                );


                $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');
                $this->data['tasas'] = $this->codegen_model->getSelect('TIPO_TASA', 'COD_TIPO_TASA,DESCRIPCION');


                $this->template->load($this->template_file, 'tasasysalarios/tasasysalarios_tasas_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addtasauvt() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/addtasauvt')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm = 0;
                $this->form_validation->set_rules('salario', 'SMMLV', 'required|numeric|trim|xss_clean|max_length[15]');
                $this->form_validation->set_rules('tasa', 'UVT', 'required|numeric|trim|xss_clean|max_length[8,0]');
                $this->form_validation->set_rules('anio', 'Año de Vigencia', 'required|numeric|trim|xss_clean|max_length[4]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {


                    $data = array(
                        'ANNO' => set_value('anio'),
                        'SALARIO_MINIMO' => set_value('salario'),
                        'VALOR_UVT' => set_value('tasa')
                    );

                    if ($this->codegen_model->add('HISTORICOSALARIOMINIMO_UVT', $data) == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/tasasysalarios/');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/datepicker.css' => 'screen',
                    'css/bootstrap.css' => 'screen',
                    'css/datepicker.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/bootstrap-datepicker.js'
                );


                $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');


                $this->template->load($this->template_file, 'tasasysalarios/tasasysalarios_uvt_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function addtasasalario() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/addtasauvt')) {
                $this->load->library('form_validation');
                $this->data['custom_error'] = '';
                $showForm = 0;
                $this->form_validation->set_rules('salario', 'SMMLV', 'required|numeric');
                $this->form_validation->set_rules('vigenciadesde', 'Vigencia Desde', 'required');
                $this->form_validation->set_rules('vigenciahasta', 'Vigencia Hasta', 'required');
                $this->form_validation->set_rules('fechacreacion', 'Fecha Creación', 'required');
                $this->form_validation->set_rules('estado_id', 'Estado', 'required|numeric|greater_than[0]');
                if ($this->form_validation->run() == false) {
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);
                } else {
                    $date = array(
                        'VIGENCIA_DESDE' => set_value('vigenciadesde'),
                        'VIGENCIA_HASTA' => set_value('vigenciahasta'),
                        'FECHACREACION' => set_value('fechacreacion')
                    );

                    $data = array(
                        'ID_SALARIO' => $this->codegen_model->getSequence('dual', 'ID_SALARIO_SEQ.NEXTVAL'),
                        'SALARIO_VIGENTE' => set_value('salario'),
                        'IDESTADO' => set_value('estado_id')
                    );

                    if ($this->codegen_model->add('SALARIO', $data, $date) == TRUE) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El nuevo salario se ha creado con éxito.</div>');
                        redirect(base_url() . 'index.php/tasasysalarios/');
                    } else {
                        $this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
                    }
                }
                //add style an js files for inputs selects
                $this->data['style_sheets'] = array(
                    'css/datepicker.css' => 'screen',
                    'css/bootstrap.css' => 'screen',
                    'css/datepicker.css' => 'screen'
                );
                $this->data['javascripts'] = array(
                    'js/chosen.jquery.min.js',
                    'js/bootstrap-datepicker.js'
                );


                $this->data['estados'] = $this->codegen_model->getSelect('ESTADOS', 'IDESTADO,NOMBREESTADO');


                $this->template->load($this->template_file, 'tasasysalarios/tasasysalarios_salario_add', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatabletasas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/manage')) {

                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/edit')) {
                    $this->load->library('datatables');
                    $this->datatables->select('TASA_PARAMETRIZADO.IDTASA,
												TASA_PARAMETRIZADO.VALOR_TASA,
												T.DESCRIPCION,
												TASA_PARAMETRIZADO.VIGENCIA_DESDE,
												TASA_PARAMETRIZADO.VIGENCIA_HASTA,
												TASA_PARAMETRIZADO.FECHACREACION,
												TASA_PARAMETRIZADO.ACUERDO,
												E.NOMBREESTADO');
                    $this->datatables->from('TASA_PARAMETRIZADO');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = TASA_PARAMETRIZADO.IDESTADO', ' inner ');
                    $this->datatables->join('TIPO_TASA T', 'T.COD_TIPO_TASA = TASA_PARAMETRIZADO.ID_TIPO_TASA', 'inner');
                } else {
                    $this->load->library('datatables');
                    $this->datatables->select('TASA_PARAMETRIZADO.IDTASA,
												TASA_PARAMETRIZADO.VALOR_TASA,
												T.DESCRIPCION,
												TASA_PARAMETRIZADO.VIGENCIA_DESDE,
												TASA_PARAMETRIZADO.VIGENCIA_HASTA,
												TASA_PARAMETRIZADO.FECHACREACION,
												TASA_PARAMETRIZADO.ACUERDO,
												E.NOMBREESTADO');
                    $this->datatables->from('TASA_PARAMETRIZADO');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = TASA_PARAMETRIZADO.IDESTADO', ' inner ');
                    $this->datatables->join('TIPO_TASA T', 'T.COD_TIPO_TASA = TASA_PARAMETRIZADO.ID_TIPO_TASA', 'inner');
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatableuvt() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/manage')) {

                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/edit')) {
                    $this->load->library('datatables');
                    $this->datatables->select('H.ANNO,
												H.SALARIO_MINIMO,
												H.VALOR_UVT');
                    $this->datatables->from('HISTORICOSALARIOMINIMO_UVT H');
                    //$this->datatables->join('ESTADOS E', 'E.IDESTADO = TASA_UVT.IDESTADO', ' left ');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="' . base_url() . 'index.php/tasasysalarios/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_UVT.ID_TASA_UVT');
                } else {
                    $this->load->library('datatables');
                    $this->load->library('datatables');
                    $this->datatables->select('H.ANNO,
                                                H.SALARIO_MINIMO,
                                                H.VALOR_UVT');
                    $this->datatables->from('HISTORICOSALARIOMINIMO_UVT H');
                    //$this->datatables->join('ESTADOS E', 'E.IDESTADO = TASA_UVT.IDESTADO', ' left ');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_UVT.ID_TASA_UVT');
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatablesalario() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/manage')) {

                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/edit')) {
                    $this->load->library('datatables');
                    $this->datatables->select('SALARIO.ID_SALARIO,SALARIO.SALARIO_VIGENTE, SALARIO.VIGENCIA_DESDE,SALARIO.VIGENCIA_HASTA,SALARIO.FECHACREACION,E.NOMBREESTADO');
                    $this->datatables->from('SALARIO');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = SALARIO.IDESTADO', ' left ');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="' . base_url() . 'index.php/tasasysalarios/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'SALARIO.ID_SALARIO');
                } else {
                    $this->load->library('datatables');
                    $this->datatables->select('SALARIO.ID_SALARIO,SALARIO.SALARIO_VIGENTE, SALARIO.VIGENCIA_DESDE,SALARIO.VIGENCIA_HASTA,SALARIO.FECHACREACION,E.NOMBREESTADO');
                    $this->datatables->from('SALARIO');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = SALARIO.IDESTADO', ' left ');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'SALARIO.ID_SALARIO');
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datatablesuperintendencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/manage')) {

                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tasasysalarios/edit')) {
                    $this->load->library('datatables');
                    $this->datatables->select('TASA_SUPERINTENDENCIA.ID_TASA_SUPERINTENDENCIA,
												TASA_SUPERINTENDENCIA.TASA_SUPERINTENDENCIA,
												T.DESCRIPCION,
												TASA_SUPERINTENDENCIA.VIGENCIA_DESDE,
												TASA_SUPERINTENDENCIA.VIGENCIA_HASTA,
												TASA_SUPERINTENDENCIA.FECHACREACION,
												E.NOMBREESTADO');
                    $this->datatables->from('TASA_SUPERINTENDENCIA');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = TASA_SUPERINTENDENCIA.IDESTADO', 'inner');
                    $this->datatables->join('TIPO_TASA T', 'T.COD_TIPO_TASA = TASA_SUPERINTENDENCIA.ID_TIPO_TASA', 'inner');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="' . base_url() . 'index.php/tasasysalarios/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_SUPERINTENDENCIA.ID_TASA_SUPERINTENDENCIA');
                } else {
                    $this->load->library('datatables');
                    $this->datatables->select('TASA_SUPERINTENDENCIA.ID_TASA_SUPERINTENDENCIA,
												TASA_SUPERINTENDENCIA.TASA_SUPERINTENDENCIA,
												T.DESCRIPCION,
												TASA_SUPERINTENDENCIA.VIGENCIA_DESDE,
												TASA_SUPERINTENDENCIA.VIGENCIA_HASTA,
												TASA_SUPERINTENDENCIA.FECHACREACION,
												E.NOMBREESTADO');
                    $this->datatables->from('TASA_SUPERINTENDENCIA');
                    $this->datatables->join('ESTADOS E', 'E.IDESTADO = TASA_SUPERINTENDENCIA.IDESTADO', 'inner');
                    $this->datatables->join('TIPO_TASA T', 'T.COD_TIPO_TASA = TASA_SUPERINTENDENCIA.ID_TIPO_TASA', 'inner');
                    $this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'TASA_SUPERINTENDENCIA.ID_TASA_SUPERINTENDENCIA');
                }
                echo $this->datatables->generate();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/tasasysalarios');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}

/* End of file tasasysalarios.php */
/* Location: ./system/application/controllers/tasasysalarios.php */