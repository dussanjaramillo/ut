<?php

class Consultarinfoempresas extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('asigna_model');
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarinfoempresas/manage')) {
                //template data
                $this->template->set('title', 'consultarinfoempresas');
                $this->data['javascripts'] = array(
                    'js/jquery.dataTables.min.js',
                    'js/jquery.validationEngine-es.js',
                    'js/jquery.validationEngine.js',
                    'js/validateForm.js'
                );
                $this->data['style_sheets'] = array(
                    'css/jquery.dataTables_themeroller.css' => 'screen',
                    'css/validationEngine.jquery.css' => 'screen'
                );
                $this->db->select('MUNICIPIO.CODMUNICIPIO, MUNICIPIO.COD_DEPARTAMENTO, MUNICIPIO.NOMBREMUNICIPIO, DEPARTAMENTO.NOM_DEPARTAMENTO');
                $this->db->join("DEPARTAMENTO", "DEPARTAMENTO.COD_DEPARTAMENTO=MUNICIPIO.COD_DEPARTAMENTO");
                $this->db->order_by("NOMBREMUNICIPIO", "asc");
                $this->data['ciudad'] = $this->db->get('MUNICIPIO')->result();

                $this->data['regional'] = $this->codegen_model->getSelect('REGIONAL', 'COD_REGIONAL, NOMBRE_REGIONAL', "", "ORDER BY NOMBRE_REGIONAL ASC");
                $this->data['nuevas'] = $this->asigna_model->empresasNuevas();

                $this->data['message'] = $this->session->flashdata('message');
                $this->template->load($this->template_file, 'consultarinfoempresas/consultarinfoempresas_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function buscar() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarinfoempresas/manage')) {
                $this->db->select('EMPRESA.CODEMPRESA, UPPER (EMPRESA.RAZON_SOCIAL) AS RAZON_SOCIAL, EMPRESA.TELEFONO_FIJO, ' .
                        'UPPER (EMPRESA.DIRECCION) AS DIRECCION, M.NOMBREMUNICIPIO, R.NOMBRE_REGIONAL, T.NOM_TIPO_EMP, ' .
                        'EMPRESA.CIIU, EMPRESA.NUM_EMPLEADOS, EMPRESA.COD_REGIONAL');
                $nit = $this->input->post('nit');
                $ciudad = $this->input->post('ciudad');
								ini_set("MEMORY_LIMIT", "500MB");
								//ini_set("MAX_EXECUTION_TIME", "300");
                if (!empty($ciudad)) :
                    $ciudad = explode("::", $ciudad);
                    $depto = $ciudad[1];
                    $ciudad = $ciudad[0];
                endif;
                $regional = $this->input->post('regional');

                if (empty($nit) and empty($ciudad) and empty($regional)) :
                    $datos['aaData'] = "";
                else :
                    if (!empty($nit)) :
                        $this->db->where('CODEMPRESA', $nit);
                    endif;
                    if (!empty($regional)) :
                        $this->db->where('R.COD_REGIONAL', $regional);
                    endif;
                    $this->db->join('REGIONAL R', 'R.COD_REGIONAL=EMPRESA.COD_REGIONAL', 'inner');
										if (!empty($ciudad)) :
											$cadena = " AND M.CODMUNICIPIO = '".$ciudad."' AND M.COD_DEPARTAMENTO = '".$depto."'";
										else :
											$cadena = "";
                    endif;
                    $this->db->join('MUNICIPIO M', 'M.CODMUNICIPIO=EMPRESA.COD_MUNICIPIO AND M.COD_DEPARTAMENTO=EMPRESA.COD_DEPARTAMENTO'.$cadena, 'inner');
                    $this->db->join('TIPOEMPRESA T', 'T.CODTIPOEMPRESA=EMPRESA.COD_TIPOEMPRESA', 'left');
                    $datos['aaData'] = $this->db->get('EMPRESA');//echo $this->db->last_query();exit;
                    $datos['aaData'] = $datos['aaData']->result_array;
                endif;
                if (!empty($datos['aaData'])) :
                    $datos['ok'] = true;
                    $tmp = array();
                    foreach ($datos['aaData'] as $dato) :
                        $data = '<div class="btn-toolbar">
											<div class="btn-group">
												<a href="' . base_url() . 'index.php/crearempresa/edit/' . $dato['CODEMPRESA'] . '" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
											</div>
										 </div>';
                        if ($this->ion_auth->is_admin() ||
                                ($this->ion_auth->in_menu('crearempresa/edit') and $this->session->userdata('regional') == $dato['COD_REGIONAL'])
                        ) :
                            $tmp[] = array($dato['CODEMPRESA'], mb_strtoupper($dato['RAZON_SOCIAL']), $dato['TELEFONO_FIJO'], $dato['DIRECCION'], $dato['NOMBREMUNICIPIO'], $dato['NOMBRE_REGIONAL'], $dato['NOM_TIPO_EMP'], $dato['CIIU'], $dato['NUM_EMPLEADOS'], $data);
                        else :
                            $tmp[] = array($dato['CODEMPRESA'], mb_strtoupper($dato['RAZON_SOCIAL']), $dato['TELEFONO_FIJO'], $dato['DIRECCION'], $dato['NOMBREMUNICIPIO'], $dato['NOMBRE_REGIONAL'], $dato['NOM_TIPO_EMP'], $dato['CIIU'], $dato['NUM_EMPLEADOS'], "");
                        endif;
                    endforeach;
                    $datos['aaData'] = $tmp;
                    $datos['iTotalRecords'] = count($tmp);
                    $datos['iTotalDisplayRecords'] = count($tmp);
                    $datos['sEcho'] = $this->input->post('sEcho');
                else :
                    $datos['ok'] = false;
                endif;
                return $this->output->set_content_type('appliation/json')->set_output(json_encode($datos));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/consultarinfoempresas');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion que trae los nits del campo autocompletar
     *
     * @return json con los resultados
     * @param string $idConcepto
     */
    function traernits() {
        if ($this->ion_auth->logged_in()) {
            //if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/traernits')) {
            $nit = $this->input->get('term');
            if (empty($nit)) {
                redirect(base_url() . 'index.php/consultarpagos');
            } else {
                $this->load->model('consultarpagos_model', '', TRUE);
                $this->consultarpagos_model->set_nit($nit);
                return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultarpagos_model->buscarnits()));
            }
            /* } else {
              $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
              redirect(base_url() . 'index.php/inicio');
              } */
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}
