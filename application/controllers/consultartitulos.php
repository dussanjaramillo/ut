<?php
class Consultartitulos extends MY_Controller {
    
        function __construct() {
        parent::__construct();
		$this->load->library('form_validation');		
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('codegen_model','',TRUE);

	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
        if ($this->ion_auth->logged_in())
           {
                if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultartitulos/manage'))
               {
                //template data
                $this->template->set('title', 'Cobro Persuasivo');
                $this->data['style_sheets']= array(
                            'css/jquery.dataTables_themeroller.css' => 'screen',
                             'css/validationEngine.jquery.css' => 'screen'
                        );
                $this->data['javascripts']= array(
                            'js/jquery.dataTables.min.js',
                            'js/jquery.dataTables.defaults.js',
                            'js/jquery.validationEngine-es.js',
                            'js/jquery.validationEngine.js',
                           // 'js/validateForm.js',
                            'js/tinymce/jquery.tinymce.min.js	',
                            'js/tinymce/plugins/moxiemanager/plugin.min.js'
                        );
                $this->load->library('datatables'); 
                $this->load->model('cobro_persuasivo_model'); 
                $this->data['message']=$this->session->flashdata('message');
              // $this->data['array_data'] = $this->cobro_persuasivo_model->consulta_resolucion();
                
                $this->template->load($this->template_file, 'consultartitulos/consultartitulos_list',$this->data); 
               }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                      redirect(base_url().'index.php/inicio');
               } 

            }else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
    
    
    
    function getData()
    {
          
        $this->load->library('datatables');
        $this->load->model('cobro_persuasivo_model');
        $data['registros'] = $this->cobro_persuasivo_model->consulta_resolucion($this->input->post('iDisplayStart'),$this->input->post('sSearch'));  
        define('AJAX_REQUEST', 1);//truco para que en nginx no muestre el debug  
        $TOTAL = $this->cobro_persuasivo_model->totalData();  
        
        
        echo json_encode(array('aaData'=>$data['registros'],  
            'iTotalRecords'=>$TOTAL,  
            'iTotalDisplayRecords'=>$TOTAL,  
            'sEcho'=>$this->input->post('sEcho'))); 
        
        

         }
			
	
    
    
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

