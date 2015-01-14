<?php

class Gestionwf_model extends CI_Model {

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
    function getnotificaciones($datos) {
        $acti = 'N';
        $this->db->select('RECORDATORIO.CODRECORDATORIO, RECORDATORIO.CODACTIVIDAD, TIPOGESTION.TIPOGESTION, RECORDATORIO.ACTIVO, GRUPOS.IDGRUPO,GRUPOS.NOMBREGRUPO, RECORDATORIO.CODPLANTILLA, PLANTILLA.NOMBRE_PLANTILLA, RECORDATORIO.TIEMPO_MEDIDA, RECORDATORIO.TIEMPO_NUM, RECORDATORIO.NOMBRE_RECORDATORIO, RECORDATORIO.TIPO_RECORDATORIO');
        $query = $this->db->where('TIPO_RECORDATORIO', $datos['tiponoti']);
        $query = $this->db->where('CODACTIVIDAD', $datos['selgestion']);
        $query = $this->db->join('USUARIOSRECORDATORIO', 'USUARIOSRECORDATORIO.CODRECORDATORIO = RECORDATORIO.CODRECORDATORIO');
        $query = $this->db->join('GRUPOS', 'GRUPOS.IDGRUPO = USUARIOSRECORDATORIO.CODRECORDADO');
        $query = $this->db->join('PLANTILLA', 'RECORDATORIO.CODPLANTILLA = PLANTILLA.CODPLANTILLA');
        $query = $this->db->join('TIPOGESTION', 'TIPOGESTION.COD_GESTION = RECORDATORIO.CODACTIVIDAD');
        $query = $this->db->where('RECORDATORIO.ACTIVO!=', "'$acti'", FALSE);
        //$query = $this->db->order_by("CODRECORDATORIO", "ASC");
        $query = $this->db->get('RECORDATORIO');
//        echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
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
    function editarnotificaciones($id) {
        $this->db->select('RECORDATORIO.CODRECORDATORIO,RECORDATORIO.ACTIVO,RECORDATORIO.PERMISO,RECORDATORIO.RECORDATORIO_CORREO,RECORDATORIO.RECORDATORIO_PANTALLA, RECORDATORIO.NOMBRE_RECORDATORIO, RECORDATORIO.CODPLANTILLA, RECORDATORIO.TIEMPO_MEDIDA, RECORDATORIO.TIEMPO_NUM, RECORDATORIO.TIPO_RECORDATORIO, RECORDATORIO.NOMBRE_RECORDATORIO');
        $this->db->where("CODRECORDATORIO", $id);
        $query = $this->db->get('RECORDATORIO');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function deleteuser($CODRECORDATORIO) {
        $this->db->where('CODRECORDATORIO', $CODRECORDATORIO);
        $this->db->delete('USUARIOSRECORDATORIO');
        //echo $this->db->last_query();
    }

    /**
     * Funcion insertar_notificaciones
     * Insertar las nuevas notificaciones generadas por el usuario
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function insertar_notificaciones($data) {
//        
//        $this->db->set('CODACTIVIDAD', $data['CODACTIVIDAD']);
//        $this->db->set('CODPLANTILLA', $data['CODPLANTILLA']);
//        $this->db->set('ACTIVO', $data['ACTIVO']);
//        $this->db->set('RECORDATORIO_CORREO', $data['RECORDATORIO_CORREO']);
//        $this->db->set('RECORDATORIO_PANTALLA', $data['RECORDATORIO_PANTALLA']);
//        $this->db->set('TIEMPO_MEDIDA', $data['TIEMPO_MEDIDA']);
//        $this->db->set('TIEMPO_NUM', $data['TIEMPO_NUM']);
//        $this->db->set('NOMBRE_RECORDATORIO', utf8_decode($data['NOMBRE_RECORDATORIO']));
//        $this->db->set('TIPO_RECORDATORIO', $data['TIPO_RECORDATORIO']);
//        $this->db->insert('RECORDATORIO');
//        
        $datauno = array('CODACTIVIDAD' => $data['CODACTIVIDAD'],
            'CODPLANTILLA' => $data['CODPLANTILLA'],
            'ACTIVO' => $data['ACTIVO'],
            'RECORDATORIO_CORREO' => $data['RECORDATORIO_CORREO'],
            'RECORDATORIO_PANTALLA' => $data['RECORDATORIO_PANTALLA'],
            'TIEMPO_MEDIDA' => $data['TIEMPO_MEDIDA'],
            'TIEMPO_NUM' => $data['TIEMPO_NUM'],
            'NOMBRE_RECORDATORIO' => $data['NOMBRE_RECORDATORIO'],
            'TIPO_RECORDATORIO' => $data['TIPO_RECORDATORIO']
        );
//        echo "<pre>";
//        var_dump($datauno);
//        
        $this->db->insert('RECORDATORIO', $datauno);
         

//        die;
    }

    /**
     * Funcion insertar_usuariorecordatorio
     * Inserta en la tabla usuariorecordatorio dependiendo los usuarios seleccionados y su CODRECORDATORIO
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     * @return Retorna todos los procesos en la base de Datos
     */
    function insertar_usuariorecordatorio($idrecordatorio, $usuario) {
        $this->db->set('CODRECORDATORIO', $idrecordatorio);
        $this->db->set('CODRECORDADO', $usuario);
        $this->db->set('TIPO_RECORDADO', 'usu');


        $this->db->insert('USUARIOSRECORDATORIO');
        //echo $this->db->last_query();
    }

    /**
     * Funcion consultaid
     * Consultar el id del ultimo ingreso para poder realizar el insert de los usuariosrecordatorio
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function consultarid($data) {

        $this->db->select('RECORDATORIO.CODRECORDATORIO');
        $this->db->where('CODACTIVIDAD', $data['CODACTIVIDAD']);
        $this->db->where('CODPLANTILLA', $data['CODPLANTILLA']);
        $this->db->where('ACTIVO', $data['ACTIVO']);
        $this->db->where('RECORDATORIO_CORREO', $data['RECORDATORIO_CORREO']);
        $this->db->where('RECORDATORIO_PANTALLA', $data['RECORDATORIO_PANTALLA']);
        $this->db->where('TIEMPO_MEDIDA', $data['TIEMPO_MEDIDA']);
        $this->db->where('TIEMPO_NUM', $data['TIEMPO_NUM']);
        $this->db->where('NOMBRE_RECORDATORIO', utf8_decode($data['NOMBRE_RECORDATORIO']));
        $this->db->where('TIPO_RECORDATORIO', $data['TIPO_RECORDATORIO']);
        $dato = $this->db->get('RECORDATORIO');

//        echo $this->db->last_query();die;
        $idrecordatorio = $dato->result_array[0];
        return $idrecordatorio;
    }

    /**
     * Funcion delete_noti
     * Actualiza el estado de ACTIVO a 'N' para no mostrarlo en las consultas de los recordatorios
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    public function delete_noti($CODRECORDATORIO) {
        $this->db->set('ACTIVO', 'N');
        $this->db->where('CODRECORDATORIO', $CODRECORDATORIO);
        $this->db->update('RECORDATORIO');
        //echo $this->db->last_query();
    }

    /**
     * Funcion update_noti
     * Actuliza $CODRECORDATORIO, $ACTIVO, $CODPLANTILLA, $TIEMPO_MEDIDA, $TIEMPO_NUM de la base de datos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    public function update_noti($CODRECORDATORIO, $ACTIVO, $CODPLANTILLA, $TIEMPO_MEDIDA, $TIEMPO_NUM, $NOMBRE_RECORDATORIO) {
        $this->db->set('ACTIVO', $ACTIVO);
        $this->db->set('CODPLANTILLA', $CODPLANTILLA);
        $this->db->set('TIEMPO_MEDIDA', $TIEMPO_MEDIDA);
        $this->db->set('TIEMPO_NUM', $TIEMPO_NUM);
        $this->db->set('NOMBRE_RECORDATORIO', $NOMBRE_RECORDATORIO);
        $this->db->where('CODRECORDATORIO', $CODRECORDATORIO);
        $this->db->update('RECORDATORIO');
//        echo $this->db->last_query();
    }

}
