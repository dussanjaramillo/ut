<?php

/**
 * Regionales (class MY_Controller) :)
 *
 * Gestión, parametrización y edición de regionales SENA
 *
 * Permite gestionar todo los relacionado con lo datos de las regionales SENA
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Regionales
 */
class Regionales extends MY_Controller {

  function __construct() {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url'));
    $this->load->model('regionales_model');
    $this->data['javascripts'] = array(
        'js/jquery.dataTables.min.js',
        'js/jquery.validationEngine-es.js',
        'js/jquery.validationEngine.js',
        'js/validateForm.js',
    );
    $this->data['style_sheets'] = array(
        'css/jquery.dataTables_themeroller.css' => 'screen',
        'css/validationEngine.jquery.css' => 'screen'
    );
  }
	
	/**
   * Funcion que muestra el primer formulario para determinar cual es el flujo a seguir en el sistema
   *
   * @return none
   * @param none
   */
  function index() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/index')) {
        //template data
        $this->template->set('title', 'Regionales SENA');
        $this->data['title'] = 'Regionales SENA';
        $this->data['stitle'] = 'Listado de regionales SENA';
        $this->data['message'] = $this->session->flashdata('message');
				$this->data['regionales'] = $this->regionales_model->ObtenerRegionales();
				$this->data['iTotalRecords'] = count($this->regionales_model->ObtenerRegionales(true));
				//print_r($this->regionales_model->ObtenerRegionales());exit;
				$this->template->load($this->template_file, 'regionales/inicio', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que permite agregar una regional
   *
   * @return none
   * @param none
   */
  function agregar() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/agregar')) {
				$this->template->set('title', 'Regionales SENA');
        $this->data['title'] = 'Regionales SENA';
        $this->data['stitle'] = 'Por favor rellene todos los campos para agregar la regional';
				$this->data['departamentos'] = $this->regionales_model->ObtenerDepartamentos();
				$this->template->load($this->template_file, 'regionales/form', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
	/**
   * Funcion que permite editar una regional
   *
   * @return none
   * @param id URI SEGMENT
   */
	function editar() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/editar')) {
				$id = $this->uri->segment(3);
				if(!empty($id)) :
					$this->template->set('title', 'Regionales SENA');
					$this->data['title'] = 'Regionales SENA';
					$this->data['stitle'] = 'Por favor rellene todos los campos que se requieran para actualizar la regional';
					$this->regionales_model->SetId($id);
					$this->data['regional'] = $this->regionales_model->ObtenerRegional();
					if(!empty($this->data['regional'])) :
						$this->data['departamentos'] = $this->regionales_model->ObtenerDepartamentos();
						$this->data['ciudades'] = $this->regionales_model->ObtenerCiudades($this->data['regional']['COD_DEPARTAMENTO']);
						$this->template->load($this->template_file, 'regionales/form', $this->data);
					else :
						$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor seleccione la regional para editar</div>');
        		redirect(base_url() . 'index.php/regionales');
					endif;
				else :
					$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor seleccione la regional para editar</div>');
        	redirect(base_url() . 'index.php/regionales');
				endif;
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
	/**
   * Funcion que permite buscar el id de un usuario
   *
   * @return none
   * @param term GET
   */
	 function usuarios() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/index')) {
				$term = $this->input->get('term');
				if(!empty($term)) :
					return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->regionales_model->Usuarios($term)));
				endif;
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
	/**
   * Funcion que permite buscar las ciudades de un departamento
   *
   * @return none
   * @param depto POST
   */
	 function ciudades() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/index')) {
				$term = $this->input->post('depto');
				if(!empty($term)) :
					return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->regionales_model->ObtenerCiudades($term)));
				endif;
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
	/**
   * Funcion que permite buscar las ciudades de un departamento
   *
   * @return none
   * @param depto POST
   */
	function regional() {
		if ($this->ion_auth->logged_in()) {
		  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/index')) {
					$term = $this->input->post('id');
					if(!empty($term)) :
						return $this->output->set_content_type('appliation/json')->set_output(json_encode($this->regionales_model->VerificarId($term)));
					endif;
		  } else {
			$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			redirect(base_url() . 'index.php/inicio');
		  }
		} else {
		  redirect(base_url() . 'index.php/auth/login');
		}
	}
	/**
   * Funcion que permite guardar los datos de la regional
   *
   * @return none
   * @param POST
   */
	function guardar() {
		if ($this->ion_auth->logged_in()) {
		  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/guardar')) {
					$datos = $this->input->post();
					echo "<pre>";//print_r($datos);//exit("</pre>");
					if(!empty($datos)) :
						//echo "Validando datos<br><br>";
						$this->form_validation->set_rules('codigo', 'Código regional', 'trim|required');
						$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required');
						$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
						$this->form_validation->set_rules('direccion', 'Dirección', 'trim|required');
						$this->form_validation->set_rules('email', 'Email regional', 'trim|required|valid_email');
						$this->form_validation->set_rules('cod_siif', 'Código SIIF', 'trim|required');
						$this->form_validation->set_rules('departamento', 'Departamento', 'trim|required|numeric');
						$this->form_validation->set_rules('ciudad', 'Ciudad/municipio', 'trim|required|numeric');
						$this->form_validation->set_rules('director', 'Director', 'trim|required|numeric');
						$this->form_validation->set_rules('coordinador', 'Coordinador', 'trim|required|numeric');
						$this->form_validation->set_rules('coordinadorr', 'Coordinador de relaciones corporativas', 'trim|required|numeric');
						$this->form_validation->set_rules('secretario', 'Secretario', 'trim|required|numeric');
						if ($this->form_validation->run() == TRUE) :
							$editar = (isset($datos['editar']))?true:false;
							$datos = array(
												"COD_REGIONAL" => mb_strtoupper($datos["codigo"]), "NOMBRE_REGIONAL" => mb_strtoupper($datos["nombre"]),
												"TELEFONO_REGIONAL" => mb_strtoupper($datos["telefono"]), "DIRECCION_REGIONAL" => mb_strtoupper($datos["direccion"]),
												"CELULAR_REGIONAL" => mb_strtoupper($datos["celular"]), "COD_CIUDAD" => mb_strtoupper($datos["ciudad"]),
												"COD_DEPARTAMENTO" => mb_strtoupper($datos["departamento"]), "EMAIL_REGIONAL" => mb_strtoupper($datos["email"]), 
												"CEDULA_DIRECTOR" => mb_strtoupper($datos["director"]), "CEDULA_COORDINADOR" => mb_strtoupper($datos["coordinador"]), 
												"COD_SIIF" => mb_strtoupper($datos["cod_siif"]), "CEDULA_SECRETARIO" => mb_strtoupper($datos["secretario"]), 
												"CEDULA_COORDINADOR_RELACIONES" => mb_strtoupper($datos["coordinadorr"])
											 );
							//print_r($datos);exit("</pre>");
							unset($datos['editar']);
							if($editar == true) :
								if($this->regionales_model->ActualizarRegional($datos) == true) :
									$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se actualizaron los datos correctamente</div>');
								else :
									$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Existen errores por favor verifique los datos.</div>');
								endif;
								redirect(base_url() . 'index.php/regionales');
							else :
								if($this->regionales_model->AgregarRegional($datos) == true) :
									$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Se agrego la regional correctamente</div>');
								else :
									$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Existen errores por favor verifique los datos.</div>');
								endif;
							endif;
						else :
							$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Existen errores por favor verifique los datos.<p>'.validation_errors().'</p></div>');
						endif;
						redirect(base_url() . 'index.php/regionales');
					endif;
		  } else {
			$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			redirect(base_url() . 'index.php/inicio');
		  }
		} else {
		  redirect(base_url() . 'index.php/auth/login');
		}
	}
	/**
	 * Funcion que permite ver el detalle de la regional
	 *
	 * @return none
	 * @param GET
	 */
	function detalle() {
		if ($this->ion_auth->logged_in()) {
		  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('regionales/detalle')) {
			$id = $this->uri->segment(3);
			if(!empty($id)) :
				$this->regionales_model->SetId($id);
				$this->data['regional'] = $this->regionales_model->ObtenerRegional();
				$this->data['departamentos'] = $this->regionales_model->ObtenerDepartamentos();
				$this->data['ciudades'] = $this->regionales_model->ObtenerCiudades($this->data['regional']['COD_DEPARTAMENTO']);
				$this->template->load($this->template_file, 'regionales/detalle', $this->data);
			else :
				$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
				redirect(base_url() . 'index.php/inicio');
			endif;
		  } else {
			$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
			redirect(base_url() . 'index.php/inicio');
		  }
		} else {
		  redirect(base_url() . 'index.php/auth/login');
		}
	}
}

/* End of file aplicaciondepago.php */
/* Location: ./system/application/controllers/aplicaciondepago.php */