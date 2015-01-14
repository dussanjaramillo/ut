<?php

class Insolvencia extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->data['style_sheets'] = array('css/jquery.dataTables_themeroller.css' => 'screen', 'css/chosen.css' => 'screen');
        $this->data['javascripts'] = array('js/jquery.dataTables.min.js', 'js/jquery.dataTables.defaults.js', 'js/chosen.jquery.min.js', 'js/jquery.confirm.js', 'js/tinymce/tinymce.jquery.min.js');
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['user'] = $this->ion_auth->user()->row();
        DEFINE('IDUSER', $this->data['user']->IDUSUARIO);
        $this->load->library(array('pagination', 'libupload', 'datatables', 'session', 'form_validation', 'upload', 'libupload'));
        $this->load->library('email');
        $this->load->library('tcpdf/tcpdf.php');
        $this->load->helper(array('form', 'url', 'codegen_helper', 'traza_fecha', 'template_helper', 'file'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('insolvencia_model');
        $this->load->file(APPPATH . "controllers/expedientes.php", TRUE);
        $this->load->model('expediente_model');
        $this->data['user'] = $this->ion_auth->user()->row();
        define("ID_USER", $this->data['user']->IDUSUARIO);
    }

    function acta_reorganizacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['regimen'] = $this->insolvencia_model->consultar_regimenel($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_actaaudienciareorganizacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function acta_calificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['regimen'] = $this->insolvencia_model->consultar_regimenel($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_actacalificacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function asignar_abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['hoy'] = date("d/m/Y");
                $this->data['usuarios'] = $this->insolvencia_model->consultar_usuarios();
                $this->data['regimen'] = $this->insolvencia_model->remitir_doc($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_asignarabogado', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function asistiraudienciaobjeciones() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->template->load($this->template_file, 'insolvencia/insolvencia_asistiraudienciaobjeciones', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function info_audiencia() {

        $fiscalizacion = $this->input->post('fiscalizacion');
        $nit = $this->input->post('nit');
        $gestion = $this->input->post('gestion');
        $titulo = $this->input->post('titulo');
        $regimen = $this->input->post('regimen');
        $opcion = $this->input->post('opcion');
        $valor = $this->input->post('valbie_carres');
        $detalle = $this->input->post('detalle_carres');
        $hoy = date('d/m/Y');
        $nom_aud = $this->input->post('numpro_carres');
        $num_aud = $this->input->post('numexp_carres');
        $archivo = $this->do_upload($fiscalizacion);

        //var_dump($archivo);die;

        $data = array(
            'COD_REGIMENINSOLVENCIA' => $regimen,
            'CREADO_POR' => $nom_aud,
            'NUM_DOCU_AUDIENCIA' => $num_aud,
            'VALOR_BIENES ' => $valor,
            'DATOS_AUDIENCIA' => $detalle,
            'AUDIENCIA_REALIZADA' => 'S',
            'ADJUDICA_BIEN' => $opcion,
            'RUTA_INFO' => $archivo["upload_data"]["file_path"],
            'DOC_INFO' => $archivo["upload_data"]["file_name"]
        );
        $data1 = array('FECHA_CREACION' => $hoy);

        $guardaActa = $this->insolvencia_model->guardar_actaaudiencia($data, $data1);
        $tipogestion = 295;
        $estado = 837;
        $mensaje = 'Registrar Resultado de la Audiencia';
        $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);

        $actualiza = array('COD_GESTIONCOBRO' => $gestion);

        $update = $this->insolvencia_model->actualiza_regimenActa($actualiza, $fiscalizacion);

        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Proceso Realizado Con Exito.</div>');
        redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
    }

    function notifica_abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['nit'] = $this->input->post('nit');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $gestion = $this->data['gestion'] = $this->input->post('gestion');
                $titulo = $this->data['titulo'] = $this->input->post('titulo');
                $this->data['regimen'] = $this->input->post('regimen');
                if ($gestion == 1350) :
                    //$this->data['encabezado'] = 'Documento de Entrega al abogado de las acreencias';
                    $this->data['encabezado'] = 'Memorial Para Hacerse Parte del Proceso';
                elseif ($gestion == 1351) :
                    $this->data['encabezado'] = 'Memorial Para Hacerse Parte del Proceso';
                elseif ($gestion == 608):
                    $this->data['encabezado'] = 'Memorial Para Hacerse Parte del Proceso';
                endif;

                $this->template->load($this->template_file, 'insolvencia/insolvencia_generaractaaudiencia', $this->data);
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_notificacion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $nit = $this->input->post('nit');
                $fiscalizacion = $this->input->post('fiscalizacion');
                $gestion = $this->input->post('gestion');
                $titulo = $this->input->post('titulo');
                $regimen = $this->input->post('regimen');

                if ($gestion == 1351 || $gestion == 608 || $gestion == 1350):
                    $tipogestion = 240;
                    $estado = 611;
                    $mensaje = 'Memorial para Hacerse Parte del Proceso Generado';
                endif;

//                    if ($gestion == 1350):
//                        $tipogestion    = 239;
//                        $estado         = 608;
//                        $mensaje        = 'Notificación Generada';
//                        elseif ($gestion == 1351 || $gestion == 608):
//                            $tipogestion    = 240;
//                            $estado         = 611;
//                            $mensaje        = 'Memorial para Hacerse Parte del Proceso Generado';                                    
//                    endif;

                $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);
                $actualiza = array('COD_GESTIONCOBRO' => $gestion);
                $docFile = $this->do_upload($titulo);
                $update = $this->insolvencia_model->actualiza_regimenActa($actualiza, $fiscalizacion);
                $tipoexpediente = 8;
                $ruta = 'uploads/insolvencia/revisarproyectos/' . $fiscalizacion . '/' . $docFile['upload_data']['file_name'];

                $data = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $estado,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                    'ID_USUARIO' => ID_USER,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                );

                $msj = $this->insolvencia_model->insertar_expediente($data);
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Proceso se ha modificado correctamente.</div>');
                redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function cargar_resultado() {
        $this->data['nit'] = $this->input->post('nit');
        $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
        $this->data['gestion'] = $this->input->post('gestion');
        $titulo = $this->data['titulo'] = $this->input->post('titulo');
        $regimen = $this->data['idregimen'] = $this->input->post('regimen');
        $this->data['regimen'] = $this->insolvencia_model->consultar_regimenel($titulo);
        $this->template->load($this->template_file, 'insolvencia/insolvencia_cargarresultadoaudiencia', $this->data);
    }

    function codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje) {
        if ($fiscalizacion == '') {
            return 0;
        } else {
            $this->datos['idgestioncobro'] = trazar($tipogestion, $estado, $fiscalizacion, $nit, $cambiarGestionActual = 'S', $cod_gestion_anterior = -1, $comentarios = $mensaje);
            $this->datos['idgestion'] = $this->datos['idgestioncobro']['COD_GESTION_COBRO'];
            return $this->datos['idgestion'];
        }
    }

    function designar_abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['hoy'] = date("d/m/Y");
                $this->data['usuarios'] = $this->insolvencia_model->consultar_usuarios();
                $this->data['regimen'] = $this->insolvencia_model->remitir_doc($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_designarabogado', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function consultarTitulos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['registros'] = $this->insolvencia_model->viewRegimenData();
                $this->template->load($this->template_file, 'insolvencia/insolvencia_verificatitulos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    private function do_upload($proceso) {
        $this->load->library('upload');
        $cFile = "./uploads/insolvencia/revisarproyectos/$proceso/";

        if (!is_dir($cFile)) {
            mkdir($cFile, 0777, true);
        }

        $config['upload_path'] = $cFile;
        $config['allowed_types'] = '*';
        $config['max_size'] = '6048';
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('filecolilla')) {
            return $error = array('error' => $this->upload->display_errors());
        } else {
            return $data = array('upload_data' => $this->upload->data());
        }
    }

