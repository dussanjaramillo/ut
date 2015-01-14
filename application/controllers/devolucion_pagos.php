<?php

/**
 * Devolucion_pagos (class MY_Controller) :)
 *
 * Gestión de pagos a devolver
 *
 * Permite seleccionar los pagos registrados en el sistema para su respectiva devolución, ya sea de forma parcial o total.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Devolucion
 */
class Devolucion_pagos extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->load->model('devolucion_pagos_model');
        $this->data['javascripts'] = array(
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
        );
        $this->data['style_sheets'] = array(
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
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('devolucion_pagos/manage') || $this->input->post('nit')) {
                //template data
                $this->template->set('title', 'Devolución de Pagos');
                $this->data['title'] = 'Devolución de Pagos';
                $this->data['stitle'] = 'Por favor seleccione el/los pago(s) que desea devolver';
                $this->data['message'] = $this->session->flashdata('message');
                $nit = $this->input->post('nit');
                $cod = $this->input->post('cod_devolucion');
								$con = $this->input->post('concepto');
                if (empty($nit) and empty($cod)) :
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No existen datos para acceder a esta área.</div>');
                    redirect(base_url() . 'index.php/inicio');
                else :
                    $this->devolucion_pagos_model->SetNit($nit);
                    $this->devolucion_pagos_model->SetReferencia($cod);
										$this->devolucion_pagos_model->SetConcepto($con);
                    $this->data['empresa'] = $this->devolucion_pagos_model->GetEmpresa();
										//echo "<pre>";print_r($this->data['empresa']);exit("</pre>");
										if(!empty($this->data['empresa'])) :
											$this->data['pagos'] = $this->devolucion_pagos_model->GetPagos();
											//echo "<pre>";print_r($this->data['pagos']);exit("</pre>");
											$this->data['cod'] = $cod;
											if ($this->data['pagos'] == false) :
													$this->data['message'] = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button>No existen pagos para realizar devoluciones.</div>';
											endif;
											$this->template->load($this->template_file, 'devolucion/lista_pagos', $this->data);
										else :
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                			redirect(base_url() . 'index.php/inicio');
										endif;
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
     * Funcion que recibe los valores datos de los pagos de donde se realizaran devoluciones
     *
     * @return none
     * @param none
     */
    function pagos() {
        if ($this->ion_auth->logged_in()) {
            $datos = $this->input->post();
            //echo "<pre>";print_r($datos);exit("</pre>");
            $data = NULL;
            foreach ($datos['ref'] as $ref) :
							$ref['devuelto'] = str_replace(".", "", $ref['devuelto']);
							if (isset($ref['devolver']) and !empty($ref['devuelto']) and $ref['devuelto'] <= $ref['pagado'] and $ref['devuelto'] <= $ref['saldo']) :
								$data[] = array(
											"COD_DEVOLUCION" => $datos['cod_devolucion'],
											"COD_PAGOSRECIBIDOS" => $ref['devolver'],
											"VALOR_DEVUELTO" => $ref['devuelto'],
											"ESTADO" => 0
									);
							endif;
            endforeach;
						//echo "<pre>";print_r($data);exit("</pre>");
            $this->data['pagos'] = false;
            $this->template->set('title', 'Devolución de Pagos');
            $this->data['title'] = 'Devolución de Pagos';
            $this->data['stitle'] = 'Por favor seleccione el/los pago(s) que desea devolver';
            if (!empty($data)) :
                //print_r($data);exit("</pre>");
                $res = $this->devolucion_pagos_model->GuardarPagos($data, $datos['cod_devolucion']);
                if ($res != false) :
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Los pagos seleccionados se aplicaron para devoluciones.</div>');
                else :
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No se pudo aplicar pagos para devoluciones.</div>');
                endif;
            else :
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No existen pagos para aplicar devoluciones, por favor verifique que relleno todos los campos.</div>');
            endif;
            redirect(base_url() . 'index.php/devolucion/Menu_GestionDevoluciones');
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function confirmar() {
        if ($this->ion_auth->logged_in()) {
            //template data
            $this->template->set('title', 'Devolución de Pagos');
            $this->data['title'] = 'Devolución de Pagos';
            $this->data['stitle'] = 'COnfirmación de pagos devueltos';
            $this->data['message'] = $this->session->flashdata('message');
            $this->template->load($this->template_file, 'devolucion/lista_pagos', $this->data);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

}

/* End of file aplicaciondepago.php */
/* Location: ./system/application/controllers/aplicaciondepago.php */