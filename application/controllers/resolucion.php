<?php

class Resolucion extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha_helper', 'template_helper'));
//        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('aprobar_resoluciones_model');
        $this->load->model('carteracobro_model');
        $this->load->model('numeros_letras_model');
        $this->load->library('tcpdf/tcpdf.php');
        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen',
            
        );
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/ajaxfileupload.js',
            'js/validCampoFranz.js',
            'js/tinymce/tinymce.jquery.min.js'
        );
        $this->data['user'] = $this->ion_auth->user()->row();
        define("REGIONAL_ALTUAL", $this->data['user']->COD_REGIONAL);
        define("ID_USER", $this->data['user']->IDUSUARIO);
        define("NOMBRE_COMPLETO", $this->data['user']->APELLIDOS . " " . $this->data['user']->NOMBRES);
        //RUTAS
        define("RUTA_INI", "./uploads/fiscalizaciones/resolucion");
        define("RUTA_DES", "uploads/fiscalizaciones/resolucion/COD_");
        define("FECHA", "TO_DATE('" . date("d/m/Y H:i:s") . "','DD/MM/RR HH24:mi:ss')");
        define("CITACION1", "29");
        define("SUBIR_DOCUMENTO", "27");
    }

    function index() {
//        $this->manage();
        $this->datos_del_abogado();
    }

    function manage() {
        if ($this->ion_auth->logged_in()) {
//            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('operadorinformacion/manage')) {
            $this->data['user'] = $this->ion_auth->user()->row();
            $this->data['coordinador'] = $permisos1 = $this->aprobar_resoluciones_model->mostrar_coordinador($this->data['user']->IDUSUARIO);
            $this->data['director'] = $permisos2 = $this->aprobar_resoluciones_model->mostrar_director($this->data['user']->IDUSUARIO);
            $this->data['abogado'] = $permisos3 = $this->aprobar_resoluciones_model->mostrar_abogados($this->data['user']->IDUSUARIO);
            if ($permisos1['CEDULA'] != "" && $permisos2['CEDULA'] != "")
                $this->template->load($this->template_file, 'resolucion/resolucion_view', $this->data);
            else if ($permisos1['CEDULA'] != "" && $permisos3['CEDULA'] != "")
                $this->template->load($this->template_file, 'resolucion/resolucion_view', $this->data);
            else if ($permisos2['CEDULA'] != "" && $permisos3['CEDULA'] != "")
                $this->template->load($this->template_file, 'resolucion/resolucion_view', $this->data);
            else if ($permisos1['CEDULA'] != "")
                header('Location:' . base_url('index.php/resolucion/datos_del_coordinador') . '');
            else if ($permisos2['CEDULA'] != "")
                header('Location:' . base_url('index.php/resolucion/datos_del_director') . '');
            else if ($permisos3['CEDULA'] != "")
                header('Location:' . base_url('index.php/resolucion/datos_del_abogado') . '');
            else {
                $this->pertmisos();
            }
//            } else {
//                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
//                redirect(base_url() . 'index.php/inicio');
//            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function citacion_pendientes($id) {
        $datos = $this->aprobar_resoluciones_model->citacion_pendientes($id);
        return $datos;
    }

    function pertmisos() {
        ?><script>alert("Permisos Insuficientes");
                    location.href = "<?php echo base_url('index.php'); ?>"</script><?php
    }

    function datos_del_coordinador() {
        $this->data['titulo'] = "Gesti&oacute;n  del coordinador";
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_coordinador') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
            ) {
                $this->aprobar_resoluciones_model->set_permiso(3);
                $this->aprobar_resoluciones_model->set_array_num(array('23', '314'));
//            $this->aprobar_resoluciones_model->set_array_num(array('23', '29', '33', '34', '35', '36', '37', '38', '39', '40', '30', '47', '48', '49', '54', '84', '85'));
                $this->template->set('title', $this->data['titulo']);
                $this->data['message'] = $this->session->flashdata('message');
                $this->data['user'] = $this->ion_auth->user()->row();
                $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
                $this->aprobar_resoluciones_model->set_iduser($this->data['user']->IDUSUARIO);
                $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar();
                $this->template->load($this->template_file, 'resolucion/aprobar', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datos_del_director() {
         if ($this->ion_auth->logged_in()) {
        if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_director') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('COORDINADOR DE RELACIONES CORPORATIVAS')
        ) {
            $this->data['titulo'] = "Gesti&oacute;n  del Director";
//            $this->aprobar_resoluciones_model->set_array_num(array('29', '33', '34', '35', '36', '37', '38', '39', '40', '30', '47', '48', '49', '54', '84', '85'));
            $this->aprobar_resoluciones_model->set_array_num(array('00'));
            $this->aprobar_resoluciones_model->set_permiso(1);
            $this->template->set('title', $this->data['titulo']);
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['user'] = $this->ion_auth->user()->row();
            $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
            $this->aprobar_resoluciones_model->set_iduser($this->data['user']->IDUSUARIO);
            $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar();
            $this->template->load($this->template_file, 'resolucion/aprobar', $this->data);
             } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function datos_del_abogado() {
        $this->data['titulo'] = "Gesti&oacute;n del Abogado";
        if ($this->ion_auth->logged_in()) {
        if ($this->ion_auth->is_admin() || $this->ion_auth->logged_in() || $this->ion_auth->in_menu('resolucion/datos_del_abogado') || $this->ion_auth->in_menu('resolucion/index') || $this->ion_auth->in_group('Abogados relaciones corporativas ')
        ) {
            $this->template->set('title', $this->data['titulo']);
            $this->aprobar_resoluciones_model->set_permiso(2);
            $this->aprobar_resoluciones_model->set_array_num(array('21', '27', '28', '29', '33', '34', '35', '36', '37', '38', '39', '40', '30', '47', '48', '49', '54', '84', '85', '310', '313', '311', '310', '315','560','561','562','662'));
            $this->data['user'] = $this->ion_auth->user()->row();
            $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
            $this->aprobar_resoluciones_model->set_iduser($this->data['user']->IDUSUARIO);
            $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar();
            $this->template->load($this->template_file, 'resolucion/aprobar', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function resolucion2() {
        $post = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['post'] = $post;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['concepto'] = $this->aprobar_resoluciones_model->conceptosfiscalizacion($post['concepto']);
        $this->data['informacion'] = $this->carteracobro_model->empresa($post['nit']);
        $this->data['ciudad'] = $this->aprobar_resoluciones_model->municipio($this->data['consulta'][0]['COD_CIUDAD']);
        $this->data['elaboro'] = $this->aprobar_resoluciones_model->proyecto($this->data['consulta'][0]['ELABORO']);
        $this->data['comentario'] = $this->aprobar_resoluciones_model->comentario($post['id']); 

        $name_file = RUTA_DES . $post['id'] . "/" . $post['id'] . ".txt";
        $text_file = fopen($name_file, "r");
        $contet_file = fread($text_file, filesize($name_file));
        $this->data['html'] = base64_decode($contet_file);

        $this->load->view('resolucion/contratos_a', $this->data);

        $this->load->view('resolucion/resolucion', $this->data);
    }

    function confirmar_resolucion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $dato = $this->aprobar_resoluciones_model->confirmar_resolucion($post['id']);
        $this->output->set_content_type('application/json')->set_output(json_encode($dato));
    }

    function resolucion_pdf() {
        $post = $this->input->get();
        $this->data['post'] = $post;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['concepto'] = $this->aprobar_resoluciones_model->conceptosfiscalizacion($post['concepto']);
        $this->data['informacion'] = $this->carteracobro_model->empresa($post['nit']);
        $this->data['ciudad'] = $this->carteracobro_model->municipio($this->data['consulta'][0]['COD_CIUDAD']);
        $this->data['comentario'] = $this->aprobar_resoluciones_model->comentario($post['id']);
        $this->data['class'] = 'director';

        $name_file = RUTA_DES . $post['id'] . "/" . $post['id'] . ".txt";
        $text_file = fopen($name_file, "r");
        $contet_file = fread($text_file, filesize($name_file));
        $this->data['html'] = base64_decode($contet_file);

        $this->load->view('resolucion/contratos_a', $this->data);
    }

    function resolucion_guardar() {
        $this->data['informacion'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->resolucion_guardar($this->data['informacion'], $this->data['user']);
    }

    function modificar() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['comentario'] = $this->aprobar_resoluciones_model->comentario($post['id']);
        $this->load->view('resolucion/modificar_resolucion', $this->data);
    }

    function guardar_modificacion() {
        $post = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->guardar_modificacion($post, $this->data['user']);
        $this->guardar_archivo2($post['id']);
    }

    function guardar_archivo2($id) {
        $post = $this->input->post();
        $this->data['post'] = $post;
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $id, 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $id)) {
                if (!mkdir(RUTA_DES . $id, 0777, true)) {
                    
                }
            }
        }
        $ar = fopen(RUTA_DES . $id . "/" . $id . ".txt", "w+") or die();
        fputs($ar, $post['archivo']);
        fclose($ar);
    }

    private function do_upload() {
        $post = $this->input->post();
        //echo $post['id']; 
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $post['id'])) {
                if (!mkdir(RUTA_DES . $post['id'], 0777, true)) {
                    
                }
            }
        }
        $config['upload_path'] = RUTA_DES . $post['id'] . "/";
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

    function guardar_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $ar = fopen(RUTA_DES . $post['id'] . "/" . $post['nombre'] . ".txt", "w+") or die();
        if (!empty($post['informacion']))
            fputs($ar, base64_encode($post['informacion']));
        else
            fputs($ar, base64_encode($post['infor']));
        fclose($ar);
    }

    function subir_archivo() {
        $this->data['post'] = $this->input->post();
        $this->load->view('resolucion/subir_archivo', $this->data);
    }

    function resolucion_imagen() {
        $this->data['post'] = $this->input->post();
        $file = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
//        echo "<pre>";
//        print_r($this->data['post']);
//        print_r($file);
//        echo "</pre>";
        $this->aprobar_resoluciones_model->subir_archivo($this->data['post'], $file, $this->data['user']);
        header("location:" . base_url('index.php/resolucion/datos_del_abogado'));
    }

    function citacion() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($_REQUEST['id']);
//        $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->aprobar_resoluciones_model->municipio($this->data['consulta'][0]['COD_CIUDAD']);
        $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->data['consulta'][0]['NOMBREMUNICIPIO'];
        $this->load->view('resolucion/citacion', $this->data);
    }

    function guardar_citacion() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->guardar_citacion($this->data);
    }

    function bloqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('resolucion/bloquear', $this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }

    function desbloquear() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->desbloquear($this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }

    function bloqueo_por_time() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->load->view('resolucion/bloqueo_por_time', $this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }

    function siguiente_codigo_del_bolqueo() {
        $this->data['post'] = $this->input->post();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($this->data['post']['id']);
        $this->aprobar_resoluciones_model->siguiente_codigo_del_bolqueo($this->data);
//        $this->load->view('resolucion/crono', $this->data);
    }

    function guardar_citacion_favorabilidad() {
        $this->data['post'] = $this->input->post();
        $this->guardar_archivo();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($this->data['post']['id']);
        $this->aprobar_resoluciones_model->guardar_citacion_favorabilidad($this->data);
    }

    function tipo_documento() {
        $post = $this->input->post();
        if (!empty($post['id'])) {
            $this->data['post'] = $post;
            $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
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
                $txt = $this->aprobar_resoluciones_model->plantilla($post['dato']);
                if (!empty($txt)) {
                    $texto = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
                    $informacion.="14 :: " . $texto;
                }
//                $informacion.="10 :: " .
        }
        echo $informacion;
    }

    function desicion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['motivo'] = $this->aprobar_resoluciones_model->motivodevolucion();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['ciudad'] = $this->carteracobro_model->municipio($this->data['consulta'][0]['COD_CIUDAD']);
        $this->load->view('resolucion/gestionar', $this->data);
    }

    function realizar_acta() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['motivo'] = $this->aprobar_resoluciones_model->motivodevolucion();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
