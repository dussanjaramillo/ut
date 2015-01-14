<?php

class Consultarprocesos extends MY_Controller {

    function __construct() {

        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('tcpdf');
        $this->load->helper(array('form', 'url', 'codegen_helper'));
        $this->load->model('codegen_model', '', TRUE);
        $this->load->model('consultaprocesos_model');
        $this->data['javascripts'] = array(
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.defaults.js',
            'js/jquery.validationEngine-es.js',
            'js/jquery.validationEngine.js',
            'js/validateForm.js',
            'js/bootbox.js',
            'js/bootbox.min.js'
        );

        $this->data['style_sheets'] = array(
            'css/jquery.dataTables_themeroller.css' => 'screen',
            'css/validationEngine.jquery.css' => 'screen'
        );
    }

    /**
     * Funcion index
     * Invoca la vista principal que es consultarprocesos/gestionprocesos
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function index() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/index')) {
                $this->template->load($this->template_file, "consultarprocesos/gestionprocesos", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion consultardaf
     * Obtiene la vista la cual muestra todos los procesos de Direccion Administrativa y financiera
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function consultadaf() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultadaf')) {
                $this->load->model('consultaprocesos_model');
                $this->data['datos'] = $this->consultaprocesos_model->getprocesosdaf();
                $this->template->load($this->template_file, "consultarprocesos/consultadaf", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    /**
     * Funcion consultardj
     * Obtiene la vista la cual muestra todos los procesos de Direccion Juridica
     * @author Haider Oviedo Martinez :: Thomas MTI
     * @since 26 II 2013 
     */
    function consultadj() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultadj')) {
                $this->data['datos'] = $this->consultaprocesos_model->getprocesosdj();
                $this->template->load($this->template_file, "consultarprocesos/consultadj", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function consultatraza() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatraza')) {
                $empresa = $this->input->post('CODEMPRESA');
                $this->data['datos'] = $this->consultaprocesos_model->gettraza();
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatraza", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function traerusuarios() {
        $usu = $this->input->get_post('REGIONAL');
        $nuevos = $this->consultaprocesos_model->getusuarios($usu);
        $option = "";
        $option = '</option> <option value = "0">Seleccione Algun Usuario</option>';
        foreach ($nuevos as $i => $nuevo)
            $option .= '<option value = "' . $i . '">' . $nuevo . '</option>';
        // echo "</select>";
        echo $option;
    }

    function verproceso() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/verproceso')) {
                $id = $this->input->post('id');
                $titulo = $this->input->post('titulo');
                $vista = $this->input->post('vista');
//                echo $id;
//                echo $titulo;
//                echo $vista;
//                die();
                $this->data['procesos'] = $this->consultaprocesos_model->verproceso($id, $titulo, $vista);
                $this->data['fiscaliza'] = $this->consultaprocesos_model->fiscalizacion();
                $this->data['acuerdo'] = $this->consultaprocesos_model->acuerdopago();
                $this->data['notiafic'] = $this->consultaprocesos_model->notiaportesfic();
                $this->data['notica'] = $this->consultaprocesos_model->noticontrato();
                $this->data['liquidar'] = $this->consultaprocesos_model->liquidacion();
                $this->data['ejecutaafic'] = $this->consultaprocesos_model->ejecutaficca();
                $this->data['ejecutamul'] = $this->consultaprocesos_model->ejecutamultas();
                $this->data['avocal'] = $this->consultaprocesos_model->tituloyavoca();
                $this->data['acercal'] = $this->consultaprocesos_model->acercapersuasivo();
                $this->data['mandal'] = $this->consultaprocesos_model->mandamiento();
                $this->data['investil'] = $this->consultaprocesos_model->investigacion();
                $this->data['avaluol'] = $this->consultaprocesos_model->mcavaluo();
                $this->data['judicial'] = $this->consultaprocesos_model->projudiciales();
                $this->data['liquidacoal'] = $this->consultaprocesos_model->liquidacoactivo();
                $this->data['nulidal'] = $this->consultaprocesos_model->nulidades();
                $this->data['remisil'] = $this->consultaprocesos_model->remisibilidad();
                $this->data['rematal'] = $this->consultaprocesos_model->mcremate();
                $this->data['acuerdocoal'] = $this->consultaprocesos_model->acuerdocoactivo();
                $this->data['verifical'] = $this->consultaprocesos_model->verificacoactivo();
                $this->data['reorganizal'] = $this->consultaprocesos_model->rireorganiza();
                $this->data['liquidalregil'] = $this->consultaprocesos_model->riliquidacion();
                $this->data['trasladol'] = $this->consultaprocesos_model->trasladojudicial();
                $this->data['resolprel'] = $this->consultaprocesos_model->resolupres();
                $this->data['devolucionrnm'] = $this->consultaprocesos_model->devolucionrnm();
                $this->data['devoluciondg'] = $this->consultaprocesos_model->devoluciondg();
                $this->data['devolucionrm'] = $this->consultaprocesos_model->devolucionrm();
                $this->data['nomisional'] = $this->consultaprocesos_model->nomisional();

