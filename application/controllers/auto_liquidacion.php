<?php
class Auto_liquidacion extends MY_Controller {
    public $TIPONOTIFICACIONPERSONAL    = '1';//PERSONAL
    public $TIPONOTIFICACIONCORREO      = '2';//CORREO
    public $TIPONOTIFICACIONDIARIO      = '3';//AVISO PUBLICADO EN DIARIO
    public $TIPONOTIFICACIONPAGINA      = '4';//PAGINA WEB DEL SENA
    public $TIPONOTIFICACIONACTA        = '5';//ACTA
    public $TIPONOTIFICACIONMEDIDA      = '6';//MEDIDA CAUTELAR
    public $TIPONOTIFICACIONADELANTE    = '7';//SEGUIR ADELANTE
    public $TIPONOTIFICACIONAVISO       = '8';//AVISO
    public $TIPONOTIFICACIONEDICTO      = '9';//EDICTO                    

   
    
    public $ESTADONOTIFICACIONGENERADA      = '1';
    public $ESTADONOTIFICACIONPREAPROBADA   = '2';
    public $ESTADONOTIFICACIONAPROBADA      = '3';
    public $ESTADONOTIFICACIONRECHAZADA     = '4';
    public $ESTADONOTIFICACIONRECIBIDA      = '5';
    public $ESTADONOTIFICACIONDEVUELTA      = '6';
    public $ESTADONOTIFICACIONPREGENERADA   = '7';   
    
    public $NOTIFICACION_GENERADA       = '1';
    public $NOTIFICACION_APROBADA       = '2';
    public $NOTIFICACION_NOAPROBADA     = '3';
    public $NOTIFICACION_REVISADA       = '4';
    public $NOTIFICACION_ENVIADO        = '5';
    public $NOTIFICACION_ENTREGADO      = '6';
    public $NOTIFICACION_DEVUELTO       = '7'; 
    