//        $this->data['ciudad'] = $this->carteracobro_model->municipio($this->data['consulta'][0]['COD_CIUDAD']);
        $this->data['ciudad']['NOMBREMUNICIPIO'] = $this->data['consulta'][0]['NOMBREMUNICIPIO'];
        $this->load->view('resolucion/gestionar_acta', $this->data);
    }

    function correspondencia_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['motivo'] = $this->aprobar_resoluciones_model->correspondencia_citacion($file, $this->data);
        if ($post['cod_estado'] == 412)
            header('Location:' . base_url('index.php/ejecutoriaactoadmin/') . '');
        else
            header('Location:' . base_url('index.php/resolucion/datos_del_abogado') . '');
    }

    function correspondencia_citacion_gestion() {
        $post = $this->input->post();
        $this->guardar_archivo();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['motivo'] = $this->aprobar_resoluciones_model->correspondencia_citacion_gestion($this->data);
        //header('Location:' . base_url('index.php/resolucion/') . '');
    }

    function pdf($html) {
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
        $pdf->Output('datos.pdf', 'D');
        exit();
    }

    function subir_acta() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->load->view('resolucion/subir_acta', $this->data);
    }

    function subir_acta_recurso() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->load->view('resolucion/subir_acta_recurso', $this->data);
    }

    function subir_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['citacion'] = $this->aprobar_resoluciones_model->citacion($post['num_citacion']);
        $this->load->view('resolucion/subir_citacion', $this->data);
    }

    function gestion_resolucion_aprobada() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $plantilla = $this->aprobar_resoluciones_model->ver_resolucion_recurso($post['id']);
        RUTA_DES . $post['id'] . "/" . $plantilla['NOM_DOCU_RESOL_RECURSO'] . ".txt";
        $texto = template_tags(RUTA_DES . $post['id'] . "/" . $plantilla['NOM_DOCU_RESOL_RECURSO'] . ".txt");
        $this->data['texto'] = base64_decode($texto);
        $this->data['plantilla'] = $plantilla;
        $this->db->where("GESTION", '1');
        $this->data['comentario'] = $this->aprobar_resoluciones_model->comentario($post['id']);
        $this->load->view('resolucion/gestion_resolucion_aprobada', $this->data);
    }

    function resolver_recurso_gestionar2() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['liquidacion'] = $this->aprobar_resoluciones_model->info_liquidacion($this->data['consulta'][0]['COD_FISCALIZACION']);
        $plantilla = $this->aprobar_resoluciones_model->ver_resolucion_recurso($post['id']);
        $this->db->where("GESTION", '1');
        $this->data['comentario'] = $this->aprobar_resoluciones_model->comentario($post['id']);
        $texto = template_tags(RUTA_DES . $post['id'] . "/" . $plantilla['NOM_DOCU_RESOL_RECURSO'] . ".txt");
        $this->data['texto'] = base64_decode($texto);
        $this->data['plantilla'] = $plantilla;
        $this->load->view('resolucion/resolver_recurso_gestionar2', $this->data);
    }

    function update_resolucion_hija() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $plantilla = $this->aprobar_resoluciones_model->update_resolucion_hija($this->data);
    }

    function favorabilidad() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->load->view('resolucion/favorabilidad', $this->data);
    }

    function subir_acta_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['file'] = $file;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->subir_acta_archivo($this->data);
