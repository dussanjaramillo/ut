<?php
/**
 * Verificarpagos (class CI_Model) :)
 *
 * Verificaci�n de pagos
 *
 * Permite verificar los pagos recibidos de una deuda en proceso juridico, y envia correo al abogado respectivo notificando el pago respectivo.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Verificarpagos
 */
class Verificarpagos_model extends CI_Model {

  private $nit;
  private $regional;
  private $proceso;
  private $fecha;
  private $estado;

  function __construct() {
    parent::__construct();
    $this->proceso = array(9, 12, 11, 14, 17);
    $this->estado = 1;
  }
  
  function set_proceso_aportes() {
    $this->proceso = array(2);
  }

  function set_nit($nit) {
    $this->nit = $nit;
  }

  function set_regional($regional) {
    $this->razon = $regional;
  }

  function set_fecha($fecha) {
    $this->fecha = $fecha;
  }
	
	function SetProceso($proceso) {
    $this->proceso = $proceso;
  }

  public function get_pagos() {
    if (!empty($this->fecha))
      $this->db->where("VW_PAGOS_JURIDICA.FECHA_PAGO >=", " TO_DATE('" . $this->fecha . "', 'YYYY-MM-DD')", FALSE);
    if (!empty($this->estado))
      $this->db->where("VW_PAGOS_JURIDICA.APLICADO", 1);
		$this->db->select("DISTINCT NO_EXPEDIENTE", FALSE);
		$this->db->select("COD_PROCESO_COACTIVO, IDENTIFICACION, EJECUTADO, TELEFONO, DIRECCION, CONCEPTO, " .
											"CORREO_ELECTRONICO, NUM_LIQUIDACION, VISTA_ORIGEN, ABOGADO, FECHA_PROCESO, EMAIL, NOMBRES, FECHA_PAGO, " .
											"FECHA_APLICACION, , DISTRIBUCION_CAPITAL, VALOR_PAGADO, DISTRIBUCION_INTERES, DISTRIBUCION_INTERES_MORA, APLICADO, " .
											"DIAS_MORA, FECHA_VENCIMIENTO, VALOR_DEUDA_ACTUAL");
    $dato = $this->db->get("VW_PAGOS_JURIDICA");
    //echo $this->db->last_query();
		//exit();
    $dato = $dato->result_array();
    if (!empty($dato)) : return $dato;
    else : return $dato = NULL;
    endif;
  }
	
	function obtenerFiscalizacion($codfiscalizacion = NULL) {
		$this->db->select("PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO, PROCESOS_COACTIVOS.ABOGADO, PROCESOS_COACTIVOS.IDENTIFICACION")
						 ->from("PROCESOS_COACTIVOS")
						 ->order_by("PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO");
		if(empty($this->proceso)) :
			return $this->db->get()->result_array();
		else :
			$data = $this->db->where("PROCESOS_COACTIVOS.COD_PROCESO_COACTIVO", $codfiscalizacion)->get()->row_array();
			//echo $this->db->last_query();
			return $data;
		endif;
	}
	
	function crearAuto($data = array()) {
		if(!empty($data)) :
			$this->db->set("FECHA_CREACION_AUTO", "SYSDATE", FALSE);
			$this->db->insert("AUTOSJURIDICOS", $data);
                        //echo $this->db->last_query();die();
			$query = $this->db->query("SELECT AutosJuridico_num_autogene_SEQ.CURRVAL FROM dual");
			$query = $query->row();
			//echo "<pre>";print_r($query);die();
			return $query->CURRVAL;
		else :
			return false;
		endif;
	}
	
	function ActualizarTitulos($data) {
     
		if(!empty($data)) :
			foreach($data['TITULOS'] as $dato) :
				$this->db->where("COD_RECEPCIONTITULO", $dato['COD_RECEPCIONTITULO']);
				unset($dato['COD_RECEPCIONTITULO']);
				foreach($dato as $key=>$dat) :
					if(!empty($key) and !is_null($key)) $this->db->set("$key", $dat);
				endforeach;
				$this->db->update("RECEPCIONTITULOS");
			endforeach;
			return true;
		else :
			return false;
		endif;
               
	}
}

?>