<?php 
class Correo_model extends CI_model{

function __construct() {
        parent::__construct();
    }

    /**
    * Método actualizarCorreo
    * Actualizar los datos del correo
    * 
    * @author Felipe R. Puerto :: Thomas MTI
    * @since 7 III 2014
    * 
    */

   function actualizarCorreo($correo,$servidorsmtp,$puertosmtp,$pass){//$caso = 301; //$codrecordatorio = 28;
       $dat = array('CORREO_ELECTRONICO' => $correo, 'SERVIDOR_SMTP' => $servidorsmtp, 'PUERTO_SMTP' => $puertosmtp, 'PASSWORD' => $pass);
       $this->db->update('PARAMETROSCORREOELECTRONICO', $dat);
       //echo " ".__LINE__.":".$this->db->last_query();
       return 'LISTO';
   }
   
    function traeParametrosCorreo(){
        $this->db->select('CORREO_ELECTRONICO');
        $this->db->select('NOMBRE_REMITENTE');
        $this->db->select('PASSWORD');
        $this->db->select('NRO_INTENTOS_FALLIDOS');
        $this->db->select('SERVIDOR_SMTP');
        //$this->db->select('SERVIDOR_POP3');
        $this->db->select('PUERTO_SMTP');
        //$this->db->select('PUERTO_POP3');
        $this->db->select('REQUIERE_SSL');
        $dato = $this->db->get('PARAMETROSCORREOELECTRONICO');        //echo "<br>".__FILE__." > ".__LINE__.": ".$this->db->last_query();ç
        $row = $dato->row_array(); 
        return $row;
    }
}

?>