//        $this->load->view('resolucion/subir_acta', $this->data);
        header('Location:' . base_url('index.php/resolucion/datos_del_abogado') . '');
    }

    function subir_acta_recurso_archivo() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['file'] = $file;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['recurso'] = $this->aprobar_resoluciones_model->id_recurso($post['id_resolucion']);
        $this->aprobar_resoluciones_model->subir_acta_recurso_archivo($this->data);
//        $this->load->view('resolucion/subir_acta', $this->data);
        header('Location:' . base_url('index.php/resolucion/datos_del_abogado') . '');
    }

    function update_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $file = $this->do_upload();
        $this->data['file'] = $file;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->update_citacion($this->data);
        if ($post['cod_siguiente'] == 412)
            header('Location:' . base_url('index.php/ejecutoriaactoadmin/') . '');
        else
            header('Location:' . base_url('index.php/resolucion/datos_del_abogado') . '');
    }

    function resolver_recurso() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['tipodocumento'] = $this->aprobar_resoluciones_model->tipodocumento();
        $this->load->view('resolucion/resolver_recurso', $this->data);
    }

    function upload() {
        $id = $_REQUEST['id'];
        if (!file_exists(RUTA_INI)) {
            if (!mkdir(RUTA_INI, 0777, true)) {
                
            } else {
                if (!mkdir(RUTA_DES . $id, 0777, true)) {
                    
                }
            }
        } else {
            if (!file_exists(RUTA_DES . $id)) {
                if (!mkdir(RUTA_DES . $id, 0777, true)) {
                    
                }
            }
        }
//        if (!file_exists("uploads/resolucion/" . $id . "_Recurso")) {
//            if (!mkdir("uploads/resolucion/" . $id . "_Recurso", 0777, true)) {
//                die('Fallo al crear las carpetas...');
//            }
//        }
//        $output_dir = "uploads/resolucion/" . $id . "_Recurso/";
        $output_dir = RUTA_DES . $id . "/";
        if (isset($_FILES["myfile"])) {
            $ret = array();

            $error = $_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if (!is_array($_FILES["myfile"]["name"])) { //single file
                $fileName = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $fileName);
                $ret[] = $fileName;
            } else {  //Multiple files, file[]
                $fileCount = count($_FILES["myfile"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = $_FILES["myfile"]["name"][$i];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $fileName);
                    $ret[] = $fileName;
                }
            }
            echo json_encode($ret);
        }
    }

    function detele() {
        $id = $_REQUEST['id'];
        if (!file_exists("uploads/resolucion/" . $id . "_Recurso")) {
            if (!mkdir("uploads/resolucion/" . $id . "_Recurso", 0777, true)) {
                die('Fallo al crear las carpetas...');
            }
        }
        $output_dir = "uploads/resolucion/" . $id . "_Recurso/";
        if (isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name'])) {
            $fileName = $_POST['name'];
            $filePath = $output_dir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            echo "Deleted File " . $fileName . "<br>";
        }
    }

    function Recurso_resolucion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->aprobar_resoluciones_model->Recurso_resolucion($this->data);
    }

    function resolver_recurso_gestionar() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['liquidacion'] = $this->aprobar_resoluciones_model->info_liquidacion($this->data['consulta'][0]['COD_FISCALIZACION']);
        $txt = $this->aprobar_resoluciones_model->plantilla('86');
        if ($this->data['consulta'][0]['COD_CPTO_FISCALIZACION'] == 2) {//fic
            $this->data['detalle'] = $this->aprobar_resoluciones_model->detalle_fic($this->data['consulta'][0]['NUM_LIQUIDACION']);
        }
        if ($this->data['consulta'][0]['COD_CPTO_FISCALIZACION'] == 1) {// aportes
            $this->data['detalle'] = $this->aprobar_resoluciones_model->detalle_aportes($this->data['consulta'][0]['NUM_LIQUIDACION']);
        }

        $reemplazo = array();
        $reemplazo['regional'] = $this->data['consulta'][0]['NOMBRE_REGIONAL'];
        $reemplazo['cod_resolucion'] = $this->data['consulta'][0]['NUMERO_RESOLUCION'];
        $reemplazo['fecha'] = $this->data['consulta'][0]['FECHA_CREACION'];
        $reemplazo['director'] = $this->data['consulta'][0]['NOMBRE_DIRECTOR'];
        $reemplazo['nombre_empresa'] = $this->data['consulta'][0]['NOMBRE_EMPLEADOR'];
        $reemplazo['nit'] = $this->data['consulta'][0]['NITEMPRESA'];
        $reemplazo['domicilio'] = $this->data['consulta'][0]['DIRECCION'];
        $reemplazo['valor_letras'] = $this->valorEnLetras($this->data['liquidacion']['SALDO_DEUDA'], "pesos");
        $reemplazo['valor'] = $this->data['liquidacion']['SALDO_DEUDA'];
        $reemplazo['ciudad'] = "";
        $reemplazo['fecha_actual'] = date("d/m/Y");
        $reemplazo['reviso'] = $this->data['info_user'];
        $reemplazo['fecha_resolucion'] = $this->data['consulta'][0]['FECHA_CREACION'];

        $texto = template_tags("./uploads/plantillas/" . $txt, $reemplazo);
        $this->data['texto'] = $texto;
        $this->data['consulta2'] = $this->aprobar_resoluciones_model->num_fisca($post['id']);
        $this->load->view('resolucion/resolver_recurso_gestionar', $this->data);
    }

