<?php

class Gestioninstancias_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * Funcion getprocesos
     * Obtiene todos los procesos desde la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna todos los procesos en la base de Datos
     */
    function getdireccion() {
        $this->db->select('COD_MACROPROCESO, NOMBRE_MACROPROCESO');
        $this->db->order_by("NOMBRE_MACROPROCESO", "ASC");
        $query = $this->db->get('WF_MACROPROCESO');
        //$query = $this->db->order_by("NOMBRE_MACROPROCESO", "ASC");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_MACROPROCESO, ENT_QUOTES)] = htmlspecialchars($row->NOMBRE_MACROPROCESO);
            //$query->free_result();
            return $datos;
        }
    }

    function getprocesos($pro2 = '') {
        $this->db->select('COD_TIPO_PROCESO, TIPO_PROCESO');
        $this->db->where('AREA', $pro2);
        $this->db->order_by("TIPO_PROCESO", "ASC");
        $query = $this->db->get('TIPOPROCESO');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_TIPO_PROCESO, ENT_QUOTES)] = htmlspecialchars($row->TIPO_PROCESO);
            //$query->free_result();
            return $datos;
        }
    }
    
    function getprocesos1() {
        $this->db->select('COD_TIPO_PROCESO, TIPO_PROCESO');
        $this->db->order_by("TIPO_PROCESO", "ASC");
        $query = $this->db->get('TIPOPROCESO');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_TIPO_PROCESO, ENT_QUOTES)] = htmlspecialchars($row->TIPO_PROCESO);
            //$query->free_result();
            return $datos;
        }
    }

    /**
     * Funcion getusuarios
     * Obtiene todos los usuarios desde la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 
     * @return Retorna todos los usuarios en la base de Datos
     */
    function getusuarios() {
//        $this->db->select('IDUSUARIO,NOMBREUSUARIO');
//        $query = $this->db->get('USUARIOS');
//        if ($query->num_rows() > 0) {
//            foreach ($query->result() as $row)
//                $datos[htmlspecialchars($row->IDUSUARIO, ENT_QUOTES)] = htmlspecialchars($row->NOMBREUSUARIO);
//            //$query->free_result();
//            return $datos;
        $this->db->select('IDGRUPO,NOMBREGRUPO');
        $this->db->order_by("NOMBREGRUPO", "ASC");
        $query = $this->db->get('GRUPOS');
        //$this->db->order_by("NOMBREGRUPO", "ASC");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->IDGRUPO, ENT_QUOTES)] = htmlspecialchars($row->NOMBREGRUPO);
            //$query->free_result();
            return $datos;
        }
        // echo $this->db->last_query();
    }

    /**
     * Funcion getplantillas
     * Obtiene todas las plantillas desde la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna todas las plantillas en la base de Datos
     */
    function getgestiones() {
        $this->db->select('COD_GESTION, TIPOGESTION');
        $query = $this->db->get('TIPOGESTION');
        $this->db->order_by("TIPOGESTION", "ASC");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_GESTION, ENT_QUOTES)] = htmlspecialchars($row->TIPOGESTION);
            //$query->free_result();
            return $datos;
        }
        // echo $this->db->last_query();
    }

    function getplantillas() {
        $this->db->select('CODPLANTILLA,NOMBRE_PLANTILLA');
        $this->db->order_by("NOMBRE_PLANTILLA", "ASC");
        $query = $this->db->get('PLANTILLA');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->CODPLANTILLA, ENT_QUOTES)] = htmlspecialchars($row->NOMBRE_PLANTILLA);
            //$query->free_result();
            return $datos;
        }
        // echo $this->db->last_query();
    }
    
    function getinstan(){
        $this->db->select('COD_TIPO_INSTANCIA,NOMBRE_TIPO_INSTANCIA');
        $this->db->order_by("NOMBRE_TIPO_INSTANCIA", "ASC");
        $this->db->where("ESTADO", "A");
        $query = $this->db->get('TIPOS_INSTANCIAS');
//        echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_TIPO_INSTANCIA, ENT_QUOTES)] = htmlspecialchars($row->NOMBRE_TIPO_INSTANCIA);
            //$query->free_result();
            return $datos;
    }
    }
    /**
     * Funcion getgestion
     * Obtiene todos las gestiones desde la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna todos las gestiones en la base de Datos
     */
    function getgestion($pro = 0) {

        $this->db->select('COD_GESTION, TIPOGESTION');
        //print_r($pro);   
        //echo $pro;
        //if(!empty($codigoproceso))
        //{
        $this->db->where("CODPROCESO", $pro);
        //}
        $this->db->order_by("TIPOGESTION", "ASC");
        $query = $this->db->get('TIPOGESTION');
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                $datos[htmlspecialchars($row->COD_GESTION, ENT_QUOTES)] = htmlspecialchars($row->TIPOGESTION);
            return $datos;
        }
    }

    /**
     * Funcion getnotificaciones
     * Obtiene todos los recordatorios desde la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna todos los recordatorios en la base de Datos
     */
    function getinstancias() {
//        $acti = 'A';
        $this->db->select('INSTANCIAS_PROCESOS.COD_INSTANCIAS_PROCESOS, INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA,INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA,INSTANCIAS_PROCESOS.ESTADO, TIPOPROCESO.TIPO_PROCESO, TIPOPROCESO.AREA, TIPOS_INSTANCIAS.NOMBRE_TIPO_INSTANCIA');
        $query = $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $query = $this->db->join('TIPOS_INSTANCIAS', 'TIPOS_INSTANCIAS.COD_TIPO_INSTANCIA = INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA');
//        $query = $this->db->where('INSTANCIAS_PROCESOS.ESTADO', "'$acti'", FALSE);
        //$query = $this->db->order_by("CODRECORDATORIO", "ASC");
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();
        return $query;
    }

    function getestados() {
        $this->db->select('COD_TIPO_INSTANCIA,NOMBRE_TIPO_INSTANCIA,ESTADO');
        //$query = $this->db->order_by("CODRECORDATORIO", "ASC");
        $query = $this->db->get('TIPOS_INSTANCIAS');
//        echo $this->db->last_query();
        return $query;
    }

    function getusernoti($id) {
        $this->db->select('GRUPOS.NOMBREGRUPO,USUARIOSRECORDATORIO.CODRECORDADO');
        $query = $this->db->join('GRUPOS', 'GRUPOS.IDGRUPO = USUARIOSRECORDATORIO.CODRECORDADO');
        $query = $this->db->where('CODRECORDATORIO', $id);
        //$query = $this->db->order_by("NOMBREGRUPO", "ASC");
        $query = $this->db->get('USUARIOSRECORDATORIO');
        //echo $this->db->last_query();

        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    /**
     * Funcion editarnotificaciones
     * Edita los registros dependiendo de su CODRECORDATORIO
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function editarestados($id) {
        $this->db->select('COD_TIPO_INSTANCIA,NOMBRE_TIPO_INSTANCIA,ESTADO');
        $this->db->where("COD_TIPO_INSTANCIA", $id);
        $query = $this->db->get('TIPOS_INSTANCIAS');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }
    
    function editarinstancias($id) {
        $this->db->select('INSTANCIAS_PROCESOS.COD_INSTANCIAS_PROCESOS,INSTANCIAS_PROCESOS.COD_TIPO_PROCESO,INSTANCIAS_PROCESOS.COD_TIPO_INSTANCIA,ESTADO,WF_MACROPROCESO.COD_MACROPROCESO');
        $this->db->join('TIPOPROCESO', 'TIPOPROCESO.COD_TIPO_PROCESO = INSTANCIAS_PROCESOS.COD_TIPO_PROCESO');
        $this->db->join('WF_MACROPROCESO', "WF_MACROPROCESO.COD_MACROPROCESO = DECODE(TIPOPROCESO.AREA,'A', 1, 2)");
        $this->db->where("COD_INSTANCIAS_PROCESOS", $id);
        $query = $this->db->get('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    /**
     * Funcion insertar_notificaciones
     * Insertar las nuevas notificaciones generadas por el usuario
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function insertar_instancias($data) {

        $datosin = array('COD_TIPO_PROCESO' => $data['selProcesos'],
            'COD_TIPO_INSTANCIA' => $data['instancia']);

        $this->db->insert('INSTANCIAS_PROCESOS', $datosin);
    }

    function insertar_estados($data) {

        $datos = array('COD_TIPO_INSTANCIA' => $data['NUM_INSTANCIA'],
            'NOMBRE_TIPO_INSTANCIA' => $data['NOM_INSTANCIA']);

        $this->db->insert('TIPOS_INSTANCIAS', $datos);
    }

    /**
     * Funcion delete_noti
     * Actualiza el estado de ACTIVO a 'N' para no mostrarlo en las consultas de los recordatorios
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    public function delete_instancia($instancia) {
        $this->db->set('ESTADO', 'I');
        $this->db->where('COD_INSTANCIAS_PROCESOS', $instancia);
        $this->db->update('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();
    }

    public function activa_estado($estado) {
        $this->db->set('ESTADO', 'A');
        $this->db->where('COD_TIPO_INSTANCIA', $estado);
        $this->db->update('TIPOS_INSTANCIAS');
//        echo $this->db->last_query();
    }

    public function inactiva_estado($estado1) {
        $this->db->set('ESTADO', 'I');
        $this->db->where('COD_TIPO_INSTANCIA', $estado1);
        $this->db->update('TIPOS_INSTANCIAS');
//        echo $this->db->last_query();
    }
    
    public function activa_instancia($estado) {
        $this->db->set('ESTADO', 'A');
        $this->db->where('COD_INSTANCIAS_PROCESOS', $estado);
        $this->db->update('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();
    }

    public function inactiva_instancia($estado1) {
        $this->db->set('ESTADO', 'I');
        $this->db->where('COD_INSTANCIAS_PROCESOS', $estado1);
        $this->db->update('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();
    }

    /**
     * Funcion update_noti
     * Actuliza $CODRECORDATORIO, $ACTIVO, $CODPLANTILLA, $TIEMPO_MEDIDA, $TIEMPO_NUM de la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    public function update_estado($NUM_INSTANCIA, $NOM_INSTANCIA,$instancia) {
        $this->db->set('COD_TIPO_INSTANCIA', $NUM_INSTANCIA);
        $this->db->set('NOMBRE_TIPO_INSTANCIA', $NOM_INSTANCIA);
        $this->db->where('COD_TIPO_INSTANCIA', $instancia);
        $this->db->update('TIPOS_INSTANCIAS');
//        echo $this->db->last_query();
    }
    
    public function update_instancia($PROCESO, $INSTANCIA, $TIPOINSTANCIA) {
        $this->db->set('COD_TIPO_PROCESO', $PROCESO);
        $this->db->set('COD_TIPO_INSTANCIA', $INSTANCIA);
        $this->db->where('COD_INSTANCIAS_PROCESOS', $TIPOINSTANCIA);
        $this->db->update('INSTANCIAS_PROCESOS');
//        echo $this->db->last_query();
    }

    public function si_existe_estado($estado) {
        $this->db->select('COD_TIPO_INSTANCIA,NOMBRE_TIPO_INSTANCIA,ESTADO');
        $this->db->where('COD_TIPO_INSTANCIA', $estado);
        //$query = $this->db->order_by("CODRECORDATORIO", "ASC");
        $query = $this->db->get('TIPOS_INSTANCIAS');
//        $query=$query->result_array;
        return $query->result_array();
    }

}
