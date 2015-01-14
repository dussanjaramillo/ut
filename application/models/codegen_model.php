<?php
class Codegen_model extends CI_Model {

  function __construct() {
    parent::__construct();
  }

  function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array') {

    $this->db->select($fields);
    $this->db->from($table);
    $this->db->limit($perpage, $start);
    if (!empty($where)) {
			if(is_array($where)) :
				foreach($where as $k=>$v) $this->db->where($k, $v);
			else : $this->db->where($where);
			endif;
    }

    $query = $this->db->get();//echo $this->db->last_query();exit;

    $result = !$one ? $query->result($array) : $query->row();
    return $result;
  }

  function getoption($table, $where, $id) {

    $this->db->where($where, $id);
    $result = $this->db->get($table);
    return $result;
  }

  function addbanco($table, $data) {

    $this->db->set('IDBANCO', $data['IDBANCO']);
    $this->db->set('NOMBREBANCO', $data['IDBANCO']);
    $this->db->set('FECHACREACION', "to_date('" . $data['FECHACREACION'] . "','dd/mm/yyyy')", false);
    $this->db->set('IDESTADO', $data['IDESTADO']);
    $this->db->insert($table);
    if ($this->db->affected_rows() == '1') {
      return TRUE;
    }

    return FALSE;
  }

  function add($table, $data, $date = '') {
    if ($date != '') {
      foreach ($data as $key => $value) {
        $this->db->set($key, $value);
      }
      foreach ($date as $keyf => $valuef) {
        $this->db->set($keyf, "to_date('" . $valuef . "','dd/mm/yyyy')", false);
      }

      $this->db->insert($table);
    } else {
      $this->db->insert($table, $data);
    }
    if ($this->db->affected_rows() == '1') {
    return TRUE;

    }

    return FALSE;
  }
  function getLastInserted($table, $id) {
	$this->db->select_max($id);
	$Q = $this->db->get($table);
	$row = $Q->row_array();
	return $row[$id];
 }

  function edit($table, $data, $fieldID, $ID) {
    $this->db->where($fieldID, $ID);
    $this->db->update($table, $data);

    if ($this->db->affected_rows() >= 0) {
      return TRUE;
    }

    return FALSE;
  }

  function edita($table, $data, $date = '', $fieldID, $ID) {
    $this->db->where($fieldID, $ID);
    if ($date != '') {
      foreach ($data as $key => $value) {
        $this->db->set($key, $value);
      }
      foreach ($date as $keyf => $valuef) {
        $this->db->set($keyf, "to_date('" . $valuef . "','dd/mm/yyyy')", false);
      }

      $this->db->update($table);
    } else {

      $this->db->update($table, $data);
    }
    if ($this->db->affected_rows() >= 0) {
      return TRUE;
    }

    return FALSE;
  }

  function delete($table, $fieldID, $ID) {
    $this->db->where($fieldID, $ID);
    $this->db->delete($table);
    if ($this->db->affected_rows() == '1') {
      return TRUE;
    }

    return FALSE;
  }

  function count($table) {
    return $this->db->count_all($table);
  }

  function max($table, $field) {
    $this->db->select_max($field);
    $query = $this->db->get($table);
    if ($query->num_rows() > 0) {
      return $query->row_array(); //return the row as an associative array
    }
    return FALSE;
  }

  function getSelect($table, $fields, $where = '', $order = '') {
		if(!empty($where)) :
			//echo "query1: SELECT " . $fields . "  FROM " . $table . " " . $where . " " . $order . " ";
			$where = explode("WHERE", $where);
			if(count($where) > 1) :
				$tmp = explode("=", $where[1]);
				$pos1 = strpos($tmp[1], "'");
				$pos2 = strpos($tmp[1], '"');
				if($pos1 < 1 and $pos2 < 1) :
					$tmp[1] = "'" . trim($tmp[1]) . "'";
				endif;
			endif;
			$where[1] = implode("=", $tmp);
			$where = implode("WHERE", $where);
		endif;
		//echo "<br>query2: SELECT " . $fields . "  FROM " . $table . " " . $where . " " . $order . " ";//exit();
    $query = $this->db->query("SELECT " . $fields . "  FROM " . $table . " " . $where . " " . $order . " ");
    //echo $this->db->last_query();
		return $query->result();
  }

  function getSequence($table, $name) {
    $query = $this->db->query("SELECT " . $name . "  FROM " . $table . " ");
    $row = $query->row_array();
    return $row['NEXTVAL'] - 1;
  }
  
    function getMunicipio($cod_regional) {

    $query = $this->db->query("
	SELECT MUNICIPIO.CODMUNICIPIO,MUNICIPIO.COD_DEPARTAMENTO,MUNICIPIO.NOMBREMUNICIPIO 
	FROM REGIONAL 
	JOIN MUNICIPIO ON MUNICIPIO.COD_DEPARTAMENTO=REGIONAL.COD_DEPARTAMENTO 
	WHERE REGIONAL.COD_REGIONAL ='" . $cod_regional . "'");
		return $query->result();
  }

}
