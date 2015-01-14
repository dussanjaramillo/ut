<?php
class SqlPadre_model extends CI_Model{
        
    function __construct() {
        parent::__construct();
    }
        
    /* Método consultaProcesos
    * @author F. Ricardo Puerto :: Thomas MTI
    */
    function consultarProcesos(){
        $sql = "SELECT codproceso, nombreproceso FROM proceso WHERE (activo = 'S' OR activo = 'SI' OR activo IS NULL) ";
        $dato = $this->db->query($sql);
        return $dato->result_array();
    }

    /* Método consultarActividades
    * @author F. Ricardo Puerto :: Thomas MTI
    */
    function consultarActividades($proceso=0){
        $sql = "SELECT codactividad, nombreactividad FROM actividad WHERE (activo = 'S' OR activo = 'SI' OR activo IS NULL) ";
        if(!empty($proceso) && $proceso!="-1"){
            $sql .= " AND codproceso = $proceso";
        }
        $dato = $this->db->query($sql);
        return $dato->result_array();
    }
}
?>