// Fin do_upload

    function enviar_remision() {
        $adjunto = array();
        $para = $this->input->post('para_ordrem');
        $conc = $this->input->post('cc_ordrem');
        $coco = $this->input->post('cco_ordrem');
        $asun = $this->input->post('asu_ordrem');
        $nit = $this->input->post('numdoc_ordrem');
        $fiscalizacion = $this->input->post('num_fiscaliza');
        $tipo_gestion = $this->input->post('tipo_gestion');
        $tipo_opcion = $this->input->post('tipo_opcion');
        $exis_tit = $this->input->post('exis_tit');
        $notificacion = $this->input->post('notificacion');
        $cod_titulo = $this->input->post('numexp_ordrem');
        $nombre_subcarpeta = 'regimeninsolvencia';

        if (!is_dir($nombre_subcarpeta)) {
            @mkdir($nombre_subcarpeta, 0777);
        } else {
            
        }

        for ($i = 0; $i < count($_FILES); $i++) {
            //no le hacemos caso a los vacios
            if (!empty($_FILES['archivo' . $i]['name'])) {
                $respuesta[] = $this->libupload->doUpload($i, $_FILES['archivo' . $i], $nombre_subcarpeta, 'pdf|doc|jpg|jpeg', 9999, 9999, 0);
                $adjunto = base_url() . 'uploads/regimeninsolvencia/' . $_FILES['archivo' . $i]['name'];
            }
        }
        enviarcorreosena($para, $notificacion, $asun, $conc, $adjunto, $coco);
        $tipogestion = 231;
        $estado = 596;
        $mensaje = 'Remsión al Juez del Concurso Generada y Entregada';
        $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);

        $data = array(
            'COD_GESTIONCOBRO' => $gestion
        );

        $update = $this->insolvencia_model->actualiza_regimen($data, $cod_titulo);
        if ($update == TRUE) {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Proceso se ha modificado correctamente.</div>');
            redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Hubo error al modificar el proceso.</div>');
            redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
        }
        //$this->asignar_abogado($nit,$exis_tit,$tipo_opcion);
    }

    function generar_actaaudiencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $asi_aud = $this->input->post('opcion');
                $obj_aud = $this->input->post('opcion1');
                $fec_aud = $this->input->post('fecha_elaaud');
                $reg_aud = $this->input->post('regins_elaaud');
                $nom_aud = $this->input->post('nompro_elaaud');
                $num_aud = $this->input->post('numexp_elaaud');
                $nit = $this->input->post('numdoc_elaaud');
                $fiscalizacion = $this->input->post('num_fisca');
                $titulo = $this->input->post('titulo');

                if ($asi_aud == "si")
                    $asi_aud = 'S';
                else
                    $asi_aud = 'N';

                if ($obj_aud == "1") {
                    $no_acr = 'S';
                    $inc_no = 'N';
                    $sin_pr = 'N';
                }
                if ($obj_aud == "2") {
                    $no_acr = 'N';
                    $inc_no = 'S';
                    $sin_pr = 'N';
                }
                if ($obj_aud == "3") {
                    $no_acr = 'N';
                    $inc_no = 'N';
                    $sin_pr = 'S';
                }
                $hoy = date('y/m/d');

                if ($fec_aud != '') {
                    $data = array(
                        'COD_REGIMENINSOLVENCIA' => $reg_aud,
                        'CREADO_POR' => $nom_aud,
                        'NUM_DOCU_AUDIENCIA' => $num_aud,
                        'AUDIENCIA_REALIZADA ' => $asi_aud,
                        'NO_ACREENCIA_SENA' => $no_acr,
                        'INCLUYE_NO_CORREPONDE' => $inc_no,
                        'SIN_PRELACION_SENA' => $sin_pr,
                    );
                    $data1 = array(
                        'FECHA_AUDIENCIA' => $fec_aud,
                        'FECHA_CREACION' => $hoy
                    );
                } else {
                    $data = array(
                        'COD_REGIMENINSOLVENCIA' => $reg_aud,
                        'CREADO_POR' => $nom_aud,
                        'NUM_DOCU_AUDIENCIA' => $num_aud,
                        'AUDIENCIA_REALIZADA ' => $asi_aud,
                        'NO_ACREENCIA_SENA' => $no_acr,
                        'INCLUYE_NO_CORREPONDE' => $inc_no,
                        'SIN_PRELACION_SENA' => $sin_pr
                    );
                    $data1 = array(
                        'FECHA_CREACION' => $hoy
                    );
                }
                $tipogestion = 289;
                $estado = 822;
                $mensaje = 'Resultado Audiencia Registrada';
                $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);

                $data3 = array(
                    'COD_GESTIONCOBRO' => $gestion
                );

                $update = $this->insolvencia_model->actualiza_regimenActa($data3, $fiscalizacion);
                $this->insolvencia_model->guardar_actaaudiencia($data, $data1);

                if ($asi_aud == 'S'):
                    $docFile = $this->do_upload($titulo);
                    $tipoexpediente = 8;
                    $ruta = 'uploads/insolvencia/revisarproyectos/' . $titulo . '/' . $docFile['upload_data']['file_name'];

                    $dataExp = array(
                        'RUTA_DOCUMENTO' => $ruta,
                        'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                        'COD_RESPUESTAGESTION' => $estado,
                        'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                        'ID_USUARIO' => ID_USER,
                        'FECHA_RADICADO' => date('d/m/Y'),
                        'COD_FISCALIZACION' => $fiscalizacion,
                    );

                    $msj = $this->insolvencia_model->insertar_expediente($dataExp);
                endif;
                $this->session->set_flashdata('message', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>El Proceso se ha modificado correctamente.</div>');
                redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
//                $this->data['viene']='1';
//                $this->data['tipo']= $this->input->post('tipo_opcion');
//                $this->data['nit']= $nit;
//                $this->data['cod_regimen']=$reg_aud; 
                //$this->template->load($this->template_file, 'insolvencia/insolvencia_generaractaaudiencia',$this->data);
            }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function elaborar_audiencia() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['cod_tit'] = $cod_tit = $this->input->post('titulo');
                $this->data['regimen'] = $this->insolvencia_model->objetar_pruebas($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_elaborardocaudiencia', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function getData() {
        if ($this->ion_auth->logged_in()) {
            $lenght = $this->input->post('iDisplayLength');
            $viene = $this->input->post('viene');
            $data['registros'] = $this->insolvencia_model->getDatax($this->input->post('iDisplayStart'), $this->input->post('sSearch'), $lenght, $viene);
            define('AJAX_REQUEST', 1); //truco para que en nginx no muestre el debug
            $TOTAL = $this->insolvencia_model->totalData($this->input->post('sSearch'), $viene);
            echo json_encode(array('aaData' => $data['registros'],
                'iTotalRecords' => $TOTAL,
                'iTotalDisplayRecords' => $TOTAL,
                'sEcho' => $this->input->post('sEcho')));
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function guardar_revisar_proyeto() {
        $codigo_regins = $this->input->post('instan_revpro');
        $numexp_regins = $this->input->post('numexp_revpro');
        //$promot_regins = $this->input->post('infor');
        $promot_regins = $this->input->post('promot_revpro');
        $telefo_regins = $this->input->post('telefo_revpro');
        $bienin_regins = $this->input->post('opcion_revpro');
        $nit = $this->input->post('nit_revpro');
        $fiscalizacion = $this->input->post('num_fisca');
        $cod_titulo = $this->input->post('titulo_remdoc');

        $respuesta = $this->do_upload($cod_titulo);

        $data = array(
            'COD_REGIMENINSOLVENCIA' => $codigo_regins,
            'COD_INSTANCIA' => '',
            'NUM_EXPEDIENTE' => $numexp_regins,
            'PROMOTOR' => $promot_regins,
            'TELEFONO' => $telefo_regins,
            'BIEN_INCLUIDO' => $bienin_regins,
            'DOCUMENTO_ADJUNTO' => $respuesta['upload_data']['file_name']
        );

        if ($bienin_regins == 'S') {
            $bienin_regins = 7;
            $tipogestion = 233;
            $estado = 598;
            $mensaje = 'Proyecto de Clasificación Bien Incluido';
        } else {
            $bienin_regins = 5;
            $tipogestion = 234;
            $estado = 1339;
            $mensaje = 'Proyecto de Clasificación Bien No Incluido';
        }

        $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);
        $data1 = array(
            'COD_GESTIONCOBRO' => $gestion,
            'COD_ESTADOPROCESO' => $bienin_regins,
        );
        $this->data['guardar_rev_pro'] = $this->insolvencia_model->guardar_rev_pro($data, $data1, $bienin_regins, $fiscalizacion);
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Proceso Generado Exitosamente.</div>');
        redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
    }

    function guardartitfisi() {
        $cod_tit = $this->input->post('cod_titulo');
        $cod_fis = $this->input->post('cod_fis');
        $nit_emp = $this->input->post('nit_emp');
        $cod_pro = $this->input->post('cod_pro');
        $cod_est = $this->input->post('cod_est');
        $tip_ges = $this->input->post('tip_ges');
        $valor_exp = $this->input->post('valor_exp');
        $titulo = $this->input->post('titulo');
        $cod_pj = $this->input->post('cod_pj');

        if ($tip_ges == 'REORGANIZACION') {
            if ($titulo == 's') {
                if ($cod_pj == NULL) {
                    $ruta = 'ordenarremision';
                    $tipogestion = '269';
                    $estado = '779';
                    $mensaje = 'Es de Reorganizacion, Existen Titulos y No Tienen Cobro Coactivo';
                    $estado_proceso = '2';
                } else {
                    $ruta = 'remitirdocsuperintendencia';
                    $tipogestion = '269';
                    $estado = '780';
                    $mensaje = 'Es de Reorganizacion, Existen Titulos y Tienen Cobro Coactivo';
                    $estado_proceso = '1';
                }
            } else {
                $ruta = 'designarabogado';
                $estado_proceso = '2';
                if ($cod_pj == NULL) {
                    $ruta = 'ordenarremision';
                    $tipogestion = '269';
                    $estado = '785';
                    $mensaje = 'Es de Reorganizacion, No Esxisten Titulos,No Tienen Obligacion Posterios, No Tienen Cobro Coactivo';
                } else {
                    $ruta = 'remitirdocsuperintendencia';
                    $tipogestion = '269';
                    $estado = '783';
                    $mensaje = 'Es de Reorganizacion, No Esxisten Titulos,No Tienen Obligacion Posterios, Tienen Cobro Coactivo';
                }
            }
        } else {
            if ($titulo == 's') {
                if ($cod_pj == NULL) {
                    $ruta = 'ordenarremision';
                    $tipogestion = '282';
                    $estado = '810';
                    $mensaje = 'Fisicos Recibidos, Existen Titulos,No Tienen Cobro Coactivo';
                    $estado_proceso = '2';
                } else {
                    $ruta = 'remitirdocsuperintendencia';
                    $tipogestion = '282';
                    $estado = '809';
                    $mensaje = 'Fisicos Recibidos, Existen Titulos, Tienen Cobro Coactivo';
                    $estado_proceso = '1';
                }
            } else {
                $ruta = 'designarabogado';
                $estado_proceso = '3';
                if ($cod_pj == NULL) {
                    $ruta = 'ordenarremision';
                    $tipogestion = '282';
                    $estado = '812';
                    $mensaje = 'Es de Reorganizacion, No Esxisten Titulos,No Tienen Obligacion Posterios, No Tienen Cobro Coactivo';
                } else {
                    $ruta = 'remitirdocsuperintendencia';
                    $tipogestion = '282';
                    $estado = '813';
                    $mensaje = 'Fisicos Recibidos, No Existen Titulos, No Tienen  Obligacion Posterior, No Tienen Cobro Coactivo';
                }
            }
        }

        $gestion = $this->codGestion($tipogestion, $estado, $cod_fis, $nit_emp, $mensaje);
        $data = array(
            //'COD_FISCALIZACION'              => $cod_fis, 
            'FECHA' => date('d/m/Y'),
            //'NITEMPRESA'                     => $nit_emp,
            'NUM_PROCESO' => $valor_exp,
            'COD_ESTADOPROCESO' => $estado_proceso,
            'COD_RECEPCION_TITULO' => $cod_tit,
            'COMENTARIOS' => $tip_ges,
            'DOCUMENTOS_FISICOS_RECIBIDOS' => 'S',
            'EXISTE_TITULO' => $titulo,
            'COD_GESTIONCOBRO' => $gestion
        );
        //var_dump($data);die;
        $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $cod_fis);
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El Titulo Fue Recibido Fisicamente Con Exito.</div>');
        redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
    }

    function guardar_verificacion() {
        $verificacion = $this->input->post('verificacion');
        $nit = $this->input->post('nit');
        $fiscalizacion = $this->input->post('fiscalizacion');
        $gestion = $this->input->post('gestion');
        $num_proceso = $this->input->post('num_proceso');
        $cobro_coactivo = $this->input->post('coactivo');
        $titulo = $this->input->post('titulo');
        $tip_ges = $this->input->post('tip_ges');
        if ($tip_ges == 'REORGANIZACION'):
            $tipo = 0;
        else:
            $tipo = 1;
        endif;
        //var_dump($_POST);DIE;
        //echo $tip_ges;die;
        //echo "--->".$gestion;die;
        switch ($gestion) {
            case 'null':
                $tipogestion = 229;
                $tiporespuesta = 593;
                $mensaje = 'Titulos Validados';
                $data = array(
                    'FECHA' => date('d/m/Y'),
                    'NUM_PROCESO' => $num_proceso,
                    'DOCUMENTOS_FISICOS_RECIBIDOS' => 'S',
                    'COD_ESTADOPROCESO' => 13,
                    'COMENTARIOS' => $tip_ges,
                    'TIPO_REGIMEN' => $tipo
                );  //var_dump($data);die;
                $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $fiscalizacion);
                break;
            case '778':
                $tipogestion = 281;
                $tiporespuesta = 806;
                $mensaje = 'Titulos Validados';
                $data = array(
                    'FECHA' => date('d/m/Y'),
                    'COD_ESTADOPROCESO' => 15,
                );
                $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $fiscalizacion);
                break;
            case '806':
                if ($verificacion == 'S') {
                    $tipogestion = 229;
                    $tiporespuesta = 1348;
                    $mensaje = 'Documentacion Valida';
                } else {
                    $tipogestion = 229;
                    $tiporespuesta = 778;
                    $mensaje = 'No Es de Reorganizacion';

                    $data = array(
                        'FECHA' => date('d/m/Y'),
                        'COD_ESTADOPROCESO' => 14
                    );
                    $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $fiscalizacion);
                }
                break;
            case '593':
                if ($verificacion == 'S') {
                    $tipogestion = 229;
                    $tiporespuesta = 1348;
                    $mensaje = 'Documentacion Valida';
                } else {
                    $tipogestion = 229;
                    $tiporespuesta = 778;
                    $mensaje = 'No Es de Reorganizacion';

                    $data = array(
                        'FECHA' => date('d/m/Y'),
                        'COMENTARIOS' => 'Liquidacion',
                        'TIPO_REGIMEN' => 1,
                        'COD_ESTADOPROCESO' => 14
                    );
                    $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $fiscalizacion);
                }
                break;
            case '601':
                if ($verificacion == 'S') {
                    $tipogestion = 237;
                    $tiporespuesta = 1342;
                    $mensaje = 'Resultado de Audiencia Aprobado';
                } else {
                    $tipogestion = 237;
                    $tiporespuesta = 1343;
                    $mensaje = 'Resultado de Audiencia No Aprobado';
                }
                break;
            case '614':
                if ($verificacion == 'S') {
                    $tipogestion = 237;
                    $tiporespuesta = 1342;
                    $mensaje = 'Resultado de Audiencia Aprobado';
                } else {
                    $tipogestion = 237;
                    $tiporespuesta = 1343;
                    $mensaje = 'Resultado de Audiencia No Aprobado';
                }
                break;
            case '1342':
                if ($verificacion == 'S') {
                    $tipogestion = 237;
                    $tiporespuesta = 602;
                    $mensaje = 'Pago Recibido';
                    $archivo = $this->do_upload($titulo);
                    $tipoexpediente = 8;
                    $ruta = 'uploads/insolvencia/revisarproyectos/' . $titulo . '/' . $archivo['upload_data']['file_name'];
                    $dataExp = array(
                        'RUTA_DOCUMENTO' => $ruta,
                        'NOMBRE_DOCUMENTO' => $archivo['upload_data']['file_name'],
                        'COD_RESPUESTAGESTION' => $tiporespuesta,
                        'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                        'ID_USUARIO' => ID_USER,
                        'FECHA_RADICADO' => date('d/m/Y'),
                        'COD_FISCALIZACION' => $fiscalizacion,
                    );
                    $msj = $this->insolvencia_model->insertar_expediente($dataExp);
                } else {
                    $tipogestion = 237;
                    $tiporespuesta = 603;
                    $mensaje = 'Pago No Recibido';
                }
            case '1343':
                if ($verificacion == 'S') {
                    $tipogestion = 237;
                    $tiporespuesta = 602;
                    $mensaje = 'Pago Recibido';
                    $archivo = $this->do_upload($titulo);
                    $tipoexpediente = 8;
                    $ruta = 'uploads/insolvencia/revisarproyectos/' . $titulo . '/' . $archivo['upload_data']['file_name'];
                    $dataExp = array(
                        'RUTA_DOCUMENTO' => $ruta,
                        'NOMBRE_DOCUMENTO' => $archivo['upload_data']['file_name'],
                        'COD_RESPUESTAGESTION' => $tiporespuesta,
                        'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                        'ID_USUARIO' => ID_USER,
                        'FECHA_RADICADO' => date('d/m/Y'),
                        'COD_FISCALIZACION' => $fiscalizacion,
                    );
                    $msj = $this->insolvencia_model->insertar_expediente($dataExp);
                } else {
                    $tipogestion = 237;
                    $tiporespuesta = 603;
                    $mensaje = 'Pago No Recibido';
                }
                break;
            case '1346':
                $tipogestion = 281;
                $tiporespuesta = 806;
                $mensaje = 'Titulos Validados';
                $data = array(
                    'FECHA' => date('d/m/Y'),
                    'COD_ESTADOPROCESO' => 15,
                );
                $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $fiscalizacion);
                break;
            case '1347':
                if ($verificacion == 'S') {
                    $tipogestion = 237;
                    $tiporespuesta = 602;
                    $mensaje = 'Pago Recibido';
                } else {
                    $tipogestion = 237;
                    $tiporespuesta = 603;
                    $mensaje = 'Pago No Recibido';
                }
                break;
            case '1348':
                if ($verificacion == 'S') {
                    if (@$coactivo != '') {
                        $tipogestion = 269;
                        $tiporespuesta = 780;
                        $mensaje = 'Es de Reorganizacion, Existen Titulos y Tienen Cobro Coactivo';
                    } else {
                        $tipogestion = 269;
                        $tiporespuesta = 779;
                        $mensaje = 'Es de Reorganizacion, Existen Titulos y No Tienen Cobro Coactivo';
                    }
                } else {
                    if (@$coactivo != '') {
                        $tipogestion = 269;
                        $tiporespuesta = 783;
                        $mensaje = 'Es de Reorganizacion, No Existen Titulos,No Tienen Obligacion Posterios, Tienen Cobro Coactivo';
                    } else {
                        $tipogestion = 269;
                        $tiporespuesta = 785;
                        $mensaje = 'Es de Reorganizacion, No Existen Titulos,No Tienen Obligacion Posterios, No Tienen Cobro Coactivo';
                    }
                }
                break;
            case '604':
                if ($verificacion == 'S') {
                    $tipogestion = 237;
                    $tiporespuesta = 1346;
                    $mensaje = 'Memorial De acuerdo';
                } else {
                    $tipogestion = 237;
                    $tiporespuesta = 1347;
                    $mensaje = 'Memorial no esta De acuerdo';
                }
                $data = array(
                    'FECHA' => date('d/m/Y'),
                    'COMENTARIOS' => 'Liquidacion',
                    'TIPO_REGIMEN' => 1,
                    'COD_ESTADOPROCESO' => 14
                );
                $this->data['guardar_remision'] = $this->insolvencia_model->guardar_titfis($data, $fiscalizacion);

                break;
            case '1349':
                if ($verificacion == 'S') {
                    $tipogestion = 239;
                    $tiporespuesta = 1350;
                    $mensaje = 'Existen otras Acreencias';
                } else {
                    $tipogestion = 239;
                    $tiporespuesta = 1351;
                    $mensaje = 'No Existen otras Acreencias';
                }
                break;
            case '837':
                if ($verificacion == 'S') {
                    $tipogestion = 296;
                    $tiporespuesta = 838;
                    $mensaje = 'Bien Recibido';
                } else {
                    $tipogestion = 296;
                    $tiporespuesta = 1352;
                    $mensaje = 'Adjudicacion de Bienes No Aceptada';
                }
                break;
            case '608':
                $tipogestion = 239;
                $tiporespuesta = 609;
                $mensaje = 'Notificación Enviada';
                $archivo = $this->do_upload($titulo);
                $tipoexpediente = 8;
                $ruta = 'uploads/insolvencia/revisarproyectos/' . $titulo . '/' . $archivo['upload_data']['file_name'];
                $dataExp = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $archivo['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                    'ID_USUARIO' => ID_USER,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                );
                $msj = $this->insolvencia_model->insertar_expediente($dataExp);
                break;
            case '611':
                $tipogestion = 240;
                $tiporespuesta = 614;
                $mensaje = 'Memorial para Hacerse Parte del Proceso Aprobado y Firmado';
                $archivo = $this->do_upload($titulo);
                $tipoexpediente = 8;
                $ruta = 'uploads/insolvencia/revisarproyectos/' . $titulo . '/' . $archivo['upload_data']['file_name'];

                $dataExp = array(
                    'RUTA_DOCUMENTO' => $ruta,
                    'NOMBRE_DOCUMENTO' => $archivo['upload_data']['file_name'],
                    'COD_RESPUESTAGESTION' => $tiporespuesta,
                    'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                    'ID_USUARIO' => ID_USER,
                    'FECHA_RADICADO' => date('d/m/Y'),
                    'COD_FISCALIZACION' => $fiscalizacion,
                );

                $msj = $this->insolvencia_model->insertar_expediente($dataExp);
                break;
        }
        $gestion = $this->codGestion($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $mensaje);
        $datos = array(
            'COD_GESTIONCOBRO' => $gestion,
        );
        $update = $this->insolvencia_model->actualiza_regimenActa($datos, $fiscalizacion);
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Proceso Realizado Con Exito.</div>');
        redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
    }

    function generar_actamemorial() {
        $nit = $this->input->post('nit');
        $regimen = $this->input->post('regimen');
        $fiscalizacion = $this->input->post('fiscalizacion');
        $gestion = $this->input->post('gestion');
        $titulo = $this->input->post('titulo');

        $tipogestion = 238;
        $tiporespuesta = 604;
        $mensaje = 'Memorial para Informar Finalización del Acuerdo de Pago Generado';
        $gestion = $this->codGestion($tipogestion, $tiporespuesta, $fiscalizacion, $nit, $mensaje);

        $datos = array(
            'COD_GESTIONCOBRO' => $gestion,
        );

        $update = $this->insolvencia_model->actualiza_regimenActa($datos, $fiscalizacion);
        $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Proceso Realizado Con Exito.</div>');
        redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
    }

    function generar_memorial() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['nit'] = $this->input->post('nit');
                $this->data['regimen'] = $this->input->post('regimen');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['gestion'] = $this->input->post('gestion');
                $this->data['titulo'] = $this->input->post('titulo');
                $this->template->load($this->template_file, 'insolvencia/insolvencia_generarmemorial', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function guardar_acta_reorganizaion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $real_aud = $this->input->post('opcion');
                $moti_aud = $this->input->post('motivo');
                $rein_aud = $this->input->post('regino_actreo');
                $nit = $this->input->post('numdic_actreo');
                $fiscalizacion = $this->input->post('fiscalizacion');
                $titulo = $this->input->post('numexp_actreo');

                if ($real_aud == 'S') {
                    $fech_aud = $this->input->post('fecha_actreo');
                    $vaob_aud = $this->input->post('valobl_actreo');
//                        $leac_aud = $this->input->post('opcion1');
                    $fopa_aud = $this->input->post('formpag_actreo');
                    $fepa_aud = $this->input->post('fecpag_actreo');

                    $data = array(
                        'COD_REGIMENINSOLVENCIA' => $rein_aud,
                        'AUDIENCIA_REALIZADA' => $real_aud,
                        'MOTIVO_NO_REALIZACION' => $moti_aud,
                        'VALOR_OBLIGACION' => $vaob_aud,
                        'FORMA_PAO' => $fopa_aud,
//                            'ACTA_LEIDA'             => $leac_aud,
                    );
                    $data1 = array(
                        'FECHA_AUDIENCIA' => $fech_aud,
                        'FECHA_PAGO' => $fepa_aud
                    );
                } else {
                    $data = array(
                        'COD_REGIMENINSOLVENCIA' => $rein_aud,
                        'AUDIENCIA_REALIZADA' => $real_aud,
                        'MOTIVO_NO_REALIZACION' => $moti_aud,
                        'VALOR_OBLIGACION' => 0,
                        'FORMA_PAO' => ' ',
//                   'ACTA_LEIDA'             => 'N'
                    );
                    $data1 = array(
                    );
                }

                $tipogestion = 236;
                $estado = 601;
                $mensaje = 'Resultado Audiencia Registrada';
                $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);
                $data2 = array(
                    'COD_GESTIONCOBRO' => $gestion,
                    'COD_ESTADOPROCESO' => '10'
                );
                $update = $this->insolvencia_model->actualiza_regimenActa($data2, $fiscalizacion);
                $this->data['guardar_reorga'] = $this->insolvencia_model->guardar_reorga($data, $data1);
                if ($real_aud == 'S'):
                    $docFile = $this->do_upload($titulo);
                    $tipoexpediente = 8;
                    $ruta = 'uploads/insolvencia/revisarproyectos/' . $titulo . '/' . $docFile['upload_data']['file_name'];

                    $data = array(
                        'RUTA_DOCUMENTO' => $ruta,
                        //'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                        'NOMBRE_DOCUMENTO' => '9id3863je0e9s8d.pdf',
                        'COD_RESPUESTAGESTION' => $estado,
                        'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                        'ID_USUARIO' => ID_USER,
                        'FECHA_RADICADO' => date('d/m/Y'),
                        'COD_FISCALIZACION' => $fiscalizacion,
                    );

                    $msj = $this->insolvencia_model->insertar_expediente($data);
                endif;
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El Abogado Fue Asignado Exitosamente.</div>');
                redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
            }
            else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function guardar_asignar_abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $insolv = $this->input->post('insolv_remdoc');
                $abo_asi = $this->input->post('abogasi_asigabo');
                $fecha_Asigna = $this->input->post('fecasi_asigabo');
                $comentarios = $this->input->post('com_asigabo');
                $nit = $this->input->post('numdoc_remdoc');
                $fiscalizacion = $this->input->post('num_fiscaliza');

                $tipogestion = 286;
                $estado = 818;
                $mensaje = 'Abogado Asignado';
                $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);

                $data = array(
                    'FECHA_ASIGNACION' => $fecha_Asigna,
                    'ABOGADO_ASIGNADO' => $abo_asi,
                    'COMENTARIOS_ASIGNACION' => $comentarios,
                    'COD_ESTADOPROCESO' => '4',
                    'COD_GESTIONCOBRO' => $gestion
                );


                $this->data['regimen'] = $this->insolvencia_model->guardar_asignacionabogado($data, $insolv);
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El Abogado Fue Asignado Exitosamente.</div>');
                redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function guardar_designar_abogado() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $insolv = $this->input->post('insolv_remdoc');
                $abo_asi = $this->input->post('abogasi_asigabo');
                $fecha_Asigna = $this->input->post('fecasi_asigabo');
                $comentarios = $this->input->post('com_asigabo');
                $nit = $this->input->post('numdoc_remdoc');
                $fiscalizacion = $this->input->post('num_fiscaliza');

                $tipogestion = 232;
                $estado = 1349;
                $mensaje = 'Abogado Asignado Sin Titulo';
                $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);

                $data = array(
                    'FECHA_ASIGNACION' => $fecha_Asigna,
                    'ABOGADO_ASIGNADO' => $abo_asi,
                    'COMENTARIOS_ASIGNACION' => $comentarios,
                    'COD_ESTADOPROCESO' => '4',
                    'COD_GESTIONCOBRO' => $gestion
                );


                $this->data['regimen'] = $this->insolvencia_model->guardar_asignacionabogado($data, $insolv);
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>El Abogado Fue Asignado Exitosamente.</div>');
                redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function guardar_remitir_dcumento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $chec = $this->input->post('opcion');
                $chec1 = $this->input->post('opcion1');
                $chec2 = $this->input->post('opcion2');
                $chec3 = $this->input->post('opcion3');
                $regins = $this->input->post('insolv_remdoc');
                $observ = $this->input->post('observ_remdoc');
                $numdoc_remdoc = $this->input->post('numexp_remdoc');
                $nit = $this->input->post('numdoc_remdoc');
                $titulo = $this->input->post('titulo_remdoc');
                $hoy = date("d/m/Y");
                $codigo_fiscal = $this->input->post('fiscalizacion');

                $contador = '0';
                if ($chec) {
                    $chec = "S";
                    $contador++;
                } else
                    $chec = "N";
                if ($chec1) {
                    $chec1 = "S";
                    $contador++;
                } else
                    $chec1 = "N";
                if ($chec2) {
                    $chec2 = "S";
                    $contador++;
                } else
                    $chec2 = "N";
                if ($chec3) {
                    $chec3 = "S";
                    $contador++;
                } else
                    $chec3 = "N";
                if ($contador != 0) {

                    $tipogestion = 284;
                    $estado = 816;
                    $mensaje = 'Documentos de Crédito Remitidos a la Superintendencia';
                    $gestion = $this->codGestion($tipogestion, $estado, $codigo_fiscal, $nit, $mensaje);

                    $data = array(
                        'COD_REGIMENINSOLVENCIA' => $regins,
                        'NUM_EXPEDIENTE' => $numdoc_remdoc,
                        'OBSERVACIONES_REMISION' => $observ,
                        'TITULO_EJECUTIVO' => $chec,
                        'LIQUIDACION' => $chec1,
                        'PORDER_CON_SOPORTES' => $chec2,
                        'ACTO_RESOLUCION' => $chec3,
                        'FECHA_REMISION' => $hoy,
                    );

                    $data1 = array(
                        'COD_GESTIONCOBRO' => $gestion,
                        'COD_ESTADOPROCESO' => '3'
                    );

                    $datos = array('COD_GESTIONCOBRO' => $gestion);


                    $this->data['guardar_remision'] = $this->insolvencia_model->guardar_remision($data, $data1);

                    $update = $this->insolvencia_model->actualiza_regimenActa($datos, $codigo_fiscal);
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Documentos Remitidos Exitosamente</div>');
                    redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Seleccionar al Menos un Titulo</div>');
                    $this->data['regimen'] = $this->insolvencia_model->remitir_doc($titulo);
                    $this->template->load($this->template_file, 'insolvencia/consultarTitulos', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function index() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                //$this->consultarTitulos();
                $this->template->load($this->template_file, 'insolvencia/insolvencia_consultatitulos', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function objetar_pruebas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['regimen'] = $this->insolvencia_model->objetar_Pruebas($cod_tit);
//                     $this->load->view('insolvencia/insolvencia_resultadopruebas',$this->data);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_objetarysolicitarpruebas', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function ordenar_remision() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['regimen'] = $this->insolvencia_model->remitir_doc($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_ordenarremsion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function pdfRevocatoria() {
        $viene = $this->input->post('viene');
        //$html       = $this->input->post('informacion');
        $html = $this->input->post('memorial');
        $logo = $this->input->post('logo');
        $nombre_pdf = 'MEMORIAL';
        if ($viene == '0') {
            $regi_insol = $this->input->post('regi_insol');
            $motivo = $this->input->post('motivo');
            $nit = $this->input->post('nit');
            $hoy = date("y/m/d");
        }

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($nombre_pdf . '.pdf', 'D');
        exit();
    }

    function remitir_dcumento() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $cod_tit = $this->input->post('titulo');
                $this->data['regimen'] = $this->insolvencia_model->remitir_doc($cod_tit);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_remitirdocumentos', $this->data);
            }
        }
    }

    function resultado_pruebas() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $this->data['codigo_regins'] = $this->input->post('codreg_objpru');
                $this->data['nit'] = $this->input->post('numdoc_objpru');
                $this->data['opcion'] = $this->input->post('opcion');
                $this->data['recdoc'] = $this->input->post('recdoc');
                $this->data['cod_titulo'] = $this->input->post('cod_titulo');
                $tipomotivo = $this->input->post('tipomotivo');
                $nit = $this->input->post('numdoc_objpru');
                $fiscalizacion = $this->input->post('num_fisca');
                $regi_insol = $this->input->post('codreg_objpru');
                $hoy = date("y/m/d");

                if ($this->data['opcion'] == "no") {
                    $tipogestion = 288;
                    $estado = 821;
                    $mensaje = 'No Elaborar memorial de Objecion';
                } else {
                    $tipogestion = 288;
                    $estado = 819;
                    $mensaje = 'Elaborar memorial de Objecion';
                }

                $gestion = $this->codGestion($tipogestion, $estado, $fiscalizacion, $nit, $mensaje);
                $data = array(
                    'COD_REGIMENINSOLVENCIA' => $regi_insol,
                    'OBSERVACIONES' => '',
                    'FECHA_CREACION' => $hoy,
                    'DOCUMENTO_MEMORIAL' => 'N',
                    'COD_MOTIVOMEMORIAL' => $tipomotivo
                );
                $data1 = array(
                    'COD_GESTIONCOBRO' => $gestion,
                    'COD_ESTADOPROCESO' => '6'
                );
                if ($this->data['opcion'] == "si"):
                    $docFile = $this->do_upload($this->data['cod_titulo']);
                    $tipoexpediente = 8;
                    $ruta = 'uploads/insolvencia/revisarproyectos/' . $this->data['cod_titulo'] . '/' . $docFile['upload_data']['file_name'];

                    $dataExp = array(
                        'RUTA_DOCUMENTO' => $ruta,
                        'NOMBRE_DOCUMENTO' => $docFile['upload_data']['file_name'],
                        'COD_RESPUESTAGESTION' => $estado,
                        'COD_TIPO_EXPEDIENTE' => $tipoexpediente,
                        'ID_USUARIO' => ID_USER,
                        'FECHA_RADICADO' => date('d/m/Y'),
                        'COD_FISCALIZACION' => $fiscalizacion,
                        'RADICADO_ONBASE' => ' ',
                        'FECHA_ONBASE' => $hoy
                    );

                    $msj = $this->insolvencia_model->insertar_expediente($dataExp);
                endif;

                $this->insolvencia_model->memorial($data, $data1);

                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>Proceso Modificado Correctamente Exitosamente.</div>');
                redirect(base_url() . 'index.php/insolvencia/consultarTitulos');
            }else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function revisar_proyeto() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('ecollect/manage')) {
                $titulo = $this->input->post('titulo');
                $tipo_regimen = $this->input->post('tipo_regimen');
                if ($tipo_regimen == 0 || $tipo_regimen == NULL) {
                    $this->data['titulo_encabezado'] = '<h3>Proyecto de Calificación y Graduación de Créditos,<br> derechos de voto e Inventario de Bienes</h3><h4>(subir Documento al Sistema)</h4>';
                } else {
                    $this->data['titulo_encabezado'] = '<h3>Proyecto de Calificación y Graduación de Créditos</h3><h4>(subir Documento al Sistema)</h4>';
                }
                $this->data['usuarios'] = $this->insolvencia_model->consultar_usuarios1();
                $this->data['regimen'] = $this->insolvencia_model->remitir_doc($titulo);
                $this->template->load($this->template_file, 'insolvencia/insolvencia_revisarproyectodecalsificacion', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
            redirect(base_url() . 'index.php/inicio');
        }
    }

    function verificar_regimen() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_group('Abogado coac') || $this->ion_auth->in_menu('insolvencia/consultarTitulos')) {
                $this->data['nit'] = $this->input->post('nit');
                $this->data['regimen'] = $this->input->post('regimen');
                $this->data['fiscalizacion'] = $this->input->post('fiscalizacion');
                $this->data['gestion'] = $this->input->post('gestion');
                $this->data['titulo'] = $this->input->post('titulo');

                $this->load->view('insolvencia/insolvencia_verificaregimen', $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

}

?>