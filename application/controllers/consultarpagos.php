<?php
/**
 * Consultarpagos (class MY_Controller) :)
 *
 * Consultar pagos
 *
 * Permite consultar pagos realizados.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Consultarpagos
 */
class Consultarpagos extends MY_Controller {

  function __construct() {

    parent::__construct();
    $this->load->library('form_validation');
    $this->load->helper(array('form', 'url', 'codegen_helper'));
    $this->load->model('codegen_model', '', TRUE);
    $this->load->model('consultarpagos_model');
    $this->data['javascripts'] = array(
        'js/jquery.dataTables.min.js',
        'js/jquery.dataTables.defaults.js',
        'js/jquery.validationEngine-es.js',
        'js/jquery.validationEngine.js',
        'js/validateForm.js'
    );
    $this->data['style_sheets'] = array(
        'css/jquery.dataTables_themeroller.css' => 'screen',
        'css/validationEngine.jquery.css' => 'screen'
    );
  }

  function index() {
    $this->manage();
  }
  /**
   * Funcion que muestra el primer formulario para determinar los filtros de busqueda de los pagos
   *
   * @return none
   * @param none
   */
  function manage() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/manage')) {
        //template data
        $this->template->set('title', 'Consultar Pagos');
        $this->data['title'] = 'CONSULTAR PAGOS';
        $this->data['stitle'] = '';
        $this->data['regionales'] = $this->consultarpagos_model->get_regionales();
        $this->data['conceptos'] = $this->consultarpagos_model->ObtenerConceptos();
        $this->data['message'] = $this->session->flashdata('message');
        $this->template->load($this->template_file, 'consultarpagos/consultarpagos', $this->data);
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que busca por nit o por razón social a una empresa pasada en el formulario 2 por post
   *
   * @return json con los resultados de busqueda
   * @param string $identificación, $razon
   */
  function buscar() {
    error_reporting(E_ALL);
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/buscar')) {
        $nit = $this->input->post("nit");
        $regional = $this->input->post('regional');
        $concepto = $this->input->post('concepto');
        $concepto2 = $this->input->post('concepto2');
        $periodo_inicio = $this->data['periodo_inicio'] = $this->input->post('periodo_inicio');
        $periodo_fin = $this->data['periodo_fin'] = $this->input->post('periodo_fin');
        $resolucion = $this->input->post('resolucion');
        if (empty($nit) || empty($periodo_inicio) || empty($periodo_fin)) {
          redirect(base_url() . 'index.php/consultarpagos');
        } else {
          if(!empty($nit)) $this->consultarpagos_model->set_nit($nit);
          if(!empty($regional)) $this->consultarpagos_model->set_regional($regional);
          if(!empty($concepto) and $concepto != "TODOS") $this->consultarpagos_model->set_concepto($concepto);
          if(!empty($concepto2) and $concepto2 != "TODOS") $this->consultarpagos_model->set_subconcepto($concepto2);
          $this->consultarpagos_model->set_periodo_inicio($periodo_inicio);
          $this->consultarpagos_model->set_periodo_fin($periodo_fin);
					
					$this->data['empresa'] = $this->consultarpagos_model->get_empresa();
          $this->template->set('title', 'Consultar Pagos');
          $this->data['title'] = 'CONSULTAR PAGOS';
					
					if(!empty($this->data['empresa'])) :
          	$this->data['message'] = $this->session->flashdata('message');
						$tmp = explode("/", $periodo_inicio);
						$this->data['year']['inicio'] = $tmp[2];
						$this->data['diainicio'] = $tmp[0];
						$tmp = explode("/", $periodo_fin);
						$this->data['year']['fin'] = $tmp[2];
						$this->data['diafin'] = $tmp[0];
						
						$tmp = explode("/", $periodo_inicio);
						$tmp2 = explode("/", $periodo_fin);
						for($x=$this->data['year']['inicio']; $x<=$this->data['year']['fin']; ++$x) :
							if($x == $this->data['year']['inicio'] and $x < $this->data['year']['fin']) :
								$this->data['mes'][$x]['mesinicio'] = $tmp[1];
								$this->data['mes'][$x]['mesfin'] = 12;
							elseif($x < $this->data['year']['fin']) :
								$this->data['mes'][$x]['mesinicio'] = 1;
								$this->data['mes'][$x]['mesfin'] = 12;
							elseif($x == $this->data['year']['fin']) :
								if($this->data['year']['inicio'] == $this->data['year']['fin']) :
									$this->data['mes'][$x]['mesinicio'] = $tmp[1];
								else :
									$this->data['mes'][$x]['mesinicio'] = 1;
								endif;
								$this->data['mes'][$x]['mesfin'] = $tmp2[1];              
							endif;
						endfor;//echo "<pre>";print_r($this->data['mes']);exit;
						
						if(!empty($resolucion)) :
							$this->consultarpagos_model->set_resolucion($resolucion);
							$this->data['resolucion'] = $this->consultarpagos_model->get_resolucion();
							$this->data['stitle'] = 'Consulta por resolución No. '.$resolucion;
							$this->data['conceptos'] = $this->consultarpagos_model->get_conceptosfiscalizacion();
							if(!empty($this->data['resolucion'])) :
								$this->data['datos'] = true;
							else :
								$this->data['datos'] = false;
								$this->data['message'] = "No existen datos relacionados con los criterios de busqueda";
							endif;
						else :
							$this->data['datos'] = true;
							$this->data['resultados'] = $this->consultarpagos_model->get_pagos();
							//echo "<pre>";print_r($this->data['resultados']);echo "</pre>";//exit;
							$this->data['stitle'] = 'Consulta por concepto';
							$this->data['conceptos'] = $this->consultarpagos_model->ObtenerConceptos();
							$this->data['subconceptos'] = $this->consultarpagos_model->get_conceptos();
						endif;
						$this->template->load($this->template_file, 'consultarpagos/resultadobusqueda', $this->data);
					else :
						$this->session->set_flashdata('message', '<div class="alert alert"><button type="button" class="close" data-dismiss="alert">&times;</button>El número de identificación no registra pagos o no existe.</div>');
						redirect(base_url() . 'index.php/consultarpagos/manage');
					endif;
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que trae los pagos de acuerdo al concepto seleccionado en aplicarpago
   *
   * @return json con los resultados
   * @param string $idConcepto
   */
  function traerconceptos() {
    if ($this->ion_auth->logged_in()) {
      //if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/traerpagos')) {
        $concepto = $this->input->post('concepto');
        if (empty($concepto)) {
          redirect(base_url() . 'index.php/consultarpagos');
        } else {
          $this->consultarpagos_model->set_concepto($concepto);
          $result['datos'] = $this->consultarpagos_model->get_conceptos();
          if(!empty($result)) :
            echo '<option value="">Seleccione el sub concepto...</option>'."\n";
            foreach($result['datos'] as $concepto) :
              echo '<option value="'.$concepto['COD_CONCEPTO_RECAUDO'].'">'.$concepto['NOMBRE_CONCEPTO'].'</option>'."\n";
            endforeach;
            echo '<option value="TODOS">TODOS</option>'."\n";
          endif;
        }
      /*} else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }*/
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
          $this->consultarpagos_model->set_nit($nit);
          return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultarpagos_model->buscarnits()));
        }
      /*} else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }*/
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que muestra el detalle de un pago
   *
   * @return NULL
   * @param int $NUM_LIQUIDACION
   */
  public function detallepago() {
    if ($this->ion_auth->logged_in()) {
      if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarpagos/detallepago')) {
        $nit = $this->uri->segment(3);
        $concepto = $this->uri->segment(4);
        $periodo_inicio = $this->uri->segment(5);
        $periodo_fin = $this->uri->segment(6);
        $tipo = $this->uri->segment(7);
        if (empty($nit) || empty($concepto) || empty($periodo_inicio)) {
          redirect(base_url() . 'index.php/consultarpagos');
        } else {
          $this->consultarpagos_model->set_nit($nit);
          $this->consultarpagos_model->set_concepto($concepto);
          $this->consultarpagos_model->set_periodo_inicio(str_replace("-", "/", $periodo_inicio));
          $this->consultarpagos_model->set_periodo_fin(str_replace("-", "/", $periodo_fin));
          $this->data['empresa'] = $this->consultarpagos_model->get_empresa();
          $this->data['pagos'] = $this->consultarpagos_model->get_pagos();
          $this->data['periodo_inicio'] = $periodo_inicio;
          $this->data['periodo_fin'] = $periodo_fin;
          $concepto2 = $this->consultarpagos_model->ObtenerConceptos();
          if(empty($tipo)) :          
            $this->template->set('title', 'DETALLE PAGOS');
            $this->data['title'] = "DETALLE PAGOS";
            $this->data['pdf'] = false;
            $this->template->load($this->template_file, 'consultarpagos/detallepago', $this->data);
          elseif($tipo == "excel") :
            $this->load->library("PHPExcel");
            if(!isset($PHPExcel)) $PHPExcel = new PHPExcel();
            // Set document properties
            $PHPExcel->getProperties()->setCreator("SENA")
                     ->setTitle("DETALLE PAGOS POR CONCEPTO ".$concepto2[0]['NOMBRE_TIPO']);
            // Add some data
            $PHPExcel->setActiveSheetIndex(0);
            $columnas = array_keys($this->data['pagos'][0]);
            $abc = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
            $x = 0;
            foreach($columnas as $columna) :
              $PHPExcel->getActiveSheet()->setCellValue($abc[$x]."1", $columna);
              $x++;
            endforeach;
            $y = 2;
            foreach($this->data['pagos'] as $pago) :
              $t = count($pago);
              $x = 0;
              foreach($pago as $pag) {
                $PHPExcel->getActiveSheet()->setCellValue($abc[$x].$y, $pag);
                $x++;
              }
              $y++;
            endforeach;
            $nombre = str_replace(' ', '_', $concepto2[0]['NOMBRE_TIPO'])."_".$nit."_".$periodo_inicio."_".$periodo_fin;
            //header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
            $objWriter->save("./uploads/consultarpagos/".$nombre.".xlsx");
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$nombre.".xlsx".'"');
            ob_clean();
            $objWriter->save("php://output");
            /*header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header ("Content-Length: ".filesize("./uploads/consultarpagos/".$nombre));
            ob_clean();
            flush();
            readfile("./uploads/consultarpagos/".$nombre);*/
            die();
          elseif($tipo == "pdf") :
            $this->template->set('title', 'DETALLE PAGOS');
            $this->data['title'] = "DETALLE PAGOS";
            $this->data['pdf'] = true;
            $datos = $this->load->view('consultarpagos/detallepago', $this->data, true);
            $this->load->library("tcpdf");
            if(!isset($tcpdf)) $tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            // set document information
            $tcpdf->SetCreator(PDF_CREATOR);
            $tcpdf->SetAuthor('SENA');
            $tcpdf->SetTitle('DETALLE PAGOS '.$concepto2[0]['NOMBRE_TIPO']);
            $tcpdf->SetSubject($empresa['CODEMPRESA']);
            ob_clean();
            $tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //$tcpdf->setLanguageArray($l);
            $tcpdf->setFontSubsetting(true);
            $tcpdf->SetFont('dejavusans', '', 8, '', true);
            $tcpdf->AddPage();
            $tcpdf->writeHTML($datos, true, false, true, false, '');
            $tcpdf->Output(str_replace(' ', '_', $concepto2[0]['NOMBRE_TIPO'])."_".$nit."_".$periodo_inicio."_".$periodo_fin.".pdf", 'D');
            exit();
          endif;
        }
      } else {
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
        redirect(base_url() . 'index.php/inicio');
      }
    } else {
      redirect(base_url() . 'index.php/auth/login');
    }
  }
  /**
   * Funcion que transforma a letras el mes pasado por parametro
   *
   * @return string mes
   * @param int $mes
   */
  private function mes($mes) {
    switch($mes) :
      case "01" : return "Ene"; break;
      case "02" : return "Feb"; break;
      case "03" : return "Mar"; break;
      case "04" : return "Abr"; break;
      case "05" : return "May"; break;
      case "06" : return "Jun"; break;
      case "07" : return "Jul"; break;
      case "08" : return "Ago"; break;
      case "09" : return "Sep"; break;
      case "10" : return "Oct"; break;
      case "11" : return "Nov"; break;
      case "12" : return "Dic"; break;
    endswitch;
  }
  
  public function mes2($mes) {
    switch($mes) :
      case "01" : return "Enero"; break;
      case "02" : return "Febrero"; break;
      case "03" : return "Marzo"; break;
      case "04" : return "Abril"; break;
      case "05" : return "Mayo"; break;
      case "06" : return "Junio"; break;
      case "07" : return "Julio"; break;
      case "08" : return "Agosto"; break;
      case "09" : return "Septiembre"; break;
      case "10" : return "Octubre"; break;
      case "11" : return "Noviembre"; break;
      case "12" : return "Diciembre"; break;
    endswitch;
  }
}

/* End of file consultapagos.php */
/* Location: ./system/application/controllers/consultapagos.php */