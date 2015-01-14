<?php

// Responsable: Leonardo Molina
class Autopruebas extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha_helper', 'template_helper', 'date', 'traza_fecha', 'template'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->library(array('datatables', 'libupload', 'form_validation', 'tcpdf/tcpdf.php'));
        $this->load->model('autopruebas_model');
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/validationEngine.jquery.css' => 'screen', 'css/bootstrap.submodal.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/validCampoFranz.js', 'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js', 'js/jquery.dataTables.defaults.js', 'js/tinymce/tinymce.jquery.min.js', 'js/jquery.confirm.js', 'js/bootstrap.submodal.js');
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
    }

    function index() {
        $this->manage();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('autopruebas/index') || $this->ion_auth->in_menu('autopruebas/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                //template data
                $this->template->set('title', 'Autos Pruebas');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['info'] = 1;
                $this->template->load($this->template_file, 'autopruebas/autopruebas_list', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function coordinador() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('autopruebas/index') || $this->ion_auth->in_menu('autopruebas/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                //template data
                $this->template->set('title', 'Autos Pruebas');
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['info'] = 2;
                $this->template->load($this->template_file, 'autopruebas/autopruebas_list2', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getData() {
        if ($this->input->get('accion') == 1) {
            $this->db->where('GC.COD_TIPO_RESPUESTA NOT IN (1324,97,100) AND 1=', "1", false);
        } elseif ($this->input->get('accion') == 2) {
            $this->db->where('GC.COD_TIPO_RESPUESTA IN (1324,97,100) AND 1=',"1" , false);
        }
        $data['registros'] = $this->autopruebas_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'));
        define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug  
        $TOTAL = $this->autopruebas_model->totalData($this->input->post('sSearch'));

        echo json_encode(array('aaData' => $data['registros'],
            'iTotalRecords' => $TOTAL,
            'iTotalDisplayRecords' => $TOTAL,
            'sEcho' => $this->input->post('sEcho')));
    }

    function aprobar_cor() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->data['traza'] = $this->autopruebas_model->historico_obser2($post);
        $this->load->view('autopruebas/aprobar', $this->data);
    }
    function aprobar_cor2() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->data['traza'] = $this->autopruebas_model->historico_obser2($post);
        $this->load->view('autopruebas/aprobar2', $this->data);
    }
    function aprobar_cor3() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->data['traza'] = $this->autopruebas_model->historico_obser3($post);
        $this->load->view('autopruebas/aprobar3', $this->data);
    }

    function rechazo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->data['traza'] = $this->autopruebas_model->historico_obser($post);
        $this->load->view('autopruebas/rechazo', $this->data);
    }
    function rechazo2() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->data['traza'] = $this->autopruebas_model->historico_obser2($post);
        $this->load->view('autopruebas/rechazo2', $this->data);
    }
    //rechazo cuando el coordinado devuelve el documento a correcciones // traslado de alegatos
    function rechazo3() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->data['traza'] = $this->autopruebas_model->historico_obser3($post);
        $this->load->view('autopruebas/rechazo3', $this->data);
    }

    function aprobar_documento_auto() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/';
        $html = $this->input->post('informacion');
        $cod_respu = $this->input->post('cod_respu');
        if ($cod_respu != 1327) {
            $nombreDoc = create_template($route_template, $html);
        } else {
            $nombreDoc = "";
        }
        $codgest = trazar(51, $this->input->post('cod_respu'), $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad2($post, $codgest['COD_GESTION_COBRO'], $nombreDoc);
    }
    function aprobar_documento_auto2() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/';
        $html = $this->input->post('informacion');
        $cod_respu = $this->input->post('cod_respu');
        if ($cod_respu != 99) {
            $nombreDoc = create_template($route_template, $html);
        } else {
            $nombreDoc = "";
        }
        $codgest = trazar(58, $this->input->post('cod_respu'), $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad6($post, $codgest['COD_GESTION_COBRO'], $nombreDoc);
    }
    function aprobar_documento_auto3() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/';
        $html = $this->input->post('informacion');
        $cod_respu = $this->input->post('cod_respu');
        if ($cod_respu != 1341) {
            $nombreDoc = create_template($route_template, $html);
            echo $nombreDoc ;
        } else {
            $nombreDoc = "";
        }
        
        $codgest = trazar(61, $this->input->post('cod_respu'), $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad9($post, $codgest['COD_GESTION_COBRO'], $nombreDoc);
    }

    function guardar_trazabilidad() {
        $post = $this->input->post();
        $codgest = trazar(51, 1326, $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad($post, $codgest['COD_GESTION_COBRO']);
    }
    function guardar_trazabilidad2() {
        $post = $this->input->post();
        $codgest = trazar(59, 98, $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad7($post, $codgest['COD_GESTION_COBRO']);
    }
    function guardar_trazabilidad3() {
        $post = $this->input->post();
        $codgest = trazar(61,1340, $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad8($post, $codgest['COD_GESTION_COBRO']);
    }

    function respAuto() {
        $this->data['codauto'] = $this->uri->segment(3);
        $this->data['nit'] = $this->uri->segment(4);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->data['respauto'] = $this->autopruebas_model->getAuto($this->uri->segment(3));
        $this->data['tipoDocumento'] = $this->autopruebas_model->getTipoDocumento();
        $this->load->view('autopruebas/autocargos_resp', $this->data);
    }
    // guarda el documento generado de la citacion
    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/';
        $ar = fopen($route_template  . $post['nombre'] . ".txt", "w+") or die();
        if (!empty($post['informacion']))
            fputs($ar, base64_encode($post['informacion']));
        else
            fputs($ar, base64_encode($post['infor']));
        fclose($ar);
    }
    function tipo_documento() {
        $post = $this->input->post();
        if (!empty($post['id'])) {
            $this->data['post'] = $post;
            $this->data['consulta'] = $this->autopruebas_model->consultar($post['id']);
            $this->data['consulta'] = $this->data['consulta'][0];
        }
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $reemplazo = array();
        $reemplazo['regional'] = $this->data['consulta']['NOMBRE_REGIONAL'];
        $reemplazo['cod_resolucion'] = $this->data['consulta']['NUMERO_RESOLUCION'];
        $reemplazo['fecha_resolucion'] = $this->data['consulta']['FECHA_CREACION'];
        $reemplazo['director'] = $this->data['consulta']['NOMBRE_DIRECTOR'];
        $reemplazo['nom_empresa'] = $this->data['consulta']['NOMBRE_EMPLEADOR'];
        $reemplazo['representante'] = $this->data['consulta']['REPRESENTANTE_LEGAL'];
        $reemplazo['nit'] = $this->data['consulta']['NITEMPRESA'];
        $reemplazo['domicilio'] = $this->data['consulta']['DIRECCION'];
        $reemplazo['ciudad'] = "";
        $reemplazo['fecha'] = date("d/m/Y");
        $reemplazo['reviso'] = $this->data['info_user'];

        $informacion = "";

        switch ($post['dato']) {
            case 1://Citación Notificación Personal
                switch ($post['concepto']) {
                    case 1: //Aportes Parafiscales 
                        $informacion.="2 :: " . $this->load->view('plantillas/citacion_para_notificacion_personal_aportes_parafiscales', $this->data);
                        break;
                    case 2://Citación Por Aviso FIC 
                        $informacion.="3 :: " . $this->load->view('plantillas/citacion_para_notificacion_personal_FIC.php', $this->data);
                        break;
                    case 3://Contratos de Aprendizaje
                        $informacion.="10 :: " . $this->load->view('plantillas/citacion_de_notificacion_personal_contratos_de_aprendizaje', $this->data);
                        break;
                }
                break;
            case 2://Generar Citación Notificación Por Correo Electrónico
                $informacion.="9 :: " . $this->load->view('plantillas/citacion_para_notificacion_por_correo_electronico', $this->data);
                break;
            case 3://Generar Citación Notificación Por Aviso y página web
                switch ($post['concepto']) {
                    case 1: //Aportes Parafiscales 
                        $informacion.="8 :: " . $this->load->view('plantillas/citacion_para_notificacion_por_aviso_y_pagina_web_Aportes_Parafiscales', $this->data);
                        $informacion.=$this->load->view('plantillas/constancia_de_notificacion_por_aviso_y_pagina_web_aportes_parafiscales', $this->data);
                        break;
                    case 2://Citación Por Aviso FIC 
                        $informacion.="8 :: " . $this->load->view('plantillas/citacion_para_notificacion_por_Aviso_y_pagina_web_FIC', $this->data);
                        $informacion.=$this->load->view('plantillas/constancia_de_citacion_por_aviso_y_pagina_web_FIC', $this->data);
                        break;
                    case 3://Contratos de Aprendizaje
                        $informacion.="10 :: " . $this->load->view('plantillas/citacion_de_notificacion_personal_contratos_de_aprendizaje.php', $this->data);
                        break;
                }
                break;
            default :
                $txt = $this->autopruebas_model->plantilla($post['dato']);
                if (!empty($txt)) {
                    $texto = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
                    $informacion.="14 :: " . $texto;
                }
//                $informacion.="10 :: " .
        }
        echo $informacion;
    }
    function guardar_citacion() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $post['post']=$this->data['post'];
        if ($post['post']['radio'] == "33") {
            $cod_cita = "1";
            $cod_siguiente = "313";
            $cod_respuesta = "54";
        } else if ($post['post']['radio'] == "34") {
            $cod_cita = "2";
            $cod_siguiente = "313";
            $cod_respuesta = "35";
        } else if ($post['post']['radio'] == "35") {
            $cod_cita = "3";
            $cod_siguiente = "313";
            $cod_respuesta = "31";
        } else if ($post['post']['radio'] == "310") {
            $cod_cita = "3";
            $cod_siguiente = "313";
            $cod_respuesta = "31";
        } else if ($post['post']['radio'] == "311") {
            $cod_cita = "3";
            $cod_siguiente = "313";
            $cod_respuesta = "31";
        } else if ($post['post']['radio'] == "411") {
            $cod_cita = "3";
            $cod_siguiente = "411";
            $cod_respuesta = "1083";
        } else if ($post['post']['radio'] == "414") {
            $cod_cita = "3";
            $cod_siguiente = "415";
            $cod_respuesta = "884";
        } else if ($post['post']['radio'] == "415") {
            $cod_cita = "3";
            $cod_siguiente = "416";
            $cod_respuesta = "884";
        }
        $codgest=trazar($cod_siguiente, $cod_respuesta, $post['post']['cod_fis'], $post['post']['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->autopruebas_model->guardar_citacion($this->data,$cod_cita);
        $this->autopruebas_model->trazabilidad4($post['post'],$codgest['COD_GESTION_COBRO']);
    }
    function update_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['file'] = $file;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->autopruebas_model->update_citacion($this->data);
        $codgest=trazar($post['cod_siguiente'], $post['cod_respuesta'], $post['cod_fis'], $post['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->autopruebas_model->trazabilidad4($post,$codgest['COD_GESTION_COBRO']);
            header('Location:' . base_url('index.php/autopruebas/') . '');
    }
    function trazabilidad_total(){
        $post = $this->input->post();
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/';
        $nombreDoc = create_template($route_template, $post['informacion']);
        $codgest=trazar(58,97, $post['cod_fis'], $post['nit'], $cambiarGestionActual = 'S', $comentarios = "");
        $this->autopruebas_model->trazabilidad5($post,$codgest['COD_GESTION_COBRO'],$nombreDoc);
    }
            function ver_documentos() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['documentacion_resolcuion'] = $this->autopruebas_model->documentacion_resolcuion($this->data['post']['id']);
        $this->data['documentacion_citacion'] = $this->autopruebas_model->documentacion_citacion($this->data['post']['id']);
//        $this->data['documentacion_recurso'] = $this->autopruebas_model->documentacion_recurso($this->data['post']['id']);
        $this->data['correspondencia'] = $this->autopruebas_model->correspondencia($this->data['post']['id']);
        $this->load->view('autopruebas/ver_documentos', $this->data);
    }
    function desicion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['motivo'] = $this->autopruebas_model->motivodevolucion();
        $this->data['consulta'] = $this->autopruebas_model->consultar($post['id']);
        $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->data['consulta'][0]['NOMBREMUNICIPIO'];
        $this->load->view('autopruebas/gestionar', $this->data);
    }
    function correspondencia_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['motivo'] = $this->autopruebas_model->correspondencia_citacion($file, $this->data);
            header('Location:' . base_url('index.php/autopruebas/') . '');
    }
    function subir_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['citacion'] = $this->autopruebas_model->citacion($post['num_citacion']);
        $this->load->view('autopruebas/subir_citacion', $this->data);
    }

    function bloqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('autopruebas/bloquear', $this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }
    function desbloquear() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->autopruebas_model->desbloquear($this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }
    function siguiente_codigo_del_bolqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->autopruebas_model->consultar($this->data['post']['id']);
        $this->autopruebas_model->siguiente_codigo_del_bolqueo($this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }
    function bloqueo_por_time() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('autopruebas/bloqueo_por_time', $this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }
    function realizar_acta() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['motivo'] = $this->autopruebas_model->motivodevolucion();
        $this->data['consulta'] = $this->autopruebas_model->consultar($post['id']);
