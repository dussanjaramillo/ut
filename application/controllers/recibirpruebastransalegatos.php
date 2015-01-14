<?php
// Responsable: Leonardo Molina
class Recibirpruebastransalegatos extends MY_Controller {
    
    function __construct() {
        parent::__construct();
            $this->load->library('form_validation');		
            $this->load->helper(array('form','url','codegen_helper','date'));
            $this->load->model('codegen_model','',TRUE);
            $this->load->library('datatables','libupload','form_validation'); 
            $this->load->model('recibirpruebastransalegatos_model');
            $this->data['style_sheets']= array('css/jquery.dataTables_themeroller.css' => 'screen');
            $this->data['javascripts']= array('js/jquery.dataTables.min.js','js/jquery.dataTables.defaults.js','js/tinymce/tinymce.jquery.min.js');
	}	
	
	function index(){
		$this->manage();
	}
        
        function tales(){
            $this->data['reg']  = $this->recibirpruebastransalegatos_model->getDatax(0,'');
            var_dump($this->data['reg']);
        }

	function manage(){
        if ($this->ion_auth->is_admin())
           {
            //template data
            $this->template->set('title', 'Autos Pruebas');
            $this->data['message']=$this->session->flashdata('message');
            $this->template->load($this->template_file, 'recibirpruebastransalegatos/recibirpruebastransalegatos_list',$this->data); 
           } 
           else
            {
              redirect(base_url().'index.php/auth/login');
            }
    }
    
    function respAuto(){
        //redirect(URL('admin','example'),client_side=True)
        // redireccionar y cargar la pagina completa
        $this->data['codauto']    = $this->uri->segment(3);
        $this->data['nit']    = $this->uri->segment(4);
        $this->data['dia']      = date('d');
        $this->data['mes']      = date('m');
        $this->data['ano']      = date('Y');
        
        $this->load->view('recibirpruebastransalegatos/recibirpruebastransalegatos_resp', $this->data);
    }
	
    function getData(){
        $data['registros'] = $this->recibirpruebastransalegatos_model->getDatax($this->input->post('iDisplayStart'),$this->input->post('sSearch'));  
        define('AJAX_REQUEST', 1);//truco para que en nginx no muestre el debug  
        $TOTAL = $this->recibirpruebastransalegatos_model->totalData($this->input->post('sSearch'));  
        echo json_encode(array('aaData'=>$data['registros'],  
            'iTotalRecords'=>$TOTAL,  
            'iTotalDisplayRecords'=>$TOTAL,
            'sEcho'=>$this->input->post('sEcho'))); 
        }
         
    
    ////// guardar form respuesta autos
    function cargaRespuesta() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('recibirpruebastransalegatos/index')) {
                
                if($this->input->post('exig')){
                    for($i = 0; $i < count($_FILES); $i++)  {
                        if (empty($_FILES['archivo'.$i]['name'])){
                           $this->form_validation->set_rules('archivo'.$i, 'Documento', 'required');
                        }
                    }
                }
                
                if ($this->form_validation->run() == false){
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
                    $this->data['registros']    = $this->multasministerio_model->getForm($this->input->post('nit'));
                    $this->data['grupos']       = $this->multasministerio_model->getAbogadosRC();
                    $this->template->load($this->template_file, 'multasministerio/multasministerio_add', $this->data);
                    
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: '.validation_errors().'</div>');
                    redirect(base_url().'index.php/ejecutoriaactoadmin');
                    
                } else{ 
                
                for($i = 0; $i < count($_FILES); $i++)  {   
                        //no le hacemos caso a los bacios
                        if(!empty($_FILES['archivo'.$i]['name'])){
                        $respuesta[] = $this->libupload->doUpload($i,$_FILES['archivo'.$i],'multas', 'pdf|doc',9999, 9999, 0);   
                        }
                    }
                    //$this->template->load($this->template_file, 'recibirpruebastransalegatos/recibirpruebastransalegatos_list_resp',$this->data);
                    
                    //acceso a la base de dtaos
                    $query = $this->ejecutoriaactoadmin_model->addEjecutoria($data);
                if ($query == TRUE){

                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La resolución se ha ejecutoriado con éxito.</div>');
                    redirect(base_url().'index.php/ejecutoriaactoadmin');
                }
                else{
                    $error = '<div class="form_error"><p>An Error Occured.</p></div>';
                    $this->session->set_flashdata('custom_error', $error);
                    redirect(base_url().'index.php/ejecutoriaactoadmin');
                }
                    
            }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
        
    private function emailNotification(){
        $config = Array( 
        'protocol' => 'smtp', 
       'smtp_host' => 'ssl://smtp.gmail.com', 
        'smtp_port' => 465, 
       'smtp_user' => 'murcia.fredy@gmail.com', 
       'smtp_pass' => 'famurcia2004' ); 
      
       $this->load->library('email', $config); 
       
       $this->email->set_newline("\r\n");
       $this->email->from('murcia.fredy@gmail.com', '');
       $this->email->to($this->input->post('para'));
       $this->email->cc($this->input->post('cc')); 
       $this->email->bcc($this->input->post('cco')); 
       $this->email->subject($this->input->post('asunto')); 
       $this->email->message($this->input->post('descripcion'));
       if (!$this->email->send()) {
         show_error($this->email->print_debugger()); }
       else {
       echo 'Your e-mail has been sent!';
       }
    }
}

/* End of file categorias.php */
/* Location: ./system/application/controllers/categorias.php */