<?php
/**
 * Regionales_model (class CI_Model) :)
 *
 * Gestión, parametrización y edición de regionales SENA
 *
 * Permite hacer las consultas para gestionar todo lo relacionado con los datos de las regionales SENA
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Regionales
 */
class Regionales_model extends CI_Model {
	var $id;

  function __construct() {
    parent::__construct();
  }
	
	function SetId($id) {
		if(!empty($id) and is_numeric($id)) :
			$this->id = trim($id);
		endif;
	}
	
	function ObtenerRegionales($total = false) {
		$this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, REGIONAL.EMAIL_REGIONAL, " . 
											"UPPER(DIR.NOMBRES) || ' ' || UPPER(DIR.APELLIDOS) AS DIRECTOR, " .
											"UPPER(CRC.NOMBRES) || ' ' || UPPER(CRC.APELLIDOS) AS COORDINADOR_RELACIONES, " .
											"UPPER(COR.NOMBRES) || ' ' || UPPER(COR.APELLIDOS) AS COORDINADOR, " .
											"UPPER(SEC.NOMBRES) || ' ' || UPPER(SEC.APELLIDOS) AS SECRETARIO");
		$this->db->from("REGIONAL");
		$this->db->join("USUARIOS DIR", "DIR.IDUSUARIO=REGIONAL.CEDULA_DIRECTOR", "LEFT");
		$this->db->join("USUARIOS CRC", "CRC.IDUSUARIO=REGIONAL.CEDULA_COORDINADOR_RELACIONES", "LEFT");
		$this->db->join("USUARIOS COR", "COR.IDUSUARIO=REGIONAL.CEDULA_COORDINADOR", "LEFT");
		$this->db->join("USUARIOS SEC", "SEC.IDUSUARIO=REGIONAL.CEDULA_SECRETARIO", "LEFT");
		$this->db->order_by("REGIONAL.COD_REGIONAL", "asc");
		$dato = $this->db->get();
		if($dato->num_rows() > 0) :
			return $dato->result_array();
		endif;
	}
	
	function ObtenerRegional() {
		if(!empty($this->id)) :
			$this->db->select("REGIONAL.COD_REGIONAL, REGIONAL.NOMBRE_REGIONAL, REGIONAL.TELEFONO_REGIONAL, REGIONAL.DIRECCION_REGIONAL, " .
												"REGIONAL.CELULAR_REGIONAL, REGIONAL.COD_CIUDAD, REGIONAL.COD_DEPARTAMENTO, REGIONAL.EMAIL_REGIONAL, " .
												"REGIONAL.CEDULA_DIRECTOR, REGIONAL.CEDULA_COORDINADOR, REGIONAL.COD_SIIF, REGIONAL.CEDULA_SECRETARIO, " .
												"REGIONAL.CEDULA_COORDINADOR_RELACIONES, " .
												"UPPER(DIR.NOMBRES) || ' ' || UPPER(DIR.APELLIDOS) AS DIRECTOR, " .
												"UPPER(CRC.NOMBRES) || ' ' || UPPER(CRC.APELLIDOS) AS COORDINADOR_RELACIONES, " .
												"UPPER(COR.NOMBRES) || ' ' || UPPER(COR.APELLIDOS) AS COORDINADOR, " .
												"UPPER(SEC.NOMBRES) || ' ' || UPPER(SEC.APELLIDOS) AS SECRETARIO");
			$this->db->from("REGIONAL");
			$this->db->join("USUARIOS DIR", "DIR.IDUSUARIO=REGIONAL.CEDULA_DIRECTOR", "LEFT");
			$this->db->join("USUARIOS CRC", "CRC.IDUSUARIO=REGIONAL.CEDULA_COORDINADOR_RELACIONES", "LEFT");
			$this->db->join("USUARIOS COR", "COR.IDUSUARIO=REGIONAL.CEDULA_COORDINADOR", "LEFT");
			$this->db->join("USUARIOS SEC", "SEC.IDUSUARIO=REGIONAL.CEDULA_SECRETARIO", "LEFT");
			$this->db->where("REGIONAL.COD_REGIONAL", $this->id);
			$dato = $this->db->get();
			if($dato->num_rows() > 0) :
				return $dato->row_array();
			endif;
		endif;
	}
	
	function ObtenerDepartamentos () {
		$this->db->select("COD_DEPARTAMENTO, NOM_DEPARTAMENTO");
		$this->db->from("DEPARTAMENTO");
		$dato = $this->db->get();
		if($dato->num_rows() > 0) :
			return $dato->result_array();
		endif;
	}
	
	function ObtenerCiudades ($id = NULL) {
		$this->db->select("CODMUNICIPIO, NOMBREMUNICIPIO");
		$this->db->from("MUNICIPIO");
		if(!is_null($id)) :
			$this->db->where("COD_DEPARTAMENTO", trim($id));
		endif;
		$dato = $this->db->get();
		if($dato->num_rows() > 0) :
			return $dato->result_array();
		endif;
	}
	
	function ObtenerUsuarios ($id = NULL) {
		$this->db->select("CODMUNICIPIO, NOMBREMUNICIPIO");
		$this->db->from("MUNICIPIO");
		if(!is_null($id)) :
			$this->db->where("COD_DEPARTAMENTO", trim($id));
		endif;
		$dato = $this->db->get();
		if($dato->num_rows() > 0) :
			return $dato->result_array();
		endif;
	}
	
	function Usuarios($termino) {
		if(!empty($termino)) :
			$this->db->select("IDUSUARIO, UPPER(NOMBRES) || ' ' || UPPER(APELLIDOS) AS NOMBRE");
			$this->db->from("USUARIOS");
			$this->db->like("IDUSUARIO", trim($termino));
			$this->db->or_like("NOMBRES", trim($termino));
			$this->db->or_like("APELLIDOS", trim($termino));
			$usuarios = $this->db->get();
			if($usuarios->num_rows() > 0) :
				$usuarios = $usuarios->result_array();
				foreach($usuarios as $usuario) :
					$datos[] = array("value" => $usuario['IDUSUARIO'], 
											 		 "label" => $usuario['IDUSUARIO'] . " :: " . $usuario['NOMBRE']
										 );
				endforeach;
				return $datos;
			endif;
		endif;
	}
	
	function VerificarId($id) {
		if(!empty($id) and is_numeric(trim($id))) :
			$this->db->select("COD_REGIONAL");
			$this->db->from("REGIONAL");
			$this->db->where("COD_REGIONAL", $id);
			$id = $this->db->get();
			if($id->num_rows() > 0) :
				return false;
			else :
				return true;
			endif;
		endif;
	}
	
	function AgregarRegional($datos) {
		if(!empty($datos)) :
			if($this->db->insert("REGIONAL", $datos)) return true;
			else return false;
		endif;
	}
	
	function ActualizarRegional($datos) {
		if(!empty($datos)) :
			$this->db->where("COD_REGIONAL", $datos['COD_REGIONAL']);
			if($this->db->update("REGIONAL", $datos)) return true;
			else return false;
		endif;
	}
}