//        $this->data['ciudad'] = $this->carteracobro_model->municipio($this->data['consulta'][0]['COD_CIUDAD']);
        $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->data['consulta'][0]['NOMBREMUNICIPIO'];
        $this->load->view('autopruebas/gestionar_acta', $this->data);
    }
    function citacion() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['consulta'] = $this->autopruebas_model->consultar($this->data['post']['id']);
        $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->data['consulta'][0]['NOMBREMUNICIPIO'];
        $this->load->view('autopruebas/citacion', $this->data);
    }
    function correspondencia_citacion_gestion() {
        $post = $this->input->post();
        $this->guardar_archivo();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['motivo'] = $this->autopruebas_model->correspondencia_citacion_gestion($this->data);
        //header('Location:' . base_url('index.php/resolucion/') . '');
    }

    function subir_doc() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->load->view('autopruebas/subir_doc', $this->data);
    }
    function subir_doc1() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/' . $post['nom_documento'];
        $this->data['texto'] = template_tags($route_template, $array = array());
        $this->load->view('autopruebas/subir_doc', $this->data);
    }

    function subir_acta() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->load->view('autopruebas/subir_acta', $this->data);
    }
    function subir_acta_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['file'] = $file;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->autopruebas_model->subir_acta_archivo($this->data);
        header('Location:' . base_url('index.php/autopruebas') . '');
    }
    function subir_acta_archivo2() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $codgest = trazar(51, 1328, $this->input->post('cod_fis'), $this->input->post('nit'), "S");
        $this->autopruebas_model->trazabilidad3($post, $codgest['COD_GESTION_COBRO'], $file['upload_data']['file_name']);
        header('Location:' . base_url('index.php/autopruebas/') . '');
    }

    private function do_upload() {
        $post = $this->input->post();
        //echo $post['id']; 

        $route_template = 'uploads/fiscalizaciones/' . $post['cod_fis'] . '/autocargos/';

        if (!file_exists($route_template)) {
            if (!mkdir($route_template, 0777, true)) {
                
            }
        }
        $config['upload_path'] = $route_template;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '20480';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

    function newTraslado() {
        $this->data['respauto'] = $this->autopruebas_model->getAuto($this->uri->segment(3));
        $this->data['empresa'] = $this->autopruebas_model->getEmpresa($this->uri->segment(4));
        $this->data['codauto'] = $this->uri->segment(3);
        $this->data['nit'] = $this->uri->segment(4);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('autopruebas/trasladoalegatos', $this->data);
    }

    function pruebasAuto() {
        $this->data['respauto'] = $this->autopruebas_model->getDocumentosRespAuto($this->uri->segment(3));
        $this->data['empresa'] = $this->autopruebas_model->getEmpresa($this->uri->segment(4));
        $this->data['pruebas'] = $this->autopruebas_model->getDocumentosPruebas($this->uri->segment(3));
        $this->data['codauto'] = $this->uri->segment(3);
        $this->data['nit'] = $this->uri->segment(4);
        $this->data['accion'] = $this->uri->segment(5);
        $this->data['cod_fis'] = $this->uri->segment(6);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('autopruebas/autopruebas_resp', $this->data);
    }

    function recibirPruebas() {
        $this->data['respauto'] = $this->autopruebas_model->getDocumentosRespAuto($this->uri->segment(3));
        $this->data['empresa'] = $this->autopruebas_model->getEmpresa($this->uri->segment(4));
        $this->data['pruebas'] = $this->autopruebas_model->getDocumentosPruebas($this->uri->segment(3));
        $this->data['autogestion'] = $this->autopruebas_model->getAutoGestion($this->uri->segment(3));
        $this->data['codauto'] = $this->uri->segment(3);
        $this->data['nit'] = $this->uri->segment(4);
        $this->data['dia'] = date('d');
        $this->data['mes'] = date('m');
        $this->data['ano'] = date('Y');
        $this->load->view('autopruebas/recibirpruebastransalegatos_resp', $this->data);
    }

    function deleteFile() {
        $file = $this->input->post('file');
        $fisc = $this->input->post('fisc');
        $dir = 'fiscalizaciones/' . $fisc . '/autocargos';
        $query = $this->autopruebas_model->deleteFile($file);
        if ($query)
            $this->libupload->delete($dir, $file);
    }

    function cargaRespuesta() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('autopruebas/index') || $this->ion_auth->in_menu('autopruebas/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {

                $auto = $this->autopruebas_model->getAuto($this->input->post('numauto'));

                if ($this->input->post('resp1') == 92) {// si Contesta
                    $this->form_validation->set_rules('resp', 'Respuesta', 'required');
                    $this->form_validation->set_rules('fecha', 'Fecha', 'required');
                    $this->form_validation->set_rules('nis', 'Nis', 'required');
                    $this->form_validation->set_rules('cargo', 'Cargo', 'required');
                    $this->form_validation->set_rules('radicado', 'No. Radicado', 'required');
                    $this->form_validation->set_rules('npresenta', 'Nombre de quien presenta', 'required');
                    $this->form_validation->set_rules('tdoc', 'Tipo de documento', 'required');
                    $this->form_validation->set_rules('ndoc', 'Número de documento', 'required');


                    for ($i = 0; $i < count($_FILES); $i++) {
                        if (empty($_FILES['archivo' . $i]['name'])) {
                            $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                        }
                    }

                    if ($this->form_validation->run() == false && validation_errors()) {
                        $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    } else {

                        $route_template = 'uploads/fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos/';
                        $nombre_subcarpeta = 'fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos';

                        if (!is_dir($route_template)) {
                            @mkdir($route_template, 0777);
                        } else {
                            
                        }

                        for ($i = 0; $i < count($_FILES); $i++) {
                            //no le hacemos caso a los vacios
                            if (!empty($_FILES['archivo' . $i]['name'])) {
                                $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|doc|jpg|jpeg', 9999, 9999, 0);
                            }
                        }
                        //var_dump($respuesta);die;
                        $data['NOMBRE_DOCU_RESPUESTA'] = $respuesta[0]['data']['file_name'];
                        $data2['SOLICITUD_PRUEBAS'] = 'S';






                        if (count($_FILES) >= 1) {// Aqui se agregan las pruebas en la tabla recursoDocumentos
                            $data3 = array(
                                'NUM_AUTOGENERADO' => $this->input->post('numauto'),
                                'NUM_RADICADO' => $this->input->post('radicado'),
                                'NOMBRE_PERSONA_PRESENTA' => $this->input->post('npresenta'),
                                'TIPO_DOCUMENTO' => $this->input->post('tdoc'),
                                'NUM_DOCUMENTO' => $this->input->post('ndoc')
                            );
                            $numrecurso = $this->autopruebas_model->add_recurso($data3);
                            for ($i = 0; $i < count($_FILES); $i++) {

                                $data4 = array(
                                    'NUM_RECURSO' => $numrecurso['NUM_RECURSO'],
                                    'NOMBRE_DOCUMENTO' => $respuesta[$i]['data']['file_name'],
                                );
                                $query = $this->autopruebas_model->add_docPruebas($data4);
                            }
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe agregar al menos una prueba.</div>');
                            redirect(base_url() . 'index.php/autopruebas');
                        }

                        //56 -> RespuestaAutoCargos
                        //92 -> Contestada
                        //93 -> NO Contestada
                        //57 -> ContestarAutoCargos
                        //94 -> Esta de acuerdo
                        //95 -> No esta de acuerdo
                        //96 -> Con pruebas
                        //58 -> ContestarAutoCargos y esta de acuerdo
                        //59 -> ContestarAutoCargos y NO esta de acuerdo

                        $codgest = trazar(57, $this->input->post('resp'), $auto->COD_FISCALIZACION, $auto->CODEMPRESA, "S");
                        $data += array(
                            'NUM_AUTOGENERADO' => $this->input->post('numauto'),
                            'COD_TIPORESPUESTA_AUTO' => $codgest['COD_GESTION_COBRO'],
                            'NRO_RADICADO' => $this->input->post('radicado'),
                            'NIS' => $this->input->post('nis'),
                            'CARGO' => $this->input->post('cargo')
                        );

                        $dataf = array(
                            'FECHA_RESPUESTA' => $this->input->post('fecha'));
                        $data2 += array(
                            'COD_GESTIONCOBRO' => $codgest['COD_GESTION_COBRO']);

                        $query = $this->autopruebas_model->add_respAuto($data, $dataf);
                        $query2 = $this->autopruebas_model->update_Auto($data2, $this->input->post('numauto'));

                        if ($query == TRUE && $query2 == TRUE) {

                            $mensaje = "Se ha recibido respuesta <br> Auto de cargos número: " . $auto->NUM_AUTOGENERADO . " <br> Fiscalizacion número: " .
                                    $auto->COD_FISCALIZACION . " <br> Empresa con nit: " . $auto->CODEMPRESA;
                            enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha generado la respuesta del auto con éxito.</div>');
                            redirect(base_url() . 'index.php/autopruebas');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                            redirect(base_url() . 'index.php/autopruebas');
                        }
                    }
                }// end Contesta
                else { // No contesta, traslado alegatos
                    $codgest = trazar(61, 100, $auto->COD_FISCALIZACION, $auto->CODEMPRESA, "S");
                    $data = array(
                        'TRASLADO_ALEGATO' => 'S',
                        'COD_GESTIONCOBRO' => $codgest['COD_GESTION_COBRO']
                    );
                    $query = $this->autopruebas_model->update_Auto($data, $this->input->post('numauto'));

                    if ($query == TRUE) {
                        $mensaje = "Se ha trasladado a alegatos <br> Auto de cargos número: " + $auto->NUM_AUTOGENERADO + " <br> Fiscalizacion número: " +
                                $auto->COD_FISCALIZACION + "  <br> Empresa con nit: " + $auto->CODEMPRESA;
                        enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);
                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El auto se ha transladado a alegatos con éxito.</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function generarAutoPruebas() {
        $html = $this->input->post('textinfo2');
        $fisc = $this->input->post('cfisc');
        $nit = $this->input->post('nit');
        $autoCargos = $this->input->post('nAuto');
        $tipoAuto = '22';
        $estado = '1'; //AUTO generado
        $tipoProceso = '1'; //???????
        $creadoPor = $this->ion_auth->user()->row()->IDUSUARIO;
        $asignadoA = $this->ion_auth->user()->row()->IDUSUARIO;
        // 62 -> Generar Auto de pruebas
        // 191 -> Auto de pruebas Generado
        $codgest = trazar(62, 101, $fisc, $nit, "S");
        $codGestion = $codgest['COD_GESTION_COBRO'];
        $route_template = 'uploads/fiscalizaciones/' . $fisc . '/autocargos/';
        $nombreDoc = create_template($route_template, $html);
        $this->autopruebas_model->saveAuto($tipoAuto, $fisc, $estado, $tipoProceso, $creadoPor, $asignadoA, $codGestion, $nombreDoc);

        $query = $this->autopruebas_model->updateStateAuto($autoCargos, $codGestion);

        if ($query == TRUE) {
            $mensaje = "Se ha generado auto de pruebas <br> Fiscalizacion número: " .
                    $fisc . " <br> Empresa con nit: " . $nit;
            enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto de pruebas se ha cargado con éxito.</div>');
            redirect(base_url() . 'index.php/autopruebas');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
            redirect(base_url() . 'index.php/autopruebas');
        }
    }

    function generarAlegatos() {

        $post = $this->input->post();
        $fisc = $this->input->post('cfisc');
        $textinfo= $this->input->post('textinfo');
        $nit = $this->input->post('nit');
        $autoCargos = $this->input->post('nAuto');
        $route_template = 'uploads/fiscalizaciones/' . $fisc . '/autocargos/';
        $nombreDoc = create_template($route_template, $textinfo);
//        die();
        // 61  -> No contestar auto
        // 100 -> Proyectado translado alegato
        $codgest = trazar(61, 100, $fisc, $nit, "S");
        $codGestion = $codgest['COD_GESTION_COBRO'];

        $data = array(
            'TRASLADO_ALEGATO' => 'S',
            'COD_GESTIONCOBRO' => $codGestion,
            'DOCUMENTOS_ALEGATOS' => $nombreDoc,
        );
        $query = $this->autopruebas_model->update_Auto($data, $autoCargos);

        if ($query == TRUE) {
            $mensaje = "Se ha Trasladado a alegatos <br> Auto número " . $autoCargos . " <br> Fiscalizacion número" .
                    $fisc . " <br> Empresa con nit: " . $nit;
            enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto de pruebas se ha cargado con éxito.</div>');
            redirect(base_url() . 'index.php/autopruebas');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
            redirect(base_url() . 'index.php/autopruebas');
        }
    }

    function addPruebasFirm() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('autopruebas/index') || $this->ion_auth->in_menu('autopruebas/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $auto = $this->autopruebas_model->getAuto($this->input->post('nAuto2'));
                for ($i = 0; $i < count($_FILES); $i++) {
                    if (empty($_FILES['archivo' . $i]['name'])) {
                        $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                    }
                }

                if ($this->form_validation->run() == false && validation_errors()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
                    redirect(base_url() . 'index.php/autopruebas');
                } else {

                    $route_template = 'uploads/fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos/';
                    $nombre_subcarpeta = 'fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos';

                    if (!is_dir($route_template)) {
                        @mkdir($route_template, 0777);
                    } else {
                        
                    }

                    for ($i = 0; $i < count($_FILES); $i++) {
                        //no le hacemos caso a los vacios
                        if (!empty($_FILES['archivo' . $i]['name'])) {
                            $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|jpg|jpeg', 9999, 9999, 0);
                        }
                    }
                    $data['NOMBRE_DOC_FIRMADO'] = $respuesta[0]['data']['file_name'];
                    $data['DOCUMENTO_RADICADO'] = $respuesta[1]['data']['file_name'];
                    $data['COLILLA_RADICADO'] = $respuesta[2]['data']['file_name'];

                    $codgest = trazar(63, 102, $auto->COD_FISCALIZACION, $auto->CODEMPRESA, "S");
                    $codGestion = $codgest['COD_GESTION_COBRO'];
                    $query = $this->autopruebas_model->updateAPruebaFirm($this->input->post('nAuto2'), $codGestion, $data);

                    if ($query == TRUE) {
                        $mensaje = "Se ha cargado el documento firmado <br> Auto de pruebas " . $auto->NUM_AUTOGENERADO . " <br> Fiscalizacion número: " .
                                $auto->COD_FISCALIZACION . " <br> Empresa con nit: " . $auto->CODEMPRESA;
                        enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Auto de pruebas se ha cargado con éxito.</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function recibirPruebaytraslado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('autopruebas/index') || $this->ion_auth->in_menu('autopruebas/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $auto = $this->autopruebas_model->getAuto($this->input->post('nAuto'));

                if ($this->input->post('datopror') == 'pro') {
                    $this->form_validation->set_rules('datopror', 'Termino Prórroga', 'required');
                }

                if ($this->input->post('resp') == 1) {
                    for ($i = 0; $i < count($_FILES); $i++) {
                        if (empty($_FILES['archivo' . $i]['name'])) {
                            $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                        }
                    }
                }
                if ($this->form_validation->run() == false && validation_errors()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
                    redirect(base_url() . 'index.php/autopruebas');
                } else {
                    if ($this->input->post('resp') == 0) {// No aporto pruebas
                        if ($this->input->post('datopror') == 'pro') {
                            echo $this->input->post('datopror');

                            $codgest = trazar(64, 891, $auto->COD_FISCALIZACION, $auto->CODEMPRESA, "S");
                            $codGestion = $codgest['COD_GESTION_COBRO'];
                            $termino = $this->input->post('pror');

                            $data = array(
                                'COD_GESTIONCOBRO' => $codGestion,
                                'SOLICITUD_PRORROGA' => 'S',
                                'TERMINO_PRORROGA' => $termino,
                            );

                            $query = $this->autopruebas_model->updateProrrogaAuto($data, $auto->NUM_AUTOGENERADO);

                            if ($query == TRUE) {

                                $mensaje = "Se asigna prorroga <br> Auto número: " . $auto->NUM_AUTOGENERADO . " <br> Fiscalizacion número: " .
                                        $auto->COD_FISCALIZACION . " <br> Empresa con nit: " . $auto->CODEMPRESA . " <br> Por el término de " . $termino . " días.";
                                enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La prórroga se ha cargado con éxito.</div>');
                                redirect(base_url() . 'index.php/autopruebas');
                            } else {
                                $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                                redirect(base_url() . 'index.php/autopruebas');
                            }
                        } else {

                            $codgest = trazar(64, 105, $auto->COD_FISCALIZACION, $auto->CODEMPRESA, "S");
                            $codGestion = $codgest['COD_GESTION_COBRO'];
                            $query = $this->autopruebas_model->updateStateAuto($auto->NUM_AUTOGENERADO, $codGestion);

                            if ($query == TRUE) {

                                $mensaje = "No se aporto pruebas <br> Auto número: " . $auto->NUM_AUTOGENERADO . " <br> Fiscalizacion número: " .
                                        $auto->COD_FISCALIZACION . " <br> Empresa con nit: " . $auto->CODEMPRESA;
                                enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

                                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La respuesta se ha cargado con éxito.</div>');
                                redirect(base_url() . 'index.php/autopruebas');
                            } else {
                                $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                                redirect(base_url() . 'index.php/autopruebas');
                            }
                        }
                    } else {

                        $nombre_subcarpeta = 'fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos';
                        if (!is_dir($nombre_subcarpeta)) {
                            @mkdir($nombre_subcarpeta, 0777);
                        } else {
                            
                        }
                        $numrecurso = $this->autopruebas_model->getRecurso($auto->NUM_AUTOGENERADO);

                        for ($i = 0; $i < count($_FILES); $i++) {
                            //no le hacemos caso a los vacios
                            if (!empty($_FILES['archivo' . $i]['name'])) {
                                $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|jpg|jpeg', 9999, 9999, 0);
                                $data4 = array(
                                    'NUM_RECURSO' => $numrecurso->NUM_RECURSO,
                                    'NOMBRE_DOCUMENTO' => $respuesta[$i]['data']['file_name'],
                                );
                                $query = $this->autopruebas_model->add_docPruebas($data4);
                            }
                        }
                        // 66  Presentar pruebas
                        // 107 Pruebas presentadas
                        $codgest = trazar(64, 104, $auto->COD_FISCALIZACION, $auto->CODEMPRESA, "S");
                        $codGestion = $codgest['COD_GESTION_COBRO'];
                        $query = $this->autopruebas_model->updateStateAuto($auto->NUM_AUTOGENERADO, $codGestion);

                        if ($query == TRUE) {

                            $mensaje = "Se han aportado pruebas  <br> Auto de pruebas " . $auto->NUM_AUTOGENERADO . " <br> Fiscalizacion número: " .
                                    $auto->COD_FISCALIZACION . " <br> Empresa con nit: " . $auto->CODEMPRESA;
                            enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

                            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>La respuesta "Con pruebas" se ha cargado con éxito.</div>');
                            redirect(base_url() . 'index.php/autopruebas');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                            redirect(base_url() . 'index.php/autopruebas');
                        }
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function enviarAlegatos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('autopruebas/index') || $this->ion_auth->in_menu('autopruebas/coordinador') || $this->ion_auth->in_group('Abogados relaciones corporativas ') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')) {
                $fisc = $this->input->post('cfisc');
                $nit = $this->input->post('nit');
                $autoCargos = $this->input->post('nAuto');
                $auto = $this->autopruebas_model->getAuto($autoCargos);

                for ($i = 0; $i < count($_FILES); $i++) {
                    if (empty($_FILES['archivo' . $i]['name'])) {
                        $this->form_validation->set_rules('archivo' . $i, 'Documento', 'required');
                    }
                }

                if ($this->form_validation->run() == false && validation_errors()) {
                    $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>ERROR: ' . validation_errors() . '</div>');
                    redirect(base_url() . 'index.php/autopruebas');
                } else {
                    $route_template = 'uploads/fiscalizaciones/' . $auto->COD_FISCALIZACION . '/';
                    $nombre_subcarpeta = 'fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos';

                    if (!is_dir($route_template)) {
                        @mkdir($route_template, 0777);
                    } else {
                        
                    }

                    $route_template = 'uploads/fiscalizaciones/' . $auto->COD_FISCALIZACION . '/autocargos/';
                    if (!is_dir($route_template)) {
                        @mkdir($route_template, 0777);
                    } else {
                        
                    }

                    $numrecurso = $this->autopruebas_model->getRecurso($auto->NUM_AUTOGENERADO);
                    for ($i = 0; $i < count($_FILES); $i++) {
                        //no le hacemos caso a los vacios
                        if (!empty($_FILES['archivo' . $i]['name'])) {
                            $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|jpg|jpeg', 9999, 9999, 0);
                        }
                    }

                    // 262 -> Alegatos
                    // 770 -> translado alegato
                    $codgest = trazar(262, 770, $fisc, $nit, "S");
                    $codGestion = $codgest['COD_GESTION_COBRO'];


                    $data4 = array(
                        'NOMBRE_DOCUMENTO' => $respuesta[0]['data']['file_name'],
                    );

                    $query = $this->autopruebas_model->updateAlegatoAuto($data4, $autoCargos, $numrecurso->NUM_RECURSO, $codGestion);
                    if ($query == TRUE) {
                        $mensaje = "Se envia a alegatos <br> Auto de pruebas " . $auto->NUM_AUTOGENERADO . " <br> Fiscalizacion número: " .
                                $auto->COD_FISCALIZACION . " <br> Empresa con nit: " . $auto->CODEMPRESA;
                        enviarcorreosena($this->ion_auth->user()->row()->EMAIL, $mensaje);

                        $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Se ha trasladado con éxito.</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Error en la Base de Datos.</div>');
                        redirect(base_url() . 'index.php/autopruebas');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pdf() {
        $html = $this->input->post('informacion');
        $nombre_pdf = $this->input->post('nombre');
        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('aqui_tu_nombre');
        //$pdf->SetTitle($sTitulo);
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setLanguageArray($l);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($nombre_pdf . '.pdf', 'D');

        exit();
    }

}
