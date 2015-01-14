<?php

class Cargarpagosplantillaunica extends MY_Controller {
		
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
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/manage'))
							 {
								//template data
								$this->template->set('title', 'Planilla única');
								$this->data['style_sheets']= array(
														'css/jquery.dataTables_themeroller.css' => 'screen'
												);
								$this->data['javascripts']= array(
														'js/jquery.dataTables.min.js',
														'js/jquery.dataTables.defaults.js'
												);
								$this->data['message']=$this->session->flashdata('message');
								$this->template->load($this->template_file, 'cargarpagosplantillaunica/cargarpagosplantillaunica_list',$this->data); 
							 }else {
								$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
											redirect(base_url().'index.php/inicio');
							 } 

						}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
	
		function add(){        
				if ($this->ion_auth->logged_in())
			    {
				   if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/add'))
				   {   
				   	   $x=0;
				       $this->data['custom_error'] = '';   								
					   $path = "uploads/cargarpagosplantillaunica";
                       if(!is_dir($path)) //create the folder if it's not already exists
                       {
                          mkdir($path,0777,TRUE);
                       }

                       $rand='';
                       $config = array();
                       $config['upload_path'] = './'.$path;
                       $config['allowed_types'] = 'zip';
                       $config['max_size'] = '10000000000';
                       $config['max_width'] = '1024';
                       $config['max_height'] = '768';
                       $config['max_filename'] = '100000000000';
                       $this->load->library('upload', $config);
                       if ($this->upload->do_upload("archivo")) 
                       {

                       	  $file_data= $this->upload->data();
                       	  $path2 = "uploads/cargarpagosplantillaunica/".$file_data['raw_name'].'_'.$rand;
                          if(!is_dir($path2)) //create the folder if it's not already exists
                          {
                             mkdir($path2,0777,TRUE);
                          }
                          $this->load->library('unzip');
                          $this->unzip->allow(array('txt','TXT','zip','ZIP'));
                          $this->unzip->extract($path.'/'.$file_data['file_name'],$path2);
                          $this->load->helper('directory'); 
                          $map = directory_map('./'.$path2, FALSE, TRUE);
                          $this->load->helper('array');
                          $this->load->helper("file"); 
                          $cont=count(array_flatten($map)); 
                          $tipo_informacion=0; 
                          $tipo_encabezado=0;

                          if ($cont>0) 
                          {
                            $y=1;
                            
                          while ($y>0) {
                            
                            $x=0;
                            $map = directory_map('./'.$path2, FALSE, TRUE);
                          	$map2=array_flatten($map);
                          	$cadena=print_dir($map,'');
                            $rutas=explode_string($cadena);
                          foreach ($rutas as $key => $value) {
                              $value=trim($value);
                                 $extencion=substr($value,-3);
                                 if ($extencion=='zip' || $extencion=='ZIP')
                                  {

                                   if (is_file($path2.'/'.$value)){
                                        $this->unzip->extract($path2.'/'.$value,$path2);
                                        unlink($path2.'/'.$value);
                                    }
                                    
                                     $x++;
                                  }
                                
                              }
                              
                              if ($x>0){
                                $y=1;
                              } else {
                                $y=0;
                              } 
                          }
                          	
                          	// $homepage =file_get_contents('http://192.168.77.2/prototiposena/uploads/cargarpagosplantillaunica/plantlla1_/plantlla1/archivo4.txt');
							// echo $homepage.'........';
                            $x=0; 
							$this->load->helper('path');
							$this->load->helper('file');
                          	foreach ($rutas as $key => $value) {
     								/*$cadena = 'http://192.168.77.2/prototiposena/uploads/cargarpagosplantillaunica/plantlla5_/';
     								$cadena2 = trim($value);
     								$cadena3 = $cadena.$cadena2;
                          	        $string = file_get_contents($cadena3);*/
                                    //echo $path2.'/'.trim($value);
                          	        $string = file_get_contents($path2.'/'.trim($value));
                                    if(set_realpath($path2.'/'.$value)){
                                    	// echo $value;
                                    	// echo "-<br>";
                                    	$x++;
                                     $segmento[$x]['tiporegistro']= substr($string, 0,1);
                                      if ($segmento[$x]['tiporegistro']=='0') {
                                      	echo '<br><br><h2>------------->> archivo detalle  <<-----------------</h2><br>';
                                         echo '<br> ------>1 <------------->'; echo  $_POST['tiporegistro'.$x]= $segmento[$x]['tiporegistro']= substr($string, 0,2);
                                          echo '<br> ------>2 <------------->'; echo $_POST['modalidad'.$x]= $segmento[$x]['modalidad']= substr($string, 2,1);
                                         echo '<br> ------>3 <------------->'; echo  $_POST['secuencia'.$x]= $segmento[$x]['secuencia']= substr($string, 3,4);
                                         echo '<br> ------>4 <------------->'; echo  $_POST['nombre_aportante'.$x]= $segmento[$x]['nombre_aportante']= substr($string, 7,200);
                                         echo '<br> ------>5 <------------->'; echo  $_POST['tipo_documento_aportante'.$x]= $segmento[$x]['tipo_documento_aportante']= substr($string, 207,2);
                                         echo '<br> ------>6 <------------->'; echo  $_POST['documento_aportante'.$x]= $segmento[$x]['documento_aportante']= substr($string, 209,16);
                                         echo '<br> ------> 7<------------->'; echo  $_POST['verificacion_aportante'.$x]= $segmento[$x]['verificacion_aportante']= substr($string, 225,1);
                                         echo '<br> ------> 8<------------->'; echo  $_POST['tipo_planilla'.$x]= $segmento[$x]['tipo_planilla']= substr($string, 226,1);
                                         echo '<br> ------> 9<------------->'; echo  $_POST['numero_planilla'.$x]= $segmento[$x]['numero_planilla']= substr($string, 227,10);
                                        echo '<br> ------>10 <------------->'; echo   $_POST['fecha_pago'.$x]= $segmento[$x]['fecha_pago']= substr($string, 237,10);
                                        echo '<br> ------>11 <------------->'; echo   $_POST['forma_de_presentacion'.$x]= $segmento[$x]['forma_de_presentacion']= substr($string, 247,1);
                                         echo '<br> ------>12 <------------->'; echo  $_POST['codigo_sucursal_aportante'.$x]= $segmento[$x]['codigo_sucursal_aportante']= substr($string, 248,10);
                                         echo '<br> ------>13 <------------->'; echo  $_POST['nombre_sucursal_aportante'.$x]= $segmento[$x]['nombre_sucursal_aportante']= substr($string, 258,40);
                                         echo '<br> ------> 14<------------->'; echo  $_POST['codigo_arp'.$x]= $segmento[$x]['codigo_arp']= substr($string, 298,6);
                                         echo '<br> ------> 15<------------->'; echo  $_POST['periodo_pago_sistema'.$x]= $segmento[$x]['periodo_pago_sistema']= substr($string, 304,7);
                                         echo '<br> ------>16 <------------->'; echo  $_POST['periodo_pago_salud'.$x]= $segmento[$x]['periodo_pago_salud']= substr($string, 311,7);
                                         echo '<br> ------>17 <------------->'; echo  $_POST['numero_radicacion'.$x]= $segmento[$x]['numero_radicacion']= substr($string, 318,10);
                                         echo '<br> ------>18 <------------->'; echo  $_POST['fecha_efectivo_pago'.$x]= $segmento[$x]['fecha_efectivo_pago']= substr($string, 328,10);
                                         echo '<br> ------>19 <------------->'; echo  $_POST['numero_empleados'.$x]= $segmento[$x]['numero_empleados']= substr($string, 338,5);
                                        echo '<br> ------> 20<------------->'; echo   $_POST['valor_nomina'.$x]= $segmento[$x]['valor_nomina']= substr($string, 343,12);
                                        echo '<br> ------>21 <------------->'; echo   $_POST['tipo_aportante1'.$x]= $segmento[$x]['tipo_aportante1']= substr($string, 355,1);
                                        echo '<br> ------>22 <------------->'; echo   $_POST['codigo_operador1'.$x]= $segmento[$x]['codigo_operador1']= substr($string, 356,1);
                                          //validaciones
                                          $this->form_validation->set_rules('tiporegistro'.$x, ' Tipo de Registro. del archivo  '.trim($value),  'trim|xss_clean|required'); 
                                          $this->form_validation->set_rules('modalidad'.$x, ' Modalidad de la Planilla. del archivo  '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('secuencia'.$x, ' Secuencia. del archivo  '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('nombre_aportante'.$x, ' Nombre o razón social del aportante. del archivo  '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('tipo_documento_aportante'.$x, ' Tipo documento. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('documento_aportante'.$x, ' Número de Identificación del aportante. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('verificacion_aportante'.$x, ' Dígito de Verificación aportante. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('tipo_planilla'.$x, ' Tipo de planilla. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('numero_planilla'.$x, ' Número de la planilla asociada a esta planilla. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('fecha_pago'.$x, ' Fecha de pago Planilla asociada a esta planilla. (AAAA-MM-DD). del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('forma_de_presentacion'.$x, ' Forma de presentación. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('codigo_sucursal_aportante'.$x, ' Código de la Sucursal del aportante.. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('nombre_sucursal_aportante'.$x, ' Nombre de la Sucursal. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('codigo_arp'.$x, ' Código de la ARP a la cual el aportante se encuentra afiliado. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('periodo_pago_sistema'.$x, ' Período de pago para los sistemas diferentes al de salud. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('periodo_pago_salud'.$x, ' Período de pago para el sistema de salud. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('numero_radicacion'.$x, ' Número de radicación o de la Planilla Integrada de Liquidación de Aportes. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('fecha_efectivo_pago'.$x, ' Fecha de pago (aaaa-mm-dd). del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('numero_empleados'.$x, ' Número total de empleados. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('valor_nomina'.$x, ' Valor total de la nómina. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('tipo_aportante1'.$x, ' Tipo de aportante. del archivo '.trim($value),  'trim|xss_clean|required');
                                          $this->form_validation->set_rules('codigo_operador1'.$x, ' Código del operador de información. del archivo '.trim($value),  'trim|xss_clean|required');
                                           $tipo_encabezado++; 
                                      } else 
                                      {
                                       // echo "<br><br><b>Archivo ".$x.'</b><br><br>'; 
                                      	echo '<br><br><h2>----------->> archivo encabezado <<------------------</h2><br> ';
                                        echo '<br> ------>1 <------------->'; echo $_POST['nombre'.$x]= $segmento[$x]['nombre']= substr($string, 0, 199);
                                        echo '<br> ------> 2<------------->'; echo $_POST['tipo_documento'.$x]=  $segmento[$x]['tipo_documento']= substr($string, 200, 2);
                                        echo '<br> ------> 3<------------->'; echo $_POST['numero_documento'.$x]=  $segmento[$x]['numero_documento']= substr($string, 202, 16);
                                       echo '<br> ------>4 <------------->'; echo  $_POST['verificacion'.$x]=  $segmento[$x]['verificacion']= substr($string, 218, 1);
                                        echo '<br> ------>5 <------------->'; echo $_POST['codigo_sucursal'.$x]=  $segmento[$x]['codigo_sucursal']= substr($string, 219, 10);
                                        echo '<br> ------>6 <------------->'; echo $_POST['nombre_sucursal'.$x]=  $segmento[$x]['nombre_sucursal']= substr($string, 229, 40);
                                       echo '<br> ------> 7<------------->'; echo  $_POST['clase_aportante'.$x]=  $segmento[$x]['clase_aportante']= substr($string, 269,1);
                                        echo '<br> ------>8 <------------->'; echo $_POST['naturaleza_juridica'.$x]=  $segmento[$x]['naturaleza_juridica']= substr($string, 270,1);
                                        echo '<br> ------>9 <------------->'; echo $_POST['tipo_persona'.$x]=  $segmento[$x]['tipo_persona']= substr($string, 271,1);
                                       echo '<br> ------> 10<------------->'; echo  $_POST['forma_presentacion'.$x]=  $segmento[$x]['forma_presentacion']= substr($string, 272,1);
                                       echo '<br> ------> 11<------------->'; echo  $_POST['direccion'.$x]= $segmento[$x]['direccion'][]= substr($string, 273,40);
                                       echo '<br> ------>12 <------------->'; echo  $_POST['codigo_municipio'.$x]=  $segmento[$x]['codigo_municipio']= substr($string, 313,3);
                                       echo '<br> ------>13 <------------->'; echo  $_POST['codigo_departamento'.$x]=  $segmento[$x]['codigo_departamento']= substr($string, 316,2);
                                       echo '<br> ------> 14<------------->'; echo  $_POST['codigo_dane'.$x]=  $segmento[$x]['codigo_dane']= substr($string, 318,4);
                                       echo '<br> ------>15 <------------->'; echo  $_POST['telefono'.$x]=  $segmento[$x]['telefono']= substr($string, 322,10);
                                       echo '<br> ------>16 <------------->'; echo  $_POST['fax'.$x]=  $segmento[$x]['fax']= substr($string, 332,10);
                                       echo '<br> ------>17 <------------->'; echo  $_POST['email'.$x]=  $segmento[$x]['email']= substr($string, 342,60);
                                        echo '<br> ------>18 <------------->'; echo $_POST['documento_representante'.$x]=  $segmento[$x]['documento_representante']= substr($string, 402,16);
                                        echo '<br> ------> 19<------------->'; echo $_POST['verificacion_representante'.$x]=  $segmento[$x]['verificacion_representante']= substr($string, 418,1);
                                        echo '<br> ------>20 <------------->'; echo $_POST['tipo_documento_representante'.$x]=  $segmento[$x]['tipo_documento_representante']= substr($string, 419,2);
                                        echo '<br> ------>21 <------------->'; echo $_POST['apellido1_representante'.$x]=  $segmento[$x]['apellido1_representante']= substr($string, 421,20);
                                       echo '<br> ------> 22<------------->'; echo  $_POST['apellido2_representante'.$x]=  $segmento[$x]['apellido2_representante']= substr($string, 441,30);
                                       echo '<br> ------>23 <------------->'; echo  $_POST['nombre1_representante'.$x]=  $segmento[$x]['vnombre1_representante']= substr($string, 471,20);
                                       echo '<br> ------>24 <------------->'; echo  $_POST['nombre2_representante'.$x]=  $segmento[$x]['nombre2_representante']= substr($string, 491,30);
                                      echo '<br> ------>25 <------------->'; echo   $_POST['fecha_concordato'.$x]=  $segmento[$x]['fecha_concordato']= substr($string, 521,10);
                                      echo '<br> ------>26 <------------->'; echo   $_POST['tipo_accion'.$x]=  $segmento[$x]['tipo_accion']= substr($string, 531,1);
                                      echo '<br> ------> 27<------------->'; echo   $_POST['fecha_fin_comercial'.$x]=  $segmento[$x]['fecha_fin_comercial']= substr($string, 532,10);
                                      echo '<br> ------>28 <------------->'; echo   $_POST['codigo_operador'.$x]=  $segmento[$x]['codigo_operador']= substr($string, 542,2);
                                      echo '<br> ------>29 <------------->'; echo   $_POST['periodo_pago'.$x]=  $segmento[$x]['periodo_pago']= substr($string, 544,7);
                                      echo '<br> ------>30 <------------->'; echo   $_POST['tipo_aportante'.$x]=  $segmento[$x]['tipo_aportante']= substr($string, 551,1);
                                        
                                        $this->form_validation->set_rules('nombre'.$x, ' Nombre o razón social del aportante. del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('tipo_documento'.$x, 'Tipo documento del aportante. del archivo '.trim($value),  'trim|xss_clean|required|alpha');

                                        $this->form_validation->set_rules('numero_documento'.$x, 'Número de Identificación del aportante. del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('verificacion'.$x, 'Dígito de Verificación.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('codigo_sucursal'.$x, 'Código de la Sucursal.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('nombre_sucursal'.$x, 'Nombre de la Sucursal o de la Dependencia.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('clase_aportante'.$x, 'Clase de aportante.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('naturaleza_juridica'.$x, 'Naturaleza Jurídica.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('tipo_persona'.$x, 'Tipo persona.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('forma_presentacion'.$x, 'Forma de presentación.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('direccion'.$x, 'Dirección correspondencia.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('codigo_municipio'.$x, 'Código ciudad o municipio.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('codigo_departamento'.$x, 'Código departamento.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('codigo_dane'.$x, 'Código DANE de la Actividad económica.  del archivo '.trim($value),  'trim|xss_clean|required');

                                        $this->form_validation->set_rules('telefono'.$x, 'Teléfono.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('fax'.$x, 'Fax.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('email'.$x, 'Dirección de correo electrónico (E-mail).  del archivo '.trim($value),  'trim|xss_clean|required|valid_email');
                                        $this->form_validation->set_rules('documento_representante'.$x, 'Número de Identificación del Representante Legal.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('verificacion_representante'.$x, 'Dígito de Verificación Representante legal.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('tipo_documento_representante'.$x, 'Tipo Identificación Representante Legal. del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('apellido1_representante'.$x, 'Primer apellido del Representante Legal.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('apellido2_representante'.$x, 'Segundo apellido del Representante Legal.  del archivo '.trim($value),  'trim|xss_clean');
                                        $this->form_validation->set_rules('nombre1_representante'.$x, 'Primer nombre del Representante Legal. del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('nombre2_representante'.$x, 'Segundo nombre del Representante Legal.  del archivo '.trim($value),  'trim|xss_clean');
                                        $this->form_validation->set_rules('fecha_concordato'.$x, 'Fecha inicio concordato, reestructuración, liquidación o cese de actividades. del archivo '.trim($value),  'trim|xss_clean');
                                        $this->form_validation->set_rules('tipo_accion'.$x, 'Tipo de acción.  del archivo '.trim($value),  'trim|xss_clean');
                                        $this->form_validation->set_rules('fecha_fin_comercial'.$x, 'Fecha en que terminó actividades comerciales.  del archivo '.trim($value),  'trim|xss_clean|');
                                        $this->form_validation->set_rules('codigo_operador'.$x, 'Código del Operador.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('periodo_pago'.$x, 'Período de pago.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $this->form_validation->set_rules('tipo_aportante'.$x, 'Tipo de aportante.  del archivo '.trim($value),  'trim|xss_clean|required');
                                        $tipo_informacion++; 
                                      }
                                     

                                    
                                    	
                                    	  

										if ($this->form_validation->run() == false)
								        {
								           echo $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
											//reset form validation class
                                            $this->form_validation->_field_data = array();
                                            $this->form_validation->_config_rules = array();
                                            $this->form_validation->_error_array = array();
                                            $this->form_validation->_error_messages = array();
                                            $this->form_validation->error_string = '';					
								        } else 
								        {
								          echo  $this->data['custom_error']= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Carga de archivo '.trim($value).' correcta.</div>';                         
                                           
								        }					

                                    } else{
                                    	// echo "error";
                                    }    
                          	}
                            
                            
                          } 
                          else
                          {
                            $this->data['custom_error'] ='<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><b>Error!</b> Se encontraron '.$cont.' archivos de texto. El archivo ZIP debe contener archivos de texto (txt).</div>' ; 
                            
                             delete_files('./'.$path2, true); // delete all files/folders
                             rmdir('./'.$path2);
                          }	
                          unlink('./'.$path.'/'.$file_data['file_name']);
                          //print_r($map);
                         
                       }
                       else
                       {
                          $this->data['custom_error'] = $this->upload->display_errors('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>', '</div>');
                       }   
                       $this->data['cantidad_archivos'] =$x;
					   $this->template->load($this->template_file, 'cargarpagosplantillaunica/cargarpagosplantillaunica_add', $this->data);
				   }else {
					   $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
					   redirect(base_url().'index.php/cargarpagosplantillaunica');
			        }
				}
				else
				{
					redirect(base_url().'index.php/auth/login');
				}
		}	


	function edit(){    
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/edit'))
							 {    
								$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No hay un ID para editar.</div>');
													redirect(base_url().'index.php/cargarpagosplantillaunica');
										}else{
															$this->load->library('form_validation');  
													$this->data['custom_error'] = '';
													$this->form_validation->set_rules('valortasa', 'Valor Tasa', 'required|numeric');  
															$this->form_validation->set_rules('estado_id', 'Estado',  'required|numeric|greater_than[0]');  
															if ($this->form_validation->run() == false)
															{
																	 $this->data['custom_error'] = (validation_errors() ? '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'.validation_errors().'</div>' : false);
																	
															} else
															{                            
																	$data = array(
																					'VALORTASA' => $this->input->post('valortasa'),
																					'IDESTADO' => $this->input->post('estado_id')
																	);
																 
														if ($this->codegen_model->edit('PLANILLAUNICA_DET',$data,'IDTASA',$this->input->post('id')) == TRUE)
														{
															$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pago se ha editado correctamente.</div>');
																			redirect(base_url().'index.php/cargarpagosplantillaunica/');
														}
														else
														{
															$this->data['custom_error'] = '<div class="error"><p>Ha ocurrido un error</p></div>';

														}
													}
															$this->data['result'] = $this->codegen_model->get('PLANILLAUNICA_DET','IDTASA,IDCONCEPTO,VALORTASA,IDESTADO','IDTASA = '.$this->uri->segment(3),1,1,true);
															$this->data['estados']  = $this->codegen_model->getSelect('ESTADOS','IDESTADO,NOMBREESTADO');
															$this->data['conceptos']  = $this->codegen_model->getSelect('CONCEPTO','IDCONCEPTO,NOMBRECONCEPTO');
																	
																	//add style an js files for inputs selects
																	$this->data['style_sheets']= array(
																					'css/chosen.css' => 'screen'
																			);
																	$this->data['javascripts']= array(
																					'js/chosen.jquery.min.js'
																			);

																	$this->template->load($this->template_file, 'cargarpagosplantillaunica/cargarpagosplantillaunica_edit', $this->data); 
											}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cargarpagosplantillaunica');
									 }
								
						}else
								{
								redirect(base_url().'index.php/auth/login');
								}
				
		}
	
		function delete(){
				 if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/delete'))
							 {
										$ID =  $this->uri->segment(3);
										if ($ID==""){
											$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Eliminar mediante edición.</div>');
													redirect(base_url().'index.php/cargarpagosplantillaunica');
										}else{
											 $data = array(
                                    				'IDESTADO' => '2'

				                            	);
				           				if($this->codegen_model->edit('PLANILLAUNICA_DET',$data,'IDTASA',$ID) == TRUE){
												//$this->codegen_model->delete('PLANILLAUNICA_DET','IDTASA',$ID);             
												$this->template->set('title', 'cargarpagosplantillaunica');
												$this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La tasa de pado se eliminó correctamente.'.$ID.'</div>');
												redirect(base_url().'index.php/cargarpagosplantillaunica/');
										}
									}
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cargarpagosplantillaunica');
									 }
					}else
						{
							redirect(base_url().'index.php/auth/login');
						}
		}
 
		 function datatable (){
				if ($this->ion_auth->logged_in())
					 {
								if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/manage'))
							 {
							 
							 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargarpagosplantillaunica/edit'))
							 {
								$this->load->library('datatables');
								$this->datatables->select('PLANILLAUNICA_DET.COD_PLANILLAUNICA,PLANILLAUNICA_DET.NRO_IDENTIFICACION_EMPLEADO,PLANILLAUNICA_DET.SECUENCIA,PLANILLAUNICA_DET.PRIMER_NOMBRE');
								$this->datatables->from('PLANILLAUNICA_DET'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="'.base_url().'index.php/cargarpagosplantillaunica/edit/$1" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'PLANILLAUNICA_DET.COD_TRANSACCION');
							}else{
								$this->load->library('datatables');
								$this->datatables->select('PLANILLAUNICA_DET.COD_PLANILLAUNICA, PLANILLAUNICA_DET.NRO_IDENTIFICACION_EMPLEADO,PLANILLAUNICA_DET.SECUENCIA,PLANILLAUNICA_DET.PRIMER_NOMBRE');
								$this->datatables->from('PLANILLAUNICA_DET'); 
								$this->datatables->add_column('edit', '<div class="btn-toolbar">
																													 <div class="btn-group">
																															<a href="#" class="btn btn-small disabled" title="Editar"><i class="icon-edit"></i></a>
																													 </div>
																											 </div>', 'PLANILLAUNICA_DET.COD_TRANSACCION');
							}
								echo $this->datatables->generate();
								}else {
										$this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
													redirect(base_url().'index.php/cargarpagosplantillaunica');
									 }
						}else
						{
							redirect(base_url().'index.php/auth/login');
						}           
		}
}


/* End of file cargarpagosplantillaunica.php */
/* Location: ./system/application/controllers/cargarpagosplantillaunica.php */