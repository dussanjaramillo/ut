<?php
class Calendariofestivo_model extends CI_Model{

	function __construct() {
        parent::__construct();
    }
    
    public function festivo($objFecha) {//echo " sel8. ";
      if ($this->ion_auth->logged_in()){  
        $this->db->select("ESTADO");
        $this->db->where("DIA", $objFecha->format('d'));
        $this->db->where("MES", $objFecha->format('m'));
        $this->db->where("ANNO", $objFecha->format('Y'));
        $dato = $this->db->get("FESTIVO");
        return $dato->result_array();
      }else{
        redirect(base_url().'index.php/auth/login');
      }    
    }
    
    public function festivosanuales($aaaa){//echo " sel17. ";
      if ($this->ion_auth->logged_in()){  
        $this->db->select("ESTADO");
        $this->db->select("DIA");
        $this->db->select("MES");
        $this->db->select("ANNO");
        //$this->db->where("MES", $objFecha->format('m'));
        $this->db->where("ANNO", $aaaa);
        $this->db->or_where("ANNO", $aaaa+1); //De este año y el siguiente        
        $dato = $this->db->get("FESTIVO");
        return $dato->result_array();
      }else{
        redirect(base_url().'index.php/auth/login');
      }  
    }    
    
    public function festivo3fechas($aaaa,$mm,$dd){
      if ($this->ion_auth->logged_in()){  
        $this->db->select("ESTADO");
        $this->db->where("ANNO", $aaaa);
        $this->db->where("MES",  $mm);
        $this->db->where("DIA",  $dd);
        $dato = $this->db->get("FESTIVO");
        
        //echo " LQ:".$this->db->last_query();        echo " DNR:".$dato->num_rows();
        return $dato->num_rows();
      }else{
        redirect(base_url().'index.php/auth/login');
      }  
    }
    
    public function ponerfestivo($num,$f0,$f1,$f2,$estadoponer){
      if ($this->ion_auth->logged_in()){    
        if($num==1){
                $sql = " UPDATE festivo SET estado=$estadoponer WHERE anno=".$f0." AND mes=".$f1." AND dia=".$f2;
        }elseif($num==0){
                $sql = " INSERT INTO festivo(anno,mes,dia,estado)values('".$f0."','".$f1."','".$f2."','$estadoponer')";
        }
        $this->db->query($sql);
        //return $dato->result_array();
      }else{
        redirect(base_url().'index.php/auth/login');
      }  
    }
    
    public function borrarfestivo($num,$f0,$f1,$f2){
      if ($this->ion_auth->logged_in()){      
        if($num==1){
                $sql = " DELETE FROM festivo WHERE anno=".$f0." AND mes=".$f1." AND dia=".$f2;
                $this->db->query($sql);
        }else{//no existe
        }
      }else{
        redirect(base_url().'index.php/auth/login');
      }  
    }
}

?>