                $this->load->view("consultarprocesos/consultarproceso", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verprocesodevolucion() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/verprocesodevolucion')) {
                $id = $this->input->post('id');
                $this->data['devoluciones'] = $this->consultaprocesos_model->verprocesodevolucion($id);
                $this->data['devolucionrnm'] = $this->consultaprocesos_model->devolucionrnm();
                $this->data['devoluciondg'] = $this->consultaprocesos_model->devoluciondg();
                $this->data['devolucionrm'] = $this->consultaprocesos_model->devolucionrm();
                $this->load->view("consultarprocesos/consultarprocesodevolucion", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verprocesojudicial() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/verprocesojudicial')) {
                $id = $this->input->post('id');
                $this->data['judiciales'] = $this->consultaprocesos_model->verprocesojudicial($id);
                $this->data['judicial'] = $this->consultaprocesos_model->projudiciales();
                $this->load->view("consultarprocesos/consultarprocesojudicial", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getnombres() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getnombres')) {
                $nombre = strtoupper($this->input->get('term'));
                if (empty($nombre)) {
                    redirect(base_url() . 'index.php/consultarprocesos');
                } else {
                    $this->consultaprocesos_model->set_nombre($nombre);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getnombre()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function getallnombres() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getallnombres')) {
                $nombre = strtoupper($this->input->get('term'));
                if (empty($nombre)) {
                    redirect(base_url() . 'index.php/consultarprocesos');
                } else {
                    $this->consultaprocesos_model->set_allnombre($nombre);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getallnombre()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getcoactivos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getcoactivos')) {
                $coactivo = $this->input->get('term');
                if (empty($coactivo)) {
                    redirect(base_url() . 'index.php/consultarprocesos');
                } else {
                    $this->consultaprocesos_model->set_coactivo($coactivo);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getcoactivo()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getusuarios() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getusuarios')) {
                $nombre = strtoupper($this->input->get('term'));
                if (empty($nombre)) {
                    redirect(base_url() . 'index.php/consultarprocesos');
                } else {
                    $this->consultaprocesos_model->set_usuarios($nombre);
//                    $tales = $this->consultaprocesos_model->getusuario();
//                    print_r($tales);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getusuario()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getcodigos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getcodigos')) {
                $nit = $this->input->get('term');
                if (empty($nit)) {
                    redirect(base_url() . 'index.php/consultarprocesos');
                } else {
                    $this->consultaprocesos_model->set_codigo($nit);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getcodigo()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }
    
    function getallcodigos() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getallcodigos')) {
                $nit = $this->input->get('term');
                if (empty($nit)) {
                    redirect(base_url() . 'index.php/consultarprocesos');
                } else {
                    $this->consultaprocesos_model->set_allcodigo($nit);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getallcodigo()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getnombresjud() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getnombresjud')) {
                $nombre = strtoupper($this->input->get('term'));
                if (empty($nombre)) {
                    redirect(base_url() . 'index.php/consultatrazajudicial');
                } else {
                    $this->consultaprocesos_model->set_nombrejud($nombre);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getnombrejud()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getcodigosjud() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/getcodigosjud')) {
                $nit = $this->input->get('term');
                if (empty($nit)) {
                    redirect(base_url() . 'index.php/consultatrazajudicial');
                } else {
                    $this->consultaprocesos_model->set_codigo($nit);
                    return $this->output->set_content_type('application/json')->set_output(json_encode($this->consultaprocesos_model->getcodigojud()));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizatraza() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatraza')) {
                $empresa = $this->input->post('CODEMPRESA');
                $ciudad = $this->input->post('CIUDAD');
                $nombre = strtoupper($this->input->post('NOMEMPRESA'));
                $regional = $this->input->post('REGIONAL');
                $proceso = $this->input->post('PROCESO');
                $fiscaliza = $this->input->post('FISCALIZACION');
                $codgestion = $this->input->post('COD_GESTION_COBRO');
                $usuario = $this->input->post('selUsuarios');
                $usuar = strtoupper($this->input->post('USERS'));
                $coactivo = $this->input->post('COACTIVO');
                $origen = $this->input->post('ORIGEN');
//                echo $origen;die();

                $this->data['datos'] = $this->consultaprocesos_model->actulizatraza($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar, $coactivo, $origen);
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatraza", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizatrazadevolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazadevolucion')) {
                $empresa = $this->input->post('CODEMPRESA');
                $ciudad = $this->input->post('CIUDAD');
                $nombre = strtoupper($this->input->post('NOMEMPRESA'));
                $regional = $this->input->post('REGIONAL');
//                $proceso = $this->input->post('PROCESO');
                $devolucion = $this->input->post('DEVOLUCION');
                $usuario = $this->input->post('selUsuarios');
                $usuar = strtoupper($this->input->post('USERS'));
//                $codgestion = $this->input->post('COD_GESTION_COBRO');

                $this->data['datos'] = $this->consultaprocesos_model->actulizatrazadevolucion($empresa, $ciudad, $nombre, $regional, $devolucion, $usuario, $usuar);
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazadevolucion", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizatrazajudicial() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazajudicial')) {
                $empresa = $this->input->post('CODEMPRESA');
                $ciudad = $this->input->post('CIUDAD');
                $nombre = strtoupper($this->input->post('NOMEMPRESA'));
                $regional = $this->input->post('REGIONAL');
//                $proceso = $this->input->post('PROCESO');
                $projudicial = $this->input->post('TITULO');
//                $codgestion = $this->input->post('COD_GESTION_COBRO');
                $usuario = $this->input->post('selUsuarios');
                $usuar = strtoupper($this->input->post('USERS'));
//                echo $usuar;die();
                $this->data['datos'] = $this->consultaprocesos_model->actulizatrazajudicial($empresa, $ciudad, $nombre, $regional, $projudicial, $usuario, $usuar);
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazajudicial", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function consultatrazaadmin() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazaadmin')) {
                $empresa = $this->input->post('CODEMPRESA');
                $this->data['datos'] = $this->consultaprocesos_model->gettrazaadmin();
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazaadmin", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verprocesoadmin() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/verprocesoadmin')) {
                $id = $this->input->post('id');
//                echo $id;
                $this->data['procesoadmin'] = $this->consultaprocesos_model->verprocesoadmin($id);
                $this->data['fiscaliza'] = $this->consultaprocesos_model->fiscalizacion();
                $this->data['acuerdo'] = $this->consultaprocesos_model->acuerdopago();
                $this->data['notiafic'] = $this->consultaprocesos_model->notiaportesfic();
                $this->data['notica'] = $this->consultaprocesos_model->noticontrato();
                $this->data['liquidar'] = $this->consultaprocesos_model->liquidacion();
                $this->data['ejecutaafic'] = $this->consultaprocesos_model->ejecutaficca();
                $this->data['ejecutamul'] = $this->consultaprocesos_model->ejecutamultas();
                $this->load->view("consultarprocesos/consultarprocesoadmin", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizatrazaadmin() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazaadmin')) {
                $empresa = $this->input->post('CODEMPRESA');
                $ciudad = $this->input->post('CIUDAD');
                $nombre = strtoupper($this->input->post('NOMEMPRESA'));
                $regional = $this->input->post('REGIONAL');
                $proceso = $this->input->post('PROCESO');
                $fiscaliza = $this->input->post('FISCALIZACION');
                $codgestion = $this->input->post('COD_GESTION_COBRO');
                $usuario = $this->input->post('selUsuarios');
                $usuar = strtoupper($this->input->post('USERS'));
                $this->data['datos'] = $this->consultaprocesos_model->actulizatrazaadmin($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar);
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazaadmin", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function getnombresrusuarios() {
        $usu = $this->input->get_post('REGIONAL');
        $nuevos = $this->consultaprocesos_model->getusuarios($usu);
        $option = "";
        $option = '</option> <option value = "0">Seleccione Algun Usuario</option>';
        foreach ($nuevos as $i => $nuevo)
            $option .= '<option value = "' . $i . '">' . $nuevo . '</option>';
        // echo "</select>";
        echo $option;
    }

    function consultatrazajuridico() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazajuridico')) {
//                $tales = $this->ion_auth->user()->row();
//                print_r($tales);
                $empresa = $this->input->post('CODEMPRESA');
                $this->data['datos'] = $this->consultaprocesos_model->gettrazajuridico();
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->data['usuarios'] = $this->consultaprocesos_model->getusuarios();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazajuridico", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verprocesojuridico() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/verprocesojuridico')) {
                $id = $this->input->post('id');
                $titulo = $this->input->post('titulo');

//                echo $titulo; die();
//                echo $id;
                $this->data['procesojuridico'] = $this->consultaprocesos_model->verprocesojuridico($id, $titulo);
                $this->data['avocal'] = $this->consultaprocesos_model->tituloyavoca();
                $this->data['acercal'] = $this->consultaprocesos_model->acercapersuasivo();
                $this->data['mandal'] = $this->consultaprocesos_model->mandamiento();
                $this->data['investil'] = $this->consultaprocesos_model->investigacion();
                $this->data['avaluol'] = $this->consultaprocesos_model->mcavaluo();
                $this->data['judicial'] = $this->consultaprocesos_model->projudiciales();
                $this->data['liquidacoal'] = $this->consultaprocesos_model->liquidacoactivo();
                $this->data['nulidal'] = $this->consultaprocesos_model->nulidades();
                $this->data['remisil'] = $this->consultaprocesos_model->remisibilidad();
                $this->data['rematal'] = $this->consultaprocesos_model->mcremate();
                $this->data['acuerdocoal'] = $this->consultaprocesos_model->acuerdocoactivo();
                $this->data['verifical'] = $this->consultaprocesos_model->verificacoactivo();
                $this->data['reorganizal'] = $this->consultaprocesos_model->rireorganiza();
                $this->data['liquidalregil'] = $this->consultaprocesos_model->riliquidacion();
                $this->data['trasladol'] = $this->consultaprocesos_model->trasladojudicial();
                $this->data['resolprel'] = $this->consultaprocesos_model->resolupres();
                $this->load->view("consultarprocesos/consultarprocesojuridico", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function verprocesonomisional() {

        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/verprocesonomisional')) {
                $id = $this->input->post('id');
//                echo $id;
                $this->data['procesonomisional'] = $this->consultaprocesos_model->verprocesonomisional($id);
                $this->data['nomisional'] = $this->consultaprocesos_model->nomisional();

                $this->load->view("consultarprocesos/consultarprocesonomisional", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizatrazajuridico() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazajuridico')) {
                $empresa = $this->input->post('CODEMPRESA');
                $ciudad = $this->input->post('CIUDAD');
                $nombre = strtoupper($this->input->post('NOMEMPRESA'));
                $regional = $this->input->post('REGIONAL');
                $proceso = $this->input->post('PROCESO');
                $fiscaliza = $this->input->post('FISCALIZACION');
                $codgestion = $this->input->post('COD_GESTION_COBRO');
                $usuario = $this->input->post('selUsuarios');
                $usuar = strtoupper($this->input->post('USERS'));
//                echo $usuario;die();

                $this->data['datos'] = $this->consultaprocesos_model->actulizatrazajuridico($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar);
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazajuridico", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function actualizatrazanomisional() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/actualizatrazanomisional')) {
                $empresa = $this->input->post('CODEMPRESA');
                $ciudad = $this->input->post('CIUDAD');
                $nombre = strtoupper($this->input->post('NOMEMPRESA'));
                $regional = $this->input->post('REGIONAL');
                $proceso = $this->input->post('PROCESO');
                $fiscaliza = $this->input->post('FISCALIZACION');
                $codgestion = $this->input->post('COD_GESTION_COBRO');
                $usuario = $this->input->post('selUsuarios');
                $usuar = strtoupper($this->input->post('USERS'));
//                echo $usuario;die();

                $this->data['datos'] = $this->consultaprocesos_model->actualizatrazanomisional($empresa, $ciudad, $nombre, $regional, $proceso, $fiscaliza, $usuario, $usuar);
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazanomisional", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pdfadmin($print = true) {
        $id = $this->input->post('cod_fiscalizacion');
        $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
            <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';

        $this->data['procesoadmin'] = $this->consultaprocesos_model->verprocesoadmin($id);
        $this->data['empresa'] = $this->consultaprocesos_model->verempresaproceso($id);
        foreach ($this->data['empresa'] as $col) {
            $empresa = $col["NOMBRE_PROPIETARIO"];
            $doc = $col["IDENTIFICACION"];
        }
        $fiscaliza = $this->consultaprocesos_model->fiscalizacion();
        $acuerdo = $this->consultaprocesos_model->acuerdopago();
        $notiafic = $this->consultaprocesos_model->notiaportesfic();
        $notica = $this->consultaprocesos_model->noticontrato();
        $liquidar = $this->consultaprocesos_model->liquidacion();
        $ejecutaafic = $this->consultaprocesos_model->ejecutaficca();
        $ejecutamul = $this->consultaprocesos_model->ejecutamultas();
        //  $alumnos = $this->consultaprocesos_model->getprocesosdaf();
        // print_r( $this->data['procesoadmin']);echo "-----------"; die();
//        foreach ($alumnos as $alumno) {
//            $html = $html . '<tr><td>' . $alumno["CODPROCESO"] . '</td><td>' . $alumno["NOMBREPROCESO"] . '</td></tr>';
//        }
        //  $html = '<table>' . $html . '</table>';

        $fiscalic = array();
        $fisca = $fiscalizacion = array();
        foreach ($fiscaliza as $fis) {
            $i = 0;
            if (!in_array($fis['COD_TIPO_INSTANCIA'], $fisca)) {
                $fiscalic[] = $fis['NOMBRE_TIPO_INSTANCIA'];
                $fisca[] = $fis['COD_TIPO_INSTANCIA'];
            }
        }

        $acuerdoc = array();
        $acue = $acuerdopago = array();
        foreach ($acuerdo as $acu) {
            $i = 0;
            if (!in_array($acu['COD_TIPO_INSTANCIA'], $acue)) {
                $acuerdoc[] = $acu['NOMBRE_TIPO_INSTANCIA'];
                $acue[] = $acu['COD_TIPO_INSTANCIA'];
            }
        }

        $nafic = array();
        $nafi = $notificaapofic = array();
        foreach ($notiafic as $naf) {
            $i = 0;
            if (!in_array($naf['COD_TIPO_INSTANCIA'], $nafi)) {
                $nafic[] = $naf['NOMBRE_TIPO_INSTANCIA'];
                $nafi[] = $naf['COD_TIPO_INSTANCIA'];
            }
        }

        $notic = array();
        $noti = $noticapren = array();
        foreach ($notica as $not) {
            $i = 0;
            if (!in_array($not['COD_TIPO_INSTANCIA'], $noti)) {
                $notic[] = $not['NOMBRE_TIPO_INSTANCIA'];
                $noti[] = $not['COD_TIPO_INSTANCIA'];
            }
        }

        $liquic = array();
        $liqu = $liquidaran = array();
        foreach ($liquidar as $liq) {
            $i = 0;
            if (!in_array($liq['COD_TIPO_INSTANCIA'], $liqu)) {
                $liquic[] = $liq['NOMBRE_TIPO_INSTANCIA'];
                $liqu[] = $liq['COD_TIPO_INSTANCIA'];
            }
        }

        $ejeafic = array();
        $ejea = $ejeafica = array();
        foreach ($ejecutaafic as $ejf) {
            $i = 0;
            if (!in_array($ejf['COD_TIPO_INSTANCIA'], $ejea)) {
                $ejeafic[] = $ejf['NOMBRE_TIPO_INSTANCIA'];
                $ejea[] = $ejf['COD_TIPO_INSTANCIA'];
            }
        }

        $ejemult = array();
        $ejem = $ejemulta = array();
        foreach ($ejecutamul as $ejm) {
            $i = 0;
            if (!in_array($ejm['COD_TIPO_INSTANCIA'], $ejem)) {
                $ejemult[] = $ejm['NOMBRE_TIPO_INSTANCIA'];
                $ejem[] = $ejm['COD_TIPO_INSTANCIA'];
            }
        }

        $html = '';
        $html2 = '';


        $columna = '';
        foreach ($this->data['procesoadmin'] as $colu) {


            $html .= '<tr>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["FECHA_CONTACTO"] . '</td>';
//                    . '<td align="center" style="vertical-align:middle">' . $colu["NIT_EMPRESA"] . '</td>'
//                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_EMPRESA"] . '</td>'
//            if ($colu['COD_TIPO_PROCESO'] == 1) {
//                $html.='<td align="center" style="vertical-align:middle">';
////                     echo count($fiscalic);
////                     print_r($fiscalic);
////                     die;
//                if (count($fiscalic) > 1) {
//                    $salto = "<br>";
//                } else {
//                    $salto = "";
//                }
//                foreach ($fiscalic as $fis) {
//                    $html.='- ' . $fis . $salto;
//                }
//                $html.='</td>';
//            } else if ($colu['COD_TIPO_PROCESO'] == 2) {
//                $html.='<td align="center" style="vertical-align:middle">';
////                     echo count($fiscalic);
////                     print_r($fiscalic);
////                     die;
//                if (count($acuerdoc) > 1) {
//                    $salto = "<br>";
//                } else {
//                    $salto = "";
//                }
//                foreach ($acuerdoc as $acu) {
//                    $html.='- ' . $acu . $salto;
//                }
//                $html.='</td>';
//            } else {
//                $html.='<td>MIERDA</td>';
//            }

            switch ($colu['COD_TIPO_PROCESO']) {
                case 1:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($fiscalic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($fiscalic as $fis) {
                        $html.='- ' . $fis . $salto;
                    }
                    $html.='</td>';
                    break;
                case 2:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($acuerdoc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($acuerdoc as $acu) {
                        $html.='- ' . $acu . $salto;
                    }
                    $html.='</td>';
                    break;
                case 3:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nafic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nafic as $naf) {
                        $html.='- ' . $naf . $salto;
                    }
                    $html.='</td>';
                    break;
                case 4:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($notic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($notic as $not) {
                        $html.='- ' . $not . $salto;
                    }
                    $html.='</td>';
                    break;
                case 5:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($notic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($notic as $not) {
                        $html.='- ' . $not . $salto;
                    }
                    $html.='</td>';
                    break;
                case 6:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($ejeafic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($ejeafic as $ejea) {
                        $html.='- ' . $ejea . $salto;
                    }
                    $html.='</td>';
                    break;
                case 7:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($ejemult) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($ejemult as $ejm) {
                        $html.='- ' . $ejm . $salto;
                    }
                    $html.='</td>';
                    break;
                default:
                    $html.='<td></td>';
                    break;
            }


            $html.='<td align="center" style="vertical-align:middle">' . $colu["TIPO_PROCESO"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["TIPOGESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_GESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] . '</td>';
            if (base64_encode(base64_decode($colu['COMENTARIOS'])) === $colu['COMENTARIOS']) {
//                        echo 'Esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . base64_decode($colu['COMENTARIOS']) . '</td>';
            } else {
//                        echo 'No esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . $colu['COMENTARIOS'] . '</td>';
            }

            $html.= '</tr>';

            $empresa = $colu["RAZON_SOCIAL"];
            $doc = $colu["NIT_EMPRESA"];
        }

        $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>'
                . '<table border="1">' . $encabezado . $html . '</table></html>';
        $name = 'ejemplos';
//        $html=  utf8_decode($html);
//        $html="hola mundo";
//          echo $html;die();

        $this->load->library("tcpdf/tcpdf");

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "Servicio Nacional de Aprendizaje SENA", "Informe Traza " . " Elaborado el Dia " . date('d/m/y') . "                                                                                   " . "Nombre. " . $empresa . "   " . "Documento. " . $doc . "" . " ");
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->SetHeaderMargin(o);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        if ($print) {
            $js = '
                print();
                ';

            $pdf->IncludeJS($js);
        }

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

    function pdfjuridico($print = true) {
        $id = $this->input->post('cod_fiscalizacion');
        $titulo = $this->input->post('cod_titulo');
        if ($id != '' && $titulo == '') {
            $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
            <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';

            $this->data['procesojuridico'] = $this->consultaprocesos_model->verprocesojuridico($id, $titulo);
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesojuridico($id);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
        } else {
            $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
            <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';
            $this->data['procesojuridico'] = $this->consultaprocesos_model->verprocesojuridico($id, $titulo);
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesojuridicotitulo($titulo);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
        }
        $avocal = $this->consultaprocesos_model->tituloyavoca();
        $acercal = $this->consultaprocesos_model->acercapersuasivo();
        $mandal = $this->consultaprocesos_model->mandamiento();
        $investil = $this->consultaprocesos_model->investigacion();
        $avaluol = $this->consultaprocesos_model->mcavaluo();
        $judicial = $this->consultaprocesos_model->projudiciales();
        $liquidacoal = $this->consultaprocesos_model->liquidacoactivo();
        $nulidal = $this->consultaprocesos_model->nulidades();
        $remisil = $this->consultaprocesos_model->remisibilidad();
        $rematal = $this->consultaprocesos_model->mcremate();
        $acuerdocoal = $this->consultaprocesos_model->acuerdocoactivo();
        $verifical = $this->consultaprocesos_model->verificacoactivo();
        $reorganizal = $this->consultaprocesos_model->rireorganiza();
        $liquidalregil = $this->consultaprocesos_model->riliquidacion();
        $trasladol = $this->consultaprocesos_model->trasladojudicial();
        $resolprel = $this->consultaprocesos_model->resolupres();
        //  $alumnos = $this->consultaprocesos_model->getprocesosdaf();

        $avocac = array();
        $avoca = $tituloyavoca = array();
        foreach ($avocal as $avo) {
            $i = 0;
            if (!in_array($avo['COD_TIPO_INSTANCIA'], $avoca)) {
                $avocac[] = $avo['NOMBRE_TIPO_INSTANCIA'];
                $avoca[] = $avo['COD_TIPO_INSTANCIA'];
            }
        }

        $acercac = array();
        $acerca = $acercapersua = array();
        foreach ($acercal as $ace) {
            $i = 0;
            if (!in_array($ace['COD_TIPO_INSTANCIA'], $acerca)) {
                $acercac[] = $ace['NOMBRE_TIPO_INSTANCIA'];
                $acerca[] = $ace['COD_TIPO_INSTANCIA'];
            }
        }

        $mandac = array();
        $manda = $mandapago = array();
        foreach ($mandal as $man) {
            $i = 0;
            if (!in_array($man['COD_TIPO_INSTANCIA'], $manda)) {
                $mandac[] = $man['NOMBRE_TIPO_INSTANCIA'];
                $manda[] = $man['COD_TIPO_INSTANCIA'];
            }
        }

        $invenc = array();
        $inven = $investiga = array();
        foreach ($investil as $inv) {
            $i = 0;
            if (!in_array($inv['COD_TIPO_INSTANCIA'], $inven)) {
                $invenc[] = $inv['NOMBRE_TIPO_INSTANCIA'];
                $inven[] = $inv['COD_TIPO_INSTANCIA'];
            }
        }

        $avaluoc = array();
        $aval = $mcavaluo = array();
        foreach ($avaluol as $ava) {
            $i = 0;
            if (!in_array($ava['COD_TIPO_INSTANCIA'], $aval)) {
                $avaluoc[] = $ava['NOMBRE_TIPO_INSTANCIA'];
                $aval[] = $ava['COD_TIPO_INSTANCIA'];
            }
        }

        $judicic = array();
        $judil = $projudil = array();
        foreach ($judicial as $jud) {
            $i = 0;
            if (!in_array($jud['COD_TIPO_INSTANCIA'], $judil)) {
                $judicic[] = $jud['NOMBRE_TIPO_INSTANCIA'];
                $judil[] = $jud['COD_TIPO_INSTANCIA'];
            }
        }

        $liquicoac = array();
        $liquicoal = $liquidacoa = array();
        foreach ($liquidacoal as $lic) {
            $i = 0;
            if (!in_array($lic['COD_TIPO_INSTANCIA'], $liquicoal)) {
                $liquicoac[] = $lic['NOMBRE_TIPO_INSTANCIA'];
                $liquicoal[] = $lic['COD_TIPO_INSTANCIA'];
            }
        }

        $nulidac = array();
        $nulidalal = $nulidades = array();
        foreach ($nulidal as $nul) {
            $i = 0;
            if (!in_array($nul['COD_TIPO_INSTANCIA'], $nulidalal)) {
                $nulidac[] = $nul['NOMBRE_TIPO_INSTANCIA'];
                $nulidalal[] = $nul['COD_TIPO_INSTANCIA'];
            }
        }

        $remicibic = array();
        $remisibil = $remisibilidad = array();
        foreach ($remisil as $rem) {
            $i = 0;
            if (!in_array($rem['COD_TIPO_INSTANCIA'], $remisibil)) {
                $remicibic[] = $rem['NOMBRE_TIPO_INSTANCIA'];
                $remisibil[] = $rem['COD_TIPO_INSTANCIA'];
            }
        }

        $rematac = array();
        $rematela = $mcremata = array();
        foreach ($rematal as $rema) {
            $i = 0;
            if (!in_array($rema['COD_TIPO_INSTANCIA'], $rematela)) {
                $rematac[] = $rema['NOMBRE_TIPO_INSTANCIA'];
                $rematela[] = $rema['COD_TIPO_INSTANCIA'];
            }
        }

        $acuerdocoac = array();
        $acuerdocoala = $acuerdacoa = array();
        foreach ($acuerdocoal as $aco) {
            $i = 0;
            if (!in_array($aco['COD_TIPO_INSTANCIA'], $acuerdocoala)) {
                $acuerdocoac[] = $aco['NOMBRE_TIPO_INSTANCIA'];
                $acuerdocoala[] = $aco['COD_TIPO_INSTANCIA'];
            }
        }

        $verificacoac = array();
        $verificacoal = $verificacoa = array();
        foreach ($verifical as $ver) {
            $i = 0;
            if (!in_array($ver['COD_TIPO_INSTANCIA'], $verificacoal)) {
                $verificacoac[] = $ver['NOMBRE_TIPO_INSTANCIA'];
                $verificacoal[] = $ver['COD_TIPO_INSTANCIA'];
            }
        }

        $reorganizacioc = array();
        $reorganizaciol = $reorganizacio = array();
        foreach ($reorganizal as $reo) {
            $i = 0;
            if (!in_array($reo['COD_TIPO_INSTANCIA'], $reorganizaciol)) {
                $reorganizacioc[] = $reo['NOMBRE_TIPO_INSTANCIA'];
                $reorganizaciol[] = $reo['COD_TIPO_INSTANCIA'];
            }
        }

        $liquidaregic = array();
        $liquidaregil = $liquidaregimen = array();
        foreach ($liquidalregil as $lir) {
            $i = 0;
            if (!in_array($lir['COD_TIPO_INSTANCIA'], $liquidaregil)) {
                $liquidaregic[] = $lir['NOMBRE_TIPO_INSTANCIA'];
                $liquidaregil[] = $lir['COD_TIPO_INSTANCIA'];
            }
        }

        $traslac = array();
        $trasla = $traslado = array();
        foreach ($trasladol as $tra) {
            $i = 0;
            if (!in_array($tra['COD_TIPO_INSTANCIA'], $trasla)) {
                $traslac[] = $tra['NOMBRE_TIPO_INSTANCIA'];
                $trasla[] = $tra['COD_TIPO_INSTANCIA'];
            }
        }

        $resolac = array();
        $resola = $resolado = array();
        foreach ($resolprel as $res) {
            $i = 0;
            if (!in_array($res['COD_TIPO_INSTANCIA'], $resola)) {
                $resolac[] = $res['NOMBRE_TIPO_INSTANCIA'];
                $resola[] = $res['COD_TIPO_INSTANCIA'];
            }
        }

        $html = '';
        $html2 = '';


        $columna = '';
        foreach ($this->data['procesojuridico'] as $colu) {


            $html .= '<tr>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["FECHA"] . '</td>';
            switch ($colu['COD_TIPO_PROCESO']) {
                case 8:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($avocac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($avocac as $avo) {
                        $html.='- ' . $avo . $salto;
                    }
                    $html.='</td>';
                    break;
                case 9:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($acercac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($acercac as $ace) {
                        $html.='- ' . $ace . $salto;
                    }
                    $html.='</td>';
                    break;
                case 10:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($mandac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($mandac as $man) {
                        $html.='- ' . $man . $salto;
                    }
                    $html.='</td>';
                    break;
                case 11:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($invenc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($invenc as $inv) {
                        $html.='- ' . $inv . $salto;
                    }
                    $html.='</td>';
                    break;
                case 12:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($avaluoc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($avaluoc as $ava) {
                        $html.='- ' . $ava . $salto;
                    }
                    $html.='</td>';
                    break;
                case 13:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($judicic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($judicic as $jud) {
                        $html.='- ' . $jud . $salto;
                    }
                    $html.='</td>';
                    break;
                case 14:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($liquicoac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($liquicoac as $lic) {
                        $html.='- ' . $lic . $salto;
                    }
                    $html.='</td>';
                    break;
                case 15:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nulidac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nulidac as $nul) {
                        $html.='- ' . $nul . $salto;
                    }
                    $html.='</td>';
                    break;
                case 16:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($remicibic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($remicibic as $rem) {
                        $html.='- ' . $rem . $salto;
                    }
                    $html.='</td>';
                    break;
                case 17:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($rematac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($rematac as $rema) {
                        $html.='- ' . $rema . $salto;
                    }
                    $html.='</td>';
                    break;
                case 18:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($acuerdocoac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($acuerdocoac as $aco) {
                        $html.='- ' . $aco . $salto;
                    }
                    $html.='</td>';
                    break;
                case 19:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($verificacoac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($verificacoac as $ver) {
                        $html.='- ' . $ver . $salto;
                    }
                    $html.='</td>';
                    break;
                case 20:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($reorganizacioc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($reorganizacioc as $reo) {
                        $html.='- ' . $reo . $salto;
                    }
                    $html.='</td>';
                    break;
                case 21:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($liquidaregic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($liquidaregic as $lir) {
                        $html.='- ' . $lir . $salto;
                    }
                    $html.='</td>';
                    break;
                case 22:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($traslac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($traslac as $tra) {
                        $html.='- ' . $tra . $salto;
                    }
                    $html.='</td>';
                    break;
                case 23:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($resolac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($resolac as $res) {
                        $html.='- ' . $res . $salto;
                    }
                    $html.='</td>';
                    break;
                default:
                    $html.='<td></td>';
                    break;
            }


            $html.='<td align="center" style="vertical-align:middle">' . $colu["TIPO_PROCESO"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["TIPOGESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_GESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] . '</td>';
            if (base64_encode(base64_decode($colu['COMENTARIOS'])) === $colu['COMENTARIOS']) {
//                        echo 'Esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . base64_decode($colu['COMENTARIOS']) . '</td>';
            } else {
//                        echo 'No esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . $colu['COMENTARIOS'] . '</td>';
            }
            $html.= '</tr>';

            $empresa = $colu["NOMBRE_PROPIETARIO"];
            $doc = $colu["IDENTIFICACION"];
        }

        $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>'
                . '<table border="1">' . $encabezado . $html . '</table></html>';
        $name = 'ejemplos';
//        $html=  utf8_decode($html);
//        $html="hola mundo";
//          echo $html;die();
//        
        $this->load->library("tcpdf/tcpdf");

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "Servicio Nacional de Aprendizaje SENA", "Informe Traza " . " Elaborado el Dia " . date('d/m/y') . "                                                                                   " . "Nombre. " . $empresa . "   " . "Documento. " . $doc . "" . " ");
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->SetHeaderMargin(o);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        if ($print) {
            $js = '
                print();
                ';

            $pdf->IncludeJS($js);
        }

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

    function pdfproceso($print = true) {

        $id = $this->input->post('cod_fiscalizacion');
        $titulo = $this->input->post('cod_titulo');
        $vista = $this->input->post('vista');

        $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
            <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';

        $this->data['procesoadmin'] = $this->consultaprocesos_model->verproceso($id, $titulo, $vista);

        if ($vista == 'AD') {
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaproceso($id);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
        }

        if ($vista == 'JR') {
            if ($id != '' && $titulo == '' || $titulo == 'Titulos Asignados Al Proceso') {
                $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesojuridico($id);
                foreach ($this->data['empresa'] as $col) {
                    $empresa = $col["NOMBRE_PROPIETARIO"];
                    $doc = $col["IDENTIFICACION"];
                }
            }else{
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesojuridicotitulo($titulo);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
            }
        }

        if ($vista == 'DV') {
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesodevolucion($id);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
        }

        if ($vista == 'NM') {
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesonomisional($id);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
        }

        if ($vista == 'PJ') {
            $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesojudicial($id);
            foreach ($this->data['empresa'] as $col) {
                $empresa = $col["NOMBRE_PROPIETARIO"];
                $doc = $col["IDENTIFICACION"];
            }
        }

        $fiscaliza = $this->consultaprocesos_model->fiscalizacion();
        $acuerdo = $this->consultaprocesos_model->acuerdopago();
        $notiafic = $this->consultaprocesos_model->notiaportesfic();
        $notica = $this->consultaprocesos_model->noticontrato();
        $liquidar = $this->consultaprocesos_model->liquidacion();
        $ejecutaafic = $this->consultaprocesos_model->ejecutaficca();
        $ejecutamul = $this->consultaprocesos_model->ejecutamultas();
        $avocal = $this->consultaprocesos_model->tituloyavoca();
        $acercal = $this->consultaprocesos_model->acercapersuasivo();
        $mandal = $this->consultaprocesos_model->mandamiento();
        $investil = $this->consultaprocesos_model->investigacion();
        $avaluol = $this->consultaprocesos_model->mcavaluo();
        $judicial = $this->consultaprocesos_model->projudiciales();
        $liquidacoal = $this->consultaprocesos_model->liquidacoactivo();
        $nulidal = $this->consultaprocesos_model->nulidades();
        $remisil = $this->consultaprocesos_model->remisibilidad();
        $rematal = $this->consultaprocesos_model->mcremate();
        $acuerdocoal = $this->consultaprocesos_model->acuerdocoactivo();
        $verifical = $this->consultaprocesos_model->verificacoactivo();
        $reorganizal = $this->consultaprocesos_model->rireorganiza();
        $liquidalregil = $this->consultaprocesos_model->riliquidacion();
        $trasladol = $this->consultaprocesos_model->trasladojudicial();
        $resolprel = $this->consultaprocesos_model->resolupres();
        $devolucionrnm = $this->consultaprocesos_model->devolucionrnm();
        $devoluciondg = $this->consultaprocesos_model->devoluciondg();
        $devolucionrm = $this->consultaprocesos_model->devolucionrm();
        $nomisional = $this->consultaprocesos_model->nomisional();
        //  $alumnos = $this->consultaprocesos_model->getprocesosdaf();
        // print_r( $this->data['procesoadmin']);echo "-----------"; die();
//        foreach ($alumnos as $alumno) {
//            $html = $html . '<tr><td>' . $alumno["CODPROCESO"] . '</td><td>' . $alumno["NOMBREPROCESO"] . '</td></tr>';
//        }
        //  $html = '<table>' . $html . '</table>';

        $nomic = array();
        $nomiso = $nomision = array();
        foreach ($nomisional as $pnm) {
            $i = 0;
            if (!in_array($pnm['COD_TIPO_INSTANCIA'], $nomiso)) {
                $nomic[] = $pnm['NOMBRE_TIPO_INSTANCIA'];
                $nomiso[] = $pnm['COD_TIPO_INSTANCIA'];
            }
        }

        $devrnmc = array();
        $devrnm = $devoluciornm = array();
        foreach ($devolucionrnm as $drnm) {
            $i = 0;
            if (!in_array($drnm['COD_TIPO_INSTANCIA'], $devrnm)) {
                $devrnmc[] = $drnm['NOMBRE_TIPO_INSTANCIA'];
                $devrnm[] = $drnm['COD_TIPO_INSTANCIA'];
            }
        }

        $devdgc = array();
        $devdg = $devoluciodg = array();
        foreach ($devoluciondg as $ddg) {
            $i = 0;
            if (!in_array($ddg['COD_TIPO_INSTANCIA'], $devdg)) {
                $devdgc[] = $ddg['NOMBRE_TIPO_INSTANCIA'];
                $devdg[] = $ddg['COD_TIPO_INSTANCIA'];
            }
        }

        $devrmc = array();
        $devrm = $devoluciorm = array();
        foreach ($devolucionrm as $drm) {
            $i = 0;
            if (!in_array($drm['COD_TIPO_INSTANCIA'], $devrm)) {
                $devrmc[] = $drm['NOMBRE_TIPO_INSTANCIA'];
                $devrm[] = $drm['COD_TIPO_INSTANCIA'];
            }
        }

        $fiscalic = array();
        $fisca = $fiscalizacion = array();
        foreach ($fiscaliza as $fis) {
            $i = 0;
            if (!in_array($fis['COD_TIPO_INSTANCIA'], $fisca)) {
                $fiscalic[] = $fis['NOMBRE_TIPO_INSTANCIA'];
                $fisca[] = $fis['COD_TIPO_INSTANCIA'];
            }
        }

        $acuerdoc = array();
        $acue = $acuerdopago = array();
        foreach ($acuerdo as $acu) {
            $i = 0;
            if (!in_array($acu['COD_TIPO_INSTANCIA'], $acue)) {
                $acuerdoc[] = $acu['NOMBRE_TIPO_INSTANCIA'];
                $acue[] = $acu['COD_TIPO_INSTANCIA'];
            }
        }

        $nafic = array();
        $nafi = $notificaapofic = array();
        foreach ($notiafic as $naf) {
            $i = 0;
            if (!in_array($naf['COD_TIPO_INSTANCIA'], $nafi)) {
                $nafic[] = $naf['NOMBRE_TIPO_INSTANCIA'];
                $nafi[] = $naf['COD_TIPO_INSTANCIA'];
            }
        }

        $notic = array();
        $noti = $noticapren = array();
        foreach ($notica as $not) {
            $i = 0;
            if (!in_array($not['COD_TIPO_INSTANCIA'], $noti)) {
                $notic[] = $not['NOMBRE_TIPO_INSTANCIA'];
                $noti[] = $not['COD_TIPO_INSTANCIA'];
            }
        }

        $liquic = array();
        $liqu = $liquidaran = array();
        foreach ($liquidar as $liq) {
            $i = 0;
            if (!in_array($liq['COD_TIPO_INSTANCIA'], $liqu)) {
                $liquic[] = $liq['NOMBRE_TIPO_INSTANCIA'];
                $liqu[] = $liq['COD_TIPO_INSTANCIA'];
            }
        }

        $ejeafic = array();
        $ejea = $ejeafica = array();
        foreach ($ejecutaafic as $ejf) {
            $i = 0;
            if (!in_array($ejf['COD_TIPO_INSTANCIA'], $ejea)) {
                $ejeafic[] = $ejf['NOMBRE_TIPO_INSTANCIA'];
                $ejea[] = $ejf['COD_TIPO_INSTANCIA'];
            }
        }

        $ejemult = array();
        $ejem = $ejemulta = array();
        foreach ($ejecutamul as $ejm) {
            $i = 0;
            if (!in_array($ejm['COD_TIPO_INSTANCIA'], $ejem)) {
                $ejemult[] = $ejm['NOMBRE_TIPO_INSTANCIA'];
                $ejem[] = $ejm['COD_TIPO_INSTANCIA'];
            }
        }

        $avocac = array();
        $avoca = $tituloyavoca = array();
        foreach ($avocal as $avo) {
            $i = 0;
            if (!in_array($avo['COD_TIPO_INSTANCIA'], $avoca)) {
                $avocac[] = $avo['NOMBRE_TIPO_INSTANCIA'];
                $avoca[] = $avo['COD_TIPO_INSTANCIA'];
            }
        }

        $acercac = array();
        $acerca = $acercapersua = array();
        foreach ($acercal as $ace) {
            $i = 0;
            if (!in_array($ace['COD_TIPO_INSTANCIA'], $acerca)) {
                $acercac[] = $ace['NOMBRE_TIPO_INSTANCIA'];
                $acerca[] = $ace['COD_TIPO_INSTANCIA'];
            }
        }

        $mandac = array();
        $manda = $mandapago = array();
        foreach ($mandal as $man) {
            $i = 0;
            if (!in_array($man['COD_TIPO_INSTANCIA'], $manda)) {
                $mandac[] = $man['NOMBRE_TIPO_INSTANCIA'];
                $manda[] = $man['COD_TIPO_INSTANCIA'];
            }
        }

        $invenc = array();
        $inven = $investiga = array();
        foreach ($investil as $inv) {
            $i = 0;
            if (!in_array($inv['COD_TIPO_INSTANCIA'], $inven)) {
                $invenc[] = $inv['NOMBRE_TIPO_INSTANCIA'];
                $inven[] = $inv['COD_TIPO_INSTANCIA'];
            }
        }

        $avaluoc = array();
        $aval = $mcavaluo = array();
        foreach ($avaluol as $ava) {
            $i = 0;
            if (!in_array($ava['COD_TIPO_INSTANCIA'], $aval)) {
                $avaluoc[] = $ava['NOMBRE_TIPO_INSTANCIA'];
                $aval[] = $ava['COD_TIPO_INSTANCIA'];
            }
        }

        $judicic = array();
        $judil = $projudil = array();
        foreach ($judicial as $jud) {
            $i = 0;
            if (!in_array($jud['COD_TIPO_INSTANCIA'], $judil)) {
                $judicic[] = $jud['NOMBRE_TIPO_INSTANCIA'];
                $judil[] = $jud['COD_TIPO_INSTANCIA'];
            }
        }

        $liquicoac = array();
        $liquicoal = $liquidacoa = array();
        foreach ($liquidacoal as $lic) {
            $i = 0;
            if (!in_array($lic['COD_TIPO_INSTANCIA'], $liquicoal)) {
                $liquicoac[] = $lic['NOMBRE_TIPO_INSTANCIA'];
                $liquicoal[] = $lic['COD_TIPO_INSTANCIA'];
            }
        }

        $nulidac = array();
        $nulidalal = $nulidades = array();
        foreach ($nulidal as $nul) {
            $i = 0;
            if (!in_array($nul['COD_TIPO_INSTANCIA'], $nulidalal)) {
                $nulidac[] = $nul['NOMBRE_TIPO_INSTANCIA'];
                $nulidalal[] = $nul['COD_TIPO_INSTANCIA'];
            }
        }

        $remicibic = array();
        $remisibil = $remisibilidad = array();
        foreach ($remisil as $rem) {
            $i = 0;
            if (!in_array($rem['COD_TIPO_INSTANCIA'], $remisibil)) {
                $remicibic[] = $rem['NOMBRE_TIPO_INSTANCIA'];
                $remisibil[] = $rem['COD_TIPO_INSTANCIA'];
            }
        }

        $rematac = array();
        $rematela = $mcremata = array();
        foreach ($rematal as $rema) {
            $i = 0;
            if (!in_array($rema['COD_TIPO_INSTANCIA'], $rematela)) {
                $rematac[] = $rema['NOMBRE_TIPO_INSTANCIA'];
                $rematela[] = $rema['COD_TIPO_INSTANCIA'];
            }
        }

        $acuerdocoac = array();
        $acuerdocoala = $acuerdacoa = array();
        foreach ($acuerdocoal as $aco) {
            $i = 0;
            if (!in_array($aco['COD_TIPO_INSTANCIA'], $acuerdocoala)) {
                $acuerdocoac[] = $aco['NOMBRE_TIPO_INSTANCIA'];
                $acuerdocoala[] = $aco['COD_TIPO_INSTANCIA'];
            }
        }

        $verificacoac = array();
        $verificacoal = $verificacoa = array();
        foreach ($verifical as $ver) {
            $i = 0;
            if (!in_array($ver['COD_TIPO_INSTANCIA'], $verificacoal)) {
                $verificacoac[] = $ver['NOMBRE_TIPO_INSTANCIA'];
                $verificacoal[] = $ver['COD_TIPO_INSTANCIA'];
            }
        }

        $reorganizacioc = array();
        $reorganizaciol = $reorganizacio = array();
        foreach ($reorganizal as $reo) {
            $i = 0;
            if (!in_array($reo['COD_TIPO_INSTANCIA'], $reorganizaciol)) {
                $reorganizacioc[] = $reo['NOMBRE_TIPO_INSTANCIA'];
                $reorganizaciol[] = $reo['COD_TIPO_INSTANCIA'];
            }
        }

        $liquidaregic = array();
        $liquidaregil = $liquidaregimen = array();
        foreach ($liquidalregil as $lir) {
            $i = 0;
            if (!in_array($lir['COD_TIPO_INSTANCIA'], $liquidaregil)) {
                $liquidaregic[] = $lir['NOMBRE_TIPO_INSTANCIA'];
                $liquidaregil[] = $lir['COD_TIPO_INSTANCIA'];
            }
        }

        $traslac = array();
        $trasla = $traslado = array();
        foreach ($trasladol as $tra) {
            $i = 0;
            if (!in_array($tra['COD_TIPO_INSTANCIA'], $trasla)) {
                $traslac[] = $tra['NOMBRE_TIPO_INSTANCIA'];
                $trasla[] = $tra['COD_TIPO_INSTANCIA'];
            }
        }

        $resolac = array();
        $resola = $resolado = array();
        foreach ($resolprel as $res) {
            $i = 0;
            if (!in_array($res['COD_TIPO_INSTANCIA'], $resola)) {
                $resolac[] = $res['NOMBRE_TIPO_INSTANCIA'];
                $resola[] = $res['COD_TIPO_INSTANCIA'];
            }
        }

        $html = '';
        $html2 = '';


        $columna = '';
        foreach ($this->data['procesoadmin'] as $colu) {


            $html .= '<tr>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["FECHA"] . '</td>';
//                    . '<td align="center" style="vertical-align:middle">' . $colu["NIT_EMPRESA"] . '</td>'
//                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_EMPRESA"] . '</td>'
//            if ($colu['COD_TIPO_PROCESO'] == 1) {
//                $html.='<td align="center" style="vertical-align:middle">';
////                     echo count($fiscalic);
////                     print_r($fiscalic);
////                     die;
//                if (count($fiscalic) > 1) {
//                    $salto = "<br>";
//                } else {
//                    $salto = "";
//                }
//                foreach ($fiscalic as $fis) {
//                    $html.='- ' . $fis . $salto;
//                }
//                $html.='</td>';
//            } else if ($colu['COD_TIPO_PROCESO'] == 2) {
//                $html.='<td align="center" style="vertical-align:middle">';
////                     echo count($fiscalic);
////                     print_r($fiscalic);
////                     die;
//                if (count($acuerdoc) > 1) {
//                    $salto = "<br>";
//                } else {
//                    $salto = "";
//                }
//                foreach ($acuerdoc as $acu) {
//                    $html.='- ' . $acu . $salto;
//                }
//                $html.='</td>';
//            } else {
//                $html.='<td>MIERDA</td>';
//            }
            $tal = count($fiscalic);
            switch ($colu['COD_TIPO_PROCESO']) {
                case 1:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($fiscalic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($fiscalic as $fis) {
                        $html.='- ' . $fis . $salto;
                    }
                    $html.='</td>';
                    break;
                case 2:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($acuerdoc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($acuerdoc as $acu) {
                        $html.='- ' . $acu . $salto;
                    }
                    $html.='</td>';
                    break;
                case 3:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nafic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nafic as $naf) {
                        $html.='- ' . $naf . $salto;
                    }
                    $html.='</td>';
                    break;
                case 4:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($notic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($notic as $not) {
                        $html.='- ' . $not . $salto;
                    }
                    $html.='</td>';
                    break;
                case 5:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($notic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($notic as $not) {
                        $html.='- ' . $not . $salto;
                    }
                    $html.='</td>';
                    break;
                case 6:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($ejeafic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($ejeafic as $ejea) {
                        $html.='- ' . $ejea . $salto;
                    }
                    $html.='</td>';
                    break;
                case 7:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($ejemult) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($ejemult as $ejm) {
                        $html.='- ' . $ejm . $salto;
                    }
                    $html.='</td>';
                    break;
                case 8:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($avocac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($avocac as $avo) {
                        $html.='- ' . $avo . $salto;
                    }
                    $html.='</td>';
                    break;
                case 9:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($acercac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($acercac as $ace) {
                        $html.='- ' . $ace . $salto;
                    }
                    $html.='</td>';
                    break;
                case 10:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($mandac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($mandac as $man) {
                        $html.='- ' . $man . $salto;
                    }
                    $html.='</td>';
                    break;
                case 11:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($invenc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($invenc as $inv) {
                        $html.='- ' . $inv . $salto;
                    }
                    $html.='</td>';
                    break;
                case 12:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($avaluoc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($avaluoc as $ava) {
                        $html.='- ' . $ava . $salto;
                    }
                    $html.='</td>';
                    break;
                case 13:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($judicic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($judicic as $jud) {
                        $html.='- ' . $jud . $salto;
                    }
                    $html.='</td>';
                    break;
                case 14:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($liquicoac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($liquicoac as $lic) {
                        $html.='- ' . $lic . $salto;
                    }
                    $html.='</td>';
                    break;
                case 15:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nulidac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nulidac as $nul) {
                        $html.='- ' . $nul . $salto;
                    }
                    $html.='</td>';
                    break;
                case 16:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($remicibic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($remicibic as $rem) {
                        $html.='- ' . $rem . $salto;
                    }
                    $html.='</td>';
                    break;
                case 17:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($rematac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($rematac as $rema) {
                        $html.='- ' . $rema . $salto;
                    }
                    $html.='</td>';
                    break;
                case 18:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($acuerdocoac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($acuerdocoac as $aco) {
                        $html.='- ' . $aco . $salto;
                    }
                    $html.='</td>';
                    break;
                case 19:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($verificacoac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($verificacoac as $ver) {
                        $html.='- ' . $ver . $salto;
                    }
                    $html.='</td>';
                    break;
                case 20:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($reorganizacioc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($reorganizacioc as $reo) {
                        $html.='- ' . $reo . $salto;
                    }
                    $html.='</td>';
                    break;
                case 21:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($liquidaregic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($liquidaregic as $lir) {
                        $html.='- ' . $lir . $salto;
                    }
                    $html.='</td>';
                    break;
                case 22:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($traslac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($traslac as $tra) {
                        $html.='- ' . $tra . $salto;
                    }
                    $html.='</td>';
                    break;
                case 23:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($resolac) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($resolac as $res) {
                        $html.='- ' . $res . $salto;
                    }
                    $html.='</td>';
                    break;
                case 24:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($devrnmc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($devrnmc as $drnm) {
                        $html.='- ' . $drnm . $salto;
                    }
                    $html.='</td>';
                    break;
                case 25:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($devdgc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($devdgc as $ddg) {
                        $html.='- ' . $ddg . $salto;
                    }
                    $html.='</td>';
                    break;
                case 26:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($devrmc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($devrmc as $drm) {
                        $html.='- ' . $drm . $salto;
                    }
                    $html.='</td>';
                    break;
                case 27:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nomisional) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nomic as $pnm) {
                        $html.='- ' . $pnm . $salto;
                    }
                    $html.='</td>';
                    break;
                default:
                    $html.='<td></td>';
                    break;
            }


            $html.='<td align="center" style="vertical-align:middle">' . $colu["TIPO_PROCESO"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["TIPOGESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_GESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] . '</td>';
            if (base64_encode(base64_decode($colu['COMENTARIOS'])) === $colu['COMENTARIOS']) {
//                        echo 'Esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . base64_decode($colu['COMENTARIOS']) . '</td>';
            } else {
//                        echo 'No esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . $colu['COMENTARIOS'] . '</td>';
            }
            $html.= '</tr>';

            $empresa = $colu["NOMEMPRESA"];
            $doc = $colu["COD_EMPRESA"];
        }

        $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>'
                . '<table border="1">' . $encabezado . $html . '</table></html>';
        $name = 'Traza PDF' . $empresa;
//        $html=  utf8_decode($html);
//        $html="hola mundo";
//          echo $html;die();
//        echo $html;
//        
        $this->creapdf1($html, $name, $empresa, $doc);
    }

    function creapdf1($html, $name, $empresa, $doc, $print = true) {
        $this->load->library("tcpdf/tcpdf");

        ob_clean();
//        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "Servicio Nacional de Aprendizaje SENA", "Informe Traza " . " Elaborado el Dia " . date('d/m/y') . "                                                                                   " . "Nombre. " . $empresa . "   " . "Documento. " . $doc . "" . " ");
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->setPrintHeader(false);
//        $pdf->setPrintFooter(false);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        //$pdf->SetHeaderMargin(o);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        if ($print) {
            $js = '
                print();
                ';

            $pdf->IncludeJS($js);
        }
//        ob_end_clean();

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

    function consultatrazadevolucion() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazadevolucion')) {
                $empresa = $this->input->post('CODEMPRESA');
                $this->data['datos'] = $this->consultaprocesos_model->gettrazadevolucion();
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazadevolucion", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function consultatrazanomisional() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazanomisional')) {
                $empresa = $this->input->post('CODEMPRESA');
                $this->data['datos'] = $this->consultaprocesos_model->gettrazanomisional();
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazanomisional", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function consultatrazajudicial() {
        if ($this->ion_auth->logged_in()) {
            if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('consultarprocesos/consultatrazajudicial')) {
                $empresa = $this->input->post('CODEMPRESA');
                $this->data['datos'] = $this->consultaprocesos_model->gettrazajudicial();
                $this->data['procesos'] = $this->consultaprocesos_model->getprocesos();
                $this->data['ciudades'] = $this->consultaprocesos_model->getciudades();
                $this->data['regionales'] = $this->consultaprocesos_model->getregionales();
                $this->template->load($this->template_file, "consultarprocesos/consultatrazajudicial", $this->data);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>No tiene permisos para acceder a esta área.</div>');
                redirect(base_url() . 'index.php/inicio');
            }
        } else {
            redirect(base_url() . 'index.php/auth/login');
        }
    }

    function pdfdevolucion($print = true) {
        $id = $this->input->post('cod_devolucion');
        $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
            <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';

        $this->data['devoluciones'] = $this->consultaprocesos_model->verprocesodevolucion($id);
        $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesodevolucion($id);
        foreach ($this->data['empresa'] as $col) {
            $empresa = $col["NOMBRE_PROPIETARIO"];
            $doc = $col["IDENTIFICACION"];
        }
        $devolucionrnm = $this->consultaprocesos_model->devolucionrnm();
        $devoluciondg = $this->consultaprocesos_model->devoluciondg();
        $devolucionrm = $this->consultaprocesos_model->devolucionrm();
        //  $alumnos = $this->consultaprocesos_model->getprocesosdaf();

        $devrnmc = array();
        $devrnm = $devoluciornm = array();
        foreach ($devolucionrnm as $drnm) {
            $i = 0;
            if (!in_array($drnm['COD_TIPO_INSTANCIA'], $devrnm)) {
                $devrnmc[] = $drnm['NOMBRE_TIPO_INSTANCIA'];
                $devrnm[] = $drnm['COD_TIPO_INSTANCIA'];
            }
        }

        $devdgc = array();
        $devdg = $devoluciodg = array();
        foreach ($devoluciondg as $ddg) {
            $i = 0;
            if (!in_array($ddg['COD_TIPO_INSTANCIA'], $devdg)) {
                $devdgc[] = $ddg['NOMBRE_TIPO_INSTANCIA'];
                $devdg[] = $ddg['COD_TIPO_INSTANCIA'];
            }
        }

        $devrmc = array();
        $devrm = $devoluciorm = array();
        foreach ($devolucionrm as $drm) {
            $i = 0;
            if (!in_array($drm['COD_TIPO_INSTANCIA'], $devrm)) {
                $devrmc[] = $drm['NOMBRE_TIPO_INSTANCIA'];
                $devrm[] = $drm['COD_TIPO_INSTANCIA'];
            }
        }

        $html = '';
        $html2 = '';


        $columna = '';

        foreach ($this->data['devoluciones'] as $colu) {


            $html .= '<tr>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["FECHA"] . '</td>';
            switch ($colu['COD_TIPO_PROCESO']) {
                case 24:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($devrnmc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($devrnmc as $drnm) {
                        $html.='- ' . $drnm . $salto;
                    }
                    $html.='</td>';
                    break;
                case 25:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($devdgc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($devdgc as $ddg) {
                        $html.='- ' . $ddg . $salto;
                    }
                    $html.='</td>';
                    break;
                case 26:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($devrmc) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($devrmc as $drm) {
                        $html.='- ' . $drm . $salto;
                    }
                    $html.='</td>';
                    break;
                default:
                    $html.='<td></td>';
                    break;
            }


            $html.='<td align="center" style="vertical-align:middle">' . $colu["TIPO_PROCESO"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["TIPOGESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_GESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["COMENTARIOS"] . '</td>';
            $html.= '</tr>';
        }

        $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>'
                . '<table border="1">' . $encabezado . $html . '</table></html>';
        $name = 'ejemplos';
//        $html=  utf8_decode($html);
//        $html="hola mundo";
//          echo $html;die();
//        
        $this->load->library("tcpdf/tcpdf");

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "Servicio Nacional de Aprendizaje SENA", "Informe Traza " . " Elaborado el Dia " . date('d/m/y') . "                                                                                   " . "Nombre. " . $empresa . "   " . "Documento. " . $doc . "" . " ");
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->SetHeaderMargin(o);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        if ($print) {
            $js = '
                print();
                ';

            $pdf->IncludeJS($js);
        }

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

    function pdfjudicial($print = true) {
        $id = $this->input->post('cod_judicial');
        $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
            <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';

        $this->data['devoluciones'] = $this->consultaprocesos_model->verprocesojudicial($id);
        $judicial = $this->consultaprocesos_model->projudiciales();
        $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesojudicial($id);
        foreach ($this->data['empresa'] as $col) {
            $empresa = $col["NOMBRE_PROPIETARIO"];
            $doc = $col["IDENTIFICACION"];
        }

        $judicic = array();
        $judil = $projudil = array();
        foreach ($judicial as $jud) {
            $i = 0;
            if (!in_array($jud['COD_TIPO_INSTANCIA'], $judil)) {
                $judicic[] = $jud['NOMBRE_TIPO_INSTANCIA'];
                $judil[] = $jud['COD_TIPO_INSTANCIA'];
            }
        }

        $html = '';
        $html2 = '';


        $columna = '';

        foreach ($this->data['devoluciones'] as $colu) {


            $html .= '<tr>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["FECHA"] . '</td>';
            switch ($colu['COD_TIPO_PROCESO']) {
                case 13:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($judicic) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($judicic as $jud) {
                        $html.='- ' . $jud . $salto;
                    }
                    $html.='</td>';
                    break;
                default:
                    $html.='<td></td>';
                    break;
            }


            $html.='<td align="center" style="vertical-align:middle">' . $colu["TIPO_PROCESO"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["TIPOGESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_GESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu['APELLIDOS'] . ' ' . $colu['NOMBRES'] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["COMENTARIOS"] . '</td>';
            $html.= '</tr>';
        }

        $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>'
                . '<table border="1">' . $encabezado . $html . '</table></html>';
        $name = 'ejemplos';
//        $html=  utf8_decode($html);
//        $html="hola mundo";
//          echo $html;die();
//        
        $this->load->library("tcpdf/tcpdf");

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "Servicio Nacional de Aprendizaje SENA", "Informe Traza " . " Elaborado el Dia " . date('d/m/y') . "                                                                                   " . "Nombre. " . $empresa . "   " . "Documento. " . $doc . "" . " ");
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->SetHeaderMargin(o);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        if ($print) {
            $js = '
                print();
                ';

            $pdf->IncludeJS($js);
        }

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

    function pdfnomisional($print = true) {
        $id = $this->input->post('cod_fiscalizacion');
        // echo $id;die();
        $encabezado = '<tr style="background-color:#FC7323">
            <td align="center" style="font-weight:bold;color:white">Fecha Creaci&oacute;n</td>
        <th align="center" style="font-weight:bold;color:white">Nombre Instancia</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Proceso</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Cartera</th>
            <th align="center" style="font-weight:bold;color:white">Nombre Actividad</th>
            <th align="center" style="font-weight:bold;color:white">Actividad Actual</th>
            <th align="center" style="font-weight:bold;color:white">Funcionario</th>
            <th align="center" style="font-weight:bold;color:white">Comentario</th>
        </tr>';

        $this->data['procesonomisional'] = $this->consultaprocesos_model->verprocesonomisional($id);

        $this->data['empresa'] = $this->consultaprocesos_model->verempresaprocesonomisional($id);
        foreach ($this->data['empresa'] as $col) {
            $empresa = $col["NOMBRE_PROPIETARIO"];
            $doc = $col["IDENTIFICACION"];
        }
        $nomisional = $this->consultaprocesos_model->nomisional();

        //  $alumnos = $this->consultaprocesos_model->getprocesosdaf();

        $nomic = array();
        $nomiso = $nomision = array();
        foreach ($nomisional as $pnm) {
            $i = 0;
            if (!in_array($pnm['COD_TIPO_INSTANCIA'], $nomiso)) {
                $nomic[] = $pnm['NOMBRE_TIPO_INSTANCIA'];
                $nomiso[] = $pnm['COD_TIPO_INSTANCIA'];
            }
        }

        $html = '';
        $html2 = '';


        $columna = '';
        foreach ($this->data['procesonomisional'] as $colu) {
//        print_r($procesonomisional);die();


            $html .= '<tr>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["FECHA"] . '</td>';
            switch ($colu['COD_TIPO_PROCESO']) {
                case 27:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nomisional) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nomic as $pnm) {
                        $html.='- ' . $pnm . $salto;
                    }
                    $html.='</td>';
                    break;
                case 23:
                    $html.='<td align="center" style="vertical-align:middle">';
//                     echo count($fiscalic);
//                     print_r($fiscalic);
//                     die;
                    if (count($nomisional) > 1) {
                        $salto = "<br>";
                    } else {
                        $salto = "";
                    }
                    foreach ($nomic as $pnm) {
                        $html.='- ' . $pnm . $salto;
                    }
                    $html.='</td>';
                    break;
                case 10:

                default:
                    $html.='<td></td>';
                    break;
            }


            $html.='<td align="center" style="vertical-align:middle">' . $colu["TIPO_PROCESO"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_CARTERA"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["TIPOGESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu["NOMBRE_GESTION"] . '</td>'
                    . '<td align="center" style="vertical-align:middle">' . $colu['NOMEMPRESA'] . '</td>';
            if (base64_encode(base64_decode($colu['COMENTARIOS'])) === $colu['COMENTARIOS']) {
//                        echo 'Esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . base64_decode($colu['COMENTARIOS']) . '</td>';
            } else {
//                        echo 'No esta Codificado';
                $html.= '<td align="center" style="vertical-align:middle">' . $colu['COMENTARIOS'] . '</td>';
            }
            $html.= '</tr>';

            $empresa = $colu["NOMBRE_PROPIETARIO"];
            $doc = $colu["IDENTIFICACION"];
        }

        $html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8"></head>'
                . '<table border="1">' . $encabezado . $html . '</table></html>';
        $name = 'ejemplos';
//        $html=  utf8_decode($html);
//        $html="hola mundo";
//          echo $html;die();
//        
        $this->load->library("tcpdf/tcpdf");

        ob_clean();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetHeaderData("Logotipo.png", PDF_HEADER_LOGO_WIDTH, "Servicio Nacional de Aprendizaje SENA", "Informe Traza " . " Elaborado el Dia " . date('d/m/y') . "                                                                                   " . "Nombre. " . $empresa . "   " . "Documento. " . $doc . "" . " ");
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->SetHeaderMargin(o);
        //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //$pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 8, '', true);
        if ($print) {
            $js = '
                print();
                ';

            $pdf->IncludeJS($js);
        }

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output($name, 'I');
    }

}

?>