<?php

class cronacuerdodepago extends MY_Controller {

    function __construct() {
        parent::__construct();

//        $POST_MAX_SIZE = ini_get('post_max_size');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->data['javascripts'] = array(
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/bootstrap.js'
        );
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js'
        );

        $this->data['style_sheets'] = array('css/validationEngine.jquery.css' => 'screen');
        $this->load->model('modelacuerdodepago_model');
        $this->load->model('Cronacuerdopago_model');
//        $this->data['user'] = $this->ion_auth->user()->row();
//        define("ID_USER", $this->data['user']->IDUSUARIO);
    }

    function index() {
        $this->acuerdo();
    }
    
// --------------------------------------------------------------------------------      
//    Se ejecuta para la cancelacion de acuerdo de pago juridico
// --------------------------------------------------------------------------------   
    
    function incumplimientocuotainicial(){
        
        $incumplimiento = $this->Cronacuerdopago_model->incumplimientocuotainicial();
        $data = array();
        
        foreach($incumplimiento as $inicial){
                $data= array(
                    'NRO_ACUERDOPAGO'=>$inicial['NRO_ACUERDOPAGO']
                );
                $this->Cronacuerdopago_model->actualizaincumplimiento($data);
        }
        
    }
    function pagototal(){
        
        
        $actualizar = $this->Cronacuerdopago_model->acuerdopagototal();
        
        if(!empty($actualizar)){
        foreach($actualizar as $aceptados){
            
            $this->Cronacuerdopago_model->actualizaacuerdospagototal($aceptados['NRO_ACUERDOPAGO']);
        }
        }

    }
    function terminacionmc(){
        
        $cod_siguiente = 442; 
        $cod_respuesta = 1159;
                
         $mc = $this->Cronacuerdopago_model->terminacionmc();
         $this->load->helper('traza_fecha_helper');
         $i = array();
         foreach($mc as $medidasc){
             
             $codgest = trazar($cod_siguiente, $cod_respuesta, $medidasc['COD_FISCALIZACION'], $medidasc['NITEMPRESA'], "S");
             
             $i[] = array( 
                 'COD_FISCALIZACION' => $medidasc['COD_FISCALIZACION'],
                 'COD_GESTIONCOBRO'=>$codgest['COD_GESTION_COBRO'],
                 'COD_RESPUESTAGESTION'=>1159
                     );
         }
         
         $mc = $this->Cronacuerdopago_model->actualizamc($i);
    }


}

?>