    public $TIPOAUTO_LIQUIDACION        = '3';//AUTO DE LIQUIDACION DE CREDITO
    public $TIPOAUTO_OBJECION           = '24';//AUTO DE LIQUIDACION DE CREDITO
    public $PROCESO                         = '14';//Liquidación de Crédito Coactivo
    
    
    function __construct() {

        parent::__construct();
        
        $this->load->library('form_validation');		
	$this->load->helper(array('form','url','codegen_helper','template_helper','traza_fecha_helper','file'));
	$this->load->model('auto_liquidacion_model','',TRUE);
        $this->load->model('plantillas_model');
        $this->load->library(array('pagination','libupload','datatables','session','form_validation'));
        
        //permisos de usuarios
        define("ABOGADO", "43"); // id de la tabla uduario_gurpos para saber el secretario
        define("SECRETARIO", "41"); // id de la tabla uduario_gurpos para saber el secretario
        define("COORDINADOR", "42"); // id de la tabla uduario_gurpos para saber el secretario
        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);   
        $this->data['permiso'] = $this->auto_liquidacion_model->permiso();        
        define("PERFIL", @$this->data['permiso'][0]['IDGRUPO']);
        define ("REGIONAL",$this->data['permiso'][0]['COD_REGIONAL']);
                           	        
        
        $this->data['style_sheets']= array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );
        $this->data['javascripts']= array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/tinymce/plugins/moxienitsr/plugin.min.js',
            'js/tinymce/tinymce.js',
            'js/jquery.dataTables.columnFilter.js',
            'js/jquery.dataTables.rowReordering.js'
        );
        
        $this->data['style_sheets']= array(
                    'css/chosen.css' => 'screen'
        );
        $this->data['javascripts']= array(
            'js/chosen.jquery.min.js',
            'js/tinymce/tinymce.min.js',
            'js/tinymce/tinymce.jquery.min.js'
        );  
        
       $this->data['style_sheets']= array(
            'css/jquery.dataTables_themeroller.css' => 'screen'
        );

       $this->data['javascripts']= array(
            'js/jquery.dataTables.min.js',
             'js/jquery.dataTables.defaults.js',
             'js/tinymce/tinymce.jquery.min.js'
        );
        
    }	
    
    function addAuto() {  
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/addAuto'))
            {   
                $fiscalizacion = '10';//$this->input->post('cod_fiscalizacion');
                $nit_empresa = '800800800';
                $this->data['tipo'] = 'autoliquidacion';
                $this->data['fiscalizacion'] = $fiscalizacion;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Auto de Liquidación de Crédito</h2>"; 
                $this->data['instancia'] = 'Auto de Liquidación de Crédito';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/autoliquidacion/';
                $this->data['plantilla'] = "auto_liquidacion.txt";
                $this->data['CONSECUTIVO'] = $consec['CONSECUTIVO'] = $fiscalizacion;
                $urlplantilla2 = "uploads/plantillas/" . $this->data['plantilla'];
                $this->data['filas2'] = template_tags($urlplantilla2,$consec);                                                
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);   
                $regional = REGIONAL;                                                
                $cargo = '7';
                $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_add', $this->data);  
            }else {
              $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
              redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }
    }
    
    function addAutoObj() {  
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/addAuto'))
            {   
                $fiscalizacion = $this->input->post('cod_fiscalizacion');
                $nit_empresa = $this->input->post('nit');
                @$this->data['auto_num'] = $this->input->post('clave');
                $this->data['tipo'] = 'objecion';
                $this->data['fiscalizacion'] = $fiscalizacion;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Auto que resuelve Objeción</h2>"; 
                $this->data['instancia'] = 'Auto que resuelve Objeción';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/objecion/';
                $this->data['plantilla'] = "auto_objecion.txt";
                $this->data['CONSECUTIVO'] = $consec['CONSECUTIVO'] = $fiscalizacion;
                $urlplantilla2 = "uploads/plantillas/" . $this->data['plantilla'];
                $this->data['filas2'] = template_tags($urlplantilla2,$consec);                                                
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);   
                $regional = REGIONAL;                                                
                $cargo = '7';
                $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_add', $this->data);  
            }else {
              $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
              redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }
    }
    
    function autos_info() {
        $this->data['post']=$this->input->post();
        $this->data['perfil'] = PERFIL;
        if ($this->ion_auth->logged_in()){                       
            if (PERFIL == ABOGADO || PERFIL == COORDINADOR || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/autos_info')){
                    $this->template->set('title', 'AUTOS');                                        
                    $this->data['registros'] = $this->auto_liquidacion_model->liquidacionesView(ID_USER,PERFIL);                                                                                                               
                    $this->data['message']=$this->session->flashdata('message');
                    $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_index',$this->data);                                                   
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }
                
        } else
	{
            redirect(base_url().'index.php/auth/login');
	}
    }
    
    function codGestion($tipogestion,$estado,$fiscalizacion,$nit,$mensaje){
         $this->datos['idgestioncobro'] = trazar($tipogestion, $estado, $fiscalizacion, $nit, $cambiarGestionActual = 'S',$cod_gestion_anterior=-1, $comentarios = $mensaje);
         $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];          
         return $this->datos['idgestion'];
     }
     
         function documentos() { 
         $ID = $this->input->post('clave');
         $idgestion = $this->input->post('gestion');
         $fiscalizacion = $this->input->post('fisca'); 
         $this->data['fiscalizacion'] = $fiscalizacion; 
         if ($this->ion_auth->logged_in()) {
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || PERFIL == COORDINADOR || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('titulo/documentos')) {                
                @$this->data['documentos'] = $this->auto_liquidacion_model->getDocumentos($ID);                 
                @$this->data['autos'] = $this->auto_liquidacion_model->getAuto($ID);                                 
                $this->load->view('auto_liquidacion/auto_liquidacion_documentos',$this->data); 
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta Ã¡rea.</div>');
                redirect(base_url() . 'index.php/inicio/nits');
            }
        }else {
            redirect(base_url() . 'index.php/auth/login');
        }                          
     }
     
     function correo() {
         if ($this->ion_auth->logged_in()) {
             if (PERFIL == ABOGADO || PERFIL == SECRETARIO || PERFIL == COORDINADOR || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('titulo/documentos')) {                
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');        
                $perfil = PERFIL;     
                $fiscalizacion = $this->input->post('cod_fiscalizacion');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'correo';
                $this->data['fiscalizacion'] = $fiscalizacion;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto de Liquidación por Correo</h2>"; 
                $this->data['instancia'] = 'Notificación por Correo';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/correo/';                
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);   
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');                    
                $regional = REGIONAL;                                                
                $cargo = '7';
                $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_correo', $this->data);  
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/auto_liquidacion/auto_liquidacion_correo');  
             }             
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }
    
    function correoObjec() {
         if ($this->ion_auth->logged_in()) {
             if (PERFIL == ABOGADO || PERFIL == SECRETARIO || PERFIL == COORDINADOR || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('titulo/documentos')) {                
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');        
                $perfil = PERFIL;     
                $fiscalizacion = $this->input->post('cod_fiscalizacion');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'correoObjec';
                $this->data['fiscalizacion'] = $fiscalizacion;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto de Objeción por Correo</h2>"; 
                $this->data['instancia'] = 'Notificación por Correo';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/correoObjec/';                
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);   
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');                    
                $regional = REGIONAL;                                                
                $cargo = '7';
                $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_correo', $this->data);  
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/auto_liquidacion/auto_liquidacion_correo');  
             }             
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }
     
     private function do_upload($cProceso, $cTipo) {
        $this->load->library('upload');        
        $cFile = "./uploads/liquidacion/".$cProceso."/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile,0777,true);
        }
        $cFile = $cFile.$cTipo."/";
        if (!is_dir($cFile)) {
            mkdir($cFile,0777,true);
        }   
        
        $config['upload_path'] = $cFile;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '6048';
        $config['encrypt_name'] = TRUE; 
        
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('filecolilla')) {
            return $error = array('error' => $this->upload->display_errors());
        }else {
            return $data = array('upload_data' => $this->upload->data()); 
        }

         
     }  
     
     private function do_uploadDoc($cProceso, $cTipo1, $cTipo2) {       
        $this->load->library('upload');        
        $cFile = "./uploads/liquidacion/".$cProceso."/pdf/";
        if (!is_dir($cFile)) {
            mkdir($cFile,0777,true);
        }
        
        if (@$_FILES['filecolilla']['name'] != '')
        {           
            $cFile1 = $cFile.$cTipo1."/";
            if (!is_dir($cFile1)) {
                mkdir($cFile1,0777,true);
            }   

            $config['upload_path'] = $cFile1;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;      

            $this->upload->initialize($config);

            if ($this->upload->do_upload('filecolilla'))
            {
               
                $data1 = $this->upload->data();
            }
            else
            {
                echo $this->upload->display_errors();
            }
        }
        
        if (@$_FILES['doccolilla']['name'] != '')
        {
            $cFile2 = $cFile.$cTipo2."/";
            if (!is_dir($cFile2)) {
                mkdir($cFile2,0777,true);
            }
            $config['upload_path'] = $cFile2;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;      

            $this->upload->initialize($config);

            if ($this->upload->do_upload('doccolilla'))
            {
                $data2 = $this->upload->data();                
            }
            else
            {
                echo $this->upload->display_errors();
            }

        }               
        @$dataArray = array ($data1,$data2);
        return $dataArray;
    }//fin do_uploadDoc   
    
    private function do_uploadImg($cProceso, $cTipo1) {
        $this->load->library('upload');        
        $cFile = "./uploads/liquidacion/".$cProceso."/pdf/";
        if (!is_dir($cFile)) {
                    mkdir($cFile,0777,true);
        }
        if (@$_FILES['filecolilla']['name'] != '')
        {           
            $cFile1 = $cFile.$cTipo1."/";
            if (!is_dir($cFile1)) {
                mkdir($cFile1,0777,true);
            }   

            $config['upload_path'] = $cFile1;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '6048';
            $config['encrypt_name'] = TRUE;      
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('filecolilla')) {
                return $error = array('error' => $this->upload->display_errors());
            }else {
                return $data = array('upload_data' => $this->upload->data()); 
            }
            @$dataArray = array ($data);
            return $dataArray;
        }        
    }
    
     
     function editAuto(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');        
        $perfil = PERFIL;     
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['nit'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Notificar Auto de Liquidación</h2>"; 
        $this->data['instancia'] = 'Notificación por Correo';
        $this->data['tipo'] = "autoliquidacion";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$fiscalizacion.'/pdf/autoliquidacion/';      
         if ($this->ion_auth->logged_in()){
             if (PERFIL == ABOGADO || PERFIL == COORDINADOR || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/editAuto')){
                 @$this->data['result'] = $this->auto_liquidacion_model->getAuto($ID);  
                 $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                 $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);   
                 $regional = REGIONAL;
                 if (@$this->data['result']->NOMBRE_DOC_GENERADO != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/autoliquidacion";
                        $cFile .= "/".$this->data['result']->NOMBRE_DOC_GENERADO;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 if (PERFIL == ABOGADO){
                       $cargo1 = 7;
                       $cargo2 = 8;
                       $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo1,$cargo2,'NOMBRES, APELLIDOS');
                    }else if (PERFIL == SECRETARIO){
                       $cargo1 = 8;
                       $cargo2 = 9;
                       $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo1,$cargo2,'NOMBRES, APELLIDOS');
                    }else if (PERFIL == COORDINADOR){
                        $cargo1 = 7;
                        $cargo2 = 8;
                        $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo1,$cargo2,'NOMBRES, APELLIDOS');
                    } 
                 $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_edit', $this->data);  
             }else{
                 redirect(base_url().'index.php/auto_liquidacion/autos_info');  
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
     }
     
     function editAutoObj(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');        
        $perfil = PERFIL;     
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['nit'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Notificar Auto de Objeción</h2>"; 
        $this->data['instancia'] = 'Notificación por Correo';
        $this->data['tipo'] = "objecion";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$fiscalizacion.'/pdf/objecion/';      
         if ($this->ion_auth->logged_in()){
             if (PERFIL == ABOGADO || PERFIL == COORDINADOR || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/editAuto')){
                 @$this->data['result'] = $this->auto_liquidacion_model->getAuto($ID);  
                 $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                 $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);   
                 $regional = REGIONAL;
                 if (@$this->data['result']->NOMBRE_DOC_GENERADO != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/objecion";
                        $cFile .= "/".$this->data['result']->NOMBRE_DOC_GENERADO;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 if (PERFIL == ABOGADO){
                       $cargo1 = 7;
                       $cargo2 = 8;
                       $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo1,$cargo2,'NOMBRES, APELLIDOS');
                    }else if (PERFIL == SECRETARIO){
                       $cargo1 = 8;
                       $cargo2 = 9;
                       $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo1,$cargo2,'NOMBRES, APELLIDOS');
                    }else if (PERFIL == COORDINADOR){
                        $cargo1 = 7;
                        $cargo2 = 8;
                        $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuariosecre('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo1,$cargo2,'NOMBRES, APELLIDOS');
                    } 
                 $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_edit', $this->data);  
             }else{
                 redirect(base_url().'index.php/auto_liquidacion/autos_info');  
             }
         }else{
             redirect(base_url().'index.php/auth/login');
         }
     }
     
     function editCorreo(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');        
        $perfil = PERFIL;     
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Auto de Liquidación de Crédito</h2>"; 
        $this->data['instancia'] = "Notificación de Auto de Liquidación de Crédito por Correo";
        $this->data['tipo'] = "correo";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$fiscalizacion.'/pdf/correo/';      
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'ACTIVO');                    
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');                    
                $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);   
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/correo";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');
                 if (PERFIL == SECRETARIO){
                    $cargo = '8';
                 }elseif (PERFIL == ABOGADO){
                    $cargo = '7';
                 }                         
                 $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                 $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_edit_correo', $this->data);  
            }else{
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }             
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }
    
     function editCorreoObjec(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');        
        $perfil = PERFIL;     
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['titulo'] = "<h2>Notificación de Auto de Objeción por Correo</h2>"; 
        $this->data['instancia'] = "Notificación de Auto de Objeción por Correo";
        $this->data['tipo'] = "correoObjec";
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$fiscalizacion.'/pdf/correoObjec/';      
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'ACTIVO');                    
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONCORREO,'INACTIVO');                    
                $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);   
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/correoObjec";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');
                 if (PERFIL == SECRETARIO){
                    $cargo = '8';
                 }elseif (PERFIL == ABOGADO){
                    $cargo = '7';
                 }                         
                 $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                 $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_edit_correo', $this->data);  
            }else{
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }             
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }
     
    function editPagina(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');        
        $perfil = PERFIL;     
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['tipo'] = 'pagina';        
        $this->data['titulo'] = "<h2>Notificar Auto de Liquidación por Página Web</h2>"; 
        $this->data['instancia'] = 'Notificación por Página Web';
        $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/pagina/';                
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$fiscalizacion.'/pdf/pagina/';      
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'ACTIVO');                    
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');                    
                $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);   
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/pagina";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');
                 if (PERFIL == SECRETARIO){
                    $cargo = '8';
                 }elseif (PERFIL == ABOGADO){
                    $cargo = '7';
                 }                         
                 $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                 $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_edit_pagina', $this->data);  
            }else{
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }             
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     }
    
    function editPaginaObjec(){
        $this->data['post']=$this->input->post();
        $ID =  $this->input->post('clave');
        $nit =  $this->input->post('nit');
        $gestion =  $this->input->post('gestion');        
        $perfil = PERFIL;     
        $fiscalizacion = $this->input->post('cod_fiscalizacion');
        $this->data['fiscalizacion'] = $fiscalizacion;
        $this->data['nitempresa'] = $nit;
        $this->data['perfil'] = $perfil;
        $this->data['iduser'] = ID_USER;
        $this->data['tipo'] = 'paginaObjec';        
        $this->data['titulo'] = "<h2>Notificar Auto de Objeción por Página Web</h2>"; 
        $this->data['instancia'] = 'Notificación por Página Web';
        $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/paginaObjec/';                
        $this->data['rutaArchivo'] = './uploads/liquidacion/'.$fiscalizacion.'/pdf/paginaObjec/';      
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {
                @$this->data['result'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'ACTIVO');                    
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');                    
                $this->data['gestion'] = $this->auto_liquidacion_model->getRespuesta($gestion);
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit);   
                $regional = REGIONAL;
                 if (@$this->data['result']->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/paginaObjec";
                        $cFile .= "/".$this->data['result']->PLANTILLA;
                        $this->data['plantilla'] = read_template($cFile);
                    }
                 $this->data['motivos'] = $this->auto_liquidacion_model->getSelect('MOTIVODEVOLUCION','COD_MOTIVO_DEVOLUCION,MOTIVO_DEVOLUCION','IDESTADO = 1','MOTIVO_DEVOLUCION');
                 if (PERFIL == SECRETARIO){
                    $cargo = '8';
                 }elseif (PERFIL == ABOGADO){
                    $cargo = '7';
                 }                         
                 $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                 $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_edit_pagina', $this->data);  
            }else{
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }             
        }else{
            redirect(base_url().'index.php/auth/login');
        }
     } 
     
    function guardar_auto() {
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {
                $this->data['custom_error'] = '';
		$this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('asignado', 'Asignado a', 'required'); 
                
                if ($this->form_validation->run() == false){                    
                    redirect(base_url() . 'index.php/auto_liquidacion/addAuto');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);                                       
                }else {                    
                    $asignado       = $asignado = explode("-",$this->input->post('asignado')); 
                    $fiscalizacion  = $this->input->post('fiscalizacion');
                    $nit_empresa    = $this->input->post('nitempresa');
                    $tipo           = $this->input->post('tipo');
                    $auto_num       = $this->input->post('auto_num');
                    if ($tipo == 'autoliquidacion'){
                        $tipo_auto = $this->TIPOAUTO_LIQUIDACION;                        
                    }elseif ($tipo == 'objecion'){
                        $tipo_auto = $this->TIPOAUTO_OBJECION;
                    }
                    $estado = '1133';
                    $mensaje = 'Auto Generado y se Asigna a Secretario';
                    $tipogestion = '437';
                    
                    $cRuta = "./uploads/liquidacion/".$fiscalizacion."/".$tipo."/";
                        if (!is_dir($cRuta)) {
                            mkdir($cRuta,0777,true);
                        }                        
                    
                    $sFile = create_template($cRuta, $this->input->post('notificacion'));  
                    
                    $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit_empresa, $mensaje);
                    
                    $data = array(
                                'COD_TIPO_AUTO'         => $tipo_auto,
                                'COD_FISCALIZACION'     => $fiscalizacion,
                                'COD_ESTADOAUTO'        => $this->ESTADONOTIFICACIONGENERADA,
                                'FECHA_CREACION_AUTO'   => date("d/m/Y"),
                                'CREADO_POR'            => ID_USER,
                                'COD_TIPO_PROCESO'      => $this->PROCESO,
                                'ASIGNADO_A'            => $asignado[0],
                                'COMENTARIOS'           => $this->input->post('comentarios'),
                                'NOMBRE_DOC_GENERADO'   => $sFile,
                                'COD_GESTIONCOBRO'      => $gestion                         
                    );
                    $insert = $this->auto_liquidacion_model->guardarAuto('AUTOSJURIDICOS', $data);
                    if ($insert == TRUE){
                         if ($tipo == 'objecion'){
                             $estadoNew = '1132';
                             $mensajeNew = 'Auto Pre-Generado';
                             $tipogestionNew = '436';
                             $gestionNew = $this->codGestion($tipogestionNew, $estadoNew, $fiscalizacion, $nit_empresa, $mensajeNew);
                             $dataNew = array(
                                'COD_GESTIONCOBRO'      => $gestionNew                         
                            );   
                            $insertNew = $this->auto_liquidacion_model->guardarObjecion($dataNew,$auto_num);                                                                                    
                        }
                    $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se ha creado correctamente.</div>');
                    redirect(base_url().'index.php/auto_liquidacion/autos_info');                       
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto no se ha podido crear.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }

                }
                
            }else {
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }

    }
    
    function guardar_notif() {
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {
                $this->data['custom_error'] = '';
		$this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('asignado', 'Asignado a', 'required'); 
                
                if ($this->form_validation->run() == false){                    
                    redirect(base_url() . 'index.php/auto_liquidacion/autos_info');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);                                       
                }else {  
                    $asignado       = $asignado = explode("-",$this->input->post('asignado')); 
                    $fiscalizacion  = $this->input->post('fiscalizacion');
                    $cod_estado     = $this->ESTADONOTIFICACIONGENERADA;
                    $nit_empresa    = $this->input->post('cod_nit');
                    $tipo           = $this->input->post('tipo');
                    if ($tipo == 'correo'|| $tipo == 'correoObjec'){
                        $respuesta = 450;
                        $mensaje = 'Notificación de Auto de Liquidación de Crédito por Correo Generada';
                        $tipogestion = 185;
                        $tipo_notificacion = $this->TIPONOTIFICACIONCORREO;
                    }else if ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                        $respuesta = 453;
                        $tipogestion = 186;
                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Generada';
                        $tipo_notificacion = $this->TIPONOTIFICACIONPAGINA;
                    }
                                        
                    $cRuta = "./uploads/liquidacion/".$fiscalizacion."/".$tipo."/";
                        if (!is_dir($cRuta)) {
                            mkdir($cRuta,0777,true);
                        }                        
                    
                    $sFile = create_template($cRuta, $this->input->post('notificacion'));                      
                    $gestion = $this->codGestion($tipogestion, $respuesta, $fiscalizacion, $nit_empresa, $mensaje);
                        $data = array(                                    
                            'COD_TIPONOTIFICACION' => $tipo_notificacion,                            
                            'FECHA_NOTIFICACION' => date("d/m/Y"),
                            'COD_ESTADO' => $cod_estado,
                            'NOMBRE_DOC_CARGADO' => ' ',
                            'OBSERVACIONES' => $this->input->post('comentarios'),
                            'ESTADO_NOTIFICACION'=>'ACTIVO',
                            'NUM_AUTOGENERADO' => $this->input->post('num_auto'),
                            'PLANTILLA' => $sFile
                        );
                        $dataAuto = array(                                    
                            'COD_GESTIONCOBRO' => $gestion,                            
                            'FECHA_GESTION' => date("d/m/Y"),
                            'ASIGNADO_A' => $asignado[0],                            
                        );
                    $insert = $this->auto_liquidacion_model->guardarNotificacion($data,$dataAuto);
                    if ($insert == TRUE){
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha creado correctamente.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación no se ha podido crear.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }
                }
            }else{
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }
        }else{
            redirect(base_url().'index.php/auth/login');
        }        
    }
    
    function guardar_objecion() {
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
	{      
            if (PERFIL == ABOGADO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_objecion'))
            {                            
                    $fiscalizacion  = $this->input->post('cod_fiscalizacion');
                    $nit_empresa    = $this->input->post('nit');
                    $auto_num    = $this->input->post('clave');
                    $gestion           = $this->input->post('gestion');
                    
                    $estado = '840';
                    $mensaje = 'Objeción Presentada';
                    $tipogestion = '298';                                                            
                    $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit_empresa, $mensaje);                    
                    
                    $data = array(                                
                                'COD_GESTIONCOBRO'      => $gestion                         
                    );
                    $insert = $this->auto_liquidacion_model->guardarObjecion($data,$auto_num);
                    if ($insert == TRUE){
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se ha modificado correctamente.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto no se ha modificado correctamente.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }                
            }else {
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }
        }else {
            redirect(base_url().'index.php/auth/login');
        }

    }
    
    function modificarNotific(){      
        if ($this->ion_auth->logged_in())
        {
            if (PERFIL == ABOGADO || PERFIL == SECRETARIO || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/guardar_auto'))
            {                
                $this->data['custom_error'] = '';
		$this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
		            
                
                if ($this->form_validation->run() == false){                    
                    redirect(base_url() . 'index.php/auto_liquidacion/editCorreo');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);                                       
                }else {  
                    $asignado       = $asignado = explode("-",$this->input->post('asignado')); 
                    $fiscalizacion  = $this->input->post('fiscalizacion');
                    $nit_empresa    = $this->input->post('cod_nit');
                    $iduser         = $this->input->post('iduser');
                    $aprobado       = $this->input->post('aprobado');
                    $revisado       = $this->input->post('revisado');
                    $perfil         = PERFIL;                                                                                                                                                      
                    $ID             = $this->input->post('notifica');
                    $auto           = $this->input->post('num_auto');
                    $tipo           = $this->input->post('tipo');
                    $estado         = $this->input->post('id_estado');
                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                        $tipoNotif = $this->TIPONOTIFICACIONCORREO;
                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                        $tipoNotif = $this->TIPONOTIFICACIONPAGINA;
                    }
                    
                    $plantilla = $this->auto_liquidacion_model->getNotifica($auto,$tipoNotif,'ACTIVO');                                                                                
                     if ($plantilla->PLANTILLA != "") {
                        $cFile  = "uploads/liquidacion/".$fiscalizacion."/".$tipo;
                        $cFile .= "/".$plantilla->PLANTILLA;
                     if (is_file($cFile))
                        $cMandamiento = read_template($cFile);
                     }else {
                        $cMandamiento = "";
                     }                            
                       
                      if (trim($this->input->post('notificacion')) == trim(@$cMandamiento)) {
                            $bGenera = FALSE;
                      }else{
                          $bGenera = TRUE;
                      }
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta = "./uploads/liquidacion/".$fiscalizacion."/".$tipo;
                            $cRuta .= "/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $plantilla->PLANTILLA;
                        }                                                                             
                        //echo $estado;die;
                        switch ($estado){
                                case $this->NOTIFICACION_ENVIADO:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 451;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Generada';                                        
                                    }                                    
                                    $asignado = $this->input->post('iduser');
                                    $estado_notificacion = 'ACTIVO';
                                    break;
                                case $this->NOTIFICACION_APROBADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 1152;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Aprobada';                                        
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 1154;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Realizada';
                                    }                                     
                                    $asignado = $this->input->post('asignado');
                                    $estado_notificacion = 'ACTIVO';
                                break;   
                                case $this->NOTIFICACION_NOAPROBADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 1153;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo No Aprobada';                                        
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 1155;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web No Realizada';                                        
                                    } 
                                    $asignado = $this->input->post('asignado');
                                    $estado_notificacion = 'ACTIVO';
                                break;   
                                case $this->NOTIFICACION_REVISADA:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 450;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Generada';
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 453;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Generada';                                        
                                    }                                     
                                    $asignado = $this->input->post('asignado');
                                    $estado_notificacion = 'ACTIVO';
                                break;  
                                case $this->NOTIFICACION_ENTREGADO:
                                    if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                        $respuesta = 800;
                                        $tipogesti = 185;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Recibida';
                                        $file = $this->do_uploadDoc($fiscalizacion,'colilla',$tipo); 
                                        $documento = $file[1]['file_name'];
                                        $colilla   = $file[0]['file_name'];
                                    }elseif ($tipo == 'pagina' || $tipo == 'paginaObjec'){
                                        $respuesta = 454;
                                        $tipogesti = 186;
                                        $mensaje='Notificación de Auto de Liquidación de Crédito por Aviso y Pagina Web Publicada';
                                        $file = $this->do_uploadImg($fiscalizacion,$tipo); 
                                        $documento = $file['upload_data']['file_name'];
                                        $colilla   = '';                                        
                                    }                                      
                                    $asignado = $this->input->post('iduser');
                                    $estado_notificacion = 'ACTIVO';
                                    break;
                                case $this->NOTIFICACION_DEVUELTO:                                                            
                                    $file = $this->do_uploadDoc($fiscalizacion,'colilla',$tipo); 
                                    $documento = $file[1]['file_name'];
                                    $colilla   = $file[0]['file_name'];
                                    if ($this->input->post('motivo') == 4){
                                        $estado_notificacion = 'INACTIVO';
                                            if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                                $respuesta = 1160;
                                                $tipogesti = 185;
                                                $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Devuelta por Dirección Errada';                                                
                                            } 
                                        $asignado = $this->input->post('iduser');
                                    }else {
                                        $estado_notificacion = 'ACTIVO';
                                            if ($tipo == 'correo' || $tipo == 'correoObjec'){
                                                $respuesta = 452;
                                                $tipogesti = 185;
                                                $mensaje='Notificación de Auto de Liquidación de Crédito por Correo Devuelta';                                                
                                            } 
                                        $asignado = $this->input->post('iduser');
                                    }
                                    break;        
                            }                            
                            
                            if (@$documento == ''){
                                @$documento = ' ';
                            }
                            if (@$colilla == ''){
                                @$$colilla = ' ';
                            }                       
                            
                            $gestion = $this->codGestion($tipogesti, $respuesta, $fiscalizacion, $nit_empresa, $mensaje);
       
                            $data = array(                           
                                'FECHA_MODIFICA_NOTIFICACION' => date("d/m/Y"),
                                'NUM_RADICADO_ONBASE' => $this->input->post('onbase'),
                                'FECHA_ONBASE' => $this->input->post('fechaonbase'),
                                'COD_ESTADO'=> $estado,
                                'DOC_COLILLA' => 'uploads/liquidacion/'.$fiscalizacion.'/pdf/colilla/',
                                'DOC_FIRMADO' => 'uploads/liquidacion/'.$fiscalizacion.'/pdf/'.$tipo.'/',
                                'NOMBRE_DOC_CARGADO' => @$documento,
                                'NOMBRE_COL_CARGADO' => @$colilla,                                                                
                                'COD_MOTIVODEVOLUCION' => $this->input->post('motivo'),
                                'OBSERVACIONES' => $this->input->post('comentarios'),
                                'PLANTILLA' => $cPlantilla,                                                                
                                'ESTADO_NOTIFICACION'=>$estado_notificacion,                                
                            );
                            $dataAuto = array(
                                'COD_GESTIONCOBRO' => $gestion,                            
                                'FECHA_GESTION' => date("d/m/Y"),
                                'ASIGNADO_A' => $asignado,                                 
                            );
                            $insert = $this->auto_liquidacion_model->modificarNotific($data,$dataAuto,$auto,$ID);
                            
                            if ($insert == TRUE){
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación se ha modificado correctamente.</div>');
                                redirect(base_url().'index.php/auto_liquidacion/autos_info');
                            }else {
                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La Notificación no se ha podido modificar.</div>');
                                redirect(base_url().'index.php/auto_liquidacion/autos_info');
                            }

                }
                
            }else {
                redirect(base_url().'index.php/auto_liquidacion/autos_info');  
            }            
        }else{
            redirect(base_url().'index.php/auth/login');
        }
    }
    
    function pagina() {
         if ($this->ion_auth->logged_in()) {
             if (PERFIL == ABOGADO || PERFIL == SECRETARIO || PERFIL == COORDINADOR || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('titulo/documentos')) {                
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');        
                $perfil = PERFIL;     
                $fiscalizacion = $this->input->post('cod_fiscalizacion');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'pagina';
                $this->data['fiscalizacion'] = $fiscalizacion;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto de Liquidación por Página Web</h2>"; 
                $this->data['instancia'] = 'Notificación por Página Web';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/pagina/';                
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);   
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');                    
                $regional = REGIONAL;                                                
                $cargo = '7';
                $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_pagina', $this->data);  
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/auto_liquidacion/auto_liquidacion_correo');  
             }             
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }
    
    function paginaObjec() {
         if ($this->ion_auth->logged_in()) {
             if (PERFIL == ABOGADO || PERFIL == SECRETARIO || PERFIL == COORDINADOR || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('titulo/documentos')) {                
                $ID =  $this->input->post('clave');
                $nit_empresa =  $this->input->post('nit');
                $gestion =  $this->input->post('gestion');        
                $perfil = PERFIL;     
                $fiscalizacion = $this->input->post('cod_fiscalizacion');
                $this->data['num_auto'] = $ID;
                $this->data['tipo'] = 'paginaObjec';
                $this->data['fiscalizacion'] = $fiscalizacion;
                $this->data['nitempresa'] = $nit_empresa;
                $this->data['titulo'] = "<h2>Notificar Auto de Objeción por Página Web</h2>"; 
                $this->data['instancia'] = 'Notificación por Página Web';
                $this->data['rutaTemporal'] = './uploads/liquidacion/temporal/'.$fiscalizacion.'/paginaObjec/';                
                $this->data['informacion'] = $this->auto_liquidacion_model->getEmpresa($nit_empresa);   
                $this->data['inactivo'] = $this->auto_liquidacion_model->getNotifica($ID,$this->TIPONOTIFICACIONPAGINA,'INACTIVO');                    
                $regional = REGIONAL;                                                
                $cargo = '7';
                $this->data['asignado'] = $this->auto_liquidacion_model->lisUsuarios('IDUSUARIO,NOMBREUSUARIO,NOMBRES,APELLIDOS,IDCARGO',$regional,$cargo,'NOMBRES, APELLIDOS');                       
                $this->template->load($this->template_file, 'auto_liquidacion/auto_liquidacion_pagina', $this->data);  
             }else{
                 $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                 redirect(base_url().'index.php/auto_liquidacion/auto_liquidacion_correo');  
             }             
         }else{
             redirect(base_url().'index.php/auth/login');
         }
    }
    
    function pdf(){
        if ($this->ion_auth->logged_in())
        {
                $this->load->library('form_validation');
                $this->load->library("tcpdf/tcpdf");		
                $html = $this->input->post('liquidacion');
                $html=  str_replace("'", "\"", $html);
                //echo $html;
                ob_clean();
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                $pdf->setFontSubsetting(true);
                $pdf->SetFont('dejavusans', '', 8, '', true);
                $pdf->SetTitle('Liquidacion');
                $pdf->AddPage();
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->Output('auto_liquidacion.pdf', 'I');
		//$this->template->load($this->template_file, 'mandamientopago/mandamientopago_pdf', $this->data);
	} else
        {
            redirect(base_url().'index.php/auth/login');
	}
    }//Fin PDF
    
    function tareasAuto(){
        $this->data['post']=$this->input->post();
        if ($this->ion_auth->logged_in())
	{ 
          if (PERFIL == ABOGADO || PERFIL == SECRETARIO || PERFIL == COORDINADOR || $this->ion_auth->is_admin() || $this->ion_auth->in_menu('auto_liquidacion/tareasAuto')){
              
                $this->data['custom_error'] = '';
		$this->form_validation->set_rules('comentarios', 'Comentarios', 'required|trim|xss_clean|max_length[200]');
		$this->form_validation->set_rules('asignado', 'Asignado a', 'required'); 
                
                if ($this->form_validation->run() == false){                    
                    redirect(base_url() . 'index.php/auto_liquidacion/editAuto');
                    $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' . validation_errors() . '</div>' : false);                                       
                }else {                    
                    $asignado       = $asignado = explode("-",$this->input->post('asignado')); 
                    $fiscalizacion  = $this->input->post('cod_fiscalizacion');
                    $nit_empresa    = $this->input->post('cod_nit');
                    $iduser         = $this->input->post('id_user');
                    $aprobado       = $this->input->post('aprobado');
                    $revisado       = $this->input->post('revisado');
                    $tipo           = $this->input->post('tipo');
                    $perfil         = PERFIL;                                                                                          
                    $ID             =  $this->input->post('num_auto');
                    $plantilla = $this->auto_liquidacion_model->getAuto($ID);
                    
                    if ($plantilla->NOMBRE_DOC_GENERADO != "") {                                                        
                            $cFile  = "./uploads/liquidacion/".$fiscalizacion."/".$tipo;
                            $cFile .= "/".$plantilla->NOMBRE_DOC_GENERADO;                           
                            if (is_file($cFile)){
                               $cMandamiento = read_template($cFile);                                
                            }
                    }else {
                        $cMandamiento = "";
                    }
                            
                        if (trim($this->input->post('notificacion')) == trim($cMandamiento)) {
                            $bGenera = FALSE;
                        }else{
                            $bGenera = TRUE;                            
                        }
                            
                        if ($bGenera == TRUE) {
                            unlink($cFile);
                            $cRuta  = "./uploads/liquidacion/".$fiscalizacion."/".$tipo."/";
                            $cPlantilla = create_template($cRuta, $this->input->post('notificacion'));
                        } else {
                            $cPlantilla = $plantilla->NOMBRE_DOC_GENERADO;
                        }                                               
                          
                    if ($revisado == '2' && $aprobado == ''){
                           $estado = '1136';
                           $mensaje = 'Auto se Devuelve al Abogado Asignado con Comentarios';
                           $tipogestion = '439';
                           $estado_auto = $this->ESTADONOTIFICACIONRECHAZADA;
                       }else if ($revisado == '1' && $perfil == '41') {
                           $estado = '1135';
                           $mensaje = 'Auto se Pre-Aprueba y Asigna a  Ejecutor';
                           $tipogestion = '438';
                           $estado_auto = $this->ESTADONOTIFICACIONPREAPROBADA;
                       }else if ($aprobado == '2'){
                            $estado = '1138';
                            $mensaje = 'Abogado Subió el Archivo Firmado';
                            $tipogestion = '440';     
                            $estado_auto = $this->ESTADONOTIFICACIONRECIBIDA;
                       }else if ($aprobado == '1'){
                            $estado = '1137';
                            $mensaje = 'Auto se Aprueba y Asigna a Abogado para Subir Archivo';
                            $tipogestion = '439';          
                            $estado_auto = $this->ESTADONOTIFICACIONAPROBADA;
                       }else if ($aprobado == '3'){
                           $estado = '1134';
                           $mensaje = 'Auto se Devuelve  al Secretario Asignado con Comentarios';
                           $tipogestion = '438';
                           $estado_auto = $this->ESTADONOTIFICACIONDEVUELTA;      
                       }else if ($aprobado == '') {
                           $estado = '1133';
                           $mensaje = 'Auto Generado y se Asigna a Secretario';
                           $tipogestion = '437';
                           $estado_auto = $this->ESTADONOTIFICACIONGENERADA; 
                       }
                     
                     if ($aprobado != ''){
                        $doc_aprobado = $iduser;                        
                    }
                    
                    if ($revisado != ''){
                        $doc_revisado = $iduser;                        
                    }         
           
                    $docFile = $this->do_upload($fiscalizacion,$this->input->post('tipo'));
                    $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit_empresa, $mensaje);                  
                    
                    $data = array(
                                'COD_ESTADOAUTO'        => $estado_auto,
                                'FECHA_GESTION'         => date("d/m/Y"),
                                'ASIGNADO_A'            => $asignado[0],
                                'REVISADO'              => $revisado,
                                'APROBADO'              => $aprobado,
                                'COMENTARIOS'           => $this->input->post('comentarios'),
                                'NOMBRE_DOC_GENERADO'   => $cPlantilla,
                                'COD_GESTIONCOBRO'      => $gestion,            
                                'NOMBRE_DOC_FIRMADO'    => $docFile['upload_data']['file_name'],
                                'FECHA_DOC_FIRMADO'     => date("d/m/Y")
                    );
                    $insert = $this->auto_liquidacion_model->modificarAuto('AUTOSJURIDICOS',$data,$ID);
                    if ($insert == TRUE){
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto se ha modificado correctamente.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }else {
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto no se ha podido modificar.</div>');
                        redirect(base_url().'index.php/auto_liquidacion/autos_info');
                    }
                    
                }
          }else{
              
          }  
        }else{
            
        }
    }
    
    function temp(){        
        $temporal = $this->input->post('temporal');
        $proceso = $this->input->post('fiscalizacion');
        $tipo = $this->input->post('tipo');     
 
        if ($this->ion_auth->logged_in())
        {                                
                $cRuta = "./uploads/liquidacion/temporal/".$proceso."/".$tipo."/";                    
                    if (is_dir($cRuta)) {
                        $handle = opendir($cRuta);
                        while ($file = readdir($handle)) {
                         if (is_file($cRuta.$file)) {
                            unlink($cRuta.$file);
                          }
                        }                        
                        mkdir($cRuta,0777,true);
                        $sFile = create_template($cRuta, $this->input->post('temporal')); 
                    }
                    
                    if (!is_dir($cRuta)) {
                        mkdir($cRuta,0777,true);
                        $sFile = create_template($cRuta, $this->input->post('temporal')); 
                    }                        
                    
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'.ucfirst($tipo).' Creado Correctamente.</div>');
		redirect(base_url().'index.php/auto_liquidacion/autos_info');
        }
   }//Fin temp              

     
     

         
}