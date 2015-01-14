<?php //ruta prototiposena: application/controllers/tiempos.php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Correo extends MY_Controller {
		
        function __construct(){
            parent::__construct();
            $this->load->library('form_validation');
            $this->load->helper(array('form', 'url', 'codegen_helper'));
            $this->load->model('codegen_model', '', TRUE);
            $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            );
        }
        
        
        function parametros(){
          if ($this->ion_auth->logged_in()){  
            $this->load->model('correo_model');
            if($this->input->post('m')=='parametrosCorreo'){
                $correo = $this->input->post('correo'); 
                $smtp = $this->input->post('smtp');
                $puerto = $this->input->post('puerto');
                $usuario = $this->input->post('usuario');
                $contrasena = $this->input->post('contrasena');
                
                $this->correo_model->actualizarCorreo($correo,$smtp,$puerto,$contrasena);
            }

            $proceso = $this->input->post('proceso');
            if(empty($proceso)){
                $proceso = $this->input->get('proceso');
            }

            $actividad = $this->input->post('actividad');
            if(empty($actividad)){
                $actividad = $this->input->get('actividad');
            }
            
            $id = $this->input->post('id');
            if(empty($id)){
                $id = $this->input->get('id');
            }
            
            $matriz  = $this->correo_model->traeParametrosCorreo();
            
            $this->data['proceso'] = $proceso;
            $this->data['actividad'] = $actividad;
            $this->data['matriz'] = $matriz;
            $this->data['cod'] = $id;
            
            $this->template->load($this->template_file, "correo/parametros", $this->data);
            //$this->load->view("correo/parametros", $this->data);
            //$this->template->load("correo/parametros", $this->data);
            //$this->load->view("correo/parametros", $this->data);
          }else{
            redirect(base_url().'index.php/auth/login');
          }  
        }
        
        function index(){
            $this->parametros();
        }
        
        /**
         * Método lanzar correos.
         * 
         * Método que invocará el CRON para revisar qué correos de cada dia se
         *  deben enviar, faltando 2 días para la fecha de vencimiento
         * 
         */
        function lanzarcorreos(){
            $this->load->helper('traza_fecha');
            //print_r($codigo);
            $fecha = $this->input->get("fecha");
            if(empty($fecha)){
                $fecha = mktime("0","0","0",date("m"),date("d")+2,date("Y"));
            }else{
                $fecha = mktime("0","0","0",date("m",strtotime($fecha)),date("d",strtotime($fecha))+2,date("Y",strtotime($fecha)));
            }
            
            $fecha = date("Y-m-d",$fecha);
            $this->load->model('flujo_model');
            $enviar = $this->flujo_model->recordatoriosFecha($fecha);
            if($enviar!=0){
                foreach($enviar as $row){
                    $nombre   = $row['NOMBRES'];//$row['FECHA_CREACION'],$row['FECHA_VENCIMIENTO']
                    $apellido = $row['APELLIDOS'];
                    $creacion = $row['FECHA_CREACION'];
                    $vencimiento = $row['FECHA_VENCIMIENTO'];
                    $mensaje = $row['TEXTO'];
                    if(!empty($nombre) || !empty($apellido)){
                        $nombres = $apellido." ".$nombre;
                        $mensaje = $nombres.":<br> ".$mensaje;
                    }
                    if(!empty($creacion)){
                        $mensaje .= "<br>Fecha de creaci&oacute;n:".$creacion;
                    }
                    if(!empty($vencimiento)){
                        $mensaje .= "<br>Fecha de vencimiento:".$vencimiento;
                    }   //$this->enviarcorreoRecordatorio($row['CORREO_USUARIO'],$row['TEXTO'],"",$row['NOMBRES'],$row['APELLIDOS'],$row['FECHA_CREACION'],$row['FECHA_VENCIMIENTO']);//enviarcorreosena($row['CORREO_USUARIO'],$mensaje,'Recordatorio actividad SENA','pcfr20@yopmail.com','C:\Users\felipe.puerto\Pictures\reloj.bmp');
                    enviarcorreosena($row['CORREO_USUARIO'],$mensaje,'Recordatorio gestión SENA','akaratchi+sena@gmail.com');
                    //enviarcorreosena($correousuario,        $mensaje,     $asunto="",$copia="",$adjunto="",$copiaoculta="")
                }
            }
            //print_r($this->data['datos']);
            //$this->template->load($this->template_file, "bloqueo/mostrarrecordatorio", $this->data);
        }
        
}

?>