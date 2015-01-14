<?php
/**
 * Aplicacionautomaticadepago (class CI_Model) :)
 *
 * Aplicación automatica de pagos
 *
 * Permite aplicar pagos automaticamente.
 *
 * @author Felipe Camacho [camachogfelipe]
 * @author http://www.cogroupsas.com
 *
 * @package Aplicacionautomaticadepago
 */
class Usuarios_model extends CI_Model {
	
	function __construct() {
    parent::__construct();
  }
	
	function PermisosUsuario($grupo_id, $id) {
		$results = $this->ObtenerPermisosGrupo($grupo_id);
		//echo "<pre>";print_r($results);echo("</pre>");
		foreach ($results as $key => $value) {
			$this->db->select("IDMENU");
			$this->db->where("IDMENU", $value->IDMENU);
			$this->db->where("IDUSUARIO", $id);
			$res = $this->db->get("PERMISOS_USUARIOS");
			if($res->num_rows() == 0) :
				$permisos_data = array(
						'IDUSUARIO' => $id,
						'IDMENU' => $value->IDMENU
				);
			endif;
			//echo "<pre>";print_r($permisos_data);echo "</pre>";exit();
			if(!empty($permisos_data)) :
				$this->AgregarPermisosUsuarios($permisos_data);
			endif;
		}
	}
	
	function PermisosUsuariosEditar($grupo_id, $id) {
		$results = $this->ObtenerPermisosGrupo($grupo_id);
		$menus = array();
		foreach ($results as $key => $value) :
			$menus[] = $value->IDMENU;
		endforeach;
		if(!empty($menus)) :
			$this->db->where_in("IDMENU", $menus);
			$this->db->where("IDUSUARIO", $id);
			$this->db->delete('PERMISOS_USUARIOS');
		endif;
		$results = $this->ObtenerPermisosUsuario($id);
		$menus = array();
		if(!empty($results)) :
			foreach ($results as $key => $value) :
				$menus[] = $value->IDMENU;
			endforeach;
		endif;
		//Agregamos los permisos del grupo al usuario
		$results = $this->ObtenerPermisosGrupo($grupo_id, $menus);
		foreach ($results as $key => $value) {
			$permisos_data = array(
					'IDUSUARIO' => $id,
					'IDMENU' => $value->IDMENU
			);
			//echo "<pre>";print_r($permisos_data);echo "</pre>";exit();
			if(!empty($permisos_data)) :
				$this->AgregarPermisosUsuarios($permisos_data);
			endif;
		}
	}
	
	function ObtenerPermisosGrupo($idgrupo, $not_in = NULL) {
		if(!empty($idgrupo)) :
			$this->db->select();
			$this->db->where("IDGRUPO", $idgrupo);
			if(!empty($not_in)) :
				$this->db->where_not_in("IDMENU", $not_in);
			endif;
			$query = $this->db->get("PERMISOS_GRUPOS");
			return $query->result();
		endif;
		return NULL;
	}
	
	function AgregarPermisosUsuarios($data) {
		if(!empty($data)) :
			$this->db->insert('PERMISOS_USUARIOS', $data);
			return TRUE;
		endif;
		return FALSE;
	}
	
	function ObtenerPermisosUsuario($idusuario) {
		if(!empty($idusuario)) :
			$this->db->select();
			$this->db->where("IDUSUARIO", $idusuario);
			$query = $this->db->get("PERMISOS_USUARIOS");
			return $query->result();
		endif;
		return NULL;
	}
	
	function ObtenerCargos() {
		$this->db->select("IDCARGO, NOMBRECARGO");
		$this->db->order_by("NOMBRECARGO", "asc");
		$query = $this->db->get("CARGOS");
		return $query->result();
	}
	
	function ObtenerGrupos() {
		$this->db->select("IDGRUPO, UPPER(NOMBREGRUPO) AS NOMBREGRUPO");
		$this->db->order_by("NOMBREGRUPO", "asc");
		$query = $this->db->get("GRUPOS");
		return $query->result();
	}
	
	function ObtenerRegionales() {
		$this->db->select("COD_REGIONAL, NOMBRE_REGIONAL");
		$this->db->order_by("NOMBRE_REGIONAL", "asc");
		$query = $this->db->get("REGIONAL");
		return $query->result();
	}
	
	function ObtenerIdGrupoUsuario($idusuario) {
		if(!empty($idusuario) and !is_null($idusuario)) :
			$this->db->select("IDGRUPO");
			$this->db->where("IDUSUARIO", $idusuario);
			$this->db->limit(1, 1);
			$query = $this->db->get("USUARIOS_GRUPOS");
			return $query->row();
		endif;
	}
	
	function EditarUsuarioGrupo($data, $idusuario) {
		$this->db->where("IDUSUARIO", $idusuario);
    $this->db->update("USUARIOS_GRUPOS", $data);
    if ($this->db->affected_rows() >= 0) return TRUE;
    return FALSE;
	}
	
	function EditarUsuario($data, $idusuario) {
		$this->db->where("IDUSUARIO", $idusuario);
    $this->db->update("USUARIOS", $data);
    if ($this->db->affected_rows() >= 0) return TRUE;
    return FALSE;
	}
	
	function ObtenerUsuario($id) {
		$this->db->select('IDUSUARIO, NOMBREUSUARIO, EMAIL, IDCARGO, NOMBRES, APELLIDOS, COD_REGIONAL, FISCALIZADOR, DIRECCION, 
											 TELEFONO, CELULAR, CORREO_PERSONAL');
		$this->db->where('IDUSUARIO', $id);
		$this->db->limit(1, 1);
		$query = $this->db->get('USUARIOS');
		return $query->row();
	}
	
	function BorrarPermiso($idpermiso) {
		$this->db->where('IDPERMISO_USUARIO', $idpermiso);
    $this->db->delete('PERMISOS_USUARIOS');
    if ($this->db->affected_rows() == '1') {
      return TRUE;
    }

    return FALSE;
	}
}