//-----------------------------------------------------------------------------
//POR: Nelson Barbosa
//Objetivo : permite enviar un valor y retorna las letras de ese valor, el segundo parametro es "pesos o dolares"
//Fecha : 30/03/2014        
//------------------------------------------------------------------------------
    function valorEnLetras($valor, $tipo) {
        $info = $this->numeros_letras_model->ValorEnLetras($valor, $tipo);
        return $info;
    }

    function terminacion() {
        $post = $this->input->post();
//        $this->data['post'] = $post;
//        $this->data['user'] = $this->ion_auth->user()->row();
//        $this->data['info_user'] = $this->data['user']->NOMBRES . ' ' . $this->data['user']->APELLIDOS;
//        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->load->view('resolucion/terminacion', $this->data);
    }

    function guardar_recurso() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['recurso'] = $this->aprobar_resoluciones_model->id_recurso($post['id']);
        $this->aprobar_resoluciones_model->guardar_recurso($this->data);
    }

    function update_recurso() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->consultar($post['id']);
        $this->data['recurso'] = $this->aprobar_resoluciones_model->id_recurso($post['id']);
        $this->aprobar_resoluciones_model->update_recurso($this->data);
    }

    function devolver_a_citacion() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->devolver_a_citacion($this->data);
    }

    function Recurso_ejecutoria() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['consulta'] = $this->aprobar_resoluciones_model->Recurso_ejecutoria($this->data);
    }

    function ver_documentos() {
        $post = $this->input->post();
        $this->data['post'] = $post;
        $this->data['user'] = $this->ion_auth->user()->row();
        $this->data['documentacion_resolcuion'] = $this->aprobar_resoluciones_model->documentacion_resolcuion($this->data['post']['id']);
        $this->data['documentacion_citacion'] = $this->aprobar_resoluciones_model->documentacion_citacion($this->data['post']['id']);
        $this->data['documentacion_recurso'] = $this->aprobar_resoluciones_model->documentacion_recurso($this->data['post']['id']);
        $this->data['correspondencia'] = $this->aprobar_resoluciones_model->correspondencia($this->data['post']['id']);
        $this->load->view('resolucion/ver_documentos', $this->data);
    }

    function documentacion_DOCUMENTOSRECURSO($id) {
        $pep = $this->aprobar_resoluciones_model->documentacion_DOCUMENTOSRECURSO($id);
        return $pep;
    }

    function documentacion_RESOLUCIONRECURSO($id) {
        $pep = $this->aprobar_resoluciones_model->documentacion_RESOLUCIONRECURSO($id);
        return $pep;
    }

    public function doUploadFile() {
        $this->data['post'] = $this->input->get();
        $post = $this->data['post'];
        $nombre_carpeta = RUTA_INI . $post['id'];
        $nombre_subcarpeta = RUTA_DES . $post['id'] . '/';

        if (!is_dir($nombre_carpeta)) {
            @mkdir($nombre_carpeta, 0777);
        } else {
            
        }
        if (!is_dir($nombre_subcarpeta)) {
            @mkdir($nombre_subcarpeta, 0777);
        } else {
            
        }
        $tabla = '';
        $status = '';
        $message = '';
        $background = '';
        $file_element_name = 'userFile';

        if ($status != 'error') {
            $config['upload_path'] = RUTA_DES . $post['id'] . '/';
            $config['allowed_types'] = 'pdf';
//            $config['allowed_types'] = 'png|jpg|gif|pdf';
            $config['max_size'] = '10000';
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($file_element_name)) {
                return false;
            } else {
                $data = $this->upload->data();
            }
            @unlink($_FILES[$file_element_name]);
        }
//        $this->Mcinvestigacion_model->guardar_documentos_bancarios($post['id'], $data['file_name']);
//        $tabla= $this->Mcinvestigacion_model->select_documentos_bancarios($post['id']);
//        $tabla= "";
//        $tabla=  base64_encode($tabla);
        $json_encode = json_encode(array('message' => $message, 'status' => $status, 'background' => $background, 'nombre_archivo' => $data['file_name']));
        echo $json_encode;
    }
    function recurso_reliquidacion(){
        $this->aprobar_resoluciones_model->recurso_reliquidacion();
    }
    
    

}
?>
